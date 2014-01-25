jQuery.validator.addMethod("phone", function(phone_number, element) {
    if (phone_number == '')
        return true;
    return !!phone_number.match(Med.getCfg().phoneRegex);
}, _tr.phoneFormat);