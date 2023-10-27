<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>
<?//ob_end_clean();?>
<?$APPLICATION->IncludeComponent(
    "altop:generator.sertificate.vlad",
    ".default",
    array(
        "COMPONENT_TEMPLATE" => ".default",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "ID_PARTNER" => $_POST['ID'],
        "DATA" => $_POST['data']
    ),
    false
);?>
<?exit();?>