<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");?><style>
    .ex-contact-text {
        font-family: 'Graphik LCG';
        font-weight: bold;
        font-size: 50px;
        font-style: normal;
        color: #000000;
        height: 70px;
    }
    .ex-small-text {
        font-family: 'Graphik LCG';
        font-weight: 400;
        font-size: 14px;
        font-style: normal;
        color: #3e4b5191;
        padding-bottom: 10px;
    }
    .ex-md-text {
        font-family: 'Graphik LCG';
        font-weight: bold;
        font-size: 22px;
        font-style: normal;
        color: #000000;
        line-height: 32px;
    }
    .ex-sec-small-text {
        font-family: 'Graphik LCG';
        font-weight: 500;
        font-size: 16px;
        font-style: normal;
        color: #3e4b51;
        padding-top: 5px;
        padding-bottom: 40px;
    }
    .tb-contact-desk{
        display: table;
    }
    .tb-contact-mobile{
        display: none;
    }
    .button-part{
        font-family: 'Graphik LCG';
        font-weight: 600;
        font-size: 14px;
        font-style: normal;
        color: white;
        text-decoration: none;
        display: inline-block;
        padding: 13px 20px 12px 20px;
        border-radius: 10px;
        background: linear-gradient(10deg,#7b66fe 0,#A799FE 100%);
    }
    .button-part:hover{
        background: #7b66fe;
    }
    .button-shop-part{
        font-family: 'Graphik LCG';
        font-weight: 500;
        font-size: 14px;
        font-style: normal;
        color: #7b66fe;
        text-decoration: none;
        display: inline-block;
        background: #ffffff;
        padding: 11px 16px 9px 16px;
        border-radius: 10px;
        border: 2px double #7b66fe;
        margin-left: 10px;
    }
    .button-shop-part:hover{
        background: #7b66fe;
        color: white !important;
    }
    .img-contact{
        display: inline-block;
        width: 36px;
        height: 36px;
    }
    .cont-container{
        padding-top: 70px;
        padding-bottom: 70px;
    }
    @media (max-width: 991px){
        .button-part > a,
        .button-part > a:hover{
            color: white;
            text-decoration: none;
        }
        .button-part{
            margin-left: auto;
            margin-right: auto;
        }
        .button-shop-part > a:hover,
        .button-shop-part:hover > a{
            color: white !important;
            text-decoration: none !important;
        }
        .button-shop-part > a{
            text-decoration: none !important;
        }
        .button-shop-part {
            margin-left: auto;
            margin-right: auto;
            border: 1px double #7b66fe;
        }
        .block-flex{
            display: flex;
        }
        .ex-pt{
            padding-top: 10px;
        }
        .tb-contact-mobile{
            display: table;
        }
        .tb-contact-desk{
            display: none;
        }
        .table-mobil{
            padding-bottom: 40px;
        }
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
</style>
    <div class="container cont-container">
        <div class="ex-contact-text">Контакты</div>

        <table class="tb-contact-desk" width="80%">
            <tr valign="top">
                <td>
                    <div class="col ex-small-text">Наш адрес</div>
                    <div class="col ex-md-text">г. Челябинск,<br />ул. Труда 174, ТК Манхэттен</div>
                    <div class="col ex-sec-small-text">2 этаж, галерея EXTREME LOOK</div>
                </td>
                <td>
                    <div class="col ex-small-text">Телефон</div>
                    <div class="col ex-md-text">+7 (351) 750-30-33<br />+7 (351) 750-41-12</div>
                </td>
                <td>
                    <div class="col ex-small-text">Горячая линия</div>
                    <div class="col ex-md-text">8 (800) 350-72-15</div>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <div class="col ex-small-text">Часы работы</div>
                    <div class="col ex-md-text">пн-сб 10:00-19:00<br />вс 12:00-18:00</div>
                </td>
                <td>
                    <div class="col ex-small-text">По любым вопросам</div>
                    <div class="col ex-md-text"><a href="mailto:info@extreme-look.ru">info@extreme-look.ru</a></div>
                </td>
                <td>
                    <div class="col ex-small-text">По вопросам сотрудничества</div>
                    <div class="col ex-md-text"><a href="mailto:distributor@extreme-look.ru">distributor@extreme-look.ru</a></div>
                </td>
            </tr>
            <tr valign="center">
                <td colspan="2" align="left" style="padding-top: 50px;">
                    <a class="link-button-cont" href="https://extreme-look.ru/partners/stat-partnyerom/" target="_blank"><div class="button-part">СТАТЬ ПАРТНЁРОМ</div></a>
                    <a href="https://extreme-look.ru/partners/" target="_blank"><div class="button-shop-part">НАЙТИ ТОЧКУ ПРОДАЖ В СВОЁМ ГОРОДЕ</div></a>
                </td>
                <td style="padding-top: 50px;">
                    <a href="https://www.instagram.com/extreme_look.ru" target="_blank"><div class="img-contact inst"></div></a>
                    <a href="https://vk.com/extreme_look" target="_blank"><div class="img-contact vk"></div></a>
                    <a href="https://www.youtube.com/user/Nitrogirll" target="_blank"><div class="img-contact you"></div></a>
                </td>
            </tr>
        </table>
        <table class="tb-contact-mobile" width="100%">
            <tr valign="top">
                <td>
                    <div class="col ex-small-text">Наш адрес</div>
                    <div class="col ex-md-text">г.Челябинск,<br />ул.Труда 174, ТК MANHATTAN</div>
                    <div class="col ex-sec-small-text">2 этаж, галерея EXTREME LOOK</div>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <div class="col ex-small-text">Телефон</div>
                    <div class="col ex-md-text table-mobil">+7(351)750-30-33<br />+7(351)750-41-12</div>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <div class="col ex-small-text">Горячая линия</div>
                    <div class="col ex-md-text table-mobil">8(800)350-72-15</div></td>
            </tr>
            <tr valign="top">
                <td>
                    <div class="col ex-small-text">Часы работы</div>
                    <div class="col ex-md-text table-mobil">пн-сб 10:00-19:00<br />вс 12:00-18:00</div>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <div class="col ex-small-text">По любым вопросам</div>
                    <div class="col ex-md-text table-mobil"><a href="mailto:info@extreme-look.ru">info@extreme-look.ru</a></div>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <div class="col ex-small-text">По вопросам сотрудничества</div>
                    <div class="col ex-md-text table-mobil"><a href="mailto:distributor@extreme-look.ru">distributor@extreme-look.ru</a></div>
                </td>
            </tr>
            <tr align="center" valign="center">
                <td colspan="2" align="left">
                    <div class="block-flex">
                        <div class="button-part"><a href="https://extreme-look.ru/partners/stat-partnyerom/" target="_blank">СТАТЬ ПАРТНЁРОМ</a></div>
                    </div>
                    <div class="block-flex ex-pt">
                        <div class="button-shop-part"><a href="https://extreme-look.ru/partners/" target="_blank">НАЙТИ ТОЧКУ ПРОДАЖ В СВОЁМ ГОРОДЕ</a></div>
                    </div>
                </td>
            </tr>
            <tr align="center" valign="center">
                <td style="padding-top: 50px;">
                    <a href="https://www.instagram.com/extreme_look.ru" target="_blank"><div class="img-contact inst"></div></a>
                    <a href="https://vk.com/extreme_look" target="_blank"><div class="img-contact vk"></div></a>
                    <a href="https://www.youtube.com/user/Nitrogirll" target="_blank"><div class="img-contact you"></div></a>
                </td>
            </tr>
        </table>
    </div>
    <br /><br />
    <div class="maps">
        <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A61bc3bc55775e8c61baaeccc99ad080a5b4eece354ab9ee4bb35af1e469ff2e7&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe>
    </div>
    <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>