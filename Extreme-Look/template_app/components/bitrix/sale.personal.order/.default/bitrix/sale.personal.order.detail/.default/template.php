<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$i = 1;?>
<div class="block" style="padding:0;margin:18px 0">
	<?if(CModule::IncludeModule('edost.delivery')){
		$ar = $GLOBALS['APPLICATION']->IncludeComponent('edost:delivery', '', array(
			'MODE' => 'sale.personal.order.detail',
			'PARAM' => array(),
			'RESULT' => $arResult,
		), null, array('HIDE_ICONS' => 'Y'));
		if(!empty($ar)) $arResult = $ar;
	}?>
	<?if(!empty($arResult['ERRORS']['FATAL'])){?>
		<?foreach($arResult['ERRORS']['FATAL'] as $error){?>
			<?=ShowError($error)?>
		<?}?>
		<?$component = $this->__component;?>
		<?if($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])){?>
			<?$APPLICATION->AuthForm('', false, false, 'N', false);?>
		<?}?>
	<?}else{?>
		<?if(!empty($arResult['ERRORS']['NONFATAL'])){?>
			<?foreach($arResult['ERRORS']['NONFATAL'] as $error){?>
				<?=ShowError($error)?>
			<?}?>
		<?}?>
		<div class="bx_order_list">
			<table class="bx_order_list_table">
				<thead>
					<tr>
						<td colspan="2">
							<?=GetMessage('SPOD_ORDER')?> <?=GetMessage('SPOD_NUM_SIGN')?><?=$arResult["ACCOUNT_NUMBER"]?>
							<?if(strlen($arResult["DATE_INSERT_FORMATED"])){?>
								<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_INSERT_FORMATED"]?>
							<?}?>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<?=GetMessage('SPOD_ORDER_STATUS')?>:
						</td>
						<td>
							<?=$arResult["STATUS"]["NAME"]?>
							<?if(strlen($arResult["DATE_STATUS_FORMATED"])){?>
								(<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_STATUS_FORMATED"]?>)
							<?}?>
						</td>
					</tr>
					<tr>
						<td>
							<?=GetMessage('SPOD_ORDER_PRICE')?>:
						</td>
						<td>
							<?=$arResult["PRICE_FORMATED"]?>
							<?if(floatval($arResult["SUM_PAID"])){?>
								(<?=GetMessage('SPOD_ALREADY_PAID')?>:&nbsp;<?=$arResult["SUM_PAID_FORMATED"]?>)
							<?}?>
						</td>
					</tr>
					<?if($arResult["CANCELED"] == "Y" || $arResult["CAN_CANCEL"] == "Y"):?>
						<tr>
							<td><?=GetMessage('SPOD_ORDER_CANCELED')?>:</td>
							<td>
								<?if($arResult["CANCELED"] == "Y"){?>
									<?=GetMessage('SPOD_YES')?>
									<?if(strlen($arResult["DATE_CANCELED_FORMATED"])){?>
										(<?=GetMessage('SPOD_FROM')?> <?=$arResult["DATE_CANCELED_FORMATED"]?>)
									<?}?>
								<?}elseif($arResult["CAN_CANCEL"] == "Y"){?>
									<?=GetMessage('SPOD_NO')?>&nbsp;&nbsp;&nbsp;[<a class="external" href="<?=modifyUrl([
												'ID' 			=> $arResult['ID'],
												'CANCEL'		=> 'Y',
												'page-heading' 	=> 'Отмена заказа',
												'back-url'		=> urlencode(modifyUrl(['go-back' => 'Y']))
											])?>"><?=GetMessage("SPOD_ORDER_CANCEL")?></a>]
								<?}?>
							</td>
						</tr>
					<?endif?>
					<tr class="table_order_separately_start">
						<td colspan="2"><?=GetMessage('SPOD_ORDER_PROPERTIES')?></td>
					</tr>
					<tr class="table_order_separately_end">
						<td><?=GetMessage('SPOD_ORDER_PERS_TYPE')?>:</td>
						<td><?=$arResult["PERSON_TYPE"]["NAME"]?></td>
					</tr>
					<?if(isset($arResult["ORDER_PROPS"])){?>
						<?foreach($arResult["ORDER_PROPS"] as $prop){?>
							<?if($prop["SHOW_GROUP_NAME"] == "Y"){?>
								<tr class="table_order_separately_start">
									<td colspan="2"><?=$prop["GROUP_NAME"]?></td>
								</tr>
							<?}?>
							<tr<?=count($arResult["ORDER_PROPS"]) == $i ? ' class=table_order_separately_end>' : '>'?>
								<td><?=$prop['NAME']?>:</td>
								<td>
									<?if($prop["TYPE"] == "CHECKBOX"){?>
										<?=GetMessage('SPOD_'.($prop["VALUE"] == "Y" ? 'YES' : 'NO'))?>
									<?}else{?>
										<?=$prop["VALUE"]?>
									<?}?>
								</td>
							</tr>
						<?$i++;
						}?>
					<?}?>
					<?if(!empty($arResult["USER_DESCRIPTION"])){?>
						<tr>
							<td><?=GetMessage('SPOD_ORDER_USER_COMMENT')?>:</td>
							<td><?=$arResult["USER_DESCRIPTION"]?></td>
						</tr>
					<?}?>
					<tr class="table_order_separately_start">
						<td colspan="2"><?=GetMessage("SPOD_ORDER_PAYMENT")?></td>
					</tr>
					<?foreach($arResult['PAYMENT'] as $payment){?>
						<tr>
							<td><?=GetMessage('SPOD_PAY_SYSTEM')?>:</td>
							<td>
								<?if(intval($payment["PAY_SYSTEM_ID"])){?>
									<?if ($payment['PAY_SYSTEM']){?>
										<?=$payment["PAY_SYSTEM"]["NAME"].' ('.$payment['PRICE_FORMATED'].')'?>
									<?}else{?>
										<?=$payment["PAY_SYSTEM_NAME"].' ('.$payment['PRICE_FORMATED'].')';?>
									<?}?>
								<?}else{?>
									<?=GetMessage("SPOD_NONE")?>
								<?}?>
							</td>
						</tr>
						<tr>
							<td><?=GetMessage('SPOD_ORDER_PAYED')?>:</td>
							<td>
								<?if($payment["PAID"] == "Y"){?>
									<?=GetMessage('SPOD_YES')?>
									<?if(strlen($payment["DATE_PAID_FORMATED"])){?>
										(<?=GetMessage('SPOD_FROM')?> <?=$payment["DATE_PAID_FORMATED"]?>)
									<?}?>
								<?}else{?>
									<?=GetMessage('SPOD_NO')?>
									<?if($payment["CAN_REPAY"]=="Y" && $payment["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"){?>
										&nbsp;&nbsp;&nbsp;[<a href="<?=$payment["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("SPOD_REPEAT_PAY")?></a>]
									<?}?>
								<?}?>
							</td>
						</tr>
						<?if($payment["CAN_REPAY"]=="Y" && $payment["PAY_SYSTEM"]["PSA_NEW_WINDOW"] != "Y"){?>
							<tr>
								<td colspan="2">
									<?$ORDER_ID = $ID;
										try
										{
											include($payment["PAY_SYSTEM"]["PSA_ACTION_FILE"]);
										}
										catch(\Bitrix\Main\SystemException $e)
										{
											if($e->getCode() == CSalePaySystemAction::GET_PARAM_VALUE)
												$message = GetMessage("SOA_TEMPL_ORDER_PS_ERROR");
											else
												$message = $e->getMessage();
											ShowError($message);
										}?>
								</td>
							</tr>
						<?}?>
					<?}?>
					<?foreach($arResult['SHIPMENT'] as $shipment){?>
						<tr>
							<td><?=GetMessage("SPOD_ORDER_DELIVERY")?>:</td>
							<td>
								<?if(intval($shipment["DELIVERY_ID"])){?>
									<?=$shipment["DELIVERY"]["NAME"]?>
									<?if(intval($shipment['STORE_ID']) && !empty($arResult["DELIVERY"]["STORE_LIST"][$shipment['STORE_ID']])){?>
										<?$store = $arResult["DELIVERY"]["STORE_LIST"][$shipment['STORE_ID']];?>
										<div class="bx_ol_store">
											<div class="bx_old_s_row_title">
												<?=GetMessage('SPOD_TAKE_FROM_STORE')?>: <b><?=$store['TITLE']?></b>
												<?if(!empty($store['DESCRIPTION'])){?>
													<div class="bx_ild_s_desc">
														<?=$store['DESCRIPTION']?>
													</div>
												<?}?>
											</div>
											<?if(!empty($store['ADDRESS'])){?>
												<div class="bx_old_s_row">
													<b><?=GetMessage('SPOD_STORE_ADDRESS')?></b>: <?=$store['ADDRESS']?>
												</div>
											<?}?>
											<?if(!empty($store['SCHEDULE'])){?>
												<div class="bx_old_s_row">
													<b><?=GetMessage('SPOD_STORE_WORKTIME')?></b>: <?=$store['SCHEDULE']?>
												</div>
											<?}?>
											<?if(!empty($store['PHONE'])){?>
												<div class="bx_old_s_row">
													<b><?=GetMessage('SPOD_STORE_PHONE')?></b>: <?=$store['PHONE']?>
												</div>
											<?}?>
											<?if(!empty($store['EMAIL'])){?>
												<div class="bx_old_s_row">
													<b><?=GetMessage('SPOD_STORE_EMAIL')?></b>: <a href="mailto:<?=$store['EMAIL']?>"><?=$store['EMAIL']?></a>
												</div>
											<?}?>
											<?if(($store['GPS_N'] = floatval($store['GPS_N'])) && ($store['GPS_S'] = floatval($store['GPS_S']))){?>
												<div id="bx_old_s_map">
													<div class="bx_map_buttons">
														<a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" id="map-show">
															<?=GetMessage('SPOD_SHOW_MAP')?>
														</a>
														<a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" id="map-hide">
															<?=GetMessage('SPOD_HIDE_MAP')?>
														</a>
													</div>
													<?ob_start();?>
														<div><?$mg = $arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']]['IMAGE'];?>
															<?if(!empty($mg['SRC'])){?><img src="<?=$mg['SRC']?>" width="<?=$mg['WIDTH']?>" height="<?=$mg['HEIGHT']?>"><br /><br /><?}?>
															<?=$store['TITLE']?></div>
													<?$ballon = ob_get_contents();?>
													<?ob_end_clean();?>
													<?$mapId = '__store_map';
														$mapParams = array(
														'yandex_lat' => $store['GPS_N'],
														'yandex_lon' => $store['GPS_S'],
														'yandex_scale' => 16,
														'PLACEMARKS' => array(
															array(
																'LON' => $store['GPS_S'],
																'LAT' => $store['GPS_N'],
																'TEXT' => $ballon
															)
														));
													?>
													<div id="map-container">
														<?$APPLICATION->IncludeComponent("bitrix:map.yandex.view", ".default", array(
															"INIT_MAP_TYPE" => "MAP",
															"MAP_DATA" => serialize($mapParams),
															"MAP_WIDTH" => "100%",
															"MAP_HEIGHT" => "200",
															"CONTROLS" => array(
																0 => "SMALLZOOM",
															),
															"OPTIONS" => array(
																0 => "ENABLE_SCROLL_ZOOM",
																1 => "ENABLE_DBLCLICK_ZOOM",
																2 => "ENABLE_DRAGGING",
															),
															"MAP_ID" => $mapId
															),
															false
														);?>
													</div>
													<?CJSCore::Init();?>
													<script>
														new CStoreMap({mapId:"<?=$mapId?>", area: '.bx_old_s_map'});
													</script>
												</div>
											<?}?>
										</div>
									<?}?>
								<?}else{?>
									<?=GetMessage("SPOD_NONE")?>
								<?}?>
							</td>
						</tr>
						<?if($shipment["TRACKING_NUMBER"]){?>
							<tr>
								<td><?=GetMessage('SPOD_ORDER_TRACKING_NUMBER')?>:</td>
								<td><?=$shipment["TRACKING_NUMBER"]?></td>
							</tr>
						<?}?>
						<tr>
							<td><?=GetMessage('SPOD_ORDER_SHIPMENT_BASKET')?>:</td>
							<td>
								<?foreach($shipment['ITEMS'] as $item){?>
									<?=$item['NAME']." (".$item['QUANTITY'].' '.$item['MEASURE_NAME'].") "?><br>
								<?}?>
							</td>
						</tr>
					<?}?>
				</tbody>
			</table>
			<h3><?=GetMessage('SPOD_ORDER_BASKET')?></h3>
			<div class="bx_order_list_table_order_items_final">
				<?if(isset($arResult["BASKET"])){?>
					<?foreach($arResult["BASKET"] as $prod){?>
						<tr>
                            <div class="bx_order_list_items_flex">
                                <?$hasLink = !empty($prod["DETAIL_PAGE_URL"]);?>
                                <div class="img">
                                    <?if($hasLink){?>
                                        <a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank">
                                    <?}?>
                                    <?if($prod['PICTURE']['SRC']){?>
                                        <img src="<?=$prod['PICTURE']['SRC']?>" width="<?=$prod['PICTURE']['WIDTH']?>" height="<?=$prod['PICTURE']['HEIGHT']?>" alt="<?=$prod['NAME']?>" />
                                    <?}?>
                                    <?if($hasLink){?>
                                        </a>
                                    <?}?>
                                </div>
                                <div class="bx_order_list_items_info_flex">
                                    <div class="bx_order_list_name">
                                        <?if($hasLink){?>
                                            <a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank">
                                        <?}?>
                                        <?=htmlspecialcharsEx($prod["NAME"])?>
                                        <?if($hasLink){?>
                                            </a>
                                        <?}?>
                                    </div>
                                    <div class="bx_order_list_price"><span class="fm"><?=GetMessage('SPOD_PRICE')?>:</span> <?=$prod["PRICE_FORMATED"]?></div>
                                    <?if($arResult['HAS_PROPS']){?>
                                        <?$actuallyHasProps = is_array($prod["PROPS"]) && !empty($prod["PROPS"]);?>
                                        <div class=""><?if($actuallyHasProps){?><span class="fm"><?=GetMessage('SPOD_PROPS')?>:</span><?}?>
                                            <table cellspacing="0" class="bx_ol_sku_prop">
                                                <?if($actuallyHasProps){?>
                                                    <?foreach($prod["PROPS"] as $prop){?>
                                                        <?if(!empty($prop['SKU_VALUE']) && $prop['SKU_TYPE'] == 'image'){?>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <nobr><?=$prop["NAME"]?>:</nobr><br />
                                                                    <img src="<?=$prop['SKU_VALUE']['PICT']['SRC']?>" width="<?=$prop['SKU_VALUE']['PICT']['WIDTH']?>" height="<?=$prop['SKU_VALUE']['PICT']['HEIGHT']?>" title="<?=$prop['SKU_VALUE']['NAME']?>" alt="<?=$prop['SKU_VALUE']['NAME']?>" />
                                                                </td>
                                                            </tr>
                                                        <?}else{?>
                                                            <tr>
                                                                <td><nobr><?=$prop["NAME"]?>:</nobr></td>
                                                                <td style="padding-left: 10px !important"><b><?=$prop["VALUE"]?></b></td>
                                                            </tr>
                                                        <?}?>
                                                    <?}?>
                                                <?}?>
                                            </table>
                                        </div>
                                    <?}?>
                                    <?if($arResult['HAS_DISCOUNT']){?>
                                        <div class="bx_order_list_price"> <span class="fm"><?=GetMessage('SPOD_DISCOUNT')?>:</span> <?=$prod["DISCOUNT_PRICE_PERCENT_FORMATED"]?></div>
                                    <?}?>
                                    <div class="bx_order_list_amount"><span class="fm"><?=GetMessage('SPOD_PRICETYPE')?>:</span><?=htmlspecialcharsEx($prod["NOTES"])?></div>
                                    <div class="bx_order_list_price">
                                        <span class="fm"><?=GetMessage('SPOD_QUANTITY')?>:</span><?=$prod["QUANTITY"]?>
                                        <?if(strlen($prod['MEASURE_TEXT'])){?>
                                            <?=$prod['MEASURE_TEXT']?>
                                        <?}else{?>
                                            <?=GetMessage('SPOD_DEFAULT_MEASURE')?>
                                        <?}?>
                                    </div>
                                </div>
                            </div>
						</tr>
					<?}?>
				<?}?>
			</div>
			<br>
			<table class="bx_ordercart_order_sum">
				<tbody>
					<? ///// WEIGHT ?>
					<?if(floatval($arResult["ORDER_WEIGHT"])):?>
						<tr>
							<td class="custom_t1"><?=GetMessage('SPOD_TOTAL_WEIGHT')?>:</td>
							<td class="custom_t2"><?=$arResult['ORDER_WEIGHT_FORMATED']?></td>
						</tr>
					<?endif?>
					<? ///// PRICE SUM ?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_PRODUCT_SUM')?>:</td>
						<td class="custom_t2"><?=$arResult['PRODUCT_SUM_FORMATTED']?></td>
					</tr>
					<? ///// DELIVERY PRICE: print even equals 2 zero ?>
					<?if(strlen($arResult["PRICE_DELIVERY_FORMATED"])):?>
						<tr>
							<td class="custom_t1"><?=GetMessage('SPOD_DELIVERY')?>:</td>
							<td class="custom_t2"><?=$arResult["PRICE_DELIVERY_FORMATED"]?></td>
						</tr>
					<?endif?>
					<? ///// TAXES DETAIL ?>
					<?foreach($arResult["TAX_LIST"] as $tax):?>
						<tr>
							<td class="custom_t1"><?=$tax["TAX_NAME"]?>:</td>
							<td class="custom_t2"><?=$tax["VALUE_MONEY_FORMATED"]?></td>
						</tr>	
					<?endforeach?>
					<? ///// TAX SUM ?>
					<?if(floatval($arResult["TAX_VALUE"])):?>
						<tr>
							<td class="custom_t1"><?=GetMessage('SPOD_TAX')?>:</td>
							<td class="custom_t2"><?=$arResult["TAX_VALUE_FORMATED"]?></td>
						</tr>
					<?endif?>
					<? ///// DISCOUNT ?>
					<?if(floatval($arResult["DISCOUNT_VALUE"])):?>
						<tr>
							<td class="custom_t1"><?=GetMessage('SPOD_DISCOUNT')?>:</td>
							<td class="custom_t2"><?=$arResult["DISCOUNT_VALUE_FORMATED"]?></td>
						</tr>
					<?endif?>
					<tr>
						<td class="custom_t1 fwb"><?=GetMessage('SPOD_SUMMARY')?>:</td>
						<td class="custom_t2 fwb"><?=$arResult["PRICE_FORMATED"]?></td>
					</tr>
				</tbody>
			</table>
		</div>
	<?}?>
</div>