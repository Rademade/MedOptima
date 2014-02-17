MedOptima.prototype.ReservationViewDoctor = Backbone.View.extend({

    events : {
        'click :not(.popup-doctors-box-schedule, .popup-doctors-box-schedule *, .popup-box-close-link, .popup-box-close-link *)' : '_clicked',
        //RM_TODO fix selector ^^^
        'click .popup-box-close-link' : '_closeBoxClicked',
        'click .popup-doctors-box-schedule button' : '_timeClicked'
    },

    $schedule : undefined,

    initialize : function() {
        this._updateSchedule();
        this.model
            .on('change:isSelected', function() {
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
        this.$el = $(this.getHtml());
        this._updateSchedule();
        this.delegateEvents(this.events);
        return this;
    },

    open : function() {
        this._clearSelectedTime();
        if (!this.$el.hasClass('activated')) {
            var _self = this;
            this.$schedule.slideToggle(100, function() {
                _self.$el.addClass('activated')
            });
        }
    },

    close : function() {
        this.model.set('selectedTime', undefined);
        if (this.$el.hasClass('activated')) {
            var _self = this;
            this.$schedule.slideToggle(100, function() {
                _self.$el.removeClass('activated')
            });
        }
        this._clearSelectedTime();
    },

    _updateSchedule : function() {
        this.$schedule = this.$el.find('.popup-schedule-time');
    },

    _clicked : function() {
        this.model.set('isSelected', !this.model.get('isSelected'));
    },

    _timeClicked : function(event) {
        var $time = $(event.currentTarget);
        if (!$time.hasClass('off')) {
            this._clearSelectedTime();
            $time.addClass('clicked');
            this.model.set('selectedTime', new Time($time.attr('data-time')));
        }
    },

    _closeBoxClicked : function() {
        this.model.set('isSelected', false);
    },

    _clearSelectedTime : function() {
        this.$el.find('.popup-schedule-time').removeClass('clicked');
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