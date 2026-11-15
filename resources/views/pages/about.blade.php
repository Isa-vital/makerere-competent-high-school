@extends('layouts.app')

@section('title', 'About Us | ' . school_detail('name'))
@section('description', 'Learn about Makerere Competent High School, its mission, history, leadership and campus in Buhimba, Kikuube.')

@section('content')
<section class="interior-hero interior-hero--split" aria-labelledby="about-title"><div class="school-shell interior-hero__grid"><div><p class="school-kicker">About our school</p><h1 id="about-title">Rooted in learning. <span>Looking ahead.</span></h1><p>Get to know Makerere Competent High School and the people and purpose behind it.</p><a class="school-button school-button--gold" href="{{ route('admissions') }}">Explore admissions <i class="fas fa-arrow-right" aria-hidden="true"></i></a></div><div class="interior-hero__photo"><img src="{{ asset('assets/images/gate.jpeg') }}" alt="Makerere Competent High School entrance" width="1080" height="810"></div></div></section>

<section class="school-section about-story" aria-labelledby="about-story-title"><div class="school-shell about-story__layout"><div><p class="school-kicker">Our story</p><h2 id="about-story-title" class="school-heading">A school with a clear purpose.</h2></div><div><p class="school-lead">Makerere Competent High School is a mixed day and boarding secondary school offering O-Level and A-Level education.</p><p>Located in Buhimba, Kikuube, the school welcomes students and families to a learning community centered on education for a better future.</p><a class="school-text-link" href="{{ route('academics') }}">Explore academic pathways <span aria-hidden="true">↗</span></a></div></div></section>

<section class="school-section about-beliefs" aria-labelledby="about-beliefs-title"><div class="school-shell"><p class="school-kicker">What guides us</p><h2 id="about-beliefs-title" class="school-heading">Our direction.</h2><div class="about-beliefs__grid"><article><span>01 / Mission</span><h3>What we do</h3><p>{{ setting('school_mission', 'To provide quality education that prepares students for their next steps.') }}</p></article><article><span>02 / Vision</span><h3>What we work towards</h3><p>{{ setting('school_vision', 'A learning community where students can grow and pursue opportunity.') }}</p></article><article><span>03 / Motto</span><h3>What we believe</h3><p>{{ setting('school_motto', 'Education for a better future') }}</p></article></div></div></section>

@if ($milestones->isNotEmpty())
<section class="school-section about-history" aria-labelledby="about-history-title"><div class="school-shell about-history__layout"><div><p class="school-kicker">Our history</p><h2 id="about-history-title" class="school-heading">Moments that shaped us.</h2></div><ol class="about-history__timeline">@foreach ($milestones as $milestone)<li><span>{{ $milestone->year }}</span><div><h3>{{ $milestone->title }}</h3><p>{{ $milestone->description }}</p></div></li>@endforeach</ol></div></section>
@endif

@if ($leadership->isNotEmpty())
<section class="school-section about-people" aria-labelledby="about-people-title"><div class="school-shell"><div class="school-section-head"><div><p class="school-kicker">School leadership</p><h2 id="about-people-title" class="school-heading">Meet the people guiding our school.</h2></div></div><div class="about-people__grid">@foreach ($leadership as $leader)<article class="about-person">@if ($leader->photo)<img src="{{ asset('storage/' . $leader->photo) }}" alt="Portrait of {{ $leader->name }}" loading="lazy">@else<div class="about-person__initial" aria-hidden="true">{{ mb_substr($leader->name, 0, 1) }}</div>@endif<div><p>{{ $leader->role }}</p><h3>{{ $leader->name }}</h3>@if ($leader->bio)<span>{{ $leader->bio }}</span>@endif</div></article>@endforeach</div></div></section>
@endif

@if ($achievements->isNotEmpty())
<section class="school-section about-achievements" aria-labelledby="about-achievements-title"><div class="school-shell"><p class="school-kicker">From our records</p><h2 id="about-achievements-title" class="school-heading">School achievements.</h2><div class="about-achievements__grid">@foreach ($achievements as $item)<article><span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $item->title }}</h3>@if ($item->value)<strong>{{ $item->value }}</strong>@endif<p>{{ $item->description }}</p></article>@endforeach</div></div></section>
@endif

@if ($facilities->isNotEmpty())
<section class="school-section about-facilities" id="facilities" aria-labelledby="about-facilities-title"><div class="school-shell"><p class="school-kicker">Our campus</p><h2 id="about-facilities-title" class="school-heading">Spaces for school life.</h2><div class="about-facilities__grid">@foreach ($facilities as $facility)<article>@if ($facility->image)<img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}" loading="lazy">@endif<div><h3>{{ $facility->name }}</h3><p>{{ $facility->description }}</p></div></article>@endforeach</div></div></section>
@endif

<section class="home-admissions" aria-labelledby="about-cta-title"><div class="school-shell home-admissions__inner"><div><p class="school-kicker">Join our school</p><h2 id="about-cta-title">Find your place here.</h2><p>See how to apply or get in touch with the school.</p></div><a class="school-button school-button--gold" href="{{ route('admissions') }}">Admissions <i class="fas fa-arrow-right" aria-hidden="true"></i></a></div></section>
@endsection
