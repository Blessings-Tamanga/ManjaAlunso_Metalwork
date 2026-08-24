@extends('layouts.app')

@section('title', 'Our Services | ManjaAlunso Metalworks')

@section('content')
    <section class="section" style="padding-top: calc(var(--nav-height) + 2rem);">
        <div class="container">
            <div class="text-center mb-3 fade-in">
                <span class="section-tag">Our Services</span>
                <h2>Comprehensive Metalwork Solutions</h2>
                <p style="color:var(--text-secondary); max-width:600px; margin:0 auto;">We offer a full range of fabrication and welding services tailored to your project requirements.</p>
            </div>
            <div class="grid-3">
                @forelse($services as $service)
                    <div class="service-card fade-in">
                        <div class="service-icon"><i class="{{ $service->icon ?? 'ri-tools-fill' }}"></i></div>
                        <h3>{{ $service->title }}</h3>
                        <p>{{ $service->description }}</p>
                    </div>
                @empty
                    <p>No services available.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection