(function() {

    var NewsSchoolSelect = function() {

        var TYPE_COMMON = 1;
        var TYPE_SCHOOL = 2;

        var _self = this;

        this.bindSelect = function() {
            $('#news_type').rmSelect().change(function($a) {
                _self._processValue( $a.data('val') );
            });
        };

        this.hideSchoolBlock = function() {
            $('#mainForm .number_4').hide();
        };

        this.showSchoolBlock = function() {
            $('#mainForm .number_4').show();
        };

        this.selectCurrent = function() {
            this._processValue( $('#news_type').rmSelect().getValue() );
        };

        this._processValue = function(val) {
            switch (parseInt(val, 10)) {
                case TYPE_COMMON:
                    _self.hideSchoolBlock();
                    break;
                case TYPE_SCHOOL:
                    _self.showSchoolBlock();
                    break;
            }
        };

    };

    AdminkaWork.prototype.News.schoolSelect = function() {
        var schoolSelect = new NewsSchoolSelect();
        schoolSelect.bindSelect();
        schoolSelect.selectCurrent();
    };

})();

