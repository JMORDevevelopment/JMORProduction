<footer id="printme">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="foo_logo">
                    <img class="img-fluid" src="{{ asset('assets/images/foo_logo.png') }}" alt="">
                </div>
            </div>

            @php
                $footerSettings = \App\Models\Setting::orderBy('id')->get();
            @endphp

            <div class="col-md-5">
                <ul class="list-unstyled list-inline">
                    <li class="list-inline-item">
                        <a href="{{ $footerSettings[11]->value ?? '#' }}">
                            <img class="img-fluid" src="{{ asset('assets/images/insta.png') }}" alt="">
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ $footerSettings[3]->value ?? '#' }}">
                            <img class="img-fluid" src="{{ asset('assets/images/facebook.png') }}" alt="">
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ $footerSettings[4]->value ?? '#' }}">
                            <img class="img-fluid" src="{{ asset('assets/images/twitter.png') }}" alt="">
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ $footerSettings[5]->value ?? '#' }}">
                            <img class="img-fluid" src="{{ asset('assets/images/youtube.png') }}" alt="">
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ $footerSettings[6]->value ?? '#' }}">
                            <img class="img-fluid" src="{{ asset('assets/images/linkedin.png') }}" alt="">
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ $footerSettings[7]->value ?? '#' }}">
                            <img class="img-fluid" src="{{ asset('assets/images/social.png') }}" alt="">
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-md-4"></div>

            <div class="col-md-12">
                <p style="color:#000!important;text-align:center;">
                    &copy; The JMOR Connection, Inc. {{ date('Y') }}<br>
                    Engineering Technology to Grow your Business. &reg; All Rights Reserved.<br><br>
                    <a href="{{ url('refund-policy') }}" target="_blank" style="color:#014073;">Refund Policy</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ url('privacy-policy') }}" target="_blank" style="color:#014073!important;">Privacy Policy</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ url('terms-and-conditions') }}" target="_blank" style="color:#014073;">Terms and Conditions</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ url('sitemap.xml') }}" target="_blank" style="color:#014073;">Sitemap</a>
                    <br><br>
                    Proudly Designed, Hosted & Maintained by Neighborhood Publications<br>
                    We Give your Business a Voice&trade;
                </p>
            </div>
        </div>
    </div>
</footer>