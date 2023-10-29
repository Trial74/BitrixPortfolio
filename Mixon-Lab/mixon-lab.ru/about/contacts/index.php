<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "about_contacts",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR."include/about/about_contacts.php"
    ),
    false,
    array("HIDE_ICONS" => "Y")
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
