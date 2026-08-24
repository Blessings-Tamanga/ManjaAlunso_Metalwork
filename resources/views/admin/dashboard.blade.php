@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="grid-4">
        <div class="stat-item">
            <div class="stat-number">{{ \App\Models\Service::count() }}</div>
            <div class="stat-label">Services</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ \App\Models\Project::count() }}</div>
            <div class="stat-label">Projects</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ \App\Models\Testimonial::count() }}</div>
            <div class="stat-label">Testimonials</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ \App\Models\ContactMessage::where('is_read', false)->count() }}</div>
            <div class="stat-label">Unread Messages</div>
        </div>
    </div>

    <div style="margin-top: 2rem;">
        <h3>Recent Messages</h3>
        <table class="table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Service</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse(\App\Models\ContactMessage::latest()->take(5)->get() as $msg)
                    <tr>
                        <td>{{ $msg->name }}</td>
                        <td>{{ $msg->email }}</td>
                        <td>{{ $msg->service_interest ?? 'N/A' }}</td>
                        <td>{{ $msg->is_read ? 'Read' : 'Unread' }}</td>
                        <td>{{ $msg->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No messages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection