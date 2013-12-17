(function(){

    var Discount = function() {

        var $dialog;

        var DISCOUNT_TYPE_FIXED = 1;
        var DISCOUNT_TYPE_PERCENT = 2;

        this.setDialog = function($div) {
            $dialog = $div.dialog({
                width: 270
            });
            this._getDiscountValueInput().numeric();
        };

        this._getDiscountTypeInput = function() {
            return $dialog.find('input[name=discountType]:checked');
        };

        this._getDiscountType = function() {
            return this._getDiscountTypeInput().val() - 0.0;
        };

        this._getDiscountValueInput = function() {
            return $dialog.find('input[name=discountValue]');
        };

        this._formatDiscount = function() {
            switch (this._getDiscountType()) {
                case DISCOUNT_TYPE_FIXED:
                    return '-' + this._getDiscountValueInput().val();
                    break;
                case DISCOUNT_TYPE_PERCENT:
                    return '-' + this._getDiscountValueInput().val() + '%';
                    break;
            }
        };

        this.isValid = function() {
            var valid = true;
            if (this._getDiscountTypeInput()[0] === undefined) {
                Adminka.Error('Выбирите тип скидки');
                valid = false;
            }
            if (this._getDiscountValueInput().val() === '') {
                Adminka.Error('Заполните сумму скидки');
                valid = false;
            } else {
                if (this._getDiscountType() === DISCOUNT_TYPE_PERCENT) {
                    if (this._getDiscountValueInput().val() > 99) {
                        Adminka.Error('Сумма скидки введена не правильно');
                        valid = false;
                    }
                }
            }
            return valid;
        };

        this.confirm = function() {
            if (this.isValid()) {
                var items = Adminka.ItemsList().getChecked();
                var itemsCount = items.length;
                for (var i = 0; i < itemsCount; i++) {
                    (function(item){
                        item.edit({
                             discountType: this._getDiscountType(),
                             discountValue: this._getDiscountValueInput().val()
                        }, function(data) {
                            var res = data.itemStats;
                            item.getRow().children('td:eq(2)').html([
                                res.fullPrice, ' - ',
                                res.discount, ' = ',
                                res.currentPrice
                            ].join(' ') );
                            item.getRow().children('td:eq(3)').html(
                                this._formatDiscount()
                            );
                        }.of(this));
                    }.of(this))(items[i]);
                }
                this.close();
            }
        };

        this.open = function() {
            $dialog.dialog('open')
        };

        this.close = function() {
            $dialog.dialog('close');
            Adminka.uncheckItems();
        };

    };

    var discount;

    var initDiscount = function() {
        if (!(discount instanceof Discount)) {
            discount = new Discount();
            discount.setDialog( $('#discount') );
        }
    };

    AdminkaWork.prototype.DiscountDialog = function() {
        initDiscount();
        return {
            open: function() {
                var checked = Adminka.ItemsList().getChecked();
                var checkedCount = checked.length;
                if (checkedCount === 0) {
                    Adminka.Error('Выберите елементы');
                    discount.close();
                } else {
                    discount.open();
                }
            },
            confirm: function() {
                discount.confirm();
            },
            close: function() {
                discount.close();
            }
        };
    };

})();