(function(){

    var Validate = function(validate) {

        var checkFields = function(field) {
            if (validate.rules === undefined) {
                validate.rules = {};
            }
            if (validate.messages === undefined) {
                validate.messages = {};
            }
            if (validate.rules[field] === undefined) {
                validate.rules[field] = {};
            }
            if (validate.messages[field] === undefined) {
                validate.messages[field] = {};
            }
        };

        var setValidation = function(field, rules, messages) {
            if (field === false) return ;
            checkFields(field);
            $.each(rules, function(name, rule) {
                validate.rules[field][name] = rule;
            });
            $.each(messages, function(name, rule) {
                validate.messages[field][name] = rule;
            });
        };

        this.setNameValidation = function(field) {
            setValidation(field, {
                required: true,
                minlength: 1,
                maxlength: 150
            }, {
                required: _tr.enterName,
                minlength: _tr.nameTooShort,
                maxlength: _tr.nameTooLong
            });
            return this;
        };

        this.setPhoneValidation = function(field) {
            setValidation(field, {
                required: true,
                phone: true
            }, {
                required: _tr.enterPhone,
                phone: _tr.phoneFormat
            });
            return this;
        };

        this.setEmailValidation = function(field) {
            setValidation(field, {
                required: true,
                email: true
            }, {
                required: _tr.enterEmail,
                email: _tr.enterEmail
            });
            return this;
        };

        this.setTextValidation = function(field) {
            setValidation(field, {
                required: true,
                minlength: 2,
                maxlength: 5000
            }, {
                required: _tr.enterText,
                minlength: _tr.textTooShort,
                maxlength: _tr.textTooLong
            });
            return this;
        };

    };

    MedOptima.prototype.validate = function(validate) {
        return new Validate( validate );
    };

    MedOptima.prototype.getBaseValidate = function() {
        return  {
            errorPlacement: function (error, element) {
                var $element = $(element);
                $element.parent().children('.error-message').remove();
                $(['<span class="error-message">',
                    $(error).text(),
                    '<i class="ico error-buble"></i>',
                    '</span>'].join('')).insertAfter($element);
            },
            highlight: function (element) {
                $(element).parent().removeClass('success').addClass('error');
            },
            unhighlight: function (element, errorClass) {
                $(element).parent().removeClass('error').addClass('success');
            }
        }
    };

})();