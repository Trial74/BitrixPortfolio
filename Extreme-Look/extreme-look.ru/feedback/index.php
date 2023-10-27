<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обратная связь");
$APPLICATION->AddHeadScript('/bitrix/templates/enext/js/feedback.js');

$orderData = \Bitrix\Sale\Order::getList([ //Получаем ID последнего заказа для проверки от всяких хулиганов
    'select' => ['ID'],
    'order' => ['ID' => 'DESC'],
    'limit' => 1
]);
if ($order = $orderData->fetch())
{
    $lastOrderId = $order['ID'];
}

if(isset($_GET['ORDER_ID']) && !empty($_GET['ORDER_ID'])) { //Существует ли ID заказа в GET
    if ($_GET['ORDER_ID'] <= $lastOrderId) { //ID заказа должен быть меньше чем ID последнего заказа иначе эрор
        $order = \Bitrix\Sale\Order::load($_GET['ORDER_ID']); //Вытягиваем заказ
        $userId = $order->getUserId();
        $typeOrder = $order->getField("PERSON_TYPE_ID"); //Определяем тип плательщика чтобы вытянуть нужное свойство

        $propertyCollection = $order->getPropertyCollection(); //Вытягиваем объект свойств заказа
        $nameUser = $propertyCollection->getItemByOrderPropertyId(1); //Имя пользователя
        $mailUser = $propertyCollection->getItemByOrderPropertyId(2); //Почта пользователя
        $telUser = $propertyCollection->getItemByOrderPropertyId(3); //Телефон пользователя

        if ($typeOrder == 1) { //Если тип плательщика физ лицо то ИД свойства 40
            $somePropValue = $propertyCollection->getItemByOrderPropertyId(40);
        }
        if ($typeOrder == 2) { //Если тип плательщика юр лицо то ИД свойства 41
            $somePropValue = $propertyCollection->getItemByOrderPropertyId(41);
        }
    }
}?>

<?if(isset($_GET['ORDER_ID']) && !empty($_GET['ORDER_ID']) && isset($_GET['SUCCESS']) && !empty($_GET['SUCCESS']) && $_GET['SUCCESS'] == 1 && $somePropValue->getValue() == "N"){

    $somePropValue->setValue("Y"); //Помечаем заказ на котором оставлен отзыв
    $order->save();

    $to = '/////';
    $subject = 'Обратный отзыв по заказу - ' . $_GET['ORDER_ID'];
    $message = 'Оставлен обратный отзыв по заказу:<br />ID пользователя: ' . $userId . '<br />Имя пользователя: ' . $nameUser->getValue() . '<br />Насколько легко было сделать заказ на сайте?: ' . $_REQUEST['r1'] . '<br />Насколько Вы довольны последним заказом?: ' . $_REQUEST['r2'] . '<br />Телефон покупателя: ' . $telUser->getValue() . '<br />Почта покупателя: ' . $mailUser->getValue();
    $headers = 'From: EXTREME LOOK <no-reply@extreme-look.ru>' . "\r\n";
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($to, $subject, $message, $headers);

    $el = new CIBlockElement;

    $PROP = array();
    $PROP[840] = $_REQUEST['r1']; //Отзыв на первый вопрос
    $PROP[841] = $_REQUEST['r2']; //Отзыв на второй вопрос
    $PROP[842] = $_GET['ORDER_ID']; //Номер заказа
    $PROP[843] = $nameUser->getValue(); //Имя пользователя
    $PROP[844] = $telUser->getValue(); //Телефон
    $PROP[845] = $mailUser->getValue(); //Почта
    $PROP[846] = $userId; //ИД пользователя

    $arLoadProductArray = Array(
        "MODIFIED_BY"    => $userId, // элемент изменен текущим пользователем
        "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
        "IBLOCK_ID"      => 108,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => "Отзыв по заказу № - " . $_GET['ORDER_ID'],
        "ACTIVE"         => "Y"
    );

    if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        echo "<div class=\"rat-feed-text\">Ваша оценка успешно принята</div>";?>
        <script>setTimeout( 'location="https://extreme-look.ru";', 2000 );</script>
    <?}
    else
        echo "<div class=\"rat-feed-text\">Ошибка</div>"
    ?>

<?}
else
if(isset($_GET['ORDER_ID']) && !empty($_GET['ORDER_ID'])):?>
    <?if($_GET['ORDER_ID'] > 20094 && $_GET['ORDER_ID'] <= $lastOrderId): //Проверка что ID заказа не менее 20094 (момент запуска обратной связи) и не более ID последнего заказа на данный момент?>
        <?if($somePropValue->getValue() == "N"):?>
            <div class="rat-feed-text">Насколько легко было сделать заказ на сайте?</div>
            <br>
            <div id="rating-feed-order" class="rating-feed">
                <div class="rat" id="rat1">1</div>
                <div class="rat" id="rat2">2</div>
                <div class="rat" id="rat3">3</div>
                <div class="rat" id="rat4">4</div>
                <div class="rat" id="rat5">5</div>
                <div class="rat" id="rat6">6</div>
                <div class="rat" id="rat7">7</div>
                <div class="rat" id="rat8">8</div>
                <div class="rat" id="rat9">9</div>
                <div class="rat" id="rat10">10</div>
                <span id="thanks">Спасибо! <br />Ваша оценка принята</span>
                <span id="re-rat">Изменить оценку</span>
            </div>
            <div class="block-text-by-rat" id="bt1">
                <div class="rat-text-ok">Очень сложно</div>
                <div class="rat-text-reok">Очень легко!</div>
            </div>
            <br><br><br>
            <div class="rat-feed-text">Насколько Вы довольны последним заказом?</div>
            <br>
            <div id="rating-feed-liked" class="rating-feed">
                <div class="ratl" id="ratl1">1</div>
                <div class="ratl" id="ratl2">2</div>
                <div class="ratl" id="ratl3">3</div>
                <div class="ratl" id="ratl4">4</div>
                <div class="ratl" id="ratl5">5</div>
                <div class="ratl" id="ratl6">6</div>
                <div class="ratl" id="ratl7">7</div>
                <div class="ratl" id="ratl8">8</div>
                <div class="ratl" id="ratl9">9</div>
                <div class="ratl" id="ratl10">10</div>
                <span id="thanksl">Спасибо! <br />Ваша оценка принята</span>
                <span id="re-ratl">Изменить оценку</span>
            </div>
            <div class="block-text-by-rat" id="bt2">
                <div class="rat-text-ok">Крайне недоволен!</div>
                <div class="rat-text-reok">Полностью доволен!</div>
            </div>
        <hr>
            <form method="post" name="feed" action="?ORDER_ID=<?=$_GET['ORDER_ID']?>&SUCCESS=1" onsubmit="return validateForm()">
                <input type="hidden" name="r1" id="ratingvalue1" />
                <input type="hidden" name="r2" id="ratingvalue2" />
                <button type="submit" class="btn btn-buy"><span>Отправить</span></button>
            </form>
        <?else:?>
            <div class="rat-feed-text">Данный заказ уже был оценен</div>
        <?endif;?>
    <?else:?>
        <div class="rat-feed-text">Ошибка формы обратной связи, заказ не найден.</div>
    <?endif;?>
<?else:?>
    <div class="rat-feed-text">Ошибка формы обратной связи</div>
<?endif;?>
<script>
    function validateForm() {
        var r1 = document.forms["feed"]["r1"].value,
            r2 = document.forms["feed"]["r2"].value;

        if (r1 == "") {
            alert("Необходимо ввести оценку 1");
            return false;
        }
        if (r2 == "") {
            alert("Необходимо ввести оценку 2");
            return false;
        }
    }
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>