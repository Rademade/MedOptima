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
        this._bindReservationEvents();
        this._bindMessageButtons();
        this._bindBodyClick();
    },

    showCreateMessage : function() {
        this.createMsg.setLabel(this._getMessageDateTime());
        this.hideLoader();
        this.createMsg.show();
        this._scrollToCalendar();
    },

    showDeleteMessage : function() {
        this.deleteMsg.setLabel('Отмена ' + this._getMessageDateTime());
        this.hideLoader();
        this.deleteMsg.show();
        this._scrollToCalendar();
    },

    showErrorMessage : function() {
        this.hideLoader();
        this.errorMsg.show();
        this._scrollToCalendar();
    },

    showLoader : function() {
        this.calendar.setButtonsVisible(false);
        this.createMsg.hide();
        this.deleteMsg.hide();
        this.errorMsg.hide();
        this.loader.show();
        this._scrollToCalendar();
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
        this.calendar.on('dateDeselect', this._dateDeselected, this);
        this.calendar.on('beforeDisplayedDateChange', this._beforeDisplayedDateChanged, this);
    },

    _bindReservationEvents : function() {
        this.model
            .on('save destroy', this.showLoader, this)
            .on('save', this.showCreateMessage, this)
            .on('destroy', this.showDeleteMessage, this)
            .on('error', this.showErrorMessage, this);
        this.reservation
            .on('show', this._scrollToReservation, this)
            .on('hide', this._scrollToCalendar, this);
    },

    _bindMessageButtons : function() {
        this.createMsg
            .on('buttonClick', this._changeReservationTimeButtonClicked, this) //RM_TODO refactor name
            .on('secondButtonClick', this.model.destroy, this.model);
        this.deleteMsg.on('buttonClick', this.model.save, this.model);
    },

    _bindBodyClick : function() {
        this.listenTo(Backbone.EventBroker, 'body:click', function(e) {
            if ($(document.body).has(e.target).length && !this.calendar.$el.has(e.target).length) {
                if (this.reservation.visible()) {
                    this.reservation.hide();
                }
            }
        }, this);
    },

    _dateSelected : function(dateModel, dateView) {
        this.reservation.hide();
        this.reservation.$el.appendTo(dateView.$el);
        this.reservation.show();
        this.model.set('visitDate', dateModel.getDate());
    },

    _dateDeselected : function() {
        this.reservation.$el.detach();
    },

    _beforeDisplayedDateChanged : function() {
        this.reservation.$el.hide().detach();
    },

    _scrollToReservation : function() {
        $.scrollTo(this.reservation.$el);
    },

    _scrollToCalendar : function() {
        $.scrollTo(this.calendar.$el);
    },

    _changeReservationTimeButtonClicked : function() {
        this.createMsg.hide();
        this.reservation.show();
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