MedOptima.prototype.SimplePopupForm = function() {

    var self = this;

    var _ajaxUrl;

    var _$blocks = {};
    var _fields = {};
    var _$buttons = {};

    this.setAjaxUrl = function(ajaxUrl) {
        _ajaxUrl = ajaxUrl;
    };

    this.setBlocks = function(blocks) {
        _$blocks = blocks;
    };

    this.setFields = function(fields) {
        _fields = fields;
    };

    this.setButtons = function(buttons) {
        _$buttons = buttons;
        _.each(buttons, function($button, buttonName) {
            var method = ['bind', Med.capitilizeFirst(buttonName)].join('');
            self[method]($button);
        });
    };

    this.bindValidate = function() {
        var validate = Med.getBaseValidate();
        validate.submitHandler = function() {
            self.submitForm();
            return false;
        };
        var validator = Med.validate(validate);
        _.each(_fields, function($field, fieldName) {
            if ($field instanceof $) {
                var method = ['set', Med.capitilizeFirst(fieldName), 'Validation'].join('');
                validator[method]($field.attr('id'));
            }
        });
        _$blocks.form.validate(validate);
    };

    this.bindActivateButton = function() {
        var $activateButton = _$buttons.activateButton;
        var $formContainer = _$blocks.formContainer;

        $(document).mouseup(function(e) {
            if (!$formContainer.is(e.target) && $formContainer.has(e.target).length === 0) {
                $formContainer.fadeOut(200);
            }
        });

        $activateButton.click(function() {
            if (!$formContainer.is(":visible")) {
                $formContainer.fadeIn(200);
            } else {
                $formContainer.fadeOut(200);
            }
        });
    };

    this.bindSubmitButton = function() {
        _$buttons.submitButton.click(function() {
            _$blocks.form.submit();
        });
    };

    this.bindTryAgainButton = function() {
        _$buttons.tryAgainButton.click(function() {
            self.resetDisplayState();
        });
    };

    this.submitForm = function() {
        _$blocks.form.hide();
        Med.showAjaxLoader(_$blocks.formContainer);
        $.ajax({
            url : _ajaxUrl,
            dataType : 'json',
            type : 'POST',
            data : this.getData()
        }).done(function(data) {
            if (data == undefined || !data.status) {
                self.showError();
            } else {
                self.showThank();
            }
        });
    };

    this.showError = function() {
        Med.hideAjaxLoader(_$blocks.formContainer);
        _$blocks.errorBox.find('.error-message').text( _tr.unknownError );
        _$blocks.errorBox.show();
    };

    this.showThank = function() {
        this.clearFields();
        _$blocks.errorBox.hide();
        _$buttons.activateButton.hide();
        _$blocks.formContainer.hide();
        _$blocks.thankYouBox.fadeIn( 400 );
    };

    this.clearFields = function() {
        _.each(_fields, function($field) {
            if ($field instanceof $) $field.val('');
        })
    };

    this.getData = function() {
        var data = {};
        _.each(_fields, function($field) {
            if ($field instanceof $) {
                data[ $field.attr('data-alias') ] = $field.val();
            }
        });
        return data;
    };

    this.resetDisplayState = function() {
        _$blocks.thankYouBox.hide();
        _$blocks.errorBox.hide();
        Med.hideAjaxLoader( _$blocks.formContainer );
        _$blocks.form.show();
        _$blocks.formContainer.show();
    };

};

MedOptima.prototype.SimplePopupForm.init = function(ajaxUrl, fieldSelectors) {
    var form = new Med.SimplePopupForm();

    var $formContainer = $('.form-container');
    var $form = $formContainer.find('form');
    var $errorBox = $formContainer.find('.error-box');

    var fields = {};
    _.each(fieldSelectors, function(fieldSelector, fieldName) {
        fields[fieldName] = $form.find(fieldSelector);
    });

    form.setAjaxUrl(ajaxUrl);
    form.setBlocks({
        "formContainer": $formContainer,
        "form": $form,
        "errorBox": $errorBox,
        "thankYouBox": $('.thank-you-box')
    });
    form.setFields(fields);
    form.setButtons({
        "submitButton": $form.find('.form-submit-button'),
        "activateButton": $('.form-activate-button'),
        "tryAgainButton": $errorBox.find('.form-try-again-button')
    });
    form.bindValidate();
    return form;
};