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
        // TODO: Later add advanced tracking to legacy DB if needed

        if ($link->type === 'link') {
            return redirect()->away($link->location_url);
        } elseif ($link->type === 'biolink') {
            $blocks = $link->biolinkBlocks()->where('is_enabled', 1)->orderBy('order')->get();
            return view('biolinks.public', compact('link', 'blocks'));
        }

        abort(404);
    }
}
