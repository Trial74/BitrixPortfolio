<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);
$APPLICATION->RestartBuffer();

use Bitrix\Iblock\Component\Base,
    Bitrix\Highloadblock as HL,
    Bitrix\Main\Entity,
    Bitrix\Main\Mail\Event;

header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if($request->isAjaxRequest())
    if($request['action'] == "pushForm"){
        $C_FIELD = array();
        $UF_FIELD = '';
        $data = $request['data'];
        $i = 1;
        $hlbl = 10;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        foreach($data as $key => $value){
            if($key == 'NAME_FORM'){
                $C_FIELD['FORM_NAME'] = $value['VALUE'];
                $C_FIELD['THEME'] = $value['VALUE'];
            }
            else {
                $C_FIELD['NUMBER_' . $i] = $key;
                $C_FIELD['LABEL_' . $i] = $value['LABEL'];
                $C_FIELD['DATA_' . $i] = $value['VALUE'];
                $i++;
            }
            $UF_FIELD .= $key . ' ' . $value['LABEL'] . '|' . $value['VALUE'];
        }
        unset($i, $key, $value);

        $resSend = Event::send(array(
            "EVENT_NAME" => "MIXON_FORM",
            "LID" => 's4',
            'MESSAGE_ID' => 179,
            "C_FIELDS" => $C_FIELD
        ));

        if($entity_data_class::add(array("UF_FORM" => $UF_FIELD))) $resHL = true;
        else $resHL = false;

        Base::sendJsonAnswer(array(
            "error" => false,
            'resSend' => $resSend->getId(),
            'resHL' => $resHL
        ));
    }
    else
        Base::sendJsonAnswer(array(
            "error" => true,
            "result" => false
        ));