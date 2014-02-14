MedOptima.prototype.ReservationViewDoctorList = Backbone.View.extend({

    $loader : undefined,
    emptyListTemplate : undefined,
    loadErrorTemplate : undefined,

    initialize : function() {
        this.$loader = Med.cloneAjaxLoader().hide();
        this.$el.after( this.$loader );
        this._initTemplates();
        this._bindEvents();
    },

    render : function() {
        this.clear();
        if (!this.collection.size()) {
            if (_.isFunction(this.emptyListTemplate)) {
                this.$el.append(this.emptyListTemplate());
            }
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

    _load : function() {
        this._loadStart();
        this.model.set('selectedDoctor', undefined);
        this.model.set('visitTime', undefined);
        this.collection.fetch({
            data : this.model.getUpdateData(),
            reset : true
        });
    },

    _loadStart : function() {
        this.hide();
        this.$loader.height( this.$el.height() ).show();
    },

    _loadFinish : function() {
        this.$loader.hide();
        this.show();
    },

    _loadError : function() {
        this.clear();
        if (_.isFunction(this.loadErrorTemplate)) {
            this.$el.append(this.loadErrorTemplate());
        }
        this._loadFinish();
    },

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
    },

    _initTemplates : function() {
        this.emptyListTemplate = _.template($('#reservation-widget-doctor-list-empty-ejs').html());
        this.loadErrorTemplate = _.template($('#reservation-widget-doctor-list-load-error-ejs').html());
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