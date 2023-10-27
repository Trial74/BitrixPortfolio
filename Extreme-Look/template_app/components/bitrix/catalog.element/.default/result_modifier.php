<?use Bitrix\Catalog\SubscribeTable;
use Bitrix\Catalog\Product\SubscribeManager;

if( $arResult['PROPERTIES']['H1_HEADER']['VALUE'] != "" ){
    $newName = $arResult['PROPERTIES']['H1_HEADER']['VALUE'];
    foreach($arResult["IPROPERTY_VALUES"] as &$metaTag)
        $metaTag = str_replace($arResult["NAME"], $newName, $metaTag);
    foreach($arResult['META_TAGS'] as &$metaTag)
        $metaTag = str_replace($arResult["NAME"], $newName, $metaTag);

    $arResult["NAME"] = $newName;
}

$arResult['OPTI_PRICE'] = CCatalogProduct::GetOptimalPrice($arResult["ID"], 1, $USER->GetUserGroupArray());


$arImageFilter = Array(
    array("name" => "watermark", "position" => "bottomright", "size"=>"big", "file"=>$_SERVER['DOCUMENT_ROOT']."/bitrix/templates/dresscode/images/watermark.png")
);

$OPTION_ADD_CART  = COption::GetOptionString("catalog", "default_can_buy_zero");
$OPTION_PRICE_TAB = COption::GetOptionString("catalog", "show_catalog_tab_with_offers");
$OPTION_CURRENCY  = $arResult["CURRENCY"] = CCurrency::GetBaseCurrency();

$arResult["IMAGES"] = array();
$arResult["FILES"] = array();
$arBasketItemsFetch = array();
$arBasketItems = array();
$arBasketDelay = array();
$arBasketIDitems = array();
$arRating = array();

function picture_separate_array_push($pictureID, $arPushImage = array()){
    $arImageFilter = Array(
        array("name" => "watermark", "position" => "center", "fill" => "repeat", "size"=>"big", "file"=>$_SERVER['DOCUMENT_ROOT']."/bitrix/templates/dresscode/images/watermark.png")
    );
    $infoimage = CFile::GetByID($pictureID)->Fetch();
    if($infoimage['CONTENT_TYPE'] == 'image/gif'){ //Мой код Если гифка выводим по факту не меняя размеров, тупорылая функция от разрабов не умеет менят размер гифок
        $arPushImage["MEDIUM_IMAGE"]['SRC'] = CFile::GetPath($pictureID);
    }
    else {
        $arPushImage["MEDIUM_IMAGE"] = array_change_key_case(CFile::ResizeImageGet($pictureID, array("width" => 501, "height" => 501), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter), CASE_UPPER);
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

$arParams["SHOW_REVIEW_FORM"] = true;

$nav = CIBlockSection::GetNavChain(false, $arSec["ID"]);
while($arSectionPath = $nav->GetNext()){
    $APPLICATION->AddChainItem($arSectionPath["NAME"], $arSectionPath["SECTION_PAGE_URL"]);
}

$arProductProperties = array();

global $relatedFilter;
$relatedFilter = array(
    "ID" => $arResult["PROPERTIES"]["RELATED_PRODUCT"]["VALUE"]
);

if(empty($arResult["IMAGES"])){
    if(!empty($arResult["DETAIL_PICTURE"])){
        array_push($arResult["IMAGES"], picture_separate_array_push($arResult["DETAIL_PICTURE"]["ID"]));
    }

    if(!empty($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"])){
        foreach ($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $irp => $nextPictureID) {
            array_push($arResult["IMAGES"], picture_separate_array_push($nextPictureID));
        }
        unset($irp, $nextPictureID);
    }
}

$arSelect = Array("ID", "DATE_CREATE", "DETAIL_TEXT", "PROPERTY_DIGNITY", "PROPERTY_SHORTCOMINGS", "PROPERTY_EXPERIENCE", "PROPERTY_GOOD_REVIEW", "PROPERTY_BAD_REVIEW", "PROPERTY_NAME", "PROPERTY_RATING");
$arFilter = Array("IBLOCK_ID" => $arParams["REVIEW_IBLOCK_ID"], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "CODE" => !empty($arParams["USE_SKU"]) ? $arBaseProduct["ID"] : $arResult["ID"]);
$res = CIBlockElement::GetList(Array("SORT" => "ASC", "CREATED_DATE"), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
    $arResult["REVIEWS"][] = $ob->GetFields();
}

$expEnums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID" => $arParams["REVIEW_IBLOCK_ID"], "CODE" => "EXPERIENCE"));
while($enumValues = $expEnums->GetNext()){
    $arResult["NEW_REVIEW"]["EXPERIENCE"][] = array(
        "ID" => $enumValues["ID"],
        "VALUE" => $enumValues["VALUE"]
    );
}

$USER_ID = $USER->GetID();
$res = CIBlockElement::GetList(
    Array(),
    Array(
        "ID" => intval(!empty($arParams["USE_SKU"]) ? $arBaseProduct["ID"] : $arResult["ID"]),
        "ACTIVE_DATE" => "Y",
        "ACTIVE" => "Y"
    ),
    false,
    false,
    Array(
        "ID",
        "IBLOCK_ID",
        "PROPERTY_USER_ID",
    )
);

while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    if($USER_ID == $arFields["PROPERTY_USER_ID_VALUE"]){
        $arParams["SHOW_REVIEW_FORM"] = false;
        break;
    }
}

foreach ($arResult["PROPERTIES"] as $index => $arProp) {
    if($arProp["CODE"] == "MORE_PROPERTIES"){
        if(!empty($arProp["VALUE"])){
            foreach ($arProp["VALUE"] as $i => $arValue) {
                $arResult["PROPERTIES"][] = array(
                    "CODE" => $arProp["PROPERTY_VALUE_ID"][$i],
                    "SORT" => 5000,
                    "VALUE" => $arProp["DESCRIPTION"][$i],
                    "NAME" => $arValue
                );
            }
        }
        unset($arResult["PROPERTIES"][$index]);
    }elseif($arProp["CODE"] == "MORE_PHOTO"){
        unset($arResult["PROPERTIES"][$index]);
    }else if($arProp["PROPERTY_TYPE"] == "F" && $arProp["SORT"] <= 5000){
        if(!empty($arProp["VALUE"])){
            if($arProp["MULTIPLE"] == "Y"){
                foreach ($arProp["VALUE"] as $ifx => $fileID) {
                    $rsFile = CFile::GetByID($fileID);
                    $arFile = $rsFile->Fetch();
                    $arResult["PROPERTIES"][] = array(
                        "CODE" => $arFile["ID"],
                        "SORT" => 5000,
                        "PROPERTY_TYPE" => "F",
                        "VALUE" => !empty($arProp["DESCRIPTION"][$ifx]) ? '<a href="'.CFile::GetPath($fileID).'">'.$arProp["DESCRIPTION"][$ifx].'</a> ' : '<a href="'.CFile::GetPath($fileID).'">'.$arFile["FILE_NAME"].'</a> ',
                        "NAME" => $arProp["NAME"]
                    );
                }
            }else{
                $rsFile = CFile::GetByID($arProp["VALUE"]);
                $arFile = $rsFile->Fetch();
                $arResult["PROPERTIES"][] = array(
                    "CODE" => $arFile["ID"],
                    "SORT" => 5000,
                    "PROPERTY_TYPE" => "F",
                    "VALUE" => !empty($arProp["DESCRIPTION"]) ? '<a href="'.CFile::GetPath($fileID).'">'.$arProp["DESCRIPTION"].'</a> ' : '<a href="'.CFile::GetPath($arProp["VALUE"]).'">'.$arFile["FILE_NAME"].'</a> ',
                    "NAME" => $arProp["NAME"]
                );
            }
        }
        unset($arResult["PROPERTIES"][$index]);
    }elseif($arProp["USER_TYPE"] == "HTML"){
        $arResult["PROPERTIES"][$index]["VALUE"] = $arProp["~VALUE"]["TEXT"];
    }
}

foreach ($arResult["PROPERTIES"] as $pid => $arPropNext) {
    if($arPropNext["PROPERTY_TYPE"] == "F" && $arPropNext["SORT"] <= 5000){
        $arResult["DISPLAY_PROPERTIES"][$pid] = $arPropNext;
    }
}

$i = 0;
$index = 0;
foreach($arResult["DISPLAY_PROPERTIES"] as $arProp){
    if(!empty($arProp["VALUE"]) && $arProp["SORT"] <= 5000){
        if($i == 5){ $index++; $i = 0; }
        $arResult["TOP_PROPERTIES"][$index][] = $arProp;
        $i++;
    }
}

$arResult["DISPLAY_PROPERTIES_GROUP"] = $arResult["DISPLAY_PROPERTIES"];
usort($arResult["DISPLAY_PROPERTIES_GROUP"], function($a, $b){
    return ($a["SORT"] - $b["SORT"]);
});

$rsStore = CCatalogStoreProduct::GetList(array(), array("PRODUCT_ID" => $arResult["ID"], "ACTIVE" => "Y"), false, false, array("AMOUNT"));
while ($arStore = $rsStore->Fetch()){
    if($arStore["AMOUNT"] > 0){
        $arResult["SHOW_STORES"] = "Y";
    }
}

$arResult["TABS"]["CATALOG_ELEMENT_BACK"] = array("PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco1.png", "NAME" => GetMessage("CATALOG_ELEMENT_BACK"), "LINK" => $arResult["SECTION"]["SECTION_PAGE_URL"]);
$arResult["TABS"]["CATALOG_ELEMENT_OVERVIEW"] = array(
    "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco2.png",
    "NAME" => GetMessage("CATALOG_ELEMENT_OVERVIEW"),
    "ACTIVE" => "Y",
    "ID" => "browse"
);

if (CModule::IncludeModule("catalog")){
    if(CCatalogProductSet::isProductHaveSet(!empty($arResult["~ID"]) ? $arResult["~ID"] : $arResult["ID"], CCatalogProductSet::TYPE_GROUP)){
        $arResult["TABS"]["CATALOG_ELEMENT_SET"] = array(
            "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco3.png",
            "NAME" => GetMessage("CATALOG_ELEMENT_SET"),
            "ID" => "set"
        );
    }
}

if(!empty($arResult["DETAIL_TEXT"])){
    $arResult["TABS"]["CATALOG_ELEMENT_DESCRIPTION"] = array(
        "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco8.png",
        "NAME" => GetMessage("CATALOG_ELEMENT_DESCRIPTION"),
        "ID" => "detailText"
    );
}

if(!empty($arResult["DISPLAY_PROPERTIES"])){
    $arResult["TABS"]["CATALOG_ELEMENT_CHARACTERISTICS"] = array(
        "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco9.png",
        "NAME" => GetMessage("CATALOG_ELEMENT_CHARACTERISTICS"),
        "ID" => "elementProperties"
    );
}

if($arResult["SHOW_RELATED"] == "Y"){
    $arResult["TABS"]["CATALOG_ELEMENT_ACCEESSORIES"] = array(
        "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco5.png",
        "NAME" => GetMessage("CATALOG_ELEMENT_ACCEESSORIES"),
        "ID" => "related"
    );
}

if(!empty($arResult["REVIEWS"])){
    $arResult["TABS"]["CATALOG_ELEMENT_REVIEW"] = array(
        "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco4.png",
        "NAME" => GetMessage("CATALOG_ELEMENT_REVIEW"),
        "ID" => "catalogReviews"
    );
}

if($arResult["SHOW_SIMILAR"] == "Y"){
    $arResult["TABS"]["CATALOG_ELEMENT_SIMILAR"] = array(
        "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco6.png",
        "NAME" => GetMessage("CATALOG_ELEMENT_SIMILAR"),
        "ID" => "similar"
    );
}

if($arResult["SHOW_STORES"] == "Y" && $arParams["HIDE_AVAILABLE_TAB"] != "Y"){
    $arResult["TABS"]["CATALOG_ELEMENT_AVAILABILITY"] = array(
        "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco7.png",
        "NAME" => GetMessage("CATALOG_ELEMENT_AVAILABILITY"),
        "ID" => "stores"
    );
}

if(!empty($arResult["FILES"])){
    $arResult["TABS"]["CATALOG_ELEMENT_FILES"] = array(
        "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco11.png",
        "NAME" => GetMessage("CATALOG_ELEMENT_FILES"),
        "ID" => "files"
    );
}


if(!empty($arResult["VIDEO"])){
    $arResult["TABS"]["CATALOG_ELEMENT_VIDEO"] = array(
        "PICTURE" => SITE_TEMPLATE_PATH."/images/elementNavIco10.png",
        "NAME" => GetMessage("CATALOG_ELEMENT_VIDEO"),
        "ID" => "video"
    );
}

$userId = $USER->GetID();
$filter = array(
    'ITEM_ID' => $arResult['ID'],
    '=SITE_ID' => SITE_ID,
    array(
        'LOGIC' => 'OR',
        array('=DATE_TO' => false),
        array('>DATE_TO' => date($DB->dateFormatToPHP(\CLang::getDateFormat('FULL')), time()))
    )
);
if($userId)
    $filter['USER_ID'] = $userId;
else{
    if (!empty($_SESSION['SUBSCRIBE_PRODUCT']['TOKEN']) && !empty($_SESSION['SUBSCRIBE_PRODUCT']['USER_CONTACT'])){
        $filter['=Bitrix\Catalog\SubscribeAccessTable:SUBSCRIBE.TOKEN'] = $_SESSION['SUBSCRIBE_PRODUCT']['TOKEN'];
        $filter['=Bitrix\Catalog\SubscribeAccessTable:SUBSCRIBE.USER_CONTACT'] = $_SESSION['SUBSCRIBE_PRODUCT']['USER_CONTACT'];
    }else{
        $arResult['IS_SUBSCRIBED'] = false;
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

if (in_array($arResult['ID'], $listRealItemId)) {
    $arResult['IS_SUBSCRIBED'] = true;
    $arResult['ID_SUBSCRIBED'] = $listRealItemId[0];
}
else {
    $arResult['IS_SUBSCRIBED'] = false;
    $arResult['ID_SUBSCRIBED'] = false;
}

/*Проверка находится ли товар в корзине*/
if(array_key_exists($arResult['ID'], $arBasketItems)){
    $arResult['ON_BASKET'] = true;
    $arResult['QUANTITY_ON_BASKET'] = $arBasketItems[$arResult['ID']];
    $arResult['ID_ON_BASKET'] = $arBasketIDitems[$arResult['ID']];
    $arResult['DELAY'] = $arBasketDelay[$arResult['ID']];
}
else{
    $arResult['ON_BASKET'] = false;
}
/*Собираем торговые предложения НАЧАЛО*/
if(!empty($arResult['OFFERS']) && isset($arResult['OFFERS'])){
    $vladJSoffers = array($arResult['ID'] => array());
    $vladOffersInApp = array();
    $vladPropDisplay = array();
    $vladPropDisplayValues = array();
    foreach($arResult['OFFERS'] as $key => $offer){
        foreach($arParams['OFFER_TREE_PROPS'] as $parKey => $paramOff){
            if(isset($offer['PROPERTIES'][$paramOff]['VALUE']) && !empty($offer['PROPERTIES'][$paramOff]['VALUE'])){
                if($paramOff == 'TSVET'){ //Цвет собираем отдельно потому что он в справочнике
                    $tsvetHEl = getTsvetH(); //Получаем значения справочника
                    $vladPropDisplay[$offer['PROPERTIES'][$paramOff]['ID']] = array(
                        'ID' => $offer['PROPERTIES'][$paramOff]['ID'],
                        'CODE' => $paramOff,
                        'NAME' => $offer['PROPERTIES'][$paramOff]['NAME']
                    );
                    $vladPropDisplayValues[$offer['PROPERTIES'][$paramOff]['ID']][$tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['ID']] = array(
                        'ID' => $tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['ID'],
                        'TITTLE' => $tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_NAME'],
                        'COLOR' => $tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_FILE'],
                        'SORT' => $tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_SORT'],
                        'TSVET' => true
                    );

                    $vladOffersInApp['SKU_PARAM'][$offer['PROPERTIES'][$paramOff]['ID']] = array(
                        'NAME'              => $offer['PROPERTIES'][$paramOff]['NAME'] ? $offer['PROPERTIES'][$paramOff]['NAME'] : false,
                        'PARAM'             => $tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_FILE'] ? $tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['UF_FILE'] : false,
                        'PROPERTY_VALUE_ID' => $offer['PROPERTIES'][$paramOff]['PROPERTY_VALUE_ID'] ? $offer['PROPERTIES'][$paramOff]['PROPERTY_VALUE_ID'] : false,
                        'VALUE_ENUM_ID'     => $tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['ID'] ? $tsvetHEl[$offer['PROPERTIES'][$paramOff]['VALUE']]['ID'] : false,
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
        $photosItem = array(); //Сбрасываем массив картинок на каждой итерации
        $photoSku = false;
        $priceItem = array();
        if(!empty($arResult["DETAIL_PICTURE"]))
            array_push($photosItem, picture_separate_array_push($arResult["DETAIL_PICTURE"]["ID"]));
        else if(!empty($offer["DETAIL_PICTURE"])) {
            $photoSku = true;
            array_push($photosItem, picture_separate_array_push($offer["DETAIL_PICTURE"]["ID"]));
        }

        if(!empty($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"])){
            foreach ($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $irp => $nextPictureID) {
                array_push($photosItem, picture_separate_array_push($nextPictureID));
            }
            unset($irp, $nextPictureID);
        }
        else if(!empty($offer["PROPERTIES"]["MORE_PHOTO"]["VALUE"])){
            foreach ($offer["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $irp => $nextPictureID) {
                array_push($photosItem, picture_separate_array_push($nextPictureID));
            }
            $photoSku = true;
            unset($irp, $nextPictureID);
        }

        $priceItem = CCatalogProduct::GetOptimalPrice($offer['ID'], 1, $USER->GetUserGroupArray());

        $vladJSoffers[$arResult['ID']][$offer['ID']] = array(
            'ID'        => $offer['ID'],
            'CAN_BUY'   => $offer['CAN_BUY'],
            'IN_BASKET' => getItemInBasket($offer['ID']),
            'QUANTITY'  => $offer['PRODUCT']['QUANTITY'],
            'PARAM'     => array(
                'WEIGHT'    => $offer['PRODUCT']['WEIGHT']  ? $offer['PRODUCT']['WEIGHT']   :   $offer['CATALOG_WEIGHT'],
                'WIDTH'     => $offer['PRODUCT']['WIDTH']   ? $offer['PRODUCT']['WIDTH']    :   $offer['CATALOG_WIDTH'],
                'LENGTH'    => $offer['PRODUCT']['LENGTH']  ? $offer['PRODUCT']['LENGTH']   :   $offer['CATALOG_LENGTH'],
                'HEIGHT'    => $offer['PRODUCT']['HEIGHT']  ? $offer['PRODUCT']['HEIGHT']   :   $offer['CATALOG_HEIGHT'],
                'CML2_ATTRIBUTES' => array(
                    "DESCRIPTION" => $offer['PROPERTIES']['CML2_ATTRIBUTES']['DESCRIPTION'],
                    "VALUE" => $offer['PROPERTIES']['CML2_ATTRIBUTES']['VALUE']
                )
            ),
            'PRICES'    => array(
                'CURRENCY'          =>     $priceItem['RESULT_PRICE']['CURRENCY'],
                'BASE_PRISE'        =>     $priceItem['RESULT_PRICE']['BASE_PRICE'],
                'PRICE'             =>     $priceItem['RESULT_PRICE']['DISCOUNT_PRICE'],
                'DISCOUNT'          =>     $priceItem['RESULT_PRICE']['DISCOUNT'],
                'PERCENT'           =>     $priceItem['RESULT_PRICE']['PERCENT']
            ),
            "PHOTOS" => $photosItem,
            "PHOTOSKU" => $photoSku,
            'SKU_PARAM' => $vladOffersInApp['SKU_PARAM']
        );
    }
    unset($key, $offer, $parKey, $paramOff, $photosItem);
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

$arResult['VLAD_SKU'] = $vladJSoffers;
$arResult['VLAD_SKU_DISPLAY'] = $vladPropDisplay;

//RATING_REVIEWS_COUNT//
if($arParams["USE_REVIEW"] != "N" && intval($arParams["REVIEWS_IBLOCK_ID"]) > 0) {
    $ratingSum = $reviewsCount = 0;
    $rsElements = CIBlockElement::GetList(array("created_date" => "ASC"), array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams["REVIEWS_IBLOCK_ID"], "PROPERTY_PRODUCT_ID" => $arResult["ID"]), false, false, array("ID", "IBLOCK_ID", "DATE_CREATE", "PROPERTY_NAME", "PROPERTY_LIKES", "PROPERTY_COMMENT", "PROPERTY_RATING"));
    while($obElement = $rsElements->GetNextElement()) {
        $arElement = $obElement->GetFields();
        $arProps = $obElement->GetProperties();
        $dateCreateRating = explode(" ", $arElement["DATE_CREATE"]);
        $arRating[] = array(
            "ID_REV" => $arElement["ID"],
            "DATE_CREATE" => $dateCreateRating[0],
            "USER_NAME" => $arElement["PROPERTY_NAME_VALUE"],
            "LIKES_COUNT" => $arElement["PROPERTY_LIKES_VALUE"] > 0 ? "+" . $arElement["PROPERTY_LIKES_VALUE"] : 0,
            "LIKES_COUNT_NUMB" => $arElement["PROPERTY_LIKES_VALUE"],
            "USER_RATING" => $arProps["RATING"]["VALUE_XML_ID"],
            "USER_MESSAGE" => $arElement["PROPERTY_COMMENT_VALUE"]
        );
        $ratingSum += $arProps["RATING"]["VALUE_XML_ID"];
        $reviewsCount++;
    }
    unset($arProps, $arElement, $obElement, $rsElements);

    $arResult["RATING_VALUE"] = $reviewsCount > 0 ? sprintf("%.1f", round($ratingSum / $reviewsCount, 1)) : 0;
    $arResult["REVIEWS_COUNT"] = $reviewsCount;
    $arResult["RATING_USER_VALUE"] = $arRating;
}?>