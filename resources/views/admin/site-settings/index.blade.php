@extends('layouts.admin')
@section('title', 'Site Settings')
@section('heading', 'Site Settings')
@section('content')
    <div class="flex-between">
        <p>Manage website content and media.</p>
    </div>

    <div style="display: grid; gap: 1.5rem; margin-top: 1.5rem;">
        @foreach([
            'hero_background' => 'Hero Background Image',
            'about_image' => 'About Us Image',
            'why_choose_us_media' => 'Why Choose Us Image/Video',
        ] as $key => $label)
            @php
                $setting = $settings[$key] ?? null;
            @endphp
            <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem;">{{ $label }}</h3>
                @if($setting && $setting->value)
                    <div style="margin-bottom: 1rem;">
                        @if($setting->type === 'image')
                            <img src="{{ asset('storage/' . $setting->value) }}" style="max-width: 300px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        @endif
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.site-settings.update', $key) }}" enctype="multipart/form-data" id="setting-form-{{ $key }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>{{ $setting && $setting->type === 'image' ? 'Replace Image' : 'Upload Image' }}</label>
                        <div style="border: 2px dashed var(--border); border-radius: var(--radius-md); padding: 1.5rem; text-align: center; cursor: pointer;" onclick="document.getElementById('file-{{ $key }}').click()">
                            <i class="ri-upload-cloud-2-line" style="font-size: 1.5rem; color: var(--accent);"></i>
                            <p style="margin-top: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">Click to upload</p>
                            <input type="file" id="file-{{ $key }}" name="value" accept="image/*" style="display: none;">
                        </div>
                        @error('value')
                            <small style="color: #ef4444;">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Save</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
