<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */
if ($arParams["SET_TITLE"] == "Y")
	$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));?>

<?if(!empty($arResult["ORDER"])){?>
	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=Loc::getMessage("SOA_ORDER_SUC", array(
					"#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"],
					"#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]
				))?>
				<?if(!empty($arResult['ORDER']["PAYMENT_ID"])){?>
					<?=Loc::getMessage("SOA_PAYMENT_SUC", array(
						"#PAYMENT_ID#" => $arResult['PAYMENT'][$arResult['ORDER']["PAYMENT_ID"]]['ACCOUNT_NUMBER']
					))?>
				<?}?>
				<br /><br />
				<?=Loc::getMessage("SOA_ORDER_SUC1", array("#LINK#" => "/?page=personal/orders&extreme-mobile=Y&ID=".$arResult["ORDER"]["ACCOUNT_NUMBER"]."&page-heading=Заказ-№".$arResult["ORDER"]["ACCOUNT_NUMBER"]))?>
			</td>
		</tr>
	</table>

	<?if(!empty($arResult["PAYMENT"])){
		foreach ($arResult["PAYMENT"] as $payment){
			if ($payment["PAY_SYSTEM_ID"] == 27){?>
				<table>
					<tr>
						<td>
							<img width="150" src="https://extreme-look.ru/upload/sale/paysystem/logotip/fed/rko_tinkoff.png" />
						</td>
					</tr>
					<tr>
						<td>
							<div class="container"><b>Окно для оформления заявки откроется автоматически, если этого не произхошло нажмите кнопку "Оформить рассрочку" и заполните форму для заявки.</b></div>
						</td>
					</tr>
					<tr>
						<td style="padding: 10px;">
                            <?
                            $emailUser ='';
                            $phoneUser = '';
                            $dbOrderProps = CSaleOrderPropsValue::GetList(
                                array("SORT" => "ASC"),
                                array("ORDER_ID" => $_REQUEST["ORDER_ID"], 'CODE' => ['EMAIL', 'PHONE'])
                            );
                            while ($arOrderProps = $dbOrderProps->GetNext()){
                                if ($arOrderProps['CODE'] == 'EMAIL'){
                                    $emailUser = $arOrderProps['VALUE'];
                                }
                                if ($arOrderProps['CODE'] == 'PHONE'){
                                    $phoneUser = $arOrderProps['VALUE'];
                                }
                            }?>
                            <a href="#" onclick="document.location.href = this.href" id="tinkoff-link" class="btn btn-sm btn-info" style="color: black;">Оформить рассрочку</a>
                            <script src="https://forma.tinkoff.ru/static/onlineScript.js"></script>
                            <script>
                                tinkoff.createLink(
                                    {
                                        sum: <?=number_format($arResult['ORDER']['PRICE'], 2, '.', '')?>,
                                        items: [{
                                            name: 'Материалы для наращивания ресниц EXTREME LOOK',
                                            price: <?=number_format($arResult['ORDER']['PRICE'], 2, '.', '')?>,
                                            quantity: 1
                                        }],
                                        promoCode: 'installment_0_0_4',
                                        shopId: '379864d3-6fe9-4fd9-9933-985f89910c25',
                                        showcaseId: '6cd52d10-0ef6-4242-a79e-3bcfbd1b3eac',
                                        failURL: 'https://extreme-look.ru/?extreme-mobile=Y&page=payrez&tin_err=Y&order=<?=$arResult['ORDER']['ID']?>',
                                        successURL: 'https://extreme-look.ru/?extreme-mobile=Y&page=payrez&tin_rez=Y&order=<?=$arResult['ORDER']['ID']?>',
                                        orderNumber: '<?=$arResult['ORDER']['ID']?>',
                                        values:{
                                            contact:{
                                                email: '<?=$emailUser?>',
                                                mobilePhone: '<?=$phoneUser?>'
                                            }
                                        }
                                    }
                                ).then((r) => {
                                    const el = document.querySelector('#tinkoff-link');
                                    el.href = r;
                                    document.location.href = r;
                                });
                            </script>
						</td>
					</tr>
				</table>
				<?break;
			}elseif($payment["PAY_SYSTEM_ID"] == 28){?>
					<table>
						<tr>
							<td>
								<img width="150" src="https://extreme-look.ru/upload/sale/paysystem/logotip/fed/rko_tinkoff.png" />
							</td>
						</tr>
						<tr>
							<td>
								<div class="container"><b>Окно для оформления заявки откроется автоматически, если этого не произхошло нажмите кнопку "Оформить рассрочку" и заполните форму для заявки.</b></div>
							</td>
						</tr>
						<tr>
							<td style="padding: 10px;">
                                <?
                                $emailUser ='';
                                $phoneUser = '';
                                $dbOrderProps = CSaleOrderPropsValue::GetList(
                                    array("SORT" => "ASC"),
                                    array("ORDER_ID" => $_REQUEST["ORDER_ID"], 'CODE' => ['EMAIL', 'PHONE'])
                                );
                                while ($arOrderProps = $dbOrderProps->GetNext()){
                                    if ($arOrderProps['CODE'] == 'EMAIL'){
                                        $emailUser = $arOrderProps['VALUE'];
                                    }
                                    if ($arOrderProps['CODE'] == 'PHONE'){
                                        $phoneUser = $arOrderProps['VALUE'];
                                    }
                                }?>
                                <a href="#" onclick="document.location.href = this.href" id="tinkoff-link" class="btn btn-sm btn-info" style="color: black;">Оформить рассрочку</a>

                                <script src="https://forma.tinkoff.ru/static/onlineScript.js"></script>
                                <script>
                                    tinkoff.createLink(
                                        {
                                            sum: <?=number_format($arResult['ORDER']['PRICE'], 2, '.', '')?>,
                                            items: [{
                                                name: 'Материалы для наращивания ресниц EXTREME LOOK',
                                                price: <?=number_format($arResult['ORDER']['PRICE'], 2, '.', '')?>,
                                                quantity: 1
                                            }],
                                            promoCode: 'installment_0_0_6',
                                            shopId: '379864d3-6fe9-4fd9-9933-985f89910c25',
                                            showcaseId: '6cd52d10-0ef6-4242-a79e-3bcfbd1b3eac',
                                            failURL: 'https://extreme-look.ru/?extreme-mobile=Y&page=payrez&tin_err=Y&order=<?=$arResult['ORDER']['ID']?>',
                                            successURL: 'https://extreme-look.ru/?extreme-mobile=Y&page=payrez&tin_rez=Y&order=<?=$arResult['ORDER']['ID']?>',
                                            orderNumber: '<?=$arResult['ORDER']['ID']?>',
                                            values:{
                                                contact:{
                                                    email: '<?=$emailUser?>',
                                                    mobilePhone: '<?=$phoneUser?>'
                                                }
                                            }
                                        }
                                    ).then((r) => {
                                        const el = document.querySelector('#tinkoff-link');
                                        el.href = r;
                                        document.location.href = r;
                                    });
                                </script>
							</td>
						</tr>
					</table>
					<?break;
			}
			if($payment["PAID"] != 'Y'){
                if ($payment["PAY_SYSTEM_ID"] == 14) break;
                if ($payment["PAY_SYSTEM_ID"] == 15) break;
				if (!empty($arResult['PAY_SYSTEM_LIST']) && array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])){
					$arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];
					if(empty($arPaySystem["ERROR"])){?>
						<br />
                        <br />
						<table class="sale_order_full_table">
							<tr>
								<td class="ps_logo">
									<div class="pay_name"><?= Loc::getMessage("SOA_PAY") ?></div>
									<?=CFile::ShowImage($arPaySystem["LOGOTIP"], 100, 100, "border=0\" style=\"width:100px\"", "", false)?>
									<div class="paysystem_name"><?= $arPaySystem["NAME"] ?></div>
									<br/>
								</td>
							</tr>
							<tr>
								<td>
									<?if(strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"){
										$orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
										$paymentAccountNumber = $payment["ACCOUNT_NUMBER"];?>
										<script>
                                            document.location.href = "https://extreme-look.ru?page=<?=$arParams["PATH_TO_PAYMENT"]?>&extreme-mobile=Y&ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>";
										</script>
										<?=Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&PAYMENT_ID=".$paymentAccountNumber))?>
									<?}else{?>
										<?=$arPaySystem["BUFFERED_OUTPUT"]?>
									<?}?>
								</td>
							</tr>
						</table>
					<?}else{?>
						<span style="color:red;"><?= Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
					<?}
				}else{?>
					<span style="color:red;"><?= Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
				<?}
			}

		}
	}?>
<?}else{?>
	<b><?=Loc::getMessage("SOA_ERROR_ORDER")?></b>
	<br />
    <br />
	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=Loc::getMessage("SOA_ERROR_ORDER_LOST", array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
				<?=Loc::getMessage("SOA_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>
<?}?>