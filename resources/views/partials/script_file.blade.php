<!-- JS Script Files -->
<!-- Global Vendor -->
<script src="{{ asset('assets_2/vendors/jquery.min.js') }}"></script>
<script src="{{ asset('assets_2/vendors/jquery.migrate.min.js') }}"></script>
<script src="{{ asset('assets_2/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets_2/vendors/aos/aos.js') }}"></script>

<!-- Components Vendor --> 
<script src="{{ asset('assets_2/vendors/slick-carousel/slick.min.js') }}"></script>
<script src="{{ asset('assets_2/vendors/ace-responsive-menu/ace-responsive-menu.js') }}"></script>
<!-- Plugin Initialize -->
<script src="{{ asset('assets_2/js/global.js') }}"></script>
<script src="{{ asset('assets_2/vendors/carousel.js') }}"></script>

<!-- Slider Captcha -->
<script src="{{ asset('assets/plugins/image-puzzle-slider-captcha/disk/longbow.slidercaptcha.min.js') }}"></script>
<script>
    var captcha = sliderCaptcha({
        id: 'captcha',
        onSuccess: function() {
            $('#qasubmitBtn').show();
        }
    });
</script>