MedOptima.prototype.ReservationViewDoctor = Backbone.View.extend({

    events : {
        'click :not(.popup-doctors-box-schedule, .popup-doctors-box-schedule *, .popup-box-close-link, .popup-box-close-link *)' : '_selfSelected',
        //RM_TODO selector ^^^
        'click .popup-box-close-link' : '_selfDeselected',
        'click .popup-doctors-box-schedule button' : '_timeSelected'
    },

    getHtml : function() {
        var template = MedOptima.prototype.ReservationViewDoctor.template;
        return _.isFunction(template) ? template({doctor : this.model.toJSON()}) : '';
    },

    render : function() {
        this.$el= $(this.getHtml());
        this.delegateEvents(this.events); //RM_TODO ???
        return this;
    },

    _timeSelected : function(event) {
        var $time = $(event.currentTarget);
        $time.siblings().removeClass('clicked');
        $time.addClass('clicked');
        this.trigger('timeSelected', $time);
    },

    _selfSelected : function() {
        this.$el.toggleClass('activated');
        this.trigger('selected', this.$el);
    },

    _selfDeselected : function() {
        this.$el.removeClass('activated');
        this.$el.find('.popup-doctors-box-schedule *').removeClass('clicked');
        this.trigger('deselected', this.$el);
    }

}, {

    template : function() {
        var $el = $('#doctor-ejs');
        if ($el[0] !== undefined) {
            return _.template($el.html());
        } else {
            return undefined;
        }
    }()

});