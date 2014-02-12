MedOptima.prototype.CalendarWidgetNavButton = Backbone.View.extend({

    _$label : undefined,

    initialize : function(options) {
        this.config = options.config;
        this._$label = this.$el.find('.calendar-widget-nav-button-label');
        this._bindEvents();
    },

    setLabel : function(label) {
        this._$label.text(label);
    },

    setEnabled : function(enable) {
        if (enable) {
            this.$el.removeClass(this.config.states.disabled)
                .addClass(this.config.states.enabled);
        } else {
            this.$el.addClass(this.config.states.disabled)
                .removeClass(this.config.states.enabled);
        }
    },

    _bindEvents : function() {
        var self = this;
        this.$el.click(function() {
            if (!self.$el.hasClass(self.config.states.disabled)) {
                self.trigger('click');
            }
        });
    },

    hide : function() {
        this.$el.hide();
    },

    show : function() {
        this.$el.show();
    }

}, {

    init : function($el, config) {
        return new MedOptima.prototype.CalendarWidgetNavButton({
            el : $el,
            config : config
        });
    }

});