var MedOptima = function(config) {

    var _$ajaxLoader;
    var _ajaxLoaderSelector;

    var _cfg = config == undefined ? {} : config;

    this.setAjaxLoader = function(ajaxLoaderSelector) {
        _$ajaxLoader = $(ajaxLoaderSelector);
        _ajaxLoaderSelector = ajaxLoaderSelector;
    };

    this.showAjaxLoader = function($container) {
        var $existingLoader = $container.find(_ajaxLoaderSelector);
        if ( $existingLoader.length === 0 ) {
            var $clonedAjax = _$ajaxLoader.clone();
            $container.append($clonedAjax);
            $clonedAjax.show();
        } else {
            $existingLoader.show();
        }
    };

    this.hideAjaxLoader = function($container) {
        $container.find(_ajaxLoaderSelector).hide();
    };

    this.capitilizeFirst = function(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    };

    this.getCfg = function() {
        return _cfg;
    };

};

var Med = new MedOptima(config);