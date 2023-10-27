<?$filter = Array
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
$activeProcent = 16;

$resArr = array(); //Результирующий массив для вывода карточек на страницу

foreach(NEW_PART as $key => $group){
    $rsGroup = CGroup::GetByID($group, "N");
    $arGroup = $rsGroup->Fetch();
    if($selectCard == $key){
        $resArr[] = array(
            'src' => SITE_TEMPLATE_PATH . '/images/partner/card-black.png',
            'select' => true,
            'procent' => $arGroup['STRING_ID'],
            'fontsize' => $fontSize
        );
        $activeProcent = $arGroup['STRING_ID'];
    }
}
unset($group, $key);
?>

<div class="block-personal-partner">
    <div class="spsp-main-profile">
        <h2>Мой кабинет</h2>
        <div class="block-partner">
            <div class="cards_partner">
                <?foreach($resArr as $card){?>
                    <?if($card['select']){?>
                        <div class="card-partner active">
                            <div class="bonus">Активных бонусов: <?=$cash?></div>
                            <img src="<?=$card['src']?>">
                            <span><?=$card['procent'] . '%'?></span>
                        </div>
                    <?}?>
                <?}?>
            </div>
        </div>
    </div>
    <div class="spsp-main-info-block-partner">
        <div class="spsp-info-left-block">
            <div class="spsp-button-block">
                <div class="spsp-left-button"><a class="open-other-link" target="_blank" href="javascript: void(0)" data-open="https://extremelook.bitrix24.ru/online/extreme-look">Хочу увеличить скидку</a></div>
                <div class="spsp-right-button"><a class="open-other-link" target="_blank" href="javascript: void(0)" data-open="https://extremelook.bitrix24.ru/online/extreme-look">Написать менеджеру</a></div>
            </div>
            <div class="spsp-text-block">Мы создали самые выгодные условия сотрудничества<br /><br />Размер скидки обновляется 1 раз в 3 месяца<br /><br />Вы можете получить скидку на все товары до 60% следуя условиям программы<br /><br />Ассортимент Extreme Look полностью закрывает потребности мастера по наращиванию ресниц</div>
            <div class="spsp-info-footer-part">
                <a class="open-other-link" href="javascript: void(0)" data-open="https://www.instagram.com/extreme_look.ru" target="_blank"><div class="img-contact inst open-other-link"></div></a>
                <a class="open-other-link" href="javascript: void(0)" data-open="https://vk.com/extreme_look" target="_blank"><div class="img-contact vk open-other-link"></div></a>
                <a class="open-other-link" href="javascript: void(0)" data-open="https://www.youtube.com/user/Nitrogirll" target="_blank"><div class="img-contact you"></div></a>
            </div>
        </div>
        <div class="spsp-info-right-block">
            <h3>Условия получения скидок</h3>
            <div class="spsp-procents-block">
                <div id='pr-16'<?=$activeProcent == 16 ? ' class="active"' : ''?>>16%</div>
                <div id='pr-22'<?=$activeProcent == 22 ? ' class="active"' : ''?>>22%</div>
                <div id='pr-31'<?=$activeProcent == 31 ? ' class="active"' : ''?>>31%</div>
                <div id='pr-44'<?=$activeProcent == 44 ? ' class="active"' : ''?>>44%</div>
                <div id='pr-50'<?=$activeProcent == 50 ? ' class="active"' : ''?>>50%</div>
                <div id='pr-60'<?=$activeProcent == 60 ? ' class="active"' : ''?>>60%</div>
            </div>
            <h2>Условия получения</h2>
            <div class="terms" id="spsp-terms-block">
                <div class="spsp-info-terms<?=$activeProcent == 16 ? ' active' : ''?>" id="pr-16">
                    <div class="spsp-price-block">21 000 ₽</div>
                    <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 7 000 ₽)</div>
                </div>
                <div class="spsp-info-terms<?=$activeProcent == 22 ? ' active' : ''?>" id="pr-22">
                    <div class="spsp-price-block">31 000 ₽</div>
                    <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 10 400 ₽)</div>
                </div>
                <div class="spsp-info-terms<?=$activeProcent == 31 ? ' active' : ''?>" id="pr-31">
                    <div class="spsp-price-block">72 000 ₽</div>
                    <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 24 000 ₽)</div>
                </div>
                <div class="spsp-info-terms<?=$activeProcent == 44 ? ' active' : ''?>" id="pr-44">
                    <div class="spsp-price-block">148 000 ₽</div>
                    <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 49 000 ₽)<br /><br />44% = 41% скидка + 3% cashback<br /><br />3% cashback вы получаете на свой внутренний счёт.<br />Можете тратить его на закупку товаров в следующем месяце.</div>
                </div>
                <div class="spsp-info-terms<?=$activeProcent == 50 ? ' active' : ''?>" id="pr-50">
                    <div class="spsp-price-block">585 000 ₽</div>
                    <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 195 000 ₽)<br /><br />50% = 46% скидка + 4% cashback<br /><br />4% cashback вы получаете на свой внутренний счёт.<br />Можете тратить его на закупку товаров в следующем месяце.</div>
                </div>
                <div class="spsp-info-terms<?=$activeProcent == 60 ? ' active' : ''?>" id="pr-60">
                    <div class="spsp-price-block">1 450 000 ₽</div>
                    <div class="spsp-text-terms">Ваша единовременная закупка или Сумма ваших закупок<br />за 3 месяца (в месяц нужно покупать на 484 000 ₽)<br /><br />60% = 55% скидка + 5% cashback<br /><br />5% cashback вы получаете на свой внутренний счёт.<br />Можете тратить его на закупку товаров в следующем месяце.</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?unset($resArr, $card);?>