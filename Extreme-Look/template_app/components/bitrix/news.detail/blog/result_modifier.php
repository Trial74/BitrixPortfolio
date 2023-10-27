<?if(count($arResult["ITEMS"]) < 1)
    return;

//DISPLAY_ACTIVE_TO//
foreach($arResult["ITEMS"] as &$arItem) {
    if(!isset($arItem["DISPLAY_ACTIVE_TO"]) && !empty($arItem["ACTIVE_TO"]))
        $arItem["DISPLAY_ACTIVE_TO"] = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arItem["ACTIVE_TO"], CSite::GetDateFormat()));
}
unset($arItem);