<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once( $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH .  '/config.php');
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"] = "N";

$APPLICATION->ShowIncludeStat = false;
header('Content-Type: application/json');

$result = true;

if(!strlen($_GET["name"]) || !strlen($_GET["email"]) || !strlen($_GET["message"]))
	exit;

$mail_form = "<h2>Сообщение об ошибке</h2>
<table border='0' style='border-collapse: collapse;'>
	<tr>
		<td>Имя:</td>
		<td>" . $_GET["name"] . "</td>
	</tr>
	<tr>
		<td>Email:</td>
		<td>" . $_GET["email"] . "</td>
	</tr>
	<tr>
		<td>Текст:</td>
		<td>" . $_GET["message"] . "</td>
	</tr>
</table>";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF8\r\n";
$headers .= "From: EXTREME-LOOK<no-reply@extreme-look.ru>";

//mail(COption::GetOptionString('main','email_from'), "Сообщение об ошибке Extreme Look App", $mail_form, $headers);
mail('it@extreme-look.ru', "Сообщение об ошибке в приложении Extreme Look", $mail_form, $headers);

echo json_encode([
	'result' => $result
]);