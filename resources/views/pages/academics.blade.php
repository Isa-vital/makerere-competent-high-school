@extends('layouts.app')

@section('title', 'Academics | ' . school_detail('name'))
@section('description', 'Explore O-Level subjects, A-Level combinations and academic life at Makerere Competent High School.')

@section('content')
<section class="interior-hero interior-hero--type" aria-labelledby="academics-title"><div class="school-shell"><p class="school-kicker">Academics at Makerere Competent</p><h1 id="academics-title">{{ page_content('academics_hero_title') }} <span>{{ page_content('academics_hero_highlight') }}</span></h1><p>{{ page_content('academics_hero_intro') }}</p><div class="interior-hero__links"><a class="school-button school-button--gold" href="#o-level">O-Level subjects <i class="fas fa-arrow-down" aria-hidden="true"></i></a><a class="school-button school-button--outline" href="#a-level">A-Level combinations <i class="fas fa-arrow-down" aria-hidden="true"></i></a></div></div></section>

<section class="school-section academic-overview" aria-labelledby="academic-overview-title"><div class="school-shell academic-overview__layout"><div><p class="school-kicker">The journey</p><h2 id="academic-overview-title" class="school-heading">{{ page_content('academics_overview_title') }}</h2></div><p class="school-lead">{{ page_content('academics_overview_body') }}</p></div></section>

<section class="school-section academic-level" id="o-level" aria-labelledby="o-level-title"><div class="school-shell"><div class="academic-level__heading"><span>01 / Senior 1–4</span><h2 id="o-level-title">O-Level</h2><p>{{ page_content('academics_o_level_intro') }}</p></div>
    @if ($oLevelSubjects->isNotEmpty())
        <div class="academic-subjects">@foreach ($oLevelSubjects->groupBy('category') as $category => $subjects)<div class="academic-subjects__group"><h3>{{ ucfirst($category ?: 'Subjects') }}</h3><ul>@foreach ($subjects as $subject)<li>{{ $subject->name }}</li>@endforeach</ul></div>@endforeach</div>
    @else<p class="academic-empty">Subject information will be added here soon. Please contact the school for current offerings.</p>@endif
</div></section>

<section class="school-section academic-level academic-level--navy" id="a-level" aria-labelledby="a-level-title"><div class="school-shell"><div class="academic-level__heading"><span>02 / Senior 5–6</span><h2 id="a-level-title">A-Level</h2><p>{{ page_content('academics_a_level_intro') }}</p></div>
    @if ($combinations->isNotEmpty())<div class="academic-combinations">@foreach ($combinations as $faculty => $items)<div><h3>{{ ucfirst($faculty ?: 'Combinations') }}</h3><ul>@foreach ($items as $item)<li><strong>{{ $item->code }}</strong><span>{{ $item->subjects }}</span></li>@endforeach</ul></div>@endforeach</div>
    @else<p class="academic-empty">Current combinations will be published here soon. Please contact the school for guidance.</p>@endif
</div></section>

@if ($academicResults->isNotEmpty())
<section class="school-section academic-results" aria-labelledby="academic-results-title"><div class="school-shell"><p class="school-kicker">Academic record</p><h2 id="academic-results-title" class="school-heading">Results and achievements.</h2><div class="academic-results__grid">@foreach ($academicResults as $result)<article>@if ($result->value)<strong>{{ $result->value }}</strong>@endif<h3>{{ $result->title }}</h3><p>{{ $result->description }}</p></article>@endforeach</div></div></section>
@endif

@if ($facilities->isNotEmpty())
<section class="school-section academic-facilities" aria-labelledby="academic-facilities-title"><div class="school-shell"><p class="school-kicker">Learning spaces</p><h2 id="academic-facilities-title" class="school-heading">On campus.</h2><div class="about-facilities__grid">@foreach ($facilities as $facility)<article>@if ($facility->image)<img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}" loading="lazy">@endif<div><h3>{{ $facility->name }}</h3><p>{{ $facility->description }}</p></div></article>@endforeach</div></div></section>
@endif

@if ($academicNews->isNotEmpty())
<section class="school-section home-news" aria-labelledby="academic-news-title"><div class="school-shell"><div class="school-section-head"><div><p class="school-kicker">From our school</p><h2 id="academic-news-title" class="school-heading">Academic updates.</h2></div><a class="school-text-link" href="{{ route('news.index') }}">All news <span aria-hidden="true">↗</span></a></div><div class="home-news__grid">@foreach ($academicNews as $item)<article class="school-news-card"><a class="school-news-card__media" href="{{ route('news.show', $item) }}" aria-label="Read {{ $item->title }}">@if(school_image_url($item->featured_image))<img src="{{ school_image_url($item->featured_image) }}" alt="" loading="lazy">@else<span>Academic<br>update</span>@endif</a><div class="school-news-card__body"><time datetime="{{ optional($item->published_at ?? $item->created_at)->format('Y-m-d') }}">{{ optional($item->published_at ?? $item->created_at)->format('j F Y') }}</time><h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3><p>{{ \Illuminate\Support\Str::limit($item->excerpt ?: strip_tags($item->content), 110) }}</p><a class="school-text-link" href="{{ route('news.show', $item) }}">Read story <span aria-hidden="true">↗</span></a></div></article>@endforeach</div></div></section>
@endif

<section class="home-admissions" aria-labelledby="academics-cta-title"><div class="school-shell home-admissions__inner"><div><p class="school-kicker">Your next step</p><h2 id="academics-cta-title">{{ page_content('academics_callout_title') }}</h2><p>{{ page_content('academics_callout_body') }}</p></div><a class="school-button school-button--gold" href="{{ route('admissions') }}">Explore admissions <i class="fas fa-arrow-right" aria-hidden="true"></i></a></div></section>
@endsection
