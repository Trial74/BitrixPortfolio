<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

$сName = 'it'.preg_replace('/[^a-zA-Z0-9_]/', 'z', $this->GetEditAreaId($this->randString()));
?>

<div class="product-item">	
	<div class="product-item-image-wrapper" data-entity="image-wrapper">		
		<?//PREVIEW_PICTURE//?>
		<a target="_self" class="product-item-image" id="<?=$itemIds['PICT_ID']?>" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$imgTitle?>"<?=($arParams["QUICK_VIEW"] == "FULL" ? " data-entity='quickView'" : "")?>>
			<?if(is_array($item['PREVIEW_PICTURE'])) {
                if($haveOffers){
                    if(!empty($item['OFFERS'][$item['OFFERS_SELECTED']]['SLIDER_PHOTOS'])) {
                        if($arParams['SHOW_LASH_IMAGE_OFFER'] == 'Y' && !$arParams['NO_LAST_IMAGE']){
                            $morePhotos = $item['OFFERS'][count($item['OFFERS']) - 1]['SLIDER_PHOTOS'];
                            $item['PREVIEW_PICTURE']['SRC'] = $item['OFFERS'][count($item['OFFERS']) - 1]['PREVIEW_PICTURE']['SRC'];
                        }else{
                            $morePhotos = $item['OFFERS'][$item['OFFERS_SELECTED']]['SLIDER_PHOTOS'];
                            $item['PREVIEW_PICTURE']['SRC'] = $item['OFFERS'][$item['OFFERS_SELECTED']]['PREVIEW_PICTURE']['SRC'];
                        }
                    }
                    else if(!empty($item['OFFERS'][0]['SLIDER_PHOTOS']) && !empty($item['OFFERS'][0]['PREVIEW_PICTURE']['SRC'])) {
                        if($arParams['SHOW_LASH_IMAGE_OFFER'] == 'Y' && !$arParams['NO_LAST_IMAGE']){
                            $morePhotos = $item['OFFERS'][count($item['OFFERS']) - 1]['SLIDER_PHOTOS'];
                            $item['PREVIEW_PICTURE']['SRC'] = $item['OFFERS'][count($item['OFFERS']) - 1]['PREVIEW_PICTURE']['SRC'];
                        }else{
                            $morePhotos = $item['OFFERS'][0]['SLIDER_PHOTOS'];
                            $item['PREVIEW_PICTURE']['SRC'] = $item['OFFERS'][0]['PREVIEW_PICTURE']['SRC'];
                        }
                    }else{
                        $morePhotos = imagesCarouselColl($item['IBLOCK_ID'], $item['ID']);
                        if (returnSections('inSection', $item['IBLOCK_SECTION_ID'])) {
                            unset($morePhotos[0]);
                            $morePhotos = array_values($morePhotos);
                        }
                    }
                } else {
                    $morePhotos = imagesCarouselColl($item['IBLOCK_ID'], $item['ID']);
                    if (returnSections('inSection', $item['IBLOCK_SECTION_ID'])) {
                        unset($morePhotos[0]);
                        $morePhotos = array_values($morePhotos);
                    }
                }?>
                <div class="ex-slider-wrapper my-slider">
                    <div class="ex-slider <?=$сName?> owl-carousel">
                        <div class="item">
                            <img class="ex-spinner-img" src="/bitrix/templates/enext/images/spinners/ex-spinner.svg" />
                            <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="<?=$imgAlt?>" title="<?=$imgTitle?>" onload="loadImg(this)" />
                        </div>
                        <?if(is_countable($morePhotos) && count($morePhotos) > 1){?>
                            <?foreach($morePhotos as $morePhoto){?>
                                <div class="item">
                                    <img class="ex-spinner-img" src="/bitrix/templates/enext/images/spinners/ex-spinner.svg" />
                                    <img src="<?=$morePhoto?>" alt="<?=$imgAlt?>" title="<?=$imgTitle?>" onload="loadImg(this)" />
                                </div>
                            <?}?>
                        <?}elseif(is_countable($morePhotos) && count($morePhotos) == 1 && !empty($morePhotos[0])){?>
                            <div class="item">
                                <img class="ex-spinner-img" src="/bitrix/templates/enext/images/spinners/ex-spinner.svg" />
                                <img src="<?=$morePhotos[0]?>" alt="<?=$imgAlt?>" title="<?=$imgTitle?>" onload="loadImg(this)" />
                            </div>
                        <?}?>
                    </div>
                </div>
			<?} else {?>
				<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo.png" width="222" height="222" alt="<?=$imgAlt?>" title="<?=$imgTitle?>" />
			<?}?>
        </a>
			<?//MARKERS//?>
			<div class="product-item-markers<?=((!$object || ($object && $objectContacts)) && !$partnersUrl && (!$haveOffers || ($haveOffers && ($arParams['OFFERS_VIEW'] == 'PROPS' || $arParams['OFFERS_VIEW'] == 'DROPDOWN_LIST') && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y')) ? ' product-item-markers-icons' : '')?>">
				<?if($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y') {?>
					<span class="product-item-marker-container<?=($price['PERCENT'] > 0 ? '' : ' product-item-marker-container-hidden')?>" id="<?=$itemIds['DISCOUNT_PERCENT_ID']?>">
						<span class="product-item-marker product-item-marker-discount product-item-marker-14px"><span data-entity="dsc-perc-val"><?=-$price['PERCENT']?>%</span></span>
					</span>
				<?}
				if(!empty($item['PROPERTIES']['MARKER']['FULL_VALUE'])) {
					foreach($item['PROPERTIES']['MARKER']['FULL_VALUE'] as $key => $arMarker) {
						if($key <= 4) {?>
                            <span class="product-item-marker-container">
								<span class="tooltip_v <?=(!empty($arMarker['FONT_SIZE']) ? ' product-item-marker-'.$arMarker['FONT_SIZE'] : '')?>"><?=(!empty($arMarker['ICON']) ? '<i class="'.$arMarker['ICON'].'"></i>' : '')?><span class="tooltiptext_v" style="<?=(!empty($arMarker['BACKGROUND_1']) ? 'background:'.$arMarker['BACKGROUND_1'] : 'background: #7b66fe')?>"><?=$arMarker['NAME']?></span></span>
							</span>
						<?} else {
							break;
						}
					}
					unset($key, $arMarker);
				}?>
			</div>
			<?//BRAND//			
			if(!empty($item['PROPERTIES']['BRAND']['FULL_VALUE']['PREVIEW_PICTURE'])) {?>
				<div class="product-item-brand">
					<img src="<?=$item['PROPERTIES']['BRAND']['FULL_VALUE']['PREVIEW_PICTURE']['SRC']?>" width="<?=$item['PROPERTIES']['BRAND']['FULL_VALUE']['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$item['PROPERTIES']['BRAND']['FULL_VALUE']['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$item['PROPERTIES']['BRAND']['FULL_VALUE']['NAME']?>" title="<?=$item['PROPERTIES']['BRAND']['FULL_VALUE']['NAME']?>" />
				</div>
			<?}?>
<!--		<?/*//DELAY//
		if(!$arParams['DISABLE_DELAY'] && (!$object || ($object && $objectContacts)) && !$partnersUrl && (!$haveOffers || ($haveOffers && ($arParams['OFFERS_VIEW'] == 'PROPS' || $arParams['OFFERS_VIEW'] == 'DROPDOWN_LIST') && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y'))) {*/?>
			<div class="visible-md visible-lg product-item-icons-container">
				<div class="product-item-delay" id="<?/*=$itemIds['DELAY_LINK']*/?>" title="<?/*=$arParams['MESS_BTN_DELAY']*/?>" style="display: <?/*=(!$offerPartnersUrl && $actualItem['CAN_BUY'] && $price['RATIO_PRICE'] > 0 ? '' : 'none')*/?>;">
					<i class="icon-star" data-entity="delay-icon"></i>
				</div>
			</div>
		--><?/*}*/
		//QUICK_VIEW//
		if($arParams['QUICK_VIEW'] == 'CLASSICAL') {?>
			<div class="hidden-xs hidden-sm product-item-quick-view" id="<?=$itemIds['QUICK_VIEW_LINK']?>"><i class="icon-eye"></i><span><?=Loc::getMessage('CT_BCI_TPL_MESS_QUICK_VIEW')?></span></div>
		<?}?>
	</div>	
	<?//TITLE//?>
	<div class="product-item-title">
		<a target="_self" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$productTitle?>"<?=($arParams["QUICK_VIEW"] == "FULL" ? " data-entity='quickView'" : "")?>><?=$productTitle?></a>
	</div>
    <?//Инфа О РАССРОЧКЕ И ДОСТАВКЕ//?>
    <!--<div class="ex-pit product-item-title">
        <div class="ex-d-r-b-d">
            <a href="javascript:void(0);">
                <div class="ex-d-r"></div>
                <div class="ex-n-b">Доступна рассрочка</div>
            </a>
        </div>
        <div class="ex-d-r-b-d">
            <a href="https://extreme-look.ru/about/delivery" target="_blank">
                <div class="ex-b-d"></div>
                <div class="ex-n-b">Доставим бесплатно</div>
            </a>
        </div>
    </div>-->

	<?//RATING//
	if(isset($item['REVIEWS_COUNT'])) {?>
		<div class="product-item-rating<?=($item['REVIEWS_COUNT'] < 1 ? ' hidden-xs hidden-sm' : '')?>">
			<?if($item['REVIEWS_COUNT'] > 0) {?>
				<div class="product-item-rating-val"<?=($item['RATING_VALUE'] <= 4.4 ? ' data-rate="'.intval($item['RATING_VALUE']).'"' : '')?>><?=$item['RATING_VALUE']?></div>			
				<?$arReviewsDeclension = new Bitrix\Main\Grid\Declension(Loc::getMessage('CT_BCI_TPL_MESS_REVIEW'), Loc::getMessage('CT_BCI_TPL_MESS_REVIEWS_1'), Loc::getMessage('CT_BCI_TPL_MESS_REVIEWS_2'));?>
				<div class="product-item-rating-reviews-count"><?=$item['REVIEWS_COUNT'].' '.$arReviewsDeclension->get($item['REVIEWS_COUNT'])?></div>
				<?unset($arReviewsDeclension);
			}?>
		</div>
	<?}?>
	<div class="product-item-info-container">
		<div class="product-item-info-block">
			<?//SKU//
			if((!$object || ($object && $objectContacts)) && !$partnersUrl && $haveOffers && ($arParams['OFFERS_VIEW'] == 'PROPS' || $arParams['OFFERS_VIEW'] == 'DROPDOWN_LIST') && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y' && !empty($item['OFFERS_PROP'])) {?>
				<div id="<?=$itemIds['TREE_ID']?>">
					<?foreach($arParams['SKU_PROPS'] as $skuProperty) {
						$propertyId = $skuProperty['ID'];
						$skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
						if(!isset($item['SKU_TREE_VALUES'][$propertyId]))
							continue;?>
						<div class="product-item-hidden" data-entity="sku-block">
							<div class="product-item-scu-container" data-entity="sku-line-block">
								<div class="product-item-scu-title"><?=$skuProperty['NAME']?></div>
								<?if($arParams['OFFERS_VIEW'] == 'PROPS') {?>
									<div class="product-item-scu-block">
										<div class="product-item-scu-list">
											<ul class="product-item-scu-item-list">
												<?foreach($skuProperty['VALUES'] as $value) {
													if(!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
														continue;

													$value['NAME'] = htmlspecialcharsbx($value['NAME']);

													if($skuProperty['SHOW_MODE'] == 'PICT') {?>
														<li class="product-item-scu-item-color" title="<?=$value['NAME']?>" data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>" style="<?=(!empty($value['CODE']) ? 'background-color: #'.$value['CODE'].';' : (!empty($value['PICT']) ? 'background-image: url('.$value['PICT']['SRC'].');' : ''));?>"></li>
													<?} else {?>
														<li class="product-item-scu-item-text" title="<?=$value['NAME']?>" data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
															<?=$value['NAME']?>
														</li>
													<?}
												}
												unset($value);?>
											</ul>											
										</div>
									</div>
								<?} else {?>
									<div class="product-item-basket-props-block">
										<div class="product-item-basket-props-drop-down" onclick="<?=$obName?>.showOfferBasketPropsDropDownPopup(this, '<?=$propertyId?>');">
											<div class="drop-down-text" data-entity="current-option">-</div>
											<div class="drop-down-arrow"><i class="icon-arrow-down"></i></div>
											<div class="drop-down-popup" data-entity="dropdownContent" style="display: none;">
												<ul>
													<?foreach($skuProperty['VALUES'] as $value) {
														if(!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
															continue;

														$value['NAME'] = htmlspecialcharsbx($value['NAME']);?>

														<li data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>" onclick="<?=$obName?>.selectOfferBasketPropsDropDownPopupItem(this);"><span><?=$value['NAME']?></span></li>
													<?}
													unset($value);?>
												</ul>
											</div>
										</div>
									</div>
								<?}?>
							</div>
						</div>
					<?}
					unset($skuProperty);?>
				</div>
				<?foreach($arParams['SKU_PROPS'] as $skuProperty) {
					if(!isset($item['OFFERS_PROP'][$skuProperty['CODE']]))
						continue;

					$skuProps[] = array(
						'ID' => $skuProperty['ID'],
						'SHOW_MODE' => $skuProperty['SHOW_MODE'],
						'VALUES' => $skuProperty['VALUES'],
						'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
					);
				}
				unset($skuProperty);
			}
			//BASKET_PROPERTIES//
			if((!$object || ($object && $objectContacts)) && !$partnersUrl && !$haveOffers) {
				if($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y' && !empty($item['PRODUCT_PROPERTIES'])) {?>
					<div class="product-item-hidden" id="<?=$itemIds['BASKET_PROP_DIV']?>">
						<?if(!empty($item['PRODUCT_PROPERTIES_FILL'])) {
							foreach($item['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo) {?>
								<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=htmlspecialcharsbx($propInfo['ID'])?>" />
								<?unset($item['PRODUCT_PROPERTIES'][$propID]);
							}
							unset($propId, $propInfo);
						}
						if(!empty($item['PRODUCT_PROPERTIES'])) {
							foreach($item['PRODUCT_PROPERTIES'] as $propId => $propInfo) {?>
								<div class="product-item-basket-props-container">
									<div class="product-item-basket-props-title"><?=$item['PROPERTIES'][$propId]['NAME']?></div>
									<div class="product-item-basket-props-block">
										<?if($item['PROPERTIES'][$propId]['PROPERTY_TYPE'] == 'L' && $item['PROPERTIES'][$propId]['LIST_TYPE'] == 'C') {?>
											<div class="product-item-basket-props-input-radio">
												<?foreach($propInfo['VALUES'] as $valueId => $value) {?>
													<label>
														<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=$valueId?>"<?=($valueId == $propInfo['SELECTED'] ? ' checked="checked"' : '');?> />
														<span class="check-container">
															<span class="check"><i class="icon-ok-b"></i></span>
														</span>
														<span class="text" title="<?=$value?>"><?=$value?></span>
													</label>
												<?}
												unset($valueId, $value);?>
											</div>
										<?} else {?>
											<div class="product-item-basket-props-drop-down" onclick="<?=$obName?>.showBasketPropsDropDownPopup(this, '<?=$propId?>');">
												<?$currId = $currVal = false;
												foreach($propInfo['VALUES'] as $valueId => $value) {
													if($valueId == $propInfo['SELECTED']) {
														$currId = $valueId;
														$currVal = $value;
													}
												}
												unset($valueId, $value);?>
												<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=(!empty($currId) ? $currId : '');?>" />
												<div class="drop-down-text" data-entity="current-option"><?=(!empty($currVal) ? $currVal : '');?></div>
												<?unset($currVal, $currId);?>
												<div class="drop-down-arrow"><i class="icon-arrow-down"></i></div>
												<div class="drop-down-popup" data-entity="dropdownContent" style="display: none;">
													<ul>
														<?foreach($propInfo['VALUES'] as $valueId => $value) {?>
															<li><span onclick="<?=$obName?>.selectBasketPropsDropDownPopupItem(this, '<?=$valueId?>');"><?=$value?></span></li>
														<?}
														unset($valueId, $value);?>
													</ul>
												</div>
											</div>
										<?}?>
									</div>
								</div>
							<?}
							unset($propId, $propInfo);
						}?>
					</div>
				<?}
			}?>
			<div class="product-item-info">
				<div class="product-item-blocks">
					<?//PRICE//?>
					<div class="product-item-price-container" data-entity="price-block">
						<div class="product-item-price" id="<?=$itemIds['PRICE_ID']?>">
							<?if(!empty($price)) {
								if($haveOffers && (($object && !$objectContacts) || $partnersUrl || ($arParams['OFFERS_VIEW'] != 'PROPS' && $arParams['OFFERS_VIEW'] != 'DROPDOWN_LIST') || $arParams['PRODUCT_DISPLAY_MODE'] == 'N')) {?>
									<span class="product-item-price-from"><?=Loc::getMessage('CT_BCI_TPL_MESS_PRICE_FROM')?></span>
									<span class="product-item-price-current"><?=($arParams['OFFERS_VIEW'] == 'LIST' && $price['SQ_M_PRICE'] > 0 ? $price['SQ_M_PRINT_PRICE'] : $price['PRINT_PRICE'])?></span>
									<?if($arParams['OFFERS_VIEW'] == 'LIST') {?>
										<span class="product-item-price-measure">/<?=($price['SQ_M_PRICE'] > 0 ? Loc::getMessage('CT_BCI_TPL_MESS_PRICE_MEASURE_SQ_M') : $actualItem['ITEM_MEASURE']['TITLE'])?></span>
									<?}
								} else {?>
									<span class="product-item-price-not-set" data-entity="price-current-not-set"<?=($price['SQ_M_PRICE'] > 0 ? ' style="display:none;"' : ($price['PRICE'] > 0 ? ' style="display:none;"' : ''))?>><?=Loc::getMessage('CT_BCI_TPL_MESS_PRICE_NOT_SET')?></span>
									<span class="product-item-price-current" data-entity="price-current"<?=($price['SQ_M_PRICE'] > 0 ? '' : ($price['PRICE'] > 0 ? '' : ' style="display:none;"'))?>><?=($price['SQ_M_PRICE'] > 0 ? $price['SQ_M_PRINT_PRICE'] : $price['PRINT_PRICE'])?></span>
									<span class="product-item-price-measure" data-entity="price-measure"<?=($price['SQ_M_PRICE'] > 0 ? '' : ($price['PRICE'] > 0 ? '' : ' style="display:none;"'))?>>/<?=($price['SQ_M_PRICE'] > 0 ? Loc::getMessage('CT_BCI_TPL_MESS_PRICE_MEASURE_SQ_M') : $actualItem['ITEM_MEASURE']['TITLE'])?></span>
								<?}
							}?>
						</div>
						<?if($arParams['SHOW_OLD_PRICE'] == 'Y') {?>
							<div class="product-item-price-old" id="<?=$itemIds['OLD_PRICE_ID']?>"<?=($price['PERCENT'] > 0 ? '' : ' style="display:none;"')?>><?=($price['PERCENT'] > 0 ? ($price['SQ_M_BASE_PRICE'] > 0 ? $price['SQ_M_PRINT_BASE_PRICE'] : $price['PRINT_BASE_PRICE']) : '')?></div>
							<div class="product-item-price-economy" id="<?=$itemIds['DISCOUNT_PRICE_ID']?>"<?=($price['PERCENT'] > 0 ? '' : ' style="display:none;"')?>><?=($price['PERCENT'] > 0 ? Loc::getMessage('CT_BCI_TPL_MESS_PRICE_ECONOMY', array('#ECONOMY#' => ($price['SQ_M_DISCOUNT'] > 0 ? $price['SQ_M_PRINT_DISCOUNT'] : $price['PRINT_DISCOUNT']))) : '')?></div>
						<?}?>
					</div>
					<?//QUANTITY_LIMIT//
					if($arParams['SHOW_MAX_QUANTITY'] !== 'N' && (!$object || ($object && $objectContacts)) && !$partnersUrl && (!$haveOffers || ($haveOffers && ($arParams['OFFERS_VIEW'] == 'PROPS' || $arParams['OFFERS_VIEW'] == 'DROPDOWN_LIST') && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y'))) {
						if($haveOffers) {?>
							<div class="product-item-hidden" id="<?=$itemIds['QUANTITY_LIMIT']?>" style="display: none;">
								<div class="product-item-quantity">
									<i class="icon-ok-b product-item-quantity-icon"></i>
									<span class="product-item-quantity-val">
										<?=$arParams['MESS_SHOW_MAX_QUANTITY'].'&nbsp;'?>
										<span data-entity="quantity-limit-value"></span>
									</span>
								</div>
							</div>								
							<div class="product-item-hidden" id="<?=$itemIds['QUANTITY_LIMIT_NOT_AVAILABLE']?>" style="display: none;">
								<div class="product-item-quantity product-item-quantity-not-avl">
									<i class="icon-close-b product-item-quantity-icon"></i>
									<span class="product-item-quantity-val"><?=$arParams['MESS_NOT_AVAILABLE']?></span>
								</div>
							</div>
                            <div class="product-item-hidden oj-data-cont" id="<?=$itemIds['OB_DATE']?>" style="display: none;">
                                <div class="oj-data"></div>
                            </div>
						<?} else {?>
                            <div class="product-item-hidden" id="<?=$itemIds['QUANTITY_LIMIT']?>">
                                <div class="product-item-quantity<?=($actualItem['CAN_BUY'] ? '' : ' product-item-quantity-not-avl')?>">
                                    <i class="icon-<?//=($actualItem['CAN_BUY'] ? 'ok' : 'close')?>-b product-item-quantity-icon"></i>
                                    <span class="product-item-quantity-val">
										<?if($actualItem['CAN_BUY']) {
                                            //echo $arParams['MESS_SHOW_MAX_QUANTITY'].'&nbsp;';
                                            if($measureRatio && (float)$actualItem['CATALOG_QUANTITY'] > 0 && $actualItem['CATALOG_QUANTITY_TRACE'] == 'Y' && $actualItem['CATALOG_CAN_BUY_ZERO'] == 'N') {
                                                /*if($arParams['SHOW_MAX_QUANTITY'] == 'M') {
                                                    if((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                                                        echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                                                    } else {
                                                        echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                                                    }
                                                }*/
                                                if($arParams['SHOW_MAX_QUANTITY'] == 'M'){//Мой код если выбран прогрессбар?>
                                                    <!--<div class="own-progress">
                                                        <?/*for($i = 1; $i = 5; $i++){*/?>
                                                            <div class="own-pr<?/*=$item['PROGRESS_QUANTITY']['active'] > $i ? ' active' : ''*/?>">
                                                                <span></span>
                                                            </div>
                                                        <?/*}*/?>
                                                    </div>
                                                    --><?/*=$item['PROGRESS_QUANTITY']['mess']*/?>
                                                    <?if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['EX_MESS_RELATIVE_QUANTITY_VERY_FEW'] &&
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio < $arParams['EX_MESS_RELATIVE_QUANTITY_FEW']
                                                    ){//Очень мало?>
                                                        <div class="own-progress">
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                        </div>
                                                        <?echo "Очень мало";
                                                    }
                                                    if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['EX_MESS_RELATIVE_QUANTITY_FEW'] &&
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio < $arParams['EX_MESS_RELATIVE_QUANTITY_ENOUGH']
                                                    ){//Мало?>
                                                        <div class="own-progress">
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                        </div>
                                                        <?echo "Мало";
                                                    }
                                                    if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['EX_MESS_RELATIVE_QUANTITY_ENOUGH'] &&
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio < $arParams['EX_MESS_RELATIVE_QUANTITY_MANY']
                                                    ){//Достаточно?>
                                                        <div class="own-progress">
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                        </div>
                                                        <?echo "Достаточно";
                                                    }
                                                    if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['EX_MESS_RELATIVE_QUANTITY_MANY'] &&
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio < $arParams['EX_MESS_RELATIVE_QUANTITY_VERY_MANY']
                                                    ){//Много?>
                                                        <div class="own-progress">
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr">
                                                                <span></span>
                                                            </div>
                                                        </div>
                                                        <?echo "Много";
                                                    }
                                                    if(
                                                        (float)$actualItem['CATALOG_QUANTITY'] / $measureRatio > $arParams['EX_MESS_RELATIVE_QUANTITY_VERY_MANY']
                                                    ){//Очень много?>
                                                        <div class="own-progress">
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                            <div class="own-pr active">
                                                                <span></span>
                                                            </div>
                                                        </div>
                                                        <? echo "Очень много";
                                                    }
                                                } else {
                                                    echo $actualItem['CATALOG_QUANTITY'];
                                                }
                                            }?>
                                        <?} else {//Нет в наличии?>
                                            <div class="own-progress">
                                                <div class="own-pr">
                                                    <span></span>
                                                </div>
                                                <div class="own-pr">
                                                    <span></span>
                                                </div>
                                                <div class="own-pr">
                                                    <span></span>
                                                </div>
                                                <div class="own-pr">
                                                    <span></span>
                                                </div>
                                                <div class="own-pr">
                                                    <span></span>
                                                </div>
                                            </div>
                                            <? echo $arParams['MESS_NOT_AVAILABLE'];
                                        }?>
									</span>
                                </div>
                            </div>
                        <?}
					}?>
                    <?//ДАТА ПОСТУПЛЕНИЯ ТОВАРА// Мой код
                    if($arParams['SHOW_MAX_QUANTITY'] !== 'N' && (!$object || ($object && $objectContacts)) && !$partnersUrl && (!$haveOffers || ($haveOffers && $arParams['OFFERS_VIEW'] == 'PROPS' && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y'))){
                        if(!$haveOffers){
                            if ($actualItem['CAN_BUY'] == ''){?>
                                <div class="product-item-hidden" data-entity="quantity-block">
                                    <?if(!empty(ozhidaemayaData($arResult['ITEM']['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                        $arResult['ITEM']['PROPERTIES']['CML2_TRAITS']['VALUE'], true, false))){ //Мой код если в товаре есть ожидаемая дата поступления?>
                                        <div class="oj-data-item">
                                            <?=ozhidaemayaData($arResult['ITEM']['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                                $arResult['ITEM']['PROPERTIES']['CML2_TRAITS']['VALUE'], false, false); //Сверяем с текущей и выводим на странице?>
                                        </div>
                                    <?}?>
                                </div>
                            <?}
                        }
                    }
					//QUANTITY//
					if($arParams['USE_PRODUCT_QUANTITY'] && (!$object || ($object && $objectContacts)) && !$partnersUrl && ((!$haveOffers && $actualItem['CAN_BUY']) || ($haveOffers && ($arParams['OFFERS_VIEW'] == 'PROPS' || $arParams['OFFERS_VIEW'] == 'DROPDOWN_LIST') && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y'))) {?>
						<div class="product-item-hidden" data-entity="quantity-block">
							<?if(!empty($item['PROPERTIES']['M2_COUNT']['VALUE'])) {?>
								<div class="product-item-amount"<?=($isMeasurePc || $isMeasureSqM ? '' : ' style="display: none;"')?>>
									<a class="product-item-amount-btn-minus" id="<?=$itemIds['PC_QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
									<input class="product-item-amount-input" id="<?=$itemIds['PC_QUANTITY_ID']?>" type="tel" value="<?=$price['PC_MIN_QUANTITY']?>" />
									<a class="product-item-amount-btn-plus" id="<?=$itemIds['PC_QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
									<div class="product-item-amount-measure"><?=Loc::getMessage('CT_BCI_TPL_MESS_MEASURE_PC')?></div>
								</div>
								<div class="product-item-amount"<?=($isMeasurePc || $isMeasureSqM ? '' : ' style="display: none;"')?>>
									<a class="product-item-amount-btn-minus" id="<?=$itemIds['SQ_M_QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
									<input class="product-item-amount-input" id="<?=$itemIds['SQ_M_QUANTITY_ID']?>" type="tel" value="<?=$price['SQ_M_MIN_QUANTITY']?>" />
									<a class="product-item-amount-btn-plus" id="<?=$itemIds['SQ_M_QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
									<div class="product-item-amount-measure"><?=Loc::getMessage('CT_BCI_TPL_MESS_MEASURE_SQ_M')?></div>
								</div>
								<?if($haveOffers) {?>
									<div class="product-item-amount"<?=($isMeasurePc || $isMeasureSqM ? ' style="display: none;"' : '')?>>
										<a class="product-item-amount-btn-minus" id="<?=$itemIds['QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
										<input class="product-item-amount-input" id="<?=$itemIds['QUANTITY_ID']?>" type="tel" value="<?=$price['MIN_QUANTITY']?>" />
										<a class="product-item-amount-btn-plus" id="<?=$itemIds['QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
										<div class="product-item-amount-measure" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$actualItem["ITEM_MEASURE"]["TITLE"]?></div>
									</div>
								<?}
							} else {?>
								<div class="product-item-amount">								
									<a class="product-item-amount-btn-minus" id="<?=$itemIds['QUANTITY_DOWN_ID']?>" href="javascript:void(0)" rel="nofollow">-</a>
									<input class="product-item-amount-input" id="<?=$itemIds['QUANTITY_ID']?>" type="tel" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$price['MIN_QUANTITY']?>" />
									<a class="product-item-amount-btn-plus" id="<?=$itemIds['QUANTITY_UP_ID']?>" href="javascript:void(0)" rel="nofollow">+</a>
									<div class="product-item-amount-measure" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$actualItem['ITEM_MEASURE']['TITLE']?></div>

                                    <?//DELAY//
                                    if(!$arParams['DISABLE_DELAY'] && (!$object || ($object && $objectContacts)) && !$partnersUrl && (!$haveOffers || ($haveOffers && ($arParams['OFFERS_VIEW'] == 'PROPS' || $arParams['OFFERS_VIEW'] == 'DROPDOWN_LIST') && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y'))) {?>
                                    <div class="product-item-amount-measure">
                                        <div class="product-item-delay" id="<?=$itemIds['DELAY_LINK']?>" title="<?=$arParams['MESS_BTN_DELAY']?>" style="display: <?=(!$offerPartnersUrl && $actualItem['CAN_BUY'] && $price['RATIO_PRICE'] > 0 ? '' : 'none')?>;">
                                            <i class="icon-delay" data-entity="delay-icon"></i>
                                        </div>
                                    </div>
                                    <?}?>
								</div>
							<?}?>
						</div>
					<?}?>
				</div>
				<?//BUTTONS//?>
				<div class="product-item-button-container" data-entity="buttons-block">			
					<?if(($object && !$objectContacts) || $partnersUrl || ($haveOffers && (($arParams['OFFERS_VIEW'] != 'PROPS' && $arParams['OFFERS_VIEW'] != 'DROPDOWN_LIST') || $arParams['PRODUCT_DISPLAY_MODE'] != 'Y')) || $arParams['DISABLE_BASKET']) {?>
						<a target="<?=$item['TARGET']?>" class="btn btn-buy" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$arParams['MESS_BTN_DETAIL']?>"<?=($arParams["QUICK_VIEW"] == "FULL" ? " data-entity='quickView'" : "")?>><i class="icon-cart"></i></a>
					<?} else {
						if(!$haveOffers) {
							if($actualItem['CAN_BUY'] || (!$actualItem['CAN_BUY'] && !$showSubscribe)) {?>

                                <div id="<?=$itemIds['BASKET_ACTIONS_ID']?>">
									<button type="button" class="btn btn-buy" id="<?=$itemIds['BUY_LINK']?>" title="<?=($arParams['ADD_TO_BASKET_ACTION'] == 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET'])?>"<?=($actualItem['CAN_BUY'] && $price['RATIO_PRICE'] > 0 ? '' : ' disabled="disabled"')?>><i class="icon-cart"></i></button>
                                </div>

							<?} elseif(!$actualItem['CAN_BUY'] && $showSubscribe) {?>
								<?$APPLICATION->IncludeComponent('bitrix:catalog.product.subscribe', 'customSubscribeVlad',
									array(
										'PRODUCT_ID' => $actualItem['ID'],
										'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
										'BUTTON_CLASS' => 'btn btn-buy',
										'DEFAULT_DISPLAY' => true,
										'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);?>
							<?}
						} else {?>
							<div id="<?=$itemIds['BASKET_ACTIONS_ID']?>">
								<button type="button" class="btn btn-buy" id="<?=$itemIds['BUY_LINK']?>" title="<?=($arParams['ADD_TO_BASKET_ACTION'] == 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET'])?>"<?=(!$offerPartnersUrl ? ($actualItem['CAN_BUY'] ? ($price['RATIO_PRICE'] > 0 ? '' : ' disabled="disabled"') : ($showSubscribe ? ' style="display: none;"' : ' disabled="disabled"')) : ' style="display: none;"')?>><i class="icon-cart"></i></button>
							</div>
							<a target="_blank" class="btn btn-buy" id="<?=$itemIds['MORE_LINK']?>" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$arParams['MESS_BTN_DETAIL']?>"<?=($arParams["QUICK_VIEW"] == "FULL" ? " data-entity='quickView'" : "").($offerPartnersUrl && $actualItem["CAN_BUY"] ? '' : ' style="display: none;"')?>><i class="icon-arrow-right"></i></a>
							<?if($showSubscribe) {?>
								<?$APPLICATION->IncludeComponent('bitrix:catalog.product.subscribe', 'customSubscribeVlad',
									array(
										'PRODUCT_ID' => $actualItem['ID'],
										'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
										'BUTTON_CLASS' => 'btn btn-buy',
										'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
										'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);?>
							<?}
						}
					}?>
				</div>
			</div>
		</div>
	</div>
    <?if($arParams['SHOW_MAX_QUANTITY'] !== 'N' && (!$object || ($object && $objectContacts)) && !$partnersUrl && (!$haveOffers || ($haveOffers && $arParams['OFFERS_VIEW'] == 'PROPS')) && (!getPartner() && !getNewPartner())) {?>
        <div class="block-item-info">
            <a href="/about/opt_price/" data-name="wholesale" class="opt-price-app">Оптовая стоимость от <?=round($price["PRICE"] - ($price["PRICE"] * 0.41))?> руб.</a>
            <a href="/about/delivery/" data-name="delivery" class="delivery-info-app">Доставим бесплатно от 5000 руб.</a>
        </div>
    <?}?>
	<?//TOTAL_COST//
	if($arParams['USE_PRODUCT_QUANTITY'] && (!$object || ($object && $objectContacts)) && !$partnersUrl && ((!$haveOffers && $actualItem['CAN_BUY']) || ($haveOffers && ($arParams['OFFERS_VIEW'] == 'PROPS' || $arParams['OFFERS_VIEW'] == 'DROPDOWN_LIST') && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y'))) {?>
		<div class="product-item-total-cost product-item-hidden" id="<?=$itemIds['TOTAL_COST_ID']?>"<?=($price['MIN_QUANTITY'] != 1 || (!empty($item['PROPERTIES']['M2_COUNT']['VALUE']) && ($price['PC_MIN_QUANTITY'] != 1 || $price['SQ_M_MIN_QUANTITY'] != 1)) ? '' : ' style="display:none;"')?>><?=Loc::getMessage('CT_BCI_TPL_MESS_TOTAL_COST')?><span data-entity="total-cost"><?=($price['MIN_QUANTITY'] != 1 || (!empty($item['PROPERTIES']['M2_COUNT']['VALUE']) && ($price['PC_MIN_QUANTITY'] != 1 || $price['SQ_M_MIN_QUANTITY'] != 1)) ? $price['PRINT_RATIO_PRICE'] : '')?></span></div>
	<?}
	//COMPARE//
	if($arParams['DISPLAY_COMPARE'] && (!$haveOffers || ($haveOffers && (!$object || ($object && $objectContacts)) && !$partnersUrl && ($arParams['OFFERS_VIEW'] == 'PROPS' || $arParams['OFFERS_VIEW'] == 'DROPDOWN_LIST') && $arParams['PRODUCT_DISPLAY_MODE'] == 'Y'))) {?>
		<div class="product-item-compare product-item-hidden">
			<label id="<?=$itemIds['COMPARE_LINK']?>">
				<input type="checkbox" data-entity="compare-checkbox">
				<span class="product-item-compare-checkbox"><i class="icon-ok-b"></i></span>
				<span class="product-item-compare-title" data-entity="compare-title"><?=$arParams["MESS_BTN_COMPARE"]?></span>
			</label>
		</div>
	<?}?>
</div>
<script>
    $(<?=json_encode("." . $сName)?>).owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        items:1
    })
</script>