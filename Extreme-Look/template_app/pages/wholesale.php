<style>
    .block-wholesale{
        padding: 10px 15px 10px 15px;
    }
</style>
<div class="block-wholesale">
    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
        array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR."/about/opt_price/include.php"
        ),
        false,
        array("HIDE_ICONS" => "Y")
    );?>
</div>
