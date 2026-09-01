<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $projects = Project::where('user_id', $user->id)
            ->withCount('links')
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $projects
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:100',
            'color' => 'nullable|string|max:30',
        ]);

        $project = Project::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'color' => $request->color ?: '#3e97ff',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Project created successfully.',
            'data' => $project
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $project = Project::where('user_id', $user->id)->findOrFail($id);

        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Project deleted successfully.'
        ]);
    }
}
