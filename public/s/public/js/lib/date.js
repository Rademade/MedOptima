$(function() {

    Date.prototype.today = new Date();

    Date.prototype.getMonthNames = function() {
        return [
            'Январь', 'Февраль', 'Март', 'Апрель',
            'Май', 'Июнь', 'Июль', 'Август',
            'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
        ];
    };

    Date.prototype.getMonthName = function() {
        return this.getMonthNames()[this.getMonth()];
    };

    Date.prototype.getDeclensionMonthNames = function() {
        return [
            'января', 'февраля', 'марта', 'апреля',
            'мая', 'июня', 'июля', 'августа',
            'сентября', 'октября', 'ноября', 'декабря'
        ];
    };

    Date.prototype.getDeclensionMonthName = function() {
        return this.getDeclensionMonthNames()[this.getMonth()];
    };

    Date.prototype.getShortDateString = function() {
        return [
            this.getDate(),
            this.getMonth() + 1,
            this.getFullYear()
        ].join('.');
    };

    Date.prototype.getFullDateString = function() {
        return [
            this.getDate(),
            this.getDeclensionMonthName(),
            this.getFullYear()
        ].join(' ');
    };

    Date.prototype.greaterThan = function(date) {
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

    Date.prototype.lesserThan = function(date) {
        if (_.isObject(date)) {
            if (this.getFullYear() != date.getFullYear()) {
                return this.getFullYear() < date.getFullYear();
            }
            if (this.getMonth() != date.getMonth()) {
                return this.getMonth() < date.getMonth()
            }
            return this.getDate() < date.getDate();
        } else {
            return false;
        }
    };

    Date.prototype.isEqual = function(date) {
        return !this.greaterThan(date) && !this.lesserThan(date);
    };

    Date.prototype.isSunday = function() {
        return this.getDay() == 0;
    };

    Date.prototype.isToday = function() {
        var today = this.today;
        return today.getDate() == this.getDate()
            && today.getMonth() == this.getMonth()
            && today.getFullYear() == this.getFullYear();
    };

    Date.prototype.isFuture = function() {
        return this.greaterThan(new Date());
    };

    Date.prototype.isPast = function() {
        return this.lesserThan(new Date());
    };

});
