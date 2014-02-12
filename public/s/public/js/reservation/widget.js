MedOptima.prototype.ReservationWidget = Backbone.View.extend({

    calendarWidget  : undefined,
    reservationView : undefined,

    createMsg   : undefined,
    deleteMsg   : undefined,
    errorMsg    : undefined,
    loader      : undefined,

    initialize: function(data) {
        _.extend(this, data);
        this._initLoader();
        this._bindCalendarEvents();
        this._bindModelEvents();
        this._bindMessageButtons();
    },

    _initLoader : function() {
        this.loader.$el.append(Med.cloneAjaxLoader().show());
    },

    _dateSelected : function($dateContainer, dateView, dateModel) {
        this.reservationView.$el.detach().appendTo($dateContainer);
        this.model.set('visitDate', dateModel.getDate());
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
        this.model
            .on('save remove', this.showLoader, this) //RM_TODO rename triggers when REST will be ready
            .on('saveSuccess', this.showCreateMessage, this)
            .on('removeSuccess', this.showDeleteMessage, this)
            .on('saveError removeError', this.showErrorMessage, this);
    },

    _bindMessageButtons : function() {
        this.createMsg.on('buttonClick', this.model.remove, this.model);
        this.deleteMsg.on('buttonClick', this.model.save, this.model);
    },

    showLoader : function() {
        this.calendarWidget.hideButtons();
        this.createMsg.hide();
        this.deleteMsg.hide();
        this.errorMsg.hide();
        this.loader.show();
    },

    hideLoader : function() {
        this.loader.hide();
    },

    showCreateMessage : function() {
        this.createMsg.setLabel(this.getMessageDateTime());
        this.hideLoader();
        this.createMsg.show();
    },

    showDeleteMessage : function() {
        this.deleteMsg.setLabel('Отмена ' + this.getMessageDateTime());
        this.hideLoader();
        this.deleteMsg.show();
    },

    showErrorMessage : function() {
        this.hideLoader();
        this.errorMsg.show();
    },

    getMessageDateTime : function() {
        return [
            this.model.get('visitDate').formatFullDate(),
            'в',
            this.model.get('visitTime').formatTime()
        ].join(' ');
    }

}, {

    init : function($calendar, $reservation) {
        var model = new MedOptima.prototype.ReservationModel();
        return new MedOptima.prototype.ReservationWidget({
            model : model,

            calendarWidget  : MedOptima.prototype.CalendarWidget.init($calendar),
            reservationView : MedOptima.prototype.ReservationView.init(model, $reservation.children('#reservation-popup')),

            createMsg   : new MedOptima.prototype.MessageView({el : $calendar.find('#message-create')}),
            deleteMsg   : new MedOptima.prototype.MessageView({el : $calendar.find('#message-delete')}),
            errorMsg    : new MedOptima.prototype.MessageView({el : $calendar.find('#message-error')}),
            loader      : new MedOptima.prototype.MessageView({el : $calendar.find('#message-loader')})
        });
    }

});