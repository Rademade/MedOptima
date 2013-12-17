var inArray = function(e, array) {
	var l = array.length;
	for(var i = 0; i < l; i++) {
		if (parseInt(array[i], 10) === parseInt(e,10)) {
			return true;
		}
	}
	return false;
};
Function.prototype.of = function (b) {
	var a = this;
	return function () {
		return a.apply(b, arguments);
	};
};
Array.prototype.swap = function (d, c) {
	var e = this[d];
	this[d] = this[c];
	this[c] = e;
};
Array.prototype.unsetByContent = function(content) {
	var s = this.length;
	for (var i=0; i < s; i++) {
		if (this[i] === content) {
			this.splice(i, 1);
			return ;
		}
	};
};
jQuery.fn.swapWith = function(to) {
    return this.each(function() {
        var copy_to = $(to).clone(true);
        var copy_from = $(this).clone(true);
        $(to).replaceWith(copy_from);
        $(this).replaceWith(copy_to);
    });
};