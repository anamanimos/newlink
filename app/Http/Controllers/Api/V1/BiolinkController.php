<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\BiolinkBlock;
use App\Models\Domain;
use Illuminate\Support\Str;

class BiolinkController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $biolinks = Link::where('user_id', $user->id)
            ->where('type', 'biolink')
            ->withCount('biolinkBlocks')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        $biolinks->getCollection()->transform(function ($b) {
            $domain = $b->domain ? $b->domain->host : config('app.url');
            $b->full_url = rtrim($domain, '/') . '/' . $b->url;
            return $b;
        });

        return response()->json([
            'status' => 'success',
            'data' => $biolinks->items(),
            'pagination' => [
                'current_page' => $biolinks->currentPage(),
                'total' => $biolinks->total(),
                'last_page' => $biolinks->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'url' => 'required|string|max:128|alpha_dash',
            'domain_id' => 'nullable|integer',
            'project_id' => 'nullable|integer',
        ]);

        $alias = strtolower($request->url);
        $domainId = (int)($request->domain_id ?? 0);

        if (Link::where('domain_id', $domainId)->where('url', $alias)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'The biolink slug "' . $alias . '" is already taken.',
            ], 422);
        }

        $biolink = Link::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id ?: null,
            'domain_id' => $domainId,
            'type' => 'biolink',
            'url' => $alias,
            'clicks' => 0,
            'is_enabled' => 1,
            'settings' => json_encode([
                'title' => $request->title ?: $alias,
                'description' => $request->description ?: '',
                'background_type' => 'preset',
                'background_value' => 'clean_slate',
                'text_color' => '#ffffff',
            ]),
        ]);

        $domainHost = config('app.url');
        if ($domainId > 0 && $biolink->domain) {
            $domainHost = 'https://' . $biolink->domain->host;
        }
        $biolink->full_url = rtrim($domainHost, '/') . '/' . $biolink->url;

        return response()->json([
            'status' => 'success',
            'message' => 'Biolink page created successfully.',
            'data' => $biolink
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $biolink = Link::where('user_id', $user->id)
            ->where('type', 'biolink')
            ->with(['biolinkBlocks' => function ($q) {
                $q->orderBy('order', 'ASC');
            }])
            ->findOrFail($id);

        $domainHost = config('app.url');
        if ($biolink->domain_id > 0 && $biolink->domain) {
            $domainHost = 'https://' . $biolink->domain->host;
        }
        $biolink->full_url = rtrim($domainHost, '/') . '/' . $biolink->url;

        return response()->json([
            'status' => 'success',
            'data' => $biolink
        ]);
    }

    public function addBlock(Request $request, $id)
    {
        $user = $request->user();
        $biolink = Link::where('user_id', $user->id)
            ->where('type', 'biolink')
            ->findOrFail($id);

        $request->validate([
            'type' => 'required|string|in:link,heading,paragraph,avatar,image,youtube,vimeo,tiktok,sound_cloud,spotify,whatsapp,email_collector',
            'name' => 'nullable|string|max:255',
            'location_url' => 'nullable|string|max:2048',
        ]);

        $nextOrder = (BiolinkBlock::where('link_id', $biolink->id)->max('order') ?? 0) + 1;

        $block = BiolinkBlock::create([
            'user_id' => $user->id,
            'link_id' => $biolink->id,
            'type' => $request->type,
            'location_url' => $request->location_url,
            'clicks' => 0,
            'order' => $nextOrder,
            'is_enabled' => 1,
            'settings' => json_encode([
                'name' => $request->name ?: ucfirst($request->type),
                'icon' => $request->icon ?: null,
            ]),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Block added to biolink successfully.',
            'data' => $block
        ], 201);
    }
}
