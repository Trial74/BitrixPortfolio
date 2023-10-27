<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;

global $arSettings;

$smartSpeed = $arSettings["SMART_SPEED"]["VALUE"] ? $arSettings["SMART_SPEED"]["VALUE"] : 1000;
$loop = count($arResult["ITEMS"]) > 1 ? true : false;
$autoplayTimeout = $arSettings["AUTOPLAY_TIMEOUT"]["VALUE"] ? $arSettings["AUTOPLAY_TIMEOUT"]["VALUE"] : 5000;
$animateOut = $arSettings["ANIMATE_OUT"]["VALUE"] != "none" ? $arSettings["ANIMATE_OUT"]["VALUE"] : false;
$animateIn = $arSettings["ANIMATE_IN"]["VALUE"] != "none" ? $arSettings["ANIMATE_IN"]["VALUE"] : false;

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('SLIDER_ITEM_DELETE_CONFIRM'));
$itemPropImageVideo = array();

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));
$containerName = 'slider-'.$obName;?>

<style type="text/css">
	.owl-carousel .animated{
		-webkit-animation-duration: <?=$smartSpeed?>ms;
		animation-duration: <?=$smartSpeed?>ms;
	}
</style>

<div class="slider-wrapper">
	<div class="slider" id="<?=$containerName?>">		
		<?foreach($arResult["ITEMS"] as $arItem) {
            $itemPropImageVideo = $arItem["VLAD_PROPERTY"];
			$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], $elementEdit);
			$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], $elementDelete, $elementDeleteParams);?>

            <div class="slider-item" <?=$itemPropImageVideo["IMG"] ? "data-mobile-image-src='" . $itemPropImageVideo['MOBILE_IMG_VIDEO'] . "'" : "data-video-mobile-src='" . $itemPropImageVideo['MOBILE_IMG_VIDEO'] . "'"?> <?=$itemPropImageVideo["IMG"] ? "data-mobile-image-width='" . $itemPropImageVideo['MOBILE_WIDTH'] . "' data-mobile-image-height='" . $itemPropImageVideo['MOBILE_HEIGHT'] . "'" : "data-video-mobile-width='" . $itemPropImageVideo['MOBILE_WIDTH'] . "' data-video-mobile-height='" . $itemPropImageVideo['MOBILE_HEIGHT'] . "'"?>>

				<div class="slider-item__caption<?=(!empty($arItem['DISPLAY_PROPERTIES']['ALIGN']['VALUE_XML_ID']) ? ' '.$arItem['DISPLAY_PROPERTIES']['ALIGN']['VALUE_XML_ID'] : '').(!empty($arItem['DISPLAY_PROPERTIES']['VERTICAL_ALIGN']['VALUE_XML_ID']) ? ' '. $arItem['DISPLAY_PROPERTIES']['VERTICAL_ALIGN']['VALUE_XML_ID'] : '');?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								<div class="slider-item__block">
									<?if(isset($arItem["DISPLAY_PROPERTIES"]["SHOW_NAME"]) && !$arItem["DISPLAY_PROPERTIES"]["SHOW_NAME"]["VALUE"] == false) {?>
										<div class="slider-item__title"><?=$arItem["NAME"]?></div>
									<?}
									if(!empty($arItem["PREVIEW_TEXT"])) {?>
										<div class="slider-item__text"><?=$arItem["PREVIEW_TEXT"]?></div>
									<?}
									if(!empty($arItem["DISPLAY_PROPERTIES"]["BUTTON_1_TEXT"]["VALUE"]) || !empty($arItem["DISPLAY_PROPERTIES"]["BUTTON_2_TEXT"]["VALUE"]) || !empty($arItem["DISPLAY_PROPERTIES"]["BUTTON_3_TEXT"]["VALUE"]) || !empty($arItem["DISPLAY_PROPERTIES"]["BUTTON_4_TEXT"]["VALUE"])) {?>
										<div class="slider-item__buttons">
											<?for($i = 1; $i <= 4; $i++) {
												if(!empty($arItem["DISPLAY_PROPERTIES"]["BUTTON_".$i."_TEXT"]["VALUE"])) {?>
													<a class="btn btn-slider btn-slider-<?=$i?>" href="<?=(!empty($arItem['DISPLAY_PROPERTIES']['BUTTON_'.$i.'_URL']['VALUE']) ? $arItem['DISPLAY_PROPERTIES']['BUTTON_'.$i.'_URL']['VALUE'] : 'javascript:void(0)');?>" role="button"><span><?=$arItem["DISPLAY_PROPERTIES"]["BUTTON_".$i."_TEXT"]["VALUE"]?></span></a>
												<?}
											}?>
										</div>
									<?}?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?if(!empty($itemPropImageVideo['URL_APP'])) {?>
					<!--<a
                            class="slider-item__link<?/*=$itemPropImageVideo['URL_OTHER'] ? ' open-other-link' : ''*/?>"
                            data-view=".view-main"
                            href="<?/*=$itemPropImageVideo['URL_OTHER'] ? 'javascript: void(0)' : $itemPropImageVideo['URL_APP']*/?>"
                            <?/*=$itemPropImageVideo['URL_OTHER'] ? 'data-open="'.$itemPropImageVideo['URL_APP'].'"' : ''*/?>
                    "></a>-->
                    <a
                            class="slider-item__link"
                            data-view=".view-main"
                            href="<?=$itemPropImageVideo['URL_APP']?>"
                    "></a>
				<?}?>
			</div>
		<?}?>
	</div>
</div>

<script type="text/javascript">
	var <?=$obName?> = new JCNewsListBlockSlider({		
		container: '<?=$containerName?>',
		smartSpeed: '<?=$smartSpeed?>',
		loop: '<?=$loop?>',
		autoplayTimeout: '<?=$autoplayTimeout?>',
		animateOut: '<?=$animateOut?>',
		animateIn: '<?=$animateIn?>'
	});
</script>