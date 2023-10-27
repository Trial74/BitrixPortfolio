<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use VladClasses\ImportPartXML;

$IMPORT_XML = new ImportPartXML;

if($argv[1] !== TOKEN_CRON) {
    $IMPORT_XML->addLog('', true);
    $IMPORT_XML->addLog('Скрипт выгрузки запущен не кроном');
    $IMPORT_XML->addLog('', false, true);
    die();
};

if($files = $IMPORT_XML->startHandlingFiles()){
    $IMPORT_XML->addLog('', true);
    $IMPORT_XML->addLog('Подготовлено файлов выгрузки: ' . count($files));
    $arrResultCash = array();
    foreach($files as $key => $file){
        $IMPORT_XML->addLog('Обработка файла выгрузки: ' . $key);
        array_push($arrResultCash, $IMPORT_XML->getCashTable(basename($file)));
        $IMPORT_XML->addLog('Получены данные из файла: ' . basename($file) . '. Партнёров с бонусами в файле: ' . count($arrResultCash[$key]));
        $IMPORT_XML->moveToArchive($file);
        $IMPORT_XML->addLog('Файл перемещён в архив');
    }

    if(count($arrResultCash) > 0){
        $IMPORT_XML->addLog('Данные из ' . count($arrResultCash) . ' файлов сформированы в массив. Начало выгрузки в контрагентов');
        foreach($arrResultCash as $key => $item){
            $IMPORT_XML->addLog('Начало выгрузки пакета: ' . $key);
            if(count($item) > 0){
                $IMPORT_XML->addLog('Контрагентов в пакете: ' . $key . ' - ' . count($item));
                foreach($item as $contragent){
                    $IMPORT_XML->setCashPart($contragent['user'], $contragent['cash']);
                }
                $IMPORT_XML->addLog('Пакет: ' . $key . ' выгружен');
            }else{
                $IMPORT_XML->addLog('В пакете: ' . $key . ' нет данных');
            }
        }
    }else{
        $IMPORT_XML->addLog('Нет данных для выгрузки');
    }

    $IMPORT_XML->addLog('', false, true);
}else{
    $IMPORT_XML->addLog('', true);
    $IMPORT_XML->addLog('Нет файлов выгрузки либо в файлах нет партнёров/бонусов');
    $IMPORT_XML->addLog('', false, true);
}
