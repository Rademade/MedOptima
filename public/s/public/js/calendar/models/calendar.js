MedOptima.prototype.CalendarWidgetModel = Backbone.Model.extend({

    attributes : {
        currentMonth : undefined, //RM_TODO store objects
        currentMonthYear : undefined,
        prevMonth : undefined,
        prevMonthYear : undefined
    },

    today : new Date(),
    date : undefined,

    setCurrentDate : function(date) {
        date = new Date(date.getFullYear(), date.getMonth()); //RM_TODO prototype method for date equal, greater than, less etc
        if (_.isUndefined(this.date) || date.getTime() !== this.date.getTime()) { //RM_TODO move to defaults
            this.trigger('beforeMonthChange');
            this.date = date;
            this.set('prevMonth', this.get('currentMonth'));
            this.set('prevMonthYear', this.get('currentMonthYear'));
            this.set('currentMonth', date.getMonth());
            this.set('currentMonthYear', date.getFullYear());
            this.trigger('monthChange');
        }
    },

    getCurrentDate : function() {
        return new Date(this.get('currentMonthYear'), this.get('currentMonth'));
    },

    isMovingNext : function() {
        return this.get('currentMonth') < this.get('prevMonth')
            || this.get('currentMonthYear') < this.get('prevMonthYear');
    },

    isPast : function() {
        return this.today.getMonth() > this.get('currentMonth')
            || this.today.getFullYear() > this.get('currentMonthYear');
    },

    isFuture : function() {
        return this.today.getMonth() < this.get('currentMonth')
            || this.today.getFullYear() < this.get('currentMonthYear');
    }

}, {

});