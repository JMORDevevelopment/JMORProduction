@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Request Information</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="wt-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-xs-12">
                        @if(isset($error['error_exists']))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                                {{ $error['error_exists'] }}
                            </div>
                        @endif

                        <form class="well" action="{{ route('request-information.validate') }}" method="post">
                            @csrf

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['first_name']) ? ' has-error' : '' }}">
                                <label class="control-label text-left" style="font-weight:bold;">First Name:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="first_name" class="form-control form-control-lg"
                                           placeholder="first name" id="first_name" value="{{ $first_name }}" />
                                    @if(isset($error['first_name']))
                                        <span class="help-block text-danger">{{ $error['first_name'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['last_name']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Last Name:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="last_name" class="form-control form-control-lg"
                                           placeholder="last name" id="last_name" value="{{ $last_name }}" />
                                    @if(isset($error['last_name']))
                                        <span class="help-block text-danger">{{ $error['last_name'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['company']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Company:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="company" class="form-control form-control-lg"
                                           placeholder="company" id="company" value="{{ $company }}" />
                                    @if(isset($error['company']))
                                        <span class="help-block text-danger">{{ $error['company'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['email']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Email:</label>
                                <div class="form-group mb-4">
                                    <input type="email" name="email" class="form-control form-control-lg"
                                           placeholder="email" id="email" value="{{ $email }}" />
                                    @if(isset($error['email']))
                                        <span class="help-block text-danger">{{ $error['email'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['phone']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Phone:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="phone" class="form-control form-control-lg" placeholder="Phone"
                                           id="phone" value="{{ $phone }}" />
                                    @if(isset($error['phone']))
                                        <span class="help-block text-danger">{{ $error['phone'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['fax']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Fax:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="fax" class="form-control form-control-lg" placeholder="fax"
                                           id="fax" value="{{ $fax }}" />
                                    @if(isset($error['fax']))
                                        <span class="help-block text-danger">{{ $error['fax'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['address']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Address:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="address" class="form-control form-control-lg" placeholder="address"
                                           id="address" value="{{ $address }}" />
                                    @if(isset($error['address']))
                                        <span class="help-block text-danger">{{ $error['address'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['suite']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Suite or Floor:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="suite" class="form-control form-control-lg" placeholder="suite"
                                           id="suite" value="{{ $suite }}" />
                                    @if(isset($error['suite']))
                                        <span class="help-block text-danger">{{ $error['suite'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['city']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">City:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="city" class="form-control form-control-lg" placeholder="city"
                                           id="city" value="{{ $city }}" />
                                    @if(isset($error['city']))
                                        <span class="help-block text-danger">{{ $error['city'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['state']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">State:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="state" class="form-control form-control-lg" placeholder="state"
                                           id="state" value="{{ $state }}" />
                                    @if(isset($error['state']))
                                        <span class="help-block text-danger">{{ $error['state'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['zip']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Zip:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="zip" class="form-control form-control-lg" placeholder="zip"
                                           id="zip" value="{{ $zip }}" />
                                    @if(isset($error['zip']))
                                        <span class="help-block text-danger">{{ $error['zip'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['service_intersted']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">What services are you interested in?</label>
                                <div class="form-group mb-4">
                                    <select name="service_intersted" class="form-control">
                                        <option value="">Select a service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service }}" {{ $service_intersted === $service ? 'selected' : '' }}>{{ $service }}</option>
                                        @endforeach
                                    </select>
                                    @if(isset($error['service_intersted']))
                                        <span class="help-block text-danger">{{ $error['service_intersted'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="control-group{{ isset($error['message']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Message:</label>
                                <div class="form-group mb-4">
                                    <textarea rows="4" cols="60" class="form-control form-control-lg"
                                              placeholder="Message" id="story_details" name="message"
                                              style="resize:none">{{ $message }}</textarea>
                                    @if(isset($error['message']))
                                        <span class="help-block text-danger">{{ $error['message'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="control-label" style="font-weight:bold;"></label>
                                <div class="form-group mb-4">
                                    <input type="hidden" name="protection_question" class="form-control form-control-lg"
                                           placeholder="Protection question" id="protection_question" />
                                    <input name="firstNumber" type="hidden" id="random_number1" value="{{ $random_number1 }}" />
                                    <input name="secondNumber" type="hidden" id="random_number2" value="{{ $random_number2 }}" />
                                    @if(isset($error['protection_question']))
                                        <span class="help-block text-danger">{{ $error['protection_question'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="slidercaptcha card">
                                    <div class="card-header">
                                        <span>Drag To Verify</span>
                                    </div>
                                    <div class="card-body">
                                        <div id="captcha"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="qasubmitBtn" class="text-right" style="display: none;">
                                <button class="btn btn-lg btn-primary py-3 mt-3 px-4 btn-pill" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-4">
                        <div class="case_studies-widget mb-4">
                            <h6 class="mb-4">Latest Post</h6>
                            @foreach($latestPosts as $latestPost)
                                <div class="card border-0 bg-light mb-1">
                                    <div class="card-body row align-items-center">
                                        <div class="col-12">
                                            <h6 class="my-2 font-size-14"><a href="{{ route('blog.detail', \Illuminate\Support\Str::after($latestPost->link, 'blog/')) }}">{{ $latestPost->name }}</a></h6>
                                            <span class="font-size-14 text-muted">{{ date('m-d-Y', strtotime($latestPost->published)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>
@endsection

@include('partials.script_file')
