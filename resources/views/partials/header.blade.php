@php
    $siteName = school_detail('name');
    $sitePhone = school_detail('phone');
    $siteEmail = school_detail('email');
    $siteAddress = school_detail('address');
    $social = school_social_links();
    $current = request()->route()?->getName();
    $navItems = [
        ['home', 'Home'], ['about', 'About Us'], ['academics', 'Academics'],
        ['admissions', 'Admissions'], ['gallery', 'Gallery'],
        ['news.index', 'News & Events'], ['alumni', 'MACOSA'], ['contact', 'Contact'],
    ];
@endphp

<a class="skip-link" href="#main-content">Skip to content</a>
<header class="site-header redesigned-header" id="site-top">
    <div class="school-utility">
        <div class="school-shell school-utility__inner">
            <span class="school-utility__location"><i class="fas fa-location-dot" aria-hidden="true"></i> {{ $siteAddress }}</span>
            <div class="school-utility__links">
                <a href="tel:{{ preg_replace('/[^+0-9]/', '', $sitePhone) }}"><i class="fas fa-phone" aria-hidden="true"></i> {{ $sitePhone }}</a>
                <a href="mailto:{{ $siteEmail }}"><i class="fas fa-envelope" aria-hidden="true"></i> {{ $siteEmail }}</a>
                @if (!empty($social['facebook']))
                    <a class="school-utility__social" href="{{ $social['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                @endif
                @if (!empty($social['instagram']))
                    <a class="school-utility__social" href="{{ $social['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                @endif
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-xl school-nav" aria-label="Main navigation">
        <div class="school-shell school-nav__inner">
            <a class="school-brand" href="{{ route('home') }}" aria-label="{{ $siteName }} home">
                <img src="{{ page_image('brand_logo_image') }}" alt="" width="58" height="55">
                <span class="school-brand__text"><strong>Makerere Competent</strong><span>High School</span></span>
            </a>
            <button class="navbar-toggler school-nav__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle main menu">
                <span class="school-nav__toggle-lines" aria-hidden="true"><span></span><span></span><span></span></span>
            </button>
            <div class="collapse navbar-collapse school-nav__collapse" id="navbarNav">
                <ul class="navbar-nav school-nav__links">
                    @foreach ($navItems as [$routeName, $label])
                        @php $active = $routeName === 'news.index' ? str_starts_with((string) $current, 'news.') : $current === $routeName; @endphp
                        <li class="nav-item"><a class="nav-link school-nav__link {{ $active ? 'active' : '' }}" href="{{ route($routeName) }}" @if($active) aria-current="page" @endif>{{ $label }}</a></li>
                    @endforeach
                </ul>
                <a href="{{ route('admissions') }}" class="school-button school-button--gold school-nav__apply">Apply Now <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    </nav>
</header>
