MedOptima.prototype.CalendarDateView = Backbone.View.extend({

    events : {
        'click span.calendar-box-date-item-value' : '_clicked'
    },

    initialize : function() {
        this.render();
        this.model.on('change', this.update, this);
        this.model.set('current', this.model.getDate().isToday());
    },

    update : function() {
        _.each(this.model.changedAttributes(), function(activate, state) {
            this.set(state, activate);
        }, this);
        return this;
    },

    render : function() {
        this.$el = $(this.getHtml());
        this.delegateEvents(this.events);
        return this;
    },

    getHtml : function() {
        var template = MedOptima.prototype.CalendarDateView.template;
        return _.isFunction(template) ? template({item : this.model.toJSON()}) : '';
    },

    set : function(state, activate) {
        if (state == 'enabled') {
            if (activate) {
                this.enable();
            } else {
                this.disable();
            }
        } else {
            var stateClass = this.getStateClass(state);
            if (!_.isUndefined(stateClass)) {
                if (activate) {
                    this.$el.addClass(stateClass);
                } else {
                    this.$el.removeClass(stateClass);
                }
            }
        }
        return this;
    },


    getConfig : function() {
        return MedOptima.prototype.CalendarDateView.config;
    },

    getStateClass : function(state) {
        return this.getConfig().states[state];
    },

    _clicked : function() {
        if (this.model.is('enabled')) {
            this.model.set('selected', !this.model.is('selected'));
        }
    },

    enable : function() {
        this.$el
            .removeClass(this.getStateClass('disabled'))
            .addClass(this.getStateClass('enabled'));
    },

    disable : function() {
        this.$el
            .removeClass(this.getStateClass('enabled'))
            .addClass(this.getStateClass('disabled'));
    }

}, {

    config : undefined,

    init : function(model, config) {
        config || (config = {});
        if ( _.isUndefined(MedOptima.prototype.CalendarDateView.config) ) {
            MedOptima.prototype.CalendarDateView.config = config;
        }
        return new MedOptima.prototype.CalendarDateView({
            model : model
        });
    },

    template : function() {
        var $el = $('#calendar-widget-date-ejs');
        if ($el[0] !== undefined) {
            return _.template($el.html());
        } else {
            return undefined;
        }
    }()

});