MedOptima.prototype.CalendarWidgetView = Backbone.View.extend({

    collection : undefined,
    _selectRules : undefined,
    _displayRules : undefined,
    _today : new Date(),
    _updateFinished : false,
    config : {
        calendar : {
            selectRules : {},
            displayRules : {}
        }
    },

    initialize : function(options) {
        this.config = options.config;
        this._selectRules = this.config.calendar.selectRules;
        this._displayRules = this.config.calendar.displayRules;
        this.model.on('monthChange', this.update, this);
    },

    update : function() {
        this._updateFinished = false;
        this.collection.reset();
        this.collection.update(this.model.toJSON());
        var $old = this.$el;
        var $new = this.render().hide();
        this.$el = $new;
        $old.after($new);
        var self = this;
        var finish = function() {
            $old.remove();
            self._updateFinished = true;
        };
        if (this.model.isMovingNext()) {
            this._slideRight($old, $new, finish);
        } else {
            this._slideLeft($old, $new, finish);
        }
    },

    render : function() {
        var $el = this.$el.clone();
        var $container = $el.children('.calendar-widget-month');
        $container.empty();
        this.collection.each(function(model) {
            var view = new MedOptima.prototype.CalendarWidgetDateView({
                model : model,
                config : this.config.date
            });
            this._configureDate(model, view);
            $container.append(view.$el);
        }, this);
        $container.append('<div class="clear"></div>');
        $el.children('.calendar-widget-month-label').text([
            this.config.locale.months[this.model.get('currentMonth')],
            this.model.get('currentMonthYear')].join(' ')
        );
        return $el;
    },

    updateFinished : function() {
        return this._updateFinished;
    },

    _configureDate : function(model, view) {
        this._applyDisplayRules(model, view);
        if (!view.is('disabled')) {
            this._applySelectRules(model, view);
        }
        this._bindDateEvents(model, view);
    },


    _applyDisplayRules : function(dateModel, dateView) {
        if (dateModel.get('month') !== this.model.get('currentMonth')) {
            dateView.setEnabled(false);
        } else if (dateModel.isSunday()) {
            dateView.setEnabled(!this._displayRules.disableSunday);
        } else {
            dateView.setEnabled(true);
        }
    },

    _applySelectRules : function(dateModel, dateView) {
        var selMonth = this.model.get('currentMonth'), selYear = this.model.get('currentMonthYear');
        var month = dateModel.get('month'), year = dateModel.get('year'), day = dateModel.get('day');
        if (selYear < this._today.getFullYear()) {                              // прошедший год
            dateView.setSelectable(this._selectRules.allowSelectPast);          // только если дата входит в отображаемый месяц
        } else if (selYear > this._today.getFullYear()) {                       // будущее
            dateView.setEnabled(true).setSelectable(this._selectRules.allowSelectFuture); // только если дата входит в отображаемый месяц
        } else {                                                                // текущий год
            if (selMonth < this._today.getMonth()) {                            // прошлое
                dateView.setSelectable(this._selectRules.allowSelectPast);      // только если дата входит в отображаемый месяц
            } else if (selMonth > this._today.getMonth()) {                      // будущее
                dateView.setSelectable(this._selectRules.allowSelectFuture);      // только если дата входит в отображаемый месяц
            } else {                                                              // текущий месяц
                if (day < this._today.getDate()) {
                    dateView.setSelectable(this._selectRules.allowSelectPast);
                } else if (day > this._today.getDate()) {
                    dateView.setSelectable(this._selectRules.allowSelectFuture)
                } else {
                    dateView.setSelectable(this._selectRules.allowSelectToday);
                }
            }
        }
    },

    _bindDateEvents : function(model, view) {
        var self = this;
        view.on('select',function(date) {
            date.$el.siblings().removeClass(date.config.states.selected);
            self.trigger('dateSelect', date);
        }).on('click',function(date) {
            self.trigger('dateClick', date);
        }).on('reselect', function(date) {
            self.trigger('dateReselect', date);
        });
    },

    _slide : function($prev, $next, from, to, duration, finish) {
        if (duration) {
            $prev.hide('slide', {
                direction : from
            }, duration / 2, function() {
                $next.show('slide', {direction : to}, duration, finish)
            });
        } else {
            $prev.hide();
            $next.show();
            finish();
        }
    },

    _slideLeft : function($prev, $next, finish) {
        var duration = this.config.calendar.slideDuration;
        this._slide($prev, $next, 'left', 'right', duration, finish);
    },

    _slideRight : function($prev, $next, finish) {
        var duration = this.config.calendar.slideDuration;
        this._slide($prev, $next, 'right', 'left', duration, finish);
    }

}, {

    init : function(model, $el, config) {
        return new MedOptima.prototype.CalendarWidgetView({
            model : model,
            el : $el,
            config : config,
            collection : MedOptima.prototype.CalendarWidgetDateCollection.init(model.toJSON())
        });
    }

});