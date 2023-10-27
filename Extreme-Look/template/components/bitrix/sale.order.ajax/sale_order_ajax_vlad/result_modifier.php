<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$component = $this->__component;
$component::scaleImages($arResult["JS_DATA"], $arParams["SERVICES_IMAGES_SCALING"]);

if(Bitrix\Main\Loader::includeModule("currency")) {
	CJSCore::Init(array("currency")); 
	$currencyFormat = CCurrencyLang::GetFormatDescription($arResult["BASE_LANG_CURRENCY"]);?>

	<script type="text/javascript">
		BX.Currency.setCurrencyFormat('<?=$arResult["BASE_LANG_CURRENCY"]?>', <?=CUtil::PhpToJSObject($currencyFormat, false, true, true)?>);
	</script>
<?}
if(getUserCashBack()){
    $arResult['JS_DATA']['TOTAL']['CASH'] = getCash($USER->GetId(), $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE']);
}

//---- СТАРЫЕ УСЛОВИЯ В ORDER AJAX ----//
/*
(paySystemId == 16 && !uspartner && this.result.TOTAL.ORDER_TOTAL_PRICE <= 50000) ||
(paySystemId == 25 && !uspartner && this.result.TOTAL.ORDER_TOTAL_PRICE <= 50000) ||
((paySystemId == 14 || paySystemId == 15) && uspartner && this.result.TOTAL.ORDER_TOTAL_PRICE >= 50000 && close) ||
((paySystemId == 14 || paySystemId == 15) && !uspartner) ||
(paySystemId == 18 && uspartner && this.result.TOTAL.ORDER_TOTAL_PRICE >= 50000 && close) ||
(paySystemId == 18 && !uspartner) ||
(paySystemId == 22 && uspartner) ||
(paySystemId == 26 && close) ||
(paySystemId == 27 && close) ||
(paySystemId == 28 && close) ||
(paySystemId == 29 && close) ||
(paySystemId == 30 && close) ||
(paySystemId == 24 && !uspartner) ||
(paySystemId == 8 && uspartner) ||
(paySystemId == 33 && administrator)*/
//---- СТАРЫЕ УСЛОВИЯ В ORDER AJAX ----//

$partner = getAllPartner();

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