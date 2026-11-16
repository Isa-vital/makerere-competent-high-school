@extends('layouts.app')

@section('title', 'Gallery | ' . school_detail('name'))
@section('description', 'Browse photographs published by Makerere Competent High School.')

@section('content')
<section class="interior-hero interior-hero--type gallery-hero" aria-labelledby="gallery-title"><div class="school-shell"><p class="school-kicker">Our school in pictures</p><h1 id="gallery-title">{{ page_content('gallery_hero_title') }} <span>{{ page_content('gallery_hero_highlight') }}</span></h1><p>{{ page_content('gallery_hero_intro') }}</p></div></section>
<section class="school-section gallery-page" aria-labelledby="gallery-collection-title"><div class="school-shell"><div class="school-section-head"><div><p class="school-kicker">The collection</p><h2 id="gallery-collection-title" class="school-heading">Explore the gallery.</h2></div></div>
    @if ($categories->isNotEmpty())<nav class="gallery-page__filters" aria-label="Gallery categories"><a href="{{ route('gallery') }}" @if(!$category) aria-current="page" @endif>All photos</a>@foreach ($categories as $option)@if($option)<a href="{{ route('gallery', ['category' => $option]) }}" @if($category === $option) aria-current="page" @endif>{{ ucfirst($option) }}</a>@endif @endforeach</nav>@endif
    @if ($galleryImages->isNotEmpty())<div class="gallery-page__grid">@foreach ($galleryImages as $image)@php $src = school_image_url($image->image_path); @endphp<figure class="gallery-page__item"><button type="button" class="gallery-page__open" data-gallery-image="{{ $src }}" data-gallery-title="{{ $image->title }}" aria-label="View {{ $image->title }} larger"><img src="{{ $src }}" alt="{{ $image->alt_text ?: $image->title }}" loading="lazy"><span aria-hidden="true">View image ↗</span></button><figcaption><strong>{{ $image->title }}</strong>@if($image->category)<span>{{ ucfirst($image->category) }}</span>@endif</figcaption></figure>@endforeach</div>
    @else<div class="gallery-page__empty"><p>{{ $category ? 'There are no photographs in this category yet.' : 'Photographs will appear here when the school publishes them.' }}</p>@if($category)<a class="school-text-link" href="{{ route('gallery') }}">View all photos <span aria-hidden="true">↗</span></a>@endif</div>@endif
</div></section>
<dialog class="gallery-dialog" id="gallery-dialog" aria-label="Photograph preview"><button type="button" class="gallery-dialog__close" aria-label="Close preview">×</button><img src="" alt=""><p></p></dialog>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dialog = document.getElementById('gallery-dialog');
    if (!dialog || !dialog.showModal) return;
    const preview = dialog.querySelector('img');
    const caption = dialog.querySelector('p');
    const close = dialog.querySelector('.gallery-dialog__close');
    document.querySelectorAll('[data-gallery-image]').forEach(button => {
        button.addEventListener('click', () => {
            preview.src = button.dataset.galleryImage;
            preview.alt = button.querySelector('img').alt;
            caption.textContent = button.dataset.galleryTitle;
            dialog.showModal();
            close.focus();
        });
    });
    close.addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
    dialog.addEventListener('close', () => { preview.removeAttribute('src'); });
});
</script>
@endpush
