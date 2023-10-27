<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?define("STOP_STATISTICS", true);
  define("NOT_CHECK_PERMISSIONS", true);
$APPLICATION->RestartBuffer();

use Bitrix\Iblock\Component\Base,
    VladClasses\ImportPartXML;

header('Content-Type: application/json');

$importPartXML = new importPartXML;

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if($request->isAjaxRequest()) {
    $action = $request['action'];
    $result = array();
    if($action == 'requestFiles') {
        if($files = $importPartXML->startHandlingFiles()){
            foreach ($files as $key => $file) {
                $resultCounts = $importPartXML->getCountPart($file);
                $result[] = array(
                    'basename' => basename($file),
                    'date' => date("d.m.y", filectime($file)),
                    'size' => $importPartXML->formatFileSize(filesize($file)),
                    'countPart' => $resultCounts['count'],
                    'countCash' => $resultCounts['cash']
                );
            }
            $error = false;
            $message = 'Запрос выполнен успешно';
            Base::sendJsonAnswer(array(
                'error' => $error,
                'noFiles' => false,
                "result" => $result,
                "message" => $message
            ));
        } else {
            Base::sendJsonAnswer(array(
                'error' => false,
                'noFiles' => true,
                "result" => true,
                "message" => 'Нет файлов выгрузки'
            ));
        }
    }elseif($action == 'buildingTableCash'){
        Base::sendJsonAnswer(array(
            'error' => false,
            'noFiles' => false,
            "result"   => $importPartXML->getCashTable($request['file']),
            "message"  => 'Таблица бонусов из файла выгрузки'
        ));
    }else{
        Base::sendJsonAnswer(array(
            'error' => true,
            'noFiles' => false,
            "result"   => false,
            "message"  => 'Ошибка запроса (не передано действие)'
        ));
    }
}else{
    Base::sendJsonAnswer(array(
        'error' => true,
        'noFiles' => false,
        "result"   => false,
        "message"  => 'Ошибка запроса (запрос выполнен не по AJAX)'
    ));
}