<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$this->setFrameMode(true);

if($arResult["FILE"] <> ""):
	if(filesize($arResult["FILE"]) > 0):?>
		<div class="mix-container-form-pd">
			<?include($arResult["FILE"]);?>
		</div>
	<?endif;
endif;?>