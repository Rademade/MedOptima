(function(){

    var CategoriesSearch = function() {

        var _$boxElement;
        var _$listBox;
        var _$nameElement;
        var _$idElement;

        var _$listHolderElement;

        var _ajaxUrl;

        this.setBoxElement = function($boxElement) {
            _$boxElement = $boxElement;
        };

        this.getBoxElement = function() {
            return _$boxElement;
        };

        this.setNameElement = function( $nameElement ) {
            _$nameElement = $nameElement;
        };

        this.getNameElement = function() {
            return _$nameElement;
        };

        this.setIdInputElement = function( $idElement ) {
            _$idElement = $idElement;
        };

        this.getIdInputElement = function() {
            return _$idElement;
        };

        this.getAutocompleteHolderElement = function() {
            if (!(_$listHolderElement instanceof jQuery)) {
                this._createAutocompleteHolderElement();
            }
            return _$listHolderElement;
        };

        this.getResultBlock = function() {
            if (!(_$listBox instanceof jQuery)) {
                _$listBox = this.getBoxElement().find('.selectedItems')
            }
            return _$listBox;
        };

        this.setSelectedEntity = function(itemData) {
            this.getResultBlock().find('li').remove();
            this.getIdInputElement().val( itemData.id );
            this.getResultBlock().html( ['<li>',
                '<span class="name">', itemData.value, '</span>',
                '<i class="remove"></i>',
            '</li>'].join('') );
            this._bindListRemove();
        };

        this.getAjaxUrl = function() {
            return _ajaxUrl;
        };

        this.setAjaxUrl = function(url) {
            _ajaxUrl = url;
        };

        this._getResults = function(req, cb) {
            var searchText = req.term;
            $.ajax({
                url: this.getAjaxUrl(),
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

        this._bindAutocomplete = function(){
            this.getNameElement().autocomplete({
                minLength: 2,
                delay: 150,
                appendTo: this.getAutocompleteHolderElement(),
                source: function( request, response ) {
                    this._getResults(request, function(data) {
                        response( this._formatData( data ) );
                    }.of(this));
                }.of(this),
                select: function(event, ui) {
                    this.getNameElement().val('');
                    this.setSelectedEntity( ui.item.compontent );
                }.of(this)
            });
        };

        this._createAutocompleteHolderElement = function() {
            var $div = $('<div></div>');
            this.getBoxElement().append( $div );
            _$listHolderElement  = $div;
        };

        this._formatData = function(data) {
            var formatedData = [];
            $.each(data, function(i, object) {
                formatedData.push({
                    "compontent":   object,
                    "value":        object.value
                });
            });
            return formatedData
        };

        this._bindListRemove = function() {
            this.getResultBlock()
                .find('li i.remove')
                    .unbind('click')
                    .bind('click', function(e){
                        $(e.currentTarget).parent('li').remove();
                        this._clearIdInput();
                    }.of(this));
        };

        this._clearIdInput = function() {
            this.getIdInputElement().val( 0 );
        };

    };


    /**
     * @param $idBox jQuery
     * @param $idInput jQuery
     * @param $idEntity jQuery
     * @param ajaxUrl string
     */
    AdminkaWork.prototype.GoodToEntity = function(
        $idBox,
        $idInput,
        $idEntity,
        ajaxUrl
    ) {
        var search = new CategoriesSearch();
        search.setBoxElement( $idBox );
        search.setNameElement( $idInput );
        search.setIdInputElement( $idEntity );
        search.setAjaxUrl( ajaxUrl );
        search._bindAutocomplete();
        search._bindListRemove()
    };

})();
