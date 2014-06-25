MedOptima.prototype.ReservationViewDoctorList = Backbone.View.extend({

    $loader : undefined,
    $emptyList : undefined,
    $loadError : undefined,

    initialize : function() {
        this._initComponents();
        this._bindEvents();
    },

    //RM_TODO refactor render
    render : function() {
        this.clear();
        if (!this.collection.size()) {
            this.$emptyList.show();
        } else {
            this.collection.each(this._renderDoctor, this);
            this.$el.append('<div class="clear"></div>');
        }
        return this;
    },

    clear : function() {
        this.$el.empty();
    },

    _load : function() {
        this._loadStart();
        this.model.setSelectedDoctor(null);
        this.model.set('visitTime', undefined);
        this.collection.fetch({
            data : this.model.getUpdateData(),
            reset : true
        });
    },

    _loadStart : function() {
        this.$el.hide();
        this.$emptyList.hide();
        this.$loadError.hide();
        this.$loader.show();
        this.trigger('beforeRender');
    },

    _loadFinish : function() {
        this.$el.show();
        this.$loader.hide();
        this.trigger('afterRender');
    },

    _loadError : function() {
        this.clear();
        this.$loadError.show();
        this._loadFinish();
    },

    //RM_TODO refactor
    _loadSuccess : function() {
        this.render();
        this._loadFinish();
    },

    _bindEvents : function() {
        this.collection.on('change:isSelected change:selectedTime', this._doctorChanged, this);
        this.collection.on('error', this._loadError, this);
        this.collection.on('reset', this._loadSuccess, this);
        this.model.on('change:visitDate', this._load, this);
        this.model.on('change:selectedServices', this._load, this);
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
        this.model.set('visitTime', doctor.get('selectedTime'));
        if (doctor.get('isSelected')) {
            this.collection.each(function(model) {
                if (model.get('id') !== doctor.get('id')) {
                    model.set('isSelected', false);
                }
            });
            this.model.setSelectedDoctor(doctor);
        } else {
            this.model.setSelectedDoctor(null);
        }
    },

    _initComponents : function() {
        this.$loader = Med.cloneAjaxLoader().hide();
        this.$el.after(this.$loader);
        this.$emptyList = this.$el.siblings('#popup-empty-list-message');
        this.$loadError = this.$el.siblings('#popup-load-error-message');
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