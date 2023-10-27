<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");?>
<?IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Loader,
    Bitrix\Main\Entity;

\Bitrix\Main\UI\Extension::load("ui.hint");
\Bitrix\Main\UI\Extension::load("ui.buttons");

if(!$USER->IsAdmin() || !$USER->GetID() == 10354){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$APPLICATION->SetPageProperty("title", "Выгрузка контрагентов из 1С");
$APPLICATION->SetTitle("Выгрузка контрагентов из 1С");
CJSCore::Init(array("jquery"));
$aTabs = array(
    array("DIV" => "files", "TAB" => GetMessage('EXTREME_1C_CONTRAGENTS_TAB_1')),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
<?$tabControl->Begin();
$tabControl->BeginNextTab();?>
    <div>
        <button onclick="reloadData()" class="ui-btn ui-btn-xs">Перезагрузить</button>
        <button onclick="remDataFile()" class="ui-btn ui-btn-xs">Очистить файл</button>
    </div>
    <div id="file_log_block_import"></div>
<?$tabControl->EndTab();
$tabControl->End();?>
<table border="1" id="cfiles" class="ex_table-contragents">
    <caption>Файлы выгрузки из 1С</caption>
    <thead>
        <tr>
            <td>Имя файла</td>
            <td>Дата записи</td>
            <td>Размер</td>
            <td>Построить таблицу бонусов</td>
            <td>Подсчёт партнёров в файле</td>
        </tr>
    </thead>
</table>
<hr>
<table id="cashResult" class="ex_table-contragents">
    <caption>Таблица бонусов партнёров</caption>
    <thead>
    <tr>
        <td>Имя партнёра</td>
        <td>Почта партнёра</td>
        <td>Партнёр на сайте</td>
        <td>Количество бонусов</td>
    </tr>
    </thead>
</table>
<script>
    BX.ready(function(){
        BX.EX.unloadingPart.init({
            ajaxUrl: '/bitrix/admin/extremelook_contragents_ajax_1C.php',
            tableFiles: $('#cfiles'),
            tableCash: $('#cashResult')
        });
        var arrData = {};
        arrData['action'] = 'loadFileLogsImport';
        BX.ajax.post(
            '/bitrix/admin/extremelook_log_dump_ajax.php',
            arrData,
            function (data) {
                var resultOBJ = JSON.parse(data);
                if(!data.error) BX.adjust(BX('file_log_block_import'), {html: '<pre>' + resultOBJ.result + '</pre>'});
                else BX.adjust(BX('file_log_block_import'), {text: 'Ошибка (файл пуст)'});
            }
        );
    });
    function remDataFile() {
        var arrData = {};
        arrData['action'] = 'removeDataLogsImport';
        BX.ajax.post(
            '/bitrix/admin/extremelook_log_dump_ajax.php',
            arrData,
            function (data) {
                var resultOBJ = JSON.parse(data);
                if(!resultOBJ.error) BX.adjust(BX('file_log_block_import'), {text: 'Файл очищен'});
                else BX.adjust(BX('file_log_block_import'), {text: 'Ошибка'});
            }
        );
    }
    function reloadData() {
        var arrData = {};
        arrData['action'] = 'loadFileLogsImport';
        BX.ajax.post(
            '/bitrix/admin/extremelook_log_dump_ajax.php',
            arrData,
            function (data) {
                var resultOBJ = JSON.parse(data);
                if(!resultOBJ.error) BX.adjust(BX('file_log_block_import'), {html: '<pre>' + resultOBJ.result + '</pre>'});
                else BX.adjust(BX('file_log_block_import'), {text: 'Ошибка (файл пуст)'});
            }
        );
    }
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>