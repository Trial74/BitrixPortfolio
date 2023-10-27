<?
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once( $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH .  '/config.php');

use Bitrix\Iblock\Component\Base;

$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";

$APPLICATION->ShowIncludeStat = false;
header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$action = isset($_GET['action']) ? $_GET['action'] : 'none';

$result = false;
if($request->isAjaxRequest()) {
    $action = $request['action'];
    switch ($action) {
        case 'login':
            if (isset($_GET['username']) && isset($_GET['password']))
                $result = $USER->Login($_GET['username'], $_GET['password'], "Y") === true;
            if ($result) {
                $fields = Array(
                    "UF_AUTH_APP" => true,
                );
                $USER->Update($USER->GetID(), $fields);
            }
            break;
        case 'logout':
            $IDoutUser = $USER->GetID();
            $result = $USER->Logout();
            $fields = Array(
                "UF_AUTH_APP" => false,
            );
            $USER->Update($IDoutUser, $fields);
            break;
        case 'delac':
            $IDdelUser = $USER->GetID();
            $result = $USER->Logout();
            $fields = Array(
                "UF_AUTH_APP" => false,
            );
            $USER->Update($IDoutUser, $fields);
            vlog('Запрос на удаление аккаунта - ' . $IDdelUser);
            break;
        case 'edit':
            if (isset($_GET["USER_PASSWORD"]) &&
                isset($_GET["USER_PASSWORD_CONFIRM"]) &&
                isset($_GET["USER_STREET"]) &&
                isset($_GET["USER_MOBILE"]) &&
                isset($_GET["USER_CITY"]) &&
                isset($_GET["USER_ZIP"]) &&
                isset($_GET["EMAIL"]) &&
                isset($_GET["FIO"])
            ) {
                global $USER;
                $userID = $USER->GetID();
                if ($userID) {
                    $NAME = explode(" ", htmlspecialchars($_GET["FIO"]));
                    $EMAIL = htmlspecialchars($_GET["EMAIL"]);
                    $PASSWORD = addslashes($_GET["USER_PASSWORD"]);
                    $REPASSWORD = addslashes($_GET["USER_PASSWORD_CONFIRM"]);
                    $PERSONAL_STREET = htmlspecialchars($_GET["USER_STREET"]);
                    $PERSONAL_MOBILE = htmlspecialchars($_GET["USER_MOBILE"]);
                    $PERSONAL_CITY = htmlspecialchars($_GET["USER_CITY"]);
                    $PERSONAL_ZIP = htmlspecialchars($_GET["USER_ZIP"]);

                    $user = new CUser;
                    $fields = Array(
                        "NAME" => BX_UTF === true ? $NAME[0] : iconv("UTF-8", "windows-1251//IGNORE", $NAME[0]),
                        "LAST_NAME" => BX_UTF === true ? $NAME[1] : iconv("UTF-8", "windows-1251//IGNORE", $NAME[1]),
                        "SECOND_NAME" => BX_UTF === true ? $NAME[2] : iconv("UTF-8", "windows-1251//IGNORE", $NAME[2]),
                        "PERSONAL_STREET" => BX_UTF === true ? $PERSONAL_STREET : iconv("UTF-8", "windows-1251//IGNORE", $PERSONAL_STREET),
                        "PERSONAL_CITY" => BX_UTF === true ? $PERSONAL_CITY : iconv("UTF-8", "windows-1251//IGNORE", $PERSONAL_CITY),
                        "PERSONAL_ZIP" => BX_UTF === true ? $PERSONAL_ZIP : iconv("UTF-8", "windows-1251//IGNORE", $PERSONAL_ZIP),
                        "PERSONAL_MOBILE" => BX_UTF === true ? $PERSONAL_MOBILE : iconv("UTF-8", "windows-1251//IGNORE", $PERSONAL_MOBILE),
                        "EMAIL" => $EMAIL,
                        "PASSWORD" => $PASSWORD,
                        "CONFIRM_PASSWORD" => $REPASSWORD
                    );

                    if (empty($PASSWORD)) {
                        unset($fields["PASSWORD"]);
                        unset($fields["REPASSWORD"]);
                    }

                    if (!$user->Update($userID, $fields)) {
                        $result = [
                            "message" => $user->LAST_ERROR,
                            "heading" => "Ошибка",
                            "reload" => false
                        ];
                    } else {
                        $result = [
                            "message" => "Информация успешно сохранена",
                            "heading" => "Сохранено",
                            "reload" => true
                        ];
                    }
                } else {
                    $result = [
                        "message" => "Требуется авторизация",
                        "heading" => "Ошибка",
                        "reload" => false
                    ];
                }

            } else {
                $result = array(
                    "message" => "Ошибка передачи формы",
                    "heading" => "Ошибка",
                    "reload" => false
                );
            }
            break;
        default:
            $result = false;
            break;
    }

    Base::sendJsonAnswer(array(
        "result" => $result
    ));
}