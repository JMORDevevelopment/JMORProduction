@extends('layouts.app')

@section('title', $text_sign_up ?? 'Sign Up')

@section('content')
    {{-- 1. Include style_file (as in CI: <?php echo $style_file; ?>) --}}
    @include('partials.style_file')

    @push('styles')
        {{-- Additional inline styles to stabilise the captcha --}}
        <style>
            /* Give the captcha card a fixed minimum height so errors don't shift it */
            .slidercaptcha.card {
                min-height: 140px;
                margin-bottom: 20px;
                position: relative;
                overflow: hidden;
            }

            /* Ensure the card body centres the captcha */
            .slidercaptcha.card .card-body {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
            }

            /* Style invalid fields with a red border */
            .has-error .form-control {
                border-color: #a94442;
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            }

            /* Optional: add a little spacing for the submit button */
            #qasubmitBtn {
                margin-top: 15px;
            }
        </style>
    @endpush

    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">{{ $text_sign_up ?? 'Sign Up' }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="wt-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4"></div>
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="text-center">
                                    <p>{{ $text_form ?? 'You can create an account here.' }}</p>
                                    <div class="panel-body">
                                        {{-- Global error display (all errors appear here only) --}}
                                        @if (session('error_exists'))
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">X</button>
                                                {{ session('error_exists') }}
                                            </div>
                                        @endif
                                        @if ($errors->any())
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">X</button>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form class="well" action="{{ url('sign-up/validate') }}" method="post">
                                            @csrf

                                            {{-- First Name --}}
                                            <div class="control-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                                                <div class="form-group mb-4">
                                                    <input type="text" name="firstname"
                                                        class="form-control form-control-lg"
                                                        placeholder="{{ $text_firstname ?? 'First Name' }}" id="firstname"
                                                        value="{{ old('firstname', $firstname ?? '') }}">
                                                    {{-- Per‑field errors removed to avoid layout shift --}}
                                                </div>
                                            </div>

                                            {{-- Last Name --}}
                                            <div class="control-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
                                                <div class="form-group mb-4">
                                                    <input type="text" name="lastname"
                                                        class="form-control form-control-lg"
                                                        placeholder="{{ $text_lastname ?? 'Last Name' }}" id="lastname"
                                                        value="{{ old('lastname', $lastname ?? '') }}">
                                                </div>
                                            </div>

                                            {{-- Email --}}
                                            <div class="control-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                                <div class="form-group mb-4">
                                                    <input type="text" name="email"
                                                        class="form-control form-control-lg"
                                                        placeholder="{{ $text_email ?? 'Email' }}" id="email"
                                                        value="{{ old('email', $email ?? '') }}">
                                                </div>
                                            </div>

                                            {{-- Password --}}
                                            <div class="control-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                                <div class="form-group mb-4">
                                                    <input type="password" name="password"
                                                        class="form-control form-control-lg"
                                                        placeholder="{{ $text_password ?? 'Password' }}" id="password">
                                                </div>
                                            </div>

                                            {{-- City --}}
                                            <div class="control-group {{ $errors->has('city') ? 'has-error' : '' }}">
                                                <div class="form-group mb-4">
                                                    <input type="text" name="city"
                                                        class="form-control form-control-lg"
                                                        placeholder="{{ $text_city ?? 'City' }}" id="city"
                                                        value="{{ old('city', $city ?? '') }}">
                                                </div>
                                            </div>

                                            {{-- Address --}}
                                            <div class="control-group {{ $errors->has('address') ? 'has-error' : '' }}">
                                                <div class="form-group mb-4">
                                                    <input type="text" name="address"
                                                        class="form-control form-control-lg"
                                                        placeholder="{{ $text_address ?? 'Address' }}" id="address"
                                                        value="{{ old('address', $address ?? '') }}">
                                                </div>
                                            </div>

                                            {{-- State --}}
                                            <div class="control-group {{ $errors->has('state') ? 'has-error' : '' }}">
                                                <div class="form-group mb-4">
                                                    <input type="text" name="state"
                                                        class="form-control form-control-lg"
                                                        placeholder="{{ $text_state ?? 'State' }}" id="state"
                                                        value="{{ old('state', $state ?? '') }}">
                                                </div>
                                            </div>

                                            {{-- Zip --}}
                                            <div class="control-group {{ $errors->has('zip') ? 'has-error' : '' }}">
                                                <div class="form-group mb-4">
                                                    <input type="text" name="zip"
                                                        class="form-control form-control-lg"
                                                        placeholder="{{ $text_zip ?? 'Zip Code' }}" id="zip"
                                                        value="{{ old('zip', $zip ?? '') }}">
                                                </div>
                                            </div>

                                            {{-- Slider Captcha (Drag To Verify) --}}
                                            <div class="form-group">
                                                <div class="slidercaptcha card">
                                                    <div class="card-header">
                                                        <span>Drag To Verify</span>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="captcha"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Submit button (hidden initially) --}}
                                            <div id="qasubmitBtn" class="form-group" style="display: none;">
                                                <button class="btn btn-lg btn-primary btn-block"
                                                    type="submit">{{ $text_sign_up ?? 'Sign Up' }}</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                <div class="form-group text-left">
                                    <span><a href="{{ route('login') }}"
                                            class="pull-right">{{ $text_sign_in ?? 'Already have an account?' }} login
                                            now</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.before_footer')
    @include('partials.script_file')

    @push('scripts')
        {{-- Script to reset captcha when validation errors are present --}}
        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // If the captcha plugin provides a reset method, call it.
                    // Example: assuming the captcha is initialised on #captcha with slideCaptcha()
                    var captchaElement = document.getElementById('captcha');
                    if (captchaElement && typeof captchaElement.slideCaptcha === 'function') {
                        captchaElement.slideCaptcha('reset');
                    }
                    // Hide the submit button until user re-verifies
                    var submitBtn = document.getElementById('qasubmitBtn');
                    if (submitBtn) {
                        submitBtn.style.display = 'none';
                    }
                });
            </script>
        @endif
    @endpush
@endsection
