$(function(){

    if ( ymaps !== undefined ) {

        ymaps.ready(function() {

            var map = new ymaps.Map("map-canvas", {
                center: [51.609809,46.013142],
                zoom: 15
            });

            map.controls
                .add('zoomControl')
                .add('typeSelector')
                .add('mapTools');

            var marker = new ymaps.Placemark([51.609809,46.013142]);

            map.geoObjects.add( marker );

        })
    }

});