@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    @include('partials.style_file')

    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Forgot Password</h1>
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
                                    <p>Enter your email address and we will send you a new password.</p>
                                </div>

                                @if(request()->has('error_email'))
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i></h4>Your email is not validate
                                    </div>
                                @endif

                                <form action="{{ route('forgot-pass') }}" autocomplete="off" class="form" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input type="email" name="email" id="email" placeholder="Email address"
                                                   class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                                            Reset Password
                                        </button>
                                    </div>
                                    <div class="form-group text-left">
                                        <a href="{{ route('login') }}">Back to Login</a>
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