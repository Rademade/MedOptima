(function(){

    var Items = {};

	var Item = function( idItem ) {

        if (Items[idItem]) {
            return Items[ idItem ];
        }

		var $row;
		var $statusLink;

        this.STATUS_DELETED = 0;
		this.STATUS_SHOW = 1;
		this.STATUS_HIDE = 2;
		
		this.getId = function() {
			return idItem;
		};
		
		/**
		 * @returns {jQuery}
		 */
		this.getRow = function() {
			if (!($row instanceof jQuery)) {
				$row = $('#row_' + this.getId());
			}
			return $row;
		};
		
		this.getStatusLink = function() {
			if (!($statusLink instanceof jQuery)) {
				$statusLink = this.getRow().find('.statusLink');
			}
			return $statusLink;
		};
		
		this.getAjaxUrl = function() {
			return Adminka.ItemsList().getAjaxUrl();
		};
		
		this._ajax = function(data, callback) {
			data.id = this.getId();
		    $.ajax({
		        type: "POST",
		        url: this.getAjaxUrl(),
		        data: data,
		        success: function(data) {
		        	if (callback !== undefined) {
		        		callback(data);
		        	}
		        }
		    });
		};
		
		this.show = function() {
			this.getStatusLink()
				.attr('onclick', '')
				.bind('click', function(){
					this.hide();
				}.of(this))
                .removeClass('show-link')
                .addClass('hide-link');
			this._ajax({
				type: Adminka.ACTION_STATUS,
				status: this.STATUS_SHOW
			});
		};
		
		this.hide = function() {
			this.getStatusLink()
				.attr('onclick', '')
				.bind('click', function(){
					this.show();
				}.of(this))
                .removeClass('hide-link')
                .addClass('show-link');
			this._ajax({
				type: Adminka.ACTION_STATUS,
				status: this.STATUS_HIDE
			});
		};
		
		this.drop = function(autoConfirm) {
            if (autoConfirm === undefined) {
                autoConfirm = false;
            }
			if (autoConfirm || confirm(Adminka._tr.deleteConfirm)) {
				this.getRow().remove();
				Adminka.ItemsList().redecorate();
				this._ajax({
					type: Adminka.ACTION_DELETE
				});
			}
		};

        this.edit = function(data, callback) {
            data.type = Adminka.ACTION_SAVE;
            this._ajax(data,callback);
        };

        Items[idItem] = this;
	};
	
	/**
	 * @param int idItem
	 * @returns {Item}
	 */
	AdminkaWork.prototype.Item = function(idItem) {
		var item = this.ItemsList().getItem( idItem );
		if (!(item instanceof Item)) {
			var item = new Item( idItem );
			this.ItemsList().addItem( item );
		}
		return item;
	};

})();