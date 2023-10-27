<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult['ERRORS']['FATAL'])){?>
	<div class="block">
		<?foreach($arResult['ERRORS']['FATAL'] as $error){?>
			<?=ShowError($error)?>
		<?}?>
	</div>
<?}else{?>
	<?if(!empty($arResult['ERRORS']['NONFATAL'])){?>
		<?foreach($arResult['ERRORS']['NONFATAL'] as $error){?>
			<?=ShowError($error)?>
		<?}?>
	<?}?>
	<?if(!empty($arResult['ORDERS'])){?>
		<div class="subnavbar">
			<div class="subnavbar-inner">
				<div class="segmented">
					<?$active = 'curr';
                    if(isset($_REQUEST["show_all"])){
                        $active = 'all';
                    }?>
					<a class="link button tab-link external <?=$active == 'all' ? 'tab-link-active' : ''?>" href="<?=modifyUrl(['show_all' => 'Y'])?>">Все</a>
					<a class="link button tab-link external <?=$active == 'curr' ? 'tab-link-active' : ''?>" href="<?=modifyUrl(['filter_history' => 'N'])?>">Текущие</a>
				</div>
			</div>
		</div>
	<?}?>
    <div style="margin-top: 60px;"></div>
	<?if(!empty($arResult['ORDERS'])){?>
		<?foreach($arResult["ORDERS"] as $key => $order){?>
            <div class="card">
                <div class="card-navbar">
                    <div class="date-order"><?=$order["ORDER"]["DATE_INSERT_FORMATED"]?></div>
                    <div class="number-order">Заказ №<?=$order["ORDER"]["ACCOUNT_NUMBER"]?></div>
                </div>
                <div class="info-by-orders">
                    <div class="summ-order"><?=GetMessage('SPOL_PAY_SUM')?>: <span><?=$order["ORDER"]["FORMATED_PRICE"]?></span></div>
                    <div class="count-items"><?=GetMessage('SPOL_COUNT_ITEMS')?><span><?=$order["BASKET_ITEMS"] ? count($order["BASKET_ITEMS"]) : 0?></span></div>
                    <div class="stat-order">
                        <div class="stat-text"><?=$arResult["INFO"]["STATUS"][$order['ORDER']['STATUS_ID']]["NAME"]?></div>
                    </div>
                    <div class="tracking-number"><?prent_r($order['ORDER'])?><?=GetMessage('SPOL_TRACKING_NUMBER')?><?=$order["ORDER"]['TRACKING_NUMBER'] ? '<span>' . $order["ORDER"]['TRACKING_NUMBER'] . '</span>' : GetMessage('SPOL_TRACKING_NUMBER_NULL')?></div>
                    <div class="summ-delivery"><?=GetMessage('SPOL_PRICE_DELIVERY')?><?=$order['ORDER']['PRICE_DELIVERY'] != 0 ? '<span>' . round($order["ORDER"]['PRICE_DELIVERY']) . '</span>' : GetMessage('SPOL_PRICE_DELIVERY_NULL')?></div>
                </div>
                <?if((isset($order['PAYMENT']) && !empty($order['PAYMENT'])) || (isset($order['SHIPMENT']) && !empty($order['SHIPMENT']))){?>
                    <div class="pay-and-delivery-img">
                        <?if(isset($order['PAYMENT']) && !empty($order['PAYMENT'])){?>
                            <div class="tittle-pay-img"><?=GetMessage('SPOL_PAY_IMG_TITTLE')?></div>
                            <?foreach($order["PAYMENT"] as $payment){?>
                                <div class="img-pay-system-item"><?=CFile::ShowImage($arResult['INFO']['PAY_SYSTEM'][$payment['PAY_SYSTEM_ID']]['LOGOTIP'], 100, 60, "border=0", "", true);?></div>
                            <?}?>
                        <?}?>
                        <?if(isset($order['SHIPMENT']) && !empty($order['SHIPMENT'])){?>
                            <div class="tittle-delivery-img"><?=GetMessage('SPOL_DELIVERY_IMG_TITTLE')?></div>
                            <?foreach ($order['SHIPMENT'] as $shipment){?>
                                <div class="img-delivery-system-item"><?=CFile::ShowImage($arResult['INFO']['DELIVERY'][$shipment['DELIVERY_ID']]['LOGOTIP'], 100, 60, "border=0", "", true);?></div>
                            <?}?>
                        <?}?>
                    </div>
                <?}?>
                <div class="card-footer">
                    <?if($order["ORDER"]["CANCELED"] != "Y"){?>
                        <a class="btn btn-default extr-cansel-button" href="<?=modifyUrl([
                            'ID' 			=> $order['ORDER']['ID'],
                            'CANCEL'		=> 'Y',
                            'page-heading' 	=> 'Отмена заказа',
                            'back-url'		=> urlencode(modifyUrl(['go-back' => 'Y']))
                        ])?>">Отменить</a>
                    <?}?>
                    <a class="btn btn-buy extr-show-button" href="<?=modifyUrl([
                        'ID' => $order['ORDER']['ID'],
                        'page-heading' 	=> urlencode('Заказ #' . $order['ORDER']['ID']),
                        'back-url'		=> urlencode(modifyUrl(['go-back' => 'Y']))
                    ])?>">Подробнее</a>
                </div>
            </div>
		<?}?>
		<?if(strlen($arResult['NAV_STRING'])){?>
			<?=$arResult['NAV_STRING']?>
		<?}?>
	<?}else{?>
		<div class="block">
			<?=GetMessage('SPOL_NO_ORDERS')?>
		</div>
	<?}?>
<?}?>