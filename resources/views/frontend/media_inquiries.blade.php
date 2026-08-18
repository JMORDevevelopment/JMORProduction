@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-12">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Media inquiries</h1>
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
                        @if(isset($error['error_exists']))
                            <div class="alert alert-danger alert-dismissible">
                                {{ $error['error_exists'] }}
                            </div>
                        @endif

                        <form class="well" action="{{ route('media-inquiries.validate') }}" method="post">
                            @csrf

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['media']) ? ' has-error' : '' }}">
                                <label class="control-label text-left" style="font-weight:bold;">Media:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="media" class="form-control form-control-lg"
                                           placeholder="Media" id="media" value="{{ $media }}" />
                                    @if(isset($error['media']))
                                        <span class="help-block text-danger">{{ $error['media'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['contact']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Contact:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="contact" class="form-control form-control-lg"
                                           placeholder="Contact" id="contact" value="{{ $contact }}" />
                                    @if(isset($error['contact']))
                                        <span class="help-block text-danger">{{ $error['contact'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['email']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">E-mail:</label>
                                <div class="form-group mb-4">
                                    <input type="email" name="email" class="form-control form-control-lg"
                                           placeholder="Email" id="email" value="{{ $email }}" />
                                    @if(isset($error['email']))
                                        <span class="help-block text-danger">{{ $error['email'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['phone']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Phone:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="phone" class="form-control form-control-lg"
                                           placeholder="Phone" id="phone" value="{{ $phone }}" />
                                    @if(isset($error['phone']))
                                        <span class="help-block text-danger">{{ $error['phone'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['story_concept']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Story Concept:</label>
                                <div class="form-group mb-4">
                                    <input type="text" name="story_concept" class="form-control form-control-lg"
                                           placeholder="Story concept" id="story_concept" value="{{ $story_concept }}" />
                                    @if(isset($error['story_concept']))
                                        <span class="help-block text-danger">{{ $error['story_concept'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['press_deadline']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Press Deadline:</label>
                                <div class="form-group mb-4">
                                    <input type="date" name="press_deadline" class="form-control form-control-lg"
                                           placeholder="Press deadline" id="press_deadline" value="{{ $press_deadline }}" />
                                    @if(isset($error['press_deadline']))
                                        <span class="help-block text-danger">{{ $error['press_deadline'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['story_details']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Story Details:</label>
                                <div class="form-group mb-4">
                                    <textarea rows="4" cols="60" class="form-control form-control-lg"
                                              placeholder="Story details" id="story_details" name="story_details"
                                              style="resize:none">{{ $story_details }}</textarea>
                                    @if(isset($error['story_details']))
                                        <span class="help-block text-danger">{{ $error['story_details'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 col-lg-6 control-group{{ isset($error['best_contact']) ? ' has-error' : '' }}">
                                <label class="control-label" style="font-weight:bold;">Best Day and Time to Contact:</label>
                                <div class="form-group mb-4">
                                    <input type="text" class="form-control form-control-lg"
                                           placeholder="Best Contact" name="best_contact" id="best_contact"
                                           value="{{ $best_contact }}" />
                                    @if(isset($error['best_contact']))
                                        <span class="help-block text-danger">{{ $error['best_contact'] }}</span>
                                    @endif
                                </div>
                                <br><br>
                            </div>

                            <div class="control-group{{ isset($error['protection_question']) ? ' has-error' : '' }}">
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
                                            <h6 class="my-2 font-size-14"><a href="{{ route('blog.detail', $latestPost->link) }}">{{ $latestPost->name }}</a></h6>
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

    <style>
        .col-lg-8, .col-lg-4 {
            float: left !important;
        }
    </style>
@endsection

@include('partials.script_file')
