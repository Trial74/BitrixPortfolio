<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");?>
<?IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Loader,
    Bitrix\Main\Entity;
\Bitrix\Main\UI\Extension::load("ui.hint");
\Bitrix\Main\UI\Extension::load("ui.buttons");

if(!$USER->IsAdmin() || !$USER->GetID() == 10354){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$APPLICATION->SetPageProperty("title", "Контроль размерности файлов");
$APPLICATION->SetTitle("Контроль размерности файлов");

$rootDir = $_SERVER['DOCUMENT_ROOT'] . '/upload'; //По умолчанию
$mainDirOpen = opendir($rootDir);

$aTabs = array( //Массив для вкладок на странице
    array("DIV" => "settings", "TAB" => GetMessage('EXTREME_FILE_CONTROL_TAB_1'), "ICON"=>"main_user_edit", "TITLE" => GetMessage('EXTREME_FILE_CONTROL_TAB_1'))
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

while(false !== ($mainDirRead = readdir($mainDirOpen))) {
    if($mainDirRead == '.' || $mainDirRead == '..' || $mainDirRead == '.DS_Store') {
        continue;
    }
    $explodeDir = explode(".", $mainDirRead);
    if(count($explodeDir) > 1) continue;
    if($mainDirRead == 'iblock') $strDirsByMain .= "<option value='$mainDirRead' selected>$mainDirRead</option>";
    else $strDirsByMain .= "<option value='$mainDirRead'>$mainDirRead</option>";
}

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
<?$tabControl->Begin();
$tabControl->BeginNextTab();?>
    <tr class="heading">
        <td colspan="2"><b><?=GetMessage('EXTREME_FILE_CONTROL_TABLE_HEAD')?></b></td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('EXTREME_FILE_CONTROL_SOURCE')?></td>
        <td width="50%">
            <select id="source">
                <option value="startBase" selected><?=GetMessage('EXTREME_FILE_CONTROL_BASE')?></option>
                <option value="startFileSystem"><?=GetMessage('EXTREME_FILE_CONTROL_FILE_SYSTEM')?></option>
            </select>
        </td>
    </tr>
    <tr id="base_module">
        <td width="50%"><?=GetMessage('EXTREME_FILE_CONTROL_TABLE_MODULE_IN_BASE')?></td>
        <td width="50%">
            <select id="module_name">
                <option value="enext">enext</option>
                <option value="fileman">fileman</option>
                <option value="forum">forum</option>
                <option value="iblock" selected>iblock</option>
                <option value="import_files">import_files</option>
                <option value="main">main</option>
                <option value="medialibrary">medialibrary</option>
                <option value="mobileapp">mobileapp</option>
                <option value="sale">sale</option>
                <option value="sender">sender</option>
                <option value="socialservices">socialservices</option>
                <option value="uf">uf</option>
            </select>
        </td>
    </tr>
    <tr id="base_limit">
        <td width="50%"><?=GetMessage('EXTREME_FILE_CONTROL_TABLE_LIMIT_IN_BASE')?></td>
        <td width="50%">
            <input type="number" size="15" maxlength="25" value="0" id="limit_in_base">
        </td>
    </tr>
    <tr id="file_system" style="display: none">
        <td width="50%"><?=GetMessage('EXTREME_FILE_CONTROL_CHOICE_DIR')?></td>
        <td width="50%">
            <select name="dir">
                <?=$strDirsByMain?>
            </select>
        </td>
    </tr>
<?$tabControl->EndTab();
$tabControl->Buttons(
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
           value="<?=GetMessage('EXTREME_FILE_CONTROL_BUTTON_START')?>"
           title="<?=GetMessage('EXTREME_FILE_CONTROL_BUTTON_START')?>"
           onclick="start()"
    />
<?$tabControl->End();?>
<style>
    .info-row{
        display: flex;
        flex-wrap: nowrap;
        flex-direction: row;
        align-content: center;
        align-items: center;
    }
    #rem{
        padding-left: 20px;
    }
</style>
<table width="100%">
    <tr>
        <td class="info-row">
            <div id="spinner"></div>
            <div id="message_rez"></div>
            <div id="rem"></div>
        </td>
    </tr>
</table>

<table width="100%" id="table_result">
    <thead>
        <tr class="adm-list-table-header">
            <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_CONTROL_TABLE_RESULT_1')?></div></td>
            <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_CONTROL_TABLE_RESULT_2')?></div></td>
            <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?=GetMessage('EXTREME_FILE_CONTROL_TABLE_RESULT_3')?></div></td>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<script>
    var select = document.getElementById("source");
    select.addEventListener("change", function(){
        var trfs = document.getElementById("file_system"),
            trbs = document.getElementById("base_module"),
            trli = document.getElementById("base_limit");

        if(this.value === 'startFileSystem'){
            trfs.style.display = 'table-row';
        }
        else trfs.style.display = 'none';
        if(this.value === 'startBase'){
            trbs.style.display = 'table-row';
            trli.style.display = 'table-row';
        }
        else {
            trbs.style.display = 'none';
            trli.style.display = 'none';
        }

    });

    function remTable() {
        var tbody = document.getElementById('table_result').getElementsByTagName("TBODY")[0],
            buttonRemBlock = document.getElementById('rem'),
            message_rez = document.getElementById('message_rez');
        tbody.innerHTML = '';
        buttonRemBlock.innerHTML = '';
        message_rez.innerHTML = '';
    }

    function start(){
        var data = {},
            source = document.getElementById('source'),
            value = source.options[source.selectedIndex].value,
            buttonWait = new BX.UI.Button(),
            buttonStart = BX('butStart'),
            sel_module = document.getElementById('module_name'),
            valueModule = sel_module.options[sel_module.selectedIndex].value,
            valueLimit = BX('limit_in_base').value;

        buttonWait.setClocking([flag=true]);
        buttonWait.renderTo(BX('spinner'));

        BX('message_rez').innerHTML = 'Идёт выборка информации, ожидайте завершения';
        BX.adjust(buttonStart, {props: {disabled: true}});

        data['action'] = value;
        data['limit'] = valueLimit;
        data['module'] = valueModule;
        BX.ajax.post(
            '/bitrix/admin/extremelook_file_control_ajax.php',
            data,
            function (data) {
                var result = JSON.parse(data);
                if(result.result === 'OK'){
                    remTable();
                    BX.cleanNode(BX('spinner'));
                    BX('message_rez').innerHTML = result.message;
                    BX.adjust(buttonStart, {props: {disabled: false}});
                    addrow(result.tablestr);
                }
                else{
                    BX.adjust(buttonStart, {props: {disabled: false}});
                    BX('message_rez').innerHTML = result.message;
                }

            }
        );
    }
    function addrow(data) {
        var tbody = document.getElementById('table_result').getElementsByTagName("TBODY")[0],
            blockRem = document.getElementById('rem'),
            buttonRem = new BX.UI.Button({
                id: "remTable",
                text: "Очистить таблицу",
                round: true,
                onclick: function(btn, event) {
                    remTable();
                },
            });

        for(key in data){
            var row = document.createElement("TR"),
                td0 = document.createElement("TD"),
                td1 = document.createElement("TD"),
                td2 = document.createElement("TD"),
                img = new Image();
                img.width = 50;
                img.src = data[key][2];

            td0.appendChild(document.createTextNode(data[key][0]));
            td1.appendChild(document.createTextNode(data[key][1]));
            td2.appendChild(img);

            row.appendChild(td0);
            row.appendChild(td1);
            row.appendChild(td2);

            tbody.appendChild(row);
        }
        blockRem.appendChild(buttonRem.getContainer());
    }
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
