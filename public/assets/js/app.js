"use strict";

$('.play_slide').slick({
        dots: false,
        arrows: true,
        infinite: true,
        slidesToShow: 1,
        autoplay: false,
        slidesToScroll: 1,

        responsive: [{
            breakpoint: 992,
            settings: {
                dots: false,
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 768,
            settings: {
                dots: false,
                arrows: true,
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 480,
            settings: {
                dots: false,
                arrows: true,
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });