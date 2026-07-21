@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')
    @include('partials.style_file')

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
                                </div>

                                {{-- Display validation errors (same style as CI) --}}
                                @if($errors->any())
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i></h4>
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('sign-up') }}/validate" autocomplete="off" class="form" method="post">
                                    @csrf

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
                                            <input type="text" name="firstname" id="firstname" placeholder="{{ $text_firstname ?? 'First Name' }}"
                                                   value="{{ old('firstname', $firstname ?? '') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
                                            <input type="text" name="lastname" id="lastname" placeholder="{{ $text_lastname ?? 'Last Name' }}"
                                                   value="{{ old('lastname', $lastname ?? '') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input type="email" name="email" id="email" placeholder="{{ $text_email ?? 'Email' }}"
                                                   value="{{ old('email', $email ?? '') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                            <input type="password" name="password" id="password" placeholder="{{ $text_password ?? 'Password' }}"
                                                   class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-home color-blue"></i></span>
                                            <input type="text" name="address" id="address" placeholder="{{ $text_address ?? 'Address' }}"
                                                   value="{{ old('address', $address ?? '') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker color-blue"></i></span>
                                            <input type="text" name="city" id="city" placeholder="{{ $text_city ?? 'City' }}"
                                                   value="{{ old('city', $city ?? '') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker color-blue"></i></span>
                                            <input type="text" name="state" id="state" placeholder="{{ $text_state ?? 'State' }}"
                                                   value="{{ old('state', $state ?? '') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input type="text" name="zip" id="zip" placeholder="{{ $text_zip ?? 'Zip Code' }}"
                                                   value="{{ old('zip', $zip ?? '') }}" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-globe color-blue"></i></span>
                                            <select name="region_id" id="region_id" class="form-control">
                                                <option value="">{{ $text_select_region ?? 'Select region' }}</option>
                                                @foreach($regions ?? [] as $region)
                                                    <option value="{{ $region->region_id ?? $region->id }}">
                                                        {{ $region->region_name ?? $region->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                                            {{ $text_sign_up ?? 'Sign Up' }}
                                        </button>
                                    </div>
                                    <div class="form-group text-left">
                                        <span>{{ $text_sign_in ?? 'Already have an account?' }} <a href="{{ route('login') }}">Sign in</a></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.before_footer')
    @include('partials.script_file')
@endsection