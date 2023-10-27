<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/selectize.css"/>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/selectize.min.js"></script>
<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);

if ($arParams['BX_EDITOR_RENDER_MODE'] == 'Y'):?>
    <img src="/bitrix/components/bitrix/map.yandex.view/templates/.default/images/screenshot.png" border="0" />
<?else:
	$arTransParams = array(
		'KEY' => $arParams['KEY'],
		'INIT_MAP_TYPE' => $arParams['INIT_MAP_TYPE'],
		'INIT_MAP_LON' => $arResult['POSITION']['yandex_lon'],
		'INIT_MAP_LAT' => $arResult['POSITION']['yandex_lat'],
		'INIT_MAP_SCALE' => $arResult['POSITION']['yandex_scale'],
		'MAP_WIDTH' => $arParams['MAP_WIDTH'],
		'MAP_HEIGHT' => $arParams['MAP_HEIGHT'],
		'CONTROLS' => $arParams['CONTROLS'],
		'OPTIONS' => $arParams['OPTIONS'],
		'MAP_ID' => $arParams['MAP_ID'],
		'LOCALE' => $arParams['LOCALE'],
		'ONMAPREADY' => 'BX_SetPlacemarks_'.$arParams['MAP_ID'],
	);
	if ($arParams['DEV_MODE'] == 'Y')
	{
		$arTransParams['DEV_MODE'] = 'Y';
		if ($arParams['WAIT_FOR_EVENT'])
			$arTransParams['WAIT_FOR_EVENT'] = $arParams['WAIT_FOR_EVENT'];
	}?>

<div class="bx-yandex-view-layout">
	<div>
		<select id="select-city" value="" multiple placeholder="Выберите одного или нескольких партнёров...">
		<? foreach($arResult['MARKERS'] as $cityName => $users): ?>
			<optgroup label="<?=$cityName?>">
				<? foreach($users as $key => $user): ?>
					<option data-data='{ "city": "<?=$cityName?>" }' value="<?=$cityName, '_', $key?>">
						<?=$user[0]['LABEL']?>
					</option>
				<? endforeach; ?>
			</optgroup>
		<? endforeach; ?>
		</select>
	</div>
	<div class="bx-yandex-view-map">
	<?$APPLICATION->IncludeComponent('bitrix:map.yandex.system', '.default', $arTransParams, false, array('HIDE_ICONS' => 'Y'));?>

	</div>
</div>
<script type="text/javascript">
var markers = [];
function BX_SetPlacemarks_<?echo $arParams['MAP_ID']?>(map)
{
	<? foreach($arResult['MARKERS'] as $cityName => $city): ?>
		<? foreach($city as $key => $user): ?>
			if( markers['<?=$cityName . '_' . $key?>'] == undefined )
				markers['<?=$cityName . '_' . $key?>'] = [];
			
			<? foreach($user as $pointKey => $point): ?>
				markers['<?=$cityName . '_' . $key?>'][<?=$pointKey?>] = BX_YMapAddPlacemark(map, <?=CUtil::PhpToJsObject($point)?>);
			<? endforeach; ?>
		<? endforeach; ?>
	<? endforeach; ?>
}
</script>
<?
endif;
?>