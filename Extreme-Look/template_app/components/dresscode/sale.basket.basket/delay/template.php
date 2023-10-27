<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$this->setFrameMode(true);?>
<?$offer = false;?>

<div class="block empty-delay verticalize" style="display:none;">
	<span class="fas fa-shopping-cart-extreme"></span>
	<span class="text-shopping-cart-extreme">Нет отложенных товаров</span>
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
			<?foreach ($arResult["ITEMS"] as $key => $arElement) {prent_r($arElement);
                if($arElement['DELAY'] == 'N') continue;

                if(!empty($arElement["INFO"]["DETAIL_PICTURE"])){
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
                                            <div class="item-cell">
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
                                                <?if($arElement['CAN_BUY'] == 'Y'){?>
                                                    <div data-id="<?=$arElement['PRODUCT_ID']?>" id="button-add-to-cart-<?=$arElement['PRODUCT_ID']?>" class="catalog-button add-to-cart-delay" style="font-size:14px;">
                                                        <span>В корзину</span>
                                                    </div>
                                                <?}else{?>
                                                    <span>Нет в наличии</span>
                                                <?}?>
                                            </div>
                                        <?}?>
                                    </div>
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
						<a href="#" data-confirm="Удалить из отложенного" class="color-extreme-look-del cart-item-delete swipeout-overswipe">
							<span class="far fa-trash-alt"></span>
						</a>
					<?}?>
				</div>
			</li>
			<?}?>
		</ul>
<?}?>
