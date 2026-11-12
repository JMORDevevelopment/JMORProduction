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

    <main role="main" class="contact-page">
        <section class="wt-section bg-light contact-paths-section">
            <div class="container">
                <p class="contact-intro">Choose the way that suits you best. Not sure where to start? Begin with a Technology Assessment and we'll take it from there.</p>

                {{-- Primary customer-acquisition pathways come first and each one has
                     its own destination; guest/show support sits in a quieter
                     secondary row so it no longer competes (issues 044 / 048). --}}
                <div class="contact-paths">
                    <a href="{{ route('request-information') }}" class="contact-path contact-path--primary"
                        data-conversion="technology_assessment">
                        <span class="contact-path__icon"><i class="fa fa-clipboard-check"></i></span>
                        <h3 class="h5">Technology Assessment</h3>
                        <span class="contact-path__cta">Start here &rarr;</span>
                    </a>

                    <a href="#inquiry-form" class="contact-path contact-path--primary"
                        onclick="inquiryform()" data-conversion="web_inquiry">
                        <span class="contact-path__icon"><i class="fa fa-life-ring"></i></span>
                        <h3 class="h5">Web Inquiry</h3>
                        <span class="contact-path__cta">Ask a question &rarr;</span>
                    </a>

                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone_number) }}" class="contact-path contact-path--primary"
                        data-conversion="phone_call">
                        <span class="contact-path__icon"><i class="fa fa-phone"></i></span>
                        <h3 class="h5">Phone Number</h3>
                        <span class="contact-path__cta">{{ $phone_number ?: 'Call us' }}</span>
                    </a>

                    <div class="contact-path contact-path--primary contact-path--static">
                        <span class="contact-path__icon"><i class="fa fa-location-arrow"></i></span>
                        <h3 class="h5">Address</h3>
                        <p class="contact-path__text">{!! nl2br(e(str_replace(['<br>', '<br/>', '<br />'], "\n", $address))) !!}</p>
                        <a class="contact-path__cta" href="https://maps.google.com/?q={{ urlencode(strip_tags($address)) }}"
                            target="_blank" rel="noopener" data-conversion="directions">
                            Get directions &rarr;
                        </a>
                    </div>
                </div>

                <div class="contact-paths contact-paths--secondary">
                    <a href="#inquiry-form" class="contact-path" onclick="inquiryformtwo()"
                        data-conversion="talk_show_guest">
                        <span class="contact-path__icon"><i class="fa fa-microphone"></i></span>
                        <h3 class="h5">Talk Show Guest</h3>
                        <span class="contact-path__cta">Apply to appear &rarr;</span>
                    </a>

                    <a href="https://www.instagram.com/gosocialjmor/" target="_blank" rel="noopener"
                        class="contact-path" data-conversion="live_support">
                        <span class="contact-path__icon"><i class="fa fa-commenting"></i></span>
                        <h3 class="h5">Live Support</h3>
                        <span class="contact-path__cta">Message us &rarr;</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="wt-section inquirysection" id="inquiry-form">
            <div class="container">
                <div class="contact-form-head">
                    <h2 id="hed">Let us know</h2>
                    <p>Fill out the form below.</p>
                </div>

                <div class="row justify-content-center align-items-stretch contact-layout">
                    <div id="map_box" class="col-lg-5">
                        <div class="contact-map">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3010.113136935536!2d-74.20879448429831!3d41.022780679299395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2e287fb5beb13%3A0x5056f097f8d06e27!2s799%20Franklin%20Ave%20%233%2C%20Franklin%20Lakes%2C%20NJ%2007417%2C%20USA!5e0!3m2!1sen!2s!4v1588958264865!5m2!1sen!2s" title="Office location map" loading="lazy" allowfullscreen=""></iframe>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        @if(isset($success))
                            <div class="alert alert-success alert-dismissible contact-alert" role="alert" id="contact-success">
                                <strong>Thank you &mdash; your message was sent.</strong><br>
                                {{ $success }}
                            </div>
                        @endif

                        <form id="form_one" class="inquiry contact-form-card" action="{{ route('contact.submit') }}" method="post"
                              data-conversion-form="web_inquiry">
                            @csrf

                            <div class="form-group{{ isset($error['name']) ? ' has-error' : '' }}">
                                <input type="text" class="form-control form-control-lg"
                                       name="name" placeholder="Full Name" required
                                       value="{{ $name }}" />
                                @if(isset($error['name']))
                                    <span class="help-block text-danger">{{ $error['name'] }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ isset($error['email']) ? ' has-error' : '' }}">
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="Email"
                                       id="email" required value="{{ $email }}" />
                                @if(isset($error['email']))
                                    <span class="help-block text-danger">{{ $error['email'] }}</span>
                                @endif
                            </div>

                            <div class="contact-toggle">
                                <span>US Resident</span>
                                <label class="switch">
                                    <input id="onephone" type="checkbox" class="swi" checked>
                                    <span class="sliderp round"></span>
                                </label>
                            </div>

                            <div class="form-group{{ isset($error['phone']) ? ' has-error' : '' }}">
                                <input id="phone" name="phone" class="form-control form-control-lg phone ph" placeholder="Enter your number...." value="{{ $phone }}">
                                <input name="" style="display: none;" class="form-control form-control-lg phb" placeholder="Enter your number....">
                                @if(isset($error['phone']))
                                    <span class="help-block text-danger">{{ $error['phone'] }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ isset($error['reason']) ? ' has-error' : '' }}">
                                <select name="reason" class="form-control form-control-lg" required>
                                    <option value="">Reason</option>
                                    <option value="Already Client" {{ $reason === 'Already Client' ? 'selected' : '' }}>Already Client</option>
                                    <option value="Donate a Product to be Unboxed" {{ $reason === 'Donate a Product to be Unboxed' ? 'selected' : '' }}>Donate a Product to be Unboxed</option>
                                    <option value="General Inquiry" {{ $reason === 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="Support" {{ $reason === 'Support' ? 'selected' : '' }}>Support</option>
                                    <option value="Vendor" {{ $reason === 'Vendor' ? 'selected' : '' }}>Vendor</option>
                                </select>
                                @if(isset($error['reason']))
                                    <span class="help-block text-danger">{{ $error['reason'] }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ isset($error['message']) ? ' has-error' : '' }}">
                                <textarea rows="7" name="message" class="form-control form-control-lg"
                                          placeholder="Message" id="message" required
                                          maxlength="999">{{ $message }}</textarea>
                                @if(isset($error['message']))
                                    <span class="help-block text-danger">{{ $error['message'] }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ isset($error['protection_question']) ? ' has-error' : '' }}">
                                <input type="hidden" name="protection_question" placeholder="Protection question" id="protection_question" />
                                <input name="firstNumber" type="hidden" id="random_number1" value="{{ $random_number1 }}" />
                                <input name="secondNumber" type="hidden" id="random_number2" value="{{ $random_number2 }}" />
                                @if(isset($error['protection_question']))
                                    <span class="help-block text-danger">{{ $error['protection_question'] }}</span>
                                @endif
                            </div>

                            <div class="slidercaptcha card">
                                <div class="card-header">
                                    <span>Drag To Verify</span>
                                </div>
                                <div class="card-body">
                                    <div id="captcha"></div>
                                </div>
                            </div>

                            <div id="qasubmitBtn" class="contact-submit" style="display: none;">
                                <button class="jm-btn jm-btn--orange jm-btn--lg" type="submit">Send Your Message</button>
                            </div>
                        </form>

                        <form id="form_two" class="inquiry contact-form-card" style="display:none;" onsubmit="return checkwork();"
                              action="{{ route('contact.interview') }}" method="post">
                            @csrf

                            <div class="contact-grid-2">
                                <div class="form-group{{ isset($error['name']) ? ' has-error' : '' }}">
                                    <input type="text" class="form-control form-control-lg"
                                           name="name" placeholder="First Name" required
                                           value="{{ old('name', '') }}" />
                                    @if(isset($error['name']))
                                        <span class="help-block text-danger">{{ $error['name'] }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ isset($error['last_name']) ? ' has-error' : '' }}">
                                    <input type="text" class="form-control form-control-lg"
                                           name="last_name" placeholder="Last Name" required
                                           value="{{ old('last_name', '') }}" />
                                    @if(isset($error['last_name']))
                                        <span class="help-block text-danger">{{ $error['last_name'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ isset($error['company_name']) ? ' has-error' : '' }}">
                                <input type="text" class="form-control form-control-lg"
                                       name="company_name" placeholder="Company Name"
                                       value="{{ old('company_name', '') }}" />
                                @if(isset($error['company_name']))
                                    <span class="help-block text-danger">{{ $error['company_name'] }}</span>
                                @endif
                            </div>

                            <div class="contact-toggle">
                                <span>US Resident</span>
                                <label class="switch">
                                    <input id="twophone" type="checkbox" class="swi" checked>
                                    <span class="sliderp round"></span>
                                </label>
                            </div>

                            <div class="form-group{{ isset($error['phone']) ? ' has-error' : '' }}">
                                <input id="phoneb" name="phone" class="form-control form-control-lg phone bph" placeholder="Enter your number...." value="{{ old('phone', '') }}">
                                <input name="" style="display: none;" class="form-control form-control-lg bphb" placeholder="Enter your number....">
                                @if(isset($error['phone']))
                                    <span class="help-block text-danger">{{ $error['phone'] }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ isset($error['email']) ? ' has-error' : '' }}">
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="Email"
                                       id="emailb" required value="{{ old('email', '') }}" />
                                @if(isset($error['email']))
                                    <span class="help-block text-danger">{{ $error['email'] }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ isset($error['bio']) ? ' has-error' : '' }}">
                                <textarea rows="6" name="bio" class="form-control form-control-lg"
                                          placeholder="Bio" id="bio" required
                                          maxlength="999">{{ old('bio', '') }}</textarea>
                                @if(isset($error['bio']))
                                    <span class="help-block text-danger">{{ $error['bio'] }}</span>
                                @endif
                            </div>

                            <div class="contact-work-head">
                                <h5>Show Appearance or Past Experience</h5>
                                <button type="button" id="add_work" class="jm-btn jm-btn--blue jm-btn--sm">Add Work</button>
                            </div>

                            <div class="form-group{{ isset($error['work_name']) ? ' has-error' : '' }}">
                                <div style="height: auto; scroll-behavior: auto; max-height: 600px; overflow: auto; display: none;" id="append" class="form-control thumbnail-sortable"></div>

                                <div class="contact-work-grid">
                                    <input type="text" id="work_name" class="form-control" value="" placeholder="Show or Experience">
                                    <input type="text" id="work_detail" class="form-control" value="" placeholder="Description">
                                    <input type="url" id="work_web" class="form-control" value="" placeholder="https:// or http://">
                                </div>

                                @if(isset($error['work_name']))
                                    <span class="help-block text-danger">{{ $error['work_name'] }}</span>
                                @endif
                            </div>

                            <input type="hidden" id="counter" name="counter" value="1">

                            <div class="form-group{{ isset($error['interview']) ? ' has-error' : '' }}">
                                <textarea rows="7" name="interview" class="form-control form-control-lg"
                                          placeholder="Interview Pitch Concept" id="interview" required>{{ old('interview', '') }}</textarea>
                                @if(isset($error['interview']))
                                    <span class="help-block text-danger">{{ $error['interview'] }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ isset($error['protection_question']) ? ' has-error' : '' }}">
                                <input type="hidden" name="protection_question" placeholder="Protection question" id="protection_questionb" />
                                <input name="firstNumber" type="hidden" id="random_number1b" value="{{ $random_number1 }}" />
                                <input name="secondNumber" type="hidden" id="random_number2b" value="{{ $random_number2 }}" />
                                @if(isset($error['protection_question']))
                                    <span class="help-block text-danger">{{ $error['protection_question'] }}</span>
                                @endif
                            </div>

                            <div class="slidercaptcha card">
                                <div class="card-header">
                                    <span>Drag To Verify</span>
                                </div>
                                <div class="card-body">
                                    <div id="captchab"></div>
                                </div>
                            </div>

                            <div id="qasubmitBtnb" class="contact-submit" style="display: none;">
                                <button class="jm-btn jm-btn--orange jm-btn--lg" type="submit">Send Your Message</button>
                            </div>
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
    function inquiryform() {
        $('#form_one').css('display', '').show();
        $('#form_two').hide();
        $('#map_box').css('display', 'block');
        $('#hed').html('Fill out the form below.');
        document.getElementById('form_one').scrollIntoView({behavior: 'smooth'});
    }

    function inquiryformtwo() {
        $('#form_one').hide();
        $('#form_two').show();
        $('#map_box').hide();
        $('#hed').html('Interview Pitch Concept.');
        document.getElementById('form_two').scrollIntoView({behavior: 'smooth'});
    }

    $('#onephone').change(function () {
        if (this.checked) {
            $('.ph').toggle();
            $('.phb').toggle();
            $('.ph').attr('name', 'phone');
            $('.phb').attr('name', '');
        } else {
            $('.ph').toggle();
            $('.phb').toggle();
            $('.ph').attr('name', '');
            $('.phb').attr('name', 'phone');
        }
    });

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

    function isValidURL(string) {
        var res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
        return (res !== null);
    }

    function checkwork() {
        if ($('#append').children().length > 0) {
            return true;
        }
        alert('Add a work');
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
        var work_name = $('#work_name' + id).val();
        var web = $('#web' + id).val();
        var detail = $('#detail' + id).val();

        if (isValidURL(web)) {
            $('#wset' + id).text(web);
            $('#hset' + id).text(work_name);
            $('#dset' + id).text(detail);
            $('#edit_v' + id).fadeToggle();
            $('#no' + id).fadeToggle();
        } else {
            alert('not valid web link');
        }
    }

    $('#add_work').click(function () {
        var counter = parseInt($('#counter').val());
        counter = counter + 1;

        var work_name = $('#work_name').val();
        var work_web = $('#work_web').val();
        var work_detail = $('#work_detail').val();

        if (work_name != '') {
            if (isValidURL(work_web)) {
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
                        $('#work_name').val('');
                        $('#work_web').val('');
                        $('#work_detail').val('');
                    },
                    error: function (xhr, status, error) {
                        alert(status);
                    }
                });

                $('#append').css('display', '');
                $('#counter').val(counter);
            } else {
                alert('not valid web link');
            }
        }
    });

    @if($open_interview)
        inquiryformtwo();
    @endif

    // Lightweight conversion tracking. It only records to a dataLayer/gtag when
    // one of those is already present, so it adds no new dependency (048).
    (function () {
        function track(eventName, params) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push(Object.assign({event: eventName}, params || {}));

            if (typeof window.gtag === 'function') {
                window.gtag('event', eventName, params || {});
            }
        }

        document.querySelectorAll('[data-conversion]').forEach(function (link) {
            link.addEventListener('click', function () {
                track('contact_cta_click', {cta: link.dataset.conversion});
            });
        });

        @if(isset($success))
            track('contact_form_submitted', {form: 'web_inquiry'});
        @endif
    })();
</script>
@endpush

@include('partials.script_file')