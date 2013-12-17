var _logTimer = (new Date()).getTime();
var _debugLogHist = [];
var _debugLogHistOffset = 0;
var _debugLogHistShow = false;

function debugLog(msg) {
    try {
        var t = '[' + (((new Date()).getTime() - _logTimer) / 1000) + '] ';
        if (ge('debuglog')) {
            if (msg === null) {
                msg = '[NULL]';
            } else if (msg === undefined) {
                msg = '[UNDEFINED]';
            }
            ge('debuglog').appendChild(ce('div', {
                innerHTML: t + msg.toString().replace('<', '&lt;').replace('>', '&gt;')
            }));
        }
        if (window.console && console.log) {
            var args = Array.prototype.slice.call(arguments);
            args.unshift(t);
            if (browser.msie || browser.mobile) {
                console.log(args.join(' '));
            } else {
                console.log.apply(console, args);
            }
        }
    } catch (e) {}
}

function debugLogHist(msg) {
    try {
        var t = '[' + (((new Date()).getTime() - _logTimer) / 1000) + '] ',
            line = new Array(57).join('=');
        if (ge('debuglog')) {
            msg = t + msg.toString().replace('<', '&lt;').replace('>', '&gt;') + '<br/>';
            msg = line + '<br/>' + msg + line + '<br/>';
            _debugLogHistOffset++;
            if (_debugLogHistOffset >= 20) {
                _debugLogHist.splice(0, _debugLogHistOffset - 19);
                _debugLogHistOffset = 19;
            }
            if (!_debugLogHist[_debugLogHistOffset]) {
                _debugLogHist[_debugLogHistOffset] = '';
            }
            _debugLogHist[_debugLogHistOffset] += msg;
        }
    } catch (e) {}
}