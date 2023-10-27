<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
CJSCore::Init(array("fx"));
$scheme = CMain::IsHTTPS() ? "https" : "http";
bxMyFunctions();?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
	<head>
		<?=$APPLICATION->ShowProperty("countersScriptsHead");?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />		
		<link href="<?=SITE_TEMPLATE_PATH?>/fonts/MuseoSansCyrl-300.woff" as="font" type="font/woff" crossorigin />
		<link href="<?=SITE_TEMPLATE_PATH?>/fonts/MuseoSansCyrl-500.woff" as="font" type="font/woff" crossorigin />
		<link href="<?=SITE_TEMPLATE_PATH?>/fonts/MuseoSansCyrl-700.woff" as="font" type="font/woff" crossorigin />
        <?$APPLICATION->SetAdditionalCss("/bitrix/templates/enext/fonts/graphillcg/stylesheet.css");?>
		<title><?$APPLICATION->ShowTitle()?></title>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/colors.min.css", true);
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/animation.min.css");		
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/csshake-default.min.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/scrollbar/jquery.scrollbar.min.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/bootstrap.min.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/font-awesome.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/owlCarousel/myStyleCar.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/owlCarousel/owl.carousel.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/owlCarousel/animate.min.css");
		CJSCore::Init(array("jquery2", "enextIntlTelInput"));
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap.min.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/formValidation.min.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/inputmask.min.js");		
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.hoverIntent.min.js");
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery-ui.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/moremenu.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/scrollbar/jquery.scrollbar.min.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/main.js");
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/popup_app.js");
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/language.js");
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/owlCarousel/owl.carousel.min.js");
        $APPLICATION->AddHeadScript("https://www.youtube.com/iframe_api");
		$APPLICATION->ShowHead();?>
    </head>
	<body class="<?=$APPLICATION->ShowProperty('catalogMenu').$APPLICATION->ShowProperty('smartFilterView')?> <?=$APPLICATION->ShowProperty('ex_background_kontract')?>"<?=$APPLICATION->ShowProperty("backgroundColor");?>>
    <!-- Top.Mail.Ru counter -->
    <script type="text/javascript">
        var _tmr = window._tmr || (window._tmr = []);
        _tmr.push({id: "3304123", type: "pageView", start: (new Date()).getTime()});
        (function (d, w, id) {
        if (d.getElementById(id)) return;
        var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
        ts.src = "https://top-fwz1.mail.ru/js/code.js";
        var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
        if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
        })(document, window, "tmr-code");
    </script>
    <noscript><div><img src="https://top-fwz1.mail.ru/counter?id=3304123;js=na" style="position:absolute;left:-9999px;" alt="Top.Mail.Ru" /></div></noscript>
    <!-- /Top.Mail.Ru counter -->

    <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();
                for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
                k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(90365176, "init", {
                clickmap:true,
                trackLinks:true,
                accurateTrackBounce:true
            });
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/90365176" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
		<?=$APPLICATION->ShowProperty("countersScriptsBodyStart");
		echo $APPLICATION->ShowPanel();
		global $arSettings;
		$arSettings = $APPLICATION->IncludeComponent("altop:settings.enext", "", array(), false, array("HIDE_ICONS" => "Y"));
		$isSiteBg = $arSettings["SITE_BACKGROUND"]["VALUE"] == "Y" ? true : false;
		$siteBgFixed = $arSettings["SITE_BACKGROUND_FIXED"]["VALUE"] == "Y" ? true : false;
		$isSiteClosed = COption::GetOptionString("main", "site_stopped") == "Y" && !$USER->CanDoOperation("edit_other_settings") ? true : false;
		$isWideScreenMode = $arSettings["WIDESCREEN_MODE"]["VALUE"] == "Y" ? true : false;

        if(MOBILE_OS == 'android') $link_popup = 'https://play.google.com/store/apps/details?id=ru.extreme_look_app.extremelook&hl=ru';
        else $link_popup = 'https://apps.apple.com/ru/app/extreme-look/id6444559836';?>

        <div class="popup-app">
            <img src="<?=SITE_TEMPLATE_PATH?>/fonts/icon_extreme/popup_app.jpg" class="img-popup-b" />
            <div class="popup-app-close" align="right"><i class="popup-close"></i></div>
            <div class="popup-app-img-block"><img width="60px" align="left" style="border-radius: 10px;" src="https://extreme-look.ru/bitrix/templates/enext/fonts/icon_extreme/icon-ExtremeLook-for-app-in-popup.png" /></div>
            <div class="popup-app-text">Extreme Look</div>
            <div class="popup-app-rating">
                <div class="p-a-star"><i class="i-p-a-star"></i></div>
                <div class="p-a-star"><i class="i-p-a-star"></i></div>
                <div class="p-a-star"><i class="i-p-a-star"></i></div>
                <div class="p-a-star"><i class="i-p-a-star"></i></div>
                <div class="p-a-star"><i class="i-p-a-star-50"></i></div>
                <div class="rat">40</div>
            </div>
            <div class="popup-button-install"><a target="_blank" type="button" href='<?=$link_popup?>' class="popup-link-install">Установить приложение</a></div>
        </div>

		<div class="page-wrapper<?=(!$siteBgFixed ? " page-wrapper-rel" : "");?>">
			<?if($isSiteBg) {?>
				<div class="hidden-print page-bg<?=($arSettings['SITE_BACKGROUND_REPEAT_X']['VALUE'] == 'Y' ? ' page-bg__repeat-x' : '').($arSettings['SITE_BACKGROUND_REPEAT_Y']['VALUE'] == 'Y' ? ' page-bg__repeat-y' : '').($siteBgFixed ? ' page-bg__fixed' : '').($arSettings['SITE_BACKGROUND_BLUR']['VALUE'] == 'Y' ? ' page-bg__blur' : '');?> hidden-xs hidden-sm"<?=$APPLICATION->ShowProperty("backgroundImage");?>></div>
			<?}
			if(!$isSiteClosed && in_array("TOP_MENU", $arSettings["SITE_BLOCKS"]["VALUE"])) {?>
				<div class="hidden-xs hidden-sm hidden-print top-menu-wrapper">
					<div class="top-menu">
                        <div class="wrapper">
                            <div class="left_block">
                                <div class="top-panel__col top-panel__logo">
                                    <!--<a href="<?/*=SITE_DIR*/?>"><img src="<?/*=SITE_TEMPLATE_PATH*/?>/images/logo/logo_animate_new_mobile.gif" /></a>-->
                                    <!--<a href="<?/*=SITE_DIR*/?>"><img src="<?/*=SITE_TEMPLATE_PATH*/?>/images/logo/logo_spring.png" /></a>-->
                                    <a href="<?=SITE_DIR?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/logo/logo-summer.png" /></a>
                                    <!--<a href="<?/*=SITE_DIR*/?>"><img src="<?/*=SITE_TEMPLATE_PATH*/?>/images/logo/logo-winter.gif" /></a>-->
                                </div>
                            </div>
						<?//TOP_MENU//?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
							array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => SITE_DIR."include/header_top_menu.php"
							),
							false,
							array("HIDE_ICONS" => "Y")
						);?>
                        </div>
					</div>
				</div>
			<?}?>
            <?if(!$isSiteClosed){?>
                <div class="mobile-home hidden-md hidden-lg">
                    <!--<a href="<?/*=SITE_DIR*/?>"><img width="126px" height="48px" src="<?/*=SITE_TEMPLATE_PATH*/?>/images/logo/logo_animate_new_mobile.gif" /></a>-->
                    <!--<a href="<?/*=SITE_DIR*/?>"><img width="126px" height="48px" src="<?/*=SITE_TEMPLATE_PATH*/?>/images/logo/logo_spring.png" /></a>-->
                    <a href="<?=SITE_DIR?>"><img width="126px" height="48px" src="<?=SITE_TEMPLATE_PATH?>/images/logo/logo-summer.png" /></a>
                    <!--<a href="<?/*=SITE_DIR*/?>"><img width="126px" height="48px" src="<?/*=SITE_TEMPLATE_PATH*/?>/images/logo/logo-winter.gif" /></a>-->
                    <div class="ex-strip"></div>
                </div>
            <?}?>
			<div class="hidden-print top-panel-wrapper">
				<div class="top-panel<?=(!$APPLICATION->GetDirProperty('PERSONAL_SECTION') && ($arSettings['CATALOG_MENU']['VALUE'] == 'OPTION-4' || $arSettings['CATALOG_MENU']['VALUE'] == 'OPTION-5') ? ' catalog-menu-outside' : '')?>">
					<div class="top-panel__cols">
						<div class="top-panel__col top-panel__thead hidden-xs hidden-sm">
							<div class="top-panel__cols">								
								<?//MENU_ICON//
								if(!$isSiteClosed && !CSite::InDir(SITE_DIR . "kontraktnoe-proizvodstvo/index.php")) {?>
									<div class="top-panel__col top-panel__menu-icon-container<?=($arSettings['CATALOG_MENU']['VALUE'] == 'OPTION-3' || $arSettings['CATALOG_MENU']['VALUE'] == 'OPTION-4' || $arSettings['CATALOG_MENU']['VALUE'] == 'OPTION-5' ? ' hidden-md hidden-lg' : '')?>">
										<i class="icon-menu"></i>
										<?if($arSettings['CATALOG_MENU']['VALUE'] == 'OPTION-6') {
											//MENU//?>
											<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
												array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR."include/slide_menu.php"
												),
												false,
												array("HIDE_ICONS" => "Y")
											);?>
										<?}?>
									</div>
								<?}else{?>
                                    <div class="top-panel__col top-panel__menu-icon-container">
                                        <i class="icon-menu"></i>
                                    </div>
                                <?}?>
							</div>
						</div>
                        <div class="cat_text"><a href="/catalog" style="text-decoration:none;color:white;">Каталог товаров</a></div>
						<div class="top-panel__col top-panel__tfoot">
							<div class="top-panel__cols">								
								<?if(!$isSiteClosed) {
									if($arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-3") {
										//MENU//?>
										<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
											array(
												"AREA_FILE_SHOW" => "file",
												"PATH" => SITE_DIR."include/slide_menu.php"
											),
											false,
											array("HIDE_ICONS" => "Y")
										);?>
									<?} elseif($arSettings["TOP_PANEL_SEARCH_BUTTON"]["VALUE"] == "Y") {?>
										<div class="hidden-xs hidden-sm top-panel__col"></div>
									<?}?>
                                    <div class="top-panel__col go-home" data-entity="menu-icon"><a href="javascript:void(0)"><i class="extreme-burger"></i></a></div>
									<div class="top-panel__col top-panel__search-container<?=($arSettings['TOP_PANEL_SEARCH_BUTTON']['VALUE'] == 'Y' ? '-button' : '')?>">
										<a class="top-panel__search-btn<?=($arSettings['TOP_PANEL_SEARCH_BUTTON']['VALUE'] != 'Y' ? ' hidden-md hidden-lg' : '')?>" href="javascript:void(0)" data-entity="showSearch">
											<span class="top-panel__search-icon"><i class="icon-search"></i></span>
										</a>
										<div class="top-panel__search <?=($arSettings['TOP_PANEL_SEARCH_BUTTON']['VALUE'] != 'Y' ? 'hidden-xs hidden-sm' : 'hidden')?>">
											<?//SEARCH//?>
											<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
												array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR."include/header_search.php"
												),
												false,
												array("HIDE_ICONS" => "Y")
											);?>
										</div>
									</div>									
								<?} else {?>
									<div class="hidden-xs hidden-sm top-panel__col"></div>
								<?}
								//LANGUAGE//?>
                                <!--<div class="top-panel__col ex-lang hidden-xs hidden-sm">
                                    <a class="top-panel___lang">
                                        <span class="top-panel__lang-graph-wrap">
                                            <i alt="Выбор языка" class="<?/*=($_SESSION['MY_PARAMS'][3] ? 'extreme-lang-r' : 'extreme-lang-e')*/?>"></i>
                                        </span>
                                        <span class="top-panel__contacts-icon hidden-xs hidden-sm"><i class="icon-arrow-down"></i></span>
                                    </a>
                                    <div class="lang-menu-popup" data-role="dropdownLanguage" style="display: none;">
                                        <ul>
                                            <li>
                                                <a class="ex-lang-menu-item" data-role="ex-lang-ru" href="#" title="Русский">
                                                    <span class="lang-menu-item-icon"><i class="extreme-lang-ru"></i></span>
                                                    <span class="lang-menu-item-name">Русский</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="ex-lang-menu-item" data-role="ex-lang-en" href="#" title="Английский (beta)">
                                                    <span class="lang-menu-item-icon"><i class="extreme-lang-en"></i></span>
                                                    <span class="lang-menu-item-name">Английский (beta)</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>-->
								<?//CONTACTS//
                                $APPLICATION->IncludeComponent("bitrix:main.include", "",
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/header_contacts.php"
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>
								<?if(!$isSiteClosed) {
									if($arSettings["TOP_PANEL_DISABLE_COMPARE"]["VALUE"] != "Y") {?>
										<div class="top-panel__col top-panel__compare">
											<?//COMPARE//?>
											<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
												array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR."include/header_compare.php"
												),
												false,
												array("HIDE_ICONS" => "Y")
											);?>
										</div>
									<?}?>
									<?//USER//?>
                                    <div class="top-panel__col top-panel__user">
                                        <?//USER//Мой код компонент персонального раздела перенесён из header.php?>
                                        <?$APPLICATION->IncludeComponent(
                                            "altop:user.enext",
                                            ".default",
                                            array(
                                                "PATH_TO_PERSONAL" => SITE_DIR."personal/",
                                                "CACHE_TYPE" => "N",
                                                "CACHE_TIME" => "36000000",
                                                "CACHE_GROUPS" => "N",
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "COMPOSITE_FRAME_MODE" => "A",
                                                "COMPOSITE_FRAME_TYPE" => "AUTO"
                                            ),
                                            false
                                        );?>
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR."include/user_menu.php",
                                                "CACHE_TYPE" => "N",
                                                "CACHE_TIME" => "3",
                                                "CACHE_GROUPS" => "N"
                                            ),
                                            false,
                                            array("HIDE_ICONS" => "Y")
                                        );?>
                                    </div>
                                    <?//CART//
                                    if($arSettings["DISABLE_BASKET"]["VALUE"] != "Y" || $arSettings["DISABLE_DELAY"]["VALUE"] != "Y") {?>
                                        <?$APPLICATION->IncludeComponent("altop:sale.basket.basket.line", "",
                                            array(
                                                "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
                                                "COMPOSITE_FRAME_MODE" => "A",
                                                "COMPOSITE_FRAME_TYPE" => "AUTO"
                                            ),
                                            false,
                                            array("HIDE_ICONS" => "Y")
                                        );?>
                                    <?}?>
                                    <?//Мой код на этом месте был компонент персонального раздела, теперь он вызывается из altop:basket.basket.line//?>
								<?}?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?if($arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-1" || $arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-2" || $arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-4" || $arSettings["CATALOG_MENU"]["VALUE"] == "OPTION-5") {
				//SLIDE_MENU//?>
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
					array(
						"AREA_FILE_SHOW" => "file",
						"PATH" => SITE_DIR."include/slide_menu.php"
					),
					false,
					array("HIDE_ICONS" => "Y")
				);?>
			<?}
			if(!$isSiteClosed && !CSite::InDir(SITE_DIR . "kontraktnoe-proizvodstvo/index.php")) {?>
				<div class="page-container-wrapper">
			<?}
			if(($isSiteBg && !$isWideScreenMode) || CSite::InDir(SITE_DIR . "kontraktnoe-proizvodstvo/index.php")) {?>
				<div class="page-container">
			<?}
			if(!$isSiteClosed) {
				if(!CSite::inDir(SITE_DIR."index.php")) {
                    if (!CSite::InDir(SITE_DIR . "personal/order/make/") &&
                        $APPLICATION->GetDirProperty("PERSONAL_SECTION") && $USER->IsAuthorized()) {
                        //PERSONAL_MENU//?>
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR . "include/personal_menu.php"
                            ),
                            false,
                            array("HIDE_ICONS" => "Y")
                        ); ?>
                    <?
                    }
                    //SECTION_BANNER//
                    $APPLICATION->ShowViewContent("UF_BANNER");
                    if (!CSite::InDir(SITE_DIR . "personal/") && !CSite::InDir(SITE_DIR . "catalog/") && !CSite::InDir(SITE_DIR . "kontraktnoe-proizvodstvo/index.php")) {
                        //NAVIGATION//?>
                        <div class="hidden-print navigation-wrapper">
                            <div class="container-fluid<?//= $APPLICATION->ShowProperty('wideScreenMode') ?>">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="navigation-content">
                                            <div id="navigation" class="navigation">
                                                <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "",
                                                    array(
                                                        "START_FROM" => "0",
                                                        "PATH" => "",
                                                        "SITE_ID" => "-"
                                                    ),
                                                    false,
                                                    array("HIDE_ICONS" => "Y")
                                                ); ?>
                                            </div>
                                            <?//SHARE//?>
                                            <div class="navigation-share">
                                                <div>
                                                    <div class="catalog-section-filter-container"></div>
                                                    <div class="catalog-section-sort-container"></div>
                                                    <div class="navigation-share-icon" data-entity="showShare"><i
                                                                class="icon-share"></i></div>
                                                </div>
                                                <div class="navigation-share-content" data-entity="shareContent">
                                                    <div class="navigation-share-content-title"><?= GetMessage("ENEXT_SHARE") ?></div>
                                                    <div class="navigation-share-content-block">
                                                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
                                                            array(
                                                                "AREA_FILE_SHOW" => "file",
                                                                "PATH" => SITE_DIR . "include/footer_share.php"
                                                            ),
                                                            false
                                                        ); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?
                    }
                    if (!CSite::InDir(SITE_DIR . "catalog/")) {
                        //SECTION_PANEL//
                        //$APPLICATION->ShowViewContent("CATALOG_SECTION_PANEL"); ?>
                        <div class="content-wrapper internal">
                            <div class="container-fluid <?= $APPLICATION->ShowProperty('wideScreenMode') ?>">
                                <div class="row">
                                    <div class="col-xs-12">
                    <?
                    }
                }
			}