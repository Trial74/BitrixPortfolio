<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use VladClasses\extremeCronClass;

$CRON_FEEDBACK = new extremeCronClass;

if($argv[1] !== TOKEN_CRON_DELAY_BASKET) {
    $CRON_FEEDBACK->addLog('', true, false, 'DelayBasket');
    $CRON_FEEDBACK->addLog('Запущено не кроном', false, false);
    $CRON_FEEDBACK->addLog('', false, true, 'DelayBasket');
    die();
}

$usersPush = array();
$delayItemsUsers = array();
$resultPush = array(
    'SUCC' => 0,
    'ERR'  => 0
);

$CRON_FEEDBACK->addLog('', true, false, 'DelayBasket');

$dateStart = new \Bitrix\Main\Type\DateTime;
$dateEnd = new \Bitrix\Main\Type\DateTime;

$dateStart->setTime(0, 0, 0)->add('-2 days');
$dateEnd->setTime(0, 0, 0)->add('-1 days');

$filter = array(
    '!FUSER.USER_ID' => null,
    '=ORDER_ID' => null,
    '=LID' => 's1',
    'DELAY' => 'Y',
    array(
        '>DATE_UPDATE' => $dateStart,
        '<DATE_UPDATE' => $dateEnd,
    )
);

$basketListDb = \Bitrix\Sale\Internals\BasketTable::getList(array(
    'select' => array('USER_ID' => 'FUSER.USER_ID', 'EMAIL' => 'FUSER.USER.EMAIL', 'FUSER_USER_NAME' => 'FUSER.USER.NAME'),
    'filter' => $filter,
    'order' => array('USER_ID' => 'ASC')
));


if($basketListDb->getSelectedRowsCount() > 0) $CRON_FEEDBACK->addLog('Запрос выполнен успешно. Количество записей - ' . $basketListDb->getSelectedRowsCount(), false, false);
else{
    $CRON_FEEDBACK->addLog('Запрос выполнен, записей по выборке нет', false, false);
    $CRON_FEEDBACK->addLog('', false, true, 'DelayBasket');
    die();
}

while($basketFetch = $basketListDb->fetch()){
    $delayItemsUsers[$basketFetch['USER_ID']] = array(
        'USER' => $basketFetch['USER_ID']
    );
}

$CRON_FEEDBACK->addLog('Обработка выборки закончена. Кол во корзин без дублей пользователей - ' . count($delayItemsUsers), false, false);
$CRON_FEEDBACK->addLog('Обработка пользователей корзин ....', false, false);

if(count($delayItemsUsers) > 1){
    $filter = array(
        'LOGIC' => 'OR'
    );
    foreach ($delayItemsUsers as $filterUserBasket){
        array_push($filter, array("=ID" => $filterUserBasket['USER']));
    }
}else{
    $filter = array(
        '=ID' => $delayItemsUsers[array_key_first($delayItemsUsers)]['USER']
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
    $CRON_FEEDBACK->addLog('', false, true, 'DelayBasket');
    die();
}

$CRON_FEEDBACK->addLog('Начало рассылки уведомлений ....', false, false);

require($_SERVER['DOCUMENT_ROOT'] . "/api/notification_extreme/notification.php");
$notification__Feed = new EX_Notification();
foreach ($usersPush as $userPush){
    $resultJSON = $notification__Feed->EX_setPush('Кажется, вы забыли про них!', 'Товары в избранном ждут вас 💜 Загляните в "Избранное", вам недавно понравились эти товары. Чтобы завершить покупку, просто сложите их в корзину и оформите заказ 😉', $userPush['UF_FIREBASE_TOKEN'], '/?page=personal/cart&extreme-mobile=Y&delay=Y');
    $arrResultPush[] = json_decode($resultJSON);
    $CRON_FEEDBACK->addLog('Пользователь ID-'.$userPush['ID'], false, false);
}

foreach ($arrResultPush as $result){
    if($result->success) $resultPush['SUCC']++;
    else $resultPush['ERR']++;
}

$CRON_FEEDBACK->addLog('Рассылка закончена', false, false);
$CRON_FEEDBACK->addLog('Всего отправлено - ' . count($resultPush), false, false);
$CRON_FEEDBACK->addLog('Удачно отправлено - ' . $resultPush['SUCC'], false, false);
$CRON_FEEDBACK->addLog('Неудачно отправлено - ' . $resultPush['ERR'], false, false);
$CRON_FEEDBACK->addLog('', false, true, 'DelayBasket');

?>