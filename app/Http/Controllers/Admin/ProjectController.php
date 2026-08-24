<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('sort_order')->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:projects|string|max:255',
            'description' => 'required|string',
            'client' => 'nullable|string|max:255',
            'completed_at' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $validated['image'] = $path;
        }

        Project::create($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug,' . $project->id,
            'description' => 'required|string',
            'client' => 'nullable|string|max:255',
            'completed_at' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $path = $request->file('image')->store('projects', 'public');
            $validated['image'] = $path;
        }

        $project->update($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }
}