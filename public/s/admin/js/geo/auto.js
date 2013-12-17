(function(){

    var GeoLocation = function($input) {

        var formatedData = [];
        var current;
        var search = new geoSearch();

        this.formatAddress = function(location) {
            var formatted_address = [];
            var neededTypes = ['country', 'locality', 'sublocality', 'route', 'street_number'];
            var componentCount = (location.address_components).length;
            for (var currentComponent = componentCount - 1; currentComponent >= 0; currentComponent--) {
                if (neededTypes.indexOf(location.address_components[currentComponent].types[0]) != -1) {
                    formatted_address.push(
                        location.address_components[currentComponent].long_name
                    );
                }
            }
            return formatted_address.join(', ');
        };

        this.setCurrent = function(location) {
            current = location;
        };

        this.getCurrent = function() {
            return current;
        };

        this.bindAutocompleate = function($input) {
            $input.autocomplete({
                minLength: 2,
                delay: 200,
                source: function( request, response ) {
                    search.getResults(request, function(data){
                        formatedData = [];
                        $.each(data, function(i, o){
                            formatedData.push({
                                compontent: o,
                                value: this.formatAddress(o)
                            });
                        }.of(this));
                        response(formatedData);
                    }.of(this));
                }.of(this),
                select: function(event, ui) {
                    this.setCurrent(ui.item.compontent);
                }.of(this)
            })
        };

    };

    window.AutoGeo = function($input) {
        var location = new GeoLocation();
        location.bindAutocompleate($input);
    };

})();