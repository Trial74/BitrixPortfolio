<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(count($arResult["ITEMS"]) < 1)
    return;
foreach($arResult["ITEMS"] as &$arItem) {
    $arItem['COUNT_COMMENTS'] = EX_GetCountElements(['IBLOCK_ID' => 122, 'ACTIVE'=> 'Y', 'PROPERTY_LIVE_ID_POST' => $arItem['ID']]);
    if($arItem['COUNT_COMMENTS'] == '') $arItem['COUNT_COMMENTS'] = 0;
}