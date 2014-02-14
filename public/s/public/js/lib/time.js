var Time = function(hours, minutes, seconds) {

    if (typeof hours == 'string' || hours instanceof String) {
        var pieces = hours.split(':');
        hours = parseInt(pieces[0]);
        minutes = parseInt(pieces[1]);
        seconds = parseInt(pieces[2]);
    }

    var _hours = (hours ? hours : 0) % 24;
    var _minutes = (minutes ? minutes : 0) % 60;
    var _seconds = (seconds ? seconds : 0) % 60;

    this.toString = function(seconds) {
        var pieces = [
            _addLeadingZero(_hours),
            _addLeadingZero(_minutes)
        ];
        if (seconds) {
            pieces.push(_addLeadingZero(_seconds));
        }
        return pieces.join(':');
    };

    this.toJSON = function() {
        return this.toString();
    };

    this.getTime = function() {
        return _hours * 3600 + _minutes * 60 + _seconds;
    };

    var _addLeadingZero = function(value) {
        return value < 10 ? '0' + value : value;
    }

};