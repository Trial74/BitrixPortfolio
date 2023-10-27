<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;

$this->IncludeLangFile('template.php');

if($arParams['SHOW_DELAY'] == 'Y') {?>
	<a class="mini-cart__delay" href="<?=$arParams['PATH_TO_BASKET'].($arParams['SHOW_BASKET'] == 'Y' ? '?delay=Y' : '')?>" title="<?=Loc::getMessage('SBBL_DELAY')?>" data-entity="delay">
        <svg version="1.1" class="extreme-li" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 171.5 153.2" xml:space="preserve"><path d="M157.9,13.8C149.1,4.9,137.4,0,124.9,0c-12.5,0-24.2,4.9-33,13.8l-6.2,6.3l-6.2-6.3C70.7,4.9,59,0,46.6,0c-12.5,0-24.2,4.9-33,13.8C-4.5,32.1-4.5,62,13.6,80.3l72.1,73l72.1-73C176,62,176,32.1,157.9,13.8z M149.4,71.9l-63.7,64.4L22.1,71.9c-13.6-13.7-13.6-36,0-49.7l0,0c6.5-6.6,15.2-10.3,24.5-10.3c9.2,0,17.9,3.6,24.5,10.3l14.7,14.9l14.7-14.9c6.5-6.6,15.2-10.3,24.5-10.3c9.2,0,17.9,3.6,24.5,10.3C163,35.9,163,58.2,149.4,71.9z"/></svg>
		<span class="mini-cart__count"><?=$arResult['DELAY']['NUM_PRODUCTS']?></span>
	</a>
<?}?>
<div class="top-panel__col top-panel__user">
    <?//USER//Мой код компонент персонального раздела перенесён из header.php?>
        <?$APPLICATION->IncludeComponent("altop:user.enext", ".default",
            array(
            "PATH_TO_PERSONAL" => SITE_DIR."personal/",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000"
            ),
            false
        );?>
    <?//USER_MENU//Мой код компонент персонального раздела перенесён из header.php?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
            array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR."include/user_menu.php"
            ),
            false,
            array("HIDE_ICONS" => "Y")
        );?>
</div>
<?if($arParams['SHOW_BASKET'] == 'Y') {?>
	<a class="mini-cart__cart<?=($arResult['CART']['NUM_PRODUCTS'] <= 0 ? ' empty' : '')?>" href="<?=$arParams['PATH_TO_BASKET']?>" title="<?=Loc::getMessage('SBBL_CART')?>" data-entity="cart">
        <svg version="1.1" class="icon-basket" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 152.1 156.2" xml:space="preserve"><g><path d="M146.3,34H44L34,4c-0.8-2.4-3-4-5.5-4H5.8C2.6,0,0,2.6,0,5.8c0,3.2,2.6,5.8,5.8,5.8h18.4l9.9,29.7l13.4,58.2c0.6,2.7,3,4.5,5.7,4.5h79.7c2.7,0,5.1-1.9,5.7-4.5L152,41.1c0.1-0.4,0.1-0.9,0.1-1.3C152.1,36.6,149.5,34,146.3,34z M128.2,92.5H57.8L47.1,45.7H139L128.2,92.5z"/><path d="M64.6,111.5c-12.3,0-22.3,10-22.3,22.3c0,12.3,10,22.3,22.3,22.3c12.3,0,22.3-10,22.3-22.3C86.9,121.5,76.9,111.5,64.6,111.5z M75.3,133.8c0,5.9-4.8,10.7-10.7,10.7c-5.9,0-10.7-4.8-10.7-10.7c0-5.9,4.8-10.7,10.7-10.7C70.5,123.2,75.3,127.9,75.3,133.8C75.3,133.8,75.3,133.8,75.3,133.8z"/><path d="M121.5,111.5c-12.3,0-22.3,10-22.3,22.3c0,12.3,10,22.3,22.3,22.3c12.3,0,22.3-10,22.3-22.3C143.8,121.5,133.8,111.5,121.5,111.5z M132.1,133.8c0,5.9-4.8,10.7-10.7,10.7c-5.9,0-10.7-4.8-10.7-10.7c0-5.9,4.8-10.7,10.7-10.7C127.4,123.2,132.1,127.9,132.1,133.8C132.1,133.8,132.1,133.8,132.1,133.8z"/></g></svg>
		<span class="mini-cart__count"><?=$arResult['CART']['NUM_PRODUCTS']?></span>
	</a>
<?}