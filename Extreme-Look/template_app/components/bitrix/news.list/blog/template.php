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
                <a class="promotions-item<?=($itemCompleted ? ' promotions-item-completed' : '')?>" title="<?=$item['NAME']?>" href="/?page=blog/detail&BLOG_ID=<?=$item['ID']?>&extreme-mobile=Y">
                    <span class="promotions-item-pic">
                        <?if(is_array($item["PREVIEW_PICTURE"])) {?>
                            <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" width="<?=$item['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$item['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$item['NAME']?>" />
                        <?}?>
                    </span>
                    <span class="promotions-item-block-container">
                        <span class="promotions-item-block">
                            <span class="promotions-item-title"><?=$item['NAME']?></span>
                        </span>
                    </span>
                </a>
            </div>
        <?}?>
    </div>
<?}else{?>
    <div class="app-promotions-main-block">
        <div class="app-promotion-prev-text">Нет записей в блоге</div>
    </div>
<?}?>