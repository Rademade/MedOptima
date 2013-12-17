AdminkaWork.prototype.MultiList = function() {

    var _$multiList;

    this.setElement = function($multiList) {
        _$multiList = $multiList;
    };

    this.bindAddButton = function(onAddCallback) {
        this._bindButton('add-one', function() {
            var $listItem = _$multiList.find('.list-item:last').clone();
            $listItem.find('input').removeClass('hasDatepicker').val('');
            _$multiList.append($listItem);
            $.isFunction(onAddCallback) ? onAddCallback.apply() : undefined;
        });
    };

    this.bindRemoveButton = function(onRemove) {
        this._bindButton('del-one', function($button) {
            var $listItem = $button.closest('.list-item');
            $listItem.remove();
            $.isFunction(onRemove) ? onRemove.apply() : undefined;
        });
    };

    this._bindButton = function(buttonClass, callback) {
        var self = this;
        _$multiList.find('.' + buttonClass).live('click', function(e) {
            e.preventDefault();
            callback($(e.currentTarget));
            self._checkRemoveButtons();
        });
    };

    this._checkRemoveButtons = function() {
        var $removeButton = _$multiList.find('.del-one');
        if (_$multiList.children().length == 1) {
            $removeButton.hide();
        } else if ($removeButton.is(':hidden')) {
            $removeButton.show();
        }
    };

};

AdminkaWork.prototype.MultiList.init = function($multiList, onAdd, onRemove) {
    var multiList = new Adminka.MultiList();
    multiList.setElement($multiList);
    multiList.bindAddButton(onAdd);
    multiList.bindRemoveButton(onRemove);
};