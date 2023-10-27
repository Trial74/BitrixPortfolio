<?php
$city = isset($_SESSION['SELECTED_CITY']) ? $_SESSION['SELECTED_CITY'] : 'Москва';

$filter = ["GROUPS_ID" => [9], '!=WORK_COUNTRY' => false, "WORK_CITY" => $city];
$rsUsers = CUser::GetList(($by="timestamp_x"), ($order="asc"), $filter, array('SELECT' => ['ID', 'UF_COUNTRY', 'UF_MAP_ADDRESS']));
$partners = [];


if($city == 'Челябинск')
	$partners[] = [
		'WORK_COMPANY'	=> 'Головной офис',
		'WORK_STREET'	=> 'ул.Труда д.183, ТРК Гагарин Парк, 2 этаж.
		тел. <a href="tel:88003507215" target="_blank">8 800 350 72 15</a>
		E-Mail: retail@extreme-look.ru'
	];

while ( $arUser = $rsUsers->Fetch() ){
	$partners[] = $arUser;
}

?>
<div class="block-title">
	Партнёры в моём городе
</div>
<div class="block">
	<?foreach ($partners as $partner): ?>
		<? if (empty($partner['WORK_STREET'])) continue; ?>
		<? if(in_array($partner['WORK_COMPANY'], $manyPartners)) continue; ?>
			<?
				$manyPartners[] = $partner['WORK_COMPANY'];
				$partnerString = str_replace(array($partner['WORK_COMPANY']. ",", $partner['WORK_COMPANY']), '', $partner['WORK_STREET']);

				$pattern = [
				'/(https?:\/\/)?(www\.)?vk\.com\/([^\/]\w+\d+)/i',
				'/\+7\s?\(\d{3}\)\s?\d{3}-\d{2}-\d{2}/',
				'/e-mail/Uis',
				'/тел/Uis'
				];

				$replace = [
				'<a href="http://vk.com/${3}" target="_blank">${0}</a>',
				'<a href="tel:${0}" target="_blank">${0}</a>',
				'<br/>E-mail',
				'<br/>тел'
				];

				$partnerStringReplaced = preg_replace($pattern, $replace, $partnerString);
			?>
			<div>
				<div style="font-weight: bold; font-size: 14px;">
					<?=$partner['WORK_COMPANY'];?>
				</div>
				<div>
					<?=$partnerStringReplaced?>
				</div>
			</div>
			<?if (count($partners) > 1):?>
			<br/>
		<? endif;?>
	<? endforeach; ?>
</div>