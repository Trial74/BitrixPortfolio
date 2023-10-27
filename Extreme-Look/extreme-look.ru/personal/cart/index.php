<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");?>

<?$APPLICATION->IncludeComponent(
	"altop:sale.basket.basket", 
	"basket_basket_vlad", 
	array(
		"PATH_TO_ORDER" => "/personal/orders/make/",
		"HIDE_COUPON" => "N",
		"COLUMNS_LIST_EXT" => array(
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "DELAY",
			4 => "PRICE",
			5 => "QUANTITY",
			6 => "SUM",
		),
		"PRICE_VAT_SHOW_VALUE" => "N",
		"USE_PREPAYMENT" => "Y",
		"QUANTITY_FLOAT" => "Y",
		"CORRECT_RATIO" => "N",
		"AUTO_CALCULATION" => "Y",
		"SET_TITLE" => "Y",
		"ACTION_VARIABLE" => "basketAction",
		"COMPATIBLE_MODE" => "Y",
		"USE_GIFTS" => "Y",
		"GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_TEXT_LABEL_GIFT" => "",
		"GIFTS_PRICE_CODE" => array(
			0 => "Розница",
			1 => "BASE",
		),
		"GIFTS_SHOW_PRICE_COUNT" => "1",
		"GIFTS_PRICE_VAT_INCLUDE" => "Y",
		"GIFTS_ACTION_VARIABLE" => "action",
		"GIFTS_PRODUCT_ID_VARIABLE" => "id",
		"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"GIFTS_ADD_PROPERTIES_TO_BASKET" => "Y",
		"GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
		"GIFTS_PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"GIFTS_PRODUCT_PROPERTIES" => "",
		"GIFTS_HIDE_NOT_AVAILABLE" => "L",
		"GIFTS_HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"GIFTS_CONVERT_CURRENCY" => "N",
		"GIFTS_PRODUCT_DISPLAY_MODE" => "Y",
		"GIFTS_PRODUCT_SUBSCRIPTION" => "Y",
		"GIFTS_ADD_TO_BASKET_ACTION" => "ADD",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MESS_BTN_ADD_TO_BASKET" => "Выбрать",
		"GIFTS_MESS_BTN_SUBSCRIBE" => "Сообщить о поступлении",
		"GIFTS_MESS_BTN_DETAIL" => "Подробнее",
		"GIFTS_PAGE_ELEMENT_COUNT" => "8",
		"OFFERS_PROPS" => array(
			0 => "",
		),
		"CATALOG_OFFER_TREE_PROPS" => array(
			0 => "IZGIB_3",
			1 => "DIAMETR_5",
			2 => "DLINA_10",
			3 => "OBYEM_1",
		),
		"CATALOG_OFFERS_CART_PROPERTIES" => array(
			0 => "IZGIB_3",
			1 => "DIAMETR_5",
			2 => "DLINA_10",
			3 => "OBYEM_1",
		),
		"CATALOG_OFFERS_PROPERTY_CODE" => array(
			0 => "IZGIB_3",
			1 => "DIAMETR_5",
			2 => "DLINA_10",
			3 => "OBYEM_1",
			4 => "",
		),
		"USE_ENHANCED_ECOMMERCE" => "N",
		"COMPONENT_TEMPLATE" => "basket_basket_vlad",
		"MIN_SUMM" => "13498",
		"IS_PARTNER" => "true",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CATALOG_IBLOCK_TYPE" => "1c_catalog",
		"CATALOG_IBLOCK_ID" => "23",
		"CATALOG_ELEMENT_SORT_FIELD" => "name",
		"CATALOG_ELEMENT_SORT_ORDER" => "asc",
		"CATALOG_ELEMENT_SORT_FIELD2" => "id",
		"CATALOG_ELEMENT_SORT_ORDER2" => "desc",
		"CATALOG_INCLUDE_SUBSECTIONS" => "Y",
		"CATALOG_USE_MAIN_ELEMENT_SECTION" => "N",
		"CATALOG_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CATALOG_MESS_BTN_BUY" => "Купить",
		"CATALOG_MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"CATALOG_MESS_BTN_SUBSCRIBE" => "Сообщить о поступлении",
		"CATALOG_MESS_BTN_DETAIL" => "Подробнее",
		"CATALOG_MESS_NOT_AVAILABLE" => "Нет в наличии",
		"CATALOG_PRICE_CODE" => array(
			0 => "Розница",
			1 => "Партнёр 16%",
			2 => "Партнёр 22%",
			3 => "Партнёр 31%",
			4 => "Партнёр 44%",
			5 => "Партнёр 50%",
			6 => "Партнёр 60%",
			7 => "Партнер",
			8 => "Золотой партнер",
			9 => "Платиновый партнер",
			10 => "Серебряный партнер",
		),
		"CATALOG_USE_PRICE_COUNT" => "Y",
		"CATALOG_SHOW_PRICE_COUNT" => "1",
		"CATALOG_PRICE_VAT_INCLUDE" => "Y",
		"CATALOG_BASKET_URL" => "/personal/cart/",
		"CATALOG_USE_PRODUCT_QUANTITY" => "Y",
		"CATALOG_ADD_PROPERTIES_TO_BASKET" => "Y",
		"CATALOG_PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"CATALOG_PRODUCT_PROPERTIES" => array(
		),
		"CATALOG_HIDE_NOT_AVAILABLE" => "N",
		"CATALOG_HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"CATALOG_PRODUCT_SUBSCRIPTION" => "Y",
		"CATALOG_SHOW_DISCOUNT_PERCENT" => "Y",
		"CATALOG_SHOW_OLD_PRICE" => "Y",
		"CATALOG_SHOW_MAX_QUANTITY" => "M",
		"CATALOG_MESS_SHOW_MAX_QUANTITY" => "Наличие",
		"CATALOG_RELATIVE_QUANTITY_FACTOR" => "5",
		"CATALOG_MESS_RELATIVE_QUANTITY_MANY" => "много",
		"CATALOG_MESS_RELATIVE_QUANTITY_FEW" => "мало",
		"CATALOG_ADD_TO_BASKET_ACTION" => "ADD",
		"CATALOG_CONVERT_CURRENCY" => "Y",
		"CATALOG_USE_REVIEW" => "Y",
		"CATALOG_REVIEWS_IBLOCK_TYPE" => "reviews",
		"CATALOG_REVIEWS_IBLOCK_ID" => "70",
		"CATALOG_REVIEWS_NEWS_COUNT" => "5",
		"CATALOG_REVIEWS_SORT_BY1" => "id",
		"CATALOG_REVIEWS_SORT_ORDER1" => "asc",
		"CATALOG_REVIEWS_SORT_BY2" => "active_from",
		"CATALOG_REVIEWS_SORT_ORDER2" => "desc",
		"CATALOG_REVIEWS_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CATALOG_REVIEWS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CATALOG_MESS_REVIEWS_TAB" => "Отзывы",
		"CATALOG_DISPLAY_COMPARE" => "N",
		"CATALOG_COMPARE_PATH" => "",
		"CATALOG_MESS_BTN_COMPARE" => "Добавить к сравнению",
		"CATALOG_COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"CATALOG_DETAIL_ADD_PICT_PROP" => "MORE_PHOTO",
		"CATALOG_DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CATALOG_DETAIL_MAIN_BLOCK_PROPERTY_CODE" => array(
		),
		"CATALOG_DETAIL_IMAGE_RESOLUTION" => "16by9",
		"CATALOG_DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
		"CATALOG_DETAIL_DETAIL_PICTURE_MODE" => array(
			0 => "MAGNIFIER",
		),
		"CATALOG_DETAIL_SHOW_SLIDER" => "Y",
		"CATALOG_USE_GIFTS_DETAIL" => "Y",
		"CATALOG_GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "5",
		"CATALOG_GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"CATALOG_GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"CATALOG_GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"CATALOG_GIFTS_MESS_BTN_BUY" => "Выбрать",
		"CATALOG_USE_STORE" => "Y",
		"CATALOG_SET_ITEMS_COUNT" => "3",
		"OBJECTS_USE_REVIEW" => "N",
		"OBJECTS_REVIEWS_IBLOCK_TYPE" => "content",
		"OBJECTS_REVIEWS_IBLOCK_ID" => "",
		"CONTACTS_IBLOCK_TYPE" => "forms",
		"CONTACTS_IBLOCK_ID" => "56",
		"CONTACTS_USE_REVIEW" => "N",
		"CONTACTS_REVIEWS_IBLOCK_TYPE" => "reviews",
		"CONTACTS_REVIEWS_IBLOCK_ID" => "58",
		"CATALOG_DETAIL_SLIDER_INTERVAL" => "5000",
		"CATALOG_DETAIL_SLIDER_PROGRESS" => "N",
		"CATALOG_STORES" => array(
			0 => "",
			1 => "2",
			2 => "",
		),
		"CATALOG_USE_MIN_AMOUNT" => "Y",
		"CATALOG_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"CATALOG_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"CATALOG_MIN_AMOUNT" => "100",
		"CATALOG_SHOW_EMPTY_STORE" => "Y",
		"CATALOG_SHOW_GENERAL_STORE_INFORMATION" => "Y",
		"CATALOG_STORE_PATH" => "/store/#store_id#",
		"CATALOG_MAIN_TITLE" => "Наличие на складах",
		"CATALOG_CURRENCY_ID" => "RUB",
		"CATALOG_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"CATALOG_OFFERS_LIMIT" => "0",
		"CATALOG_OFFERS_SORT_FIELD" => "sort",
		"CATALOG_OFFERS_SORT_ORDER" => "asc",
		"CATALOG_OFFERS_SORT_FIELD2" => "id",
		"CATALOG_OFFERS_SORT_ORDER2" => "desc",
		"CATALOG_PRODUCT_DISPLAY_MODE" => "Y",
		"CATALOG_DETAIL_OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"CATALOG_DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"CATALOG_DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => array(
			0 => "IZGIB_3",
			1 => "DIAMETR_5",
			2 => "DLINA_10",
			3 => "OBYEM_1",
		)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>