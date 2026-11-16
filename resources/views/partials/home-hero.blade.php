<section class="school-hero" aria-labelledby="home-hero-title">
    <img class="school-hero__image" src="{{ page_image('home_hero_image') }}" alt="Makerere Competent High School" width="1080" height="810" fetchpriority="high">
    <div class="school-hero__shade" aria-hidden="true"></div>
    <div class="school-shell school-hero__content">
        <div class="school-hero__copy">
            <p class="school-eyebrow"><span></span> {{ page_content('home_hero_eyebrow') }}</p>
            <h1 id="home-hero-title">{{ page_content('home_hero_title') }} <em>{{ page_content('home_hero_highlight') }}</em></h1>
            <p class="school-hero__intro">{{ page_content('home_hero_intro') }}</p>
            <div class="school-hero__actions">
                <a class="school-button school-button--gold" href="{{ route('admissions') }}">Apply Now <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                <a class="school-button school-button--outline" href="{{ route('academics') }}">Explore Academics <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
    <div class="school-hero__caption"><span>01 / 01</span> <span>Makerere Competent High School · Buhimba</span></div>
</section>
