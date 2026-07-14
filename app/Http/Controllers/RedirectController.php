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

        // Increment clicks
        $link->increment('clicks');
        
        // Fetch country from IP
        $countryCode = null;
        $ip = $request->ip();
        if ($ip !== '127.0.0.1' && $ip !== '::1') {
            try {
                $geo = json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode"));
                if ($geo && isset($geo->countryCode)) {
                    $countryCode = $geo->countryCode;
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
            'os' => $this->getOS($request->header('User-Agent')),
            'browser' => $this->getBrowser($request->header('User-Agent')),
            'device_type' => $this->getDevice($request->header('User-Agent')),
        ]);

        if ($link->type === 'link') {
            return redirect()->away($link->location_url);
        } elseif ($link->type === 'biolink') {
            $blocks = $link->biolinkBlocks()->where('is_enabled', 1)->orderBy('order')->get();
            return view('biolinks.public', compact('link', 'blocks'));
        }

        abort(404);
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
}
