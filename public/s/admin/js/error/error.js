(function(){

    var _errorList;

    var ErrorList = function() {

    	var ul = undefined,
    		maxWidth = 0;
    	var errors = [];

    		this.getUl = function() {
    		if (ul === undefined) {
    			ul = $('#globalError');
    		}
    		return ul;
    	};


    	this._addView = function(e) {
    		var dom = $('<li>' + e.getText() + '<i class="error-close"></i></li>');
    		dom.appendTo(this.getUl());
    		return dom;
    	};

    	this.add = function(e) {
    		this._checklimit();
    		var dom = this._addView(e);
    		e.setDom(dom, this, errors.push(e) - 1);
    		dom.find('i').bind('click', function(){
    			e.close();
    		});
    		this.show();
    		e.autoClose(5000);
    	};

    	this._checklimit = function() {
    		if (errors.length > 4) {
    			this.getUl().children('li:first').remove();
    			this.remove(0);
    		}
    	};

    	this.show = function() {
    		this.getUl().removeClass('hide').parent().removeClass('hide');
    		$.each(errors, function(i, e){
    			e.show();
    		});
            this.getUl().find('.last').removeClass('last');
            this.getUl().find('li:last').addClass('last');
    	};

    	this.remove = function(i) {
    		errors.splice(i, 1);
    	};

    };
    var Error = function(text) {

    	var e;
    	var index;

    	this.getText = function() {
    		return text;
    	};

    	this.setDom = function(domE, myIndex) {
    		e = domE;
    		index = myIndex;
    	};

    	this.close = function() {
    		e.slideToggle(200, function(){
    			e.remove();
    		});
    		this._getErrorList().remove(index);
    	};

    	this.autoClose = function(time) {
    		setTimeout(function(){
    			this.close();
    		}.of(this), time);
    	};

    	this.show = function() {
    		e.fadeIn();
    	};

    	this._getErrorList = function() {
    		if (!(_errorList instanceof ErrorList)) {
    			_errorList = new ErrorList();
    		}
    		return _errorList;
    	};

    	this._getErrorList().add(this);
    };


    AdminkaWork.prototype.Error = function(errorText) {
        new Error(errorText);
   	};


})();
