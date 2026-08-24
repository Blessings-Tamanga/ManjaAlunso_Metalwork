@props(['testimonials'])

<section class="section">
    <div class="container">
        <x-section-heading tag="Testimonials" title="Client Experiences" />

        <div class="grid-3">
            @foreach ($testimonials as $testimonial)
                <x-testimonial-card
                    :rating="$testimonial['rating']"
                    :quote="$testimonial['quote']"
                    :name="$testimonial['name']"
                    :title="$testimonial['title']"
                />
            @endforeach
        </div>
    </div>
</section>
