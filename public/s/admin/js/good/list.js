(function(){

    var GoodItemsList = function(
        $inputContainer,
        inputName
    ) {

        var setedIds = [],
            appendList = false,
            $appendUl;

        /**
         * @param $ul jQuery
         */
        this.enableList = function($ul) {
            $appendUl = $ul;
            appendList = true;
        };

        this.bindListRemove = function() {
            $inputContainer.find('input').each(function(i, input){
               this._bindRemove(
                   $appendUl.children('li:eq(' + i + ')'),
                   parseInt($(input).val(), 10)
               );
            }.of(this));
        };

        /**
         * @param $li jQuery
         */
        this._bindRemove = function($li, id){
            $li.find('.remove').bind('click', function(){
                $li.remove();
                this.inputRemove(id);
            }.of(this));
        };

        this._updateInput = function() {
            $inputContainer.html('');
            $.each(setedIds, function(i, id) {
                $inputContainer.append([
                    '<input ',
                        'type="hidden" ',
                        'name="', inputName, '[]" ',
                        'value="', id, '" ',
                    '/>'
                ].join(''));
            });
        };

        this.inputAdd = function(id) {
            id = id-0;
            setedIds.push( id );
            this._updateInput();
        };

        this.inputRemove = function(id) {
            id = id-0;
            setedIds.unsetByContent(id);
            this._updateInput();
        };

        this.addToList = function(item) {
            this.inputAdd( item.id );
            if (appendList) {
                var $li = $(['<li>',
                    '<span class="name">', item.value ,'</span>',
                    '<i class="remove"></i>',
                    '<span class="type">', item.article, '</span>',
                '</li>'].join(''));
                $appendUl.append( $li );
                this._bindRemove( $li, item.id );
            }
        };

        this.getResults = function(req, cb) {
            var searchText = req.term;
            $.ajax({
                url: $('#itemsAjax').val(),
                type: 'GET',
                dataType: 'json',
                data: {
                    type: Adminka.ACTION_SEARCH,
                    setedIds:setedIds,
                    search: searchText
                },
                success: function(data) {
                    cb(data.items);
                }
            });
        };

        /**
         * @param $input jQuery
         * @param appendTo String
         */
        this.bindAutoComplete = function($input, appendTo) {

            var $list;
            var checked = false;
            var formatedData = [];
            var listSelector = '#' + appendTo;

            $input.autocomplete({
                minLength: 2,
                delay: 150,
                appendTo: listSelector,
                source: function( request, response ) {
                    this.getResults(request, function(data){
                        formatedData = [];
                        $.each(data, function(i, o){
                            formatedData.push({
                                compontent: o,
                                value: o.value + ' #' + o.article
                            });
                        });
                        response(formatedData);
                    });
                }.of(this),
                create: function() {
                    $list = $(listSelector + ' .ui-autocomplete');
                }.of(this),
                open: function() {
                    checked = false;
                }.of(this),
                select: function(event, ui) {
                    this.addToList(ui.item.compontent);
                    close();
                }.of(this)
            }).keypress(function(e){
                if (e.which == 13 && checked === false) {
                    this.addToList(formatedData[0].compontent);
                    close();
                }
            }.of(this));

            var close = function() {
                if (formatedData[0] !== undefined) {
                    checked = true;
                    setTimeout(function(){
                        $list.hide();
                        $input.val('');
                    }, 100);
                }
            }.of(this);
        };

        this._scanInput = function() {
            $inputContainer.find('input').each(function(i, input){
                setedIds.push( parseInt($(input).val(), 10) );
            });
        };

        this._scanInput();
    };


    /**
     * @param $inputContainer jQuery
     * @param inputName string
     * @param inputSelector string
     * @param autoCompleteDivSelector string
     */
    AdminkaWork.prototype.GoodItemsList = function(
        $inputContainer,
        inputName,
        inputSelector,
        autoCompleteDivSelector
    ) {
        var $input = $('#' + inputSelector);
        var search = new GoodItemsList($inputContainer, inputName);
        search.bindAutoComplete(
            $input,
            autoCompleteDivSelector
        );
        search.enableList(
            $input.parent().parent().children('.selectedItems')
        );
        search.bindListRemove()
    };

})();
