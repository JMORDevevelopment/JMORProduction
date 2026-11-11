@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Newsletter</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="wt-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        @if($state === 'confirmed')
                            <div class="alert alert-success" role="status" style="text-align: left;">
                                <strong>You're confirmed!</strong>
                                @if(! empty($firstName))
                                    Thanks, {{ $firstName }} —
                                @endif
                                your email is now subscribed to the JMOR newsletter.
                            </div>
                            <p class="mt-3">
                                <a href="{{ route('home') }}" class="btn btn-primary">Back to home</a>
                            </p>
                        @elseif($state === 'already')
                            <div class="alert alert-info" role="status" style="text-align: left;">
                                <strong>Already confirmed.</strong>
                                @if(! empty($firstName))
                                    {{ $firstName }}, this
                                @else
                                    This
                                @endif
                                address is already subscribed to the JMOR newsletter.
                            </div>
                            <p class="mt-3">
                                <a href="{{ route('home') }}" class="btn btn-primary">Back to home</a>
                            </p>
                        @else
                            <div class="alert alert-danger" role="alert" style="text-align: left;">
                                <strong>Invalid confirmation link.</strong>
                                This link is missing, expired, or already used. Please use the latest confirmation email, or subscribe again from the contact page.
                            </div>
                            <p class="mt-3">
                                <a href="{{ route('contact') }}" class="btn btn-primary">Go to contact page</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
