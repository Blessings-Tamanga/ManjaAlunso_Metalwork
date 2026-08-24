@extends('layouts.admin')
@section('title', 'Contact Messages')
@section('heading', 'Contact Messages')
@section('content')
    <table class="table">
        <thead><tr><th>Name</th><th>Email</th><th>Service</th><th>Message</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
            @forelse($messages as $msg)
                <tr style="{{ $msg->is_read ? '' : 'font-weight:bold;' }}">
                    <td>{{ $msg->name }}</td>
                    <td>{{ $msg->email }}</td>
                    <td>{{ $msg->service_interest ?? 'N/A' }}</td>
                    <td>{{ Str::limit($msg->message, 50) }}</td>
                    <td>
                        @if(!$msg->is_read)
                            <form action="{{ route('admin.contacts.mark-read', $msg) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline">Mark Read</button>
                            </form>
                        @else
                            Read
                        @endif
                        <form action="{{ route('admin.contacts.destroy', $msg) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this message?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                    <td>{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No messages.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $messages->links() }}
@endsection