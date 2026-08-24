@extends('layouts.admin')
@section('title', 'Create Testimonial')
@section('heading', 'Create Testimonial')
@section('content')
    <form action="{{ route('admin.testimonials.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="client_name">Client Name</label>
            <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" required>
        </div>
        <div class="form-group">
            <label for="client_company">Company (optional)</label>
            <input type="text" id="client_company" name="client_company" value="{{ old('client_company') }}">
        </div>
        <div class="form-group">
            <label for="content">Testimonial</label>
            <textarea id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
        </div>
        <div class="form-group">
            <label for="rating">Rating (1-5)</label>
            <input type="number" id="rating" name="rating" min="1" max="5" value="{{ old('rating', 5) }}" required>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_approved" value="1" {{ old('is_approved') ? 'checked' : '' }}>
                Approved
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection