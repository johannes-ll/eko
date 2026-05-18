<?php

header('Content-Type: application/json');

$lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
$lon = isset($_GET['lon']) ? (float)$_GET['lon'] : null;
$time = $_GET['time'] ?? null;

if (!$lat || !$lon) {
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

$targetTime = null;

if ($time) {
    // normalize to SMHI format: YYYY-MM-DDTHH:00:00Z
    $targetTime = gmdate('Y-m-d\TH:00:00\Z', strtotime($time));
}

foreach ($data['timeSeries'] as $forecast) {

    // if time requested, match exact hour
    if ($targetTime && $forecast['time'] !== $targetTime) {
        continue;
    }

    $temp = $forecast['data']['air_temperature'] ?? null;
    $code = $forecast['data']['symbol_code'] ?? null;

    if ($targetTime) {
        break;
    }
}

echo json_encode([
    "lat" => $lat,
    "lon" => $lon,
    "time" => $time,
    "temp" => $temp,
    "code" => $code
]);