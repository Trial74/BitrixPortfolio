<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?>
<?$APPLICATION->SetTitle("Рассрочка без переплат");?>
    <style>
        .title-opt > h1,
        .by-bu-ro-credit,
        .text-tittle-block-credit,
        .text-block-credit{
            font-family: 'Graphik LCG';
        }
        .title-opt > h1{
            font-weight: bold;
            font-size: 60px;
            padding-left: 42px;
            margin-bottom: 40px;
        }
        .main-block-credit{
            display: flex;
        }
        .right-block-credit{
            width: 100%;
            float: right;
        }
        .left-block-credit{
            width: 100%;
        }
        .left-block-credit > a > img,
        .right-block-credit > a > img{
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .left-block-credit,
        .right-block-credit{
            padding-left: 15px;
            padding-right: 15px;
            height: fit-content;
            display: grid;
        }
        .by-bu-ro-credit{
            font-weight: 600;
            font-size: 16px;
            color: #7a66fa;
            border-radius: 20px;
            padding: 10px;
            width: fit-content;
            width: -moz-fit-content;
            border: 2px solid;
            margin-top: 15px;
        }
        .text-tittle-block-credit{
            font-weight: bold;
            font-size: 20px;
            margin-top: 15px;
        }
        .text-block-credit{
            font-weight: 400;
            font-size: 16px;
            margin-top: 15px;
        }
        .left-block-credit > a.a-l,
        .right-block-credit > a.a-r{
            margin-top: 30px;
            width: 200px;
            margin-bottom: 30px;
        }
        @media (min-width: 1700px) {
            .text-tittle-block-credit{
                font-size: 30px;
                line-height: 30px;
            }
            .text-block-credit{
                font-size: 20px;
            }
            .by-bu-ro-credit{
                font-size: 18px;
            }
        }
        @media (max-width: 991px){
            .title-opt > h1{
                font-size: 40px;
                margin-bottom: 25px;
            }
        }
        @media (max-width: 600px){
            .main-block-credit{
                display: block;
            }
        }
    </style>

    <div class="title-opt"><h1>Рассрочка без переплат</h1></div>

    <div class="container-fluid main-block-credit">
        <div class="left-block-credit">
            <a href="https://extreme-look.ru/about/credit/s_by_business/">
                <img src="/bitrix/templates/enext/images/credit/banner_1.jpg" alt="Рассрочка без переплат для бизнеса" />
            </a>
            <div class="by-bu-ro-credit">Для розничных клиентов</div>
            <div class="text-tittle-block-credit">Покупай без денег</div>
            <div class="text-block-credit">Мы презентуем уникальное предложение - забирайте материалы от 5  до 200 тысяч рублей, а платите потом!</div>
            <a href="https://extreme-look.ru/about/credit/s_by_business/" class="a-l btn btn-buy"><span>Читать подробнее</span></a>
        </div>
    </div>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>