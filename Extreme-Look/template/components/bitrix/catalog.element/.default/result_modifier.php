<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Type\Collection,	
	Bitrix\Catalog\Product\Price,
	Bitrix\Catalog\ProductTable;

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$arSettings = CEnext::GetFrontParametrsValues(SITE_ID);

//OFFERS_VIEW//
$arParams["OFFERS_VIEW"] = $arSettings["OFFERS_VIEW"];

if(!empty($arResult["OFFERS"])) {
	$arResult["OFFERS_OBJECTS"] = false;
	foreach($arResult["OFFERS"] as $arOffer) {
		if(!empty($arOffer["PROPERTIES"]["OBJECT"]["VALUE"])) {
			$arResult["OFFERS_OBJECTS"] = true;
			break;
		}
	}
	unset($arOffer);
}

if($arResult["OFFERS_OBJECTS"])
	$arParams["OFFERS_VIEW"] = "OBJECTS";

//ASK_PRICE//
$arParams["ASK_PRICE"] = true;
if($arSettings["ASK_PRICE"] != "Y")
	$arParams["ASK_PRICE"] = false;

//UNDER_ORDER//
$arParams["UNDER_ORDER"] = true;
if($arSettings["UNDER_ORDER"] != "Y")
	$arParams["UNDER_ORDER"] = false;

//DISABLE_BASKET//
$arParams["DISABLE_BASKET"] = false;
if($arSettings["DISABLE_BASKET"] == "Y") {
	$arParams["DISABLE_BASKET"] = true;
	if((!isset($arResult["PROPERTIES"]["PARTNERS_URL"]["VALUE"]) || empty($arResult["PROPERTIES"]["PARTNERS_URL"]["VALUE"])) && $arParams["USE_PRODUCT_QUANTITY"])
		$arParams["USE_PRODUCT_QUANTITY"] = false;
}

//DISABLE_DELAY//
$arParams["DISABLE_DELAY"] = false;
if($arSettings["DISABLE_DELAY"] == "Y")
	$arParams["DISABLE_DELAY"] = true;

//SHOW_SUBSCRIBE//
if($arParams["PRODUCT_SUBSCRIPTION"] == "Y") {
	$saleNotifyOption = Option::get("sale", "subscribe_prod");
	if(strlen($saleNotifyOption) > 0)
		$saleNotifyOption = unserialize($saleNotifyOption);
	$saleNotifyOption = is_array($saleNotifyOption) ? $saleNotifyOption : array();
	foreach($saleNotifyOption as $siteId => $data) {
		if($siteId == SITE_ID && $data["use"] != "Y")
			$arParams["PRODUCT_SUBSCRIPTION"] = "N";
	}
}

//QUICK_ORDER//
$arParams["QUICK_ORDER"] = true;
if($arSettings["QUICK_ORDER"] != "Y")
	$arParams["QUICK_ORDER"] = false;

//SCHEME//
$arResult["SCHEME"] = CMain::IsHTTPS() ? "https" : "http";

//OFFERS_IBLOCK//
if(!isset($arResult["OFFERS_IBLOCK"])) {
	$mxResult = CCatalogSKU::GetInfoByProductIBlock($arParams["IBLOCK_ID"]);
	if(is_array($mxResult))
		$arResult["OFFERS_IBLOCK"] = $mxResult["IBLOCK_ID"];
}

//OLD_PRICE//
if(!empty($arResult["OFFERS"])) {
	foreach($arResult["OFFERS"] as $key => &$arOffer) {
		if(!empty($arOffer["PROPERTIES"]["OLD_PRICE"]["VALUE"]) || !empty($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"])) {
			$oldPrice = !empty($arOffer["PROPERTIES"]["OLD_PRICE"]["VALUE"]) ? str_replace(",", ".", $arOffer["PROPERTIES"]["OLD_PRICE"]["VALUE"]) : str_replace(",", ".", $arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"]);
			foreach($arOffer["ITEM_PRICES"] as $keyPrice => &$arPrice) {
				if($arPrice["PRICE"] > 0 && $arPrice["PERCENT"] == 0) {
					$arPrice["UNROUND_BASE_PRICE"] = $oldPrice;
					$arPrice["BASE_PRICE"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $oldPrice, $arPrice["CURRENCY"]);
					$arPrice["PRINT_BASE_PRICE"] = CCurrencyLang::CurrencyFormat($arPrice["BASE_PRICE"], $arPrice["CURRENCY"], true);
					$arPrice["RATIO_BASE_PRICE"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["BASE_PRICE"] * $arPrice["MIN_QUANTITY"], $arPrice["CURRENCY"]);
					$arPrice["PRINT_RATIO_BASE_PRICE"] = CCurrencyLang::CurrencyFormat($arPrice["RATIO_BASE_PRICE"], $arPrice["CURRENCY"], true);
					$arPrice["DISCOUNT"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["BASE_PRICE"] - $arPrice["PRICE"], $arPrice["CURRENCY"]);
					$arPrice["PRINT_DISCOUNT"] = CCurrencyLang::CurrencyFormat($arPrice["DISCOUNT"], $arPrice["CURRENCY"], true);
					$arPrice["RATIO_DISCOUNT"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["RATIO_BASE_PRICE"] - $arPrice["RATIO_PRICE"], $arPrice["CURRENCY"]);
					$arPrice["PRINT_RATIO_DISCOUNT"] = CCurrencyLang::CurrencyFormat($arPrice["RATIO_DISCOUNT"], $arPrice["CURRENCY"], true);
					$arPrice["PERCENT"] = roundEx(100 * $arPrice["DISCOUNT"] / $arPrice["BASE_PRICE"], 0);
					
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["UNROUND_BASE_PRICE"] = $arPrice["UNROUND_BASE_PRICE"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["BASE_PRICE"] = $arPrice["BASE_PRICE"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_BASE_PRICE"] = $arPrice["PRINT_BASE_PRICE"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["RATIO_BASE_PRICE"] = $arPrice["RATIO_BASE_PRICE"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_RATIO_BASE_PRICE"] = $arPrice["PRINT_RATIO_BASE_PRICE"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["DISCOUNT"] = $arPrice["DISCOUNT"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_DISCOUNT"] = $arPrice["PRINT_DISCOUNT"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["RATIO_DISCOUNT"] = $arPrice["RATIO_DISCOUNT"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_RATIO_DISCOUNT"] = $arPrice["PRINT_RATIO_DISCOUNT"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["PERCENT"] = $arPrice["PERCENT"];
				}
			}
			unset($keyPrice, $arPrice, $oldPrice);
		}
	}
	unset($key, $arOffer);
} elseif(!empty($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"])) {
	$oldPrice = str_replace(",", ".", $arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"]);
	foreach($arResult["ITEM_PRICES"] as &$arPrice) {
		if($arPrice["PRICE"] > 0 && $arPrice["PERCENT"] == 0) {
			$arPrice["UNROUND_BASE_PRICE"] = $oldPrice;
			$arPrice["BASE_PRICE"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $oldPrice, $arPrice["CURRENCY"]);
			$arPrice["PRINT_BASE_PRICE"] = CCurrencyLang::CurrencyFormat($arPrice["BASE_PRICE"], $arPrice["CURRENCY"], true);
			$arPrice["RATIO_BASE_PRICE"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["BASE_PRICE"] * $arPrice["MIN_QUANTITY"], $arPrice["CURRENCY"]);
			$arPrice["PRINT_RATIO_BASE_PRICE"] = CCurrencyLang::CurrencyFormat($arPrice["RATIO_BASE_PRICE"], $arPrice["CURRENCY"], true);
			$arPrice["DISCOUNT"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["BASE_PRICE"] - $arPrice["PRICE"], $arPrice["CURRENCY"]);
			$arPrice["PRINT_DISCOUNT"] = CCurrencyLang::CurrencyFormat($arPrice["DISCOUNT"], $arPrice["CURRENCY"], true);
			$arPrice["RATIO_DISCOUNT"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["RATIO_BASE_PRICE"] - $arPrice["RATIO_PRICE"], $arPrice["CURRENCY"]);
			$arPrice["PRINT_RATIO_DISCOUNT"] = CCurrencyLang::CurrencyFormat($arPrice["RATIO_DISCOUNT"], $arPrice["CURRENCY"], true);
			$arPrice["PERCENT"] = roundEx(100 * $arPrice["DISCOUNT"] / $arPrice["BASE_PRICE"], 0);
		}
	}
	unset($arPrice, $oldPrice);
}

//MEASURE//
$measureIds = $arMeasureList = array();

if(!empty($arResult["OFFERS"])) {
	foreach($arResult["OFFERS"] as $arOffer) {
		$measureIds[] = $arOffer["ITEM_MEASURE"]["ID"];
	}
	unset($arOffer);
} else {
	$measureIds[] = $arResult["ITEM_MEASURE"]["ID"];
}

if(count($measureIds) > 0) {
	$rsMeasures = CCatalogMeasure::getList(array(), array("ID" => array_unique($measureIds)), false, false, array("ID", "SYMBOL_INTL"));
	while($arMeasure = $rsMeasures->GetNext()) {
		$arMeasureList[$arMeasure["ID"]] = $arMeasure["SYMBOL_INTL"];
	}
	unset($arMeasure, $rsMeasures);

	if(!empty($arResult["OFFERS"])) {
		foreach($arResult["OFFERS"] as $key => &$arOffer) {
			if(array_key_exists($arOffer["ITEM_MEASURE"]["ID"], $arMeasureList)) {
				$arOffer["ITEM_MEASURE"]["SYMBOL_INTL"] = $arMeasureList[$arOffer["ITEM_MEASURE"]["ID"]];
				$arResult["JS_OFFERS"][$key]["ITEM_MEASURE"] = $arOffer["ITEM_MEASURE"];
			}
		}
		unset($key, $arOffer);
	} else {
		if(array_key_exists($arResult["ITEM_MEASURE"]["ID"], $arMeasureList))
			$arResult["ITEM_MEASURE"]["SYMBOL_INTL"] = $arMeasureList[$arResult["ITEM_MEASURE"]["ID"]];
	}
}
unset($arMeasureList, $measureIds);

//SQ_M_PRICE//
//PC_PRICE//
if(!empty($arResult["PROPERTIES"]["M2_COUNT"]["VALUE"])) {
	$sqMCount = str_replace(",", ".", $arResult["PROPERTIES"]["M2_COUNT"]["VALUE"]);	
	if(!empty($arResult["OFFERS"])) {
		foreach($arResult["OFFERS"] as $key => &$arOffer) {
			$measureRatio = $arOffer["ITEM_MEASURE_RATIOS"][$arOffer["ITEM_MEASURE_RATIO_SELECTED"]]["RATIO"];
			if($arOffer["ITEM_MEASURE"]["SYMBOL_INTL"] == "pc. 1") {
				$arOffer["PC_MAX_QUANTITY"] = $arOffer["CATALOG_QUANTITY"];
				$arOffer["PC_STEP_QUANTITY"] = $measureRatio;				
				$arOffer["SQ_M_MAX_QUANTITY"] = round($arOffer["CATALOG_QUANTITY"] / $sqMCount, 2);
				$arOffer["SQ_M_STEP_QUANTITY"] = round($measureRatio / $sqMCount, 2);

				$arResult["JS_OFFERS"][$key]["PC_MAX_QUANTITY"] = $arOffer["PC_MAX_QUANTITY"];
				$arResult["JS_OFFERS"][$key]["PC_STEP_QUANTITY"] = $arOffer["PC_STEP_QUANTITY"];				
				$arResult["JS_OFFERS"][$key]["SQ_M_MAX_QUANTITY"] = $arOffer["SQ_M_MAX_QUANTITY"];
				$arResult["JS_OFFERS"][$key]["SQ_M_STEP_QUANTITY"] = $arOffer["SQ_M_STEP_QUANTITY"];
				
				foreach($arOffer["ITEM_PRICES"] as $keyPrice => &$arPrice) {
					$arPrice["SQ_M_BASE_PRICE"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["BASE_PRICE"] * $sqMCount, $arPrice["CURRENCY"]);
					$arPrice["SQ_M_PRINT_BASE_PRICE"] = CCurrencyLang::CurrencyFormat($arPrice["SQ_M_BASE_PRICE"], $arPrice["CURRENCY"], true);
					$arPrice["SQ_M_PRICE"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["PRICE"] * $sqMCount, $arPrice["CURRENCY"]);	
					$arPrice["SQ_M_PRINT_PRICE"] = CCurrencyLang::CurrencyFormat($arPrice["SQ_M_PRICE"], $arPrice["CURRENCY"], true);
					$arPrice["SQ_M_DISCOUNT"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["SQ_M_BASE_PRICE"] - $arPrice["SQ_M_PRICE"], $arPrice["CURRENCY"]);
					$arPrice["SQ_M_PRINT_DISCOUNT"] = CCurrencyLang::CurrencyFormat($arPrice["SQ_M_DISCOUNT"], $arPrice["CURRENCY"], true);
					$arPrice["PC_MIN_QUANTITY"] = $arPrice["MIN_QUANTITY"];
					$arPrice["SQ_M_MIN_QUANTITY"] = round($arPrice["MIN_QUANTITY"] / $sqMCount, 2);

					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["SQ_M_BASE_PRICE"] = $arPrice["SQ_M_BASE_PRICE"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["SQ_M_PRINT_BASE_PRICE"] = $arPrice["SQ_M_PRINT_BASE_PRICE"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["SQ_M_PRICE"] = $arPrice["SQ_M_PRICE"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["SQ_M_PRINT_PRICE"] = $arPrice["SQ_M_PRINT_PRICE"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["SQ_M_DISCOUNT"] = $arPrice["SQ_M_DISCOUNT"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["SQ_M_PRINT_DISCOUNT"] = $arPrice["SQ_M_PRINT_DISCOUNT"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["PC_MIN_QUANTITY"] = $arPrice["PC_MIN_QUANTITY"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["SQ_M_MIN_QUANTITY"] = $arPrice["SQ_M_MIN_QUANTITY"];
				}
				unset($keyPrice, $arPrice);
			} elseif($arOffer["ITEM_MEASURE"]["SYMBOL_INTL"] == "m2") {
				$arOffer["PC_MAX_QUANTITY"] = floor($arOffer["CATALOG_QUANTITY"] / $measureRatio);
				$arOffer["PC_STEP_QUANTITY"] = 1;
				$arOffer["SQ_M_MAX_QUANTITY"] = $arOffer["CATALOG_QUANTITY"];
				$arOffer["SQ_M_STEP_QUANTITY"] = $measureRatio;

				$arResult["JS_OFFERS"][$key]["PC_MAX_QUANTITY"] = $arOffer["PC_MAX_QUANTITY"];
				$arResult["JS_OFFERS"][$key]["PC_STEP_QUANTITY"] = $arOffer["PC_STEP_QUANTITY"];
				$arResult["JS_OFFERS"][$key]["SQ_M_MAX_QUANTITY"] = $arOffer["SQ_M_MAX_QUANTITY"];
				$arResult["JS_OFFERS"][$key]["SQ_M_STEP_QUANTITY"] = $arOffer["SQ_M_STEP_QUANTITY"];

				foreach($arOffer["ITEM_PRICES"] as $keyPrice => &$arPrice) {
					$arPrice["PC_MIN_QUANTITY"] = 1;
					$arPrice["SQ_M_MIN_QUANTITY"] = $arPrice["MIN_QUANTITY"];

					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["PC_MIN_QUANTITY"] = $arPrice["PC_MIN_QUANTITY"];
					$arResult["JS_OFFERS"][$key]["ITEM_PRICES"][$keyPrice]["SQ_M_MIN_QUANTITY"] = $arPrice["SQ_M_MIN_QUANTITY"];
				}
				unset($keyPrice, $arPrice);
			}
		}
		unset($measureRatio, $key, $arOffer);
	} else {
		if($arResult["ITEM_MEASURE"]["SYMBOL_INTL"] == "pc. 1") {
			foreach($arResult["ITEM_PRICES"] as &$arPrice) {
				$arPrice["SQ_M_BASE_PRICE"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["BASE_PRICE"] * $sqMCount, $arPrice["CURRENCY"]);
				$arPrice["SQ_M_PRINT_BASE_PRICE"] = CCurrencyLang::CurrencyFormat($arPrice["SQ_M_BASE_PRICE"], $arPrice["CURRENCY"], true);
				$arPrice["SQ_M_PRICE"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["PRICE"] * $sqMCount, $arPrice["CURRENCY"]);	
				$arPrice["SQ_M_PRINT_PRICE"] = CCurrencyLang::CurrencyFormat($arPrice["SQ_M_PRICE"], $arPrice["CURRENCY"], true);
				$arPrice["SQ_M_DISCOUNT"] = Price::roundPrice($arPrice["PRICE_TYPE_ID"], $arPrice["SQ_M_BASE_PRICE"] - $arPrice["SQ_M_PRICE"], $arPrice["CURRENCY"]);
				$arPrice["SQ_M_PRINT_DISCOUNT"] = CCurrencyLang::CurrencyFormat($arPrice["SQ_M_DISCOUNT"], $arPrice["CURRENCY"], true);
				$arPrice["PC_MIN_QUANTITY"] = $arPrice["MIN_QUANTITY"];
				$arPrice["SQ_M_MIN_QUANTITY"] = round($arPrice["MIN_QUANTITY"] / $sqMCount, 2);
			}
			unset($arPrice);
		} elseif($arResult["ITEM_MEASURE"]["SYMBOL_INTL"] == "m2") {
			foreach($arResult["ITEM_PRICES"] as &$arPrice) {
				$arPrice["PC_MIN_QUANTITY"] = 1;
				$arPrice["SQ_M_MIN_QUANTITY"] = $arPrice["MIN_QUANTITY"];
			}
			unset($arPrice);
		}
	}
	unset($sqMCount);
}

if(!empty($arResult["OFFERS"]) && $arParams["OFFERS_VIEW"] != "PROPS" && $arParams["OFFERS_VIEW"] != "DROPDOWN_LIST") {
	//OFFERS_SELECTED//
	$arResult["OFFERS_SELECTED"] = null;
	
	$minPrice = null;
	$minPriceIndex = null;
	foreach($arResult["OFFERS"] as $key => $arOffer) {
		if(!$arOffer["CAN_BUY"] || $arOffer["ITEM_PRICE_SELECTED"] === null)
			continue;

		$priceScale = $arOffer["ITEM_PRICES"][$arOffer["ITEM_PRICE_SELECTED"]]["PRICE"];		
		if($priceScale <= 0)
			continue;
		
		if($minPrice === null || $minPrice > $priceScale) {
			$minPrice = $priceScale;
			$minPriceIndex = $key;
		}
		unset($priceScale);
	}
	unset($arOffer, $key);
	
	if($minPriceIndex !== null)
		$arResult["OFFERS_SELECTED"] = $minPriceIndex;
	
	unset($minPriceIndex, $minPrice);

	//OFFERS_QUANTITY//
	$arResult["CATALOG_QUANTITY_TRACE"] = Option::get("catalog", "default_quantity_trace");
	$arResult["CATALOG_CAN_BUY_ZERO"] = Option::get("catalog", "default_can_buy_zero");
	$arResult["OFFERS_QUANTITY"] = 0; 
	foreach($arResult["OFFERS"] as $arOffer) {
		if(!$arOffer["CAN_BUY"])
			continue;

		$arResult["OFFERS_QUANTITY"] += round($arOffer["CATALOG_QUANTITY"] / $arOffer["ITEM_MEASURE_RATIOS"][$arOffer["ITEM_MEASURE_RATIO_SELECTED"]]["RATIO"], 2);
	}
	unset($arOffer);
}

//PROPERTIES//
foreach($arResult["PROPERTIES"] as &$arProp) {
	//MARKERS//
	if($arProp["CODE"] == "MARKER" && !empty($arProp["VALUE"])) {
		$rsElement = CIBlockElement::GetList(array(), array("ID" => $arProp["VALUE"], "IBLOCK_ID" => $arProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID", "NAME", "SORT"));	
		while($obElement = $rsElement->GetNextElement()) {
			$arElement = $obElement->GetFields();
			$arElement["PROPERTIES"] = $obElement->GetProperties();

			$arProp["FULL_VALUE"][] = array(
				"NAME" => $arElement["NAME"],
				"SORT" => $arElement["SORT"],
				"BACKGROUND_1" => $arElement["PROPERTIES"]["BACKGROUND_1"]["VALUE"],
				"BACKGROUND_2" => $arElement["PROPERTIES"]["BACKGROUND_2"]["VALUE"],
				"ICON" => $arElement["PROPERTIES"]["ICON"]["VALUE"],
				"FONT_SIZE" => $arElement["PROPERTIES"]["FONT_SIZE"]["VALUE_XML_ID"]
			);
		}
		unset($arElement, $obElement, $rsElement);

		if(!empty($arProp["FULL_VALUE"]))
			Collection::sortByColumn($arProp["FULL_VALUE"], array("SORT" => SORT_NUMERIC, "NAME" => SORT_ASC));	
	//MORE_PHOTO//
	} elseif($arProp["CODE"] == "MORE_PHOTO" && !empty($arProp["VALUE"])) {
        if(returnSections('inSection', $arResult['IBLOCK_SECTION_ID'])) {
            unset($arProp["VALUE"][0], $arProp["PROPERTY_VALUE_ID"][0], $arProp["DESCRIPTION"][0], $arProp["~VALUE"][0], $arProp["~DESCRIPTION"][0], $arResult["MORE_PHOTO"][1]);
            $arProp["VALUE"] = array_values($arProp["VALUE"]);
            $arProp["PROPERTY_VALUE_ID"] = array_values($arProp["PROPERTY_VALUE_ID"]);
            $arProp["DESCRIPTION"] = array_values($arProp["DESCRIPTION"]);
            $arProp["~VALUE"] = array_values($arProp["~VALUE"]);
            $arProp["~DESCRIPTION"] = array_values($arProp["~DESCRIPTION"]);
            $arResult["MORE_PHOTO"] = array_values($arResult["MORE_PHOTO"]);
        }
		if(!empty($arResult["OFFERS"])) {
			$arResult["MORE_PHOTO"] = array();
			foreach($arProp["VALUE"] as $file) {
				$arFile = CFile::GetFileArray($file);
				if(is_array($arFile))
					$arResult["MORE_PHOTO"][] = $arFile;
				unset($arFile);
			}
			unset($file);
			foreach($arResult["OFFERS"] as &$offer) {
				if(!empty($offer["DETAIL_PICTURE"])) {
					$offer["MORE_PHOTO_COUNT"] += count($arResult["MORE_PHOTO"]);
					$offer["MORE_PHOTO"] = array_merge($offer["MORE_PHOTO"], $arResult["MORE_PHOTO"]);
				}
			}
			unset($offer);
			foreach($arResult["JS_OFFERS"] as &$offer) {
				if(!empty($offer["DETAIL_PICTURE"])) {
					$offer["SLIDER_COUNT"] += count($arResult["MORE_PHOTO"]);
					$offer["SLIDER"] = array_merge($offer["SLIDER"], $arResult["MORE_PHOTO"]);
				}
			}
			unset($offer);
		}
        if(isset($arResult['PROPERTIES']['VID_IS_LIVE']) && !empty($arResult['PROPERTIES']['VID_IS_LIVE']['VALUE'])){
            $arSelect = Array("ID", "ACTIVE", "NAME", "PREVIEW_PICTURE", "PROPERTY_LIVE_COUNT", "PROPERTY_LIVE_ID_VIDEO", "PROPERTY_LIVE_URL_VIDEO");
            $arFilter = Array("IBLOCK_ID"=>IntVal(121), "ID" => $arResult['PROPERTIES']['VID_IS_LIVE']['VALUE'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
            $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM'] = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect)->fetch();
            $temp = CFile::GetFileArray($arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['PREVIEW_PICTURE']);
            unset($temp['TIMESTAMP_X'], $temp['MODULE_ID'], $temp['FILE_SIZE'], $temp['CONTENT_TYPE'], $temp['SUBDIR'], $temp['FILE_NAME'], $temp['ORIGINAL_NAME'], $temp['DESCRIPTION'], $temp['HANDLER_ID'], $temp['EXTERNAL_ID'], $temp['VERSION_ORIGINAL_ID'], $temp['META']);
            $temp['LIVE'] = true;
            $temp['PARAMS'] = array(
                'NAME' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['NAME'],
                'ACTIVE' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['ACTIVE'],
                'ID_VIDEO' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['PROPERTY_LIVE_ID_VIDEO_VALUE'],
                'LIVE_COUNT' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['PROPERTY_LIVE_COUNT_VALUE']
            );
            $arResult['MORE_PHOTO'][] = $temp;
            unset($temp);

        }
	//BRAND//
	} elseif($arProp["CODE"] == "BRAND" && !empty($arProp["VALUE"])) {
		$rsElement = CIBlockElement::GetList(array(), array("ID" => $arProp["VALUE"], "IBLOCK_ID" => $arProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE"));
		while($arElement = $rsElement->GetNext()) {
			$arProp["FULL_VALUE"] = array(
				"NAME" => $arElement["NAME"],
				"PREVIEW_PICTURE" => $arElement["PREVIEW_PICTURE"] > 0 ? CFile::GetFileArray($arElement["PREVIEW_PICTURE"]) : array()
			);
		}
		unset($arElement, $rsElement);
	//OBJECT//
	} elseif($arProp["CODE"] == "OBJECT" && !empty($arProp["VALUE"])) {
		$arDays = array("MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN");
		$rsElement = CIBlockElement::GetList(array(), array("ID" => $arProp["VALUE"], "IBLOCK_ID" => $arProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL"));
		while($obElement = $rsElement->GetNextElement()) {
			$arElement = $obElement->GetFields();
			$arElement["PROPERTIES"] = $obElement->GetProperties();
			
			$arProp["FULL_VALUE"] = array(
				"ID" => $arElement["ID"],
				"NAME" => $arElement["NAME"],
				"PREVIEW_PICTURE" => $arElement["PREVIEW_PICTURE"] > 0 ? CFile::GetFileArray($arElement["PREVIEW_PICTURE"]) : false,
				"DETAIL_PAGE_URL" => $arElement["DETAIL_PAGE_URL"]
			);

			foreach($arElement["PROPERTIES"] as $arElProp) {
				//OBJECT_ADDRESS//
				if($arElProp["CODE"] == "ADDRESS" && !empty($arElProp["VALUE"])) {
					$arProp["FULL_VALUE"][$arElProp["CODE"]] = $arElProp["VALUE"];
				//OBJECT_TIMEZONE//
				} elseif($arElProp["CODE"] == "TIMEZONE" && !empty($arElProp["VALUE"])) {
					$rsTZElement = CIBlockElement::GetList(array(), array("ID" => $arElProp["VALUE"], "IBLOCK_ID" => $arElProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID"));	
					while($obTZElement = $rsTZElement->GetNextElement()) {
						$arTZElement = $obTZElement->GetFields();
						$arTZElement["PROPERTIES"] = $obTZElement->GetProperties();

						$arProp["FULL_VALUE"][$arElProp["CODE"]] = $arTZElement["PROPERTIES"]["OFFSET"]["VALUE"];
					}
					unset($arTZElement, $obTZElement, $rsTZElement);
				//OBJECT_WORKING_HOURS//
				} elseif(in_array($arElProp["CODE"], $arDays) && !empty($arElProp["VALUE"])) {
					$workingHoursIds[] = $arElProp["VALUE"];
				//OBJECT_PHONE_EMAIL_SKYPE_LINKS//
				} elseif(($arElProp["CODE"] == "PHONE" || $arElProp["CODE"] == "EMAIL" || $arElProp["CODE"] == "SKYPE") && !empty($arElProp["VALUE"])) {
					$arProp["FULL_VALUE"][$arElProp["CODE"]] = array(
						"VALUE" => $arElProp["VALUE"],
						"DESCRIPTION" => $arElProp["DESCRIPTION"]
					);
				//OBJECT_PHONE_SMS_EMAIL_EMAIL//
				} elseif(($arElProp["CODE"] == "PHONE_SMS" || $arElProp["CODE"] == "EMAIL_EMAIL") && !empty($arElProp["VALUE"])) {
					$arProp["FULL_VALUE"][$arElProp["CODE"]] = true;
				//OBJECT_DELIVERY_PAYMENT_METHODS//
				} elseif(($arElProp["CODE"] == "DELIVERY_METHODS" || $arElProp["CODE"] == "PAYMENT_METHODS") && !empty($arElProp["VALUE"])) {
					$arProp["FULL_VALUE"][$arElProp["CODE"]] = $arElProp["VALUE"];
				}
			}
			unset($arElProp);
			
			//OBJECT_WORKING_HOURS//
			if(!empty($workingHoursIds)) {	
				$rsWHElements = CIBlockElement::GetList(array(), array("ID" => array_unique($workingHoursIds)), false, false, array("ID", "IBLOCK_ID"));	
				while($obWHElement = $rsWHElements->GetNextElement()) {
					$arWHElement = $obWHElement->GetFields();
					$arWHElement["PROPERTIES"] = $obWHElement->GetProperties();

					$arWorkingHours[$arWHElement["ID"]] = array(
						"WORK_START" => strtotime($arWHElement["PROPERTIES"]["WORK_START"]["VALUE"]) ? $arWHElement["PROPERTIES"]["WORK_START"]["VALUE"] : "",
						"WORK_END" => strtotime($arWHElement["PROPERTIES"]["WORK_END"]["VALUE"]) ? $arWHElement["PROPERTIES"]["WORK_END"]["VALUE"] : "",
						"BREAK_START" => strtotime($arWHElement["PROPERTIES"]["BREAK_START"]["VALUE"]) ? $arWHElement["PROPERTIES"]["BREAK_START"]["VALUE"] : "",
						"BREAK_END" => strtotime($arWHElement["PROPERTIES"]["BREAK_END"]["VALUE"]) ? $arWHElement["PROPERTIES"]["BREAK_END"]["VALUE"] : ""
					);
				}
				unset($arWHElement, $obWHElement, $rsWHElements);
				
				if(!empty($arWorkingHours)) {
					foreach($arElement["PROPERTIES"] as $arElProp) {
						if(in_array($arElProp["CODE"], $arDays) && !empty($arElProp["VALUE"])) {
							if(array_key_exists($arElProp["VALUE"], $arWorkingHours)) {
								$arProp["FULL_VALUE"]["WORKING_HOURS"][$arElProp["CODE"]] = $arWorkingHours[$arElProp["VALUE"]];
								$arProp["FULL_VALUE"]["WORKING_HOURS"][$arElProp["CODE"]]["NAME"] = $arElProp["NAME"];
							}
						}
					}
					unset($arElProp);
				}
				unset($arWorkingHours);
			}
			unset($workingHoursIds);
		}
		unset($arElement, $obElement, $rsElement, $arDays);
	//ADVANTAGES//
	} elseif($arProp["CODE"] == "ADVANTAGES") {
		if(empty($arProp["VALUE"])) {		
			foreach($arResult["SECTION"]["PATH"] as $arSectionPath) {
				$sectionIds[] = $arSectionPath["ID"];
			}
			unset($arSectionPath);
			
			if(!empty($sectionIds)) {		
				$rsSections = CIBlockSection::GetList(array("DEPTH_LEVEL" => "DESC"), array("ID" => $sectionIds, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "UF_ADVANTAGES"));
				while($arSection = $rsSections->GetNext()) {
					if(empty($arProp["VALUE"]) && !empty($arSection["UF_ADVANTAGES"]))
						$arProp["VALUE"] = $arSection["UF_ADVANTAGES"];
				}
				unset($arSection, $rsSections);
			}
			unset($sectionIds);
		}
		
		if(!empty($arProp["VALUE"])) {
			$rsElements = CIBlockElement::GetList(array("SORT" => "ASC", "ACTIVE_FROM" => "DESC", "ID" => "DESC"), array("ID" => $arProp["VALUE"], "ACTIVE" => "Y", "IBLOCK_ID" => $arProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE"));
			while($arElement = $rsElements->GetNext()) {
				if($arElement["PREVIEW_PICTURE"] > 0)
					$arElement["PREVIEW_PICTURE"] = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);
				$arProp["FULL_VALUE"][] = $arElement;
			}
			unset($arElement, $rsElements);	
		}
	//YOUTUBE_ID//
	} elseif($arProp["CODE"] == "YOUTUBE_ID" && !empty($arProp["VALUE"])) {
		if(!empty($arResult["OFFERS"])) {
			foreach($arResult["OFFERS"] as &$offer) {
				foreach($arProp["VALUE"] as $k => $v) {
					$arYouTube[$k]["ID"] = $arProp["PROPERTY_VALUE_ID"][$k];
					$arYouTube[$k]["VALUE"] = $v;				
					$offer["MORE_PHOTO_COUNT"]++;
				}
				unset($k, $v);

				$offer["MORE_PHOTO"] = array_merge($arYouTube, $offer["MORE_PHOTO"]);			
			}
			unset($offer, $arYouTube);
			
			foreach($arResult["JS_OFFERS"] as &$offer) {
				foreach($arProp["VALUE"] as $k => $v) {
					$arYouTube[$k]["ID"] = $arProp["PROPERTY_VALUE_ID"][$k];
					$arYouTube[$k]["VALUE"] = $v;				
					$offer["SLIDER_COUNT"]++;
				}
				unset($k, $v);

				$offer["SLIDER"] = array_merge($arYouTube, $offer["SLIDER"]);	
			}
			unset($offer, $arYouTube);
		} else {
			foreach($arProp["VALUE"] as $k => $v) {	
				$arYouTube[$k]["ID"] = $arProp["PROPERTY_VALUE_ID"][$k];
				$arYouTube[$k]["VALUE"] = $v;			
				$arResult["MORE_PHOTO_COUNT"] ++;
			}
			unset($k, $v);

			$arResult["MORE_PHOTO"] = array_merge($arYouTube, $arResult["MORE_PHOTO"]);
			unset($arYouTube);
		}
	//FILES_DOCS//
	} elseif($arProp["CODE"] == "FILES_DOCS" && !empty($arProp["VALUE"])) {
		foreach($arProp["VALUE"] as $arDocId) {
			$arDocFile = CFile::GetFileArray($arDocId);
			
			$fileTypePos = strrpos($arDocFile["FILE_NAME"], ".");		
			$fileType = substr($arDocFile["FILE_NAME"], $fileTypePos + 1);
			$fileTypeFull = substr($arDocFile["FILE_NAME"], $fileTypePos);
			
			$fileName = str_replace($fileTypeFull, "", $arDocFile["ORIGINAL_NAME"]);		
			
			$fileSize = $arDocFile["FILE_SIZE"];
			$metrics = array(
				0 => Loc::getMessage("CT_BCE_CATALOG_SIZE_B"),
				1 => Loc::getMessage("CT_BCE_CATALOG_SIZE_KB"),
				2 => Loc::getMessage("CT_BCE_CATALOG_SIZE_MB"),
				3 => Loc::getMessage("CT_BCE_CATALOG_SIZE_GB")
			);
			$metric = 0;
			while(floor($fileSize / 1024) > 0) {
				$metric ++;
				$fileSize /= 1024;
			}
			$fileSizeFormat = round($fileSize, 1)." ".$metrics[$metric];

			$arProp["FULL_VALUE"][] = array(
				"NAME" => $fileName,
				"DESCRIPTION" => $arDocFile["DESCRIPTION"],
				"TYPE" => $fileType,
				"SIZE" => $fileSizeFormat,
				"SRC" => $arDocFile["SRC"]			
			);
		}
		unset($arDocId);
	//MORE_PRODUCTS//
	} elseif($arProp["CODE"] == "MORE_PRODUCTS" && !empty($arProp["VALUE"])) {
		$rsElements = CIBlockElement::GetList(array(), array("ID" => $arProp["VALUE"], "ACTIVE" => "Y", "IBLOCK_ID" => $arProp["LINK_IBLOCK_ID"], "SECTION_GLOBAL_ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID"));	
		while($arElement = $rsElements->GetNext()) {	
			if(!empty($arElement["IBLOCK_SECTION_ID"]))
				$sectionIds[] = $arElement["IBLOCK_SECTION_ID"];
		}
		unset($arElement, $rsElements);

		if(!empty($sectionIds)) {
			$arCount = array_count_values($sectionIds);
			$rsSections = CIBlockSection::GetList(array("NAME" => "ASC"), array("ID" => array_unique($sectionIds)), false, array("ID", "IBLOCK_ID", "NAME"));	
			while($arSection = $rsSections->GetNext()) {
				$arProp["SECTIONS"][] = array(
					"ID" => $arSection["ID"],
					"NAME" => $arSection["NAME"],
					"COUNT" => $arCount[$arSection["ID"]]
				);
			}
		}
		unset($arCount, $sectionIds);
	}
}
unset($arProp);

//MORE_PHOTO_PREVIEW//
if(!empty($arResult["OFFERS"])) {
	foreach($arResult["OFFERS"] as &$offer) {
		if(!empty($offer["MORE_PHOTO"])) {
            if(isset($arResult['PROPERTIES']['VID_IS_LIVE']) && !empty($arResult['PROPERTIES']['VID_IS_LIVE']['VALUE'])){


                $arSelect = Array("ID", "ACTIVE", "NAME", "PREVIEW_PICTURE", "PROPERTY_LIVE_COUNT", "PROPERTY_LIVE_ID_VIDEO", "PROPERTY_LIVE_URL_VIDEO");
                $arFilter = Array("IBLOCK_ID"=>IntVal(121), "ID" => $arResult['PROPERTIES']['VID_IS_LIVE']['VALUE'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
                $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM'] = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect)->fetch();


                $temp = CFile::GetFileArray($arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['PREVIEW_PICTURE']);
                unset($temp['TIMESTAMP_X'], $temp['MODULE_ID'], $temp['FILE_SIZE'], $temp['CONTENT_TYPE'], $temp['SUBDIR'], $temp['FILE_NAME'], $temp['ORIGINAL_NAME'], $temp['DESCRIPTION'], $temp['HANDLER_ID'], $temp['EXTERNAL_ID'], $temp['VERSION_ORIGINAL_ID'], $temp['META']);
                $temp['LIVE'] = true;
                $temp['PARAMS'] = array(
                    'NAME' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['NAME'],
                    'ACTIVE' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['ACTIVE'],
                    'ID_VIDEO' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['PROPERTY_LIVE_ID_VIDEO_VALUE'],
                    'LIVE_COUNT' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['PROPERTY_LIVE_COUNT_VALUE']
                );
                $offer['MORE_PHOTO'][] = $temp;
                unset($temp);
            }
			foreach($offer["MORE_PHOTO"] as &$photo) {
				if(!isset($photo["VALUE"])) {
					$arFileTmp = CFile::ResizeImageGet($photo["ID"], array("width" => 80, "height" => 80), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$photo["PREVIEW"] = array(
						"SRC" => $arFileTmp["src"],
						"WIDTH" => $arFileTmp["width"],
						"HEIGHT" => $arFileTmp["height"],
					);
					unset($arFileTmp);
				}
			}
			unset($photo);
		}
	}
	unset($offer);
    foreach($arResult["JS_OFFERS"] as &$jsoffer) {
        if(isset($arResult['PROPERTIES']['VID_IS_LIVE']) && !empty($arResult['PROPERTIES']['VID_IS_LIVE']['VALUE'])){
            $arSelect = Array("ID", "ACTIVE", "NAME", "PREVIEW_PICTURE", "PROPERTY_LIVE_COUNT", "PROPERTY_LIVE_ID_VIDEO", "PROPERTY_LIVE_URL_VIDEO");
            $arFilter = Array("IBLOCK_ID"=>IntVal(121), "ID" => $arResult['PROPERTIES']['VID_IS_LIVE']['VALUE'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
            $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM'] = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect)->fetch();
            $temp = CFile::GetFileArray($arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['PREVIEW_PICTURE']);
            unset($temp['TIMESTAMP_X'], $temp['MODULE_ID'], $temp['FILE_SIZE'], $temp['CONTENT_TYPE'], $temp['SUBDIR'], $temp['FILE_NAME'], $temp['ORIGINAL_NAME'], $temp['DESCRIPTION'], $temp['HANDLER_ID'], $temp['EXTERNAL_ID'], $temp['VERSION_ORIGINAL_ID'], $temp['META']);
            $temp['LIVE'] = true;
            $temp['PARAMS'] = array(
                'NAME' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['NAME'],
                'ACTIVE' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['ACTIVE'],
                'ID_VIDEO' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['PROPERTY_LIVE_ID_VIDEO_VALUE'],
                'LIVE_COUNT' => $arResult['PROPERTIES']['VID_IS_LIVE']['ELEM']['PROPERTY_LIVE_COUNT_VALUE']
            );
            $jsoffer['SLIDER'][] = $temp;
            unset($temp);
        }
    }
    unset($jsoffer);
} elseif(!empty($arResult["MORE_PHOTO"])) {
	foreach($arResult["MORE_PHOTO"] as &$photo) {
		if(!isset($photo["VALUE"])) {
			$arFileTmp = CFile::ResizeImageGet($photo["ID"], array("width" => 80, "height" => 80), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$photo["PREVIEW"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"],
			);
			unset($arFileTmp);
		}
	}
	unset($photo);
}

//CONTACTS//
if(intval($arParams["CONTACTS_IBLOCK_ID"]) > 0) {
	$arDays = array("MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN");
	$rsElements = CIBlockElement::GetList(array("SORT" => "ASC", "ACTIVE_FROM" => "DESC"), array("IBLOCK_ID" => $arParams["CONTACTS_IBLOCK_ID"], "ACTIVE" => "Y"), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE"));
	while($obElement = $rsElements->GetNextElement()) {
		$arElement = $obElement->GetFields();
		$arElement["PROPERTIES"] = $obElement->GetProperties();
		
		$arResult["CONTACTS"] = array(
			"NAME" => $arElement["NAME"],
			"PREVIEW_PICTURE" => $arElement["PREVIEW_PICTURE"] > 0 ? CFile::GetFileArray($arElement["PREVIEW_PICTURE"]) : false,
			"DETAIL_PAGE_URL" => SITE_DIR."contacts/",
			"EMAIL_EMAIL" => true
		);

		foreach($arElement["PROPERTIES"] as $arElProp) {
			//CONTACTS_ADDRESS//
			if($arElProp["CODE"] == "ADDRESS" && !empty($arElProp["VALUE"])) {
				$arResult["CONTACTS"][$arElProp["CODE"]] = $arElProp["VALUE"];
			//CONTACTS_TIMEZONE//
			} elseif($arElProp["CODE"] == "TIMEZONE" && !empty($arElProp["VALUE"])) {
				$rsTZElement = CIBlockElement::GetList(array(), array("ID" => $arElProp["VALUE"], "IBLOCK_ID" => $arElProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID"));	
				while($obTZElement = $rsTZElement->GetNextElement()) {
					$arTZElement = $obTZElement->GetFields();
					$arTZElement["PROPERTIES"] = $obTZElement->GetProperties();

					$arResult["CONTACTS"][$arElProp["CODE"]] = $arTZElement["PROPERTIES"]["OFFSET"]["VALUE"];
				}
				unset($arTZElement, $obTZElement, $rsTZElement);
			//CONTACTS_WORKING_HOURS//
			} elseif(in_array($arElProp["CODE"], $arDays) && !empty($arElProp["VALUE"])) {
				$workingHoursIds[] = $arElProp["VALUE"];
			//CONTACTS_PHONE_EMAIL_SKYPE_LINKS//
			} elseif(($arElProp["CODE"] == "PHONE" || $arElProp["CODE"] == "EMAIL" || $arElProp["CODE"] == "SKYPE") && !empty($arElProp["VALUE"])) {
				$arResult["CONTACTS"][$arElProp["CODE"]] = array(
					"VALUE" => $arElProp["VALUE"],
					"DESCRIPTION" => $arElProp["DESCRIPTION"]
				);
			//CONTACTS_DELIVERY_PAYMENT_METHODS//
			} elseif(($arElProp["CODE"] == "DELIVERY_METHODS" || $arElProp["CODE"] == "PAYMENT_METHODS") && !empty($arElProp["VALUE"])) {
				$arResult["CONTACTS"][$arElProp["CODE"]] = $arElProp["VALUE"];
			}
		}
		unset($arElProp);

		//CONTACTS_WORKING_HOURS//
		if(!empty($workingHoursIds)) {	
			$rsWHElements = CIBlockElement::GetList(array(), array("ID" => array_unique($workingHoursIds)), false, false, array("ID", "IBLOCK_ID"));	
			while($obWHElement = $rsWHElements->GetNextElement()) {
				$arWHElement = $obWHElement->GetFields();
				$arWHElement["PROPERTIES"] = $obWHElement->GetProperties();

				$arWorkingHours[$arWHElement["ID"]] = array(
					"WORK_START" => strtotime($arWHElement["PROPERTIES"]["WORK_START"]["VALUE"]) ? $arWHElement["PROPERTIES"]["WORK_START"]["VALUE"] : "",
					"WORK_END" => strtotime($arWHElement["PROPERTIES"]["WORK_END"]["VALUE"]) ? $arWHElement["PROPERTIES"]["WORK_END"]["VALUE"] : "",
					"BREAK_START" => strtotime($arWHElement["PROPERTIES"]["BREAK_START"]["VALUE"]) ? $arWHElement["PROPERTIES"]["BREAK_START"]["VALUE"] : "",
					"BREAK_END" => strtotime($arWHElement["PROPERTIES"]["BREAK_END"]["VALUE"]) ? $arWHElement["PROPERTIES"]["BREAK_END"]["VALUE"] : ""
				);
			}
			unset($arWHElement, $obWHElement, $rsWHElements);
			
			if(!empty($arWorkingHours)) {
				foreach($arElement["PROPERTIES"] as $arElProp) {
					if(in_array($arElProp["CODE"], $arDays) && !empty($arElProp["VALUE"])) {
						if(array_key_exists($arElProp["VALUE"], $arWorkingHours)) {
							$arResult["CONTACTS"]["WORKING_HOURS"][$arElProp["CODE"]] = $arWorkingHours[$arElProp["VALUE"]];
							$arResult["CONTACTS"]["WORKING_HOURS"][$arElProp["CODE"]]["NAME"] = $arElProp["NAME"];
						}
					}
				}
				unset($arElProp);
			}
			unset($arWorkingHours);
		}
		unset($workingHoursIds);
	}
	unset($arElement, $obElement, $rsElements, $arDays);

	//CONTACTS_RATING_REVIEWS_COUNT//
	if($arParams["CONTACTS_USE_REVIEW"] != "N" && intval($arParams["CONTACTS_REVIEWS_IBLOCK_ID"]) > 0) {
		$ratingSum = 0;
		$reviewsCount = 0;
		
		$rsElements = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams["CONTACTS_REVIEWS_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID"));
		while($obElement = $rsElements->GetNextElement()) {
			$arElement = $obElement->GetFields();
			$arProps = $obElement->GetProperties();

			$ratingSum += $arProps["RATING"]["VALUE_XML_ID"];
			
			$reviewsCount++;
		}
		unset($arProps, $arElement, $obElement, $rsElements);

		$arResult["CONTACTS"]["RATING_VALUE"] = $reviewsCount > 0 ? sprintf("%.1f", round($ratingSum / $reviewsCount, 1)) : 0;
		$arResult["CONTACTS"]["REVIEWS_COUNT"] = $reviewsCount;
		
		unset($reviewsCount, $ratingSum);
	}
}

//OFFERS_OBJECTS//
if(!empty($arResult["OFFERS"]) && $arParams["OFFERS_VIEW"] == "OBJECTS") {
	foreach($arResult["OFFERS"] as $arOffer) {
		foreach($arOffer["PROPERTIES"] as $arProp) {
			if($arProp["CODE"] == "OBJECT" && !empty($arProp["VALUE"]))
				$objectsIds[] = $arProp["VALUE"];
		}
		unset($arProp);
	}
	unset($arOffer);

	$arObjects = array();
	if(!empty($objectsIds)) {
		$arDays = array("MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN");
		$rsElements = CIBlockElement::GetList(array(), array("ID" => array_unique($objectsIds)), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL"));
		while($obElement = $rsElements->GetNextElement()) {
			$arElement = $obElement->GetFields();
			$arElement["PROPERTIES"] = $obElement->GetProperties();
			
			$arObjects[$arElement["ID"]] = array(
				"ID" => $arElement["ID"],
				"NAME" => $arElement["NAME"],
				"PREVIEW_PICTURE" => $arElement["PREVIEW_PICTURE"] > 0 ? CFile::GetFileArray($arElement["PREVIEW_PICTURE"]) : false,
				"DETAIL_PAGE_URL" => $arElement["DETAIL_PAGE_URL"]
			);

			foreach($arElement["PROPERTIES"] as $arElProp) {
				//OBJECT_ADDRESS//
				if($arElProp["CODE"] == "ADDRESS" && !empty($arElProp["VALUE"])) {
					$arObjects[$arElement["ID"]][$arElProp["CODE"]] = $arElProp["VALUE"];
				//OBJECT_TIMEZONE//
				} elseif($arElProp["CODE"] == "TIMEZONE" && !empty($arElProp["VALUE"])) {
					$rsTZElement = CIBlockElement::GetList(array(), array("ID" => $arElProp["VALUE"], "IBLOCK_ID" => $arElProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID"));	
					while($obTZElement = $rsTZElement->GetNextElement()) {
						$arTZElement = $obTZElement->GetFields();
						$arTZElement["PROPERTIES"] = $obTZElement->GetProperties();

						$arObjects[$arElement["ID"]][$arElProp["CODE"]] = $arTZElement["PROPERTIES"]["OFFSET"]["VALUE"];
					}
					unset($arTZElement, $obTZElement, $rsTZElement);
				//OBJECT_WORKING_HOURS//
				} elseif(in_array($arElProp["CODE"], $arDays) && !empty($arElProp["VALUE"])) {
					$workingHoursIds[] = $arElProp["VALUE"];
				//OBJECT_PHONE_EMAIL_SKYPE_LINKS//
				} elseif(($arElProp["CODE"] == "PHONE" || $arElProp["CODE"] == "EMAIL" || $arElProp["CODE"] == "SKYPE") && !empty($arElProp["VALUE"])) {
					$arObjects[$arElement["ID"]][$arElProp["CODE"]] = array(
						"VALUE" => $arElProp["VALUE"],
						"DESCRIPTION" => $arElProp["DESCRIPTION"]
					);
				//OBJECT_PHONE_SMS_EMAIL_EMAIL//
				} elseif(($arElProp["CODE"] == "PHONE_SMS" || $arElProp["CODE"] == "EMAIL_EMAIL") && !empty($arElProp["VALUE"])) {
					$arObjects[$arElement["ID"]][$arElProp["CODE"]] = true;
				//OBJECT_DELIVERY_PAYMENT_METHODS//
				} elseif(($arElProp["CODE"] == "DELIVERY_METHODS" || $arElProp["CODE"] == "PAYMENT_METHODS") && !empty($arElProp["VALUE"])) {
					$arObjects[$arElement["ID"]][$arElProp["CODE"]] = $arElProp["VALUE"];
				}
			}
			unset($arElProp);

			//OBJECT_WORKING_HOURS//
			if(!empty($workingHoursIds)) {	
				$rsWHElements = CIBlockElement::GetList(array(), array("ID" => array_unique($workingHoursIds)), false, false, array("ID", "IBLOCK_ID"));	
				while($obWHElement = $rsWHElements->GetNextElement()) {
					$arWHElement = $obWHElement->GetFields();
					$arWHElement["PROPERTIES"] = $obWHElement->GetProperties();

					$arWorkingHours[$arWHElement["ID"]] = array(
						"WORK_START" => strtotime($arWHElement["PROPERTIES"]["WORK_START"]["VALUE"]) ? $arWHElement["PROPERTIES"]["WORK_START"]["VALUE"] : "",
						"WORK_END" => strtotime($arWHElement["PROPERTIES"]["WORK_END"]["VALUE"]) ? $arWHElement["PROPERTIES"]["WORK_END"]["VALUE"] : "",
						"BREAK_START" => strtotime($arWHElement["PROPERTIES"]["BREAK_START"]["VALUE"]) ? $arWHElement["PROPERTIES"]["BREAK_START"]["VALUE"] : "",
						"BREAK_END" => strtotime($arWHElement["PROPERTIES"]["BREAK_END"]["VALUE"]) ? $arWHElement["PROPERTIES"]["BREAK_END"]["VALUE"] : ""
					);
				}
				unset($arWHElement, $obWHElement, $rsWHElements);
				
				if(!empty($arWorkingHours)) {
					foreach($arElement["PROPERTIES"] as $arElProp) {
						if(in_array($arElProp["CODE"], $arDays) && !empty($arElProp["VALUE"])) {
							if(array_key_exists($arElProp["VALUE"], $arWorkingHours)) {
								$arObjects[$arElement["ID"]]["WORKING_HOURS"][$arElProp["CODE"]] = $arWorkingHours[$arElProp["VALUE"]];
								$arObjects[$arElement["ID"]]["WORKING_HOURS"][$arElProp["CODE"]]["NAME"] = $arElProp["NAME"];
							}
						}
					}
					unset($arElProp);
				}
				unset($arWorkingHours);
			}
			unset($workingHoursIds);
		}
		unset($arElement, $obElement, $rsElements, $arDays);

		//OFFERS_OBJECTS_RATING_REVIEWS_COUNT//
		if($arParams["OBJECTS_USE_REVIEW"] != "N" && intval($arParams["OBJECTS_REVIEWS_IBLOCK_ID"]) > 0) {
			foreach($arObjects as $arObject) {
				$ratingSum[$arObject["ID"]] = 0;
				$reviewsCount[$arObject["ID"]] = 0;
			}
			unset($arObject);
			
			$rsElements = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams["OBJECTS_REVIEWS_IBLOCK_ID"], "PROPERTY_OBJECT_ID" => array_keys($arObjects)), false, false, array("ID", "IBLOCK_ID"));
			while($obElement = $rsElements->GetNextElement()) {
				$arElement = $obElement->GetFields();
				$arProps = $obElement->GetProperties();

				$ratingSum[$arProps["OBJECT_ID"]["VALUE"]] += $arProps["RATING"]["VALUE_XML_ID"];
				
				$reviewsCount[$arProps["OBJECT_ID"]["VALUE"]]++;
			}
			unset($arProps, $arElement, $obElement, $rsElements);

			foreach($arObjects as &$arObject) {
				$arObject["RATING_VALUE"] = $reviewsCount[$arObject["ID"]] > 0 ? sprintf("%.1f", round($ratingSum[$arObject["ID"]] / $reviewsCount[$arObject["ID"]], 1)) : 0;
				$arObject["REVIEWS_COUNT"] = $reviewsCount[$arObject["ID"]];
			}
			unset($reviewsCount, $ratingSum, $arObject);
		}
	}
	unset($objectsIds);
	
	foreach($arResult["OFFERS"] as &$arOffer) {		
		foreach($arOffer["PROPERTIES"] as &$arProp) {
			if($arProp["CODE"] == "OBJECT") {
				if(!empty($arProp["VALUE"]) && array_key_exists($arProp["VALUE"], $arObjects))
					$arProp["FULL_VALUE"] = $arObjects[$arProp["VALUE"]];
				elseif(!empty($arResult["PROPERTIES"]["OBJECT"]["FULL_VALUE"]))
					$arProp["FULL_VALUE"] = $arResult["PROPERTIES"]["OBJECT"]["FULL_VALUE"];
				elseif(!empty($arResult["CONTACTS"]))
					$arProp["FULL_VALUE"] = $arResult["CONTACTS"];
			}
		}
		unset($arProp);
	}
	unset($arOffer);
	unset($arObjects);
}

//DISPLAY_PROPERTIES//
if(!empty($arResult["DISPLAY_PROPERTIES"])) {
	foreach($arResult["DISPLAY_PROPERTIES"] as &$property) {
		if($property["CODE"] == "BRAND") {
			continue;
		} elseif($property["CODE"] == "COLLECTION") {			
			$property["DISPLAY_VALUE"] = strip_tags($property["DISPLAY_VALUE"]);
			$rsElements = CIBlockElement::GetList(array(), array("ID" => $property["VALUE"], "IBLOCK_ID" => $property["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID", "CODE", "NAME"));	
			while($obElement = $rsElements->GetNextElement()) {
				$arElement = $obElement->GetFields();
				$arElement["PROPERTIES"] = $obElement->GetProperties();
				foreach($arElement["PROPERTIES"] as $arCollectProp) {
					if($arCollectProp["CODE"] == "BRAND" && !empty($arCollectProp["VALUE"])) {
						$rsBrand = CIBlockElement::GetList(array(), array("ID" => $arCollectProp["VALUE"], "IBLOCK_ID" => $arCollectProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL"));
						if($arBrand = $rsBrand->GetNext()) {
							$property["DISPLAY_VALUE"] = "<a href='".$arBrand["~DETAIL_PAGE_URL"].$arElement["CODE"]."/'>".$arElement["NAME"]."</a>";
						}
						unset($arBrand, $rsBrand);
					}
				}
				unset($arCollectProp);
			}
			unset($arElement, $obElement, $rsElements);
		} else {
			$property["DISPLAY_VALUE"] = is_array($property["DISPLAY_VALUE"]) ? implode(" / ", $property["DISPLAY_VALUE"]) : strip_tags($property["DISPLAY_VALUE"]);
		}
	}
	unset($property);

	//PROPS_GROUPS//
	//PROPS_UNGROUPS//
	$propsGroupsIblockId = intval($arSettings["PROPS_GROUPS_IBLOCK_ID"]);
	if($propsGroupsIblockId > 0) {
		$arResult["PROPS_GROUPS"] = array();
		$arResult["PROPS_UNGROUPS"] = array();
		$propsGroupsList = array();
		$rsElements = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), array("ACTIVE" => "Y", "IBLOCK_ID" => $propsGroupsIblockId, "PROPERTY_CODE" => array_keys($arResult["DISPLAY_PROPERTIES"])), false, false, array("ID", "IBLOCK_ID", "NAME"));
		while($obElement = $rsElements->GetNextElement()) {
			$arElement = $obElement->GetFields();

			$arResult["PROPS_GROUPS"][$arElement["ID"]] = array(
				"NAME" => $arElement["NAME"],
				"PROPERTIES" => array()
			);
			
			$arProps = $obElement->GetProperties();
			foreach($arProps as $arProp) {
				if($arProp["CODE"] == "CODE") {
					foreach($arResult["DISPLAY_PROPERTIES"] as $property) {
						if(in_array($property["CODE"], $arProp["VALUE"])) {
							$arResult["PROPS_GROUPS"][$arElement["ID"]]["PROPERTIES"][$property["CODE"]] = $property;
							$propsGroupsList[] = $property["CODE"];
						}
					}
					unset($property);
				}
			}
			unset($arProp);
		}
		unset($arProps, $arElement, $obElement, $rsElements);

		if(!empty($arResult["PROPS_GROUPS"])) {
			foreach($arResult["DISPLAY_PROPERTIES"] as $property) {
				if(!in_array($property["CODE"], $propsGroupsList))
					$arResult["PROPS_UNGROUPS"][] = $property;
			}
			unset($property);
		}
	}
}

//UF_CODE//
if(!empty($arResult["OFFERS"]) && !empty($arResult["OFFERS_PROP"])) {	
	foreach($arResult["SKU_PROPS"] as &$skuProperty) {
		if($skuProperty["SHOW_MODE"] == "PICT") {
			$entity = $skuProperty["USER_TYPE_SETTINGS"]["ENTITY"];
			if(!($entity instanceof Bitrix\Main\Entity\Base))
				continue;
			
			$entityFields = $entity->getFields();
			if(!array_key_exists("UF_CODE", $entityFields))
				continue;
			
			$entityDataClass = $entity->getDataClass();
			
			$directorySelect = array("ID", "UF_CODE");
			$directoryOrder = array();
			
			$entityGetList = array(
				"select" => $directorySelect,
				"order" => $directoryOrder
			);
			$propEnums = $entityDataClass::getList($entityGetList);
			while($oneEnum = $propEnums->fetch()) {
				$values[$oneEnum["ID"]] = $oneEnum["UF_CODE"];
			}

			foreach($skuProperty["VALUES"] as &$val) {				
				if(isset($values[$val["ID"]]))
					$val["CODE"] = $values[$val["ID"]];
			}
			unset($val, $values);
		}
	}
	unset($skuProperty);
}

//RATING_REVIEWS_COUNT//
if($arParams["USE_REVIEW"] != "N" && intval($arParams["REVIEWS_IBLOCK_ID"]) > 0) {
	$ratingSum = $reviewsCount = 0;
	$rsElements = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams["REVIEWS_IBLOCK_ID"], "PROPERTY_PRODUCT_ID" => $arResult["ID"]), false, false, array("ID", "IBLOCK_ID"));
	while($obElement = $rsElements->GetNextElement()) {
		$arElement = $obElement->GetFields();
		$arProps = $obElement->GetProperties();
		
		$ratingSum += $arProps["RATING"]["VALUE_XML_ID"];
		
		$reviewsCount++;
	}
	unset($arProps, $arElement, $obElement, $rsElements);
	
	$arResult["RATING_VALUE"] = $reviewsCount > 0 ? sprintf("%.1f", round($ratingSum / $reviewsCount, 1)) : 0;
	$arResult["REVIEWS_COUNT"] = $reviewsCount;
}

//SETS//
if(CCatalogProductSet::isProductInSet($arResult["ID"])) {
	$allSets = CCatalogProductSet::getAllSetsByProduct($arResult["ID"], CCatalogProductSet::TYPE_SET);
	if(!empty($allSets)) {
		foreach($allSets as $oneSet) {
			if($oneSet["ACTIVE"] == "Y") {
				$arSet = $oneSet;
				break;
			}
		}
		unset($oneSet);
	}
	unset($allSets);

	if(!empty($arSet["ITEMS"])) {
		Collection::sortByColumn($arSet["ITEMS"], array("SORT" => SORT_ASC));
		
		$arSetItemsIds = $arSetItemsLinks = array();
		foreach($arSet["ITEMS"] as $key => $arSetItem) {			
			$arSetItemsIds[] = $arSetItem["ITEM_ID"];
			$arSetItemsLinks[$arSetItem["ITEM_ID"]] = $key;
		}
		unset($key, $arSetItem);

		if(!empty($arSetItemsIds)) {
			$arSetRatioMeasureList = ProductTable::getCurrentRatioWithMeasure($arSetItemsIds);
			foreach($arSet["ITEMS"] as &$arSetItem) {
				if(array_key_exists($arSetItem["ITEM_ID"], $arSetRatioMeasureList)) {
					$arSetItem["MEASURE"] = $arSetRatioMeasureList[$arSetItem["ITEM_ID"]]["MEASURE"]["SYMBOL"];
					$arSetItem["QUANTITY"] = round($arSetItem["QUANTITY"] * $arSetRatioMeasureList[$arSetItem["ITEM_ID"]]["RATIO"], 2);
				}
			}
			unset($arSetItem);

			$setItemsList = $setOffersList = array();
			$rsElements = CIBlockElement::GetList(array(), array("ID" => $arSetItemsIds, "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "CATALOG_TYPE"));
			while($obElement = $rsElements->GetNextElement()) {
				$arElement = $obElement->GetFields();
				
				$setItemsList[$arElement["ID"]]["ID"] = $arElement["ID"];
				$setItemsList[$arElement["ID"]]["NAME"] = $arElement["NAME"];

				if($arElement["PREVIEW_PICTURE"] > 0)
					$setItemsList[$arElement["ID"]]["PREVIEW_PICTURE"] = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);

				$setItemsList[$arElement["ID"]]["DETAIL_PAGE_URL"] = $arElement["DETAIL_PAGE_URL"];

				$arProps = $obElement->GetProperties();
				foreach($arProps as $arProp) {
					if($arProp["CODE"] == "BRAND" && !empty($arProp["VALUE"])) {
						$rsBrandElement = CIBlockElement::GetList(array(), array("ID" => $arProp["VALUE"], "IBLOCK_ID" => $arProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE"));
						while($arBrandElement = $rsBrandElement->GetNext()) {
							$setItemsList[$arElement["ID"]]["BRAND"] = array(
								"NAME" => $arBrandElement["NAME"],
								"PREVIEW_PICTURE" => $arBrandElement["PREVIEW_PICTURE"] > 0 ? CFile::GetFileArray($arBrandElement["PREVIEW_PICTURE"]) : array()
							);
						}
						unset($arBrandElement, $rsBrandElement);
					}
				}
				unset($arProp);

				if($arElement["CATALOG_TYPE"] == ProductTable::TYPE_OFFER)
					$setOffersList[$arElement["ID"]] = $arElement["ID"];
			}
			unset($arProps, $arElement, $obElement, $rsElements);
		}
		unset($arSetItemsIds);

		if(!empty($setItemsList)) {
			if(!empty($setOffersList)) {
				$setItemsParents = CCatalogSku::getProductList($setOffersList);
				if(!empty($setItemsParents)) {
					$offersMap = array();
					foreach($setItemsParents as $offerId => $parentData) {
						if(!isset($offersMap[$parentData["ID"]]))
							$offersMap[$parentData["ID"]] = array();
						$offersMap[$parentData["ID"]][$offerId] = $offerId;
					}
					unset($offerId, $parentData);
					
					if(!empty($offersMap)) {
						$rsElements = CIBlockElement::GetList(array(), array("ID" => array_keys($offersMap), "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "PREVIEW_PICTURE"));
						while($obElement = $rsElements->GetNextElement()) {
							$arElement = $obElement->GetFields();
							$arProps = $obElement->GetProperties();
							
							foreach($offersMap[$arElement["ID"]] as $itemId) {
								unset($setOffersList[$itemId]);
								
								if(!isset($setItemsList[$itemId]["PREVIEW_PICTURE"]) && $arElement["PREVIEW_PICTURE"] > 0)
									$setItemsList[$itemId]["PREVIEW_PICTURE"] = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);

								if(!isset($setItemsList[$itemId]["DETAIL_PAGE_URL"]))
									$setItemsList[$itemId]["DETAIL_PAGE_URL"] = $arElement["DETAIL_PAGE_URL"];

								if(!isset($setItemsList[$itemId]["BRAND"])) {
									foreach($arProps as $arProp) {
										if($arProp["CODE"] == "BRAND" && !empty($arProp["VALUE"])) {
											$rsBrandElement = CIBlockElement::GetList(array(), array("ID" => $arProp["VALUE"], "IBLOCK_ID" => $arProp["LINK_IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE"));
											while($arBrandElement = $rsBrandElement->GetNext()) {
												$setItemsList[$itemId]["BRAND"] = array(
													"NAME" => $arBrandElement["NAME"],
													"PREVIEW_PICTURE" => $arBrandElement["PREVIEW_PICTURE"] > 0 ? CFile::GetFileArray($arBrandElement["PREVIEW_PICTURE"]) : array()
												);
											}
											unset($arBrandElement, $rsBrandElement);
										}
									}
									unset($arProp);
								}
							}
							unset($itemId);
						}
						unset($arProps, $arElement, $obElement, $rsElements);
					}
					unset($offersMap);
				}
				unset($setItemsParents);
				
				if(!empty($setOffersList)) {
					foreach($setOffersList as $setOfferId)
						unset($setItemsList[$setOfferId]);
					unset($setOfferId);
				}
			}
			unset($setOffersList);
			
			foreach($setItemsList as $setItem) {
				if(array_key_exists($setItem["ID"], $arSetItemsLinks))
					$arSet["ITEMS"][$arSetItemsLinks[$setItem["ID"]]]["ITEM_DATA"] = $setItem;
			}
			unset($setItem);
		}
		unset($setItemsList);

		foreach($arSet["ITEMS"] as &$arSetItem) {
			if(!isset($arSetItem["ITEM_DATA"]))
				continue;

			$arSetItem["ITEM_DATA"]["QUANTITY"] = $arSetItem["QUANTITY"];
			$arSetItem["ITEM_DATA"]["MEASURE"] = $arSetItem["MEASURE"];

			$arResult["SET_ITEMS"][] = $arSetItem["ITEM_DATA"];
		}
		unset($arSetItem);
	}
	unset($arSet);
}

//CACHE_KEYS//
$haveOffers = !empty($arResult["OFFERS"]);
if($haveOffers) {
	$actualItem = isset($arResult["OFFERS"][$arResult["OFFERS_SELECTED"]])
		? $arResult["OFFERS"][$arResult["OFFERS_SELECTED"]]
		: reset($arResult["OFFERS"]);
	$arResult["DETAIL_PICTURE_EPILOG"] = $actualItem["DETAIL_PICTURE"];
} else {
	$arResult["DETAIL_PICTURE_EPILOG"] = $arResult["DETAIL_PICTURE"];
}

$arResult["BREADCRUMB_TITLE"] = $arResult["PROPERTIES"]["BREADCRUMB_TITLE"]["VALUE"];

$this->__component->SetResultCacheKeys(
	array(		
		"PREVIEW_TEXT",
		"DETAIL_PICTURE_EPILOG",
		"BREADCRUMB_TITLE"
	)
);
if(!empty($arResult["OFFERS"])) {//Мой код добавляем дату поступления характеристик в массив элемента раздела
    foreach($arResult["OFFERS"] as $key => &$skuPropertyes){//Перебираем характеристики
        foreach ($arResult["JS_OFFERS"][$key]["DISPLAY_PROPERTIES"] as &$skuProperty){ //Перебераем свойства характеристик
            if($skuProperty["CODE"] == "CML2_TRAITS"){//Находим реквизиты в свойствах
                if(is_array($skuProperty["VALUE"])) { //Если более одного реквизита (массив)
                    foreach ($skuProperty["VALUE"] as &$value) { //Перебираем значения реквизитов
                        $d = DateTime::createFromFormat('d.m.Y', $value); //Сверяем находится ли там дата
                        if ($d && $d->format('d.m.Y') === $value) { //Ещё раз проверяем верный формат что это действительно дата поступления
                            $arResult["JS_OFFERS"][$key]["RECEIPT_DATE"] = $value; //Если да то записываем в массив дату
                            break; //Выходим сразу из цикла
                        } else //Если реквизит не является датой поступления
                            $arResult["JS_OFFERS"][$key]["RECEIPT_DATE"] = false; //Заносим ложь в массив
                    }
                }
                else{ //Если один реквизит (значение)
                    $d = DateTime::createFromFormat('d.m.Y', $skuProperty["VALUE"]); //Сверяем находится ли там дата
                    if ($d && $d->format('d.m.Y') === $skuProperty["VALUE"]) { //Ещё раз проверяем верный формат что это действительно дата поступления
                        $arResult["JS_OFFERS"][$key]["RECEIPT_DATE"] = $skuProperty["VALUE"]; //Если да то записываем в массив дату
                        break; //Выходим сразу из цикла
                    } else //Если реквизит не является датой поступления
                        $arResult["JS_OFFERS"][$key]["RECEIPT_DATE"] = false; //Заносим ложь в массив
                }
            }
            else{ //Если нет реквизитов
                $arResult["JS_OFFERS"][$key]["RECEIPT_DATE"] = false; //Заносим ложь в массив
            }
        }
    }
    unset($skuPropertyes, $skuProperty, $value); //Очищаем ссылки
}