<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\Domain;

class RedirectController extends Controller
{
    public function resolve(Request $request, $slug)
    {
        $host = $request->getHost();
        $domainId = 0;

        // Check if custom domain
        if ($host !== parse_url(config('app.url'), PHP_URL_HOST)) {
            $domain = Domain::where('host', $host)->first();
            if ($domain) {
                $domainId = $domain->id;
            } else {
                abort(404);
            }
        }

        // Find the link
        $link = Link::where('url', $slug)
                    ->where('domain_id', $domainId)
                    ->where('is_enabled', 1)
                    ->firstOrFail();

        $userAgent = $request->header('User-Agent');

        // Check if it's a known bot/crawler
        $isBot = preg_match('/bot|crawl|slurp|spider|mediapartners|facebookexternalhit|whatsapp|telegrambot|twitterbot|linkedinbot/i', $userAgent);

        if (!$isBot) {
            // Increment clicks only for real users
            $link->increment('clicks');
            
            // Fetch country and city from IP
            $countryCode = null;
            $cityName = null;
            $ip = $request->ip();
            if ($ip !== '127.0.0.1' && $ip !== '::1') {
                try {
                    $geo = json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode,city"));
                    if ($geo) {
                        if (isset($geo->countryCode)) {
                            $countryCode = $geo->countryCode;
                        }
                        if (isset($geo->city)) {
                            $cityName = $geo->city;
                        }
                    }
                } catch (\Exception $e) {
                    // Silently ignore geo-location failures
                }
            }

            // Detailed tracking
            \App\Models\TrackLink::create([
                'link_id' => $link->id,
                'user_id' => $link->user_id,
                'ip' => $ip,
                'country_code' => $countryCode,
                'city_name' => $cityName,
                'os' => $this->getOS($userAgent),
                'browser' => $this->getBrowser($userAgent),
                'device_type' => $this->getDevice($userAgent),
                'referrer_host' => $this->getReferrer($request),
            ]);
        }

        if ($link->type === 'link') {
            return redirect()->away($link->location_url);
        } elseif ($link->type === 'biolink') {
            $blocks = $link->biolinkBlocks()->where('is_enabled', 1)->orderBy('order')->get();
            return view('biolinks.public', compact('link', 'blocks'));
        } elseif ($link->type === 'warotator') {
            return view('warotators.public', compact('link'));
        }

        abort(404);
    }

    public function redirectBlock(Request $request, $id)
    {
        $block = \App\Models\BiolinkBlock::findOrFail($id);
        
        $userAgent = $request->header('User-Agent');
        $isBot = preg_match('/bot|crawl|slurp|spider|mediapartners|facebookexternalhit|whatsapp|telegrambot|twitterbot|linkedinbot/i', $userAgent);

        if (!$isBot) {
            $block->increment('clicks');

            // Fetch country and city from IP
            $countryCode = null;
            $cityName = null;
            $ip = $request->ip();
            if ($ip !== '127.0.0.1' && $ip !== '::1') {
                try {
                    $geo = json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode,city"));
                    if ($geo) {
                        if (isset($geo->countryCode)) {
                            $countryCode = $geo->countryCode;
                        }
                        if (isset($geo->city)) {
                            $cityName = $geo->city;
                        }
                    }
                } catch (\Exception $e) {
                    // Silently ignore geo-location failures
                }
            }

            // Save detailed block click track log
            \App\Models\TrackLink::create([
                'link_id' => $block->link_id,
                'biolink_block_id' => $block->id,
                'user_id' => $block->user_id,
                'ip' => $ip,
                'country_code' => $countryCode,
                'city_name' => $cityName,
                'os' => $this->getOS($userAgent),
                'browser' => $this->getBrowser($userAgent),
                'device_type' => $this->getDevice($userAgent),
                'referrer_host' => $this->getReferrer($request),
            ]);
        }

        return redirect()->away($block->location_url ?? url('/'));
    }

    public function whatsappRotatorSubmit(Request $request, $id)
    {
        $link = \App\Models\Link::where('type', 'warotator')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'message' => 'nullable|string'
        ]);

        $numbersSetting = $link->settings['numbers'] ?? '';
        $rawNumbers = preg_split('/[\n,]+/', $numbersSetting);
        $numbers = array_filter(array_map(function($num) {
            $cleaned = preg_replace('/\D/', '', $num);
            return trim($cleaned);
        }, $rawNumbers));

        if (empty($numbers)) {
            return response()->json(['success' => false, 'message' => 'Nomor WhatsApp tujuan belum dikonfigurasi.'], 422);
        }

        // Round-robin selection based on lead counts
        $counts = [];
        foreach ($numbers as $num) {
            $counts[$num] = \App\Models\WhatsappLead::where('link_id', $link->id)
                ->where('whatsapp_number_used', $num)
                ->count();
        }

        asort($counts);
        $targetPhone = key($counts);

        // Save detailed track log as a link click as well
        $userAgent = $request->header('User-Agent');
        $countryCode = null;
        $cityName = null;
        $ip = $request->ip();
        if ($ip !== '127.0.0.1' && $ip !== '::1') {
            try {
                $geo = json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode,city"));
                if ($geo) {
                    if (isset($geo->countryCode)) {
                        $countryCode = $geo->countryCode;
                    }
                    if (isset($geo->city)) {
                        $cityName = $geo->city;
                    }
                }
            } catch (\Exception $e) {
                // Ignore geo failures
            }
        }

        // Log link click count and entry
        $link->increment('clicks');
        \App\Models\TrackLink::create([
            'link_id' => $link->id,
            'user_id' => $link->user_id,
            'ip' => $ip,
            'country_code' => $countryCode,
            'city_name' => $cityName,
            'os' => $this->getOS($userAgent),
            'browser' => $this->getBrowser($userAgent),
            'device_type' => $this->getDevice($userAgent),
            'referrer_host' => $this->getReferrer($request),
        ]);

        // Save lead response
        $lead = \App\Models\WhatsappLead::create([
            'link_id' => $link->id,
            'name' => $request->name,
            'city' => $request->city,
            'phone' => $request->phone,
            'message' => $request->message ?? '',
            'whatsapp_number_used' => $targetPhone,
            'ip' => $ip
        ]);

        // Compile pre-filled message
        $template = $link->settings['template'] ?? '';
        $msg = str_replace(
            ['[nama]', '[name]', '[kota]', '[city]', '[nomor]', '[phone]', '[pesan]', '[message]'],
            [$lead->name, $lead->name, $lead->city, $lead->city, $lead->phone, $lead->phone, $lead->message, $lead->message],
            $template
        );

        $waUrl = 'https://api.whatsapp.com/send?phone=' . urlencode($targetPhone) . '&text=' . urlencode($msg);

        return response()->json([
            'success' => true,
            'redirect_url' => $waUrl
        ]);
    }

    private function getOS($userAgent)
    {
        $osPlatform = "Unknown OS Platform";
        $osArray = [
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        ];
        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
                break;
            }
        }
        return $osPlatform;
    }

    private function getBrowser($userAgent)
    {
        $browser = "Unknown Browser";
        $browserArray = [
            '/edg/i'       => 'Edge',
            '/edge/i'      => 'Edge',
            '/opr/i'       => 'Opera',
            '/opera/i'     => 'Opera',
            '/chrome/i'    => 'Chrome',
            '/safari/i'    => 'Safari',
            '/firefox/i'   => 'Firefox',
            '/msie/i'      => 'Internet Explorer',
            '/trident/i'   => 'Internet Explorer',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Handheld Browser'
        ];
        foreach ($browserArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $browser = $value;
                break;
            }
        }
        return $browser;
    }

    private function getDevice($userAgent)
    {
        $tablet_browser = 0;
        $mobile_browser = 0;

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($userAgent))) {
            $tablet_browser++;
        }
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($userAgent))) {
            $mobile_browser++;
        }

        if ($tablet_browser > 0) {
            return 'tablet';
        } else if ($mobile_browser > 0) {
            return 'mobile';
        } else {
            return 'desktop';
        }
    }

    private function getReferrer(Request $request)
    {
        $referer = $request->headers->get('referer');
        $userAgent = $request->header('User-Agent');
        
        // 1. Try to get it from the HTTP Referer header
        if (!empty($referer)) {
            // Check for Android App Intents
            if (str_contains(strtolower($referer), 'android-app://com.whatsapp')) return 'WhatsApp';
            if (str_contains(strtolower($referer), 'android-app://com.instagram')) return 'Instagram';
            if (str_contains(strtolower($referer), 'android-app://com.facebook')) return 'Facebook';

            $host = parse_url($referer, PHP_URL_HOST);
            if ($host) {
                // Simplify common hosts
                if (str_contains($host, 'instagram.com')) return 'Instagram';
                if (str_contains($host, 'facebook.com')) return 'Facebook';
                if (str_contains($host, 'twitter.com') || str_contains($host, 't.co')) return 'Twitter (X)';
                if (str_contains($host, 'tiktok.com')) return 'TikTok';
                if (str_contains($host, 'youtube.com') || str_contains($host, 'youtu.be')) return 'YouTube';
                if (str_contains($host, 'whatsapp.com')) return 'WhatsApp';
                
                return str_replace('www.', '', $host);
            }
        }
        
        // 2. Fallback: Detect social network in-app browsers from User-Agent
        if (!empty($userAgent)) {
            if (stripos($userAgent, 'Instagram') !== false) return 'Instagram';
            if (stripos($userAgent, 'FBAN') !== false || stripos($userAgent, 'FBAV') !== false) return 'Facebook';
            if (stripos($userAgent, 'TikTok') !== false || stripos($userAgent, 'Bytedance') !== false) return 'TikTok';
            if (stripos($userAgent, 'Twitter') !== false) return 'Twitter (X)';
            if (stripos($userAgent, 'Snapchat') !== false) return 'Snapchat';
            if (stripos($userAgent, 'WhatsApp') !== false) return 'WhatsApp';
            if (stripos($userAgent, 'Line') !== false) return 'LINE';
        }

        return null;
    }
}
