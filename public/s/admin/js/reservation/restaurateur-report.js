AdminkaWork.prototype.RestaurateurReport = function(data) {

    var _ajaxUrl = '/ru/reservation/restaurant/restaurateur/xls/send';

    var _$button = $('#button-send-restaurateur-report');

    var _sendData = data;

    var _nowProcessing = false;

    this.bindSendButton = function() {
        _$button.click(function() {
            if ( _nowProcessing ) {
                return;
            }
            _nowProcessing = true;
            _$button.text('Отправляем...');
            $.ajax({
                url : _ajaxUrl,
                dataType : 'json',
                type : 'POST',
                data : _sendData
            }).done(function(data) {
                if ( data.status != 0 ) {
                    _$button.text('Отправлено!');
                } else {
                    _$button.text('Произошла ошибка');
                }
                _nowProcessing = false;
            });
        });
    }

};

AdminkaWork.prototype.RestaurateurReport.init = function(data) {
    var report = new Adminka.RestaurateurReport(data);
    report.bindSendButton();
    return report;
};