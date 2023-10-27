<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>

<?use Bitrix\Iblock\Component\Base;

global $USER;
header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if($request->isAjaxRequest()) {
    $action = $request['action'];
    if($action == 'PUSH'){

        $IDs = $request['ids'];
        $subj = $request['tittle'];
        $mess = $request['message'];
        $nameVar = $request['name_var'];
        $url = isset($request['url']) ? $request['url'] : '';
        $image = isset($request['image']) ? $request['image'] : '';

        require($_SERVER['DOCUMENT_ROOT'] . "/api/notification_extreme/notification.php");
        $notification__NEW = new EX_Notification();
        foreach ($IDs as $id){
            $arUSER = $USER->GetByID($id)->fetch();

            if(!empty($arUSER['NAME'])) $nameRes = $arUSER['NAME'];
            elseif(!empty($nameVar)) $nameRes = $nameVar;
            else $nameRes = 'Уважаемый покупатель';

            $resSubj = $subj;
            $resMess = $mess;
            if(preg_match_all('|#(.*)#|Uis', $resSubj, $q)) {
                $resSubj = preg_replace('|(#).*(#)|Uis', $nameRes, $subj);
            }

            if(preg_match_all('|#(.*)#|Uis', $resMess, $w)) {
                $resMess = preg_replace('|(#).*(#)|Uis', $nameRes, $mess);
            }

            if (isset($arUSER['UF_FIREBASE_TOKEN']) && !empty($arUSER['UF_FIREBASE_TOKEN'])) {
                $resultJSON = $notification__NEW->EX_setPush($resSubj, $resMess, $arUSER['UF_FIREBASE_TOKEN'], $url, $image);
                $result[] = json_decode($resultJSON);
            }
        }
    }elseif($action == 'BOT_PUSH'){

        require($_SERVER['DOCUMENT_ROOT'] . "/api/telegram/src/Api.php");
        $bot = new Api();

        $IDs = $request['ids'];
        $message = $request['message'];
        $format = $request['format'];
        $photo = $request['photo'];
        $count_true = 0;
        $count_false = 0;

        if(empty($message) || !is_array($IDs)){
            $result = false;
        }else{
            if(!empty($photo)){
                foreach ($IDs as $id){
                    $resSend = $bot->sendPhotoByUserChat($id, $message, $photo, $format);
                    if($resSend['ok']) $count_true++;
                    else $count_false++;
                }
            }else{
                foreach ($IDs as $id){
                    $resSend = $bot->sendMessByUserChat($id, $message, $format);
                    if($resSend['ok']) $count_true++;
                    else $count_false++;
                }
            }


            $result = array(
                    'all' => count($IDs),
                    'succ' => $count_true,
                    'fail' => $count_false
                );
        }

    }else{
        $result[] = array(
            'results' => 'No Action'
        );
    }

}else{
    $result[] = array(
        'results' => 'No AjaxRequest'
    );
}
Base::sendJsonAnswer(array(
    "result" => $result
));?>