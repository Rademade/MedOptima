$(function() {

    var menuOpen = function($el) {
        $el.next().toggle(0);
        if (!$el.hasClass('current')) {
            $el.addClass('current');
        } else {
            $el.removeClass('current');
        }
    };

    $('.submenu-link') .bind('click', function() {
        menuOpen($(this));
    });

    (function() {
        var $current = $('a.current');
        $current.closest('.submenu')
            .prev()
            .addClass('current');
        if ($('.submenu-link.current')) {
            menuOpen(
                $current.closest('.submenu').prev()
            );
        }
    })();

});