@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Testimonials</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <div class="wt-section bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    @forelse($testimonials as $testimonial)
                        <div class="col-md-4">
                            <div class="card text-center mb-md-0 mb-3">
                                <div class="card-body py-5">
                                    <div class="pricing-header mb-5">
                                        <h1 style="font-size:18px; text-align:left;">
                                            {{ $testimonial->customer->firstname ?? '' }} {{ $testimonial->customer->lastname ?? '' }}
                                        </h1>
                                        <p class="text-black" style="text-align: justify;text-justify: inter-word;">{{ $testimonial->message }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Testimonial(s) not found...</p>
                    @endforelse
                </div>
            </div>
        </div>
        @include('partials.before_footer')
    </main>
@endsection

@include('partials.script_file')
