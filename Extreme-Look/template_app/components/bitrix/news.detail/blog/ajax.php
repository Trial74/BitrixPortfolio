<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
use Bitrix\Iblock\Component\Base;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/mobileapp/config.php");
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";

$APPLICATION->ShowIncludeStat = false;
header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if($request->isAjaxRequest()) {
    $action = $request['action'];
    $code = $request['symbol'];
    if($code) {
        switch ($action) {
            case 'section':
                $arFilter = Array('IBLOCK_ID' => IntVal(23), 'GLOBAL_ACTIVE'=>'Y', "CODE" => 'valiki_dlya_laminirovaniya', 'UF_SECTION_HIDE' => 1);
                $db_list = CIBlockSection::GetList(array('ID' => 'asc'), $arFilter, false, array('ID'));
                if($arFields = $db_list->GetNext()) {
                    Base::sendJsonAnswer(array(
                        'error' => false,
                        'message' => 'success',
                        'section' => true,
                        'result' => $arFields
                    ));
                }else{
                    Base::sendJsonAnswer(array(
                        'error' => true,
                        'message' => 'Раздел не найден или не активен',
                    ));
                }
                break;
            case 'product':
                $arSelect = Array("ID");
                $arFilter = Array("IBLOCK_ID" => IntVal(CATALOG_IBLOCK), "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "CODE" => $code);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                $ob = $res->GetNextElement();
                if($arFields = $ob->GetFields()){
                    Base::sendJsonAnswer(array(
                        'error' => false,
                        'message' => 'success',
                        'section' => false,
                        'result' => $arFields
                    ));
                }else{
                    Base::sendJsonAnswer(array(
                        'error' => true,
                        'message' => 'Товар не найден или не активен',
                    ));
                }
                break;
            default:
                Base::sendJsonAnswer(array(
                    'error' => true,
                    'message' => 'Не задано действие'
                ));
                break;
        }
    }else{
        Base::sendJsonAnswer(array(
            'error' => true,
            'message' => 'Не задан символьный код'
        ));
    }
}