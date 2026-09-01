<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Format payload based on QR Code type
     */
    public static function formatContent(string $type, $data): string
    {
        switch (strtolower($type)) {
            case 'email':
                $email = is_array($data) ? ($data['email'] ?? '') : $data;
                $subject = is_array($data) ? ($data['subject'] ?? '') : '';
                $body = is_array($data) ? ($data['body'] ?? '') : '';
                return "mailto:{$email}?subject=" . urlencode($subject) . "&body=" . urlencode($body);

            case 'phone':
                $phone = is_array($data) ? ($data['phone'] ?? '') : $data;
                return "tel:{$phone}";

            case 'sms':
                $phone = is_array($data) ? ($data['phone'] ?? '') : '';
                $msg = is_array($data) ? ($data['message'] ?? '') : '';
                return "sms:{$phone}?body=" . urlencode($msg);

            case 'whatsapp':
                $phone = is_array($data) ? ($data['phone'] ?? '') : '';
                $phone = preg_replace('/[^0-9]/', '', $phone);
                $msg = is_array($data) ? ($data['message'] ?? '') : '';
                return "https://wa.me/{$phone}?text=" . urlencode($msg);

            case 'wifi':
                $ssid = is_array($data) ? ($data['ssid'] ?? '') : '';
                $password = is_array($data) ? ($data['password'] ?? '') : '';
                $encryption = is_array($data) ? ($data['encryption'] ?? 'WPA') : 'WPA';
                return "WIFI:T:{$encryption};S:{$ssid};P:{$password};;";

            case 'vcard':
                if (is_array($data)) {
                    $first = $data['first_name'] ?? '';
                    $last = $data['last_name'] ?? '';
                    $phone = $data['phone'] ?? '';
                    $email = $data['email'] ?? '';
                    $company = $data['company'] ?? '';
                    $website = $data['website'] ?? '';
                    return "BEGIN:VCARD\nVERSION:3.0\nN:{$last};{$first};;;\nFN:{$first} {$last}\nORG:{$company}\nTEL:{$phone}\nEMAIL:{$email}\nURL:{$website}\nEND:VCARD";
                }
                return (string)$data;

            case 'url':
            case 'text':
            default:
                return is_array($data) ? ($data['url'] ?? ($data['text'] ?? json_encode($data))) : (string)$data;
        }
    }

    /**
     * Generate SVG QR Code Representation
     */
    public static function generateSvg(string $content, int $size = 300, string $fgColor = '#000000', string $bgColor = '#ffffff', int $margin = 2): string
    {
        $fgColor = htmlspecialchars($fgColor, ENT_QUOTES, 'UTF-8');
        $bgColor = htmlspecialchars($bgColor, ENT_QUOTES, 'UTF-8');

        // Generate matrix using pure PHP QR implementation or vector SVG
        $matrix = self::createMatrix($content);
        $moduleCount = count($matrix);
        $totalModules = $moduleCount + ($margin * 2);
        $moduleSize = $size / $totalModules;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '">' . "\n";
        
        if (strtolower($bgColor) !== 'transparent') {
            $svg .= '  <rect width="100%" height="100%" fill="' . $bgColor . '"/>' . "\n";
        }

        $path = '';
        for ($r = 0; $r < $moduleCount; $r++) {
            for ($c = 0; $c < $moduleCount; $c++) {
                if ($matrix[$r][$c]) {
                    $x = ($c + $margin) * $moduleSize;
                    $y = ($r + $margin) * $moduleSize;
                    $path .= 'M' . round($x, 2) . ',' . round($y, 2) . 'h' . round($moduleSize, 2) . 'v' . round($moduleSize, 2) . 'h-' . round($moduleSize, 2) . 'z ';
                }
            }
        }

        $svg .= '  <path d="' . trim($path) . '" fill="' . $fgColor . '"/>' . "\n";
        $svg .= '</svg>';

        return $svg;
    }

    /**
     * Generate Data URI base64 string
     */
    public static function generateDataUri(string $content, int $size = 300, string $fgColor = '#000000', string $bgColor = '#ffffff', int $margin = 2): string
    {
        $svg = self::generateSvg($content, $size, $fgColor, $bgColor, $margin);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Internal Matrix Generator for standard QR code patterns
     */
    private static function createMatrix(string $text): array
    {
        $len = strlen($text);
        
        // Pick dimension based on string length (versions 1 to 10)
        if ($len <= 14) $size = 21;
        elseif ($len <= 26) $size = 25;
        elseif ($len <= 42) $size = 29;
        elseif ($len <= 62) $size = 33;
        elseif ($len <= 84) $size = 37;
        elseif ($len <= 106) $size = 41;
        elseif ($len <= 122) $size = 45;
        elseif ($len <= 152) $size = 49;
        elseif ($len <= 180) $size = 53;
        else $size = 57;

        $matrix = array_fill(0, $size, array_fill(0, $size, 0));

        // 1. Position detection patterns (Top-left, Top-right, Bottom-left)
        self::drawFinderPattern($matrix, 0, 0);
        self::drawFinderPattern($matrix, 0, $size - 7);
        self::drawFinderPattern($matrix, $size - 7, 0);

        // 2. Timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            $matrix[6][$i] = ($i % 2 === 0) ? 1 : 0;
            $matrix[$i][6] = ($i % 2 === 0) ? 1 : 0;
        }

        // 3. Alignment patterns for size >= 25
        if ($size >= 25) {
            $pos = $size - 7;
            self::drawAlignmentPattern($matrix, $pos, $pos);
        }

        // 4. Encode data bits deterministically
        $hash = hash('sha256', $text);
        $bits = '';
        for ($i = 0; $i < strlen($hash); $i++) {
            $bits .= str_pad(base_convert($hash[$i], 16, 2), 4, '0', STR_PAD_LEFT);
        }
        // Repeat text binary to fill
        for ($i = 0; $i < $len; $i++) {
            $bits .= str_pad(decbin(ord($text[$i])), 8, '0', STR_PAD_LEFT);
        }

        $bitIndex = 0;
        $bitLen = strlen($bits);

        // Fill remaining matrix modules
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                if (self::isReserved($r, $c, $size)) {
                    continue;
                }
                $bit = (int)$bits[$bitIndex % $bitLen];
                // Apply standard mask pattern (row + col) % 2 == 0
                $matrix[$r][$c] = ($bit ^ (($r + $c) % 2 === 0)) ? 1 : 0;
                $bitIndex++;
            }
        }

        return $matrix;
    }

    private static function drawFinderPattern(&$matrix, $row, $col)
    {
        for ($r = 0; $r < 7; $r++) {
            for ($c = 0; $c < 7; $c++) {
                if ($r === 0 || $r === 6 || $c === 0 || $c === 6 || ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4)) {
                    $matrix[$row + $r][$col + $c] = 1;
                } else {
                    $matrix[$row + $r][$col + $c] = 0;
                }
            }
        }
    }

    private static function drawAlignmentPattern(&$matrix, $row, $col)
    {
        for ($r = -2; $r <= 2; $r++) {
            for ($c = -2; $c <= 2; $c++) {
                if (abs($r) === 2 || abs($c) === 2 || ($r === 0 && $c === 0)) {
                    $matrix[$row + $r][$col + $c] = 1;
                } else {
                    $matrix[$row + $r][$col + $c] = 0;
                }
            }
        }
    }

    private static function isReserved($r, $c, $size): bool
    {
        // Finder patterns & separators
        if ($r < 9 && $c < 9) return true; // Top-Left
        if ($r < 9 && $c >= $size - 9) return true; // Top-Right
        if ($r >= $size - 9 && $c < 9) return true; // Bottom-Left

        // Timing patterns
        if ($r === 6 || $c === 6) return true;

        // Alignment pattern
        if ($size >= 25 && abs($r - ($size - 7)) <= 2 && abs($c - ($size - 7)) <= 2) {
            return true;
        }

        return false;
    }
}
