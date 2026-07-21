import $ from 'jquery';
window.$ = window.jQuery = $;

import 'bootstrap/dist/css/bootstrap.min.css';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';
import 'slick-carousel';

import '@fortawesome/fontawesome-free/css/all.min.css';
import '@fortawesome/fontawesome-free/css/v4-shims.min.css';

// Ported from the CI footer.php — removes the 100px left margin on the nav
// wrapper under 900px so the collapsed navbar doesn't overflow on small
// screens. (assets/js/app.js's .play_slide Slick init was left out: that
// class has no matching element in slider_section.php, confirmed dead code
// same as theme.css/ace-responsive-menu/slider_section1.php.)
$(window).on('load resize', function () {
    if ($(window).width() < 900) {
        $('#otherDiv').removeClass('custom-size');
    }
});