var uploadImage = function(
	uploadUrl,
	$file,
	$fileInput,
	$previewImage,
	idPhoto,
	height,
	width
) {
	
	idPhoto = parseInt(idPhoto, 10);
	
	this.setId = function(id) {
		idPhoto = id;
		$fileInput.val(id);
	};
	
	this.setPhoto = function(path) {
		$previewImage.attr('src', path.replace(/\&amp\;/g,  "&") + '&time=' + new Date().getTime());
	};
	
	this.appendPhoto = function(data) {
		this.setId(parseInt(data.id, 10));
		this.setPhoto(data.path);
	};

	this._bind = function() {
		$file.change(function(){
			$file.upload([uploadUrl,
              '?id=', idPhoto,
              '&w=', height,
              '&h=', width,
              '&t=', new Date().getTime()
            ].join(''), function(answer){
				try {
					this.appendPhoto($.parseJSON(answer));
					$file.val('');
				} catch(e) {
					new Error(answer);
				}
			}.of(this));
		}.of(this));
	};
	
	this._bind();
};