<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH .  '/config.php');
use \Bitrix\Sale,
    \Bitrix\Sale\Basket,
    \Bitrix\Main\Application,
    \Bitrix\Main\Context,
    \Bitrix\Currency\CurrencyManager;

CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");

define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);

$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";

$APPLICATION->ShowIncludeStat = false;

header('Content-Type: application/json'); //Задаём загаловок для ответа аяксу

$OPTION_CURRENCY = CCurrency::GetBaseCurrency(); //Подтягиваем валюту из настроек магазина

$request = Application::getInstance()->getContext()->getRequest();

$result = false; //Переменная результата для аякса
$partner = getPartner(); //Переменная партнёра является/не является
$newPartner = getNewPartner();
$basket = array(); //Подготовленный масив с данными корзины для обработки в JavaScript
$basketOBJUser = updateBasket(); //Вызываем функцию загрузки актуальной корзины в ней объекты, купоны, цены, скидки и всё что нужно
if($request->isAjaxRequest()) {
    $action = $request['action']; //Параметр с действием из аякса
    switch ($action) {
        case 'add': //Параметр обращения аякса по добавлению товара в корзину
            $search = false;
            $setFields = array(
                'QUANTITY' => isset($request['qty']) ? $request['qty'] : 1,
                'CURRENCY' => CurrencyManager::getBaseCurrency(),
                'LID' => Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider'
            );
            if($basketOBJUser['result']){ //В корзине чтото есть
                $basketItems = $basketOBJUser['basketAppEX']->getBasketItems();
                foreach ($basketItems as $bItem){
                    if($bItem->getProductId() == $request['id']){
                        $search = $bItem;
                        break;
                    }
                }

                if(!$search){ //Товар не найден значит в корзине ещё нет
                    $item = $basketOBJUser['basketAppEX']->createItem('catalog', $request['id']);
                    $item->setFields($setFields);
                    $basketOBJUser['basketAppEX']->save();
                    $result = $item->getField('ID');
                }else{ //Товар найден значит в отложенных
                    $search->setFields(array(
                        'DELAY' => 'N'
                    ));
                    $basketOBJUser['basketAppEX']->save();
                    $result = $search->getField('ID');
                }

            }else{ //Корзина пустая
                $item = $basketOBJUser['basketAppEX']->createItem('catalog', $request['id']);
                $item->setFields($setFields);
                $basketOBJUser['basketAppEX']->save();
                $result = $item->getField('ID');
            }

            $basketOBJUser = updateBasket();
        break;
        case 'addDelayByBasket':
            $basketItems = $basketOBJUser['basketAppEX']->getBasketItems();
            foreach ($basketItems as $bItem){
                if($bItem->getProductId() == $request['id']){
                    $bItem->setFields(array(
                        'DELAY' => 'N'
                    ));
                    break;
                }
            }
            $basketOBJUser['basketAppEX']->save();
            $result = true;
            $basketOBJUser = updateBasket();
        break;
        case 'addDelay':
            $item = $basketOBJUser['basketAppEX']->createItem('catalog', $request['id']);
            $item->setFields(array(
                'QUANTITY' => 1,
                'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                'DELAY' => 'Y'
            ));
            $basketOBJUser['basketAppEX']->save();
            $result = $item->getField('ID');
            $basketOBJUser = updateBasket();
        break;
        case 'setQty': //Параметр обращения аякса по обновлению товара в корзине
            $basketItemUPD = $basketOBJUser['basketAppEX']->getItemById($request['id']); //Получаем товар который надо обновить из GET параметра
            if ($basketItemUPD) //Если такой товар лежит в корзине
            {
                if ($request['qty'] > 0) { //Если количество больше единицы
                    $basketItemUPD->setField('QUANTITY', $request['qty']); //Обновляем количество
                    $result = true; //Результат для аякса истина
                    $basketOBJUser['basketAppEX']->save(); //Сохраняем корзину
                } else $result = false;
            } else $result = false; //Если такой товар не найден в корзине результат для аякса - ошибка
            $basketOBJUser = updateBasket(); //Обновляем объект
        break;
        case 'delete':
            $basketItemUPD = $basketOBJUser['basketAppEX']->getItemById($request['id']);
            if ($basketItemUPD) {
                $result = $basketItemUPD->delete(); //Удаляем товар из корзины
                if ($result->isSuccess()) { //Удачно?
                    $basketOBJUser['basketAppEX']->save(); //Сохраняем корзину
                    $result = true; //Результат для аякса истина
                } else { //Неудачно?
                    $result = false; //Результат для аякса ошибка
                }
            } else $result = false;
            $basketOBJUser = updateBasket(); //Обновляем объект
        break;
        case 'deleteDelay':
            $basketDelayItemUPD = $basketOBJUser['basketAppEX']->getItemById($request['id']);
            if ($basketDelayItemUPD) {
                $result = $basketDelayItemUPD->delete(); //Удаляем товар из корзины
                if ($result->isSuccess()) { //Удачно?
                    $basketOBJUser['basketAppEX']->save(); //Сохраняем корзину
                    $result = true; //Результат для аякса истина
                } else { //Неудачно?
                    $result = false; //Результат для аякса ошибка
                }
            } else $result = false;
            $basketOBJUser = updateBasket(); //Обновляем объект
        break;
        case 'delayInBasket':

        break;
        case 'coupon': //Параметр обращения аякса по добавлению купона/карты
            if (strlen(trim($_GET["value"])) == 13 && $_GET["value"] != 'EXTREME_BLACK' && $_GET["value"] != 'extreme_black' && $_GET["value"] != 'Extreme_black') { //Если введена карта 13 значное число (карты это не купоны по виду карты определяется либо купон на 5% либо купон на 10%)
                $card_number = (int)substr(trim($_GET["value"]), -5, 4); //Получаем последние цифры карты
                $coupon_name = false; //Параметр найдена карта или нет
                $card_type = (int)substr(trim($_GET["value"]), 0, 2); //Получаем тип карты по первым двум числам (05 - 5%, 10 - 10%)

                if ($card_type == 05 && $card_number >= 0 && $card_number <= 4718) { //Проверяем диапазон в 5% карте
                    $coupon_name = 'Скидка по карте 5%'; //Если всё верно применяем купон (по названию купона)
                }
                if ($card_type == 10 && $card_number >= 0 && $card_number <= 2518) { //Проверяем диапазон в 10% карте
                    $coupon_name = 'Скидка по карте 10%'; //Если всё верно применяем купон (по названию купона)
                }
                if ($coupon_name) { //Если карта актуальна
                    $result = \Bitrix\Sale\DiscountCouponsManager::add($coupon_name); //Добавляем купон
                    if (!$result) { //Если купон не найден нужно обязательно удалить потому что он применяется всё равно
                        \Bitrix\Sale\DiscountCouponsManager::delete($coupon_name, true); //Удаляем не найденый в системе купон
                    }
                    $basketOBJUser = updateBasket(); //Обновляем объект (с пересчётом по купону)
                } else { //Если карта неактуальна
                    $result = false; //Возвращаем ошибку для аякса
                }
            } else { //Если введена не карта а купон
                $result = \Bitrix\Sale\DiscountCouponsManager::add($_GET["value"]); //Добавляем купон
                if (!$result) { //Если купон не найден нужно обязательно удалить потому что он применяется всё равно
                    \Bitrix\Sale\DiscountCouponsManager::delete($_GET["value"], true); //Удаляем не найденый купон
                    $result = false; //Возвращаем ошибку для аякса
                }
                $basketOBJUser = updateBasket(); //Обновляем объект
            }
            break;
        case 'clearcoupon': //Параметр обращения аякса по удалению купона
            \Bitrix\Sale\DiscountCouponsManager::delete($_GET["value"], true);
            $result = true;
            $basketOBJUser = updateBasket(); //Обновляем объект
            break;
        case 'getBasket': //Параметр обращения аякса по получению объекта корзины
            $result = true;
            $basketOBJUser = updateBasket(); //Обновляем объект
            break;
        default:
            $result = true;
            break;
    }
}else $action = 'none';

if(isset($basketOBJUser) && $basketOBJUser['result']){
    if($partner || $newPartner){ //Если партнёр или новый партнёр
        if($partner){ //Если старый партнёр
            $minSummPartners = MIN_SUMM_PARTNER_CONST; //Заносим минимальную сумму по умолчанию
            $currentUser = $USER->GetById($USER->GEtID())->Fetch(); //Получаем пользователя
            if($partner && !empty($currentUser['UF_MIN_SUMM'])){ //Проверяем заполнена ли минимальная сумма (должна быть заполнена всегда, но на всякий случай)
                $minSummPartners = $currentUser['UF_MIN_SUMM']; //Если заполнена обновляем переменную
            }
        }

        if(array_key_exists(SERVICE_FEE_ID, $basketOBJUser['basketUser'])){ //Если в корзине лежит сервисный сбор
            if ($basketOBJUser['basketUser'][SERVICE_FEE_ID]['QUANTITY'] > 1){ //Если количество сервисного сбора более одного
                $basketItemUPD = $basketOBJUser['basketAppEX']->getItemById($basketOBJUser['basketUser'][SERVICE_FEE_ID]['ID']); //Получаем его
                $basketItemUPD->setField('QUANTITY', 1); //Меняем количество на единицу
                $basketOBJUser['basketAppEX']->save(); //Сохраняем
                $basketOBJUser = updateBasket(); //Обновляем объект
            }
        }else{ //Если партнёр и нет сервисного сбора в корзине
            Add2BasketByProductID(SERVICE_FEE_ID, 1, ['QUANTITY' => 1], []); //Добавляем единицу
            $basketOBJUser = updateBasket(); //Обновляем объект
        }
    }else{ //Если не партнёр
        if(array_key_exists(SERVICE_FEE_ID, $basketOBJUser['basketUser'])){ //Если сервисный с бор лежит в корзине
            $basketItem = $basketOBJUser['basketAppEX']->getItemById($basketOBJUser['basketUser'][SERVICE_FEE_ID]['ID']); //Получаем его
            $resultServ = $basketItem->delete(); //Удаляем
            if ($resultServ->isSuccess()) { //Удаление удачно?
                $basketOBJUser['basketAppEX']->save(); //Сохраняем корзину
                $basketOBJUser = updateBasket(); //Обновляем объект
            }
        }
    }
}

foreach($basketOBJUser['basketUser'] as $key => $itemsBasketUser){ //Перебираем корзину для формирования ответа аяксу
    $basketOBJUser['basketUser'][$key]['DIS'] = $basketOBJUser['pricesDEX'][$basketOBJUser['basketUser'][$key]['ID']];

    $basketOBJUser['basketUser'][$key]['PRICE_LABEL'] = ($basketOBJUser['basketUser'][$key]['DIS']['BASE_PRICE'] != $basketOBJUser['basketUser'][$key]['DIS']['PRICE'] ? '<s>' . FormatCurrency($basketOBJUser['basketUser'][$key]['DIS']["BASE_PRICE"], $OPTION_CURRENCY) . '</s> ' : '') . FormatCurrency($basketOBJUser['basketUser'][$key]['DIS']['PRICE'], $OPTION_CURRENCY);

    $basketOBJUser['basketUser'][$key]['QUANTITY'] = floatval($basketOBJUser['basketUser'][$key]['QUANTITY']);

    if($basketOBJUser['basketUser'][$key]['DIS']['PRICE'] == 0)
    {
        $basketOBJUser['giftBasketCollection'] = array();
    }

    if($basketOBJUser['basketUser'][$key]['DIS']['BASE_PRICE'] != $basketOBJUser['basketUser'][$key]['DIS']['PRICE'])
    {
        $basketOBJUser['basketUser'][$key]['EX_DISCAUNT'] = true;
        $basketOBJUser['basketUser'][$key]['OLD_PRICE_FORMAT'] = FormatCurrency($basketOBJUser['basketUser'][$key]['DIS']['BASE_PRICE'], $OPTION_CURRENCY);
        $basketOBJUser['basketUser'][$key]['NEW_PRICE_FORMAT'] = FormatCurrency($basketOBJUser['basketUser'][$key]['DIS']['PRICE'], $OPTION_CURRENCY);
        $basketOBJUser['basketUser'][$key]['PRICE_QUANTITY_FORMAT'] = FormatCurrency($basketOBJUser['basketUser'][$key]['QUANTITY'] * $basketOBJUser['basketUser'][$key]['DIS']['PRICE'], $OPTION_CURRENCY);
        $basketOBJUser['basketUser'][$key]['PRICE_ECONOM_FORMAT'] = FormatCurrency(($basketOBJUser['basketUser'][$key]['DIS']['BASE_PRICE'] * $basketOBJUser['basketUser'][$key]['QUANTITY']) - ($basketOBJUser['basketUser'][$key]['DIS']['PRICE'] * $basketOBJUser['basketUser'][$key]['QUANTITY']), $OPTION_CURRENCY);
    }
    else
    {
        $basketOBJUser['basketUser'][$key]['EX_DISCAUNT'] = false;
        $basketOBJUser['basketUser'][$key]['PRICE_QUANTITY_FORMAT'] = FormatCurrency($basketOBJUser['basketUser'][$key]['QUANTITY'] * $basketOBJUser['basketUser'][$key]['PRICE'], $OPTION_CURRENCY);
    }


    $totalPrice += ($basketOBJUser['basketUser'][$key]['DIS']['PRICE'] * $basketOBJUser['basketUser'][$key]['QUANTITY']);
    //if($basketOBJUser['basketUser'][$key]['EX_DISCAUNT'])
        $totalBase += ($basketOBJUser['basketUser'][$key]['DIS']['BASE_PRICE'] * $basketOBJUser['basketUser'][$key]['QUANTITY']);
    //else $totalBase = false;
}

if(!empty($basketOBJUser['delayBasket']) && $basketOBJUser['resultDelay']){
    foreach($basketOBJUser['delayBasket'] as $key => $itemsBasketUser){
        $basketOBJUser['delayBasket'][$key]['PRICE_QUANTITY_FORMAT'] = FormatCurrency($basketOBJUser['delayBasket'][$key]['PRICE'], $OPTION_CURRENCY);
    }
}

$basket['basketD7'] = $basketOBJUser;
$basket['length'] = ($basketOBJUser['result'] && is_countable($basketOBJUser['basketUser'])) ? count($basketOBJUser['basketUser']) : 0;
$basket['lengthDelay'] = ($basketOBJUser['resultDelay'] && is_countable($basketOBJUser['delayBasket'])) ? count($basketOBJUser['delayBasket']) : 0;
$basket['price'] = $totalPrice;
$basket['baseprice'] = $totalBase;
$basket['basepriceFormat'] = $totalBase ? FormatCurrency($totalBase, $OPTION_CURRENCY) : false;
$basket['totalEconom'] = $totalBase > $totalPrice ? FormatCurrency($totalBase - $totalPrice, $OPTION_CURRENCY) : false;
$basket['priceFormat'] = FormatCurrency($totalPrice, $OPTION_CURRENCY);
$basket['partner'] = $partner;
$basket['newPartner'] = $newPartner;
$basket['newPartnerCash'] = getNewPartner() ? getCash($USER->GetID(), round($totalPrice)) : false;
$basket['passMin'] = $totalPrice >= $minSummPartners;
$basket['price_partner_text'] = 'Минимальная сумма заказа ' . $minSummPartners . ' руб.';
$basket['price_order_text'] = 'Оформить заказ';

echo json_encode([
    'action'    => $action,
	'result'	=> $result,
	'basket'	=> $basket
]);