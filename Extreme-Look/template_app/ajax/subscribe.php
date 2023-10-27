<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!isset($_POST) || empty($_POST) || empty($_POST['itemId']) || empty($_POST['action'])){
    echo json_encode([
        'result' => 'Error',
        'errors' => 'Error by data',
        '$_POST' => $_POST
    ]);
}else{
    if($_POST['action'] == 'addSubscribe') {
        if ($_POST['userId'] == 'false' || $_POST['userId'] == 0) $userId = false;
        else $userId = $_POST['userId'];
        $subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;
        $contactTypes = $subscribeManager->contactTypes;
        $subscribeData = array(
            'USER_CONTACT' => $_POST['contact'] ? $_POST['contact'] : false,
            'ITEM_ID' => $_POST['itemId'],
            'SITE_ID' => 's1',
            'CONTACT_TYPE' => \Bitrix\Catalog\SubscribeTable::CONTACT_TYPE_EMAIL,
            'USER_ID' => $userId
        );
        $subscribeId = $subscribeManager->addSubscribe($subscribeData);
        if ($subscribeId) {
            echo json_encode([
                'result' => true,
                'errors' => 'N',
                'subId' => $subscribeId
            ]);
        } else {
            $errorObject = current($subscribeManager->getErrors());
            $errors = array('error' => true);
            if ($errorObject) {
                $errors['message'] = $errorObject->getMessage();
                if ($errorObject->getCode() == $subscribeManager::ERROR_ADD_SUBSCRIBE_ALREADY_EXISTS) {
                    $errors['setButton'] = true;
                }
            }
            echo json_encode([
                'result' => false,
                'errors' => $errors,
            ]);
        }
    }elseif($_POST['action'] == 'unSubscribe'){
        $idSub = array();
        $idSub[] = $_POST['idSub'];

        $subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;
        if(!$subscribeManager->deleteManySubscriptions($idSub, $_POST['itemId']))
        {
            $errorObject = current($subscribeManager->getErrors());
            if($errorObject) {
                $errors = $errorObject->getMessage();
            }
            echo json_encode([
                'result' => false,
                'errors' => $errors,
            ]);
        }
        else{
            echo json_encode([
                'result' => true,
                'errors' => 'N',
            ]);
        }
    }
}