<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";

$APPLICATION->ShowIncludeStat = false;
header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if($request->isAjaxRequest()) {
    $action = $request['action'];
    switch ($action){
        case 'getQuant':
            $quantRes = catalogQuantity($request['quant']);
            echo json_encode([
                'result'	=> $quantRes
            ]);
        break;
        default:
            echo json_encode([
                'result'	=> false
            ]);
        break;
    }
}