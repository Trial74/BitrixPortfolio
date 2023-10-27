<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
use Bitrix\Iblock\Component\Base;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";

$APPLICATION->ShowIncludeStat = false;
header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if($request->isAjaxRequest()) {
    $action = $request['action'];
    switch ($action){
        case 'setReview':
            $el = new CIBlockElement;
            $arFields = array(
                "NAME" => 'Отзыв от  ' . ConvertTimeStamp(time(), "FULL"),
                "IBLOCK_ID" => 70,
                "ACTIVE" => "N",
                "ACTIVE_FROM" => ConvertTimeStamp(time(), "FULL"),
                "PROPERTY_VALUES" => array(
                    "PRODUCT_ID" => $request['idItem'],
                    "RATING" => array(
                        "ID" => $request['evaluation']
                    ),
                    "COMMENT" => Array(
                        "VALUE" => Array (
                            "TEXT" => $request['message'],
                        )
                    ),
                    "NAME" => $request['userName'],
                    "USER_ID" => $request['userId'],
                    "LIKES" => 0
                ),
            );
            if($elementId = $el->Add($arFields)) {
                Base::sendJsonAnswer(array(
                    'result' => true
                ));
            }
            else{
                Base::sendJsonAnswer(array(
                    'result' => false
                ));
            }
            break;
        case 'addLike':
            $revElementObj = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "IBLOCK_ID" => 70, "ID" => $request['idRev']), false, false, array("ID", "IBLOCK_ID", "PROPERTY_NAME", "PROPERTY_LIKES", "PROPERTY_COMMENT", "PROPERTY_RATING", "PROPERTY_PRODUCT_ID", "PROPERTY_USER_ID"));
            $revElement = $revElementObj->fetch();

            $el = new CIBlockElement;
            $addLikeByElmentArr = Array(
                "PROPERTY_VALUES"=> array(
                    "PRODUCT_ID" => $revElement['PROPERTY_PRODUCT_ID_VALUE'],
                    "RATING" => array(
                        "ID" => $revElement['PROPERTY_RATING_ENUM_ID']
                    ),
                    "COMMENT" => Array(
                        "VALUE" => Array (
                            "TEXT" => $revElement['PROPERTY_COMMENT_VALUE']['TEXT'],
                            "TYPE" => $revElement['PROPERTY_COMMENT_VALUE']['TYPE']
                        )
                    ),
                    "NAME" => $revElement['PROPERTY_NAME_VALUE'],
                    "USER_ID" => $revElement['PROPERTY_USER_ID_VALUE'],
                    "LIKES" => $request['likeCount']
                ),
            );
            $res = $el->Update($request['idRev'], $addLikeByElmentArr);
            Base::sendJsonAnswer(array(
                'result' => $res
            ));
            break;
        case 'setQuestion':
            $el = new CIBlockElement;
            $arFields = array(
                "NAME" => 'Вопрос из карточки товара - ' . $request['itemName'],
                "IBLOCK_ID" => 110,
                "ACTIVE" => "Y",
                "PROPERTY_VALUES" => array(
                    "QUESTION_NAME" => $request['userName'],
                    "QUESTION_EMAIL" => $request['userEmail'],
                    "QUESTION_MESS" => $request['userQue'],
                    "QUESTION_ITEM" => $request['itemName']
                ),
            );
            if($elementId = $el->Add($arFields)) {
                Base::sendJsonAnswer(array(
                    'result' => true
                ));
            }
            else{
                Base::sendJsonAnswer(array(
                    'result' => false
                ));
            }
            break;
        default:
            Base::sendJsonAnswer(array(
                'result' => false
            ));
            break;
    }
}