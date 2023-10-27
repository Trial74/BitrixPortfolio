<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$this->setFrameMode(true);?>
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
<?if(!empty($arResult["ITEMS"])){?>
	<?$OPTION_CURRENCY = CCurrency::GetBaseCurrency();?>
	<div class="list media-list cart-list" style="margin:60px 0;">
		<ul>
			<?foreach ($arResult["ITEMS"] as $key => $arElement) {
			    
                $mxResult = CCatalogSku::GetProductInfo($arElement['PRODUCT_ID']);
                if(is_array($mxResult)){
                    $offer = true;
                }else $offer = false;

                if(!empty($arElement["INFO"]["DETAIL_PICTURE"])){
                    $arElement["IMAGE"] = CFile::ResizeImageGet(CFile::GetFileArray($arElement["INFO"]["DETAIL_PICTURE"]), ["width" => 100, "height" => 100], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
                }else{//Если нет картинки проверяем не является ли это торговым предложением
                    if($offer){
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
			<li class="swipeout cart-item product-card basket-item" data-id="<?=$arElement["ID"]?>"<?=$arElement['DELAY'] == 'Y' ? ' style="display:none"' : ''?>>
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
						<a href="/page-catalog.element/element-id=<?=$offer ? $mxResult['ID'] : $arElement["PRODUCT_ID"]?>/" class="color-extreme-look">Подробнее</a>
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
                        <div class="gift-block-tittle">
                            <div class="gift-block-tittle-name">Выберите подарок</div>
                            <div class="gift-block-tittle-open-icon" id="g-open"></div>
                        </div>
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
