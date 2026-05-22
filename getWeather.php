<?php

header('Content-Type: application/json');

$lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
$lon = isset($_GET['lon']) ? (float)$_GET['lon'] : null;
$time = isset($_GET['time']) ? trim($_GET['time']) : null;

if ($lat === null || $lon === null) {
    echo json_encode(["error" => "Missing lat or lon"]);
    exit;
}

$url = "https://opendata-download-metfcst.smhi.se/api/category/snow1g/version/1/geotype/point/lon/$lon/lat/$lat/data.json?parameters=air_temperature,symbol_code";

$response = @file_get_contents($url);

if ($response === false) {
    echo json_encode(["error" => "SMHI request failed"]);
    exit;
}

$data = json_decode($response, true);

if (!isset($data['timeSeries'])) {
    echo json_encode(["error" => "Invalid SMHI response"]);
    exit;
}

$temp = null;
$code = null;

$closestForecast = null;
$closestDiff = PHP_INT_MAX;

$targetTime = $time ? strtotime($time) : time();

foreach ($data['timeSeries'] as $forecast) {

    $forecastTime = strtotime($forecast['time']);
    if ($forecastTime === false) continue;

    $diff = abs($forecastTime - $targetTime);

    if ($diff < $closestDiff) {
        $closestDiff = $diff;
        $closestForecast = $forecast;
    }
}

if ($closestForecast !== null) {

    $temp = $closestForecast['data']['air_temperature'] ?? null;
    $code = $closestForecast['data']['symbol_code'] ?? null;
}

echo json_encode([
    "lat" => $lat,
    "lon" => $lon,
    "time" => $time,
    "temp" => $temp,
    "code" => $code
]);