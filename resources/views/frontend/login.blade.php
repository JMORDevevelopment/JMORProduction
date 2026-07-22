    @extends('layouts.app')

    @section('title', 'Login')

    @section('content')
        @include('partials.style_file')

        <section class="wt-section bg-gray text-center inner-page-header">
            <div class="container">
                <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                    <div class="col-md-7">
                        <div class="text-center">
                            <h1 class="display-sm-4 display-lg-3">Login</h1>
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
                                    @if(request()->has('error_email'))
                                        <div class="alert alert-warning alert-dismissible">
                                            <h4><i class="icon fa fa-warning"></i></h4>Your email is not validate
                                        </div>
                                    @endif

                                    @if(request()->has('reset_pass'))
                                        <div class="alert alert-success alert-dismissible">
                                            <h4><i class="icon fa fa-success"></i></h4>Your new password is reset please check your email
                                        </div>
                                    @endif

                                    {{-- Display validation errors --}}
@if ($errors->any())
    <div class="alert alert-warning alert-dismissible">
        <h4><i class="icon fa fa-warning"></i></h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                                    <div class="text-center">
                                        <p>You can Login to this page.</p>
                                        <div class="panel-body">
                                            @if(session('error_login'))
                                                <div class="alert alert-warning alert-dismissible">
                                                    <h4></h4>{{ session('error_login')['global_error'] }}
                                                </div>
                                            @endif

                                            <form action="{{ route('login') }}" autocomplete="off" class="form" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                                        <input id="email" name="email" placeholder="Email address"
                                                            value="{{ old('email') ?? $email ?? '' }}"
                                                            class="form-control" type="email">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                                        <input id="password" name="password" placeholder="Password"
                                                            value="{{ old('password') ?? $password ?? '' }}"
                                                            class="form-control" type="password">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-lg btn-primary btn-block">Login</button>
                                                </div>
                                                <div class="form-group text-left">
                                                    <span><a href="{{ route('sign-up') }}">Sign up</a></span>
                                                    <span><a href="{{ route('forgot-password') }}" class="pull-right">Forgot password</a></span>
                                                </div>
                                            </form>
                                        </div>
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
    @endsection