MedOptima.prototype.MessageView = Backbone.View.extend({

    events : {
        'click .message-button:first'   : '_firstButtonClicked',
        'click .message-button:last'    : '_secondButtonClicked'
    },

    _$label : undefined,

    initialize : function() {
        this._$label = this.$el.find('.message-label');
    },

    setLabel : function(label) {
        this._$label.text(label);
        return this;
    },

    _firstButtonClicked : function() {
        this.trigger('buttonClick');
    },

    //RM_TODO refactor
    _secondButtonClicked : function() {
        this.trigger('secondButtonClick');
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