@extends('layouts.admin')
@section('title', 'Edit Gallery Image')
@section('heading', 'Edit Gallery Image')
@section('content')
    <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $gallery->title) }}" required>
        </div>
        <div class="form-group">
            <label>Image</label>
            @if($gallery->image_path)
                <div style="margin-bottom: 1rem;">
                    <img src="{{ asset('storage/' . $gallery->image_path) }}" style="max-width: 200px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                </div>
            @endif
            <div style="border: 2px dashed var(--border); border-radius: var(--radius-md); padding: 2rem; text-align: center; cursor: pointer;" onclick="this.querySelector('input').click()">
                <i class="ri-upload-cloud-2-line" style="font-size: 2rem; color: var(--accent);"></i>
                <p style="margin-top: 0.5rem; color: var(--text-secondary);">Click to upload a new image</p>
                <small style="color: var(--text-secondary);">PNG, JPG up to 2MB</small>
                <input type="file" id="image" name="image" accept="image/*" style="display: none;" onchange="previewImage(this)">
            </div>
            <img id="imagePreview" style="display: none; margin-top: 1rem; max-width: 200px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
        </div>
        <div class="form-group">
            <label for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $gallery->sort_order) }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline">Cancel</a>
    </form>
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
@endsection
