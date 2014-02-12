MedOptima.prototype.MessageView = Backbone.View.extend({

    events : {
        'click .message-button' : '_clicked'
    },

    _$label : undefined,

    initialize : function() {
        this._$label = this.$el.find('.message-label');
    },

    setLabel : function(label) {
        this._$label.text(label);
        return this;
    },

    _clicked : function() {
        this.trigger('buttonClick');
    },

    show : function() {
        this.$el.show();
        return this;
    },

    hide : function() {
        this.$el.hide();
        return this;
    }

});