<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");?>
<?
/*    $APPLICATION->IncludeComponent(
        "altop:fortuna.vlad", "",
        array(
            "CACHE_TYPE" => "N"
        ),
        false
    );*/
?>

    <a href="javascript:void(0)" onclick="delLocal()">Клик</a>
    <script>
        function delLocal(){
            localStorage.removeItem('fortunaLastView');
        }
    </script>
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>