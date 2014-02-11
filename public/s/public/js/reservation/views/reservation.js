MedOptima.prototype.ReservationView = Backbone.View.extend({

    serviceListView : undefined,
    doctorListView : undefined,
    formView : undefined,

    initialize : function(data) {
        _.extend(this, data);
        this._bindEvents();
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
        this.formView.on('submit', this._save, this);
        this.model.on('change:visitTime', this._scrollToForm, this);
    },

    _save : function() {
        this.hide();
        this.model.save();
    },

    _scrollToForm : function() {
        //RM_TODO scroll
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