<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->IncludeComponent("bitrix:news.list","promotions",
    array(
        "DISPLAY_DATE" => "",
        "DISPLAY_PICTURE" => "",
        "DISPLAY_PREVIEW_TEXT" => "",
        "SEF_MODE" => "Y",
        "AJAX_MODE" => "Y",
        "IBLOCK_TYPE" => "content",
        "IBLOCK_ID" => "65",
        "NEWS_COUNT" => "12",
        "USE_SEARCH" => "N",
        "USE_RSS" => "N",
        "USE_RATING" => "N",
        "USE_CATEGORIES" => "N",
        "USE_REVIEW" => "",
        "USE_FILTER" => "Y",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "ACTIVE_TO",
        "SORT_ORDER2" => "nulls,DESC",
        "CHECK_DATES" => "N",
        "PREVIEW_TRUNCATE_LEN" => "",
        "LIST_ACTIVE_DATE_FORMAT" => "j F Y",
        "LIST_FIELD_CODE" => array(
            0 => "ACTIVE_TO"
        ),
        "LIST_PROPERTY_CODE" => array(
            0 => "MARKER",
            1 => "SHOW_TIMER"
        ),
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "DISPLAY_NAME" => "N",
        "META_KEYWORDS" => "META_KEYWORDS",
        "META_DESCRIPTION" => "META_DESCRIPTION",
        "BROWSER_TITLE" => "BROWSER_TITLE",
        "DETAIL_SET_CANONICAL_URL" => "N",
        "DETAIL_ACTIVE_DATE_FORMAT" => "j F Y",
        "DETAIL_FIELD_CODE" => array(
            0 => "ACTIVE_TO"
        ),
        "DETAIL_PROPERTY_CODE" => array(
            0 => "MARKER",
            1 => "SHOW_TIMER",
            2 => "SALE_DICOUNT_ID",
            3 => "BRANDS",
            4 => "PRODUCTS",
            5 => "OBJECT"
        ),
        "DETAIL_DISPLAY_TOP_PAGER" => "N",
        "DETAIL_DISPLAY_BOTTOM_PAGER" => "N",
        "DETAIL_PAGER_TITLE" => "",
        "DETAIL_PAGER_TEMPLATE" => "",
        "DETAIL_PAGER_SHOW_ALL" => "N",
        "SET_TITLE" => "Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "ADD_ELEMENT_CHAIN" => "Y",
        "SET_LAST_MODIFIED" => "N",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "SET_STATUS_404" => "Y",
        "SHOW_404" => "N",
        "MESSAGE_404" => "",
        "PAGER_BASE_LINK" => "",
        "PAGER_PARAMS_NAME" => "",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "USE_PERMISSIONS" => "N",
        "GROUP_PERMISSIONS" => "",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Самые выгодные предложения недели",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "arrows",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "",
        "PAGER_SHOW_ALL" => "N",
        "FILTER_NAME" => "",
        "FILTER_FIELD_CODE" => array(),
        "FILTER_PROPERTY_CODE" => array(),
        "NUM_NEWS" => "",
        "NUM_DAYS" => "",
        "YANDEX" => "",
        "MAX_VOTE" => "",
        "VOTE_NAMES" => "",
        "CATEGORY_IBLOCK" => "",
        "CATEGORY_CODE" => "",
        "CATEGORY_ITEMS_COUNT" => "",
        "MESSAGES_PER_PAGE" => "",
        "USE_CAPTCHA" => "",
        "REVIEW_AJAX_POST" => "",
        "PATH_TO_SMILE" => "",
        "FORUM_ID" => "",
        "URL_TEMPLATES_READ" => "",
        "SHOW_LINK_TO_FORUM" => "",
        "POST_FIRST_MESSAGE" => "",
        "SEF_FOLDER" => "/promotions/",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "AJAX_OPTION_HISTORY" => "N",
        "USE_SHARE" => "",
        "SHARE_HIDE" => "",
        "SHARE_TEMPLATE" => "",
        "SHARE_HANDLERS" => "",
        "SHARE_SHORTEN_URL_LOGIN" => "",
        "SHARE_SHORTEN_URL_KEY" => "",
        "COMPONENT_TEMPLATE" => "promotions",
        "AJAX_OPTION_ADDITIONAL" => "",
        "INCLUDE_SUBSECTIONS" => "N",
        "CATALOG_IBLOCK_TYPE" => "1c_catalog",
        "CATALOG_IBLOCK_ID" => "23",
        "CATALOG_INCLUDE_SUBSECTIONS" => "Y",
        "CATALOG_HIDE_NOT_AVAILABLE" => "N",
        "CATALOG_HIDE_NOT_AVAILABLE_OFFERS" => "N",
        "CATALOG_ELEMENT_SORT_FIELD" => "sort",
        "CATALOG_ELEMENT_SORT_ORDER" => "asc",
        "CATALOG_ELEMENT_SORT_FIELD2" => "id",
        "CATALOG_ELEMENT_SORT_ORDER2" => "desc",
        "CATALOG_OFFERS_SORT_FIELD" => "sort",
        "CATALOG_OFFERS_SORT_ORDER" => "asc",
        "CATALOG_OFFERS_SORT_FIELD2" => "id",
        "CATALOG_OFFERS_SORT_ORDER2" => "desc",
        "CATALOG_OFFERS_PROPERTY_CODE" => array(
            0 => "COLOR_REF",
            1 => "SIZE"
        ),
        "CATALOG_PRODUCT_DISPLAY_MODE" => "Y",
        "CATALOG_OFFER_TREE_PROPS" => array(
            0 => "COLOR_REF",
            1 => "SIZE"
        ),
        "CATALOG_PRODUCT_SUBSCRIPTION" => "Y",
        "CATALOG_SHOW_DISCOUNT_PERCENT" => "Y",
        "CATALOG_SHOW_OLD_PRICE" => "Y",
        "CATALOG_SHOW_MAX_QUANTITY" => "M",
        "CATALOG_MESS_SHOW_MAX_QUANTITY" => "В наличии",
        "CATALOG_RELATIVE_QUANTITY_FACTOR" => "5",
        "CATALOG_MESS_RELATIVE_QUANTITY_MANY" => "много",
        "CATALOG_MESS_RELATIVE_QUANTITY_FEW" => "мало",
        "CATALOG_MESS_BTN_BUY" => "Купить",
        "CATALOG_MESS_BTN_ADD_TO_BASKET" => "Купить",
        "CATALOG_MESS_BTN_SUBSCRIBE" => "Сообщить о поступлении",
        "CATALOG_MESS_BTN_DETAIL" => "Подробнее",
        "CATALOG_MESS_NOT_AVAILABLE" => "Нет в наличии",
        "CATALOG_USE_MAIN_ELEMENT_SECTION" => "N",
        "CATALOG_PRICE_CODE" => array(
            0 => "Розница",
            1 => "Партнер",
            2 => "Золотой партнер",
            3 => "Платиновый партнер",
            4 => "BASE",
            5 => "Серебрянный партнер"
        ),
        "CATALOG_USE_PRICE_COUNT" => "Y",
        "CATALOG_SHOW_PRICE_COUNT" => "1",
        "CATALOG_PRICE_VAT_INCLUDE" => "Y",
        "CATALOG_CONVERT_CURRENCY" => "N",
        "CATALOG_CURRENCY_ID" => "",
        "CATALOG_BASKET_URL" => "/personal/cart/",
        "CATALOG_USE_PRODUCT_QUANTITY" => "N",
        "CATALOG_ADD_PROPERTIES_TO_BASKET" => "Y",
        "CATALOG_PARTIAL_PRODUCT_PROPERTIES" => "Y",
        "CATALOG_PRODUCT_PROPERTIES" => array(),
        "CATALOG_OFFERS_CART_PROPERTIES" => array(
            0 => "COLOR_REF",
            1 => "SIZE"
        ),
        "CATALOG_ADD_TO_BASKET_ACTION" => "ADD",
        "CATALOG_USE_REVIEW" => "Y",
        "CATALOG_REVIEWS_IBLOCK_TYPE" => "reviews",
        "CATALOG_REVIEWS_IBLOCK_ID" => "70",
        "CATALOG_DISPLAY_COMPARE" => "N",
        "CATALOG_COMPARE_PATH" => "/catalog/compare/",
        "CATALOG_MESS_BTN_COMPARE" => "Добавить к сравнению",
        "CATALOG_COMPARE_NAME" => "CATALOG_COMPARE_LIST",
        "SHOW_OBJECT" => "Y",
        "OBJECTS_IBLOCK_TYPE" => "content",
        "OBJECTS_IBLOCK_ID" => "51",
        "OBJECTS_PROPERTY_CODE" => array(
            0 => "MON",
            1 => "TUE",
            2 => "WED",
            3 => "THU",
            4 => "FRI",
            5 => "SAT",
            6 => "SUN",
            7 => "PHONE",
            8 => "EMAIL"
        ),
        "OBJECTS_SHOW_PROMOTIONS" => "Y",
        "OBJECTS_USE_REVIEW" => "N",
        "OBJECTS_REVIEWS_IBLOCK_TYPE" => "reviews",
        "OBJECTS_REVIEWS_IBLOCK_ID" => "59",
        "STRICT_SECTION_CHECK" => "N",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO"
    ),
    false
);?>