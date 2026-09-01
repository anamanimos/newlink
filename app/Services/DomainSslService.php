<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DomainSslService
{
    /**
     * Get the public IP address of this server
     */
    public static function getServerIp(): string
    {
        if ($envIp = env('SERVER_PUBLIC_IP')) {
            return $envIp;
        }

        return cache()->remember('server_public_ip_v2', 86400, function () {
            // Check reliable public IP check APIs
            $services = [
                'https://api.ipify.org',
                'https://icanhazip.com',
                'https://ifconfig.me/ip',
            ];

            foreach ($services as $url) {
                try {
                    $context = stream_context_create([
                        'http' => ['timeout' => 2]
                    ]);
                    $ip = @file_get_contents($url, false, $context);
                    if ($ip) {
                        $ip = trim($ip);
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                            return $ip;
                        }
                    }
                } catch (\Exception $e) {
                    // continue
                }
            }

            // If server address is already a valid public IP
            if (isset($_SERVER['SERVER_ADDR']) && filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $_SERVER['SERVER_ADDR'];
            }

            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
            if ($appHost && $appHost !== 'localhost' && !str_ends_with($appHost, '.test')) {
                $resolved = @gethostbyname($appHost);
                if ($resolved && $resolved !== $appHost && filter_var($resolved, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $resolved;
                }
            }

            return $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
        });
    }

    /**
     * Resolve fresh DNS A records using authoritative DoH APIs to bypass local DNS cache
     */
    public static function resolveDnsIps(string $host): array
    {
        $ips = [];

        // 1. Query Google DNS-over-HTTPS (DoH) for live authoritative record
        try {
            $res = \Illuminate\Support\Facades\Http::timeout(3)->get("https://dns.google/resolve?name=" . urlencode($host) . "&type=A");
            if ($res->successful()) {
                $json = $res->json();
                if (!empty($json['Answer'])) {
                    foreach ($json['Answer'] as $ans) {
                        if (isset($ans['type']) && $ans['type'] == 1 && isset($ans['data']) && filter_var($ans['data'], FILTER_VALIDATE_IP)) {
                            $ips[] = $ans['data'];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // fallback
        }

        // 2. Query Cloudflare DoH API if Google DNS returned empty
        if (empty($ips)) {
            try {
                $res = \Illuminate\Support\Facades\Http::timeout(3)
                    ->withHeaders(['Accept' => 'application/dns-json'])
                    ->get("https://cloudflare-dns.com/dns-query?name=" . urlencode($host) . "&type=A");
                if ($res->successful()) {
                    $json = $res->json();
                    if (!empty($json['Answer'])) {
                        foreach ($json['Answer'] as $ans) {
                            if (isset($ans['type']) && $ans['type'] == 1 && isset($ans['data']) && filter_var($ans['data'], FILTER_VALIDATE_IP)) {
                                $ips[] = $ans['data'];
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // fallback
            }
        }

        // 3. Fallback to PHP native DNS resolution
        if (empty($ips)) {
            $records = @dns_get_record($host, DNS_A);
            if ($records) {
                foreach ($records as $r) {
                    if (isset($r['ip'])) {
                        $ips[] = $r['ip'];
                    }
                }
            }
        }

        if (empty($ips)) {
            $singleIp = @gethostbyname($host);
            if ($singleIp && $singleIp !== $host && filter_var($singleIp, FILTER_VALIDATE_IP)) {
                $ips[] = $singleIp;
            }
        }

        return array_values(array_unique($ips));
    }

    /**
     * Check if a domain's DNS is pointed to this server
     */
    public static function verifyDns(string $host): array
    {
        $host = strtolower(trim(preg_replace('#^https?://#', '', $host), '/'));
        $serverIp = self::getServerIp();

        // If local dev environment, allow simulation
        if ($serverIp === '127.0.0.1' || str_ends_with($host, '.test') || $host === 'localhost') {
            return [
                'verified' => true,
                'server_ip' => $serverIp,
                'resolved_ips' => ['127.0.0.1'],
                'message' => 'Mode pengembangan lokal (Simulated DNS OK).'
            ];
        }

        $resolvedIps = self::resolveDnsIps($host);

        $isMatch = in_array($serverIp, $resolvedIps);
        $isCloudflare = false;

        // Check if any resolved IP belongs to Cloudflare
        foreach ($resolvedIps as $ip) {
            if (self::isCloudflareIp($ip)) {
                $isCloudflare = true;
                break;
            }
        }

        // If not directly matched by IP, test if traffic routes to this application (e.g. via Cloudflare Proxy / CDN)
        if (!$isMatch) {
            $pingSuccess = self::testAppPing($host);
            if ($pingSuccess) {
                $isMatch = true;
            }
        }

        if ($isMatch) {
            $msg = $isCloudflare 
                ? "DNS domain terverifikasi aktif mengarah ke server ini via Cloudflare Proxy (Proxy & SSL Cloudflare Aktif)." 
                : "DNS A Record domain telah terverifikasi mengarah langsung ke server ini ({$serverIp}).";
        } else {
            if ($isCloudflare) {
                $msg = "<div class='text-start fs-7'>"
                     . "<p class='mb-2'>Domain terdeteksi menggunakan <strong>Cloudflare Proxy</strong> (<code>" . implode(', ', $resolvedIps) . "</code>), namun server belum dapat memverifikasi respons aplikasi dari domain ini.</p>"
                     . "<div class='p-3 bg-light-warning rounded-3 border border-warning my-3'>"
                     . "<div class='fw-bolder text-gray-900 mb-1'><i class='ki-outline ki-time fs-6 text-warning me-1'></i> Waktu Tunggu Propagasi:</div>"
                     . "<div class='text-gray-700 fs-8 mb-2'>Perubahan DNS di Cloudflare membutuhkan waktu propagasi <strong>2 hingga 15 menit</strong>. Harap tunggu beberapa menit sebelum klik <em>Cek DNS</em> kembali.</div>"
                     . "<div class='separator separator-dashed my-2'></div>"
                     . "<div class='fw-bolder text-gray-900 mb-1'>Panduan Pengaturan di Cloudflare:</div>"
                     . "<ol class='ps-4 mb-0 text-gray-700 fs-8'>"
                     . "<li>Pastikan <strong>A Record</strong> diarahkan ke IP <code>{$serverIp}</code>.</li>"
                     . "<li>Buka menu <strong>SSL/TLS</strong> di Cloudflare & ubah mode enkripsi ke <strong>Full</strong> atau <strong>Full (Strict)</strong>.</li>"
                     . "<li><em>(Solusi Instan)</em> Ubah status Proxy menjadi <strong>DNS Only (Awan Abu-abu)</strong> agar verifikasi langsung terhubung ke server tanpa filter proxy.</li>"
                     . "</ol>"
                     . "</div>"
                     . "</div>";
            } else {
                $msg = "<div class='text-start fs-7'>"
                     . "<p class='mb-2'>DNS domain belum mengarah ke server ini (<code>{$serverIp}</code>). Saat ini masih mengarah ke: <code>" . (implode(', ', $resolvedIps) ?: 'Belum terdeteksi') . "</code>.</p>"
                     . "<div class='p-3 bg-light-info rounded-3 border border-info my-3'>"
                     . "<div class='fw-bolder text-gray-900 mb-1'><i class='ki-outline ki-time fs-6 text-info me-1'></i> Informasi Waktu Propagasi:</div>"
                     . "<div class='text-gray-700 fs-8'>Jika Anda baru saja memperbarui DNS/A Record, proses propagasi global umumnya membutuhkan waktu <strong>5 hingga 30 menit</strong>. Silakan tunggu beberapa menit dan lakukan cek ulang.</div>"
                     . "</div>"
                     . "</div>";
            }
        }

        return [
            'verified' => $isMatch,
            'is_cloudflare' => $isCloudflare,
            'server_ip' => $serverIp,
            'resolved_ips' => $resolvedIps,
            'message' => $msg
        ];
    }

    /**
     * Test if a domain routes to this application by calling the internal ping endpoint
     */
    private static function testAppPing(string $host): bool
    {
        $schemes = ['https://', 'http://'];
        foreach ($schemes as $scheme) {
            try {
                $url = "{$scheme}{$host}/_system/domain-ping";
                $response = \Illuminate\Support\Facades\Http::timeout(6)
                    ->withoutVerifying()
                    ->withOptions(['verify' => false, 'allow_redirects' => true])
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Accept' => 'application/json, text/plain, */*'
                    ])
                    ->get($url);

                \Illuminate\Support\Facades\Log::info("Domain ping check for {$url}", [
                    'status' => $response->status(),
                    'body' => substr($response->body(), 0, 200)
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    if (isset($json['status']) && $json['status'] === 'ok' && isset($json['app']) && $json['app'] === 'newlink') {
                        return true;
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("Domain ping exception for {$scheme}{$host}: " . $e->getMessage());
            }
        }
        return false;
    }

    /**
     * Check if an IP address belongs to Cloudflare Proxy network
     */
    public static function isCloudflareIp(string $ip): bool
    {
        $cfPrefixes = [
            '104.16.', '104.17.', '104.18.', '104.19.', '104.20.', '104.21.', '104.22.', '104.23.', '104.24.', '104.25.', '104.26.', '104.27.', '104.28.', '104.29.', '104.30.', '104.31.',
            '172.64.', '172.65.', '172.66.', '172.67.', '172.68.', '172.69.', '172.70.', '172.71.',
            '108.162.', '190.93.', '188.114.', '197.234.', '198.41.', '162.158.', '173.245.'
        ];

        foreach ($cfPrefixes as $prefix) {
            if (str_starts_with($ip, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Provision SSL using Certbot
     */
    public static function provisionSsl(string $host): array
    {
        $host = strtolower(trim(preg_replace('#^https?://#', '', $host), '/'));

        // Validate hostname pattern
        if (!preg_match('/^[a-z0-9.-]+\.[a-z]{2,}$/i', $host)) {
            return [
                'success' => false,
                'message' => 'Format nama domain tidak valid.'
            ];
        }

        // Verify DNS first
        $dnsCheck = self::verifyDns($host);
        if (!$dnsCheck['verified']) {
            return [
                'success' => false,
                'message' => 'Gagal menerbitkan SSL: ' . $dnsCheck['message']
            ];
        }

        // On Windows or local dev, simulate success
        if (PHP_OS_FAMILY === 'Windows' || self::getServerIp() === '127.0.0.1') {
            return [
                'success' => true,
                'message' => 'SSL berhasil diaktifkan (Simulasi Lingkungan Lokal).'
            ];
        }

        // Production Linux VPS: Run Certbot
        $adminEmail = env('MAIL_FROM_ADDRESS', 'admin@damaijaya.my.id');
        $command = "sudo certbot --nginx -d " . escapeshellarg($host) . " --non-interactive --agree-tos -m " . escapeshellarg($adminEmail) . " --redirect 2>&1";

        $output = [];
        $returnVar = 0;
        exec($command, $output, $returnVar);
        $outputStr = implode("\n", $output);

        Log::info("Certbot execution for {$host}", [
            'command' => $command,
            'exit_code' => $returnVar,
            'output' => $outputStr
        ]);

        if ($returnVar === 0) {
            // Reload Nginx to ensure clean state
            exec("sudo systemctl reload nginx 2>&1");

            return [
                'success' => true,
                'message' => "Sertifikat SSL Let's Encrypt berhasil diterbitkan dan diaktifkan untuk {$host}!",
                'output' => $outputStr
            ];
        }

        return [
            'success' => false,
            'message' => "Gagal menerbitkan SSL untuk {$host}. Pastikan port 80/443 tidak terblokir firewall.",
            'output' => $outputStr
        ];
    }

    /**
     * Check if SSL is actively provisioned for a domain
     */
    public static function isSslActive($domain): bool
    {
        if (is_object($domain)) {
            if ($domain->ssl_status === 'active') {
                return true;
            }
            $host = $domain->host;
        } else {
            $host = (string) $domain;
        }

        $host = strtolower(trim(preg_replace('#^https?://#', '', $host), '/'));
        
        // Check if certificate file exists on server
        if (file_exists("/etc/letsencrypt/live/{$host}/fullchain.pem")) {
            return true;
        }

        return false;
    }
}
