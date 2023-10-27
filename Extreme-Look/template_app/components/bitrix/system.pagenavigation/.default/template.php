<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arResult["sUrlPath"] = "/page-catalog.section/";
$exploded = explode('&', $arResult["NavQueryString"]);
$arResult["NavQueryString"] = '';
$excludeParams = [
	'page'
];

foreach($exploded as $KeyValue){
	$splited = explode('=', $KeyValue);
	if(in_array($splited[0], $excludeParams))
		continue;
	if(strlen($arResult["NavQueryString"]))
		$arResult["NavQueryString"] .= '|';
	$arResult["NavQueryString"] .= $splited[0] . '=' . $splited[1];
}

if(!$arResult["NavShowAlways"])
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;?>

<div class="pagination">
    <?$strNavQueryString = ($arResult["NavQueryString"] != "" ? ($arResult["NavQueryString"] . "|") : "");
    $strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] : "") . '/';
    if($arResult["bDescPageNumbering"] === true){
        $bFirst = true;
        if($arResult["NavPageNomer"] < $arResult["NavPageCount"]){
            if($arResult["bSavePage"]){?>
                <a class="page-prev link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>/"><span class="fas fa-angle-left"></span></a>
            <?}elseif($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1)){?>
                <a class="page-prev link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><span class="fas fa-angle-left"></span></a>
            <?}else{?>
                <a class="page-prev link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>/"><span class="fas fa-angle-left"></span></a>
            <?}
        }
        if($arResult["nStartPage"] < $arResult["NavPageCount"]){
        $bFirst = false;
        if($arResult["bSavePage"]){?>
            <a class="pager-first link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>/">1</a>
        <?}else?>
            <a class="pager-first link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
        <?}
        if($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)){?>
            <a class="page-dots link" href="<?=$arResult["sUrlPath"] . $strNavQueryString ?>PAGEN_<?=$arResult["NavNum"]?>=<?=intVal($arResult["nStartPage"] + ($arResult["NavPageCount"] - $arResult["nStartPage"]) / 2)?>/">...</a>
        <?}
    }
    do{
        $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
        if($arResult["nStartPage"] == $arResult["NavPageNomer"]){?>
            <a href="#" class="link disabled <?=($bFirst ? "pager-first " : "")?>page-current"><?=$NavRecordGroupPrint?></a>
        <?}elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false){?>
            <a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="link <?=($bFirst ? "pager-first" : "") ?>"><?=$NavRecordGroupPrint?></a>
        <?}else{?>
            <a href="<?=$arResult["sUrlPath"] . $strNavQueryString ?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"] ?>/" class="link <?=($bFirst ? "pager-first" : "") ?>"><?=$NavRecordGroupPrint?></a>
        <?}
            $arResult["nStartPage"]--;
            $bFirst = false;
    }while($arResult["nStartPage"] >= $arResult["nEndPage"]);
        if($arResult["NavPageNomer"] > 1){
            if($arResult["nEndPage"] > 1){
                if($arResult["nEndPage"] > 2){?>
                    <a class="page-dots link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] / 2)?>/">...</a>
                <?}?>
                <a class="link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1/"><?=$arResult["NavPageCount"]?></a>
            <?}?>
            <a class="link pager-next" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"] ?>=<?=($arResult["NavPageNomer"] - 1)?>/">Next</a>
        <?}else{
            $bFirst = true;
            if($arResult["NavPageNomer"] > 1){
                if($arResult["bSavePage"]){?>
                    <a class="link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"] - 1)?>/"><span class="fas fa-angle-left"></span></a>
                <?}else{
                    if($arResult["NavPageNomer"] > 2){?>
                        <a class="page-prev link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"] - 1)?>/"><span class="fas fa-angle-left"></span></a>
                    <?}else{?>
                        <a class="page-prev link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><span class="fas fa-angle-left"></span></a>
                    <?}
                }
                if($arResult["nStartPage"] > 1){
                    $bFirst = false;
                    if($arResult["bSavePage"]){?>
                        <a class="pager-first link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1/">1</a>
                    <?}else{?>
                        <a class="pager-first link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
                    <?}
                    if($arResult["nStartPage"] > 2){?>
                        <a class="page-dots link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nStartPage"] / 2) ?>/">...</a>
                    <?}
                }
            }
            do{
                if($arResult["nStartPage"] == $arResult["NavPageNomer"]){?>
                    <a href="#" class="link disabled <?=($bFirst ? "pager-first " : "")?>page-current"><?=$arResult["nStartPage"]?></a>
                <?}elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false){?>
                    <a href="<?=$arResult["sUrlPath"] ?><?=$strNavQueryStringFull ?>" class="link <?=($bFirst ? "pager-first" : "")?>"><?=$arResult["nStartPage"] ?></a>
                <?}else{?>
                    <a href="<?=$arResult["sUrlPath"] . $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?=$arResult["nStartPage"]?>/" class="link <?=($bFirst ? "pager-first" : "")?>"><?=$arResult["nStartPage"]?></a>
                <?}
                $arResult["nStartPage"]++;
                $bFirst = false;
            }while($arResult["nStartPage"] <= $arResult["nEndPage"]);
            if($arResult["NavPageNomer"] < $arResult["NavPageCount"]){
                if($arResult["nEndPage"] < $arResult["NavPageCount"]){
                    if($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)){?>
                        <a class="page-dots link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2)?>/">...</a>
                    <?}?>
                    <a class="link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>/"><?=$arResult["NavPageCount"]?></a>
                <?}?>
                <a class="pager-next link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>/"><span class="fas fa-angle-right"></span></a>
            <?}
        }
    if($arResult["bShowAll"]){
        if($arResult["NavShowAll"]){?>
            <a class="page-pagen link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0/">Paged</a>
        <?}else{?>
            <a class="page-all link" href="<?=$arResult["sUrlPath"] . $strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1/">All</a>
        <?}
    }?>
</div>