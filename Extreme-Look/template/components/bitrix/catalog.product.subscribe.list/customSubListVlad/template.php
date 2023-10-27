<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$GLOBALS['LIST_SUBSCRIPTIONS'] = $arParams['LIST_SUBSCRIPTIONS']; //Мой код возвращаем только массивы с конкретными идентификаторами подписок и ИД товаров пользователя чтобы кинуть его в JS'ку
