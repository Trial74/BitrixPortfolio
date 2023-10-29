<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$this->setFrameMode(true);

if($arResult["FILE"] <> ""){
    if (filesize($arResult["FILE"]) > 0) {?>
        <div class="container-fluid p-0 mb-5">
            <?include($arResult["FILE"]);?>
        </div>
    <?}
}?>