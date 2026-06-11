@props([
    'title',
    'description',
    'eyebrow' => null,
    'wrapperClass' => 'section-heading',
])

<div class="{{ $wrapperClass }}">
    @if ($eyebrow)
        <span>{{ $eyebrow }}</span>
    @endif

    <h1>{{ $title }}</h1>
    <p>{{ $description }}</p>
</div>
