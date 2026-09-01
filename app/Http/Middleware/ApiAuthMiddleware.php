<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\ApiLog;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // 1. Extract API Key
        $apiKey = $request->bearerToken();
        if (!$apiKey) {
            $apiKey = $request->header('X-API-KEY');
        }
        if (!$apiKey) {
            $apiKey = $request->get('api_key');
        }

        // 2. Validate API Key
        $user = null;
        if ($apiKey) {
            $user = User::where('api_key', $apiKey)->where('api_key', '!=', '')->first();
        }

        // 3. Reject if invalid or disabled
        if (!$user || !$user->status) {
            $latency = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log 401 attempt
            ApiLog::create([
                'user_id' => $user ? $user->id : null,
                'api_key' => $apiKey ? substr($apiKey, 0, 8) . '...' : null,
                'endpoint' => $request->path(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status_code' => 401,
                'response_time_ms' => $latency,
                'request_payload' => json_encode($request->except(['password', 'api_key'])),
                'response_summary' => 'Unauthorized / Invalid API Key',
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or missing API key. Please provide your API key using the Authorization Bearer header: "Authorization: Bearer <your_api_key>"',
                'error_code' => 401
            ], 401);
        }

        // 4. Attach user to request
        $request->setUserResolver(fn () => $user);
        $request->attributes->set('auth_user', $user);

        // 5. Proceed with request
        $response = $next($request);

        // 6. Log API Call
        $latency = round((microtime(true) - $startTime) * 1000, 2);
        try {
            ApiLog::create([
                'user_id' => $user->id,
                'api_key' => substr($apiKey, 0, 8) . '...',
                'endpoint' => $request->path(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status_code' => $response->getStatusCode(),
                'response_time_ms' => $latency,
                'request_payload' => in_array($request->method(), ['POST', 'PUT', 'PATCH']) ? json_encode($request->except(['password', 'api_key'])) : null,
                'response_summary' => substr($response->getContent(), 0, 200),
            ]);
        } catch (\Exception $e) {
            // Silently ignore log errors to avoid blocking API response
        }

        return $response;
    }
}
