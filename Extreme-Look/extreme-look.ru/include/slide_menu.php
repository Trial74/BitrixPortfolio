<?global $arSettings;
$template = "";
if($arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-1")
	$template = "slide_menu_option_1";
elseif($arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-2")
	$template = "slide_menu_option_2";
elseif($arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-3" || $arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-4" || $arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-5")
	$template = "catalog_menu_option_3_4_5";
elseif($arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-6")
	$template = "catalog_menu_option_6";?>
<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"menu_vertical_vlad", 
	array(
		"ROOT_MENU_TYPE" => "left",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "36000000",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "Y",
		"ALLOW_MULTI_SELECT" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"COMPONENT_TEMPLATE" => "menu_vertical_vlad",
		"DELAY" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>

