$(function() {
    $('#SumbitSeriesForm').click(function() {
        $('#SeriesForm').submit();
    });
    $('.moveUpTr').bind('click', function() {
        moveUpTR(this);
    });
    $('.moveDownTr').bind('click', function() {
        moveDownTR(this);
    });
    $('.moveUp').bind('click', function() {
        moveUp(this);
    });
    $('.moveDown').bind('click', function() {
        moveDown(this);
    });

});