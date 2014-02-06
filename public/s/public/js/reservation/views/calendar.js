MedOptima.prototype.ReservationViewCalendar = Backbone.View.extend({

    _selectedDate : undefined,

    initialize : function() {
        this._initOldCalendar(); //RM_TODO jquery datepicker
//        this._initDatePicker();
    },

    getDate : function() {
        return this._selectedDate;
    },

    _initDatePicker : function() {
        var self = this;
        this.$el.datepicker({
            dateFormat : "dd.mm.yy",
            numberOfMonths : 1,
            firstDay : 1,
            minDate : '+0d',
            onSelect : function(date) {
                self._selectedDate = date.toLocaleString();
                self.trigger('dateClicked', self.$el.find('a.ui-state-active').parent('td'));
            },
            onChangeMonthYear : function(year, month) {
                console.log(arguments);
            }
        });
    },

    _initOldCalendar : function() {
        var self = this;
        this.$el.find('.calendar-box-date-item-value').click(function() {
            var $container = $(this).parent('.calendar-box-date-item');
            self._selectedDate = $(this).text() + '.02.2014';
            self.$el.find('.calendar-box-date-item').removeClass('clicked');
            $container.toggleClass('clicked');
            self.trigger('dateClicked', $container);
        });
    }

}, {

    init : function($calendar) {
        return new MedOptima.prototype.ReservationViewCalendar({
            el : $calendar
        });
    }

});