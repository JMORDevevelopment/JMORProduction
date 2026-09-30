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
                <div class="row" style="justify-content: center;">
                    <div class="col-lg-2 mb-5 mb-lg-0 text-center">
                        <div class="contactIcons text-primary mb-2">
                            <i class="fa fa-life-ring" onclick="inquiryform()"></i>
                        </div>
                        <h3 class="h5">Web Inquiry</h3>
                        <p class="mb-0" style="cursor:pointer;" onclick="inquiryform()">Inquire Now</p>
                    </div>
                    <div class="col-lg-2 mb-5 mb-lg-0 text-center">
                        <div class="contactIcons text-primary mb-2">
                            <i class="fa fa-microphone" onclick="inquiryformtwo()"></i>
                        </div>
                        <h3 class="h5">Talk Show Guest</h3>
                        <p class="mb-0" style="cursor:pointer;" onclick="inquiryformtwo()">Apply Now</p>
                    </div>
                    <div class="col-lg-2 mb-5 mb-lg-0 text-center">
                        <div class="contactIcons text-primary mb-2">
                            <i class="fa fa-commenting" onclick="inquiryform()" style="cursor:pointer;"></i>
                        </div>
                        <h3 class="h5">Live Support</h3>
                        <p class="mb-0" style="cursor:pointer;" onclick="inquiryform()">Chat Now</p>
                    </div>
                    <div class="col-lg-2 mb-5 mb-lg-0 text-center">
                        <div class="contactIcons text-primary mb-2">
                            <i class="fa fa-location-arrow" id="viewadress" style="cursor:pointer;"></i>
                        </div>
                        <h3 class="h5">Address</h3>
                        <p class="mb-0"><a id="viewadres" style="cursor:pointer;">View Address</a></p>
                        <p class="mb-0 viewadres" style="display:none;">{!! nl2br(e(str_replace(['<br>', '<br/>', '<br />'], "\n", $address))) !!}</p>
                    </div>
                    <div class="col-lg-2 mb-5 mb-lg-0 text-center">
                        <div class="contactIcons text-primary mb-2">
                            <i class="fa fa-phone" id="viewnmbrr" style="cursor:pointer;"></i>
                        </div>
                        <h3 class="h5">Phone Number</h3>
                        <a id="viewnmbr" style="cursor:pointer;">View Number</a>
                        <p class="mb-0 viewnmbr" style="display:none;">{{ $phone_number }}</p>
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
                    </div>
                    <div class="col-lg-7">
                        @if(isset($success))
                            <div class="alert alert-success alert-dismissible">
                                {{ $success }}
                            </div>
                        @endif

                        <form id="form_one" class="inquiry" action="{{ route('contact.submit') }}" method="post">
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

                            <p>US Resident</p>
                            <label class="switch">
                                <input id="onephone" type="checkbox" class="swi" checked>
                                <span class="sliderp round"></span>
                            </label>

                            <div class="form-group mb-4{{ isset($error['phone']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <input id="phone" name="phone" style="display: '';" class="form-control form-control-lg phone ph" placeholder="Enter your number...." value="{{ $phone }}">
                                    <input id="" name="" style="display: none;" class="form-control form-control-lg phb" placeholder="Enter your number....">
                                    @if(isset($error['phone']))
                                        <span class="help-block text-danger">{{ $error['phone'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-4{{ isset($error['reason']) ? ' has-error' : '' }}">
                                <div class="controls">
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

                        <form id="form_two" class="inquiry" style="display:none;" onsubmit="return checkwork();"
                              action="{{ route('contact.interview') }}" method="post">
                            @csrf

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
                                        <input id="phoneb" name="phone" style="display: '';" class="form-control form-control-lg phone bph" placeholder="Enter your number...." value="{{ old('phone', '') }}">
                                        <input id="" name="" style="display: none;" class="form-control form-control-lg bphb" placeholder="Enter your number....">
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
                                    <button type="button" id="add_work" class="btn btn-primary pull-right">Add Work</button>
                                </div>
                            </div>

                            <div class="form-group mb-4{{ isset($error['work_name']) ? ' has-error' : '' }}">
                                <div class="controls">
                                    <div style="height: auto; scroll-behavior: auto; max-height: 600px; overflow: auto; display: none;" id="append" class="form-control thumbnail-sortable"></div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" id="work_name" class="form-control" value="" placeholder="Show or Experience ">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" id="work_detail" class="form-control" value="" placeholder="Description">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="url" id="work_web" class="form-control" value="" placeholder="https:// or http://">
                                        </div>
                                    </div>

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
                                              style="resize:none">{{ old('interview', '') }}</textarea>
                                    @if(isset($error['interview']))
                                        <span class="help-block text-danger">{{ $error['interview'] }}</span>
                                    @endif
                                </div>
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

                            <div id="qasubmitBtnb" class="text-center" style="display: none;">
                                <button class="btn btn-lg btn-primary py-3 mt-3 px-4 btn-pill" type="submit">Send Your Message</button>
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

    function reveal(selector, hideId) {
        document.querySelectorAll(selector).forEach(function (el) {
            el.style.display = 'block';
        });
        var link = document.getElementById(hideId);
        if (link) {
            link.style.display = 'none';
        }
    }

    ['viewadres', 'viewadress'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function () {
                reveal('.viewadres', 'viewadres');
            });
        }
    });

    ['viewnmbr', 'viewnmbrr'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function () {
                reveal('.viewnmbr', 'viewnmbr');
            });
        }
    });

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
</script>
@endpush

@include('partials.script_file')
