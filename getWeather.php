<?php
/*Anropar smhi API, hämtar data för lufttemperatur samt symbol för särskilda koordinater*/
header('Content-Type: application/json');

// SMHI tar inte emot längre koordinater tydligen, vi måste korta ner.
$lat = isset($_GET['lat']) ? round((float)$_GET['lat'], 6) : null;
$lon = isset($_GET['lon']) ? round((float)$_GET['lon'], 6) : null;
$time = isset($_GET['time']) ? trim($_GET['time']) : null;

if ($lat === null || $lon === null) {
    echo json_encode(["error" => "Missing lat or lon"]);
    exit;
}
/*Avsmalnad url som pekar på de två önskade parametrarna*/
$url = "https://opendata-download-metfcst.smhi.se/api/category/snow1g/version/1/geotype/point/lon/$lon/lat/$lat/data.json?parameters=air_temperature,symbol_code";
/*Hämtar rådata från url, som är i json format*/
$response = @file_get_contents($url);
/*Kontroll*/
if ($response === false) {
    echo json_encode(["error" => "SMHI request failed, {$lon}, {$lat}"]);
    exit;
}
/*Avkodar json rådata till php-array*/
$data = json_decode($response, true);
/*Kontroll*/
if (!isset($data['timeSeries'])) {
    echo json_encode(["error" => "Invalid SMHI response"]);
    exit;
}

$temp = null;
$code = null;

$closestForecast = null;
$closestDiff = PHP_INT_MAX;
/*Sätter måltiden*/
$targetTime = $time ? strtotime($time) : time();
/*Loopar igenom tills hittar tidpunkt närmast måltiden*/
foreach ($data['timeSeries'] as $forecast) {

    $forecastTime = strtotime($forecast['time']);
    if ($forecastTime === false) continue;

    $diff = abs($forecastTime - $targetTime);
/*Uppdaterar ifall en närmare tidpunkt hittas*/
    if ($diff < $closestDiff) {
        $closestDiff = $diff;
        $closestForecast = $forecast;
    }
}
/*Extraherar värden för lufttemperatur samt symbol, ifall match inträffar*/
if ($closestForecast !== null) {

    $temp = $closestForecast['data']['air_temperature'] ?? null;
    $code = $closestForecast['data']['symbol_code'] ?? null;
}
/*Skickar tillbaka resultatet som json-objekt till klienten*/
echo json_encode([
    "lat" => $lat,
    "lon" => $lon,
    "time" => $time,
    "temp" => $temp,
    "code" => $code
]);