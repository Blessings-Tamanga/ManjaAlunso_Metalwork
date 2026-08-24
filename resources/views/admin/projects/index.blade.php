@extends('layouts.admin')
@section('title', 'Projects')
@section('heading', 'Projects')
@section('content')
    <div class="flex-between">
        <p>Manage your projects.</p>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Add Project</a>
    </div>
    <table class="table">
        <thead><tr><th>Title</th><th>Client</th><th>Featured</th><th>Image</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($projects as $project)
                <tr>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->client ?? '-' }}</td>
                    <td>{{ $project->is_featured ? '⭐' : '' }}</td>
                    <td>@if($project->image) ✅ @else ❌ @endif</td>
                    <td>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline">Edit</a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No projects.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection