<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

global $arSettings;
$isWideScreenMode = $arSettings["WIDESCREEN_MODE"]["VALUE"] == "Y" ? true : false;

if($isWideScreenMode)
	$APPLICATION->SetPageProperty("wideScreenMode", "-ws");?>

<div class="promotions-container">
	<?//SECTION_LIST//?>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "catalog",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
			"TOP_DEPTH" => "1",
			"SECTION_FIELDS" => array(),
			"SECTION_USER_FIELDS" => array(
				0 => "UF_ICON"
			),
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
			"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
		),
		$component,
		array("HIDE_ICONS" => "Y")
	);?>

	<?//PREVIEW//
	if(!$_REQUEST["PAGEN_1"] || empty($_REQUEST["PAGEN_1"]) || $_REQUEST["PAGEN_1"] <= 1) {?>
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "preview",
			array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/promotions_preview.php"
			),		
			$component
		);?>
	<?}
	
	//ELEMENTS//?>
	<?$APPLICATION->IncludeComponent("bitrix:news.list", "promotions",
		array(
			"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
			"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
			"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
			"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
			"AJAX_MODE" => $arParams["AJAX_MODE"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"NEWS_COUNT" => $arParams["NEWS_COUNT"],
			"SORT_BY1" => $arParams["SORT_BY1"],
			"SORT_ORDER1" => $arParams["SORT_ORDER1"],
			"SORT_BY2" => $arParams["SORT_BY2"],
			"SORT_ORDER2" => $arParams["SORT_ORDER2"],
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
			"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
			"CHECK_DATES" => $arParams["CHECK_DATES"],
			"DETAIL_URL" => $arParams["DETAIL_URL"],
			"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
			"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
			"SET_TITLE" => "N",
			"SET_BROWSER_TITLE" => $arParams["SET_BROWSER_TITLE"],
			"SET_META_KEYWORDS" => $arParams["SET_META_KEYWORDS"],
			"SET_META_DESCRIPTION" => $arParams["SET_META_DESCRIPTION"],
			"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
			"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
			"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
			"HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
			"PARENT_SECTION" => $arResult["VARIABLES"]["SECTION_ID"],
			"PARENT_SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_FILTER" => $arParams["CACHE_FILTER"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arParams["PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
			"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
			"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"SHOW_404" => $arParams["SHOW_404"],
			"MESSAGE_404" => $arParams["MESSAGE_404"],
			"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
			"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
			"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
			"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
			"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
			"AJAX_OPTION_ADDITIONAL" => $arParams["AJAX_OPTION_ADDITIONAL"]
		),
		$component
	);?>

	<?//DESCRIPTION//
	if(!$_REQUEST["PAGEN_1"] || empty($_REQUEST["PAGEN_1"]) || $_REQUEST["PAGEN_1"] <= 1) {?>
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "description",
			array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/promotions_description.php"
			),
			$component
		);?>
	<?}?>
</div>

<?//TITLE//
if(!empty($_REQUEST["PAGEN_1"]) && $_REQUEST["PAGEN_1"] > 1) {
	$APPLICATION->SetPageProperty("title", $arParams["PAGER_TITLE"]." | ".GetMessage("SECT_TITLE")." ".$_REQUEST["PAGEN_1"]);
	$APPLICATION->SetPageProperty("keywords", "");
	$APPLICATION->SetPageProperty("description", "");
}