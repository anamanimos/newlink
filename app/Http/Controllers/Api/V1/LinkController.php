<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\Domain;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Link::where('user_id', $user->id)
            ->whereIn('type', ['link', 'rotator', 'file', 'vcard', 'event', 'static']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('url', 'like', "%{$s}%")
                  ->orWhere('location_url', 'like', "%{$s}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $perPage = min((int)($request->per_page ?? 15), 100);
        $links = $query->orderBy('created_at', 'DESC')->paginate($perPage);

        // Transform collection to add full short URL
        $links->getCollection()->transform(function ($link) {
            $domain = $link->domain ? $link->domain->host : config('app.url');
            $link->full_url = rtrim($domain, '/') . '/' . $link->url;
            return $link;
        });

        return response()->json([
            'status' => 'success',
            'data' => $links->items(),
            'pagination' => [
                'current_page' => $links->currentPage(),
                'per_page' => $links->perPage(),
                'total' => $links->total(),
                'last_page' => $links->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'location_url' => 'required|url|max:2048',
            'url' => 'nullable|string|max:128|alpha_dash',
            'project_id' => 'nullable|integer',
            'domain_id' => 'nullable|integer',
        ]);

        // Generate or check alias
        $alias = $request->url ? strtolower($request->url) : Str::random(6);

        // Check uniqueness for this domain
        $domainId = (int)($request->domain_id ?? 0);
        if (Link::where('domain_id', $domainId)->where('url', $alias)->exists()) {
            if ($request->url) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The custom alias "' . $alias . '" is already taken.',
                ], 422);
            } else {
                $alias = Str::random(7);
            }
        }

        $link = Link::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id ?: null,
            'domain_id' => $domainId,
            'type' => 'link',
            'url' => $alias,
            'location_url' => $request->location_url,
            'clicks' => 0,
            'is_enabled' => 1,
            'settings' => $request->settings ? json_encode($request->settings) : null,
        ]);

        $domainHost = config('app.url');
        if ($domainId > 0) {
            $dom = Domain::find($domainId);
            if ($dom) $domainHost = 'https://' . $dom->host;
        }
        $link->full_url = rtrim($domainHost, '/') . '/' . $link->url;

        return response()->json([
            'status' => 'success',
            'message' => 'Shortlink created successfully.',
            'data' => $link
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $link = Link::where('user_id', $user->id)->findOrFail($id);

        $domainHost = config('app.url');
        if ($link->domain_id > 0 && $link->domain) {
            $domainHost = 'https://' . $link->domain->host;
        }
        $link->full_url = rtrim($domainHost, '/') . '/' . $link->url;

        // Add recent click summary
        $todayClicks = \App\Models\TrackLink::where('link_id', $link->id)
            ->whereDate('datetime', \Carbon\Carbon::today())
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => array_merge($link->toArray(), [
                'today_clicks' => $todayClicks,
            ])
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $link = Link::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'location_url' => 'nullable|url|max:2048',
            'url' => 'nullable|string|max:128|alpha_dash',
            'project_id' => 'nullable|integer',
            'is_enabled' => 'nullable|boolean',
        ]);

        if ($request->filled('url') && $request->url !== $link->url) {
            $alias = strtolower($request->url);
            if (Link::where('domain_id', $link->domain_id)->where('url', $alias)->where('id', '!=', $link->id)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The custom alias "' . $alias . '" is already taken.',
                ], 422);
            }
            $link->url = $alias;
        }

        if ($request->filled('location_url')) {
            $link->location_url = $request->location_url;
        }

        if ($request->has('project_id')) {
            $link->project_id = $request->project_id ?: null;
        }

        if ($request->has('is_enabled')) {
            $link->is_enabled = $request->is_enabled ? 1 : 0;
        }

        $link->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Link updated successfully.',
            'data' => $link
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $link = Link::where('user_id', $user->id)->findOrFail($id);

        $link->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Link deleted successfully.'
        ]);
    }
}
