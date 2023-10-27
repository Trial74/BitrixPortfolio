<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);
$APPLICATION->RestartBuffer();

use Bitrix\Iblock\Component\Base,
    Bitrix\Highloadblock as HL,
    Bitrix\Main\Entity,
    Bitrix\Main\Mail\Event;

header('Content-Type: application/json');

require_once ($_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/lib/webhooks_B24/class.php');
$HOOK_B24 = new extreme_B24;

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if($request->isAjaxRequest()) {
    $C_FIELD = array('ASSIGNED' => 31011);
    $UF_FIELD = '';
    $data = $request['data'];
    $i = 1;
    $hlbl = 10;
    $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    $action = $request->getPost("action");
    $data = $request->getPost("data");
    $today = date("Y-m-d H:i:s");

    switch($action) {
        case "get-offer":
            $C_FIELD['THEME'] = 'Заполнена форма "Получить предложение" Лэндинг';
            $C_FIELD['NAME'] = $data['OFFER-NAME'];
            $C_FIELD['PHONE'] = $data['OFFER-PHONE'];
            $C_FIELD['MAIL'] = $data['OFFER-MAIL'];
            $UF_FIELD = 'Заполнена форма "Получить предложение Лэндинг"| ' . $data["OFFER-NAME"] . ' | ' . $data['OFFER-PHONE'] . ' | ' . $data['OFFER-MAIL'] . ' | ' . $today;

            $resSend = Event::send(array(
                "EVENT_NAME" => "MIXON_FORM",
                "LID" => 's4',
                'MESSAGE_ID' => 180,
                "C_FIELDS" => $C_FIELD
            ));

            if($entity_data_class::add(array("UF_FORM" => $UF_FIELD))) $resHL = true;
            else $resHL = false;

            $C_FIELD['THEME'] = 'Форма "Получить предложение" Лэндинг - ' . $C_FIELD['NAME'];
            $HOOK_B24::addContact($C_FIELD, true);

            Base::sendJsonAnswer(array(
                "error" => false,
                'resSend' => $resSend,
                'fields' => $C_FIELD,
                'uf_fields' => $UF_FIELD,
                'data' => $data
            ));

        break;
        case "get-price":
            $C_FIELD['THEME'] = 'Заполнена форма "Узнать стоимость" Лэндинг';
            $C_FIELD['NAME'] = $data['PRICE-NAME'];
            $C_FIELD['PHONE'] = $data['PRICE-PHONE'];
            $C_FIELD['MAIL'] = $data['PRICE-MAIL'];
            $C_FIELD['PROIZ'] = $data['PRICE-PROIZ'];
            $C_FIELD['OBIEM'] = $data['PRICE-OBIEM'];

            $UF_FIELD = 'Заполнена форма "Узнать стоимость Лэндинг"| ' . $data["PRICE-NAME"] . ' | ' . $data['PRICE-PHONE'] . ' | ' . $data['PRICE-MAIL'] . ' | ' . $data['PRICE-PROIZ'] . ' | ' . $data['PRICE-OBIEM']  . ' | ' . $today;

            $resSend = Event::send(array(
                "EVENT_NAME" => "MIXON_FORM",
                "LID" => 's4',
                'MESSAGE_ID' => 180,
                "C_FIELDS" => $C_FIELD
            ));

            if($entity_data_class::add(array("UF_FORM" => $UF_FIELD))) $resHL = true;
            else $resHL = false;

            $C_FIELD['THEME'] = 'Форма "Узнать стоимость" Лэндинг - ' . $C_FIELD['NAME'];
            $HOOK_B24::addContact($C_FIELD, true);

            Base::sendJsonAnswer(array(
                "error" => false,
            ));

        break;
        case "get-feedback":

            $C_FIELD['THEME'] = 'Заполнена форма "Обратной связи" Лэндинг';
            $C_FIELD['NAME'] = $data['CONTACTS-NAME'];
            $C_FIELD['PHONE'] = $data['CONTACTS-PHONE'];
            $C_FIELD['MAIL'] = $data['CONTACTS-MAIL'];
            $C_FIELD['COMPANY'] = $data['CONTACTS-COMPANY'];

            $UF_FIELD = 'Заполнена форма "Обратная связь Лэндинг"| ' . $data["CONTACTS-NAME"] . ' | ' . $data['CONTACTS-PHONE'] . ' | ' . $data['CONTACTS-MAIL'] . ' | ' . $today;

            $resSend = Event::send(array(
                "EVENT_NAME" => "MIXON_FORM",
                "LID" => 's4',
                'MESSAGE_ID' => 180,
                "C_FIELDS" => $C_FIELD
            ));

            if($entity_data_class::add(array("UF_FORM" => $UF_FIELD))) $resHL = true;
            else $resHL = false;

            $C_FIELD['THEME'] = 'Форма "Обратной связи" Лэндинг - ' . $C_FIELD['NAME'];
            $HOOK_B24::addContact($C_FIELD, true);

            Base::sendJsonAnswer(array(
                "error" => false,
            ));
        break;
        case "test-message":

            $C_FIELD['THEME'] = 'Тестовое письмо для проверки';
            $C_FIELD['NAME'] = 'Влад';
            $C_FIELD['PHONE'] = '89507443404';
            $C_FIELD['MAIL'] = 'it@extreme-look.ru';

            $resSend = Event::send(array(
                "EVENT_NAME" => "MIXON_FORM",
                "LID" => 's4',
                'MESSAGE_ID' => 185,
                "C_FIELDS" => $C_FIELD
            ));

            //$C_FIELD['ASSIGNED'] = 20789;
            //$HOOK_B24::addContact($C_FIELD, true);

            Base::sendJsonAnswer(array(
                "error" => false,
                "res" => $resSend->isSuccess(),
                "msg_errors" => $resSend->getErrorMessages()
            ));
        break;
    }
}