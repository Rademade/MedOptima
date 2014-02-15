MedOptima.prototype.ReservationView = Backbone.View.extend({

    serviceListView : undefined,
    doctorListView : undefined,
    formView : undefined,

    initialize : function(data) {
        _.extend(this, data);
        this._bindEvents();
    },

    show : function() {
        this.trigger('show');
        this.$el.fadeIn(200);
        return this;
    },

    hide : function() {
        this.trigger('hide');
        this.$el.fadeOut(200);
        return this;
    },

    visible : function() {
        return this.$el.is(':visible');
    },


    _bindEvents : function() {
        this.formView.on('submit', this._formSubmit, this);
        this.model.on('change:visitTime', this._visitTimeChanged, this);
    },

    _visitTimeChanged : function() {
        if (this.model.get('visitTime')) {
            this.formView.show();
            if (!this.formView.$el.visible()) {
                $.scrollTo(this.formView.$el);
            }
        } else {
            $.scrollTo(this.$el.parent());
            this.formView.hide();
        }
    },

    _formSubmit : function() {
        this.hide();
        this.model.save();
    }

}, {

    init: function(model, $reservation) {
        return new MedOptima.prototype.ReservationView({
            model : model,
            el : $reservation,
            serviceListView : MedOptima.prototype.ReservationViewServiceList.init(model, $reservation),
            doctorListView : MedOptima.prototype.ReservationViewDoctorList.init(model, $reservation),
            formView : MedOptima.prototype.ReservationViewForm.init(model, $reservation)
        })
    }

});