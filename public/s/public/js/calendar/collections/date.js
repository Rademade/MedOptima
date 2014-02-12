MedOptima.prototype.CalendarWidgetDateCollection = Backbone.Collection.extend({

    model : MedOptima.prototype.CalendarWidgetDateModel,

    update : function(params) {
        var current = new Date(params.currentMonthYear, params.currentMonth);
        this._addPrevMonthDays(current);
        this._addCurrentMonthDays(current);
        this._addNextMonthDays(current);
        return this;
    },

    _addCurrentMonthDays : function(current) {
        var date = new Date(current.getTime());
        date.setMonth(date.getMonth() + 1);
        date.setDate(0);
        var monthDaysCount = date.getDate();
        for (var i = 1; i <= monthDaysCount; ++i) {
            date.setDate(i);
            this.add({date : date});
        }
    },

    _addPrevMonthDays : function(current) {
        var date = new Date(current.getTime());
        var weekday = current.getDay();
        weekday = weekday ? weekday : 7;
        date.setDate(1 - weekday);
        for (var i = 1; i < weekday; ++i) {
            date.setDate(date.getDate() + 1);
            this.add({date : date});
        }
    },

    _addNextMonthDays : function(current) {
        var date = new Date(current.getTime());
        date.setMonth(current.getMonth() + 1);
        while (this.size() < 6 * 7) {
            this.add({ date : date });
            date.setDate(date.getDate() + 1);
        }
    }

}, {

    init : function(params) {
        var self = new MedOptima.prototype.CalendarWidgetDateCollection();
        self.update(params);
        return self;
    }

});