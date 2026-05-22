<!DOCTYPE html>
<head>
    <meta name="viewport" id="vp" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />
    <meta charset="utf-8" />


    <link rel="stylesheet" href="https://api.mazemap.com/js/v2.2.6/mazemap.min.css">
    <script type='text/javascript' src='https://api.mazemap.com/js/v2.2.6/mazemap.min.js'></script>

    <style>
        body { margin:0px; padding:0px; width: 100vw; height:100vh; }
        #map { width: 100%; height: 100%; }
    </style>
</head>
<body>
    <div id="map" class="mazemap"></div>

    <script>

      const startLng = 17.619748;
      const startLat = 59.859456;
      const startZ = 1;

        var map = new Mazemap.Map({
            container: 'map',
            campuses: 110,
            center: {lng: startLng, lat: startLat},
            zoom: 17,
            zLevel: startZ
        });

        var routeDrawer;
        var currentPopup;

        map.on('load', function() {
          routeDrawer = new Mazemap.AtoBTripBasicDrawer(map, {
            routeLineColorPrimary: '#0099EA', 
            showDirectionArrows: true
          });

          map.on('click', onMapClick);
        });

        function onMapClick(e) {
          var targetLngLat = e.lngLat;
          var targetZ = map.zLevel;

          if(currentPopup) {
            currentPopup.remove();
          }

          if(routeDrawer) {
            routeDrawer.clear();
          }

            var fromString = `${startLng},${startLat},${startZ}`;
            var toString = `${targetLngLat.lng},${targetLngLat.lat},${targetZ}`;

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

            Mazemap.Data.getPoiAt(targetLngLat, targetZ)
            .then(poi => {
            var poiName = (poi && poi.properties && poi.properties.name) ? poi.properties.name : "Framme!";

              currentPopup = new Mazemap.Popup()
              .setLngLat(targetLngLat)
              .setHTML(`${poiName}`)
              .addTo(map);
            });
        });
        }
    </script>
</body>