<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:services|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $service->id,
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        $service->update($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted.');
    }
}