@extends('layouts.app')

@section('content')

<div class="home_banner">
    <div class="img">
        <img class="img-fluid" src="{{ asset('assets/images/banner.png') }}">
    </div>
</div>

<div class="slide_sec">
    @include('partials.slider_section')
</div>

<div class="clear"></div>

<div class="our_services" style="background: #fff!important">
    <div class="container">
        <h3>Our Services</h3>
        <div class="row">
            @foreach ($mainServices as $mainService)
                <div class="col-md-3 col-sm-6">
                    <div class="services_box">
                        <a href="{{ url('service/' . $mainService['link']) }}">
                            <img src="{{ $mainService['image'] }}">
                        </a>
                        <a href="{{ url('service/' . $mainService['link']) }}" style="color:#0053a0;">
                            <h3>{{ $mainService['title'] }}</h3>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="blue_banner">
    <div class="container">
        <h3>JMOR EDGE System Services Plans</h3>
    </div>
</div>

<div class="tabs_bar">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($homeTabs as $index => $homeTab)
            <li class="nav-itemss">
                <a class="nav-link {{ $index == 3 ? 'active' : '' }}"
                   data-bs-toggle="tab"
                   href="#home{{ $homeTab['tab_id'] }}">
                    {{ $homeTab['tab_title'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach ($homeTabs as $index => $homeTab)
            <div id="home{{ $homeTab['tab_id'] }}"
                 class="container tab-pane {{ $index == 3 ? 'active' : '' }}">
                <br>
{!! $homeTab['tab_description'] !!}
                <div class="row">
                    <div class="col-md-4">
                        <h4 style="margin-bottom:0px!important;font-size:20px;margin-top:25px;font-weight:bold;">
                            FEATURES
                        </h4>
                        @foreach (json_decode($homeTab['tab_list'], true) ?? [] as $feature)
                            @if (!empty($feature))
                                <div class="col-md-12">
                                    <div class="media">
                                        <img class="mr-3" src="{{ asset('assets/images/check.jpg') }}">
                                        <div class="media-body">
                                            <h5 class="mt-0">{{ $feature }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <h4 style="margin-bottom:0px!important;font-size:20px;margin-top:25px;font-weight:bold;">
                            BENEFITS
                        </h4>
                        @foreach (json_decode($homeTab['benefits'], true) ?? [] as $benefit)
                            @if (!empty($benefit))
                                <div class="col-md-12">
                                    <div class="media">
                                        <img class="mr-3" src="{{ asset('assets/images/check.jpg') }}">
                                        <div class="media-body">
                                            <h5 class="mt-0">{{ $benefit }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <h4 style="margin-bottom:0px!important;font-size:20px;margin-top:25px;font-weight:bold;">
                            COST
                        </h4>
                        @foreach (json_decode($homeTab['cost'], true) ?? [] as $costItem)
                            @if (!empty($costItem))
                                <div class="col-md-12">
                                    <div class="media">
                                        <img class="mr-3" src="{{ asset('assets/images/check.jpg') }}">
                                        <div class="media-body">
                                            <h5 class="mt-0">{!! $costItem !!}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endif  
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="about_jmor">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="about_desc">
                    <h3>About JMOR</h3>
                    <div class="white_box">
                        <h4>Mission Statement</h4>
                        <p>
                            The JMOR Connection, Inc. is dedicated to providing solutions and services that keep
                            your business up and running smoothly. We will provide your business with the latest
                            and most efficient technology available. We will fix your problem right the first time.
                        </p>

                        <p>
                            About JMOR:<br>
                            Having over 28 years of experience, we care more about helping our clients solve their
                            issues over whether or not we are making money. We believe in security, trust,
                            integrity, and professionalism. We understand the challenges of a day-to-day service
                            business. We are not afraid to be different nor to think outside the box. Our clients
                            get the facts from us and they can stop the insanity; doing the same thing over and
                            over again and expecting different results. We are here to serve the community and
                            have been serving them for years.
                        </p>

                        <p>
                            Our clients and our community come first and we want to let them know that we are here
                            today, tomorrow and for your growing needs of the future. We ask all of our clients the
                            same question: are you willing to sacrifice quality and service for price?
                        </p>

                        <p>
                            The JMOR Connection, Inc. is dedicated to providing solutions and services that will
                            help small businesses grow and keep them up and running.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box_img">
                    <img class="img-fluid" src="{{ asset('assets/images/motherboard.jpeg') }}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="headlines">
    <div class="container-fluid">
        <h3>News Headlines and Social Media</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="video">
                    <iframe width="100%" height="480"
                            src="https://www.youtube.com/embed/VZ3gZACXylc"
                            frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    <p>
                        At JMOR we are not going to start selling you shoes; yes, our clients may have feet but we
                        are A Technology Company. Our firm is not one to jump on bandwagons; we will continue to do
                        what we have been doing for years: professional Computer, Network, IT Support and Repair
                        Services delivered right the first time.
                        <a href="{{ url('the-jmor-blog') }}" style="color:red;">Read More</a>
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <style>
                    .blog.pad-m.pad-top-l.powrMark.text-center {
                        display: none !important;
                    }
                    .powr-instagram-feed #appView .powrMark {
                        display: none !important;
                    }
                </style>

                <div class="gallery">
                    {{-- Legacy Instagram feed fetch was disabled in the original CI view and is
                         intentionally omitted here. --}}
                </div>

                <p>
                    <a href="https://www.instagram.com/gosocialjmor/" target="_blank" style="color:#fff;padding:10px 0;">
                        Goto Instagram
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .tabs_bar .tab-content .media {
        margin-top: 15px !important;
    }

    .tab-content > .active {
        display: block;
        position: relative !important;
        width: 100%;
        top: 0px !important;
        -webkit-transform: none !important;
        transform: none !important;
    }

    @media screen and (min-width: 768px) {
        li.nav-itemss:nth-child(1) {
            width: 33%;
        }
        li.nav-itemss:nth-child(1) a {
            padding: 10px 20px;
        }
        li.nav-itemss:nth-child(2),
        li.nav-itemss:nth-child(3),
        li.nav-itemss:nth-child(4) {
            width: 22.32%;
        }
    }
</style>

@endsection