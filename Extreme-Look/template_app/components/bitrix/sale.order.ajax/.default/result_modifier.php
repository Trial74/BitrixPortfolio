<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);
/*
global $USER;
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
        array()
        );
$isService = false;
$basketItems = array();
while ($arItems = $dbBasketItems->Fetch())
{
    if($arItems['PRODUCT_ID'] == 4992){
        $isService = true;
    }
    $basketItems[]=$arItems;
}
*/
#echo "<div style='display: none'>";
#echo "<pre>"; print_r($basketItems); echo "</pre>";
#echo "</div>";
//$arResult['JS_DATA']['PAY_SYSTEM'][1]['ACTIVE'] == 'N';
if (CSite::InGroup(array(9))){
    $arResult['JS_DATA']['IS_PARTNER'] = 'Y';
}else{
    $arResult['JS_DATA']['IS_PARTNER'] = 'N';
}
global $USER;
$USER_ID = $USER->GetID();
$rsUser = CUser::GetByID($USER_ID);
$arUser = $rsUser->Fetch();
 #echo "<pre style='display: none'>"; print_r($arUser); echo "</pre>";
if(CSite::InGroup(array(9))){
  $name = $arUser['WORK_COMPANY'];
}else{
  if($arUser['SECOND_NAME']){
      $name = $arUser['LAST_NAME'].' '.$arUser['NAME'].' '.$arUser['SECOND_NAME'];
  }else{
      $name = $arUser['LAST_NAME'].' '.$arUser['NAME'];
  }
}
$arResult['JS_DATA']['ORDER_PROP']['properties'][0]['VALUE'] = $name;


foreach ($arResult['JS_DATA']['ORDER_PROP']['properties'] as &$prop) {
    if ($prop['CODE'] == 'LOCATION') {
        if($prop['VALUE'][0] == $prop['DEFAULT_VALUE']){
            $prop['VALUE'] = '';
            $prop['DEFAULT_VALUE'] = '';
        }
    }
}
