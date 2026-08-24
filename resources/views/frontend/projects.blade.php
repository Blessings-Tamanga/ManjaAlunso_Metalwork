@extends('layouts.app')

@section('title', 'Projects | ManjaAlunso Metalworks')

@section('content')
    <section class="section" style="padding-top: calc(var(--nav-height) + 2rem);">
        <div class="container">
            <div class="text-center mb-3 fade-in">
                <span class="section-tag">Our Work</span>
                <h2>Projects That Speak for Themselves</h2>
            </div>
            <div class="grid-2">
                @forelse($projects as $project)
                    <div class="project-row fade-in">
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
                            @if($project->client)
                                <p><strong>Client:</strong> {{ $project->client }}</p>
                            @endif
                            @if($project->completed_at)
                                <p><strong>Completed:</strong> {{ $project->completed_at->format('F Y') }}</p>
                            @endif
                            <a href="{{ route('contact') }}" class="btn btn-outline">Inquire</a>
                        </div>
                    </div>
                @empty
                    <p>No projects yet.</p>
                @endforelse
            </div>
            {{ $projects->links() }}
        </div>
    </section>
@endsection