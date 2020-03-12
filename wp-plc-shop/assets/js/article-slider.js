jQuery(function() {
    jQuery('.plc-shop-swiper-container').each(function () {
        var iSliderPerView = jQuery(this).attr('data-slides-per-view');
        var sSliderID = jQuery(this).attr('id');
        var mySwiper = new Swiper(jQuery(this), {
            speed: 400,
            spaceBetween: 8,
            slidesPerView: iSliderPerView,
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 1,
                    spaceBetween: 8
                },
                // when window width is >= 480px
                480: {
                    slidesPerView: 1,
                    spaceBetween: 8
                },
                // when window width is >= 640px
                640: {
                    slidesPerView: iSliderPerView,
                    spaceBetween: 8
                }
            }
        });
    });
});