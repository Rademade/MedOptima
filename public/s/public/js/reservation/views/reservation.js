MedOptima.prototype.ReservationView = Backbone.View.extend({

    serviceListView     : undefined,
    doctorListView      : undefined,
    formView            : undefined,

    initialize : function(data) {
        _.extend(this, data);
        this._bindEvents();
    },

    show : function() {
        this.$el.fadeIn(200);
        this.trigger('show');
        return this;
    },

    hide : function() {
        this.$el.fadeOut(200);
        this.trigger('hide');
        return this;
    },

    visible : function() {
        return this.$el.is(':visible');
    },

    _bindEvents : function() {
        this.formView
            .on('submit', this._formSubmit, this)
            .on('show', this._scrollToForm, this)
            .on('hide', this._scrollToSelf, this);
        this.model
            .on('change:visitTime', this._visitTimeChanged, this)
            .on('change:selectedDoctor', this._selectedDoctorChanged, this);
        this.doctorListView.on('afterRender', this._afterDoctorListRender, this);
    },

    _visitTimeChanged : function() {
        if (this.model.get('visitTime')) {
            this.formView.show();
        } else {
            this.formView.hide();
        }
    },

    _selectedDoctorChanged : function() {
        this._scrollToSelf();
    },

    _formSubmit : function() {
        this.hide();
        this.model.save();
    },

    _scrollToForm : function() {
        $.scrollTo(this.formView.$el);
    },

    _scrollToSelf : function() {
        $.scrollTo(this.$el);
    },

    _afterDoctorListRender : function() {
        this._scrollToSelf();
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