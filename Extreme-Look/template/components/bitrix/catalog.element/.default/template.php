<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/mobile_detected/mobile_detected.php');
$detectCat = new Mobile_Detect;
if ($detectCat->isMobile()) {
    $mobile = true;
    define("VERSION_IN_CAT", "mobile");
}else{
    $mobile = false;
    define("VERSION_IN_CAT", "desktop");
}
$disabledVlad = false;
use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
$arSettings = CEnext::GetFrontParametrsValues(SITE_ID);
$isWideScreenMode = $arSettings["WIDESCREEN_MODE"] == "Y" ? true : false;
$minOrderSum = intval($arSettings["MIN_ORDER_SUM"]);

$this->addExternalCss(SITE_TEMPLATE_PATH."/js/owlCarousel/owl.carousel.min.css");
$this->addExternalJS(SITE_TEMPLATE_PATH."/js/owlCarousel/owl.carousel.min.js");

if($arParams["OFFERS_VIEW"] != "OBJECTS" && $arSettings["AUTO_DELIVERY_CALCULATION"] == "Y") {
	$this->addExternalCss('/bitrix/components/altop/geo.delivery.enext/templates/slide_panel/style.min.css');
	$this->addExternalCss(SITE_TEMPLATE_PATH.'/components/bitrix/sale.location.selector.search/slide_panel/style.min.css');
}

$templateLibrary = array("popup", "fx");
$currencyList = "";
$marketplace = array(
    'ozon' => false,
    'wildberries' => false
);
$buyMarketplace = false;

if((isset($arResult['PROPERTIES']['SSYLKA_NA_OZON']) && !empty($arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE'])) || (isset($arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']) && !empty($arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']))){
    $parseOZ = parse_url($arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']);
    $parseWB = parse_url($arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']);
    if(is_array($parseOZ))
        if(isset($parseOZ['host']) && ($parseOZ['host'] === 'www.ozon.ru' || $parseOZ['host'] === 'ozon.ru'))
            $marketplace['ozon'] = true;
    if(is_array($parseWB))
        if(isset($parseWB['host']) && ($parseWB['host'] === 'www.wildberries.ru' || $parseWB['host'] === 'wildberries.ru'))
            $marketplace['wildberries'] = true;
}

if(!empty($arResult["CURRENCIES"])) {
	$templateLibrary[] = "currency";
	$currencyList = CUtil::PhpToJSObject($arResult["CURRENCIES"], false, true, true);
}

$templateData = array(	
	"TEMPLATE_LIBRARY" => $templateLibrary,
	"CURRENCIES" => $currencyList
);
unset($currencyList, $templateLibrary);

if(isset($arResult['PROPERTIES']['VIDEO_1']) && !empty($arResult['PROPERTIES']['VIDEO_1']) && !empty($arResult['PROPERTIES']['VIDEO_1']['VALUE'])) {
    $video = true;
    $validYouTube = validYouTube($arResult['PROPERTIES']['VIDEO_1']['VALUE']);
}
else {
    $video = false;
    $validYouTube['error'] = true;
}

$mainId = $this->GetEditAreaId($arResult["ID"].(!empty($arParams["RCM_ID"]) ? "_".md5($arParams["RCM_ID"]) : ""));
$itemIds = array(
	"ID" => $mainId,
	"DISCOUNT_PERCENT_ID" => $mainId."_dsc_pict",	
	"BIG_SLIDER_ID" => $mainId."_big_slider",	
	"SLIDER_CONT_ID" => $mainId."_slider_cont",
	"ARTICLE_ID" => $mainId."_article",
	"OLD_PRICE_ID" => $mainId."_old_price",
	"PRICE_ID" => $mainId."_price",
	"DISCOUNT_PRICE_ID" => $mainId."_price_discount",	
	"SLIDER_CONT_OF_ID" => $mainId."_slider_cont_",
	"QUANTITY_ID" => $mainId."_quantity",
	"QUANTITY_DOWN_ID" => $mainId."_quant_down",
	"QUANTITY_UP_ID" => $mainId."_quant_up",	
	"PC_QUANTITY_ID" => $mainId."_pc_quantity",
	"PC_QUANTITY_DOWN_ID" => $mainId."_pc_quant_down",
	"PC_QUANTITY_UP_ID" => $mainId."_pc_quant_up",	
	"SQ_M_QUANTITY_ID" => $mainId."_sq_m_quantity",
	"SQ_M_QUANTITY_DOWN_ID" => $mainId."_sq_m_quant_down",
	"SQ_M_QUANTITY_UP_ID" => $mainId."_sq_m_quant_up",
	"QUANTITY_MEASURE" => $mainId."_quant_measure",
	"QUANTITY_LIMIT" => $mainId."_quant_limit",
	"QUANTITY_LIMIT_NOT_AVAILABLE" => $mainId."_quant_limit_not_avl",
    "QUANTITY_PROGRESS" => $mainId . "_quant_progress",
	"TOTAL_COST_ID" => $mainId."_total_cost",
	"BUY_LINK" => $mainId."_buy_link",
	"ADD_BASKET_LINK" => $mainId."_add_basket_link",	
	"BASKET_ACTIONS_ID" => $mainId."_basket_actions",
	"PARTNERS_LINK" => $mainId."_partners_link",
	"PARTNERS_ID" => $mainId."_partners",
	"ASK_PRICE_LINK" => $mainId."_ask_price",
	"NOT_AVAILABLE_MESS" => $mainId."_not_avail",
	"COMPARE_LINK" => $mainId."_compare_link",
	"QUICK_ORDER_LINK" => $mainId."_quick_order",	
	"DELAY_LINK" => $mainId."_delay_link",
	"SELECT_SKU_LINK" => $mainId."_select_sku_link",
	"TREE_ID" => $mainId."_skudiv",
	"DISPLAY_PROP_DIV" => $mainId."_sku_prop",
    "DISPLAY_PROP_DIV_SECOND" => $mainId."_sku_prop_second",
	"DISPLAY_MAIN_PROP_DIV" => $mainId."_main_sku_prop",
	"DISPLAY_MAIN_PROP_DIV_SECOND" => $mainId."_main_sku_prop_second",
	"BASKET_PROP_DIV" => $mainId."_basket_prop",
	"SUBSCRIBE_LINK" => $mainId."_subscribe",
	"TABS_ID" => $mainId."_tabs",
	"TABS_ID_MOBILE" => $mainId."_tabs_mobile",
	"TAB_CONTAINERS_ID" => $mainId."_tab_containers",
	"SKU_ITEMS_ID" => $mainId."_sku_items",
	"CONSTRUCTOR_ID" => $mainId."_constructor",
	"GEO_DELIVERY_ID" => $mainId."_geo_delivery",
    "BUTTON_OZON" => $mainId.'_btn_ozon',
    "BUTTON_WB" => $mainId.'_btn_wb'
);
$obName = $templateData["JS_OBJ"] = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $mainId);

$name = !empty($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"])
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
	: $arResult["NAME"];

$title = !empty($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"])
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult["NAME"];

$alt = !empty($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"])
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult["NAME"];

$haveOffers = !empty($arResult["OFFERS"]);
if($haveOffers) {
	$actualItem = isset($arResult["OFFERS"][$arResult["OFFERS_SELECTED"]]) ? $arResult["OFFERS"][$arResult["OFFERS_SELECTED"]] : reset($arResult["OFFERS"]);

	$showWeightOffer = false;
	$showDesmisionOffer = false;
    foreach($arResult["OFFERS"] as $offer) {
        if(isset($offer["DISPLAY_PROPERTIES"]["VES_1"])){
            $showWeightOffer = true;
        }
        if(isset($offer["DISPLAY_PROPERTIES"]["DIMENSIONS"])){
            $showDesmisionOffer = true;
        }
        if($showWeightOffer && $showDesmisionOffer) break;
    }
    unset($offer);

	$showSliderControls = false;
	if($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") {
		foreach($arResult["OFFERS"] as $offer) {
			if($offer["MORE_PHOTO_COUNT"] > 1) {
				$showSliderControls = true;
				break;
			}
		}
		unset($offer);
	} else {
		$showSliderControls = $actualItem["MORE_PHOTO_COUNT"] > 1;
	}
} else {
	$actualItem = $arResult;
	$showSliderControls = $arResult["MORE_PHOTO_COUNT"] > 1;
}

$skuProps = array();
$price = $actualItem["ITEM_PRICES"][$actualItem["ITEM_PRICE_SELECTED"]];
$measureRatio = $actualItem["ITEM_MEASURE_RATIOS"][$actualItem["ITEM_MEASURE_RATIO_SELECTED"]]["RATIO"];
$showDiscount = $price["PERCENT"] > 0;

$isMeasurePc = $isMeasureSqM = false;
if($actualItem["ITEM_MEASURE"]["SYMBOL_INTL"] == "pc. 1")
	$isMeasurePc = true;
elseif($actualItem["ITEM_MEASURE"]["SYMBOL_INTL"] == "m2")
	$isMeasureSqM = true;

$showDescription = !empty($arResult["DETAIL_TEXT"]);
$showBuyBtn = in_array("BUY", $arParams["ADD_TO_BASKET_ACTION"]);
$showAddBtn = in_array("ADD", $arParams["ADD_TO_BASKET_ACTION"]);
$showSubscribe = $arParams["PRODUCT_SUBSCRIPTION"] === "Y" && ($arResult["CATALOG_SUBSCRIBE"] === "Y" || $haveOffers);

$object = !empty($arResult["PROPERTIES"]["OBJECT"]["FULL_VALUE"]) ? $arResult["PROPERTIES"]["OBJECT"]["FULL_VALUE"] : false;
$objectContacts = $object["PHONE_SMS"] || $object["EMAIL_EMAIL"] ? true : false;

if(!$haveOffers || $arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST")
	$partnersUrl = !empty($actualItem["PROPERTIES"]["PARTNERS_URL"]["VALUE"]) ? true : false;
else
	$partnersUrl = !empty($arResult["PROPERTIES"]["PARTNERS_URL"]["VALUE"]) ? true : false;

$moreProductsIds = !empty($arResult["PROPERTIES"]["MORE_PRODUCTS"]["VALUE"]) ? $arResult["PROPERTIES"]["MORE_PRODUCTS"]["VALUE"] : false;

$arParams["MESS_BTN_BUY"] = $arParams["MESS_BTN_BUY"] ?: Loc::getMessage("CT_BCE_CATALOG_BUY");
$arParams["MESS_BTN_ADD_TO_BASKET"] = $arParams["MESS_BTN_ADD_TO_BASKET"] ?: Loc::getMessage("CT_BCE_CATALOG_ADD");
$arParams["MESS_NOT_AVAILABLE"] = $arParams["MESS_NOT_AVAILABLE"] ?: Loc::getMessage("CT_BCE_CATALOG_NOT_AVAILABLE");
$arParams["MESS_BTN_COMPARE"] = $arParams["MESS_BTN_COMPARE"] ?: Loc::getMessage("CT_BCE_CATALOG_COMPARE");
$arParams["MESS_BTN_DELAY"] = $arParams["MESS_BTN_DELAY"] ?: Loc::getMessage("CT_BCE_CATALOG_DELAY");
$arParams["MESS_PRICE_RANGES_TITLE"] = $arParams["MESS_PRICE_RANGES_TITLE"] ?: Loc::getMessage("CT_BCE_CATALOG_PRICE_RANGES_TITLE");
$arParams["MESS_DESCRIPTION_TAB"] = $arParams["MESS_DESCRIPTION_TAB"] ?: Loc::getMessage("CT_BCE_CATALOG_DESCRIPTION_TAB");
$arParams["MESS_PROPERTIES_TAB"] = $arParams["MESS_PROPERTIES_TAB"] ?: Loc::getMessage("CT_BCE_CATALOG_PROPERTIES_TAB");
$arParams["MESS_REVIEWS_TAB"] = $arParams["MESS_REVIEWS_TAB"] ?: Loc::getMessage("CT_BCE_CATALOG_REVIEWS_TAB");
$arParams["MESS_SHOW_MAX_QUANTITY"] = $arParams["MESS_SHOW_MAX_QUANTITY"] ?: Loc::getMessage("CT_BCE_CATALOG_SHOW_MAX_QUANTITY");
$arParams["MESS_RELATIVE_QUANTITY_MANY"] = $arParams["MESS_RELATIVE_QUANTITY_MANY"] ?: Loc::getMessage("CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY");
$arParams["MESS_RELATIVE_QUANTITY_FEW"] = $arParams["MESS_RELATIVE_QUANTITY_FEW"] ?: Loc::getMessage("CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW");

?>

<div class="bx-catalog-element" id="<?=$itemIds['ID']?>" itemscope itemtype="http://schema.org/Product">
	<div class="product-item-detail-tabs-content" id="<?=$itemIds['TAB_CONTAINERS_ID']?>">
		<div class="row">
			<div class="col-xs-12 col-md-9<?=($isWideScreenMode ? ' col-ws-left' : '')?>" data-entity="product-container">
				<div class="row" data-entity="tab-container" data-value="description">
					<div class="col-xs-12 col-md-7">
						<?//SLIDER//?>
						<div class="product-item-detail-slider-container<?=($showSliderControls ? ' full' : '')?>" id="<?=$itemIds['BIG_SLIDER_ID']?>">
							<span class="product-item-detail-slider-close" data-entity="close-popup"><i class="icon-close"></i></span>
							<div class="product-item-detail-slider-block<?=($arParams['IMAGE_RESOLUTION'] === '1by1' ? ' product-item-detail-slider-block-square' : '')?>">
								<span class="product-item-detail-slider-left" data-entity="slider-control-left" style="display: none;"><i class="icon-arrow-left"></i></span>
								<span class="product-item-detail-slider-right" data-entity="slider-control-right" style="display: none;"><i class="icon-arrow-right"></i></span>
								<?//MARKERS//?>
								<div class="product-item-detail-markers">
									<?if($arParams["SHOW_DISCOUNT_PERCENT"] === "Y") {?>
										<span class="product-item-detail-marker-container<?=($showDiscount ? '' : ' product-item-detail-marker-container-hidden')?>" id="<?=$itemIds['DISCOUNT_PERCENT_ID']?>">
											<span class="product-item-detail-marker product-item-detail-marker-discount product-item-detail-marker-14px"><span data-entity="dsc-perc-val"><?=-$price["PERCENT"]?>%</span></span>
										</span>
									<?}
									if(!empty($arResult["PROPERTIES"]["MARKER"]["FULL_VALUE"])) {
										foreach($arResult["PROPERTIES"]["MARKER"]["FULL_VALUE"] as $key => $arMarker) {
											if($key <= 4) {?>
                                                <span class="product-item-detail-marker-container">
													<span class="tooltip_v <?=(!empty($arMarker['FONT_SIZE']) ? ' product-item-detail-marker-'.$arMarker['FONT_SIZE'] : '')?>"><?=(!empty($arMarker["ICON"]) ? "<i class='".$arMarker["ICON"]."'></i>" : "")?><span class="tooltiptext_v" style="left: 60px;<?=(!empty($arMarker['BACKGROUND_1']) ? ' background:'.$arMarker['BACKGROUND_1'] : ' background: #7b66fe')?>"><?=$arMarker["NAME"]?></span></span>
												</span>
											<?} else {
												break;
											}
										}
										unset($key, $arMarker);
									}?>
								</div>
								<?//SLIDER_IMAGES//?>
								<div class="product-item-detail-slider-videos-images-container" data-entity="videos-images-container">
									<?if(!empty($actualItem["MORE_PHOTO"])) {
										$activeKey = 0;
										foreach($actualItem["MORE_PHOTO"] as $key => $photo) {

											if(!empty($photo["VALUE"])) {
												$activeKey++;?>
												<div class="product-item-detail-slider-video" data-entity="video" data-id="<?=$photo['ID']?>">
													<iframe width="640" height="480" src="<?=$arResult['SCHEME']?>://www.youtube.com/embed/<?=$photo['VALUE']?>?rel=0&showinfo=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe>
												</div>
											<?} else {?>
                                                <?if(isset($photo['PARAMS'])){?>
                                                    <div class="product-item-detail-slider-image<?=($key == $activeKey ? ' active' : '')?>" data-entity="image" data-id="<?=$photo['ID']?>">
                                                        <div id="img_live" class="block_by_live_image">
                                                            <img src="<?=$photo['SRC']?>" width="<?=$photo['WIDTH']?>" height="<?=$photo['HEIGHT']?>" alt="<?=$alt?>" title="<?=$title?>">
                                                        </div>
                                                    </div>
                                                <?}else{?>
                                                    <div class="product-item-detail-slider-image<?=($key == $activeKey ? ' active' : '')?>" data-entity="image" data-id="<?=$photo['ID']?>">
                                                        <img src="<?=$photo['SRC']?>" width="<?=$photo['WIDTH']?>" height="<?=$photo['HEIGHT']?>" alt="<?=$alt?>" title="<?=$title?>"<?=($key == $activeKey ? " itemprop='image'" : "")?>>
                                                    </div>
                                                <?}?>
											<?}
										}
									}
                                    unset($key, $photo, $activeKey);
									//SLIDER_PROGRESS//
									if($arParams["SLIDER_PROGRESS"] === "Y") {?>
										<div class="product-item-detail-slider-progress-bar" data-entity="slider-progress-bar" style="width: 0;"></div>
									<?}?>
								</div>
								<?//BRAND//
								if(!empty($arResult["PROPERTIES"]["BRAND"]["FULL_VALUE"]["PREVIEW_PICTURE"])) {?>
									<div class="product-item-detail-brand">
										<img src="<?=$arResult['PROPERTIES']['BRAND']['FULL_VALUE']['PREVIEW_PICTURE']['SRC']?>" width="<?=$arResult['PROPERTIES']['BRAND']['FULL_VALUE']['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arResult['PROPERTIES']['BRAND']['FULL_VALUE']['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arResult['PROPERTIES']['BRAND']['FULL_VALUE']['NAME']?>" title="<?=$arResult['PROPERTIES']['BRAND']['FULL_VALUE']['NAME']?>" />
									</div>
								<?}?>
							</div>
							<?//SLIDER_CONTROLS//
							if($showSliderControls) {
								if($haveOffers) {
									if($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") {
										foreach($arResult["OFFERS"] as $keyOffer => $offer) {
											if(!isset($offer["MORE_PHOTO_COUNT"]) || $offer["MORE_PHOTO_COUNT"] <= 0)
												continue;
											$strVisible = $arResult["OFFERS_SELECTED"] == $keyOffer ? "" : "none";?>
											<div class="hidden-xs hidden-sm product-item-detail-slider-controls-block" id="<?=$itemIds['SLIDER_CONT_OF_ID'].$offer['ID']?>" style="display: <?=$strVisible?>;">
												<?$activeKeyPhoto = 0;
                                                if(returnSections('inSection', $arResult['IBLOCK_SECTION_ID'])) {
                                                    unset($offer["MORE_PHOTO"][1]);
                                                    $offer["MORE_PHOTO"] = array_values($offer["MORE_PHOTO"]);
                                                    $offer["MORE_PHOTO_COUNT"] = $offer["MORE_PHOTO_COUNT"] - 1;
                                                }
												foreach($offer["MORE_PHOTO"] as $keyPhoto => $photo) {
													if(!empty($photo["VALUE"])) {
														$activeKeyPhoto++;?>
														<div class="product-item-detail-slider-controls-video" data-entity="slider-control" data-value="<?=$offer['ID'].'_'.$photo['ID']?>">
															<div class="product-item-detail-slider-controls-video-image">
																<img src="<?=$arResult['SCHEME']?>://img.youtube.com/vi/<?=$photo['VALUE']?>/default.jpg" alt="<?=$alt?>" title="<?=$title?>" />
															</div>
															<div class="product-item-detail-slider-controls-video-play"><i class="icon-play-s"></i></div>
														</div>
													<?} else {?>
														<div<?=isset($photo['PARAMS']) ? ' id="live-control"' : ''?> class="product-item-detail-slider-controls-image<?=($keyPhoto == $activeKeyPhoto ? ' active' : '')?>" data-entity="slider-control" data-value="<?=$offer['ID'].'_'.$photo['ID']?>">
															<img src="<?=$photo['PREVIEW']['SRC']?>" width="<?=$photo['PREVIEW']['WIDTH']?>" height="<?=$photo['PREVIEW']['HEIGHT']?>" alt="<?=$alt?>" title="<?=$title?>" />
														</div>
													<?}
												}
												unset($keyPhoto, $photo, $activeKeyPhoto);?>
											</div>
										<?}
										unset($keyOffer, $offer);
									} else {
										$offer = isset($arResult["OFFERS"][$arResult["OFFERS_SELECTED"]]) ? $arResult["OFFERS"][$arResult["OFFERS_SELECTED"]] : reset($arResult["OFFERS"]);
										if($offer["MORE_PHOTO_COUNT"] > 0) {?>
											<div class="hidden-xs hidden-sm product-item-detail-slider-controls-block" id="<?=$itemIds['SLIDER_CONT_OF_ID'].$offer['ID']?>">
												<?$activeKeyPhoto = 0;
												foreach($offer["MORE_PHOTO"] as $keyPhoto => $photo) {
													if(!empty($photo["VALUE"])) {
														$activeKeyPhoto++;?>
														<div class="product-item-detail-slider-controls-video" data-entity="slider-control" data-value="<?=$offer['ID'].'_'.$photo['ID']?>">
															<div class="product-item-detail-slider-controls-video-image">
																<img src="<?=$arResult['SCHEME']?>://img.youtube.com/vi/<?=$photo['VALUE']?>/default.jpg" alt="<?=$alt?>" title="<?=$title?>" />
															</div>
															<div class="product-item-detail-slider-controls-video-play"><i class="icon-play-s"></i></div>
														</div>
													<?}else{?>
														<div<?=isset($photo['PARAMS']) ? ' id="live-control"' : ''?> class="product-item-detail-slider-controls-image<?=($keyPhoto == $activeKeyPhoto ? ' active' : '')?>" data-entity="slider-control" data-value="<?=$offer['ID'].'_'.$photo['ID']?>">
															<img src="<?=$photo['PREVIEW']['SRC']?>" width="<?=$photo['PREVIEW']['WIDTH']?>" height="<?=$photo['PREVIEW']['HEIGHT']?>" alt="<?=$alt?>" title="<?=$title?>" />
														</div>
													<?}
												}
												unset($keyPhoto, $photo, $activeKeyPhoto);?>
											</div>
										<?}
									}
								} else {?>
									<div class="hidden-xs hidden-sm product-item-detail-slider-controls-block" id="<?=$itemIds['SLIDER_CONT_ID']?>">
										<?if(!empty($actualItem["MORE_PHOTO"])) {
											$activeKey = 0;
											foreach($actualItem["MORE_PHOTO"] as $key => $photo) {
												if(!empty($photo["VALUE"])) {
													$activeKey++;?>
													<div class="product-item-detail-slider-controls-video" data-entity="slider-control" data-value="<?=$photo['ID']?>">
														<div class="product-item-detail-slider-controls-video-image">
															<img src="<?=$arResult['SCHEME']?>://img.youtube.com/vi/<?=$photo['VALUE']?>/default.jpg" alt="<?=$alt?>" title="<?=$title?>" />
														</div>
														<div class="product-item-detail-slider-controls-video-play"><i class="icon-play-s"></i></div>
													</div>
												<?}else{?>
													<div<?=isset($photo['PARAMS']) ? ' id="live-control"' : ''?> class="product-item-detail-slider-controls-image<?=($key == $activeKey ? ' active' : '')?>" data-entity="slider-control" data-value="<?=$photo['ID']?>">
														<img src="<?=gif($photo['SRC']) ? $photo['PREVIEW']['SRC'] : $photo['SRC']?>" width="<?=$photo['PREVIEW']['WIDTH']?>" height="<?=$photo['PREVIEW']['HEIGHT']?>" alt="<?=$alt?>" title="<?=$title?>" />
													</div>
                                                <?}
											}
											unset($key, $photo, $activeKey);
										}?>
									</div>
								<?}
							}?>
						</div>
					</div>
					<div class="col-xs-12 col-md-5 product-item-detail-blocks">
						<?
						//RATING//
						if(isset($arResult["REVIEWS_COUNT"])) {?>							
							<div class="product-item-detail-rating">
								<?if($arResult["RATING_VALUE"] > 0) {?>
									<div class="product-item-detail-rating-val"<?=($arResult["RATING_VALUE"] <= 4.4 ? " data-rate='".intval($arResult["RATING_VALUE"])."'" : "")?>><?=$arResult["RATING_VALUE"]?></div>
								<?}
								$arReviewsDeclension = new Bitrix\Main\Grid\Declension(Loc::getMessage("CT_BCE_CATALOG_REVIEW"), Loc::getMessage("CT_BCE_CATALOG_REVIEWS_1"), Loc::getMessage("CT_BCE_CATALOG_REVIEWS_2"));?>
                                <?if($disabledVlad):?>
								    <div class="product-item-detail-rating-reviews-count"><?=($arResult["REVIEWS_COUNT"] > 0 ? $arResult["REVIEWS_COUNT"]." ".$arReviewsDeclension->get($arResult["REVIEWS_COUNT"]) : Loc::getMessage("CT_BCE_CATALOG_NO_REVIEWS"))?></div>
                                <?endif;?>
								<?unset($arReviewsDeclension);?>
							</div>
							<?if($arResult["REVIEWS_COUNT"] > 0) {?>
								<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
									<meta itemprop="ratingValue" content="<?=$arResult['RATING_VALUE']?>" />
									<meta itemprop="reviewCount" content="<?=$arResult['REVIEWS_COUNT']?>" />
								</span>
							<?}
						}
						//PREVIEW_TEXT//
						if(!empty($arResult["PREVIEW_TEXT"])) {?>
							<div class="product-item-detail-preview"><?=$arResult["PREVIEW_TEXT"]?></div>
						<?}
						//PROPERTIES//
						$isMainProps = false;
						if(!empty($arResult["DISPLAY_PROPERTIES"])) {
							foreach($arResult["DISPLAY_PROPERTIES"] as $property) {
								if(isset($arParams["MAIN_BLOCK_PROPERTY_CODE"][$property["CODE"]])) {
									$isMainProps = true;
									break;
								}
							}
							unset($property);
						}
						if(shpgart($arResult['PRODUCT'], $arResult['PROPERTIES']['CML2_TRAITS']) || !!$isMainProps || (($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") && $arResult["SHOW_OFFERS_PROPS"])) {?>
							<div class="product-item-detail-main-properties-container">					
								<div class="product-item-detail-properties-block"<?=(($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") && $arResult["SHOW_OFFERS_PROPS"] ? " id='".$itemIds["DISPLAY_MAIN_PROP_DIV"]."'" : "");?>>
                                    <?$arShPGRes = shpgart($arResult['PRODUCT'], $arResult['PROPERTIES']['CML2_TRAITS']);?>
                                    <div class="product-item-detail-properties">
                                        <div class="product-item-detail-properties-name"><?=$arShPGRes[2]?></div>
                                        <div class="product-item-detail-properties-val"><?=$arShPGRes[3]?></div>
                                    </div>
									<?if(!empty($arResult["DISPLAY_PROPERTIES"])) {
										foreach($arResult["DISPLAY_PROPERTIES"] as $property) {
											if(isset($arParams["MAIN_BLOCK_PROPERTY_CODE"][$property["CODE"]])) {?>
												<div class="product-item-detail-properties">
													<div class="product-item-detail-properties-name"><?=$property["NAME"]?></div>
													<div class="product-item-detail-properties-val"><?=$property["DISPLAY_VALUE"]?></div>
												</div>
											<?}
										}
										unset($property);
									}?>
                                    <?if(!$showWeightOffer){?>
                                        <div class="product-item-detail-properties">
                                            <div class="product-item-detail-properties-name"><?=$arShPGRes[4]?></div>
                                            <div class="product-item-detail-properties-val"><?=$arShPGRes[5]?></div>
                                        </div>
                                    <?}?>
                                    <?if(!$showDesmisionOffer){?>
                                        <div class="product-item-detail-properties">
                                            <div class="product-item-detail-properties-name"><?=$arShPGRes[0]?></div>
                                            <div class="product-item-detail-properties-val"><?=$arShPGRes[1]?></div>
                                        </div>
                                    <?}?>
                                    <?unset($arShPGRes);?>
								</div>
							</div>
						<?}
						unset($isMainProps);
						//ADVANTAGES//			
						if(!empty($arResult["PROPERTIES"]["ADVANTAGES"]["FULL_VALUE"])) {?>
							<div class="product-item-detail-advantages">
								<?foreach($arResult["PROPERTIES"]["ADVANTAGES"]["FULL_VALUE"] as $arItem) {
									if(!empty($arItem["PREVIEW_PICTURE"])) {?>
										<div class="product-item-detail-advantages-item">
											<div class="product-item-detail-advantages-item-pic">
												<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" width="<?=$arItem['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arItem['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
											</div>
											<div class="visible-md visible-lg product-item-detail-advantages-item-tooltip"><?=$arItem["NAME"]?></div>
										</div>
									<?}
								}
								unset($arItem);?>
							</div>
						<?}?>
					</div>
					<?//SKU_ITEMS//
					if($haveOffers && $arParams["OFFERS_VIEW"] != "PROPS" && $arParams["OFFERS_VIEW"] != "DROPDOWN_LIST") {?>
						<div class="col-xs-12 product-item-detail-scu-items-container" id="<?=$itemIds['SKU_ITEMS_ID']?>">
							<div class="h2"><?=Loc::getMessage("CT_BCE_CATALOG_SKU_ITEMS")?></div>
							<div class="product-item-detail-scu-items">
								<?//SKU_ITEMS_HEAD//?>
								<div class="hidden-xs hidden-sm product-item-detail-scu-item">
									<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col-non-left-pad"><?=($arParams["OFFERS_VIEW"] == "LIST" ? Loc::getMessage("CT_BCE_CATALOG_SKU_ITEMS_ITEM") : $arResult["OFFERS"][0]["PROPERTIES"]["OBJECT"]["NAME"])?></div>
									<div class="product-item-detail-scu-item-col"></div>
									<?if($arParams["OFFERS_VIEW"] != "LIST") {
										$offersMethods = false;
										foreach($arResult["OFFERS"] as $arOffer) {
											if(!empty($arOffer["PROPERTIES"]["OBJECT"]["FULL_VALUE"]["DELIVERY_METHODS"]) || !empty($arOffer["PROPERTIES"]["OBJECT"]["FULL_VALUE"]["PAYMENT_METHODS"])) {
												$offersMethods = true;
												break;
											}
										}
										unset($arOffer);
										if($offersMethods) {?>
											<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col-methods"></div>
										<?}
									}
									if(!empty($arResult["OFFERS_PROP"])) {
										foreach($arResult["SKU_PROPS"] as $skuProperty) {
											if(!isset($arResult["OFFERS_PROP"][$skuProperty["CODE"]]))
												continue;?>											
											
											<div class="product-item-detail-scu-item-col"><?=htmlspecialcharsEx($skuProperty["NAME"])?></div>
										<?}
										unset($skuProperty);
									}?>
									<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col-price"><?=Loc::getMessage("CT_BCE_CATALOG_SKU_ITEMS_PRICE")?></div>
									<?if($arParams["USE_PRODUCT_QUANTITY"] && $arParams["OFFERS_VIEW"] == "LIST" && (!$object || ($object && $objectContacts))) {?>
										<div class="product-item-detail-scu-item-col"></div>
									<?}?>
									<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col-buttons"></div>
									<?if(!$arParams["DISABLE_DELAY"] && $arParams["OFFERS_VIEW"] == "LIST" && (!$object || ($object && $objectContacts)) && !$partnersUrl) {
										$numOffersPartnersUrl = 0;
										foreach($arResult["OFFERS"] as $arOffer) {
											if(!empty($arOffer["PROPERTIES"]["PARTNERS_URL"]["VALUE"]))
												$numOffersPartnersUrl++;
										}
										unset($arOffer);
										if($numOffersPartnersUrl != count($arResult["OFFERS"])) {?>
											<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col-delay"></div>
										<?}
									}?>
								</div>
								<?//SKU_ITEMS_BODY//
								foreach($arResult["OFFERS"] as $key => $arOffer) {
									$offerName = !empty($arOffer["NAME"]) ? $arOffer["NAME"] : $name;
									$offerTitle = !empty($arOffer["NAME"]) ? $arOffer["NAME"] : $title;
									$offerAlt =  !empty($arOffer["NAME"]) ? $arOffer["NAME"] : $alt;
									
									$offerPrice = $arOffer["ITEM_PRICES"][$arOffer["ITEM_PRICE_SELECTED"]];
									$offerMeasureRatio = $arOffer["ITEM_MEASURE_RATIOS"][$arOffer["ITEM_MEASURE_RATIO_SELECTED"]]["RATIO"];
									
									$offerObject = !empty($arOffer["PROPERTIES"]["OBJECT"]["FULL_VALUE"]) ? $arOffer["PROPERTIES"]["OBJECT"]["FULL_VALUE"] : false;
									$offerObjectContacts = $offerObject["PHONE_SMS"] || $offerObject["EMAIL_EMAIL"] ? true : false;
									$offerPartnersUrl = !empty($arOffer["PROPERTIES"]["PARTNERS_URL"]["VALUE"]) ? true : false;?>
									
									<div class="product-item-detail-scu-item<?=($arParams['OFFERS_VIEW'] == 'LIST' && $arParams['DISPLAY_COMPARE'] ? ' product-item-detail-scu-item-width-compare' : '')?>" data-entity="sku-item" data-num="<?=$key?>">
										<div class="product-item-detail-scu-item-col<?=($arParams["OFFERS_VIEW"] != "LIST" ? ' product-item-detail-scu-item-col-non-left-pad' : '')?>">
											<?//SKU_ITEMS_LIST//
											if($arParams["OFFERS_VIEW"] == "LIST") {?>
												<div class="product-item-detail-scu-item-image">
													<?//SKU_ITEMS_LIST_IMAGE//
													if(is_array($arOffer["PREVIEW_PICTURE"])) {?>
														<img src="<?=$arOffer['PREVIEW_PICTURE']['SRC']?>" width="<?=$arOffer['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arOffer['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$offerAlt?>" title="<?=$offerTitle?>" />
													<?} else {?>
														<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo.png" width="222" height="222" alt="<?=$offerAlt?>" title="<?=$offerTitle?>" />
													<?}
													//SKU_ITEMS_LIST_COMPARE//
													if($arParams["DISPLAY_COMPARE"]) {?>
														<div class="product-item-detail-compare">
															<label title="<?=$arParams['MESS_BTN_COMPARE']?>" data-entity="compare">
																<input type="checkbox" data-entity="compare-checkbox">
																<span class="product-item-detail-compare-checkbox"><i class="icon-ok-b"></i></span>
																<span class="visible-xs visible-sm product-item-detail-compare-title" data-entity="compare-title"><?=$arParams["MESS_BTN_COMPARE"]?></span>
															</label>
														</div>
													<?}?>
												</div>
											<?//SKU_ITEMS_OBJECTS//
											} else {?>
												<<?=($offerObject ? "a target='_blank' href='".$offerObject["DETAIL_PAGE_URL"]."'" : "div")?> class="product-item-detail-scu-item-object-image">
													<?//SKU_ITEMS_OBJECTS_IMAGE//
													if(is_array($offerObject["PREVIEW_PICTURE"])) {?>
														<img src="<?=$offerObject['PREVIEW_PICTURE']['SRC']?>" width="<?=$offerObject['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$offerObject['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$offerObject['NAME']?>" />
													<?} else {?>
														<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo.png" width="222" height="222" alt="<?=$offerObject['NAME']?>" />
													<?}?>
												</<?=($offerObject ? "a" : "div")?>>
											<?}?>
										</div>
										<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col<?=($arParams['OFFERS_VIEW'] != 'LIST' ? '-object' : '')?>-info">
											<?//SKU_ITEMS_LIST//
											if($arParams["OFFERS_VIEW"] == "LIST") {?>
												<?//SKU_ITEMS_LIST_TITLE//?>
												<div class="product-item-detail-scu-item-title"><?=$offerName?></div>
											<?//SKU_ITEMS_OBJECTS//
											} else {
												//SKU_ITEMS_OBJECTS_TITLE//
												if($offerObject) {?>
													<a target="_blank" class="product-item-detail-scu-item-object-title" href="<?=$offerObject['DETAIL_PAGE_URL']?>"><?=$offerObject["NAME"]?></a>
												<?}
												if(isset($offerObject["REVIEWS_COUNT"]) && $offerObject["REVIEWS_COUNT"] > 0) {?>
													<div class="product-item-detail-scu-item-object-rating">
														<div class="product-item-detail-scu-item-object-rating-val"<?=($offerObject["RATING_VALUE"] <= 4.4 ? " data-rate='".intval($offerObject["RATING_VALUE"])."'" : "")?>><?=$offerObject["RATING_VALUE"]?></div>			
														<?$arReviewsDeclension = new Bitrix\Main\Grid\Declension(Loc::getMessage("CT_BCE_CATALOG_REVIEW"), Loc::getMessage("CT_BCE_CATALOG_REVIEWS_1"), Loc::getMessage("CT_BCE_CATALOG_REVIEWS_2"));?>
														<div class="product-item-detail-scu-item-object-rating-reviews-count"><?=$offerObject["REVIEWS_COUNT"]." ".$arReviewsDeclension->get($offerObject["REVIEWS_COUNT"])?></div>
														<?unset($arReviewsDeclension);?>
													</div>
												<?}?>
												<div class="product-item-detail-scu-item-object-hours product-item-detail-scu-item-object-hours-hidden" data-entity="hours"></div>
											<?}?>
										</div>
										<?//SKU_ITEMS_OBJECTS_DELIVERY_PAYMENT_METHODS//
										if($arParams["OFFERS_VIEW"] != "LIST" && $offersMethods) {?>
											<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col-methods">
												<?if(!empty($offerObject["DELIVERY_METHODS"])) {?>
													<div class="product-item-detail-scu-item-object-method"><?=$offerObject["DELIVERY_METHODS"]?></div>
												<?}
												if(!empty($offerObject["PAYMENT_METHODS"])) {?>
													<div class="product-item-detail-scu-item-object-method"><?=$offerObject["PAYMENT_METHODS"]?></div>
												<?}?>
											</div>
										<?}
										//SKU_ITEMS_PROPS//
										if(!empty($arResult["OFFERS_PROP"])) {
											foreach($arResult["SKU_PROPS"] as $skuProperty) {
												if(!isset($arResult["OFFERS_PROP"][$skuProperty["CODE"]]))
													continue;?>											
												
												<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col-prop">
													<div class="visible-xs visible-sm product-item-scu-title"><?=htmlspecialcharsEx($skuProperty["NAME"])?></div>
													<?if(array_key_exists("PROP_".$skuProperty["ID"], $arOffer["TREE"])) {
														$value = $skuProperty["VALUES"][$arOffer["TREE"]["PROP_".$skuProperty["ID"]]];
														if($skuProperty["SHOW_MODE"] === "PICT") {?>
															<div class="product-item-detail-scu-item-color" title="<?=$value['NAME']?>" style="<?=(!empty($value['CODE']) ? 'background-color: #'.$value['CODE'].';' : (!empty($value['PICT']) ? 'background-image: url('.$value['PICT']['SRC'].');' : ''));?>"></div>
														<?} else {?>
															<div class="product-item-detail-scu-item-text" title="<?=$value['NAME']?>"><?=$value["NAME"]?></div>
														<?}
														unset($value);
													}?>
												</div>
											<?}
											unset($skuProperty);
										}?>
										<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col-price">
											<?//SKU_ITEMS_PRICE//?>
											<div class="product-item-detail-scu-item-price">
												<?if(($arParams["OFFERS_VIEW"] == "LIST" && $offerPrice["SQ_M_PRICE"] > 0) || $offerPrice["PRICE"] > 0) {?>
													<div class="product-item-detail-price">
														<span class="product-item-detail-scu-item-price-current" data-entity="price-current"><?=($arParams["OFFERS_VIEW"] == "LIST" && $offerPrice["SQ_M_PRICE"] > 0 ? $offerPrice["SQ_M_PRINT_PRICE"] : $offerPrice["PRINT_PRICE"])?></span>
														<?if($arParams["OFFERS_VIEW"] == "LIST") {?>
															<span class="product-item-detail-price-measure">/<?=($offerPrice["SQ_M_PRICE"] > 0 ? Loc::getMessage("CT_BCE_CATALOG_MEASURE_SQ_M") : $arOffer["ITEM_MEASURE"]["TITLE"])?></span>
														<?}?>
													</div>
												<?} else {?>
													<div class="product-item-detail-price-not-set"><?=Loc::getMessage("CT_BCE_CATALOG_PRICE_NOT_SET")?></div>
												<?}
												if($arParams["SHOW_OLD_PRICE"] === "Y" && $offerPrice["PERCENT"] > 0) {?>
													<div class="product-item-detail-price-old" data-entity="price-old"><?=($offerPrice["SQ_M_BASE_PRICE"] > 0 ? $offerPrice["SQ_M_PRINT_BASE_PRICE"] : $offerPrice["PRINT_BASE_PRICE"])?></div>
													<div class="product-item-detail-price-economy" data-entity="price-economy"><?=Loc::getMessage("CT_BCE_CATALOG_ECONOMY_INFO2", array("#ECONOMY#" => ($offerPrice["SQ_M_DISCOUNT"] > 0 ? $offerPrice["SQ_M_PRINT_DISCOUNT"] : $offerPrice["PRINT_DISCOUNT"])))?></div>
												<?}?>
											</div>
											<?//SKU_ITEMS_QUANTITY_LIMIT//
											if($arParams["SHOW_MAX_QUANTITY"] !== "N") {?>
												<div class="product-item-detail-quantity<?=($arOffer['CAN_BUY'] ? '' : ' product-item-detail-quantity-not-avl')?>">
													<i class="icon-<?=($arOffer['CAN_BUY'] ? 'ok' : 'close')?>-b product-item-detail-quantity-icon"></i>
													<span class="product-item-detail-quantity-val">
														<?if($arOffer["CAN_BUY"]) {
															echo $arParams["MESS_SHOW_MAX_QUANTITY"]."&nbsp;";
															if($offerMeasureRatio && (float)$arOffer["CATALOG_QUANTITY"] > 0 && $arOffer["CATALOG_QUANTITY_TRACE"] === "Y" && $arOffer["CATALOG_CAN_BUY_ZERO"] === "N") {
																if($arParams["SHOW_MAX_QUANTITY"] === "M") {
																	if((float)$arOffer["CATALOG_QUANTITY"] / $offerMeasureRatio >= $arParams["RELATIVE_QUANTITY_FACTOR"]) {
																		echo $arParams["MESS_RELATIVE_QUANTITY_MANY"];
																	} else {
																		echo $arParams["MESS_RELATIVE_QUANTITY_FEW"];
																	}

																} else {
																	echo $arOffer["CATALOG_QUANTITY"];
																}
															}
														} else {
															echo $arParams["MESS_NOT_AVAILABLE"];
														}?>
													</span>
												</div>
											<?}?>
										</div>
										<?//SKU_ITEMS_QUANTITY//
										if($arParams["USE_PRODUCT_QUANTITY"] && $arParams["OFFERS_VIEW"] == "LIST" && (!$object || ($object && $objectContacts))) {?>
											<div class="product-item-detail-scu-item-col">												
												<?if($arOffer["CAN_BUY"] && $offerPrice["PRICE"] > 0) {
													if(!empty($arResult["PROPERTIES"]["M2_COUNT"]["VALUE"]) && ($arOffer["ITEM_MEASURE"]["SYMBOL_INTL"] == "pc. 1" || $arOffer["ITEM_MEASURE"]["SYMBOL_INTL"] == "m2")) {?>
														<div class="product-item-detail-amount">
															<a class="product-item-detail-amount-btn-minus" href="javascript:void(0)" rel="nofollow" data-entity="pc-quantity-down">-</a>
															<input class="product-item-detail-amount-input" type="tel" value="<?=$offerPrice['PC_MIN_QUANTITY']?>" data-entity="pc-quantity" />
															<a class="product-item-detail-amount-btn-plus" href="javascript:void(0)" rel="nofollow" data-entity="pc-quantity-up">+</a>
															<div class="product-item-detail-amount-measure"><?=Loc::getMessage("CT_BCE_CATALOG_MEASURE_PC")?></div>
														</div>
														<div class="product-item-detail-amount">
															<a class="product-item-detail-amount-btn-minus" href="javascript:void(0)" rel="nofollow" data-entity="sq-m-quantity-down">-</a>
															<input class="product-item-detail-amount-input" type="tel" value="<?=$offerPrice['SQ_M_MIN_QUANTITY']?>" data-entity="sq-m-quantity" />
															<a class="product-item-detail-amount-btn-plus" href="javascript:void(0)" rel="nofollow" data-entity="sq-m-quantity-up">+</a>
															<div class="product-item-detail-amount-measure"><?=Loc::getMessage("CT_BCE_CATALOG_MEASURE_SQ_M")?></div>
														</div>
													<?} else {?>
														<div class="product-item-detail-amount">								
															<a class="product-item-detail-amount-btn-minus" href="javascript:void(0)" rel="nofollow" data-entity="quantity-down">-</a>
															<input class="product-item-detail-amount-input" type="tel" value="<?=$offerPrice['MIN_QUANTITY']?>" data-entity="quantity" />
															<a class="product-item-detail-amount-btn-plus" href="javascript:void(0)" rel="nofollow" data-entity="quantity-up">+</a>
															<div class="product-item-detail-amount-measure"><?=$arOffer["ITEM_MEASURE"]["TITLE"]?></div>
														</div>
													<?}
												}?>
											</div>
										<?}?>
										<div class="product-item-detail-scu-item-col product-item-detail-scu-item-col-buttons">
											<?//SKU_ITEMS_BUTTONS//
											if($arOffer["CAN_BUY"]) {
												if($offerPrice["PRICE"] > 0) {
													if(!$arParams["DISABLE_BASKET"] && (($arParams["OFFERS_VIEW"] == "LIST" && !$partnersUrl && !$offerPartnersUrl) || ($arParams["OFFERS_VIEW"] == "OBJECTS" && !$offerPartnersUrl))) {
														if(($arParams["OFFERS_VIEW"] == "LIST" && (!$object || ($object && $objectContacts))) || ($arParams["OFFERS_VIEW"] == "OBJECTS" && $offerObjectContacts)) {
															if($showAddBtn) {?>
																<button type="button" class="btn btn-buy" data-entity="add"><i class="icon-cart"></i><span><?=$arParams["MESS_BTN_ADD_TO_BASKET"]?></span></button>
															<?}
															if($showBuyBtn) {?>
																<button type="button" class="btn btn-buy" data-entity="buy"><i class="icon-cart"></i><span><?=$arParams["MESS_BTN_BUY"]?></span></button>
															<?}
														}
														if(($arParams["OFFERS_VIEW"] == "LIST" && $object) || ($arParams["OFFERS_VIEW"] == "OBJECTS" && $offerObject)) {?>
															<button type="button" class="btn btn-default" data-entity="object"><i class="icon-phone-call"></i><span><?=Loc::getMessage("CT_BCE_CATALOG_CONTACTS")?></span></button>
														<?}
													} elseif(($arParams["OFFERS_VIEW"] == "LIST" && ($partnersUrl || $offerPartnersUrl)) || ($arParams["OFFERS_VIEW"] == "OBJECTS" && $offerPartnersUrl)) {?>
														<button type="button" class="btn btn-buy" data-entity="partner-link"><i class="icon-cart"></i><span><?=$arParams["MESS_BTN_BUY"]?></span></button>
														<?if(!empty($arSettings["PARTNERS_INFO_MESSAGE"])) {?>
															<div class="hidden-xs hidden-sm product-item-detail-info-message"><?=$arSettings["PARTNERS_INFO_MESSAGE"]?></div>
														<?}
													}
												} else {
													if(($arParams["OFFERS_VIEW"] == "LIST" || ($arParams["OFFERS_VIEW"] == "OBJECTS" && $offerObject)) && $arParams["ASK_PRICE"]) {?>
														<button type="button" class="btn btn-default" data-entity="ask-price"><i class="icon-comment"></i><span><?=Loc::getMessage("CT_BCE_CATALOG_ASK_PRICE")?></span></button>
													<?}
												}
											} else {
												if($arParams["OFFERS_VIEW"] == "LIST" || ($arParams["OFFERS_VIEW"] == "OBJECTS" && $offerObject)) {
													if($arParams["UNDER_ORDER"]) {?>
														<button type="button" class="btn btn-default" data-entity="not-available"><i class="icon-clock"></i><span><?=Loc::getMessage("CT_BCE_CATALOG_UNDER_ORDER")?></span></button>
													<?}
													if($arParams["PRODUCT_SUBSCRIPTION"] === "Y" && $arOffer["CATALOG_SUBSCRIBE"] === "Y") {?>
														<?$APPLICATION->IncludeComponent("bitrix:catalog.product.subscribe", "customSubscribeVlad",
															array(
																"PRODUCT_ID" => $arOffer["ID"],
																"BUTTON_ID" => $itemIds["SUBSCRIBE_LINK"]."_".$this->GetEditAreaId($arOffer["ID"]),
																"BUTTON_CLASS" => "btn btn-default s_custom",
																"DEFAULT_DISPLAY" => true,
																"MESS_BTN_SUBSCRIBE" => $arParams["~MESS_BTN_SUBSCRIBE"]
															),
															$component,
															array("HIDE_ICONS" => "Y")
														);?>
													<?}
												}
											}?>
										</div>
										<?//SKU_ITEMS_DELAY//
										if(!$arParams["DISABLE_DELAY"] && $arParams["OFFERS_VIEW"] == "LIST" && (!$object || ($object && $objectContacts)) && !$partnersUrl && $numOffersPartnersUrl != count($arResult["OFFERS"])) {?>
											<div class="hidden-xs hidden-sm product-item-detail-scu-item-col product-item-detail-scu-item-col-delay">
												<?if(!$offerPartnersUrl && $arOffer["CAN_BUY"] && $offerPrice["PRICE"] > 0) {?>
													<div class="product-item-detail-delay" title="<?=$arParams['MESS_BTN_DELAY']?>" data-entity="delay"><i class="icon-star"></i></div>
												<?}?>
											</div>
										<?}?>
									</div>
									<?unset($offerName, $offerTitle, $offerAlt, $offerPrice, $offerMeasureRatio, $offerArticle, $offerObject, $offerObjectContacts, $offerPartnersUrl);
								}
								unset($key, $arOffer, $offersMethods, $numOffersPartnersUrl);?>
							</div>
						</div>
					<?}
					//SET_CONSTRUCTOR//
					if($arResult["MODULES"]["catalog"] && $arResult["OFFER_GROUP"] && (!$haveOffers || ($haveOffers && ($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST")))) {?>
						<div class="col-xs-12 product-item-detail-set-constructor" id="<?=$itemIds['CONSTRUCTOR_ID']?>">
							<?$APPLICATION->IncludeComponent("altop:catalog.set.constructor.enext", ".default",
								array(
									"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arParams["IBLOCK_ID"],
									"ELEMENT_ID" => $actualItem["ID"],
									"BASKET_URL" => $arParams["BASKET_URL"],
									"PRICE_CODE" => $arParams["PRICE_CODE"],
									"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
									"BUNDLE_ITEMS_COUNT" => !empty($arResult["PROPERTIES"]["SET_ITEMS_COUNT"]["VALUE"]) ? $arResult["PROPERTIES"]["SET_ITEMS_COUNT"]["VALUE"] : $arParams["SET_ITEMS_COUNT"],
									"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
									"CURRENCY_ID" => $arParams["CURRENCY_ID"],
									"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
									"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
									"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
									"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
									"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"]
								),
								$component,
								array("HIDE_ICONS" => "Y")
							);?>
						</div>
					<?}
					//SET_ITEMS//
					if(!empty($arResult["SET_ITEMS"])) {?>
						<div class="col-xs-12 product-item-detail-set-items-container">
							<div class="h2"><?=Loc::getMessage("CT_BCE_CATALOG_SET_ITEMS")?></div>
							<div class="row product-item-detail-set-items <?=strtolower($arSettings['PRODUCTS_LIST_VIEW_MOBILE'])?>">
								<?foreach($arResult["SET_ITEMS"] as $arSetItem) {?>
									<div class="<?=($arSettings['PRODUCTS_LIST_VIEW_MOBILE'] == 'TWO_IN_ROW' ? 'col-xs-6' : 'col-xs-12 ')?> col-md-4">
										<a class="product-item-detail-set-item" href="<?=$arSetItem['DETAIL_PAGE_URL']?>" title="<?=$arSetItem['NAME']?>">
											<?//SET_ITEMS_IMAGE//?>
											<span class="product-item-detail-set-item-image">
												<?if(is_array($arSetItem["PREVIEW_PICTURE"])) {?>
													<img src="<?=$arSetItem['PREVIEW_PICTURE']['SRC']?>" width="<?=$arSetItem['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arSetItem['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arSetItem['NAME']?>" title="<?=$arSetItem['NAME']?>" />
												<?} else {?>
													<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo.png" width="222" height="222" alt="<?=$arSetItem['NAME']?>" title="<?=$arSetItem['NAME']?>" />
												<?}
												if(!empty($arSetItem["BRAND"]["PREVIEW_PICTURE"])) {?>
													<span class="product-item-detail-set-item-brand">
														<img src="<?=$arSetItem['BRAND']['PREVIEW_PICTURE']['SRC']?>" width="<?=$arSetItem['BRAND']['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arSetItem['BRAND']['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arSetItem['BRAND']['NAME']?>" title="<?=$arSetItem['BRAND']['NAME']?>" />
													</span>
												<?}?>
											</span>
											<?//SET_ITEMS_TITLE//?>
											<span class="product-item-detail-set-item-title"><?=$arSetItem["NAME"]?></span>
											<?//SET_ITEMS_QUANTITY//?>
											<span class="product-item-detail-set-item-quantity"><?=$arSetItem["QUANTITY"]." ".$arSetItem["MEASURE"]?></span>
										</a>
									</div>
								<?}
								unset($arSetItem);?>
							</div>
						</div>
					<?}
					//GIFTS//
					if($arResult["CATALOG"] && $arParams["USE_GIFTS_DETAIL"] == "Y" && (!$haveOffers || ($haveOffers && ($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST"))) && Bitrix\Main\ModuleManager::isModuleInstalled("sale")) {?>
						<div class="col-xs-12 product-item-detail-gifts" data-entity="parent-container" style="display: none;">
							<?if($arParams["GIFTS_DETAIL_HIDE_BLOCK_TITLE"] !== "Y") {?>
								<div class="h2" data-entity="header" data-showed="false" style="display: none; opacity: 0;"><?=($arParams["GIFTS_DETAIL_BLOCK_TITLE"] ?: Loc::getMessage("CT_BCE_CATALOG_GIFTS"))?></div>
							<?}
							CBitrixComponent::includeComponentClass("bitrix:sale.products.gift");?>
							<?$APPLICATION->IncludeComponent("bitrix:sale.products.gift", ".default",
								array(
									"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arParams["IBLOCK_ID"],
									"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
									"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
									"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
									"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
									"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
									"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
									"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => $arParams["LIST_PROPERTY_CODE"],
									"PROPERTY_CODE_".$arResult["OFFERS_IBLOCK"] => $arParams["OFFER_TREE_PROPS"],
									"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
									"BASKET_URL" => $arParams["BASKET_URL"],
									"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
									"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
									"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
									"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
									"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],				
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"CACHE_FILTER" => $arParams["CACHE_FILTER"],
									"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],									
									"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"] ? "Y" : "N",
									"PAGE_ELEMENT_COUNT" => 0,
									"DEFERRED_PAGE_ELEMENT_COUNT" => $arParams["GIFTS_DETAIL_PAGE_ELEMENT_COUNT"],				
									"PRICE_CODE" => $arParams["PRICE_CODE"],
									"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"] ? "Y" : "N",
									"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"] ? "Y" : "N",
									"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"] ? "Y" : "N",
									"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"] ? "Y" : "N",
									"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
									"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
									"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],										
									"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
									"CART_PROPERTIES_".$arResult["OFFERS_IBLOCK"] => $arParams["OFFERS_CART_PROPERTIES"],
									"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
									"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
									"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
									"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
									"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
									"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
									"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
									"SECTION_ID" => "",
									"SECTION_CODE" => "",
									"SECTION_URL" => "",
									"DETAIL_URL" => "",					
									"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"] ? "Y" : "N",
									"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
									"CURRENCY_ID" => $arParams["CURRENCY_ID"],
									"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
									"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
									"TEXT_LABEL_GIFT" => $arParams["GIFTS_DETAIL_TEXT_LABEL_GIFT"],							
									"PRODUCT_DISPLAY_MODE" => $arParams["PRODUCT_DISPLAY_MODE"],						
									"PRODUCT_ROW_VARIANTS" => "",
									"DEFERRED_PRODUCT_ROW_VARIANTS" => Bitrix\Main\Web\Json::encode(SaleProductsGiftComponent::predictRowVariants(3, $arParams["GIFTS_DETAIL_PAGE_ELEMENT_COUNT"])),
									"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => $arParams["ADD_PICT_PROP"],
									"ADDITIONAL_PICT_PROP_".$arResult["OFFERS_IBLOCK"] => $arParams["OFFER_ADD_PICT_PROP"],
									"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
									"OFFER_TREE_PROPS_".$arResult["OFFERS_IBLOCK"] => $arParams["OFFER_TREE_PROPS"],
									"PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
									"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],						
									"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
									"SHOW_MAX_QUANTITY" => $arParams["SHOW_MAX_QUANTITY"],
									"MESS_SHOW_MAX_QUANTITY" => $arParams["~MESS_SHOW_MAX_QUANTITY"],
									"RELATIVE_QUANTITY_FACTOR" => $arParams["RELATIVE_QUANTITY_FACTOR"],
									"MESS_RELATIVE_QUANTITY_MANY" => $arParams["~MESS_RELATIVE_QUANTITY_MANY"],
									"MESS_RELATIVE_QUANTITY_FEW" => $arParams["~MESS_RELATIVE_QUANTITY_FEW"],				
									"MESS_BTN_BUY" => $arParams["~MESS_BTN_BUY"],
									"MESS_BTN_ADD_TO_BASKET" => $arParams["~MESS_BTN_ADD_TO_BASKET"],
									"GIFTS_MESS_BTN_BUY" => $arParams["~GIFTS_MESS_BTN_BUY"],
									"GIFTS_MESS_BTN_ADD_TO_BASKET" => $arParams["~GIFTS_MESS_BTN_BUY"],
									"MESS_BTN_SUBSCRIBE" => $arParams["~MESS_BTN_SUBSCRIBE"],
									"MESS_BTN_DETAIL" => $arParams["~MESS_BTN_DETAIL"],
									"MESS_NOT_AVAILABLE" => $arParams["~MESS_NOT_AVAILABLE"],
									"MESS_BTN_COMPARE" => $arParams["~MESS_BTN_COMPARE"],
									"POTENTIAL_PRODUCT_TO_BUY" => array(
										"ID" => isset($arResult["ID"]) ? $arResult["ID"] : null,
										"MODULE" => isset($arResult["MODULE"]) ? $arResult["MODULE"] : "catalog",
										"PRODUCT_PROVIDER_CLASS" => isset($arResult["~PRODUCT_PROVIDER_CLASS"]) ? $arResult["~PRODUCT_PROVIDER_CLASS"] : "\Bitrix\Catalog\Product\CatalogProvider",
										"QUANTITY" => isset($arResult["QUANTITY"]) ? $arResult["QUANTITY"] : null,
										"IBLOCK_ID" => isset($arResult["IBLOCK_ID"]) ? $arResult["IBLOCK_ID"] : null,
										"PRIMARY_OFFER_ID" => isset($arResult["OFFERS"][$arResult["OFFERS_SELECTED"]]["ID"]) ? $arResult["OFFERS"][$arResult["OFFERS_SELECTED"]]["ID"] : null,
										"SECTION" => array(
											"ID" => isset($arResult["SECTION"]["ID"]) ? $arResult["SECTION"]["ID"] : null,
											"IBLOCK_ID" => isset($arResult["SECTION"]["IBLOCK_ID"]) ? $arResult["SECTION"]["IBLOCK_ID"] : null,
											"LEFT_MARGIN" => isset($arResult["SECTION"]["LEFT_MARGIN"]) ? $arResult["SECTION"]["LEFT_MARGIN"] : null,
											"RIGHT_MARGIN" => isset($arResult["SECTION"]["RIGHT_MARGIN"]) ? $arResult["SECTION"]["RIGHT_MARGIN"] : null,
										)
									),										
									"USE_ENHANCED_ECOMMERCE" => $arParams["USE_ENHANCED_ECOMMERCE"],
									"DATA_LAYER_NAME" => $arParams["DATA_LAYER_NAME"],
									"BRAND_PROPERTY" => $arParams["BRAND_PROPERTY"],
									"ADD_TO_BASKET_ACTION" => $arParams["ADD_TO_BASKET_ACTION"],
									"COMPARE_PATH" => $arParams["COMPARE_PATH"],
									"COMPARE_NAME" => $arParams["COMPARE_NAME"],
									"DETAIL_ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
									"DETAIL_OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
									"DETAIL_PROPERTY_CODE" => $arParams["PROPERTY_CODE"],				
									"DETAIL_OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
									"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => $arParams["MAIN_BLOCK_PROPERTY_CODE"],
									"DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => $arParams["MAIN_BLOCK_OFFERS_PROPERTY_CODE"],	
									"DETAIL_IMAGE_RESOLUTION" => $arParams["IMAGE_RESOLUTION"],				
									"DETAIL_ADD_DETAIL_TO_SLIDER" => $arParams["ADD_DETAIL_TO_SLIDER"],
									"DETAIL_DETAIL_PICTURE_MODE" => $arParams["DETAIL_PICTURE_MODE"],
									"DETAIL_SHOW_SLIDER" => $arParams["SHOW_SLIDER"],
									"DETAIL_SLIDER_INTERVAL" => $arParams["SLIDER_INTERVAL"],
									"DETAIL_SLIDER_PROGRESS" => $arParams["SLIDER_PROGRESS"],
									"USE_GIFTS_DETAIL" => $arParams["USE_GIFTS_DETAIL"],
									"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => $arParams["GIFTS_DETAIL_PAGE_ELEMENT_COUNT"],
									"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_HIDE_BLOCK_TITLE"],
									"GIFTS_DETAIL_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_BLOCK_TITLE"],
									"GIFTS_DETAIL_TEXT_LABEL_GIFT" => $arParams["GIFTS_DETAIL_TEXT_LABEL_GIFT"],
									"GIFTS_MESS_BTN_BUY" => $arParams["~GIFTS_MESS_BTN_BUY"],
									"USE_STORE" => $arParams["USE_STORE"],
									"STORE_PATH" => $arParams["STORE_PATH"],
									"STORES" => $arParams["STORES"],
									"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
									"USER_FIELDS" => $arParams["USER_FIELDS"],
									"FIELDS" => $arParams["FIELDS"],
									"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
									"SHOW_EMPTY_STORE" => $arParams["SHOW_EMPTY_STORE"],
									"SHOW_GENERAL_STORE_INFORMATION" => $arParams["SHOW_GENERAL_STORE_INFORMATION"],
									"MAIN_TITLE" => $arParams["~MAIN_TITLE"],
									"USE_REVIEW" => $arParams["USE_REVIEW"],
									"REVIEWS_IBLOCK_TYPE" => $arParams["REVIEWS_IBLOCK_TYPE"],
									"REVIEWS_IBLOCK_ID" => $arParams["REVIEWS_IBLOCK_ID"],
									"REVIEWS_NEWS_COUNT" => $arParams["REVIEWS_NEWS_COUNT"],
									"REVIEWS_SORT_BY1" => $arParams["REVIEWS_SORT_BY1"],
									"REVIEWS_SORT_ORDER1" => $arParams["REVIEWS_SORT_ORDER1"],
									"REVIEWS_SORT_BY2" => $arParams["REVIEWS_SORT_BY2"],
									"REVIEWS_SORT_ORDER2" => $arParams["REVIEWS_SORT_ORDER2"],
									"REVIEWS_ACTIVE_DATE_FORMAT" => $arParams["REVIEWS_ACTIVE_DATE_FORMAT"],
									"REVIEWS_PROPERTY_CODE" => $arParams["REVIEWS_PROPERTY_CODE"],
									"MESS_REVIEWS_TAB" => $arParams["MESS_REVIEWS_TAB"],
									"SET_ITEMS_COUNT" => $arParams["SET_ITEMS_COUNT"],
									"REINIT_ADD_BUY_URL_TEMPLATE" => $arParams["REINIT_ADD_BUY_URL_TEMPLATE"],
									"OBJECTS_USE_REVIEW" => $arParams["OBJECTS_USE_REVIEW"],
									"OBJECTS_REVIEWS_IBLOCK_ID" => $arParams["OBJECTS_REVIEWS_IBLOCK_ID"],
									"CONTACTS_IBLOCK_ID" => $arParams["CONTACTS_IBLOCK_ID"],
									"CONTACTS_USE_REVIEW" => $arParams["CONTACTS_USE_REVIEW"],
									"CONTACTS_REVIEWS_IBLOCK_ID" => $arParams["CONTACTS_REVIEWS_IBLOCK_ID"],
									"POPUP_MODE" => $arParams["POPUP_MODE"],
									"QUICK_VIEW" => isset($arParams["POPUP_MODE"]) && $arParams["POPUP_MODE"] == "Y" ? "OFF" : $arSettings["QUICK_VIEW"]
								),
								$component,
								array("HIDE_ICONS" => "Y")
							);?>
						</div>
					<?}?>
                    <!-- Мобильная начало -->
                        <div class="col-xs-12" id="<?=$itemIds['TABS_ID_MOBILE']?>" style="display: none">
                            <h4>Описание</h4>
                            <div>
                                <?//DETAIL_TEXT
                                if($showDescription) {?>
                                    <div class="col-xs-12 pt-5">
                                        <?=$arResult["DETAIL_TEXT"]?>
                                    </div>
                                <?}?>
                            </div>
                            <h4>Характеристики</h4>
                            <div>
                                <div class="col-xs-12">
                                    <?//PROPERTIES//
                                    $isMainProps = false;
                                    if(!empty($arResult["DISPLAY_PROPERTIES"])) {
                                        foreach($arResult["DISPLAY_PROPERTIES"] as $property) {
                                            if(isset($arParams["MAIN_BLOCK_PROPERTY_CODE"][$property["CODE"]])) {
                                                $isMainProps = true;
                                                break;
                                            }
                                        }
                                        unset($property);
                                    }
                                    if(shpgart($arResult['PRODUCT'], $arResult['PROPERTIES']['CML2_TRAITS']) || !!$isMainProps || (($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") && $arResult["SHOW_OFFERS_PROPS"])) {?>
                                        <div class="product-item-detail-tabs-main-properties-container">
                                            <div class="product-item-detail-properties-block"<?=(($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") && $arResult["SHOW_OFFERS_PROPS"] ? " id='".$itemIds["DISPLAY_MAIN_PROP_DIV_SECOND"]."'" : "");?>>
                                                <?$arShPGRes = shpgart($arResult['PRODUCT'], $arResult['PROPERTIES']['CML2_TRAITS']);?>
                                                <div class="product-item-detail-properties">
                                                    <div class="product-item-detail-properties-name"><?=$arShPGRes[2]?></div>
                                                    <div class="product-item-detail-properties-val"><?=$arShPGRes[3]?></div>
                                                </div>
                                                <?if(!empty($arResult["DISPLAY_PROPERTIES"])) {
                                                    foreach($arResult["DISPLAY_PROPERTIES"] as $property) {
                                                        if(isset($arParams["MAIN_BLOCK_PROPERTY_CODE"][$property["CODE"]])) {?>
                                                            <div class="product-item-detail-properties">
                                                                <div class="product-item-detail-properties-name"><?=$property["NAME"]?></div>
                                                                <div class="product-item-detail-properties-val"><?=$property["DISPLAY_VALUE"]?></div>
                                                            </div>
                                                        <?}
                                                    }
                                                    unset($property);
                                                }?>
                                                <?if(!$showWeightOffer){?>
                                                    <div class="product-item-detail-properties">
                                                        <div class="product-item-detail-properties-name"><?=$arShPGRes[4]?></div>
                                                        <div class="product-item-detail-properties-val"><?=$arShPGRes[5]?></div>
                                                    </div>
                                                <?}?>
                                                <?if(!$showDesmisionOffer){?>
                                                    <div class="product-item-detail-properties">
                                                        <div class="product-item-detail-properties-name"><?=$arShPGRes[0]?></div>
                                                        <div class="product-item-detail-properties-val"><?=$arShPGRes[1]?></div>
                                                    </div>
                                                <?}?>
                                                <?unset($arShPGRes);?>
                                            </div>
                                        </div>
                                    <?}
                                    unset($isMainProps);?>
                                </div>
                            </div>
                            <?if($video && !$validYouTube['error']){?>
                                <h4>Видео</h4>
                                <div>
                                    <div class="col-xs-12 block-video-item" itemprop="video">
                                        <iframe src="<?=$validYouTube['url']?>" title="<?=$validYouTube['tittle']?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                            <?}?>
                            <h4>Отзывы о товаре</h4>
                            <div>
                                <?if(isset($arResult["REVIEWS_COUNT"])) {?>
                                    <div class="col-xs-12 product-item-detail-tab-content" data-entity="tab-container" data-value="reviews">
                                        <?if($disabledVlad){?><div class="h2"><?=$arParams["MESS_REVIEWS_TAB"]?></div><?}?>
                                        <div class="product-item-detail-reviews">
                                            <?$GLOBALS["arReviewsFilter"] = array("PROPERTY_PRODUCT_ID" => $arResult["ID"]);?>
                                            <?$APPLICATION->IncludeComponent("bitrix:news.list", "reviews",
                                                array(
                                                    "IBLOCK_TYPE" => $arParams["REVIEWS_IBLOCK_TYPE"],
                                                    "IBLOCK_ID" => $arParams["REVIEWS_IBLOCK_ID"],
                                                    "NEWS_COUNT" => $arParams["REVIEWS_NEWS_COUNT"],
                                                    "SORT_BY1" => $arParams["REVIEWS_SORT_BY1"],
                                                    "SORT_ORDER1" => $arParams["REVIEWS_SORT_ORDER1"],
                                                    "SORT_BY2" => $arParams["REVIEWS_SORT_BY2"],
                                                    "SORT_ORDER2" => $arParams["REVIEWS_SORT_ORDER2"],
                                                    "FILTER_NAME" => "arReviewsFilter",
                                                    "FIELD_CODE" => array(),
                                                    "PROPERTY_CODE" => $arParams["REVIEWS_PROPERTY_CODE"],
                                                    "CHECK_DATES" => "Y",
                                                    "DETAIL_URL" => "",
                                                    "AJAX_MODE" => "",
                                                    "AJAX_OPTION_SHADOW" => "",
                                                    "AJAX_OPTION_JUMP" => "",
                                                    "AJAX_OPTION_STYLE" => "",
                                                    "AJAX_OPTION_HISTORY" => "",
                                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                    "PREVIEW_TRUNCATE_LEN" => "",
                                                    "ACTIVE_DATE_FORMAT" => $arParams["REVIEWS_ACTIVE_DATE_FORMAT"],
                                                    "DISPLAY_PANEL" => "",
                                                    "SET_TITLE" => "N",
                                                    "SET_BROWSER_TITLE" => "N",
                                                    "SET_META_KEYWORDS" => "N",
                                                    "SET_META_DESCRIPTION" => "N",
                                                    "SET_STATUS_404" => "N",
                                                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                                    "ADD_SECTIONS_CHAIN" => "",
                                                    "HIDE_LINK_WHEN_NO_DETAIL" => "",
                                                    "PARENT_SECTION" => "",
                                                    "PARENT_SECTION_CODE" => "",
                                                    "DISPLAY_NAME" => "",
                                                    "DISPLAY_DATE" => "",
                                                    "DISPLAY_TOP_PAGER" => "N",
                                                    "DISPLAY_BOTTOM_PAGER" => "Y",
                                                    "PAGER_SHOW_ALWAYS" => "",
                                                    "PAGER_TEMPLATE" => "arrows",
                                                    "PAGER_DESC_NUMBERING" => "",
                                                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "",
                                                    "PAGER_SHOW_ALL" => "",
                                                    "AJAX_OPTION_ADDITIONAL" => ""
                                                ),
                                                $component,
                                                array("HIDE_ICONS" => "Y")
                                            );?>
                                        </div>
                                    </div>
                                <?}?>
                            </div>
                            <h4 class="ex-last-tab">Задать вопрос</h4>
                            <div>
                                <div id="mess-by-form" class="mess-by-form">
                                                <span class="slide-panel__close">
                                                    <i class="icon-close"></i>
                                                </span>
                                    <div class="message-f">
                                        <h1>Спасибо!</h1>
                                        <div class="mess-f">
                                            Ваш запрос успешно отправлен специалистам команды EXTREME LOOK
                                        </div>
                                        <div class="mess-f">
                                            В ближайшее время мы обязательно на него ответим
                                        </div>
                                    </div>
                                    <div class="icon-mess-f"></div>
                                </div>
                                <form id="formQuestionItem" action="">
                                    <input type="hidden" id="_ITEM" name="QUESTION_ITEM" value="<?=$arResult["PREVIEW_TEXT"]?>">
                                    <input maxlength="40" id="_NAME" placeholder="Пожалуйста представьтесь" class="form-control fl-left" name="QUESTION_NAME" type="text">
                                    <input maxlength="60" id="_EMAIL" placeholder="Введите Email адрес" class="form-control fl-left" name="QUESTION_EMAIL" type="text">
                                    <textarea maxlength="700" id="_MESS" placeholder="Задайте свой вопрос" class="form-control my-textarea" name="QUESTION_MESS"></textarea>
                                    <button onclick="questionFormAjax();" id="submit_q_form" class="btn btn-buy" type="button" disabled><span>Отправить</span></button>
                                </form>
                            </div>
                            <script>
                                $(function() {
                                    $("#<?=$itemIds['TABS_ID_MOBILE']?>").accordion({
                                        active: 0,
                                        heightStyle: "content",
                                        collapsible: true,
                                        icons: {
                                            "header": "ex-icon-arrow-up",
                                            "activeHeader": "ex-icon-arrow-down"
                                        }
                                    });
                                });
                            </script>
                        </div>
                    <!-- Мобильная конец -->
                    <!-- Десктопная начало -->
                        <div class="tabs-element-ex product-item-detail-description" itemprop="description">
                            <div class="block-tabs-item" id="<?=$itemIds['TABS_ID']?>" style="display: block">
                                <div class="tabs-wrap">
                                    <div class="tabs__list" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'main-element-tabs_quick' : 'main-element-tabs'?>">
                                        <div class="">
                                            <div class="row">
                                                <div class="col-xs-12" data-entity="tab-container">
                                                    <div class="tabs-el__scroll">
                                                        <ul class="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tabs-el__tabs_quick' : 'tabs-el__tabs'?>" data-entity="tabs">
                                                            <li class="tabs__tab" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el_quick' : 'tab-el'?>" data-value="description">Описание</li>
                                                            <?if(($arSettings["TAB_PROPERTIES"] == "Y" && (!empty($arResult["DISPLAY_PROPERTIES"]) || (($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") && $arResult["SHOW_OFFERS_PROPS"]))) || shpgart($arResult['PRODUCT'], $arResult['PROPERTIES']['CML2_TRAITS'])) {?>
                                                                <li class="tabs__tab" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el_quick' : 'tab-el'?>" data-value="specifications">Характеристики</li>
                                                            <?}?>
                                                            <?if($video && !$validYouTube['error']){?>
                                                                <li class="tabs__tab" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el_quick' : 'tab-el'?>" data-value="video">Видео</li>
                                                            <?}?>
                                                            <li class="tabs__tab" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el_quick' : 'tab-el'?>" data-value="reviews">Отзывы о товаре</li>
                                                            <li class="tabs__tab" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el_quick' : 'tab-el'?>" data-value="question">Задать вопрос</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabs__content" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'main-tabs-content-element_quick' : 'main-tabs-content-element'?>">
                                        <div class="row">
                                            <div class="tabs__box" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el-content_quick' : 'tab-el-content'?>" data-value="description">
                                                <?//DETAIL_TEXT
                                                if($showDescription){?>
                                                    <div class="col-xs-12 pt-5" itemprop="description">
                                                        <?=$arResult["DETAIL_TEXT"]?>
                                                    </div>
                                                <?}?>
                                            </div>
                                            <?if(shpgart($arResult['PRODUCT'], $arResult['PROPERTIES']['CML2_TRAITS']) || ($arSettings["TAB_PROPERTIES"] == "Y" && (!empty($arResult["DISPLAY_PROPERTIES"]) || (($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") && $arResult["SHOW_OFFERS_PROPS"])))) {?>
                                                <div class="tabs__box" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el-content_quick' : 'tab-el-content'?>" data-value="specifications">
                                                    <div class="col-xs-12" itemprop="specifications">
                                                        <?//PROPERTIES//
                                                        $isMainProps = false;
                                                        if(!empty($arResult["DISPLAY_PROPERTIES"])) {
                                                            foreach($arResult["DISPLAY_PROPERTIES"] as $property) {
                                                                if(isset($arParams["MAIN_BLOCK_PROPERTY_CODE"][$property["CODE"]])) {
                                                                    $isMainProps = true;
                                                                    break;
                                                                }
                                                            }
                                                            unset($property);
                                                        }
                                                        if(shpgart($arResult['PRODUCT'], $arResult['PROPERTIES']['CML2_TRAITS']) || !!$isMainProps || (($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") && $arResult["SHOW_OFFERS_PROPS"])) {?>
                                                            <div class="product-item-detail-tabs-main-properties-container">
                                                                <div class="product-item-detail-properties-block"<?=(($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") && $arResult["SHOW_OFFERS_PROPS"] ? " id='".$itemIds["DISPLAY_MAIN_PROP_DIV_SECOND"]."'" : "");?>>
                                                                    <?$arShPGRes = shpgart($arResult['PRODUCT'], $arResult['PROPERTIES']['CML2_TRAITS']);?>
                                                                    <div class="product-item-detail-properties">
                                                                        <div class="product-item-detail-properties-name"><?=$arShPGRes[2]?></div>
                                                                        <div class="product-item-detail-properties-val"><?=$arShPGRes[3]?></div>
                                                                    </div>
                                                                    <?if(!empty($arResult["DISPLAY_PROPERTIES"])) {
                                                                        foreach($arResult["DISPLAY_PROPERTIES"] as $property) {
                                                                            if(isset($arParams["MAIN_BLOCK_PROPERTY_CODE"][$property["CODE"]])) {?>
                                                                                <div class="product-item-detail-properties">
                                                                                    <div class="product-item-detail-properties-name"><?=$property["NAME"]?></div>
                                                                                    <div class="product-item-detail-properties-val"><?=$property["DISPLAY_VALUE"]?></div>
                                                                                </div>
                                                                            <?}
                                                                        }
                                                                        unset($property);
                                                                    }?>
                                                                    <?if(!$showWeightOffer){?>
                                                                        <div class="product-item-detail-properties">
                                                                            <div class="product-item-detail-properties-name"><?=$arShPGRes[4]?></div>
                                                                            <div class="product-item-detail-properties-val"><?=$arShPGRes[5]?></div>
                                                                        </div>
                                                                    <?}?>
                                                                    <?if(!$showDesmisionOffer){?>
                                                                        <div class="product-item-detail-properties">
                                                                            <div class="product-item-detail-properties-name"><?=$arShPGRes[0]?></div>
                                                                            <div class="product-item-detail-properties-val"><?=$arShPGRes[1]?></div>
                                                                        </div>
                                                                    <?}?>
                                                                    <?unset($arShPGRes);?>
                                                                </div>
                                                            </div>
                                                        <?}
                                                        unset($isMainProps);?>
                                                    </div>
                                                </div>
                                            <?}?>
                                            <?if($video && !$validYouTube['error']){?>
                                                <div class="tabs__box" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el-content_quick' : 'tab-el-content'?>" data-value="video">
                                                    <div class="col-xs-12 block-video-item" itemprop="video">
                                                        <iframe src="<?=$validYouTube['url']?>" title="<?=$validYouTube['tittle']?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            <?}?>
                                            <div class="tabs__box" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el-content_quick' : 'tab-el-content'?>" data-value="reviews">
                                                <?if(isset($arResult["REVIEWS_COUNT"])) {?>
                                                <div class="col-xs-12 product-item-detail-tab-content" data-entity="tab-container" data-value="reviews">
                                                    <?if($disabledVlad){?><div class="h2"><?=$arParams["MESS_REVIEWS_TAB"]?></div><?}?>
                                                    <div class="product-item-detail-reviews">
                                                        <?$GLOBALS["arReviewsFilter"] = array("PROPERTY_PRODUCT_ID" => $arResult["ID"]);?>
                                                        <?$APPLICATION->IncludeComponent("bitrix:news.list", "reviews",
                                                            array(
                                                                "IBLOCK_TYPE" => $arParams["REVIEWS_IBLOCK_TYPE"],
                                                                "IBLOCK_ID" => $arParams["REVIEWS_IBLOCK_ID"],
                                                                "NEWS_COUNT" => $arParams["REVIEWS_NEWS_COUNT"],
                                                                "SORT_BY1" => $arParams["REVIEWS_SORT_BY1"],
                                                                "SORT_ORDER1" => $arParams["REVIEWS_SORT_ORDER1"],
                                                                "SORT_BY2" => $arParams["REVIEWS_SORT_BY2"],
                                                                "SORT_ORDER2" => $arParams["REVIEWS_SORT_ORDER2"],
                                                                "FILTER_NAME" => "arReviewsFilter",
                                                                "FIELD_CODE" => array(),
                                                                "PROPERTY_CODE" => $arParams["REVIEWS_PROPERTY_CODE"],
                                                                "CHECK_DATES" => "Y",
                                                                "DETAIL_URL" => "",
                                                                "AJAX_MODE" => "",
                                                                "AJAX_OPTION_SHADOW" => "",
                                                                "AJAX_OPTION_JUMP" => "",
                                                                "AJAX_OPTION_STYLE" => "",
                                                                "AJAX_OPTION_HISTORY" => "",
                                                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                                                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                                "PREVIEW_TRUNCATE_LEN" => "",
                                                                "ACTIVE_DATE_FORMAT" => $arParams["REVIEWS_ACTIVE_DATE_FORMAT"],
                                                                "DISPLAY_PANEL" => "",
                                                                "SET_TITLE" => "N",
                                                                "SET_BROWSER_TITLE" => "N",
                                                                "SET_META_KEYWORDS" => "N",
                                                                "SET_META_DESCRIPTION" => "N",
                                                                "SET_STATUS_404" => "N",
                                                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                                                "ADD_SECTIONS_CHAIN" => "",
                                                                "HIDE_LINK_WHEN_NO_DETAIL" => "",
                                                                "PARENT_SECTION" => "",
                                                                "PARENT_SECTION_CODE" => "",
                                                                "DISPLAY_NAME" => "",
                                                                "DISPLAY_DATE" => "",
                                                                "DISPLAY_TOP_PAGER" => "N",
                                                                "DISPLAY_BOTTOM_PAGER" => "Y",
                                                                "PAGER_SHOW_ALWAYS" => "",
                                                                "PAGER_TEMPLATE" => "arrows",
                                                                "PAGER_DESC_NUMBERING" => "",
                                                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "",
                                                                "PAGER_SHOW_ALL" => "",
                                                                "AJAX_OPTION_ADDITIONAL" => ""
                                                            ),
                                                            $component,
                                                            array("HIDE_ICONS" => "Y")
                                                        );?>
                                                    </div>
                                                </div>
                                                <?}?>
                                            </div>
                                            <div id="mess-by-form" class="mess-by-form">
                                                <span class="slide-panel__close">
                                                    <i class="icon-close"></i>
                                                </span>
                                                <div class="message-f">
                                                    <h1>Спасибо!</h1>
                                                    <div class="mess-f">
                                                        Ваш запрос успешно отправлен специалистам команды EXTREME LOOK
                                                    </div>
                                                    <div class="mess-f">
                                                        В ближайшее время мы обязательно на него ответим
                                                    </div>
                                                </div>
                                                <div class="icon-mess-f"></div>
                                            </div>
                                            <div class="tabs__box" data-entity="<?=$arParams['QUICK_POPUP_VLAD'] ? 'tab-el-content_quick' : 'tab-el-content'?>" data-value="question">
                                                <form id="formQuestionItem" action="">
                                                    <input type="hidden" id="_ITEM" name="QUESTION_ITEM" value="<?=$arResult["PREVIEW_TEXT"]?>">
                                                    <input maxlength="40" id="_NAME" placeholder="Пожалуйста представьтесь" class="form-control fl-left" name="QUESTION_NAME" type="text">
                                                    <input maxlength="60" id="_EMAIL" placeholder="Введите Email адрес" class="form-control fl-left" name="QUESTION_EMAIL" type="text">
                                                    <textarea maxlength="700" id="_MESS" placeholder="Задайте свой вопрос" class="form-control my-textarea" name="QUESTION_MESS"></textarea>
                                                    <button onclick="questionFormAjax();" id="submit_q_form" class="btn btn-buy" type="button" disabled><span>Отправить</span></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <!-- Десктопная конец -->
                <?if($arParams['QUICK_POPUP_VLAD']){ //выводим форму стать партнёром после описания в быстром просмотре
                    $APPLICATION->IncludeComponent("bitrix:main.include", "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR."include/footer_form-part.php"
                        ),
                        false,
                        array("HIDE_ICONS" => "Y")
                    );
                }?>
                <?if($arParams['QUICK_POPUP_VLAD']){ //Выводим рекомендации в модальном окне карточки товара
                    $UF_RECOMENDED_PROD = array(); //Мой код. Выводим кастомную фильтрацию по доп полю рекомендованных товаров который накидывается вручную.
                    $UF_SECTION_RECOMENDED_PROD = array(); //Мой код. Выводим кастомную фильтрацию по доп полю рекомендованных разделов который накидывается вручную.
                    global $RecomCustomArrFilterItems; //Кастомный фильтр должен быть глобальным иначе компонент его не увидит
                    $RecomCustomArrFilterItems['ID'] = array(); //Кастомный фильтр с идентификаторами товаров формируется ниже
                    $resDbRecCus = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $arResult['ID'], "sort", "asc", array("CODE" => "UF_RECOMENDED_PROD")); //Запрашиваем свойства товара на странице которого находимся
                    $resDbRecCusSect = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $arResult['ID'], "sort", "asc", array("CODE" => "UF_RECOMENDS_SECTION")); //Запрашиваем свойства товара на странице которого находимся
                    while ($resRecomendenCustom = $resDbRecCus->GetNext()) {
                        if(isset($resRecomendenCustom['VALUE']) && !empty($resRecomendenCustom['VALUE']) && $resRecomendenCustom['VALUE']) {
                            $UF_RECOMENDED_PROD[] = $resRecomendenCustom['VALUE']; //Формируем массив ID товаров указанных в свойстве
                        }
                    }
                    while ($resRecomendenCustomSection = $resDbRecCusSect->GetNext()) {
                        if(isset($resRecomendenCustomSection['VALUE']) && !empty($resRecomendenCustomSection['VALUE']) && $resRecomendenCustomSection['VALUE']) {
                            $ELEM_BY_SECTIONS = CIBlockElement::GetList(
                                Array("ID" => "ASC"),
                                Array("IBLOCK_ID" => 23, "SECTION_ID" => $resRecomendenCustomSection['VALUE'], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => "Y"),
                                false,
                                false,
                                Array('ID')
                            );
                            while ($ELEMS = $ELEM_BY_SECTIONS->GetNext()) {
                                array_push($UF_SECTION_RECOMENDED_PROD, $ELEMS['ID']); //Формируем массив ID товаров из разделов
                            }
                        }
                    }
                    // *** ЗАНОСИМ В ГЛОБАЛЬНЫЙ КАСТОМНЫЙ ФИЛЬТР ВСЁ ЧТО УКАЗАНО В РЕКОМЕНДОВАННОМ -- НАЧАЛО -- ***//
                    if(count($UF_RECOMENDED_PROD) == 1)
                        if(isset($UF_RECOMENDED_PROD[0]) && !empty($UF_RECOMENDED_PROD[0])) {
                            array_push($RecomCustomArrFilterItems['ID'], $UF_RECOMENDED_PROD[0]);
                        }
                    if(count($UF_RECOMENDED_PROD) > 1){
                        foreach($UF_RECOMENDED_PROD as $key => $RE)
                            array_push($RecomCustomArrFilterItems['ID'], $UF_RECOMENDED_PROD[$key]);
                    }
                    if(count($UF_SECTION_RECOMENDED_PROD) == 1)
                        if(isset($UF_SECTION_RECOMENDED_PROD[0]) && !empty($UF_SECTION_RECOMENDED_PROD[0])) {
                            array_push($RecomCustomArrFilterItems['ID'], $UF_SECTION_RECOMENDED_PROD[0]);
                        }
                    if(count($UF_SECTION_RECOMENDED_PROD) > 1){
                        foreach($UF_SECTION_RECOMENDED_PROD as $key => $RES)
                            array_push($RecomCustomArrFilterItems['ID'], $UF_SECTION_RECOMENDED_PROD[$key]);
                    }
                    // *** ЗАНОСИМ В ГЛОБАЛЬНЫЙ КАСТОМНЫЙ ФИЛЬТР ВСЁ ЧТО УКАЗАНО В РЕКОМЕНДОВАННОМ -- КОНЕЦ -- ***//?>
                <?if(isset($RecomCustomArrFilterItems['ID']) && !empty($RecomCustomArrFilterItems['ID']) && $RecomCustomArrFilterItems['ID']) {?>
                    <div class="product-item-detail-related" data-entity="parent-container" style="display: none;">
                        <div class="col-xs-12" itemprop="description">
                            <div class="h2">С этим товаром покупают</div>
                           <?shuffle($RecomCustomArrFilterItems['ID']); //Перемешиваем массив так чисто по приколу
                               //**Мой код КОМПОНЕНТ КАТАЛОГА ВЫВОДИТ ТОВАРЫ ПО ФИЛЬТРУ ИЗ ДОП ПОЛЯ "Рекомендованные"**//
                               $APPLICATION->IncludeComponent("bitrix:catalog.section", ".default",
                                   array(
                                       //"COMPONENT_TEMPLATE" => ".default",
                                       "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                       "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                       //"SECTION_ID" => "",
                                       //"SECTION_CODE" => "",
                                       "SECTION_USER_FIELDS" => array(),
                                       "FILTER_NAME" => "RecomCustomArrFilterItems",
                                       "INCLUDE_SUBSECTIONS" => "Y", //$arParams["INCLUDE_SUBSECTIONS"],
                                       "SHOW_ALL_WO_SECTION" => "Y",
                                       "CUSTOM_FILTER" => "",
                                       "HIDE_NOT_AVAILABLE" => "Y", //$arParams["HIDE_NOT_AVAILABLE"],
                                       "HIDE_NOT_AVAILABLE_OFFERS" => "Y", //$arParams["HIDE_NOT_AVAILABLE_OFFERS"],
                                       "ELEMENT_SORT_FIELD" => "rand", //$arParams["ELEMENT_SORT_FIELD"],
                                       "ELEMENT_SORT_ORDER" => "asc", //$arParams["ELEMENT_SORT_ORDER"],
                                       "ELEMENT_SORT_FIELD2" => "", //$arParams["ELEMENT_SORT_FIELD2"],
                                       "ELEMENT_SORT_ORDER2" => "", //$arParams["ELEMENT_SORT_ORDER2"],
                                       "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                                       "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                                       "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                                       "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                                       "PAGE_ELEMENT_COUNT" => "4",//$isWideScreenMode ? "12" : "8",
                                       "LINE_ELEMENT_COUNT" => "4",
                                       "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                                       "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                                       "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                                       "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
                                       "BACKGROUND_IMAGE" => "-",
                                       //"PRODUCT_ROW_VARIANTS" => $isWideScreenMode ? "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]" : "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
                                       "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
                                       "PRODUCT_DISPLAY_MODE" => $arParams["PRODUCT_DISPLAY_MODE"],
                                       "OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
                                       "PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
                                       "SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
                                       "SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
                                       "SHOW_MAX_QUANTITY" => $arParams["SHOW_MAX_QUANTITY"],
                                       "MESS_SHOW_MAX_QUANTITY" => $arParams["MESS_SHOW_MAX_QUANTITY"],
                                       "RELATIVE_QUANTITY_FACTOR" => $arParams["RELATIVE_QUANTITY_FACTOR"],
                                       "MESS_RELATIVE_QUANTITY_MANY" => $arParams["MESS_RELATIVE_QUANTITY_MANY"],
                                       "MESS_RELATIVE_QUANTITY_FEW" => $arParams["MESS_RELATIVE_QUANTITY_FEW"],
                                       "MESS_BTN_BUY" => $arParams["MESS_BTN_BUY"],
                                       "MESS_BTN_ADD_TO_BASKET" => $arParams["MESS_BTN_ADD_TO_BASKET"],
                                       "MESS_BTN_SUBSCRIBE" => $arParams["MESS_BTN_SUBSCRIBE"],
                                       "MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
                                       "MESS_NOT_AVAILABLE" => $arParams["MESS_NOT_AVAILABLE"],
                                       "RCM_TYPE" => "personal",
                                       "RCM_PROD_ID" => "",
                                       "SHOW_FROM_SECTION" => "N",
                                       "SECTION_URL" => "",
                                       "DETAIL_URL" => "",
                                       "SECTION_ID_VARIABLE" => "SECTION_ID",
                                       "SEF_MODE" => "N",
                                       "AJAX_MODE" => "N",
                                       "AJAX_OPTION_JUMP" => "N",
                                       "AJAX_OPTION_STYLE" => "Y",
                                       "AJAX_OPTION_HISTORY" => "N",
                                       "AJAX_OPTION_ADDITIONAL" => "",
                                       "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                       "CACHE_TIME" => $arParams["CACHE_TIME"],
                                       "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                       "SET_TITLE" => "N",
                                       "SET_BROWSER_TITLE" => "N",
                                       "BROWSER_TITLE" => "-",
                                       "SET_META_KEYWORDS" => "N",
                                       "META_KEYWORDS" => "-",
                                       "SET_META_DESCRIPTION" => "N",
                                       "META_DESCRIPTION" => "-",
                                       "SET_LAST_MODIFIED" => "N",
                                       "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"] ? "Y" : "N",
                                       "ADD_SECTIONS_CHAIN" => "N",
                                       "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                       "USE_REVIEW" => $arParams["USE_REVIEW"],
                                       "REVIEWS_IBLOCK_TYPE" => $arParams["REVIEWS_IBLOCK_TYPE"],
                                       "REVIEWS_IBLOCK_ID" => $arParams["REVIEWS_IBLOCK_ID"],
                                       "REVIEWS_NEWS_COUNT" => $arParams["REVIEWS_NEWS_COUNT"],
                                       "REVIEWS_SORT_BY1" => $arParams["REVIEWS_SORT_BY1"],
                                       "REVIEWS_SORT_ORDER1" => $arParams["REVIEWS_SORT_ORDER1"],
                                       "REVIEWS_SORT_BY2" => $arParams["REVIEWS_SORT_BY2"],
                                       "REVIEWS_SORT_ORDER2" => $arParams["REVIEWS_SORT_ORDER2"],
                                       "REVIEWS_ACTIVE_DATE_FORMAT" => $arParams["REVIEWS_ACTIVE_DATE_FORMAT"],
                                       "REVIEWS_PROPERTY_CODE" => $arParams["REVIEWS_PROPERTY_CODE"],
                                       "MESS_REVIEWS_TAB" => $arParams["MESS_REVIEWS_TAB"],
                                       "ACTION_VARIABLE" => "action",
                                       "PRODUCT_ID_VARIABLE" => "id",
                                       "PRICE_CODE" => $arParams["PRICE_CODE"],
                                       "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"] ? "Y" : "N",
                                       "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"] ? "Y" : "N",
                                       "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"] ? "Y" : "N",
                                       "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                                       "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                                       "BASKET_URL" => "/personal/basket.php",//$arParams["BASKET_URL"],
                                       "USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"] ? "Y" : "N",
                                       "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                       "ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
                                       "PRODUCT_PROPS_VARIABLE" => "prop",
                                       "PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
                                       "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                                       "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                                       "ADD_TO_BASKET_ACTION" => $showBuyBtn ? "BUY" : "ADD",
                                       "DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"] ? "Y" : "N",
                                       "COMPARE_PATH" => $arParams["COMPARE_PATH"],
                                       "MESS_BTN_COMPARE" => $arParams["MESS_BTN_COMPARE"],
                                       "COMPARE_NAME" => $arParams["COMPARE_NAME"],
                                       "USE_ENHANCED_ECOMMERCE" => "N",
                                       "PAGER_TEMPLATE" => "arrows",
                                       "DISPLAY_TOP_PAGER" => "N",
                                       "DISPLAY_BOTTOM_PAGER" => "N",
                                       "PAGER_TITLE" => "",
                                       "PAGER_SHOW_ALWAYS" => "N",
                                       "PAGER_DESC_NUMBERING" => "N",
                                       "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                       "PAGER_SHOW_ALL" => "N",
                                       "PAGER_BASE_LINK_ENABLE" => "N",
                                       "LAZY_LOAD" => "N",
                                       "LOAD_ON_SCROLL" => "N",
                                       "SET_STATUS_404" => "N",
                                       "SHOW_404" => "N",
                                       "MESSAGE_404" => "",
                                       "COMPATIBLE_MODE" => "N",
                                       "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                                       "DETAIL_ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
                                       "DETAIL_OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
                                       "DETAIL_PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
                                       "DETAIL_OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
                                       "DETAIL_MAIN_BLOCK_PROPERTY_CODE" => $arParams["MAIN_BLOCK_PROPERTY_CODE"],
                                       "DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => $arParams["MAIN_BLOCK_OFFERS_PROPERTY_CODE"],
                                       "DETAIL_IMAGE_RESOLUTION" => $arParams["IMAGE_RESOLUTION"],
                                       "DETAIL_ADD_DETAIL_TO_SLIDER" => $arParams["ADD_DETAIL_TO_SLIDER"],
                                       "DETAIL_DETAIL_PICTURE_MODE" => $arParams["DETAIL_PICTURE_MODE"],
                                       "DETAIL_SHOW_SLIDER" => $arParams["SHOW_SLIDER"],
                                       "DETAIL_SLIDER_INTERVAL" => $arParams["SLIDER_INTERVAL"],
                                       "DETAIL_SLIDER_PROGRESS" => $arParams["SLIDER_PROGRESS"],
                                       "USE_GIFTS_DETAIL" => $arParams["USE_GIFTS_DETAIL"],
                                       "GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => $arParams["GIFTS_DETAIL_PAGE_ELEMENT_COUNT"],
                                       "GIFTS_DETAIL_HIDE_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_HIDE_BLOCK_TITLE"],
                                       "GIFTS_DETAIL_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_BLOCK_TITLE"],
                                       "GIFTS_DETAIL_TEXT_LABEL_GIFT" => $arParams["GIFTS_DETAIL_TEXT_LABEL_GIFT"],
                                       "GIFTS_MESS_BTN_BUY" => $arParams["~GIFTS_MESS_BTN_BUY"],
                                       "USE_STORE" => $arParams["USE_STORE"],
                                       "STORE_PATH" => $arParams["STORE_PATH"],
                                       "STORES" => $arParams["STORES"],
                                       "USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
                                       "USER_FIELDS" => $arParams["USER_FIELDS"],
                                       "FIELDS" => $arParams["FIELDS"],
                                       "MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
                                       "SHOW_EMPTY_STORE" => $arParams["SHOW_EMPTY_STORE"],
                                       "SHOW_GENERAL_STORE_INFORMATION" => $arParams["SHOW_GENERAL_STORE_INFORMATION"],
                                       "MAIN_TITLE" => $arParams["~MAIN_TITLE"],
                                       "SET_ITEMS_COUNT" => $arParams["SET_ITEMS_COUNT"],
                                       "REINIT_ADD_BUY_URL_TEMPLATE" => $arParams["REINIT_ADD_BUY_URL_TEMPLATE"],
                                       "OBJECTS_USE_REVIEW" => $arParams["OBJECTS_USE_REVIEW"],
                                       "OBJECTS_REVIEWS_IBLOCK_ID" => $arParams["OBJECTS_REVIEWS_IBLOCK_ID"],
                                       "CONTACTS_IBLOCK_ID" => $arParams["CONTACTS_IBLOCK_ID"],
                                       "CONTACTS_USE_REVIEW" => $arParams["CONTACTS_USE_REVIEW"],
                                       "CONTACTS_REVIEWS_IBLOCK_ID" => $arParams["CONTACTS_REVIEWS_IBLOCK_ID"],
                                       "QUICK_VIEW" => isset($arParams["POPUP_MODE"]) && $arParams["POPUP_MODE"] == "Y" ? "OFF" : $arSettings["QUICK_VIEW"]
                                   ),
                                   $component,
                                   array("HIDE_ICONS" => "Y")
                               );?>
                        </div>
                </div>
                <?}
                    unset($UF_RECOMENDED_PROD, $RecomCustomArrFilterItems, $resRecomendenCustom, $resDbRecCus);//Крашим всё?>
                <?}?>
					<?//  **ОПИСАНИЕ**  //?>
					<?if($showDescription && $disabledVlad) {?>
						<div class="col-xs-12 product-item-detail-description" itemprop="description">
							<div class="h2"><?=$arParams["MESS_DESCRIPTION_TAB"]?></div>
							<?=$arResult["DETAIL_TEXT"]?>
						</div>
					<?}?>
				</div>				
				<?
				//FREE_TAB
				if(!empty($arResult["PROPERTIES"]["FREE_TAB"]["VALUE"])) {?>
					<div class="product-item-detail-tab-content" data-entity="tab-container" data-value="free-tab">
						<div class="h2"><?=$arResult["PROPERTIES"]["FREE_TAB"]["NAME"]?></div>
						<?=$arResult["PROPERTIES"]["FREE_TAB"]["~VALUE"]["TEXT"];?>
					</div>
				<?}
				//FILES_DOCS
				if(!empty($arResult["PROPERTIES"]["FILES_DOCS"]["FULL_VALUE"])) {?>
					<div class="product-item-detail-tab-content" data-entity="tab-container" data-value="files-docs">
						<div class="h2"><?=$arResult["PROPERTIES"]["FILES_DOCS"]["NAME"]?></div>
						<div class="row product-item-detail-files-docs">
							<?foreach($arResult["PROPERTIES"]["FILES_DOCS"]["FULL_VALUE"] as $key => $arDoc) {?><!--
							 --><div class="col-xs-12 col-md-4">
									<a class="product-item-detail-files-docs-item" href="<?=$arDoc['SRC']?>" target="_blank">
										<div class="product-item-detail-files-docs-icon" data-type="<?=$arDoc['TYPE']?>"></div>
										<div class="product-item-detail-files-docs-block">
											<span class="product-item-detail-files-docs-name"><?=!empty($arDoc["DESCRIPTION"]) ? $arDoc["DESCRIPTION"] : $arDoc["NAME"]?></span>
											<span class="product-item-detail-files-docs-size"><?=Loc::getMessage("CT_BCE_CATALOG_SIZE").$arDoc["SIZE"]?></span>
										</div>
									</a>
								</div><!--
						 --><?}
							unset($key, $arDoc);?>
						</div>
					</div>
				<?}
				//STORES//
				if($arParams["USE_STORE"] == "Y" && (!$haveOffers || ($haveOffers && ($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST")))) {?>
					<div class="product-item-detail-tab-content" data-entity="tab-container" data-value="stores">
						<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", ".default",
							array(
								"ELEMENT_ID" => $arResult["ID"],
								"STORE_PATH" => $arParams["STORE_PATH"],
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"MAIN_TITLE" => $arParams["MAIN_TITLE"],
								"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
								"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
								"STORES" => $arParams["STORES"],
								"SHOW_EMPTY_STORE" => $arParams["SHOW_EMPTY_STORE"],
								"SHOW_GENERAL_STORE_INFORMATION" => $arParams["SHOW_GENERAL_STORE_INFORMATION"],
								"USER_FIELDS" => $arParams["USER_FIELDS"],
								"FIELDS" => $arParams["FIELDS"]
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);?>
					</div>
				<?}?>
			</div>
			<div class="col-xs-12 col-md-3<?=($isWideScreenMode ? ' col-ws-right' : '')?>">			
				<div class="product-item-detail-ghost-top"></div>				
				<div class="product-item-detail-pay-block">
					<?//SHORT_CARD//?>
					<div class="product-item-detail-short-card">
						<div class="product-item-detail-short-card-image">
							<img src="" data-entity="short-card-picture" />
						</div>
						<div class="product-item-detail-short-card-title"><?=$name?></div>
					</div>
					<?//PRICE//?>
					<div class="product-item-detail-info-container">					
						<div id="<?=$itemIds['PRICE_ID']?>">
							<?if($haveOffers && $arParams["OFFERS_VIEW"] != "PROPS" && $arParams["OFFERS_VIEW"] != "DROPDOWN_LIST") {?>
								<span class="product-item-detail-price-from"><?=Loc::getMessage("CT_BCE_CATALOG_PRICE_FROM")?></span>
								<span class="product-item-detail-price-current"><?=($arParams["OFFERS_VIEW"] == "LIST" && $price["SQ_M_PRICE"] > 0 ? $price["SQ_M_PRINT_PRICE"] : $price["PRINT_PRICE"])?></span>
								<?if($arParams["OFFERS_VIEW"] == "LIST") {?>
									<span class="product-item-detail-price-measure">/<?=($price["SQ_M_PRICE"] > 0 ? Loc::getMessage("CT_BCE_CATALOG_MEASURE_SQ_M") : $actualItem["ITEM_MEASURE"]["TITLE"])?></span>
								<?}
							} else {?>
								<span class="product-item-detail-price-not-set" data-entity="price-current-not-set"<?=($price["SQ_M_PRICE"] > 0 ? " style='display:none;'" : ($price["PRICE"] > 0 ? " style='display:none;'" : ""))?>><?=Loc::getMessage("CT_BCE_CATALOG_PRICE_NOT_SET")?></span>
								<span class="product-item-detail-price-current" data-entity="price-current"<?=($price["SQ_M_PRICE"] > 0 ? "" : ($price["PRICE"] > 0 ? "" : " style='display:none;'"))?>><?=($price["SQ_M_PRICE"] > 0 ? $price["SQ_M_PRINT_PRICE"] : $price["PRINT_PRICE"])?></span>
								<span class="product-item-detail-price-measure" data-entity="price-measure"<?=($price["SQ_M_PRICE"] > 0 ? "" : ($price["PRICE"] > 0 ? "" : " style='display:none;'"))?>>/<?=($price["SQ_M_PRICE"] > 0 ? Loc::getMessage("CT_BCE_CATALOG_MEASURE_SQ_M") : $actualItem["ITEM_MEASURE"]["TITLE"])?></span>
							<?}?>
                            <div style="float: right;">
                                <?if(!$arParams["DISABLE_DELAY"] && (!$object || ($object && $objectContacts)) && (!$haveOffers || ($haveOffers && ($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST")))) {?>
                                    <div class="product-item-detail-delay" id="<?=$itemIds['DELAY_LINK']?>" title="<?=$arParams['MESS_BTN_DELAY']?>"><i class="ex-icon-izbr-card-detail"></i></div>
                                <?}?>
                            </div>
						</div>
						<?if($arParams["SHOW_OLD_PRICE"] === "Y") {?>
							<div class="product-item-detail-price-old" id="<?=$itemIds['OLD_PRICE_ID']?>"<?=($showDiscount ? "" : " style='display:none;'")?>><?=($showDiscount ? ($price["SQ_M_BASE_PRICE"] > 0 ? $price["SQ_M_PRINT_BASE_PRICE"] : $price["PRINT_BASE_PRICE"]) : "")?></div>
							<div class="product-item-detail-price-economy" id="<?=$itemIds['DISCOUNT_PRICE_ID']?>"<?=($showDiscount ? "" : " style='display:none;'")?>><?=($showDiscount ? Loc::getMessage("CT_BCE_CATALOG_ECONOMY_INFO2", array("#ECONOMY#" => ($price["SQ_M_DISCOUNT"] > 0 ? $price["SQ_M_PRINT_DISCOUNT"] : $price["PRINT_DISCOUNT"]))) : "")?></div>
						<?}?>
					</div>
					<?//QUANTITY_LIMIT
					if($arParams["SHOW_MAX_QUANTITY"] !== "N"){
						if($haveOffers) {
							if($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") {?>
                                <span class="product-item-detail-quantity-val" id="<?=$itemIds['QUANTITY_PROGRESS']?>"></span>
								<!--<div class="product-item-detail-info-container" id="<?/*=$itemIds['QUANTITY_LIMIT']*/?>" style="display: none;">
									<div class="product-item-detail-quantity">
										<i class="icon-ok-b product-item-detail-quantity-icon"></i>
										<span class="product-item-detail-quantity-val">
											<?/*=$arParams["MESS_SHOW_MAX_QUANTITY"]."&nbsp;"*/?>
											<span data-entity="quantity-limit-value"></span>
										</span>
									</div>
								</div>
								<div class="product-item-detail-info-container" id="<?/*=$itemIds['QUANTITY_LIMIT_NOT_AVAILABLE']*/?>" style="display: none;">
									<div class="product-item-detail-quantity product-item-detail-quantity-not-avl">
										<i class="icon-close-b product-item-detail-quantity-icon"></i>
										<span class="product-item-detail-quantity-val"><?/*=$arParams["MESS_NOT_AVAILABLE"]*/?></span>
									</div>
								</div>-->
                                <div class="product-item-detail-button-basket oj-data-cont" id="oj-block" style="display: none;">
                                    <div class="oj-data"></div>
                                </div>
							<?} else {?>
								<div class="product-item-detail-info-container">
									<div class="product-item-detail-quantity<?=($arResult['CATALOG_QUANTITY_TRACE'] === 'N' || $arResult['CATALOG_CAN_BUY_ZERO'] === 'Y' || $arResult['OFFERS_QUANTITY'] > 0 ? '' : ' product-item-detail-quantity-not-avl')?>">
										<i class="icon-<?=($arResult['CATALOG_QUANTITY_TRACE'] === 'N' || $arResult['CATALOG_CAN_BUY_ZERO'] === 'Y' || $arResult['OFFERS_QUANTITY'] > 0 ? 'ok' : 'close')?>-b product-item-detail-quantity-icon"></i>
										<span class="product-item-detail-quantity-val">
											<?if($arResult["CATALOG_QUANTITY_TRACE"] === "N" || $arResult["CATALOG_CAN_BUY_ZERO"] === "Y" || $arResult["OFFERS_QUANTITY"] > 0) {
												echo $arParams["MESS_SHOW_MAX_QUANTITY"]."&nbsp;";
												if($arResult["CATALOG_QUANTITY_TRACE"] === "Y" && $arResult["CATALOG_CAN_BUY_ZERO"] === "N") {
													if($arParams["SHOW_MAX_QUANTITY"] === "M") {
														if($arResult["OFFERS_QUANTITY"] >= $arParams["RELATIVE_QUANTITY_FACTOR"]) {
															echo $arParams["MESS_RELATIVE_QUANTITY_MANY"];
														} else {
															echo $arParams["MESS_RELATIVE_QUANTITY_FEW"];
														}
													} else {
														echo $arResult["OFFERS_QUANTITY"];
													}
												}
											} else {
												echo $arParams["MESS_NOT_AVAILABLE"];
											}?>
										</span>
									</div>
								</div>
							<?}
						} else {?>
							<div class="product-item-detail-info-container" id="<?=$itemIds['QUANTITY_LIMIT']?>">
								<div class="product-item-detail-quantity<?=($actualItem['CAN_BUY'] ? '' : ' product-item-detail-quantity-not-avl')?>">
									<i class="icon-<?//=($actualItem['CAN_BUY'] ? 'ok' : 'close')?>-b product-item-detail-quantity-icon"></i>
									<span class="product-item-detail-quantity-val">
										<?if($actualItem["CAN_BUY"]) {
											if($measureRatio && (float)$actualItem["CATALOG_QUANTITY"] > 0 && $actualItem["CATALOG_QUANTITY_TRACE"] === "Y" && $actualItem["CATALOG_CAN_BUY_ZERO"] === "N") {
												if($arParams["SHOW_MAX_QUANTITY"] === "M") {
                                                    if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['EX_MESS_RELATIVE_QUANTITY_VERY_FEW'] &&
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio < $arParams['EX_MESS_RELATIVE_QUANTITY_FEW']
                                                    ){//Очень мало?>
                                                        <div class="own-progress"> <div class="own-pr active"> <span></span> </div><div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div></div>
                                                        <?echo "Очень мало";
                                                    }
                                                    if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['EX_MESS_RELATIVE_QUANTITY_FEW'] &&
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio < $arParams['EX_MESS_RELATIVE_QUANTITY_ENOUGH']
                                                    ){//Мало?>
                                                        <div class="own-progress"> <div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div><div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div></div>
                                                        <?echo "Мало";
                                                    }
                                                    if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['EX_MESS_RELATIVE_QUANTITY_ENOUGH'] &&
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio < $arParams['EX_MESS_RELATIVE_QUANTITY_MANY']
                                                    ){//Достаточно?>
                                                        <div class="own-progress"> <div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div><div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div></div>
                                                        <?echo "Достаточно";
                                                    }
                                                    if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['EX_MESS_RELATIVE_QUANTITY_MANY'] &&
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio < $arParams['EX_MESS_RELATIVE_QUANTITY_VERY_MANY']
                                                    ){//Много?>
                                                        <div class="own-progress"> <div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div><div class="own-pr"> <span></span> </div></div>
                                                        <?echo "Много";
                                                    }
                                                    if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio > $arParams['EX_MESS_RELATIVE_QUANTITY_VERY_MANY']
                                                    ){//Очень много?>
                                                        <div class="own-progress"> <div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div><div class="own-pr active"> <span></span> </div></div>
                                                        <? echo "Очень много";
                                                    }
												} else {
													echo $actualItem["CATALOG_QUANTITY"];
												}
											}
										} else {//Нет в наличии?>
                                            <div class="own-progress"> <div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div><div class="own-pr"> <span></span> </div></div>
                                            <? echo $arParams['MESS_NOT_AVAILABLE'];
                                        }?>
									</span>
								</div>
							</div>
						<?}
					}
					//PRICE_RANGES//
					if($arParams["USE_PRICE_COUNT"] && (!$haveOffers || ($haveOffers && ($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST")))) {
						$showRanges = !$haveOffers && count($actualItem["ITEM_QUANTITY_RANGES"]) > 1;
						$useRatio = $arParams["USE_RATIO_IN_RANGES"] === "Y";?>
						<div class="product-item-detail-info-container"<?=($showRanges ? "" : " style='display: none;'");?> data-entity="price-ranges-block">
							<div class="product-item-detail-properties-block" data-entity="price-ranges-body">
								<?if($showRanges) {
									foreach($actualItem["ITEM_QUANTITY_RANGES"] as $range) {
										if($range["HASH"] !== "ZERO-INF") {
											$itemPrice = false;
											foreach($arResult["ITEM_PRICES"] as $itemPrice) {
												if($itemPrice["QUANTITY_HASH"] === $range["HASH"]){
													break;
												}
											}
											if($itemPrice) {?>
												<div class="product-item-detail-properties">
													<div class="product-item-detail-properties-name">													
														<?if(is_infinite($range["SORT_TO"])) {
															echo Loc::getMessage("CT_BCE_CATALOG_RANGE_FROM", array("#FROM#" => $range["SORT_FROM"]." ".$actualItem["ITEM_MEASURE"]["TITLE"]));
														} else {
															echo $range["SORT_FROM"].($range["SORT_TO"] != $range["SORT_FROM"] ? " - ".$range["SORT_TO"] : "")." ".$actualItem["ITEM_MEASURE"]["TITLE"];
														}?>
													</div>
													<div class="product-item-detail-properties-val">
														<?=($useRatio ? $itemPrice["PRINT_RATIO_PRICE"] : $itemPrice["PRINT_PRICE"])?>
													</div>
												</div>
											<?}
											unset($itemPrice);
										}
									}
									unset($range);
								}?>
							</div>
						</div>
						<?unset($showRanges, $useRatio);
					}
					//SKU//
					if($haveOffers && !empty($arResult["OFFERS_PROP"])) {
						if($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") {?>
							<div class="product-item-detail-scu-container" id="<?=$itemIds['TREE_ID']?>">
						<?}
						foreach($arResult["SKU_PROPS"] as $skuProperty) {
							if(!isset($arResult["OFFERS_PROP"][$skuProperty["CODE"]]))
								continue;
							$propertyId = $skuProperty["ID"];
							$skuProps[] = array(
								"ID" => $propertyId,
								"SHOW_MODE" => $skuProperty["SHOW_MODE"],
								"VALUES" => $skuProperty["VALUES"],
								"VALUES_COUNT" => $skuProperty["VALUES_COUNT"]
							);
							if($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") {?>
								<div class="product-item-detail-info-container" data-entity="sku-line-block">
									<div class="product-item-detail-scu-title"><?=htmlspecialcharsEx($skuProperty["NAME"])?></div>
									<?if($arParams["OFFERS_VIEW"] == "PROPS") {?>
										<div class="product-item-detail-scu-block">
											<div class="product-item-detail-scu-list">
												<ul class="product-item-detail-scu-item-list">
													<?foreach($skuProperty["VALUES"] as &$value) {
														$value["NAME"] = htmlspecialcharsbx($value["NAME"]);
														if($skuProperty["SHOW_MODE"] === "PICT") {?>
															<li class="product-item-detail-scu-item-color" title="<?=$value['NAME']?>" data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>" style="<?=(!empty($value['CODE']) ? 'background-color: #'.$value['CODE'].';' : (!empty($value['PICT']) ? 'background-image: url('.$value['PICT']['SRC'].');' : ''));?>"></li>
														<?} else {?>
															<li class="product-item-detail-scu-item-text" title="<?=$value['NAME']?>" data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
																<?=$value["NAME"]?>
															</li>
														<?}
													}
													unset($value);?>
												</ul>											
											</div>
										</div>
									<?} else {?>
										<div class="product-item-detail-basket-props-block">
											<div class="product-item-detail-basket-props-drop-down" onclick="<?=$obName?>.showOfferBasketPropsDropDownPopup(this, '<?=$propertyId?>');">
												<div class="drop-down-text" data-entity="current-option">-</div>
												<div class="drop-down-arrow"><i class="icon-arrow-down"></i></div>
												<div class="drop-down-popup" data-entity="dropdownContent" style="display: none;">
													<ul>
														<?foreach($skuProperty["VALUES"] as $value) {?>
															<li data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>" onclick="<?=$obName?>.selectOfferBasketPropsDropDownPopupItem(this);"><span><?=$value["NAME"]?></span></li>
														<?}
														unset($value);?>
													</ul>
												</div>
											</div>
										</div>
									<?}?>
								</div>
							<?}
						}
						unset($skuProperty);
						if($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") {?>
							</div>
						<?}
					}
					//BASKET_PROPERTIES//
					if(!$haveOffers) {
						$emptyProductProperties = empty($arResult["PRODUCT_PROPERTIES"]);					
						if($arParams["ADD_PROPERTIES_TO_BASKET"] === "Y" && !$emptyProductProperties) {?>
							<div class="product-item-detail-info-container" id="<?=$itemIds['BASKET_PROP_DIV']?>">
								<?if(!empty($arResult["PRODUCT_PROPERTIES_FILL"])) {
									foreach($arResult["PRODUCT_PROPERTIES_FILL"] as $propId => $propInfo) {?>
										<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=htmlspecialcharsbx($propInfo['ID'])?>" />
										<?unset($arResult["PRODUCT_PROPERTIES"][$propId]);
									}
									unset($propId, $propInfo);
								}
								$emptyProductProperties = empty($arResult["PRODUCT_PROPERTIES"]);
								if(!$emptyProductProperties) {
									foreach($arResult["PRODUCT_PROPERTIES"] as $propId => $propInfo) {?>
										<div class="product-item-detail-basket-props-container">
											<div class="product-item-detail-basket-props-title"><?=$arResult["PROPERTIES"][$propId]["NAME"]?></div>
											<div class="product-item-detail-basket-props-block">
												<?if($arResult["PROPERTIES"][$propId]["PROPERTY_TYPE"] === "L" && $arResult["PROPERTIES"][$propId]["LIST_TYPE"] === "C") {?>
													<div class="product-item-detail-basket-props-input-radio">
														<?foreach($propInfo["VALUES"] as $valueId => $value) {?>
															<label>
																<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=$valueId?>"<?=($valueId == $propInfo["SELECTED"] ? " checked='checked'" : "");?> />
																<span class="check-container">
																	<span class="check"><i class="icon-ok-b"></i></span>
																</span>
																<span class="text" title="<?=$value?>"><?=$value?></span>
															</label>
														<?}
														unset($valueId, $value);?>
													</div>
												<?} else {?>
													<div class="product-item-detail-basket-props-drop-down" onclick="<?=$obName?>.showBasketPropsDropDownPopup(this, '<?=$propId?>');">
														<?$currId = $currVal = false;
														foreach($propInfo["VALUES"] as $valueId => $value) {
															if($valueId == $propInfo["SELECTED"]) {
																$currId = $valueId;
																$currVal = $value;
															}
														}
														unset($valueId, $value);?>
														<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=(!empty($currId) ? $currId : '');?>" />
														<div class="drop-down-text" data-entity="current-option"><?=(!empty($currVal) ? $currVal : "");?></div>
														<?unset($currVal, $currId);?>
														<div class="drop-down-arrow"><i class="icon-arrow-down"></i></div>
														<div class="drop-down-popup" data-entity="dropdownContent" style="display: none;">
															<ul>
																<?foreach($propInfo["VALUES"] as $valueId => $value) {?>
																	<li><span onclick="<?=$obName?>.selectBasketPropsDropDownPopupItem(this, '<?=$valueId?>');"><?=$value?></span></li>
																<?}
																unset($valueId, $value);?>
															</ul>
														</div>
													</div>
												<?}?>
											</div>
										</div>
									<?}
									unset($propId, $propInfo);
								}?>
							</div>
						<?}
						unset($emptyProductProperties);
					}
					//QUANTITY//
					if($arParams["USE_PRODUCT_QUANTITY"] && (!$object || ($object && $objectContacts)) && (!$haveOffers || ($haveOffers && ($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST")))) {?>
						<div class="product-item-detail-info-container<?=($marketplace['ozon'] || $marketplace['wildberries'] ? ' ex-marketplaces-quant' : '')?>" style="<?=(!$actualItem['CAN_BUY'] ? 'display: none;' : '')?>" data-entity="quantity-block">
							<?if(!empty($arResult["PROPERTIES"]["M2_COUNT"]["VALUE"])) {?>
								<div class="product-item-detail-amount"<?=($isMeasurePc || $isMeasureSqM ? "" : " style='display: none;'")?>>
									<a class="product-item-detail-amount-btn-minus" id="<?=$itemIds['PC_QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
									<input class="product-item-detail-amount-input" id="<?=$itemIds['PC_QUANTITY_ID']?>" type="tel" value="<?=$price['PC_MIN_QUANTITY']?>" />
									<a class="product-item-detail-amount-btn-plus" id="<?=$itemIds['PC_QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
									<div class="product-item-detail-amount-measure"><?=Loc::getMessage("CT_BCE_CATALOG_MEASURE_PC")?></div>
								</div>
								<div class="product-item-detail-amount"<?=($isMeasurePc || $isMeasureSqM ? "" : " style='display: none;'")?>>
									<a class="product-item-detail-amount-btn-minus" id="<?=$itemIds['SQ_M_QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
									<input class="product-item-detail-amount-input" id="<?=$itemIds['SQ_M_QUANTITY_ID']?>" type="tel" value="<?=$price['SQ_M_MIN_QUANTITY']?>" />
									<a class="product-item-detail-amount-btn-plus" id="<?=$itemIds['SQ_M_QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
									<div class="product-item-detail-amount-measure"><?=Loc::getMessage("CT_BCE_CATALOG_MEASURE_SQ_M")?></div>
								</div>
								<?if($haveOffers) {?>
									<div class="product-item-detail-amount"<?=($isMeasurePc || $isMeasureSqM ? " style='display: none;'" : "")?>>
										<a class="product-item-detail-amount-btn-minus" id="<?=$itemIds['QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
										<input class="product-item-detail-amount-input" id="<?=$itemIds['QUANTITY_ID']?>" type="tel" value="<?=$price['MIN_QUANTITY']?>" />
										<a class="product-item-detail-amount-btn-plus" id="<?=$itemIds['QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
										<div class="product-item-detail-amount-measure" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$actualItem["ITEM_MEASURE"]["TITLE"]?></div>
									</div>
								<?}?>
								<div class="product-item-detail-total-cost" id="<?=$itemIds['TOTAL_COST_ID']?>"<?=($price["MIN_QUANTITY"] != 1 || $price["PC_MIN_QUANTITY"] != 1 || $price["SQ_M_MIN_QUANTITY"] != 1 ? "" : " style='display:none;'")?>><?=Loc::getMessage("CT_BCE_CATALOG_TOTAL_COST")?><span data-entity="total-cost"><?=($price["MIN_QUANTITY"] != 1 || $price["PC_MIN_QUANTITY"] != 1 || $price["SQ_M_MIN_QUANTITY"] != 1 ? $price["PRINT_RATIO_PRICE"] : "")?></span></div>
							<?} else {?>
								<div class="product-item-detail-amount">								
									<a class="product-item-detail-amount-btn-minus" id="<?=$itemIds['QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
									<input class="product-item-detail-amount-input" id="<?=$itemIds['QUANTITY_ID']?>" type="tel" value="<?=$price['MIN_QUANTITY']?>" />
									<a class="product-item-detail-amount-btn-plus" id="<?=$itemIds['QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
									<div class="product-item-detail-amount-measure" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$actualItem["ITEM_MEASURE"]["TITLE"]?></div>
								</div>
								<div class="product-item-detail-total-cost" id="<?=$itemIds['TOTAL_COST_ID']?>"<?=($price["MIN_QUANTITY"] != 1 ? "" : " style='display:none;'")?>><?=Loc::getMessage("CT_BCE_CATALOG_TOTAL_COST")?><span data-entity="total-cost"><?=($price["MIN_QUANTITY"] != 1 ? $price["PRINT_RATIO_PRICE"] : "")?></span></div>
							<?}?>
						</div>
					<?}
					//BUTTONS//?>
					<div class="product-item-detail-button-container<?=$actualItem["CAN_BUY"] ? '' : ' bx-no-item'?><?=($marketplace['ozon'] || $marketplace['wildberries'] ? ' ex-marketplaces': '')?>" data-entity="main-button-container">
						<?if($haveOffers && $arParams["OFFERS_VIEW"] != "PROPS" && $arParams["OFFERS_VIEW"] != "DROPDOWN_LIST") {
							//SELECT_SKU//?>
							<button type="button" class="btn btn-default" id="<?=$itemIds['SELECT_SKU_LINK']?>"><span><?=Loc::getMessage("CT_BCE_CATALOG_SELECT_SKU_".$arParams["OFFERS_VIEW"])?></span></button>
							<?//LIST_URL//
							if(!empty($arResult["PROPERTIES"]["LIST_URL"]["VALUE"])) {
								foreach($arResult["PROPERTIES"]["LIST_URL"]["VALUE"] as $key => $val) {?>
									<a rel="nofollow" target="_blank" class="btn btn-default" href="<?=$val?>" role="button"><?=(!empty($arResult["PROPERTIES"]["LIST_URL"]["DESCRIPTION"][$key]) ? $arResult["PROPERTIES"]["LIST_URL"]["DESCRIPTION"][$key] : "")?></a>
								<?}
								unset($key, $val);
							}
							//BUY_INFO_MESSAGE//
							if(!empty($arSettings["BUY_INFO_MESSAGE"])) {?>
								<div class="product-item-detail-info-message"><?=$arSettings["BUY_INFO_MESSAGE"]?></div>
							<?}
						} else {
							//BUY//
                            if(!$arParams["DISABLE_BASKET"] && (!$object || ($object && $objectContacts))) {?>
                                <div id="<?=$itemIds['BASKET_ACTIONS_ID']?>">
                                    <?if($showAddBtn) {?>
                                        <div class="product-item-detail-buttons-block">
                                            <div class="product-item-detail-buttons">
                                                <?if($actualItem["CAN_BUY"]){ //Мой код Если товар есть в наличии?>
                                                    <div class="product-item-detail-button-basket">
                                                        <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){ $buyMarketplace = true?>
                                                            <a target="_blank" id="<?=$itemIds['BUTTON_OZON']?>" class="btn btn-ozon" href="<?=$arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']?>"><?=Loc::getMessage("CT_BCE_CATALOG_OZON")?></a>
                                                        <?}?>
                                                        <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){ $buyMarketplace = true?>
                                                            <a target="_blank" id="<?=$itemIds['BUTTON_WB']?>" class="btn btn-wildberries" href="<?=$arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']?>"><?=Loc::getMessage("CT_BCE_CATALOG_WILDBERRIES")?></a>
                                                        <?}?>
                                                        <button type="button" class="btn btn-buy" id="<?=$itemIds['ADD_BASKET_LINK']?>" style="display: <?=(!$partnersUrl ? '' : 'none')?>;"<?=($actualItem["CAN_BUY"] && $price["PRICE"] > 0 ? "" : " disabled='disabled'")?>>
                                                            <i class="icon-cart"></i>
                                                            <span><?=$buyMarketplace ? 'Купить на сайте' : $arParams["MESS_BTN_ADD_TO_BASKET"]?></span>
                                                        </button>
                                                    </div>
                                                    <div class="product-item-detail-button-too">
                                                        <div class="product-item-detail-total-cost" id="<?=$itemIds['TOTAL_COST_ID']?>"<?=($price["MIN_QUANTITY"] != 1 ? "" : " style='display:none;'")?>><?=Loc::getMessage("CT_BCE_CATALOG_TOTAL_COST")?>
                                                            <span data-entity="total-cost">
                                                                <?=($price["MIN_QUANTITY"] != 1 ? $price["PRINT_RATIO_PRICE"] : "")?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?}else{ //Мой код Если товара нет в наличии?>
                                                    <div class="product-item-detail-button-basket<?=!empty(ozhidaemayaData($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                                        $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                                        true, false)) && !$haveOffers ? ' oj-data-cont' : ''?>">
                                                        <?if(!$haveOffers){?>
                                                            <?if(!empty(ozhidaemayaData($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                                                $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                                                true, false))){ //Мой код если в товаре есть ожидаемая дата поступления?>
                                                                <div class="oj-data">
                                                                    <? echo ozhidaemayaData($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                                                        $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                                                        true, false); //Сверяем с текущей и выводим на странице
                                                                    ?>
                                                                </div>
                                                            <?}else{?>
                                                                <button type="button" class="btn btn-buy" id="<?=$itemIds['BUY_LINK']?>" style="display: <?=(!$partnersUrl ? '' : 'none')?>;"<?=($actualItem["CAN_BUY"] && $price["PRICE"] > 0 ? "" : " disabled='disabled'")?>>
                                                                    <i class="icon-cart"></i>
                                                                    <span><?=$arParams["MESS_BTN_BUY"]?></span>
                                                                </button>
                                                            <?}?>
                                                        <?}?>
                                                    </div>
                                                    <div class="product-item-detail-button-too">
                                                        <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){ $buyMarketplace = true?>
                                                            <a target="_blank" id="<?=$itemIds['BUTTON_OZON']?>" class="btn btn-ozon" href="<?=$arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']?>"><?=Loc::getMessage("CT_BCE_CATALOG_OZON")?></a>
                                                        <?}?>
                                                        <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){ $buyMarketplace = true?>
                                                            <a target="_blank" id="<?=$itemIds['BUTTON_WB']?>" class="btn btn-wildberries" href="<?=$arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']?>"><?=Loc::getMessage("CT_BCE_CATALOG_WILDBERRIES")?></a>
                                                        <?}?>
                                                        <?if($arParams["UNDER_ORDER"]) { ?>
                                                            <button type="button" class="btn btn-buy" id="<?=$itemIds['NOT_AVAILABLE_MESS']?>" style="display: <?=(!$actualItem['CAN_BUY'] ? '' : 'none')?>;">
                                                                <span>Предзаказ</span>
                                                            </button>
                                                        <?}?>
                                                    </div>
                                                <?}?>
                                            </div>
                                        </div>
                                    <?}
                                    if($showBuyBtn) {?>
                                        <div class="product-item-detail-buttons-block">
                                            <div class="product-item-detail-buttons">
                                                <?if($actualItem["CAN_BUY"]): //Мой код Если товар есть в наличии?>
                                                    <div class="product-item-detail-button-basket">
                                                        <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){ $buyMarketplace = true?>
                                                            <a target="_blank" id="<?=$itemIds['BUTTON_OZON']?>" class="btn btn-ozon" href="<?=$arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']?>"><?=Loc::getMessage("CT_BCE_CATALOG_OZON")?></a>
                                                        <?}?>
                                                        <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){ $buyMarketplace = true?>
                                                            <a target="_blank" id="<?=$itemIds['BUTTON_WB']?>" class="btn btn-wildberries" href="<?=$arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']?>"><?=Loc::getMessage("CT_BCE_CATALOG_WILDBERRIES")?></a>
                                                        <?}?>
                                                        <button type="button" class="btn btn-buy" id="<?=$itemIds['BUY_LINK']?>" style="display: <?=(!$partnersUrl ? '' : 'none')?>;"<?=($actualItem["CAN_BUY"] && $price["PRICE"] > 0 ? "" : " disabled='disabled'")?>>
                                                            <i class="icon-cart"></i>
                                                            <span><?=$buyMarketplace ? 'Купить на сайте' : $arParams["MESS_BTN_BUY"]?></span>
                                                        </button>
                                                    </div>
                                                    <div class="product-item-detail-button-too">
                                                        <div class="product-item-detail-total-cost" id="<?=$itemIds['TOTAL_COST_ID']?>"<?=($price["MIN_QUANTITY"] != 1 ? "" : " style='display:none;'")?>><?=Loc::getMessage("CT_BCE_CATALOG_TOTAL_COST")?>
                                                            <span data-entity="total-cost">
                                                                <?=($price["MIN_QUANTITY"] != 1 ? $price["PRINT_RATIO_PRICE"] : "")?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?else: //Мой код Если товара нет в наличии?>
                                                    <div class="product-item-detail-button-basket<?=!empty(ozhidaemayaData($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                                        $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                                        true, false)) && !$haveOffers ? ' oj-data-cont' : ''?>">
                                                        <?if(!$haveOffers){?>
                                                            <?if (!empty(ozhidaemayaData($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                                                $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                                                true, false))){ //Мой код если в товаре есть ожидаемая дата поступления?>
                                                                <div class="oj-data">
                                                                    <? echo ozhidaemayaData($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                                                        $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                                                        true, false); //Сверяем с текущей и выводим на странице
                                                                    ?>
                                                                </div>
                                                            <?}else{?>
                                                                <button type="button" class="btn btn-buy" id="<?=$itemIds['BUY_LINK']?>" style="display: <?=(!$partnersUrl ? '' : 'none')?>;"<?=($actualItem["CAN_BUY"] && $price["PRICE"] > 0 ? "" : " disabled='disabled'")?>>
                                                                    <i class="icon-cart"></i>
                                                                    <span><?=$arParams["MESS_BTN_BUY"]?></span>
                                                                </button>
                                                            <?}?>
                                                        <?}?>
                                                    </div>
                                                    <div class="product-item-detail-button-too">
                                                        <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){ $buyMarketplace = true?>
                                                            <a target="_blank" id="<?=$itemIds['BUTTON_OZON']?>" class="btn btn-ozon" href="<?=$arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']?>"><?=Loc::getMessage("CT_BCE_CATALOG_OZON")?></a>
                                                        <?}?>
                                                        <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){ $buyMarketplace = true?>
                                                            <a target="_blank" id="<?=$itemIds['BUTTON_WB']?>" class="btn btn-wildberries" href="<?=$arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']?>"><?=Loc::getMessage("CT_BCE_CATALOG_WILDBERRIES")?></a>
                                                        <?}?>
                                                        <?if($arParams["UNDER_ORDER"]) { ?>
                                                            <button type="button" class="btn btn-buy" id="<?=$itemIds['NOT_AVAILABLE_MESS']?>" style="display: <?=(!$actualItem['CAN_BUY'] ? '' : 'none')?>;">
                                                                <span>Предзаказ</span>
                                                            </button>
                                                        <?}?>
                                                    </div>
                                                <?endif;?>
                                            </div>
                                        </div>
                                    <?}?>
                                </div>
                            <?}
							//PARTNERS_LINK//?>
							<div id="<?=$itemIds['PARTNERS_ID']?>">
								<button type="button" class="btn btn-buy" id="<?=$itemIds['PARTNERS_LINK']?>" style="display: <?=($partnersUrl ? '' : 'none')?>;"<?=($actualItem["CAN_BUY"] && $price["PRICE"] > 0 ? "" : " disabled='disabled'")?>><i class="icon-cart"></i><span><?=$arParams["MESS_BTN_BUY"]?></span></button>
								<?if(!empty($arSettings["PARTNERS_INFO_MESSAGE"])) {?>
									<div class="product-item-detail-info-message" style="display: <?=($partnersUrl ? '' : 'none')?>;" data-entity="partners-message"><?=$arSettings["PARTNERS_INFO_MESSAGE"]?></div>
								<?}?>
							</div>
							<?//LIST_URL//
							if(!empty($arResult["PROPERTIES"]["LIST_URL"]["VALUE"])) {
								foreach($arResult["PROPERTIES"]["LIST_URL"]["VALUE"] as $key => $val) {?>
									<a rel="nofollow" target="_blank" class="btn btn-default" href="<?=$val?>" role="button"><?=(!empty($arResult["PROPERTIES"]["LIST_URL"]["DESCRIPTION"][$key]) ? $arResult["PROPERTIES"]["LIST_URL"]["DESCRIPTION"][$key] : "")?></a>
								<?}
								unset($key, $val);
							}
							//BUY_INFO_MESSAGE//
							if(!empty($arSettings["BUY_INFO_MESSAGE"])) {?>
								<div class="product-item-detail-info-message"><?=$arSettings["BUY_INFO_MESSAGE"]?></div>
							<?}
							//ASK_PRICE//
							if($arParams["ASK_PRICE"]) {?>
								<button type="button" class="btn btn-default" id="<?=$itemIds['ASK_PRICE_LINK']?>" style="display: <?=($actualItem['CAN_BUY'] && $price['PRICE'] <= 0 ? '' : 'none')?>;"><i class="icon-comment"></i><span><?=Loc::getMessage("CT_BCE_CATALOG_ASK_PRICE")?></span></button>
							<?}
							//SUBSCRIBE//
							if($showSubscribe) {?>
								<?$APPLICATION->IncludeComponent("bitrix:catalog.product.subscribe", "customSubscribeVlad",
									array(
										"PRODUCT_ID" => $actualItem["ID"],
										"BUTTON_ID" => $itemIds["SUBSCRIBE_LINK"],
										"BUTTON_CLASS" => "btn btn-default s_custom",
										"DEFAULT_DISPLAY" => !$actualItem["CAN_BUY"],
										"MESS_BTN_SUBSCRIBE" => $arParams["~MESS_BTN_SUBSCRIBE"]
									),
									$component,
									array("HIDE_ICONS" => "Y")
								);?>
							<?}
						}?>
					</div>
					<?//COMPARE//
					if($arParams["DISPLAY_COMPARE"] && (!$haveOffers || ($haveOffers && ($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST")))) {?>
						<div class="product-item-detail-compare">
							<label id="<?=$itemIds['COMPARE_LINK']?>">
								<input type="checkbox" data-entity="compare-checkbox">
								<span class="product-item-detail-compare-checkbox"><i class="icon-ok-b"></i></span>
								<span class="product-item-detail-compare-title" data-entity="compare-title"><?=$arParams["MESS_BTN_COMPARE"]?></span>
							</label>
						</div>
					<?}
					//DELIVERY_PAYMENT_METHODS//
					if($arParams["OFFERS_VIEW"] != "OBJECTS") {
						if(($arSettings["AUTO_DELIVERY_CALCULATION"] == "Y" && (!$haveOffers || ($haveOffers && $arParams["OFFERS_VIEW"] != "LIST"))) || ($object && (!empty($object["DELIVERY_METHODS"]) || !empty($object["PAYMENT_METHODS"]))) || !empty($arResult["CONTACTS"]["DELIVERY_METHODS"]) || !empty($arResult["CONTACTS"]["PAYMENT_METHODS"])) {?>
							<div class="product-item-detail-methods">

                                <div class="product-item-detail-method tool">
                                    <?if(!getPartner()){?>
                                        <div class="block-item-info">
                                            <a href="/about/opt_price/" data-name="wholesale" class="opt-price-app">Оптовая стоимость <?=round($price["PRICE"] - ($price["PRICE"] * 0.41))?> руб.</a>
                                            <a href="/about/delivery/" data-name="delivery" class="delivery-info-app">Доставим бесплатно от 5000 руб.</a>
                                        </div>
                                    <?}?>
                                </div>
								<?if($arSettings["AUTO_DELIVERY_CALCULATION"] == "Y" && (!$haveOffers || ($haveOffers && $arParams["OFFERS_VIEW"] != "LIST"))) {?>
									<div class="product-item-detail-geo-delivery" id="<?=$itemIds['GEO_DELIVERY_ID']?>">
										<div class="product-item-detail-geo-delivery-icon"><i class="icon-delivery"></i></div>
										<div class="product-item-detail-geo-delivery-info">
											<div class="product-item-detail-geo-delivery-city"><span data-entity="city"></span></div>
											<div class="product-item-detail-geo-delivery-from"><?=Loc::getMessage("CT_BCE_CATALOG_GEO_DELIVERY_DETERMINE")?></div>
										</div>
										<div class="product-item-detail-geo-delivery-price"><div class="product-item-detail-geo-delivery-loader"><div><span></span></div></div></div>
									</div>
								<?}?>
							</div>
						<?}
					}
					//QUICK_ORDER//
					if($arParams["QUICK_ORDER"] && (!$object || ($object && $objectContacts)) && (!$haveOffers || ($haveOffers && ($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST")))) {						
						$quickOrderQuantityId = $itemIds["QUANTITY_ID"];
						if(!$haveOffers && !empty($arResult["PROPERTIES"]["M2_COUNT"]["VALUE"])) {
							if($isMeasurePc)
								$quickOrderQuantityId = $itemIds["PC_QUANTITY_ID"];
							elseif($isMeasureSqM)
								$quickOrderQuantityId = $itemIds["SQ_M_QUANTITY_ID"];
						}?>
						<?$APPLICATION->IncludeComponent("altop:quick.order.enext", "",
							array(
								"MODE" => "PRODUCT",
								"PRODUCT_ID" => $actualItem["ID"],							
								"CONTAINER_ID" => $itemIds["QUICK_ORDER_LINK"],
								"CONTAINER_CLASS" => "product-item-detail-quick-order".($minOrderSum > 0 && $price["RATIO_PRICE"] < $minOrderSum ? " product-item-detail-quick-order-hidden" : ""),
								"DEFAULT_DISPLAY" => !$partnersUrl && $actualItem["CAN_BUY"] && $price["PRICE"] > 0,
								"QUANTITY_ID" => $quickOrderQuantityId,
								"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
								"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
								"CART_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
								"BASKET_PROPS_ID" => $itemIds["BASKET_PROP_DIV"],
								"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
								"BASKET_SKU_PROPS" => $arResult["OFFERS_PROP_CODES"],
								"OBJECT_ID" => $object ? $object["ID"] : "" 
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);?>
						<?unset($quickOrderQuantityId);
					}?>
				</div>
				<?//OBJECT//
				if($arParams["OFFERS_VIEW"] != "OBJECTS" && $object) {?>
					<div class="product-item-detail-object-container">
						<a target="_blank" class="product-item-detail-object" href="<?=$object['DETAIL_PAGE_URL']?>">
							<span class="product-item-detail-object-image">
								<?if(is_array($object["PREVIEW_PICTURE"])) {?>									
									<img src="<?=$object['PREVIEW_PICTURE']['SRC']?>" width="<?=$object['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$object['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$object['NAME']?>" />
								<?} else {?>
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo.png" width="222" height="222" alt="<?=$object['NAME']?>" title="<?=$object['NAME']?>" />
								<?}?>
							</span>
							<span class="product-item-detail-object-text"><?=$object["NAME"]?></span>
						</a>
						<div class="product-item-detail-object-contacts">
							<button type="button" class="product-item-detail-object-btn"><i class="icon-phone-call"></i></button>
						</div>
					</div>
				<?}?>
				<div class="product-item-detail-ghost-bottom"></div>
			</div>
			<?//ARTICLES//
			if(!empty($arResult["PROPERTIES"]["ARTICLES"]["VALUE"])) {?>
				<div class="col-xs-12 product-item-detail-tab-content" data-entity="tab-container" data-value="articles">
					<div class="h2"><?=$arResult["PROPERTIES"]["ARTICLES"]["NAME"]?></div>
					<div class="product-item-detail-articles">
						<?$GLOBALS["arArticlesFilter"] = array("ID" => $arResult["PROPERTIES"]["ARTICLES"]["VALUE"]);?>
						<?$APPLICATION->IncludeComponent("bitrix:news.list", "blog",
							array(
								"IBLOCK_TYPE" => "content",
								"IBLOCK_ID" => $arResult["PROPERTIES"]["ARTICLES"]["LINK_IBLOCK_ID"],
								"NEWS_COUNT" => $isWideScreenMode ? "8" : "6",
								"SORT_BY1" => "SORT",
								"SORT_ORDER1" => "ASC",
								"SORT_BY2" => "ACTIVE_FROM",
								"SORT_ORDER2" => "DESC",
								"FILTER_NAME" => "arArticlesFilter",
								"FIELD_CODE" => array(),
								"PROPERTY_CODE" => array(),
								"CHECK_DATES" => "Y",
								"DETAIL_URL" => "",
								"AJAX_MODE" => "",
								"AJAX_OPTION_SHADOW" => "",
								"AJAX_OPTION_JUMP" => "",
								"AJAX_OPTION_STYLE" => "",
								"AJAX_OPTION_HISTORY" => "",
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"CACHE_FILTER" => $arParams["CACHE_FILTER"],
								"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								"PREVIEW_TRUNCATE_LEN" => "",
								"ACTIVE_DATE_FORMAT" => "",
								"DISPLAY_PANEL" => "",
								"SET_TITLE" => "N",
								"SET_BROWSER_TITLE" => "N",
								"SET_META_KEYWORDS" => "N",
								"SET_META_DESCRIPTION" => "N",		
								"SET_STATUS_404" => "N",
								"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
								"ADD_SECTIONS_CHAIN" => "N",
								"HIDE_LINK_WHEN_NO_DETAIL" => "",
								"PARENT_SECTION" => "",
								"PARENT_SECTION_CODE" => "",
								"DISPLAY_NAME" => "",
								"DISPLAY_DATE" => "",
								"DISPLAY_TOP_PAGER" => "N",
								"DISPLAY_BOTTOM_PAGER" => "Y",
								"PAGER_SHOW_ALWAYS" => "",
								"PAGER_TEMPLATE" => "arrows",
								"PAGER_DESC_NUMBERING" => "",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
								"PAGER_SHOW_ALL" => "",
								"AJAX_OPTION_ADDITIONAL" => "",
								"DISPLAY_PAGINATION" => "N"
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);?>
					</div>
				</div>
			<?}
			//SECTIONS_MORE_PRODUCTS//
			if($moreProductsIds) {?>
				<div class="col-xs-12 product-item-detail-tab-content" data-entity="tab-container" data-value="more-products">
					<div class="h2"><?=$arResult["PROPERTIES"]["MORE_PRODUCTS"]["NAME"]?></div>					
					<?//SECTIONS//?>
					<div class="product-item-detail-more-products-sections-links" data-entity="moreProductsSectionsLinks">
						<div class="product-item-detail-more-products-section-link active" data-entity="moreProductsSectionsLink" data-section-id="0"><?=Loc::getMessage("CT_BCE_CATALOG_SECTIONS_ALL")?><span><?=count($moreProductsIds)?></span></div>
						<?if(!empty($arResult["PROPERTIES"]["MORE_PRODUCTS"]["SECTIONS"])) {
							foreach($arResult["PROPERTIES"]["MORE_PRODUCTS"]["SECTIONS"] as $arSection) {?>
								<div class="product-item-detail-more-products-section-link" data-entity="moreProductsSectionsLink" data-section-id="<?=$arSection['ID']?>"><?=$arSection["NAME"]?><span><?=$arSection["COUNT"]?></span></div>
							<?}
							unset($arSection);
						}?>
					</div>
					<?//MORE_PRODUCTS//?>
					<div class="product-item-detail-more-products">
						<?$GLOBALS["arMoreProductsFilter"] = array("ID" => $moreProductsIds);?>
						<?$APPLICATION->IncludeComponent("bitrix:catalog.section", ".default", 
							array(
								"COMPONENT_TEMPLATE" => ".default",
								"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
								"IBLOCK_ID" => $arParams["IBLOCK_ID"],
								"SECTION_ID" => "",
								"SECTION_CODE" => "",
								"SECTION_USER_FIELDS" => array(),
								"FILTER_NAME" => "arMoreProductsFilter",
								"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
								"SHOW_ALL_WO_SECTION" => "Y",
								"CUSTOM_FILTER" => "",
								"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
								"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
								"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
								"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
								"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
								"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
								"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
								"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
								"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
								"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
								"PAGE_ELEMENT_COUNT" => $isWideScreenMode ? "12" : "8",
								"LINE_ELEMENT_COUNT" => "4",
								"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
								"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
								"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
								"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
								"BACKGROUND_IMAGE" => "-",
								"PRODUCT_ROW_VARIANTS" => $isWideScreenMode ? "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]" : "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
								"PRODUCT_DISPLAY_MODE" => $arParams["PRODUCT_DISPLAY_MODE"],
								"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
								"PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
								"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
								"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
								"SHOW_MAX_QUANTITY" => $arParams["SHOW_MAX_QUANTITY"],
								"MESS_SHOW_MAX_QUANTITY" => $arParams["MESS_SHOW_MAX_QUANTITY"],
								"RELATIVE_QUANTITY_FACTOR" => $arParams["RELATIVE_QUANTITY_FACTOR"],
								"MESS_RELATIVE_QUANTITY_MANY" => $arParams["MESS_RELATIVE_QUANTITY_MANY"],
								"MESS_RELATIVE_QUANTITY_FEW" => $arParams["MESS_RELATIVE_QUANTITY_FEW"],
								"MESS_BTN_BUY" => $arParams["MESS_BTN_BUY"],
								"MESS_BTN_ADD_TO_BASKET" => $arParams["MESS_BTN_ADD_TO_BASKET"],
								"MESS_BTN_SUBSCRIBE" => $arParams["MESS_BTN_SUBSCRIBE"],
								"MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
								"MESS_NOT_AVAILABLE" => $arParams["MESS_NOT_AVAILABLE"],
								"RCM_TYPE" => "personal",
								"RCM_PROD_ID" => "",
								"SHOW_FROM_SECTION" => "N",
								"SECTION_URL" => "",
								"DETAIL_URL" => "",
								"SECTION_ID_VARIABLE" => "SECTION_ID",
								"SEF_MODE" => "N",
								"AJAX_MODE" => "N",
								"AJAX_OPTION_JUMP" => "N",
								"AJAX_OPTION_STYLE" => "Y",
								"AJAX_OPTION_HISTORY" => "N",
								"AJAX_OPTION_ADDITIONAL" => "",
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								"SET_TITLE" => "N",
								"SET_BROWSER_TITLE" => "N",
								"BROWSER_TITLE" => "-",
								"SET_META_KEYWORDS" => "N",
								"META_KEYWORDS" => "-",
								"SET_META_DESCRIPTION" => "N",
								"META_DESCRIPTION" => "-",
								"SET_LAST_MODIFIED" => "N",
								"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"] ? "Y" : "N",
								"ADD_SECTIONS_CHAIN" => "N",
								"CACHE_FILTER" => $arParams["CACHE_FILTER"],
								"USE_REVIEW" => $arParams["USE_REVIEW"],
								"REVIEWS_IBLOCK_TYPE" => $arParams["REVIEWS_IBLOCK_TYPE"],
								"REVIEWS_IBLOCK_ID" => $arParams["REVIEWS_IBLOCK_ID"],
								"REVIEWS_NEWS_COUNT" => $arParams["REVIEWS_NEWS_COUNT"],
								"REVIEWS_SORT_BY1" => $arParams["REVIEWS_SORT_BY1"],
								"REVIEWS_SORT_ORDER1" => $arParams["REVIEWS_SORT_ORDER1"],
								"REVIEWS_SORT_BY2" => $arParams["REVIEWS_SORT_BY2"],
								"REVIEWS_SORT_ORDER2" => $arParams["REVIEWS_SORT_ORDER2"],
								"REVIEWS_ACTIVE_DATE_FORMAT" => $arParams["REVIEWS_ACTIVE_DATE_FORMAT"],
								"REVIEWS_PROPERTY_CODE" => $arParams["REVIEWS_PROPERTY_CODE"],
								"MESS_REVIEWS_TAB" => $arParams["MESS_REVIEWS_TAB"],
								"ACTION_VARIABLE" => "action",
								"PRODUCT_ID_VARIABLE" => "id",								
								"PRICE_CODE" => $arParams["PRICE_CODE"],
								"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"] ? "Y" : "N",
								"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"] ? "Y" : "N",
								"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"] ? "Y" : "N",
								"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
								"CURRENCY_ID" => $arParams["CURRENCY_ID"],
								"BASKET_URL" => "/personal/basket.php",//$arParams["BASKET_URL"],
								"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"] ? "Y" : "N",
								"PRODUCT_QUANTITY_VARIABLE" => "quantity",
								"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
								"PRODUCT_PROPS_VARIABLE" => "prop",
								"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
								"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
								"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
								"ADD_TO_BASKET_ACTION" => $showBuyBtn ? "BUY" : "ADD",
								"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"] ? "Y" : "N",
								"COMPARE_PATH" => $arParams["COMPARE_PATH"],
								"MESS_BTN_COMPARE" => $arParams["MESS_BTN_COMPARE"],
								"COMPARE_NAME" => $arParams["COMPARE_NAME"],
								"USE_ENHANCED_ECOMMERCE" => "N",
								"PAGER_TEMPLATE" => "arrows",
								"DISPLAY_TOP_PAGER" => "N",
								"DISPLAY_BOTTOM_PAGER" => "N",
								"PAGER_TITLE" => "",
								"PAGER_SHOW_ALWAYS" => "N",
								"PAGER_DESC_NUMBERING" => "N",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL" => "N",
								"PAGER_BASE_LINK_ENABLE" => "N",
								"LAZY_LOAD" => "Y",
								"LOAD_ON_SCROLL" => "N",
								"SET_STATUS_404" => "N",
								"SHOW_404" => "N",
								"MESSAGE_404" => "",
								"COMPATIBLE_MODE" => "N",
								"DISABLE_INIT_JS_IN_COMPONENT" => "N",
								"DETAIL_ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
								"DETAIL_OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
								"DETAIL_PROPERTY_CODE" => $arParams["PROPERTY_CODE"],				
								"DETAIL_OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
								"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => $arParams["MAIN_BLOCK_PROPERTY_CODE"],
								"DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => $arParams["MAIN_BLOCK_OFFERS_PROPERTY_CODE"],	
								"DETAIL_IMAGE_RESOLUTION" => $arParams["IMAGE_RESOLUTION"],				
								"DETAIL_ADD_DETAIL_TO_SLIDER" => $arParams["ADD_DETAIL_TO_SLIDER"],
								"DETAIL_DETAIL_PICTURE_MODE" => $arParams["DETAIL_PICTURE_MODE"],
								"DETAIL_SHOW_SLIDER" => $arParams["SHOW_SLIDER"],
								"DETAIL_SLIDER_INTERVAL" => $arParams["SLIDER_INTERVAL"],
								"DETAIL_SLIDER_PROGRESS" => $arParams["SLIDER_PROGRESS"],
								"USE_GIFTS_DETAIL" => $arParams["USE_GIFTS_DETAIL"],
								"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => $arParams["GIFTS_DETAIL_PAGE_ELEMENT_COUNT"],
								"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_HIDE_BLOCK_TITLE"],
								"GIFTS_DETAIL_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_BLOCK_TITLE"],
								"GIFTS_DETAIL_TEXT_LABEL_GIFT" => $arParams["GIFTS_DETAIL_TEXT_LABEL_GIFT"],
								"GIFTS_MESS_BTN_BUY" => $arParams["~GIFTS_MESS_BTN_BUY"],
								"USE_STORE" => $arParams["USE_STORE"],
								"STORE_PATH" => $arParams["STORE_PATH"],
								"STORES" => $arParams["STORES"],
								"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
								"USER_FIELDS" => $arParams["USER_FIELDS"],
								"FIELDS" => $arParams["FIELDS"],
								"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
								"SHOW_EMPTY_STORE" => $arParams["SHOW_EMPTY_STORE"],
								"SHOW_GENERAL_STORE_INFORMATION" => $arParams["SHOW_GENERAL_STORE_INFORMATION"],
								"MAIN_TITLE" => $arParams["~MAIN_TITLE"],
								"SET_ITEMS_COUNT" => $arParams["SET_ITEMS_COUNT"],
								"REINIT_ADD_BUY_URL_TEMPLATE" => $arParams["REINIT_ADD_BUY_URL_TEMPLATE"],
								"OBJECTS_USE_REVIEW" => $arParams["OBJECTS_USE_REVIEW"],
								"OBJECTS_REVIEWS_IBLOCK_ID" => $arParams["OBJECTS_REVIEWS_IBLOCK_ID"],
								"CONTACTS_IBLOCK_ID" => $arParams["CONTACTS_IBLOCK_ID"],
								"CONTACTS_USE_REVIEW" => $arParams["CONTACTS_USE_REVIEW"],
								"CONTACTS_REVIEWS_IBLOCK_ID" => $arParams["CONTACTS_REVIEWS_IBLOCK_ID"],
								"QUICK_VIEW" => isset($arParams["POPUP_MODE"]) && $arParams["POPUP_MODE"] == "Y" ? "OFF" : $arSettings["QUICK_VIEW"]
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);?>
					</div>
				</div>
			<?}
			//ОТЗЫВЫ//
			if(isset($arResult["REVIEWS_COUNT"]) && $disabledVlad) {?>
				<div class="col-xs-12 product-item-detail-tab-content" data-entity="tab-container" data-value="reviews">
                    <div class="h2"><?=$arParams["MESS_REVIEWS_TAB"]?></div>
					<div class="product-item-detail-reviews">
						<?$GLOBALS["arReviewsFilter"] = array("PROPERTY_PRODUCT_ID" => $arResult["ID"]);?>
						<?$APPLICATION->IncludeComponent("bitrix:news.list", "reviews",
							array(
								"IBLOCK_TYPE" => $arParams["REVIEWS_IBLOCK_TYPE"],
								"IBLOCK_ID" => $arParams["REVIEWS_IBLOCK_ID"],
								"NEWS_COUNT" => $arParams["REVIEWS_NEWS_COUNT"],
								"SORT_BY1" => $arParams["REVIEWS_SORT_BY1"],
								"SORT_ORDER1" => $arParams["REVIEWS_SORT_ORDER1"],
								"SORT_BY2" => $arParams["REVIEWS_SORT_BY2"],
								"SORT_ORDER2" => $arParams["REVIEWS_SORT_ORDER2"],
								"FILTER_NAME" => "arReviewsFilter",
								"FIELD_CODE" => array(),
								"PROPERTY_CODE" => $arParams["REVIEWS_PROPERTY_CODE"],
								"CHECK_DATES" => "Y",
								"DETAIL_URL" => "",
								"AJAX_MODE" => "",
								"AJAX_OPTION_SHADOW" => "",
								"AJAX_OPTION_JUMP" => "",
								"AJAX_OPTION_STYLE" => "",
								"AJAX_OPTION_HISTORY" => "",
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"CACHE_FILTER" => $arParams["CACHE_FILTER"],
								"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								"PREVIEW_TRUNCATE_LEN" => "",
								"ACTIVE_DATE_FORMAT" => $arParams["REVIEWS_ACTIVE_DATE_FORMAT"],
								"DISPLAY_PANEL" => "",
								"SET_TITLE" => "N",
								"SET_BROWSER_TITLE" => "N",
								"SET_META_KEYWORDS" => "N",
								"SET_META_DESCRIPTION" => "N",
								"SET_STATUS_404" => "N",
								"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
								"ADD_SECTIONS_CHAIN" => "",
								"HIDE_LINK_WHEN_NO_DETAIL" => "",
								"PARENT_SECTION" => "",
								"PARENT_SECTION_CODE" => "",
								"DISPLAY_NAME" => "",
								"DISPLAY_DATE" => "",
								"DISPLAY_TOP_PAGER" => "N",
								"DISPLAY_BOTTOM_PAGER" => "Y",
								"PAGER_SHOW_ALWAYS" => "",
								"PAGER_TEMPLATE" => "arrows",
								"PAGER_DESC_NUMBERING" => "",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
								"PAGER_SHOW_ALL" => "",
								"AJAX_OPTION_ADDITIONAL" => ""
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);?>
					</div>
				</div>
			<?}?>
		</div>
	</div>
	<?//PREDICTION//
	if($arResult["CATALOG"] && (!$object || ($object && $objectContacts)) && (!$haveOffers || ($haveOffers && ($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST"))) && $actualItem["CAN_BUY"] && Bitrix\Main\ModuleManager::isModuleInstalled("sale")) {?>
		<?$APPLICATION->IncludeComponent("bitrix:sale.prediction.product.detail", ".default",
			array(
				"BUTTON_ID" => !$partnersUrl ? ($showBuyBtn ? $itemIds["BUY_LINK"] : $itemIds["ADD_BASKET_LINK"]) : $itemIds["PARTNERS_LINK"],
				"CUSTOM_SITE_ID" => isset($arParams["CUSTOM_SITE_ID"]) ? $arParams["CUSTOM_SITE_ID"] : null,
				"POTENTIAL_PRODUCT_TO_BUY" => array(
					"ID" => isset($arResult["ID"]) ? $arResult["ID"] : null,
					"MODULE" => isset($arResult["MODULE"]) ? $arResult["MODULE"] : "catalog",
					"PRODUCT_PROVIDER_CLASS" => isset($arResult["PRODUCT_PROVIDER_CLASS"]) ? $arResult["PRODUCT_PROVIDER_CLASS"] : "CCatalogProductProvider",
					"QUANTITY" => isset($arResult["QUANTITY"]) ? $arResult["QUANTITY"] : null,
					"IBLOCK_ID" => isset($arResult["IBLOCK_ID"]) ? $arResult["IBLOCK_ID"] : null,
					"PRIMARY_OFFER_ID" => isset($arResult["OFFERS"][0]["ID"]) ? $arResult["OFFERS"][0]["ID"] : null,
					"SECTION" => array(
						"ID" => isset($arResult["SECTION"]["ID"]) ? $arResult["SECTION"]["ID"] : null,
						"IBLOCK_ID" => isset($arResult["SECTION"]["IBLOCK_ID"]) ? $arResult["SECTION"]["IBLOCK_ID"] : null,
						"LEFT_MARGIN" => isset($arResult["SECTION"]["LEFT_MARGIN"]) ? $arResult["SECTION"]["LEFT_MARGIN"] : null,
						"RIGHT_MARGIN" => isset($arResult["SECTION"]["RIGHT_MARGIN"]) ? $arResult["SECTION"]["RIGHT_MARGIN"] : null,
					)
				)
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?>
	<?}
	//META//?>
	<meta itemprop="name" content="<?=$name?>" />
	<?if(!empty($arResult["PROPERTIES"]["BRAND"]["FULL_VALUE"])) {?>
		<meta itemprop="brand" content="<?=$arResult['PROPERTIES']['BRAND']['FULL_VALUE']['NAME']?>" />
	<?}?>
	<meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />
	<?if($haveOffers) {
		foreach($arResult["JS_OFFERS"] as $offer) {
			$currentOffersList = array();
			if(!empty($offer["TREE"]) && is_array($offer["TREE"])) {
				foreach($offer["TREE"] as $propName => $skuId) {
					$propId = (int)substr($propName, 5);
					foreach($skuProps as $prop) {
						if($prop["ID"] == $propId) {
							foreach($prop["VALUES"] as $propId => $propValue) {
								if($propId == $skuId) {
									$currentOffersList[] = $propValue["NAME"];
									break;
								}
							}
							unset($propId, $propValue);
						}
					}
					unset($prop);
				}
				unset($propName, $skuId);
			}
			$offerPrice = $offer["ITEM_PRICES"][$offer["ITEM_PRICE_SELECTED"]];?>
			<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="sku" content="<?=htmlspecialcharsbx(implode('/', $currentOffersList))?>" />
				<meta itemprop="price" content="<?=$offerPrice['RATIO_PRICE']?>" />
				<meta itemprop="priceCurrency" content="<?=$offerPrice['CURRENCY']?>" />
				<link itemprop="availability" href="http://schema.org/<?=($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
			</span>
		<?}
		unset($offerPrice, $currentOffersList, $offer);
	} else {?>
		<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?=$price['RATIO_PRICE']?>" />
			<meta itemprop="priceCurrency" content="<?=$price['CURRENCY']?>" />
			<link itemprop="availability" href="http://schema.org/<?=($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
		</span>
	<?}?>
</div>

<?if(isset($arParams["REINIT_ADD_BUY_URL_TEMPLATE"]) && $arParams["REINIT_ADD_BUY_URL_TEMPLATE"] == "Y") {
	$addUrlTemplate = $arResult["DETAIL_PAGE_URL"]."?".$arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#";
	$buyUrlTemplate = $arResult["DETAIL_PAGE_URL"]."?".$arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#";
} else {
	$addUrlTemplate = $arResult["~ADD_URL_TEMPLATE"];
	$buyUrlTemplate = $arResult["~BUY_URL_TEMPLATE"];
}

if($haveOffers) {
	$useRatio = $arParams["USE_RATIO_IN_RANGES"] === "Y";

	foreach($arResult["JS_OFFERS"] as $ind => &$jsOffer) {
		$fullOffer = $arResult["OFFERS"][$ind];
		$measureName = $fullOffer["ITEM_MEASURE"]["TITLE"];

		$strAllProps = "";
		$strMainProps = "";
		$strPriceRangesRatio = "";
		$strPriceRanges = "";

		if(($arParams["OFFERS_VIEW"] == "PROPS" || $arParams["OFFERS_VIEW"] == "DROPDOWN_LIST") && $arResult["SHOW_OFFERS_PROPS"]) {
			if(!empty($jsOffer["DISPLAY_PROPERTIES"])) {
				if($arSettings["TAB_PROPERTIES"] == "Y" && !empty($arResult["PROPS_GROUPS"])) {
					$strAllProps .= "
						<div class='product-item-detail-properties-group' data-entity='sku-props'>
							<div class='product-item-detail-properties-group-name'>".Loc::getMessage("CT_BCE_CATALOG_PROPS_GROUPS_OFFERS_NAME")."</div>
							<div class='product-item-detail-properties-group-val'></div>
						</div>
					";
				}
				foreach($jsOffer["DISPLAY_PROPERTIES"] as $property) {					
					$current = "
						<div class='product-item-detail-properties' data-entity='sku-props'>
							<div class='product-item-detail-properties-name'>".$property["NAME"]."</div>
							<div class='product-item-detail-properties-val'>".(is_array($property["VALUE"]) ? implode(" / ", $property["VALUE"]) : $property["VALUE"])."</div>
						</div>
					";
					if($arSettings["TAB_PROPERTIES"] == "Y") {
						if(!empty($arResult["PROPS_GROUPS"])) {
							$strAllProps .= "
								<div class='product-item-detail-properties-group-property' data-entity='sku-props'>
									<div class='product-item-detail-properties-group-property-name'>".$property["NAME"]."</div>
									<div class='product-item-detail-properties-group-property-val'>".(is_array($property["VALUE"]) ? implode(" / ", $property["VALUE"]) : $property["VALUE"])."</div>
								</div>
							";
						} else {
							$strAllProps .= $current;
						}
					}
					if(isset($arParams["MAIN_BLOCK_OFFERS_PROPERTY_CODE"][$property["CODE"]])) {
						$strMainProps .= $current;
					}
				}
				unset($current, $property);
			}
		}

		if($arParams["USE_PRICE_COUNT"] && count($jsOffer["ITEM_QUANTITY_RANGES"]) > 1) {
			foreach($jsOffer["ITEM_QUANTITY_RANGES"] as $range) {
				if($range["HASH"] !== "ZERO-INF") {
					$itemPrice = false;
					foreach($jsOffer["ITEM_PRICES"] as $itemPrice) {
						if($itemPrice["QUANTITY_HASH"] === $range["HASH"]) {
							break;
						}
					}
					if($itemPrice) {
						$strPriceRanges .= "<div class='product-item-detail-properties'><div class='product-item-detail-properties-name'>";
						if(is_infinite($range["SORT_TO"])) {
							$strPriceRanges .= Loc::getMessage("CT_BCE_CATALOG_RANGE_FROM", array("#FROM#" => $range["SORT_FROM"]." ".$measureName));
						} else {
							$strPriceRanges .= $range["SORT_FROM"].($range["SORT_TO"] != $range["SORT_FROM"] ? " - ".$range["SORT_TO"] : "")." ".$measureName;
						}
						$strPriceRanges .= "</div><div class='product-item-detail-properties-val'>".($useRatio ? $itemPrice["PRINT_RATIO_PRICE"] : $itemPrice["PRINT_PRICE"])."</div></div>";
					}
					unset($itemPrice);
				}
			}
			unset($range);
		}
		
		$jsOffer["ARTICLE"] = !empty($arResult["OFFERS"][$ind]["PROPERTIES"]["ARTNUMBER"]["VALUE"])
			? $arResult["OFFERS"][$ind]["PROPERTIES"]["ARTNUMBER"]["VALUE"]
			: "-";
		
		$offerObject = !empty($arResult["OFFERS"][$ind]["PROPERTIES"]["OBJECT"]["FULL_VALUE"]) ? $arResult["OFFERS"][$ind]["PROPERTIES"]["OBJECT"]["FULL_VALUE"] : false;
		$offerObjectContacts = $offerObject["PHONE_SMS"] || $offerObject["EMAIL_EMAIL"] ? true : false;
		if($offerObject) {
			$jsOffer["OBJECT"] = array(
				"ID" => $offerObject["ID"],
				"NAME" => $offerObject["NAME"],
				"ADDRESS" => $offerObject["ADDRESS"],
				"TIMEZONE" => $offerObject["TIMEZONE"],
				"WORKING_HOURS" => $offerObject["WORKING_HOURS"],		
				"PHONE" => $offerObject["PHONE"],						
				"EMAIL" => $offerObject["EMAIL"],
				"SKYPE" => $offerObject["SKYPE"],
				"CALLBACK_FORM" => $offerObjectContacts
			);
		}
		unset($offerObjectContacts, $offerObject);
		
		$jsOffer["PARTNERS_URL"] = !empty($arResult["OFFERS"][$ind]["PROPERTIES"]["PARTNERS_URL"]["VALUE"]) ? true : (!empty($arResult["PROPERTIES"]["PARTNERS_URL"]["VALUE"]) ? true : false);
		
		$jsOffer["DISPLAY_PROPERTIES"] = $strAllProps;
		$jsOffer["DISPLAY_PROPERTIES_MAIN_BLOCK"] = $strMainProps;
		$jsOffer["PRICE_RANGES_HTML"] = $strPriceRanges;
	}
	unset($strAllProps, $strMainProps, $strPriceRangesRatio, $strPriceRanges, $ind, $jsOffer, $useRatio);
	
	$jsParams = array(
		"CONFIG" => array(
			"USE_CATALOG" => $arResult["CATALOG"],			
			"SHOW_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
			"SHOW_PRICE" => true,
			"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"] === "Y",
			"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"] === "Y",
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
			"SHOW_SKU_PROPS" => $arResult["SHOW_OFFERS_PROPS"],
			"OFFER_GROUP" => $arResult["OFFER_GROUP"],
			"MAIN_PICTURE_MODE" => $arParams["DETAIL_PICTURE_MODE"],
			"ADD_TO_BASKET_ACTION" => $arParams["ADD_TO_BASKET_ACTION"],			
			"SHOW_MAX_QUANTITY" => $arParams["SHOW_MAX_QUANTITY"],
			"RELATIVE_QUANTITY_FACTOR" => $arParams["RELATIVE_QUANTITY_FACTOR"],
			"USE_SUBSCRIBE" => $showSubscribe,
			"MIN_ORDER_SUM" => $minOrderSum,
			"SHOW_SLIDER" => $arParams["SHOW_SLIDER"],
			"SLIDER_INTERVAL" => $arParams["SLIDER_INTERVAL"],
			"ALT" => $alt,
			"TITLE" => $title,
			"MAGNIFIER_ZOOM_PERCENT" => 100,
			"USE_ENHANCED_ECOMMERCE" => $arParams["USE_ENHANCED_ECOMMERCE"],
			"DATA_LAYER_NAME" => $arParams["DATA_LAYER_NAME"],
			"BRAND_PROPERTY" => !empty($arResult["DISPLAY_PROPERTIES"][$arParams["BRAND_PROPERTY"]])
				? $arResult["DISPLAY_PROPERTIES"][$arParams["BRAND_PROPERTY"]]["DISPLAY_VALUE"]
				: null
		),
		"PRODUCT_TYPE" => $arResult["CATALOG_TYPE"],
		"OFFERS_VIEW" => $arParams["OFFERS_VIEW"],
		"VISUAL" => $itemIds,
		"DEFAULT_PICTURE" => array(
			"PREVIEW_PICTURE" => $arResult["DEFAULT_PICTURE"],
			"DETAIL_PICTURE" => $arResult["DEFAULT_PICTURE"]
		),
		"PRODUCT" => array(
			"ID" => $arResult["ID"],
			"IBLOCK_ID" => $arResult["IBLOCK_ID"],
			"ACTIVE" => $arResult["ACTIVE"],
			"NAME" => $arResult["~NAME"],
			"CATEGORY" => $arResult["CATEGORY_PATH"],
            "OZON" => $marketplace['ozon'] ? $arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE'] : false,
            "WILDBERRRIES" => $marketplace['wildberries'] ? $arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE'] : false
		),
		"BASKET" => array(
			"QUANTITY" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"SKU_PROPS" => $arResult["OFFERS_PROP_CODES"],
			"ADD_URL_TEMPLATE" => $addUrlTemplate,
			"BUY_URL_TEMPLATE" => $buyUrlTemplate
		),
		"OFFERS" => $arResult["JS_OFFERS"],
		"OFFER_SELECTED" => $arResult["OFFERS_SELECTED"],
		"TREE_PROPS" => $skuProps
	);

} else {
	$jsParams = array(
		"CONFIG" => array(
			"USE_CATALOG" => $arResult["CATALOG"],			
			"SHOW_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
			"SHOW_PRICE" => !empty($arResult["ITEM_PRICES"]),
			"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"] === "Y",
			"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"] === "Y",
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
			"MAIN_PICTURE_MODE" => $arParams["DETAIL_PICTURE_MODE"],
			"ADD_TO_BASKET_ACTION" => $arParams["ADD_TO_BASKET_ACTION"],			
			"SHOW_MAX_QUANTITY" => $arParams["SHOW_MAX_QUANTITY"],
			"RELATIVE_QUANTITY_FACTOR" => $arParams["RELATIVE_QUANTITY_FACTOR"],
			"USE_SUBSCRIBE" => $showSubscribe,
			"MIN_ORDER_SUM" => $minOrderSum,
			"SHOW_SLIDER" => $arParams["SHOW_SLIDER"],
			"SLIDER_INTERVAL" => $arParams["SLIDER_INTERVAL"],
			"ALT" => $alt,
			"TITLE" => $title,
			"MAGNIFIER_ZOOM_PERCENT" => 100,
			"USE_ENHANCED_ECOMMERCE" => $arParams["USE_ENHANCED_ECOMMERCE"],
			"DATA_LAYER_NAME" => $arParams["DATA_LAYER_NAME"],
			"BRAND_PROPERTY" => !empty($arResult["DISPLAY_PROPERTIES"][$arParams["BRAND_PROPERTY"]])
				? $arResult["DISPLAY_PROPERTIES"][$arParams["BRAND_PROPERTY"]]["DISPLAY_VALUE"]
				: null
		),
		"VISUAL" => $itemIds,
		"PRODUCT_TYPE" => $arResult["CATALOG_TYPE"],
		"PRODUCT" => array(
			"ID" => $arResult["ID"],
			"IBLOCK_ID" => $arResult["IBLOCK_ID"],
			"ACTIVE" => $arResult["ACTIVE"],
			"PICT" => is_array($arResult["DETAIL_PICTURE"]) ? $arResult["DETAIL_PICTURE"] : $arResult["DEFAULT_PICTURE"],
			"NAME" => $arResult["~NAME"],
			"SUBSCRIPTION" => true,
			"ITEM_PRICE_MODE" => $arResult["ITEM_PRICE_MODE"],
			"ITEM_PRICES" => $arResult["ITEM_PRICES"],
			"ITEM_PRICE_SELECTED" => $arResult["ITEM_PRICE_SELECTED"],
			"ITEM_QUANTITY_RANGES" => $arResult["ITEM_QUANTITY_RANGES"],
			"ITEM_QUANTITY_RANGE_SELECTED" => $arResult["ITEM_QUANTITY_RANGE_SELECTED"],
			"ITEM_MEASURE_RATIOS" => $arResult["ITEM_MEASURE_RATIOS"],
			"ITEM_MEASURE_RATIO_SELECTED" => $arResult["ITEM_MEASURE_RATIO_SELECTED"],
			"ITEM_MEASURE" => $arResult["ITEM_MEASURE"],
			"SLIDER_COUNT" => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM'] ? $arResult["MORE_PHOTO_COUNT"] + 1 : $arResult["MORE_PHOTO_COUNT"],
			"SLIDER" => $arResult["MORE_PHOTO"],
			"CAN_BUY" => $arResult["CAN_BUY"],
			"CHECK_QUANTITY" => $arResult["CHECK_QUANTITY"],
			"QUANTITY_FLOAT" => is_float($measureRatio),
			"MAX_QUANTITY" => $arResult["CATALOG_QUANTITY"],
			"STEP_QUANTITY" => $measureRatio,
			"CATEGORY" => $arResult["CATEGORY_PATH"],
            "OZON" => $marketplace['ozon'] ? $arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE'] : false,
            "WILDBERRRIES" => $marketplace['wildberries'] ? $arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE'] : false
		),
		"BASKET" => array(			
			"QUANTITY" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PROPS" => $arParams["PRODUCT_PROPS_VARIABLE"],			
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ADD_URL_TEMPLATE" => $addUrlTemplate,
			"BUY_URL_TEMPLATE" => $buyUrlTemplate
		)
	);
	
	if(!empty($arResult["PROPERTIES"]["M2_COUNT"]["VALUE"])) {		
		if($isMeasurePc) {
			$jsParams["PRODUCT"]["PC_MAX_QUANTITY"] = $arResult["CATALOG_QUANTITY"];
			$jsParams["PRODUCT"]["PC_STEP_QUANTITY"] = $measureRatio;

			$jsParams["PRODUCT"]["SQ_M_MAX_QUANTITY"] = round($arResult["CATALOG_QUANTITY"] / str_replace(",", ".", $arResult["PROPERTIES"]["M2_COUNT"]["VALUE"]), 2);			
			$jsParams["PRODUCT"]["SQ_M_STEP_QUANTITY"] = round($measureRatio / str_replace(",", ".", $arResult["PROPERTIES"]["M2_COUNT"]["VALUE"]), 2);
		} elseif($isMeasureSqM) {
			$jsParams["PRODUCT"]["PC_MAX_QUANTITY"] = floor($arResult["CATALOG_QUANTITY"] / $measureRatio);			
			$jsParams["PRODUCT"]["PC_STEP_QUANTITY"] = 1;

			$jsParams["PRODUCT"]["SQ_M_MAX_QUANTITY"] = $arResult["CATALOG_QUANTITY"];
			$jsParams["PRODUCT"]["SQ_M_STEP_QUANTITY"] = $measureRatio;
		}
	}
}

if($arParams["DISPLAY_COMPARE"]) {
	$jsParams["COMPARE"] = array(
		"COMPARE_NAME" => $arParams["COMPARE_NAME"],
		"COMPARE_PATH" => $arParams["COMPARE_PATH"],
		"COMPARE_URL_TEMPLATE" => $arResult["~COMPARE_URL_TEMPLATE"],
		"COMPARE_DELETE_URL_TEMPLATE" => $arResult["~COMPARE_DELETE_URL_TEMPLATE"]
	);
}

if($object) {
	$jsParams["OBJECT"] = array(
		"ID" => $object["ID"],
		"NAME" => $object["NAME"],
		"ADDRESS" => $object["ADDRESS"],
		"TIMEZONE" => $object["TIMEZONE"],
		"WORKING_HOURS" => $object["WORKING_HOURS"],		
		"PHONE" => $object["PHONE"],						
		"EMAIL" => $object["EMAIL"],
		"SKYPE" => $object["SKYPE"],
		"CALLBACK_FORM" => $objectContacts
	);
}

$jsParams['MARKETPLACES'] = $marketplace;
$jsParams['PRODUCT']['OZON'] = $marketplace['ozon'] ? $arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE'] : false;
$jsParams['PRODUCT']['WILDBERRRIES'] = $marketplace['wildberries'] ? $arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE'] : false;
$jsParams['MOBILE'] = $mobile;

if($arParams['SHOW_MAX_QUANTITY'] === 'M'){ //Мой код передаём параметры много мало в JS для обработки в характеристиках
    $jsParams['EX_QUANT_PROGRESS'] = array(
        "EX_MESS_RELATIVE_QUANTITY_VERY_FEW" => $arParams['EX_MESS_RELATIVE_QUANTITY_VERY_FEW'],
        "EX_MESS_RELATIVE_QUANTITY_FEW" => $arParams['EX_MESS_RELATIVE_QUANTITY_FEW'],
        "EX_MESS_RELATIVE_QUANTITY_ENOUGH" => $arParams['EX_MESS_RELATIVE_QUANTITY_ENOUGH'],
        "EX_MESS_RELATIVE_QUANTITY_MANY" => $arParams['EX_MESS_RELATIVE_QUANTITY_MANY'],
        "EX_MESS_RELATIVE_QUANTITY_VERY_MANY" => $arParams['EX_MESS_RELATIVE_QUANTITY_VERY_MANY']
    );
}

$signer = new Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arResult["ORIGINAL_PARAMETERS"])), "catalog.element");

if($moreProductsIds) {
	$signedMoreProductsIds = $signer->sign(base64_encode(serialize($moreProductsIds)), "catalog.element");
	$jsParams["MORE_PRODUCTS"] = array(
		"PRODUCTS_IDS" => $signedMoreProductsIds
	);
}?>

<?if($arParams['QUICK_POPUP_VLAD']){?>
    <script type="text/javascript">
        var tabsContainerEl = document.body.querySelector('[data-entity="main-element-tabs_quick"]'),
            tabsTabsEl = !!tabsContainerEl && tabsContainerEl.querySelector('.tabs-el__tabs_quick'),
            tabsEl = !!tabsContainerEl && tabsContainerEl.querySelectorAll('[data-entity="tab-el_quick"]'),
            tabsContentContainerEl = document.body.querySelector('[data-entity="main-tabs-content-element_quick"]'),
            tabValueEl, targetTabEl,
            haveActiveEl = false, iEl;
        if(!!tabsTabsEl) {
            BX.loadScript(BX.message('SITE_TEMPLATE_PATH') + '/js/owlCarousel/owl.carousel.min.js', function() {
                BX.addClass(tabsTabsEl, 'owl-carousel');
                $(tabsTabsEl).owlCarousel({
                    autoWidth: true,
                    nav: true,
                    navText: ['<i class=\"icon-arrow-left\"></i>', '<i class=\"icon-arrow-right\"></i>'],
                    navContainer: '.tabs-el__scroll',
                    dots: false,
                });
                if(!!tabsEl) {
                    for(var iEl in tabsEl) {
                        if(tabsEl.hasOwnProperty(iEl) && BX.type.isDomNode(tabsEl[iEl])) {
                            tabValueEl = tabsEl[iEl].getAttribute('data-value');
                            if(tabValueEl) {
                                targetTabEl = tabsContentContainerEl.querySelector('[data-value="' + tabValueEl + '"]');
                                if(BX.type.isDomNode(targetTabEl)) {
                                    if(!haveActiveEl) {
                                        BX.addClass(tabsEl[iEl], 'active');
                                        BX.show(targetTabEl);
                                        haveActiveEl = true;
                                    } else {
                                        BX.removeClass(tabsEl[iEl], 'active');
                                        BX.hide(targetTabEl);
                                    }
                                    BX.bind(tabsEl[iEl], 'click', function(event) {
                                        BX.PreventDefault(event);

                                        var targetTabValueEl = this.getAttribute('data-value'),
                                            j, k;

                                        if(!BX.hasClass(this, 'active') && targetTabValueEl) {
                                            var tabsContentEl = tabsContentContainerEl.querySelectorAll('[data-entity="tab-el-content_quick"]');
                                            if(!!tabsContentEl) {
                                                for(var j in tabsContentEl) {
                                                    if(tabsContentEl.hasOwnProperty(j) && BX.type.isDomNode(tabsContentEl[j])) {
                                                        if(tabsContentEl[j].getAttribute('data-value') == targetTabValueEl) {
                                                            BX.show(tabsContentEl[j]);
                                                        } else {
                                                            BX.hide(tabsContentEl[j]);
                                                        }
                                                    }
                                                }
                                            }
                                            for(k in tabsEl) {
                                                if(tabsEl.hasOwnProperty(k) && BX.type.isDomNode(tabsEl[k])) {
                                                    if(tabsEl[k].getAttribute('data-value') == targetTabValueEl) {
                                                        BX.addClass(tabsEl[k], 'active');
                                                    } else {
                                                        BX.removeClass(tabsEl[k], 'active');
                                                    }
                                                }
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
<?}?>

<script type="text/javascript">

    $("#_MESS").keypress(function (){
        if($("#_MAIL").val() == '' || $("#_NAME").val() == '' || $("#_MESS").val() == '')
            $('#submit_q_form').attr('disabled', true);
        else
            $('#submit_q_form').attr('disabled', false);
    });

    $("#_MESS").blur(function (){
        if($("#_MAIL").val() == '' || $("#_NAME").val() == '' || $("#_MESS").val() == '')
            $('#submit_q_form').attr('disabled', true);
        else
            $('#submit_q_form').attr('disabled', false);
    });

    $("#_EMAIL").blur(function(){
        let patternEmail = "^[a-z0-9_-]+@[a-z0-9-]+\\.([a-z]{1,6}\\.)?[a-z]{2,6}$",
            mail = $("#_EMAIL"),
            mailTrim = $.trim(mail.val());
        if(mail.val() != ''){
            if(mailTrim.search(patternEmail) == 0){
                if($("#_NAME").val() == '' || $("#_MESS").val() == '')
                    $('#submit_q_form').attr('disabled', true);
                else
                    $('#submit_q_form').attr('disabled', false);
                mail.css("border-color", "green");
            }else{
                mail.css("border-color", "red");
                $('#submit_q_form').attr('disabled', true);
            }
        }else{
            mail.css("border-color", "red");
            $('#submit_q_form').attr('disabled', true);
        }
    });

    $("#_NAME").blur(function(){
        let patternName = "^([а-яёА-Я]+|[A-Za-z]+)$",
            name = $("#_NAME"),
            nameTrim = $.trim(name.val());
        if(name.val() != ''){
            if(nameTrim.search(patternName) == 0){
                if($("#_EMAIL").val() == '' || $("#_MESS").val() == '')
                    $('#submit_q_form').attr('disabled', true);
                else
                    $('#submit_q_form').attr('disabled', false);
                name.css("border-color", "green");
            }else{
                name.css("border-color", "red");
                $('#submit_q_form').attr('disabled', true);
            }
        }else{
            name.css("border-color", "red");
            $('#submit_q_form').attr('disabled', true);
        }
    });

    $(".mess-by-form>.slide-panel__close>i.icon-close").click(function() {
        let block = document.getElementById("mess-by-form"),
        name = $("#_NAME"), email = $("#_EMAIL"), mess = $("#_MESS");
        name.val(''); email.val(''); mess.val('');
        name.css("border-color", "#d0e0e3");
        email.css("border-color", "#d0e0e3");
        $('#submit_q_form').attr('disabled', true);
        block.style.height = "0";
    });

    function questionFormAjax() {

        let item = $("#_ITEM").val(),
            name = $.trim($("#_NAME").val()),
            email = $.trim($("#_EMAIL").val()),
            mess = $.trim($("#_MESS").val()),
            block = document.getElementById("mess-by-form");

        BX.ajax({
            url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
            method: 'POST',
            dataType: 'json',
            timeout: 30,
            async: true,
            processData: false,
            scriptsRunFirst: false,
            emulateOnload: false,
            start: true,
            cache: false,
            data: {
                action: 'questionForm',
                item: item,
                name: name,
                email: email,
                mess: mess
            },
            onsuccess: function(data) {
                $('#submit_q_form').attr('disabled', true);
                if(document.body.clientWidth > 991)
                    block.style.height = "226px";
                else
                    block.style.height = "282px";
            },
            onfailure: function(data){
                console.log(data);
            }
        });
    }
</script>
<script type="text/javascript">
	BX.message({
		CATALOG_ELEMENT_SQ_M_MESSAGE: '<?=GetMessageJS("CT_BCE_CATALOG_MEASURE_SQ_M")?>',
		CATALOG_ELEMENT_ECONOMY_INFO_MESSAGE: '<?=GetMessageJS("CT_BCE_CATALOG_ECONOMY_INFO2")?>',
		CATALOG_ELEMENT_BASKET_URL: '<?=$arParams["BASKET_URL"]?>',
		CATALOG_ELEMENT_ADD_BASKET_MESSAGE: '<?=($showBuyBtn ? $arParams["MESS_BTN_BUY"] : $arParams["MESS_BTN_ADD_TO_BASKET"])?>',
		CATALOG_ELEMENT_ADD_BASKET_OK_MESSAGE: '<?=GetMessageJS("CT_BCE_CATALOG_ADD_OK")?>',		
		CATALOG_ELEMENT_DELAY_MESSAGE: '<?=$arParams["MESS_BTN_DELAY"]?>',
		CATALOG_ELEMENT_DELAY_OK_MESSAGE: '<?=GetMessageJS("CT_BCE_CATALOG_DELAY_OK")?>',		
		CATALOG_ELEMENT_RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams["MESS_RELATIVE_QUANTITY_MANY"])?>',
		CATALOG_ELEMENT_RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams["MESS_RELATIVE_QUANTITY_FEW"])?>',
		CATALOG_ELEMENT_COMPARE_MESSAGE: '<?=$arParams["MESS_BTN_COMPARE"]?>',
		CATALOG_ELEMENT_COMPARE_OK_MESSAGE: '<?=GetMessageJS("CT_BCE_CATALOG_COMPARE_OK")?>',				
		CATALOG_ELEMENT_GEO_DELIVERY_UNDEFINED: '<?=GetMessageJS("CT_BCE_CATALOG_GEO_DELIVERY_UNDEFINED")?>',
		CATALOG_ELEMENT_GEO_DELIVERY_FROM: '<?=GetMessageJS("CT_BCE_CATALOG_GEO_DELIVERY_FROM")?>',
		CATALOG_ELEMENT_GEO_DELIVERY_LOCATION: '<?=GetMessageJS("CT_BCE_CATALOG_GEO_DELIVERY_LOCATION")?>',
		CATALOG_ELEMENT_GEO_DELIVERY_SLIDE_PANEL_TITLE: '<?=GetMessageJS("CT_BCE_CATALOG_GEO_DELIVERY_SLIDE_PANEL_TITLE")?>',		
		CATALOG_ELEMENT_OBJECT_TODAY: '<?=GetMessageJS("CT_BCE_CATALOG_OBJECT_TODAY")?>',
		CATALOG_ELEMENT_OBJECT_24_HOURS: '<?=GetMessageJS("CT_BCE_CATALOG_OBJECT_24_HOURS")?>',
		CATALOG_ELEMENT_OBJECT_OFF: '<?=GetMessageJS("CT_BCE_CATALOG_OBJECT_OFF")?>',
		CATALOG_ELEMENT_OBJECT_BREAK: '<?=GetMessageJS("CT_BCE_CATALOG_OBJECT_BREAK")?>',
		CATALOG_ELEMENT_OBJECT_LOADING: '<?=GetMessageJS("CT_BCE_CATALOG_OBJECT_LOADING");?>',
		CATALOG_ELEMENT_TEMPLATE_PATH: '<?=$templateFolder?>',
		CATALOG_ELEMENT_PARAMETERS: '<?=CUtil::JSEscape($signedParams)?>'
	});
	var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>

<?unset($actualItem, $itemIds, $jsParams);