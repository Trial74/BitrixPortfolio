<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

if(!empty($arResult['ITEMS'])){?>
    <div class="row promotions">
        <?foreach ($arResult['ITEMS'] as $key => $item){
            $currentDateTime = time() + CTimeZone::GetOffset();
            $itemCompleted = false;
            if(!empty($item["ACTIVE_TO"]) && $currentDateTime >= strtotime($item["ACTIVE_TO"])) {
                $itemCompleted = true;
            }?>
            <div class="col-pr-app">
                <a class="promotions-item<?=($itemCompleted ? ' promotions-item-completed' : '')?>" title="<?=$item['NAME']?>" href="/?page=promotions/detail&PROM_ID=<?=$item['ID']?>&extreme-mobile=Y">
                    <span class="promotions-item-pic">
                        <?if(is_array($item["PREVIEW_PICTURE"])) {?>
                            <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" width="<?=$item['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$item['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$item['NAME']?>" />
                        <?}?>
                    </span>
                    <span class="promotions-item-block-container">
                        <span class="promotions-item-block">
                            <span class="promotions-item-title"><?=$item['NAME']?></span>
                            <span class="promotions-item-date">
                                <?if(!$itemCompleted) {
                                    echo Loc::getMessage("PROMOTIONS_ITEM_RUNNING")." ".(!empty($item["DISPLAY_ACTIVE_TO"]) ? Loc::getMessage("PROMOTIONS_ITEM_UNTIL")." ".$item["DISPLAY_ACTIVE_TO"] : Loc::getMessage("PROMOTIONS_ITEM_ALWAYS"));
                                }else{
                                    echo Loc::getMessage("PROMOTIONS_ITEM_COMPLETED")." ".$item["DISPLAY_ACTIVE_TO"];
                                }?>
                            </span>
                        </span>
                    </span>
                    <span class="promotions-item-icons">
                        <span class="promotions-item-icon">
                            <?if(!empty($item["MARKER"])) {
                                foreach($item["MARKER"] as $keyMarker => $arMarker) {
                                    if($keyMarker <= 2) {?>
                                        <span class="promotions-item-marker-container">
                                            <span class="tooltip_v<?=(!empty($arMarker['FONT_SIZE']) ? ' promotions-item-marker-'.$arMarker['FONT_SIZE'] : '')?>"><?=(!empty($arMarker["ICON"]) ? "<i class='".$arMarker["ICON"]."'></i>" : "")?><span class="tooltiptext_v" style="<?=(!empty($arMarker['BACKGROUND_1']) ? 'background:'.$arMarker['BACKGROUND_1'] : 'background: #7b66fe')?>"><?=$arMarker["NAME"]?></span></span>
                                        </span>
                                    <?} else {
                                        break;
                                    }
                                }
                                unset($keyMarker, $arMarker);
                            }?>
                        </span>
                        <span class="promotions-item-icon">
                            <?if(!$itemCompleted) {
                                if(!empty($item["ACTIVE_TO"])) {
                                    $jsActiveTo = CUtil::PhpToJSObject(ParseDateTime($item['ACTIVE_TO'], FORMAT_DATETIME), false, true);
                                    if($item["SHOW_TIMER"] != false) {?>
                                        <span class="promotions-item-timer"><i class="icon-clock"></i><span data-entity="timer" data-active-to="<?=$jsActiveTo?>"></span></span>
                                    <?} else {
                                        $daysLeft = ceil((strtotime($item["ACTIVE_TO"]) - $currentDateTime) / 86400);
                                        if($daysLeft > 1 && $daysLeft <= 3) {?>
                                            <span class="promotions-item-timer"><i class="icon-clock"></i><span><?=Loc::getMessage("PROMOTIONS_ITEM_DAYS_LEFT", array("#DAYS_COUNT#" => $daysLeft))?></span></span>
                                        <?} elseif($daysLeft == 1) {
                                            $hoursLeft = floor((strtotime($item["ACTIVE_TO"]) - $currentDateTime) / 3600);
                                            if($hoursLeft >= 3) {?>
                                                <span class="promotions-item-timer"><i class="icon-clock"></i><span><?=Loc::getMessage("PROMOTIONS_ITEM_DAY_LEFT", array("#DAYS_COUNT#" => $daysLeft))?></span></span>
                                            <?} else {?>
                                                <span class="promotions-item-timer"><i class="icon-clock"></i><span data-entity="timer" data-active-to="<?=$jsActiveTo?>"></span></span>
                                            <?}
                                        }
                                    }
                                    unset($jsActiveTo);
                                }
                            } else {?>
                                <span class="promotions-item-timer"><i class="icon-clock"></i><span><?=Loc::getMessage("PROMOTIONS_ITEM_COMPLETED")?></span></span>
                            <?}?>
                        </span>
                    </span>
                </a>
            </div>
        <?}?>
    </div>
<?}else{?>
    <div class="app-promotions-main-block">
        <div class="app-promotion-prev-text">Активных акций и скидок нет</div>
    </div>
<?}?>