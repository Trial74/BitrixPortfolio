<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent("altop:menu.links.enext", "",
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "23",
		"DEPTH_LEVEL" => "3",
		"COUNT_ELEMENTS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y"
    ),
	false,
	Array("HIDE_ICONS" => "Y")
);
$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);?>