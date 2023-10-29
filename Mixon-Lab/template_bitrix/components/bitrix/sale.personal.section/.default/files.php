<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\FileTablel;

global $USER;
if(!$USER->IsAuthorized()){
    return;
}
else{
    $filter = Array("GROUPS_ID" => ALL_PART, "UF_PAGE_PART" => true, "ACTIVE" => "Y", "ID" => $USER->GetID());
    $rsUser = CUser::GetList(($by = "id"), ($order = "asc"), $filter, array('SELECT' => ['ID,', 'UF_COUNTRY', 'UF_COUNTRY_URL', 'UF_SITY_PAT', 'UF_SITY_URL', 'UF_MAP_ADDRESS', 'UF_PAGE_PART', 'UF_DOP_INFO', 'UF_SERTIFICATE']));
    $user = $rsUser->fetch();
    $file = CFile::GetPath($user['UF_SERTIFICATE']);

    $arResFiles = sortArrayByResArrExtr();
}

$this->addExternalCss(SITE_TEMPLATE_PATH."/js/owlCarousel/owl.carousel.min.css");
$this->addExternalJS(SITE_TEMPLATE_PATH."/js/owlCarousel/owl.carousel.min.js");?>
    <div class="sale-personal-section-private">
        <div class="spsp-tabs-container">
            <div class="spsp-tabs-scroll">
                <ul class="spsp-tabs">
                    <li class="spsp-tab">
                        <a href="<?=$arResult['PATH_TO_PRIVATE']?>" class="spsp-tab-link">
                            <span><?=Loc::getMessage("SPSP_MAIN_PROFILE")?></span>
                        </a>
                    </li>
                    <li class="spsp-tab">
                        <a href="<?=$arResult['PATH_TO_PROFILE']?>" class="spsp-tab-link">
                            <span><?=Loc::getMessage("SPSP_PROFILE_LIST")?></span>
                        </a>
                    </li>
                    <?if(boolPartPersonalSertificate()){?>
                        <li class="spsp-tab active">
                            <a href="<?=$arParams['SEF_URL_TEMPLATES']['sert_page']?>" class="spsp-tab-link">
                                <span><?=Loc::getMessage("SPSP_SERT_LIST")?></span>
                            </a>
                        </li>
                    <?}?>
                    <?if(getNewPartner()){?>
                        <li class="spsp-tab">
                            <a href="<?=$arParams['SEF_URL_TEMPLATES']['partner']?>" class="spsp-tab-link">
                                <span><?=Loc::getMessage("SPSP_PARTNER")?></span>
                            </a>
                        </li>
                    <?}?>
                    <div class="clearfix"></div>
                </ul>
            </div>
        </div>
        <div class="spsp-main-profile">
            <div class="mb-personal-data">
                <div class="mb-title-container">
                    <div class="mb-title">
                        <div class="mb-title__icon"><i class="icon-user-ggray"></i></div>
                        <div class="mb-title__val">Сертификат партнёра</div>
                    </div>
                </div>
                <div class="mb-block-container">
                    <div class="row">
                        <div class="col-md-4 mb-personal-data-inner">
                            <?if($file){?>
                                <a href="<?=$file?>" target="_blank"><?=CFile::ShowImage($file, 300, 300, "border=0", "", false);?></a>
                            <?}else{?>
                            <div class="error-sert">
                                <span>Сертификат ещё не сгенерирован, обратитесь к Вашему менеджеру за уточнением.</span>
                            </div>
                            <?}?>
                        </div>
                        <div class="col-md-8 mb-personal-data-inner">
                            <?if($file){?>
                                <a class="btn btn-buy" href="<?=$file?>" download><span>Скачать</span></a>
                                <button id="print-srert" style="display: none" class="btn btn-buy" <?=$file ? '': 'disabled'?>><span>Распечатать</span></button>
                                <button onclick="errorSert()" class="btn btn-buy" <?=$file ? '': 'disabled'?>><span>Ошибка в сертификате</span></button>
                            <?}?>
                            <div id="block-error-sert-message" class="mess-err close">
                                <label for="error-sert-message" id="label-err-mess" class="error-mess"></label>
                                <textarea placeholder="Напишите в чём ошибка сертификата" class="form-control" id="error-sert-message" cols="30" rows="10"></textarea>
                                <button onclick="adderrorMess()" class="btn btn-buy" <?=$file ? '': 'disabled'?>><span>Отправить</span></button>
                            </div>
                            <div class="label-mess-success">
                                <label id="label-mess"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


                <div class="row">
                    <div class="col-md-12 mb-personal-data-inner">
                        <?if(isset($arResFiles) && !empty($arResFiles)){?>
                            <?foreach($arResFiles['arResult'] as $key => $arResFile){?>
                                <?if(isset($arResFile['ITEMS'])){?>
                                    <div class="spsp-main-profile">
                                        <div class="mb-personal-data">
                                            <div class="mb-title-container">
                                                <div class="mb-title">
                                                    <div class="mb-title__icon"><i class="<?=$arResFiles['iconsGroup'][$arResFile['ID_PROPERTY_SECTION']]?>"></i></div>
                                                    <div class="mb-title__val"><?=$key?></div>
                                                </div>
                                            </div>
                                            <div class="mb-block-container">
                                                <div class="row">
                                                    <div class="col-md-12 mb-personal-data-inner">
                                                        <div class="row">
                                                            <section class="grid">
                                                                <?foreach($arResFile['ITEMS'] as $keyIT => $items){?>
                                                                    <article class="grid-item">
                                                                        <div class="image">
                                                                            <img height="200px" src="<?=$items["PREVIEW_PICTURE"] ? CFile::GetPath($items["PREVIEW_PICTURE"]) : '/bitrix/templates/enext/fonts/icon_extreme/no_photo.png';?>" />
                                                                        </div>
                                                                        <div class="info">
                                                                            <h6><b><?=$items['PROPERTY_DESKR_F_VALUE']?></b></h6>
                                                                            <div class="button-wrap">
                                                                                <?if($items['PROPERTY_FILES_VALUE_YANDEX_DISK'] == 'N' || $items['PROPERTY_FILES_VALUE_GOOGLEVIVEW'] == 'Y'){?>
                                                                                    <a class="btn btn-buy" href="<?=$items['PROPERTY_FILES_VALUE'] ? CFile::GetPath($items['PROPERTY_FILES_VALUE']) : ''?>" download><i class="d-icon"></i></a>
                                                                                <?}elseif($items['PROPERTY_FILES_VALUE_YANDEX_DISK'] == 'Y' || $items['PROPERTY_FILES_VALUE_GOOGLEVIVEW'] == 'N'){?>
                                                                                    <a class="btn btn-buy" target="_blank" href="<?=$items['PROPERTY_URL_BY_YA_VALUE']?>"><i class="arrow-alt-right-icon"></i></a>
                                                                                <?}?>

                                                                                <?if($items['PROPERTY_FILES_VALUE_GOOGLEVIVEW'] == 'Y' && $items['PROPERTY_FILES_VALUE_BROWSERVIVEW'] == 'N'){?>
                                                                                    <a class="btn btn-buy" target="_blank" href="<?='https://docs.google.com/gview?url=https://extreme-look.ru' . CFile::GetPath($items['PROPERTY_FILES_VALUE']) . '&a=v'?>">
                                                                                        <i class="eye-icon"></i>
                                                                                    </a>
                                                                                <?}elseif($items['PROPERTY_FILES_VALUE_GOOGLEVIVEW'] == 'N' && $items['PROPERTY_FILES_VALUE_BROWSERVIVEW'] == 'Y'){?>
                                                                                    <a class="btn btn-buy" target="_blank" href="<?='https://extreme-look.ru' . CFile::GetPath($items['PROPERTY_FILES_VALUE'])?>">
                                                                                        <i class="eye-icon"></i>
                                                                                    </a>
                                                                                <?}?>
                                                                            </div>
                                                                        </div>
                                                                    </article>
                                                                <?}?>
                                                            </section>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?}?>
                            <?}?>
                        <?}else{?>
                            Файлы пока не добавлены
                        <?}?>
                    </div>
                </div>

    <script type="text/javascript">
        function adderrorMess() {
            if(!BX('error-sert-message').value)
                BX.adjust(BX("label-err-mess"), {text: 'Вы не ввели сообщение!'});
            else {
                var errMess = {};
                BX.adjust(BX("label-err-mess"), {text: ''});
                errMess['ID'] = <?=$USER->GetID()?>;
                errMess['value'] = BX('error-sert-message').value;
                BX.adjust(BX("label-err-mess"), {text: ''});
                BX.ajax.post(
                    '<?=$templateFolder?>/ajax/ajax.php',
                    errMess,
                    function (data) {
                        var result = JSON.parse(data);
                        BX.adjust(BX("label-mess"), {text: result.result});
                        BX('error-sert-message').value = '';
                        if(BX.hasClass(BX('block-error-sert-message'), 'close'))
                            BX.removeClass(BX('block-error-sert-message'), 'close');
                        else
                            BX.addClass(BX('block-error-sert-message'), 'close');
                    }
                );
            }
        }
        function errorSert(){
            BX.adjust(BX("label-mess"), {text: ''});
            if(BX.hasClass(BX('block-error-sert-message'), 'close'))
                BX.removeClass(BX('block-error-sert-message'), 'close');
            else
                BX.addClass(BX('block-error-sert-message'), 'close');

        }
        window.onload = function () {
            var printBut = document.getElementById('print-srert');
            printBut.onclick = function (){
                var win = window.open();
                win.document.write('<img src=<?=$file?>>');
                win.print();
                win.close();
            }
        }
    </script>

<?//BREADCRUMBS//
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_PRIVATE"));

//TITLE//
if($arParams["SET_TITLE"] == "Y")
    $APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_PRIVATE"));