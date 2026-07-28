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

$(window).on('load resize', function () {
    if ($(window).width() < 900) {
        $('#otherDiv').removeClass('custom-size');
    }
});

(function () {
    var timer;

    $('.jm-header__navlist > li[data-megamenu]').each(function () {
        var $li = $(this);
        var menuId = $li.data('megamenu');
        var $menu = $('.jm-megamenu[data-megamenu="' + menuId + '"]');
        if ($menu.length === 0) return;

        $li.on('mouseenter', function () {
            clearTimeout(timer);
            $('.jm-megamenu').hide();
            $menu.css('display', 'block');
        });

        $li.on('mouseleave', function () {
            timer = setTimeout(function () {
                $menu.css('display', 'none');
            }, 200);
        });

        $menu.on('mouseenter', function () {
            clearTimeout(timer);
        });

        $menu.on('mouseleave', function () {
            timer = setTimeout(function () {
                $menu.css('display', 'none');
            }, 200);
        });
    });
})();
