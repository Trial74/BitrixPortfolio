<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
    "NAME" => GetMessage("Активация партнёра"),
    "DESCRIPTION" => GetMessage("Заявка на активацию партнёра"),
    "PATH" => array(
        "ID" => "act_partner",
        "CHILD" => array(
            "ID" => "act_part",
            "NAME" => "Активация партнёра"
        )
    )
);
?>