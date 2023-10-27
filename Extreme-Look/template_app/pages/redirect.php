<?
if(isset($_GET['action'])){
    if($_GET['action'] == 'feedback' && isset($_GET['orderid'])){?>
        <div id="redirecting-push" data-redirect="<?='https://extreme-look.ru/feedback/?ORDER_ID='.$_GET['orderid']?>"></div>
    <?}elseif($_GET['action'] == 'ozon'){?>
        <div id="redirecting-push" data-redirect="https://www.ozon.ru/seller/extreme-look-817815/?miniapp=seller_817815"></div>
    <?}elseif($_GET['action'] == 'wb'){?>
        <div id="redirecting-push" data-redirect="https://www.wildberries.ru/seller/56540"></div>
    <?}elseif($_GET['action'] == 'mixon'){?>
        <div id="redirecting-push" data-redirect="https://mixon-lab.ru/"></div>
    <?}elseif($_GET['action'] == 'landing'){?>
        <div id="redirecting-push" data-redirect="https://extreme-look.ru/kontraktnoe-proizvodstvo/"></div>
    <?}elseif($_GET['action'] == 'telegram'){?>
        <div id="redirecting-push" data-redirect="https://t.me/extreme_look_ru"></div>
    <?}?>
<?}

