<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

global $USER;
$USER_ID = $USER->GetID();
$rsUser = CUser::GetByID($USER_ID);
$arUser = $rsUser->Fetch();

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

$arFilter = array('IBLOCK_ID' => 27);
$rsSect = CIBlockSection::GetList(Array("NAME"=>"ASC"), $arFilter);
while ($arSect = $rsSect->GetNext())
{
    $arResult['JS_DATA']['FORBIDDEN_COUNTRIES'][] = $arSect['NAME'];
}

if($USER_ID){
  $rsUser = CUser::GetByID($USER_ID);
  $arResult['JS_DATA']['USER'] = $rsUser->Fetch();

  $arResult['JS_DATA']['USER']['COUNTRY_NAME'] = GetCountryByID($arResult['JS_DATA']['USER']['PERSONAL_COUNTRY']);

  $res = \Bitrix\Main\UserGroupTable::getList(array('filter' => array('USER_ID' => $USER_ID)));
  while($arGroup = $res->Fetch()){
    $arResult['JS_DATA']['USER']['GROUPS'][] = $arGroup['GROUP_ID'];
  }
}else{
  $arResult['JS_DATA']['USER'] = [];
}
if(getUserCashBack()){
    $arResult['JS_DATA']['TOTAL']['CASH'] = getCash($USER_ID, $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE']);
}


//16 - оплата онлайн сайт
//25 - оплата онлайн сайт двухстадийка
//26 - оплата онлайн приложение
//30 - оплата онлайн приложение двухстадийка
//31 - оплата онлайн lashmaker
//32 - оплата онлайн lashmaker двухстадийка
//18 - Сбер рассрочка
//29 - сбер рассрочка приложение
//14 - тинькофф рассрочка 4 месяца сайт
//27 - тинькофф рассрочка 4 месяца приложение
//15 - тинькофф рассрочка 6 месяцев сайт
//28 - тинькофф рассрочка 6 месяцев приложение
//1 - наличный рассчёт
//22 - оплата по реквизитам
//23 - безналичный рассчёт
//24 - оплата по счёту розница
//8 - оплата по счёту партнёры
//33 - MC-Credit

//---- СТАРЫЕ УСЛОВИЯ В ORDER AJAX ----//
/*
((paySystemId == 13 || paySystemId == 26) && !uspartner && this.result.TOTAL.ORDER_TOTAL_PRICE <= 50000 && newCouponGift) ||
((paySystemId == 27 || paySystemId == 28) && uspartner && this.result.TOTAL.ORDER_TOTAL_PRICE >= 50000 && close) ||
((paySystemId == 27 || paySystemId == 28) && !uspartner) ||
(paySystemId == 29 && uspartner && this.result.TOTAL.ORDER_TOTAL_PRICE >= 50000 && close) ||
(paySystemId == 29 && !uspartner) ||
(paySystemId == 22 && uspartner) ||
(paySystemId == 14 && close) ||
(paySystemId == 15 && close) ||
(paySystemId == 16 && close) ||
(paySystemId == 18 && close) ||
(paySystemId == 25 && close) ||
(paySystemId == 24 && !uspartner) ||
(paySystemId == 8 && uspartner) ||
(paySystemId == 30 && !uspartner && this.result.TOTAL.ORDER_TOTAL_PRICE <= 50000 && newCouponGift)*/
//---- СТАРЫЕ УСЛОВИЯ В ORDER AJAX ----//


$partner = getPartner() || getNewPartner();

foreach ($arResult['JS_DATA']['PAY_SYSTEM'] as $key => &$paySystem){
    if($partner){
        if(isset($arParams['PAY_DEFAULT_PART']) && !empty($arParams['PAY_DEFAULT_PART'])){
            if(isset($paySystem['CHECKED']) && $paySystem['CHECKED'] == 'Y' && (int)$paySystem['ID'] != (int)$arParams['PAY_DEFAULT_PART'])
                unset($paySystem['CHECKED']);

            if((int)$paySystem['ID'] == (int)$arParams['PAY_DEFAULT_PART'] && !isset($paySystem['CHECKED']))
                $paySystem['CHECKED'] = 'Y';
        }
    }else{
        if(isset($arParams['PAY_DEFAULT_PART']) && !empty($arParams['PAY_DEFAULT_PART'])) {
            if (isset($paySystem['CHECKED']) && $paySystem['CHECKED'] == 'Y' && (int)$paySystem['ID'] != (int)$arParams['PAY_DEFAULT_ROZN'])
                unset($paySystem['CHECKED']);

            if ((int)$paySystem['ID'] == (int)$arParams['PAY_DEFAULT_ROZN'] && !isset($paySystem['CHECKED']))
                $paySystem['CHECKED'] = 'Y';
        }
    }
}
unset($key, $paySystem, $partner);