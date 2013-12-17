(function(){
	
	var colorPicker = function($preview, $input) {
		
		this.getColor = function() {
			return $input.val();
		};
		
		this.setColor = function(hex) {
			$preview.css('backgroundColor', '#' + hex);
			$input.val( hex );
		};
		
		this.bind = function() {
			$preview.ColorPicker({
				color: this.getColor(),
				onShow: function (colpkr) {
					$(colpkr).fadeIn(300);
					return false;
				}.of(this),
				onHide: function (colpkr) {
					$(colpkr).fadeOut(300);
					return false;
				}.of(this),
				onChange: function (hsb, hex, rgb) {
					this.setColor( hex );
				}.of(this)
			});
		};
		
	};
	
	AdminkaWork.prototype.colorPiker = function($preview, $input) {
		(new colorPicker($preview, $input)).bind();
	};

	
})();