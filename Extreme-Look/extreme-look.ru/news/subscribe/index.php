<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подписка на новостную рассылку");?>

<?$APPLICATION->IncludeComponent(
	"bitrix:sender.subscribe", 
	"subscribe_vlad", 
	array(
		"COMPONENT_TEMPLATE" => "subscribe_vlad",
		"USE_PERSONALIZATION" => "Y",
		"CONFIRMATION" => "N",
		"SHOW_HIDDEN" => "N",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"SET_TITLE" => "Y",
		"HIDE_MAILINGS" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>