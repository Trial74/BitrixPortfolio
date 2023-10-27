<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->AddHeadScript("/bitrix/templates/enext_mixon/js/popup.windows.js");?>
<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/bootstrap_5.3.css");?>
<?CJSCore::Init(array('popup'))?>
<?global $USER;?>

<style>
    .container-xxl, .container-xl, .container-lg, .container-md, .container-sm, .container{
        max-width: 1440px;
    }
    .l-padding{
        padding-right: 15px;
        padding-left: 15px;
    }
    /***ФОРМЫ В СПЛЫВАЮЩИХ ОКНАХ НАЧАЛО***/
    .kontraktnoe-proizvodstvo-page{
        font-family: "Graphik LCG";
    }
    .kontraktnoe-proizvodstvo-page .popup-window-with-titlebar{
        font-family: 'Raleway', sans-serif;
    }
    .kontraktnoe-proizvodstvo-page .tittle_blocks{
        font-size: 70px !important;
        line-height: 70px;
        padding-bottom: 70px !important;
    }
    .kontraktnoe-proizvodstvo-page .popup-window{
        background-color: #212529;
        border: 2px solid #be8cfa;
        color: white;
    }
    .kontraktnoe-proizvodstvo-page .popup-window-close-icon{
        opacity: 1;
    }
    .mix-flex{
        display: flex !important;
    }
    .mix-nav-popup{
        font-size: 36px !important;
        font-weight: 800 !important;
        width: 340px !important;
        text-align: center !important;
        margin: 0 auto !important;
    }
    #form-succ .mix-nav-popup,
    #form-succ-feed .mix-nav-popup{
        font-size: 30px !important;
        width: 380px !important;
    }
    #get-price .mix-nav-popup{
        min-width: 340px !important;
    }
    .mix-popup-content{
        width: 400px !important;
    }
    .popup-window-titlebar{
        margin-top: 90px !important;
        height: 90px !important;
    }
    #form-succ .popup-window-titlebar{
        height: unset !important;
        margin-top: 20px !important;
    }
    #form-succ .mix-pr-popup-tittle-succ,
    #form-succ-feed .mix-pr-popup-tittle-succ{
        text-align: center !important;
    }
    .popup-window-titlebar-close-icon{
        top: 45px !important;
        right: 200px !important;
    }
    .popup-window-close-icon:after{
        background-image: url('/bitrix/templates/enext_mixon/images/icons/popup/popup_close_icon.png') !important;
        width: 30px !important;
        height: 30px !important;
    }
    .text-field__label {
        display: block !important;
        margin-bottom: 0.25rem !important;
    }

    .text-field__input,
    .kontraktnoe-proizvodstvo-page .popup-window .text-field__input{
        display: block !important;
        width: 400px !important;
        padding: 10px 0 !important;
        font-family: inherit !important;
        font-weight: 400 !important;
        line-height: 1.5 !important;
        background-color: transparent !important;
        background-clip: padding-box !important;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        border-width: 1px !important;
        border-color: #9a98fa !important;
        border-style: solid !important;
        border-radius: 10px !important;
        height: 52px !important;
        font-size: 22px !important;
        color: white !important;
    }
    .kontraktnoe-proizvodstvo-page .popup-window .text-field__input{
        width: 100% !important;
        padding-left: 50px !important;
        font-size: 18px !important;
    }
    .kontraktnoe-proizvodstvo-page .popup-window .invalid-feedback{
        font-weight: 800;
    }
    .text-field__input::placeholder {
        color: white !important;
        opacity: 0.4 !important;
    }

    .text-field__input:focus {
        color: #212529 !important;
        background-color: #fff !important;
        border-color: #be8cfa !important;
        outline: 0 !important;
    }

    .text-field__input:disabled,
    .text-field__input[readonly] {
        background-color: #f5f5f5 !important;
        opacity: 1 !important;
    }

    .text-field__icon {
        position: relative !important;
    }

    .text-field__icon::before {
        content: '' !important;
        color: #bdbdbd !important;
        position: absolute !important;
        display: flex !important;
        align-items: center !important;
        bottom: 0 !important;
        left: 20px !important;
        top: 27px !important;
        transform: translateY(-50%) !important;
    }
    .text-field__icon input {
        padding-left: 50px !important;
        margin-top: 10px !important;
    }
    .text-field__icon_name::before,
    .text-field__icon_phone::before,
    .text-field__icon_email::before,
    .text-field__icon_proiz::before,
    .text-field__icon_obiem::before{
        width: 25px !important;
        height: 25px !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
    }
    .text-field__icon_name::before {
        background-image: url("/bitrix/templates/enext_mixon/images/icons/popup/popup_name_icon.png") !important;
    }
    .text-field__icon_phone::before {
        background-image: url("/bitrix/templates/enext_mixon/images/icons/popup/popup_phone_icon.png") !important;
    }
    .text-field__icon_email::before {
        background-image: url("/bitrix/templates/enext_mixon/images/icons/popup/popup_mail_icon.png") !important;
    }
    .text-field__icon_proiz::before {
        background-image: url("/bitrix/templates/enext_mixon/images/icons/popup/popup_proiz_icon.png") !important;
    }
    .text-field__icon_obiem::before {
        background-image: url("/bitrix/templates/enext_mixon/images/icons/popup/popup_obiem_icon.png") !important;
    }
    .form-check-input {
        width: 25px !important;
        min-width: 25px !important;
        height: 25px !important;
        background-size: 25px !important;
        background-image: url("/bitrix/templates/enext_mixon/images/icons/popup/no-check.png") !important;
        border: none !important;
    }
    .form-check-input:focus{
        border-color: unset !important;
        box-shadow: unset !important;
    }
    .form-check-input:checked[type=checkbox]{
        background-image: url("/bitrix/templates/enext_mixon/images/icons/popup/check.png") !important;
    }
    .form-check-input:checked{
        background-color: unset !important;
        border-color: unset !important;
    }
    .form-check{
        margin-top: 20px !important;
        padding-left: 2.5em !important;
    }
    .form-check-label{
        font-size: 14px !important;
    }
    .form-check .form-check-input{
        margin-left: -2.5em !important;
    }
    .form_block_10 input{
        border-width: 3px;
        border-color: #9a98fa;
        border-style: solid;
        border-radius: 10px;
        height: 93px;
        font-size: 22px;
        color: white;
    }
    .form_block_10 input:focus{
        background: transparent;
        color: white;
    }
    .mix-form-button-block{
        margin: 30px auto 0 !important;
        justify-content: space-evenly !important;
        align-items: center !important;
        font-size: 20px !important;
        width: 180px !important;
        height: 60px !important;
        background-image: -moz-linear-gradient( 90deg, rgb(195,138,250) 0%, rgb(145,156,250) 100%) !important;
        background-image: -webkit-linear-gradient( 90deg, rgb(195,138,250) 0%, rgb(145,156,250) 100%) !important;
        background-image: -ms-linear-gradient( 90deg, rgb(195,138,250) 0%, rgb(145,156,250) 100%) !important;
        box-shadow: -11.388px 42.501px 35px 0px rgb(111 109 192 / 13%) !important;
        color: white !important;
        border-radius: 38px !important;
        cursor: pointer !important;
    }
    .mix-popup-price-block{
        width: 220px !important;
    }
    .form-check-input.is-invalid:checked{
        background-color: transparent !important;
    }
    .form-check-input.is-invalid{
        border-color: transparent !important;
    }
    .form-check-input.is-invalid:focus{
        box-shadow: none !important;
    }
    /***ФОРМЫ В СПЛЫВАЮЩИХ ОКНАХ КОНЕЦ***/
    #popup-window-content-get-offer.popup-window-content,
    #popup-window-content-get-price.popup-window-content,
    #popup-window-content-form-succ.popup-window-content,
    #popup-window-content-form-succ-feed.popup-window-content{
        background: transparent !important;
    }
    .container-xxl.block_2 img{
        width: 90px;
    }
    .container-xxl.block_3 img{
        /*width: 400px;*/
    }
    .block_4 img{
        width: 822px;
    }
    .block_4 img.second-img-block_4{
        width: 682px;
        margin-top: 26px;
    }
    .block_4 .third-img-block_4{
        width: 547px;
        top: 35px;
        left: -87px;
    }
    .block_5 img{
        max-width: 100%;
    /*max-height: 90%;*/
    }
    .block_5 .block_5-first-img{
        height: 85%;
    }
    .block_5 .block_5-second-img{
        height: 92%;
    }
    .block_5 .block_5-third-img{
        height: 100%;
    }
    .pop{
        cursor: pointer;
    }
    .block_1 .tittle_blocks{
        font-size: 120px !important;
        line-height: 100px;
        color: #a296fa;
        padding-bottom: 0 !important;
        padding-top: 50px;
    }
    .block_1 .subtittle_block{
        font-size: 53px !important;
    }
    .block_1 .sub-subtittle_block{
        font-size: 30px;
        line-height: 29px;
    }
    .container-fluid.after_block_1{
        margin-top: 120px !important;
        margin-bottom: 55px !important;
    }
    .container-fluid.after_block_3{
        margin-top: 63px !important;
        margin-bottom: 0px !important;
    }
    .container-fluid.after_block_3_4{
        margin-top: 0px !important;
        margin-bottom: 54px !important;
    }
    .container-fluid.after_block_4{
        margin-top: 60px !important;
        margin-bottom: 112px !important;
    }
    .container-fluid.after_block_7{
        margin-top: 90px !important;
        margin-bottom: 85px !important;
    }
    .container-fluid.after_block_8{
        margin-top: 27px !important;
        margin-bottom: 53px !important;
    }
    .container-fluid.after_block_9{
        margin-top: 53px !important;
        margin-bottom: 54px !important;
    }
    .container-fluid.after_block_10{
        margin-top: 87px !important;
    }
    .container-fluid.after_block_11{
        margin-bottom: 20px !important;
    }
    .button_block_1{
        margin-top: 57px;
    }
    .block_2_text{
        font-size: 24px;
        line-height: 24px;
        font-weight: 300;
        margin-top: 10px !important;
    }
    .block_2{
        margin-bottom: 110px;
    }
    .block_3_text{
        font-size: 40px;
        font-weight: 300;
        line-height: 50px;
    }
    .block_3_blocks{
        margin-bottom: 45px;
    }
    .block_3_4 .button_block_3_4{
        background-image: url("img/normal.png");
        background-size: contain;
        background-repeat: no-repeat;
        display: block;
        width: 36%;
        height: 14%;
        bottom: 20%;
    }
    .block_3_4 .button_block_3_4:hover{
        background-image: url("img/hover.png");
    }
    .block_4-text{
        font-size: 21px;
        line-height: 30px;
        margin-top: 34px;
    }
    .block_4-subtext{
        width: 75%;
        font-size: 21px;
        line-height: 35px;
        margin-top: 34px;
    }
    .block_5{
        margin-bottom: 110px;
    }
    .block_5 .block_5-m{
        margin-top: 14px;
    }
    .block_6 .block_6-m{
        margin-top: 15px;
    }
    .block_6 .block_6-m-b{
        margin-bottom: 50px;
    }
    .block_7{
        margin-top: 60px;
    }
    .block_7 .block_7-m{
        margin-top: 15px;
    }
    .block_7 .block_7-m-s{
        margin-top: 50px;
    }
    .block_8-text{
        font-size: 30px;
        line-height: 34px;
    }
    .block_8-m{
        margin-top: -50px;
    }
    .block_9-text{
        font-size: 34px;
        line-height: 37px;
        margin-top: 15px;
    }
    .block_10-m{
        margin-top: 46px;
    }
    .block_11_contacts > .l_city{
        font-size: 40px;
        color: #a5a5a6;
    }
    .block_11_contacts > .l_tel{
        font-size: 40px;
        color: #a5a5a6;
    }
    .block_11_contacts > .l_tel > a{
        color: #a5a5a6;
        text-decoration: none;
    }
    .block_11_contacts > .l_mail{
        font-size: 60px;
        color: #a097fa;
    }
    .block_1_info{
        width: 50%;
    }
    .block_1_img{
        width: 1500px;
        height: 1000px;
        bottom: -120px;
        left: -310px;
    }
    .button_block_1 > img,
    #get-offer > img,
    #get-price > img,
    #b_watsapp > img,
    #get-feedback{
        transition: 1s;
    }
    .button_block_1 > img:hover,
    #get-offer > img:hover,
    #get-price > img:hover,
    #b_watsapp > img:hover,
    #get-feedback:hover{
        transform: scale(1.05);
    }
    @media (max-width: 991px){
        .kontraktnoe-proizvodstvo-page .tittle_blocks{
            font-size: 30px !important;
            line-height: 30px;
            padding-bottom: 30px !important;
        }
        .container-xxl.block_2 img{
            width: 45px;
        }
        .container-xxl.block_3 img{
            width: 200px;
        }
        .block_2 > .fs-3{
            font-size: 10px !important;
        }
        .block_3 > .fs-1{
            font-size: 12px !important;
        }
        .container-xxl.block_4 img{
            width: 100%;
        }
        .container-xxl.block_5 img{
            max-width: 90%;
            max-height: 100%;
        }
        .form_block_10 input{
            height: 43px;
            font-size: 14px;
        }
        .block_1_info{
            width: 100%;
        }
        .block_1_img{
            opacity: .3 !important;
        }
    }
    @media (max-width: 840px){
        .text-field__input{
            width: 100% !important;
        }
        .mix-popup-content{
            width: 100% !important;
        }
        .popup-window.popup-window-with-titlebar{
            margin: 0 10px 0 0;
        }
        .popup-window-titlebar-close-icon{
            right: 180px !important;
        }
        .kontraktnoe-proizvodstvo-page .block_1{
            margin: 40px 0 0 0 !important;
        }
        .block_1 .tittle_blocks{
            font-size: 40px !important;
            line-height: 40px;
            padding-top: 25px;
        }
        .block_1 .subtittle_block{
            font-size: 25px !important;
        }
        .block_1 .sub-subtittle_block{
            font-size: 20px;
            line-height: 22px;
        }
        .button_block_1 {
            margin-top: 40px;
        }
        .container-fluid.after_block_1 {
            margin-top: 60px !important;
        }
        .container-fluid.after_block_9{
            margin-top: 13px !important;
            margin-bottom: 0px !important;
        }
        .container-fluid.after_block_10{
            margin-top: 10px !important;
        }
        .block_10-m{
            margin-top: 30px;
        }
        .block_2{
            margin-bottom: 65px;
        }
        .block_2_text{
            font-size: 15px;
            line-height: 18px;
            font-weight: 300;
            margin-top: 0px !important;
        }
        .block_3{
            margin-bottom: 0px;
        }
        .block_3_text{
            font-size: 17px;
            font-weight: 300;
            line-height: 20px;
        }
        .block_4-text{
            font-size: 17px;
            line-height: 22px;
            margin-top: 16px;
        }
        .block_4-subtext{
            width: 100%;
            font-size: 16px;
            line-height: 22px;
            margin-top: 14px;
        }
        .block_4 img.second-img-block_4{
            margin-top: 5px;
        }
        .block_4 .third-img-block_4{
            position: static !important;
            width: 75%;
            margin-left: auto;
            margin-right: auto;
        }
        .block_8-text{
            font-size: 18px;
            line-height: 25px;
        }
        .block_9-text{
            font-size: 19px;
            line-height: 25px;
        }
        .container-fluid.after_block_4{
            margin-top: 30px !important;
            margin-bottom: 20px !important;
        }
        .block_11_contacts > .l_city{
            font-size: 20px;
            color: #a5a5a6;
        }
        .block_11_contacts > .l_tel{
            font-size: 20px;
            color: #a5a5a6;
        }
        .block_11_contacts > .l_tel > a{
            color: #a5a5a6;
            text-decoration: none;
        }
        .block_11_contacts > .l_mail{
            font-size: 30px;
            color: #a097fa;
        }
        .w-m-100{
            width: 100% !important;
        }
        .w-m-75{
            width: 75% !important;
        }
        .block_1_img{
            width: 1000px;
        }
    }
</style>
    <div class="block_1 container-fluid">
        <!--<div class="position-relative">
            <img class="position-absolute top-0 left-0" src="<?/*=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_1/2.png'*/?>" alt="MIXON">
        </div>-->
        <div class="container-xxl l-padding pt-5 mt-5 pb-5 mb-5">
            <div class="row row-cols-1 row-cols-md-2">
                <div class="block_1_infoo z-1 row row-cols-1 col-12 col-md-6">
                    <div class="col w-50 w-m-75 gy-5">
                        <a target="_blank" href="https://mixon-lab.ru/" class="logo_block_1">
                            <img src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_1/3.png'?>" alt="MIXON">
                        </a>
                    </div>
                    <div class="col tittle_blocks fw-bold w-75 gy-5">Контрактное производство</div>
                    <div class="col subtittle_block text-white gy-0 gy-md-5">
                        <div class="w-m-100 lh-sm">бьюти-материалов под вашим брендом</div>
                    </div>
                    <div class="col sub-subtittle_block text-white gy-5">
                        <div class="w-m-100">Контрактное производство «Под ключ» - от дизайна до готового продукта</div>
                    </div>
                    <div data-popup="get-price" class="col gy-5 pop button_block_1">
                        <img src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_1/4.png'?>" alt="MIXON">
                    </div>
                </div>
                <div class="position-relative col-12 col-md-6">
                    <div class="z-0 block_1_img position-absolute d-flex flex-column justify-content-end">
                        <img class="" src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_1/1.png?25042024'?>" alt="MIXON">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid after_block_1">
        <img src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/hr.png' ?>">
    </div>
    <div class="block_2 container-xxl">
        <div class="row row-cols-2 row-cols-xl-4 justify-content-between fs-3 fw-medium text-white">
            <div class="col d-flex text-center flex-column">
                <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_2/1.png'?>" alt="Более 100+ готовых рецептур">
                <div class="w-100 m-auto p-3 block_2_text">Более 100+ готовых рецептур</div>
            </div>
            <div class="col d-flex text-center flex-column">
                <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_2/2.png'?>" alt="Высокое качество продукции">
                <div class="w-100 m-auto p-3 block_2_text">Высокое качество продукции</div>
            </div>
            <div class="col d-flex text-center flex-column">
                <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_2/3.png'?>" alt="Эксклюзивные разработки по вашему желанию">
                <div class="w-100 m-auto p-3 block_2_text">Эксклюзивные разработки по вашему желанию</div>
            </div>
            <div class="col d-flex text-center flex-column">
                <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_2/4.png'?>" alt="Производство в России">
                <div class="w-100 m-auto p-3 block_2_text">Производство в России</div>
            </div>
        </div>
    </div>
    <div class="block_3 container-xxl">
        <div class="row row-cols-2 row-cols-md-3 text-white">
            <div class="col d-flex flex-column block_3_blocks">
                <div class="me-auto ms-auto position-relative">
                    <img src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_3/1.png'?>" alt="Производим сами более 8 лет">
                    <div class="position-absolute block_3_text" style="bottom:8%;left:9%">Производим сами более 8 лет</div>
                </div>
            </div>
            <div class="col d-flex flex-column block_3_blocks">
                <div class="me-auto ms-auto position-relative">
                    <img src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_3/2.png'?>" alt="Порог входа от 12 900 руб">
                    <div class="position-absolute block_3_text" style="bottom:8%;left:9%">Порог входа от 12 900 руб.</div>
                </div>
            </div>
            <div class="col d-flex flex-column block_3_blocks">
                <div class="me-auto ms-auto position-relative">
                    <img src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_3/3.png'?>" alt="Производство от 0,5 кг./0,5 лит">
                    <div class="position-absolute block_3_text" style="bottom:8%;left:9%">Производство от 0,5 кг./0,5 лит.</div>
                </div>
            </div>
            <div class="col d-flex flex-column block_3_blocks">
                <div class="me-auto ms-auto position-relative">
                    <img src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_3/4.png'?>" alt="100+ готовых рецептур">
                    <div class="position-absolute block_3_text" style="bottom:8%;left:9%">100+ готовых рецептур</div>
                </div>
            </div>
            <div class="col d-flex flex-column block_3_blocks">
                <div class="me-auto ms-auto position-relative">
                    <img src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_3/5.png'?>" alt="Фасовка от 1 мл">
                    <div class="position-absolute block_3_text" style="bottom:8%;left:9%">Фасовка от 1 мл</div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid after_block_3">
        <img src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/hr.png'?>">
    </div>
    <div class="block_3_4 container-xxl pt-5 pb-5">
        <div class="row row-cols-1">
            <div class="position-relative">
                <video id="block_3_4_vid" style="width:100%;" autoplay loop muted playsinline>
                    <source src="constructor.mp4?150620230" type="video/mp4" autoplay="true">
                </video>
                <a class="button_block_3_4 position-absolute" href="https://mixon-lab.ru/product_constructor/"></a>
                <script>
                    $(document).ready(function(){ $("#block_3_4_vid")[0].play(); });
                </script>
            </div>
        </div>
    </div>
    <div class="container-fluid after_block_3_4">
        <img src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/hr.png'?>">
    </div>
    <div class="block_4 container-xxl pt-5 pb-5">
        <div class="row row-cols-1 row-cols-md-2 text-white">
            <div class="col-12 col-md-8">
                <div class="d-flex flex-column justify-content-between h-100">
                    <div class="fs-1 fw-bold tittle_blocks">О компании MIXON</div>
                    <div class="pt-5 pt-md-0">
                        <img src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_4/3.png' ?>" alt="Доставка готовых бьюти-средств осуществляется по всей России">
                    </div>
                    <div class="block_4-text pt-5 pt-md-0">У нас 2 собственных бренда (<span class="fw-bold">EXTREME LOOK, LASHMAKER</span>), которые успешно существуют на рынке и пользуются большим спросом.</div>
                    <div class="block_4-subtext pt-3 pt-md-0">Мы владеем собственной инновационной химической лабораторией с полным штатом высококвалифицированных химиков-технологов. Готовы взяться за любой объем продукции и доведем формулу до совершенства.</div>
                    <div class="fs-3 pt-5 pt-md-0">
                        <img class="second-img-block_4" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_4/2.png' ?>" alt="Доставка готовых бьюти-средств осуществляется по всей России">
                    </div>
                </div>
            </div>
            <div class="position-relative col-12 col-md-4">
                <div class="position-absolute third-img-block_4">
                    <img src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_4/1.png'?>" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid after_block_4">
        <img src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/hr.png'?>">
    </div>
    <div class="block_5 container-xxl pt-5">
        <div class="container fs-1 fw-bold text-white pb-5 tittle_blocks">Кто наши клиенты?</div>
        <div class="row row-cols-1 row-cols-md-3 block_5-m">
            <div class="col d-flex pt-5 pt-md-0">
                <img class="me-auto ms-auto block_5-first-img" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_5/1.png' ?>" alt="Бьюти-мастера">
            </div>
            <div class="col d-flex pt-5 pt-md-0">
                <img class="me-auto ms-auto block_5-second-img" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_5/2.png' ?>" alt="Владельцы собственных магазинов">
            </div>
            <div class="col d-flex pt-5 pt-md-0">
                <img class="me-auto ms-auto block_5-third-img" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_5/3.png' ?>" alt="Предприниматели, поставляющие продукцию на маркетплейсы">
            </div>
        </div>
    </div>
    <div class="block_6 container-xxl pt-5 mt-5">
        <div class="container fs-1 fw-bold text-white pb-5 tittle_blocks">Что мы можем для вас производить?</div>
        <div class="row row-cols-1 row-cols-md-2 block_6-m">
            <div class="col d-flex pt-5 pt-md-0 block_6-m-b">
                <img class="me-auto ms-auto" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_6/1.png' ?>" alt="Клей для наращивания ресниц">
            </div>
            <div class="col d-flex pt-5 pt-md-0 block_6-m-b">
                <img class="me-auto ms-auto" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_6/2.png' ?>" alt="Препараты для наращивания и ламинирования ресниц">
            </div>
            <div class="col d-flex pt-5 pt-md-0 block_6-m-b">
                <img class="me-auto ms-auto" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_6/3.png' ?>" alt="Ресницы для наращивания">
            </div>
            <div class="col d-flex pt-5 pt-md-0 block_6-m-b">
                <img class="me-auto ms-auto" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_6/4.png' ?>" alt="Пинцеты для наращивания ресниц">
            </div>
            <div class="col d-flex pt-5 pt-md-0 block_6-m-b">
                <img class="me-auto ms-auto" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_6/5.png' ?>" alt="Дезинфицирующие средства">
            </div>
            <div class="col d-flex pt-5 pt-md-0">
                <div class="position-relative">
                    <img class="me-auto ms-auto" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_6/6.png' ?>" alt="Получить предложение">
                    <div data-popup="get-offer" id="get-offer" class="pop position-absolute w-75 h-25" style="bottom:10%;left:11%;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="block_7 container-xxl">
        <div class="container fs-1 fw-bold text-white pb-5 tittle_blocks">Варианты сотрудничества</div>
        <div class="row row-cols-1 block_7-m">
            <div class="col d-flex pt-5 pt-md-0">
                <img class="me-auto ms-auto" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_7/1.png' ?>" alt="Готовый рецепт из нашей библиотеки">
            </div>
            <div class="col d-flex pt-5 pt-md-0 block_7-m-s">
                <img class="me-auto ms-auto" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_7/2.png' ?>" alt="Персонализация">
            </div>
            <div class="col d-flex pt-5 pt-md-0 block_7-m-s">
                <img class="me-auto ms-auto" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_7/3.png' ?>" alt="Эксклюзив">
            </div>
        </div>
    </div>
    <div class="container-fluid after_block_7">
        <img src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/hr.png'?>">
    </div>
    <div class="block_8 container-xxl">
        <div class="container fs-1 fw-bold text-white pb-5 tittle_blocks">Этапы работы</div>
        <div class="row row-cols-1 row-cols-md-2 text-white g-5 block_8-m">
            <div class="row col d-flex pt-md-0 gy-5">
                <div class="col-2">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/1.png'?>" alt="Оставьте заявку на сайте">
                </div>
                <div class="col-10">
                    <div class="block_8-text">Оставьте заявку на сайте или свяжитесь с нашим менеджером по контрактному производству.</div>
                    <div class="d-flex mt-4">
                        <div data-popup="get-offer" id="get-offer" class="pop" style="padding: 0 10px;">
                            <img class="me-auto ms-auto w-100" src="<?= SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/9.png' ?>" alt="Оставьте заявку на сайте">
                        </div>
                        <a target="_blank" id="b_watsapp" href="https://wa.me/79227421468" style="margin-left:auto;margin-right:50px;">
                            <img class="me-auto ms-auto w-100" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/10.png'?>" alt="Оставьте заявку WatsApp">
                        </a>
                    </div>
                </div>
            </div>
            <div class="row col d-flex pt-md-0 gy-5">
                <div class="col-2">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/2.png'?>" alt="Заполнение брифа на контрактное производство">
                </div>
                <div class="col-10 block_8-text">Заполнение брифа на контрактное производство. Для более подробного понимания ваших желаний, заполняете бриф, где детально указываете все, что хотели бы видеть в вашем продукте.</div>
            </div>
            <div class="row col d-flex pt-md-0 gy-5">
                <div class="col-2">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/3.png'?>" alt="Согласование условий сотрудничества">
                </div>
                <div class="col-10 block_8-text">Согласование условий сотрудничества. Наш менеджер связывается с вами и уточняет еще раз все детали, чтобы ничего не пропустить.</div>
            </div>
            <div class="row col d-flex pt-md-0 gy-5">
                <div class="col-2">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/4.png'?>" alt="Разработка индивидуального предложения">
                </div>
                <div class="col-10 block_8-text">Разработка индивидуального предложения. Составляет от 3-7 дней.</div>
            </div>
            <div class="row col d-flex pt-md-0 gy-5">
                <div class="col-2">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/5.png'?>" alt="Заключение договора">
                </div>
                <div class="col-10 block_8-text">Заключение договора. Подписываем договор и начинаем работу.</div>
            </div>
            <div class="row col d-flex pt-md-0 gy-5">
                <div class="col-2">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/6.png'?>" alt="Изготовление продукта">
                </div>
                <div class="col-10 block_8-text">После детального изучения вашего запроса мы погружаемся в работу и приступаем к изготовлению продукта.</div>
            </div>
            <div class="row col d-flex pt-md-0 gy-5">
                <div class="col-2">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/7.png'?>" alt="Производство">
                </div>
                <div class="col-10 block_8-text">Производство. За вами будет закреплен менеджер, который контролирует процесс и сопровождает вас на всех этапах производства.</div>
            </div>
            <div class="row col d-flex gy-5">
                <div class="col-0 col-md-1"></div>
                <div class="col-12 col-md-11 ps-md-5 pop" id="get-price" data-popup="get-price">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_8/8.png'?>" alt="Получить предложение">
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid after_block_8">
        <img src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/hr.png'?>">
    </div>
    <div class="block_9 container-xxl pt-5 mt-5 pb-5">
        <div class="container fs-1 fw-bold text-white pb-5 tittle_blocks">Дополнительные услуги</div>
        <div class="row row-cols-2 row-cols-md-4 fs-3 text-white g-5">
            <div class="col d-flex pt-5 pt-md-0">
                <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_9/1.png'?>" alt="Разработка эксклюзивных формул">
            </div>
            <div class="col d-flex pt-5 pt-md-0">
                <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_9/2.png'?>" alt="Дизайн продукции">
            </div>
            <div class="col d-flex pt-5 pt-md-0">
                <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_9/3.png'?>" alt="Сертификация товаров">
            </div>
            <div class="col d-flex pt-5 pt-md-0">
                <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_9/4.png'?>" alt="Регистрация товарного знака">
            </div>
        </div>
        <div class="pt-5 mt-5 pb-3">
            <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_9/5.png'?>" alt="Работать с нами – легко и удобно">
        </div>
        <div class="block_9-text text-white">Вы сами выбираете формат сотрудничества контрактного производства: от разработки формулы до полного создания образа вашего бренда. </div>
    </div>
    <div class="container-fluid after_block_9">
        <img src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/hr.png'?>">
    </div>
    <div class="block_10 form_block_10 container-xxl pt-5 mt-5 pb-5">
        <div class="container fs-1 fw-bold text-white pb-5 tittle_blocks">Начнем наше сотрудничество</div>
        <form action="POST" id="form-get-feedback">
            <div class="row row-cols-1 row-cols-md-2 fs-3 text-white g-5">
                <div class="col d-flex flex-column d-flex pt-md-0 block_10-m">
                    <div class="w-100">
                        <input type="email" class="form-control" name="CONTACTS-MAIL" id="l_email" placeholder="Ваша почта">
                    </div>
                </div>
                <div class="col d-flex flex-column pt-md-0 block_10-m">
                    <div class="w-100">
                        <input type="text" class="form-control" name="CONTACTS-NAME" id="l_contact" placeholder="Контактное лицо">
                    </div>
                </div>
                <div class="col d-flex flex-column pt-md-0 block_10-m">
                    <div class="w-100">
                        <input type="text" class="form-control" name="CONTACTS-PHONE" id="l_tel" placeholder="Телефон">
                    </div>
                </div>
                <div class="col d-flex flex-column pt-md-0 block_10-m">
                    <div class="w-100">
                        <input type="text" class="form-control" name="CONTACTS-COMPANY" id="l_company" placeholder="Ваша организация">
                    </div>
                </div>
                <div class="col d-flex flex-column pt-md-0 block_10-m">
                    <img data-action="get-feedback" id="get-feedback" class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH.'/images/kontractnoe-proizvodstvo/block_10/1.png'?>" alt="Связаться со мной">
                </div>
                <div class="col d-flex pt-md-0">
                    <div>Отправляя данные вы соглашаетесь с <a target="_blank" href="https://mixon-lab.ru/agreement/?id=2">политикой конфиденциальности</a></div>
                </div>
            </div>
        </form>
    </div>
    <div class="container-fluid after_block_10">
        <img src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/hr.png'?>">
    </div>
    <div class="block_11 container-xxl pt-5 pb-5">
        <div class="row row-cols-1 row-cols-md-3 block_11_contacts fs-3 text-white g-5">
            <div class="col col-md-2 l_city d-flex pt-md-0">г. Москва</div>
            <div class="col col-md-4 l_tel d-flex pt-md-0">
                тел.: <a href="tel:89227421468">8(922)742-14-68</a>
            </div>
            <div class="col col-md-6 l_mail d-flex pt-md-0">
                <a href="mailto:info@mixon-lab.ru">info@mixon-lab.ru</a>
            </div>
        </div>
    </div>
    <div class="container-fluid after_block_11">
        <img src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/hr.png'?>">
    </div>
    <div class="block_12 container-xxl mb-5 pb-5 pt-5">
        <div class="row row-cols-3 block_12_footer fs-3 text-white g-5">
            <div class="col-6 l_f_m d-flex pt-5 pt-md-0">2023 © MIXON. Все права защищены</div>
            <div class="col-3 l_f_vk d-flex pt-5 pt-md-0" style="justify-content: end;">
                <a target="_blank" href="https://vk.com/mixon_lab">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_12/1.png'?>" alt="Группа VK MIXON">
                </a>
            </div>
            <div class="col-3 l_f_l d-flex pt-5 pt-md-0">
                <a target="_blank" href="https://mixon-lab.ru/">
                    <img class="me-auto ms-auto" src="<?=SITE_TEMPLATE_PATH . '/images/kontractnoe-proizvodstvo/block_12/2.png'?>" alt="Логотип MIXON">
                </a>
            </div>
        </div>
    </div>

    <script>
        (function(w,d,u){
            var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
            var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn-ru.bitrix24.ru/b5123257/crm/site_button/loader_7_pt5kbj.js');
    </script>
    <script>
        $('#block_3_4_vid')[0].play();
    </script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>