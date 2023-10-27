<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<? require_once( $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH .  '/config.php'); ?>
<?CModule::IncludeModule("iblock");
  $search = $_GET['search'];
  $arFilter = ["IBLOCK_ID" => CATALOG_IBLOCK, "?NAME" => "%$search%", "ACTIVE" => "Y"];
  $searchDB = CIBlockElement::GetList(["NAME" => "ASC"], $arFilter, false, ['nPageSize' => 30], ["ID", "NAME", "PREVIEW_PICTURE"]);
?>

<div class="list">
		<ul>
			<? while($search = $searchDB->Fetch()):?>
				<li>
					<a href="/page-catalog.element/element-id=<?=$search['ID']?>/" class="item-link">
						<div class="item-content">
							<div class="item-media" style="min-height: 70px; min-width: 60px;">
									<img src="<?=CFile::GetPath($search['PREVIEW_PICTURE'])?>" style="max-width: 60px; max-height: 60px;" class="lazy-fade-in lazy-loaded">
							</div>
							<div class="item-inner">
								<div class="item-title"><?=$search['NAME']?></div>
							</div>
						</div>
					</a>
				</li>
			<? endwhile;?>
		</ul>
</div>

<? /*$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	".default",
	array(
		"ELEMENT_SORT_FIELD" => $SORT_FIELD,
        "ELEMENT_SORT_ORDER" => $SORT_ORDER,
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => CATALOG_IBLOCK,
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "N",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => PRODUCTS_PER_PAGE,
		"DISPLAY_TOP_PAGER" => "Y",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
    "FILTER_NAME" => "arFilter",
		"CACHE_TYPE" => CACHE_TYPE,
		"CACHE_TIME" => CACHE_TIME,
		"CACHE_GROUPS" => "Y",
		"CACHE_FILTER" => "N",
		"PRICE_CODE" => array(
			0 => "Розница",
			1 => "Партнер",
			2 => "Золотой партнер",
			3 => "Платиновый партнер",
			4 => "Серебрянный партнер",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_PRODUCT_QUANTITY" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "10800",
		"PAGER_SHOW_ALL" => "N",
		"COMPONENT_TEMPLATE" => "section",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"FILE_404" => "",
		"TEMPLATE_THEME" => "blue",
		"PRODUCT_DISPLAY_MODE" => "N",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
	),
	false
); */?>
