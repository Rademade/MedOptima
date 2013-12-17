(function(){

    var Search = function() {

        var searchUrl;

        var inputSelector;
        var $input;

        var autoListSelector;

        var $autoCompleteUl;
        var matchedData = [];

        var $form;

        this.setSearchUrl = function(url) {
            searchUrl = url;
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

        this.setForm = function(formSelector) {
            $form = $(formSelector);
        };

        this.submitСomponent = function(data) {
            $input.val(data.value);
            $form.submit();
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
                select: function(event, ui) {
                    this.submitСomponent(ui.item.compontent);
                }.of(this)
            }).keypress(function(e) {
                if (e.which == 13) {
                    $form.submit();
                }
            }.of(this));

        };

    };

    AdminkaWork.prototype.ItemsSearch = function(
        inputSelector,
        itemsListSelector,
        formSelector,
        itemSearchUrl
    ) {
        var search = new Search();
        search.setSearchUrl( itemSearchUrl );
        search.setInput( inputSelector );
        search.setForm( formSelector );
        search.setAutoList( itemsListSelector );
        search.bindAutoComplete();
    };

})();