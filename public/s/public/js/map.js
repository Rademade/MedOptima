$(function(){

    if ( ymaps !== undefined ) {
        var myMap;

        ymaps.ready(init);

        function init () {
            myMap = new ymaps.Map("map-canvas", {
                center: [51.609809,46.013142],
                zoom: 15
            });

            myMap.controls.add('zoomControl').add('typeSelector').add('mapTools');

            var myPlacemark = new ymaps.GeoObject({
                geometry: {
                    type: "Point",
                    coordinates: [51.609809,46.013142]
                }});

            var myPlacemark = new ymaps.Placemark([51.609809,46.013142]);

            myMap.geoObjects.add(myPlacemark);

        }
    }

});