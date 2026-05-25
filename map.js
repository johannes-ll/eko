const startLng = 17.619748;
const startLat = 59.859456;
const startZ = 1;

var map = new Mazemap.Map({
    container: 'map',
    campuses: 110,
    center: { lng: startLng, lat: startLat },
    zoom: 12,
    zLevel: startZ
});

var routeDrawer;
var currentPopup;

map.on('load', function () {

    routeDrawer = new Mazemap.AtoBTripBasicDrawer(map, {
        routeLineColorPrimary: '#0099EA',
        showDirectionArrows: true
    });

    // optional initial route
    setRoute({
        lat: 59.8978,
        lng: 17.6333
    });
});


// MAKE THIS GLOBAL
window.setRoute = function(targetLngLat) {

    const targetZ = map.zLevel;

    if (currentPopup) {
        currentPopup.remove();
    }

    if (routeDrawer) {
        routeDrawer.clear();
    }

    const fromString = `${startLng},${startLat},${startZ}`;
    const toString = `${targetLngLat.lng},${targetLngLat.lat},${targetZ}`;

    const routeParams = {
        mode: "PEDESTRIAN",
        campusCollectionTag: "uu",
        fromLngLatZ: fromString,
        toLngLatZ: toString,
        lang: "sv"
    };


    Mazemap.Data.getAtoBTrip(routeParams)
        .then(function(trip) {

            routeDrawer.setAtoBTrip(trip);

            return Mazemap.Data.getPoiAt(targetLngLat, targetZ);
        })
        .then(poi => {

            // const poiName =
            //     (poi && poi.properties && poi.properties.name)
            //         ? poi.properties.name
            //         : "Framme!";

            // currentPopup = new Mazemap.Popup()
            //     .setLngLat(targetLngLat)
            //     .setHTML(poiName)
            //     .addTo(map);
        });
};