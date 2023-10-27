<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if($arResult["SECTIONS_COUNT"] < 1)
	return;?>

<?if (CSite::InDir(SITE_DIR . "catalog/index.php")) {
//SECTION_PANEL//?>

<?//NAVIGATION//?>
<div class="hidden-print navigation-wrapper">
    <div class="container<?= $APPLICATION->ShowProperty('wideScreenMode') ?>">
        <div class="row">
            <div class="col-xs-12">
                <div class="navigation-content">
                    <div id="navigation" class="navigation">
                        <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "",
                            array(
                                "START_FROM" => "0",
                                "PATH" => "",
                                "SITE_ID" => "-"
                            ),
                            false,
                            array("HIDE_ICONS" => "Y")
                        ); ?>
                    </div>
                    <?//SHARE//?>
                    <div class="navigation-share">
                        <div>
                            <div class="catalog-section-filter-container"></div>
                            <div class="catalog-section-sort-container"></div>
                            <div class="navigation-share-icon" data-entity="showShare"><i
                                        class="icon-share"></i></div>
                        </div>
                        <div class="navigation-share-content" data-entity="shareContent">
                            <div class="navigation-share-content-title"><?= GetMessage("ENEXT_SHARE") ?></div>
                            <div class="navigation-share-content-block">
                                <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR . "include/footer_share.php"
                                    ),
                                    false
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?$APPLICATION->ShowViewContent("CATALOG_SECTION_PANEL");?>
<div class="content-wrapper internal">
    <div class="container<?= $APPLICATION->ShowProperty('wideScreenMode') ?>">
        <div class="row">
            <div class="col-xs-12">

<?}?>
<div class="catalog-section-list">
	<div class="row catalog-sections">
		<?foreach($arResult["SECTIONS"] as $arSection) {
		    if(!$arSection["SECTION_HIDE"] || !$arSection["SECTION_GROUP_HIDE"]) continue;

			$this->AddEditAction($arSection["ID"], $arSection["EDIT_LINK"], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT"));
			$this->AddDeleteAction($arSection["ID"], $arSection["DELETE_LINK"], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage("CT_BCSL_ELEMENT_DELETE_CONFIRM")));

			$sectionTitle = $arSection["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
				? $arSection["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
				: $arSection["NAME"];

			$imgTitle = $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"] != ""
				? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
				: $arSection["NAME"];
			
			$imgAlt = $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"] != ""
				? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
				: $arSection["NAME"];?>

			<div class="col-xs-12 col-md-3<?=($arParams['SECTION_ROW'] != 4 ? ' col-lg-2' : '')?>">
				<a<?=($arParams["ADD_SECTION_TARGET"] == "Y" ? " target='_self'" : "")?> class="catalog-section-item" id="<?=$this->GetEditAreaId($arSection['ID'])?>" href="<?=$arSection['SECTION_PAGE_URL']?>" title="<?=$sectionTitle?>">
					<?if($arParams["COUNT_ELEMENTS"] && $arSection["ELEMENT_CNT"] > 0) {?>
						<span class="catalog-section-item__count"><?=$arSection["ELEMENT_CNT"]?></span>
					<?}?>
                    <?if($arParams["HIDE_SECTION_NAME"] != "Y" && VERSION == 'desktop') {?>
                        <span class="catalog-section-item__title"><?=$arSection["NAME"]?></span>
                    <?}?>
					<span class="catalog-section-item__graph-wrapper">
						<span class="catalog-section-item__graph">
							<?if(!empty($arSection["UF_ICON"])) {?>
								<i class="<?=$arSection['UF_ICON']?>" aria-hidden="true"></i>
							<?} elseif(is_array($arSection["PICTURE"])) {?>
                                <img class="ex-spinner-img" src="/bitrix/templates/enext/images/spinners/ex-spinner.svg" />
								<img src="<?=$arSection['PICTURE']['SRC']?>" width="<?=$arSection['PICTURE']['WIDTH']?>" height="<?=$arSection['PICTURE']['HEIGHT']?>" alt="<?=$imgAlt?>" title="<?=$imgTitle?>" onload="loadImg(this)" />
							<?} else {?>
								<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo.png" width="134" height="134" alt="<?=$imgAlt?>" title="<?=$imgTitle?>" />
							<?}?>
						</span>
					</span>
					<?if($arParams["HIDE_SECTION_NAME"] != "Y" && VERSION == 'mobile') {?>
						<span class="catalog-section-item__title"><?=$arSection["NAME"]?></span>
					<?}?>
				</a>
			</div>
		<?}
		unset($arSection);?>
	</div>
</div>

<script>
    $('.ex-owl-carousel-catalog').owlCarousel({
        loop:false,
        margin:10,
        nav:true,
        navText: ['<i class="icon-arrow-left"></i>', '<i class="icon-arrow-right"></i>'],

        responsive:{
            250:{
                items:2
            },
            600:{
                items:3
            },
            960:{
                items:5
            },
            1200:{
                items:6
            }
        }
    })
</script>