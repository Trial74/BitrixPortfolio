<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");
use Bitrix\Highloadblock as HL,
    Bitrix\Main\Entity;
    \Bitrix\Main\UI\Extension::load("ui.hint");
    \Bitrix\Main\UI\Extension::load("ui.buttons");

if(!$USER->IsAdmin() || !$USER->GetID() == 10354){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$APPLICATION->SetPageProperty("title", "Чистильщик места на сайте");
$APPLICATION->SetTitle("Очистка места на сайте");

$hlbl = 7;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rootDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/iblock'; //По умолчанию
$backupDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/backup_clear'; //По умолчанию

$rsData = $entity_data_class::getList(array(
    "select" => array("*")
));

$mainDir = $_SERVER["DOCUMENT_ROOT"] . '/upload';
$mainDirOpen = opendir($mainDir);
$strDirsByMain = '<tr><td colspan="2"><div class="div-links-by-dir">';
while(false !== ($mainDirRead = readdir($mainDirOpen))) {
    if ($mainDirRead == '.' || $mainDirRead == '..') {
        continue;
    }
    $explodeDir = explode(".", $mainDirRead);
    if(count($explodeDir) > 1) continue;
    $strDirsByMain .= "<div onclick=clickLinkByDir('$mainDirRead') class=link-dir id='mainDirRead'>$mainDirRead</div>";

}
$strDirsByMain .= '</div></td></tr>';

$aTabs = array( //Массив для вкладок на странице
    array("DIV" => "settings", "TAB" => GetMessage('EXTREME_FILE_MEMORY_TAB_1'), "ICON"=>"main_user_edit", "TITLE" => GetMessage('EXTREME_FILE_MEMORY_SETTINGS')),
    array("DIV" => "logs", "TAB" => GetMessage('EXTREME_FILE_MEMORY_TAB_2'), "ICON"=>"main_user_edit", "TITLE" => GetMessage('EXTREME_FILE_MEMORY_LOGS'))
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
    <style>
        .div-links-by-dir{
            display: flex;
            height: 80px;
            flex-direction: column;
            flex-wrap: wrap;
            align-content: center;
            align-items: stretch;
            justify-content: center;
        }
        .link-dir{
            margin: 2px 5px;
            background: #bdbdbd;
            color: black;
            padding: 4px;
            border: solid 1px black;
            border-radius: 3px;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
        }
        .link-dir:hover{
            background: white;
            text-decoration: none;
        }
        .table-logs-class > td{
            width: 14.285714285%;
        }
        table#logs_edit_table>tbody>tr:nth-child(odd){
            background-color: #e0e8ea;
        }
        table#logs_edit_table>tbody>tr:nth-child(even){
            background-color: white;
        }
        @media (max-width: 1620px){
            .div-links-by-dir {
                height: 100px;
            }
        }
        @media (max-width: 1320px){
            .div-links-by-dir {
                height: 120px;
            }
        }
    </style>
<?$tabControl->Begin();
$tabControl->BeginNextTab();?>

    <tr class="heading">
        <td colspan="2"><b><?=GetMessage('EXTREME_FILE_MEMORY_HEAD_1')?></b></td>
    </tr>
    <?=$strDirsByMain?>
    <tr>
        <td width="50%" class="adm-detail-content-cell-l"><?=GetMessage('EXTREME_FILE_MEMORY_ROOT_DIR')?></td>
        <td width="50%" class="adm-detail-content-cell-r"><input type="text" size="70" maxlength="255" value="<?=$rootDir?>" id="root_dir" disabled></td>
    </tr>
    <tr>
        <td width="50%" class="adm-detail-content-cell-l"><?=GetMessage('EXTREME_FILE_MEMORY_BACKUP_DIR')?></td>
        <td width="50%" class="adm-detail-content-cell-r"><input type="text" size="70" maxlength="255" value="<?=$backupDir?>" id="backup_dir"></td>
    </tr>
    <tr>
        <td width="50%" class="adm-detail-content-cell-l"><?=GetMessage('EXTREME_FILE_MEMORY_LIMIT')?></td>
        <td width="50%" class="adm-detail-content-cell-r"><input type="text" size="30" maxlength="255" value="0" id="limit"></td>
    </tr>
    <tr>
        <td width="50%" class="adm-detail-content-cell-l"><?=GetMessage('EXTREME_FILE_MEMORY_SLEEP')?></td>
        <td width="50%" class="adm-detail-content-cell-r"><input type="text" size="30" maxlength="255" value="0" id="sleep"></td>
    </tr>
    <tr>
        <td width="50%" class="adm-detail-content-cell-l"><?=GetMessage('EXTREME_FILE_MEMORY_NUMBER_DIR')?></td>
        <td width="50%" class="adm-detail-content-cell-r"><input type="text" size="5" maxlength="8" value="0" id="number_dir"><input class="adm-btn-save"
                                                                                                                                        type="button"
                                                                                                                                        id="butStart"
                                                                                                                                        value="<?=GetMessage('EXTREME_FILE_MEMORY_NUMBER_DIR_BUTTON')?>"
                                                                                                                                        title="<?=GetMessage('EXTREME_FILE_MEMORY_NUMBER_DIR_BUTTON')?>"
                                                                                                                                        onclick="numberDir()"
            /></td>
    </tr>


<?$tabControl->BeginNextTab();?>
    <tr class="heading">
        <td colspan="7"><b><?=GetMessage('EXTREME_FILE_MEMORY_HEAD_LOGS')?></b></td>
    </tr>
    <tr class="adm-list-table-header">
        <td class="adm-list-table-cell" style="text-align: start;"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_MEMORY_TABLE_HEAD_LOGS_1')?></div></td>
        <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_MEMORY_TABLE_HEAD_LOGS_2')?></div></td>
        <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_MEMORY_TABLE_HEAD_LOGS_3')?></div></td>
        <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_MEMORY_TABLE_HEAD_LOGS_4')?></div></td>
        <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_MEMORY_TABLE_HEAD_LOGS_5')?></div></td>
        <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_MEMORY_TABLE_HEAD_LOGS_6')?></div></td>
        <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_MEMORY_TABLE_HEAD_LOGS_7')?></div></td>
    </tr>

<?while($arData = $rsData->Fetch()){?>
    <tr class="table-logs-class">
        <td style="text-align: start;padding: 5px 4px 7px 10px;"><b><?=$arData['UF_DATE_TIME']?></b></td>
        <td><b><?=$arData['UF_SIZE_CLEAR']?></b></td>
        <td><b><?=$arData['UF_QUANTITY_DIR']?></b></td>
        <td><b><?=$arData['UF_SEARCH_BASE']?></b></td>
        <td><b><?=$arData['UF_CLEAR_SESS']?></b></td>
        <td><b><?=$arData['UF_TIME_WORK']?></b></td>
        <td><b><?=$arData['UF_ALL_FILES']?></b></td>
    </tr>
<?}?>

<?$tabControl->EndTab();?>
<?$tabControl->Buttons(
    array( //Вырубаем стандартный набор кнопок
        "disabled" => false,
        "btnApply" => false,
        "back_url" => false,
        "btnSave" => false
    )
);?>
    <input class="adm-btn-save"
           type="button"
           id="butStart"
           value="<?=GetMessage('EXTREME_FILE_MEMORY_START')?>"
           title="<?=GetMessage('EXTREME_FILE_MEMORY_START')?>"
           onclick="startSubmit()"
    />
    <input class="adm-btn-save"
           type="button"
           id="butStart"
           value="<?=GetMessage('EXTREME_FILE_MEMORY_COUNT_ROOT_DIR')?>"
           title="<?=GetMessage('EXTREME_FILE_MEMORY_COUNT_ROOT_DIR')?>"
           onclick="countDir()"
    />
<?$tabControl->End();?>
    <table width="100%">
        <tr>
            <td>
                <div id="spinner"></div>
                <div id="message_rez"></div>
            </td>
        </tr>
    </table>

    <script>

        function clickLinkByDir(link){
            BX('root_dir').value = '/var/www/u0907786/data/www/extreme-look.ru/upload/' + link;
        }

        function numberDir() {
            var val = BX('number_dir').value,
                data = {};

            if(val !== ''){
                data['action'] = 'namedir';
                data['val'] = val;
                data['root_dir'] = BX('root_dir').value;

                BX.ajax.post(
                    '/bitrix/admin/extremelook_file_memory_ajax.php',
                    data,
                    function (data) {
                        var result = JSON.parse(data);
                        BX.cleanNode(BX('spinner'));
                        BX('message_rez').innerHTML = result.message;
                        BX.adjust(buttonStart, {props: {disabled: false}});
                    }
                );
            }
            else BX('message_rez').innerHTML = err_mess('Поле \'Номер итерации\' не заполнено');
        }

        function countDir() {
            var data = {},
                buttonWait = new BX.UI.Button(),
                buttonStart = BX('butStart');
            if(examination_input()) {
                buttonWait.setClocking([flag=true]);
                buttonWait.renderTo(BX('spinner'));

                BX('message_rez').innerHTML = 'Идёт подсчёт каталогов, ожидайте завершения';
                BX.adjust(buttonStart, {props: {disabled: true}});

                data['action'] = 'countdir';
                data['root_dir']    = BX('root_dir').value;

                BX.ajax.post(
                    '/bitrix/admin/extremelook_file_memory_ajax.php',
                    data,
                    function (data) {
                        var result = JSON.parse(data);
                        BX.cleanNode(BX('spinner'));
                        BX('message_rez').innerHTML = result.message;
                        BX.adjust(buttonStart, {props: {disabled: false}});
                    }
                );
            } else BX('message_rez').innerHTML = err_mess('Поле \'Каталог очистки\' не заполнено');
        }
        function startSubmit(){
            var data = {},
                buttonWait = new BX.UI.Button(),
                buttonStart = BX('butStart');

            if(examination_input()){
                buttonWait.setClocking([flag=true]);
                buttonWait.renderTo(BX('spinner'));

                BX('message_rez').innerHTML = 'Очистка началась, ожидайте завершения';
                BX.adjust(buttonStart, {props: {disabled: true}});

                data['action']      = 'start';
                data['limit']       = BX('limit').value;
                data['sleep']       = BX('sleep').value;
                data['root_dir']    = BX('root_dir').value;
                data['backup_dir']  = BX('backup_dir').value;

                BX.ajax.post(
                    '/bitrix/admin/extremelook_file_memory_ajax.php',
                    data,
                    function (data) {
                        var result = JSON.parse(data);
                        BX.cleanNode(BX('spinner'));
                        BX('message_rez').innerHTML = result.message;
                        BX.adjust(buttonStart, {props: {disabled: false}});
                    }
                );
            } else BX('message_rez').innerHTML = err_mess('Поле \'Каталог очистки\' не заполнено');
        }
        function examination_input() {
            if(BX('root_dir').value !== '')
                return true;
                else return false;

        }

        function err_mess(str) {
            return '<div class=\'adm-info-message-wrap adm-info-message-red\'><div class=\'adm-info-message\'><div class=\'adm-info-message-title\'>' + str + '</div><div class=\'adm-info-message-icon\'></div></div></div>';
        }
    </script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>