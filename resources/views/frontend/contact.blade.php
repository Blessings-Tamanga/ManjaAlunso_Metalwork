@extends('layouts.app')

@section('title', 'Contact Us | ManjaAlunso Metalworks')

@section('content')
    <section class="section" style="padding-top: calc(var(--nav-height) + 2rem);" id="contact">
        <div class="container">
            <div class="text-center mb-3 fade-in">
                <span class="section-tag">Get In Touch</span>
                <h2>Start Your Project Today</h2>
            </div>
            <div class="contact-grid">
                <div class="contact-form fade-in">
                    @if(session('success'))
                        <div class="alert alert-success" style="background: #16a34a; color: white; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form id="quoteForm" action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Your name" value="{{ old('name') }}" required>
                            @error('name')<small style="color: #ef4444;">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="you@company.com" value="{{ old('email') }}" required>
                            @error('email')<small style="color: #ef4444;">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label for="service">Service Interest</label>
                            <select id="service" name="service_interest" required>
                                <option value="">Select service</option>
                                <option value="Custom Fabrication" {{ old('service_interest') == 'Custom Fabrication' ? 'selected' : '' }}>Custom Fabrication</option>
                                <option value="Structural Steel" {{ old('service_interest') == 'Structural Steel' ? 'selected' : '' }}>Structural Steel</option>
                                <option value="Welding & Repairs" {{ old('service_interest') == 'Welding & Repairs' ? 'selected' : '' }}>Welding & Repairs</option>
                                <option value="Gates & Fencing" {{ old('service_interest') == 'Gates & Fencing' ? 'selected' : '' }}>Gates & Fencing</option>
                                <option value="Industrial Maintenance" {{ old('service_interest') == 'Industrial Maintenance' ? 'selected' : '' }}>Industrial Maintenance</option>
                                <option value="Architectural Metalwork" {{ old('service_interest') == 'Architectural Metalwork' ? 'selected' : '' }}>Architectural Metalwork</option>
                            </select>
                            @error('service_interest')<small style="color: #ef4444;">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label for="message">Project Details</label>
                            <textarea id="message" name="message" rows="4" placeholder="Describe your project..." required>{{ old('message') }}</textarea>
                            @error('message')<small style="color: #ef4444;">{{ $message }}</small>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;">Submit Request</button>
                    </form>
                </div>
                <div class="contact-info fade-in">
                    <div class="info-card">
                        <i class="ri-map-pin-line"></i>
                        <div><strong>Address</strong><br><span style="color:var(--text-secondary);">Mchesi, Lilongwe City</span></div>
                    </div>
                    <div class="info-card">
                        <i class="ri-phone-line"></i>
                        <div><strong>Phone</strong><br><span style="color:var(--text-secondary);">+265 (999) xxx-xxx</span></div>
                    </div>
                    <div class="info-card">
                        <i class="ri-mail-line"></i>
                        <div><strong>Email</strong><br><span style="color:var(--text-secondary);">info@manjaalunso.com</span></div>
                    </div>
                    <div class="map-container">
                        <iframe src="https://maps.google.com/maps?q=Industrial%20District&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Location"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection