<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
    return;

$countItem = count($arResult["ITEMS"]);?>
<div class="container-lg live-main-container">
    <div class="row gy-3 gy-lg-6">
        <?foreach($arResult["ITEMS"] as $arItem) {?>
            <div class="col-6">
                <a class="row col m-0 p-0 text-decoration-none align-content-start live-news-item-link" href="<?=$arItem['DETAIL_PAGE_URL']?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
                    <div class="live-prev-container col-12 col-lg-6 ps-0 pe-0 pe-lg-4">
                        <?if(!empty($arItem["PREVIEW_PICTURE"])) {?>
                            <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" />
                        <?}?>
                    </div>
                    <div class="container-lg live-info-container col-12 col-lg-6 pt-4 pb-0 pt-lg-5 pb-lg-5">
                        <div class="row row-cols-1 h-100">
                            <div class="col live-name ps-0 pe-0 ps-lg-5 pe-lg-5">
                                <div class="d-flex align-items-center h-100"><?=$arItem["NAME"]?></div>
                            </div>
                            <div class="col live-prev-text pt-3 pt-lg-0 ps-0 pe-0 ps-lg-5 pe-lg-5">
                                <div class="d-flex align-items-center h-100"><?=$arItem['PREVIEW_TEXT']?></div>
                            </div>
                            <div class="col live-count ps-5">
                                <div class="d-flex d-none d-lg-block align-items-center h-100 live-count-video"><?=$arItem['PROPERTIES']['LIVE_COUNT']['VALUE'] . ' ' . numberS($arItem['PROPERTIES']['LIVE_COUNT']['VALUE'], array('просмотр', 'просмотра', 'просмотров'))?></div>
                            </div>
                            <div class="col d-none d-lg-block">
                                <div class="d-flex align-items-end h-100"><img width="200px" src="/images/live/live-button.png" /></div>
                            </div>
                            <div class="col d-none d-lg-block pe-5 d-flex align-items-end">
                                <div class="row m-0 w-75 ps-5 pe-5">
                                    <div class="col-4 p-0 m-0 row">
                                        <div class="col-12 live-likes"></div>
                                        <div class="col-12 live-count"><?=$arItem['PROPERTIES']['LIVE_LIKES']['VALUE']?></div>
                                    </div>
                                    <div class="col-4 p-0 m-0 row">
                                        <div class="col-12 live-comments"></div>
                                        <div class="col-12 live-count"><?=$arItem['COUNT_COMMENTS']?></div>
                                    </div>
                                    <div class="col-4 p-0 m-0 row">
                                        <div class="col-12 live-reposts"></div>
                                        <div class="col-12 live-count"><?=$arItem['PROPERTIES']['LIVE_REPOSTS']['VALUE']?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?}?>
    </div>
    <?if($arParams["DISPLAY_BOTTOM_PAGER"]) {
        if(!empty($arResult["NAV_STRING"])) {?>
            <div class="col-xs-12">
                <?=$arResult["NAV_STRING"];?>
            </div>
        <?}
    }?>
</div>