MedOptima.prototype.CalendarWidget = Backbone.View.extend({

    config      : undefined,
    calendar    : undefined,
    prevBtn     : undefined,
    nextBtn     : undefined,

    initialize : function(options) {
        options || (options = {});
        _.extend(this, options);
        this._initComponents();
        this._bindNavigateButtons();
        this._bindOtherButtons();
        this._bindEvents();
        this.setButtonsVisible(true);
        this.setButtonsEnabled(true);
        this.calendar.model.setDisplayedDate(new Date);
    },

    setButtonsVisible : function(visible) {
        this.prevBtn.setVisible(visible);
        this.nextBtn.setVisible(visible);
        return this;
    },
    
    setButtonsEnabled : function(enabled) {
        this.prevBtn.setEnabled(enabled);
        this.nextBtn.setEnabled(enabled);
        return this;    
    },
    
    _initComponents : function() {
        this.calendar = MedOptima.prototype.CalendarView.init(
            this.$el.find('.calendar-widget-month-container'),
            this.config
        );
        this.prevBtn = MedOptima.prototype.CalendarWidgetNavButton.init(
            this.$el.find('.calendar-widget-nav-button-prev'),
            this.config.navigation
        );
        this.nextBtn = MedOptima.prototype.CalendarWidgetNavButton.init(
            this.$el.find('.calendar-widget-nav-button-next'),
            this.config.navigation
        );
    },

    _bindNavigateButtons : function() {
        this.prevBtn.on('click', function() {
            this.calendar.model.switchToPreviousMonth();
        }, this);
        this.nextBtn.on('click', function() {
            this.calendar.model.switchToNextMonth()
        }, this);
    },

    _bindOtherButtons : function() {
        var self = this;
        var $today = this.$el.find('.calendar-widget-today-button');
        $today.click(function() {
            self.calendar.model.setDisplayedDate(new Date());
            self.trigger('todayButtonClick', $today);
        });
    },

    _bindEvents : function() {
        this.calendar.collection.on('change:selected', this._dateSelectionChanged, this);
        this.calendar.model
            .on('beforeDisplayedDateChange', this._beforeDisplayedDateChanged, this)
            .on('displayedDateChange', this._displayedDateChanged, this);
        this.calendar.on('dateReselect', function(dateModel, dateView) {
            this.trigger('dateReselect', dateModel, dateView);
        }, this);
    },

    _beforeDisplayedDateChanged : function() {
        this.trigger('beforeDisplayedDateChange');
    },
    
    _displayedDateChanged : function() {
        this._updateButtons();
        this.trigger('displayedDateChange');
    },
    
    _dateSelectionChanged : function(dateModel) {
        if (dateModel.is('selected')) {
            var displayedDate = this.calendar.model.getDisplayedDate();
            var selectedDate = dateModel.getDate();

            if (selectedDate.getFullYear() < displayedDate.getFullYear()) {
                this.calendar.model.switchToPreviousMonth();
            } else if (selectedDate.getFullYear() > displayedDate.getFullYear()) {
                this.calendar.model.switchToNextMonth();
            } else {
                if (selectedDate.getMonth() > displayedDate.getMonth()) {
                    this.calendar.model.switchToNextMonth();
                } else if (selectedDate.getMonth() < displayedDate.getMonth()) {
                    this.calendar.model.switchToPreviousMonth();
                } else {
                    this.trigger('dateSelect', dateModel, this.calendar.findDateView(dateModel));
                }
            }
            var newDateModel = this.calendar.collection.findByDate(selectedDate);
            newDateModel.set('selected', true);
        }
    },

    _updateButtons : function() {
        var date = this.calendar.model.getDisplayedDate();
        this.prevBtn.setEnabled(
            (date.isFuture() && !this.config.navigation.allowPast)
                || this.config.navigation.allowPast
        );
        this.nextBtn.setEnabled(
            date.isPast() && !this.config.navigation.allowFuture
                || this.config.navigation.allowFuture
        );
        this._updateButtonsLabel();
    },

    _updateButtonsLabel : function() {
        var date = this.calendar.model.getDisplayedDate();
        date.setMonth(date.getMonth() - 1);
        this.prevBtn.setLabel(date.getMonthName());
        date.setMonth(date.getMonth() + 2);
        this.nextBtn.setLabel(date.getMonthName());
    }

}, {

    init : function($el, config) {
        return new MedOptima.prototype.CalendarWidget({
            el          : $el,
            config      : config
        });
    }

});