var MedOptima = function(config) {

    var _$ajaxLoader;
    var _ajaxLoaderSelector = '#ajax-loader';

    var _cfg = config == undefined ? {} : config;

    this.showAjaxLoader = function($container, show) {
        if (!_.isFunction(show)) {
            show = function($loader) {
                $loader.show();
            }
        }
        var $existingLoader = $container.find(_ajaxLoaderSelector);
        if ( $existingLoader.length === 0 ) {
            var $clonedAjax = _$getLoader().clone();
            $container.append($clonedAjax);
            show($clonedAjax);
        } else {
            show($existingLoader);
        }
    };

    this.hideAjaxLoader = function($container, hide) {
        if (!_.isFunction(hide)) {
            hide = function($loader) {
                $loader.hide();
            }
        }
        hide($container.find(_ajaxLoaderSelector));
    };

    this.cloneAjaxLoader = function() {
        return _$getLoader().clone();
    };

    this.capitilizeFirst = function(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    };

    this.getCfg = function() {
        return _cfg;
    };

    var _$getLoader = function() {
        if (!(_$ajaxLoader instanceof $)) {
            _$ajaxLoader = $(_ajaxLoaderSelector);
        }
        return _$ajaxLoader;
    };

};

var Med = new MedOptima(config);


