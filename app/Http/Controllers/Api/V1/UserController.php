<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();

        // Calculate quotas
        $totalLinks = $user->links()->where('type', 'link')->count();
        $totalBiolinks = $user->links()->where('type', 'biolink')->count();
        $totalProjects = \App\Models\Project::where('user_id', $user->id)->count();
        $totalDomains = $user->domains()->count();
        $totalPixels = $user->pixels()->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'plan_id' => $user->plan_id ?: 'free',
                'plan_expiration_date' => $user->plan_expiration_date,
                'status' => $user->status ? 'active' : 'disabled',
                'role' => $user->type == 1 ? 'admin' : 'user',
                'api_key' => $user->api_key,
                'usage' => [
                    'shortlinks_count' => $totalLinks,
                    'biolinks_count' => $totalBiolinks,
                    'projects_count' => $totalProjects,
                    'domains_count' => $totalDomains,
                    'pixels_count' => $totalPixels,
                ],
                'created_at' => $user->created_at ? $user->created_at->toIso8601String() : null,
            ]
        ]);
    }

    public function regenerateKey(Request $request)
    {
        $user = $request->user();
        $newKey = Str::random(32);
        
        $user->update(['api_key' => $newKey]);

        return response()->json([
            'status' => 'success',
            'message' => 'API key successfully regenerated.',
            'data' => [
                'api_key' => $newKey
            ]
        ]);
    }
}
