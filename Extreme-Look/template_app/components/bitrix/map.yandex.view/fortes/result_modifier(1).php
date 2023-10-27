<?
$curl = curl_init();
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

 // Метод для получения координат по адресу
function getLonLat($address, $curl){ 
	curl_setopt($curl, CURLOPT_URL, 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode($address) . '&language=ru');
	$response = json_decode(curl_exec($curl));
	
	$result = false;
	
	if( isset($response->results[0]->geometry->location) ){
		return true;
		$result = [];
		$result[] = $response->results[0]->geometry->location;
		
		foreach($response->results[0]->address_components as $component){
			if( in_array('locality', $component->types) )
				$result[] = $component->long_name;
		}
	}else	
	return false; //$result;
}

$markers = [];
$filter = [ "GROUPS_ID" => $arParams['GROUPS_ID'], "!UF_MAP_ADDRESS" => false];
$params = [ "SELECT" => ["ID", "UF_MAP_ADDRESS"]];
$rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), $filter, $params); // выбираем пользователей

while($user = $rsUsers->Fetch()) {
	$hasChanges = false;
	
	foreach( $user['UF_MAP_ADDRESS'] as $addressKey => $address ){	
		$label = $user['WORK_COMPANY'];
		
		$exploded = explode('||', $address);
		
		$text = "<strong>$user[WORK_COMPANY]</strong>";
		$text .= "<br/>" . $exploded[0];
		if( isset($user['WORK_PHONE']) )
			$text .= "<br/>Тел: ".$exploded[1];
			#$text .= "<br/>Тел: $user[WORK_PHONE]";
		#if( isset($user['WORK_STREET']) )
		#	$text .= '<br/>' . $user['WORK_STREET'];
		
		if( count($exploded) >= 5 ){
			$LON = $exploded[2];
			$LAT = $exploded[3];
			$cityName = $exploded[4];
		}
		else{
			$lonLat = getLonLat($address, $curl);
			
			if( $lonLat === false)
				continue;
				
			$LON = $lonLat[0]->lng;
			$LAT = $lonLat[0]->lat;
			$cityName = $lonLat[1];
			
			$user['UF_MAP_ADDRESS'][$addressKey] = $exploded[0] . '||' . $exploded[1] . '||' . $LON . '||' . $LAT . '||' . $cityName;
			
			$hasChanges = true;
		}
		
		$markers[$cityName][$user['ID']][] = [
			'LABEL'	 => $label,
			'TEXT'	 => $text,
			'LON' 	 => $LON,
			'LAT' 	 => $LAT
		];
	}
	
	if( $hasChanges ){
		$nUser = new CUser;
		$fields = [
			"UF_MAP_ADDRESS" => $user['UF_MAP_ADDRESS']
		]; 
		$nUser->Update($user['ID'], $fields);
	}
}

ksort($markers);

$arResult["MARKERS"] = $markers;

curl_close($curl);