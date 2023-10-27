<?
/*if($_SERVER['HTTP_USER_AGENT'] == 'extreme-look-app' || $_SERVER['HTTP_USER_AGENT'] == 'extreme-look-app-vlad' || VERSION == 'mobile'){
    $APPLICATION->IncludeComponent(
        "altop:stories.vlad",
        ".default",
        array(
            "CACHE_TYPE" => "N"
        ),
        false
    );
}*/
?>

<?//MENU_HORISONTAL//
    $APPLICATION->IncludeComponent(
        "bitrix:menu",
        "horizontal_one_level_app",
        array(
            "ROOT_MENU_TYPE" => "more",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "3",
            "MENU_CACHE_USE_GROUPS" => "N",
            "MENU_CACHE_GET_VARS" => array(
            ),
            "MAX_LEVEL" => "1",
            "CHILD_MENU_TYPE" => "more",
            "USE_EXT" => "N",
            "ALLOW_MULTI_SELECT" => "N",
            "CACHE_SELECTED_ITEMS" => "N",
            "COMPONENT_TEMPLATE" => "horizontal_one_level_app",
            "DELAY" => "N",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO"
        ),
        false
    );
?>

<div class="block-slider_app" style="overflow: hidden;">
    <?include('block_slider/slider.php');?>
</div>

<style>
    .block-title-catalog-home{
        font-family: 'Graphik LCG';
        font-size: 26px;
        font-weight: 600;
        margin: 32px 16px 16px;
    }
</style>

<div class="block-title-catalog-home">
	Каталог товаров
</div>

<?include('catalog/section.php');?>

<div class="ex-banners-links-block">
    <div class="ex-banners-block-mobile">
        <div class="ex-banner-stat-part"><a href="/?page=stat-partner&extreme-mobile=Y"><img width="100%" src="/images/banners/mobile/stat-part-mobile.png" alt="Стать партнёром"></a></div>
        <div class="ex-banner-promotions"><a href="javascript: void(0)" data-open="https://extreme-look.ru/live/" class="open-other-link"><img width="100%" src="/images/banners/mobile/live_vid_mobile.png" alt="Live Blog"></a></div>
        <div class="ex-banner-blog"><a href="/?page=blog/list&extreme-mobile=Y"><img width="100%" src="/images/banners/mobile/blog-mobile.jpg" alt="Lash блог Extreme Look"></a></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        app.methods.newAppMessage();
    });
</script>
