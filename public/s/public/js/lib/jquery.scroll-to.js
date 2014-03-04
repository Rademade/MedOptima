(function($) {

    $.scrollTo = function($el, speed, top) {
        if (!$el.visible() && $el.is(':visible')) {
            speed || (speed = 500);
            $(document.body).animate({
                scrollTop : top ? top : $el.offset().top
            }, speed);
        }
    }

})(jQuery);