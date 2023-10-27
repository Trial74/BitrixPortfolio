<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отзывы о компании");?>

<?$APPLICATION->IncludeComponent("bitrix:news", "reviews", 
	array(
		"DISPLAY_DATE" => "",
		"DISPLAY_PICTURE" => "",
		"DISPLAY_PREVIEW_TEXT" => "",
		"SEF_MODE" => "Y",
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "reviews",
		"IBLOCK_ID" => "58",
		"NEWS_COUNT" => "5",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_REVIEW" => "",
		"USE_FILTER" => "Y",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER2" => "DESC",
		"CHECK_DATES" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "j F Y",
		"LIST_FIELD_CODE" => array(),
		"LIST_PROPERTY_CODE" => array(
			0 => "RATING",
			1 => "TERM",
			2 => "RECOMMEND",
			3 => "ADVANTAGES",
			4 => "DEFECTS",
			5 => "COMMENT",
			6 => "NAME",
			7 => "USER_ID",
			8 => "CITY",
			9 => "LIKES",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "N",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DETAIL_ACTIVE_DATE_FORMAT" => "",
		"DETAIL_FIELD_CODE" => array(),
		"DETAIL_PROPERTY_CODE" => array(),
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
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Отзывы о компании",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "arrows",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
		"PAGER_SHOW_ALL" => "N",
		"FILTER_NAME" => "arReviewsFilter",
		"FILTER_FIELD_CODE" => "",
		"FILTER_PROPERTY_CODE" => "",
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
		"SEF_FOLDER" => "/about/reviews/",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"USE_SHARE" => "",
		"SHARE_HIDE" => "",
		"SHARE_TEMPLATE" => "",
		"SHARE_HANDLERS" => "",
		"SHARE_SHORTEN_URL_LOGIN" => "",
		"SHARE_SHORTEN_URL_KEY" => "",
		"COMPONENT_TEMPLATE" => "reviews",
		"AJAX_OPTION_ADDITIONAL" => "",
		"INCLUDE_SUBSECTIONS" => "N",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "",
		),		
		"CONTACTS_IBLOCK_TYPE" => "blocks",
		"CONTACTS_IBLOCK_ID" => "44",
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>