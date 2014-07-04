MedOptima.prototype.CalendarConfig = function() {

    this.calendar = {
        enablePast      : false,
        enableFuture    : true,
        enableToday     : false,
        enableSunday    : false,
        highlightPast   : false,
        highlightFuture : true,
        highlightToday  : true,
        highlightSunday : false,
        slideDuration   : 0
    };

    this.date = {
        states : {
            enabled     : '',
            disabled    : 'off-date',
            highlighted : 'active-date',
            selected    : 'clicked',
            current     : 'current-date',
            weekend     : 'off-date'
        }
    };

    this.navigation  = {
        states : {
            enabled     : '',
            disabled    : 'button-disabled'
        },
        allowPast   : false,
        allowFuture : true
    };

    this.applyDateRules = undefined;

    this.init = function() {

    };

    this.init();

};

