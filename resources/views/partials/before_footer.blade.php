<div class="card bg-primary border-0 rounded-0 p-5">
    <div class="container">
        <div class="row justify-content-between align-items-center text-center text-md-left" data-aos-easing="linear" data-aos-delay="50">
            <div class="col-md-8">
                <h5 class="mb-1">Smart IT that Keeps Your Business Running</h5>
                <p class="mb-0">We to listen to our clients, understand their challenges and provide solutions that make business more efficient.</p>
                <a href="{{ route('contact') }}" class="btn btn btn-outline-light btn-pill">Contact Us</a>
            </div>
            <div class="col-md-4 text-lg-right mt-md-0 mt-3">  
                <h5 class="mb-1" style="text-align: left;">Join Newsletter</h5>               
                <!-- Begin Mailchimp Signup Form -->
                @push('styles')
                    <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
                    <style type="text/css">
                        #mc_embed_signup{background:transparent; clear:left; font:14px Helvetica,Arial,sans-serif; }
                    </style>
                @endpush
                @if(session('newsletter_success'))
                    <div class="alert alert-success" role="status" style="text-align: left;">{{ session('newsletter_success') }}</div>
                @endif
                @php
                    $newsletterErrors = [];
                    $nBag = ($errors ?? null);
                    if ($nBag !== null) {
                        foreach (['FNAME', 'LNAME', 'EMAIL', 'NEWSLETTER_CONSENT'] as $nField) {
                            if ($nBag->has($nField)) {
                                $newsletterErrors[$nField] = $nBag->first($nField);
                            }
                        }
                    }
                @endphp
                @if(count($newsletterErrors))
                    <div class="alert alert-danger" role="alert" style="text-align: left;">
                        <ul class="mb-0" style="padding-left: 1.1rem; list-style: disc;">
                            @foreach(['FNAME' => 'First Name', 'LNAME' => 'Last Name', 'EMAIL' => 'Email Address', 'NEWSLETTER_CONSENT' => 'Consent'] as $field => $label)
                                @if(isset($newsletterErrors[$field]))
                                    <li>{{ $label }}: {{ $newsletterErrors[$field] }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div id="mc_embed_signup">
                    @php($newsletterAction = config('newsletter.action') ?: route('newsletter.subscribe'))
                    <form action="{{ $newsletterAction }}" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"{{ config('newsletter.action') ? ' target="_blank"' : '' }}>
                        @csrf
                        <div id="mc_embed_signup_scroll">
                            <div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
                            <div class="mc-field-group">
                                <label for="mce-FNAME">First Name  <span class="asterisk">*</span></label>
                                <input type="text" value="" name="FNAME" class="required" id="mce-FNAME" autocomplete="given-name" required>
                            </div>
                            <div class="mc-field-group">
                                <label for="mce-LNAME">Last Name  <span class="asterisk">*</span></label>
                                <input type="text" value="" name="LNAME" class="required" id="mce-LNAME" autocomplete="family-name" required>
                            </div>
                            <div class="mc-field-group">
                                <label for="mce-EMAIL">Email Address  <span class="asterisk">*</span></label>
                                <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" autocomplete="email" required>
                            </div>
                            <div class="mc-field-group">
                                <label class="mc-consent" for="mc-embedded-subscribe-consent" style="display: block; text-align: left; margin: 8px 0;">
                                    <input type="checkbox" name="NEWSLETTER_CONSENT" value="I agree" id="mc-embedded-subscribe-consent" class="required" style="width: auto; margin-right: 6px;" required>
                                    I agree to receive the JMOR newsletter and accept the
                                    <a href="{{ route('privacy-policy') }}" target="_blank" rel="noopener">Privacy Policy</a>.
                                    <span class="asterisk">*</span>
                                </label>
                            </div>
                            <div id="mce-responses" class="clear">
                                <div class="response" id="mce-error-response" style="display:none"></div>
                                <div class="response" id="mce-success-response" style="display:none"></div>
                            </div>
                            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_162c559900d0da871b5ac38b9_8ad51d0bdf" tabindex="-1" value=""></div>
                            <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btn btn-outline-light btn-pill"></div>
                        </div>
                    </form>
                </div>
                <!--End mc_embed_signup-->
            </div>
        </div> 
    </div>
</div>