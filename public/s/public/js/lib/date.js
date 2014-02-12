$(function() {

    Date.prototype.getMonthNames = function() {
        return [
            'января', 'февраля', 'марта', 'апреля',
            'мая', 'июня', 'июля', 'августа',
            'сентября', 'октября', 'ноября', 'декабря'
        ];
    };

    Date.prototype.getMonthName = function() {
        return this.getMonthNames()[this.getMonth() + 1];
    };

    Date.prototype.formatShortDate = function() {
        return [
            this.getDate(),
            this.getMonth() + 1,
            this.getFullYear()
        ].join('.');
    };

    Date.prototype.formatFullDate = function() {
        return [
            this.getDate(),
            this.getMonthName(),
            this.getFullYear()
        ].join(' ');
    };

    Date.prototype.formatTime = function() {
        var hours = this.getHours();
        var minutes = this.getMinutes();
        if (hours < 10) {
            hours = '0' + hours;
        }
        if (minutes < 10) {
            minutes = '0' + minutes;
        }
        return [
            hours,
            minutes
        ].join(':');
    };

    Date.prototype.setTimeString = function(timeStr) {
        if (!_.isArray(timeStr)) {
            var tokens = timeStr.split(':');
        }
        this.setHours(tokens[0]);
        this.setMinutes(tokens[1]);
        return this;
    };

    Date.prototype.isDateGreater = function(date) {
        if (_.isObject(date)) {
            if (this.getFullYear() != date.getFullYear()) {
                return this.getFullYear() > date.getFullYear();
            }
            if ( this.getMonth() != date.getMonth() ) {
                return this.getMonth() > date.getMonth()
            }
            return this.getDate() > date.getDate();
        } else {
            return false;
        }
    };

    Date.prototype.isDateLesser = function(date) {
        return !this.isDateGreater(date);
    };

    Date.prototype.isDateEqual = function(date) {
        return !this.isDateGreater(date) && !this.isDateLesser(date);
    };

});
