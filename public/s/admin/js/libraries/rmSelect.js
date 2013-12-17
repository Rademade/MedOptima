(function($){

    var lists = [];
    var SelectList = function($div, changeCallBack) {
        "use strict";

        var opened = false;
        var bodyBinded = false;
        var $input;

        this._value = undefined;

        var _changeCallback = [];

        this.change = function(func) {
            if ($.isFunction(func)) {
                _changeCallback.push( func );
            }
        };

        this._bind = function() {
            "use strict";
            $div.find(".selectedLi, .selectUlMarker").bind('click.rmSelect', function(){
                if (this.isOpen()){
                    this.close();
                } else {
                    this.open();
                }
                return false;
            }.of(this));

            $div.find('li')
                .bind('click.rmSelect',function(e){
                    this.select($(e.currentTarget));
                    return false;
                }.of(this))
                .last()
                .addClass('last');
            if (!bodyBinded) {
                bodyBinded = true;
                $('body').bind('click.rmSelect', function(){
                    if (this.isOpen()) {
                        this.close();
                    }
                }.of(this));
            }
            this._findInput();
        };

        this._findInput = function() {
            $input = $div.find('input[type="hidden"]');
        };

        this._unbind = function() {
            "use strict";
            $div.children(".selectedLi, .selectUlMarker").unbind('.rmSelect');
            $div.find('li').unbind('.rmSelect');
        };

        this.isOpen = function() {
            return opened;
        };

        this.open = function() {
            "use strict";
            $.each(lists, function(i, o){
                if (o.isOpen()) {
                    o.close();
                }
            });
            var height = $div.find('a:first').height();
            var $ul = $div.children('ul');
            var listHeight = $ul.height();
            if ($div.offset().top + listHeight > $(window).height()) { //TODO don't work
//                height = listHeight * -1;
            }
            $ul.css('top', (height + 1) + 'px' ).show();
            opened = true;
        };

        this.refresh = function() {
            this._unbind();
            this._bind();
        };

        this.updateInput = function($a) {
            this._value = $a.data('val');
            if ($input[ 0 ] !== undefined) {
                $input.val( $a.data('val') );
            }
        };

        this.getValue = function() {
            if (this._value) {
                return this._value;
            } else {
                return $div.find('input').val();
            }
        };

        this.select = function($li) {
            "use strict";
            var $a = $li.children('a');
            $a.addClass('selectedLi');
            $a.setTo( $div.children(".selectedLi") );
            $div.find("li a.selectedLi").removeClass('selectedLi');
            $a.addClass('current');
            this.close();
            this.updateInput( $a );
            $.each(_changeCallback, function(i, func) {
                func( $a );
            });
            this.refresh();
            return true;
        };

        this.close = function() {
            "use strict";
            $div.children('ul').hide();
            opened = false;
        };

        this.change( changeCallBack );
        this._bind();
        lists.push( this );
    };

    $.fn.rmSelectList = function(callback) {
        var $e = $(this);
        var select = [];
        $.each($e, function(i, g){
            select.push( $(g).rmSelect(callback) );
        }.of(this));
        return select;
    };

    $.fn.rmSelect = function(callback) {
        var $e = $(this);
        if ($e.length > 1) {
            return $e.rmSelectList( callback );
        } else {
            if (!$e.data('rmSelect')) {
                $e.data('rmSelect', new SelectList($(this), callback));
            }
            return $e.data('rmSelect');
        }
    };

})(jQuery);