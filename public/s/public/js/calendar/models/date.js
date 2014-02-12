MedOptima.prototype.CalendarWidgetDateModel = Backbone.Model.extend({

    attributes : {
        day : undefined,
        month : undefined,
        year : undefined
    }, //RM_TODO store as object; add attributes as future and past

    initialize : function(params) {
        if (params.date instanceof Date) {
            this.set('day', params.date.getDate());
            this.set('month', params.date.getMonth());
            this.set('year', params.date.getFullYear());
            this.date = params.date;
        }
    },

    isToday : function() {
        var today = new Date();
        return this.get('day') == today.getDate()
            && this.get('month') == today.getMonth()
            && this.get('year') == today.getFullYear();
    },

    isSunday : function() {
        return this.getDate().getDay() == 0;
    },

    getDate : function() {
        return new Date(this.get('year'), this.get('month'), this.get('day'));
    }

});