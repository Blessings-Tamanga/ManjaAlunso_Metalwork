@extends('layouts.admin')

@section('title', 'Edit Service')
@section('heading', 'Edit Service')

@section('content')
    <form action="{{ route('admin.services.update', $service) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $service->title) }}" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $service->slug) }}" required>
        </div>
        <div class="form-group">
            <label for="icon">Icon Class</label>
            <input type="text" id="icon" name="icon" value="{{ old('icon', $service->icon) }}">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required>{{ old('description', $service->description) }}</textarea>
        </div>
        <div class="form-group">
            <label for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                Active
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Update Service</button>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection