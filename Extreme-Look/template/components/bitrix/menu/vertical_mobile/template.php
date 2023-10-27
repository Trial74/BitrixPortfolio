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

	<div class="top-panel__col_f_c">
		<a class="top-panel__contacts-block-footer" id="<?=$strMainID?>" href="javascript:void(0)">
			<span class="top-panel__contacts-icon"><i class="extreme-ph"></i></span>
			<span class="top-panel__contacts-caption hidden_c">
				<?if(!empty($arItem["PREVIEW_TEXT"])) {?>
					<span class="top-panel__contacts-title"><?=$arItem["PREVIEW_TEXT"]?></span>
				<?}
				if(!empty($arItem["DETAIL_TEXT"])) {?>
					<span class="top-panel__contacts-descr"><?=$arItem["DETAIL_TEXT"]?></span>
				<?}?>
			</span>
		</a>
	</div>
<?}?>