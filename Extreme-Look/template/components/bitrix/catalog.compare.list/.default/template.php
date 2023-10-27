<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$itemCount = count($arResult);
$needReload = isset($_REQUEST["compare_list_reload"]) && $_REQUEST["compare_list_reload"] == "Y";
$idCompareCount = "compareList".$this->randString();
$obCompare = "ob".$idCompareCount;
$idCompareAll = $idCompareCount."_count";

$strIds = "";
$arCompare = $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"];
if(!empty($arCompare)) {
	$strIds = "?ids=".implode("%2B", array_keys($arCompare));
}
unset($arCompare);?>

<div class="top-panel__compare-block" id="<?=$idCompareCount?>">
	<?if($needReload)
		$APPLICATION->RestartBuffer();?>
	<a class="top-panel__compare-link" href="<?=$arParams['COMPARE_URL'].(strlen($strIds) > 0 ? $strIds : '')?>" title="<?=GetMessage('CP_BCCL_TPL_MESS_COMPARE')?>">
		<i class="icon-compare"></i>
		<?$frame = $this->createFrame($idCompareCount)->begin("");?>
		<span class="top-panel__compare-count" id="<?=$idCompareAll?>"><?=$itemCount?></span>
		<?$frame->end();?>
	</a>
	<?if($needReload)
		die();?>
</div>

<?$jsParams = array(
	"VISUAL" => array(
		"ID" => $idCompareCount,
	),
	"AJAX" => array(
		"url" => $APPLICATION->GetCurPage(),		
		"reload" => array(
			"compare_list_reload" => "Y"
		)
	)
);?>

<script type="text/javascript">
	var <?=$obCompare?> = new JCCatalogCompareList(<?=CUtil::PhpToJSObject($jsParams, false, true)?>)
</script>