<?
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"] = "N";
$APPLICATION->ShowIncludeStat = false;

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

require_once($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/config.php');

global $USER;
$searchEnabled = false;
$pageName = '404';
$pageParams = '';
$pageClass = '';

if(isset($_GET['page']) && file_exists( __DIR__ . '/' . $_GET['page'] . '.php' ))
	$pageName = $_GET['page'];

$selectedCity = isset($_SESSION['SELECTED_CITY']) ? $_SESSION['SELECTED_CITY'] : 'Москва';

$pageTitles = [
	'404'				=> 'Страница не найдена',
	'about'				=> 'Рассрочка',
	'register' 			=> 'Регистрация',
	'stat-partner' 	    => 'Стать партнёром',
	'error_report' 		=> 'Сообщить об ошибке',
	'wholesale' 		=> 'Оптовые цены',
	'contacts'			=> 'Контакты',
	'catalog/index' 	=> 'Каталог',
	'citybuy'			=> $selectedCity,
	'catalog/section' 	=> 'Каталог',
	'catalog/element' 	=> 'Каталог',
	'personal/cart' 	=> 'Корзина',
	'personal/index' 	=> 'Личный кабинет',
	'personal/order' 	=> 'Оформление заказа',
	'personal/orders' 	=> 'Мои заказы',
    'delivery'          => 'Доставка',
    'promotions'        => 'Акции и скидки',
    'promotions/detail' => 'Акции и скидки'
];

if(in_array($pageName, ['partners']))
	$searchEnabled = true;

if(isset($_GET['page-heading']))
	$pageTitles[$pageName] = $_GET['page-heading'];

if($pageName == 'catalog/section')
	$pageClass = ' infinite-scroll-content';

if($pageName == 'catalog/element')
    $pageClass = ' app-catalog-element';

$pageShowMenu = [
	'home',
	'catalog/index',
	'about',
	'personal/index'
];

$pageHash = md5($pageName . implode('.', $_GET));
unset($_GET['page'], $_GET['extreme-mobile']);
foreach( $_GET as $key => $val )
	$pageParams .= $key . '=' . $val . "|";

if(strlen($pageParams)){
	$pageParams = trim($pageParams, '|') . '/';
	//$pageParams = str_replace(['?', '='], ['qq', '--'], $pageParams);
}

if(isset($_GET['go-back']) && isset($_SESSION['prev_page'])){
    if(($cnt = is_countable($_SESSION['prev_page']) ? count($_SESSION['prev_page']) : 0) > 0 ){
        unset($_SESSION['prev_page'][$cnt-1]);
    }
}

$cities = [];
$filter = ["GROUPS_ID" => [9], '!=WORK_COUNTRY' => false, "!=WORK_CITY" => false];
$rsUsers = CUser::GetList(($by="WORK_CITY"), ($order="asc"), $filter, array('SELECT' => ['ID', 'UF_COUNTRY', 'UF_MAP_ADDRESS']));
$partners = [];

while($arUser = $rsUsers->Fetch()){
	$cities[$arUser['WORK_CITY']] = $arUser['WORK_CITY'];
}

$shareText = '';

switch($pageName){
	case 'catalog/element':
		$elementId = isset($_GET['element-id']) ? $_GET['element-id'] : '';
		$arSelect = ["ID", "NAME", "CODE"];
		$arFilter = ["IBLOCK_ID" => CATALOG_IBLOCK, "ACTIVE" => "Y", "ID" => $elementId];
		$res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);

		if($arrData = $res->Fetch())
			$shareText = 'https://extreme-look.ru/catalog/product/' . $arrData['CODE'] . '/';
    break;
    case 'catalog/section':
        $sectionId = isset($_GET['section-id']) ? $_GET['section-id'] : '';
        $arSelect = ["ID", "NAME", "CODE"];
        $arFilter = ["IBLOCK_ID" => CATALOG_IBLOCK, "ACTIVE" => "Y", "ID" => $sectionId];
        $res = CIBlockSection::GetList([], $arFilter, false, false, $arSelect);

        if($arrData = $res->Fetch())
            $shareText = 'https://extreme-look.ru/catalog/' . $arrData['CODE'] . '/';
    break;
    case 'catalog/index':
        $shareText = 'https://extreme-look.ru/catalog/';
    break;
    case 'stat-partner':
        $shareText = 'https://extreme-look.ru/partners/stat-partnyerom/';
    break;
    case 'delivery':
        $shareText = 'https://extreme-look.ru/about/delivery/';
    break;
    case 'wholesale':
        $shareText = 'https://extreme-look.ru/about/opt_price/';
    break;
    case 'contacts':
        $shareText = 'https://extreme-look.ru/about/contacts/';
    break;
}
$ajaxUrl = '?page=' . $pageName . ( count($_GET) ? '&' . http_build_query($_GET) : '') . '&ajax=Y&' . MOBILE_GET . '=Y';?>

<?if(!AJAX_REQUEST){?>
<div class="page" data-name="<?=$pageName?>" data-path="/page-<?=str_replace('/', '.', $pageName)?>/<?=$pageParams?>" data-ajax="<?=$ajaxUrl?>">
	<?include('includes/navbar.php');?>
	<?if(isset($_COOKIE['new_version'])){?>
		<div class="search-content"></div>
	<?}?>
    <!-- ПУНКТЫ МЕНЮ ПОД НАВБАРОМ В ЗАВИСИМОСТИ ОТ СТРАНИЦЫ НАЧАЛО -->
    <?if($pageName == 'personal/cart'){
        if(isset($_REQUEST["subscribe"]) && $_REQUEST["subscribe"])
            $active = 'subscribe';
        elseif(isset($_REQUEST["basket"]) && $_REQUEST["basket"])
            $active = 'basket';
        elseif(isset($_REQUEST["delay"]) && $_REQUEST["delay"])
            $active = 'delay';
        else
            $active = false;?>
        <div class="subnavbar" style="position: fixed;">
            <div class="subnavbar-inner">
                <div class="segmented">
                    <a class="link button tab-link external<?=$active=='basket' ? ' tab-link-active' : ''?>" href="<?=modifyUrl(['basket' => 'Y'])?>">Корзина</a>
                    <a class="link button tab-link external<?=$active=='delay' ? ' tab-link-active' : ''?>" href="<?=modifyUrl(['delay' => 'Y'])?>">Отложенные</a>
                    <a class="link button tab-link external<?=$active=='subscribe' ? ' tab-link-active' : ''?>" href="<?=modifyUrl(['subscribe' => 'Y'])?>">Подписки</a>
                </div>
            </div>
        </div>
    <?}?>
    <!-- ПУНКТЫ МЕНЮ ПОД НАВБАРОМ В ЗАВИСИМОСТИ ОТ СТРАНИЦЫ КОНЕЦ -->
	<div class="page-content ptr-content<?=$pageClass?>" data-url="<?=$APPLICATION->GetCurPage(false)?>" data-ptr-distance="110" <?if(isset($_COOKIE['new_version']) && ($APPLICATION->GetCurDir() == "/" || $APPLICATION->GetCurPage(false) == "/page-home/")){?> <?}?>>
    <div class="ptr-preloader">
        <div class="preloader"></div>
        <div class="ptr-arrow"></div>
    </div>
    <a href="#" style="position: absolute; top: -500px; left: -500px;" class="item-link smart-select-city smart-select">
        <select name="cities">
            <?foreach($cities as $city){?>
                <option value="<?=$city?>" <?=$city == $selectedCity ? 'selected' : ''?>><?=$city?></option>
            <?}?>
        </select>
        <div class="item-content">
            <div class="item-inner">
                <div class="item-title">Ручной выбор города</div>
            </div>
        </div>
    </a>
<?}?>
    <?include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/pages/' . $pageName . ".php";?>
<?if(!AJAX_REQUEST){?>
	</div>
<?}?>
<?if(isset($_SESSION['prev_page'])) {
    $_cnt = is_countable($_SESSION['prev_page']) ? count($_SESSION['prev_page']) : 0;
    if (!isset($_GET['go-back']) && ($_cnt == 0 || $_SESSION['prev_page'][$_cnt - 1] != $pageName)) {
        $_SESSION['prev_page'][] = $pageName;
    }
}
if($pageName == 'home')
    $_SESSION['prev_page'] = [];?>
</div>
<?/*require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");*/?>