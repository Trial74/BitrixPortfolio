<?define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if($request->isAjaxRequest()) {
    $action = $request->get("action");
    $user = $request->get("user");

    switch ($action){
        case 'setView':
            if(CModule::IncludeModule("iblock")) {
                if($user == 'Y' && !empty($request->get("userID"))){
                    $PROPERTYES = array("LIVE_USERS_VIEWED", "LIVE_COUNT");
                    $VALUES = array();
                    foreach ($PROPERTYES as $property) {
                        $OBprops = CIBlockElement::GetProperty($request->get("iblock"), $request->get("id"), "sort", "asc", array("CODE" => $property));
                        while ($ob = $OBprops->GetNext()) {
                            if($property == 'LIVE_USERS_VIEWED')
                                $VALUES[$property][] = $ob['VALUE'];
                            if($property == 'LIVE_COUNT')
                                $VALUES[$property] = $ob['VALUE'];
                        }
                    }
                    $ViewedCount = (int) $VALUES['LIVE_COUNT'];
                    $ViewedCount++;
                    $VALUES['LIVE_COUNT'] = $ViewedCount;
                    array_push($VALUES['LIVE_USERS_VIEWED'], $request->get("userID"));
                    CIBlockElement::SetPropertyValuesEx($request->get("id"), $request->get("iblock"), $VALUES);
                    Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                        "error" => 'N',
                        "result" => 'Просмотрено. Авторизован: ' . $request->get("userID")
                    ));
                }else if($user == 'N'){
                    $OBprops = CIBlockElement::GetProperty($request->get("iblock"), $request->get("id"), "sort", "asc", array("CODE" => "LIVE_COUNT"));
                    if($arProps = $OBprops->Fetch()){
                        $ViewedCount = (int) $arProps['VALUE'];
                        $ViewedCount++;
                        CIBlockElement::SetPropertyValuesEx($request->get("id"), $request->get("iblock"), array("LIVE_COUNT" => $ViewedCount));
                    }
                    Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                        "error" => 'N',
                        "result" => 'Просмотрено. Не авторизован'
                    ));
                }
            }else{
                Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                    "error" => 'Y',
                    "result" => 'Ошибка подключения модуля IBLOCK'
                ));
            }
        break;
        case 'setLike':
            if(CModule::IncludeModule("iblock")) {
                if($user == 'Y' && !empty($request->get("userID"))){
                    $PROPERTYES = array("LIVE_USERS_LIKED", "LIVE_LIKES");
                    $VALUES = array();
                    foreach ($PROPERTYES as $property) {
                        $OBprops = CIBlockElement::GetProperty($request->get("iblock"), $request->get("id"), "sort", "asc", array("CODE" => $property));
                        while ($ob = $OBprops->GetNext()) {
                            if($property == 'LIVE_USERS_LIKED')
                                $VALUES[$property][] = $ob['VALUE'];
                            if($property == 'LIVE_LIKES')
                                $VALUES[$property] = $ob['VALUE'];
                        }
                    }
                    $ViewedCount = (int) $VALUES['LIVE_LIKES'];
                    $ViewedCount++;
                    $VALUES['LIVE_LIKES'] = $ViewedCount;
                    array_push($VALUES['LIVE_USERS_LIKED'], $request->get("userID"));
                    CIBlockElement::SetPropertyValuesEx($request->get("id"), $request->get("iblock"), $VALUES);
                    Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                        "error" => 'N',
                        "result" => 'Лайк поставлен: ' . $request->get("userID"),
                        "data" => $ViewedCount
                    ));
                }else{
                    Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                        "error" => 'Y',
                        "result" => 'Ошибка пользователя'
                    ));
                }
            }
        break;
        case 'setComment':
            if(CModule::IncludeModule("iblock")) {
                $el = new CIBlockElement;

                $PROP = array();
                $PROP[1212] = $request->get("name");
                $PROP[1210] = $request->get("text");
                $PROP[1211] = $request->get("user") == 'N' ? null : $request->get("user");
                $PROP[1213] = 0;
                $PROP[1214] = $request->get("id");

                $arLoadPropArray = Array(
                    "MODIFIED_BY"    => $USER->GetID(),
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"      => $request->get("iblock"),
                    "PROPERTY_VALUES"=> $PROP,
                    "NAME"           => "Комментарий к посту " . $request->get("id"),
                    "ACTIVE"         => "N",
                );

                if($COMMENT_ID = $el->Add($arLoadPropArray))
                    Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                        "error" => 'N',
                        "result" => $COMMENT_ID
                    ));
                else
                    Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                        "error" => 'Y',
                        "result" => $el->LAST_ERROR
                    ));
            }else{
                Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                    "error" => 'Y',
                    "result" => 'Ошибка подключения модуля IBLOCK'
                ));
            }
        break;
        case 'checkCaptcha':
            if(!$APPLICATION->CaptchaCheckCode($request->get("captcha_word"), $request->get("captcha_code")))
                Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                    "error" => 'Y',
                    "result" => 'Неверно введена капча'
                ));
            else
                Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                    "error" => 'N',
                    "result" => 'Капча введена верно'
                ));
        break;
        case 'returnCapcha':

        break;
        case 'setShare':
            $VALUES = array();

            $OBprops = CIBlockElement::GetProperty($request->get("iblock"), $request->get("id"), "sort", "asc", array("CODE" => 'LIVE_REPOSTS'));
            while ($ob = $OBprops->GetNext()) {
                    $VALUES['LIVE_REPOSTS'] = $ob['VALUE'];
            }

            $repostsCount = (int) $VALUES['LIVE_REPOSTS'];
            $repostsCount++;
            $VALUES['LIVE_REPOSTS'] = $repostsCount;
            CIBlockElement::SetPropertyValuesEx($request->get("id"), $request->get("iblock"), $VALUES);
            Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                "error" => 'N',
                "result" => 'Репост сделан'
            ));
        break;
        case 'setLikeComment':
            $PROPERTYES = array("LIVE_COMMENT_USERS_LIKES", "LIVE_COMMENT_LIKES");
            $VALUES = array();
            foreach ($PROPERTYES as $property) {
                $OBprops = CIBlockElement::GetProperty($request->get("iblock"), $request->get("id"), "sort", "asc", array("CODE" => $property));
                while ($ob = $OBprops->GetNext()) {
                    if($property == 'LIVE_COMMENT_USERS_LIKES')
                        $VALUES[$property][] = $ob['VALUE'];
                    if($property == 'LIVE_COMMENT_LIKES')
                        $VALUES[$property] = $ob['VALUE'];
                }
            }
            $likesCommentCount = (int) $VALUES['LIVE_COMMENT_LIKES'];
            $likesCommentCount++;
            $VALUES['LIVE_COMMENT_LIKES'] = $likesCommentCount;
            array_push($VALUES['LIVE_COMMENT_USERS_LIKES'], $request->get("user"));

            CIBlockElement::SetPropertyValuesEx($request->get("id"), $request->get("iblock"), $VALUES);
            Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                "error" => 'N',
                "data" => $likesCommentCount,
                "debug" => $VALUES,
                "result" => 'Лайк на коммент поставлен'
            ));
        break;
        default:
            Bitrix\Iblock\Component\Base::sendJsonAnswer(array(
                "error" => 'Y',
                "result" => 'No action',
                "data" => $user
            ));
        break;
    }
}