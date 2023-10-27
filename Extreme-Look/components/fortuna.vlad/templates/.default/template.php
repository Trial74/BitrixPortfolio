<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if($arResult['isAuth']) return;

$APPLICATION->AddHeadScript('/bitrix/components/fortuna.vlad/templates/.default/script.js');

$this->setFrameMode(true);
$frame = $this->createFrame()->begin();
$clientVersion = VERSION == 'desktop' ? 'extremefortuna-desktop' : 'extremefortuna-mobile';?>
<div class="extremefortuna-js-container <?=$clientVersion?> <?=$arParams['F_TEMPLATE']?>" id="extremefortuna-container" style="display:none;">
    <div class="extremefortuna-js-wrapper" id="extremefortuna-wrapper">
        <div class="extremefortuna-reset extremefortuna-js-form extremefortuna-fadeIn extremefortuna-animated extremefortuna-fastest <?=$clientVersion?>" id="extremefortuna-form" style="position: relative;">
            <div class="extremefortuna-circle-container">
                <div class="deal-wheel">
                    <ul class="spinner"></ul>
                    <div class="ticker"></div>
                </div>
                <?if($arParams['F_TEMPLATE'] == 't-fortuna_winter' && VERSION !== 'mobile'){?>
                    <div class="fortuna-gift-winter">
                        <img src="/bitrix/components/altop/fortuna.vlad/templates/develop/image/fortuna_gift_winter.png" />
                    </div>
                <?}elseif($arParams['F_TEMPLATE'] == 't-fortuna_spring' && VERSION !== 'mobile'){?>
                    <div class="fortuna-gift-spring">
                        <img src="/bitrix/components/altop/fortuna.vlad/templates/develop/image/fortuna_gift_spring.png" />
                    </div>
                <?}?>
                <div class="extremefortuna-arrow">
                    <?if($arParams['F_TEMPLATE'] == 't-fortuna_winter' || $arParams['F_TEMPLATE'] == 't-fortuna_spring'){?>
                        <img src="/bitrix/components/altop/fortuna.vlad/templates/develop/image/f_winter_arrow.png" />
                    <?}else{?>
                        <svg width="85" height="56" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 74.6 57.9" style="enable-background:new 0 0 74.6 57.9;" xml:space="preserve"><style type="text/css">.st0{fill:#FFFFFF;}.st1{fill:#B6AFF9;}</style> <g> <path class="st0" d="M74.6,29c0,0-19.7,29-45.7,29c-16,0-29-13-29-29C0,13,13,0,29,0C55.5,0,74.6,29,74.6,29z"/><ellipse transform="matrix(0.9259 -0.3778 0.3778 0.9259 -8.7196 13.4601)" class="st1" cx="29.9" cy="29" rx="12.6" ry="12.6"/></g></svg>
                    <?}?>
                </div>
            </div>
            <div class="extremefortuna-main" id="ex-main">
                <div class="extremefortuna-content<?=VERSION == 'mobile' ? ' ff-mobile' : ''?>">
                    <div class="extremefortuna-close" id="extremefortuna-close">×</div>
                    <div class="extremefortuna-title"><br>Скидка или суперприз?</div>
                    <div class="extremefortuna-text">Заполните данные о себе и заберите подарок!</div>
                    <div class="extremefortuna-inputs">
                        <div class="extremefortuna-inputs-wrapper">
                            <form id="extremefortuna-form-form">
                                <input type="text" name="name" id="name_fort" class="extremefortuna-input extremefortuna-valid" placeholder="Имя">
                                <label id="name_fort-error" class="error" for="name_fort" style="display: none"></label>
                                <input type="email" name="email" id="email_fort" class="extremefortuna-input extremefortuna-valid" placeholder="Email">
                                <label id="email_fort-error" class="error" for="email_fort" style="display: none"></label>
                                <input type="text" name="phone" id="phone_fort" class="extremefortuna-input extremefortuna-valid" placeholder="Номер телефона">
                                <label id="phone_fort-error" class="error" for="phone_fort" style="display: none"></label>
                                <button type="button" name="button" id="sumbit_fortuna" class="extremefortuna-submit btn-spin" style="display: none">Крутить колесо</button>
                                <label class="extremefortuna-checkbox-block">
                                    <input type="checkbox" name="check" class="extremefortuna-checkbox extremefortuna-valid" checked value="1">
                                    <div class="extremefortuna-checkbox-check">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z"/></svg>
                                    </div>
                                    Я даю согласие на обработку моих персональных данных согласно
                                    <a target="_blank" href="https://extreme-look.ru/agreement/?id=2">политики конфиденциальности</a> компании
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="extremefortuna-thankyou" id="ex-thankyou">
                <div class="extremefortuna-content">
                    <div class="extremefortuna-close" id="extremefortuna-close">×</div>
                    <div class="extremefortuna-title"><br>Вот это везение! Поздравляем!</div>
                    <div class="extremefortuna-text">Мы отправили подарок <br>на указанную почту ❤️</div>
                    <div class="extremefortuna-inputs thank">
                        <a href="/catalog/" class="extremefortuna-submit" id="button_by_catalog">Начать покупки</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?$frame->end();?>