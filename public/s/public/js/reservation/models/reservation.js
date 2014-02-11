MedOptima.prototype.ReservationModel = Backbone.Model.extend({

    _saveUrl : '/reservation/save/ajax',
    _cancelUrl : '/reservation/remove/ajax',

    defaults : {
        id : 0,
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

    //RM_TODO via native backbone save

    save : function() {
        console.log(this.toJSON());
        this.trigger('save');
        var self = this;
        if (this.isValid()) {
            $.ajax({
                type : 'post',
                url : self._saveUrl,
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

    remove : function() {
        this.trigger('remove');
        var self = this;
        if (this.get('id') !== 0) {
            $.ajax({
                type : 'post',
                url : self._cancelUrl,
                data : {id : self.get('id')},
                dataType : 'json',
                success : function(data) {
                    console.log(data);
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
            date : self.get('visitDate')
        };
        if (self.get('selectedServices').length) {
            _.extend(data, {
                services : self.get('selectedServices').join(',')
            })
        }
        return data;
    },

    getPrettyVisitDateTime : function() {
        var visitTime = this.get('visitTime');
        var visitDate = this.get('visitDate').split('.');
        var visitMonth = visitDate[1];
        visitDate = visitDate[0];
        var monthNames = [
            'января', 'февраля', 'марта', 'апреля', //RM_TODO
            'мая', 'июня', 'июля', 'августа',
            'сентября', 'октября', 'ноября', 'декабря'
        ];
        return [visitDate, monthNames[visitMonth - 1], 'в', visitTime].join(' ');
    }

}, {

});