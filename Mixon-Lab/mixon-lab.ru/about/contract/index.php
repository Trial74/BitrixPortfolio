<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "about_company",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR."include/contract_manufacturing.php"
    ),
    false,
    array("HIDE_ICONS" => "Y")
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>