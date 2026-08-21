@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Sitemap</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="wt-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <ul class="list-unstyled">
                            @foreach($pages as $page)
                                <li class="mb-2">
                                    <a href="{{ url($page->link) }}">{{ $page->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <div class="case_studies-widget mb-4">
                            <h6 class="mb-4">Quick Links</h6>
                            <div class="card border-0 bg-light mb-1">
                                <div class="card-body">
                                    <a href="{{ route('home') }}">Home</a>
                                </div>
                            </div>
                            <div class="card border-0 bg-light mb-1">
                                <div class="card-body">
                                    <a href="{{ route('about-us') }}">About Us</a>
                                </div>
                            </div>
                            <div class="card border-0 bg-light mb-1">
                                <div class="card-body">
                                    <a href="{{ route('contact') }}">Contact Us</a>
                                </div>
                            </div>
                            <div class="card border-0 bg-light mb-1">
                                <div class="card-body">
                                    <a href="{{ route('packages') }}">Packages</a>
                                </div>
                            </div>
                            <div class="card border-0 bg-light mb-1">
                                <div class="card-body">
                                    <a href="{{ route('blog') }}">Blog</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>
@endsection

@include('partials.script_file')
