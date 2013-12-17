var Lang_Tabs = function() {
	
	var unusedCount = 0;
	var usedIds = [];
	var tabs;
		
	this.setUsedLangsIds = function(ids) {
		usedIds = ids;
		return this;
	};
	
	this.setUnusedLangsIds = function(ids) {
		unusedCount = ids.length;
		return this;
	};

	this._bindDeleteTab = function() {
		$("#tabs span.ui-icon-close").unbind('click').bind( "click", function() {
			if (confirm('You whant delete this tab?')) {
				var li = $(this).parent();
				var id = li.attr('data-id');
				var index = $( "li", tabs ).index(li);
				tabs.tabs( "remove", index );
				unusedCount++;
				usedIds.unsetByContent(id);
			}
		}.of(this));
	};
	
	this._bind = function() {
		tabs = $("#tabs").tabs({
			tabTemplate: "<li><a href='#{href}'>#{label}</a><span class='ui-icon ui-icon-close removeTab'>&nbsp;</span></li>"
		});
		this._bindDeleteTab();
	};
	
	this._bind();
};