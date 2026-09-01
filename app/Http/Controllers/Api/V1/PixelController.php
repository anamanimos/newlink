<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pixel;

class PixelController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $pixels = Pixel::where('user_id', $user->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $pixels
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'type' => 'required|string|max:64',
            'name' => 'required|string|max:128',
            'pixel_id' => 'nullable|string|max:255',
        ]);

        $pixel = Pixel::create([
            'user_id' => $user->id,
            'type' => strtolower($request->type),
            'name' => $request->name,
            'pixel' => $request->pixel_id ?: ($request->pixel ?: ''),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pixel created successfully.',
            'data' => $pixel
        ], 201);
    }
}
