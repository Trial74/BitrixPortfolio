<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена - Ошибка 404");?>
<div class="page-404">
    <div class="img-block">
        <img src="/images/404.jpg" />
    </div>
    <div class="text-block">
        <h2>Ой... Мы не можем найти эту страницу!</h2>
        <p>Мы сожалеем, но страница на которую Вы пытались перейти не существует.</p>
        <p>Пожалуйста вернитесь на <a href="https://extreme-look.ru">главную</a> страницу или воспользуйтесь меню сайта</p>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>