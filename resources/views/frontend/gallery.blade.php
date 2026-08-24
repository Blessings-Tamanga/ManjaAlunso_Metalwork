@props(['items'])

<section class="section bg-light">
    <div class="container">
        <x-section-heading tag="Project Gallery" title="Our Craft in Detail" />

        <div class="gallery-grid fade-in">
            @foreach ($items as $item)
                <x-gallery-item :image="$item['image']" :alt="$item['alt']" :label="$item['label']" />
            @endforeach
        </div>
    </div>
</section>
