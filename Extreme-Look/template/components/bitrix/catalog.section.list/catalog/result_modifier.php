<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if($arResult['SECTIONS_COUNT'] > 0) {
    global $USER;
    $user = $USER->IsAuthorized();
	$boolClear = false;
	$arNewSections = array();
	foreach($arResult['SECTIONS'] as $key => $arOneSection) {
		if($arOneSection['RELATIVE_DEPTH_LEVEL'] > 1) {
			$boolClear = true;
			continue;
		}
		$arNewSections[] = $arOneSection;

        $rsSections = CIBlockSection::GetList(
            array(),
            array(
                "IBLOCK_ID" => 23,
                "ID" => $arResult['SECTIONS'][$key]['ID']
            ),
            false,
            array("ID", "UF_SECTION_HIDE", "UF_CLOSE_GROUP")
        );
        $arSect = $rsSections->Fetch();
        $arResult['SECTIONS'][$key]['SECTION_HIDE'] = $arSect['UF_SECTION_HIDE'];

        if($user){
            $arGroups = $USER->GetUserGroupArray();
            //7 - открыт всем
            //8 - Закрыт для розницы
            //9 - Закрыт всем партнёрам
            //10 - Закрыт новым партнёрам
            //11 - Закрыт старым партнёрам
            //12 - Закрыт всем

            if($arSect['UF_CLOSE_GROUP'] && count($arSect['UF_CLOSE_GROUP']) > 1){
                foreach($arSect['UF_CLOSE_GROUP'] as $group){
                    switch($group){
                        case 7:
                            $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true;
                            break;
                        case 8:
                            if(count(array_uintersect($arGroups, ROZN, "strcasecmp")) > 0){
                                $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;
                                break;
                            }else {$arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true; break;}
                            break;
                        case 9:
                            if(count(array_uintersect($arGroups, ALL_PART, "strcasecmp")) > 0){
                                $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;
                                break;
                            }else {$arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true; break;}
                            break;
                        case 10:
                            if(count(array_uintersect($arGroups, NEW_PART, "strcasecmp")) > 0){
                                $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;

                                break;
                            } else {$arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true; break;}
                            break;
                        case 11:
                            if(count(array_uintersect($arGroups, OLD_PART, "strcasecmp")) > 0){
                                $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;
                                break;
                            }
                            else {$arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true; break;}
                            break;
                        case 12:
                            $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;
                            break;
                        default:
                            $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true;
                            break;
                    }
                }
            }else{
                switch($arSect['UF_CLOSE_GROUP'][0]){
                    case 7:
                        $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                    case 8:
                        if(count(array_uintersect($arGroups, ROZN, "strcasecmp")) > 0)
                            $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;
                        else $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                    case 9:
                        if(count(array_uintersect($arGroups, ALL_PART, "strcasecmp")) > 0)
                            $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;
                        else $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                    case 10:
                        if(count(array_uintersect($arGroups, NEW_PART, "strcasecmp")) > 0)
                            $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;
                        else $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                    case 11:
                        if(count(array_uintersect($arGroups, OLD_PART, "strcasecmp")) > 0)
                            $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;
                        else $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                    case 12:
                            $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = false;
                    break;
                    default:
                        $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                }
            }
        }else{
            $arResult['SECTIONS'][$key]['SECTION_GROUP_HIDE'] = true;
        }
	}
	unset($arOneSection);
	if($boolClear) {
		$arResult['SECTIONS'] = $arNewSections;
		$arResult['SECTIONS_COUNT'] = count($arNewSections);
	}
	unset($arNewSections);
}