@extends('layouts.admin')
@section('title', 'Create Project')
@section('heading', 'Create Project')
@section('content')
    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="client">Client (optional)</label>
            <input type="text" id="client" name="client" value="{{ old('client') }}">
        </div>
        <div class="form-group">
            <label for="completed_at">Completed Date</label>
            <input type="date" id="completed_at" name="completed_at" value="{{ old('completed_at') }}">
        </div>
        <div class="form-group">
            <label>Project Image</label>
            <div style="border: 2px dashed var(--border); border-radius: var(--radius-md); padding: 2rem; text-align: center; cursor: pointer; transition: all var(--transition);" onclick="document.getElementById('image').click()">
                <i class="ri-upload-cloud-2-line" style="font-size: 2rem; color: var(--accent);"></i>
                <p style="margin-top: 0.5rem; color: var(--text-secondary);">Click to upload an image</p>
                <small style="color: var(--text-secondary);">PNG, JPG up to 2MB</small>
                <input type="file" id="image" name="image" accept="image/*" style="display: none;" onchange="previewImage(this)">
            </div>
            <img id="imagePreview" style="display: none; margin-top: 1rem; max-width: 200px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
        </div>
        <script>
            function previewImage(input) {
                const preview = document.getElementById('imagePreview');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                Featured on Homepage
            </label>
        </div>
        <div class="form-group">
            <label for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
        </div>
        <button type="submit" class="btn btn-primary">Save Project</button>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection