<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/partners/functions_partner.php");

$markers = [];
$filter = [ "GROUPS_ID" => [9,11,12,13], "UF_PAGE_PART" => true];
$params = [ "SELECT" => ["ID", "UF_MAP_ADDRESS", "UF_COUNTRY", "UF_PAGE_PART", 'UF_SITY_PAT', "UF_LON", "UF_LAT"]];
$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, $params);

while($user = $rsUsers->Fetch()) {
	$hasChanges = false;
	
	foreach( $user['UF_MAP_ADDRESS'] as $addressKey => $address ){
		$label = $user['WORK_COMPANY'];
		$exploded = explode('||', $address);
		$text = "<strong>" . $user['WORK_COMPANY'] . "</strong>";
		$text .= "<br/>" . $exploded[0];
		if( isset($user['WORK_PHONE']) )
			$text .= "<br/>Тел: " . $exploded[1];

		if(!empty($user['UF_LON'][$addressKey]) && !empty($user['UF_LAT'][$addressKey])){
            $LON = $user['UF_LON'][$addressKey];
            $LAT = $user['UF_LAT'][$addressKey];
            $cityName = $user['UF_SITY_PAT'][$addressKey];
        }
		else{
            $lonLat = lowerCornerAnDupperCorner($exploded[0]);
            if( $lonLat === false)
                continue;
            $LON = $lonLat[0];
            $LAT = $lonLat[1];
            $cityName = $lonLat[2];
            //$user['UF_MAP_ADDRESS'][$addressKey] = $exploded[0] . '||' . $exploded[1] . '||' . $LON . '||' . $LAT;
            $user['UF_LON'][$addressKey] = $LON;
            $user['UF_LAT'][$addressKey] = $LAT;
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
			//"UF_MAP_ADDRESS" => $user['UF_MAP_ADDRESS'],
            "UF_LON" => $user['UF_LON'],
            "UF_LAT" => $user['UF_LAT']
		]; 
		$nUser->Update($user['ID'], $fields);
	}
}
ksort($markers);
$arResult["MARKERS"] = $markers;
?>