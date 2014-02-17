MedOptima.prototype.ReservationViewForm = Backbone.View.extend({

    events : {
        'click #submit-button' : '_submitButtonClicked'
    },

    _$fieldName : undefined,
    _$fieldPhone : undefined,

    initialize : function() {
        _.bindAll(this, '_submitButtonClicked');
        this._initFields();
        this._bindValidation();
    },

    show : function() {
        this.$el.animate({
            height : 'show'
        }, 100);
    },

    hide : function() {
        this.$el.animate({
            height : 'hide'
        }, 100);
    },

    _submitButtonClicked : function() {
        this.$el.submit();
    },

    _bindValidation : function() {
        var self = this;
        var validate = Med.getBaseValidate();
        validate.submitHandler = function() {
            self._updateData();
            self.trigger('submit');
            return false;
        };
        var validator = Med.validate(validate);
        this._$fieldPhone.mask(Med.getCfg().phoneMask);
        validator
            .setNameValidation(this._$fieldName.attr('id'))
            .setPhoneValidation(this._$fieldPhone.attr('id'));
        this.$el.validate(validate);
    },

    _updateData : function() {
        this.model.set('visitorName', this._$fieldName.val());
        this.model.set('visitorPhone', this._$fieldPhone.val());
    },

    _initFields : function() {
        this._$fieldName = this.$el.find('#visitor-name');
        this._$fieldPhone = this.$el.find('#visitor-phone');
    }

}, {

    init : function(model, $reservation) {
        return new MedOptima.prototype.ReservationViewForm({
            model : model,
            el : $reservation.find('form')
        });
    }

});