MedOptima.prototype.ReservationModel = Backbone.Model.extend({

    methodToUrl : {
        'create' : '/reservation/create/ajax',
        'update' : '/reservation/update/ajax',
        'delete' : '/reservation/delete/ajax'
    },

    defaults : {
        id                  : null,
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


//    sync : function(method, model, options) {
//        if (model.methodToUrl && model.methodToUrl[method.toLowerCase()]) {
//            options = options || {};
//            options.url = model.methodToUrl[method.toLowerCase()];
//        }
//        return Backbone.sync.apply(this, arguments);
//    },

    //RM_TODO via native backbone save. Use REST routes
    save : function() {
        this.trigger('save');
        var self = this;
        if (this.isValid()) {
            $.ajax({
                type : 'post',
                url : self.methodToUrl['create'],
                data : self.toJSON(),
                dataType : 'json',
                success : function(data) {
                    if (data.status !== 0) {
                        self.set('id', data.id);
                        self.trigger('saveSuccess');
                    } else {
                        self.trigger('saveError');
                    }
                },
                error : function() {
                    self.trigger('saveError');
                }
            });
        } else {
            self.trigger('saveError');
        }
    },

    //TODO via native backbone->remove(). Use REST routes
    remove : function() {
        this.trigger('remove');
        var self = this;
        if (this.get('id') !== 0) {
            $.ajax({
                type : 'post',
                url : self.methodToUrl['delete'],
                data : {id : self.get('id')},
                dataType : 'json',
                success : function(data) {
                    if (data.status !== 0) {
                        self.set('id', data.id);
                        self.trigger('removeSuccess');
                    } else {
                        self.trigger('removeError');
                    }
                },
                error : function() {
                    self.trigger('removeError');
                }
            })
        } else {
            self.trigger('removeError');
        }
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