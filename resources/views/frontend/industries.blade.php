@props(['industries'])

<section class="section">
    <div class="container">
        <x-section-heading tag="Industries Served" title="Trusted Across Sectors" />

        <div class="grid-4">
            @foreach ($industries as $industry)
                <x-industry-card :icon="$industry['icon']" :title="$industry['title']" :description="$industry['description']" />
            @endforeach
        </div>
    </div>
</section>
