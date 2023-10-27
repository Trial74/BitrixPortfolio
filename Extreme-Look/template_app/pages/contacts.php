<style>
    .cont-container{
        padding-top: 30px !important;
        padding-bottom: 0 !important;
        padding-right: 10px;
        padding-left: 15px;
    }
    iframe{
        position: static;
    }
</style>
<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR."/about/contacts/include.php"
    ),
    false,
    array("HIDE_ICONS" => "Y")
);?>
<!--<div class="block" style="overflow: hidden; max-width: 100%;">
    <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Aa2f00724babd009d3cbaa07502884335c52836bda6cb233990573abacb66fb55&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe>
</div>-->
