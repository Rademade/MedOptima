MedOptima.prototype.ReservationViewDoctorList = Backbone.View.extend({

    _$loader : undefined,

    initialize : function() {
        this._$loader = Med.cloneAjaxLoader().hide();
        this.$el.after( this._$loader );
        this.collection.on('change:isSelected change:selectedTime', this._doctorChanged, this);
        this._bindEvents();
    },

    update : function() {
        this._loadStart();

        var self = this;
        this.model.set('selectedDoctor', undefined);
        this.model.set('visitTime', undefined);
        this.collection.fetch({
            data : self.model.getUpdateData(),
            reset : true,
            success : function(collection, response) {
                _.each(response, function(value, key) {
                    value.id = key;
                });
                collection.reset();
                collection.add(response);
                self.render();
                self._loadFinish();
            },
            error : function() {
                //RM_TODO error
            }
        });
        return this;
    },

    render : function() {
        this.clear();
        if (!this.collection.size()) {
            //RM_TODO move to other EJS template
            this.$el.append('<span class="popup-error-message">На это время нету свободных докторов</span>');
        } else {
            this.collection.each(this._renderDoctor, this);
        }
        this.$el.append('<div class="clear"></div>');
        return this;
    },

    clear : function() {
        this.$el.empty();
    },

    show : function() {
        this.$el.animate({
            height : 'show'
        }, 200);
        return this;
    },

    hide : function() {
        this.$el.hide();
        return this;
    },

    _loadStart : function() {
        this.hide();
        this._$loader.height(this.$el.height()).show();
    },

    _loadFinish : function() {
        this._$loader.hide();
        this.show();
    },

    _bindEvents : function() {
        this.model.on('change:visitDate', this.update, this);
        this.model.on('change:selectedServices', this.update, this);
    },

    _renderDoctor : function(doctorModel) {
        var doctorView = new MedOptima.prototype.ReservationViewDoctor({
            model : doctorModel
        });
        doctorView.render();
        if (doctorView.$el instanceof $) {
            doctorView.$el.appendTo(this.$el);
        }
    },

    _doctorChanged : function(doctor) {
        if (doctor.get('isSelected')) {
            this.collection.each(function(model) {
                if (model.get('id') != doctor.get('id') && model.get('isSelected')) {
                    model.set('isSelected', false);
                    model.set('selectedTime', undefined);
                }
            });
            this.model.set('selectedDoctor', doctor.get('id'));
            this.model.set('visitTime', doctor.get('selectedTime'));
        } else {
            this.model.set('visitTime', undefined);
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