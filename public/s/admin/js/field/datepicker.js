(function(){

    AdminkaWork.prototype.datepicker = function(selector, withTime) {
        if (withTime == 1) {
            $(selector).datetimepicker({
                timeFormat: 'HH:mm',
                stepMinute: 5
            });
        } else {
            $(selector).datepicker({
                dateFormat: 'dd.mm.yy'
            });
        }
    };

})();