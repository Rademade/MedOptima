MedOptima.prototype.CalendarView = Backbone.View.extend({

    config          : undefined,
    collection      : undefined,
    $label          : undefined,
    collectionView  : [],
    $container      : undefined,

    initialize : function(options) {
        _.extend(this, options);
        if (_.isFunction(this.config.applyDateRules) ) {
            this.applyDateRules = this.config.applyDateRules;
        }
        this.$label = this.$el.find('.calendar-widget-month-label');
        this._bindEvents();
    },

    _bindEvents : function() {
        this.collection.on('change:selected', this._dateSelectionChanged, this);
        this.model.on('beforeDisplayedDateChange', this._beforeDisplayedDateChanged, this);
        this.model.on('displayedDateChange', this._displayedDateChanged, this);
    },

    render : function() {
        this.$container = this.$el.children('.calendar-widget-month');
        this.$container.empty();
        this.collection.each(this._renderDate, this);
        this.$container.append('<div class="clear"></div>');
        this._updateLabel();
    },

    _renderDate : function(dateModel) {
        var dateView = MedOptima.prototype.CalendarDateView.init(dateModel, this.config.date);
        this.applyDateRules(dateModel, this.model.getDisplayedDate());
        this.$container.append(dateView.$el);
        this.collectionView.push(dateView);
    },

    _updateLabel : function() {
        this.$label.text([
            this.model.getDisplayedDate().getMonthName(),
            this.model.getDisplayedDate().getFullYear()
        ].join(' '));
    },

    animate : function() {

    },

    findDateView : function(dateModel) {
        return _.find(this.collectionView, function(dateView) {
            return dateView.model.isEqual(dateModel);
        }, this);
    },

    applyDateRules : function(dateModel, calendarDisplayedDate) {
        var date = dateModel.get('date');
        if (date.isPast()) {
            dateModel.set('enabled', this.config.calendar.enablePast);
        } else if (date.isFuture()) {
            dateModel.set('enabled', this.config.calendar.enableFuture);
        } else {
            dateModel.set('enabled', this.config.calendar.enableToday);
        }
        if (date.isSunday() && dateModel.is('enabled')) {
            dateModel.set('enabled', this.config.calendar.enableSunday);
        }
        if (dateModel.is('enabled')
            && calendarDisplayedDate.getMonth() == date.getMonth()
            && calendarDisplayedDate.getFullYear() == date.getFullYear()
        ) {
            if (date.isPast()) {
                dateModel.set('highlighted', this.config.calendar.highlightPast);
            } else if (date.isFuture()) {
                dateModel.set('highlighted', this.config.calendar.highlightFuture);
            } else {
                dateModel.set('highlighted', this.config.calendar.highlightToday);
            }
            if (date.isSunday() && dateModel.is('highlighted')) {
                dateModel.set('highlighted', this.config.calendar.highlightSunday);
            }
        }
    },

    _beforeDisplayedDateChanged : function() {
        this.collectionView = [];
    },

    _displayedDateChanged : function() {
        this.render();
    },

    _dateSelectionChanged : function(dateModel) {
        if (dateModel.is('selected')) {
            this.collection.each(function(otherDateModel) {
                if (!otherDateModel.isEqual(dateModel)) {
                    otherDateModel.set('selected', false);
                }
            }, this);
        }
    }

}, {

    init : function($el, config) {
        var collection = new MedOptima.prototype.CalendarDateCollection();
        return new MedOptima.prototype.CalendarView({
            model       : new MedOptima.prototype.CalendarModel({collection : collection}),
            el          : $el,
            config      : config,
            collection  : collection
        });
    }

});