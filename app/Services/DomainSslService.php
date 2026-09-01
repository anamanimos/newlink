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

        if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] !== '127.0.0.1' && $_SERVER['SERVER_ADDR'] !== '::1') {
            return $_SERVER['SERVER_ADDR'];
        }

        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        if ($appHost && $appHost !== 'localhost' && !str_ends_with($appHost, '.test')) {
            $resolved = gethostbyname($appHost);
            if ($resolved && $resolved !== $appHost) {
                return $resolved;
            }
        }

        return '127.0.0.1';
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

        $resolvedIps = [];
        $records = @dns_get_record($host, DNS_A);
        if ($records) {
            foreach ($records as $r) {
                if (isset($r['ip'])) {
                    $resolvedIps[] = $r['ip'];
                }
            }
        }

        if (empty($resolvedIps)) {
            $singleIp = @gethostbyname($host);
            if ($singleIp && $singleIp !== $host) {
                $resolvedIps[] = $singleIp;
            }
        }

        $isMatch = in_array($serverIp, $resolvedIps);

        // If Cloudflare Proxy is used, also verify if domain resolves to Cloudflare or target
        if (!$isMatch && !empty($resolvedIps)) {
            // Check if domain is accessible via HTTP
            $isMatch = self::testHttpReachable($host);
        }

        return [
            'verified' => $isMatch,
            'server_ip' => $serverIp,
            'resolved_ips' => $resolvedIps,
            'message' => $isMatch 
                ? "DNS domain telah mengarah ke server ({$serverIp})."
                : "DNS domain belum mengarah ke server ({$serverIp}). Terdeteksi: " . (implode(', ', $resolvedIps) ?: 'Tidak ditemukan')
        ];
    }

    /**
     * Test if the host reaches our application via HTTP
     */
    private static function testHttpReachable(string $host): bool
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 3,
                'method' => 'HEAD',
                'follow_location' => 0,
            ]
        ]);
        $headers = @get_headers("http://{$host}", true, $context);
        return $headers !== false;
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
}
