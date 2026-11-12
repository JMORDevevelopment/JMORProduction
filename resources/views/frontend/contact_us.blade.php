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
                <div class="row contact-tiles" style="justify-content: center;">
                    <div class="col-lg-3 mb-5 mb-lg-0 text-center">
                        <button type="button" class="contact-action border-0 bg-transparent w-100"
                                onclick="inquiryform()"
                                aria-label="Web inquiry: jump to the inquiry form">
                            <span class="contact-tile__icon" aria-hidden="true">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <span class="h5 d-block contact-tile__title">Web Inquiry</span>
                            <span class="mb-0 d-block contact-tile__sub">Inquire Now</span>
                        </button>
                    </div>
                    <div class="col-lg-3 mb-5 mb-lg-0 text-center">
                        <button type="button" class="contact-action border-0 bg-transparent w-100"
                                onclick="inquiryformtwo()"
                                aria-label="Talk show guest: jump to the guest application form">
                            <span class="contact-tile__icon" aria-hidden="true">
                                <i class="fa fa-microphone"></i>
                            </span>
                            <span class="h5 d-block contact-tile__title">Talk Show Guest</span>
                            <span class="mb-0 d-block contact-tile__sub">Apply Now</span>
                        </button>
                    </div>
                    <div class="col-lg-3 mb-5 mb-lg-0 text-center">
                        <button type="button" class="contact-action border-0 bg-transparent w-100"
                                onclick="chatNow()"
                                aria-label="Chat with us: jump to the message box">
                            <span class="contact-tile__icon" aria-hidden="true">
                                <i class="fa fa-comment-dots"></i>
                            </span>
                            <span class="h5 d-block contact-tile__title">Chat With Us</span>
                            <span class="mb-0 d-block contact-tile__sub">Message now</span>
                        </button>
                    </div>
                    <div class="col-lg-3 mb-5 mb-lg-0 text-center">
                        @if($phoneTel)
                            <a class="contact-action d-block w-100 text-decoration-none"
                               href="tel:{{ $phoneTel }}"
                               aria-label="Call us at {{ $phone_number }}">
                                <span class="contact-tile__icon" aria-hidden="true">
                                    <i class="fa fa-phone"></i>
                                </span>
                                <span class="h5 d-block contact-tile__title">Call Us</span>
                                <span class="mb-0 d-block contact-tile__sub">{{ $phone_number }}</span>
                            </a>
                        @else
                            <div class="contact-action d-block w-100">
                                <span class="contact-tile__icon" aria-hidden="true">
                                    <i class="fa fa-phone"></i>
                                </span>
                                <span class="h5 d-block contact-tile__title">Call Us</span>
                                <span class="mb-0 d-block contact-tile__sub">{{ $phone_number }}</span>
                            </div>
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
                                    <p class="mb-2"><strong>A confirmation email is on its way.</strong> Keep it for your records.</p>
                                    <div class="d-flex justify-content-center flex-wrap mb-3 contact-thanks-actions">
                                        @if($phoneTel)
                                            <a class="btn btn-primary btn-pill px-4 mr-2 mb-2" href="tel:{{ $phoneTel }}">
                                                <i class="fa fa-phone" aria-hidden="true"></i> {{ $phone_number }}
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-outline-primary btn-pill px-4 mb-2" id="thanks-chat">
                                            <i class="fa fa-commenting" aria-hidden="true"></i> Chat With Us
                                        </button>
                                        <button type="button" class="btn btn-success btn-pill px-4 mb-2" id="thanks-continue">
                                            <i class="fa fa-check" aria-hidden="true"></i> Continue
                                        </button>
                                    </div>
                                    <p class="text-muted small mb-0">Need help right away? Call us — this confirmation stays open until you continue. <span id="thanks-count" class="sr-only">5</span></p>
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
                                        <span>Drag To Verify <small class="text-muted d-block">Slide the puzzle piece to unlock the Send button</small></span>
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

                        @php
                            // After a validation error, reopen only the step that failed.
                            $errorStep = 1;
                            if (count($error)) {
                                $stepFields = [
                                    1 => ['name', 'last_name', 'company_name', 'phone', 'email'],
                                    2 => ['bio', 'work_name', 'work_detail', 'weblink', 'interview', 'counter'],
                                    3 => ['agreement_accepted', 'signature', 'protection_question'],
                                ];
                                foreach ($stepFields as $stepNumber => $fieldNames) {
                                    foreach ($fieldNames as $fieldName) {
                                        if (isset($error[$fieldName])) {
                                            $errorStep = $stepNumber;
                                            break 2;
                                        }
                                    }
                                }
                            }
                        @endphp

                        <form id="form_two" class="inquiry" style="display:none;"
                              onsubmit="return checkwork();"
                              action="{{ route('contact.interview') }}" method="post">
                            @csrf

                            @if(count($error))
                                <div class="alert alert-danger ts-error-summary" role="alert" id="ts-error-summary" tabindex="-1">
                                    <strong><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Please fix the following before sending:</strong>
                                    <ul>
                                        @foreach($error as $fieldError)
                                            <li>{{ $fieldError }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <nav class="ts-steps mb-4" aria-label="Application progress">
                                <span class="ts-step-pill{{ $errorStep === 1 ? ' active' : '' }}" data-step-pill="1">1. Contact</span>
                                <span class="ts-step-pill{{ $errorStep === 2 ? ' active' : '' }}" data-step-pill="2">2. Experience</span>
                                <span class="ts-step-pill{{ $errorStep === 3 ? ' active' : '' }}" data-step-pill="3">3. Agreement</span>
                            </nav>

                            <fieldset class="ts-step" data-step="1"{{ $errorStep !== 1 ? ' hidden' : '' }}>
                                <legend class="ts-step__title">Step 1: Contact information</legend>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="ts-field{{ isset($error['name']) ? ' has-error' : '' }}">
                                            <label for="ts_name">First Name <span class="text-danger" aria-hidden="true">*</span></label>
                                            <input type="text" class="form-control" id="ts_name"
                                                   name="name" placeholder="e.g. Jane" required
                                                   value="{{ old('name', '') }}" />
                                            @if(isset($error['name']))
                                                <span class="help-block text-danger">{{ $error['name'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="ts-field{{ isset($error['last_name']) ? ' has-error' : '' }}">
                                            <label for="ts_last_name">Last Name <span class="text-danger" aria-hidden="true">*</span></label>
                                            <input type="text" class="form-control" id="ts_last_name"
                                                   name="last_name" placeholder="e.g. Doe" required
                                                   value="{{ old('last_name', '') }}" />
                                            @if(isset($error['last_name']))
                                                <span class="help-block text-danger">{{ $error['last_name'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="ts-field{{ isset($error['company_name']) ? ' has-error' : '' }}">
                                            <label for="ts_company">Company / Organization <small class="text-muted">(optional)</small></label>
                                            <input type="text" class="form-control" id="ts_company"
                                                   name="company_name" placeholder="e.g. Acme Corp"
                                                   value="{{ old('company_name', '') }}" />
                                            @if(isset($error['company_name']))
                                                <span class="help-block text-danger">{{ $error['company_name'] }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="ts-field">
                                            <label class="ts-switch" for="twophone">
                                                <input id="twophone" type="checkbox" class="swi" checked
                                                       aria-label="Toggle US phone format when you are a US resident">
                                                <span class="sliderp round"></span>
                                                <span class="ts-switch-row">
                                                    <span class="ts-switch-row__label">US Resident</span>
                                                    <small class="ts-switch-row__hint">On: US phone format. Toggle off if you live outside the US.</small>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="ts-field{{ isset($error['phone']) ? ' has-error' : '' }}">
                                            <label for="phoneb">Phone Number <span class="text-danger" aria-hidden="true">*</span></label>
                                            <input id="phoneb" name="phone" type="tel" inputmode="tel" autocomplete="tel"
                                                   aria-label="Phone number (US format)"
                                                   class="form-control phone bph" required
                                                   placeholder="(999) 999-9999 or with extension"
                                                   value="{{ old('phone', '') }}">
                                            <input id="phone_intl" name="" type="tel" inputmode="tel" autocomplete="tel"
                                                   aria-label="Phone number (international format)"
                                                   style="display: none;" class="form-control bphb"
                                                   placeholder="e.g. +44 20 7946 0958" value="{{ old('phone', '') }}">
                                            @if(isset($error['phone']))
                                                <span class="help-block text-danger">{{ $error['phone'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="ts-field{{ isset($error['email']) ? ' has-error' : '' }}">
                                            <label for="emailb">Email <span class="text-danger" aria-hidden="true">*</span></label>
                                            <input type="email" name="email" class="form-control" placeholder="you@example.com"
                                                   id="emailb" required value="{{ old('email', '') }}" />
                                            @if(isset($error['email']))
                                                <span class="help-block text-danger">{{ $error['email'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="ts-nav ts-nav--end">
                                    <button type="button" class="btn btn-primary ts-next" data-next="2">Next</button>
                                </div>
                            </fieldset>

                            <fieldset class="ts-step" data-step="2"{{ $errorStep !== 2 ? ' hidden' : '' }}>
                                <legend class="ts-step__title">Step 2: Experience and pitch</legend>

                                <div class="ts-field mb-4{{ isset($error['bio']) ? ' has-error' : '' }}">
                                    <label for="bio">Your Bio <span class="text-danger" aria-hidden="true">*</span></label>
                                    <textarea rows="6" name="bio" class="form-control"
                                              placeholder="Tell us about your background, expertise, and what you would bring to the show." id="bio" required
                                              maxlength="999">{{ old('bio', '') }}</textarea>
                                    @if(isset($error['bio']))
                                        <span class="help-block text-danger">{{ $error['bio'] }}</span>
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <div class="row align-items-start mb-3">
                                        <div class="col-md-8">
                                            <h5>Show Appearance or Past Experience</h5>
                                            <p class="text-muted small mb-2">Fill in the fields below, then click
                                                <strong>Add Work or Experience</strong>. Repeat for each show — at least one is required.</p>
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            <button type="button" id="add_work" class="btn btn-primary"
                                                    aria-controls="append">Add Work or Experience</button>
                                        </div>
                                    </div>

                                    @php
                                        $oldWorkNames = old('work_name');
                                        $oldWorkDetails = old('work_detail');
                                        $oldWorkLinks = old('weblink');
                                        $hasOldWorks = is_array($oldWorkNames) && $oldWorkNames !== [];
                                    @endphp
                                    <div id="append" class="ts-work-list" style="{{ $hasOldWorks ? 'display: flex;' : 'display: none;' }}">
                                        @if($hasOldWorks)
                                            @foreach($oldWorkNames as $oldIndex => $oldWorkName)
                                                @include('frontend.ajaxv', [
                                                    'id' => 900 + (int) $oldIndex,
                                                    'work_name' => (string) $oldWorkName,
                                                    'work_detail' => is_array($oldWorkDetails) ? (string) ($oldWorkDetails[$oldIndex] ?? '') : '',
                                                    'work_web' => is_array($oldWorkLinks) ? (string) ($oldWorkLinks[$oldIndex] ?? '') : '',
                                                ])
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="d-block fw-semibold mb-1" for="work_name">Show or Experience</label>
                                            <input type="text" id="work_name" class="form-control" value=""
                                                   placeholder="Show or Experience " aria-label="Show or experience name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="d-block fw-semibold mb-1" for="work_detail">Description</label>
                                            <textarea id="work_detail" class="form-control" rows="2" maxlength="500"
                                                      placeholder="Description" aria-label="Show or experience description"></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="d-block fw-semibold mb-1" for="work_web">Web Link <small class="text-muted">(optional)</small></label>
                                            <input type="url" id="work_web" class="form-control" value=""
                                                   placeholder="(optional) https:// or http://" aria-label="Show or experience web link">
                                        </div>
                                    </div>

                                    <span id="work_error" class="text-danger d-block mt-2" role="alert"></span>

                                    @if(isset($error['work_name']))
                                        <span class="help-block text-danger">{{ $error['work_name'] }}</span>
                                    @endif
                                </div>

                                <input type="hidden" id="counter" name="counter" value="{{ old('counter', 1) }}">

                                <div class="ts-field{{ isset($error['interview']) ? ' has-error' : '' }}">
                                    <label for="interview">Interview Pitch Concept <span class="text-danger" aria-hidden="true">*</span></label>
                                    <textarea rows="6" name="interview" class="form-control"
                                              placeholder="What would you like to talk about on the show?" id="interview" required>{{ old('interview') }}</textarea>
                                    @if(isset($error['interview']))
                                        <span class="help-block text-danger">{{ $error['interview'] }}</span>
                                    @endif
                                </div>

                                <div class="ts-nav">
                                    <button type="button" class="btn btn-outline-secondary ts-back" data-back="1">Back</button>
                                    <button type="button" class="btn btn-primary ts-next" data-next="3">Next</button>
                                </div>
                            </fieldset>

                            <fieldset class="ts-step" data-step="3"{{ $errorStep !== 3 ? ' hidden' : '' }}>
                                <legend class="ts-step__title">Step 3: Agreement and signature</legend>

                                <div class="ts-agreement mb-4 p-3 border rounded">
                                    <h5>Guest Agreement</h5>
                                    @if(! empty($agreementUrl))
                                        <p class="mb-3">
                                            Please review the
                                            <a href="{{ $agreementUrl }}" target="_blank" rel="noopener">JMOR guest agreement (PDF)</a>
                                            before signing.
                                        </p>
                                    @else
                                        <p class="mb-3">
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
                                            <span class="help-block text-danger">{{ $error['agreement_accepted'] }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="ts-field mb-4{{ isset($error['signature']) ? ' has-error' : '' }}">
                                    <label for="signatureb">Electronic Signature <span class="text-danger" aria-hidden="true">*</span></label>
                                    <input type="text" class="form-control" id="signatureb"
                                           name="signature" maxlength="100" required
                                           placeholder="Type your full name as your signature"
                                           value="{{ old('signature', '') }}" />
                                    @if(isset($error['signature']))
                                        <span class="help-block text-danger">{{ $error['signature'] }}</span>
                                    @endif
                                </div>

                                <div class="ts-field{{ isset($error['protection_question']) ? ' has-error' : '' }}">
                                    <input type="hidden" name="protection_question" id="protection_questionb" />
                                    <input name="firstNumber" type="hidden" id="random_number1b" value="{{ $random_number1 }}" />
                                    <input name="secondNumber" type="hidden" id="random_number2b" value="{{ $random_number2 }}" />
                                    @if(isset($error['protection_question']))
                                        <span class="help-block text-danger">{{ $error['protection_question'] }}</span>
                                    @endif
                                </div>

                                <div class="ts-captcha mb-4">
                                    <div class="ts-captcha__head">
                                        <span>
                                            <strong><i class="fa fa-lock" aria-hidden="true"></i> Drag To Verify</strong>
                                            <small class="d-block text-muted">Slide the puzzle piece to unlock the submit button</small>
                                        </span>
                                    </div>
                                    <div class="ts-captcha__body">
                                        <div id="captchab"></div>
                                    </div>
                                </div>

                                <div class="ts-nav ts-nav--between">
                                    <button type="button" class="btn btn-outline-secondary ts-back" data-back="2">Back</button>
                                    <span class="ts-nav__submit">
                                        <span class="ts-nav__hint" id="ts_submit_hint">Drag the slider above to enable submit.</span>
                                        <button id="qasubmitBtnb" class="btn btn-primary btn-lg px-4" type="submit"
                                                disabled>Submit Application</button>
                                    </span>
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
        // Talk-show captcha only initializes once its container is visible.
        if (window.jmorInitTalkShowCaptcha) {
            window.jmorInitTalkShowCaptcha();
        }
    }

    function tsShowStep(step) {
        var form = document.getElementById('form_two');
        if (!form) {
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
        // Step 3 hosts the drag captcha — init now that it is visible.
        if (String(step) === '3' && window.jmorInitTalkShowCaptcha) {
            window.jmorInitTalkShowCaptcha();
        }
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
        var isUs = this.checked;
        $('#phoneb').toggle(isUs).attr('name', isUs ? 'phone' : '').prop('required', isUs);
        $('#phone_intl').toggle(!isUs).attr('name', isUs ? '' : 'phone').prop('required', !isUs);
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
        clearWorkError();

        // Every added entry must still have a name and a valid optional link.
        var cardsValid = true;
        $('#append .ts-work-card').each(function () {
            var nameInput = $(this).find('input[name="work_name[]"]').first();
            var webInput = $(this).find('input[name="weblink[]"]').first();
            var cardName = (nameInput.val() || '').trim();
            var cardWeb = (webInput.val() || '').trim();
            if (!cardName) {
                showWorkError('Please enter a show or experience name for every added entry.');
                nameInput.trigger('focus');
                cardsValid = false;
                return false;
            }
            if (cardWeb && !isValidURL(cardWeb)) {
                showWorkError('Web link must be a valid URL (https://...) or be left empty.');
                webInput.trigger('focus');
                cardsValid = false;
                return false;
            }
        });
        if (!cardsValid) {
            jumpToWorkFields();
            return false;
        }

        // If the user filled the three fields but forgot "Add", auto-add on submit.
        var name = ($('#work_name').val() || '').trim();
        if (name) {
            var detail = ($('#work_detail').val() || '').trim();
            var web = ($('#work_web').val() || '').trim();
            if (web && !isValidURL(web)) {
                showWorkError('Web link must be a valid URL (https://...) or be left empty.');
                jumpToWorkFields();
                return false;
            }
            appendWorkEntry(name, detail, web);
            return true;
        }

        if ($('#append .ts-work-card').length > 0) {
            return true;
        }

        showWorkError('Please add at least one show or experience. Fill in the fields below and click “Add Work or Experience”.');
        jumpToWorkFields();
        return false;
    }

    function jumpToWorkFields() {
        if (typeof tsShowStep === 'function') {
            tsShowStep(2);
        }
        var workName = document.getElementById('work_name');
        if (workName) {
            setTimeout(function () {
                workName.scrollIntoView({behavior: 'smooth', block: 'center'});
                workName.focus({preventScroll: true});
            }, 100);
        }
    }

    function escapeAttr(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function buildWorkCard(id, workName, workDetail, workWeb) {
        return '' +
            '<div class="ts-work-card" id="accordion' + id + '">' +
                '<div class="ts-work-card__head">' +
                    '<span class="ts-work-card__title"><i class="fa fa-television" aria-hidden="true"></i> Entry ' + id + '</span>' +
                    '<button type="button" class="ts-work-card__remove" onclick="remove_parent(this);" ' +
                        'aria-label="Remove entry ' + id + '">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i> Remove' +
                    '</button>' +
                '</div>' +
                '<div class="ts-work-card__grid">' +
                    '<div>' +
                        '<label for="work_name' + id + '">Show or Experience <span class="text-danger" aria-hidden="true">*</span></label>' +
                        '<input type="text" name="work_name[]" id="work_name' + id + '" class="form-control" ' +
                            'value="' + escapeAttr(workName) + '" maxlength="255" required>' +
                    '</div>' +
                    '<div>' +
                        '<label for="detail' + id + '">Description</label>' +
                        '<textarea name="work_detail[]" id="detail' + id + '" class="form-control" rows="2" ' +
                            'maxlength="500">' + escapeAttr(workDetail) + '</textarea>' +
                    '</div>' +
                    '<div>' +
                        '<label for="web' + id + '">Web Link <small class="text-muted">(optional)</small></label>' +
                        '<input type="url" name="weblink[]" id="web' + id + '" class="form-control" ' +
                            'value="' + escapeAttr(workWeb) + '" placeholder="https://">' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function appendWorkEntry(workName, workDetail, workWeb) {
        var counter = parseInt($('#counter').val(), 10) || 1;
        counter += 1;
        $('#append').append(buildWorkCard(counter, workName, workDetail, workWeb));
        $('#append').css('display', 'flex');
        $('#counter').val(counter);
        $('#work_name').val('');
        $('#work_web').val('');
        $('#work_detail').val('');
    }

    function remove_parent(element) {
        $(element).closest('.ts-work-card').remove();
        if ($('#append .ts-work-card').length < 1) {
            $('#append').css('display', 'none');
        }
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
                $('#append').css('display', 'flex');
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
        (function () {
            // Land on the step that failed so the user can fix it in place.
            @if(count($error))
                if (typeof tsShowStep === 'function') {
                    tsShowStep({{ $errorStep }});
                }
            @endif
            var summary = document.getElementById('ts-error-summary');
            if (summary) {
                setTimeout(function () {
                    summary.scrollIntoView({behavior: 'smooth', block: 'center'});
                    summary.focus({preventScroll: true});
                }, 250);
                return;
            }
            var firstError = document.querySelector('#form_two .help-block.text-danger, #work_error:not(:empty)');
            if (firstError) {
                setTimeout(function () {
                    firstError.scrollIntoView({behavior: 'smooth', block: 'center'});
                }, 250);
            }
        })();
    @endif

    (function () {
        var overlay = document.getElementById('contact-thanks');
        if (!overlay) {
            return;
        }

        // Bring the confirmation into view immediately — this is the
        // "dialogue box" the user must not miss after submit.
        window.scrollTo({top: 0, behavior: 'smooth'});

        function dismissOverlay() {
            overlay.remove();
        }

        var continueButton = document.getElementById('thanks-continue');
        if (continueButton) {
            continueButton.addEventListener('click', dismissOverlay);
        }

        overlay.addEventListener('click', function (event) {
            if (event.target === overlay) {
                dismissOverlay();
            }
        });

        var chatButton = document.getElementById('thanks-chat');
        if (chatButton) {
            chatButton.addEventListener('click', function () {
                dismissOverlay();
                chatNow();
            });
        }

        // Keep the countdown element (tests assert its id) but do NOT
        // auto-navigate — an instant redirect felt like a page refresh.
        var countElement = document.getElementById('thanks-count');
        var remaining = 5;
        if (countElement) {
            var timer = setInterval(function () {
                remaining -= 1;
                if (remaining <= 0) {
                    clearInterval(timer);
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
            text-align: left;
        }
        .help-block {
            display: block;
            margin-top: 0.4rem;
            font-size: 0.86rem;
        }
        .ts-field.has-error .form-control,
        .form-group.has-error .form-control {
            border-color: #dc3545;
        }

        /* Wizard stepper */
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

        /* Step cards */
        .ts-step {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        .ts-step[hidden] {
            display: none;
        }
        .ts-step__title {
            display: block;
            width: 100%;
            font-size: 1.1rem;
            font-weight: 700;
            color: #212529;
            padding: 0;
            margin: 0 0 1rem;
            border: 0;
        }
        .ts-field > label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        /* US resident switch */
        .ts-switch {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            margin: 0;
            font-weight: 600;
        }
        .ts-switch .swi {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .ts-switch .sliderp {
            position: relative;
            display: inline-block;
            width: 42px;
            height: 24px;
            background: #adb5bd;
            border-radius: 999px;
            transition: background 0.15s ease;
        }
        .ts-switch .sliderp::before {
            content: "";
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.15s ease;
        }
        .ts-switch .swi:checked + .sliderp {
            background: #0d6efd;
        }
        .ts-switch .swi:checked + .sliderp::before {
            transform: translateX(18px);
        }
        .ts-switch-row {
            display: flex;
            flex-direction: column;
        }
        .ts-switch-row__hint {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 400;
        }

        /* Step navigation */
        .ts-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        .ts-nav--end {
            justify-content: flex-end;
        }
        .ts-nav__submit {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .ts-nav__hint {
            color: #6c757d;
            font-size: 0.85rem;
        }

        /* Added work entries */
        .ts-work-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-height: 480px;
            overflow: auto;
            margin-bottom: 1rem;
        }
        .ts-work-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            background: #fff;
        }
        .ts-work-card__head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        .ts-work-card__title {
            font-weight: 700;
            font-size: 0.92rem;
        }
        .ts-work-card__remove {
            background: none;
            border: 0;
            color: #dc3545;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .ts-work-card__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
        }
        .ts-work-card label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        .ts-work-card textarea {
            resize: vertical;
        }

        /* Captcha frame */
        .ts-captcha {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .ts-captcha__head {
            padding: 0.75rem 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .ts-captcha__head small {
            color: #6c757d;
        }
        .ts-captcha__body {
            padding: 1rem;
        }

        /* Error summary + show-all after server validation errors */
        .ts-error-summary ul {
            list-style-type: disc;
            margin: 0.5rem 0 0;
            padding-left: 1.2rem;
        }

        #contact-thanks .contact-thanks-card {
            border-top: 4px solid #198754;
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
        .contact-tiles {
            justify-content: center;
        }
        .contact-action {
            cursor: pointer;
            display: block;
            padding: 8px 4px;
            border-radius: 16px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            color: inherit;
            text-decoration: none;
        }
        .contact-action:hover,
        .contact-action:focus {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(1, 32, 64, 0.1);
            text-decoration: none;
            outline: none;
        }
        .contact-action:hover .contact-tile__title,
        .contact-action:focus .contact-tile__title {
            text-decoration: none;
            color: #0053a0;
        }
        .contact-tile__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 84px;
            height: 84px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: #eff5fb;
            color: #0053a0;
            box-shadow: inset 0 0 0 1px rgba(0, 83, 160, 0.08);
            transition: background 0.15s ease, color 0.15s ease, transform 0.15s ease;
        }
        .contact-tile__icon i {
            font-size: 32px;
            line-height: 1;
        }
        .contact-action:hover .contact-tile__icon,
        .contact-action:focus .contact-tile__icon {
            background: #0053a0;
            color: #fff;
            transform: scale(1.05);
        }
        .contact-tile__title {
            font-weight: 700;
            color: #012040;
            margin-bottom: 4px;
        }
        .contact-tile__sub {
            color: #5c6b7a;
            font-size: 0.95rem;
        }
        @media (max-width: 991.98px) {
            .contact-tile__icon {
                width: 72px;
                height: 72px;
            }
            .contact-tile__icon i {
                font-size: 28px;
            }
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
