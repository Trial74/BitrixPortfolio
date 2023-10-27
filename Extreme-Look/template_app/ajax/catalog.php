<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once( $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/config.php');
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";

$APPLICATION->ShowIncludeStat = false;
header('Content-Type: application/json');

$data = 'none';

switch($_GET['type']){
	case 'sort':
		$data = $_GET['param'];
		$_SESSION['CATALOG_SORT'] = $_GET['param'];
		break;
	case 'view':
		$data = $_GET['param'];
		$_SESSION['CATALOG_VIEW'] = $_GET['param'];
		break;
}

echo json_encode([
	'data'		=> $data
]);
