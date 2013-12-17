(function(){

    var ItemPosition = function(idItem) {

        var $selectBox;

        this.InitWait = function(selectListSelector) {
            $selectBox = $('#' + selectListSelector);
            $selectBox.bind('click', function(){
                $selectBox.unbind('click');
                this.InitSelect();
            }.of(this));
        };

        this.getId = function() {
            return idItem;
        };

        this._sendPosition = function(position) {
            this._ajax({
                type: Adminka.ACTION_POSITION,
                position: position - 0
            });
        };

        this._ajax = function(data, callback) {
            data.id = this.getId();
            $.ajax({
                type: "POST",
                url: Adminka.ItemsList().getAjaxUrl(),
                data: data,
                success: function() {
                    if (callback !== undefined) {
                        callback();
                    }
                }
            });
        };

        this.InitSelect = function() {
            var rmSelect = $selectBox.rmSelect(function($a){
                this._sendPosition( $a.attr('data-val') );
            }.of(this));
            setTimeout(function(){
                rmSelect.open();
            }, 20);
        };

    };

    AdminkaWork.prototype.ItemManualPosition = function(
        selectListSelector,
        idItem
    ) {
        var manualPosition = new ItemPosition( idItem );
        manualPosition.InitWait( selectListSelector );
    };


})();