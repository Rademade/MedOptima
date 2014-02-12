MedOptima.prototype.ReservationModel = Backbone.Model.extend({

    urlRoot: '/api/reservation',

    defaults : {
        id                  : undefined,
        visitDate           : undefined,
        visitTime           : undefined,
        selectedServices    : [],
        selectedDoctor      : undefined,
        visitorName         : undefined,
        visitorPhone        : undefined
    },

    initialize : function() {
        this.set('selectedServices', []);
    },

    isValid : function() {
        return !_.any(this.attributes, _.isUndefined);
    },
    
    save: function() {
        MedOptima.prototype.ReservationModel.__super__.save.apply(this, arguments);
        this.trigger('save');
    },

    getUpdateData : function() { //RM_TODO rename
        var self = this;
        var data = {
            date : self.get('visitDate').formatShortDate()
        };
        if (self.get('selectedServices').length) {
            _.extend(data, {
                services : self.get('selectedServices').join(',')
            })
        }
        return data;
    },

    toJSON: function() {
        var self = this;
        return _.extend(_.clone(this.attributes), {
            visitDate : self.get('visitDate').formatShortDate(),
            visitTime : self.get('visitTime').formatTime()
        });
    }

});