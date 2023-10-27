<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
    bxMyFunctions();
	CModule::IncludeModule("iblock");
    global $USER;
	$sections = [];
	$sectionName = '';
	$sectionId = false;
    $user = $USER->IsAuthorized();
    if($user)
        $arGroups = $USER->GetUserGroupArray();
	if( isset($_GET['section-id']) )
		$sectionId = $_GET['section-id'];

	if($sectionId !== false){
		$res = CIBlockSection::GetList(['SORT' => 'ASC'], ["IBLOCK_ID" => CATALOG_IBLOCK, "=ID" => $sectionId] ,false, ["ID", "IBLOCK_ID", "NAME","UF_SECTION_HIDE","UF_CLOSE_GROUP"]);

		if($sect = $res->Fetch())
			$sectionName = $sect['NAME'];
	}

	$arFilter = [
		"IBLOCK_ID" => CATALOG_IBLOCK,
		"SECTION_ID" => $sectionId,
		"INCLUDE_SUBSECTIONS" => "N",
		"ACTIVE" => "Y",
		"GLOBAL_ACTIVE" => 'Y'
	];

	$res = CIBlockSection::GetList(['SORT' => 'ASC'], $arFilter ,false, ["ID","IBLOCK_ID","IBLOCK_TYPE_ID","IBLOCK_SECTION_ID", "PICTURE","NAME","UF_SECTION_HIDE","UF_CLOSE_GROUP"]);

	while ($arSection = $res->GetNext())
		$sections[$arSection['ID']] = $arSection;

	$elements = [];
	$arSelect = ["ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_PICTURE", "DETAIL_PICTURE"];
	$arFilter = ["IBLOCK_ID" => CATALOG_IBLOCK, "SECTION_ID" => $sectionId, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"];

	$res = CIBlockElement::GetList([], $arFilter, false, ["nPageSize" => 50], $arSelect);

	while($arFields = $res->GetNext()){
		$pictId = strlen($arFields['PREVIEW_PICTURE']) ? $arFields['PREVIEW_PICTURE'] : $arFields['DETAIL_PICTURE'];
		if( !strlen($pictId) )
			$arFields['picture'] = SITE_TEMPLATE_PATH . '/images/empty.png';
		else
			$arFields['picture'] = CFile::GetPath($pictId);
		$elements[$arFields['ID']] = $arFields;
	}

foreach($sections as $key => $section) {
    if ($user) {

        //7 - открыт всем
        //8 - Закрыт для розницы
        //9 - Закрыт всем партнёрам
        //10 - Закрыт новым партнёрам
        //11 - Закрыт старым партнёрам
        //12 - Закрыт всем

        if($section[$key]['UF_CLOSE_GROUP'] && count($section[$key]['UF_CLOSE_GROUP']) > 1) {
            foreach ($section[$key]['UF_CLOSE_GROUP'] as $group) {
                switch ($group) {
                    case 7:
                        $sections[$key]['SECTION_GROUP_HIDE'] = true;
                        break;
                    case 8:
                        if (count(array_uintersect($arGroups, ROZN, "strcasecmp")) > 0) {
                            $sections[$key]['SECTION_GROUP_HIDE'] = false;
                            break;
                        } else {
                            $sections[$key]['SECTION_GROUP_HIDE'] = true;
                            break;
                        }
                        break;
                    case 9:
                        if (count(array_uintersect($arGroups, ALL_PART, "strcasecmp")) > 0) {
                            $sections[$key]['SECTION_GROUP_HIDE'] = false;
                            break;
                        } else {
                            $sections[$key]['SECTION_GROUP_HIDE'] = true;
                            break;
                        }
                        break;
                    case 10:
                        if (count(array_uintersect($arGroups, NEW_PART, "strcasecmp")) > 0) {
                            $sections[$key]['SECTION_GROUP_HIDE'] = false;
                            break;
                        } else {
                            $sections[$key]['SECTION_GROUP_HIDE'] = true;
                            break;
                        }
                        break;
                    case 11:
                        if (count(array_uintersect($arGroups, OLD_PART, "strcasecmp")) > 0) {
                            $sections[$key]['SECTION_GROUP_HIDE'] = false;
                            break;
                        } else {
                            $sections[$key]['SECTION_GROUP_HIDE'] = true;
                            break;
                        }
                        break;
                    case 12:
                        $sections[$key]['SECTION_GROUP_HIDE'] = false;
                        break;
                    default:
                        $sections[$key]['SECTION_GROUP_HIDE'] = true;
                        break;
                }
            }
        } else {
            switch ($sections[$key]['UF_CLOSE_GROUP'][0]) {
                case 7:
                    $sections[$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                case 8:
                    if (count(array_uintersect($arGroups, ROZN, "strcasecmp")) > 0)
                        $sections[$key]['SECTION_GROUP_HIDE'] = false;
                    else $sections[$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                case 9:
                    if (count(array_uintersect($arGroups, ALL_PART, "strcasecmp")) > 0)
                        $sections[$key]['SECTION_GROUP_HIDE'] = false;
                    else $sections[$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                case 10:
                    if (count(array_uintersect($arGroups, NEW_PART, "strcasecmp")) > 0)
                        $sections[$key]['SECTION_GROUP_HIDE'] = false;
                    else $sections[$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                case 11:
                    if (count(array_uintersect($arGroups, OLD_PART, "strcasecmp")) > 0)
                        $sections[$key]['SECTION_GROUP_HIDE'] = false;
                    else $sections[$key]['SECTION_GROUP_HIDE'] = true;
                    break;
                case 12:
                    $sections[$key]['SECTION_GROUP_HIDE'] = false;
                    break;
                default:
                    $sections[$key]['SECTION_GROUP_HIDE'] = true;
                    break;
            }
        }
    } else {
        $sections[$key]['SECTION_GROUP_HIDE'] = true;
    }
}
?>

<?if(count($sections) && !$sectionId && !AJAX_REQUEST){?>
    <div class="flex-container">
        <?foreach($sections as $sectId => $section){
            if(!$section['UF_SECTION_HIDE'] || !$section['SECTION_GROUP_HIDE']) continue;?>
            <div class="flex-item">
                <a href="/page-catalog.section/section-id=<?=$sectId?>/" class="catalog-section-item">
                    <span class="catalog-section-item__graph-wrapper">
                        <span class="catalog-section-item__graph">
                            <?$image = CFile::GetFileArray($section['PICTURE']);
                            $arImageFilter = [
                                ["name" => "watermark", "position" => "center", "fill"=>"repeat", "size"=>"big", "file" => $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/enext/images/watermark.png"]
                            ];
                            $image = CFile::ResizeImageGet($image, ["width" => 90, "height" => 90], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
                            if(empty($image)){
                                $image["src"] = SITE_TEMPLATE_PATH."/images/empty.png";
                            }?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/empty.png" style="max-width: 90px; max-height: 90px;" data-src="<?=$image['src']?>" class="lazy lazy-fade-in">
                        </span>
                    </span>
                    <span class="catalog-section-item__title"><?=$section['NAME']?></span>
                </a>
            </div>
        <?}?>
    </div>
<?}elseif(count($sections) && $sectionId && !AJAX_REQUEST){?>
    <div class="block-title <?if (isset($_COOKIE['new_version'])){?> text-center <?}?>">
        <?=$sectionName?>
    </div>
    <div class="list-container">
        <?foreach($sections as $sectId => $section){
            if(!$section['UF_SECTION_HIDE']) continue;?>
            <div class="list-item">
                <a href="/page-catalog.section/section-id=<?=$sectId?>/" class="catalog-section-item-list">
                    <span class="catalog-section-item__graph-wrapper-list">
                        <span class="catalog-section-item__graph">
                            <?$image = CFile::GetFileArray($section['PICTURE']);
                            $arImageFilter = [
                                ["name" => "watermark", "position" => "center", "fill"=>"repeat", "size"=>"big", "file" => $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/enext/images/watermark.png"]
                            ];
                            $image = CFile::ResizeImageGet($image, ["width" => 50, "height" => 50], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arImageFilter);
                            if(empty($image)){
                                $image["src"] = SITE_TEMPLATE_PATH."/images/empty.png";
                            }?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/empty.png" style="max-width: 50px; max-height: 50px;" data-src="<?=$image['src']?>" class="lazy lazy-fade-in">
                        </span>
                    </span>
                    <span class="catalog-section-item__title-list"><?=$section['NAME']?></span>
                </a>
            </div>
        <?}?>
    </div>
<?}?>

<?
$SORT_FIELD = 'sort';
$SORT_ORDER = 'ASC';

	if(isset($_SESSION['CATALOG_SORT'])){
		switch($_SESSION['CATALOG_SORT']){
            case 'price-default':
                $SORT_FIELD = 'name';
                $SORT_ORDER = 'ASC';
                break;
            case 'price-up':
                $SORT_FIELD = 'SCALED_PRICE_1';
                break;
			case 'price-down':
                $SORT_FIELD = 'SCALED_PRICE_1';
				$SORT_ORDER = 'DESC';
				break;
			case 'views-up':
				$SORT_FIELD = 'shows';
				break;
			case 'views-down':
				$SORT_FIELD = 'shows';
				$SORT_ORDER = 'DESC';
				break;
		}
	}
?>

<?
// выведем товары  из всех подразделов
$INCLUDE_SUBSECTIONS = "N";
if ($sectionId !== false){
	$INCLUDE_SUBSECTIONS = "Y";
}

$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	".default",
	array(
		"ELEMENT_SORT_FIELD" => $SORT_FIELD,
        "ELEMENT_SORT_ORDER" => $SORT_ORDER,
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => CATALOG_IBLOCK,
		"SECTION_ID" => $sectionId,
		"SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" =>  $INCLUDE_SUBSECTIONS,
		"SHOW_ALL_WO_SECTION" => "N",
		"HIDE_NOT_AVAILABLE" => "L",
		"PAGE_ELEMENT_COUNT" => PRODUCTS_PER_PAGE,
		"DISPLAY_TOP_PAGER" => "Y",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"CACHE_TYPE" => "N", //CACHE_TYPE A,
		"CACHE_TIME" => CACHE_TIME,
		"CACHE_GROUPS" => "Y",
		"CACHE_FILTER" => "N",
		"USE_REVIEW" => "Y",
		"REVIEWS_IBLOCK_ID" => 70,
		"PRICE_CODE" => array(
			0 => "Розница",
			1 => "Партнер",
			2 => "Золотой партнер",
			3 => "Платиновый партнер",
			4 => "Серебрянный партнер",
		),
        "PROPERTY_CODE" => array(
            0 => "CML2_TRAITS",
            1 => "SKOROST_STSEPKI",
            2 => "SOSTAV"
        ),
        "OFFER_TREE_PROPS" => array(
            0 => "IZGIB_3",
            1 => "DIAMETR_5",
            2 => "DLINA_10",
            3 => "OBYEM_1",
            4 => "KOL_VO",
            5 => "RAZMER",
            6 => "UPAKOVKA",
            7 => "TSVET"
        ),
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_PRODUCT_QUANTITY" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "10800",
		"PAGER_SHOW_ALL" => "N",
		"COMPONENT_TEMPLATE" => "section",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"FILE_404" => "",
		"TEMPLATE_THEME" => "blue",
		"PRODUCT_DISPLAY_MODE" => "N",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
        "SHOW_PHOTO_LAST_OFFER" => 'Y',
        "NO_SHOW_LASH_IMAGE_OFFER_BY_SECTION" => array(
            0 => 396
        )
	),
	false
);?>
