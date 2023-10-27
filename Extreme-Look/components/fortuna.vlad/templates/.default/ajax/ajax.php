<?define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);

if(!empty($_REQUEST["REQUEST_URI"]))
    $_SERVER["REQUEST_URI"] = $_REQUEST["REQUEST_URI"];

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if($request->isAjaxRequest()) {
    $action = $request->getPost("action");
    switch ($action){
        case 'addCouponContact':
            $dataGift = $request->getPost("dataGift");
            $dataUser = $request->getPost("dataUser");
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $chars = substr(str_shuffle($permitted_chars), 0, 7); //Генерация случайной строи для купона
            $errorMess = '';

            /*** Добавление контакта в Б24 начало***/
            require_once ($_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/lib/webhooks_B24/contact_hook/crest.php');
            $resultCallCRM = CRest::call(
                'crm.contact.add',
                [
                    "fields" =>
                        [
                            "NAME" => $dataUser['name'],
                            "EMAIL" => [["VALUE" => $dataUser['mail'], "VALUE_TYPE" => "HOME"]],
                            "PHONE" => [["VALUE" => $dataUser['tel'], "VALUE_TYPE" => "MOBILE"]],
                            "OPENED" => "Y",
                            "ASSIGNED_BY_ID" => 12, //Ответственный - Белла Левитская
                            "TYPE_ID" => 4,
                            "SOURCE_ID" => 9
                        ],
                    "params" => [
                        "REGISTER_SONET_EVENT" => "Y"
                    ]
                ]
            );
            if(!empty($resultCallCRM['result']))
                $errorMess = 'Инфа успешно добавлена в битрикс 24';
            else
                $errorMess = 'Ошибка добавления инфы в битрикс 24';
            /*** Добавление контакта в Б24 конец***/

            /*** Генерация купона по призу начало***/
            $dateTwoD = new \Bitrix\Main\Type\DateTime();
            $fields = array(
                "DISCOUNT_ID" => $dataGift['gift'], // ИД скидки
                "ACTIVE" => "Y",
                'ACTIVE_FROM' => new \Bitrix\Main\Type\DateTime(),
                'ACTIVE_TO' => $dateTwoD->add("+3 day"), //Купон действует три дня
                "TYPE" => \Bitrix\Sale\Internals\DiscountCouponTable::TYPE_ONE_ORDER,
                "COUPON" => $chars,
                "DATE_APPLY" => false
            );
            $resultAddCoup = \Bitrix\Sale\Internals\DiscountCouponTable::add($fields);
            if($resultAddCoup)
                $errorMess .= ', купон успешно создан: ' . $resultAddCoup->getId();
            else
                $errorMess .= ', ошибка создания купона: ' . $resultAddCoup->getErrorMessages();
            /*** Генерация купона по призу конец***/

            /*** Отправка письма с купоном пользователю начало***/
            $resultSend = \Bitrix\Main\Mail\Event::send(array(
                "EVENT_NAME" => "TEST_MAIL",
                "LID" => 's1',
                'MESSAGE_ID' => 178, //ИД шаблона
                "C_FIELDS" => array(
                    "EMAIL" => $dataUser['mail'],
                    "COUPON" => $chars,
                    "GIFT_NAME" => $dataGift['text'],
                    "GIFT_DISCRIPTION" => $dataGift['description']
                ),
            ));
            if($resultSend)
                $errorMess .= ', письмо успешно отправлено: ' . $resultSend->getId();
            else
                $errorMess .= ', ошибка отправки письма: ' . $resultSend->getErrorMessages();
            /*** Отправка письма с купоном пользователю начало***/

            /*** Добавление контакта админку сайта начало***/
            $iBlockFort = new CIBlockElement;
            $PROP = array();
            $PROP[1070] = $dataUser['name'];
            $PROP[1071] = $dataUser['mail'];
            $PROP[1072] = $dataUser['tel'];
            $PROP[1073] = $dataGift['text'];
            $PROP[1074] = $chars;
            $PROP[1075] = $errorMess;
            $arLoadProductArray = Array(
                "MODIFIED_BY"    => 10354,
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID"      => 119,
                "PROPERTY_VALUES"=> $PROP,
                "NAME"           => "Заполнена форма - " . $dataUser['name'],
                "ACTIVE"         => "Y"
            );
            $iBlockFort->Add($arLoadProductArray);
            /*** Добавление контакта админку сайта конец***/

            echo json_encode(
                [
                    "error" => false,
                    "result" => $dataGift
                ]
            );
        break;
        case 'checkEmail': //Проверка на дубли
            $dataFilter = $request->getPost("dataFilter");
            $arSelect = Array("ID");
            $arFilter = Array(
                "IBLOCK_ID"=>IntVal(119),
                "ACTIVE"=>"Y",
                array(
                    "LOGIC" => "OR",
                    array("PROPERTY_EMAIL" => $dataFilter['mail']),
                    array("PROPERTY_PHONE" => $dataFilter['phone'])
                ),
            );
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
            while($ob = $res->GetNextElement())
                $arFields = $ob->GetFields();

            if($arFields)
                echo json_encode(
                    [
                        "result" => true
                    ]
                );
            else
                echo json_encode(
                    [
                        "result" => false
                    ]
                );

        break;
        default:
            echo json_encode(
                [
                    "result" => 'No action'
                ]
            );
        break;
    }
}