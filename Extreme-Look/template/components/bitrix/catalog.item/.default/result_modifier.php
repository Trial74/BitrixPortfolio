<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$item = &$arResult['ITEM'];

$haveOffers = !empty($item['OFFERS']);

$object = !empty($item['PROPERTIES']['OBJECT']['FULL_VALUE']) ? $item['PROPERTIES']['OBJECT']['FULL_VALUE'] : false;
$objectContacts = $object['PHONE_SMS'] || $object['EMAIL_EMAIL'] ? true : false;

$partnersUrl = !empty($item['PROPERTIES']['PARTNERS_URL']['VALUE']) ? true : false;

//TARGET//
if(($object && !$objectContacts) || $partnersUrl)
	$item['TARGET'] = '_blank';

//OFFERS_VIEW//
if($item['OFFERS_OBJECTS'])
	$arParams['OFFERS_VIEW'] = 'OBJECTS';

if($haveOffers && (!$object || ($object && $objectContacts)) && !$partnersUrl && ($arParams['OFFERS_VIEW'] == 'PROPS' || $arParams['OFFERS_VIEW'] == 'DROPDOWN_LIST') && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y') {
	//PRODUCT_DISPLAY_MODE//
	$numOffersPartnersUrl = 0;
	foreach($item['OFFERS'] as $offer) {
		if(!empty($offer['PROPERTIES']['PARTNERS_URL']['VALUE']))
			$numOffersPartnersUrl++;
	}
	unset($offer);

	if($numOffersPartnersUrl == count($item['OFFERS'])) {
		$arParams['PRODUCT_DISPLAY_MODE'] = 'N';
		$item['TARGET'] = '_blank';
	}

	//JS_OFFERS//
	if($arParams['PRODUCT_DISPLAY_MODE'] == 'Y') {
		foreach($item['JS_OFFERS'] as $ind => &$jsOffer) {
			if(!empty($item['OFFERS'][$ind]['PROPERTIES']['PARTNERS_URL']['VALUE']))
				$jsOffer['PARTNERS_URL'] = true;
			elseif(!empty($item['PROPERTIES']['PARTNERS_URL']['VALUE']))
				$jsOffer['PARTNERS_URL'] = true;
		}
		unset($ind, $jsOffer);
	}
}

//ITEM_START_PRICE//
if($haveOffers && (($object && !$objectContacts) || $partnersUrl || ($arParams['OFFERS_VIEW'] != 'PROPS' && $arParams['OFFERS_VIEW'] != 'DROPDOWN_LIST') || $arParams['PRODUCT_DISPLAY_MODE'] == 'N')) {
	$item['OFFERS_SELECTED'] = null;

	$minPrice = null;
	$minPriceIndex = null;
	foreach($item['OFFERS'] as $key => $arOffer) {
		if(!$arOffer['CAN_BUY'] || $arOffer['ITEM_PRICE_SELECTED'] === null)
			continue;

		$priceScale = $arOffer['ITEM_PRICES'][$arOffer['ITEM_PRICE_SELECTED']]['PRICE'];		
		if($priceScale <= 0)
			continue;
		
		if($minPrice === null || $minPrice > $priceScale) {
			$minPrice = $priceScale;
			$minPriceIndex = $key;
		}
		unset($priceScale);
	}
	unset($arOffer, $key);
	if($minPriceIndex !== null) {
		$item['OFFERS_SELECTED'] = $minPriceIndex;
		
		$minOffer = $item['OFFERS'][$minPriceIndex];
		if(!empty($minOffer['PREVIEW_PICTURE']))
			$item['PREVIEW_PICTURE'] = $minOffer['PREVIEW_PICTURE'];
	}
	unset($minOffer, $minPriceIndex, $minPrice);
}

if($haveOffers || ($arParams['OFFERS_VIEW'] != 'PROPS' && $arParams['OFFERS_VIEW'] != 'DROPDOWN_LIST')) {
    foreach ($item['OFFERS'] as $key => $arOffer) {
        if(is_array($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"]) && !empty($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"])){
            foreach ($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $photoID) {
                $arFileMorePhotoOffer = CFile::GetPath($photoID);
                $arResult['ITEM']['OFFERS'][$key]["SLIDER_PHOTOS"][] = $arFileMorePhotoOffer;
            }
        }
    }
    unset($arOffer, $key, $photoID);
}

if($haveOffers) { //Мой код добавляем дату поступления характеристик в массив элемента раздела
    foreach($item["OFFERS"] as $key => &$skuPropertyes) { //Перебираем характеристики
        foreach ($item["JS_OFFERS"][$key]["DISPLAY_PROPERTIES"] as &$skuProperty){ //Перебераем свойства характеристик
            if($skuProperty["CODE"] == "CML2_TRAITS"){ //Находим реквизиты в свойствах
                if(is_array($skuProperty["VALUE"])) { //Если более одного реквизита (массив)
                    foreach ($skuProperty["VALUE"] as &$value) { //Перебираем значения реквизитов
                        $d = DateTime::createFromFormat('d.m.Y', $value); //Сверяем находится ли там дата
                        if ($d && $d->format('d.m.Y') === $value) { //Ещё раз проверяем верный формат что это действительно дата поступления
                            $item["JS_OFFERS"][$key]["RECEIPT_DATE"] = $value; //Если да то записываем в массив дату
                            break; //Выходим сразу из цикла
                        } else //Если реквизит не является датой поступления
                            $item["JS_OFFERS"][$key]["RECEIPT_DATE"] = false; //Заносим ложь в массив
                    }
                }
                else{ //Если один реквизит (значение)
                    $d = DateTime::createFromFormat('d.m.Y', $skuProperty["VALUE"]); //Сверяем находится ли там дата
                    if ($d && $d->format('d.m.Y') === $skuProperty["VALUE"]) { //Ещё раз проверяем верный формат что это действительно дата поступления
                        $item["JS_OFFERS"][$key]["RECEIPT_DATE"] = $skuProperty["VALUE"]; //Если да то записываем в массив дату
                        break; //Выходим сразу из цикла
                    } else //Если реквизит не является датой поступления
                        $item["JS_OFFERS"][$key]["RECEIPT_DATE"] = false; //Заносим ложь в массив
                }
            }
            else{ //Если нет реквизитов
                $item["JS_OFFERS"][$key]["RECEIPT_DATE"] = false; //Заносим ложь в массив
            }
        }
    }
    unset($skuPropertyes, $skuProperty, $value); //Очищаем ссылки
}

$arParams['NO_LAST_IMAGE'] = false;
if((isset($arParams['C_ID']) && !empty($arParams['C_ID'])) && (isset($arParams['NO_SHOW_LASH_IMAGE_OFFER_BY_SECTION']) && !empty($arParams['NO_SHOW_LASH_IMAGE_OFFER_BY_SECTION']))){
    $noShowSects = explode(",", $arParams['NO_SHOW_LASH_IMAGE_OFFER_BY_SECTION']);
    //prent_r(is_array($noShowSects) ? 'TRUUU' : 'FAALSE');
    if(is_array($noShowSects) && in_array($arParams['C_ID'], $noShowSects))
        $arParams['NO_LAST_IMAGE'] = true;
}
unset($item, $haveOffers, $object, $objectContacts, $partnersUrl, $measureRatioMod, $noShowSects);