<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$randomString = $this->randString();

if(!$arResult["USER_ID"] && !isset($arParams["GUEST_ACCESS"])) {	
	$contactTypeCount = count($arResult["CONTACT_TYPES"]);
	$authStyle = "display: block;";
	$identificationStyle = "display: none;";
	if(!empty($_GET["result"])) {
		$authStyle = "display: none;";
		$identificationStyle = "display: block;";
	}?>

	<div class="row">
		<div id="alertMessage" class="col-xs-12">
			<div class="alert alert-error" style="display: block;"><?=Loc::getMessage("CPSL_SUBSCRIBE_PAGE_TITLE_AUTHORIZE")?></div>
		</div>
		<?$authListGetParams = array();?>
		<div class="col-xs-12" id="catalog-subscriber-auth-form" style="<?=$authStyle?>">
			<?$APPLICATION->authForm("", false, false, "N", false);?>
		</div>
		<?$APPLICATION->setTitle(Loc::getMessage("CPSL_TITLE_PAGE_WHEN_ACCESSING"));?>
		
		<div id="catalog-subscriber-identification-form" style="<?=$identificationStyle?>">
			<div class="col-xs-12 catalog-subscriber-identification-form">
				<div class="csif-title-container">
					<div class="csif-title">
						<div class="csif-title__icon"><i class="icon-key"></i></div>
						<div class="csif-title__val"><?=Loc::getMessage("CPSL_HEADLINE_FORM_SEND_CODE")?></div>
					</div>
				</div>
				<form method="post">
					<?=bitrix_sessid_post()?>
					<input type="hidden" name="siteId" value="<?=SITE_ID?>">
					<div class="csif-block-container">
						<div class="row">
							<div class="col-xs-12 col-md-4 csif-personal-data-inner">
								<?if($contactTypeCount > 1) {?>
									<div class="csif-formgroup-container form-group">
										<div class="csif-formgroup-container">
											<div class="csif-label-container"><?=Loc::getMessage("CPSL_CONTACT_TYPE_SELECTION")?></div>
											<div class="csif-input-container form-group">
												<select id="contactType" class="form-control" name="contactType">
													<?foreach($arResult["CONTACT_TYPES"] as $contactTypeData) {?>
														<option value="<?=intval($contactTypeData['ID'])?>"><?=htmlspecialcharsbx($contactTypeData["NAME"])?></option>
													<?}?>
												</select>
											</div>
										</div>
									</div>
								<?}?>
								<div class="csif-formgroup-container">
									<?$contactLable = Loc::getMessage("CPSL_CONTACT_TYPE_NAME");
									$contactTypeId = 0;
									if($contactTypeCount == 1) {
										$contactType = current($arResult["CONTACT_TYPES"]);
										$contactLable = $contactType["NAME"];
										$contactTypeId = $contactType["ID"];
									}?>
									<div class="csif-label-container"><?=htmlspecialcharsbx($contactLable)?></div>
									<div class="csif-input-container form-group">
										<input type="text" class="form-control" name="userContact" id="contactInput">
										<input type="hidden" name="subscriberIdentification" value="Y">
										<?if($contactTypeId) {?>
											<input type="hidden" name="contactType" value="<?=$contactTypeId?>">
										<?}?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="csif-formgroup-container">
						<button type="submit" class="btn btn-buy"><?=Loc::getMessage("CPSL_BUTTON_SUBMIT_CODE")?></button>
					</div>
				</form>
				<div class="csif-title-container">
					<div class="csif-title">
						<div class="csif-title__icon"><i class="icon-unlock"></i></div>
						<div class="csif-title__val"><?=Loc::getMessage("CPSL_HEADLINE_FORM_FOR_ACCESSING")?></div>
					</div>
				</div>
				<form method="post">
					<?=bitrix_sessid_post()?>
					<div class="csif-block-container">
						<div class="row">
							<div class="col-xs-12 col-md-4 csif-personal-data-inner">
								<?=bitrix_sessid_post()?>
								<div class="csif-formgroup-container">
									<div class="csif-label-container"><?=htmlspecialcharsbx($contactLable)?></div>
									<div class="csif-input-container form-group">
										<input type="text" class="form-control" name="userContact" id="contactInput" value="<?=!empty($_GET['contact']) ? htmlspecialcharsbx(urldecode($_GET['contact'])): ''?>" />
									</div>
								</div>
								<div class="csif-formgroup-container">
									<div class="csif-label-container"><?=Loc::getMessage("CPSL_CODE_LABLE")?></div>
									<div class="csif-input-container form-group">
										<input type="text" class="form-control" name="subscribeToken" id="token" />
										<input type="hidden" name="accessCodeVerification" value="Y" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="csif-formgroup-container">
						<button type="submit" class="btn btn-buy"><?=Loc::getMessage("CPSL_BUTTON_SUBMIT_ACCESS")?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		BX.ready(function() {
			if(BX("cpsl-auth")) {
				BX.bind(BX("cpsl-auth"), "click", BX.delegate(showAuthForm, this));
				BX.bind(BX("cpsl-identification"), "click", BX.delegate(showAuthForm, this));
			}
			function showAuthForm() {
				var formType = BX.proxy_context.id.replace("cpsl-", ""),
					authForm = BX("catalog-subscriber-auth-form"),
					codeForm = BX("catalog-subscriber-identification-form");
				
				if(!authForm || !codeForm || !BX("catalog-subscriber-" + formType + "-form"))
					return;

				BX.style(authForm, "display", "none");
				BX.style(codeForm, "display", "none");
				BX.style(BX("catalog-subscriber-" + formType + "-form"), "display", "");
			}
		});
	</script>
<?}?>

<script type="text/javascript">
	BX.message({
		CPSL_STATUS_SUCCESS: "<?=GetMessageJS('CPSL_STATUS_SUCCESS');?>",
		CPSL_STATUS_ERROR: "<?=GetMessageJS('CPSL_STATUS_ERROR');?>"
	});
</script>

<?if(!empty($_GET["result"]) && !empty($_GET["message"])) {
	$successNotify = strpos($_GET["result"], "Ok") ? true : false;
	$postfix = $successNotify ? "Ok" : "Fail";
	$popupTitle = Loc::getMessage("CPSL_SUBSCRIBE_POPUP_TITLE_".strtoupper(str_replace($postfix, "", $_GET["result"])));

	$arJSParams = array(
		"NOTIFY_USER" => true,
		"NOTIFY_POPUP_TITLE" => $popupTitle,
		"NOTIFY_SUCCESS" => $successNotify,
		"NOTIFY_MESSAGE" => urldecode($_GET["message"]),
	);?>
	<script type="text/javascript">
		var <?="jaClass_".$randomString;?> = new JCCatalogProductSubscribeList(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
	</script>
<?}

if(!empty($arResult["ITEMS"])) {
	$skuTemplate = array();
	if(!empty($arResult["SKU_PROPS"])) {
		foreach($arResult["SKU_PROPS"] as $itemId => $arProp) {
			foreach($arProp as $propId => $prop) {
				$propId = $prop["ID"];
				$skuTemplate[$itemId][$propId] = array(
					"SCROLL" => array(
						"START" => "",
						"FINISH" => "",
					),
					"FULL" => array(
						"START" => "",
						"FINISH" => "",
					),
					"ITEMS" => array()
				);
				$templateRow = "";
				if("TEXT" == $prop["SHOW_MODE"]) {
					$skuTemplate[$itemId][$propId]["FULL"]["START"] = "<div class='bx_subscribe_item_scu_size' id='#ITEM#_prop_".$propId."_cont'>".
						"<span class='bx_subscribe_item_scu_size_title'>".htmlspecialcharsbx($prop["NAME"])."</span>".
						"<div class='bx_subscribe_item_scu_size_val'>".
						"<div class='bx_size'>".
						"<ul class='bx_scu_list' id='#ITEM#_prop_".$propId."_list'>";
					$skuTemplate[$itemId][$propId]["FULL"]["FINISH"] = "</ul></div></div></div>";
					foreach($prop["VALUES"] as $value) {
						$value["NAME"] = htmlspecialcharsbx($value["NAME"]);
						$skuTemplate[$itemId][$propId]["ITEMS"][$value["ID"]] = "<li class='bx_scu_list_size' data-treevalue='".$propId."_".$value["ID"].
							"' data-onevalue='".$value["ID"]."' title='".$value["NAME"]."'>".$value["NAME"]."</li>";
					}
					unset($value);
				} elseif("PICT" == $prop["SHOW_MODE"]) {
					$skuTemplate[$itemId][$propId]["FULL"]["START"] = "<div class='bx_subscribe_item_scu_pict' id='#ITEM#_prop_".$propId."_cont'>".
						"<span class='bx_subscribe_item_scu_pict_title'>".htmlspecialcharsbx($prop["NAME"])."</span>".
						"<div class='bx_subscribe_item_scu_pict_val'>".
						"<div class='bx_scu'>".
						"<ul class='bx_scu_list' id='#ITEM#_prop_".$propId."_list'>";
					$skuTemplate[$itemId][$propId]["FULL"]["FINISH"] = "</ul></div></div></div>";
					foreach($prop["VALUES"] as $value) {
						$value["NAME"] = htmlspecialcharsbx($value["NAME"]);
						if(strripos($value["PICT"]["SRC"], "no_photo") && !empty($value["CODE"]))
							$backgroundSCU = "background-color: #".$value["CODE"].";";
						else
							$backgroundSCU = "background-image:url(\'".$value["PICT"]["SRC"]."\');";

						$skuTemplate[$itemId][$propId]["ITEMS"][$value["ID"]] = "<li class='bx_scu_list_pict' data-treevalue='".$propId."_".$value["ID"]."' data-onevalue='".$value["ID"]."' title='".$value["NAME"]."' style='".$backgroundSCU."'></li>";
					}
					unset($value);
				}
			}
		}
		unset($templateRow, $prop);
	}?>
		<div id="alertMessage" class="col-xs-12"></div>
		<div class="infinite-items list-type-list ex-app-subscribe">
			<div class="row bx_subscribe_section" style="flex-direction:column;align-items:stretch;margin-top:190px;">
				<?foreach($arResult["ITEMS"] as $key => $arItem) {
					$strMainID = $this->GetEditAreaId($arItem["ID"]);
					$arItemIDs = array(
						"ID" => $strMainID,
						"PICT" => $strMainID . "_pict",
						"PICT" => $strMainID . "_pict",
						"SUBSCRIBE_DELETE_LINK" => $strMainID . "_delete_subscribe",
						"PROP_DIV" => $strMainID . "_sku_tree",
						"PROP" => $strMainID . "_prop_"
					);
					$strObName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

					$strTitle = isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && "" != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"];?>
                    <div class="basket-item card product-card infinite-item" id="<?=$strMainID;?>" style="">
                        <div class="product-avatar">
                            <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" style="max-width: 150px; max-height: 150px;" alt="<?=$strTitle;?>" title="<?=$strTitle;?>">
                        </div>
                        <div class="product-content">
                            <div class="product-name">
                                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=$arItem['NAME']?>"><?=$arItem["NAME"]?></a>
                            </div>
                        </div>
                        <?//SIMPLE_PRODUCT//
                        if(!isset($arItem["OFFERS"]) || empty($arItem["OFFERS"])) {?>
                            <div class="card-subscribe-footer">
                                <div class="price-item-cart">
                                    <div class="base-price"><?=$arItem['PRICE_BY_SUBSCRIBE']['RESULT_PRICE']['BASE_PRICE']?> руб<span>/шт</span></div>
                                </div>
                                <a id="<?=$arItemIDs['SUBSCRIBE_DELETE_LINK']?>" class="delete-subscribe catalog-button" href="javascript:void(0)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="white" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>
                                    <span><?=Loc::getMessage("CPSL_TPL_MESS_BTN_UNSUBSCRIBE")?></span>
                                </a>
                                <a class="select-item-sub catalog-button" href="<?=$arItem['DETAIL_PAGE_URL']?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="white" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg>
                                </a>
                            </div>
                            <?$arJSParams = array(
                                "PRODUCT_TYPE" => $arItem["CATALOG_TYPE"],
                                "SHOW_ABSENT" => true,
                                "PRODUCT" => array(
                                    "ID" => $arItem["ID"],
                                    "NAME" => $arItem["~NAME"],
                                    "PICT" => $arItem["PREVIEW_PICTURE"],
                                    "LIST_SUBSCRIBE_ID" => $arParams["LIST_SUBSCRIPTIONS"],
                                ),
                                "VISUAL" => array(
                                    "ID" => $arItemIDs["ID"],
                                    "PICT_ID" => $arItemIDs["PICT"],
                                    "DELETE_SUBSCRIBE_ID" => $arItemIDs["SUBSCRIBE_DELETE_LINK"],
                                )
                            );?>
                            <script type="text/javascript">
                                var <?=$strObName;?> = new JCCatalogProductSubscribeList(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
                            </script>
                        <?} else {
                            if(!empty($arItem["OFFERS"]) && isset($skuTemplate[$arItem["ID"]])) {
                                $arSkuProps = array();?>
                                <div class="bx_subscribe_item_scu_container">
                                    <div class="bx_subscribe_item_scu_block">
                                        <div id="<?=$arItemIDs['PROP_DIV']?>">
                                            <?foreach($skuTemplate[$arItem["ID"]] as $propId => $propTemplate) {
                                                $rowTemplate = $propTemplate["FULL"];
                                                echo "<div class='bx_subscribe_item_scu_hidden'>", str_replace("#ITEM#_prop_", $arItemIDs["PROP"], $rowTemplate["START"]);
                                                foreach($propTemplate["ITEMS"] as $value => $valueItem) {
                                                    echo str_replace("#ITEM#_prop_", $arItemIDs["PROP"], $valueItem);
                                                }
                                                echo str_replace("#ITEM#_prop_", $arItemIDs["PROP"], $rowTemplate["FINISH"]), "</div>";
                                                unset($value, $valueItem, $rowTemplate);
                                            }
                                            unset($propTemplate);
                                            foreach($arResult["SKU_PROPS"][$arItem["ID"]] as $arOneProp) {
                                                $arSkuProps[] = array(
                                                    "ID" => $arOneProp["ID"],
                                                    "SHOW_MODE" => $arOneProp["SHOW_MODE"],
                                                    "VALUES_COUNT" => $arOneProp["VALUES_COUNT"]
                                                );
                                            }?>
                                        </div>
                                    </div>
                                </div>
                                <div class="bx_subscribe_item_controls">
                                    <div class="bx_subscribe_item_controls_block">
                                        <a id="<?=$arItemIDs['SUBSCRIBE_DELETE_LINK']?>" class="btn btn-default" href="javascript:void(0)"><i class="icon-close"></i><span class="hidden-xs hidden-sm"><?=Loc::getMessage("CPSL_TPL_MESS_BTN_UNSUBSCRIBE")?></span></a>
                                    </div>
                                    <div class="bx_subscribe_item_controls_block">
                                        <a class="btn btn-default no-pad" href="<?=$arItem['DETAIL_PAGE_URL']?>"><i class="icon-arrow-right"></i></a>
                                    </div>
                                </div>
                                <?$arJSParams = array(
                                    "PRODUCT_TYPE" => $arItem["CATALOG_TYPE"],
                                    "SHOW_ABSENT" => true,
                                    "SHOW_SKU_PROPS" => $arItem["OFFERS_PROPS_DISPLAY"],
                                    "DEFAULT_PICTURE" => array(
                                        "PICTURE" => $arItem["PRODUCT_PREVIEW"]
                                    ),
                                    "VISUAL" => array(
                                        "ID" => $arItemIDs["ID"],
                                        "PICT_ID" => $arItemIDs["PICT"],
                                        "TREE_ID" => $arItemIDs["PROP_DIV"],
                                        "TREE_ITEM_ID" => $arItemIDs["PROP"],
                                        "DELETE_SUBSCRIBE_ID" => $arItemIDs["SUBSCRIBE_DELETE_LINK"],
                                    ),
                                    "PRODUCT" => array(
                                        "ID" => $arItem["ID"],
                                        "NAME" => $arItem["~NAME"],
                                        "LIST_SUBSCRIBE_ID" => $arParams["LIST_SUBSCRIPTIONS"],
                                    ),
                                    "OFFERS" => $arItem["JS_OFFERS"],
                                    "OFFER_SELECTED" => $arItem["OFFERS_SELECTED"],
                                    "TREE_PROPS" => $arSkuProps,
                                );?>
                                <script type="text/javascript">
                                    var <?=$strObName;?> = new JCCatalogProductSubscribeList(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
                                </script>
                            <?}
                        }?>
                    </div>
				<?}?>
			</div>
		</div>
<?} else {
	if(isset($arParams["GUEST_ACCESS"]))
		ShowNote(Loc::getMessage("CPSL_SUBSCRIBE_NOT_FOUND"), "warning");
}?>