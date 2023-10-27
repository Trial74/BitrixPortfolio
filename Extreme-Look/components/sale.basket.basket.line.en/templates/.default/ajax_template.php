<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;

$this->IncludeLangFile('template.php');

if($arParams['SHOW_DELAY'] == 'Y') {?>
        <a class="mini-cart__delay" href="<?=$arParams['PATH_TO_BASKET'].($arParams['SHOW_BASKET'] == 'Y' ? '?delay=Y' : '')?>" title="<?=Loc::getMessage('SBBL_DELAY')?>" data-entity="delay">
            <span class="top-panel__user-graph-wrap">
                <span class="top-panel__user-graph">
                    <div class="top-panel-block-by-count">
                        <i class="extreme-li"></i>
                        <span class="mini-cart__count"><?=$arResult['DELAY']['NUM_PRODUCTS']?></span>
                    </div>
                </span>
            </span>
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
        <span class="top-panel__user-graph-wrap">
            <span class="top-panel__user-graph">
                <div class="top-panel-block-by-count">
                    <i class="extreme-sh"></i>
                    <span class="mini-cart__count"><?=$arResult['CART']['NUM_PRODUCTS']?></span>
                </div>
            </span>
        </span>
	</a>
<?}