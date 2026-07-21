{{-- This is the sign_up view from CI --}}
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
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="text-center">
                                    <p>{{ $text_form ?? 'You can create an account here.' }}</p>
                                </div>

                                {{-- Display validation errors --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('sign-up') }}/validate" autocomplete="off" class="form" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="firstname">{{ $text_firstname ?? 'First Name' }}</label>
                                        <input type="text" name="firstname" id="firstname" class="form-control"
                                               value="{{ old('firstname', $firstname ?? '') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lastname">{{ $text_lastname ?? 'Last Name' }}</label>
                                        <input type="text" name="lastname" id="lastname" class="form-control"
                                               value="{{ old('lastname', $lastname ?? '') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">{{ $text_email ?? 'Email' }}</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                               value="{{ old('email', $email ?? '') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">{{ $text_password ?? 'Password' }}</label>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">{{ $text_address ?? 'Address' }}</label>
                                        <input type="text" name="address" id="address" class="form-control"
                                               value="{{ old('address', $address ?? '') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="city">{{ $text_city ?? 'City' }}</label>
                                        <input type="text" name="city" id="city" class="form-control"
                                               value="{{ old('city', $city ?? '') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="state">{{ $text_state ?? 'State' }}</label>
                                        <input type="text" name="state" id="state" class="form-control"
                                               value="{{ old('state', $state ?? '') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="zip">{{ $text_zip ?? 'Zip Code' }}</label>
                                        <input type="text" name="zip" id="zip" class="form-control"
                                               value="{{ old('zip', $zip ?? '') }}" required>
                                    </div>
                                    {{-- Region and Nation are optional; add if needed --}}
                                    <div class="form-group">
    <label for="region_id">{{ $text_region ?? 'Region' }}</label>
    <select name="region_id" id="region_id" class="form-control">
        <option value="">{{ $text_select_region ?? 'Select region' }}</option>
        @foreach($regions ?? [] as $region)
            <option value="{{ $region->region_id ?? $region->id }}">
                {{ $region->region_name ?? $region->name }}
            </option>
        @endforeach
    </select>
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