<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $totalProjects = Project::where('user_id', $userId)->count();
        $totalProjectLinks = \App\Models\Link::where('user_id', $userId)->whereNotNull('project_id')->count();
        $topProject = Project::where('user_id', $userId)->withCount('links')->orderBy('links_count', 'desc')->first();
        $avgLinksPerProject = $totalProjects > 0 ? round($totalProjectLinks / $totalProjects, 1) : 0;

        $query = Project::where('user_id', $userId)->withCount('links')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $projects = $query->paginate(15)->withQueryString();

        return view('projects.index', compact(
            'projects',
            'totalProjects',
            'totalProjectLinks',
            'topProject',
            'avgLinksPerProject'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:64',
            'color' => 'nullable|string|max:16'
        ]);

        Project::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'color' => $request->color ?? '#000000',
        ]);

        return back()->with('success', 'Project berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $project = Project::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:64',
            'color' => 'nullable|string|max:16'
        ]);

        $project->update([
            'name' => $request->name,
            'color' => $request->color ?? '#000000',
        ]);

        return back()->with('success', 'Project berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $project = Project::where('user_id', auth()->id())->findOrFail($id);
        $project->delete();

        return back()->with('success', 'Project berhasil dihapus!');
    }
}
