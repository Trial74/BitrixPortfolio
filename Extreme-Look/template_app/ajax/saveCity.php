<?
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once( $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/config.php');
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";

$APPLICATION->ShowIncludeStat = false;
header('Content-Type: application/json');

$city = isset($_GET['city']) ? $_GET['city'] : false;

$success = false;

if($city){
	$success = true;
	$_SESSION['SELECTED_CITY'] = $city;
}

echo json_encode([
	'success'	=> $success
]);