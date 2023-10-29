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
        <?$APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH."/fonts/SF-UI/stylesheet.css");
        $APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH."/fonts/raleway/stylesheet.css");?>
		<title><?$APPLICATION->ShowTitle()?></title>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/colors.css", true);
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/animation.min.css");		
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/csshake-default.min.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/scrollbar/jquery.scrollbar.min.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/bootstrap.min.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/font-awesome.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/splide/splide-core.min.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/splide/splide-default.min.css");
		CJSCore::Init(array("jquery2", "enextIntlTelInput", "popup"));
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/splide/splide.js");
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/popup.windows.js");
        $APPLICATION->AddHeadScript("https://unpkg.com/@popperjs/core@2");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap.min.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/formValidation.min.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/inputmask.min.js");		
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.hoverIntent.min.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/scrollbar/jquery.scrollbar.min.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/main.js");
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/language.js");
		$APPLICATION->ShowHead();?>
    </head>
	<body class="<?=$APPLICATION->ShowProperty('catalogMenu').$APPLICATION->ShowProperty('smartFilterView')?>"<?=$APPLICATION->ShowProperty("backgroundColor");?>>
		<?=$APPLICATION->ShowProperty("countersScriptsBodyStart");
		echo $APPLICATION->ShowPanel();
		global $arSettings;
		$arSettings = $APPLICATION->IncludeComponent("altop:settings.enext", "", array(), false, array("HIDE_ICONS" => "Y"));
		$isSiteBg = $arSettings["SITE_BACKGROUND"]["VALUE"] == "Y" ? true : false;
		$siteBgFixed = $arSettings["SITE_BACKGROUND_FIXED"]["VALUE"] == "Y" ? true : false;
		$isSiteClosed = COption::GetOptionString("main", "site_stopped") == "Y" && !$USER->CanDoOperation("edit_other_settings") ? true : false;
		$isWideScreenMode = $arSettings["WIDESCREEN_MODE"]["VALUE"] == "Y" ? true : false;?>

		<div class="page-wrapper<?=(!$siteBgFixed ? " page-wrapper-rel" : "");?>">
			<?if(!$isSiteClosed){?>
				<div class="container-fluid top-header-wrapper">
					<div class="container-lg top-header ps-0 pe-0">
                        <div class="wrapper">
                            <div class="left_block">
                                <div class="top-panel__col top-panel__logo">
                                    <a href="<?=SITE_DIR?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/logo/mixon-logo.png" /></a>
                                </div>
                            </div>
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
			<?}
			if(!$isSiteClosed){?>
				<div class="page-container-wrapper">
			<?}
			if($isSiteBg && !$isWideScreenMode) {?>				
				<div class="page-container">
			<?}?>