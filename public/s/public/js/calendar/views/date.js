MedOptima.prototype.CalendarWidgetDateView = Backbone.View.extend({

    events : {
        'click span.calendar-box-date-item-value' : '_clicked'
    },

    config : {},

    initialize : function(options) {
        this.config = options.config;
        this.render();
        this.set('weekend', this.model.isSunday());
        this.set('current', this.model.isToday());
    },

    is : function(state) {
        return this.$el.hasClass(this.config.states[state]);
    },

    set : function(states, active) {
        if (typeof states == 'string') {
            states = states.split(',');
        }
        _.each(states, function(state) {
            var _class = this.config.states[state];
            if (active) {
                this.$el.addClass(_class);
            } else {
                this.$el.removeClass(_class);
            }
        }, this);
        return this;
    },

    setEnabled : function(enable) {
        return this.set('selected,selectable', false).set('disabled', !enable);
    },

    setSelectable : function(selectable) {
        return this.set('selected', false).set('selectable', selectable);
    },

    render : function() {
        this.$el = $(this.getHtml());
        this.delegateEvents(this.events);
        return this;
    },

    getHtml : function() {
        var template = MedOptima.prototype.CalendarWidgetDateView.template;
        return _.isFunction(template) ? template({item : this.model.toJSON()}) : '';
    },

    _clicked : function() {
        if (this.is('selectable')) {
            if (this.is('selected')) {
                this.trigger('reselect', this);
            } else {
                this.set('selected', true);
                this.trigger('select', this);
            }
        } else {
            this.trigger('click', this);
        }
    }

}, {

    template : function() {
        var $el = $('#calendar-widget-date-ejs');
        if ($el[0] !== undefined) {
            return _.template($el.html());
        } else {
            return undefined;
        }
    }()

});