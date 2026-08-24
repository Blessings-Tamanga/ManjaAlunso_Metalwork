@extends('layouts.admin')

@section('title', 'Create Service')
@section('heading', 'Create Service')

@section('content')
    <form action="{{ route('admin.services.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug (URL)</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required>
            <small>Unique identifier, e.g., "custom-fabrication".</small>
        </div>
        <div class="form-group">
            <label for="icon">Icon Class</label>
            <input type="text" id="icon" name="icon" value="{{ old('icon', 'ri-tools-fill') }}" placeholder="ri-tools-fill">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
        </div>
        <div class="form-group">
            <label for="is_active">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                Active
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Save Service</button>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection