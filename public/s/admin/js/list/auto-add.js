(function(){

    var AutoAdd = function() {

        var staregy;

        var ajaxUrl;
        var searchUrl;

        var inputSelector;
        var $input;

        var autoListSelector;

        var $autoCompleteUl;
        var openedUl = false;
        var matchedData = [];

        var $tableBody;

        this.setSearchUrl = function(url) {
            searchUrl = url;
        };

        this.setAjaxUrl = function(url) {
            ajaxUrl = url;
        };

        this.setInput = function(selector) {
            inputSelector = selector;
            $input = $(selector);
        };

        this.setAutoList = function(selector) {
            autoListSelector = selector;
        };

        this._getResults = function(req, cb) {
            var searchText = req.term;
            $.ajax({
                url:  searchUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    type: Adminka.ACTION_SEARCH,
                    search: searchText
                },
                success: function(data) {
                    cb(data.items);
                }
            });
        };

        this.setStrategy = function(addType) {
            staregy = addType;
        };

        this._add = function(component, callback) {
            $.ajax({
                url:  ajaxUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    type: Adminka.ACTION_ADD,
                    idItem: component.id
                },
                success: function(res) {
                    if (callback instanceof Function) {
                        if ((res.id - 0) !== 0) {
                            component.id = res.id;
                            component.position = res.position;
                            callback(component, res);
                        }
                    }
                }
            });
        };

        this.getTableBody = function() {
            if (!($tableBody instanceof jQuery)) {
                $tableBody =  $('#itemsList tbody');
            }
            return $tableBody;
        };

        this.appendComponent = function(compontent) {
            this._add(compontent, function(data, saveData) {
                this.getTableBody().append(
                    staregy.getRowHtml( data, saveData )
                );
                Adminka.ItemsListRefresh();
                Adminka.ItemsListSort().refresh();
            }.of(this));
        };

        this._closeUl = function() {
            if (matchedData[0] !== undefined) {
                openedUl = true;
                setTimeout(function(){
                    $autoCompleteUl.hide();
                    $input.val('');
                }, 100);
            }
        };

        this.bindAutoComplete = function() {

            $input.autocomplete({
                minLength: 2,
                delay: 150,
                appendTo: autoListSelector,
                source: function( request, response ) {
                    this._getResults(request, function(data){
                        matchedData = [];
                        $.each(data, function(i, o) {
                            matchedData.push({
                                compontent: o,
                                value: o.value
                            });
                        });
                        response(matchedData);
                    });
                }.of(this),
                create: function() {
                    $autoCompleteUl = $(autoListSelector + ' .ui-autocomplete');
                }.of(this),
                open: function() {
                    openedUl = false;
                }.of(this),
                select: function(event, ui) {
                    this.appendComponent(ui.item.compontent);
                    this._closeUl();
                }.of(this)
            }).keypress(function(e) {
                if (e.which == 13 && openedUl === false) {
                    if (matchedData[0] !== undefined) {
                        this.appendComponent(matchedData[0].compontent);
                    }
                    this._closeUl();
                }
            }.of(this));

        };

    };

    AdminkaWork.prototype.addStrategy = {};

    AdminkaWork.prototype.autoAdd = function(
        inputSelector,
        itemsListSelector,
        itemSearchUrl,
        itemSubmitUrl,
        addStrategy
    ) {
        var auto = new AutoAdd();
        auto.setStrategy( Adminka.addStrategy[addStrategy]() );
        auto.setSearchUrl( itemSearchUrl );
        auto.setAjaxUrl( itemSubmitUrl );
        auto.setInput( inputSelector );
        auto.setAutoList( itemsListSelector );
        auto.bindAutoComplete();
    };

})();