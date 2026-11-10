@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Contact Us</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="wt-section bg-light">
            <div class="container">
                <div class="row justify-content-center align-items-center text-center pb-lg-5">
                    <div class="col-md-8"></div>
                </div>
                @php
                    $phoneDigits = preg_replace('/\D/', '', (string) $phone_number);
                    $phoneTel = $phoneDigits === ''
                        ? null
                        : (strlen($phoneDigits) === 11 && str_starts_with($phoneDigits, '1')
                            ? '+'.$phoneDigits
                            : '+1'.$phoneDigits);
                @endphp
                <div class="row" style="justify-content: center;">
                    <div class="col-lg-3 mb-5 mb-lg-0 text-center">
                        <button type="button" class="contact-action border-0 bg-transparent w-100"
                                onclick="inquiryform()"
                                aria-label="Web inquiry: jump to the inquiry form">
                            <div class="contactIcons text-primary mb-2">
                                <i class="fa fa-life-ring" aria-hidden="true"></i>
                            </div>
                            <span class="h5 d-block">Web Inquiry</span>
                            <span class="mb-0 d-block">Inquire Now</span>
                        </button>
                    </div>
                    <div class="col-lg-3 mb-5 mb-lg-0 text-center">
                        <button type="button" class="contact-action border-0 bg-transparent w-100"
                                onclick="inquiryformtwo()"
                                aria-label="Talk show guest: jump to the guest application form">
                            <div class="contactIcons text-primary mb-2">
                                <i class="fa fa-microphone" aria-hidden="true"></i>
                            </div>
                            <span class="h5 d-block">Talk Show Guest</span>
                            <span class="mb-0 d-block">Apply Now</span>
                        </button>
                    </div>
                    <div class="col-lg-3 mb-5 mb-lg-0 text-center">
                        <button type="button" class="contact-action border-0 bg-transparent w-100"
                                onclick="chatNow()"
                                aria-label="Chat with us: jump to the message box">
                            <div class="contactIcons text-primary mb-2">
                                <i class="fa fa-commenting" aria-hidden="true"></i>
                            </div>
                            <span class="h5 d-block">Chat With Us</span>
                            <span class="mb-0 d-block">Message now</span>
                        </button>
                    </div>
                    <div class="col-lg-3 mb-5 mb-lg-0 text-center">
                        <div class="contactIcons text-primary mb-2">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                        </div>
                        <span class="h5 d-block">Call Us</span>
                        @if($phoneTel)
                            <a class="mb-0 d-block" href="tel:{{ $phoneTel }}">{{ $phone_number }}</a>
                        @else
                            <span class="mb-0 d-block">{{ $phone_number }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="wt-section inquirysection">
            <div class="container">
                <div class="row justify-content-center align-items-center text-center mb-lg-4 mb-4">
                    <div class="col-md-6 mb-lg-5 mb-4">
                        <h2 id="hed" class="mb-4">Let us know</h2>
                        <p class="text-muted">Fill out the form below.</p>
                    </div>
                </div>

                <div class="row text-center" style="justify-content: center;">
                    <div id="map_box" class="col-lg-5">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3010.113136935536!2d-74.20879448429831!3d41.022780679299395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2e287fb5beb13%3A0x5056f097f8d06e27!2s799%20Franklin%20Ave%20%233%2C%20Franklin%20Lakes%2C%20NJ%2007417%2C%20USA!5e0!3m2!1sen!2s!4v1588958264865!5m2!1sen!2s" width="100%" height="320" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                        <p class="mt-2 mb-0 small text-muted">{!! nl2br(e(str_replace(['<br>', '<br/>', '<br />'], "\n", $address))) !!}</p>
                    </div>
                    <div class="col-lg-7">
                        @if($success)
                            <div id="contact-thanks" class="contact-thanks text-center" role="status" aria-live="polite">
                                <div class="contact-thanks-card bg-white rounded shadow-sm p-5 mx-auto">
                                    <i class="fa fa-check-circle text-success fa-3x mb-3" aria-hidden="true"></i>
                                    <h2 class="h4">Thank you!</h2>
                                    <p class="mb-4">{{ $success }}</p>
                                    <p class="mb-2"><strong>Need help right away?</strong></p>
                                    <div class="d-flex justify-content-center flex-wrap mb-3 contact-thanks-actions">
                                        @if($phoneTel)
                                            <a class="btn btn-primary btn-pill px-4 mr-2 mb-2" href="tel:{{ $phoneTel }}">
                                                <i class="fa fa-phone" aria-hidden="true"></i> {{ $phone_number }}
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-outline-primary btn-pill px-4 mb-2" id="thanks-chat">
                                            <i class="fa fa-commenting" aria-hidden="true"></i> Chat With Us
                                        </button>
                                    </div>
                                    <p class="text-muted small mb-0">Returning to Contact Us in <span id="thanks-count">5</span> seconds&hellip;</p>
                                </div>
                            </div>
                        @endif

                        <form id="form_one" class="inquiry" action="{{ route('contact.submit') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="control-group">
                                <div class="form-group mb-4{{ isset($error['name']) ? ' has-error' : '' }}">
                                    <input type="text" class="form-control form-control-lg"
                                           name="name" placeholder="Full Name" required
                                           value="{{ $name }}" />
                                    @if(isset($error['name']))
                                        <span class="help-block text-danger">{{ $error['name'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-4{{ isset($error['email']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Email"
                                           id="email" required value="{{ $email }}" />
                                    @if(isset($error['email']))
                                        <span class="help-block text-danger">{{ $error['email'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-4{{ isset($error['phone']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <input id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel"
                                           aria-label="Phone number"
                                           style="display: '';" class="form-control form-control-lg phone ph"
                                           placeholder="Enter your number...." value="{{ $phone }}">
                                    @if(isset($error['phone']))
                                        <span class="help-block text-danger">{{ $error['phone'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-4{{ isset($error['reason']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <select name="reason" id="reason" class="form-control form-control-lg" required>
                                        <option value="">Reason</option>
                                        <option value="Already Client" {{ $reason === 'Already Client' ? 'selected' : '' }}>Already Client</option>
                                        <option value="Donate a Product to be Unboxed" {{ $reason === 'Donate a Product to be Unboxed' ? 'selected' : '' }}>Donate a Product to be Unboxed</option>
                                        <option value="General Inquiry" {{ $reason === 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                        <option value="Employment &amp; Internships" {{ $reason === 'Employment & Internships' ? 'selected' : '' }}>Employment &amp; Internships</option>
                                        <option value="Technology Assessment" {{ $reason === 'Technology Assessment' ? 'selected' : '' }}>Technology Assessment</option>
                                        <option value="Residential Support" {{ $reason === 'Residential Support' ? 'selected' : '' }}>Residential Support</option>
                                        <option value="Business Support" {{ $reason === 'Business Support' ? 'selected' : '' }}>Business Support</option>
                                        <option value="Vendor" {{ $reason === 'Vendor' ? 'selected' : '' }}>Vendor</option>
                                    </select>
                                    @if(isset($error['reason']))
                                        <span class="help-block text-danger">{{ $error['reason'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div id="resume_group" class="form-group mb-4{{ isset($error['resume']) ? ' has-error' : '' }}" style="display: none;">
                                <div class="controls">
                                    <label for="resume" class="d-block text-left">Resume (PDF, max 5 MB) <span class="text-danger">*</span></label>
                                    <input type="file" name="resume" id="resume" accept="application/pdf,.pdf"
                                           class="form-control form-control-lg">
                                    @if(isset($error['resume']))
                                        <span class="help-block text-danger">{{ $error['resume'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div id="assessment_group" class="form-group mb-4{{ isset($error['assessment_scope']) || isset($error['business_name']) || isset($error['business_type']) ? ' has-error' : '' }}" style="display: none;">
                                <div class="controls text-left">
                                    <label class="d-block">Assessment type <span class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="assessment_scope"
                                               id="scope_residential" value="residential"
                                               {{ $assessment_scope === 'residential' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="scope_residential">Residential</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="assessment_scope"
                                               id="scope_business" value="business"
                                               {{ $assessment_scope === 'business' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="scope_business">Business</label>
                                    </div>
                                    @if(isset($error['assessment_scope']))
                                        <span class="help-block text-danger d-block">{{ $error['assessment_scope'] }}</span>
                                    @endif

                                    <div id="business_fields" style="display: {{ $assessment_scope === 'business' ? 'block' : 'none' }};">
                                        <div class="form-group mb-3 mt-2">
                                            <input type="text" name="business_name" id="business_name"
                                                   class="form-control form-control-lg" placeholder="Business Name"
                                                   maxlength="100" value="{{ $business_name }}">
                                            @if(isset($error['business_name']))
                                                <span class="help-block text-danger">{{ $error['business_name'] }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" name="business_type" id="business_type"
                                                   class="form-control form-control-lg" placeholder="Business Type (e.g. Retail, Healthcare, Finance)"
                                                   maxlength="100" value="{{ $business_type }}">
                                            @if(isset($error['business_type']))
                                                <span class="help-block text-danger">{{ $error['business_type'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4{{ isset($error['message']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <textarea rows="10" cols="100" name="message" class="form-control form-control-lg"
                                              placeholder="Message" id="message" required
                                              maxlength="999" style="resize:none">{{ $message }}</textarea>
                                    @if(isset($error['message']))
                                        <span class="help-block text-danger">{{ $error['message'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="control-group{{ isset($error['protection_question']) ? ' has-error' : '' }} text-left">
                                <label class="control-label text-left" style="font-weight:bold; text-align:left;"></label>
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

                            <div id="qasubmitBtn" class="text-center" style="display: none;">
                                <button class="btn btn-lg btn-primary py-3 mt-3 px-4 btn-pill" type="submit">Send Your Message</button>
                            </div>
                        </form>

                        <form id="form_two" class="inquiry{{ count($error) ? ' ts-show-all' : '' }}" style="display:none;"
                              onsubmit="return checkwork();"
                              action="{{ route('contact.interview') }}" method="post">
                            @csrf

                            <nav class="ts-steps mb-4" aria-label="Application progress">
                                <span class="ts-step-pill active" data-step-pill="1">1. Contact</span>
                                <span class="ts-step-pill" data-step-pill="2">2. Experience</span>
                                <span class="ts-step-pill" data-step-pill="3">3. Agreement</span>
                            </nav>

                            <fieldset class="ts-step" data-step="1">
                                <legend class="sr-only">Step 1: Contact information</legend>
                            <div class="control-group">
                                <div class="form-group mb-4{{ isset($error['name']) ? ' has-error' : '' }}">
                                    <input type="text" class="form-control form-control-lg"
                                           name="name" placeholder="First Name" required
                                           value="{{ old('name', '') }}" />
                                    @if(isset($error['name']))
                                        <span class="help-block text-danger">{{ $error['name'] }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-4{{ isset($error['last_name']) ? ' has-error' : '' }}">
                                    <input type="text" class="form-control form-control-lg"
                                           name="last_name" placeholder="Last Name" required
                                           value="{{ old('last_name', '') }}" />
                                    @if(isset($error['last_name']))
                                        <span class="help-block text-danger">{{ $error['last_name'] }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-4{{ isset($error['company_name']) ? ' has-error' : '' }}">
                                    <input type="text" class="form-control form-control-lg"
                                           name="company_name" placeholder="Company Name"
                                           value="{{ old('company_name', '') }}" />
                                    @if(isset($error['company_name']))
                                        <span class="help-block text-danger">{{ $error['company_name'] }}</span>
                                    @endif
                                </div>

                                <p>US Resident</p>
                                <label class="switch">
                                    <input id="twophone" type="checkbox" class="swi" checked>
                                    <span class="sliderp round"></span>
                                </label>

                                <div class="form-group mb-4{{ isset($error['phone']) ? ' has-error' : '' }}">
                                    <div class="controls">
                                        <input id="phoneb" name="phone" type="tel" inputmode="tel" autocomplete="tel"
                                               aria-label="Phone number"
                                               style="display: '';" class="form-control form-control-lg phone bph"
                                               placeholder="Enter your number...." value="{{ old('phone', '') }}">
                                        <input id="" name="" type="tel" inputmode="tel"
                                               aria-label="Phone number (non-US resident)"
                                               style="display: none;" class="form-control form-control-lg bphb"
                                               placeholder="Enter your number....">
                                        @if(isset($error['phone']))
                                            <span class="help-block text-danger">{{ $error['phone'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4{{ isset($error['email']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Email"
                                           id="emailb" required value="{{ old('email', '') }}" />
                                    @if(isset($error['email']))
                                        <span class="help-block text-danger">{{ $error['email'] }}</span>
                                    @endif
                                </div>
                            </div>

                                <div class="ts-nav text-right">
                                    <button type="button" class="btn btn-primary ts-next" data-next="2">Next</button>
                                </div>
                            </fieldset>

                            <fieldset class="ts-step" data-step="2" hidden>
                                <legend class="sr-only">Step 2: Experience and pitch</legend>
                            <div class="form-group mb-4{{ isset($error['bio']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <textarea rows="10" cols="100" name="bio" class="form-control form-control-lg"
                                              placeholder="Bio" id="bio" required
                                              maxlength="999" style="resize:none">{{ old('bio', '') }}</textarea>
                                    @if(isset($error['bio']))
                                        <span class="help-block text-danger">{{ $error['bio'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <h5>Show Appearance or Past Experience</h5>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="add_work" class="btn btn-primary pull-right"
                                            aria-controls="append">Add Work or Experience</button>
                                </div>
                            </div>

                            <div class="form-group mb-4{{ isset($error['work_name']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <div style="height: auto; scroll-behavior: auto; max-height: 600px; overflow: auto; display: none;" id="append" class="form-control thumbnail-sortable"></div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" id="work_name" class="form-control" value=""
                                                   placeholder="Show or Experience " aria-label="Show or experience name">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" id="work_detail" class="form-control" value=""
                                                   placeholder="Description" aria-label="Show or experience description">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="url" id="work_web" class="form-control" value=""
                                                   placeholder="(optional) https:// or http://" aria-label="Show or experience web link">
                                        </div>
                                    </div>

                                    <span id="work_error" class="text-danger" role="alert"></span>

                                    @if(isset($error['work_name']))
                                        <span class="help-block text-danger">{{ $error['work_name'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" id="counter" name="counter" value="1">

                            <div class="form-group mb-4{{ isset($error['interview']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <textarea rows="10" cols="100" name="interview" class="form-control form-control-lg"
                                              placeholder="Interview Pitch Concept" id="interview" required
                                              style="resize:none">{{ old('interview') }}</textarea>
                                    @if(isset($error['interview']))
                                        <span class="help-block text-danger">{{ $error['interview'] }}</span>
                                    @endif
                                </div>
                            </div>

                                <div class="ts-nav d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary ts-back" data-back="1">Back</button>
                                    <button type="button" class="btn btn-primary ts-next" data-next="3">Next</button>
                                </div>
                            </fieldset>

                            <fieldset class="ts-step" data-step="3" hidden>
                                <legend class="sr-only">Step 3: Agreement and signature</legend>

                                <div class="ts-agreement mb-4 p-3 border rounded">
                                    <h5>Guest Agreement</h5>
                                    @if(! empty($agreementUrl))
                                        <p class="mb-2">
                                            Please review the
                                            <a href="{{ $agreementUrl }}" target="_blank" rel="noopener">JMOR guest agreement (PDF)</a>
                                            before signing.
                                        </p>
                                    @else
                                        <p class="mb-2">
                                            By signing below you confirm that the information provided is accurate and
                                            agree to be considered as a guest on the JMOR Tech Talk Show, including
                                            publication of your biography and appearance details.
                                        </p>
                                    @endif
                                    <div class="form-check{{ isset($error['agreement_accepted']) ? ' has-error' : '' }}">
                                        <input class="form-check-input" type="checkbox" name="agreement_accepted"
                                               value="1" id="agreement_accepted"
                                               {{ old('agreement_accepted') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="agreement_accepted">
                                            I have reviewed and accept the guest agreement.
                                        </label>
                                        @if(isset($error['agreement_accepted']))
                                            <span class="help-block text-danger d-block">{{ $error['agreement_accepted'] }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mb-4{{ isset($error['signature']) ? ' has-error' : '' }}">
                                    <label for="signatureb">Electronic Signature</label>
                                    <input type="text" class="form-control form-control-lg" id="signatureb"
                                           name="signature" maxlength="100" required
                                           placeholder="Type your full name as your signature"
                                           value="{{ old('signature', '') }}" />
                                    @if(isset($error['signature']))
                                        <span class="help-block text-danger">{{ $error['signature'] }}</span>
                                    @endif
                                </div>

                            <div class="control-group{{ isset($error['protection_question']) ? ' has-error' : '' }} text-left">
                                <label class="control-label text-left" style="font-weight:bold; text-align:left;"></label>
                                <div class="form-group mb-4">
                                    <input type="hidden" name="protection_question" class="form-control form-control-lg"
                                           placeholder="Protection question" id="protection_questionb" />
                                    <input name="firstNumber" type="hidden" id="random_number1b" value="{{ $random_number1 }}" />
                                    <input name="secondNumber" type="hidden" id="random_number2b" value="{{ $random_number2 }}" />
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
                                        <div id="captchab"></div>
                                    </div>
                                </div>
                            </div>

                                <div class="ts-nav d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-outline-secondary ts-back" data-back="2">Back</button>
                                    <div id="qasubmitBtnb" style="display: none;">
                                        <button class="btn btn-lg btn-primary py-3 mt-3 px-4 btn-pill" type="submit">Send Your Message</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js" type="text/javascript"></script>
<script>
    function scrollToForm(formId) {
        var form = document.getElementById(formId);
        if (!form) {
            return;
        }
        form.scrollIntoView({behavior: 'smooth', block: 'start'});
        var focusable = form.querySelector('input:not([type="hidden"]), select, textarea');
        if (focusable) {
            setTimeout(function () {
                focusable.focus({preventScroll: true});
            }, 400);
        }
    }

    function inquiryform() {
        $('#form_one').css('display', '').show();
        $('#form_two').hide();
        $('#map_box').css('display', 'block');
        $('#hed').html('Fill out the form below.');
        scrollToForm('form_one');
    }

    function inquiryformtwo() {
        $('#form_one').hide();
        $('#form_two').show();
        $('#map_box').hide();
        $('#hed').html('Interview Pitch Concept.');
        scrollToForm('form_two');
    }

    function tsShowStep(step) {
        var form = document.getElementById('form_two');
        if (!form || form.classList.contains('ts-show-all')) {
            return;
        }
        form.querySelectorAll('.ts-step').forEach(function (fieldset) {
            fieldset.hidden = fieldset.getAttribute('data-step') !== String(step);
        });
        form.querySelectorAll('.ts-step-pill').forEach(function (pill) {
            pill.classList.toggle('active', pill.getAttribute('data-step-pill') === String(step));
        });
        var active = form.querySelector('.ts-step:not([hidden]) input:not([type="hidden"]), .ts-step:not([hidden]) textarea');
        if (active) {
            active.focus({preventScroll: true});
        }
        form.scrollIntoView({behavior: 'smooth', block: 'start'});
    }

    function tsValidateStep(step) {
        var form = document.getElementById('form_two');
        var fieldset = form.querySelector('.ts-step[data-step="' + step + '"]');
        if (!fieldset) {
            return true;
        }
        var fields = fieldset.querySelectorAll('input, textarea, select');
        for (var i = 0; i < fields.length; i++) {
            if (fields[i].type === 'hidden' || fields[i].disabled) {
                continue;
            }
            if (!fields[i].checkValidity()) {
                fields[i].reportValidity();
                fields[i].focus();
                return false;
            }
        }
        return true;
    }

    document.addEventListener('click', function (event) {
        var next = event.target.closest('.ts-next');
        if (next) {
            var form = document.getElementById('form_two');
            var current = parseInt(next.getAttribute('data-next'), 10) - 1;
            if (tsValidateStep(current)) {
                tsShowStep(parseInt(next.getAttribute('data-next'), 10));
            }
            return;
        }
        var back = event.target.closest('.ts-back');
        if (back) {
            tsShowStep(parseInt(back.getAttribute('data-back'), 10));
        }
    });

    function chatNow() {
        inquiryform();
        var message = document.getElementById('message');
        if (message) {
            setTimeout(function () {
                message.focus({preventScroll: true});
            }, 400);
        }
    }

    $('#twophone').change(function () {
        if (this.checked) {
            $('.bph').toggle();
            $('.bphb').toggle();
            $('.bph').attr('name', 'phone');
            $('.bphb').attr('name', '');
            $('.bph').prop('required', true);
            $('.bphb').prop('required', false);
        } else {
            $('.bph').toggle();
            $('.bphb').toggle();
            $('.bph').attr('name', '');
            $('.bphb').attr('name', 'phone');
            $('.bphb').prop('required', true);
            $('.bph').prop('required', false);
        }
    });

    if ($.fn.mask) {
        $('.phone').mask('(999) 999-9999? x9999', {autoclear: false});
    }

    function syncWorkflowFields() {
        var reason = ($('#reason').val() || '');
        $('#resume_group').toggle(reason === 'Employment & Internships');
        $('#assessment_group').toggle(reason === 'Technology Assessment');
        if (reason !== 'Employment & Internships') {
            var resumeInput = document.getElementById('resume');
            if (resumeInput) {
                resumeInput.value = '';
            }
        }
    }

    $('#reason').on('change', syncWorkflowFields);
    $('input[name="assessment_scope"]').on('change', function () {
        $('#business_fields').toggle($(this).val() === 'business' && this.checked);
    });
    syncWorkflowFields();

    function isValidURL(string) {
        var res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
        return (res !== null);
    }

    function showWorkError(message) {
        var el = document.getElementById('work_error');
        if (el) {
            el.textContent = message;
        }
    }

    function clearWorkError() {
        var el = document.getElementById('work_error');
        if (el) {
            el.textContent = '';
        }
    }

    function checkwork() {
        if ($('#append').children().length > 0) {
            clearWorkError();
            return true;
        }
        showWorkError('Please add at least one show or experience before sending.');
        var workName = document.getElementById('work_name');
        if (workName) {
            workName.focus();
        }
        return false;
    }

    function remove_parent(element) {
        $(element).closest('.panel-group').remove();
        if ($('#append').children().length < 1) {
            $('#append').css('display', 'none');
        }
    }

    function edit_parent(object, id) {
        $('#edit_v' + id).fadeToggle();
        $('#no' + id).fadeToggle();
    }

    function save(id) {
        var work_name = $('#work_name' + id).val().trim();
        var web = $('#web' + id).val().trim();
        var detail = $('#detail' + id).val();

        if (work_name === '') {
            showWorkError('Show or experience name cannot be empty.');
            $('#work_name' + id).focus();
            return;
        }
        if (web !== '' && !isValidURL(web)) {
            showWorkError('Web link must be a valid URL (https://...) or be left empty.');
            $('#web' + id).focus();
            return;
        }

        clearWorkError();
        $('#wset' + id).text(web !== '' ? web : 'Not provided');
        $('#hset' + id).text(work_name);
        $('#dset' + id).text(detail);
        $('#edit_v' + id).fadeToggle();
        $('#no' + id).fadeToggle();
    }

    $('#add_work').click(function () {
        clearWorkError();

        var counter = parseInt($('#counter').val());
        counter = counter + 1;

        var work_name = $('#work_name').val().trim();
        var work_web = $('#work_web').val().trim();
        var work_detail = $('#work_detail').val();

        if (work_name === '') {
            showWorkError('Please enter a show or experience name.');
            $('#work_name').focus();
            return;
        }
        if (work_web !== '' && !isValidURL(work_web)) {
            showWorkError('Web link must be a valid URL (https://...) or be left empty.');
            $('#work_web').focus();
            return;
        }

        $.ajax({
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                id: counter,
                work_name: work_name,
                work_detail: work_detail,
                work_web: work_web
            },
            dataType: 'json',
            url: '{{ route('contact.load_restaurants') }}',
            success: function (response) {
                $('#append').append(response.html);
                $('#append').css('display', '');
                $('#counter').val(counter);
                $('#work_name').val('');
                $('#work_web').val('');
                $('#work_detail').val('');
                $('#work_name').focus();
            },
            error: function (xhr, status, error) {
                showWorkError('Could not add the entry. Please check your connection and try again.');
            }
        });
    });

    @if($open_interview)
        inquiryformtwo();
    @endif

    (function () {
        var overlay = document.getElementById('contact-thanks');
        if (!overlay) {
            return;
        }

        var chatButton = document.getElementById('thanks-chat');
        if (chatButton) {
            chatButton.addEventListener('click', function () {
                overlay.remove();
                chatNow();
            });
        }

        var countElement = document.getElementById('thanks-count');
        var remaining = 5;
        if (countElement) {
            var timer = setInterval(function () {
                remaining -= 1;
                if (remaining <= 0) {
                    clearInterval(timer);
                    window.location.href = @json(route('contact'));
                    return;
                }
                countElement.textContent = remaining;
            }, 1000);
        }
    })();
</script>
@endpush

@push('styles')
    <style>
        #form_one, #form_two {
            scroll-margin-top: 120px;
        }
        .ts-steps {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .ts-step-pill {
            flex: 1;
            min-width: 100px;
            text-align: center;
            padding: 8px 12px;
            border-radius: 999px;
            background: #e9ecef;
            color: #495057;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .ts-step-pill.active {
            background: #0d6efd;
            color: #fff;
        }
        .ts-step[hidden] {
            display: none;
        }
        #form_two.ts-show-all .ts-step[hidden] {
            display: block;
        }
        #form_two.ts-show-all .ts-nav {
            display: none;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .contact-action {
            cursor: pointer;
        }
        .contact-action:hover .h5,
        .contact-action:focus .h5 {
            text-decoration: underline;
        }
        .contact-thanks {
            position: fixed;
            inset: 0;
            background: rgba(1, 32, 64, 0.75);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .contact-thanks-card {
            max-width: 520px;
            width: 100%;
        }
        .contact-thanks-actions .btn {
            min-width: 180px;
        }
    </style>
@endpush

@include('partials.script_file')
