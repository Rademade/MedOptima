MedOptima.prototype.ReservationViewDoctorList = Backbone.View.extend({

    initialize : function() {
        this._bindEvents();
    },

    update : function() {
        var self = this;
        this.model.set('selectedDoctor', undefined);
        this.model.set('visitTime', undefined);
        this._loadStart();
        this.collection.fetch({
            data : self.model.getUpdateData(),
            reset : true,
            success : function(collection, response, options) {
                self.render(response);
                self._loadFinish();

            },
            error : function() {
                //RM_TODO show error
            }
        });
    },

    render : function(items) {
        this.clear();
        _.each(items, this._renderItem, this);
        if (items.length === 0) {
            this.$el.append(''); //RM_TODO message if no doctors
        }
        this.$el.append('<div class="clear"></div>');
        return this;
    },

    clear : function() {
        this.$el.find('.popup-doctors-box-item').remove();
    },

    _doctorSelected : function($doctor) {
        $doctor.siblings().removeClass('activated');
        this.model.set('selectedDoctor', $doctor.attr('data-id'));
        this.model.set('visitTime', undefined);
    },

    _doctorDeselected : function($doctor) {
        $doctor.siblings().removeClass('activated');
        this.model.set('selectedDoctor', undefined);
        this.model.set('visitTime', undefined);
    },

    _timeSelected : function($time) {
        this.model.set('visitTime', $time.attr('data-time'));
    },

    _loadStart : function() {
        Med.showAjaxLoader(this.$el.parent().parent()); //RM_TODO correct height
    },

    _loadFinish : function() {
        Med.hideAjaxLoader(this.$el.parent().parent());
    },

    _bindEvents : function() {
        this.model.on('change:visitDate', this.update, this);
        this.model.on('change:selectedServices', this.update, this);
    },

    _bindItemEvents : function(item) {
        item.on('selected', this._doctorSelected, this);
        item.on('deselected', this._doctorDeselected, this);
        item.on('timeSelected', this._timeSelected, this);
    },

    _renderItem : function(item) {
        var doctorView = new MedOptima.prototype.ReservationViewDoctor({
            model : new MedOptima.prototype.ReservationModelDoctor(item)
        });
        this._bindItemEvents(doctorView);
        doctorView.render();
        if (doctorView.$el instanceof $) {
            this.$el.append(doctorView.$el);
        }
    }

}, {

    init: function(model, $reservation) {
        return new MedOptima.prototype.ReservationViewDoctorList({
            model : model,
            el : $reservation.find('.popup-doctors-box'),
            collection : new MedOptima.prototype.ReservationDoctorCollection()
        })
    }

});