<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    /**
     * Get the active SSO configuration (Database Settings override .env)
     */
    public static function getConfig(): array
    {
        $db = Setting::get('sso', []);
        
        $enabled = isset($db['is_enabled']) ? (bool)$db['is_enabled'] : (isset($db['sso_is_enabled']) ? (bool)$db['sso_is_enabled'] : config('services.sso.enabled', true));
        $baseUrl = !empty($db['base_url']) ? rtrim($db['base_url'], '/') : config('services.sso.base_url', 'https://app.damaijaya.my.id');
        $clientId = !empty($db['client_id']) ? trim($db['client_id']) : (!empty($db['sso_api']) ? trim($db['sso_api']) : config('services.sso.client_id', ''));
        $clientSecret = !empty($db['client_secret']) ? trim($db['client_secret']) : (!empty($db['sso_secret']) ? trim($db['sso_secret']) : config('services.sso.client_secret', ''));
        $redirectUri = !empty($db['redirect_uri']) ? trim($db['redirect_uri']) : (config('services.sso.redirect_uri') ?: route('sso.callback'));
        $buttonText = !empty($db['button_text']) ? $db['button_text'] : config('services.sso.button_text', 'Masuk dengan Akun Damai Jaya (SSO)');

        return [
            'enabled' => $enabled,
            'base_url' => $baseUrl,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'button_text' => $buttonText,
        ];
    }

    /**
     * Redirect user to OIDC provider authorization endpoint
     */
    public function redirect(Request $request)
    {
        $config = self::getConfig();

        if (!$config['enabled']) {
            return redirect()->route('login')->with('error', 'Login via SSO saat ini sedang dinonaktifkan.');
        }

        if (empty($config['client_id'])) {
            return redirect()->route('login')->with('error', 'SSO Client ID belum dikonfigurasi. Hubungi administrator.');
        }

        $state = Str::random(40);
        $codeVerifier = Str::random(64);
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

        session([
            'sso_state' => $state,
            'sso_code_verifier' => $codeVerifier,
        ]);

        $query = http_build_query([
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect_uri'],
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        $authorizeUrl = $config['base_url'] . '/oauth/authorize?' . $query;

        return redirect()->away($authorizeUrl);
    }

    /**
     * Handle the OIDC callback from provider
     */
    public function callback(Request $request)
    {
        $config = self::getConfig();

        if ($request->has('error')) {
            $err = $request->input('error_description') ?: $request->input('error');
            Log::warning('SSO Authorization Error: ' . $err);
            return redirect()->route('login')->with('error', 'Gagal login SSO: ' . $err);
        }

        // Verify state
        $savedState = session('sso_state');
        if (empty($savedState) || $savedState !== $request->input('state')) {
            return redirect()->route('login')->with('error', 'Validasi sesi SSO (state) tidak valid atau telah kedaluwarsa. Silakan coba kembali.');
        }

        $code = $request->input('code');
        if (empty($code)) {
            return redirect()->route('login')->with('error', 'Kode otorisasi SSO tidak ditemukan.');
        }

        $codeVerifier = session('sso_code_verifier');
        $tokenUrl = $config['base_url'] . '/oauth/token';

        try {
            $tokenResponse = Http::asForm()->timeout(15)->post($tokenUrl, [
                'grant_type' => 'authorization_code',
                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'redirect_uri' => $config['redirect_uri'],
                'code' => $code,
                'code_verifier' => $codeVerifier,
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('SSO Token Exchange Failed', [
                    'status' => $tokenResponse->status(),
                    'body' => $tokenResponse->body()
                ]);
                return redirect()->route('login')->with('error', 'Gagal menukar token otorisasi SSO. Pastikan Client ID dan Client Secret telah sesuai.');
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;
            $idToken = $tokenData['id_token'] ?? null;

            $userData = [];

            // 1. Try decoding ID Token JWT
            if ($idToken) {
                $jwtClaims = $this->decodeJwtPayload($idToken);
                if ($jwtClaims) {
                    $userData = $jwtClaims;
                }
            }

            // 2. Query /oauth/userinfo endpoint if access token available
            if ($accessToken) {
                try {
                    $userInfoRes = Http::withToken($accessToken)
                        ->timeout(10)
                        ->get($config['base_url'] . '/oauth/userinfo');

                    if ($userInfoRes->successful()) {
                        $userInfo = $userInfoRes->json();
                        $userData = array_merge($userData, $userInfo);
                    }
                } catch (\Exception $e) {
                    Log::warning('SSO userinfo fetch warning: ' . $e->getMessage());
                }
            }

            $emailClean = strtolower(trim($email));
            if (empty($emailClean)) {
                return redirect()->route('login')->with('error', 'Data pengguna dari provider SSO tidak memuat alamat email yang valid.');
            }

            if (empty($name)) {
                $name = explode('@', $emailClean)[0];
            }

            // 1. First priority: Find existing user by sso_id
            $user = null;
            if (!empty($ssoId)) {
                $user = User::where('sso_id', $ssoId)->first();
            }

            // 2. Second priority: Find existing user by matching email (connect existing account)
            if (!$user) {
                $user = User::whereRaw('LOWER(email) = ?', [$emailClean])->first();
            }

            if ($user) {
                // Connect existing account with SSO credentials
                $user->sso_provider = 'damaijaya';
                if (!empty($ssoId)) {
                    $user->sso_id = $ssoId;
                }
                if (empty($user->email_verified_at)) {
                    $user->email_verified_at = now();
                }
                $user->last_activity = now();
                $user->increment('total_logins');
                $user->save();

                Log::info("SSO Account Linked: User #{$user->id} ({$user->email}) connected via Damai Jaya SSO.");
            } else {
                // 3. Create new user only if email does not exist yet
                $user = User::create([
                    'name' => $name,
                    'email' => $emailClean,
                    'email_verified_at' => now(),
                    'password' => null,
                    'status' => 1,
                    'type' => 0, // regular user
                    'source' => 'sso',
                    'sso_provider' => 'damaijaya',
                    'sso_id' => $ssoId,
                    'plan_id' => 'free',
                    'plan_settings' => json_encode([
                        'links_limit' => -1,
                        'biolinks_limit' => -1,
                        'domains_limit' => -1,
                        'pixels_limit' => -1,
                        'projects_limit' => -1,
                    ]),
                    'total_logins' => 1,
                    'last_activity' => now(),
                ]);

                Log::info("SSO New User Created: User #{$user->id} ({$user->email}) registered via Damai Jaya SSO.");
            }

            if ($user->status == 0) {
                return redirect()->route('login')->with('error', 'Akun Anda saat ini dinonaktifkan oleh administrator.');
            }

            // Clear temporary SSO session
            session()->forget(['sso_state', 'sso_code_verifier']);

            // Authenticate user
            Auth::login($user, true);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))->with('success', "Selamat datang kembali, {$user->name}!");

        } catch (\Exception $e) {
            Log::error('SSO Authentication Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', 'Terjadi kesalahan sistem saat memproses login SSO: ' . $e->getMessage());
        }
    }

    /**
     * Decode the payload part of a JWT string
     */
    private function decodeJwtPayload(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) < 2) {
            return null;
        }

        $payload = $parts[1];
        // URL-safe base64 decoding
        $remainder = strlen($payload) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $payload .= str_repeat('=', $padlen);
        }

        $decoded = base64_decode(strtr($payload, '-_', '+/'));
        if (!$decoded) {
            return null;
        }

        return json_decode($decoded, true);
    }
}