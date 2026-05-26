// konstanter som aldrig behöver ändras, vi utgår alltid från eko.
const startLng = 17.619748
const startLat = 59.859456
const startZ = 1

// vi skapar vår mazemap karta
var map = new Mazemap.Map({
    container: 'map',
    campuses: 110,
    center: { lng: startLng, lat: startLat },
    zoom: 12,
    zLevel: startZ
})

var routeDrawer
var currentPopup

// när kartan laddar --> skapa drawer
// vi gör inget mer eftersom vi preloadar mazemap kartan
map.on('load', function () {

    routeDrawer = new Mazemap.AtoBTripBasicDrawer(map, {
        routeLineColorPrimary: '#0099EA',
        showDirectionArrows: true
    })
})


// vår globala setRoute funktion som vi anropar från app.js
window.setRoute = function(targetLngLat) {

    const targetZ = map.zLevel


    if (routeDrawer) {
        routeDrawer.clear()
    }

    // konvertera våra koordinater så mazemap förstår
    const fromString = `${startLng},${startLat},${startZ}`
    const toString = `${targetLngLat.lng},${targetLngLat.lat},${targetZ}`

    const routeParams = {
        mode: "PEDESTRIAN",
        campusCollectionTag: "uu",
        fromLngLatZ: fromString,
        toLngLatZ: toString,
        lang: "sv"
    }


    Mazemap.Data.getAtoBTrip(routeParams)
        .then(function(trip) {

            // rita ut trip
            routeDrawer.setAtoBTrip(trip);

            // kolla upp bounds för kartan
            const bounds = new Mazemap.LngLatBounds();

            bounds.extend([startLng, startLat]);
            bounds.extend([targetLngLat.lng, targetLngLat.lat]);

            map.fitBounds(bounds, {
                padding: {top: 50, bottom: 50, left: 50, right: 50},
                maxZoom: 17,
                linear: false
            });

            return Mazemap.Data.getPoiAt(targetLngLat, targetZ)
        })
        .then(poi => {})
}