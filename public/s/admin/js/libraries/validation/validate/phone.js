jQuery.validator.addMethod("phone", function(phone_number, element) {
    if (phone_number == '')
    	return true;
	return !!phone_number.match(/^\+[0-9]{8,14}$/);
}, 'Not phone number');