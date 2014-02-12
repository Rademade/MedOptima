MedOptima.prototype.ReservationViewDoctor = Backbone.View.extend({

    events : {
        'click :not(.popup-doctors-box-schedule, .popup-doctors-box-schedule *, .popup-box-close-link, .popup-box-close-link *)' : '_selected',
        //RM_TODO fix selector ^^^
        'click .popup-box-close-link' : '_closed',
        'click .popup-doctors-box-schedule button' : '_timeSelected'
    },

    _$schedule : undefined,

    initialize : function() {
        this._updateSchedule();
        this.model.on('change:isSelected', function() {
            if (this.model.get('isSelected')) {
                this.open();
            } else {
                this.close();
            }
        }, this);
    },

    getHtml : function() {
        var template = MedOptima.prototype.ReservationViewDoctor.template;
        return _.isFunction(template) ? template({doctor : this.model.toJSON()}) : '';
    },

    render : function() {
        console.log(this.getHtml());
        this.$el = $(this.getHtml());
        this._updateSchedule();
        this.delegateEvents(this.events);
        return this;
    },

    open : function() {
        if (!this.$el.hasClass('activated')) {
            var _self = this;
            this._$schedule.slideToggle(100, function() {
                _self.$el.addClass('activated')
            });
        }
    },

    close : function() {
        if (this.$el.hasClass('activated')) {
            var _self = this;
            this._$schedule.slideToggle(100, function() {
                _self.$el.removeClass('activated')
            });
        }
    },

    _updateSchedule : function() {
        this._$schedule = this.$el.find('.popup-schedule-time');
    },

    _timeSelected : function(event) {
        var $time = $(event.currentTarget);
        if (!$time.hasClass('off')) {
            $time.siblings().removeClass('clicked');
            $time.addClass('clicked');
            this.model.set('selectedTime', $time.attr('data-time'));
        }
    },

    _selected : function() {
        this.model.set('isSelected', !this.model.get('isSelected'));
        if (!this.model.get('isSelected')) {
            this._clearSelectedTime();
        }
    },

    _closed : function() {
        this.model.set('isSelected', false);
        this._clearSelectedTime();
    },

    _clearSelectedTime : function() {
        this.$el.find('.popup-schedule-time').removeClass('clicked');
        this.model.set('selectedTime', undefined);
    }

}, {

    template : function() {
        var $el = $('#reservation-widget-doctor-ejs');
        if ($el[0] !== undefined) {
            return _.template($el.html());
        } else {
            return undefined;
        }
    }()

});