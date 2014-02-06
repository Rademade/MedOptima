MedOptima.prototype.ReservationModel = Backbone.Model.extend({

    _createUrl : '/reservation/create/ajax', //RM_TODO from server

    attributes : {
        visitDate : undefined,
        visitTime : undefined,
        selectedServices : [],
        selectedDoctor : undefined,
        visitorName : undefined,
        visitorPhone : undefined
    },

    initialize : function() {
        this.set('selectedServices', []);
    },

    isValid : function() {
        return !_.any(this.attributes, _.isUndefined);
    },

    save : function() {
        if (!this.isValid()) {
            return false;
        }
        var self = this;
        $.ajax({
            type : 'post',
            url : self._createUrl,
            data : self.toJSON(),
            dataType : 'json',
            success : function(data) {
                //RM_TODO trigger
            },
            error : function() {
                //RM_TODO implement
            }
        });
        return true;
    },

    getUpdateData : function() { //RM_TODO rename
        var self = this;
        var data = {
            date : self.get('visitDate')
        };
        if (self.get('selectedServices').length) {
            _.extend(data, {
                services : self.get('selectedServices').join(',')
            })
        }
        return data;
    }

}, {

});