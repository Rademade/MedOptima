$(function(){

    $('input[data-phone]').mask( Med.getCfg().phoneMask );

    $('.jcarousel').jcarousel({

    });

    $('.jcarousel-control-prev').on('jcarouselcontrol:active', function() {
        $(this).removeClass('inactive');
    }).on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        })
        .jcarouselControl({
            target : '-=1'
        });

    $('.jcarousel-control-next')
        .on('jcarouselcontrol:active', function() {
            $(this).removeClass('inactive');
        })
        .on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        })
        .jcarouselControl({
            target : '+=1'
        });


    $('.jcarousel-pagination').jcarouselPagination({
        item : function(page, $el) {
            return '<li><a href="#' + page + '"><img src="' + $el.find('img').attr('src') + '" width="160" height="80"/></a></li>';
        }
    });

    $('.jcarousel-pagination')
        .on('jcarouselpagination:active', 'a', function() {
            $(this).addClass('active');
        })
        .on('jcarouselpagination:inactive', 'a', function() {
            $(this).removeClass('active');
        })
        .jcarouselPagination();

});