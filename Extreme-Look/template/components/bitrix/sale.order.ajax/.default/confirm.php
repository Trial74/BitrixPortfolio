<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

if($arParams["SET_TITLE"] == "Y") {
	$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
}

if(!empty($arResult["ORDER"])) {?>
	<span class="alert alert-success alert-show">
		<?=Loc::getMessage("SOA_ORDER_SUC", array("#USER_NAME#" =>$_SESSION["SESS_AUTH"]["FIRST_NAME"], "#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]));
		if(!empty($arResult['ORDER']["PAYMENT_ID"])) {
			echo Loc::getMessage("SOA_PAYMENT_SUC", array("#PAYMENT_ID#" => $arResult['PAYMENT'][$arResult['ORDER']["PAYMENT_ID"]]['ACCOUNT_NUMBER']));
		}?>
		<br />
		<?=Loc::getMessage("SOA_ORDER_SUC1");?>
	</span>
	<?if($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y') {
		if(!empty($arResult["PAYMENT"])) {
			foreach($arResult["PAYMENT"] as $payment) {


                if ($payment["PAY_SYSTEM_ID"] == 14) {
                    ?>
                    <table>
                        <tr>
                            <td>
                                <img width="150"
                                     src="https://extreme-look.ru/upload/sale/paysystem/logotip/fed/rko_tinkoff.png"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="container"><b>Для продолжения нажмите "Оформить рассрочку" и заполните форму
                                        для заявки.</b></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px;">
                                <form target="_blank" id="start_tinkoff"
                                      action="https://loans.tinkoff.ru/api/partners/v1/lightweight/create"
                                      method="post">
                                    <input name="shopId" value="379864d3-6fe9-4fd9-9933-985f89910c25" type="hidden"/>
                                    <input name="showcaseId" value="6cd52d10-0ef6-4242-a79e-3bcfbd1b3eac"
                                           type="hidden"/>
                                    <input name="promoCode" value="installment_0_0_4" type="hidden"/>
                                    <input name="orderNumber" value="<?= $arResult['ORDER']['ID'] ?>" type="hidden"/>
                                    <input name="sum"
                                           value="<?= number_format($arResult['ORDER']['PRICE'], 2, '.', '') ?>"
                                           type="hidden">
                                    <input name="itemName_0" value="Материалы для наращивания ресниц EXTREME LOOK"
                                           type="hidden"/>
                                    <input name="itemQuantity_0" value="1" type="hidden"/>
                                    <input name="itemPrice_0"
                                           value="<?= number_format($arResult['ORDER']['PRICE'], 2, '.', '') ?>"
                                           type="hidden"/>
                                    <input name="itemCategory_0" value="" type="hidden"/>
                                    <?
                                    $emailUser = '';
                                    $phoneUser = '';
                                    $dbOrderProps = CSaleOrderPropsValue::GetList(
                                        array("SORT" => "ASC"),
                                        array("ORDER_ID" => $_REQUEST["ORDER_ID"], 'CODE' => ['EMAIL', 'PHONE'])
                                    );
                                    while ($arOrderProps = $dbOrderProps->GetNext()):
                                        if ($arOrderProps['CODE'] == 'EMAIL') {
                                            $emailUser = $arOrderProps['VALUE'];
                                        }
                                        if ($arOrderProps['CODE'] == 'PHONE') {
                                            $phoneUser = $arOrderProps['VALUE'];
                                        }
                                    endwhile;
                                    ?>
                                    <input name="customerEmail" value="<?= $emailUser ?>" type="hidden"/>
                                    <input name="customerPhone" value="<?= $phoneUser ?>" type="hidden"/>
                                    <input type="submit" style="padding: 10px; color: #fff; border: none"
                                           class="btn btn-buy" value="Оформить рассрочку"/>
                                </form>
                            </td>
                        </tr>
                    </table>
                    <script>
                        window.onload = function () {
                            document.forms['start_tinkoff'].submit();
                            //window.location.replace('/personal/order/');
                        }
                    </script>
                    <?
                    break;
                } elseif ($payment["PAY_SYSTEM_ID"] == 15) {
                    ?>
                    <table>
                        <tr>
                            <td>
                                <img width="150"
                                     src="https://extreme-look.ru/upload/sale/paysystem/logotip/fed/rko_tinkoff.png"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="container"><b>Для продолжения нажмите "Оформить рассрочку" и заполните форму
                                        для заявки.</b></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px;">
                                <form target="_blank" id="start_tinkoff"
                                      action="https://loans.tinkoff.ru/api/partners/v1/lightweight/create"
                                      method="post">
                                    <input name="shopId" value="379864d3-6fe9-4fd9-9933-985f89910c25" type="hidden"/>
                                    <input name="showcaseId" value="6cd52d10-0ef6-4242-a79e-3bcfbd1b3eac"
                                           type="hidden"/>
                                    <input name="promoCode" value="installment_0_0_6" type="hidden"/>
                                    <input name="orderNumber" value="<?= $arResult['ORDER']['ID'] ?>" type="hidden"/>
                                    <input name="sum"
                                           value="<?= number_format($arResult['ORDER']['PRICE'], 2, '.', '') ?>"
                                           type="hidden">
                                    <input name="itemName_0" value="Материалы для наращивания ресниц EXTREME LOOK"
                                           type="hidden"/>
                                    <input name="itemQuantity_0" value="1" type="hidden"/>
                                    <input name="itemPrice_0"
                                           value="<?= number_format($arResult['ORDER']['PRICE'], 2, '.', '') ?>"
                                           type="hidden"/>
                                    <input name="itemCategory_0" value="" type="hidden"/>
                                    <?
                                    $emailUser = '';
                                    $phoneUser = '';
                                    $dbOrderProps = CSaleOrderPropsValue::GetList(
                                        array("SORT" => "ASC"),
                                        array("ORDER_ID" => $_REQUEST["ORDER_ID"], 'CODE' => ['EMAIL', 'PHONE'])
                                    );
                                    while ($arOrderProps = $dbOrderProps->GetNext()):
                                        if ($arOrderProps['CODE'] == 'EMAIL') {
                                            $emailUser = $arOrderProps['VALUE'];
                                        }
                                        if ($arOrderProps['CODE'] == 'PHONE') {
                                            $phoneUser = $arOrderProps['VALUE'];
                                        }
                                    endwhile;
                                    ?>
                                    <input name="customerEmail" value="<?= $emailUser ?>" type="hidden"/>
                                    <input name="customerPhone" value="<?= $phoneUser ?>" type="hidden"/>
                                    <input type="submit" style="padding: 10px; color: #fff; border: none"
                                           class="btn btn-buy" value="Оформить рассрочку"/>
                                </form>
                            </td>
                        </tr>
                    </table>
                    <script>
                        window.onload = function () {
                            document.forms['start_tinkoff'].submit();
                            //window.location.replace('/personal/order/');
                        }
                    </script>
                    <?
                    break;
                } elseif ($payment["PAY_SYSTEM_ID"] == 13) {

                    $arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];
                    $order = Sale\Order::loadByAccountNumber($_REQUEST["ORDER_ID"]);
                    $fields = $order->getFields();
                    $basket = $order->getBasket();
                    $i = 0;
                    $emailUser = '';
                    $phoneUser = '';
                    $dbOrderProps = CSaleOrderPropsValue::GetList(
                        array("SORT" => "ASC"),
                        array("ORDER_ID" => $_REQUEST["ORDER_ID"], 'CODE' => ['EMAIL', 'PHONE'])
                    );
                    while ($arOrderProps = $dbOrderProps->GetNext()):
                        if ($arOrderProps['CODE'] == 'EMAIL') {
                            $emailUser = $arOrderProps['VALUE'];
                        }
                        if ($arOrderProps['CODE'] == 'PHONE') {
                            $phoneUser = $arOrderProps['VALUE'];
                        }
                    endwhile;
                    ?>
                    <br/><br/>
                    <table class="sale_order_full_table">
                        <tr>
                            <td class="ps_logo">
                                <div class="pay_name"><?= Loc::getMessage("SOA_PAY") ?></div>
                                <?= CFile::ShowImage($arPaySystem["LOGOTIP"], 150, 150,
                                    "border=0\" style=\"width:150px\"", "", false) ?>
                                <div class="paysystem_name"><?= $arPaySystem["NAME"] ?></div>
                                <br/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <form id="pay" name="pay" method="POST" action="https://paymaster.ru/payment/init">
                                    <input type="hidden" name="LMI_PAYMENT_AMOUNT"
                                           value="<?= $arResult['ORDER']['PRICE'] ?>">
                                    <input type="hidden" name="LMI_CURRENCY" value="RUB">
                                    <input type="hidden" name="LMI_PAYMENT_DESC"
                                           value="Оплата платежа №-<?= $arResult['ORDER']['ID'] ?>">
                                    <input type="hidden" name="LMI_PAYMENT_NO" value="<?= $arResult['ORDER']['ID'] ?>">
                                    <input type="hidden" name="LMI_MERCHANT_ID"
                                           value="11a7dba7-b54b-4ce8-9ff3-b9930774896b">
                                    <input type="hidden" name="LMI_SUCCESS_URL"
                                           value="https://extreme-look.ru/personal/order/payment/paymaster_success.php">
                                    <input type="hidden" name="LMI_FAIL_URL"
                                           value="https://extreme-look.ru/personal/order/payment/paymaster_fail.php">

                                    <input type="hidden" name="LMI_PAYER_EMAIL" value="<?= $emailUser ?>">
                                    <input type="hidden" name="LMI_PAYER_PHONE_NUMBER" value="<?= $phoneUser ?>">
                                    <input type="hidden" name="LMI_SUCCESS_METHOD" value="1">
                                    <input type="hidden" name="LMI_FAIL_METHOD" value="1">
                                    <input type="hidden" name="BX_PAYSYSTEM_CODE" value="13">
                                    <input type="hidden" name="BX_HANDLER" value="PAYMASTER">

                                    <?
                                    foreach ($basket as $basketItem) {
                                        ?>

                                        <input type="hidden" name="LMI_SHOPPINGCART.ITEM[<?= $i; ?>].NAME"
                                               value="<?= htmlspecialcharsbx($basketItem->GetField("NAME")); ?>">
                                        <input type="hidden" name="LMI_SHOPPINGCART.ITEM[<?= $i; ?>].QTY"
                                               value="<?= $basketItem->GetField("QUANTITY"); ?>">
                                        <input type="hidden" name="LMI_SHOPPINGCART.ITEM[<?= $i; ?>].PRICE"
                                               value="<?= $basketItem->GetField("PRICE"); ?>">
                                        <input type="hidden" name="LMI_SHOPPINGCART.ITEM[<?= $i; ?>].TAX"
                                               value="no_vat">

                                        <?
                                        $i++;
                                    }
                                    if ($fields["PRICE_DELIVERY"] > 0) {
                                        ?>
                                        <input type="hidden" name="LMI_SHOPPINGCART.ITEM[<?= $i; ?>].NAME"
                                               value="Доставка товара">
                                        <input type="hidden" name="LMI_SHOPPINGCART.ITEM[<?= $i; ?>].QTY"
                                               value="Количество">
                                        <input type="hidden" name="LMI_SHOPPINGCART.ITEM[<?= $i; ?>].PRICE"
                                               value="<?= $fields["PRICE_DELIVERY"]; ?>">
                                        <input type="hidden" name="LMI_SHOPPINGCART.ITEM[<?= $i; ?>].TAX"
                                               value="no_vat">
                                        <?
                                    } ?>
                                    <input type="submit" value="Оплатить">
                                </form>
                            </td>
                        </tr>
                    </table>
                    <script>
                        window.onload = function () {
                            document.forms['pay'].submit();
                            //window.location.replace('/personal/order/');
                        }
                    </script>
                    <?
                } elseif ($payment["PAY_SYSTEM_ID"] == 8) {
                    $arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]]; ?>
                    <br/><br/>
                    <table class="sale_order_full_table">
                        <tr>
                            <td class="ps_logo">
                                <div class="pay_name"><?= Loc::getMessage("SOA_PAY") ?></div>
                                <?= CFile::ShowImage($arPaySystem["LOGOTIP"], 150, 150,
                                    "border=0\" style=\"width:150px\"", "", false) ?>
                                <div class="paysystem_name"><?= $arPaySystem["NAME"] ?></div>
                                <br/>
                            </td>
                        </tr>
                    </table>
                    <?
                }
            }

                if (!empty($arResult["PAYMENT"])) {
                    foreach ($arResult["PAYMENT"] as $payment) {
                        if ($payment["PAY_SYSTEM_ID"] == 14) break;
                        if ($payment["PAY_SYSTEM_ID"] == 15) break;
                        if ($payment["PAY_SYSTEM_ID"] == 13) break;
                        if ($payment["PAY_SYSTEM_ID"] == 8) break;


                        if ($payment["PAID"] != 'Y') {
                            if (!empty($arResult['PAY_SYSTEM_LIST']) &&
                                array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])) {
                                $arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];
                                if (empty($arPaySystem["ERROR"])) { ?>
                                    <table class="sale_order_full_table">
                                        <tr>
                                            <td class="ps_logo">
                                                <div class="pay_name"><?= Loc::getMessage("SOA_PAY") ?></div>
                                                <?= CFile::ShowImage($arPaySystem["LOGOTIP"], 100, 100,
                                                    "border=0\" style=\"max-width: 78px; max-height: 42px;\"", "",
                                                    false); ?>
                                                <div class="paysystem_name"><?= $arPaySystem["NAME"] ?></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <? if (strlen($arPaySystem["ACTION_FILE"]) > 0 &&
                                                    $arPaySystem["NEW_WINDOW"] == "Y" &&
                                                    $arPaySystem["IS_CASH"] != "Y") {
                                                    $orderAccountNumber =
                                                        urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
                                                    $paymentAccountNumber = $payment["ACCOUNT_NUMBER"]; ?>
                                                    <script>
                                                        window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
                                                    </script>
                                                <?= Loc::getMessage("SOA_PAY_LINK",
                                                    array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" .
                                                        $orderAccountNumber . "&PAYMENT_ID=" . $paymentAccountNumber));
                                                if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']) { ?>
                                                <br/>
                                                    <?= Loc::getMessage("SOA_PAY_PDF",
                                                        array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" .
                                                            $orderAccountNumber . "&pdf=1&DOWNLOAD=Y"));
                                                }
                                                } else {
                                                    echo $arPaySystem["BUFFERED_OUTPUT"];
                                                } ?>
                                            </td>
                                        </tr>
                                    </table>
                                <? } else {
                                    ShowNote(Loc::getMessage("SOA_ORDER_PS_ERROR"));
                                }
                            } else {
                                ShowNote(Loc::getMessage("SOA_ORDER_PS_ERROR"));
                            }
                        }
                    }
                }
            }
	} else {
		ShowNote($arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']);
	}
} else {?>
	<span class="alert alert-error alert-show">
		<?=Loc::getMessage("SOA_ERROR_ORDER");?>
		<br />
		<?=Loc::getMessage("SOA_ERROR_ORDER_LOST", array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]));?>
		<br />
		<?=Loc::getMessage("SOA_ERROR_ORDER_LOST1");?>
	</span>
<?}