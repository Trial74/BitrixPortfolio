<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?><?$APPLICATION->SetTitle("Для розничных клиентов");?> <style>
        .title-opt > h1,
        .text-one,
        .tittle-text-one,
        .text-block-credit,
        .tittle-text-two,
        .tittle-text-three,
        .text-three,
        .block-predl-credit,
        .tittle-text-four,
        .text-four,
        .block-info-credit,
        .tittle-text-five{
            font-family: 'Graphik LCG';
        }
        .text-one{
            font-weight: 500;
            font-size: 16px;
            padding-top: 15px;
        }
        .tittle-text-one{
            font-weight: bold;
            font-size: 27px;
            line-height: 30px;
        }
        .tittle-text-two{
            font-weight: bold;
            font-size: 25px;
            line-height: 30px;
            padding-top: 15px;
        }
        .tittle-text-three{
            font-weight: bold;
            font-size: 27px;
            padding-top: 30px;
        }
        .text-three{
            font-weight: 500;
            font-size: 16px;
            padding-top: 15px;
        }
        .text-three > div{
            position: relative;
            margin-left: 15px;
            padding-top: 10px;
        }
        .text-three > div:before{
            content: "\2022";
            color: #2F80ED;
            font-size: 25px;
            position: absolute;
            left: -15px;
        }
        .tittle-text-four{
            font-weight: bold;
            font-size: 27px;
            padding-top: 30px;
        }
        .tittle-text-five{
            font-weight: bold;
            font-size: 27px;
            line-height: 40px;
        }
        .text-four{
            font-weight: 500;
            font-size: 16px;
            margin-top: 10px;
        }
        .text-block-credit{
            width: 500px;
            margin-left: 15px;
        }
        .title-opt > h1{
            font-weight: bold;
            font-size: 60px;
            margin-bottom: 40px;
        }
        .banner-text-block{
            display: flex;
        }
        .img-block-credit{
            width: 50%;
        }
        .img-block-credit > img{
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .block-predl-credit{
            font-weight: 500;
            font-size: 16px;
            display: inline-block;
        }
        .block-pr-1,
        .block-pr-2,
        .block-pr-3,
        .block-pr-4{
            position: relative;
            background: #f9f5fe;
            padding: 15px;
            border-radius: 10px;
            margin-left: 5px;
            margin-right: 5px;
            height: 100px;
            padding-left: 120px;
            min-width: 276px;
            max-width: 276px;
            display: flex;
            float: left;
            margin-top: 15px;

        }
        .block-pr-1 > div,
        .block-pr-2 > div,
        .block-pr-3 > div,
        .block-pr-4 > div{
            margin: auto;
        }
        .block-pr-1:before{
            content: '';
            background-image: url("/bitrix/templates/enext/images/credit/proc.svg");
            background-repeat: no-repeat;
            background-size: contain;
            position: absolute;
            width: 70px;
            height: 68px;
            left: 25px;
        }
        .block-pr-2:before,
        .block-pr-3:before{
            content: '';
            background-image: url("/bitrix/templates/enext/images/credit/null.svg");
            background-repeat: no-repeat;
            background-size: contain;
            position: absolute;
            width: 70px;
            height: 68px;
            left: 25px;
        }
        .block-pr-4:before{
            content: '';
            background-image: url("/bitrix/templates/enext/images/credit/six.svg");
            background-repeat: no-repeat;
            background-size: contain;
            position: absolute;
            width: 70px;
            height: 68px;
            left: 25px;
        }
        .block-info-credit{
            font-weight: 500;
            font-size: 16px;
            display: inline-block;
        }
        .block-inf-1,
        .block-inf-2,
        .block-inf-3,
        .block-inf-4,
        .block-inf-5{
            position: relative;
            padding: 15px;
            border-radius: 10px;
            margin-left: 5px;
            margin-right: 5px;
            height: 85px;
            padding-left: 120px;
            min-width: 333px;
            max-width: 333px;
            display: flex;
            float: left;
            margin-top: 15px;

        }
        .block-inf-1 > div,
        .block-inf-2 > div,
        .block-inf-3 > div,
        .block-inf-4 > div,
        .block-inf-5 > div{
            margin: auto;
        }
        .block-inf-1:before,
        .block-inf-2:before,
        .block-inf-3:before,
        .block-inf-4:before,
        .block-inf-5:before{
            content: '';
            background-repeat: no-repeat;
            background-size: contain;
            position: absolute;
            width: 53px;
            height: 52px;
            left: 25px;
        }
        .block-inf-1:before{
            background-image: url("/bitrix/templates/enext/fonts/icon_extreme/infograf/1.svg");
        }
        .block-inf-2:before{
            background-image: url("/bitrix/templates/enext/fonts/icon_extreme/infograf/2.svg");
        }
        .block-inf-3:before{
            background-image: url("/bitrix/templates/enext/fonts/icon_extreme/infograf/3.svg");
        }
        .block-inf-4:before{
            background-image: url("/bitrix/templates/enext/fonts/icon_extreme/infograf/4.svg");
        }
        .block-inf-5:before {
            background-image: url("/bitrix/templates/enext/fonts/icon_extreme/infograf/5.svg");
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
        .footer-credit{
            display: flex;
            margin-top: 100px;
        }
        .tittle-text-five{
            float: left;
            width: 100%;
        }
        .soc-credit{
            width: 40%
        }
        @media (max-width: 991px) {
            .tittle-text-two {
                font-size: 19px;
            }
            .tittle-text-three{
                font-size: 21px;
            }
            .footer-credit{
                display: block;
            }
            .tittle-text-five{
                float: none;
                width: 100%;
            }
            .soc-credit{
                width: 100%;
                text-align: center;
            }
            .banner-text-block{
                display: block;
            }
            .img-block-credit{
                width: 100%;
            }
            .title-opt > h1{
                font-size: 26px;
                margin-bottom: 0;
            }
            .text-block-credit{
                width: 100%;
                margin-top: 30px;
            }
        }
        @media (min-width: 1700px) {
            .text-block-credit{
                width: 700px;
            }
            .tittle-text-one{
                font-size: 35px;
            }
            .text-one{
                font-size: 20px;
            }
            .tittle-text-two{
                font-size: 32px;
                line-height: 40px;
            }
            .tittle-text-three{
                font-size: 33px;
            }
            .text-three{
                font-size: 20px;
            }
        }
    </style>
<div class="title-opt">
	<h1>Рассрочка для розничных клиентов</h1>
</div>
<div class="banner-text-block">
	<div class="img-block-credit">
 <img alt="Рассрочка без переплат для бизнеса" src="/bitrix/templates/enext/images/credit/banner_1.jpg">
	</div>
	<div class="text-block-credit">
		<br>
	</div>
</div>
<div class="tittle-text-three" style="margin-bottom: 30px;">
	 Что мы предлагаем?
</div>
<div class="block-predl-credit">
	<div class="block-pr-1">
		<div>
			 Рассрочку на приобретение материалов
		</div>
	</div>
	<div class="block-pr-2">
		<div>
			 0% первоначальный взнос
		</div>
	</div>
	<div class="block-pr-3">
		<div>
			 0% переплат
		</div>
	</div>
	<div class="block-pr-4">
		<div>
			 Срок до 6 месяцев
		</div>
	</div>
</div>
<div class="tittle-text-four">
	 Как оформить рассрочку?
</div>
<div class="text-four">
	 Пять простых шагов для оформления рассрочки
</div>
<div class="block-info-credit">
	<div class="block-inf-1">
		<div>
			 В разделе <b>"Оплата"</b> выберите метод оплаты <b>"Купить в рассрочку"</b> - Оформить заказ
		</div>
	</div>
	<div class="block-inf-2">
		<div>
 <b>Заполните и отправьте анкету</b> для банка (Тинькофф, Сбербанк)
		</div>
	</div>
	<div class="block-inf-3">
		<div>
 <b>Ожидайте решение банка</b>
		</div>
	</div>
	<div class="block-inf-4">
		<div>
			 После успешного одобрения Вашей заявки банком, <b>Вам поступит СМС-оповещение</b>
		</div>
	</div>
	<div class="block-inf-5">
		<div>
 <b>Мы собираем и направляем заказ к Вам!</b>
		</div>
	</div>
</div>
<div class="footer-credit">
	<div class="tittle-text-five">
		 Вступайте в серьезный бизнес вместе с EXTREME LOOK!
	</div>
	<div class="soc-credit">
 <a href="https://www.instagram.com/extreme_look.ru" target="_blank">
		<div class="img-contact inst">
		</div>
 </a> <a href="https://vk.com/extreme_look" target="_blank">
		<div class="img-contact vk">
		</div>
 </a> <a href="https://www.youtube.com/user/Nitrogirll" target="_blank">
		<div class="img-contact you">
		</div>
 </a>
	</div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>