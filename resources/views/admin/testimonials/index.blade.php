@extends('layouts.admin')
@section('title', 'Testimonials')
@section('heading', 'Testimonials')
@section('content')
    <div class="flex-between">
        <p>Manage client testimonials.</p>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">Add Testimonial</a>
    </div>
    <table class="table">
        <thead><tr><th>Client</th><th>Company</th><th>Rating</th><th>Approved</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($testimonials as $t)
                <tr>
                    <td>{{ $t->client_name }}</td>
                    <td>{{ $t->client_company ?? '-' }}</td>
                    <td>{{ $t->rating }} ★</td>
                    <td>{{ $t->is_approved ? '✅' : '❌' }}</td>
                    <td>
                        <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-sm btn-outline">Edit</a>
                        <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No testimonials.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection