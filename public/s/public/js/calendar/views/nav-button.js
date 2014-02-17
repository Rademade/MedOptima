MedOptima.prototype.CalendarWidgetNavButton = Backbone.View.extend({

    $label : undefined,
    config : undefined,

    initialize : function(options) {
        _.extend(this, options);
        this._bindEvents();
    },

    setLabel : function(label) {
        this.$label.text(label);
    },

    hide : function() {
        this.$el.hide();
    },

    show : function() {
        this.$el.show();
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

    setVisible : function(visible) {
        if (visible) {
            this.show();
        } else {
            this.hide();
        }
    },

    _bindEvents : function() {
        var self = this;
        this.$el.click(function() {
            if (!self.$el.hasClass(self.config.states.disabled)) {
                self.trigger('click');
            }
        });
    }

}, {

    init : function($el, config) {
        return new MedOptima.prototype.CalendarWidgetNavButton({
            el : $el,
            config : config,
            $label : $el.find('.calendar-widget-nav-button-label')
        });
    }

});