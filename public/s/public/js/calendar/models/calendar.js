MedOptima.prototype.CalendarModel = Backbone.Model.extend({

    attributes : {
        displayedDate           : undefined,
        previousDisplayedDate   : undefined,
        collection              : undefined
    },

    initialize : function(options) {
        _.extend(this, options);
    },

    setDisplayedDate : function(date) {
        date = new Date(date.getFullYear(), date.getMonth());
        var displayedDate = this.get('displayedDate');
        if (_.isUndefined(displayedDate) || !displayedDate.isEqual(date)) {
            this.set('previousDisplayedDate', displayedDate, {silent : true});
            this.set('displayedDate', date, {silent : true});
            this._update();
        }
        return this;
    },

    getDisplayedDate : function() {
        return new Date(this.get('displayedDate').getTime());
    },

    getPreviousDisplayedDate : function() {
        return this.get('previousDisplayedDate');
    },

    switchToNextMonth : function() {
        var date = new Date(this.getDisplayedDate().getTime());
        date.setMonth(date.getMonth() + 1);
        return this.setDisplayedDate(date);
    },

    switchToPreviousMonth : function() {
        var date = new Date(this.getDisplayedDate().getTime());
        date.setMonth(date.getMonth() - 1);
        return this.setDisplayedDate(date);
    },

    _update : function() {
        this.trigger('beforeDisplayedDateChange');
        this.collection.reset();
        var current = this.getDisplayedDate();
        this._addPreviousMonthDays(current);
        this._addCurrentMonthDays(current);
        this._addNextMonthDays(current);
        this.trigger('displayedDateChange');
        return this;
    },

    _addCurrentMonthDays : function(current) {
        var date = new Date(current.getTime());
        date.setMonth(date.getMonth() + 1);
        date.setDate(0);
        var monthDaysCount = date.getDate();
        for (var i = 1; i <= monthDaysCount; ++i) {
            date.setDate(i);
            this.get('collection').add({date : new Date(date.getTime())});
        }
    },

    _addPreviousMonthDays : function(current) {
        var date = new Date(current.getTime());
        var weekday = current.getDay();
        weekday = weekday ? weekday : 7;
        date.setDate(1 - weekday);
        for (var i = 1; i < weekday; ++i) {
            date.setDate(date.getDate() + 1);
            this.get('collection').add({date : new Date(date.getTime())});
        }
    },

    _addNextMonthDays : function(current) {
        var date = new Date(current.getTime());
        date.setMonth(current.getMonth() + 1);
        while (this.get('collection').size() < 6 * 7) {
            this.get('collection').add({date : new Date(date.getTime())});
            date.setDate(date.getDate() + 1);
        }
    }


});