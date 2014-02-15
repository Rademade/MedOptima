(function($) {

    $.scrollTo = function($el, speed) {
        if (!$el.visible()) {
            speed || (speed = 500);
            $(document.body).animate({
                scrollTop : $el.offset().top
            }, speed);
        }
    }

})(jQuery);