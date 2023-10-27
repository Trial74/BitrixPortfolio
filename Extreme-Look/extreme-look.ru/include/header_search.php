<?$APPLICATION->IncludeComponent(
	"altop:search.title", 
	"visual", 
	array(
		"PAGE" => "/catalog/",
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "10",
		"ORDER" => "title",
		"USE_LANGUAGE_GUESS" => "Y",
		"CHECK_DATES" => "Y",
		"CATEGORY_0_TITLE" => "Каталог",
		"CATEGORY_0" => array(
			0 => "iblock_1c_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "all",
		),
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "title-search",
		"PRICE_CODE" => array(
			0 => "Розница",
			1 => "Партнер",
			2 => "Золотой партнер",
			3 => "Платиновый партнер",
			4 => "Серебрянный партнер",
		),
		"PRICE_VAT_INCLUDE" => "N",
		"CONVERT_CURRENCY" => "Y",
		"COMPONENT_TEMPLATE" => "visual",
		"CATEGORY_0_iblock_1c_catalog" => array(
			0 => "23",
			1 => "114",
		),
		"CURRENCY_ID" => "RUB",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>