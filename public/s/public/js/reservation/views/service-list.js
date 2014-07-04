MedOptima.prototype.ReservationViewServiceList = Backbone.View.extend({

    events : {
        'click button' : 'serviceClicked'
    },

    initialize : function() {
        this.listenTo(this.model, 'change:selectedDoctor', this.__onDoctorChanged);
    },

    serviceClicked : function(event) {
        var $service = $(event.currentTarget);
        if ($service.hasClass('off')) return;
        $service.toggleClass('clicked');
        this.model.set('selectedServices', this._getSelectedServices());
    },

    _getSelectedServices : function() {
        return _.map(this.$el.find('button.clicked'), function(el) {
            var $el = $(el);
            return $el.attr('data-id');
        });
    },

    __onDoctorChanged : function(doctor) {
        console.log('doctor changed');
        if (!doctor) return this.__enableServices('all');
        return this.__enableServices(doctor.get('services'));
    },

    __enableServices : function(services) {
        if (_.isArray(services)) {

            var pred = function() {
                var $el = $(this), id = $el.data('id');
                if (_.indexOf(services, id) === -1) {
                    $el.addClass('off');
                } else {
                    $el.removeClass('off');
                }
            };

            this.$('button').each(pred);

        } else if (services === 'all') {
            this.$('button').removeClass('off');
        }
    }

}, {

    init : function(model, $reservation) {
        return new MedOptima.prototype.ReservationViewServiceList({
            model : model,
            el : $reservation.find('#service-list')
        });
    }

});