<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

if(!$USER->IsAuthorized())
    header('Location: https://extreme-look.ru/');

$filter = Array
(
    "ID" => $USER->GetID()
);
$rsUsers = CUser::GetList(($by="ID"), ($order="desc"), $filter, array('SELECT' => array('UF_CASH_PART'), 'FIELDS' => array('ID')));
$user = $rsUsers->fetch();

$cash = !empty($user['UF_CASH_PART']) ? $user['UF_CASH_PART'] : 0;
$group_part = array_uintersect(NEW_PART, $USER->GetUserGroupArray(), "strcasecmp");
$selectCard = array_key_first($group_part);

$width = 300; //Размер выбранного элемента
$decrease = 20; //Уменьшение элементов
$offset = 150; //Коэффициент смещения карточек друг от друга
$fontSize = 90; //Размер текста с процентами для выбранного элемента

$sinus = 0; //Для динамического рассчёта элементов до выбранного и после выбранного
$zindex = 0; //Для вычисления порядка слоёв
$left = 0; //Смещение карточек
$fontSizeOffset = 0; //Смещение размера текста процентов

$resArr = array(); //Результирующий массив для вывода карточек на страницу

foreach(NEW_PART as $key => $group){
    $left = $left + $offset;
    $rsGroup = CGroup::GetByID($group, "N");
    $arGroup = $rsGroup->Fetch();
    if($selectCard > $key){
        $sinus = $width - (($selectCard - $key) * $decrease);
        $zindex = count(NEW_PART) - ($selectCard - $key);
        $fontSizeOffset = $fontSize - (($selectCard - $key) * 5);
        $resArr[] = array(
            'src' => SITE_TEMPLATE_PATH . '/images/partner/card-pink.png',
            'left' => $left,
            'zindex' => $zindex,
            'select' => false,
            'width' => $sinus,
            'procent' => $arGroup['STRING_ID'],
            'fontsize' => $fontSizeOffset
        );
    }
    else if($selectCard == $key){
        $resArr[] = array(
            'src' => SITE_TEMPLATE_PATH . '/images/partner/card-black.png',
            'left' => $left,
            'zindex' => count(NEW_PART),
            'select' => true,
            'width' => $width,
            'procent' => $arGroup['STRING_ID'],
            'fontsize' => $fontSize
        );
    }
    else if($selectCard < $key){
        $sinus = $width - (($key - $selectCard) * $decrease);
        $zindex = count(NEW_PART) - ($key - $selectCard);
        $fontSizeOffset = $fontSize - (($key - $selectCard) * 5);
        $resArr[] = array(
            'src' => SITE_TEMPLATE_PATH . '/images/partner/card-pink.png',
            'left' => $left,
            'zindex' => $zindex,
            'select' => false,
            'width' => $sinus,
            'procent' => $arGroup['STRING_ID'],
            'fontsize' => $fontSizeOffset
        );
    }
}
unset($group, $key);
?>

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
                        <li class="spsp-tab">
                            <a href="<?=$arParams['SEF_URL_TEMPLATES']['sert_page']?>" class="spsp-tab-link">
                                <span><?=Loc::getMessage("SPSP_SERT_LIST")?></span>
                            </a>
                        </li>
                    <?}?>
                    <?if(getNewPartner()){?>
                        <li class="spsp-tab active">
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
            <h2>Мой кабинет</h2>
            <div class="block-partner">
                <div class="cards_partner">
                    <?foreach($resArr as $card){?>
                        <div class="card<?=$card['select'] ? ' active' : ''?>" style="z-index:<?=$card['zindex']?>;width:<?=$card['width'] . 'px'?>;left:<?=$card['left'] . 'px'?>;<?=$card['select'] ? 'bottom:0' : ''?>">
                            <?if($card['select']){?><div class="bonus">Активных бонусов: <?=$cash?></div><?}?>
                            <img src="<?=$card['src']?>">
                            <span style="font-size:<?=$card['fontsize'] . 'px'?>;line-height:<?=$card['fontsize'] . 'px'?>"><?=$card['procent'] . '%'?></span>
                            <?if(!$card['select']){?><div class="sheet"></div><?}?>
                        </div>
                    <?}?>
                </div>
            </div>
        </div>
        <div class="spsp-main-info-block-partner">
            <div class="spsp-info-left-block">
                <div class="spsp-button-block">
                    <div class="spsp-left-button"><a target="_blank" href="https://extremelook.bitrix24.ru/online/extreme-look">Хочу увеличить скидку</a></div>
                    <div class="spsp-right-button"><a target="_blank" href="https://extremelook.bitrix24.ru/online/extreme-look">Написать менеджеру</a></div>
                </div>
                <div class="spsp-text-block">Мы создали самые выгодные условия сотрудничества<br /><br />Размер скидки обновляется 1 раз в 3 месяца<br /><br />Вы можете получить скидку на все товары до 60% следуя условиям программы<br /><br />Ассортимент Extreme Look полностью закрывает потребности мастера по наращиванию ресниц</div>
                <div class="spsp-info-footer-part">
                    <a href="https://www.instagram.com/extreme_look.ru" target="_blank"><div class="img-contact inst"></div></a>
                    <a href="https://vk.com/extreme_look" target="_blank"><div class="img-contact vk"></div></a>
                    <a href="https://www.youtube.com/user/Nitrogirll" target="_blank"><div class="img-contact you"></div></a>
                </div>
            </div>
            <div class="spsp-info-right-block">
                <h3>Условия получения скидок</h3>
                <div class="spsp-procents-block">
                    <div id='pr-16'>16%</div>
                    <div id='pr-22'>22%</div>
                    <div id='pr-31'>31%</div>
                    <div id='pr-44'>44%</div>
                    <div id='pr-50'>50%</div>
                    <div id='pr-60' class="active">60%</div>
                </div>
                <h2>Условия получения</h2>
                <div class="terms" id="spsp-terms-block">
                    <div class="spsp-info-terms" id="pr-16">
                        <div class="spsp-price-block">21 000 ₽</div>
                        <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 7 000 ₽)</div>
                    </div>
                    <div class="spsp-info-terms" id="pr-22">
                        <div class="spsp-price-block">31 000 ₽</div>
                        <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 10 400 ₽)</div>
                    </div>
                    <div class="spsp-info-terms" id="pr-31">
                        <div class="spsp-price-block">72 000 ₽</div>
                        <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 24 000 ₽)</div>
                    </div>
                    <div class="spsp-info-terms" id="pr-44">
                        <div class="spsp-price-block">148 000 ₽</div>
                        <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 49 000 ₽)<br /><br />44% = 41% скидка + 3% cashback<br /><br />3% cashback вы получаете на свой внутренний счёт.<br />Можете тратить его на закупку товаров в следующем месяце.</div>
                    </div>
                    <div class="spsp-info-terms" id="pr-50">
                        <div class="spsp-price-block">585 000 ₽</div>
                        <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 195 000 ₽)<br /><br />50% = 46% скидка + 4% cashback<br /><br />4% cashback вы получаете на свой внутренний счёт.<br />Можете тратить его на закупку товаров в следующем месяце.</div>
                    </div>
                    <div class="spsp-info-terms active" id="pr-60">
                        <div class="spsp-price-block">1 450 000 ₽</div>
                        <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 484 000 ₽)<br /><br />60% = 55% скидка + 5% cashback<br /><br />5% cashback вы получаете на свой внутренний счёт.<br />Можете тратить его на закупку товаров в следующем месяце.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?unset($resArr, $card);?>
<?//BREADCRUMBS//
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_PRIVATE"));

//TITLE//
if($arParams["SET_TITLE"] == "Y")
    $APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_PRIVATE"));