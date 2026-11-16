@extends('layouts.app')

@section('title', 'News & Events | ' . school_detail('name'))
@section('description', 'Published news and updates from Makerere Competent High School.')

@section('content')
<section class="interior-hero interior-hero--type news-hero" aria-labelledby="news-title"><div class="school-shell"><p class="school-kicker">The latest from our school</p><h1 id="news-title">{{ page_content('news_hero_title') }} <span>{{ page_content('news_hero_highlight') }}</span></h1><p>{{ page_content('news_hero_intro') }}</p></div></section>
<section class="school-section home-news news-page" aria-labelledby="news-list-title"><div class="school-shell"><p class="school-kicker">News &amp; events</p><h2 id="news-list-title" class="school-heading">Latest updates.</h2>
    @if ($news->isEmpty())<div class="home-news__empty"><p>There are no published articles yet. Please check back soon.</p></div>
    @else<div class="home-news__grid">@foreach ($news as $item)<article class="school-news-card"><a class="school-news-card__media" href="{{ route('news.show', $item) }}" aria-label="Read {{ $item->title }}">@if(school_image_url($item->featured_image))<img src="{{ school_image_url($item->featured_image) }}" alt="" loading="lazy">@else<span>Makerere<br>Competent</span>@endif</a><div class="school-news-card__body"><time datetime="{{ optional($item->published_at ?? $item->created_at)->format('Y-m-d') }}">{{ optional($item->published_at ?? $item->created_at)->format('j F Y') }}</time><h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3><p>{{ \Illuminate\Support\Str::limit($item->excerpt ?: strip_tags($item->content), 130) }}</p><a class="school-text-link" href="{{ route('news.show', $item) }}">Read story <span aria-hidden="true">↗</span></a></div></article>@endforeach</div><div class="news-page__pagination">{{ $news->links() }}</div>@endif
</div></section>
@endsection
