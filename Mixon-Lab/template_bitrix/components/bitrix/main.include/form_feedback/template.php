<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$this->setFrameMode(true);

$mainId = $this->GetEditAreaId(md5(randString(7)));
$obName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $mainId);
$itemIds = array(
    "FORM_ID" => $mainId.'_formfeed',
    "BUTTON_ID" => $mainId.'_formbutton',
);
$jsParams = array(
    "IDS" => $itemIds
);
if($arResult["FILE"] <> ""){
    if (filesize($arResult["FILE"]) > 0) {?>
        <form action="POST" id="<?=$itemIds['FORM_ID']?>" class="form-contacts">
            <?include($arResult["FILE"]);?>
        </form>
    <?}
}?>
<script>
    var <?=$obName?> = new JCFormFeedBack(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>