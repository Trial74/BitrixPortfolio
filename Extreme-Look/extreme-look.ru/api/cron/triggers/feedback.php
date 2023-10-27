<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use VladClasses\extremeCronClass;

$CRON_FEEDBACK = new extremeCronClass;

if($argv[1] !== TOKEN_CRON_FEEDBACK) {
    $CRON_FEEDBACK->addLog('', true, false, 'Feedback');
    $CRON_FEEDBACK->addLog('Запущено не кроном', false, false);
    $CRON_FEEDBACK->addLog('', false, true, 'Feedback');
    die();
}

$dateStart = new \Bitrix\Main\Type\DateTime;
$dateEnd = new \Bitrix\Main\Type\DateTime;

$dateStart->setTime(0, 0, 0)->add('-14 days');
$dateEnd->setTime(0, 0, 0)->add('-13 days');

$orders = array();
$usersPush = array();
$filter = array();
$arrResultPush = array();
$resultPush = array(
    'SUCC' => 0,
    'ERR'  => 0
);

$CRON_FEEDBACK->addLog('', true, false, 'Feedback');

$ordersListDb = \Bitrix\Sale\Order::getList([
    'select' => array('ID', 'USER_ID', 'STATUS_ID'),
    'filter' => array(
        ">DATE_INSERT" => $dateStart,
        "<DATE_INSERT" => $dateEnd,
        "CANCELED" =>"N"
    ),
    'order' => array('ID' => 'DESC')
]);

if($ordersListDb->getSelectedRowsCount() > 0) $CRON_FEEDBACK->addLog('Запрос выполнен успешно. Количество записей - ' . $ordersListDb->getSelectedRowsCount(), false, false);
else{
    $CRON_FEEDBACK->addLog('Запрос выполнен, записей по выборке нет', false, false);
    $CRON_FEEDBACK->addLog('', false, true, 'Feedback');
    die();
}

while($order = $ordersListDb->fetch()){
    $orders[$order['USER_ID']] = array(
        'USER' => $order['USER_ID'],
        'ORDER_ID' => $order['ID']
    );
}

$CRON_FEEDBACK->addLog('Обработка выборки закончена. Кол во заказов без дублей пользователей - ' . count($orders), false, false);
$CRON_FEEDBACK->addLog('Обработка пользователей заказов ....', false, false);

if(count($orders) > 1){
    $filter = array(
        'LOGIC' => 'OR'
    );
    foreach ($orders as $filterUserOrder){
        array_push($filter, array("=ID" => $filterUserOrder['USER']));
    }
}else{
    $filter = array(
        '=ID' => $orders[array_key_first($orders)]['USER']
    );
}

$resultUser = \Bitrix\Main\UserTable::getList(
    array(
        'filter' => $filter,
        'select' => array('ID', 'NAME', 'UF_FIREBASE_TOKEN')
    )
);

while ($fetchUser = $resultUser->fetch()) {
    if(!empty($fetchUser['UF_FIREBASE_TOKEN'])){
        $usersPush[] = $fetchUser;
        $CRON_FEEDBACK->addLog('Пользователь - ('.$fetchUser['ID'].') '.$fetchUser['NAME'].' В списке на рассылку', false, false);
    }

}

$CRON_FEEDBACK->addLog('Обработка пользователей заказов завершена. Пользователей с токенами - ' . count($usersPush), false, false);

if(count($usersPush) == 0){
    $CRON_FEEDBACK->addLog('', false, true, 'Feedback');
    die();
}

$CRON_FEEDBACK->addLog('Начало рассылки уведомлений ....', false, false);

require($_SERVER['DOCUMENT_ROOT'] . "/api/notification_extreme/notification.php");
$notification__Feed = new EX_Notification();
foreach ($usersPush as $userPush){
    $resultJSON = $notification__Feed->EX_setPush('Заказ №-'.$orders[$userPush['ID']]['ORDER_ID'].' доставлен 💜', 'Сообщите нам, все ли понравилось? Мы готовы стать еще лучше! 📝', $userPush['UF_FIREBASE_TOKEN'], '/?page=redirect&action=feedback&orderid='.$orders[$userPush['ID']]['ORDER_ID']);
    $arrResultPush[] = json_decode($resultJSON);
    $CRON_FEEDBACK->addLog('Заказ №-'.$orders[$userPush['ID']]['ORDER_ID'].' доставлен 💜', false, false);
}

foreach ($arrResultPush as $result){
    if($result->success) $resultPush['SUCC']++;
    else $resultPush['ERR']++;
}

$CRON_FEEDBACK->addLog('Рассылка закончена', false, false);
$CRON_FEEDBACK->addLog('Всего отправлено - ' . count($resultPush), false, false);
$CRON_FEEDBACK->addLog('Удачно отправлено - ' . $resultPush['SUCC'], false, false);
$CRON_FEEDBACK->addLog('Неудачно отправлено - ' . $resultPush['ERR'], false, false);
$CRON_FEEDBACK->addLog('', false, true, 'Feedback');
?>