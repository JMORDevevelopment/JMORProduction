{{-- Foundation/vendor scripts: @prepend keeps them first in the scripts stack (as in CI, where jQuery loaded at the start). --}}
@prepend('scripts')
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
    if ($('#captcha').length) {
        var captcha = sliderCaptcha({
            id: 'captcha',
            crossOrigin: true,
            headers: { 'Access-Control-Allow-Origin': 'https://imgs.blazor.zone' },
            onSuccess: function() {
                $('#qasubmitBtn').show();
                if ($('#random_number1').length) {
                    var num_one = $('#random_number1').val();
                    var num_two = $('#random_number2').val();
                    var protection_question = Number(num_one) + Number(num_two);
                    $('#protection_question').val(protection_question);
                }
                setTimeout(function() {
                    resetCaptcha();
                }, 300000);
            },
            onFail: function() {
                $('#qasubmitBtn').hide();
            },
            onRefresh: function() {
                $('#qasubmitBtn').hide();
            }
        });
        var resetCaptcha = function() {
            captcha.reset();
            $('#qasubmitBtn').hide();
        };
    }
    if ($('#captchab').length) {
        var captchab = sliderCaptcha({
            id: 'captchab',
            crossOrigin: true,
            headers: { 'Access-Control-Allow-Origin': 'https://imgs.blazor.zone' },
            onSuccess: function() {
                $('#qasubmitBtnb').show();
                if ($('#random_number1b').length) {
                    var num_one = $('#random_number1b').val();
                    var num_two = $('#random_number2b').val();
                    var protection_question = Number(num_one) + Number(num_two);
                    $('#protection_questionb').val(protection_question);
                }
                setTimeout(function() {
                    resetCaptchaB();
                }, 300000);
            },
            onFail: function() {
                $('#qasubmitBtnb').hide();
            },
            onRefresh: function() {
                $('#qasubmitBtnb').hide();
            }
        });
        var resetCaptchaB = function() {
            captchab.reset();
            $('#qasubmitBtnb').hide();
        };
    }
</script>
@endpush