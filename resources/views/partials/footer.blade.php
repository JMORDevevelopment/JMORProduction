@php
    $topSettings = \App\Models\Setting::orderBy('id')->get();
@endphp

<footer class="jm-footer" role="contentinfo">
    <div class="container">
        <div class="row" style="row-gap:36px">
            <div class="col-lg-3 col-md-6">
                <img src="{{ asset('assets/images/logo.png') }}" alt="The JMOR Connection, Inc." class="jm-footer__logo">
                <p class="jm-footer__tagline">Engineering Technology to Grow your Business.&reg;</p>
                <ul class="jm-footer__socials" aria-label="Footer social links">
                    <li><a href="{{ $topSettings[11]->value ?? 'https://www.instagram.com/gosocialjmor/' }}"><img
                                src="{{ asset('assets/images/insta.png') }}" alt="Instagram"></a></li>
                    <li><a href="{{ $topSettings[3]->value ?? 'https://www.facebook.com/JMORConnection/' }}"><img
                                src="{{ asset('assets/images/facebook.png') }}" alt="Facebook"></a></li>
                    <li><a href="{{ $topSettings[4]->value ?? 'https://twitter.com/JMORCONNECTION' }}"><img
                                src="{{ asset('assets/images/twitter.png') }}" alt="Twitter"></a></li>
                    <li><a href="{{ $topSettings[5]->value ?? '#' }}"><img
                                src="{{ asset('assets/images/youtube.png') }}" alt="YouTube"></a></li>
                    <li><a href="{{ $topSettings[6]->value ?? 'https://www.linkedin.com/company/2623706/' }}"><img
                                src="{{ asset('assets/images/linkedin.png') }}" alt="LinkedIn"></a></li>
                    <li><a href="{{ $topSettings[7]->value ?? 'https://www.patreon.com/jmor' }}"><img
                                src="{{ asset('assets/images/social.png') }}" alt="Patreon"></a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h4 class="jm-footer__title">Solutions</h4>
                <ul class="jm-footer__links">
                    <li><a href="{{ url('it-service-providers-in-new-jersey-for-homes-and-businesses') }}">IT Services New Jersey</a></li>
                    <li><a href="{{ url('network-cyber-security-internet-safety') }}">Network Cyber Security</a></li>
                    <li><a href="{{ url('hardware-firewalls-and-utm-devices') }}">Hardware Firewalls &amp; UTM</a></li>
                    <li><a href="{{ url('antivirus-malware-ransomware-vunerability-endpoint-management-solutions') }}">Endpoint Protection</a></li>
                    <li><a href="{{ url('ai-solutions-for-businesses-consumers-safe-secure-ai-integrations-in-nj-and-beyond') }}">AI Solutions</a></li>
                    <li><a href="{{ url('technical-relocation-services') }}">Technical Relocation</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h4 class="jm-footer__title">Company</h4>
                <ul class="jm-footer__links">
                    <li><a href="{{ url('about-us') }}">About us</a></li>
                    <li><a href="{{ url('our-mission') }}">Our Mission</a></li>
                    <li><a href="{{ url('why-choose-jmor') }}">Why choose JMOR?</a></li>
                    <li><a href="{{ url('testimonials') }}">Testimonials</a></li>
                    <li><a href="{{ url('media-relations') }}">Media Relations</a></li>
                    <li><a href="{{ url('contact') }}">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h4 class="jm-footer__title">Resources</h4>
                <ul class="jm-footer__links">
                    <li><a href="{{ url('technology-guides-it-resources-the-jmor-connection-inc') }}">Technology Guides</a></li>
                    <li><a href="{{ url('the-jmor-blog') }}">Blog</a></li>
                    <li><a href="{{ url('case-studies') }}">Case studies</a></li>
                    <li><a href="{{ url('jmor-shows') }}">JMOR Shows</a></li>
                    <li><a href="{{ url('events') }}">Events</a></li>
                    <li><a href="{{ url('the-jmor-store') }}">The JMOR Store</a></li>
                </ul>
            </div>
        </div>

        <div class="jm-footer__bottom">
            <div>&copy; The JMOR Connection, Inc. {{ date('Y') }} &middot; All Rights Reserved.</div>
            <div class="jm-footer__legal">
                <a href="{{ url('refund-policy') }}">Refund Policy</a>
                <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
                <a href="{{ url('terms') }}">Terms and Conditions</a>
                <a href="{{ url('sitemap') }}">Sitemap</a>
            </div>
        </div>

        <div class="jm-footer__credit">
            Proudly Designed, Hosted &amp; Maintained by Neighborhood Publications &mdash; We Give your Business a Voice&trade;
        </div>
    </div>
</footer>
