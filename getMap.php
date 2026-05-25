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
      // start ekonomikum
      const startLng = 17.619748;
      const startLat = 59.859456;
      const startZ = 1;
      // slutkoordinater (för event **anpassa dessa**)
      const targetLng = 17.64254;
      const targetLat = 59.85237;
        // initiera Mazemap kartan, 110 är uppsala universitets campus id
        var map = new Mazemap.Map({
            container: 'map',
            campuses: 110,
            center: {lng: startLng, lat: startLat},
            zoom: 12,
            zLevel: startZ
        });

        var routeDrawer;
        var currentPopup;
        // körs när kartan laddats färdigt
        map.on('load', function() {
          // initiera ruttritaren
          routeDrawer = new Mazemap.AtoBTripBasicDrawer(map, {
            routeLineColorPrimary: '#0099EA', 
            showDirectionArrows: true
          });
          // anpassa zoom till start- och slutkoordinater
          if (targetLng && targetLat) {
            var bounds = new Mazemap.LngLatBounds(
              new Mazemap.LngLat(startLng, startLat), 
              new Mazemap.LngLat(targetLng, targetLat)
          );

          map.fitBounds(bounds, {
            padding: 50,
            maxZoom: 18,
            linear: false
          });
        }
          // beräkna och rita rutt
          onMapLoad();

        });
        // hämta vägbeskrivning, rita rutt samt popup
        function onMapLoad() {
          var targetLngLat = {
            lng: targetLng,
            lat: targetLat
          };
          var targetZ = map.zLevel;

          if(currentPopup) {
            currentPopup.remove();
          }

          if(routeDrawer) {
            routeDrawer.clear();
          }
            // formattera om för att passa Mazemap API
            var fromString = `${startLng},${startLat},${startZ}`;
            var toString = `${targetLngLat.lng},${targetLngLat.lat},${targetZ}`;
            // parametrar för beräkning av rutt
            const routeParams = {
              mode: "PEDESTRIAN", // prioriterar gång
              campusCollectionTag: "uu", // uppsala universitets tagg
              fromLngLatZ: fromString,
              toLngLatZ: toString,
              lang: "sv"
            };
            // anropa Mazemap API för att få rutt
            Mazemap.Data.getAtoBTrip(routeParams)
            .then(function(trip) {
              routeDrawer.setAtoBTrip(trip);
              // hämta information om destinationsplats
              Mazemap.Data.getPoiAt(targetLngLat, targetZ)
              .then(poi => {
                var poiName = (poi && poi.properties && poi.properties.name) ? poi.properties.name : "Framme!";
                // skapa och visa popup vid slutkoordinater
                currentPopup = new Mazemap.Popup()
                .setLngLat(targetLngLat)
                .setHTML(`${poiName}`)
                .addTo(map);
            });
        });
        }
    </script>
</body>