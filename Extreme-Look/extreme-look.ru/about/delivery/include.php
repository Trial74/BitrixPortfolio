<style>
    .title-opt > h1,
    .text-one,
    .text-two,
    .text-three,
    .text-info-deliveryt,
    .text-banner-one,
    .text-banner-two,
    .text-banner-one-t,
    .text-banner-one-after,
    .text-one-f,
    .head-le-tittle,
    .head-le-text,
    .text-footer-del-left,
    .footer-delivery > div{
        font-family: 'Graphik LCG';
    }
    .title-opt > h1{
        font-weight: bold;
        font-size: 60px;
    }
    .text-one{
        font-weight: 600;
        font-size: 16px;
        margin-top: 20px;
        margin-bottom: 60px;
    }
    .text-one-f{
        font-weight: 600;
        font-size: 16px;
        margin-top: 20px;
        position: relative;
        margin-left: 20px;
    }
    .text-two{
        font-weight: bold;
        font-size: 18px;
        margin-top: 40px;
    }
    .text-three{
        font-weight: 600;
        font-size: 16px;
        color: #898f92;
        margin-top: 20px;
    }
    .col-ex{
        color: #7b66fe;
    }
    .li:before{
        content: "\2022";
        color: #2F80ED;
        position: absolute;
        left: -15px;
    }
    .text-banner-one,
    .text-banner-one-t{
        font-size: 52px;
        font-weight: bold;
        color: white;
        width: -moz-fit-content;
        width: max-content;
    }
    .text-banner-one-after{
        font-weight: 600;
        background: white;
        border-radius: 20px;
        padding: 7px 25px 7px 25px;
        width: -moz-fit-content;
        width: max-content;
        box-shadow: 5px 5px 9px rgba(0,0,0,0.5);
        margin-top: 30px;
    }
    .text-banner-two{
        margin-top: 40px;
        font-weight: 400;
        font-size: 12px;
        color: white;
        width: -moz-fit-content;
    }
    .block-banner-one{
        background: #7a66fd;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
        border-radius: 10px;
        width: 100%;
        margin-left: 15px;
        margin-right: 15px;
        display: flex;
    }
    .after-before{
        position: relative;
        height: auto;
        width: 30%;
        float: right;
    }
    .after-before:after{
        content: '';
        position: absolute;
        background: url('/bitrix/templates/enext/images/delivery/banner_one.png') no-repeat;
        background-size: contain;
        width: 90%;
        height: 94%;
        left: 0;
        bottom: 0;
        top: 10px;
        right: 0;
    }
    .after-before:before{
        content: '';
        position: absolute;
        background: url('/bitrix/templates/enext/images/delivery/shadow.png') no-repeat;
        background-size: cover;
        width: 150%;
        height: 100%;
        left: auto;
        bottom: 0;
        top: 0;
        right: 0;
    }

    .after-before-two{
        position: relative;
        height: auto;
        width: 30%;
        float: right;
    }
    .after-before-two:after{
        content: '';
        position: absolute;
        background: url('/bitrix/templates/enext/images/delivery/deliv.png') no-repeat;
        background-size: contain;
        width: 90%;
        height: 94%;
        left: 0;
        bottom: 0;
        top: 10px;
        right: 0;
    }
    .after-before-two:before{
        content: '';
        position: absolute;
        background: url('/bitrix/templates/enext/images/delivery/shadow.png') no-repeat;
        background-size: cover;
        width: 150%;
        height: 100%;
        left: auto;
        bottom: 0;
        top: 0;
        right: 0;
    }

    .info-banner-one{
        width: 70%;
        float:left;
        padding: 50px 0 50px 50px;
    }
    .text-one-t-del{
        font-weight: 600;
        font-size: 16px;
        margin-top: 40px;
        position: relative;
        margin-left: 60px;
    }
    .text-one-t-del.one:before{
        content:'';
        background-image: url("/bitrix/templates/enext/images/delivery/1.svg");
        position: absolute;
        left: -60px;
        width: 40px;
        height: 40px;
        top: -10px;
    }
    .text-one-t-del.two:before{
        content:'';
        background-image: url("/bitrix/templates/enext/images/delivery/2.svg");
        position: absolute;
        left: -60px;
        width: 40px;
        height: 40px;
        top: -10px;
    }
    .text-one-t-del.three:before{
        content:'';
        background-image: url("/bitrix/templates/enext/images/delivery/3.svg");
        position: absolute;
        left: -60px;
        width: 40px;
        height: 40px;
        top: -10px;
    }
    .block-on-footer{
        background: #f9f5fe;
        border-radius: 10px;
        padding: 40px;
        margin-top: 20px;
        margin-left: 15px;
        margin-right: 15px;
    }
    .head-d{
        display: flex;
    }
    .head-le{
        width: 50%;
        float: left
    }
    .head-ri{
        width: 50%;
    }
    .foot-d{
        margin-top: 20px;
        width: 100%;
    }
    .head-le-tittle{
        font-weight: bold;
        font-size: 20px;
        margin-bottom: 15px;
    }
    .head-le-text{
        position: relative;
        font-weight: 500;
        font-size: 16px;
        margin-top: 5px;
    }
    .text-info-delivery{
        margin-left: 15px;
        position: relative;
    }
    .text-info-delivery::before{
        content: '';
        position: absolute;
        background: url('/bitrix/templates/enext/images/opt_price/info_icon.svg');
        background-size: cover;
        width: 24px;
        height: 31px;
        left: -30px;
        top: -4px;
    }
    .img-contact{
        display: inline-block;
        width: 36px;
        height: 36px;
    }
    .img-contact.inst{
        background-image: url("/bitrix/templates/enext/fonts/icon_extreme/svg/instagram_contact.svg");
    }
    .img-contact.vk{
        background-image: url("/bitrix/templates/enext/fonts/icon_extreme/svg/vk_contact.svg");
    }
    .img-contact.you{
        background-image: url("/bitrix/templates/enext/fonts/icon_extreme/svg/youtube_contact.svg");
    }
    .img-contact.inst:hover{
        background-image: url("/bitrix/templates/enext/fonts/icon_extreme/svg/instagram_contact_hover.svg");
    }
    .img-contact.vk:hover{
        background-image: url("/bitrix/templates/enext/fonts/icon_extreme/svg/vk_contact_hover.svg");
    }
    .img-contact.you:hover{
        background-image: url("/bitrix/templates/enext/fonts/icon_extreme/svg/youtube_contact_hover.svg");
    }
    .text-footer-del{
        display: flex;
        margin-top: 30px;
    }
    .text-footer-del-left{
        font-weight: 500;
        font-size: 16px;
        width: 70%;
        float: left;
        line-height: 20pt;
    }
    .text-footer-del-right{
        width: 30%;
        text-align: end;
    }
    .footer-delivery > div{
        font-weight: 600;
        font-size: 14px;
        color: #898f92;
        margin-top: 20px;

    }
    @media (max-width: 1260px) {
        .text-banner-one{
            font-size: 42px;
        }
    }
    @media (max-width: 991px) {
        .text-banner-one{
            font-size: 30px;
        }
        .text-banner-one-after {
            font-size: 13px;
        }
        .block-banner-one{
            margin-left: 0;
            margin-right: 0;
        }
        .after-before:before,
        .after-before-two:before{
            display: none;
        }
    }
    @media (max-width: 600px) {
        .text-footer-del{
            display: block;
        }
        .text-footer-del-left{
            float: none;
            width: 100%;
        }
        .text-footer-del-right{
            width: 100%;
            text-align: center;
        }
        .text-info-delivery{
            margin-left: 30px;
        }
        .text-info-delivery::before{
            top: 3px;
        }
        .head-d{
            display: block;
        }
        .head-le,
        .head-ri{
            width: 100%;
        }
        .head-le{
            margin-bottom: 15px;
        }
        .block-on-footer{
            margin-right: 0;
            margin-left: 0;
        }
        .text-one-t-del.one:before,
        .text-one-t-del.two:before,
        .text-one-t-del.three:before{
            top: 0;
        }
        .text-banner-one{
            font-size: 28px;
        }
        .text-banner-one-t{
            font-size: 24px;
        }
        .info-banner-one{
            padding: 50px 0 50px 15px;
        }
        .text-banner-one-after {
            font-size: 11px;
        }
        .after-before:after{
            top: 50%;
        }
        .after-before-two:after{
            top: 60%;
        }
    }
</style>

    <div class="title-opt"><h1>Доставка</h1></div>

    <div class="text-two">Наша компания выполняет доставку выбранного Вами товара по всему МИРУ.</div>
    <div class="text-one">У нас нет минимального порога заказа, отправляем выбранные Вами товары <span class="col-ex">в любую точку от 140р.<sup><span style="font-size: 8pt;">1</span></sup></span></div>

    <div class="block-banner-one">
        <div class="info-banner-one">
            <div class="text-banner-one">Доставка курьером</div>
            <div class="text-banner-one-after">Только для покупателей из г. Челябинск</div>
            <div class="text-banner-two">Доставка осуществляется ежедневно с 10:00 до 19:00 часов (кроме субботы и воскресенья).<br />Товары, заказанные Вами в субботу и воскресенье, доставляются в понедельник.<br />Время осуществления доставки зависит от времени размещения заказа и наличия товара на складе. </div>
        </div>
        <div class="after-before"></div>
    </div>

    <div class="text-one-f li">Если заказ подтвержден менеджером Службы доставки до 12:00, товар может быть доставлен в этот же рабочий день между 15:00 и 19:00.</div>
    <div class="text-one-f li">Иное время доставки определяется по договоренности с клиентом.</div>
    <div class="text-one-f li">Доставка осуществляется по адресу, указанному при оформлении заказа. Если необходимо доставить товар по иному адресу, необходимо сообщить адрес менеджеру Службы доставки, который свяжется с Вами непосредственно после оформления заказа на сайте.
    </div>


    <div class="text-one-t-del one">Стоимость доставки товара из нашего магазина по <span class="col-ex">г. Челябинск - бесплатна<sup><span style="font-size: 8pt;">2</span></sup></span>, при условии выбора товаров на сумму, не менее 2000 руб.</div>
    <div class="text-one-t-del two">Доставка осуществляется <span class="col-ex">по 100% предоплате</span> Вашего заказа.</div>
    <div class="text-one-t-del three" style="margin-bottom: 30px;"><span class="col-ex">Время доставки согласовывается с менеджером Службы доставки</span>, который обязательно свяжется с вами сразу после того, как Вы разместите свой заказ.</div>

    <div class="block-banner-one">
        <div class="info-banner-one">
            <div class="text-banner-one-t">Доставка в регионы РФ</div>
            <div class="text-banner-one-after">Для покупателей из регионов</div>
            <div class="text-banner-two text-info-delivery">Стоимость доставки зависит от выбора транспортной компании.</div>
        </div>
        <div class="after-before-two"></div>
    </div>

    <div class="block-on-footer">
        <div class="head-d">
            <div class="head-le">
                <div class="head-le-tittle">Почта России</div>
                <div class="head-le-text li">Стоимость доставки <span class="col-ex">300 руб.</span></div>
                <div class="head-le-text li">Если сумма заказа более 5000 руб. - <span class="col-ex">бесплатно<sup><span style="font-size: 8pt;">3</span></sup></span></div>
            </div>
            <div class="head-ri"><div class="head-le-tittle">EMC</div>
                <div class="head-le-text li"><span class="col-ex">700 руб/1000 руб/1500 руб</span>, в зависимости от удалённости Вашего региона<sup><span style="font-size: 8pt;">3</span></sup></span></div>
            </div>
        </div>
        <hr>
        <div class="foot-d">
            <div class="head-le-tittle">Транспортные компании</div>
            <div class="head-le-text li">Вы можете выбрать одну из нескольких, наиболее популярных транспортных компаний <span class="col-ex">(СДЭК, Деловые Линии, Байкал Сервис, Энергия, Луч (только для Челябинской обл.))</span> или любую другую, удобную для Вас, транспортную компанию.</div>
            <div class="head-le-text li"><span class="col-ex">Оплата доставки производится при получении груза</span> на терминале выбранной транспортной компании или курьеру, при доставке курьером до двери.</div>
        </div>
    </div>

    <div class="text-footer-del">
        <div class="text-footer-del-left">
            В случае вопросов, пожеланий и претензий обращайтесь к нам по следующим координатам:<br />
            Служба доставки: <a href="tel:+73517503033" style="text-decoration: none;"><span class="col-ex">+7 (351) 750-30-33</span></a> <span style="font-size: 9pt;">(многоканальный)</span><br />
            Электронная почта: <a href="mailto:info@extreme-look.ru"><span class="col-ex">info@extreme-look.ru</span></a>
        </div>
        <div class="text-footer-del-right">
            <a href="https://www.instagram.com/extreme_look.ru" target="_blank"><div class="img-contact inst"></div></a>
            <a href="https://vk.com/extreme_look" target="_blank"><div class="img-contact vk"></div></a>
            <a href="https://www.youtube.com/user/Nitrogirll" target="_blank"><div class="img-contact you"></div></a>
        </div>
    </div>


    <div class="footer-delivery">
        <div><sup><span style="font-size: 8pt;">1*</span></sup> Данная стоимость указана справочно и базируется выбранной транспортной компании, весе и габаритах отправления, удаленности города-получателя от склада компании.</div>
        <div><sup><span style="font-size: 8pt;">2*</span></sup> Стоимость доставки в удаленные районы г. Челябинск, а также, если сумма Вашего заказа менее 2000 руб., составляет 200 руб. К удаленным районам относятся: Ленинский, Металлургический, Советский, Тракторозаводский. При повышенном тарифе на доставку, стоимость доставки рассчитывается индивидуально. Если, при оформлении заказа, Вы оплатили фиксированную стоимость доставки (или не оплатили, по условиям акции бесплатной доставки по городу), возможно, потребуется доплата.</div>
        <div><sup><span style="font-size: 8pt;">3*</span></sup> Если вес отправления больше 10 кг или заказ содержит товары из раздела Освещение/Косметологические кушетки - будет произведен индивидуальный расчет стоимости доставки. Стоимость отправления за пределы РФ производится по индивидуальному расчету.</div>
    </div>