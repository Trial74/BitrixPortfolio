<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?$offer = false;?>
<div class="block empty-cart verticalize" style="display:none;">
	<span class="fas fa-shopping-cart-extreme"></span>
	<span class="text-shopping-cart-extreme">Корзина пуста</span>
</div>

<?$totalPrice = 0;
$arImageFilter = [
    [
        "name" => "watermark",
        "position" => "center",
        "fill"=>"repeat",
        "size"=>"big",
        "file" => WATERMARK_PATH
    ]
];?>
<?if(empty($arResult["ITEMS"])){?>
	<style>
		.empty-cart {display:block !important;}
	</style>
<?}else{?>
	<?$OPTION_CURRENCY = CCurrency::GetBaseCurrency();?>
	<div class="list media-list cart-list" style="margin:60px 0;">
		<ul>
			<?foreach ($arResult["ITEMS"] as $key => $arElement) {?>
                <?if(!empty($arElement["INFO"]["DETAIL_PICTURE"])){
                    $arElement["IMAGE"] = CFile::ResizeImageGet(CFile::GetFileArray($arElement["INFO"]["DETAIL_PICTURE"]), ["width" => 100, "height" => 100], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
                }else{//Если нет картинки проверяем не является ли это торговым предложением
                    $mxResult = CCatalogSku::GetProductInfo($arElement['PRODUCT_ID']);
                    if (is_array($mxResult)){
                        $offer = true;
                        $resProducts = Bitrix\Iblock\ElementTable::getList([
                            'select' => ["PREVIEW_PICTURE"],
                            'filter' => [
                                "IBLOCK_ID" => 23,
                                'ID' => $mxResult['ID'],
                                "ACTIVE" => "Y",
                            ],
                        ]);
                        $imgSku = $resProducts->fetch();
                        $arElement["IMAGE"] = CFile::ResizeImageGet(CFile::GetFileArray($imgSku["PREVIEW_PICTURE"]), ["width" => 100, "height" => 100], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
                    }
                }?>
			<li class="swipeout cart-item product-card basket-item" data-id="<?=$arElement["ID"]?>">
				<div class="swipeout-content item-content">
                    <div class="item-inner item-cell" style="padding-top:0; padding-bottom:0;">
                        <div class="item-row">
                            <div class="item-cell" style="width:45%;">
                                    <?if(empty($arElement["IMAGE"])){
                                        $arElement["IMAGE"]["src"] = NOIMAGE_SMALL_PATH;
                                    }?>
                                    <img src="<?=NOIMAGE_SMALL_PATH?>" data-src="<?=$arElement["IMAGE"]['src']?>" alt="<?=htmlspecialcharsEx($arElement["NAME"])?>" class="lazy lazy-fade-in">
                            </div>
                            <div class="item-cell">
                                <div class="item-inner">
                                    <div class="item-title">
                                        <?=$arElement["INFO"]["NAME"]?>
                                    </div>
                                    <div class="item-subtitle">
                                        <div class="item-row">
                                            <div class="item-cell" id="ex-new-price">
                                                <span>Стоимость:</span>
                                                <span><?=FormatCurrency($arElement["PRICE"], $OPTION_CURRENCY)?></span>
                                            </div>
                                        </div>
                                        <div class="item-row">
                                            <div class="item-cell" id="ex-old-price" <?=$arElement["INFO"]["OLD_PRICE"] == $arElement["PRICE"] ? 'style="height: 0;"' : ''?>>
                                                <span><?=($arElement["INFO"]["OLD_PRICE"] != $arElement["PRICE"] ? FormatCurrency($arElement["INFO"]["OLD_PRICE"], $OPTION_CURRENCY) : '')?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-subtitle" style="padding-bottom: 15px;">
                                        <?if($arElement["INFO"]["CATALOG_QUANTITY"] > 0){?>
                                            <?$canBuy = true;
                                            $quantRes = catalogQuantity($arElement["INFO"]["CATALOG_QUANTITY"]);
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
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-inner item-cell">
                                <div class="item-row">
                                    <div class="item-cell" style="width: 214px;">
                                        <?if($arElement['PRODUCT_ID'] != SERVICE_FEE_ID){?>
                                            <div class="basket-item-td basket-item-quantity">
                                                <div class="basket-item-amount cart basketQty" style="position: static;" data-id="<?=$arElement['ID']?>">
                                                    <a class="hidden-print basket-item-amount-btn-minus quanbas<?=intVal($arElement["QUANTITY"]) <= 1 ? ' cart-item-delete' : ' minus'?>" href="#"><?=intVal($arElement["QUANTITY"]) <= 1 ? '<span class="far fa-trash-alt"></span>' : '-'?></a>
                                                    <input class="basket-item-amount-input qtybas" min="1" type="text" maxlength="18" value="<?=intVal($arElement["QUANTITY"])?>">
                                                    <a class="hidden-print basket-item-amount-btn-plus quanbas plus" href="#">+</a>
                                                </div>
                                            </div>
                                        <?}?>
                                    </div>
                                    <div class="item-cell" style="width: 60px; margin-left: 0;">шт.</div>
                                    <div class="item-cell" id="quant-prise-ex">
                                        <div class="preloader" style="width: 20px; height: 20px"></div>
                                    </div>
                                </div>
                                <div class="item-row">
                                    <div class="item-cell" id="econom"></div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="swipeout-actions-right">
					<?if($arElement['PRODUCT_ID'] != SERVICE_FEE_ID){?>
						<a href="/page-catalog.element/element-id=<?=$arElement["PRODUCT_ID"]?>/" class="color-extreme-look">Подробнее</a>
						<a href="#" data-confirm="Подтвердите удаление" class="color-extreme-look-del cart-item-delete swipeout-overswipe">
							<span class="far fa-trash-alt"></span>
						</a>
					<?}?>
				</div>
			</li>
			<?}?>
		</ul>

    <div class="toolbar-bottom-md order-toolbar basket-order-toolbar">
  		<div class="basket-footer-inner">
            <div class="item-inner item-cell" style="padding-left: 16px;">
                <div class="item-row">
                    <div class="item-cell">
                        <div class="block-price-basket-app-flex">
                            <div class="block-price-basket-app">
                                <div id="ca-basket" class="cash-old-price-backet hidden_app"></div>
                                <div id="tp-basket" class="total-price-backet">Итого:<span><div class="preloader" style="width:20px;height:20px"></div></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item-inner item-cell" id="ex-total-econom-block" style="padding-left: 16px; display: none;">
                    <div class="item-row">
                        <div class="item-cell">
                            <div id="tbp-basket" class="total-base-price-backet"><span></span></div>
                        </div>
                    </div>
                    <div class="item-row">
                        <div class="item-cell">
                            <div id="tbep-basket" class="total-econom-base-price-backet">Экономия:<span></span></div>
                        </div>
                    </div>
                </div>
                <?if(getUserCashBack()){?>
                    <div class="bx_ordercart_order_cash_left" id="cash_block">
                        <div class="cash-name">Баллы и бонусы <i class="question-ex-app" data-tooltip="<?=GetMessage("SALE_BONUS_TOOLTIP")?>"></i></div>
                        <div class="cash-block" id="cash-block-app">
                            <div class="write-off active" id="CASH_WRITE_OFF"></div>
                            <div class="enroll" id="ENROLL"></div>
                        </div>
                    </div>
                <?}?>
                <?if($USER->GetID() == 10354){?>
                    <div class="basket-gift-block" id="gift-block" style="display:none;">
                        <div class="gift-block-tittle">Выберите подарок</div>
                        <div class="gift-block-items" id="gift-items"></div>
                    </div>
                <?}?>
                <div class="item-row" style="padding-top: 15px;">
                    <div class="item-title couponlabel">Введите промокод или номер скидочной карты</div>
                </div>
                <div class="item-row">
                    <div class="item-cell">
                        <div class="item-content item-input item-input-outline">
                            <div class="item-inner" style="padding-top: 0; padding-bottom: 0;">
                                <input type="text" id="name_coupon" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="item-cell" style="width: 15%;height: 48px;">
                        <a class="btn btn-buy" id="add_coupon" style="width: 33px;" href="#" title="Нажмите для применения нового купона" role="button">
                            <i class="fas fa-check"></i>
                        </a>
                    </div>
                </div>
                <div class="item-inner item-cell" id="ex-coupons-block" style="margin-left: 0;padding-top: 0;">
                    <div class="item-row">
                        <div class="item-title couponlabel" id="ex_couplabel" style="display: none;">Применённые купоны/карты:</div>
                    </div>
                    <div class="item-inner item-row coupon-list" id="coupon-list-basket" style="margin-left: 16px;"></div>
                </div>
                <div class="item-row">
                    <div class="item-cell">
                        <a href="/?<?=MOBILE_GET?>=Y&page=personal/order<?=getUserCashBack() ? '&cash=N' : ''?>" data-href="/?<?=MOBILE_GET?>=Y&page=personal/order" style="margin-top: 15px;" id="btn-order-backet" class="tab-link button button-fill external-link disabled order-link link external">
                            <span>
                                <div class="preloader color-white" style="width: 20px; height: 20px"></div>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
  		</div>
    </div>
<?}?>
<?//prent_r($arParams);?>
<?CBitrixComponent::includeComponentClass("bitrix:sale.products.gift.basket");?>
<?$APPLICATION->IncludeComponent("bitrix:sale.products.gift.basket", "",
    array(
        "IBLOCK_TYPE" => $arParams["CATALOG_IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"],
        "ELEMENT_SORT_FIELD" => $arParams["CATALOG_ELEMENT_SORT_FIELD"],
        "ELEMENT_SORT_ORDER" => $arParams["CATALOG_ELEMENT_SORT_ORDER"],
        "ELEMENT_SORT_FIELD2" => $arParams["CATALOG_ELEMENT_SORT_FIELD2"],
        "ELEMENT_SORT_ORDER2" => $arParams["CATALOG_ELEMENT_SORT_ORDER2"],
        "PROPERTY_CODE" => $arParams["CATALOG_PROPERTY_CODE"],
        "INCLUDE_SUBSECTIONS" => $arParams["CATALOG_INCLUDE_SUBSECTIONS"],
        "BASKET_URL" => $arParams["CATALOG_BASKET_URL"],
        "ACTION_VARIABLE" => $arParams["CATALOG_ACTION_VARIABLE"],
        "PRODUCT_ID_VARIABLE" => $arParams["CATALOG_PRODUCT_ID_VARIABLE"],
        "SECTION_ID_VARIABLE" => $arParams["CATALOG_SECTION_ID_VARIABLE"],
        "PRODUCT_QUANTITY_VARIABLE" => $arParams["CATALOG_PRODUCT_QUANTITY_VARIABLE"],
        "PRODUCT_PROPS_VARIABLE" => $arParams["CATALOG_PRODUCT_PROPS_VARIABLE"],
        "CACHE_TYPE" => "N",//$arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_FILTER" => $arParams["CACHE_FILTER"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "DISPLAY_COMPARE" => $arParams["CATALOG_DISPLAY_COMPARE"] ? "Y" : "N",
        "PAGE_ELEMENT_COUNT" => 0,
        "DEFERRED_PAGE_ELEMENT_COUNT" => $arParams["GIFTS_PAGE_ELEMENT_COUNT"],
        "PRICE_CODE" => $arParams["CATALOG_PRICE_CODE"],
        "USE_PRICE_COUNT" => $arParams["CATALOG_USE_PRICE_COUNT"] ? "Y" : "N",
        "SHOW_PRICE_COUNT" => $arParams["CATALOG_SHOW_PRICE_COUNT"] ? "Y" : "N",
        "PRICE_VAT_INCLUDE" => $arParams["CATALOG_PRICE_VAT_INCLUDE"] ? "Y" : "N",
        "USE_PRODUCT_QUANTITY" => $arParams["CATALOG_USE_PRODUCT_QUANTITY"] ? "Y" : "N",
        "ADD_PROPERTIES_TO_BASKET" => $arParams["CATALOG_ADD_PROPERTIES_TO_BASKET"],
        "PARTIAL_PRODUCT_PROPERTIES" => $arParams["CATALOG_PARTIAL_PRODUCT_PROPERTIES"],
        "PRODUCT_PROPERTIES" => $arParams["CATALOG_PRODUCT_PROPERTIES"],
        "OFFERS_CART_PROPERTIES" => $arParams["CATALOG_OFFERS_CART_PROPERTIES"],
        "OFFERS_FIELD_CODE" => $arParams["CATALOG_OFFERS_FIELD_CODE"],
        "OFFERS_PROPERTY_CODE" => $arParams["CATALOG_OFFERS_PROPERTY_CODE"],
        "OFFERS_SORT_FIELD" => $arParams["CATALOG_OFFERS_SORT_FIELD"],
        "OFFERS_SORT_ORDER" => $arParams["CATALOG_OFFERS_SORT_ORDER"],
        "OFFERS_SORT_FIELD2" => $arParams["CATALOG_OFFERS_SORT_FIELD2"],
        "OFFERS_SORT_ORDER2" => $arParams["CATALOG_OFFERS_SORT_ORDER2"],
        "OFFERS_LIMIT" => $arParams["CATALOG_OFFERS_LIMIT"],
        "SECTION_ID" => "",
        "SECTION_CODE" => "",
        "SECTION_URL" => "",
        "DETAIL_URL" => "",
        "USE_MAIN_ELEMENT_SECTION" => $arParams["CATALOG_USE_MAIN_ELEMENT_SECTION"] ? "Y" : "N",
        "CONVERT_CURRENCY" => $arParams["CATALOG_CONVERT_CURRENCY"],
        "CURRENCY_ID" => $arParams["CATALOG_CURRENCY_ID"],
        "HIDE_NOT_AVAILABLE" => $arParams["CATALOG_HIDE_NOT_AVAILABLE"],
        "HIDE_NOT_AVAILABLE_OFFERS" => $arParams["CATALOG_HIDE_NOT_AVAILABLE_OFFERS"],
        "TEXT_LABEL_GIFT" => $arParams["GIFTS_TEXT_LABEL_GIFT"],
        "PRODUCT_DISPLAY_MODE" => $arParams["CATALOG_PRODUCT_DISPLAY_MODE"],
        "PRODUCT_ROW_VARIANTS" => "",
        "DEFERRED_PRODUCT_ROW_VARIANTS" => Bitrix\Main\Web\Json::encode(SaleProductsGiftBasketComponent::predictRowVariants(4, $arParams["GIFTS_PAGE_ELEMENT_COUNT"])),
        "OFFER_TREE_PROPS" => $arParams["CATALOG_OFFER_TREE_PROPS"],
        "PRODUCT_SUBSCRIPTION" => $arParams["CATALOG_PRODUCT_SUBSCRIPTION"],
        "SHOW_DISCOUNT_PERCENT" => $arParams["CATALOG_SHOW_DISCOUNT_PERCENT"],
        "SHOW_OLD_PRICE" => $arParams["CATALOG_SHOW_OLD_PRICE"],
        "SHOW_MAX_QUANTITY" => $arParams["CATALOG_SHOW_MAX_QUANTITY"],
        "MESS_SHOW_MAX_QUANTITY" => $arParams["~CATALOG_MESS_SHOW_MAX_QUANTITY"],
        "RELATIVE_QUANTITY_FACTOR" => $arParams["CATALOG_RELATIVE_QUANTITY_FACTOR"],
        "MESS_RELATIVE_QUANTITY_MANY" => $arParams["~CATALOG_MESS_RELATIVE_QUANTITY_MANY"],
        "MESS_RELATIVE_QUANTITY_FEW" => $arParams["~CATALOG_MESS_RELATIVE_QUANTITY_FEW"],
        "MESS_BTN_BUY" => $arParams["~CATALOG_MESS_BTN_BUY"],
        "MESS_BTN_ADD_TO_BASKET" => $arParams["~CATALOG_MESS_BTN_ADD_TO_BASKET"],
        "GIFTS_MESS_BTN_BUY" => $arParams["~GIFTS_MESS_BTN_BUY"],
        "GIFTS_MESS_BTN_ADD_TO_BASKET" => $arParams["~GIFTS_MESS_BTN_BUY"],
        "MESS_BTN_SUBSCRIBE" => $arParams["~CATALOG_MESS_BTN_SUBSCRIBE"],
        "MESS_BTN_DETAIL" => $arParams["~CATALOG_MESS_BTN_DETAIL"],
        "MESS_NOT_AVAILABLE" => $arParams["~CATALOG_MESS_NOT_AVAILABLE"],
        "MESS_BTN_COMPARE" => $arParams["~CATALOG_MESS_BTN_COMPARE"],
        "APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
        "FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],
        "USE_ENHANCED_ECOMMERCE" => $arParams["USE_ENHANCED_ECOMMERCE"],
        "DATA_LAYER_NAME" => $arParams["DATA_LAYER_NAME"],
        "BRAND_PROPERTY" => $arParams["BRAND_PROPERTY"],
        "ADD_TO_BASKET_ACTION" => $arParams["CATALOG_ADD_TO_BASKET_ACTION"],
        "COMPARE_PATH" => $arParams["CATALOG_COMPARE_PATH"],
        "COMPARE_NAME" => $arParams["CATALOG_COMPARE_NAME"],
        "DETAIL_ADD_PICT_PROP" => $arParams["CATALOG_DETAIL_ADD_PICT_PROP"],
        "DETAIL_OFFER_ADD_PICT_PROP" => $arParams["CATALOG_DETAIL_OFFER_ADD_PICT_PROP"],
        "DETAIL_PROPERTY_CODE" => $arParams["CATALOG_DETAIL_PROPERTY_CODE"],
        "DETAIL_OFFERS_FIELD_CODE" => $arParams["CATALOG_DETAIL_OFFERS_FIELD_CODE"],
        "DETAIL_MAIN_BLOCK_PROPERTY_CODE" => $arParams["CATALOG_DETAIL_MAIN_BLOCK_PROPERTY_CODE"],
        "DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => $arParams["CATALOG_DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE"],
        "DETAIL_IMAGE_RESOLUTION" => $arParams["CATALOG_DETAIL_IMAGE_RESOLUTION"],
        "DETAIL_ADD_DETAIL_TO_SLIDER" => $arParams["CATALOG_DETAIL_ADD_DETAIL_TO_SLIDER"],
        "DETAIL_DETAIL_PICTURE_MODE" => $arParams["CATALOG_DETAIL_DETAIL_PICTURE_MODE"],
        "DETAIL_SHOW_SLIDER" => $arParams["CATALOG_DETAIL_SHOW_SLIDER"],
        "DETAIL_SLIDER_INTERVAL" => $arParams["CATALOG_DETAIL_SLIDER_INTERVAL"],
        "DETAIL_SLIDER_PROGRESS" => $arParams["CATALOG_DETAIL_SLIDER_PROGRESS"],

        "USE_REVIEW" => $arParams["CATALOG_USE_REVIEW"],
        "REVIEWS_IBLOCK_TYPE" => $arParams["CATALOG_REVIEWS_IBLOCK_TYPE"],
        "REVIEWS_IBLOCK_ID" => $arParams["CATALOG_REVIEWS_IBLOCK_ID"],
        "REVIEWS_NEWS_COUNT" => $arParams["CATALOG_REVIEWS_NEWS_COUNT"],
        "REVIEWS_SORT_BY1" => $arParams["CATALOG_REVIEWS_SORT_BY1"],
        "REVIEWS_SORT_ORDER1" => $arParams["CATALOG_REVIEWS_SORT_ORDER1"],
        "REVIEWS_SORT_BY2" => $arParams["CATALOG_REVIEWS_SORT_BY2"],
        "REVIEWS_SORT_ORDER2" => $arParams["CATALOG_REVIEWS_SORT_ORDER2"],
        "REVIEWS_ACTIVE_DATE_FORMAT" => $arParams["CATALOG_REVIEWS_ACTIVE_DATE_FORMAT"],
        "REVIEWS_PROPERTY_CODE" => $arParams["CATALOG_REVIEWS_PROPERTY_CODE"],
        "MESS_REVIEWS_TAB" => $arParams["CATALOG_MESS_REVIEWS_TAB"],

        "USE_GIFTS_DETAIL" => $arParams["CATALOG_USE_GIFTS_DETAIL"],
        "GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => $arParams["CATALOG_GIFTS_DETAIL_PAGE_ELEMENT_COUNT"],
        "GIFTS_DETAIL_HIDE_BLOCK_TITLE" => $arParams["CATALOG_GIFTS_DETAIL_HIDE_BLOCK_TITLE"],
        "GIFTS_DETAIL_BLOCK_TITLE" => $arParams["CATALOG_GIFTS_DETAIL_BLOCK_TITLE"],
        "GIFTS_DETAIL_TEXT_LABEL_GIFT" => $arParams["CATALOG_GIFTS_DETAIL_TEXT_LABEL_GIFT"],
        "GIFTS_MESS_BTN_BUY" => $arParams["CATALOG_GIFTS_MESS_BTN_BUY"],

        "USE_STORE" => $arParams["CATALOG_USE_STORE"],
        "STORE_PATH" => $arParams["CATALOG_STORE_PATH"],
        "STORES" => $arParams["CATALOG_STORES"],
        "USE_MIN_AMOUNT" => $arParams["CATALOG_USE_MIN_AMOUNT"],
        "USER_FIELDS" => $arParams["CATALOG_USER_FIELDS"],
        "FIELDS" => $arParams["CATALOG_FIELDS"],
        "MIN_AMOUNT" => $arParams["CATALOG_MIN_AMOUNT"],
        "SHOW_EMPTY_STORE" => $arParams["CATALOG_SHOW_EMPTY_STORE"],
        "SHOW_GENERAL_STORE_INFORMATION" => $arParams["CATALOG_SHOW_GENERAL_STORE_INFORMATION"],
        "MAIN_TITLE" => $arParams["CATALOG_MAIN_TITLE"],

        "SET_ITEMS_COUNT" => $arParams["CATALOG_SET_ITEMS_COUNT"],

        "OBJECTS_USE_REVIEW" => $arParams["OBJECTS_USE_REVIEW"],
        "OBJECTS_REVIEWS_IBLOCK_ID" => $arParams["OBJECTS_REVIEWS_IBLOCK_ID"],
        "CONTACTS_IBLOCK_ID" => $arParams["CONTACTS_IBLOCK_ID"],
        "CONTACTS_USE_REVIEW" => $arParams["CONTACTS_USE_REVIEW"],
        "CONTACTS_REVIEWS_IBLOCK_ID" => $arParams["CONTACTS_REVIEWS_IBLOCK_ID"]
    ),
    $component,
    array("HIDE_ICONS" => "Y")
);?>
