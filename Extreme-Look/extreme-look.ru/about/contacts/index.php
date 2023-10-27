<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//Контакты также инклюдятся в приложении
$APPLICATION->SetTitle("Контакты");?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR."/about/contacts/include.php"
    ),
    false,
    array("HIDE_ICONS" => "Y")
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>