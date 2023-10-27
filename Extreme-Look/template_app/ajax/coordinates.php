<?php
session_start();
header('Content-Type: application/json');

$result = false;
$city = '';

$get_API = "http://maps.googleapis.com/maps/api/geocode/json?latlng=";
$get_API .= round($_GET['lat'], 4) . ",";
$get_API .= round($_GET['lon'], 4);
$get_API .= '&language=ru';

$curl = curl_init();
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, $get_API);

$response = json_decode(curl_exec($curl));



if( isset($response->results[0]->address_components) ){
	foreach($response->results[0]->address_components as $component){
		if( isset($component->types) && in_array('locality', $component->types) ){
			if( strlen($component->long_name) ){
				$result = true;
				//$_SESSION['SELECTED_CITY'] = $component->long_name;
				$city = $component->long_name;
			}
			break;
		}
	}
}

echo json_encode([
	'result' => $result,
	'city'	=> $city
]);