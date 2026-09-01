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
                $msg = "Domain terdeteksi menggunakan Cloudflare Proxy (" . implode(', ', $resolvedIps) . "), namun belum meneruskan traffic ke aplikasi di server ini. Pastikan di Cloudflare: A Record diarahkan ke IP {$serverIp}, dan menu SSL/TLS disetel ke mode 'Full'.";
            } else {
                $msg = "DNS domain belum mengarah ke server ini ({$serverIp}). Saat ini masih mengarah ke: " . (implode(', ', $resolvedIps) ?: 'Tidak ditemukan / DNS belum disetel') . ". Silakan arahkan A Record ke {$serverIp} di DNS / Cloudflare Anda.";
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
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 4,
                        'follow_location' => 1,
                        'max_redirects' => 3,
                        'ignore_errors' => true,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ]);
                $content = @file_get_contents($url, false, $context);
                if ($content) {
                    $json = @json_decode($content, true);
                    if (isset($json['status']) && $json['status'] === 'ok' && isset($json['app']) && $json['app'] === 'newlink') {
                        return true;
                    }
                }
            } catch (\Exception $e) {
                // continue to next scheme
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
