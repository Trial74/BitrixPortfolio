<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);?>

<div class="senter-block mix-footer-menu mix-flex">
    <?foreach ($arResult as $key => $item){?>
        <div class="mix-item-menu">
            <a class="mix-item-link" href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
        </div>
    <?}?>
</div>