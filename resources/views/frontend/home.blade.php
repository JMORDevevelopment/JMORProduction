@extends('layouts.app')

@section('content')

    <section class="jm-hero">
        <div class="container">
            <div class="row align-items-center" style="row-gap:44px">
                <div class="col-lg-6">
                    <div class="jm-hero__badge">Since 1997 &middot; New Jersey</div>
                    <h1>Engineering technology to grow your business.</h1>
                    <p class="jm-hero__copy">The JMOR Connection keeps your systems up, secure and current — computer, network and IT support delivered right the first time.</p>

                    <div class="jm-hero__actions">
                        <a href="{{ route('contact') }}" class="jm-btn jm-btn--orange jm-btn--lg"
                            data-conversion="hero_contact">Reach Out Today</a>
                        <a href="{{ route('packages') }}" class="jm-btn jm-btn--outline jm-btn--lg"
                            data-conversion="hero_plans">See service plans</a>
                    </div>

                    @if (!empty($phone))
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="jm-hero__call"
                            data-conversion="hero_call">
                            <i class="fa fa-phone" aria-hidden="true"></i> Call {{ $phone }}
                        </a>
                    @endif

                    <div class="jm-hero__stats">
                        <div><strong>1997</strong><span>Serving New Jersey since</span></div>
                        <div><strong>24/7</strong><span>Remote support</span></div>
                        <div><strong>On-site</strong><span>NJ technicians, quarterly</span></div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="jm-hero__media">
                        <img src="{{ asset('assets/images/home.jfif') }}" alt="JMOR technicians at work" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="jm-partners">
        <div class="container">                <div class="jm-partners__inner">
                    <div class="jm-partners__label">Certifications &amp; partnerships</div>
                    <p class="jm-partners__copy">
                        Ask which certifications and partner programs cover your systems and industry.
                    </p>
                    <a href="{{ route('contact') }}" class="jm-link-arrow jm-partners__cta">
                        Ask about our credentials &rarr;
                    </a>
                </div>
        </div>
    </section>

    <section class="jm-services">
        <div class="container">
            <div class="jm-section-head">
                <div>
                    <div class="jm-eyebrow">What we do</div>
                    <h2>Our Services</h2>
                </div>
                <a href="{{ route('solutions') }}" class="jm-link-arrow">All solutions &rarr;</a>
            </div>

            <div class="row g-4">
                @forelse ($mainServices as $mainService)
                    <div class="col-md-4 col-lg-3 col-sm-6">
                        {{-- The card is not a link: informational headings must not
                             be clickable, so only the CTA and icon link out. --}}
                        <div class="jm-card jm-service-card">
                            <div class="jm-service-card__icon">
                                @if (!empty($mainService['image']))
                                    <img src="{{ asset($mainService['image']) }}" alt="" loading="lazy">
                                @else
                                    <i class="fa fa-cogs" aria-hidden="true"></i>
                                @endif
                            </div>
                            <h3>{{ $mainService['title'] ?? 'Service' }}</h3>
                            @if (!empty($mainService['link']))
                                <a href="{{ route('service.detail', $mainService['link']) }}" class="jm-link-arrow">
                                    Learn more &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No services found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="jm-featured">
        <div class="container">
            <div class="jm-section-head">
                <div>
                    <div class="jm-eyebrow">Featured</div>
                    <h2>Specialist services</h2>
                </div>
                @include('partials.slider_section')
            </div>

            <div class="jm-featured-carousel">
                <div class="jm-featured-track" id="featuredTrack">
                    @forelse ($mainSliders as $mainSlider)
                        <div class="jm-featured-card">
                            <div class="jm-featured-card__img">
                                <img src="{{ url($mainSlider['slider_image'] ?? '') }}" alt="{{ $mainSlider['slider_name'] ?? '' }}">
                            </div>
                            <div class="jm-featured-card__body">
                                <h3>{{ $mainSlider['slider_name'] ?? '' }}</h3>
                                @if (!empty($mainSlider['slider_link']))
                                    <a href="{{ $mainSlider['slider_link'] }}" class="jm-btn jm-btn--blue jm-btn--sm">View details</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:40px;color:var(--jm-text-light);width:100%">No featured services available.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="jm-industries">
        <div class="container">
            <div class="jm-section-head" style="text-align:center;justify-content:center;">
                <div>
                    <div class="jm-eyebrow">Who we serve</div>
                    <h2>Built around your industry</h2>
                    <p>We understand the challenges of a day-to-day service business — and the tech that keeps it moving.</p>
                </div>
            </div>

            <div class="jm-industries__pills">
                <a href="{{ route('attorneys-law-firms') }}" class="jm-industries__pill">Attorneys &amp; Law Firms</a>
                <a href="{{ route('cpa-firms') }}" class="jm-industries__pill">CPA Firms &amp; Accountants</a>
                <a href="{{ route('dentists') }}" class="jm-industries__pill">Dentists</a>
                <a href="{{ route('general-practice-doctors') }}" class="jm-industries__pill">General Practice Doctors</a>
                <a href="{{ route('fast-food-restaurants') }}" class="jm-industries__pill">Fast Food Restaurants</a>
                <a href="{{ route('manufacturers') }}" class="jm-industries__pill">Manufacturers</a>
                <a href="{{ route('office-managers') }}" class="jm-industries__pill">Office Managers</a>
                <a href="{{ route('packages.detail', 'home') }}" class="jm-industries__pill">Homes &amp; families</a>
            </div>
        </div>
    </section>

    <section class="jm-plans" id="jm-plans">
        <div class="container">
            <div class="jm-section-head jm-section-head--light">
                <div>
                    <div class="jm-eyebrow jm-eyebrow--light">Service plans</div>
                    <h2>JMOR EDGE System Services Plans</h2>
                    <p>You take care of your business, JMOR will handle the tech.</p>
                </div>
                <div class="jm-plans__toggle" role="tablist">
                    <button class="jm-plans__toggle-btn active" data-view="cards" role="tab">Plan cards</button>
                    <button class="jm-plans__toggle-btn" data-view="compare" role="tab">Compare all</button>
                </div>
            </div>

            <div class="jm-plans-cards--visible" id="plans-cards">
                <div class="row g-4">
                    @forelse ($homeTabs as $i => $homeTab)
                        @php
                            $tabList = is_array($homeTab['tab_list'])
                                ? $homeTab['tab_list']
                                : json_decode($homeTab['tab_list'] ?? '[]', true);
                            $benefits = is_array($homeTab['benefits'])
                                ? $homeTab['benefits']
                                : json_decode($homeTab['benefits'] ?? '[]', true);
                            $costs = is_array($homeTab['cost'])
                                ? $homeTab['cost']
                                : json_decode($homeTab['cost'] ?? '[]', true);
                            $costLines = array_values(array_filter($costs, fn($c) => !empty($c)));
                            $primaryCost = $costLines[0] ?? '';
                            $extraCosts = array_slice($costLines, 1);
                            $isPopular = $i === 2;
                        @endphp
                        <div class="col-lg-3 col-md-6">
                            <div class="jm-plan-card {{ $isPopular ? 'jm-plan-card--popular' : '' }}">
                                <div class="jm-plan-card__tag">{{ $homeTab['tab_title'] ?? 'Plan' }}</div>

                                <div class="jm-plan-card__body">
                                    <h3 class="jm-plan-card__name">{{ $homeTab['tab_title'] ?? 'Plan' }}</h3>

                                    @if (!empty($primaryCost))
                                        <div class="jm-plan-card__price">
                                            <div class="jm-plan-card__price-amount">{!! \App\Support\RichText::sanitize($primaryCost) !!}</div>
                                            @if (!empty($extraCosts))
                                                <div class="jm-plan-card__price-extra">
                                                    @foreach ($extraCosts as $extra)
                                                        <div class="jm-plan-card__price-line">{!! \App\Support\RichText::sanitize($extra) !!}</div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if (!empty($tabList) && is_array($tabList))
                                        <ul class="jm-plan-card__list">
                                            @foreach ($tabList as $feature)
                                                @if (!empty($feature))
                                                    <li><span class="jm-check">&check;</span><span>{{ $feature }}</span></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif

                                    <a href="{{ route('contact') }}" class="jm-btn jm-btn--outline-blue jm-plan-card__cta">Get this plan</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p style="color:rgba(255,255,255,0.6);">No service plans available.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="jm-plans-compare" id="plans-compare">
                <table>
                    <thead>
                        <tr>
                            <th>Feature</th>
                            @foreach ($homeTabs as $homeTab)
                                <th>{{ $homeTab['tab_title'] ?? 'Plan' }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Latest security &amp; software patches</td>
                            @foreach ($homeTabs as $homeTab)
                                <td><span class="check">&check;</span></td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Proactive remote threat monitoring</td>
                            @foreach ($homeTabs as $homeTab)
                                <td><span class="check">&check;</span></td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Secure remote diagnostics</td>
                            @foreach ($homeTabs as $homeTab)
                                <td><span class="check">&check;</span></td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>24/7 remote support</td>
                            @foreach ($homeTabs as $homeTab)
                                <td><span class="check">&check;</span></td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>On-site technician visits</td>
                            <td><span>Bi-annual, 60 min/PC</span></td>
                            <td><span>Quarterly, 60 min/PC</span></td>
                            <td><span>Quarterly, 60 min/PC + server</span></td>
                            <td><span>Quarterly + annual review, 90 min</span></td>
                        </tr>
                        <tr>
                            <td>Full server support</td>
                            <td><span class="dash">&mdash;</span></td>
                            <td><span class="dash">&mdash;</span></td>
                            <td><span class="check">&check;</span></td>
                            <td><span class="check">&check;</span></td>
                        </tr>
                        <tr>
                            <td>Hosting, MYSQL, up to 100 mailboxes</td>
                            <td><span class="dash">&mdash;</span></td>
                            <td><span class="dash">&mdash;</span></td>
                            <td><span class="dash">&mdash;</span></td>
                            <td><span class="check">&check;</span></td>
                        </tr>
                        <tr>
                            <td>Published pricing</td>
                            @foreach ($homeTabs as $homeTab)
                                @php
                                    $costLines = array_filter(
                                        is_array($homeTab['cost']) ? $homeTab['cost'] : json_decode($homeTab['cost'] ?? '[]', true),
                                        fn ($cost) => trim(strip_tags((string) $cost)) !== ''
                                    );
                                @endphp
                                <td>
                                    @forelse ($costLines as $cost)
                                        <div style="color:#0053a0;font-weight:700">
                                            {!! \App\Support\RichText::sanitize($cost) !!}
                                        </div>
                                    @empty
                                        <span class="dash">&mdash;</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="jm-about">
        <div class="container">
            <div class="row align-items-center" style="row-gap:40px">
                <div class="col-lg-6">
                    <div class="jm-eyebrow">About JMOR</div>
                    <h2>Mission Statement</h2>
                    <p class="jm-about__mission">The JMOR Connection, Inc. is dedicated to providing solutions and services that keep your business up and running smoothly. We will provide your business with the latest and most efficient technology available. We will fix your problem right the first time.</p>
                    <div class="jm-about__blockquote-wrapper">
                        <p class="jm-about__blockquote">With nearly three decades of experience, we care more about helping our clients solve their issues than about whether we are making money. We believe in security, trust, integrity, and professionalism. We understand the challenges of running a day-to-day service business, and we are not afraid to be different or to think outside the box.</p>
                        <p class="jm-about__blockquote">Our clients and our community come first and we want to let them know that we are here today, tomorrow and for your growing needs of the future. We ask all of our clients the same question: are you willing to sacrifice quality and service for price?</p>
                    </div>
                    <div class="jm-about__actions">
                        <a href="{{ route('about') }}" class="jm-btn jm-btn--orange">Why choose JMOR?</a>
                        <a href="{{ route('testimonials') }}" class="jm-btn jm-btn--outline-blue">Read testimonials</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="jm-about__media">
                        <img src="{{ asset('assets/images/motherboard.jpeg') }}" alt="JMOR IT support in New Jersey" class="img-fluid">
                        <div class="jm-about__side-cards">
                            <a href="{{ route('case-studies') }}" class="jm-about__side-card">
                                <div class="jm-about__side-card-title">Case studies</div>
                                <div class="jm-about__side-card-desc">Real NJ businesses, real fixes</div>
                            </a>
                            <a href="{{ route('our-dei-diversiety-equity-inclusion-non-discrimination-policy') }}"
                                class="jm-about__side-card">
                                <div class="jm-about__side-card-title">Our DEI policy</div>
                                <div class="jm-about__side-card-desc">Diversity, equity &amp; inclusion</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="jm-lead" id="request-information">
        <div class="container">
            <div class="row align-items-center" style="row-gap:36px">
                <div class="col-lg-5">
                    <div class="jm-eyebrow">Talk to us</div>
                    <h2>Tell us what is slowing you down.</h2>
                    <p class="jm-lead__copy">
                        Send a few details and a JMOR technician will get back to you with next steps.
                        Prefer to talk it through? Call us and we will pick up.
                    </p>
                    <ul class="jm-lead__points">
                        <li><i class="fa fa-check" aria-hidden="true"></i> Response from a real technician, not a call centre</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> No obligation, no long-term contract</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> Serving New Jersey homes and businesses since 1997</li>
                    </ul>
                    @if (!empty($phone))
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="jm-btn jm-btn--outline-blue"
                            data-conversion="lead_call">
                            <i class="fa fa-phone" aria-hidden="true"></i>&nbsp; {{ $phone }}
                        </a>
                    @endif
                </div>

                <div class="col-lg-7">
                    @if (session('contact_success'))
                        <div class="alert alert-success" role="alert">
                            <strong>Thank you &mdash; your message was sent.</strong><br>
                            {{ session('contact_success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="post" class="jm-lead__form"
                        data-conversion-form="home_lead">
                        @csrf
                        <input type="hidden" name="reason" value="General Inquiry">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="lead-name">Full name</label>
                                <input id="lead-name" type="text" name="name" value="{{ old('name') }}" required maxlength="80">
                            </div>
                            <div class="col-md-6">
                                <label for="lead-email">Email</label>
                                <input id="lead-email" type="email" name="email" value="{{ old('email') }}" required maxlength="50">
                            </div>
                            <div class="col-md-6">
                                <label for="lead-phone">Phone</label>
                                <input id="lead-phone" type="tel" name="phone" value="{{ old('phone') }}" required maxlength="20">
                            </div>
                            <div class="col-md-6">
                                <label for="lead-protection">
                                    Spam check: {{ $randomNumber1 }} + {{ $randomNumber2 }}
                                </label>
                                <input id="lead-protection" type="number" name="protection_question" required>
                            </div>
                            <div class="col-12">
                                <label for="lead-message">How can we help?</label>
                                <textarea id="lead-message" name="message" rows="4" required maxlength="5000">{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="jm-btn jm-btn--orange jm-btn--lg">Send request</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="jm-blog-feed">
        <div class="container">
            <div class="jm-section-head">
                <div>
                    <div class="jm-eyebrow">JMOR blog</div>
                    <h2>Latest from the blog</h2>
                    <p>Practical advice on keeping your technology secure, current and working.</p>
                </div>
                <a href="{{ route('blog') }}" class="jm-btn jm-btn--outline-blue">Explore More &rarr;</a>
            </div>

            <div class="row g-4">
                @forelse ($latestBlogPosts as $post)
                    @php
                        $postUrl = route('blog.detail', \Illuminate\Support\Str::after($post->link, 'blog/'));
                    @endphp
                    <div class="col-md-4">
                        <article class="jm-card jm-blog-card">
                            <a href="{{ $postUrl }}" class="jm-blog-card__img" tabindex="-1" aria-hidden="true">
                                @if (!empty($post->image))
                                    <img src="{{ asset($post->image) }}" alt="" loading="lazy">
                                @endif
                            </a>
                            <div class="jm-blog-card__body">
                                <div class="jm-blog-card__meta">
                                    {{ \Illuminate\Support\Carbon::parse($post->published)->format('M j, Y') }}
                                </div>
                                <h3>{{ $post->name }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->description), 110) }}</p>
                                <a href="{{ $postUrl }}" class="jm-link-arrow">Read article &rarr;</a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>New articles are on the way — visit the blog for the full archive.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="jm-news">
        <div class="container">
            <div class="jm-section-head">
                <div>
                    <div class="jm-eyebrow">Latest</div>
                    <h2>News Headlines and Social Media</h2>
                </div>
                <div style="display:flex;gap:18px">
                    <a href="{{ route('blog') }}" class="jm-link-arrow">Blog &rarr;</a>
                    <a href="https://www.instagram.com/gosocialjmor/" target="_blank" class="jm-link-arrow">Instagram &rarr;</a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="jm-card jm-news__video">
                        <div class="jm-news__video-frame">
                            <iframe src="https://www.youtube.com/embed/VZ3gZACXylc" title="JMOR" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                        <div class="jm-news__video-body">
                            <p>At JMOR we are not going to start selling you shoes; yes, are clients may have feet but we are A Technology Company. Our firm is not one to jump on bandwagons; we will continue to do what we have been doing for years: professional Computer, Network, IT Support and Repair Services delivered right the first time.</p>
                            <a href="{{ route('blog') }}" class="jm-link-arrow">Read more &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="jm-card jm-news__insta">
                        <div class="jm-news__insta-head">
                            <img src="{{ asset('assets/images/insta.png') }}" alt="" class="jm-news__insta-avatar">
                            <span>@gosocialjmor</span>
                        </div>
                        <p class="jm-news__insta-copy">
                            See the latest posts, tech tips and behind-the-scenes updates on our Instagram profile.
                        </p>
                        <a href="https://www.instagram.com/gosocialjmor/" target="_blank" rel="noopener"
                            class="jm-btn jm-btn--navy jm-news__insta-cta">View Instagram profile</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="jm-cta-banner">
        <div class="container">
            <div class="jm-cta-banner__inner">
                <div>
                    <h2>Ready to stop the insanity?</h2>
                    <p class="jm-cta-banner__sub">Talk to a JMOR technician about your systems today.</p>
                </div>
                <div class="jm-cta-banner__actions">
                    @if (!empty($phone))
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="jm-cta-banner__phone"
                            data-conversion="banner_call">
                            {{ $phone }}
                        </a>
                    @endif
                    <a href="{{ route('contact') }}" class="jm-btn jm-btn--orange"
                        data-conversion="banner_contact">Request information</a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtns = document.querySelectorAll('.jm-plans__toggle-btn');
        const cardsView = document.getElementById('plans-cards');
        const compareView = document.getElementById('plans-compare');

        toggleBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                toggleBtns.forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                if (this.dataset.view === 'compare') {
                    cardsView.className = 'jm-plans-cards--hidden';
                    compareView.className = 'jm-plans-compare jm-plans-compare--visible';
                } else {
                    cardsView.className = 'jm-plans-cards--visible';
                    compareView.className = 'jm-plans-compare';
                }
            });
        });

        const track = document.getElementById('featuredTrack');
        if (track) {
            const cards = track.querySelectorAll('.jm-featured-card');
            const prevBtn = document.querySelector('[data-slide="prev"]');
            const nextBtn = document.querySelector('[data-slide="next"]');
            let current = 0;

            function getPerView() {
                return window.innerWidth < 640 ? 1 : window.innerWidth < 992 ? 2 : 3;
            }

            function updateTrack() {
                const perView = getPerView();
                const maxSlide = Math.max(0, cards.length - perView);
                const gapTotal = 24 * (perView - 1);
                const basis = `calc((100% - ${gapTotal}px) / ${perView})`;
                cards.forEach(c => { c.style.flex = `0 0 ${basis}`; });
                if (current > maxSlide) current = maxSlide;
                track.style.transform = `translateX(calc(-${current} * (${basis} + 24px)))`;
            }

            if (prevBtn) prevBtn.addEventListener('click', function (e) {
                e.preventDefault();
                current = Math.max(0, current - 1);
                updateTrack();
            });

            if (nextBtn) nextBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const perView = getPerView();
                const maxSlide = Math.max(0, cards.length - perView);
                current = Math.min(maxSlide, current + 1);
                updateTrack();
            });

            updateTrack();

            let resizeTimer;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(updateTrack, 200);
            });
        }

        // Conversion tracking. It only writes to a dataLayer/gtag that already
        // exists, so no analytics dependency is introduced (036).
        function trackConversion(eventName, params) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push(Object.assign({event: eventName}, params || {}));

            if (typeof window.gtag === 'function') {
                window.gtag('event', eventName, params || {});
            }
        }

        document.querySelectorAll('[data-conversion]').forEach(function (el) {
            el.addEventListener('click', function () {
                trackConversion('home_cta_click', {cta: el.dataset.conversion});
            });
        });

        const leadForm = document.querySelector('[data-conversion-form="home_lead"]');
        if (leadForm) {
            leadForm.addEventListener('submit', function () {
                trackConversion('home_lead_submitted', {form: 'home_lead'});
            });
        }

    });
</script>
@endpush
