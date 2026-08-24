@props(['stats'])

<section class="section bg-light">
    <div class="container">
        <div class="grid-4">
            @foreach ($stats as $stat)
                <x-stat-item :target="$stat['target']" :label="$stat['label']" />
            @endforeach
        </div>
    </div>
</section>
