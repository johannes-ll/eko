<?php
// ändra
$latitude = 59.8579;
$longitude = 17.6392;
$zoomLevel = 15;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenStreetMap</title>


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #map {
            height: 500px;
            width: 100%;
            border-radius: 8px;
        }
    </style>
</head>
<body>


    <div id="map"></div>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
   
    <script>
        // ändra till let för låta ändra senare
        const lat = <?php echo $latitude; ?>;
        const lon = <?php echo $longitude; ?>;
        const zoom = <?php echo $zoomLevel; ?>;


        const map = L.map('map').setView([lat, lon], zoom);


        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);


        const marker = L.marker([lat, lon]).addTo(map);
        marker.bindPopup("<b>You are here</b>").openPopup();
    </script>


</body>
</html>