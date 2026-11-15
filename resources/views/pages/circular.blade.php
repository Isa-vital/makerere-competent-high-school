@extends('layouts.app')

@section('title', 'School Circular | ' . school_detail('name'))
@section('description', 'Contact Makerere Competent High School for the latest school circulars and term dates.')

@section('content')
<section class="interior-hero interior-hero--type circular-hero" aria-labelledby="circular-title"><div class="school-shell"><p class="school-kicker">Information for families</p><h1 id="circular-title">School <span>circular.</span></h1><p>Contact the school for the latest circulars, term dates and other notices.</p></div></section>

<section class="school-section circular-page" aria-labelledby="circular-info-title"><div class="school-shell"><div class="circular-page__intro"><div><p class="school-kicker">Current information</p><h2 id="circular-info-title" class="school-heading">Stay informed.</h2></div><p>School notices and term dates may change. Please contact the school for the latest circular.</p></div><div class="circular-page__note"><h3>Request the latest circular</h3><p>Our team can help you find the current notice and answer your questions.</p><a class="school-button school-button--navy" href="{{ route('contact') }}">Contact the school <i class="fas fa-arrow-right" aria-hidden="true"></i></a></div></div></section>
@endsection
