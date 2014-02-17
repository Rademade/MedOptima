MedOptima.prototype.CalendarDateModel = Backbone.Model.extend({

    defaults : {
        date        : undefined,
        enabled     : false,
        highlighted : false,
        selected    : false,
        current     : false
    },

    initialize : function() {
        if (_.isUndefined(this.get('date')) ) {
            this.set('date', new Date);
        }
    },

    is : function(state) {
        return this.get(state);
    },

    getDate : function() {
        return this.get('date');
    },

    toJSON : function() {
        return {
            day : this.getDate().getDate()
        };
    },

    isEqual : function(other) {
        return this.getDate().isEqual(other.getDate());
    }

});