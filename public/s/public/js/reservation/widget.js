MedOptima.prototype.ReservationWidget = Backbone.View.extend({

    calendar    : undefined,
    reservation : undefined,

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

    showCreateMessage : function() {
        this.createMsg.setLabel(this._getMessageDateTime());
        this.hideLoader();
        this.createMsg.show();
    },

    showDeleteMessage : function() {
        this.deleteMsg.setLabel('Отмена ' + this._getMessageDateTime());
        this.hideLoader();
        this.deleteMsg.show();
    },

    showErrorMessage : function() {
        this.hideLoader();
        this.errorMsg.show();
    },

    showLoader : function() {
        this.calendar.setButtonsVisible(false);
        this.createMsg.hide();
        this.deleteMsg.hide();
        this.errorMsg.hide();
        this.loader.show();
    },

    hideLoader : function() {
        this.loader.hide();
    },

    _getMessageDateTime : function() {
        return [
            this.model.get('visitDate').getFullDateString(),
            'в',
            this.model.get('visitTime').toString()
        ].join(' ');
    },

    _initLoader : function() {
        this.loader.$el.append(Med.cloneAjaxLoader().show());
    },

    _bindCalendarEvents : function() {
        this.calendar.on('dateSelect', this._dateSelected, this);
        this.calendar.on('beforeDisplayedDateChange', this._beforeDisplayedDateChanged, this);
    },

    _bindModelEvents : function() {
        this.model
            .on('save destroy', this.showLoader, this)
            .on('save', this.showCreateMessage, this)
            .on('destroy', this.showDeleteMessage, this)
            .on('error', this.showErrorMessage, this);
    },

    _bindMessageButtons : function() {
        this.createMsg.on('buttonClick', this.model.destroy, this.model);
        this.deleteMsg.on('buttonClick', this.model.save, this.model);
    },

    _dateSelected : function(dateModel, dateView) {
        if ( dateView.$el.find('#' + this.reservation.$el.attr('id')).length ) {
            if (this.reservation.visible()) {
                this.reservation.hide();
            } else {
                this.reservation.show();
            }
        } else {
            this.reservation.$el.appendTo(dateView.$el);
            this.reservation.show();
        }
        this.model.set('visitDate', dateModel.getDate());
    },

    _beforeDisplayedDateChanged : function() {
        this.reservation.$el.hide().detach();
    }

}, {

    init : function($calendar, $reservation) {
        var model = new MedOptima.prototype.ReservationModel();
        return new MedOptima.prototype.ReservationWidget({
            model : model,

            calendar    : MedOptima.prototype.CalendarWidget.init($calendar, new MedOptima.prototype.CalendarConfig()),
            reservation : MedOptima.prototype.ReservationView.init(model, $reservation.children('#reservation-popup')),

            createMsg   : new MedOptima.prototype.MessageView({el : $calendar.find('#message-create')}),
            deleteMsg   : new MedOptima.prototype.MessageView({el : $calendar.find('#message-delete')}),
            errorMsg    : new MedOptima.prototype.MessageView({el : $calendar.find('#message-error')}),
            loader      : new MedOptima.prototype.MessageView({el : $calendar.find('#message-loader')})
        });
    }

});