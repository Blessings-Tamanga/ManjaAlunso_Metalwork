@extends('layouts.admin')

@section('title', 'Services')
@section('heading', 'Services')

@section('content')
    <div class="flex-between">
        <p>Manage your services.</p>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Add Service</a>
    </div>

    <table class="table">
        <thead>
            <tr><th>Title</th><th>Icon</th><th>Active</th><th>Order</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse($services as $service)
                <tr>
                    <td>{{ $service->title }}</td>
                    <td><i class="{{ $service->icon }}"></i></td>
                    <td>{{ $service->is_active ? '✅' : '❌' }}</td>
                    <td>{{ $service->sort_order }}</td>
                    <td>
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline">Edit</a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline" onclick="return confirm('Delete this service?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No services found.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection