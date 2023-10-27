<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Loader;
\Bitrix\Main\UI\Extension::load("ui.buttons");
    use Bitrix\Main\Composite;
use Bitrix\Main\Composite\Helper;

if(!$USER->IsAdmin() || !$USER->GetID() == 10354){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
if(Helper::isOn())
    $sizeChache = Helper::getCacheFileSize();

$APPLICATION->SetTitle("Тестовая среда для разработчика");?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
    <?if($sizeChache) echo'Размер кеша: ' . CFile::FormatSize($sizeChache)?>
<br /><br /><br />
<button onclick="push()">Отправить тестовое письмо</button>
<div id="res_push"></div>
<?

//Тестируй код тут

////***

//Тестируй код тут

?>
<script>
function push(){
    BX.ajax.post(
        '/ajax/form-ajax.php',
        {action: 'test-message'},
        function(data){
            var result = JSON.parse(data);
            if(result.res)
                BX.adjust(BX('res_push'), {text: 'Письмо отправлено'});
            else
                BX.adjust(BX('res_push'), {text: 'Ошибка отправки'});

            console.log(result);
        }
    );
}
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>