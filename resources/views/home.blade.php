@extends('layouts.app')

@section('title', 'ManjaAlunso Metalworks | Precision Steel Fabrication')

@section('content')
    <!-- Hero -->
    <section class="hero" id="hero" @if($heroBackground) style="background: url('{{ asset('storage/' . $heroBackground) }}') center/cover no-repeat;" @endif>
        <div class="container hero-content">
            <h1>Precision Metal<br>Fabrication & Welding</h1>
            <p>Expert steel fabrication, structural engineering, and custom metalwork. Built with integrity, delivered with precision.</p>
            <div class="hero-buttons">
                <a href="{{ route('contact') }}" class="btn btn-primary">Learn More</a>
                <a href="{{ route('projects') }}" class="btn btn-outline" style="color: white;">View Projects</a>
            </div>
        </div>
    </section>

    <!-- About -->
    <section class="section" id="about">
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
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="section bg-light" id="services">
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
                    <p>No services available at the moment.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Projects -->
    <section class="section" id="projects">
        <div class="container">
            <div class="text-center mb-3 fade-in">
                <span class="section-tag">Featured Work</span>
                <h2>Projects That Speak for Themselves</h2>
            </div>
            @forelse($projects as $project)
                <div class="project-row {{ $loop->iteration % 2 == 0 ? 'reverse' : '' }} fade-in">
                    <div class="proj-img img-hover">
                        @if($project->image)
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy">
                        @else
                            <img src="https://images.unsplash.com/photo-1565008447742-97f6f38c985c?auto=format&fit=crop&w=800&q=80" alt="Project" loading="lazy">
                        @endif
                    </div>
                    <div class="proj-content">
                        <h3>{{ $project->title }}</h3>
                        <p>{{ $project->description }}</p>
                        <a href="{{ route('contact') }}" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
            @empty
                <p>No featured projects yet.</p>
            @endforelse
        </div>
    </section>

    <!-- Gallery (static, but can be dynamic with all projects) -->
    <section class="section bg-light">
        <div class="container">
            <div class="text-center mb-3 fade-in">
                <span class="section-tag">Project Gallery</span>
                <h2>Our Craft in Detail</h2>
            </div>
            <div class="gallery-grid fade-in">
                @forelse($galleries as $item)
                    <div class="gallery-item">
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" loading="lazy">
                        <div class="gallery-overlay"><span>{{ $item->title }}</span></div>
                    </div>
                @empty
                    <p>No gallery images yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Industries (static) -->
    <section class="section">
        <div class="container">
            <div class="text-center mb-3 fade-in">
                <span class="section-tag">Industries Served</span>
                <h2>Trusted Across Sectors</h2>
            </div>
            <div class="grid-4">
                <div class="industry-card fade-in text-center">
                    <i class="ri-building-2-line" style="font-size:2rem; color:var(--accent); margin-bottom:0.8rem;"></i>
                    <h4>Construction</h4>
                    <p style="color:var(--text-secondary); font-size:0.85rem;">Structural frameworks</p>
                </div>
                <div class="industry-card fade-in text-center">
                    <i class="ri-oil-line" style="font-size:2rem; color:var(--accent); margin-bottom:0.8rem;"></i>
                    <h4>Oil & Gas</h4>
                    <p style="color:var(--text-secondary); font-size:0.85rem;">Pipeline supports</p>
                </div>
                <div class="industry-card fade-in text-center">
                    <i class="ri-plant-line" style="font-size:2rem; color:var(--accent); margin-bottom:0.8rem;"></i>
                    <h4>Agriculture</h4>
                    <p style="color:var(--text-secondary); font-size:0.85rem;">Equipment fabrication</p>
                </div>
                <div class="industry-card fade-in text-center">
                    <i class="ri-building-line" style="font-size:2rem; color:var(--accent); margin-bottom:0.8rem;"></i>
                    <h4>Manufacturing</h4>
                    <p style="color:var(--text-secondary); font-size:0.85rem;">Machine guards & platforms</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="section bg-light">
        <div class="container">
            <div class="grid-2">
                <div class="fade-in">
                    <span class="section-tag">Why Choose Us</span>
                    <h2>Built on Trust, Proven by Results</h2>
                    <div style="display:grid; gap:1rem; margin-top:1.5rem;">
                        <div style="display:flex; gap:12px; align-items:flex-start;">
                            <i class="ri-shield-check-line" style="color:var(--accent); font-size:1.3rem;"></i>
                            <div><strong>Certified Excellence</strong><p style="color:var(--text-secondary); font-size:0.85rem;">Fully licensed and insured with rigorous safety standards.</p></div>
                        </div>
                        <div style="display:flex; gap:12px; align-items:flex-start;">
                            <i class="ri-team-line" style="color:var(--accent); font-size:1.3rem;"></i>
                            <div><strong>Skilled Workforce</strong><p style="color:var(--text-secondary); font-size:0.85rem;">50+ certified welders and fabricators.</p></div>
                        </div>
                        <div style="display:flex; gap:12px; align-items:flex-start;">
                            <i class="ri-timer-line" style="color:var(--accent); font-size:1.3rem;"></i>
                            <div><strong>On-Time Delivery</strong><p style="color:var(--text-secondary); font-size:0.85rem;">Projects completed on schedule, within budget.</p></div>
                        </div>
                    </div>
                </div>
                <div class="img-hover fade-in">
                    @if($whyChooseUsMedia)
                        <img src="{{ asset('storage/' . $whyChooseUsMedia) }}" alt="Why choose us" loading="lazy">
                    @else
                        <img src="{{ asset('Assets/Media/portrait-african-american-man-factory.jpg') }}" alt="Skilled welder" loading="lazy">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section">
        <div class="container">
            <div class="text-center mb-3 fade-in">
                <span class="section-tag">Testimonials</span>
                <h2>Client Experiences</h2>
            </div>
            <div class="grid-3">
                @forelse($testimonials as $testimonial)
                    <div class="testimonial-card fade-in">
                        <div style="color:var(--accent); margin-bottom:0.8rem;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating) ★ @else ☆ @endif
                            @endfor
                        </div>
                        <p style="color:var(--text-secondary); font-style:italic; margin-bottom:1rem;">"{{ $testimonial->content }}"</p>
                        <strong>{{ $testimonial->client_name }}</strong><br>
                        @if($testimonial->client_company)
                            <small style="color:var(--text-secondary);">{{ $testimonial->client_company }}</small>
                        @endif
                    </div>
                @empty
                    <p>No testimonials yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Statistics (static values) -->
    <section class="section bg-light">
        <div class="container">
            <div class="grid-4">
                <div class="stat-item fade-in">
                    <div class="stat-number" data-target="15">0</div>
                    <div class="stat-label">Years Experience</div>
                </div>
                <div class="stat-item fade-in">
                    <div class="stat-number" data-target="500">0</div>
                    <div class="stat-label">Projects Completed</div>
                </div>
                <div class="stat-item fade-in">
                    <div class="stat-number" data-target="50">0</div>
                    <div class="stat-label">Skilled Workers</div>
                </div>
                <div class="stat-item fade-in">
                    <div class="stat-number" data-target="98">0</div>
                    <div class="stat-label">% Client Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="section" id="contact">
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