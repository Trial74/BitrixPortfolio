<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/partners/functions_partner.php");

$markers = [];
$filter = [ "GROUPS_ID" => [9,11,12,13], "UF_PAGE_PART" => true];
$params = [ "SELECT" => ["ID", "UF_MAP_ADDRESS", "UF_COUNTRY", "UF_PAGE_PART"]];
$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, $params); // выбираем пользователей

while($user = $rsUsers->Fetch()) {
	$hasChanges = false;
	
	foreach( $user['UF_MAP_ADDRESS'] as $addressKey => $address ){
		$label = $user['WORK_COMPANY'];
		$exploded = explode('||', $address);
		$text = "<strong>" . $user['WORK_COMPANY'] . "</strong>";
		$text .= "<br/>" . $exploded[0];
		if( isset($user['WORK_PHONE']) )
			$text .= "<br/>Тел: " . $exploded[1];
		
		if( count($exploded) >= 5 ){
			$LON = $exploded[2];
			$LAT = $exploded[3];
			$cityName = $exploded[4];
		}
		else{
			$lonLat = lowerCornerAnDupperCorner($exploded[0]);
			if( $lonLat === false)
				continue;
			$LON = $lonLat[0];
			$LAT = $lonLat[1];
			$cityName = $lonLat[2];
			
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
?>