(function(){

    var ItemStock = function(idSizeItem) {

        var $selectBox;
        var rmSelect;

        this.InitWait = function(selectListSelector) {
            $selectBox = $('#' + selectListSelector);
            $selectBox.bind('click', function(){
                $selectBox.unbind('click');
                this.InitSelect();
            }.of(this));
        };

        this.getId = function() {
            return idSizeItem;
        };

        this._sendStatus = function(status) {
            this._ajax({
                type: Adminka.ACTION_STATUS,
                status: status - 0
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
                this._sendStatus( $a.attr('data-val') );
            }.of(this));
            setTimeout(function(){
                rmSelect.open();
            }, 20);
        };

    };

    AdminkaWork.prototype.ItemStock = function(
        selectListSelector,
        idSizeItem
    ) {
        var stock = new ItemStock( idSizeItem );
        stock.InitWait( selectListSelector );
    };


})();