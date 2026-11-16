@extends('layouts.app')

@section('title', 'School Circular | ' . school_detail('name'))
@section('description', 'Contact Makerere Competent High School for the latest school circulars and term dates.')

@section('content')
<section class="interior-hero interior-hero--type circular-hero" aria-labelledby="circular-title"><div class="school-shell"><p class="school-kicker">Information for families</p><h1 id="circular-title">{{ page_content('circular_hero_title') }} <span>{{ page_content('circular_hero_highlight') }}</span></h1><p>{{ page_content('circular_hero_intro') }}</p></div></section>

<section class="school-section circular-page" aria-labelledby="circular-info-title"><div class="school-shell"><div class="circular-page__intro"><div><p class="school-kicker">Current information</p><h2 id="circular-info-title" class="school-heading">{{ page_content('circular_intro_title') }}</h2></div><p>{{ page_content('circular_intro_body') }}</p></div><div class="circular-page__note"><h3>{{ page_content('circular_notice_title') }}</h3><p>{{ page_content('circular_notice_body') }}</p><a class="school-button school-button--navy" href="{{ route('contact') }}">Contact the school <i class="fas fa-arrow-right" aria-hidden="true"></i></a></div></div></section>
@endsection
