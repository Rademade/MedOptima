(function(){

    var CategoriesSearch = function(
        $inputContainer,
        inputName
    ) {

        var idRoot,
            setedIds = [],
            appendList = false,
            $appendUl,
            _ajaxUrl;

        this.setIdRootCategory = function(id) {
            idRoot = id-0;
        };

        this.getIdRootCategory = function() {
            return idRoot;
        };

        /**
         * @param $ul jQuery
         */
        this.enableList = function($ul) {
            $appendUl = $ul;
            appendList = true;
        };

        this.levelToString = function(index) {
            index = index - 0;//toint
            if (index === 1) {
                return Adminka._tr.heading;
            } else {
                return Adminka._tr.subheading;
            }
        };

        this.bindListRemove = function() {
            $inputContainer.find('input').each(function(i, input){
                this._bindRemove(
                    $appendUl.children('li:eq(' + i + ')'),
                    parseInt($(input).val(), 10)
                );
            }.of(this));
        };

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
            id = id - 0;
            setedIds.push( id );
            this._updateInput();
        };

        this.inputRemove = function(id) {
            id = id - 0;
            setedIds.unsetByContent(id);
            this._updateInput();
        };

        this.addToList = function(item) {
            this.inputAdd( item.id );
            if (appendList) {
                var $li = $(['<li>',
                    '<span class="name">', item.value ,'</span>',
                    '<i class="remove"></i>',
                '</li>'].join(''));
                $appendUl.append( $li );
                this._bindRemove( $li, item.id );
            }
        };

        this.getAjaxUrl = function() {
            return _ajaxUrl;
        };

        this.setAjaxUrl = function(url) {
            _ajaxUrl = url;
        };

        this.getResults = function(req, cb) {
            var searchText = req.term;
            $.ajax({
                url : this.getAjaxUrl(),
                type : 'GET',
                dataType : 'json',
                data : {
                    type : Adminka.ACTION_SEARCH,
                    setedIds : setedIds,
                    idRoot : this.getIdRootCategory(),
                    search : searchText
                },
                success : function(data) {
                    cb(data.items);
                }
            });
        };

        this.bindAutoComplete = function($input, appendTo) {

            var $list;
            var checked = false;
            var formattedData = [];
            var listSelector = '#' + appendTo;

            $input.autocomplete({
                minLength : 2,
                delay : 150,
                appendTo : listSelector,
                source : function( request, response ) {
                    this.getResults(request, function(data){
                        formattedData = [];
                        $.each(data, function(i, o){
                            formattedData.push({
                                compontent : o,
                                value : o.value
                            });
                        });
                        response(formattedData);
                    });
                }.of(this),
                create : function() {
                    $list = $(listSelector + ' .ui-autocomplete');
                }.of(this),
                open : function() {
                    checked = false;
                }.of(this),
                select : function(event, ui) {
                    this.addToList(ui.item.compontent);
                    close();
                }.of(this)
            }).keypress(function(e){
                if (e.which == 13 && checked === false) {
                    this.addToList(formattedData[0].compontent);
                    close();
                }
            }.of(this));

            var close = function() {
                if (formattedData[0] !== undefined) {
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

    AdminkaWork.prototype.GoodToMany = function(
        $inputContainer,
        inputName,
        inputSelector,
        autoCompleteDivSelector,
        type,
        ajaxUrl
    ) {
        var $input = $('#' + inputSelector);
        var search = new CategoriesSearch($inputContainer, inputName);
        search.setAjaxUrl( ajaxUrl );
        search.bindAutoComplete(
            $input,
            autoCompleteDivSelector
        );
        search.enableList($input.parent().siblings('.selectedItems'));
        search.bindListRemove()
    };

})();
