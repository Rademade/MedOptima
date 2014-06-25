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
        _.bindAll(this, '_synced');
        this.bind('sync', this._synced);
    },

    save : function() {
        MedOptima.prototype.ReservationModel.__super__.save.apply(this, arguments);
        this.trigger('save', this);
    },

    isValid : function() {
        return !_.any(this.attributes, _.isUndefined);
    },
    
    getUpdateData : function() {
        var self = this;
        var data = {
            date : self.get('visitDate').getShortDateString()
        };
        if (self.get('selectedServices').length) {
            _.extend(data, {
                services : self.get('selectedServices').join(',')
            })
        }
        return data;
    },

    toJSON: function() {
        var json = this.omit('visitDate', 'selectedDoctor');
        return _.extend(json, {
            visitDate : this.get('visitDate').getShortDateString(),
            selectedDoctor : this.get('selectedDoctor').get('id')
        });
    },

    setSelectedDoctor : function(doctor) {
        this.attributes.selectedDoctor = doctor;
        this.trigger('change:selectedDoctor', doctor);
    },

    _synced : function() {
        if (this.get('status') != undefined && !this.get('status')) {
            this.trigger('error');
        }
    }

});