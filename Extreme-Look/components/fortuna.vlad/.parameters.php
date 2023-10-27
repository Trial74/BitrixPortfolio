<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "GROUPS" => array(
        "VISUAL" => array(
            "NAME" => "Внешний вид",
            "SORT" => "100"
        )
    ),
    "PARAMETERS" => array(
        "F_TEMPLATE" => array(
            "PARENT" => "VISUAL",  // если нет - ставится ADDITIONAL_SETTINGS
            "NAME" => "Внешний вид",
            "TYPE" => "LIST",
            "REFRESH" => "N",
            "MULTIPLE" => "N",
            "VALUES" => array(
                't-fortuna_default' => 'Стандартный',
                't-fortuna_winter' => 'Зимний',
                't-fortuna_spring' => 'Весенний'
            ),
            "ADDITIONAL_VALUES" => "N",
            "DEFAULT" => "default"
        ),
    ),
);?>