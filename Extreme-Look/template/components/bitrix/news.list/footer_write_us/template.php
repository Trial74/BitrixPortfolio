<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;

global $arSettings;

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('HEADER_CONTACTS_ITEM_DELETE_CONFIRM'));

foreach($arResult["ITEMS"] as $arItem) {
	$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], $elementEdit);
	$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], $elementDelete, $elementDeleteParams);
	
	$strMainID = $this->GetEditAreaId($arItem["ID"]);	
	$strObName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);?>

	<div class="top-panel__col_f_w">
		<a class="footer-panel__write-us-block-footer" id="<?=$strMainID?>" href="javascript:void(0)">
			<span class="top-panel__contacts-icon"><i class="extreme-wr"></i></span>
			<span class="top-panel__contacts-caption hidden_w">
				<?if(!empty($arItem["PREVIEW_TEXT"])) {?>
					<span class="top-panel__contacts-title"><?=$arItem["PREVIEW_TEXT"]?></span>
				<?}
				if(!empty($arItem["DETAIL_TEXT"])) {?>
					<span class="top-panel__contacts-descr"><?=$arItem["DETAIL_TEXT"]?></span>
				<?}?>
			</span>
		</a>
		<?$arJSParams = array(				
			"ITEM" => array(
				"ADDRESS" => $arItem["ADDRESS"],
				"TIMEZONE" => $arItem["TIMEZONE"],
				"WORKING_HOURS" => $arItem["WORKING_HOURS"],			
				"PHONE" => $arItem["PHONE"],								
				"EMAIL" => $arItem["EMAIL"],
				"SKYPE" => $arItem["SKYPE"]
			),
			"VISUAL" => array(
				"ID" => $strMainID
			)
		);?>
		<script type="text/javascript">
			BX.message({
                FOOTER_WRITE_US_TITLE: '<?=GetMessageJS("FOOTER_WRITE_US_TITLE");?>',
				HEADER_CONTACTS_ITEM_TODAY: '<?=GetMessageJS("HEADER_CONTACTS_ITEM_TODAY");?>',
				HEADER_CONTACTS_ITEM_24_HOURS: '<?=GetMessageJS("HEADER_CONTACTS_ITEM_24_HOURS");?>',
				HEADER_CONTACTS_ITEM_OFF: '<?=GetMessageJS("HEADER_CONTACTS_ITEM_OFF");?>',
				HEADER_CONTACTS_ITEM_BREAK: '<?=GetMessageJS("HEADER_CONTACTS_ITEM_BREAK");?>',
				HEADER_CONTACTS_TEMPLATE_PATH: '<?=CUtil::JSEscape($templateFolder)?>'
			});
			var <?=$strObName;?> = new JCNewsListHeaderContacts(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
		</script>
	</div>
<?}?>