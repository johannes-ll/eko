<?php


$lat = 59.8;
$lon = 17.6;


$url = "https://opendata-download-metfcst.smhi.se/api/category/snow1g/version/1/geotype/point/lon/{$lon}/lat/{$lat}/data.json?parameters=air_temperature";


// array med inställningar att hämta samt identifiera
$options = ["http" => ["method" => "GET", "header" => "User-Agent: FlyFrånEkoStudentProjekt/1.0\r\n"]];


// skapar strömkontext, paketerar och tillåter identifieringen
$context = stream_context_create($options);


// anropar data som kommer i json format
$get_json = file_get_contents($url, false, $context);


// konverterar till php-array format som kan hanteras
$converted_json = json_decode($get_json, true);


// if tillsammans med foreach som kollar konverterade datan, om inte tom så ta första sedan break
if (!empty($converted_json['timeSeries'])) {
    foreach ($converted_json['timeSeries'] as $parameter) {
        if (isset($parameter['data'])) {
            $temp = $parameter['data']['air_temperature'];
            break;
        }
    }
} else {
    // annars printa (förbättra detta @@@@@)
    $timeseries = $converted_json['timeSeries'];
    print_r($timeseries);
}


echo $temp;
?>
