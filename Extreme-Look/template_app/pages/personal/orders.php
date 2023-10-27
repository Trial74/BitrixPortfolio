<script data-skip-moving="true" src="/bitrix/templates/mobileapp/js/jquery.js"></script>

<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order", 
	".default", 
	array(
		"SEF_MODE" => "N",
		"SEF_FOLDER" => "/",
		"ORDERS_PER_PAGE" => "10",
		"PATH_TO_PAYMENT" => "/personal/order/payment/pay",
		"PATH_TO_BASKET" => "/personal/cart/",
		"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "N",
		"NAV_TEMPLATE" => "orders_list",
		"SHOW_ACCOUNT_NUMBER" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"PROP_1" => array(
		),
		"PROP_2" => array(
		),
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"ORDER_DEFAULT_SORT" => "ID",
		"DETAIL_HIDE_USER_INFO" => array(
			0 => "0",
		),
		"PROP_3" => array(
		),
		"PROP_4" => array(
		),
		"PATH_TO_CATALOG" => "/catalog/",
		"DISALLOW_CANCEL" => "N",
		"RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "0",
		),
		"REFRESH_PRICES" => "N",
		"ALLOW_INNER" => "N",
		"ONLY_INNER_FULL" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>

<?/*$APPLICATION->IncludeComponent(
    "bitrix:sale.personal.order",
    ".default",
    array(
        "SEF_MODE" => "N",
        "SEF_FOLDER" => "/",
        "ORDERS_PER_PAGE" => "10",
        "PATH_TO_PAYMENT" => "/personal/order/payment/pay",
        "PATH_TO_BASKET" => "/personal/cart/",
        "SET_TITLE" => "Y",
        "SAVE_IN_SESSION" => "N",
        "NAV_TEMPLATE" => ".default",
        "SHOW_ACCOUNT_NUMBER" => "Y",
        "COMPONENT_TEMPLATE" => ".default",
        "PROP_1" => array(
        ),
        "PROP_2" => array(
        ),
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
        "CUSTOM_SELECT_PROPS" => array(
        ),
        "HISTORIC_STATUSES" => array(
            0 => "F",
        ),
        "DETAIL_HIDE_USER_INFO" => array(
            0 => "0",
        ),
        "PROP_3" => array(
        ),
        "PROP_4" => array(
        ),
        "PATH_TO_CATALOG" => "/catalog/",
        "DISALLOW_CANCEL" => "N",
        "RESTRICT_CHANGE_PAYSYSTEM" => array(
            0 => "0",
        ),
        "REFRESH_PRICES" => "N",
        "ORDER_DEFAULT_SORT" => "ID",
        "ALLOW_INNER" => "N",
        "ONLY_INNER_FULL" => "N",
        "STATUS_COLOR_DO" => "gray",
        "STATUS_COLOR_F" => "gray",
        "STATUS_COLOR_N" => "green",
        "STATUS_COLOR_P" => "yellow",
        "STATUS_COLOR_PV" => "gray",
        "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO"
    ),
    false
);*/?>
