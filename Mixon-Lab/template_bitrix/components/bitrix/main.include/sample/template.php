<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$this->setFrameMode(true);

if($arResult["FILE"] <> ""){
    if (filesize($arResult["FILE"]) > 0) {?>
        <div class="preview">
            <?include($arResult["FILE"]);?>
        </div>
    <?}
}?>