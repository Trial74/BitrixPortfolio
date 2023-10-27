<? $APPLICATION->ShowHead();?>
<? //$APPLICATION->ShowCSS();?>
<script data-skip-moving="true" src="/bitrix/templates/mobileapp/js/jquery.js"></script>
<script>
  window.SITE_TEMPLATE_PATH = '<?=SITE_TEMPLATE_PATH?>/';
  window.LOADED_PAGE = '<?=$_GET['page']?>';
  window.MOBILE_GET = '<?=MOBILE_GET?>';
  window.PRODUCTS_PER_PAGE = <?=PRODUCTS_PER_PAGE?>;
  window.USER = {
    authorized: (<?=$USER->IsAuthorized() ? 1 : 0?> === 1)
  }
</script>

<div class="block">
	<?$APPLICATION->IncludeComponent(
		"bitrix:sale.order.ajax",
		"order_new",
		array(
			"ADDITIONAL_PICT_PROP_13" => "-",
			"ADDITIONAL_PICT_PROP_20" => "-",
			"ADDITIONAL_PICT_PROP_22" => "-",
			"ADDITIONAL_PICT_PROP_24" => "-",
			"ALLOW_AUTO_REGISTER" => "N",
			"ALLOW_USER_PROFILES" => "N",
			"BASKET_IMAGES_SCALING" => "standard",
			"BASKET_POSITION" => "after",
			"COMPATIBLE_MODE" => "Y",
			"DELIVERIES_PER_PAGE" => "20",
			"DELIVERY_FADE_EXTRA_SERVICES" => "N",
			"DELIVERY_NO_AJAX" => "N",
			"DELIVERY_NO_SESSION" => "Y",
			"DELIVERY_TO_PAYSYSTEM" => "d2p",
			"DISABLE_BASKET_REDIRECT" => "N",
			"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
			"PATH_TO_AUTH" => "/auth/",
			"PATH_TO_BASKET" => "basket.php",
			"PATH_TO_PAYMENT" => "/personal/order/payment/",
			"PATH_TO_PERSONAL" => "/personal/",
			"PAY_FROM_ACCOUNT" => "N",
			"PAY_SYSTEMS_PER_PAGE" => "20",
			"PICKUPS_PER_PAGE" => "20",
			"PRODUCT_COLUMNS_HIDDEN" => array(
			),
			"PRODUCT_COLUMNS_VISIBLE" => array(
				0 => "PREVIEW_PICTURE",
				1 => "PROPS",
			),
			"PROPS_FADE_LIST_1" => array(
			),
			"PROPS_FADE_LIST_2" => array(
			),
			"SEND_NEW_USER_NOTIFY" => "Y",
			"SERVICES_IMAGES_SCALING" => "standard",
			"SET_TITLE" => "Y",
			"SHOW_BASKET_HEADERS" => "N",
			"SHOW_COUPONS_BASKET" => "N",
			"SHOW_COUPONS_DELIVERY" => "N",
			"SHOW_COUPONS_PAY_SYSTEM" => "N",
			"SHOW_DELIVERY_INFO_NAME" => "Y",
			"SHOW_DELIVERY_LIST_NAMES" => "Y",
			"SHOW_DELIVERY_PARENT_NAMES" => "Y",
			"SHOW_MAP_IN_PROPS" => "N",
			"SHOW_NEAREST_PICKUP" => "N",
			"SHOW_ORDER_BUTTON" => "final_step",
			"SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
			"SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
			"SHOW_STORES_IMAGES" => "Y",
			"SHOW_TOTAL_ORDER_BUTTON" => "N",
			"SKIP_USELESS_BLOCK" => "Y",
			"TEMPLATE_LOCATION" => "popup",
			"TEMPLATE_THEME" => "site",
			"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
			"USE_CUSTOM_ERROR_MESSAGES" => "N",
			"USE_CUSTOM_MAIN_MESSAGES" => "N",
			"USE_PREPAYMENT" => "N",
			"USE_YM_GOALS" => "N",
			"COMPONENT_TEMPLATE" => "order_new",
			"ALLOW_NEW_PROFILE" => "N",
			"ADDITIONAL_PICT_PROP_23" => "-",
			"ADDITIONAL_PICT_PROP_25" => "-",
			"ADDITIONAL_PICT_PROP_26" => "-",
			"USE_PRELOAD" => "Y",
			"COMPOSITE_FRAME_MODE" => "A",
			"COMPOSITE_FRAME_TYPE" => "AUTO",
			"ALLOW_APPEND_ORDER" => "Y",
			"SHOW_NOT_CALCULATED_DELIVERIES" => "L",
			"SHOW_VAT_PRICE" => "Y",
			"ACTION_VARIABLE" => "action",
			"USE_ENHANCED_ECOMMERCE" => "N"
		),
		false
	);?>

	<?

	$APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "main", Array(
		"ID" => "1",	// Соглашение
		"IS_CHECKED" => "Y",	// Галка согласия проставлена по умолчанию
		"AUTO_SAVE" => "Y",	// Сохранять автоматически факт согласия
		"IS_LOADED" => "Y",	// Загружать текст соглашения сразу
		"REPLACE" => array(
			"button_caption" => "Оформить заказ",
			"fields" => array(
				0 => "Email",
				1 => "Телефон",
				2 => "Имя",
			),
		),
		"COMPONENT_TEMPLATE" => ".default"
	),
		false
	);

	?>

</div>
<div id="app" style="display: none"></div>
<script src="<?=SITE_TEMPLATE_PATH?>/js/scripts.js?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/js/scripts.js')?>"></script>
