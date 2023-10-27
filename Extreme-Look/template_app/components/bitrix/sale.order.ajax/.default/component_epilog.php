<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(CSite::InGroup(array(9)) && $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE'] < 15000 && !isset($_GET['ORDER_ID'])){
  LocalRedirect('/personal/cart/?order_sum_back=1');
}
#echo "<pre style='display: none'>"; print_r($arResult['JS_DATA']); echo "</pre>";
