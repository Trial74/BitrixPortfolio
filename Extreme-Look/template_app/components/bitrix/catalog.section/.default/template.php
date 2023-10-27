<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?
$OPTION_CURRENCY = CCurrency::GetBaseCurrency();
$firstBuyElBool = false;
$firstBueParam = array();
$selectedItem = array();
$select = false;
$haveOffers = false;
$noItemImage = false;
$idBasket = '';
$propsIndexes = array(
    'OBYEM' => 'Объём',
    'DLITELNOST_NOSKI' => 'Длительность носки',
    'SKOROST_STSEPKI' => 'Скорость сцепки',
    'TSVET' => 'Цвет',
    'TSVET_1' => 'Цвет',
    'TSVET_3' => 'Цвет',
    'VREMYA_EKSPOZITSII' => 'Время экспозиции',
    'LIPKOST_LENTY_1' => 'Липкость ленты',
    'KOLICHESTVO_LINIY_1' => 'Количество линий'
);
$arImageFilter = [
    [
        "name" => "watermark",
        "position" => "center",
        "fill"=>"repeat",
        "size"=>"big",
        "file" => $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/dresscode/images/watermark.png"
    ]
];
$lastInfinite = false;
$listType = 'list';

if(isset($_SESSION['CATALOG_VIEW']))
    $listType = $_SESSION['CATALOG_VIEW'];
if($arResult["NAV_RESULT"]->NavPageCount == 1 || $arResult["NAV_RESULT"]->NavPageCount == $_GET['PAGEN_2']){
    echo '<span class="last-infinite"></span>';
    $lastInfinite = true;
}
elseif($arResult["NAV_RESULT"]->NavPageCount < $_GET['PAGEN_2'])
    exit;
?>

<?if(!empty($arResult["ITEMS"])){?>
	<?if(!AJAX_REQUEST){?>
        <div class="card-sortview">
            <div class="card-content">
                <a href="#" class="tab-link catalog-sort link sortview no-ripple" data-sort="<?=$_SESSION['CATALOG_SORT']?>">
                    <span class="ex-sort-app"></span>
                    <span style="vertical-align: middle; line-height: 15px; color: black;">Сортировка</span>
                    <div id="sortdrop" style="height: 0"></div>
                </a>
                <a href="#" class="tab-link catalog-view link sortview no-ripple" data-view="<?=$listType?>">
                    <span class="ex-view-app<?=$listType == 'list' ? ' view-list' : ' view-tile'?>"></span>
                    <span style="vertical-align: middle; line-height: 15px; color: black;">Отображение</span>
                    <div id="viewdrop" style="height: 0"></div>
                </a>
            </div>
        </div>
	<?}?>
    <?if($listType == 'list'){?>
        <div class="infinite-items list-type-list">
        <?foreach($arResult["ITEMS"] as $element){
            $marketplace = array(
                'ozon' => false,
                'wildberries' => false
            );
            if(isset($element['PROPERTIES']['SSYLKA_NA_OZON']) && !empty($element['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']) || (isset($arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']) && !empty($arResult['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']))){
                $parseOZ = parse_url($element['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']);
                $parseWB = parse_url($element['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']);
                if(is_array($parseOZ))
                    if(isset($parseOZ['host']) && ($parseOZ['host'] === 'www.ozon.ru' || $parseOZ['host'] === 'ozon.ru'))
                        $marketplace['ozon'] = true;
                if(is_array($parseWB))
                    if(isset($parseWB['host']) && ($parseWB['host'] === 'www.wildberries.ru' || $parseWB['host'] === 'wildberries.ru'))
                        $marketplace['wildberries'] = true;
            }?>
            <?//Ищем первый доступный товар в характеристиках если нет такого ставим первый в массиве выбранным НАЧАЛО
            if(!empty($element['OFFERS']) && !empty($element['VLAD_SKU_DISPLAY'])){
                $haveOffers = true;
                $selectedItem = getParamsOffersByApp($element); //Функция в init собирает данные первого выбранного торгового предложения
                if($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && $selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'N')
                    $idBasket = $selectedItem['SELECTED_ITEM']['IN_BASKET']['BASKET_ID'];
            }else{
                if($element['ON_BASKET'] && $element['IS_DELAY'] == 'N'){
                    $idBasket = $element['ID_ON_BASKET'];
                }
            }
            //Ищем первый доступный товар в характеристиках если нет такого ставим первый в массиве выбранным КОНЕЦ?>

            <div class="basket-item card product-card infinite-item<?=$haveOffers ? ' true-offer' : ''?>"
                 id="block-catalog-card-offer-<?=$element["ID"]?>"
                 data-id="<?=$haveOffers ? $selectedItem['SELECTED_ITEM']['ID'] : $element["ID"]?>"
                 data-idbasket="<?=$idBasket?>"
            >
                <?if((!empty($element["PROPERTIES"]["MARKER"]["VALUE"])) || (!empty($element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']) && $element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] != $element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'])){?>
                    <div class="markerContainer">
                        <?if(!empty($element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']) && $element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] != $element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']){?>
                            <span class="product-item-marker product-item-marker-discount product-item-marker-14px"><span data-entity="dsc-perc-val"><?=-$element['OPTI_PRICE']['RESULT_PRICE']['PERCENT']?>%</span></span>
                        <?}?>
                        <?foreach ($element["PROPERTIES"]["MARKER"]["VALUE"] as $ifv => $marker){?>
                            <i class="marker <?=$marker['ICON']?>"></i>
                        <?}?>
                    </div>
                <?}?>
                <a href="/page-catalog.element/element-id=<?=$element['ID']?>/" class="link card-header">
                    <div class="product-avatar" id="<?=$element['ID']?>">
                        <?$canBuy = false;
                        if($haveOffers && !empty($selectedItem['SELECTED_ITEM']['PREV_PICTURE'])){
                            $noItemImage = true;
                            if($arParams['SHOW_PHOTO_LAST_OFFER'] == 'Y' && !empty($element['VLAD_SKU'][$element['ID']]) && !$arParams['NO_LAST_IMAGE']){
                                $lastElemArr = end($element['VLAD_SKU'][$element['ID']]);
                                $prevPicture = $lastElemArr['PREV_PICTURE'];
                                reset($element['VLAD_SKU'][$element['ID']]);
                                unset($element['VLAD_SKU'][$element['ID']][$lastElemArr['ID']]);
                            }else{
                                $prevPicture = $selectedItem['SELECTED_ITEM']['PREV_PICTURE'];
                                unset($element['VLAD_SKU'][$element['ID']][$selectedItem["SELECTED_ITEM"]["ID"]]);
                            }
                        }
                        else $prevPicture = $element["DETAIL_PICTURE"];

                        $element["IMAGE"] = CFile::ResizeImageGet($prevPicture, ["width" => 150, "height" => 150], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
                        if(empty($element["IMAGE"])){
                            $element["IMAGE"]["src"] = NOIMAGE_PATH;
                        }?>
                        <img id="<?=$haveOffers ? ($arParams['SHOW_PHOTO_LAST_OFFER'] == 'Y' && !$arParams['NO_LAST_IMAGE']) ? $lastElemArr['ID'] : $selectedItem["SELECTED_ITEM"]["ID"] : ''?>" style="max-width: 150px; max-height: 150px;" src="<?=$element["IMAGE"]["src"]?>" class="lazy<?=$haveOffers ? ' photo-offer' : ''?>" />
                        <?if($haveOffers && $noItemImage){
                            foreach($element['VLAD_SKU'][$element['ID']] as $key => $skuPhotos) {?>
                                <img id="<?=$skuPhotos["ID"]?>" style="max-width: 150px; max-height: 150px;" src="<?=$skuPhotos["PREV_PICTURE"]["SRC"]?>" class="lazy hidden_app photo-offer" />
                            <?}
                        }?>

                        <div class="rating-item">
                            <div class="reviews-item-stars">
                                <?for($i = 1; $i <= 5; $i++){?>
                                    <i class="icon-star-s reviews-item-star<?=$i <= $element["RATING_VALUE"] ? ' active' : ''?>"></i>
                                <?}?>
                            <?unset($i);?>
                            </div>
                            <div class="count-reviews"><?=$element["REVIEWS_COUNT"]?></div>
                        </div>
                    </div>
                    <div class="product-content">
                        <div class="product-name">
                            <?=$element['NAME']?>
                        </div>
                        <?if($element['PROPERTIES']['MINI_OPISANIE_DLYA_APP']['VALUE']){?>
                            <div class="mini-description">
                                <?=$element['PROPERTIES']['MINI_OPISANIE_DLYA_APP']['VALUE']?>
                            </div>
                        <?}?>
                        <div class="properties-block-section">
                            <?foreach($element['PROPERTIES'] as $PrID => $propertyItem){
                                if(array_key_exists($PrID, $propsIndexes) && !empty($propertyItem['VALUE'])){?>
                                    <div class="section-prop_disp">
                                        <?=$propsIndexes[$PrID] . ': '?>
                                        <span><?=$propertyItem['VALUE']?></span>
                                    </div>
                                 <?}?>
                            <?}?>
                        </div>
                        <?if($haveOffers){//Если есть торговые предложения?>
                            <div class="product-date item-subtitle block-display-quantity">
                                <?if($selectedItem['SELECTED_ITEM']["QUANTITY"] > 0){
                                    $available = true;
                                    $quantRes = catalogQuantity($selectedItem['SELECTED_ITEM']["QUANTITY"]);
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
                                        <span>Нет в наличии</span>
                                    </div>
                                <?}?>
                            </div>

                            <div class="price-item-cart"<?=!empty($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE']) ? '' : ' style="display: none"'?>>
                                <?$discontPrice = !empty($selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT']) && $selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT'] != $selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'];?>

                                <div class="discont-price"<?=$discontPrice ? '' : ' style="display: none"'?>><?=$selectedItem['SELECTED_ITEM']['PRICES']['PRICE']?> руб.<span>/шт</span></div>
                                <div class="old-price"<?=$discontPrice ? '' : ' style="display: none"'?>><?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'], $OPTION_CURRENCY);?></div>
                                <div class="discont-benefit"<?=$discontPrice ? '' : ' style="display: none"'?>>Экономия: <?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'] - $selectedItem['SELECTED_ITEM']['PRICES']['PRICE'], $OPTION_CURRENCY)?></div>
                                <div class="base-price"<?=!$discontPrice ? '' : ' style="display: none"'?>><?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['PRICE'], $OPTION_CURRENCY);?><span>/шт</span></div>
                            </div>

                            <div href="#" data-dt="" class="link disabled"<?=!$available ? '' : ' style="display: none"'?>>
                                <?if(!empty(ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'], $element['PROPERTIES']['CML2_TRAITS']['VALUE'], false, false))){?>
                                    <?=ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'], $element['PROPERTIES']['CML2_TRAITS']['VALUE'], false, false)?>
                                <?}else{?>
                                    Нет в наличии
                                <?}?>

                            </div>
                    </div>
                </a>

                                <div class="product-item-catalog-scu-container" id="scu-container-<?=$element['ID']?>" data-id-item="<?=$element['ID']?>">
                                    <?foreach ($element['VLAD_SKU_DISPLAY'] as $key => $V_sku){
                                        $propertyId = $V_sku["ID"];
                                        $firstBuyElBool = false;?>
                                        <div class="product-item-detail-scu-elem">
                                            <div class="product-item-detail-info-container" data-entity="sku-line-block">
                                                <div class="product-item-detail-scu-title"><?=htmlspecialcharsEx($V_sku["NAME"])?></div>
                                            </div>
                                            <div class="product-item-detail-scu-block">
                                                <div class="product-item-detail-scu-list" data-id-item="<?=$element['ID']?>" data-prop="<?=$propertyId?>">
                                                    <ul class="product-item-detail-scu-item-list"
                                                        data-entity="sku-line-list"
                                                        data-id-item="<?=$element['ID']?>"
                                                        data-prop="<?=$propertyId?>"
                                                        data-name="<?=htmlspecialcharsEx($V_sku["NAME"])?>"
                                                    >
                                                        <?foreach($V_sku["VALUES"] as $keyVal => &$value) {
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
                                        </div>
                                    <?}?>
                                    <?=$selectedItem['HIDDEN_HTML']?>
                                </div>

                            <div class="quantity-in-section-list basket-item-td basket-item-quantity checking<?=($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && $selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'N') ? '' : ' hidden_app'?>" data-id="<?=$selectedItem['SELECTED_ITEM']['ID']?>"<?=$selectedItem['SELECTED_ITEM']['CAN_BUY'] ? '' : ' style="display: none"'?>>
                                <div class="basket-item-amount catalog basketQty" data-id="<?=$selectedItem['SELECTED_ITEM']["ID"]?>">
                                    <a class="hidden-print basket-item-amount-btn-minus quan-on-basket minus" href="#" id="amount-m-<?=$selectedItem['SELECTED_ITEM']['ID']?>">
                                        <?=($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && (int)$selectedItem['SELECTED_ITEM']['IN_BASKET']['QUANTITY'] === 1) ? '<span class="far fa-trash-alt"></span>' : '-'?>
                                    </a>
                                    <input class="basket-item-amount-input qty-on-basket" id="quantity-cart-catalog-<?=$selectedItem['SELECTED_ITEM']['ID']?>" min="1" type="number" maxlength="18" value="<?=$selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] ? $selectedItem['SELECTED_ITEM']['IN_BASKET']['QUANTITY'] : 1?>">
                                    <a class="hidden-print basket-item-amount-btn-plus quan-on-basket plus" href="#" id="amount-p-<?=$selectedItem['SELECTED_ITEM']['ID']?>">+</a>
                                </div>
                                <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){?>
                                    <a data-id="<?=$selectedItem['PARAMETERS']['OZON']['ID_ITEM']?>" data-mark="CLICK_OZON" class="btn-ozon open-market-link" href="javascript: void(0)" data-open="<?=$selectedItem['PARAMETERS']['OZON']['LINK']?>"><i class='ex-icon-ozon'></i></a>
                                <?}?>
                                <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){?>
                                    <a data-id="<?=$selectedItem['PARAMETERS']['WILDBERRIES']['ID_ITEM']?>" data-mark="CLICK_WILDBERRIES" class="btn-wildberries open-market-link" href="javascript: void(0)" data-open="<?=$selectedItem['PARAMETERS']['WILDBERRIES']['LINK']?>"><i class='ex-icon-wildberries'></i></a>
                                <?}?>
                            </div>

                            <div class="catalog-buy-button block-button-buy<?=($marketplace['ozon'] || $marketplace['wildberries'] ? ' ex-marketplaces': '')?>"<?=(!$selectedItem['SELECTED_ITEM']['CAN_BUY'] || $selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT']) ? ' style="display: none"' : ''?>>
                                <div data-id="<?=$selectedItem['SELECTED_ITEM']['ID']?>" id="button-add-to-cart-<?=$selectedItem['SELECTED_ITEM']['ID']?>" class="add-to-cart catalog-button"><span>В корзину</span></div>
                                <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){?>
                                    <a data-id="<?=$selectedItem['PARAMETERS']['OZON']['ID_ITEM']?>" data-mark="CLICK_OZON" class="btn-ozon open-market-link" href="javascript: void(0)" data-open="<?=$selectedItem['PARAMETERS']['OZON']['LINK']?>"><i class='ex-icon-ozon'></i></a>
                                <?}?>
                                <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){?>
                                    <a data-id="<?=$selectedItem['PARAMETERS']['WILDBERRIES']['ID_ITEM']?>" data-mark="CLICK_WILDBERRIES" class="btn-wildberries open-market-link" href="javascript: void(0)" data-open="<?=$selectedItem['PARAMETERS']['WILDBERRIES']['LINK']?>"><i class='ex-icon-wildberries'></i></a>
                                <?}?>
                            </div>

                            <div class="catalog-sub-button block-button-buy<?=($marketplace['ozon'] || $marketplace['wildberries'] ? ' market-block': '')?>"<?=$selectedItem['SELECTED_ITEM']['CAN_BUY'] ? ' style="display: none"' : ''?>>
                                <div <?=$element['IS_SUBSCRIBED'] ? 'data-idsub="' . $element['ID_SUBSCRIBED'] . '"' : ''?> data-item="<?=$element['ID']?>" data-contact="<?=$USER->getEmail() ? $USER->getEmail() : 'false'?>" data-auth="<?=$USER->IsAuthorized() ? $USER->GetId() : 'false'?>" id="button-section-subscribe-<?=$element['ID']?>" class="subscribe-item catalog-button<?=$element['IS_SUBSCRIBED'] ? ' subscribed' : ''?>">
                                    <svg version="1.1" class="subscribe-item-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 521 428.8" xml:space="preserve"><path d="M471.5,0h-422C22.2,0,0,22.2,0,49.5v270C0,346.8,22.2,369,49.5,369l141.5,0l54,54c3.8,3.8,8.9,5.8,13.9,5.8s10.1-1.9,13.9-5.8l54-54l144.5,0c27.3,0,49.5-22.2,49.5-49.5v-270C521,22.2,498.8,0,471.5,0z M482,66.4v236.1l-118.8-118L482,66.4z M157.8,184.6L39,302.6V66.4L157.8,184.6z M185.4,212.1l18.9,18.8c15,15,35,23.3,56.2,23.3s41.2-8.3,56.2-23.2l18.9-18.8L454.2,330h-144L259,381.2L207.8,330h-4.3l0,0H66.7L185.4,212.1z M260.5,215.1c-10.8,0-21-4.2-28.7-11.9L66.7,39h387.6L289.1,203.3C281.5,210.9,271.3,215.1,260.5,215.1z"/></svg>
                                    <span><?=$element['IS_SUBSCRIBED'] ? 'Подписка' : 'Подписаться'?></span>
                                </div>
                                <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){?>
                                    <a data-id="<?=$selectedItem['PARAMETERS']['OZON']['ID_ITEM']?>" data-mark="CLICK_OZON" class="btn-ozon open-market-link" href="javascript: void(0)" data-open="<?=$selectedItem['PARAMETERS']['OZON']['LINK']?>"><i class='ex-icon-ozon'></i></a>
                                <?}?>
                                <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){?>
                                    <a data-id="<?=$selectedItem['PARAMETERS']['WILDBERRIES']['ID_ITEM']?>" data-mark="CLICK_WILDBERRIES" class="btn-ozon open-market-link" href="javascript: void(0)" data-open="<?=$selectedItem['PARAMETERS']['WILDBERRIES']['LINK']?>"><i class='ex-icon-ozon'></i></a>
                                <?}?>
                            </div>

                            <div class="block-item-info"<?=!empty($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE']) || !getPartner() ? '' : ' style="display: none"'?>>
                                <a href="/page-wholesale/" data-name="wholesale" class="opt-price-app">Оптовая стоимость от <?=round($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'] - ($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'] * 0.41))?> руб.</a>
                                <a href="/page-delivery/" data-name="delivery" class="delivery-info-app">Доставим бесплатно от 5000 руб.</a>
                            </div>
                            <div class="delay-button<?=$selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'Y' ? ' delay-active' : ''?>" id="delay" data-item="<?=$selectedItem['SELECTED_ITEM']['ID']?>" data-idbasket="<?=$haveOffers ? (($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && $selectedItem['SELECTED_ITEM']['IN_BASKET']['DELAY'] == 'Y') ? $selectedItem['SELECTED_ITEM']['IN_BASKET']['BASKET_ID'] : '') : (($element['ON_BASKET'] && $element['IS_DELAY'] == 'Y') ? $element['ID_ON_BASKET'] : '')?>"></div>

                            <?$available = false;?>
                        <?}else{?>
                            <div class="product-date item-subtitle">
                                <?if($element["CATALOG_QUANTITY"] > 0){?>
                                    <?$canBuy = true;
                                    $quantRes = catalogQuantity($element["CATALOG_QUANTITY"]);
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
                                        <span>Нет в наличии</span>
                                    </div>
                                <?}?>
                            </div>

                                <?if(!empty($element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'])){?>
                                    <div class="price-item-cart">
                                        <?if(!empty($element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']) && $element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] != $element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']){?>
                                            <div class="discont-price"><?=$element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']?> руб<span>/шт</span></div>
                                            <div class="old-price"><?=$element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']?> руб</div>
                                            <div class="discont-benefit">Экономия: <?=$element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'] - $element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']?> руб</div>
                                        <?}else{?>
                                            <div class="base-price"><?=$element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']?> руб<span>/шт</span></div>
                                        <?}?>
                                    </div>
                                <?}?>
                                    <?if($canBuy){?>
                            </div>
                        </a>
                                        <div class="quantity-in-section-list basket-item-td basket-item-quantity checking" <?=($element['ON_BASKET'] && $element['IS_DELAY'] != 'Y') ? '' : ' style="display: none"'?>" data-id="<?=$element['ID']?>">
                                            <div class="basket-item-amount catalog basketQty" data-id="<?=$element["ID"]?>">
                                                <a class="hidden-print basket-item-amount-btn-minus<?=($element['ON_BASKET'] && $element['IS_DELAY'] != 'Y') ? ' quan-on-basket ' : ' quan '?>minus" href="#" id="amount-m-<?=$element['ID']?>">
                                                    <?=($element['ON_BASKET'] && (int)$element['QUANTITY_ON_BASKET'] === 1) ? '<span class="far fa-trash-alt"></span>' : '-'?>
                                                </a>
                                                <input class="basket-item-amount-input<?=($element['ON_BASKET'] && $element['IS_DELAY'] != 'Y') ? ' qty-on-basket' : ' qty'?>" id="quantity-cart-catalog-<?=$element["ID"]?>" min="1" type="number" maxlength="18" value="<?=($element['ON_BASKET'] && $element['IS_DELAY'] != 'Y') ? $element['QUANTITY_ON_BASKET'] : 1?>">
                                                <a class="hidden-print basket-item-amount-btn-plus<?=($element['ON_BASKET'] && $element['IS_DELAY'] != 'Y') ? ' quan-on-basket ' : ' quan '?>plus" href="#" id="amount-p-<?=$element['ID']?>">+</a>
                                            </div>
                                            <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){?>
                                                <a data-id="<?=$element['ID']?>" data-mark="CLICK_OZON" class="btn-ozon open-market-link" href="javascript: void(0)" data-open="<?=$element['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']?>"><i class='ex-icon-ozon'></i></a>
                                            <?}?>
                                            <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){?>
                                                <a data-id="<?=$element['ID']?>" data-mark="CLICK_WILDBERRIES" class="btn-wildberries open-market-link" href="javascript: void(0)" data-open="<?=$element['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']?>"><i class='ex-icon-wildberries'></i></a>
                                            <?}?>
                                        </div>
                                        <div class="block-button-buy<?=($marketplace['ozon'] || $marketplace['wildberries'] ? ' ex-marketplaces': '')?>"<?=($element['ON_BASKET'] && $element['IS_DELAY'] != 'Y') ? ' style="display: none"' : ''?>>
                                            <div data-id="<?=$element['ID']?>" id="button-add-to-cart-<?=$element['ID']?>" class="catalog-button add-to-cart"><span>В корзину</span></div>
                                            <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){?>
                                                <a data-id="<?=$element['ID']?>" data-mark="CLICK_OZON" class="btn-ozon open-market-link" href="javascript: void(0)" data-open="<?=$element['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']?>"><i class='ex-icon-ozon'></i></a>
                                            <?}?>
                                            <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){?>
                                                <a data-id="<?=$element['ID']?>" data-mark="CLICK_WILDBERRIES" class="btn-wildberries open-market-link" href="javascript: void(0)" data-open="<?=$element['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']?>"><i class='ex-icon-wildberries'></i></a>
                                            <?}?>
                                        </div>
                                        <?if(!getPartner()){?>
                                            <div class="block-item-info">
                                                <a href="/page-wholesale/" data-name="wholesale" class="opt-price-app">Оптовая стоимость от <?=round($element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'] - ($element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'] * 0.41))?> руб.</a>
                                                <a href="/page-delivery/" data-name="delivery" class="delivery-info-app">Доставим бесплатно от 5000 руб.</a>
                                            </div>
                                            <div class="delay-button<?=($element['ON_BASKET'] && $element['IS_DELAY'] == 'Y') ? ' delay-active' : ''?>" id="delay" data-item="<?=$element['ID']?>" data-idbasket="<?=($element['ON_BASKET'] && $element['IS_DELAY'] == 'Y') ? $element['ID_ON_BASKET'] : ''?>"></div>
                                        <?}?>
                                    <?}else{?>
                                        <div href="#" data-dt="" class="link disabled">
                                            <?if(!empty(ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                                $element['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                                false, false))){?>
                                                <?=ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                                    $element['PROPERTIES']['CML2_TRAITS']['VALUE'], false, false)?>
                                            <?}else{?>
                                                Нет в наличии
                                            <?}?>
                                        </div>
                                </div>
                            </a>
                                    <div class="block-button-buy<?=$marketplace['ozon'] || $marketplace['wildberries'] ? ' market-block' : ''?>">
                                        <div <?=$element['IS_SUBSCRIBED'] ? 'data-idsub="' . $element['ID_SUBSCRIBED'] . '"' : ''?> data-item="<?=$element['ID']?>" data-contact="<?=$USER->getEmail() ? $USER->getEmail() : 'false'?>" data-auth="<?=$USER->IsAuthorized() ? $USER->GetId() : 'false'?>" id="button-section-subscribe-<?=$element['ID']?>" class="subscribe-item catalog-button<?=$element['IS_SUBSCRIBED'] ? ' subscribed' : ''?>">
                                            <svg version="1.1" class="subscribe-item-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 521 428.8" xml:space="preserve"><path d="M471.5,0h-422C22.2,0,0,22.2,0,49.5v270C0,346.8,22.2,369,49.5,369l141.5,0l54,54c3.8,3.8,8.9,5.8,13.9,5.8s10.1-1.9,13.9-5.8l54-54l144.5,0c27.3,0,49.5-22.2,49.5-49.5v-270C521,22.2,498.8,0,471.5,0z M482,66.4v236.1l-118.8-118L482,66.4z M157.8,184.6L39,302.6V66.4L157.8,184.6z M185.4,212.1l18.9,18.8c15,15,35,23.3,56.2,23.3s41.2-8.3,56.2-23.2l18.9-18.8L454.2,330h-144L259,381.2L207.8,330h-4.3l0,0H66.7L185.4,212.1z M260.5,215.1c-10.8,0-21-4.2-28.7-11.9L66.7,39h387.6L289.1,203.3C281.5,210.9,271.3,215.1,260.5,215.1z"/></svg>
                                            <span><?=$element['IS_SUBSCRIBED'] ? 'Подписка' : 'Подписаться'?></span>
                                        </div>
                                        <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){?>
                                            <a data-id="<?=$element['ID']?>" data-mark="CLICK_OZON" class="btn-ozon open-market-link" href="javascript: void(0)" data-open="<?=$element['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']?>"><i class='ex-icon-ozon'></i></a>
                                        <?}?>
                                        <?if(!getPartner() && !getNewPartner() && $marketplace['wildberries']){?>
                                            <a data-id="<?=$element['ID']?>" data-mark="CLICK_WILDBERRIES" class="btn-wildberries open-market-link" href="javascript: void(0)" data-open="<?=$element['PROPERTIES']['SSYLKA_NA_WILDBERRIES']['VALUE']?>"><i class='ex-icon-wildberries'></i></a>
                                        <?}?>
                                    </div>
                                    <?if(!empty($element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'])){?>
                                        <div class="block-item-info">
                                            <a href="/page-wholesale/" data-name="wholesale" class="opt-price-app">Оптовая стоимость от <?=round($element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'] - ($element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'] * 0.41))?> руб.</a>
                                            <a href="/page-delivery/" data-name="delivery" class="delivery-info-app">Доставим бесплатно от 5000 руб.</a>
                                        </div>
                                    <?}?>
                                <?}?>
                        <?}?>
            </div>
            <?$haveOffers = false;?>
        <?}?>
        <?if(!AJAX_REQUEST){?>
            </div>
            <?if(!$lastInfinite){?>
                <div class="preloader infinite-scroll-preloader"></div>
            <?}?>
        <?}?>
    <?}elseif($listType == 'tile'){?>
        <div class="infinite-items flex-container-items tile">
        <?foreach($arResult["ITEMS"] as $element){
            $marketplace = array(
                'ozon' => false,
                'wildberries' => false
            );
            $bueMarket = false;
            if(isset($element['PROPERTIES']['SSYLKA_NA_OZON']) && !empty($element['PROPERTIES']['SSYLKA_NA_OZON']['VALUE'])){
                $parse = parse_url($element['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']);
                if(is_array($parse))
                    if(isset($parse['host']) && ($parse['host'] === 'www.ozon.ru' || $parse['host'] === 'ozon.ru'))
                        $marketplace['ozon'] = true;
                if(isset($parse['host']) && ($parse['host'] === 'www.wildberries.ru' || $parse['host'] === 'wildberries.ru'))
                    $marketplace['wildberries'] = true;
            }?>
            <?//Ищем первый доступный товар в характеристиках если нет такого ставим первый в массиве выбранным НАЧАЛО
            if(!empty($element['OFFERS']) && !empty($element['VLAD_SKU_DISPLAY'])){
                $haveOffers = true;
                $selectedItem = getParamsOffersByApp($element); //Функция в init собирает данные первого выбранного торгового предложения
            }
            //Ищем первый доступный товар в характеристиках если нет такого ставим первый в массиве выбранным КОНЕЦ?>
            <div class="flex-item-catalog infinite-item basket-item<?=$haveOffers ? ' true-offer' : ''?>"
                 id="block-catalog-card-offer-<?=$element["ID"]?>"
                 data-id="<?=$haveOffers ? $selectedItem['SELECTED_ITEM']['ID'] : $element["ID"]?>"
                 data-idbasket="<?=$haveOffers ? ($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] ? $selectedItem['SELECTED_ITEM']['IN_BASKET']['BASKET_ID'] : '') : $element['ID_ON_BASKET']?>"
                 style="height: min-content;"
            >
                <?if((!empty($element["PROPERTIES"]["MARKER"]["VALUE"])) || (!empty($element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']) && $element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] != $element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'])){?>
                    <div class="markerContainer">
                        <?if(!empty($element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']) && $element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] != $element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']){?>
                            <span class="product-item-marker product-item-marker-discount product-item-marker-14px"><span data-entity="dsc-perc-val"><?=-$element['OPTI_PRICE']['RESULT_PRICE']['PERCENT']?>%</span></span>
                        <?}?>
                        <?foreach ($element["PROPERTIES"]["MARKER"]["VALUE"] as $ifv => $marker){?>
                            <i class="marker <?=$marker['ICON']?>"></i>
                        <?}?>
                    </div>
                <?}?>
                <a href="/page-catalog.element/element-id=<?=$element['ID']?>/" class="link card-tile">
                    <div class="product-avatar">
                        <?$canBuy = false;
                        if($haveOffers && !empty($selectedItem['SELECTED_ITEM']['PREV_PICTURE'])){
                            $noItemImage = true;
                            if($arParams['SHOW_PHOTO_LAST_OFFER'] == 'Y' && !empty($element['VLAD_SKU'][$element['ID']])){
                                $lastElemArr = end($element['VLAD_SKU'][$element['ID']]);
                                $prevPicture = $lastElemArr['PREV_PICTURE'];
                                reset($element['VLAD_SKU'][$element['ID']]);
                                unset($element['VLAD_SKU'][$element['ID']][$lastElemArr['ID']]);
                            }else{
                                $prevPicture = $selectedItem['SELECTED_ITEM']['PREV_PICTURE'];
                                unset($element['VLAD_SKU'][$element['ID']][$selectedItem["SELECTED_ITEM"]["ID"]]);
                            }
                        }
                        else $prevPicture = $element["DETAIL_PICTURE"];

                        $element["IMAGE"] = CFile::ResizeImageGet($prevPicture, ["width" => 150, "height" => 150], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
                        if(empty($element["IMAGE"])){
                            $element["IMAGE"]["src"] = NOIMAGE_PATH;
                        }?>
                        <img id="<?=$haveOffers ? $arParams['SHOW_PHOTO_LAST_OFFER'] == 'Y' ? $lastElemArr['ID'] : $selectedItem["SELECTED_ITEM"]["ID"] : ''?>" src="<?=NOIMAGE_PATH?>" style="max-width: 150px; max-height: 150px;" data-src="<?=$element["IMAGE"]["src"]?>" class="lazy<?=$haveOffers ? ' photo-offer' : ''?>" />

                        <?if($haveOffers && $noItemImage){
                            foreach($element['VLAD_SKU'][$element['ID']] as $key => $skuPhotos) {?>
                                <img id="<?=$skuPhotos["ID"]?>" src="<?=NOIMAGE_PATH?>" style="max-width: 150px; max-height: 150px;" data-src="<?=$skuPhotos["PREV_PICTURE"]["SRC"]?>" class="lazy hidden_app photo-offer" />
                            <?}
                        }?>
                    </div>
                    <div class="product-content">
                        <div class="product-name">
                            <?=$element['NAME']?>
                        </div>
                        <?if($element['PROPERTIES']['MINI_OPISANIE_DLYA_APP']['VALUE']){?>
                            <div class="mini-description">
                                <?=$element['PROPERTIES']['MINI_OPISANIE_DLYA_APP']['VALUE']?>
                            </div>
                        <?}?>
                        <div class="properties-block-section">
                            <?foreach($element['PROPERTIES'] as $PrID => $propertyItem){
                                if(array_key_exists($PrID, $propsIndexes) && !empty($propertyItem['VALUE'])){?>
                                    <div class="section-prop_disp">
                                        <?=$propsIndexes[$PrID] . ': '?>
                                        <span><?=$propertyItem['VALUE']?></span>
                                    </div>
                                <?}?>
                            <?}?>
                        </div>
                        <?if($haveOffers){?>
                            <div class="product-date item-subtitle block-display-quantity">
                                <?if($selectedItem['SELECTED_ITEM']["QUANTITY"] > 0){
                                    $available = true;
                                    $quantRes = catalogQuantity($selectedItem['SELECTED_ITEM']["QUANTITY"]);
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
                                        <span>Нет в наличии</span>
                                    </div>
                                <?}?>
                            </div>
                            <div class="price-item-cart"<?=!empty($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE']) ? '' : ' style="display: none"'?>>
                                <?$discontPrice = !empty($selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT']) && $selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT'] != $selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'];?>

                                <div class="discont-price"<?=$discontPrice ? '' : ' style="display: none"'?>><?=$selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT']?> руб.<span>/шт</span></div>
                                <div class="old-price"<?=$discontPrice ? '' : ' style="display: none"'?>><?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['PRICE'], $OPTION_CURRENCY);?></div>
                                <div class="discont-benefit"<?=$discontPrice ? '' : ' style="display: none"'?>>Экономия: <?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['PRICE'] - $selectedItem['SELECTED_ITEM']['PRICES']['DISCOUNT'], $OPTION_CURRENCY)?></div>
                                <div class="base-price"<?=!$discontPrice ? '' : ' style="display: none"'?>><?=FormatCurrency($selectedItem['SELECTED_ITEM']['PRICES']['PRICE'], $OPTION_CURRENCY);?><span>/шт</span></div>
                            </div>

                            <div href="#" data-dt="" class="link disabled"<?=!$available ? '' : ' style="display: none"'?>>
                                <?if(!empty(ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'], $element['PROPERTIES']['CML2_TRAITS']['VALUE'], false, false))){?>
                                    <?=ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'], $element['PROPERTIES']['CML2_TRAITS']['VALUE'], false, false)?>
                                <?}else{?>
                                    Нет в наличии
                                <?}?>

                            </div>
                    </div>
                </a>
                            <div class="product-item-catalog-scu-container" id="scu-container-<?=$element['ID']?>" data-id-item="<?=$element['ID']?>" style="margin-left: unset;">
                                <?foreach ($element['VLAD_SKU_DISPLAY'] as $key => $V_sku){
                                    $propertyId = $V_sku["ID"];
                                    $firstBuyElBool = false;?>
                                    <div class="product-item-detail-scu-elem">
                                        <div class="product-item-detail-info-container" data-entity="sku-line-block">
                                            <div class="product-item-detail-scu-title"><?=htmlspecialcharsEx($V_sku["NAME"])?></div>
                                        </div>
                                        <div class="product-item-detail-scu-block">
                                            <div class="product-item-detail-scu-list" data-id-item="<?=$element['ID']?>" data-prop="<?=$propertyId?>">
                                                <ul class="product-item-detail-scu-item-list"
                                                    data-entity="sku-line-list"
                                                    data-id-item="<?=$element['ID']?>"
                                                    data-prop="<?=$propertyId?>"
                                                    data-name="<?=htmlspecialcharsEx($V_sku["NAME"])?>"
                                                >
                                                    <?foreach($V_sku["VALUES"] as $keyVal => &$value) {
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
                                    </div>
                                <?}?>
                                <?=$selectedItem['HIDDEN_HTML']?>
                            </div>
                            <div class="quantity-in-section-list basket-item-td basket-item-quantity checking<?=$selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] ? '' : ' hidden_app'?>" data-id="<?=$selectedItem['SELECTED_ITEM']['ID']?>"<?=$selectedItem['SELECTED_ITEM']['CAN_BUY'] ? '' : ' style="display: none"'?>>
                                <div class="basket-item-amount catalog basketQty" data-id="<?=$selectedItem['SELECTED_ITEM']["ID"]?>">
                                    <a class="hidden-print basket-item-amount-btn-minus quan-on-basket minus" href="#" id="amount-m-<?=$selectedItem['SELECTED_ITEM']['ID']?>">
                                        <?=($selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] && (int)$selectedItem['SELECTED_ITEM']['IN_BASKET']['QUANTITY'] === 1) ? '<span class="far fa-trash-alt"></span>' : '-'?>
                                    </a>
                                    <input class="basket-item-amount-input qty-on-basket" id="quantity-cart-catalog-<?=$selectedItem['SELECTED_ITEM']['ID']?>" min="1" type="number" maxlength="18" value="<?=$selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT'] ? $selectedItem['SELECTED_ITEM']['IN_BASKET']['QUANTITY'] : 1?>">
                                    <a class="hidden-print basket-item-amount-btn-plus quan-on-basket plus" href="#" id="amount-p-<?=$selectedItem['SELECTED_ITEM']['ID']?>">+</a>
                                </div>
                            </div>

                            <div class="catalog-buy-button block-button-buy" style="margin-left: unset;">
                                <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){$bueMarket = true;?>
                                    <a data-id="<?=$selectedItem['PARAMETERS']['ID_ITEM']?>" class="btn-ozon m-btn-list open-market-link" href="javascript: void(0)" data-open="<?=$selectedItem['PARAMETERS']['VALUE']?>">Купить на <i class='ex-icon-ozon'></i></a>
                                <?}?>
                                <div data-id="<?=$selectedItem['SELECTED_ITEM']['ID']?>" id="button-add-to-cart-<?=$selectedItem['SELECTED_ITEM']['ID']?>" class="add-to-cart catalog-button<?=$bueMarket ? ' m-btn-list' : ''?>"<?=(!$selectedItem['SELECTED_ITEM']['CAN_BUY'] || $selectedItem['SELECTED_ITEM']['IN_BASKET']['RESULT']) ? ' style="display: none"' : ''?>><span><?=$bueMarket ? 'Купить на сайте' : 'В корзину'?></span></div>
                            </div>

                            <div class="catalog-sub-button block-button-buy"<?=$selectedItem['SELECTED_ITEM']['CAN_BUY'] ? ' style="margin-left: unset; display: none;"' : 'style="margin-left: unset;"'?>>
                                <div <?=$element['IS_SUBSCRIBED'] ? 'data-idsub="' . $element['ID_SUBSCRIBED'] . '"' : ''?> data-item="<?=$element['ID']?>" data-contact="<?=$USER->getEmail() ? $USER->getEmail() : 'false'?>" data-auth="<?=$USER->IsAuthorized() ? $USER->GetId() : 'false'?>" id="button-section-subscribe-<?=$element['ID']?>" class="subscribe-item catalog-button<?=$element['IS_SUBSCRIBED'] ? ' subscribed' : ''?>">
                                    <svg version="1.1" class="subscribe-item-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 521 428.8" xml:space="preserve"><path d="M471.5,0h-422C22.2,0,0,22.2,0,49.5v270C0,346.8,22.2,369,49.5,369l141.5,0l54,54c3.8,3.8,8.9,5.8,13.9,5.8s10.1-1.9,13.9-5.8l54-54l144.5,0c27.3,0,49.5-22.2,49.5-49.5v-270C521,22.2,498.8,0,471.5,0z M482,66.4v236.1l-118.8-118L482,66.4z M157.8,184.6L39,302.6V66.4L157.8,184.6z M185.4,212.1l18.9,18.8c15,15,35,23.3,56.2,23.3s41.2-8.3,56.2-23.2l18.9-18.8L454.2,330h-144L259,381.2L207.8,330h-4.3l0,0H66.7L185.4,212.1z M260.5,215.1c-10.8,0-21-4.2-28.7-11.9L66.7,39h387.6L289.1,203.3C281.5,210.9,271.3,215.1,260.5,215.1z"/></svg>
                                    <span><?=$element['IS_SUBSCRIBED'] ? 'Подписка' : 'Подписаться'?></span>
                                </div>
                            </div>

                            <div class="block-item-info"<?=!empty($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE']) || !getPartner() ? ' style="margin-left: unset;"' : ' style="margin-left: unset; display: none;"'?>>
                                <a href="/page-wholesale/" data-name="wholesale" class="opt-price-app">Оптовая стоимость от <?=round($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'] - ($selectedItem['SELECTED_ITEM']['PRICES']['BASE_PRISE'] * 0.41))?> руб.</a>
                                <a href="/page-delivery/" data-name="delivery" class="delivery-info-app">Доставим бесплатно от 5000 руб.</a>
                            </div>
                            <?$available = false;?>
                        <?}else{?>
                        <div class="product-date item-subtitle">
                            <?if($element["CATALOG_QUANTITY"] > 0){?>
                                <?$canBuy = true;
                                $quantRes = catalogQuantity($element["CATALOG_QUANTITY"]);
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
                                    <span>Нет в наличии</span>
                                </div>
                            <?}?>
                        </div>
                    </div>
                </a>
                <div class="footer-tile">
                    <?if($canBuy){?>
                        <div class="price-item-cart">
                            <?if(!empty($element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']) && $element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] != $element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']){?>
                                <div class="discont-price"><?=$element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']?> руб<span>/шт</span></div>
                                <div class="old-price"><?=$element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']?> руб</div>
                                <div class="discont-benefit">Экономия: <?=$element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'] - $element['OPTI_PRICE']['RESULT_PRICE']['DISCOUNT_PRICE']?> руб</div>
                            <?}else{?>
                                <div class="base-price"><?=$element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE']?> руб<span>/шт</span></div>
                            <?}?>
                        </div>
                        <div class="button-and-quantity<?=(!getPartner() && !getNewPartner() && $marketplace['ozon'] ? ' m-btn-list-nooffer' : '')?>">
                            <div class="quantity-in-section-tile basket-item-td basket-item-quantity checking<?=$element['ON_BASKET'] ? '' : ' hidden_app'?>" data-id="<?=$element['ID']?>">
                                <div class="basket-item-amount catalog basketQty" data-id="<?=$element["ID"]?>">
                                    <a class="hidden-print basket-item-amount-btn-minus<?=$element['ON_BASKET'] ? ' quan-on-basket ' : ' quan '?>minus" href="#" id="amount-m-<?=$element['ID']?>">
                                        <?=($element['ON_BASKET'] && (int)$element['QUANTITY_ON_BASKET'] === 1) ? '<span class="far fa-trash-alt"></span>' : '-'?>
                                    </a>
                                    <input class="basket-item-amount-input<?=$element['ON_BASKET'] ? ' qty-on-basket' : ' qty'?>" id="quantity-cart-catalog-<?=$element["ID"]?>" min="1" type="number" maxlength="18" value="<?=$element['ON_BASKET'] ? $element['QUANTITY_ON_BASKET'] : 1?>">
                                    <a class="hidden-print basket-item-amount-btn-plus<?=$element['ON_BASKET'] ? ' quan-on-basket ' : ' quan '?>plus" href="#" id="amount-p-<?=$element['ID']?>">+</a>
                                </div>
                            </div>
                            <?if(!getPartner() && !getNewPartner() && $marketplace['ozon']){$bueMarket = true;?>
                                <a data-id="<?=$element['ID']?>" class="btn-ozon m-btn-list open-market-link" href="javascript: void(0)" data-open="<?=$element['PROPERTIES']['SSYLKA_NA_OZON']['VALUE']?>">Купить на <i class='ex-icon-ozon'></i></a>
                            <?}?>
                            <div data-id="<?=$element['ID']?>" id="button-add-to-cart-<?=$element['ID']?>" class="<?=$element['ON_BASKET'] ? 'add-to-cart-ok' : 'add-to-cart'?><?=$element['ON_BASKET'] ? ' hidden_app' : ''?> catalog-button<?=$bueMarket ? ' m-btn-list' : ''?>"><span><?=$bueMarket ? 'Купить на сайте' : 'В корзину'?></span></div>
                        </div>
                        <div class="block-item-info">
                            <a href="/page-wholesale/" data-name="wholesale" class="opt-price-app">Оптовая стоимость <?=round($element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'] - ($element['OPTI_PRICE']['RESULT_PRICE']['BASE_PRICE'] * 0.41))?> руб.</a>
                            <a href="/page-delivery/" data-name="delivery" class="delivery-info-app">Доставим бесплатно от 5000 руб.</a>
                        </div>
                    <?}else{?>
                        <a href="#" data-dt="" class="link no-order">
                            <div class="no-order-info">
                                <?if(!empty(ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                    $element['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                    false, false))){?>
                                    <?=ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                        $element['PROPERTIES']['CML2_TRAITS']['VALUE'], false, false)?>
                                <?}else{?>
                                    Нет в наличии
                                <?}?>
                                <div <?=$element['IS_SUBSCRIBED'] ? 'data-idsub="' . $element['ID_SUBSCRIBED'] . '"' : ''?> data-item="<?=$element['ID']?>" data-contact="<?=$USER->getEmail() ? $USER->getEmail() : 'false'?>" data-auth="<?=$USER->IsAuthorized() ? $USER->GetId() : 'false'?>" id="button-section-subscribe-<?=$element['ID']?>" class="subscribe-item catalog-button<?=$element['IS_SUBSCRIBED'] ? ' subscribed' : ''?>">
                                    <svg version="1.1" class="subscribe-item-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 521 428.8" xml:space="preserve"><path d="M471.5,0h-422C22.2,0,0,22.2,0,49.5v270C0,346.8,22.2,369,49.5,369l141.5,0l54,54c3.8,3.8,8.9,5.8,13.9,5.8s10.1-1.9,13.9-5.8l54-54l144.5,0c27.3,0,49.5-22.2,49.5-49.5v-270C521,22.2,498.8,0,471.5,0z M482,66.4v236.1l-118.8-118L482,66.4z M157.8,184.6L39,302.6V66.4L157.8,184.6z M185.4,212.1l18.9,18.8c15,15,35,23.3,56.2,23.3s41.2-8.3,56.2-23.2l18.9-18.8L454.2,330h-144L259,381.2L207.8,330h-4.3l0,0H66.7L185.4,212.1z M260.5,215.1c-10.8,0-21-4.2-28.7-11.9L66.7,39h387.6L289.1,203.3C281.5,210.9,271.3,215.1,260.5,215.1z"/></svg>
                                    <span><?=$element['IS_SUBSCRIBED'] ? 'Подписка' : 'Подписаться'?></span>
                                </div>
                            </div>
                        </a>
                    <?}?>
                </div>
                <?}?>
            </div>
            <?$haveOffers = false;?>
        <?}?>
        <?if(!AJAX_REQUEST){?>
            </div>
            <?if(!$lastInfinite){?>
                <div class="preloader infinite-scroll-preloader"></div>
            <?}?>
        <?}?>
    <?}?>
<?}?>