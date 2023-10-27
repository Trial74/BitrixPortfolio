<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/partners/functions_partner.php");
$openedCountry = $_GET['partner-country'];
?>

<div class="block-header">
	<?=$openedCountry?>
</div>

<?
$filter = Array("GROUPS_ID" => [9,11,12,13], "UF_PAGE_PART" => true);
$rsUsers = CUser::GetList(($by="timestamp_x"), ($order="asc"), $filter, array('SELECT' => ['ID', 'UF_COUNTRY', 'UF_SITY_PAT', 'UF_MAP_ADDRESS', 'UF_PAGE_PART', 'UF_DOP_INFO'])); // выбираем пользователей
$users = array();
while ($arUser = $rsUsers->Fetch()):?>
	<? $users[] = $arUser;
endwhile;?>

<?
$users_grouped = array();
$email_unique = array();

global $USER;

$pattern = [
    '/(https?:\/\/)?(www\.)?vk\.com\/([^\/]\w+\d+)/i',
    '/(\+7\s?\(\d{3}\)\s?\d{3}-\d{2}-\d{2})|(\+?[\(\)0-9]{6,18})/',
    '/E-?mail:\s?(.*(?:com|ru))/Uis'
];

$replace = [
    '<br/><a href="http://vk.com/${3}" target="_blank">${0}</a>',
    '<a href="tel:${0}" class="external" target="_blank">${0}</a>',
    '<br/>E-mail: <a class="external" href="mailto: ${1}">${1}</a>'
];

?>
<?foreach($users as $city => $user):?>
	<?
	if (!in_array($user['EMAIL'], $email_unique)) {

		$email_unique[] = $user['EMAIL'];

		$users_grouped[GetCountryByID(codeCountry($user['UF_COUNTRY'][0]))][$user['UF_SITY_PAT'][0]][] = $user;
		// if ($user['WORK_COUNTRY'] > 0 && $user['WORK_CITY']) {
		// 	$manyCity = explode("|", $user['WORK_CITY']);
		//
		// 	foreach ($user['UF_COUNTRY'] as $country) {
		// 		$info = explode('|', $country);
		// 		$user['WORK_STREET'] = $info[2];
		// 		$users_grouped[GetCountryByID(trim($info[0]))][trim($info[1])][] = $user;
		// 	}
		// }
	}
	?>
<?endforeach?>
<?
	ksort($users_grouped[$openedCountry]);
?>
<div class="list accordion-list">
	<ul>
		<? foreach ($users_grouped[$openedCountry] as $city => $partners): ?>
			<li class="accordion-item">
				<a href="#" class="item-content item-link">
					<div class="item-inner">
						<div class="item-title">
							<?= $city?>
						</div>
					</div>
				</a>
				<div class="accordion-item-content">
					<div class="block block-strong search-text">
						<?foreach ($partners as $partner) {
							  if (empty($partner['WORK_STREET'])) continue;
							  if (in_array($partner['WORK_COMPANY'], $manyPartners)) continue;
							  $manyPartners[] = $partner['WORK_COMPANY'];
							  $partnerString = str_replace(array($partner['WORK_COMPANY']. ",", $partner['WORK_COMPANY']), '', $partner['WORK_STREET']);
							  $partnerStringReplaced = preg_replace($pattern, $replace, $partnerString);
							  ?>
							  <div class="partner_box__partner">
								  <div class="partner_box__partner__name"><?=$partner['WORK_COMPANY'];?></div>
								  <div><?=$partnerStringReplaced?></div>
								  <? if(is_array( $partner['UF_MAP_ADDRESS'])): ?>
									<?foreach($partner['UF_MAP_ADDRESS'] as $addressKey => $address){
										$address = preg_replace($pattern, $replace, $address);
										$exploded = explode('||', $address);
										if(strpos($exploded[0], $city) !== false){
											echo '<div>' . $exploded[0] . ', <strong>' . $exploded[1] . '</strong></div>';
										}?>
									<?}?>
								  <?endif;?>
							  </div>
							  <?if (count($partners) > 1):?>
								  <br/>
							  <? endif;?>
						  <?}?>
					</div>
				</div>
			</li>
		<? endforeach; ?>
	</ul>
</div>
<?
/*
if (isset($_COOKIE['new_version'])):
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	"fortes",
	array(
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:3:{s:10:\"yandex_lat\";d:55.11693204776155;s:10:\"yandex_lon\";d:61.132705349702036;s:12:\"yandex_scale\";i:7;}",
		"MAP_WIDTH" => "AUTO",
		"MAP_HEIGHT" => "500",
		"CONTROLS" => array(
			0 => "ZOOM",
			1 => "SMALLZOOM",
			2 => "MINIMAP",
			3 => "TYPECONTROL",
			4 => "SCALELINE",
		),
		"OPTIONS" => array(
			0 => "ENABLE_SCROLL_ZOOM",
			1 => "ENABLE_DBLCLICK_ZOOM",
			2 => "ENABLE_DRAGGING",
		),
		"MAP_ID" => "yam_1",
		"COMPONENT_TEMPLATE" => "fortes",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
<? endif;?>*/
