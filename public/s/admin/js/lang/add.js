var Lang_Add = function(filedsTemplate, tmpID, defaultId) {
	
	var selectBox;
	var addButton;
	var unusedCount = 0;
	var usedIds = [];
	var tabs;
	
	var bindE = 0;
	
	this.setSelect = function($select) {
		selectBox = $select;
		new SelectList( $select, function(){
			
		}.of(this));
		$select.hide();
		this._bind();
		return this;
	};
	
	this.getSelect = function() {
		return selectBox;
	};
	
	this.setAddButton = function(jButton) {
		addButton = jButton;
		this._bind();
		return this;
	};
	
	this.setUsedLangsIds = function(ids) {
		usedIds = ids;
		this._bind();
		return this;
	};
	
	this.setUnusedLangsIds = function(ids) {
		unusedCount = ids;
		this._bind();
		return this;
	};
	
	this._rebuildSelect = function() {
		if (unusedCount.length !== 0) {//check with length
			selected.remove();
			addButton.show();
			e.hide();
		} else {
			li.hide();
		}
	};
	
	this._bind = function() {
		++bindE;
		if (bindE === 4) {
			tabs = $("#tabs").tabs({
				tabTemplate: "<li><a href='#{href}'>#{label}</a><span class='ui-icon ui-icon-close removeTab'>&nbsp;</span></li>"
			});
			addButton.bind('click', function(){
				$(this).hide();
				selectBox.show();
			});
			//TODO
			/*selectBox.bind('change', function(){
				var e = this.getSelect(),
					li = e.parent(),
					selected = e.children('option:selected'),
					iso = selected.attr('data-iso'),
					id = parseInt(selected.val(), 10),
					name = selected.text();
				usedIds.push(id);
				unusedCount.unsetByContent(id);
				this._addTab(id, iso, name);
			}.of(this));*/
			$("#tabs span.ui-icon-close").bind( "click", function() {
				if (confirm('You whant delete this tab?')) {
					var li = $(this).parent();
					var id = li.attr('data-id');
					var index = $( "li", tabs ).index(li);
					tabs.tabs( "remove", index );
					unusedCount.push(id);
					usedIds.unsetByContent(id);
					this._rebuildSelect();
				}
			}.of(this));
		}
	};
	
	this._addTab = function(id, iso, name) {
		tabs.tabs('add', '#' + iso, name);
		$('#tabLI li:last').attr('data-id', id);
		var tab = $('#' + iso).html(['<table class="items edit-inside" width="100%" cellpadding="0" cellspacing="0">',
		   '<tbody>',
		   		filedsTemplate.replace(
		   			new RegExp(tmpID, 'g'),
		   			id
		   		),
		   	'</tbody>',
		'</table>'].join(''));
		var switchInputs = $('.lang_' + id);
		$('.lang_' + defaultId).each(function(i, v){
			$(switchInputs[i]).val($(v).val());
			//TODO TEXTAREA NOT COPY
		});
		
	};

};