MedOptima.prototype.ReservationWidget = Backbone.View.extend({

    calendarWidget : undefined,
    reservationView : undefined,
    dialog : undefined,

    initialize: function(data) {
        _.extend(this, data);
        this._bindCalendarEvents();
        this._bindModelEvents();
        this._bindDialogEvents();
    },

    _dateSelected : function($dateContainer, dateView, dateModel) {
        this.reservationView.$el.detach().appendTo($dateContainer);
        this.model.set('visitDate', dateModel.getFormattedDate());
        this.reservationView.show();

    },

    _beforeMonthChanged : function() {
        this.reservationView.hide().$el.detach();
    },

    _dateReselected : function() {
        if (this.reservationView.visible()) {
            this.reservationView.hide();
        } else {
            this.reservationView.show();
        }
    },

    _bindCalendarEvents : function() {
        this.calendarWidget.on('dateSelect', this._dateSelected, this);
        this.calendarWidget.on('dateReselect', this._dateReselected, this);
        this.calendarWidget.on('beforeMonthChange', this._beforeMonthChanged, this);
    },

    _bindModelEvents : function() {
        //RM_TODO extract methods for event's!
        this.model
            .on('save remove', function() {
                this.dialog.$errorMsg.hide();
                this.dialog.$saveSuccessMsg.hide();
                this.dialog.$removeSuccessMsg.hide();
                this.dialog.$loader.show();
            }, this)
            .on('saveSuccess saveError removeSuccess removeError', function() {
                this.dialog.$loader.hide();
            }, this)
            .on('saveSuccess', function() {
                this.dialog.setLabel(this.dialog.$saveSuccessMsg, this.model.getPrettyVisitDateTime());
                this.dialog.$saveSuccessMsg.show();
            }, this)
            .on('removeSuccess', function() {
                this.dialog.setLabel(this.dialog.$removeSuccessMsg, 'Отмена ' + this.model.getPrettyVisitDateTime());
                this.dialog.$removeSuccessMsg.show();
            }, this)
            .on('saveError removeError', function() {
                this.dialog.$errorMsg.show();
            }, this);
    },

    _bindDialogEvents : function() {
        this.dialog
            .on('removeButtonClick', this.model.remove, this.model)
            .on('saveButtonClick', this.model.save, this.model);
    }

}, {

    init : function($calendar, $reservation) {
        var model = new MedOptima.prototype.ReservationModel();
        return new MedOptima.prototype.ReservationWidget({
            model : model,
            calendarWidget : MedOptima.prototype.CalendarWidget.init($calendar),
            reservationView : MedOptima.prototype.ReservationView.init(model, $reservation.children('#reservation-popup')),
            dialog : MedOptima.prototype.DialogView.init($calendar)
        });
    }

});