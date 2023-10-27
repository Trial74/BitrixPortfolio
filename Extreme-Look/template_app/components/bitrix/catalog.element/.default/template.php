<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $USER;
$OPTION_CURRENCY = CCurrency::GetBaseCurrency();
$available = false;
$firstBuyElBool = false;
$firstBueParam = array();
$selectedItem = array();
$select = false;
$noItemImage = false;
$haveOffers = !empty($arResult["OFFERS"]);
$propsIndexes = array( //Свойства товара
    'OBYEM' => 'Объём',
    'DLITELNOST_NOSKI' => 'Длительность носки',
    'SKOROST_STSEPKI' => 'Скорость сцепки',
    'TSVET' => 'Цвет',
    'TSVET_1' => 'Цвет',
    'TSVET_3' => 'Цвет',
    'VREMYA_EKSPOZITSII' => 'Время экспозиции',
    'KOLICHESTVO_LINIY_1' => 'Количество линий'
);
$propsOffersIndexes = array( //Свойства характеристик
    'VES_1' => 'Вес'
);
//Ищем первый доступный товар в характеристиках если нет такого ставим первый в массиве выбранным НАЧАЛО

if($haveOffers){
    $selectedItem = getParamsOffersByApp($arResult);
}
//Ищем первый доступный товар в характеристиках если нет такого ставим первый в массиве выбранным КОНЕЦ

$marketplace = array(
    'ozon' => false,
    'wildberries' => false
);
$twoMarket = false;

if(isset($arResult['PROPERTIES']['SSYLKA_NA_OZON']) && !empty($arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']) || (isset($arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']) && !empty($arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']))){
    $parseOZ = parse_url($arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']);
    $parseWB = parse_url($arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']);
    if(is_array($parseOZ))
        if(isset($parseOZ['host']) && ($parseOZ['host'] === 'www.ozon.ru' || $parseOZ['host'] === 'ozon.ru'))
            $marketplace['ozon'] = true;
    if(is_array($parseWB))
        if(isset($parseWB['host']) && ($parseWB['host'] === 'www.wildberries.ru' || $parseWB['host'] === 'wildberries.ru'))
            $marketplace['wildberries'] = true;

    if($marketplace['ozon'] && $marketplace['wildberries']) $twoMarket = true;
}
?>
	<div class="block detail_propduct">
		<?if(!empty($arResult["PROPERTIES"]["OFFERS"]["VALUE"])){?>
			<div class="markerContainer">
				<?foreach ($arResult["PROPERTIES"]["OFFERS"]["VALUE"] as $ifv => $marker){?>
					<div class="marker" style="background-color: <?=strstr($arResult["PROPERTIES"]["OFFERS"]["VALUE_XML_ID"][$ifv], "#") ? $arResult["PROPERTIES"]["OFFERS"]["VALUE_XML_ID"][$ifv] : "#424242"?>"><?=$marker?></div>
				<?}?>
			</div>
		<?}?>
		<div id="<?=$haveOffers ? $selectedItem["SELECTED_ITEM"]["ID"] : ''?>" data-pagination='{"el": ".pagination-<?=$selectedItem["SELECTED_ITEM"]["ID"]?>"}' data-space-between="0" class="swiper-container swiper-init element-images-slider">
				<?if($haveOffers && empty($arResult["IMAGES"])) {
                    $noItemImage = true;
                    $arResult["IMAGES"] = $selectedItem["SELECTED_ITEM"]["PHOTOS"];
                }?>
			<div class="swiper-pagination pagination-<?=$selectedItem["SELECTED_ITEM"]["ID"]?>"></div>
			<div class="swiper-wrapper">
				<?foreach ($arResult["IMAGES"] as $ipr => $arNextPicture){?>
					<div class="swiper-slide">
						<?if($ipr == 0){?>
							<img class="lazy lazy-fade-in" src="<?=NOIMAGE_PATH?>" data-src="<?=$arNextPicture["MEDIUM_IMAGE"]["SRC"]?>">
						<?}else{?>
							<img src="<?=$arNextPicture["MEDIUM_IMAGE"]["SRC"]?>">
						<?}?>
					</div>
				<?}?>
                <?unset($ipr, $arNextPicture)?>
			</div>
		</div>
        <?if($haveOffers && $noItemImage){
            unset($arResult['VLAD_SKU'][$arResult['ID']][$selectedItem["SELECTED_ITEM"]["ID"]]); //Удаляем выбранный товар из массива картинок характеристик потому что он уже выведен выше
            foreach($arResult['VLAD_SKU'][$arResult['ID']] as $key => $skuPhotos){?>
                <div id="<?=$skuPhotos['ID']?>" data-pagination='{"el": ".pagination-<?=$skuPhotos['ID']?>"}' data-space-between="0" class="swiper-container element-images-slider hidden_app">
                    <div class="swiper-pagination pagination-<?=$skuPhotos['ID']?>"></div>
                    <div class="swiper-wrapper">
                        <?foreach ($skuPhotos["PHOTOS"] as $ipr => $arNextPicture){?>
                            <div class="swiper-slide">
                                <?if($ipr == 0){?>
                                    <img class="lazy lazy-fade-in" src="<?=NOIMAGE_PATH?>" data-src="<?=$arNextPicture["MEDIUM_IMAGE"]["SRC"]?>">
                                <?}else{?>
                                    <img src="<?=$arNextPicture["MEDIUM_IMAGE"]["SRC"]?>">
                                <?}?>
                            </div>
                        <?}?>
                        <?unset($ipr, $arNextPicture)?>
                    </div>
                </div>
            <?}
        }?>
		<br/>
		<div class="product-card">
            <div class="product-name">
                <?=$arResult['NAME']?>
            </div>

        <?//*** --- ЕСЛИ ЕСТЬ ХАРАКТЕРИСТИКИ НАЧАЛО --- ***//?>
            <?if($haveOffers && !empty($arResult['VLAD_SKU_DISPLAY'])){?>
                <div class="product-item-detail-scu-container" id="scu-container-<?=$arResult['ID']?>" data-id-item="<?=$arResult['ID']?>">
                    <?foreach ($arResult['VLAD_SKU_DISPLAY'] as $key => $V_sku){
                        $propertyId = $V_sku["ID"];
                        $firstBuyElBool = false;?>
                        <div class="product-item-detail-info-container" data-entity="sku-line-block">
                            <div class="product-item-detail-scu-title"><?=htmlspecialcharsEx($V_sku["NAME"])?></div>
                        </div>
                        <div class="product-item-detail-scu-block">
                            <div class="product-item-detail-scu-list">
                                <ul class="product-item-detail-scu-item-list" data-entity="sku-line-list" data-prop="<?=$propertyId?>">
                                    <?foreach($V_sku["VALUES"] as $keyVal => &$value){
                                        if(!$firstBuyElBool) {
                                            foreach ($selectedItem['SELECTED_PARAM'] as $keyParam => $valueParamFirst) {
                                                if ($keyParam == $propertyId && $valueParamFirst == $value['ID']){
                                                    $select = true;
                                                    $firstBuyElBool = true;
                                                    array_push($selectedItem, $propertyId . '_' . $value['ID']);
                                                }
                                            }
                                        }
                                        $value["NAME"] = $value['TSVET'] ? '<img width="30px" src="' . $value["COLOR"] . '" />' : htmlspecialcharsbx($value["NAME"]);?>
                                        <li class="product-item-detail-scu-item-text<?=$select ? ' selected' : ''?><?=$value['TSVET'] ? ' scu-color' : ''?>" title="<?=$value['TSVET'] ? $value['TITTLE'] : $value['NAME']?>" data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>"><?=$value["NAME"]?></li>
                                        <?$select = false;?>
                                    <?}?>
                                    <?unset($value);?>
                                </ul>
                            </div>
                        </div>
                    <?}?>
                    <?=$selectedItem['HIDDEN_HTML']?>
                </div>
            <?}?>
            <?if($haveOffers){?>
                <?if($selectedItem['SELECTED_ITEM']["QUANTITY"] > 0){?>
                    <?$available = true;
                    $quantRes = catalogQuantity($selectedItem['SELECTED_ITEM']["QUANTITY"]);
                    if($quantRes['result']){?>
                        <div class="block-display-quantity">
                            <div class="product-item-detail-quantity-val">
                                <div class="own-progress">
                                    <?for($i = 1; $i <= 5; $i++){?>
                                        <div class="own-pr<?=$quantRes['active'] >= $i ? ' active' : ''?>">
                                            <span></span>
                                        </div>
                                    <?}?>
                                </div>
                                <span><?=$quantRes['message']?></span>
                            </div>
                            <div class="delay-button<?=$selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'Y' ? ' delay-active' : ''?>" id="delay-element" data-item="<?=$selectedItem['SELECTED_ITEM']['ID']?>" data-idbasket="<?=$haveOffers ? (($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && $selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'Y') ? $selectedItem['SELECTED_ITEM']['IN_BASKET']['BASKET_ID'] : '') : ''?>"></div>
                        </div>
                    <?}?>
                <?}else{?>
                    <div class="block-display-quantity">
                        <div class="product-item-detail-quantity-val">
                            <div class="own-progress">
                                <?for($i = 1; $i <= 5; $i++){?>
                                    <div class="own-pr">
                                        <span></span>
                                    </div>
                                <?}?>
                            </div>
                            <span id="test">Нет в наличии</span>
                        </div>
                        <div class="delay-button<?=$selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'Y' ? ' delay-active' : ''?>" id="delay-element" data-item="<?=$selectedItem['SELECTED_ITEM']['ID']?>" data-idbasket="<?=$haveOffers ? (($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && $selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'Y') ? $selectedItem['SELECTED_ITEM']['IN_BASKET']['BASKET_ID'] : '') : ''?>"></div>
                    </div>
                <?}?>

                <div class="price-item-cart-no-available"<?=!$available ? '' : ' style="display: none"'?>>
                    <?$discontPrice = !empty($selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT']) && $selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT'] != $selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'];?>
                    <div class="discont-price"<?=$discontPrice ? '' : ' style="display: none"'?>><?=$selectedItem['SELECTED_ITEM']['PRICES']['PRICE']?> руб.<span>/шт</span></div>
                    <div class="old-price"<?=$discontPrice ? '' : ' style="display: none"'?>><?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'], $OPTION_CURRENCY);?></div>
                    <div class="discont-benefit"<?=$discontPrice ? '' : ' style="display: none"'?>>Экономия: <?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'] - $selectedItem['SELECTED_ITEM']['PRICES']['PRICE'], $OPTION_CURRENCY)?></div>
                    <div class="base-price"<?=!$discontPrice ? '' : ' style="display: none"'?>><?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['PRICE'], $OPTION_CURRENCY);?><span>/шт</span></div>
                </div>


                <div class="basket-item card-footer offers-footer-element" data-id="<?=$selectedItem['SELECTED_ITEM']['ID']?>" data-idbasket="<?=(($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && $selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'N') ? $selectedItem['SELECTED_ITEM']['IN_BASKET']['BASKET_ID'] : '')?>">
                    <div class="price-item-cart"<?=$available ? '' : ' style="display: none"'?>>
                        <?$discontPrice = !empty($selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT']) && $selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT'] != $selectedItem['PRICES']['BASE_PRISE'];?>
                            <div class="discont-price"<?=$discontPrice ? '' : ' style="display: none"'?>><?=$selectedItem['SELECTED_ITEM']['PRICES']['PRICE']?> руб.<span>/шт</span></div>
                            <div class="old-price"<?=$discontPrice ? '' : ' style="display: none"'?>><?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'], $OPTION_CURRENCY);?></div>
                            <div class="discont-benefit"<?=$discontPrice ? '' : ' style="display: none"'?>>Экономия: <?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'] - $selectedItem['SELECTED_ITEM']['PRICES']['PRICE'], $OPTION_CURRENCY)?></div>
                            <div class="base-price"<?=!$discontPrice ? '' : ' style="display: none"'?>><?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['PRICE'], $OPTION_CURRENCY);?><span>/шт</span></div>

                    </div>
                    <div class="basket-item-td basket-item-quantity checking"<?=($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && $selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'N') ? '' : ' style="display: none"'?> data-id="<?=$selectedItem['SELECTED_ITEM']['ID']?>">
                        <div class="basket-item-amount catalog basketQty" data-id="<?=$selectedItem['SELECTED_ITEM']['ID']?>">
                            <a class="hidden-print basket-item-amount-btn-minus quan-on-basket minus" href="#" id="amount-m-<?=$selectedItem['SELECTED_ITEM']['ID']?>"><?=$selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && $selectedItem['SELECTED_ITEM']['IN_BASKET']['QUANTITY'] == 1 ? '<span class="far fa-trash-alt"></span>' : '-'?></a>
                            <input class="basket-item-amount-input qty-on-basket"
                                   id="quantity-cart-element-<?=$selectedItem['SELECTED_ITEM']["ID"]?>"
                                   min="1"
                                   max="<?=$selectedItem['SELECTED_ITEM']['QUANTITY']?>"
                                   type="number"
                                   maxlength="10"
                                   value="<?=$selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] ? $selectedItem['SELECTED_ITEM']['IN_BASKET']['QUANTITY'] : 1?>"
                            >
                            <a class="hidden-print basket-item-amount-btn-plus quan-on-basket plus" href="#" id="amount-p-<?=$selectedItem['SELECTED_ITEM']['ID']?>">+</a>
                        </div>
                    </div>
                    <div data-id="<?=$selectedItem['SELECTED_ITEM']['ID']?>" id="button-add-to-cart-<?=$selectedItem['SELECTED_ITEM']['ID']?>" class="add-to-cart element-button<?=$twoMarket ? ' two-market' : ''?>"<?=($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && $selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'N') || !$available ? ' style="display: none"' : ''?>><?=$twoMarket ? '' : '<span>В корзину</span>'?></div>
                    <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){?>
                        <div data-id="<?=$arResult['ID']?>" data-mark="CLICK_OZON" data-open="<?=$selectedItem['PARAMETERS']['OZON']['LINK']?>" class="btn-ozon-elem element-button open-market-link"><i class='ex-icon-ozon'></i></div>
                    <?}?>
                    <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){?>
                        <div data-id="<?=$arResult['ID']?>" data-mark="CLICK_WILDBERRIES" data-open="<?=$selectedItem['PARAMETERS']['WILDBERRIES']['LINK']?>" class="btn-wildberries-elem element-button open-market-link"><i class='ex-icon-wildberries'></i></div>
                    <?}?>

                    <?if(!empty(ozhidaemayaData($selectedItem['SELECTED_ITEM']['PARAM']['CML2_ATTRIBUTES']['DESCRIPTION'],
                        $selectedItem['SELECTED_ITEM']['PARAM']['CML2_ATTRIBUTES']['VALUE'],
                        false, false))){?>
                        <a href="#" class="link not-available"<?=!$available ? '' : ' style="display: none"'?>>
                            <?=ozhidaemayaData($selectedItem['SELECTED_ITEM']['PARAM']['CML2_ATTRIBUTES']['DESCRIPTION'],
                                $selectedItem['SELECTED_ITEM']['PARAM']['CML2_ATTRIBUTES']['VALUE'],
                                false, false);?>
                        </a>
                        <div <?=$arResult['IS_SUBSCRIBED'] ? 'data-idsub="' . $arResult['ID_SUBSCRIBED'] . '"' : ''?> data-item="<?=$arResult['ID']?>" data-contact="<?=$USER->getEmail() ? $USER->getEmail() : 'false'?>" data-auth="<?=$USER->IsAuthorized() ? $USER->GetId() : 'false'?>" id="button-item-subscribe-<?=$selectedItem['SELECTED_ITEM']['ID']?>" class="subscribe-item catalog-button<?=$arResult['IS_SUBSCRIBED'] ? ' subscribed' : ''?>"<?=!$available ? '' : ' style="display: none"'?>>
                            <svg version="1.1" class="subscribe-item-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 521 428.8" xml:space="preserve"><path d="M471.5,0h-422C22.2,0,0,22.2,0,49.5v270C0,346.8,22.2,369,49.5,369l141.5,0l54,54c3.8,3.8,8.9,5.8,13.9,5.8s10.1-1.9,13.9-5.8l54-54l144.5,0c27.3,0,49.5-22.2,49.5-49.5v-270C521,22.2,498.8,0,471.5,0z M482,66.4v236.1l-118.8-118L482,66.4z M157.8,184.6L39,302.6V66.4L157.8,184.6z M185.4,212.1l18.9,18.8c15,15,35,23.3,56.2,23.3s41.2-8.3,56.2-23.2l18.9-18.8L454.2,330h-144L259,381.2L207.8,330h-4.3l0,0H66.7L185.4,212.1z M260.5,215.1c-10.8,0-21-4.2-28.7-11.9L66.7,39h387.6L289.1,203.3C281.5,210.9,271.3,215.1,260.5,215.1z"/></svg>
                            <span><?=$arResult['IS_SUBSCRIBED'] ? 'Подписка' : 'Подписаться'?></span>
                        </div>
                    <?}else{?>
                        <a href="#" style="width: 100%;" class="link not-available"<?=!$available ? '' : ' style="display: none"'?> data-id="<?=$arResult['ID']?>">
                            Нет в наличии
                        </a>
                        <div <?=$arResult['IS_SUBSCRIBED'] ? 'data-idsub="' . $arResult['ID_SUBSCRIBED'] . '"' : ''?> data-item="<?=$selectedItem['SELECTED_ITEM']['ID']?>" data-contact="<?=$USER->getEmail() ? $USER->getEmail() : 'false'?>" data-auth="<?=$USER->IsAuthorized() ? $USER->GetId() : 'false'?>" id="button-item-subscribe-<?=$selectedItem['SELECTED_ITEM']['ID']?>" class="subscribe-item catalog-button<?=$arResult['IS_SUBSCRIBED'] ? ' subscribed' : ''?>"<?=!$available ? '' : ' style="display: none"'?>>
                            <svg version="1.1" class="subscribe-item-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 521 428.8" xml:space="preserve"><path d="M471.5,0h-422C22.2,0,0,22.2,0,49.5v270C0,346.8,22.2,369,49.5,369l141.5,0l54,54c3.8,3.8,8.9,5.8,13.9,5.8s10.1-1.9,13.9-5.8l54-54l144.5,0c27.3,0,49.5-22.2,49.5-49.5v-270C521,22.2,498.8,0,471.5,0z M482,66.4v236.1l-118.8-118L482,66.4z M157.8,184.6L39,302.6V66.4L157.8,184.6z M185.4,212.1l18.9,18.8c15,15,35,23.3,56.2,23.3s41.2-8.3,56.2-23.2l18.9-18.8L454.2,330h-144L259,381.2L207.8,330h-4.3l0,0H66.7L185.4,212.1z M260.5,215.1c-10.8,0-21-4.2-28.7-11.9L66.7,39h387.6L289.1,203.3C281.5,210.9,271.3,215.1,260.5,215.1z"/></svg>
                            <span><?=$arResult['IS_SUBSCRIBED'] ? 'Подписка' : 'Подписаться'?></span>
                        </div>
                    <?}?>
                </div>
        <?//*** --- ЕСЛИ ЕСТЬ ХАРАКТЕРИСТИКИ КОНЕЦ --- ***//?>
            <?}else{?>
                <?if($arResult["CATALOG_QUANTITY"] > 0){
                    prent_r($arResult);?>
                    <?$available = true;
                    $quantRes = catalogQuantity($arResult["CATALOG_QUANTITY"]);
                    if($quantRes['result']){?>
                        <div class="product-item-detail-quantity-val">
                            <div class="own-progress">
                                <?for($i = 1; $i <= 5; $i++){?>
                                    <div class="own-pr<?=$quantRes['active'] >= $i ? ' active' : ''?>">
                                        <span></span>
                                    </div>
                                <?}?>
                            </div>
                            <span><?=$quantRes['message']?></span>
                            <div class="delay-button<?=($arResult['ON_BASKET'] && $arResult['DELAY'] == 'Y') ? ' delay-active' : ''?>" id="delay-element" data-item="<?=$arResult['ID']?>" data-idbasket="<?=($arResult['ON_BASKET'] && $arResult['DELAY'] == 'Y') ? $arResult['ID_ON_BASKET'] : ''?>"></div>
                        </div>
                    <?}?>
                <?}else{?>
                    <div class="product-item-detail-quantity-val">
                        <div class="own-progress">
                            <?for($i = 1; $i <= 5; $i++){?>
                                <div class="own-pr">
                                    <span></span>
                                </div>
                            <?}?>
                        </div>
                        <span id="test">Нет в наличии</span>
                        <div class="delay-button<?=($arResult['ON_BASKET'] && $arResult['DELAY'] == 'Y') ? ' delay-active' : ''?>" id="delay-element" data-item="<?=$arResult['ID']?>" data-idbasket="<?=($arResult['ON_BASKET'] && $arResult['DELAY'] == 'Y') ? $arResult['ID_ON_BASKET'] : ''?>"></div>
                    </div>
                <?}?>

                <?if(!$available && !empty($arResult['OPTI_PRICE']['PRICE']['PRICE'])){?>
                    <div class="price-item-cart">
                        <?if(!empty($arResult['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']) && $arResult['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] != $arResult['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']){?>
                            <div class="discont-price"><?=$arResult['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']?> руб.<span>/шт</span></div>
                            <div class="old-price"><?=FormatCurrency($arResult['OPTI_PRICE']['PRICE']['PRICE'], $OPTION_CURRENCY);?></div>
                            <div class="discont-benefit">Экономия: <?=$arResult['OPTI_PRICE']['PRICE']['PRICE'] - $arResult['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']?></div>
                        <?}else{?>
                            <div class="base-price"><?=FormatCurrency($arResult['OPTI_PRICE']['PRICE']['PRICE'], $OPTION_CURRENCY);?><span>/шт</span></div>
                        <?}?>
                    </div>
                <?}?>

                <div class="basket-item card-footer" data-id="<?=$arResult['ID']?>" data-idbasket="<?=$arResult['ID_ON_BASKET']?>">
                    <?if($available){?>
                        <div class="price-item-cart">
                            <?if(!empty($arResult['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']) && $arResult['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] != $arResult['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']){?>
                                <div class="discont-price"><?=$arResult['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']?> руб.<span>/шт</span></div>
                                <div class="old-price"><?=FormatCurrency($arResult['OPTI_PRICE']['PRICE']['PRICE'], $OPTION_CURRENCY);?></div>
                                <div class="discont-benefit">Экономия: <?=$arResult['OPTI_PRICE']['PRICE']['PRICE'] - $arResult['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']?></div>
                                <?}else{?>
                                <div class="base-price"><?=FormatCurrency($arResult['OPTI_PRICE']['PRICE']['PRICE'], $OPTION_CURRENCY);?><span>/шт</span></div>
                            <?}?>
                        </div>
                        <div class="basket-item-td basket-item-quantity checking" <?=($arResult['ON_BASKET'] && $arResult['DELAY'] == 'N') ? '' : ' style="display: none"'?> data-id="<?=$arResult['ID']?>">
                            <div class="basket-item-amount catalog basketQty" data-id="<?=$arResult['ID']?>">
                                <a class="hidden-print basket-item-amount-btn-minus<?=$arResult['ON_BASKET'] ? ' quan-on-basket ' : ' quan '?>minus" href="#" id="amount-m-<?=$arResult['ID']?>">
                                    <?=($arResult['ON_BASKET'] && (int)$arResult['QUANTITY_ON_BASKET'] === 1) ? '<span class="far fa-trash-alt"></span>' : '-'?>
                                </a>
                                <input class="basket-item-amount-input<?=$arResult['ON_BASKET'] ? ' qty-on-basket' : ' qty'?>" id="quantity-cart-element-<?=$arResult["ID"]?>" min="1" type="text" maxlength="18" value="<?=$arResult['ON_BASKET'] ? $arResult['QUANTITY_ON_BASKET'] : 1?>">
                                <a class="hidden-print basket-item-amount-btn-plus<?=$arResult['ON_BASKET'] ? ' quan-on-basket ' : ' quan '?>plus" href="#" id="amount-p-<?=$arResult['ID']?>">+</a>
                            </div>
                        </div>
                        <div data-id="<?=$arResult['ID']?>" id="button-add-to-cart-<?=$arResult['ID']?>" class="element-button add-to-cart" <?=($arResult['ON_BASKET'] && $arResult['DELAY'] == 'N') ? ' style="display: none"' : ''?>><span>В корзину</span></div>
                        <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){?>
                            <div data-id="<?=$arResult['ID']?>" data-mark="CLICK_OZON" data-open="<?=$arResult['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']?>" class="btn-ozon-elem element-button open-market-link"><i class='ex-icon-ozon'></i></div>
                        <?}?>
                        <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){?>
                            <div data-id="<?=$arResult['ID']?>" data-mark="CLICK_WILDBERRIES" data-open="<?=$arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']?>" class="btn-wildberries-elem element-button open-market-link"><i class='ex-icon-wildberries'></i></div>
                        <?}?>
                    <?}else{?>
                        <?if(!empty(ozhidaemayaData($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                            $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'],
                            false, false))){?>
                            <a href="#" class="link not-available">
                                <?=ozhidaemayaData($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                    $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                    false, false);?>
                            </a>
                            <div <?=$arResult['IS_SUBSCRIBED'] ? 'data-idsub="' . $arResult['ID_SUBSCRIBED'] . '"' : ''?> data-item="<?=$arResult['ID']?>" data-contact="<?=$USER->getEmail() ? $USER->getEmail() : 'false'?>" data-auth="<?=$USER->IsAuthorized() ? $USER->GetId() : 'false'?>" id="button-item-subscribe-<?=$arResult['ID']?>" class="subscribe-item catalog-button<?=$arResult['IS_SUBSCRIBED'] ? ' subscribed' : ''?>">
                                <svg version="1.1" class="subscribe-item-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 521 428.8" xml:space="preserve"><path d="M471.5,0h-422C22.2,0,0,22.2,0,49.5v270C0,346.8,22.2,369,49.5,369l141.5,0l54,54c3.8,3.8,8.9,5.8,13.9,5.8s10.1-1.9,13.9-5.8l54-54l144.5,0c27.3,0,49.5-22.2,49.5-49.5v-270C521,22.2,498.8,0,471.5,0z M482,66.4v236.1l-118.8-118L482,66.4z M157.8,184.6L39,302.6V66.4L157.8,184.6z M185.4,212.1l18.9,18.8c15,15,35,23.3,56.2,23.3s41.2-8.3,56.2-23.2l18.9-18.8L454.2,330h-144L259,381.2L207.8,330h-4.3l0,0H66.7L185.4,212.1z M260.5,215.1c-10.8,0-21-4.2-28.7-11.9L66.7,39h387.6L289.1,203.3C281.5,210.9,271.3,215.1,260.5,215.1z"/></svg>
                                <span><?=$arResult['IS_SUBSCRIBED'] ? 'Подписка' : 'Подписаться'?></span>
                            </div>
                        <?}else{?>
                            <a href="#" style="width: 100%;" class="link not-available" data-id="<?=$arResult['ID']?>">
                                Нет в наличии
                            </a>
                            <div <?=$arResult['IS_SUBSCRIBED'] ? 'data-idsub="' . $arResult['ID_SUBSCRIBED'] . '"' : ''?> data-item="<?=$arResult['ID']?>" data-contact="<?=$USER->getEmail() ? $USER->getEmail() : 'false'?>" data-auth="<?=$USER->IsAuthorized() ? $USER->GetId() : 'false'?>" id="button-item-subscribe-<?=$arResult['ID']?>" class="subscribe-item catalog-button<?=$arResult['IS_SUBSCRIBED'] ? ' subscribed' : ''?>">
                                <svg version="1.1" class="subscribe-item-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 521 428.8" xml:space="preserve"><path d="M471.5,0h-422C22.2,0,0,22.2,0,49.5v270C0,346.8,22.2,369,49.5,369l141.5,0l54,54c3.8,3.8,8.9,5.8,13.9,5.8s10.1-1.9,13.9-5.8l54-54l144.5,0c27.3,0,49.5-22.2,49.5-49.5v-270C521,22.2,498.8,0,471.5,0z M482,66.4v236.1l-118.8-118L482,66.4z M157.8,184.6L39,302.6V66.4L157.8,184.6z M185.4,212.1l18.9,18.8c15,15,35,23.3,56.2,23.3s41.2-8.3,56.2-23.2l18.9-18.8L454.2,330h-144L259,381.2L207.8,330h-4.3l0,0H66.7L185.4,212.1z M260.5,215.1c-10.8,0-21-4.2-28.7-11.9L66.7,39h387.6L289.1,203.3C281.5,210.9,271.3,215.1,260.5,215.1z"/></svg>
                                <span><?=$arResult['IS_SUBSCRIBED'] ? 'Подписка' : 'Подписаться'?></span>
                            </div>
                        <?}?>
                    <?}?>
                </div>
            <?}?>
			<br/>
            <?if($arParams['MENU_CHARACT'] === 'OLD'){?>
            <div class="characteristics-menu">
                <ul class="list-menu-ch">
                    <li data-name="description" class="item-ch-men active"><span>Описание</span></li>
                    <li data-name="characteristics" class="item-ch-men"><span>Характеристики</span></li>
                    <li data-name="reviews" class="item-ch-men"><span>Отзывы о товаре</span></li>
                    <li data-name="askquestion" class="item-ch-men"><span>Задать вопрос</span></li>
                </ul>
            </div>
            <div data-name-ch="description"><?=$arResult['DETAIL_TEXT']?></div>
            <div data-name-ch="characteristics" style="display: none;">
                <div class="properties-block-item">
                    <?foreach($arResult['PROPERTIES'] as $PrID => $propertyItem){
                        if(array_key_exists($PrID, $propsIndexes) && !empty($propertyItem['VALUE'])){?>
                            <div class="item-prop_disp">
                                <div class="item-prop-name"><?=$propsIndexes[$PrID] . ': '?></div>
                                <div class="item-prop-val"><?=$propertyItem['VALUE']?></div>
                            </div>
                        <?}?>
                    <?}?>
                </div>
            </div>
            <div data-name-ch="reviews" id="main-block-review" style="display: none;">
                <div class="reviews-evaluation">
                    <div class="reviews-evaluation-title">Хотите оставить отзыв?<span>Поставьте свою оценку!</span></div>
                    <div class="reviews-evaluation-stars" data-id-item="<?=$arResult['ID']?>">
                        <i class="icon-star-s reviews-evaluation-star" data-rat-numb="0" data-rating-id="266" data-value="Ужасно"></i>
                        <i class="icon-star-s reviews-evaluation-star" data-rat-numb="1" data-rating-id="267" data-value="Плохо"></i>
                        <i class="icon-star-s reviews-evaluation-star" data-rat-numb="2" data-rating-id="268" data-value="Нормально"></i>
                        <i class="icon-star-s reviews-evaluation-star" data-rat-numb="3" data-rating-id="269" data-value="Хорошо"></i>
                        <i class="icon-star-s reviews-evaluation-star" data-rat-numb="4" data-rating-id="270" data-value="Отлично"></i>
                    </div>
                    <div class="hidden-md reviews-evaluation-val">Сделайте выбор!</div>
                    <div class="form-send-rev" id="formreview-<?=$arResult['ID']?>" style="display: none">
                        <div class="list item-content item-input item-input-with-value">
                                <ul>
                                    <li class="item-inner block-input">
                                        <div class="item-title item-label">Имя</div>
                                        <div class="item-input-wrap">
                                            <input type="text" id="name-rev" value="<?=$USER->GetFirstName()?>" class="input-with-value">
                                        </div>
                                    </li>
                                    <li class="item-inner block-input">
                                        <div class="item-title item-label">Отзыв</div>
                                        <div class="item-input-wrap">
                                            <textarea id="text-rev"></textarea>
                                        </div>
                                    </li>
                                </ul>
                                <input type="hidden" id="user-id-rev" value="<?=$USER->GetID()?>">
                                <input type="hidden" id="item-id-rev" value="<?=$arResult['ID']?>">
                                <input type="hidden" id="item-name-rev" value="<?=$arResult['NAME']?>">
                                <input type="hidden" id="evaluation-rev" value="">
                                <div class="reviews-general-stats-btn">
                                    <div class="reviews-btn" id="<?=$arResult['ID']?>">
                                        <i class="icon-comment"></i>
                                        <span>Оставить отзыв</span>
                                    </div>
                                </div>
                                <div id="error-rev"></div>
                        </div>
                    </div>
                </div>
                <?
                if(!empty($arResult["RATING_USER_VALUE"])){?>
                    <div class="reviews-items">
                        <?foreach($arResult["RATING_USER_VALUE"] as $itemRat){?>
                            <div class="reviews-item">
                                <div class="container-review-name">
                                    <div class="reviews-item-user-name"><?=$itemRat["USER_NAME"]?></div>
                                    <div class="reviews-item-date"><?=$itemRat["DATE_CREATE"]?></div>
                                </div>
                                <div class="container-review-data">
                                    <div class="reviews-item-caption">
                                        <div class="reviews-item-rating-container">
                                            <div class="reviews-item-rating">
                                                <div class="reviews-item-stars">
                                                    <?for($i = 1; $i <= 5; $i++){?>
                                                        <i class="icon-star-s reviews-item-star<?=$i <= $itemRat["USER_RATING"] ? ' active' : ''?>"></i>
                                                    <?}?>
                                                    <?unset($i);?>
                                                </div>
                                                <div class="reviews-item-term"></div>
                                            </div>
                                            <div class="reviews-item-likes" data-id-rev="<?=$itemRat["ID_REV"]?>"><?=$itemRat["LIKES_COUNT"]?></div>
                                        </div>
                                        <div class="reviews-item-user-text"><?=$itemRat["USER_MESSAGE"]["TEXT"]?></div>
                                        <div class="reviews-item-like"
                                             data-id-rev="<?=$itemRat["ID_REV"]?>"
                                             data-count-likes="<?=$itemRat["LIKES_COUNT_NUMB"]?>"
                                        >
                                            <i class="icon-heart-b" data-entity="like-icon"></i>
                                            <span>Поддерживаю</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?}?>
                    </div>
                <?}?>
            </div>
            <div data-name-ch="askquestion" style="display: none;">
                <div class="form-send-que" id="formquestion-<?=$arResult['ID']?>">
                    <div class="list item-content item-input item-input-with-value">
                        <ul>
                            <li class="item-inner block-input">
                                <div class="item-title item-label">Пожалуйста представтесь</div>
                                <div class="item-input-wrap">
                                    <input type="text" id="name-que" maxlength="60" value="<?=$USER->GetFirstName()?>" class="input-with-value">
                                </div>
                            </li>
                            <li class="item-inner block-input">
                                <div class="item-title item-label">Email адрес</div>
                                <div class="item-input-wrap">
                                    <input type="text" id="email-que" maxlength="70" value="<?=$USER->GetEmail()?>" class="input-with-value">
                                </div>
                            </li>
                            <li class="item-inner block-input">
                                <div class="item-title item-label">Задайте свой вопрос</div>
                                <div class="item-input-wrap">
                                    <textarea id="text-que"></textarea>
                                </div>
                            </li>
                        </ul>
                        <input type="hidden" id="item-name-rev" value="<?=$arResult['NAME']?>">
                        <div class="question-general-stats-btn">
                            <div class="question-btn" id="<?=$arResult['ID']?>">
                                <span>Отправить</span>
                                <i class="icon-question"></i>
                            </div>
                        </div>
                        <div id="error-que"></div>
                    </div>
                </div>
            </div>
            <?}else{?>
                <div id="accordion">
                    <h4>Описание</h4>
                    <div>
                        <?=$arResult['DETAIL_TEXT']?>
                    </div>
                    <h4>Характеристики</h4>
                    <div>
                        <div class="properties-block-item">
                            <?foreach($arResult['PROPERTIES'] as $PrID => $propertyItem){
                                if(array_key_exists($PrID, $propsIndexes) && !empty($propertyItem['VALUE'])){?>
                                    <div class="item-prop_disp">
                                        <div class="item-prop-name"><?=$propsIndexes[$PrID] . ': '?></div>
                                        <div class="item-prop-val"><?=$propertyItem['VALUE']?></div>
                                    </div>
                                <?}?>
                            <?}?>
                        </div>
                    </div>
                    <h4>Отзывы о товаре</h4>
                    <div>
                        <div class="reviews-evaluation">
                            <div class="reviews-evaluation-title">Хотите оставить отзыв?<span>Поставьте свою оценку!</span></div>
                            <div class="reviews-evaluation-stars" data-id-item="<?=$arResult['ID']?>">
                                <i class="icon-star-s reviews-evaluation-star" data-rat-numb="0" data-rating-id="266" data-value="Ужасно"></i>
                                <i class="icon-star-s reviews-evaluation-star" data-rat-numb="1" data-rating-id="267" data-value="Плохо"></i>
                                <i class="icon-star-s reviews-evaluation-star" data-rat-numb="2" data-rating-id="268" data-value="Нормально"></i>
                                <i class="icon-star-s reviews-evaluation-star" data-rat-numb="3" data-rating-id="269" data-value="Хорошо"></i>
                                <i class="icon-star-s reviews-evaluation-star" data-rat-numb="4" data-rating-id="270" data-value="Отлично"></i>
                            </div>
                            <div class="hidden-md reviews-evaluation-val">Сделайте выбор!</div>
                            <div class="form-send-rev" id="formreview-<?=$arResult['ID']?>" style="display: none">
                                <div class="list item-content item-input item-input-with-value">
                                    <ul>
                                        <li class="item-inner block-input">
                                            <div class="item-title item-label">Имя</div>
                                            <div class="item-input-wrap">
                                                <input type="text" id="name-rev" value="<?=$USER->GetFirstName()?>" class="input-with-value">
                                            </div>
                                        </li>
                                        <li class="item-inner block-input">
                                            <div class="item-title item-label">Отзыв</div>
                                            <div class="item-input-wrap">
                                                <textarea id="text-rev"></textarea>
                                            </div>
                                        </li>
                                    </ul>
                                    <input type="hidden" id="user-id-rev" value="<?=$USER->GetID()?>">
                                    <input type="hidden" id="item-id-rev" value="<?=$arResult['ID']?>">
                                    <input type="hidden" id="item-name-rev" value="<?=$arResult['NAME']?>">
                                    <input type="hidden" id="evaluation-rev" value="">
                                    <div class="reviews-general-stats-btn">
                                        <div class="reviews-btn" id="<?=$arResult['ID']?>">
                                            <i class="icon-comment"></i>
                                            <span>Оставить отзыв</span>
                                        </div>
                                    </div>
                                    <div id="error-rev"></div>
                                </div>
                            </div>
                        </div>
                        <?
                        if(!empty($arResult["RATING_USER_VALUE"])){?>
                            <div class="reviews-items">
                                <?foreach($arResult["RATING_USER_VALUE"] as $itemRat){?>
                                    <div class="reviews-item">
                                        <div class="container-review-name">
                                            <div class="reviews-item-user-name"><?=$itemRat["USER_NAME"]?></div>
                                            <div class="reviews-item-date"><?=$itemRat["DATE_CREATE"]?></div>
                                        </div>
                                        <div class="container-review-data">
                                            <div class="reviews-item-caption">
                                                <div class="reviews-item-rating-container">
                                                    <div class="reviews-item-rating">
                                                        <div class="reviews-item-stars">
                                                            <?for($i = 1; $i <= 5; $i++){?>
                                                                <i class="icon-star-s reviews-item-star<?=$i <= $itemRat["USER_RATING"] ? ' active' : ''?>"></i>
                                                            <?}?>
                                                            <?unset($i);?>
                                                        </div>
                                                        <div class="reviews-item-term"></div>
                                                    </div>
                                                    <div class="reviews-item-likes" data-id-rev="<?=$itemRat["ID_REV"]?>"><?=$itemRat["LIKES_COUNT"]?></div>
                                                </div>
                                                <div class="reviews-item-user-text"><?=$itemRat["USER_MESSAGE"]["TEXT"]?></div>
                                                <div class="reviews-item-like"
                                                     data-id-rev="<?=$itemRat["ID_REV"]?>"
                                                     data-count-likes="<?=$itemRat["LIKES_COUNT_NUMB"]?>"
                                                >
                                                    <i class="icon-heart-b" data-entity="like-icon"></i>
                                                    <span>Поддерживаю</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?}?>
                            </div>
                        <?}?>
                    </div>
                    <h4>Задать вопрос</h4>
                    <div>
                        <div class="form-send-que" id="formquestion-<?=$arResult['ID']?>">
                            <div class="list item-content item-input item-input-with-value">
                                <ul>
                                    <li class="item-inner block-input">
                                        <div class="item-title item-label">Пожалуйста представтесь</div>
                                        <div class="item-input-wrap">
                                            <input type="text" id="name-que" maxlength="60" value="<?=$USER->GetFirstName()?>" class="input-with-value">
                                        </div>
                                    </li>
                                    <li class="item-inner block-input">
                                        <div class="item-title item-label">Email адрес</div>
                                        <div class="item-input-wrap">
                                            <input type="text" id="email-que" maxlength="70" value="<?=$USER->GetEmail()?>" class="input-with-value">
                                        </div>
                                    </li>
                                    <li class="item-inner block-input">
                                        <div class="item-title item-label">Задайте свой вопрос</div>
                                        <div class="item-input-wrap">
                                            <textarea id="text-que"></textarea>
                                        </div>
                                    </li>
                                </ul>
                                <input type="hidden" id="item-name-rev" value="<?=$arResult['NAME']?>">
                                <div class="question-general-stats-btn">
                                    <div class="question-btn" id="<?=$arResult['ID']?>">
                                        <span>Отправить</span>
                                        <i class="icon-question"></i>
                                    </div>
                                </div>
                                <div id="error-que"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?}?>
		</div>
	</div>
    <div data-name-ch="reviews" class="block rating-item" style="display: none;">
        <div class="reviews-general-stats">
            <div class="reviews-general-stats-rating">
                <div class="reviews-general-stats-rating-circle p100">
                    <div class="reviews-general-stats-rating-circle-val"><?=$arResult["RATING_VALUE"]?></div>
                    <div class="reviews-general-stats-rating-circle-slice">
                        <div class="reviews-general-stats-rating-circle-bar"></div>
                        <div class="reviews-general-stats-rating-circle-fill"></div>
                    </div>
                </div>
                <div class="reviews-general-stats-rating-title">Общий рейтинг</div>
                <div class="reviews-general-stats-btn">
                    <div class="reviews-btn footer" id="<?=$arResult['ID']?>">
                        <i class="icon-comment"></i>
                        <span>Написать отзыв</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
