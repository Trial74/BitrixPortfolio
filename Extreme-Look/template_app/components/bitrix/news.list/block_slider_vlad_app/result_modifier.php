<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$itemPropImageVideo = array();

foreach($arResult["ITEMS"] as $key => $arItem) {
    $itemPropImageVideo['URL_OTHER'] = false; //Ссылка внешняя или внутренняя
    if(!empty($arItem["DISPLAY_PROPERTIES"]["PICTURE_MOBILE"]["FILE_VALUE"])){
        $itemPropImageVideo['MOBILE_IMG_VIDEO'] = $arItem["DISPLAY_PROPERTIES"]["PICTURE_MOBILE"]["FILE_VALUE"]["SRC"];
        $itemPropImageVideo['IMG'] = true;
    }else if(!empty($arItem["DISPLAY_PROPERTIES"]["VIDEO_MOBILE"]["FILE_VALUE"])){
        $itemPropImageVideo['MOBILE_IMG_VIDEO'] = $arItem["DISPLAY_PROPERTIES"]["VIDEO_MOBILE"]["FILE_VALUE"]["SRC"];
        $itemPropImageVideo['IMG'] = false;
    }
    else continue;

    if(!empty($arItem["DISPLAY_PROPERTIES"]["MOBILE_HEIGHT"]["VALUE"]) && !empty($arItem["DISPLAY_PROPERTIES"]["MOBILE_WIDTH"]["VALUE"])){
        $itemPropImageVideo['MOBILE_WIDTH'] = $arItem["DISPLAY_PROPERTIES"]["MOBILE_WIDTH"]["VALUE"];
        $itemPropImageVideo['MOBILE_HEIGHT'] = $arItem["DISPLAY_PROPERTIES"]["MOBILE_HEIGHT"]["VALUE"];
    }
    else{
        $itemPropImageVideo['MOBILE_WIDTH'] = 555;
        $itemPropImageVideo['MOBILE_HEIGHT'] = 1249;
    }

    if(!empty($arItem["DISPLAY_PROPERTIES"]["URL_APP"]["VALUE"]) || !empty($arItem["DISPLAY_PROPERTIES"]["LINK_BY_ITEM"]["VALUE"]) || !empty($arItem["DISPLAY_PROPERTIES"]["LINK_BY_SECTION"]["VALUE"])){
        if(!empty($arItem["DISPLAY_PROPERTIES"]["URL_APP"]["VALUE"])) {
            if(!empty($arItem["DISPLAY_PROPERTIES"]["URL_APP"]["VALUE"])) {
                $itemPropImageVideo['URL_APP'] = $arItem["DISPLAY_PROPERTIES"]["URL_APP"]["VALUE"];
                $itemPropImageVideo['URL_OTHER'] = true;
            }
        }
        else if(!empty($arItem["DISPLAY_PROPERTIES"]["LINK_BY_ITEM"]["VALUE"])){
            $itemPropImageVideo['URL_APP'] = '/page-catalog.element/element-id=' . $arItem["DISPLAY_PROPERTIES"]["LINK_BY_ITEM"]["VALUE"] . '/';
        }
        else if(!empty($arItem["DISPLAY_PROPERTIES"]["LINK_BY_SECTION"]["VALUE"])) {
            $itemPropImageVideo['URL_APP'] = '/page-catalog.section/section-id=' . $arItem["DISPLAY_PROPERTIES"]["LINK_BY_SECTION"]["VALUE"] . '/';
        }
        else {
            $itemPropImageVideo['URL_APP'] = null;
        }
    }

    $arResult["ITEMS"][$key]["VLAD_PROPERTY"] = $itemPropImageVideo;
    $itemPropImageVideo = array();
}
unset($key, $arItem);