jQuery.validator.addMethod("float", function(number, element) {
    if (number == '')
    	return true;
	return !!number.match(/^[0-9]{1,20}(\.[0-9]{1,2})?$/);
}, 'Not float number');