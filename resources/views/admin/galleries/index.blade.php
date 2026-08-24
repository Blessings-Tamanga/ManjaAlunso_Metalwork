@extends('layouts.admin')
@section('title', 'Gallery')
@section('heading', 'Gallery')
@section('content')
    <div class="flex-between">
        <p>Manage project gallery images.</p>
        <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">Add Image</a>
    </div>

    <table class="table">
        <thead>
            <tr><th>Image</th><th>Title</th><th>Sort Order</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse($galleries as $item)
                <tr>
                    <td><img src="{{ asset('storage/' . $item->image_path) }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-sm);"></td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->sort_order }}</td>
                    <td>
                        <a href="{{ route('admin.galleries.edit', $item) }}" class="btn btn-sm btn-outline">Edit</a>
                        <form action="{{ route('admin.galleries.destroy', $item) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No gallery items.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
