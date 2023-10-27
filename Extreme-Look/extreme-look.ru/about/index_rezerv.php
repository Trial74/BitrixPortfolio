<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->SetTitle("О нас");?>
    <style>
        .title-opt > h1,
        .text-one,
        .text-two,
        .extreme-look,
        .text-col,
        .left-b-block,
        .right-b-block > a,
        .pr-ab-text,
        .foot-ab-text,
        .text-footer-ur{
            font-family: 'Graphik LCG';
        }
        .text-col{
            font-weight: 500;
            font-size: 14px;
            margin-top: 20px;
            width: 200px;
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
            font-size: 18px;
            margin-top: 30px;
        }
        .text-footer-ur{
            display: block;
            margin-top: 30px;
            font-weight: 600;
            font-size: 14px;
            line-height: initial;
            width: 50%;
            float: left;
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
        .extreme-look{
            font-weight: bold;
            font-size: 110px;
            color: #7a66fb;
            margin-top: 90px;
            margin-bottom: 60px;
        }
        .row,
        .column{
            box-sizing: border-box;
        }
        .row:before,
        .row:after{
            content: " ";
            display: table;
        }
        .row:after{
            clear: both;
        }
        .column-2{
            width: 17.3333333333%;
        }
        .column{
            border-radius: 4px;
            padding: 5px;
            min-height: 30px;
            position: relative;
            float: left;
            display: block;
            margin-right: 2%;
            height: 190px;
        }
        .row > .column:nth-child(n+6){
            margin-top: 10px;
        }
        .row{
            margin-bottom: 10px;
        }
        .row:last-child{
            margin-bottom: 0;
        }
        .blue-block{
            background: #7b66fe;
            box-shadow: 0 0 10px rgb(0 0 0 / 50%);
            padding: 15px;
            color: white;
            display: flex;
            border-radius: 10px;
        }
        .left-b-block{
            float: left;
            width: 70%;
            font-weight: 400;
            font-size: 12px;
        }
        .right-b-block > a{
            position: relative;
            color: white !important;
            font-weight: bold;
            font-size: 22px;
            margin: auto;
        }
        .right-b-block > a:before{
            content: '';
            position: absolute;
            background: url("/bitrix/templates/enext/images/about/post.svg") no-repeat center;
            width: 70px;
            height: 70px;
            left: -90px;
            top: -23px;
        }
        .right-b-block{
            min-width: 410px;
            max-width: 410px;
            text-align: center;
            display: flex;
        }
        .icon-col{
            margin-right: auto;
            width: 70px;
            height: 70px;
        }
        .icon-col.cen{
            background: url("/bitrix/templates/enext/images/about/price.svg") no-repeat center;
        }
        .icon-col.tov{
            background: url("/bitrix/templates/enext/images/about/assort.svg") no-repeat center;
        }
        .icon-col.opic{
            background: url("/bitrix/templates/enext/images/about/opisan.svg") no-repeat center;
        }
        .icon-col.search{
            background: url("/bitrix/templates/enext/images/about/search.svg") no-repeat center;
        }
        .icon-col.obr{
            background: url("/bitrix/templates/enext/images/about/obr.svg") no-repeat center;
        }
        .icon-col.sert{
            background: url("/bitrix/templates/enext/images/about/sert.svg") no-repeat center;
        }
        .icon-col.garant{
            background: url("/bitrix/templates/enext/images/about/garant_obsl.svg") no-repeat center;
        }
        .icon-col.pokupk{
            background: url("/bitrix/templates/enext/images/about/shoping.svg") no-repeat center;
        }
        .icon-col.soglas{
            background: url("/bitrix/templates/enext/images/about/quick.svg") no-repeat center;
        }
        .icon-col.obmen{
            background: url("/bitrix/templates/enext/images/about/obmen.svg") no-repeat center;
        }
        .ab-block{
            display: flex;
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 50px;
        }
        .ab-img{
            background: #f7f4fd;
            width: 200px;
            height: 300px;
            padding: 30px 0 0 0;
            border-radius: 10px;
            max-width: 200px;
            min-width: 200px;
            position: relative;
            margin-right: 15px;
        }
        .ab-img > img{
            display: block;
            height: 60%;
            width: auto;
            margin-left: auto;
            margin-right: auto;
        }
        .pr-ab-text{
            font-weight: 700;
            font-size: 12px;
            margin-top: 20px;
            padding-left: 15px;
            padding-right: 15px;
            line-height: 12px;
        }
        .foot-block-ab{
            margin-top: 30px;
            display: inline-block;
        }
        .foot-ab-el{
            border-radius: 10px;
            border: 2px double #f7f4fd;
            width: 600px;
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
        .footer-block-ab{
            display: flex;
        }
        .social-ab{
            width: 50%;
            margin: auto;
            text-align: center;
        }
        @media only screen and (max-width: 791px) {
            .right-b-block > a{
                margin: 0 0 0 auto;
            }
            .blue-block{
                display: block;
            }
            .left-b-block {
                float: none;
                width: 100%;
            }
            .right-b-block{
                margin-top: 20px;
                min-width: 0;
            }
            .row {
                margin-bottom: 0;
            }
            .row > .column:nth-child(n+6){
                margin-top: 0;
            }
            .left{
                float:left;
            }
            .column-2{
                width: 46%;
            }
            .column {
                margin-bottom: 10px;
                height: 215px;
                text-align: center;
            }
            .icon-col{
                margin-left: auto;
            }
            .row:last-child .column:last-child {
                margin-bottom: 0;
            }
        }
        @media (max-width: 1200px){
            .extreme-look{
                font-size: 78px;
            }
        }
        @media (max-width: 991px){
            .text-col{
                width: 155px;
            }
            .footer-block-ab{
                display: block;
            }
            .social-ab{
                width: 100%;
                margin-top: 15px;
            }
            .text-footer-ur{
                float: none;
                width: 100%;
            }
            .foot-ab-el{
                width: 100%;
            }
            .title-opt > h1{
                font-size: 40px;
            }
            .extreme-look{
                font-size: 87px;
                margin-top: 50px;
                margin-bottom: 40px;
            }
            .ab-block{
                overflow: auto;
            }
        }
        @media (max-width: 800px){
            .extreme-look{
                font-size: 78px;

            }
        }
        @media (max-width: 700px){
            .extreme-look{
                font-size: 48px;

            }
        }
        @media (max-width: 500px){
            .extreme-look{
                font-size: 38px;
            }
            .right-b-block > a{
                font-size: 18px;
            }
            .right-b-block > a:before{
                width: 60px;
                height: 60px;
            }
        }
    </style>
    <div class="title-opt"><h1>О нас</h1></div>

    <div class="text-two">Рады приветствовать Вас на сайте нашей компании!</div>

    <div class="extreme-look">EXTREME LOOK</div>

    <div class="text-one">Наша компания была основана в 2009 году, а наш интернет-магазин стал одним из первых магазинов, осуществляющих on-line продажу материалов для наращивания ресниц в регионе. Компания специализируется на оптовой и розничной продаже материалов как для профессионального использования, так и для продажи в специализированных студиях и магазинах.</div>

    <div class="text-one">На данный момент мы представляем собой крупную компанию, владеющую интернет–магазином и имеющую в своей сети единый call-центр, который регулирует всю деятельность магазина, отдел продаж, службу доставки, широкий штат квалифицированных логистов, собственный склад c постоянным наличием необходимого запаса товаров.</div>

    <div class="text-one">За это время у нас сложились партнерские отношения с ведущими студиями, позволяющие предлагать высококачественную продукцию по конкурентоспособным ценам.</div>

    <div class="text-one">Мы можем гордиться тем, что у нас один из самых широких ассортиментов материалов для наращивания ресниц в городе и области.</div>

    <div class="text-two" style="margin-bottom: 30px;">На нашем сайте к Вашим услугам:</div>

    <div class="row">
        <div class="column column-2 left">
            <div class="icon-col cen"></div>
            <div class="text-col">Реальные и конкурентоспособные цены</div>
        </div>
        <div class="column column-2">
            <div class="icon-col tov"></div>
            <div class="text-col">Широчайший ассортимент товаров</div>
        </div>
        <div class="column column-2 left">
            <div class="icon-col opic"></div>
            <div class="text-col">Качественные описания и изображения товаров</div>
        </div>
        <div class="column column-2">
            <div class="icon-col search"></div>
            <div class="text-col">Поиск товаров в магазине</div>
        </div>
        <div class="column column-2">
            <div class="icon-col obr"></div>
            <div class="text-col">Система обратной связи</div>
        </div>
        <div class="column column-2 left">
            <div class="icon-col sert"></div>
            <div class="text-col">Продажа только сертифицированных и имеющих легальное происхождение товаров</div>
        </div>
        <div class="column column-2">
            <div class="icon-col garant"></div>
            <div class="text-col">Гарантийное обслуживание купленных у нас товаров</div>
        </div>
        <div class="column column-2 left">
            <div class="icon-col pokupk"></div>
            <div class="text-col">Покупка товара, не выходя из дома или офиса</div>
        </div>
        <div class="column column-2">
            <div class="icon-col soglas"></div>
            <div class="text-col">Быстрое согласование товара с клиентом для подтверждения заказа</div>
        </div>
        <div class="column column-2">
            <div class="icon-col obmen"></div>
            <div class="text-col">Обмен товаров ненадлежащего качества и многое другое</div>
        </div>
    </div>

    <div class="blue-block">
        <div class="left-b-block">Мы всегда рады общению с нашими клиентами.<br />Если у вас есть какие-либо пожелания, предложения, замечания, касающиеся работы нашего<br />Интернет-магазина - пишите нам, и мы с благодарностью примем ваше мнение во внимание:</div>
        <div class="right-b-block"><a href="mailto:info@extreme-look.ru">info@extreme-look.ru</a></div>
    </div>

    <div class="text-two">Марка EXTREME LOOK - создана для специалистов в области ЛЭШ-индустрии, для тех, кто по-настоящему любит и ценит свое дело. Вся продукция от EXTREME LOOK это по-настоящему уникальная коллекция лучших собраний для профессионала:</div>

    <div class="ab-block">
        <div class="ab-img">
            <img width="100%" class="block-ab-img" src="/bitrix/templates/enext/images/about/resnic.png">
            <div class="pr-ab-text">Мягкие и эластичные ресницы</div>
        </div>
        <div class="ab-img">
            <img width="100%" class="block-ab-img" src="/bitrix/templates/enext/images/about/wate.png">
            <div class="pr-ab-text">Линия профессиональных жидкостей, включающая в себя инновационные продукты, не имеющие аналогов</div>
        </div>
        <div class="ab-img">
            <img width="100%" class="block-ab-img" src="/bitrix/templates/enext/images/about/pincet.png">
            <div class="pr-ab-text">Завоевавшая доверие во всем Мире коллекция сверхточных пинцетов</div>
        </div>
        <div class="ab-img">
            <img width="100%" class="block-ab-img" src="/bitrix/templates/enext/images/about/klei.png">
            <div class="pr-ab-text">Совершенная линия профессионального клея для Лэш-стилиста</div>
        </div>
    </div>

    <dif class="foot-block-ab">
        <div class="foot-ab-el">
            <img class="foot-block-ab-img" src="/bitrix/templates/enext/images/about/serts.svg">
            <div class="foot-ab-text">Наша продукция имеет полный набор обязательных и добровольных сертификатов качества, и гарантирует высочайшее качество и безопасность выпускаемого товара.</div>
        </div>
        <div class="foot-ab-el">
            <img class="foot-block-ab-img" src="/bitrix/templates/enext/images/about/firm.svg">
            <div class="foot-ab-text">Весь ассортимент поставляется в фирменной упаковке, с подробным описанием товара и инструкцией по его грамотному использованию.</div>
        </div>
        <div class="foot-ab-el">
            <img class="foot-block-ab-img" src="/bitrix/templates/enext/images/about/new_tov.svg">
            <div class="foot-ab-text">Все товарные направления регулярно расширяются и обновляются.</div>
        </div>
        <div class="foot-ab-el">
            <img class="foot-block-ab-img" src="/bitrix/templates/enext/images/about/skid.svg">
            <div class="foot-ab-text">Наша компания ценит постоянных клиентов, поэтому поощряет их выбор гибкой системой скидок, как для оптовых, так и для розничных покупателей.</div>
        </div>
        <div class="foot-ab-el">
            <img class="foot-block-ab-img" src="/bitrix/templates/enext/images/about/quick_deliv.svg">
            <div class="foot-ab-text">Мы осуществляем быструю доставку по всеми Миру, и имеем одну из самых широких представительских сетей (более 180 дистрибьюторов) в 14 странах!</div>
        </div>
    </dif>

    <div class="footer-block-ab">
        <div class="text-footer-ur">
            Юридические данные:<br />
            <br />
            Индивидуальный предприниматель Хитрина Нина Тимоновна<br />
            ИНН 740302053814<br />
            ОГРН 310741215500010<br />
            Адрес, 108828, г. Москва, пос. Краснопахорское, тер. ДНП Идиллия влд. 16 стр 1
        </div>
        <div class="social-ab">
            <a href="https://www.instagram.com/extreme_look.ru" target="_blank"><div class="img-contact inst"></div></a>
            <a href="https://vk.com/extreme_look" target="_blank"><div class="img-contact vk"></div></a>
            <a href="https://www.youtube.com/user/Nitrogirll" target="_blank"><div class="img-contact you"></div></a>
        </div>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>