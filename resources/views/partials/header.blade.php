@php
    $topSettings = \App\Models\Setting::orderBy('id')->get();
@endphp

<div class="jm-topbar">
    <div class="container">
        <div class="jm-topbar__row">
            <div class="jm-topbar__left">
                <a href="tel:8777675667" class="jm-topbar__phone">(877) 767-5667</a>
                <span class="jm-topbar__divider" aria-hidden="true"></span>
                <span class="jm-topbar__tagline">Serving New Jersey homes &amp; businesses since 1997</span>
            </div>

            <div class="jm-topbar__right">
                <ul class="jm-topbar__socials" aria-label="social links">
                    <li><a href="{{ $topSettings[11]->value ?? 'https://www.instagram.com/gosocialjmor/' }}"
                            title="Instagram"><img src="{{ asset('assets/images/insta.png') }}" alt="Instagram"></a>
                    </li>
                    <li><a href="{{ $topSettings[3]->value ?? 'https://www.facebook.com/JMORConnection/' }}"
                            title="Facebook"><img src="{{ asset('assets/images/facebook.png') }}" alt="Facebook"></a>
                    </li>
                    <li><a href="{{ $topSettings[4]->value ?? 'https://twitter.com/JMORCONNECTION' }}"
                            title="Twitter"><img src="{{ asset('assets/images/twitter.png') }}" alt="Twitter"></a></li>
                    <li><a href="{{ $topSettings[5]->value ?? '#' }}" title="YouTube"><img
                                src="{{ asset('assets/images/youtube.png') }}" alt="YouTube"></a></li>
                    <li><a href="{{ $topSettings[6]->value ?? 'https://www.linkedin.com/company/2623706/' }}"
                            title="LinkedIn"><img src="{{ asset('assets/images/linkedin.png') }}" alt="LinkedIn"></a>
                    </li>
                    <li><a href="{{ $topSettings[7]->value ?? 'https://www.patreon.com/jmor' }}" title="Patreon"><img
                                src="{{ asset('assets/images/social.png') }}" alt="Patreon"></a></li>
                </ul>

                <span class="jm-topbar__divider" aria-hidden="true"></span>
                <a href="{{ url('gift-card') }}" class="jm-topbar__link">Gift Card</a>

                @auth
                    <span class="jm-topbar__divider" aria-hidden="true"></span>
                    <a href="{{ url('dashboard') }}" class="jm-topbar__link--strong">My Account</a>
                    <span class="jm-topbar__divider" aria-hidden="true"></span>
                    <a href="{{ route('logout') }}" class="jm-topbar__link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a>
                    <form id="logout-form" action="{{ url('logout') }}" method="POST" class="d-none">@csrf</form>
                @else
                    <a href="{{ url('login') }}" class="jm-topbar__link--strong">Login</a>
                @endauth
            </div>
        </div>
    </div>
</div>

<header class="jm-header" role="banner">
    <div class="container">
        <div class="jm-header__row">
            <a href="{{ url('/') }}" class="jm-header__brand" aria-label="JMOR home">
                <img src="{{ asset('assets/images/logo.png') }}" alt="The JMOR Connection, Inc.">
            </a>

            <nav class="jm-header__nav d-none d-lg-flex" aria-label="Primary navigation">
                <ul class="jm-header__navlist">
                    @foreach ($navigation as $item)
                        @include('partials.navigation', ['item' => $item, 'depth' => 0, 'desktop' => true])
                    @endforeach
                </ul>
            </nav>

            <div class="jm-header__actions">
                <form action="{{ url('search') }}" method="post" autocomplete="off"
                    class="jm-header__search d-none d-xl-flex" role="search">
                    @csrf
                    <input type="text" name="search" placeholder="Search" aria-label="Search site">
                    <button type="submit" aria-label="Search"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>

                <a href="{{ url('contact') }}" class="jm-header__cta-btn d-none d-md-inline-flex">Reach Out Today</a>

                <button class="jm-header__toggle d-lg-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>

    <div class="collapse jm-header__mobile d-lg-none" id="navbarNavDropdown">
        <div class="container">
            <ul class="navbar-nav">
                @foreach ($navigation as $item)
                    @include('partials.navigation', ['item' => $item, 'depth' => 0])
                @endforeach
            </ul>
            <a href="{{ url('contact') }}" class="jm-btn jm-btn--orange jm-header__mobile-cta">Reach Out Today</a>
        </div>
    </div>
</header>
