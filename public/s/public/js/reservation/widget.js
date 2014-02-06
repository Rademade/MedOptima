MedOptima.prototype.ReservationWidget = Backbone.View.extend({

    calendarView : undefined,
    reservationView : undefined,

    initialize: function(data) {
        _.extend(this, data);
        this._bindEvents();
    },

    _dateClicked : function($el) {
        var oldDate = this.reservationView.model.get('visitDate');
        var newDate = this.calendarView.getDate();
        var dateChanged = oldDate !== newDate;
        if (this.reservationView.visible()) {
            this.reservationView.hide();
            if (dateChanged) {
                this.reservationView.relocate($el).show();
            }
        } else {
            if (dateChanged) {
                this.reservationView.relocate($el);
            }
            this.reservationView.show();
        }
        this.reservationView.model.set('visitDate', newDate);
    },

    _bindEvents : function() {
        this.calendarView.on('dateClicked', this._dateClicked, this);
    }

}, {

    init : function($calendar, $reservation) {
        var model = new MedOptima.prototype.ReservationModel();
        return new MedOptima.prototype.ReservationWidget({
            calendarView : MedOptima.prototype.ReservationViewCalendar.init($calendar),
            reservationView : MedOptima.prototype.ReservationView.init(model, $reservation)
        });
    }

});