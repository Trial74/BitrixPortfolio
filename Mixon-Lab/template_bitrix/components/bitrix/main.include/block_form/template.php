<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$this->setFrameMode(true);

if($arResult["FILE"] <> "" && !empty($arParams['FORM']) && $arParams['FORM'] != ""):
	if(filesize($arResult["FILE"]) > 0):?>
        <form action="POST" class="form-private-design<?=$arParams['ACTIVE'] == 'Y' ? ' active' : ''?>" id="mix-form-private-design<?=$arParams['FORM']?>">
			<?include($arResult["FILE"]);?>
		</form>
	<?endif;
endif;?>