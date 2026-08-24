@extends('layouts.app')

@section('title', 'About Us | ManjaAlunso Metalworks')

@section('content')
    <section class="section" style="padding-top: calc(var(--nav-height) + 2rem);">
        <div class="container">
            <div class="grid-2">
                <div class="img-hover fade-in">
                    @if($aboutImage)
                        <img src="{{ asset('storage/' . $aboutImage) }}" alt="About us" loading="lazy">
                    @else
                        <img src="{{ asset('Assets/Media/portrait-african-american-man-factory.jpg') }}" alt="About us" loading="lazy">
                    @endif
                </div>
                <div class="fade-in">
                    <span class="section-tag">About Us</span>
                    <h2>Crafting Metal With Purpose</h2>
                    <p style="color:var(--text-secondary); margin:1rem 0 1.5rem;">ManjaAlunso Metalworks is a trusted name in steel fabrication and welding. With over 15 years of experience, we combine skilled craftsmanship with modern technology to deliver durable, high-quality metalwork for industrial and commercial clients.</p>
                    <p style="color:var(--text-secondary);">Our commitment to safety, precision, and on‑time delivery has earned us a reputation as a reliable partner for projects of all sizes.</p>
                </div>
            </div>
        </div>
    </section>
@endsection