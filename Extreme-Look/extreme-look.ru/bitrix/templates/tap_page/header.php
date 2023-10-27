<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title><?$APPLICATION->ShowTitle()?></title>
    <?$APPLICATION->SetAdditionalCss("/bitrix/templates/enext/fonts/graphillcg/stylesheet.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/bootstrap.min-5_2.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/splide/splide-default.min.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/splide/splide-core.min.css");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/splide/splide.js");?>
    <?$APPLICATION->ShowHead();?>

</head>
<body>
<?$APPLICATION->ShowPanel();?>