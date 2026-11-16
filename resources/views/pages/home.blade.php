@extends('layouts.app')

@section('title', 'Home | ' . school_detail('name'))
@section('description', setting('site_description', 'Discover O-Level and A-Level education at Makerere Competent High School in Buhimba, Kikuube, Uganda.'))

@section('content')
@include('partials.home-hero')

<section class="home-intro school-section" id="about" aria-labelledby="home-intro-title">
    <div class="school-shell home-intro__grid">
        <div class="home-intro__text">
            <p class="school-kicker">A place to learn and grow</p>
            <h2 id="home-intro-title" class="school-heading">{{ page_content('home_intro_title') }} <span>{{ page_content('home_intro_highlight') }}</span> {{ page_content('home_intro_end') }}</h2>
            <p class="school-lead">{{ page_content('home_intro_lead') }}</p>
            <p>{{ page_content('home_intro_body') }}</p>
            <a class="school-text-link" href="{{ route('about') }}">Discover our school <span aria-hidden="true">↗</span></a>
        </div>
        <figure class="home-intro__figure">
            <img src="{{ page_image('home_intro_image') }}" alt="Makerere Competent High School entrance in Buhimba" width="1080" height="810" loading="lazy">
            <figcaption>Our school · Buhimba, Kikuube</figcaption>
        </figure>
    </div>
</section>

<section class="home-pathways school-section" id="pathways" aria-labelledby="home-pathways-title">
    <div class="school-shell">
        <div class="school-section-head">
            <div><p class="school-kicker">Academic pathways</p><h2 id="home-pathways-title" class="school-heading">{{ page_content('home_pathways_title') }}</h2></div>
            <p>{{ page_content('home_pathways_intro') }}</p>
        </div>
        <div class="home-pathways__grid">
            <a class="home-pathway" href="{{ route('academics') }}#o-level">
                <span class="home-pathway__number">01 / Senior 1–4</span>
                <span class="home-pathway__content"><strong>O-Level</strong><span>{{ page_content('home_o_level_intro') }}</span></span>
                <span class="home-pathway__arrow" aria-hidden="true">↗</span>
            </a>
            <a class="home-pathway home-pathway--blue" href="{{ route('academics') }}#a-level">
                <span class="home-pathway__number">02 / Senior 5–6</span>
                <span class="home-pathway__content"><strong>A-Level</strong><span>{{ page_content('home_a_level_intro') }}</span></span>
                <span class="home-pathway__arrow" aria-hidden="true">↗</span>
            </a>
        </div>
    </div>
</section>

<section class="home-principles school-section" aria-labelledby="home-principles-title">
    <div class="school-shell home-principles__layout">
        <div><p class="school-kicker">The Makerere Competent approach</p><h2 id="home-principles-title" class="school-heading">{{ page_content('home_principles_title') }} <span>{{ page_content('home_principles_highlight') }}</span></h2></div>
        <div class="home-principles__list">
            <div><span>01</span><div><h3>Learning</h3><p>{{ page_content('home_learning_body') }}</p></div></div>
            <div><span>02</span><div><h3>Character</h3><p>{{ page_content('home_character_body') }}</p></div></div>
            <div><span>03</span><div><h3>Community</h3><p>{{ page_content('home_community_body') }}</p></div></div>
        </div>
    </div>
</section>

<section class="home-life school-section" id="gallery" aria-labelledby="home-life-title">
    <div class="school-shell home-life__grid">
        <div class="home-life__visual"><img src="{{ page_image('home_life_image') }}" alt="View of the school entrance and grounds" width="1080" height="810" loading="lazy"><span>Our place in Buhimba</span></div>
        <div class="home-life__story"><p class="school-kicker">School life</p><h2 id="home-life-title" class="school-heading">{{ page_content('home_life_title') }}</h2><p>{{ page_content('home_life_body') }}</p><a class="school-text-link" href="{{ route('gallery') }}">Visit the gallery <span aria-hidden="true">↗</span></a></div>
    </div>
</section>

<section class="home-news school-section" id="news" aria-labelledby="home-news-title">
    <div class="school-shell">
        <div class="school-section-head"><div><p class="school-kicker">From our school</p><h2 id="home-news-title" class="school-heading">News &amp; events.</h2></div><a class="school-text-link" href="{{ route('news.index') }}">All updates <span aria-hidden="true">↗</span></a></div>
        @if ($latestNews->isNotEmpty())
            <div class="home-news__grid">
                @foreach ($latestNews as $item)
                    <article class="school-news-card">
                        <a href="{{ route('news.show', $item) }}" class="school-news-card__media" aria-label="Read {{ $item->title }}">
                            @if (school_image_url($item->featured_image))
                                <img src="{{ school_image_url($item->featured_image) }}" alt="" loading="lazy">
                            @else
                                <span>Makerere<br>Competent</span>
                            @endif
                        </a>
                        <div class="school-news-card__body"><time datetime="{{ optional($item->published_at ?? $item->created_at)->format('Y-m-d') }}">{{ optional($item->published_at ?? $item->created_at)->format('j F Y') }}</time><h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3><p>{{ \Illuminate\Support\Str::limit($item->excerpt ?: strip_tags($item->content), 115) }}</p><a class="school-text-link" href="{{ route('news.show', $item) }}">Read story <span aria-hidden="true">↗</span></a></div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="home-news__empty"><p>School updates will appear here when they are published.</p><a class="school-text-link" href="{{ route('news.index') }}">Visit news &amp; events <span aria-hidden="true">↗</span></a></div>
        @endif
    </div>
</section>

<section class="home-admissions" aria-labelledby="home-admissions-title"><div class="school-shell home-admissions__inner"><div><p class="school-kicker">Admissions</p><h2 id="home-admissions-title">{{ page_content('home_admissions_title') }}</h2><p>{{ page_content('home_admissions_body') }}</p></div><a href="{{ route('admissions') }}" class="school-button school-button--gold">Explore admissions <i class="fas fa-arrow-right" aria-hidden="true"></i></a></div></section>

<section class="home-contact school-section" id="quick-contact" aria-labelledby="home-contact-title"><div class="school-shell home-contact__grid"><div><p class="school-kicker">Talk to us</p><h2 id="home-contact-title" class="school-heading">{{ page_content('home_contact_title') }}</h2><p>{{ page_content('home_contact_body') }}</p><div class="home-contact__details"><p><strong>Visit</strong><br>{{ school_detail('address') }}</p><p><strong>Call</strong><br><a href="tel:{{ preg_replace('/[^+0-9]/', '', school_detail('phone')) }}">{{ school_detail('phone') }}</a></p><p><strong>Email</strong><br><a href="mailto:{{ school_detail('email') }}">{{ school_detail('email') }}</a></p></div></div><div class="school-form-panel">
    @if (session('status'))<p class="school-form-notice" role="status">{{ session('status') }}</p>@endif
    @if ($errors->any())<div class="school-form-errors" role="alert"><strong>Please correct the following:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form id="quick-contact-form" method="POST" action="{{ route('contact.store') }}">@csrf
        <div class="school-form-row"><div><label for="home-name">Full name <span>*</span></label><input id="home-name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required></div><div><label for="home-email">Email address <span>*</span></label><input id="home-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required></div></div>
        <div class="school-form-row"><div><label for="home-phone">Phone number</label><input id="home-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel"></div><div><label for="home-subject">Subject <span>*</span></label><input id="home-subject" name="subject" type="text" value="{{ old('subject') }}" required></div></div>
        <label for="home-message">Message <span>*</span></label><textarea id="home-message" name="message" rows="5" required>{{ old('message') }}</textarea>
        <button class="school-button school-button--navy" type="submit">Send message <i class="fas fa-arrow-right" aria-hidden="true"></i></button>
    </form>
</div></div></section>
@endsection
