@php
    $siteName = school_detail('name');
    $sitePhone = school_detail('phone');
    $siteEmail = school_detail('email');
    $social = school_social_links();
@endphp
<footer class="school-footer">
    <div class="school-shell">
        <div class="school-footer__main">
            <div class="school-footer__identity"><a href="{{ route('home') }}" class="school-footer__brand"><img src="{{ asset('assets/images/competentlogo.jpeg') }}" alt="" width="64" height="61"><span>{{ $siteName }}</span></a><p>{{ setting('school_motto', 'Education for a better future') }}</p><span class="school-footer__place">{{ school_detail('address') }}</span></div>
            <div class="school-footer__column"><h2>Explore</h2><a href="{{ route('about') }}">About us</a><a href="{{ route('academics') }}">Academics</a><a href="{{ route('admissions') }}">Admissions</a><a href="{{ route('gallery') }}">Gallery</a><a href="{{ route('news.index') }}">News &amp; events</a></div>
            <div class="school-footer__column"><h2>Community</h2><a href="{{ route('alumni') }}">MACOSA</a><a href="{{ route('circular') }}">School circular</a><a href="{{ route('contact') }}">Contact us</a><div class="school-footer__social">@if (!empty($social['facebook']))<a href="{{ $social['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>@endif @if (!empty($social['instagram']))<a href="{{ $social['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>@endif</div></div>
            <div class="school-footer__column school-footer__contact"><h2>Get in touch</h2><a href="tel:{{ preg_replace('/[^+0-9]/', '', $sitePhone) }}">{{ $sitePhone }}</a><a href="mailto:{{ $siteEmail }}">{{ $siteEmail }}</a><p>Stay up to date with news from the school.</p><form method="POST" action="{{ route('newsletter.subscribe') }}" class="school-footer__form">@csrf<label for="footer-email">Email address</label><div><input id="footer-email" name="email" type="email" autocomplete="email" placeholder="Your email address" required><button type="submit" aria-label="Subscribe to newsletter"><i class="fas fa-arrow-right" aria-hidden="true"></i></button></div></form></div>
        </div>
        <div class="school-footer__bottom"><span>© {{ date('Y') }} {{ $siteName }}. All rights reserved.</span><a href="#site-top">Back to top ↑</a></div>
    </div>
</footer>
