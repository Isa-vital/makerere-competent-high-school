@extends('layouts.app')

@section('title', $news->title . ' | ' . school_detail('name'))
@section('description', $news->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($news->content), 160))

@section('content')
<article class="news-article"><header class="news-article__header"><div class="school-shell"><a class="news-article__back" href="{{ route('news.index') }}">← All news &amp; events</a><p class="school-kicker">{{ $news->category ? ucfirst($news->category) : 'School news' }}</p><h1>{{ $news->title }}</h1><div class="news-article__meta"><time datetime="{{ optional($news->published_at ?? $news->created_at)->format('Y-m-d') }}">{{ optional($news->published_at ?? $news->created_at)->format('j F Y') }}</time>@if($news->author)<span>By {{ $news->author->name }}</span>@endif</div></div></header>
    @if(school_image_url($news->featured_image))<div class="school-shell news-article__image"><img src="{{ school_image_url($news->featured_image) }}" alt="{{ $news->title }}" loading="eager"></div>@endif
    <div class="school-shell news-article__body"><div class="news-article__content">{!! $news->content !!}</div><a class="school-text-link" href="{{ route('news.index') }}">More school news <span aria-hidden="true">↗</span></a></div>
</article>
@endsection
