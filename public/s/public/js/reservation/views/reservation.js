MedOptima.prototype.ReservationView = Backbone.View.extend({

    serviceListView : undefined,
    doctorListView : undefined,
    formView : undefined,
    //RM_TODO add component : messageView

    initialize : function(data) {
        _.extend(this, data);
        this._bindEvents();
    },

    relocate : function($container) {
        this.$el.appendTo($container).hide();
        return this;
    },

    show : function() {
        this.$el.fadeIn(200);
        return this;
    },

    hide : function() {
        this.$el.fadeOut(200);
        return this;
    },

    visible : function() {
        return this.$el.is(':visible');
    },

    _bindEvents : function() {
        this.formView.on('submit', this._createReservation, this);
    },

    _createReservation : function() {
        console.log(this.model.toJSON());
        if (this.model.isValid()) {
            console.log('Valid');
            this.model.save();
        }
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