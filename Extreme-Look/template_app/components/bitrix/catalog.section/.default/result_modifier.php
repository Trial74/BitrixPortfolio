<?
use Bitrix\Catalog\SubscribeTable;
use Bitrix\Catalog\Product\SubscribeManager;

global $USER;
$userId = $USER->GetID();
$arBasketItemsFetch = array();
$arBasketItems = array();
$arBasketDelay = array();
$arBasketIDitems = array();

function picture_separate_array_push_catalog($pictureID, $arPushImage = array()){
    $arImageFilter = Array(
        array("name" => "watermark", "position" => "center", "fill" => "repeat", "size"=>"big", "file"=>$_SERVER['DOCUMENT_ROOT']."/bitrix/templates/dresscode/images/watermark.png")
    );
    $infoimage = CFile::GetByID($pictureID)->Fetch();
    if($infoimage['CONTENT_TYPE'] == 'image/gif'){ //Мой код Если гифка выводим по факту не меняя размеров, тупорылая функция от разрабов не умеет менять размер гифок
        $arPushImage['SRC'] = CFile::GetPath($pictureID);
    }
    else {
        $arPushImage = array_change_key_case(CFile::ResizeImageGet($pictureID, array("width" => 150, "height" => 150), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter), CASE_UPPER);
    }
    return $arPushImage;
}

$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL"
    ),
    false,
    false,
    array("ID", "PRODUCT_ID", "QUANTITY", "DELAY")
);

while($arBasketItemsFetch = $dbBasketItems->Fetch()){
    $arBasketItems[$arBasketItemsFetch['PRODUCT_ID']] = $arBasketItemsFetch['QUANTITY'];
    $arBasketIDitems[$arBasketItemsFetch['PRODUCT_ID']] = $arBasketItemsFetch['ID'];
    $arBasketDelay[$arBasketItemsFetch['PRODUCT_ID']] = $arBasketItemsFetch['DELAY'];

}

if(!empty($arResult["ITEMS"])){
	foreach ($arResult["ITEMS"] as $index => $arElement){

		$arResult["ITEMS"][$index]['OPTI_PRICE'] = CCatalogProduct::GetOptimalPrice($arElement["ID"], 1, $USER->GetUserGroupArray());

		if(!empty($arResult["ITEMS"][$index]["PROPERTIES"]["MARKER"]["VALUE"])){

			$rsElement = CIBlockElement::GetList(array(), array("ID" => $arResult["ITEMS"][$index]["PROPERTIES"]["MARKER"]["VALUE"], "IBLOCK_ID" => 40), false, false, array("ID", "IBLOCK_ID", "NAME", "SORT"));
			while($obElement = $rsElement->GetNextElement()) {
				$arElement = $obElement->GetFields();
				$arElement["PROPERTIES"] = $obElement->GetProperties();

				$arProp[] = array(
					"NAME" => $arElement["NAME"],
					"SORT" => $arElement["SORT"],
					"BACKGROUND_1" => $arElement["PROPERTIES"]["BACKGROUND_1"]["VALUE"],
					"BACKGROUND_2" => $arElement["PROPERTIES"]["BACKGROUND_2"]["VALUE"],
					"ICON" => $arElement["PROPERTIES"]["ICON"]["VALUE"],
					"FONT_SIZE" => $arElement["PROPERTIES"]["FONT_SIZE"]["VALUE_XML_ID"]
				);
			}
			$arResult["ITEMS"][$index]["PROPERTIES"]["MARKER"]["VALUE"] = $arProp;
            unset($arProp, $arElement, $obElement, $rsElement);
		}

        /*--- Проверка подписки на товар ---*/
        $filter = array(
            'ITEM_ID' => $arElement['ID'],
            '=SITE_ID' => SITE_ID,
            array(
                'LOGIC' => 'OR',
                array('=DATE_TO' => false),
                array('>DATE_TO' => date($DB->dateFormatToPHP(\CLang::getDateFormat('FULL')), time()))
            )
        );
        if ($userId)
            $filter['USER_ID'] = $userId;
        else{
            if (!empty($_SESSION['SUBSCRIBE_PRODUCT']['TOKEN']) && !empty($_SESSION['SUBSCRIBE_PRODUCT']['USER_CONTACT'])){
                $filter['=Bitrix\Catalog\SubscribeAccessTable:SUBSCRIBE.TOKEN'] = $_SESSION['SUBSCRIBE_PRODUCT']['TOKEN'];
                $filter['=Bitrix\Catalog\SubscribeAccessTable:SUBSCRIBE.USER_CONTACT'] = $_SESSION['SUBSCRIBE_PRODUCT']['USER_CONTACT'];
            }else{
                $arElement['IS_SUBSCRIBED'] = false;
            }
        }

        $queryObject = SubscribeTable::getList(array('select' => array('ID', 'ITEM_ID'), 'filter' => $filter));

        $subscribeManager = new SubscribeManager;
        $listRealItemId = array();
        while ($subscribe = $queryObject->fetch()) {
            $subscribeManager->setSessionOfSibscribedProducts($subscribe['ITEM_ID']);
            $listRealItemId[] = $subscribe['ID'];
            $listRealItemId[] = $subscribe['ITEM_ID'];
        }

        if (in_array($arElement['ID'], $listRealItemId)) {
            $arResult["ITEMS"][$index]['IS_SUBSCRIBED'] = true;
            $arResult["ITEMS"][$index]['ID_SUBSCRIBED'] = $listRealItemId[0];
        }
        else {
            $arResult["ITEMS"][$index]['IS_SUBSCRIBED'] = false;
            $arResult["ITEMS"][$index]['ID_SUBSCRIBED'] = false;
        }

        /*Проверка находится ли товар в корзине*/
        if(array_key_exists($arResult["ITEMS"][$index]['ID'], $arBasketItems)){
            $arResult["ITEMS"][$index]['ON_BASKET'] = true;
            $arResult["ITEMS"][$index]['QUANTITY_ON_BASKET'] = $arBasketItems[$arResult["ITEMS"][$index]['ID']];
            $arResult["ITEMS"][$index]['ID_ON_BASKET'] = $arBasketIDitems[$arResult["ITEMS"][$index]['ID']];
            $arResult["ITEMS"][$index]["IS_DELAY"] = $arBasketDelay[$arResult["ITEMS"][$index]['ID']];
        }
        else{
            $arResult["ITEMS"][$index]['ON_BASKET'] = false;
        }

        /*Собираем торговые предложения НАЧАЛО*/
        if(!empty($arResult["ITEMS"][$index]['OFFERS']) && isset($arResult["ITEMS"][$index]['OFFERS'])){
            $vladJSoffers = array($arResult["ITEMS"][$index]['ID'] => array());
            $vladOffersInApp = array();
            $vladPropDisplay = array();
            $vladPropDisplayValues = array();
            $priceItem = array();
            foreach($arResult["ITEMS"][$index]['OFFERS'] as $key => $offer){
                foreach($arParams['OFFER_TREE_PROPS'] as $parKey => $paramOff){
                    if(isset($offer['PROPERTIES'][$paramOff]['VALUE']) && !empty($offer['PROPERTIES'][$paramOff]['VALUE'])){
                        if($paramOff == 'TSVET') { //Цвет собираем отдельно потому что он в справочнике
                            $tsvetHSect = getTsvetH(); //Получаем значения справочника
                            $vladPropDisplay[$offer['PROPERTIES'][$paramOff]['ID']] = array(
                                'ID' => $offer['PROPERTIES'][$paramOff]['ID'],
                                'CODE' => $paramOff,
                                'NAME' => $offer['PROPERTIES'][$paramOff]['NAME']
                            );
                            $vladPropDisplayValues[$offer['PROPERTIES'][$paramOff]['ID']][$tsvetHSect[$offer['PROPERTIES'][$paramOff]['VALUE']]['ID']] = array(
                                'ID' => $tsvetHSect[$offer['PROPERTIES'][$paramOff]['VALUE']]['ID'],
                                'TITTLE' => $tsvetHSect[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_NAME'],
                                'COLOR' => $tsvetHSect[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_FILE'],
                                'SORT' => $tsvetHSect[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_SORT'],
                                'TSVET' => true
                            );
                            $vladOffersInApp['SKU_PARAM'][$offer['PROPERTIES'][$paramOff]['ID']] = array(
                                'NAME'              => $offer['PROPERTIES'][$paramOff]['NAME'] ? $offer['PROPERTIES'][$paramOff]['NAME'] : false,
                                'PARAM'             => $tsvetHSect[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_FILE'] ? $tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_FILE'] : false,
                                'PROPERTY_VALUE_ID' => $offer['PROPERTIES'][$paramOff]['PROPERTY_VALUE_ID'] ? $offer['PROPERTIES'][$paramOff]['PROPERTY_VALUE_ID'] : false,
                                'VALUE_ENUM_ID'     => $tsvetHSect[$offer['PROPERTIES'][$paramOff]['VALUE']]['ID'] ? $tsvetHSect[$offer['PROPERTIES'][$paramOff]['VALUE']]['ID'] : false,
                                'TSVET' => true
                            );

                        }else{
                            $vladPropDisplay[$offer['PROPERTIES'][$paramOff]['ID']] = array(
                                'ID' => $offer['PROPERTIES'][$paramOff]['ID'],
                                'CODE' => $paramOff,
                                'NAME' => $offer['PROPERTIES'][$paramOff]['NAME']
                            );
                            $vladPropDisplayValues[$offer['PROPERTIES'][$paramOff]['ID']][$offer['PROPERTIES'][$paramOff]['VALUE_ENUM_ID']] = array(
                                'ID' => $offer['PROPERTIES'][$paramOff]['VALUE_ENUM_ID'],
                                'NAME' => $offer['PROPERTIES'][$paramOff]['VALUE'],
                                'SORT' => $offer['PROPERTIES'][$paramOff]['VALUE_SORT'],
                                'TSVET' => false
                            );
                            $vladOffersInApp['SKU_PARAM'][$offer['PROPERTIES'][$paramOff]['ID']] = array(
                                'NAME'              => $offer['PROPERTIES'][$paramOff]['NAME'] ? $offer['PROPERTIES'][$paramOff]['NAME'] : false,
                                'PARAM'             => $offer['PROPERTIES'][$paramOff]['VALUE'] ? $offer['PROPERTIES'][$paramOff]['VALUE'] : false,
                                'PROPERTY_VALUE_ID' => $offer['PROPERTIES'][$paramOff]['PROPERTY_VALUE_ID'] ? $offer['PROPERTIES'][$paramOff]['PROPERTY_VALUE_ID'] : false,
                                'VALUE_ENUM_ID'     => $offer['PROPERTIES'][$paramOff]['VALUE_ENUM_ID'] ? $offer['PROPERTIES'][$paramOff]['VALUE_ENUM_ID'] : false,
                                'TSVET' => false
                            );
                        }
                    }
                }
                $priceItem = CCatalogProduct::GetOptimalPrice($offer['ID'], 1, $USER->GetUserGroupArray());

                $vladJSoffers[$arResult["ITEMS"][$index]['ID']][$offer['ID']] = array(
                    'ID'        => $offer['ID'],
                    'CAN_BUY'   => $offer['CAN_BUY'],
                    'IN_BASKET' => getItemInBasket($offer['ID']),
                    'QUANTITY'  => $offer['PRODUCT']['QUANTITY'],
                    'PARAM'     => array(
                        'WEIGHT'  => $offer['PRODUCT']['WEIGHT']  ? $offer['PRODUCT']['WEIGHT']   :   $offer['CATALOG_WEIGHT'],
                        'WIDTH'   => $offer['PRODUCT']['WIDTH']   ? $offer['PRODUCT']['WIDTH']    :   $offer['CATALOG_WIDTH'],
                        'LENGTH'  => $offer['PRODUCT']['LENGTH']  ? $offer['PRODUCT']['LENGTH']   :   $offer['CATALOG_LENGTH'],
                        'HEIGHT'  => $offer['PRODUCT']['HEIGHT']  ? $offer['PRODUCT']['HEIGHT']   :   $offer['CATALOG_HEIGHT'],
                        'CML2_ATTRIBUTES' => array(
                            "DESCRIPTION" => $offer['PROPERTIES']['CML2_ATTRIBUTES']['DESCRIPTION'],
                            "VALUE" => $offer['PROPERTIES']['CML2_ATTRIBUTES']['VALUE']
                        )
                    ),
                    'PRICES'  => array(
                        'CURRENCY'    => $priceItem['RESULT_PRICE']['CURRENCY'],
                        'BASE_PRISE'  => $priceItem['RESULT_PRICE']['BASE_PRICE'],
                        'PRICE'       => $priceItem['RESULT_PRICE']['DISCOUNT_PRICE'],
                        'DISCOUNT'    => $priceItem['RESULT_PRICE']['DISCOUNT'],
                        'PERCENT'     => $priceItem['RESULT_PRICE']['PERCENT']
                    ),
                    "PREV_PICTURE" => $offer['PREVIEW_PICTURE'],
                    "PHOTOSKU"     => !empty($offer['PREVIEW_PICTURE']) ? true : false,
                    "MORE_PHOTO"   => $offer['PROPERTIES']['MORE_PHOTO']['VALUE'],
                    'SKU_PARAM'    => $vladOffersInApp['SKU_PARAM']
                );
            }
            unset($key, $offer, $parKey, $paramOff);
        }

        foreach($vladPropDisplayValues as $key => &$prop){ //Сортируем характеристики по индексу сортировки
            usort($prop, function($a,$b){
                return ($a['SORT']-$b['SORT']);
            });
        }
        unset($key, $prop);

        foreach($vladPropDisplay as $key => $prop){
            $vladPropDisplay[$key]['VALUES'] = $vladPropDisplayValues[$prop['ID']];
        }
        unset($key, $prop);

        $arResult['ITEMS'][$index]['VLAD_SKU'] = $vladJSoffers;
        $arResult['ITEMS'][$index]['VLAD_SKU_DISPLAY'] = $vladPropDisplay;



        //RATING_REVIEWS_COUNT//
        if($arParams["USE_REVIEW"] != "N" && intval($arParams["REVIEWS_IBLOCK_ID"]) > 0) {
            $ratingSum = $reviewsCount = 0;
            $rsElements = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams["REVIEWS_IBLOCK_ID"], "PROPERTY_PRODUCT_ID" => $arResult['ITEMS'][$index]['ID']), false, false, array("ID", "IBLOCK_ID", "PROPERTY_RATING"));
            while($obElement = $rsElements->GetNextElement()){
                $arElementField = $obElement->GetFields();
                $arProps = $obElement->GetProperties();
                $ratingSum += $arProps["RATING"]["VALUE_XML_ID"];
                $reviewsCount++;
            }
            $arResult['ITEMS'][$index]["RATING_VALUE"] = $reviewsCount > 0 ? round($ratingSum / $reviewsCount) : 0;
            $reviewsText = numberS($reviewsCount, array('отзыв', 'отзыва', 'отзывов'));
            $arResult['ITEMS'][$index]["REVIEWS_COUNT"] = $reviewsCount . ' ' . $reviewsText;
            unset($arProps, $arElementField, $obElement, $rsElements);
        }
    }
}
$arParams['NO_LAST_IMAGE'] = false;
if((isset($arParams['SECTION_ID']) && !empty($arParams['SECTION_ID'])) && (isset($arParams['NO_SHOW_LASH_IMAGE_OFFER_BY_SECTION']) && !empty($arParams['NO_SHOW_LASH_IMAGE_OFFER_BY_SECTION']))){
    if(is_array($arParams['NO_SHOW_LASH_IMAGE_OFFER_BY_SECTION']) && in_array($arParams['SECTION_ID'], $arParams['NO_SHOW_LASH_IMAGE_OFFER_BY_SECTION']))
        $arParams['NO_LAST_IMAGE'] = true;
}


?>