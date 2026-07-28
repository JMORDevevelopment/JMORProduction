@extends('layouts.app')

@section('content')

    <section class="jm-hero">
        <div class="container">
            <div class="row align-items-center" style="row-gap:44px">
                <div class="col-lg-6">
                    <div class="jm-hero__badge">28+ years &middot; New Jersey</div>
                    <h1>Engineering technology to grow your business.</h1>
                    <p class="jm-hero__copy">The JMOR Connection keeps your systems up, secure and current — computer, network and IT support delivered right the first time.</p>

                    <div class="jm-hero__actions">
                        <a href="{{ url('contact') }}" class="jm-btn jm-btn--orange jm-btn--lg">Reach Out Today</a>
                        <a href="{{ url('packages') }}" class="jm-btn jm-btn--outline jm-btn--lg">See service plans</a>
                    </div>

                    <div class="jm-hero__stats">
                        <div><strong>28+</strong><span>Years in business</span></div>
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
        <div class="container">
            <div class="jm-partners__inner">
                <div class="jm-partners__label">Certified &amp; partnered with</div>
                <div class="jm-partners__grid">
                    <div class="jm-partners__item">Partner badge</div>
                    <div class="jm-partners__item">Partner badge</div>
                    <div class="jm-partners__item">Certification</div>
                    <div class="jm-partners__item">Certification</div>
                    <div class="jm-partners__item">Client logo</div>
                    <div class="jm-partners__item">Client logo</div>
                </div>
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
                <a href="{{ url('solutions') }}" class="jm-link-arrow">All solutions &rarr;</a>
            </div>

            <div class="row g-4">
                @forelse ($mainServices as $mainService)
                    <div class="col-md-4 col-lg-3 col-sm-6">
                        <a href="{{ url('service/' . ($mainService['link'] ?? '#')) }}" class="jm-card jm-service-card">
                            <div class="jm-service-card__icon">
                                @if (!empty($mainService['image']))
                                    <img src="{{ $mainService['image'] }}" alt="">
                                @else
                                    <i class="fa fa-cogs"></i>
                                @endif
                            </div>
                            <h3>{{ $mainService['title'] ?? 'Service' }}</h3>
                            <span class="jm-link-arrow">Learn more &rarr;</span>
                        </a>
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
                <a href="{{ url('attorneys-law-firms') }}" class="jm-industries__pill">Attorneys &amp; Law Firms</a>
                <a href="{{ url('cpa-firms') }}" class="jm-industries__pill">CPA Firms &amp; Accountants</a>
                <a href="{{ url('dentists') }}" class="jm-industries__pill">Dentists</a>
                <a href="{{ url('general-practice-doctors') }}" class="jm-industries__pill">General Practice Doctors</a>
                <a href="{{ url('fast-food-restaurants') }}" class="jm-industries__pill">Fast Food Restaurants</a>
                <a href="{{ url('manufacturers') }}" class="jm-industries__pill">Manufacturers</a>
                <a href="{{ url('office-managers') }}" class="jm-industries__pill">Office Managers</a>
                <a href="{{ url('packages/home') }}" class="jm-industries__pill">Homes &amp; families</a>
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
                            $firstCost = is_array($costs) && !empty($costs[0]) ? $costs[0] : '';
                            $priceDetail = is_array($costs) && !empty($costs[1]) ? $costs[1] : '';
                            $priceNote = is_array($costs) && !empty($costs[2]) ? $costs[2] : '';
                            $isPopular = $i === 2;
                        @endphp
                        <div class="col-lg-3 col-md-6">
                            <div class="jm-plan-card {{ $isPopular ? 'jm-plan-card--popular' : '' }}">
                                <div class="jm-plan-card__tag">{{ $homeTab['tab_title'] ?? 'Plan' }}</div>

                                <div class="jm-plan-card__body">
                                    <h3 class="jm-plan-card__name">{{ $homeTab['tab_title'] ?? 'Plan' }}</h3>

                                    @if (!empty($firstCost))
                                        <div class="jm-plan-card__price">
                                            <span class="jm-plan-card__price-amount">{{ $firstCost }}</span>
                                            @if (!empty($priceDetail))
                                                <span class="jm-plan-card__price-detail">{{ $priceDetail }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if (!empty($priceNote))
                                        <div class="jm-plan-card__desc">{{ $priceNote }}</div>
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

                                    <a href="{{ url('contact') }}" class="jm-btn jm-btn--outline-blue jm-plan-card__cta">Get this plan</a>
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
                            <td>Cost per system</td>
                            <td><span style="color:#0053a0;font-weight:700">$99/mo</span></td>
                            <td><span style="color:#0053a0;font-weight:700">$99/mo</span></td>
                            <td><span style="color:#0053a0;font-weight:700">$99/mo</span></td>
                            <td><span style="color:#0053a0;font-weight:700">$99/mo</span></td>
                        </tr>
                        <tr>
                            <td>Cost per server</td>
                            <td><span class="dash">&mdash;</span></td>
                            <td><span class="dash">&mdash;</span></td>
                            <td><span style="color:#0053a0;font-weight:700">$150/mo</span></td>
                            <td><span style="color:#0053a0;font-weight:700">$250/mo</span></td>
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
                        <p class="jm-about__blockquote">Having over 28 years of experience, we care more about helping our clients solve their issues over whether or not we are making money. We believe in security, trust, integrity, and professionalism. We understand the challenges of a day-to-day service business. We are not afraid to be different nor to think outside the box.</p>
                        <p class="jm-about__blockquote">Our clients and our community come first and we want to let them know that we are here today, tomorrow and for your growing needs of the future. We ask all of our clients the same question: are you willing to sacrifice quality and service for price?</p>
                    </div>
                    <div class="jm-about__actions">
                        <a href="{{ url('about') }}" class="jm-btn jm-btn--orange">Why choose JMOR?</a>
                        <a href="{{ url('testimonials') }}" class="jm-btn jm-btn--outline-blue">Read testimonials</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="jm-about__media">
                        <img src="{{ asset('assets/images/motherboard.jpeg') }}" alt="JMOR IT support in New Jersey" class="img-fluid">
                        <div class="jm-about__side-cards">
                            <a href="{{ url('case-studies') }}" class="jm-about__side-card">
                                <div class="jm-about__side-card-title">Case studies</div>
                                <div class="jm-about__side-card-desc">Real NJ businesses, real fixes</div>
                            </a>
                            <a href="{{ url('our-mission') }}" class="jm-about__side-card">
                                <div class="jm-about__side-card-title">Our mission &amp; DEI</div>
                                <div class="jm-about__side-card-desc">How we work, and why</div>
                            </a>
                        </div>
                    </div>
                </div>
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
                    <a href="{{ url('the-jmor-blog') }}" class="jm-link-arrow">Blog &rarr;</a>
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
                            <a href="{{ url('the-jmor-blog') }}" class="jm-link-arrow">Read more &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="jm-card jm-news__insta">
                        <div class="jm-news__insta-head">
                            <img src="{{ asset('assets/images/insta.png') }}" alt="" class="jm-news__insta-avatar">
                            <span>@gosocialjmor</span>
                        </div>
                        <div class="jm-news__insta-grid">
                            @for ($i = 0; $i < 9; $i++)
                                <div class="jm-news__insta-tile">Post</div>
                            @endfor
                        </div>
                        <a href="https://www.instagram.com/gosocialjmor/" target="_blank"
                            class="jm-btn jm-btn--navy jm-news__insta-cta">Go to Instagram</a>
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
                    <a href="tel:8777675667" class="jm-cta-banner__phone">
                        (877) 767-5667
                    </a>
                    <a href="{{ url('contact') }}" class="jm-btn jm-btn--orange">Request information</a>
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
            const perView = window.innerWidth < 640 ? 1 : window.innerWidth < 992 ? 2 : 3;
            const maxSlide = Math.max(0, cards.length - perView);

            function updateTrack() {
                const gapTotal = 24 * perView;
                const basis = `calc((100% - ${gapTotal}px) / ${perView})`;
                cards.forEach(c => { c.style.flex = `0 0 ${basis}`; });
                track.style.transform = `translateX(calc(-${current} * (${basis} + 24px)))`;
            }

            if (prevBtn) prevBtn.addEventListener('click', function (e) {
                e.preventDefault();
                current = Math.max(0, current - 1);
                updateTrack();
            });

            if (nextBtn) nextBtn.addEventListener('click', function (e) {
                e.preventDefault();
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
    });
</script>
@endpush
