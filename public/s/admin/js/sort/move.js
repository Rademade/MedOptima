//####PAGE TREE MOVEING
//ul 
function moveUp(obj) { //starts moveUp function
    last = false;
    first = false;
    if ($(obj).parent().hasClass('last')) {
        $(obj).parent().removeClass('last');
        last = true;
    }
    if ($(obj).parent().prev().hasClass('first')) {
        $(obj).parent().prev().removeClass('first');
        first = true;
    }
    preId = $(obj).parent().prev().attr('id');
    preText = $(obj).parent().prev().html();
    mainMove(obj, preId, preText, TYPE_MOVE_UP, last, first);
}

function moveDown(obj) { //starts moveDown function
    last = false;
    first = false;
    if ($(obj).parent().next().hasClass('last')) {
        $(obj).parent().next().removeClass('last');
        last = true;
    }
    if ($(obj).parent().hasClass('first')) {
        $(obj).parent().removeClass('first');
        first = true;
    }
    preId = $(obj).parent().next().attr('id');
    preText = $(obj).parent().next().html();
    mainMove(obj, preId, preText, TYPE_MOVE_DOWN, last, first);
}
//end ul
//Table


function moveUpTR(obj) { //starts moveUp function
    preId = $(obj).parent().parent().prev().attr('id');
    preText = $(obj).parent().parent().prev().html();
    mainMoveTr(obj, preId, preText, TYPE_MOVE_UP);
}

function moveDownTR(obj) { //starts moveDown function
    preId = $(obj).parent().parent().next().attr('id');
    preText = $(obj).parent().parent().next().html();
    mainMoveTr(obj, preId, preText, TYPE_MOVE_DOWN);
}

function mainMoveTr(obj, preId, preText, typeMove) {
    currentId = $(obj).parent().parent().attr('id');
    $(obj).parent().parent().attr('id', preId);
    curentText = $(obj).parent().parent().html();
    switch (typeMove) {
    case TYPE_MOVE_UP:
        $(obj).parent().parent().prev().replaceWith('<tr id="' + currentId + '">' + curentText + '</tr>');
        break;
    case TYPE_MOVE_DOWN:
        $(obj).parent().parent().next().replaceWith('<tr id="' + currentId + '">' + curentText + '</tr>');
        break;
    }
    $(obj).parent().parent().html(preText);
    ReDecorateZebra();
    ReBuildArrow();
    $.ajax({
        type: "POST",
        url: $('#moveUrl').val(),
        data: {
            'curentId': currentId,
            'withId': preId,
            'typeMove': typeMove,
            type: TYPE_MOVE
        },
        success: function(result) {

        }
    });
}
//endtable;

function mainMove(obj, preId, preText, typeMove, last, first) {
    currentId = $(obj).parent().attr('id');
    addClasses = '';
    $(obj).parent().attr('id', preId);
    curentText = $(obj).parent().html();
    level = $(obj).parent()[0].className.match(/.*level_([0-9]).*/); //geting level num
    addClasses += (last && typeMove == TYPE_MOVE_DOWN) ? 'last' : '';
    addClasses += (first && typeMove == TYPE_MOVE_UP) ? 'first' : '';
    replaceText = '<li class="level_' + level[1] + ' ' + addClasses + '" id="' + currentId + '">' + curentText + '</li>'; //what clicked
    switch (typeMove) {
    case TYPE_MOVE_UP:
        {
            $(obj).parent().prev().replaceWith(replaceText);
            if (last) $(obj).parent().addClass('last');
        }
        break;
    case TYPE_MOVE_DOWN:
        {
            $(obj).parent().next().replaceWith(replaceText);
            if (first) $(obj).parent().addClass('first');
        }
        break;
    }
    $(obj).parent().html(preText)[last]; //Magic
    ReDecorateZebra();
    ReBuildArrow();
    $.ajax({
        type: "POST",
        url: $('#moveUrl').val(),
        data: {
            'curentId': currentId,
            'withId': preId,
            type: TYPE_MOVE,
            'typeMove': typeMove
        },
        success: function(result) {

        }
    });
}

function ReDecorateZebra() {
    i = 1;
    if ($('.tree')[0] !== undefined) {
        $('.tree li').each(function() {
            $(this).removeClass('grey').removeClass('light');
            i % 2 == 0 ? $(this).addClass('light') : $(this).addClass('grey');
            i++;
        });
    }

    if ($('.zebra')[0] !== undefined) {
        $('.zebra tbody tr').each(function() {
            $(this).removeClass('grey').removeClass('light');
            i % 2 == 0 ? $(this).addClass('light') : $(this).addClass('grey');
            i++;
        });
    }
}

function ReBuildArrow() {
    if ($('.tree')[0] !== undefined) {
        i = 1;
        classStr = 'level_';
        $('.tree .moveUp').replaceWith('<a href="javascript:void(0);" class="clearMoveUp"><img src="/static/admin/images/nobg.png" alt="" width="9" height="8" /></a>');
        $('.tree .moveDown').replaceWith('<a href="javascript:void(0);" class="clearMoveDown"><img src="/static/admin/images/nobg.png" alt="" width="9" height="8" /></a>');
        //make clear
        while ($('.level_' + i)[0] !== undefined) {
            j = 1;
            liCount = $('.level_' + i).length;
            $('.level_' + i).each(function() { //set new arrow
                if (j != liCount && !$(this).hasClass('last')) {
                    $(this).children('.clearMoveDown').replaceWith('<a href="javascript:void(0);" class="moveDown"><img class="" src="/static/admin/images/arrow_blue_down.gif" alt="" width="10" height="15" /></a>');
                }
                if (j != 1 && !$(this).hasClass('first')) {
                    $(this).children('.clearMoveUp').replaceWith('<a href="javascript:void(0);" class="moveUp"><img class="" src="/static/admin/images/arrow_blue_up.gif" alt="" width="10" height="15" /></a>');
                }
                j++;
            });
            i++;
        }
        $('.moveUp').bind('click', function() {
            moveUp(this);
        });
        $('.moveDown').bind('click', function() {
            moveDown(this);
        });
    }

    if ($('.zebra')[0] !== undefined) {
        i = 1;
        current = $('#page_current').val();
        first = $('#page_first').val();
        last = $('#page_last').val();
        currentItemCount = $('#page_currentItemCount').val();
        $('.moveUpTr').replaceWith('<a href="javascript:void(0);" class="clearMoveUp"><img src="/static/admin/images/nobg.png" alt="" width="9" height="8" /></a>');
        $('.moveDownTr').replaceWith('<a href="javascript:void(0);" class="clearMoveDown"><img src="/static/admin/images/nobg.png" alt="" width="9" height="8" /></a>');
        $('.zebra tbody tr').each(function() {
            if ((current != first) || (i != 1)) {
                $(this).children('td').children('.clearMoveUp').replaceWith('<a href="javascript:void(0);" class="moveUpTr"><img src="/static/admin/images/arrow_blue_up.gif" alt="" width="10" height="15"></a>');
            }
            if ((current != last) || (i != currentItemCount)) {
                $(this).children('td').children('.clearMoveDown').replaceWith('<a href="javascript:void(0);" class="moveDownTr"><img src="/static/admin/images/arrow_blue_down.gif" alt="" width="10" height="15"></a>');
            }
            i++;
        });
        $('.moveUpTr').bind('click', function(){
            moveUpTR(this);
        });
        $('.moveDownTr').bind('click', function(){
            moveDownTR(this);
        });
    }
};