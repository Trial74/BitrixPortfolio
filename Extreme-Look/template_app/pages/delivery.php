<style>
    .title-opt > h1 {
        font-size: 50px !important;
    }
    .delivery-app{
        padding: 15px 10px 10px 15px;
    }
</style>
<div class="delivery-app">
    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
        array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR."/about/delivery/include.php"
        ),
        false,
        array("HIDE_ICONS" => "Y")
    );?>
</div>
