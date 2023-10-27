<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle('Логи крона');
if(!$USER->IsAdmin() || !$USER->GetID() == 10354){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
<table style="margin:10px 0">
    <tr>
        <td><button onclick="reloadData()">Перезагрузить</button></td>
        <td><button onclick="remDataFile()">Очистить файл</button></td>
    </tr>
    <tr>
        <td colspan="2" style="border-bottom: 2px solid gray"></td>
    </tr>
</table>
<div id="file_log_block"></div>
<script>
    BX.ready(function () {
        var arrData = {};
        arrData['action'] = 'loadFileLogsCron';
        BX.ajax.post(
            '/bitrix/admin/extremelook_log_dump_ajax.php',
            arrData,
            function (data) {
                var resultOBJ = JSON.parse(data);
                if(!data.error) BX.adjust(BX('file_log_block'), {html: '<pre>' + resultOBJ.result + '</pre>'});
                else BX.adjust(BX('file_log_block'), {text: 'Ошибка'});
            }
        );
    });
    function remDataFile() {
        var arrData = {};
        arrData['action'] = 'removeDataLogsCron';
        BX.ajax.post(
            '/bitrix/admin/extremelook_log_dump_ajax.php',
            arrData,
            function (data) {
                var resultOBJ = JSON.parse(data);
                if(!resultOBJ.error) BX.adjust(BX('file_log_block'), {text: 'Файл очищен'});
                else BX.adjust(BX('file_log_block'), {text: 'Ошибка'});
            }
        );
    }
    function reloadData() {
        var arrData = {};
        arrData['action'] = 'loadFileLogsCron';
        BX.ajax.post(
            '/bitrix/admin/extremelook_log_dump_ajax.php',
            arrData,
            function (data) {
                var resultOBJ = JSON.parse(data);
                if(!resultOBJ.error) BX.adjust(BX('file_log_block'), {html: '<pre>' + resultOBJ.result + '</pre>'});
                else BX.adjust(BX('file_log_block'), {text: 'Ошибка'});
            }
        );
    }
</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
