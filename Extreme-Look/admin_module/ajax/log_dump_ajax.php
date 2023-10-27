<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>

<?use Bitrix\Iblock\Component\Base;

header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$result = false;
$error = false;

if($request->isAjaxRequest()) {
    $pathLog = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/php_interface/log.txt';
    $pathLogImport = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/extremelook/log/log_import_contr_cash.txt';
    $pathLogCron = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/extremelook/log/log_cron.txt';
    $action = $request['action'];
    if ($action == 'loadFile') {
        if(!$result = file_get_contents($pathLog))
            $error = true;
    }elseif ($action == 'removeData') {
        if(!!$result = file_put_contents($pathLog, ''))
            $error = true;
    }elseif($action == 'loadFileLogsImport'){
        if(!$result = file_get_contents($pathLogImport))
            $error = true;
    }elseif ($action == 'removeDataLogsImport') {
        if (!!$result = file_put_contents($pathLogImport, ''))
            $error = true;
    }elseif ($action == 'loadFileLogsCron') {
        if (!$result = file_get_contents($pathLogCron))
            $error = true;
    }elseif ($action == 'removeDataLogsCron') {
        if (!!$result = file_put_contents($pathLogCron, ''))
            $error = true;
    }
}
Base::sendJsonAnswer(array(
    "error" => $error,
    "result" => $result
));