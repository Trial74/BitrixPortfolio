<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?	$lastInfinite = false;
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
                <a href="#" class="tab-link catalog-sort link sortview">
                    <span class="ex-sort-app"></span>
                    <span style="vertical-align: middle; line-height: 15px; color: black;">Сортировка<span>
                </a>
                <a href="#" class="tab-link catalog-view link sortview">
                    <span class="ex-view-app"></span>
                    <span style="vertical-align: middle; line-height: 15px; color: black;">Отображение<span>
                </a>
            </div>
        </div>

    <?}?>
    <?if($listType == 'list'){?>
        <div class="infinite-items list-type-list">
        <?foreach($arResult["ITEMS"] as $element){?>
        <div class="basket-item card product-card infinite-item" data-id="<?=$element["ID"]?>" data-idbasket="<?=$element['ID_ON_BASKET']?>">
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
            <div class="product-avatar">
                <?$canBuy = false;
                $arImageFilter = [
                    ["name" => "watermark", "position" => "center", "fill"=>"repeat", "size"=>"big", "file" => $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/dresscode/images/watermark.png"]
                ];
                $element["IMAGE"] = CFile::ResizeImageGet($element["DETAIL_PICTURE"], ["width" => 150, "height" => 150], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
                if(empty($element["IMAGE"])){
                    $element["IMAGE"]["src"] = NOIMAGE_PATH;
                }
                ?>
                <img src="<?=NOIMAGE_PATH?>" style="max-width: 150px; max-height: 150px;" data-src="<?=$element["IMAGE"]["src"]?>" class="lazy" />
            </div>
            <div class="product-content">
                <div class="product-name">
                    <?=$element['NAME']?>
                </div>

                <?if($element['PROPERTIES']['MINI_OPISANIE_DLYA_APP']['VALUE'] && $USER->GetID() == 10354){?>
                    <div class="mini-description">
                        <?=$element['PROPERTIES']['MINI_OPISANIE_DLYA_APP']['VALUE']?>
                    </div>
                <?}?>

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
                <div class="card-footer">
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
                        <div class="quantity-in-section-list basket-item-td basket-item-quantity checking<?=$element['ON_BASKET'] ? '' : ' hidden_app'?>" data-id="<?=$element['ID']?>">
                            <div class="basket-item-amount catalog basketQty" data-id="<?=$element["ID"]?>">
                                <a class="hidden-print basket-item-amount-btn-minus<?=$element['ON_BASKET'] ? ' quan-on-basket ' : ' quan '?>minus" href="#" id="amount-m-<?=$element['ID']?>">-</a>
                                <input class="basket-item-amount-input<?=$element['ON_BASKET'] ? ' qty-on-basket' : ' qty'?>" id="quantity-cart-catalog-<?=$element["ID"]?>" min="1" type="number" maxlength="18" value="<?=$element['ON_BASKET'] ? $element['QUANTITY_ON_BASKET'] : 1?>">
                                <a class="hidden-print basket-item-amount-btn-plus<?=$element['ON_BASKET'] ? ' quan-on-basket ' : ' quan '?>plus" href="#" id="amount-p-<?=$element['ID']?>">+</a>
                            </div>
                        </div>
                        <div data-id="<?=$element['ID']?>" id="button-add-to-cart-<?=$element['ID']?>" class="<?=$element['ON_BASKET'] ? 'add-to-cart-ok' : 'add-to-cart'?><?=$element['ON_BASKET'] ? ' hidden_app' : ''?> catalog-button"><span>В корзину</span></div>
                    <?}else{?>
                        <a href="#" data-dt="" class="link disabled">
                            <?if(!empty(ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                $element['PROPERTIES']['CML2_TRAITS']['VALUE'],
                                false, false))){?>
                                <?=ozhidaemayaData($element['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'],
                                    $element['PROPERTIES']['CML2_TRAITS']['VALUE'], false, false)?>
                            <?}else{?>
                                Нет в наличии
                            <?}?>
                        </a>
                        <div <?=$element['IS_SUBSCRIBED'] ? 'data-idsub="' . $element['ID_SUBSCRIBED'] . '"' : ''?> data-item="<?=$element['ID']?>" data-contact="<?=$USER->getEmail() ? $USER->getEmail() : 'false'?>" data-auth="<?=$USER->IsAuthorized() ? $USER->GetId() : 'false'?>" id="button-section-subscribe-<?=$element['ID']?>" class="subscribe-item catalog-button<?=$element['IS_SUBSCRIBED'] ? ' subscribed' : ''?>">
                            <svg version="1.1" class="subscribe-item-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 521 428.8" xml:space="preserve"><path d="M471.5,0h-422C22.2,0,0,22.2,0,49.5v270C0,346.8,22.2,369,49.5,369l141.5,0l54,54c3.8,3.8,8.9,5.8,13.9,5.8s10.1-1.9,13.9-5.8l54-54l144.5,0c27.3,0,49.5-22.2,49.5-49.5v-270C521,22.2,498.8,0,471.5,0z M482,66.4v236.1l-118.8-118L482,66.4z M157.8,184.6L39,302.6V66.4L157.8,184.6z M185.4,212.1l18.9,18.8c15,15,35,23.3,56.2,23.3s41.2-8.3,56.2-23.2l18.9-18.8L454.2,330h-144L259,381.2L207.8,330h-4.3l0,0H66.7L185.4,212.1z M260.5,215.1c-10.8,0-21-4.2-28.7-11.9L66.7,39h387.6L289.1,203.3C281.5,210.9,271.3,215.1,260.5,215.1z"/></svg>
                        </div>
                    <?}?>
                </div>
            </div>
        <?}?>
        <?if(!AJAX_REQUEST){?>
            </div>
            <?if(!$lastInfinite){?>
                <div class="preloader infinite-scroll-preloader"></div>
            <?}?>
        <?}?>
    <?}elseif($listType == 'tile'){?>
        <div class="infinite-items flex-container-items tile">
        <?foreach($arResult["ITEMS"] as $element){?>
            <div class="flex-item-catalog infinite-item basket-item" data-id="<?=$element['ID']?>">
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
                        $arImageFilter = [
                            ["name" => "watermark", "position" => "center", "fill"=>"repeat", "size"=>"big", "file" => $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/dresscode/images/watermark.png"]
                        ];
                        $element["IMAGE"] = CFile::ResizeImageGet($element["DETAIL_PICTURE"], ["width" => 150, "height" => 150], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
                        if(empty($element["IMAGE"])){
                            $element["IMAGE"]["src"] = NOIMAGE_PATH;
                        }
                        ?>
                        <img src="<?=NOIMAGE_PATH?>" style="max-width: 150px; max-height: 150px;" data-src="<?=$element["IMAGE"]["src"]?>" class="lazy" />
                    </div>
                    <div class="product-content">
                        <div class="product-name">
                            <?=$element['NAME']?>
                        </div>
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
                        <div class="button-and-quantity">
                            <div class="quantity-in-section-tile basket-item-td basket-item-quantity checking" data-id="<?=$element['ID']?>">
                                <div class="basket-item-amount catalog basketQty" data-id="<?=$element["ID"]?>">
                                    <a class="hidden-print basket-item-amount-btn-minus quan minus" href="#">-</a>
                                    <input class="basket-item-amount-input qty" id="quantity-cart-catalog-<?=$element["ID"]?>" min="1" type="number" maxlength="18" value="1">
                                    <a class="hidden-print basket-item-amount-btn-plus quan plus" href="#">+</a>
                                </div>
                            </div>
                            <div data-id="<?=$element['ID']?>" id="button-add-to-cart-<?=$element['ID']?>" class="add-to-cart catalog-button"></div>
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
                                </div>
                            </div>
                        </a>
                    <?}?>
                </div>
            </div>
        <?}?>
        <?if(!AJAX_REQUEST){?>
            </div>
            <?if(!$lastInfinite){?>
                <div class="preloader infinite-scroll-preloader"></div>
            <?}?>
        <?}?>
    <?}?>
<?}?>