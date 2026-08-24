@props(['image', 'alt', 'tag', 'title', 'points'])

<section class="section bg-light">
    <div class="container">
        <div class="grid-2">
            <div class="fade-in">
                <span class="section-tag">{{ $tag }}</span>
                <h2>{{ $title }}</h2>
                <div style="display:grid; gap:1rem; margin-top:1.5rem;">
                    @foreach ($points as $point)
                        <x-feature-point :icon="$point['icon']" :title="$point['title']" :description="$point['description']" />
                    @endforeach
                </div>
            </div>
            <div class="img-hover fade-in">
                <img src="{{ $image }}" alt="{{ $alt }}" loading="lazy">
            </div>
        </div>
    </div>
</section>
