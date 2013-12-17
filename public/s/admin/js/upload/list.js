AdminkaWork.prototype.Gallery = function(
	idGallery,
	ajaxUrl,
	uploadUrl,
	$block,
	$button
) {

    var self = this;

	var height = 150;
	var width = 150;

	var parsePath = function(path) {
		return path.replace(/\&amp\;/g,  "&") + '&time=' + new Date().getTime();
	};

	this.setPhotoWidth = function(w) {
		width = parseInt(w, 10);
	};

	this.setPhotoHeight = function(h) {
		height = parseInt(h, 10);
	};

	this.appendPhoto = function(data) {
		$block.append([
            '<li data-id="', data.id, '">',
                '<img src="', parsePath(data.path), '" alt=""/>',
                '<i class="delete delete-photo-link"></i>',
            '</li>'
        ].join(''));
		this._sortRefresh();
		this._bindDelete();
	};

	var ajaxQuery = function(data, cb) {
		data.id = idGallery;
		$.ajax({
			type: "POST",
	        url: ajaxUrl,
	        dataType: "json",
	        data: data,
	        success: function(){
	        	if (cb) {
	        		cb();
	        	}
	        }
	   });
	};

	this.sendPositions = function() {
		var ids = [];
		$block.children('li').each(function(u, b){
			ids.push( parseInt($(b).attr('data-id'), 10) );
		});
		ajaxQuery({
			type: 4,
			ids: ids
		}, function(){});
	};

	this._sortRefresh = function() {
		$block.sortable('refresh');
	};

	this._bindSort = function() {
		$block.sortable({
			stop: function(event, ui) {
				this.sendPositions();
			}.of(this)
		});
	};

	this._bindDelete = function() {
		$block.find('.delete').unbind('click').bind('click', function(e){
			if (confirm('Do you really want delete this photo?')) {
				var $b = $(e.currentTarget).parent();
				$b.remove();
				ajaxQuery({
					type: 2,
					what: 'photo',
					idPhoto: $b.attr('data-id')
				}, function(){});
				this._sortRefresh();
			}
		}.of(this));
	};

	this._bindUpload = function() {
        new qq.FileUploader({
            element : $button[0],
            action : uploadUrl,
            allowedExtensions : [
                'jpg',
                'jpeg',
                'png'
            ],
            sizeLimit : 1024 * 1024 * 8, //8Mb
            multiple : true,
            template: '',
            params : {
                w : width,
                h : height,
                t : new Date().getTime()
            },
            classes: {
                button: 'uplProfile',
                drop: 'avatarDropArea',
                dropActive: 'avatarDropArea-active',
                list: 'qq-upload-list',
                file: 'qq-upload-file',
                spinner: 'qq-upload-spinner',
                size: 'qq-upload-size',
                cancel: 'qq-upload-cancel'
            },
            onComplete : function(id, fileName, photoData) {
                try {
                    self.appendPhoto(photoData);
                } catch(e) {
                    new Error(photoData);
                }
            }
        });
	};

	this._bindUpload();
	this._bindDelete();
	this._bindSort();

};