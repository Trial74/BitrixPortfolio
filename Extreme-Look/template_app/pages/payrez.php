<style>
    .block-rez{
        font-family: 'Graphik LCG';
        font-weight: 500;
        font-size: 16px;
        color: white;
    }
    .rez-err{
        background: #B867FE !important;
    }
    .block-rez.rez-succ,
    .block-rez.rez-err{
        background: #7b66fe;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
        margin-top: 25%;
        padding: 10px 15px 10px 15px;
        text-align: center;
        border-radius: 10px;
        display: flex;
    }
    .logo-bank{
        height: 57px;
    }
    .message-pay{
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 15px;
    }
    .button-rez-pay{
        width: 92%;
        margin-top: 20px;
        padding-left: 15px;
        padding-right: 15px;
        display: inline-flex;
    }
    .but1,
    .but2{
        width: 50%;
        padding-left: 5px;
        padding-right: 5px;
    }
</style>
<?if(isset($_GET['sber_suc']) && !empty($_GET['sber_suc']) && $_GET['sber_suc'] == 'Y'){?>
    <div class="block-rez rez-succ">
        <div class="logo-bank"><img src="/bitrix/templates/mobileapp/pages/includes/payrezult/logo-bank/sber-rez.png" alt="Сбербанк"></div>
        <div class="message-pay">Заказ №-<?=$_GET['order']?> успешно оплачен.</div>
    </div>
<?}elseif(isset($_GET['sber_err']) && !empty($_GET['sber_err']) && $_GET['sber_err'] == 'Y'){?>
    <div class="block-rez rez-err">
        <div class="logo-bank"><img src="/bitrix/templates/mobileapp/pages/includes/payrezult/logo-bank/sber-rez.png" alt="Сбербанк"></div>
        <div class="message-pay">Ошибка оплаты заказа №-<?=$_GET['order']?>.</div>
    </div>
<?}elseif(isset($_GET['tin_rez']) && !empty($_GET['tin_rez']) && $_GET['tin_rez'] == 'Y'){?>
    <div class="block-rez rez-succ">
        <div class="logo-bank"><img src="/bitrix/templates/mobileapp/pages/includes/payrezult/logo-bank/tinkof-rez.png" alt="Тинькофф"></div>
        <div class="message-pay">Заявка на рассрочку по заказу №-<?=$_GET['order']?> успешно оставлена.</div>
    </div>
<?}elseif(isset($_GET['tin_err']) && !empty($_GET['tin_err']) && $_GET['tin_err'] == 'Y'){?>
    <div class="block-rez rez-err">
        <div class="logo-bank"><img src="/bitrix/templates/mobileapp/pages/includes/payrezult/logo-bank/tinkof-rez.png" alt="Тинькофф"></div>
        <div class="message-pay">Ошибка рассрочки по заказу №-<?=$_GET['order']?></div>
    </div>
<?}elseif(isset($_GET['sbercred_rez']) && !empty($_GET['sbercred_rez']) && $_GET['sbercred_rez'] == 'Y'){?>
    <div class="block-rez rez-succ">
        <div class="logo-bank"><img src="/bitrix/templates/mobileapp/pages/includes/payrezult/logo-bank/sber-rez.png" alt="Сбербанк"></div>
        <div class="message-pay">Заявка на рассрочку по заказу №-<?=$_GET['PAYMENT_ID']?> успешно оставлена.</div>
    </div>
<?}elseif(isset($_GET['sbercred_err']) && !empty($_GET['sbercred_err']) && $_GET['sbercred_err'] == 'Y'){?>
    <div class="block-rez rez-err">
        <div class="logo-bank"><img src="/bitrix/templates/mobileapp/pages/includes/payrezult/logo-bank/sber-rez.png" alt="Сбербанк"></div>
        <div class="message-pay">Ошибка рассрочки по заказу №-<?=$_GET['PAYMENT_ID']?></div>
    </div>
<?}else{?>
    <div class="block-rez rez-err">
        Ошибка
    </div>
<?}?>
<div class="button-rez-pay">
    <div class="but1">
        <a id="goToHome" href="#" class="tab-link button button-fill link">На главную</a>
    </div>
    <div class="but2">
        <a href="/?page=personal/orders&<?=MOBILE_GET?>=Y" class="tab-link button button-fill link">Мои заказы</a>
    </div>
</div>
