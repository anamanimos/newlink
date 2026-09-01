<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domain;

class DomainController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $domains = Domain::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('type', 1); // global domains
        })->where('is_enabled', 1)->get();

        return response()->json([
            'status' => 'success',
            'data' => $domains
        ]);
    }
}
