
      const startLng = 17.619748;
      const startLat = 59.859456;
      const startZ = 1;

        var map = new Mazemap.Map({
            container: 'map',
            campuses: 110,
            center: {lng: startLng, lat: startLat},
            zoom: 12,
            zLevel: startZ
        });

        var routeDrawer;
        var currentPopup;

        map.on('load', function() {
          routeDrawer = new Mazemap.AtoBTripBasicDrawer(map, {
            routeLineColorPrimary: '#0099EA', 
            showDirectionArrows: true
          });

          onMapLoad()

        });

        function onMapLoad() {
          var targetLngLat = {
            lat: 59.8978,
            lng: 17.6333
          };
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