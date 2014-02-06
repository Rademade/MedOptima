MedOptima.prototype.ReservationViewServiceList = Backbone.View.extend({

    events : {
        'click button' : 'serviceClicked'
    },

    serviceClicked : function(event) {
        var $service = $(event.currentTarget);
        $service.toggleClass('clicked');
        this.model.set('selectedServices', this._getSelectedServices());
    },

    _getSelectedServices : function() {
        return _.map(this.$el.find('button.clicked'), function(el) {
            var $el = $(el);
            return $el.attr('data-id');
        });
    }

}, {

    init : function(model, $reservation) {
        return new MedOptima.prototype.ReservationViewServiceList({
            model : model,
            el : $reservation.find('#service-list')
        });
    }

});