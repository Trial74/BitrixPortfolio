<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/components/bitrix/news.detail/live/js/classie.js");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/components/bitrix/news.detail/live/js/modernizr.custom.js");

global $USER;
$USER_AUTH = $USER->IsAuthorized();
$liveProducts = 'N';
$liked = false;
$jsParams = array();

if(!empty($arResult["PROPERTIES"]['LIVE_PRODUCTS']['VALUE'])){
    $liveProducts = 'Y';
    global $liveProdutsFilter;
    $liveProdutsFilter['=ID'] = $arResult["PROPERTIES"]['LIVE_PRODUCTS']['VALUE'];
}

$mainId = $this->GetEditAreaId($arResult['ID']);
$blockIDS = array(
    'COVER_BLOCK_FRAME' => 'coverblock_' . $mainId,
    'VIDEO_BLOCK_ID' => 'vblock_' . $mainId,
    'LIKE_BLOCK_ID' => 'lblock_' . $mainId,
    'LIKE_INFO_BLOCK' => 'linfblock_' . $mainId,
    'REPOST_BLOCK_ID' => 'rblock_' . $mainId,
    'SHARE_BLOCK_ID' => 'shareblock_' . $mainId,
    'SHARE_CONTENT_BLOCK_ID' => 'shcontent_' . $mainId,
    'COMMENT_ID' => 'cblock_' . $mainId,
    'ADD_COMMENT' => 'combutton_' . $mainId,
    'FORM_COMMENT' => 'formcomment_' . $mainId,
    'LIKE_ERROR' => 'l_error_' . $mainId,
    'RESULT_COMMENT_BLOCK' => 'res_comment_' . $mainId,
    'COMMENT_BUTTON_ID' => 'b_comment_' . $mainId,
    'COUNT_LIKES_BLOCK' => 'count_likes_' . $mainId
);

if(!empty($arResult["PROPERTIES"]['LIVE_USERS_VIEWED']['VALUE']) && $USER_AUTH){
    $jsParams['US_VIEWED'] = in_array($USER->GetID(), $arResult["PROPERTIES"]['LIVE_USERS_VIEWED']['VALUE']) ? 'Y' : 'N';
}
if(!empty($arResult["PROPERTIES"]['LIVE_USERS_LIKED']['VALUE']) && $USER_AUTH){
    $liked = in_array($USER->GetID(), $arResult["PROPERTIES"]['LIVE_USERS_LIKED']['VALUE']);
}
if(!$USER_AUTH){
    include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
    $cpt = new CCaptcha();
    $captchaPass = COption::GetOptionString("main", "captcha_password", "");
    if(strlen($captchaPass) <= 0){
        $captchaPass = randString(10);
        COption::SetOptionString("main", "captcha_password", $captchaPass);
    }
    $cpt->SetCodeCrypt($captchaPass);
}

$jsParams['IDS'] = $blockIDS;
$jsParams['ID_ITEM'] = $arResult['ID'];
$jsParams['IBLOCK'] = $arParams['IBLOCK_ID'];
$jsParams['IBLOCK_COMMENT'] = 122;
$jsParams['ID_VIDEO'] = !empty($arResult["PROPERTIES"]['LIVE_ID_VIDEO']['VALUE']) ? $arResult["PROPERTIES"]['LIVE_ID_VIDEO']['VALUE'] : 'N';
$jsParams['TITLE'] = $arResult['NAME'];
$jsParams['DESCRIPTION'] = $arResult['PREVIEW_TEXT'];
$jsParams['IMG'] = !empty($arResult["PREVIEW_PICTURE"]) ? $arResult['PREVIEW_PICTURE']['SRC'] : false;
$jsParams['MOBILE'] = MOBILE ? 'Y' : 'N';
$jsParams['ajaxURL'] = SITE_TEMPLATE_PATH . '/components/bitrix/news.detail/live/ajax.php';
$jsParams['USER'] = $USER_AUTH ? $USER->GetByID($USER->GetID())->fetch() : 'N';
$jsParams['COUNT_VIEW'] = $arResult['PROPERTIES']['LIVE_COUNT']['VALUE'];

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);?>

<div class="container-lg p-0">
    <div class="container-lg live-news-item-container">
        <div class="row w-100 m-0">
            <?if(MOBILE){?>
                <div class="col-12 p-0 text-center live-prev-container position-relative" id="<?=$blockIDS['VIDEO_BLOCK_ID']?>">
                    <?if(!empty($arResult["PREVIEW_PICTURE"])){?>
                        <img src="<?=$arResult['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arResult['NAME']?>" />
                        <div class="d-flex gap-3 flex-column position-absolute p-5 bottom-0 start-0">
                            <div class="live-detail-name-mobile"><?=$arResult['NAME']?></div>
                            <div class="d-flex align-items-center live-count-detail-video-mobile"><?=$arResult['PROPERTIES']['LIVE_COUNT']['VALUE']?></div>
                        </div>
                    <?}?>
                </div>
            <?}?>
            <div class="col-lg-4 row col-12 m-0 m-lg-0 ps-0 pe-0 ps-lg-5 pe-lg-5">
                <div class="col-12 pe-0 ps-0">
                    <div class="d-flex gap-4 flex-column justify-content-evenly w-100 w-lg-75 m-auto h-100">
                        <div class="d-flex align-items-center live-count-detail-video d-none d-lg-block"><?=$arResult['PROPERTIES']['LIVE_COUNT']['VALUE'] . ' ' . numberS($arResult['PROPERTIES']['LIVE_COUNT']['VALUE'], array('просмотр', 'просмотра', 'просмотров'))?></div>
                        <div class="live-detail-name d-none d-lg-block"><?=$arResult['NAME']?></div>
                        <div class="live-detail-prev-text d-none d-lg-block"><?=$arResult['PREVIEW_TEXT']?></div>
                        <div class="row h-lg-25 h-100 mt-5 mb-5 mt-lg-0 mb-lg-0 d-flex align-items-end justify-content-center">
                            <div class="col-12 p-0 m-0 row m-lg-auto w-75">
                                <div class="col-4 p-0 live-like-share text-end pe-2 position-relative" id="<?=$blockIDS['LIKE_BLOCK_ID']?>">
                                    <div class="<?=$liked ? 'live-liked' : ''?>" id="<?=$blockIDS['LIKE_INFO_BLOCK']?>"></div>
                                    <div id="<?=$blockIDS['LIKE_ERROR']?>" class="live-error-likes position-absolute"></div>
                                    <div id="like" class="live_like cbutton cbutton--effect-stana">
                                        <span class="cbutton__helper">
                                            <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
                                                <clipPath id="clip_shape_1">
                                                    <path class="clip-ring" d="M50,24.188c-14.255,0-25.812,11.557-25.812,25.812 c0,14.255,11.557,25.812,25.812,25.812c14.255 0,25.812-11.558,25.812-25.812C75.812,35.745,64.255,24.188,50,24.188z M50,64.75 c-8.146,0-14.749-6.604-14.749-14.75c0-8.146,6.603-14.749,14.749-14.749c8.146,0,14.75,6.603,14.75,14.749 C64.75,58.146,58.146,64.75,50,64.75z"/>
                                                </clipPath>
                                                <g clip-path="url(#clip_shape_1)">
                                                    <line x1="50" y1="89.75" x2="50" y2="75.75"/>
                                                    <line x1="84.844" y1="70.132" x2="72.191" y2="62.828"/>
                                                    <line x1="84.844" y1="29.956" x2="72.193" y2="37.259"/>
                                                    <line x1="50" y1="9.25" x2="50" y2="24.25"/>
                                                    <line x1="15.243" y1="70.132" x2="27.894" y2="62.829"/>
                                                    <line x1="15.243" y1="29.956" x2="27.893" y2="37.258"/>
                                                </g>
                                            </svg>
                                        </span>
                                        <div id="<?=$blockIDS['COUNT_LIKES_BLOCK']?>"><?=$arResult['PROPERTIES']['LIVE_LIKES']['VALUE']?></div>
                                    </div>
                                </div>
                                <div class="col-4 p-0 live-like-share<?=MOBILE ? '' : ' position-relative'?>">
                                    <div class="navigation-share-content position-absolute start-0 d-none<?=MOBILE ? ' bottom-0 flex-column' : ' top-0'?>" id="<?=$blockIDS['SHARE_BLOCK_ID']?>" style="right:unset;">
                                        <div class="navigation-share-content-title"><?=GetMessage("ENEXT_SHARE")?></div>
                                        <div class="navigation-share-content-block" id="<?=$blockIDS['SHARE_CONTENT_BLOCK_ID']?>"></div>
                                    </div>
                                    <div id="<?=$blockIDS['REPOST_BLOCK_ID']?>" class="live_undo"></div>
                                </div>
                                <div class="col-4 p-0 live-comment-block">
                                    <div class="live_comment" id="<?=$blockIDS['COMMENT_BUTTON_ID']?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?if(!MOBILE){?>
                <div class="col-4 text-center live-prev-container position-relative" id="<?=$blockIDS['VIDEO_BLOCK_ID']?>">
                    <?if(!empty($arResult["PREVIEW_PICTURE"])){?>
                        <img width="340px" src="<?=$arResult['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arResult['NAME']?>" />
                        <div class="live-icon-prev position-absolute m-auto" id="full-screen"></div>
                    <?}?>
                </div>
            <?}?>
            <?if($liveProducts === 'Y'){?>
                <div class="col-lg-4 col-12 p-0 p-lg-5 live-block-splide">
                    <section class="splide d-flex flex-column justify-content-evenly h-100">
                        <div class="live-products-block">Товары с трансляции</div>
                        <div class="splide__track">
                            <ul class="splide__list">
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:catalog.section",
                                    "live",
                                    array(
                                        "COMPONENT_TEMPLATE" => "live",
                                        "IBLOCK_TYPE" => "1c_catalog",
                                        "IBLOCK_ID" => "23",
                                        "SECTION_ID" => "",
                                        "SECTION_CODE" => "",
                                        "SECTION_USER_FIELDS" => array(
                                            0 => "",
                                            1 => "",
                                        ),
                                        "FILTER_NAME" => "liveProdutsFilter",
                                        "INCLUDE_SUBSECTIONS" => "Y",
                                        "SHOW_ALL_WO_SECTION" => "Y",
                                        "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
                                        "HIDE_NOT_AVAILABLE" => "N",
                                        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                                        "ELEMENT_SORT_FIELD" => "sort",
                                        "ELEMENT_SORT_ORDER" => "asc",
                                        "ELEMENT_SORT_FIELD2" => "id",
                                        "ELEMENT_SORT_ORDER2" => "desc",
                                        "OFFERS_SORT_FIELD" => "sort",
                                        "OFFERS_SORT_ORDER" => "asc",
                                        "OFFERS_SORT_FIELD2" => "id",
                                        "OFFERS_SORT_ORDER2" => "desc",
                                        "PAGE_ELEMENT_COUNT" => "8",
                                        "LINE_ELEMENT_COUNT" => "",
                                        "PROPERTY_CODE" => array(
                                            0 => "",
                                            1 => "",
                                        ),
                                        "OFFERS_FIELD_CODE" => array(
                                            0 => "",
                                            1 => "",
                                        ),
                                        "OFFERS_PROPERTY_CODE" => array(
                                            0 => "IZGIB_3",
                                            1 => "DIAMETR_5",
                                            2 => "DLINA_10",
                                            3 => "OBYEM_1",
                                            4 => "",
                                        ),
                                        "OFFERS_LIMIT" => "0",
                                        "BACKGROUND_IMAGE" => "-",
                                        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
                                        "PRODUCT_DISPLAY_MODE" => "N",
                                        "OFFER_TREE_PROPS" => array(
                                            0 => "IZGIB_3",
                                            1 => "DIAMETR_5",
                                            2 => "DLINA_10",
                                            3 => "OBYEM_1",
                                        ),
                                        "PRODUCT_SUBSCRIPTION" => "Y",
                                        "SHOW_DISCOUNT_PERCENT" => "Y",
                                        "SHOW_OLD_PRICE" => "Y",
                                        "SHOW_MAX_QUANTITY" => "M",
                                        "MESS_SHOW_MAX_QUANTITY" => "",
                                        "RELATIVE_QUANTITY_FACTOR" => "5",
                                        "MESS_RELATIVE_QUANTITY_MANY" => "много",
                                        "MESS_RELATIVE_QUANTITY_FEW" => "мало",
                                        "MESS_BTN_BUY" => "Купить",
                                        "MESS_BTN_ADD_TO_BASKET" => "Купить",
                                        "MESS_BTN_SUBSCRIBE" => "Сообщить о поступлении",
                                        "MESS_BTN_DETAIL" => "Подробнее",
                                        "MESS_NOT_AVAILABLE" => "Нет в наличии",
                                        "RCM_TYPE" => "personal",
                                        "RCM_PROD_ID" => "",
                                        "SHOW_FROM_SECTION" => "Y",
                                        "SECTION_URL" => "/catalog/#SECTION_CODE#/",
                                        "DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
                                        "SECTION_ID_VARIABLE" => "SECTION_ID",
                                        "SEF_MODE" => "N",
                                        "AJAX_MODE" => "N",
                                        "AJAX_OPTION_JUMP" => "N",
                                        "AJAX_OPTION_STYLE" => "Y",
                                        "AJAX_OPTION_HISTORY" => "N",
                                        "AJAX_OPTION_ADDITIONAL" => "",
                                        "CACHE_TYPE" => "A",
                                        "CACHE_TIME" => "36000000",
                                        "CACHE_GROUPS" => "N",
                                        "SET_TITLE" => "N",
                                        "SET_BROWSER_TITLE" => "N",
                                        "BROWSER_TITLE" => "-",
                                        "SET_META_KEYWORDS" => "N",
                                        "META_KEYWORDS" => "-",
                                        "SET_META_DESCRIPTION" => "N",
                                        "META_DESCRIPTION" => "-",
                                        "SET_LAST_MODIFIED" => "N",
                                        "USE_MAIN_ELEMENT_SECTION" => "N",
                                        "ADD_SECTIONS_CHAIN" => "N",
                                        "CACHE_FILTER" => "Y",
                                        "USE_REVIEW" => "Y",
                                        "REVIEWS_IBLOCK_TYPE" => "reviews",
                                        "REVIEWS_IBLOCK_ID" => "70",
                                        "ACTION_VARIABLE" => "action",
                                        "PRODUCT_ID_VARIABLE" => "id",
                                        "CUSTOM_CURRENT_PAGE" => "/catalog/",
                                        "PRICE_CODE" => array(
                                            0 => "Розница",
                                            1 => "Партнер",
                                            2 => "Золотой партнер",
                                            3 => "Платиновый партнер",
                                        ),
                                        "USE_PRICE_COUNT" => "Y",
                                        "SHOW_PRICE_COUNT" => "1",
                                        "PRICE_VAT_INCLUDE" => "Y",
                                        "CONVERT_CURRENCY" => "N",
                                        "BASKET_URL" => "/personal/cart/",
                                        "USE_PRODUCT_QUANTITY" => "Y",
                                        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                        "ADD_PROPERTIES_TO_BASKET" => "Y",
                                        "PRODUCT_PROPS_VARIABLE" => "prop",
                                        "PARTIAL_PRODUCT_PROPERTIES" => "Y",
                                        "PRODUCT_PROPERTIES" => array(
                                        ),
                                        "OFFERS_CART_PROPERTIES" => array(
                                        ),
                                        "ADD_TO_BASKET_ACTION" => "ADD",
                                        "DISPLAY_COMPARE" => "N",
                                        "COMPARE_PATH" => "/catalog/compare/",
                                        "MESS_BTN_COMPARE" => "Добавить к сравнению",
                                        "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
                                        "USE_ENHANCED_ECOMMERCE" => "N",
                                        "PAGER_TEMPLATE" => "arrows",
                                        "DISPLAY_TOP_PAGER" => "N",
                                        "DISPLAY_BOTTOM_PAGER" => "N",
                                        "PAGER_TITLE" => "Товары",
                                        "PAGER_SHOW_ALWAYS" => "N",
                                        "PAGER_DESC_NUMBERING" => "N",
                                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                        "PAGER_SHOW_ALL" => "N",
                                        "PAGER_BASE_LINK_ENABLE" => "N",
                                        "LAZY_LOAD" => "Y",
                                        "LOAD_ON_SCROLL" => "N",
                                        "SET_STATUS_404" => "N",
                                        "SHOW_404" => "N",
                                        "MESSAGE_404" => "",
                                        "COMPATIBLE_MODE" => "N",
                                        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                                        "MESS_BTN_LAZY_LOAD" => "Показать ещё",
                                        "EX_MESS_RELATIVE_QUANTITY_VERY_FEW" => "1",
                                        "EX_MESS_RELATIVE_QUANTITY_FEW" => "6",
                                        "EX_MESS_RELATIVE_QUANTITY_ENOUGH" => "21",
                                        "EX_MESS_RELATIVE_QUANTITY_MANY" => "51",
                                        "EX_MESS_RELATIVE_QUANTITY_VERY_MANY" => "101",
                                        "REVIEWS_NEWS_COUNT" => "5",
                                        "REVIEWS_SORT_BY1" => "sort",
                                        "REVIEWS_SORT_ORDER1" => "asc",
                                        "REVIEWS_SORT_BY2" => "active_from",
                                        "REVIEWS_SORT_ORDER2" => "desc",
                                        "REVIEWS_ACTIVE_DATE_FORMAT" => "d.m.Y",
                                        "REVIEWS_PROPERTY_CODE" => array(
                                            0 => "",
                                            1 => "",
                                        ),
                                        "MESS_REVIEWS_TAB" => "Отзывы",
                                        "DETAIL_ADD_PICT_PROP" => "-",
                                        "DETAIL_PROPERTY_CODE" => array(
                                            0 => "",
                                            1 => "",
                                        ),
                                        "DETAIL_IMAGE_RESOLUTION" => "16by9",
                                        "DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
                                        "DETAIL_DETAIL_PICTURE_MODE" => array(
                                            0 => "POPUP",
                                            1 => "MAGNIFIER",
                                        ),
                                        "DETAIL_SHOW_SLIDER" => "N",
                                        "USE_GIFTS_DETAIL" => "Y",
                                        "USE_STORE" => "N",
                                        "SET_ITEMS_COUNT" => "3",
                                        "OBJECTS_USE_REVIEW" => "Y",
                                        "CONTACTS_IBLOCK_TYPE" => "content",
                                        "CONTACTS_IBLOCK_ID" => "",
                                        "CONTACTS_USE_REVIEW" => "Y",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "SHOW_LASH_IMAGE_OFFER" => "Y",
                                        "DETAIL_OFFER_ADD_PICT_PROP" => "-",
                                        "DETAIL_MAIN_BLOCK_PROPERTY_CODE" => array(
                                        ),
                                        "DETAIL_OFFERS_FIELD_CODE" => array(
                                            0 => "",
                                            1 => "",
                                        ),
                                        "DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => array(
                                        ),
                                        "GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "5",
                                        "GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
                                        "GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
                                        "GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
                                        "GIFTS_MESS_BTN_BUY" => "Выбрать",
                                        "OBJECTS_REVIEWS_IBLOCK_TYPE" => "content",
                                        "OBJECTS_REVIEWS_IBLOCK_ID" => "",
                                        "CONTACTS_REVIEWS_IBLOCK_TYPE" => "content",
                                        "CONTACTS_REVIEWS_IBLOCK_ID" => ""
                                    ),
                                    false
                                );?>
                            </ul>
                        </div>
                    </section>
                </div>
            <?}?>
        </div>
    </div>
    <div class="container-lg mt-5 pt-5">
        <div class="row">
            <div class="col-12 col-lg-6 p-0">
                <div class="d-flex flex-column">
                    <div class="p-2 d-flex flex-column flex-lg-row">
                        <div class="add-comment-tittle mb-0 mb-lg-5">Оставить комментарий</div>
                        <div class="count-comments ms-0 ms-lg-5 mb-5"><?=$arResult['COUNT_COMMENTS'] . ' ' . numberS($arResult['COUNT_COMMENTS'], array('комментарий', 'комментария', 'комментариев'))?></div>
                    </div>
                    <form action="POST" class="position-relative" name="ADD_COMMENT" id="<?=$blockIDS['FORM_COMMENT']?>">
                        <div id="<?=$blockIDS['RESULT_COMMENT_BLOCK']?>" class="live-result-comment position-absolute h-100 top-0 left-0"></div>
                        <div class="p-2">
                            <div class="mb-3">
                                <input type="text" class="form-control w-50" name="LIVE_NAME_COMMENT" placeholder="Ваше имя" value="<?=$USER_AUTH ? $USER->GetFirstName() : ''?>">
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="mb-3">
                                <textarea class="form-control" name="LIVE_TEXT_COMMENT" placeholder="Текст сообщения" rows="6"></textarea>
                            </div>
                        </div>
                        <?if(!$USER_AUTH){?>
                            <div class="live-comment-capcha position-relative p-2 d-flex flex-row align-items-center gap-2">
                                <input name="CAPTCHA_CODE" value="<?=htmlspecialchars($cpt->GetCodeCrypt());?>" type="hidden">
                                <input name="LIVE_CAPTCHA" class="form-control w-25" placeholder="Введите код" type="text">
                                <img src="/bitrix/tools/captcha.php?captcha_code=<?=htmlspecialchars($cpt->GetCodeCrypt());?>">
                            </div>
                        <?}?>
                    </form>
                    <div class="p-2">
                        <button type="button" class="btn btn-primary" id="<?=$blockIDS['ADD_COMMENT']?>">Отправить комментарий</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 p-0">
                <div class="d-flex flex-column">
                    <?if(is_countable($arResult['COMMENTS']) && count($arResult['COMMENTS']) > 0){?>
                        <?foreach($arResult['COMMENTS'] as $comment){?>
                            <div class="live-comment-main-block d-flex flex-column mb-5 mt-5" id="<?=$blockIDS['COMMENT_ID'] . '_' . $comment['ID']?>">
                                <div class="d-flex flex-row align-items-center gap-2 gap-lg-5 ms-0 ms-lg-5">
                                    <div class="live-comment-user-avatar"></div>
                                    <div class="live-comment-user-name"><?=$comment['PROPERTY_LIVE_NAME_VALUE']?></div>
                                    <div class="live-comment-date-create"><?=$comment['DATE_CREATE']?></div>
                                </div>
                                <div class="live-comment-text ms-0 ms-lg-5 mt-5"><?=$comment['PROPERTY_LIVE_COMMENT_VALUE']['TEXT']?></div>
                                <div class="d-flex flex-row align-items-center gap-5 ms-0 ms-lg-5 mt-5">
                                    <div class="live-comment-button">
                                        <button type="button" class="btn btn-primary" data-id-comment="<?=$comment['ID']?>">Ответить</button>
                                    </div>
                                    <div class="live-comment-like-block position-relative d-flex flex-row align-items-center gap-2">
                                        <?if(!$USER_AUTH || $comment['LIKED']){?>
                                            <div class="position-absolute d-none live-result-comment-like"><?=$comment['LIKED'] ? 'Вы уже ставили лайк на этот комментарий' : 'Чтобы ставить лайки пожалуста <a href="/personal/private/?login=yes">авторизуйтесь</a> или <a href="/personal/private/?register=yes">зарегестрируйтесь</a> на сайте'?></div>
                                        <?}?>
                                        <div class="live-comment-like-message position-absolute"></div>
                                        <div class="live-comment-like<?=$comment['LIKED'] ? ' liked' : ''?>" data-id-comment="<?=$comment['ID']?>"></div>
                                        <div class="live-comment-count-like"> - <?=$comment['PROPERTY_LIVE_COMMENT_LIKES_VALUE'] . ' нравится'?></div>
                                    </div>
                                </div>
                            </div>
                        <?}?>
                    <?}?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var pr = <?=json_encode($liveProducts)?>;

    if(pr === 'Y')
        new Splide('.splide',{
            width: '100%',
            height: '100%',
            pagination: false,
            autoplay: false,
            lazyLoad: true,
            rewind: true,
            wheel: false,
            perPage: 2,
            gap: '15px'
        }).mount();

    var <?=$obName?> = new JCLiveItem(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>