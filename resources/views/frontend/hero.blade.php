@props(['heading', 'subheading'])

<section class="hero" id="hero">
    <div class="container hero-content">
        <h1>{!! $heading !!}</h1>
        <p>{{ $subheading }}</p>
        <div class="hero-buttons">
            <a href="#contact" class="btn btn-primary">Learn More</a>
            <a href="#projects" class="btn btn-outline" style="color: white;">View Projects</a>
        </div>
    </div>
</section>
