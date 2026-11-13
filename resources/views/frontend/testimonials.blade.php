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
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-8 text-center">
                        <p class="mb-0">
                            Every testimonial below was verified against a completed JMOR engagement and is
                            published with customer consent. Full names and contact details are never shown.
                        </p>
                    </div>
                </div>

                <div class="row justify-content-center">
                    @forelse($testimonials as $testimonial)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 text-center">
                                <div class="card-body py-4">
                                    <span class="badge badge-success mb-3">Verified customer</span>
                                    <p class="text-black" style="text-align: justify;text-justify: inter-word;">
                                        &ldquo;{{ $testimonial->message }}&rdquo;
                                    </p>
                                    <p class="mb-0">
                                        <strong>{{ $testimonial->display_name ?: 'Verified customer' }}</strong>
                                        @if($testimonial->service_used)
                                            <br><span class="text-muted">{{ $testimonial->service_used }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-lg-8 text-center">
                            <p>Verified customer testimonials are being reviewed. Please check back soon.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @include('partials.before_footer')
    </main>
@endsection

@include('partials.script_file')
