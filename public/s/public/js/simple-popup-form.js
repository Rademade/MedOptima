MedOptima.prototype.SimplePopupForm = function() {

    var self = this;

    var _ajaxUrl;

    var _blocks = {};
    var _fields = {};
    var _buttons = {};

    this.setAjaxUrl = function(ajaxUrl) {
        _ajaxUrl = ajaxUrl;
    };

    this.setBlocks = function(blocks) {
        _blocks = blocks;
    };

    this.setFields = function(fields) {
        _fields = fields;
    };

    this.setButtons = function(buttons) {
        _buttons = buttons;
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
                if (fieldName == 'phone') {
                    $field.mask(Med.getCfg().phoneMask);
                }
                validator[method]($field.attr('id'));
            }
        });
        _blocks['form'].validate(validate);
    };

    this.bindActivateButton = function() {
        var $activateButton = _buttons['activateButton'];
        var $formContainer = _blocks['formContainer'];
        $(document).mouseup(function(e) {
            if (!$formContainer.is(e.target)
                && $formContainer.has(e.target).length === 0
            ) {
                $formContainer.fadeOut(200);
            }
        });
        $activateButton.click(function() {
            if (!$formContainer.is(":visible")) {
                $formContainer.fadeIn(200);
                self.resetDisplayState();
            } else {
                $formContainer.fadeOut(200);
            }
        });
    };

    this.bindSubmitButton = function() {
        _buttons['submitButton'].click(function() {
            _blocks['form'].submit();
        });
    };

    this.bindTryAgainButton = function() {
        _buttons['tryAgainButton'].click(function() {
            self.resetDisplayState();
        });
    };

    this.submitForm = function() {
        _blocks['form'].hide();
        Med.showAjaxLoader(_blocks['formContainer']);
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
        Med.hideAjaxLoader(_blocks['formContainer']);
        _blocks['errorBox'].show();
    };

    this.showThank = function() {
        this.clearFields();
        _blocks['formContainer'].fadeOut(200);
        _buttons['activateButton'].fadeOut(200);
        _blocks['thankYouBox'].show();
    };

    this.clearFields = function() {
        _.each(_fields, function($field) {
            if ($field instanceof $) {
                $field.val('');
            }
        })
    };

    this.getData = function() {
        var data = {};
        _.each(_fields, function($field) {
            if ($field instanceof $) {
                data[$field.attr('data-alias')] = $field.val();
            }
        });
        return data;
    };

    this.resetDisplayState = function() {
        _blocks['thankYouBox'].hide();
        _blocks['errorBox'].hide();
        Med.hideAjaxLoader(_blocks['formContainer']);
        _blocks['form'].show();
        _blocks['formContainer'].show();
    };

};

MedOptima.prototype.SimplePopupForm.init = function(ajaxUrl, fieldSelectors) {
    var form = new Med.SimplePopupForm();

    var $formContainer = $('#form-container');
    var $form = $formContainer.find('form');
    var $errorBox = $formContainer.find('#error-box');

    var blocks = {
        "formContainer": $formContainer,
        "form": $form,
        "errorBox": $errorBox,
        "thankYouBox": $('#thank-you-box')
    };
    var fields = {};
    _.each(fieldSelectors, function(fieldSelector, fieldName) {
        fields[fieldName] = $form.find(fieldSelector);
    });
    form.setAjaxUrl(ajaxUrl);
    form.setBlocks(blocks);
    form.setFields(fields);
    form.setButtons({
        "submitButton": $form.find('#form-submit-button'),
        "activateButton": $('#form-activate-button'),
        "tryAgainButton": $errorBox.find('#form-try-again-button')
    });
    form.bindValidate();
    return form;
};