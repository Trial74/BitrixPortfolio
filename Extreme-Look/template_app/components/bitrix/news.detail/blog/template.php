<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$currentDateTime = time() + CTimeZone::GetOffset();
$itemCompleted = false;
if(!empty($arResult["ACTIVE_TO"]) && $currentDateTime >= strtotime($arResult["ACTIVE_TO"])) {
    $itemCompleted = true;
}
?>

<div class="promotions-detail-tabs-content">

    <div class="promotions-item-detail-container" data-entity="tab-container" data-value="promotion">
        <div class="promotions-item-detail<?=(is_array($arResult['DETAIL_PICTURE']) ? ' promotions-item-detail-full' : '').($itemCompleted ? ' promotions-item-detail-completed' : '')?>">
            <?//ITEM_PIC//?>
            <div class="promotions-item-detail-pic-container">
                <div class="promotions-item-detail-pic">
                    <?if(is_array($arResult["DETAIL_PICTURE"])) {?>
                        <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" width="<?=$arResult['DETAIL_PICTURE']['WIDTH']?>" height="<?=$arResult['DETAIL_PICTURE']['HEIGHT']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>" />
                    <?}?>
                </div>
            </div>
            <div class="promotions-item-detail-icons">
                <div class="promotions-item-detail-icon">
                    <?//ITEM_MARKER//
                    if(!empty($arResult["MARKER"])) {
                        foreach($arResult["MARKER"] as $key => $arMarker) {
                            if($key <= 2) {?>
                                <div class="promotions-item-detail-marker-container">
                                    <div class="tooltip_v <?=(!empty($arMarker['FONT_SIZE']) ? ' promotions-item-detail-marker-'.$arMarker['FONT_SIZE'] : '')?>"><?=(!empty($arMarker["ICON"]) ? "<i class='".$arMarker["ICON"]."'></i>" : "")?><span class="tooltiptext_v" style="<?=(!empty($arMarker['BACKGROUND_1']) ? 'background:'.$arMarker['BACKGROUND_1'] : 'background: #7b66fe')?>"><?=$arMarker["NAME"]?></span></div>
                                </div>
                            <?} else {
                                break;
                            }
                        }
                        unset($key, $arMarker);
                    }?>
                </div>
                <div class="promotions-item-detail-icon">
                    <?//ITEM_TIMER//
                    if(!$itemCompleted) {
                        if(!empty($arResult["ACTIVE_TO"])) {
                            $jsActiveTo = CUtil::PhpToJSObject(ParseDateTime($arResult["ACTIVE_TO"], FORMAT_DATETIME), false, true);
                            if($arResult["SHOW_TIMER"] != false) {?>
                                <div class="promotions-item-detail-timer"><i class="icon-clock"></i><span data-entity="timer" data-active-to="<?=$jsActiveTo?>"></span></div>
                            <?} else {
                                $daysLeft = ceil(abs(strtotime($arResult["ACTIVE_TO"]) - $currentDateTime) / 86400);
                                if($daysLeft > 1 && $daysLeft <= 3) {?>
                                    <div class="promotions-item-detail-timer"><i class="icon-clock"></i><span><?=Loc::getMessage("PROMOTIONS_ITEM_DETAIL_DAYS_LEFT", array("#DAYS_COUNT#" => $daysLeft))?></span></div>
                                <?} elseif($daysLeft == 1) {
                                    $hoursLeft = floor((strtotime($arResult["ACTIVE_TO"]) - $currentDateTime) / 3600);
                                    if($hoursLeft >= 3) {?>
                                        <div class="promotions-item-detail-timer"><i class="icon-clock"></i><span><?=Loc::getMessage("PROMOTIONS_ITEM_DETAIL_DAY_LEFT", array("#DAYS_COUNT#" => $daysLeft))?></span></div>
                                    <?} else {?>
                                        <div class="promotions-item-detail-timer"><i class="icon-clock"></i><span data-entity="timer" data-active-to="<?=$jsActiveTo?>"></span></div>
                                    <?}
                                }
                            }
                            unset($jsActiveTo);
                        }
                    } else {?>
                        <div class="promotions-item-detail-timer"><i class="icon-clock"></i><span><?=Loc::getMessage("PROMOTIONS_ITEM_DETAIL_COMPLETED")?></span></div>
                    <?}?>
                </div>
            </div>
        </div>
        <?//ITEM_PREVIEW_TEXT//
        if(!empty($arResult["PREVIEW_TEXT"])) {?>
            <div class="promotions-item-detail-preview-text"><?=$arResult["PREVIEW_TEXT"]?></div>
        <?}?>
    </div>
    <?//DETAIL_TEXT//
    if(!empty($arResult["DETAIL_TEXT"])) {?>
        <div class="promotions-detail-detail-text" data-entity="tab-container" data-value="description"><?=$arResult["DETAIL_TEXT"]?></div>
    <?}?>
</div>