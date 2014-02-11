MedOptima.prototype.DialogView = Backbone.View.extend({

    $saveSuccessMsg : undefined,
    $errorMsg : undefined,
    $removeSuccessMsg : undefined,
    $loader : undefined,

    initialize : function(params) {
        _.extend(this, params);
        this.$loader.append(Med.cloneAjaxLoader().show());
        this._bindEvents();
    },

    setLabel : function($el, label) {
        return $el.find('.message-label').text(label);
    },

    _bindEvents : function() {
        var self = this;
        this.$saveSuccessMsg.find('a').click(function(event) {
            event.preventDefault();
            self.trigger('removeButtonClick');
        });
        this.$removeSuccessMsg.find('a').click(function(event) {
            event.preventDefault();
            self.trigger('saveButtonClick');
        });
    }

}, {

    init : function($container) {
        return new MedOptima.prototype.DialogView({
            $saveSuccessMsg : $container.find('#message-save-success'),
            $errorMsg : $container.find('#message-error'),
            $removeSuccessMsg : $container.find('#message-remove-success'),
            $loader : $container.find('#message-loader')
        });
    }

});