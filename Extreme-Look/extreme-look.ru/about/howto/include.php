<style>
    .title-opt > h1,
    .text-one,
    .text-one-t-del,
    .foot-ab-text,
    .text-two,
    .text-three{
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
    }
    .text-two{
        font-weight: bold;
        font-size: 20px;
        margin-top: 50px;
    }
    .text-three{
        font-weight: 500;
        font-size: 14px;
        margin-top: 5px;
        max-width: 1024px;
    }
    .text-one-t-del {
        font-weight: bold;
        font-size: 25px;
        margin-top: 40px;
        position: relative;
        margin-left: 60px;
    }
    .text-one-t-del.one:before {
        content: '';
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
    .text-one-t-del.three{
        margin-bottom: 50px;
    }
    .foot-block-ab{
        margin-top: 30px;
        display: inline-block;
        max-width: 1200px;
    }
    .foot-ab-el{
        width: 372px;
        height: 120px;
        position: relative;
        margin-right: 15px;
        margin-top: 15px;
        display: flex;
        float: left;
    }
    .foot-block-ab-img{
        float: left;
        width: 120px;
        padding: 20px;
    }
    .foot-ab-text{
        font-weight: 500;
        font-size: 12px;
        line-height: initial;
        margin: auto 0 auto 0;
    }
    div.foot-ab-text > span{
        font-weight: bold;
    }
    .extreme-color{
        font-weight: bold;
        color: #7b66fe;
    }
    .foot-ab-el.footer{
        max-width: 1024px;
        width: auto;
    }
    .footer > .foot-ab-text > span{
        font-weight: bold;
        font-size: 20px;
    }
    .footer > .foot-ab-text{
        font-weight: 500;
        font-size: 14px;
    }
    @media (max-width: 600px) {
        .title-opt > h1{
            font-weight: bold;
            font-size: 40px;
        }
        .foot-ab-el {
            width: 310px;
            height: auto;
        }
    }
</style>

<div class="title-opt"><h1>Как купить</h1></div>
<div class="text-one">Процедура покупки товара в нашем Интернет-магазине очень проста и состоит из нескольких шагов.</div>

<div class="text-one-t-del one">Оформление заказа</div>

<span class="foot-block-ab">
    <div class="foot-ab-el">
     <img src="/bitrix/templates/enext/images/howto/v_korzinu.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
            <span>01.</span> После выбора товара нажмите кнопку "В корзину" — товар добавится в вашу корзину.
        </div>
    </div>
    <div class="foot-ab-el">
     <img src="/bitrix/templates/enext/images/howto/vasha_korzina.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
            <span>02.</span> Далее, если вы закончили выбирать товар, нажмите кнопку "Ваша корзина".
        </div>
    </div>
    <div class="foot-ab-el">
     <img src="/bitrix/templates/enext/images/howto/spisok_tovarov.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
            <span>03.</span> На странице "Ваша корзина" будут перечислены все выбранные Вами товары.
        </div>
    </div>
    <div class="foot-ab-el">
     <img src="/bitrix/templates/enext/images/howto/kolichestvo.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
            <span>04.</span> В поле "Количество" Вы можете изменить количество товара для покупки. После изменения количества товара необходимо нажать кнопку "Пересчитать" для пересчета итоговой суммы заказа.
        </div>
    </div>
    <div class="foot-ab-el">
     <img src="/bitrix/templates/enext/images/howto/deistvia.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
           <span>05.</span> В колонке "Действия" над каждым товаром можно произвести следующие действия: либо удалить товар из корзины, либо отложить товар на будущее в избранное.
        </div>
    </div>
    <div class="foot-ab-el">
     <img src="/bitrix/templates/enext/images/howto/promokod.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
            <span>06.</span> Также можно ввести код скидки в соответствующее поле.
        </div>
    </div>
 </span>

<div class="text-one-t-del two">Оформление и подтверждение заказа</div>

<div class="text-two">Оформить заказ</div>
<div class="text-three">После ввода необходимой информации о доставке товара (ФИО получателя, адрес доставки, контактные данные, вариант доставки, способ оплаты и т.д) для оформления заказа Вам нужно нажать кнопку "Оформить заказ".</div>

<div class="text-two">Копия заказа</div>
<div class="text-three">Копия заказа будет выслана на ваш e-mail, указанный при оформлении заказа.</div>

<div class="text-two">Персональные данные</div>
<div class="text-three">Внимание! Неправильно указанный номер телефона, неточный или неполный адрес могут привести к дополнительной задержке! Пожалуйста, внимательно проверяйте ваши персональные данные при регистрации и оформлении заказа.</div>

<div class="text-two">Доставка</div>
<div class="text-three">Через некоторое время (обычно в течение часа) после оформления покупки, с вами свяжется наш менеджер по контактным данным, указанным при оформлении заказа. С менеджером можно будет согласовать точное время и сроки доставки, а также уточнить детали.</div>

<div class="text-two">Примечание</div>
<div class="text-three">Для постоянных клиентов на сайте магазина есть Регистрация. В своем кабинете вы можете просмотреть содержимое корзины, историю своих заказов, а также повторить или отказаться от заказа, подписаться на рассылку новостей магазина.</div>

<div class="text-one-t-del three">Оплата и цены</div>

<div class="text-three">Цены, указанные на сайте, являются окончательными и не требуют доплат при стандартных условиях поставки. Все налоги включены в стоимость товара.</div>
<div class="text-three extreme-color">Внимание! Для каждого отдельного заказа возможен только один способ оплаты на ваш выбор. Оплата заказа по частям различными способами невозможна.</div>

<span class="foot-block-ab" style="display: inline-grid">
    <div class="foot-ab-el footer">
     <img src="/bitrix/templates/enext/images/howto/nal_raschet.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
            <span>Наличный расчёт</span><br />Оплата производится в магазине при самовывозе. Вместе с товаром передается товарный и кассовый чеки, а также гарантийный талон.
        </div>
    </div>
    <div class="foot-ab-el footer">
     <img src="/bitrix/templates/enext/images/howto/galka.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
            <span>Оплата по счету на оплату.</span><br />Вы можете оплатить заказ в любом отделении банка или банковском приложении для смартфона. Зачисление денежных средств на расчётный счёт магазина займет от 1 до 3 рабочих дней. Если Вы являетесь юр.лицом, нам потребуются реквизиты Вашей организации.
        </div>
    </div>
    <div class="foot-ab-el footer">
     <img src="/bitrix/templates/enext/images/howto/karta.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
            <span>Оплата картой онлайн.</span><br />Система предложит Вам удобный метод оплаты прямо с сайта в режиме онлайн. После оплаты, Вы получите квитанцию о совершенном платеже. Подробности в соответствующем разделе.
        </div>
    </div>
<!--    <div class="foot-ab-el footer">
     <img src="/bitrix/templates/enext/images/howto/procent.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
           <span>Рассрочка без процентов и переплат!</span><br />Подробности в соответствующем <a href="https://extreme-look.ru/about/credit/">разделе</a>.
        </div>
    </div>
    <div class="foot-ab-el footer">
     <img src="/bitrix/templates/enext/images/howto/galka.svg" class="foot-block-ab-img">
        <div class="foot-ab-text">
            <span>Кредит</span><br />Инструкция для покупки в кредит без переплаты от Покупай со Сбербанком.
        </div>
    </div>-->
 </span>