<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arResGroups = array();
$resultDB = \Bitrix\Main\GroupTable::getList(array(
    'select'  => array('ID', 'NAME'),
    'filter'  => array('!ID' => array('1', '2', '3', '4', '5', '6', '7', '8', '16', '20'))
));

while ($arGroup = $resultDB->fetch()) {
    $arResGroups[$arGroup['ID']] = $arGroup['NAME'];
}
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "IN_GROUP" => array(
            "PARENT" => "BASE",
            "NAME" => "Группы партнёров (выбери действующие чтобы скрипт проверял ссылки для активации)",
            "TYPE" => "LIST",
            "VALUES" => $arResGroups,
            "MULTIPLE" => "Y",
            "SIZE" => 10
        ),
    ),
);
unset($arResGroups, $arGroup, $resultDB);
?>