/*
    Triggers:
        nextButtonClick : function($el, view)
        prevButtonClick : function($el, view)
        todayButtonClick : function($el)
        dateClick : function($el, view, model)
        dateSelect : function($el, view, model)
        dateReselect : function($el, view, model)
        dateChange : function(date)
        beforeMonthChange : function(month)
        monthChange : function(month)
 */
MedOptima.prototype.CalendarWidget = Backbone.View.extend({

    config : {
        date : {
            states : {
                disabled    : 'off-date',
                selectable  : 'active-date',
                selected    : 'clicked',
                current     : 'current-date',
                weekend     : 'off-date'
            },
            disableSunday   : true
        },
        calendar : {
            selectRules : {
                allowSelectPast     : false,
                allowSelectToday    : true,
                allowSelectFuture   : true,
                allowSelectSunday   : true
            },
            displayRules : {
                disableSunday : false
            },
            slideDuration : 0
        },
        navigation : {
            allowPast   : false,
            allowFuture : true,
            states : {
                enabled : '',
                disabled : 'button-disabled'
            }
        },
        locale : {
            months : [
                'Январь','Февраль','Март','Апрель',
                'Май','Июнь','Июль','Август',
                'Сентябрь','Октябрь','Ноябрь','Декабрь'
            ]
        }
    },

    prevButtonView : undefined,
    nextButtonView : undefined,
    calendarView : undefined,

    initialize : function(options) {
        _.extend(this.config, options.config);
        this._initViews();
        this._bindButtons();
        this._configureRepeater();
        this.model.setCurrentDate(new Date());
    },

    showButtons : function() {
        this.prevButtonView.show();
        this.nextButtonView.show();
    },

    hideButtons : function() {
        this.prevButtonView.hide();
        this.nextButtonView.hide();
    },

    _initViews : function() {
        this.calendarView = MedOptima.prototype.CalendarWidgetView.init(
            this.model,
            this.$el.find('.calendar-widget-month-container'),
            this.config
        );
        this.prevButtonView = MedOptima.prototype.CalendarWidgetNavButton.init(
            this.$el.find('.calendar-widget-nav-button-prev'),
            this.config.navigation
        );
        this.nextButtonView = MedOptima.prototype.CalendarWidgetNavButton.init(
            this.$el.find('.calendar-widget-nav-button-next'),
            this.config.navigation
        );
    },

    _bindButtons : function() {
        this._bindNavigateButtons();
        this._bindOtherButtons();
    },

    _bindNavigateButtons : function() {
        this.prevButtonView.on('click', function() {
            if (this.calendarView.updateFinished()) {
                var date = this.model.getCurrentDate();
                date.setMonth(date.getMonth() - 1);
                this.model.setCurrentDate(date);
                this.trigger('prevButtonClick', this.prevButtonView.$el, this.prevButtonView);
            }
        }, this);
        this.nextButtonView.on('click', function() {
            if (this.calendarView.updateFinished()) {
                var date = this.model.getCurrentDate();
                date.setMonth(date.getMonth() + 1);
                this.model.setCurrentDate(date);
                this.trigger('nextButtonClick', this.nextButtonView.$el, this.nextButtonView);
            }
        }, this);
    },

    _bindOtherButtons : function() {
        var self = this;
        var $today = this.$el.find('.calendar-widget-today-button');
        $today.click(function() {
            self.model.setCurrentDate(new Date);
            self.trigger('todayButtonClick', $today);
        });
    },

    _configureRepeater : function() {
        var self = this;
        this.calendarView
            .on('dateClick', function(date) {
                self.trigger('dateClick', date.$el, date, date.model);
            }).on('dateSelect', function(date) {
                self.trigger('dateSelect', date.$el, date, date.model);
                self.trigger('dateChange', date.model.getDate());
            }).on('dateReselect', function(date) {
                self.trigger('dateReselect', date.$el, date, date.model);
            });

        this.model.on('monthChange', function() {
            this._updateButtons();
            this.trigger('monthChange', this.model.get('currentMonth'));
        }, this);
        this.model.on('beforeMonthChange', function() {
            this.trigger('beforeMonthChange', this.model.get('currentMonth'));
        }, this);
    },

    _updateButtons : function() {
        this.prevButtonView.setEnabled(
            (this.model.isFuture() && !this.config.navigation.allowPast)
                || this.config.navigation.allowPast
        );
        this.nextButtonView.setEnabled(
            this.model.isPast() && !this.config.navigation.allowFuture
                || this.config.navigation.allowFuture
        );
        this._updateButtonsLabel();
    },

    _updateButtonsLabel : function() {
        var date = this.model.getCurrentDate();
        date.setMonth(date.getMonth() - 1);
        this.prevButtonView.setLabel(
            this.config.locale.months[date.getMonth()]
        );
        date.setMonth(date.getMonth() + 2);
        this.nextButtonView.setLabel(
            this.config.locale.months[date.getMonth()]
        );
    }

}, {

    init : function($el, config) {
        return new MedOptima.prototype.CalendarWidget({
            el : $el,
            model : new MedOptima.prototype.CalendarWidgetModel(),
            config : config
        });
    }

});