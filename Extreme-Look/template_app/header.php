<?require_once( $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/config.php');?>
<?use \Bitrix\Main\Loader,
    \Olegpro\IpGeoBase\IpGeoBase,
    VladClasses\pushTokenAppClass,
    VladClasses\AppBasket;?>

<?
$_PUSHAUTHCLASS = new pushTokenAppClass;

if( AJAX_REQUEST ){
    include("pages/index.php");
    exit;
}
if (isset($_COOKIE['PUSH_TOKEN'])){
    $_SESSION['PUSH_TOKEN'] = $_COOKIE['PUSH_TOKEN'];
}
if(isset($_COOKIE['APP_VERSION'])){
    $_SESSION['APP_VERSION'] = $_COOKIE['APP_VERSION'];
}
/*if (isset($_GET['new_version']) && !isset($_COOKIE['new_version'])){
    setcookie('new_version', 'Y');
}
if (isset($_COOKIE['new_version'])){
    $_GET['new_version'] = 'Y';
}*/

$_PUSHAUTHCLASS->update();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#3a3a3a">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />
    <title><?=BROWSER_TITLE?></title>
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/vlad-icons.css?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/css/vlad-icons.css')?>">
    <?$APPLICATION->SetAdditionalCss("/bitrix/templates/enext/fonts/graphillcg/stylesheet.css");?>
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/framework7/css/framework7.css?<?=time()?>">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/app.css?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/css/app.css')?>">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/template.css?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/css/template.css')?>">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/color-theme.css?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/css/color-theme.css')?>">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/js/owlCarousel/owl.carousel.css">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/js/owlCarousel/animate.min.css">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/iblocknews.css?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/css/iblocknews.css')?>">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/custom.css?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/css/custom.css')?>">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/jq-ui.css?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/css/jq-ui.css')?>">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH?>/js/jquery-ui.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH?>/components/bitrix/news.list/block_slider_vlad_app/script.js?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/css/custom.css')?>"></script>
    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
    <script src="//yastatic.net/share2/share.js"></script>

    <!--<script src="/bitrix/templates/enext/js/owlCarousel/owl.carousel.min.js"></script>-->

    <?if(!isset($_SESSION['prev_page']))
        $_SESSION['prev_page'] = [];

    unset($_SESSION['external']);

    if( isset($_GET['page']) && $_GET['page'] != 'home'){
        $APPLICATION->ShowHead();
        $_SESSION['external'] = true;
    }
    else $_GET['page'] = 'home';

    $new_version = (isset($_GET['new_version'])) ? "&new_version=Y" : "";?>
    <script data-skip-moving="true">
        <?
        if( defined("F7_THEME") )
            echo "window.theme = '" . F7_THEME . "';" . PHP_EOL;
        ?>
        window.onerror = function(error, url, line) {
            console.log(error + ' [' + url+ '], line - ' + line);
        };
        window.geo = {
            attempts: 0,
            coords: false,
            city: <?=isset($_SESSION['SELECTED_CITY']) ? "'" . $_SESSION['SELECTED_CITY'] . "'" : 0?>
        };
        window.SITE_TEMPLATE_PATH = '<?=SITE_TEMPLATE_PATH?>/';
        window.LOADED_PAGE = '<?=$_GET['page']?>';
        window.MOBILE_GET = '<?=MOBILE_GET?>';
        window.PRODUCTS_PER_PAGE = <?=PRODUCTS_PER_PAGE?>;
        window.USER = {
            authorized: (<?=$USER->IsAuthorized() ? 1 : 0?> === 1)
        }
    </script>
    <?$APPLICATION->ShowHead();?>
</head>
<body>
<div id="app">
    <div class="statusbar"></div>
    <div class="panel panel-left panel-cover">
        <div class="view view-left">
            <?include('ajax/leftBar.php');?>
        </div>
    </div>
    <div class="view view-main ios-edges">
        <?include("pages/index.php");?>
        <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>