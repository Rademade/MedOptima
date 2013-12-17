(function(){

	var ItemsList = function() {
		
		var $table,
			ajaxUrl,
			items = {};
		
		this.setTable = function($t) {
			$table = $t;
		};
		
		this.getTable = function() {
			return $table;
		};

        this.getRows = function() {
            return this.getTable().find('tbody tr');
        };
		
		this.setAjaxUrl = function(url) {
			ajaxUrl = url;
		};
		
		this.getAjaxUrl = function() {
			return ajaxUrl;
		};
		
		this.getItem = function(idItem) {
            idItem = idItem-0;
			return items[idItem];
		};

		/**
		 * @param item {Item}
		 * @return void
		 */
		this.addItem = function(item) {
            items[ item.getId() ] = item;
		};

        this.clearItems = function() {
            items = {}
        };

        this.getChecked = function() {
            var checked = [];
            this.getRows().find('.rowCheck:checked').each(function(i, v){
                checked.push( this.getItem(v.value) );
            }.of(this));
            return checked;
        };

		this.redecorate = function() {
			var i = 0;
            this.getRows().each(function(){
				++i;
				var $tr = $(this).attr('data-index', i).removeClass('grey white');
				if ( i%2 == 1 ) {
					$tr.addClass('grey');
				} else {
					$tr.addClass('white');
				}
			});
		};
	};

    AdminkaWork.prototype.uncheckItems = function() {
        this.ItemsList()
            .getRows()
            .find('.rowCheck:checked')
            .attr('checked', false);
    };

    AdminkaWork.prototype.removeChecked = function() {
        var checked = this.ItemsList().getChecked();
        var checkedCount = checked.length;
        if (checkedCount === 0) {
            this.Error('Не выбраны елементы для удаления');
        }
        for (var i = 0; i < checkedCount; i++) {
            checked[i].drop(true);
        }
    };

    AdminkaWork.prototype.ItemsListRefresh = function() {
        this.ItemsListUpdate();
        this.ItemsList().redecorate();
    };

    AdminkaWork.prototype.ItemsListUpdate  = function() {
        this.ItemsList().clearItems();
        this.ItemsList().getRows().each(function(i, v){
            Adminka.Item( $(v).attr('data-id') );
        });
    };

    var itemsList;

    AdminkaWork.prototype.ItemsList = function() {
		if (!(itemsList instanceof ItemsList)) {
            itemsList = new ItemsList();
            itemsList.setTable( $('#itemsList') );
            this.ItemsListUpdate();
		}
		return itemsList;
	};
	
})();