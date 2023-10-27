<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(empty($arResult))
	return;
?>
<div class="container-menu-on-slider">
    <div class="one-menu-mobile">
        <ul class="horizontal-one-menu">
            <?$previousLevel = 0;
            foreach($arResult as $arItem){
                if($arItem["TEXT"] == ' Доставки') continue;
                if($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel) {
                    echo str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));
                }
                if($arItem["IS_PARENT"]) {?>
                    <li<?=($arItem["SELECTED"] ? " class='active'" : "")?> data-entity="dropdown">
                       <a href="<?=$arItem['LINK']?>"><?=$arItem["TEXT"]?> <i class="icon-arrow-<?=($arItem['DEPTH_LEVEL'] == 1 ? 'down' : 'right');?>"></i></a>
                        <ul>
                <?} else {?>
                    <li<?=$arItem["SELECTED"] ? " class='active'" : ""?>>
                        <a href="<?=$arItem['LINK']?>"><?=$arItem["TEXT"]?></a>
                    </li>
                <?}
                $previousLevel = $arItem["DEPTH_LEVEL"];
            }
            if($previousLevel > 1) {
                echo str_repeat("</ul></li>", ($previousLevel - 1));
            }?>
        </ul>
    </div>
</div>