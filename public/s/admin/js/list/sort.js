(function(){

	var SortList = function(
			$table,
			ajaxUrl
		) {

		var minPosition;
		
		this.setMinimum = function( position ) {
			minPosition = position;
		};
		
		this.getMinimum = function() {
			return minPosition;
		};
		
		this._save = function(sortedIds) {
			$.ajax({
				type: "POST",
		        url: ajaxUrl,
		        dataType: "json",
		        data: {
		        	type: Adminka.ACTION_SORT,
		        	ids: sortedIds
		        }
		   });
		};

        this.refresh = function() {
            $table.tableDnDUpdate();
        };
		
		this.bind = function() {
            $table.tableDnD({
				onDrop: function(table, row) {
		            var rows = table.tBodies[0].rows;
		            var sortedRows = {};
		            for (var i=0; i < rows.length; i++) {
		            	sortedRows[
		            	   i + this.getMinimum()
		            	] = parseInt(
		            		rows[i].getAttribute('data-id'),
		            		10
		            	);
		            }
		            this._save( sortedRows );
		            Adminka.ItemsList().redecorate();
			    }.of(this)
			});
		};
		
	};


    var Sortable;

    /**
     * @param minPosition
     * @return {SortList}
     */
	AdminkaWork.prototype.ItemsListSort = function( minPosition ) {
		if (!(Sortable instanceof SortList)) {
            Sortable = new SortList(
				Adminka.ItemsList().getTable(),
				Adminka.ItemsList().getAjaxUrl()
			);
            Sortable.setMinimum( minPosition - 0 );
            Sortable.bind();
		}
		return Sortable;
	};
	
})();