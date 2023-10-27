<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
if(!$isSiteClosed) {
    if(!CSite::inDir(SITE_DIR."index.php")) {?>
        </div>
        </div>
        </div>
        </div>
        <?if(!CSite::inDir(SITE_DIR."personal/order/make/")) {?>
            <div class="hidden-print viewed-wrapper" data-entity="parent-container" style="display: none;">
                <div class="container">
                    <div class="row viewed">
                        <div class="col-xs-12">
                            <div class="h2" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
                                <?//VIEWED_TITLE//?>
                                <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer_viewed_title.php"), false);?>
                            </div>
                            <?//VIEWED//?>
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR."include/footer_viewed.php",
                                    "AREA_FILE_RECURSIVE" => "N",
                                    "EDIT_MODE" => "html",
                                ),
                                false,
                                array("HIDE_ICONS" => "Y")
                            );?>
                        </div>
                    </div>
                </div>
            </div>
            <?if(in_array("BIG_DATA", $arSettings["SITE_BLOCKS"]["VALUE"])) {?>
                <div class="hidden-print bigdata-wrapper" data-entity="parent-container" style="display: none;">
                    <div class="container-fluid<?=(CSite::inDir(SITE_DIR."personal/cart/") || CSite::inDir(SITE_DIR."personal/subscribe/") || CSite::inDir(SITE_DIR."personal/orders/") || CSite::inDir(SITE_DIR."personal/private/") || CSite::inDir(SITE_DIR."about/") || CSite::inDir(SITE_DIR."news/") || CSite::inDir(SITE_DIR."feedback/") || CSite::inDir(SITE_DIR."catalog/index.php") || CSite::inDir(SITE_DIR."index.php") || CSite::inDir(SITE_DIR."articles/") || remBigDataBlock($_SERVER['REQUEST_URI']) || isset($_GET['q'])) ? '' : ' p-tabs-bigdata'?>">
                        <div class="row bigdata">
                            <div class="col-xs-12">
                                <div class="h1" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
                                    <?//BIGDATA_TITLE//?>
                                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer_bigdata_title.php"), false);?>
                                </div>
                                <?//BIGDATA//?>
                                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR."include/footer_bigdata.php",
                                        "AREA_FILE_RECURSIVE" => "N",
                                        "EDIT_MODE" => "html",
                                    ),
                                    false,
                                    array("HIDE_ICONS" => "Y")
                                );?>
                            </div>
                        </div>
                    </div>
                </div>
            <?}
        }
    }
}
//FEEDBACK//
if(in_array("FEEDBACK", $arSettings["SITE_BLOCKS"]["VALUE"])) {?>
    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
        array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR."include/footer_feedback.php"
        ),
        false,
        array("HIDE_ICONS" => "Y")
    );?>
<?}
if(!$isSiteClosed && in_array("BOTTOM_MENU", $arSettings["SITE_BLOCKS"]["VALUE"])) {?>
    <div class="hidden-print bottom-menu-wrapper">
        <div class="bottom-menu">
            <div class="container-fluid<?=(CSite::inDir(SITE_DIR."personal/cart/") || CSite::inDir(SITE_DIR."personal/subscribe/") || CSite::inDir(SITE_DIR."personal/orders/") || CSite::inDir(SITE_DIR."personal/private/") || CSite::inDir(SITE_DIR."about/") || CSite::inDir(SITE_DIR."news/") || CSite::inDir(SITE_DIR."feedback/") || CSite::inDir(SITE_DIR."catalog/index.php") || CSite::inDir(SITE_DIR."index.php") || CSite::inDir(SITE_DIR."articles/") || remBigDataBlock($_SERVER['REQUEST_URI']) || isset($_GET['q'])) ? '' : ' p-tabs-bigdata'?>">
                <div class="row">
                    <div class="col-xs-12">
                        <!--BOTTOM_MENU-->
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/footer_bottom_menu.php"
                            ),
                            false,
                            array("HIDE_ICONS" => "Y")
                        );?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?}?>
<div class="hidden-print footer-wrapper">
    <div class="container-fluid<?=(CSite::inDir(SITE_DIR."personal/cart/") || CSite::inDir(SITE_DIR."personal/subscribe/") || CSite::inDir(SITE_DIR."personal/orders/") || CSite::inDir(SITE_DIR."personal/private/") || CSite::inDir(SITE_DIR."about/") || CSite::inDir(SITE_DIR."news/") || CSite::inDir(SITE_DIR."feedback/") || CSite::inDir(SITE_DIR."catalog/index.php") || CSite::inDir(SITE_DIR."index.php") || CSite::inDir(SITE_DIR."articles/") || remBigDataBlock($_SERVER['REQUEST_URI']) || isset($_GET['q'])) ? '' : ' p-tabs-bigdata'?>">
        <div class="row">
            <div class="footer">
                <div class="col-xs-12 col-md-4">
                    <div class="footer__copyright">
                        <?//COPYRIGHT//?>
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer_copyright.php"), false);?>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <?//SOCIAL//?>
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR."include/footer_social.php"
                        ),
                        false,
                        array("HIDE_ICONS" => "Y")
                    );?>
                </div>
                <div class="col-xs-12 col-md-3">
                    <ul class="store">
                        <li><a href='https://play.google.com/store/apps/details?id=ru.extreme_look_app.extremelook'><img height="45px" width="140px" alt='Доступно в Google Play' src='https://play.google.com/intl/en_us/badges/static/images/badges/ru_badge_web_generic.png'/></a></li>
                        <li><a href='https://apps.apple.com/ru/app/extreme-look/id1444560097'><img height="45px" width="110px" style="padding: 8px 0 0 0;" alt='Доступно в App Store' src='https://extreme-look.ru/bitrix/templates/enext/fonts/icon_extreme/svg/appstore.svg'/></a></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-md-2">
                    <!--FOOTER_MENU-->
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR."include/footer_menu.php"
                        ),
                        false,
                        array("HIDE_ICONS" => "Y")
                    );?>
                </div>
                <div class="col-xs-12 col-md-1">
                    <div class="footer__developer">
                        <?//DEVELOPER//?>
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer_developer.php"), false);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?//SLIDE_PANEL//?>
<div class="slide-panel"></div>
<?if($isSiteBg && !$isWideScreenMode) {?>
    </div>
<?}
if(!$isSiteClosed) {?>
    </div>
<?}?>
</div>
<?//SCROLL_UP//?>
<a class="scroll-up" href="javascript:void(0)"><i class="icon-arrow-up"></i></a>
<?if($USER->IsAdmin()){
    $APPLICATION->IncludeComponent(
        "altop:fortuna.vlad", "",
        array(
            "CACHE_TYPE" => "N"
        ),
        false
    );
}?>
<div id="storis-block" class="stori"></div>
<?//JS//?>
<script type="text/javascript">
    BX.message({
        SITE_ID: "<?=SITE_ID?>",
        SITE_DIR: "<?=SITE_DIR?>",
        SITE_SERVER_NAME: "<?=SITE_SERVER_NAME?>",
        SITE_TEMPLATE_PATH: "<?=SITE_TEMPLATE_PATH?>",
        SITE_CHARSET: "<?=SITE_CHARSET?>",
        LANGUAGE_ID: "<?=LANGUAGE_ID?>",
        COOKIE_NAME: "<?=Bitrix\Main\Config\Option::get('main', 'cookie_name', 'BITRIX_SM')?>",
        SLIDE_PANEL_SEARCH_TITLE: "<?=GetMessageJS('ENEXT_SLIDE_PANEL_SEARCH_TITLE')?>",
        SLIDE_PANEL_UNDEFINED_ERROR: "<?=GetMessageJS('ENEXT_SLIDE_PANEL_UNDEFINED_ERROR')?>"
    });
    //IE fix for "jumpy" fixed background
    if(navigator.userAgent.match(/MSIE 10/i) || navigator.userAgent.match(/Trident\/7\./) || navigator.userAgent.match(/Edge\/12\./)) {
        $("body").on("mousewheel", function () {
            event.preventDefault();
            var wd = event.wheelDelta;
            var csp = window.pageYOffset;
            window.scrollTo(0, csp - wd);
        });
    }
</script>
<?=$APPLICATION->ShowProperty("countersScriptsBodyEnd");?>
</body>
</html>