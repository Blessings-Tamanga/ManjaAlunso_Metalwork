@extends('layouts.admin')

@section('title', 'Edit Testimonial')
@section('heading', 'Edit Testimonial')

@section('content')
    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label for="client_name">Client Name</label>
            <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $testimonial->client_name) }}" required>
        </div>
        <div class="form-group">
            <label for="client_company">Company (optional)</label>
            <input type="text" id="client_company" name="client_company" value="{{ old('client_company', $testimonial->client_company) }}">
        </div>
        <div class="form-group">
            <label for="content">Testimonial</label>
            <textarea id="content" name="content" rows="4" required>{{ old('content', $testimonial->content) }}</textarea>
        </div>
        <div class="form-group">
            <label for="rating">Rating (1-5)</label>
            <input type="number" id="rating" name="rating" min="1" max="5" value="{{ old('rating', $testimonial->rating) }}" required>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', $testimonial->is_approved) ? 'checked' : '' }}>
                Approved
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection
