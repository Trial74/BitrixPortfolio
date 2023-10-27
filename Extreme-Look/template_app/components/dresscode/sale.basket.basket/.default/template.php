<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<div class="block empty-cart verticalize" style="display:none;">
	<span class="fas fa-shopping-cart-extreme"></span>
    <span class="text-shopping-cart-extreme">Корзина пуста</span>
</div>

<? $totalPrice = 0; ?>

<? if( empty($arResult["ITEMS"]) ): ?>
	<style>
		.empty-cart { display: block !important; }
	</style>
<? else: ?>
	<?$OPTION_CURRENCY = CCurrency::GetBaseCurrency();?>
	<div class="list media-list cart-list">
		<ul>
			<?foreach ($arResult["ITEMS"] as $key => $arElement):?>
			<li class="swipeout cart-item basket-item" data-id="<?=$arElement["ID"]?>">
				<div class="swipeout-content item-content">
					<div class="item-media">
						<?
							$arImageFilter = [
								["name" => "watermark", "position" => "center", "fill"=>"repeat", "size"=>"big", "file" => WATERMARK_PATH]
							];

							$arElement["IMAGE"] = CFile::ResizeImageGet(CFile::GetFileArray($arElement["INFO"]["DETAIL_PICTURE"]), ["width" => 100, "height" => 100], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
							if(empty($arElement["IMAGE"])){
								$arElement["IMAGE"]["src"] = NOIMAGE_PATH;
							}
						?>
						<img src="<?=NOIMAGE_PATH?>" data-src="<?=$arElement["IMAGE"]['src']?>" alt="<?=$arElement["NAME"]?>" class="lazy lazy-fade-in">
					</div>
					<div class="item-inner">
						<div class="item-title">
							<?=$arElement["INFO"]["NAME"]?>
						</div>
						<div class="item-subtitle">
							Стоимость:
							<span class="price">
								<?=($arElement["INFO"]["OLD_PRICE"] != $arElement["PRICE"] ? '<s>' . FormatCurrency($arElement["INFO"]["OLD_PRICE"], $OPTION_CURRENCY) . '</s>' : '')?>
								<?=FormatCurrency($arElement["PRICE"], $OPTION_CURRENCY);?>
							</span>
						</div>
						<div class="item-subtitle" style="padding-bottom: 15px;">
							<?if($arElement["INFO"]["CATALOG_QUANTITY"] > 0):?>
								<?
								$totalPrice += $arElement['PRICE'];
								?>
								<div class="inStock label">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/inStock.png" alt="" class="icon">
									<span>В наличии</span>
								</div>
							<?else:?>
								<?if($arElement["INFO"]["CAN_BUY"] == true):?>
									<div class="onOrder label">
										<img src="<?=SITE_TEMPLATE_PATH?>/images/onOrder.png" alt="" class="icon">
										<span>Ожидается</span>
									</div>
								<?else:?>
									<div class="outOfStock label" style="color: #7f7f7f;">
										<img src="<?=SITE_TEMPLATE_PATH?>/images/outOfStock.png" alt="" class="icon">
										<span>Недоступно</span>
									</div>
								<?endif;?>
							<?endif;?>
						</div>
						<?if($arElement['PRODUCT_ID'] != SERVICE_FEE_ID){?>
							<div class="cart basketQty" data-id="<?=$arElement["ID"]?>">
								<a href="#" class="link minus<?=intVal($arElement["QUANTITY"]) <= 1 ? ' disabled' : ''?>">
									<span class="fa fa-minus"></span>
								</a>
								<input class="qty" value="<?=intVal($arElement["QUANTITY"])?>" min="0" type="number"/>
								<a href="#" class="link plus">
									<span class="fa fa-plus"></span>
								</a>
							</div>
						<?}?>
					</div>
				</div>
				<div class="swipeout-actions-right">
					<?if($arElement['PRODUCT_ID'] != SERVICE_FEE_ID){?>
						<a href="/page-catalog.element/element-id=<?=$arElement["PRODUCT_ID"]?>/" class="color-orange">
							Подробнее
						</a>
						<a href="#" data-confirm="Подтвердите удаление" class="color-red cart-item-delete swipeout-overswipe">
							<span class="far fa-trash-alt"></span>
						</a>
					<?}?>
				</div>
			</li>
			<?endforeach;?>
		</ul>
		<div class="block">
			<a href="#" class="link button button-fill clear-cart">
				Очистить корзину
			</a>
		</div>
	</div>
	<? /*
	<div class="coupon-fab fab fab-right-bottom" style="bottom: 75px; position: fixed;">
		<a href="#" class="set-coupon link">
			<span style="font-size: 20px;" class="fa fa-percent"></span>
		</a>
	</div>
	*/ ?>
<? endif; ?>




<? if( !empty($arResult["ITEMS"]) ): ?>
	<? // Сперва закрывающий тег, это связано с порядком подключения файлов ?>
	</div>
	<div class="toolbar toolbar-bottom-md order-toolbar">
		<div class="toolbar-inner">
			<a href="#" class="tab-link set-coupon link">
				Получить скидку
			</a>
			<a href="/?<?=MOBILE_GET?>=Y&page=personal/order" data-href="/?<?=MOBILE_GET?>=Y&page=personal/order"  class="tab-link external-link disabled order-link  link external">
				Проверка...
				<?
				/*
					Оформить заказ на <?=FormatCurrency($totalPrice, $OPTION_CURRENCY);?>
				*/
				?>
			</a>
		</div>
<? endif; ?>
