<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Loader;

if(!$USER->IsAdmin() || !$USER->GetID() == 10354){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$APPLICATION->SetPageProperty("title", "Отправка пушей в новом приложении");
$APPLICATION->SetTitle("Отправка пушей в новом приложении");?>

<?$aTabs = array(
    array("DIV" => "Push", "ONSELECT" => "selectTab('Push')", "TAB" => GetMessage('EXTREME_PUSH_TAB_1')),
    array("DIV" => "instruction", "ONSELECT" => "selectTab('instr')", "TAB" => GetMessage('EXTREME_PUSH_TAB_2'))
);
$obName = 'ob_'.preg_replace('/[^a-zA-Z0-9_]/', 'x', 'extreme_push');
$itemIDS = array(
    "GRID_ID"           => $obName . "_push_users_grid",
    "COUNT_BLOCK"       => $obName . "_count_block",
    "ARRUSERS"          => $obName . "_arr_users_textarea",
    "ADDDEL"            => $obName . "_ad_del_input",
    "ADDADMIN"          => $obName . "_ad_admin_button",
    "ADDROZ"            => $obName . "_ad_roznica_button",
    "ADDPART"           => $obName . "_ad_part_button",
    "ADDALL"            => $obName . "_ad_all_button",
    "ADDGUEST"          => $obName . "_ad_guests_button",
    "CLEAR"             => $obName . "_clear_button",
    "TITTLE_PUSH"       => $obName . "_tittle_push_input",
    "LABEL_COUNT"       => $obName . "_label_count_label",
    "MESSAGE_PUSH"      => $obName . "_message_push_textarea",
    "TEXTAREA_COUNT"    => $obName . "_textarea_count_label",
    "URL_IMAGE"         => $obName . "_url_image_input",
    "URL_PUSH"          => $obName . "_url_push_input",
    "SEND"              => $obName . "_send_button",
    "REZULT_BLOCK"      => $obName . "_rez_block",
    "REZULT_MESSAGE"    => $obName . "_result_message_block",
    "PUSH_USERS_BLOCK"  => $obName . "_new_push_users_block",
    "NAME_VARIABLE"     => $obName . "_name_variable"
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$activeTab = $tabControl->ActiveTabParam();?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
<?$tabControl->Begin();
$tabControl->BeginNextTab();?>
    <tr>
        <td id="<?=$itemIDS['COUNT_BLOCK']?>"></td>
    </tr>
    <tr class="heading">
        <td colspan="2">
            <?=GetMessage('EXTREME_PUSH_LIST_IDS_USERS')?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <textarea name="ARRUSERS" class="arrusers" id="<?=$itemIDS['ARRUSERS']?>" rows="10" disabled></textarea>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <label class="label-ex" for="<?=$itemIDS['ADDDEL']?>"><?=GetMessage('EXTREME_PUSH_CHECK_LABEL')?></label>
        </td>
        <td width="50%">
            <input type="checkbox" id="<?=$itemIDS['ADDDEL']?>" checked>
        </td>
    </tr>
    <tr style="text-align: center">
        <td colspan="2" width="100%">
            <button class="ui-btn ui-btn-success" id="<?=$itemIDS['ADDADMIN']?>"><?=GetMessage('EXTREME_PUSH_BUTTON_ADD_ADMINS')?></button>
            <button class="ui-btn ui-btn-success" id="<?=$itemIDS['ADDROZ']?>"><?=GetMessage('EXTREME_PUSH_BUTTON_ADD_ROZN')?></button>
            <button class="ui-btn ui-btn-success" id="<?=$itemIDS['ADDPART']?>"><?=GetMessage('EXTREME_PUSH_BUTTON_ADD_PARTNERS')?></button>
            <button class="ui-btn ui-btn-success" id="<?=$itemIDS['ADDALL']?>"><?=GetMessage('EXTREME_PUSH_BUTTON_ADD_ALL')?></button>
            <button class="ui-btn ui-btn-success" id="<?=$itemIDS['ADDGUEST']?>"><?=GetMessage('EXTREME_PUSH_BUTTON_ADD_GUESTS')?></button>
            <button class="ui-btn ui-btn-success" id="<?=$itemIDS['CLEAR']?>"><?=GetMessage('EXTREME_PUSH_BUTTON_DEL_ALL')?></button>
        </td>
    </tr>
    <tr class="heading">
        <td colspan="2">
            <?=GetMessage('EXTREME_PUSH_SETTINGS_TITTLE')?>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <?=GetMessage('EXTREME_PUSH_NOTIF_TITTLE')?>
        </td>
        <td width="50%">
            <input id="<?=$itemIDS['TITTLE_PUSH']?>" maxlength="60" type="text">
            <label id="<?=$itemIDS['LABEL_COUNT']?>" class="label-count" for="<?=$itemIDS['TITTLE_PUSH']?>">0/60</label>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <?=GetMessage('EXTREME_PUSH_NOTIF_MESSAGE')?>
        </td>
        <td width="50%">
            <textarea id="<?=$itemIDS['MESSAGE_PUSH']?>" class="message-push" cols="50" maxlength="160" rows="10"></textarea>
            <label id="<?=$itemIDS['TEXTAREA_COUNT']?>" class="textarea-count" for="<?=$itemIDS['MESSAGE_PUSH']?>">0/160</label>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <?=GetMessage('EXTREME_PUSH_NOTIF_IMAGE')?>
        </td>
        <td width="50%">
            <input id="<?=$itemIDS['URL_IMAGE']?>" type="text" style="width: 51%;">
        </td>
    </tr>
    <tr>
        <td width="50%">
            <?=GetMessage('EXTREME_PUSH_NOTIF_URL')?>
        </td>
        <td width="50%">
            <input id="<?=$itemIDS['URL_PUSH']?>" type="text" style="width: 51%;">
        </td>
    </tr>
    <tr>
        <td width="50%">
            <?=GetMessage('EXTREME_PUSH_NAME_VARIABLE')?>
        </td>
        <td width="50%">
            <input id="<?=$itemIDS['NAME_VARIABLE']?>" type="text" style="width: 51%;" value="<?=GetMessage('EXTREME_PUSH_NAME_VARIABLE_VALUE')?>">
        </td>
    </tr>
    <tr>
        <td width="50%">
            <?=GetMessage('EXTREME_PUSH_NOTIF_URL_INFO')?>
        </td>
        <td width="50%">
            <ul style="list-style:none"><?=GetMessage("EXTREME_PUSH_CRIB")?></ul>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <?=GetMessage("EXTREME_PUSH_MESS_PUSH")?>
        </td>
        <td width="50%">
            <button class="ui-btn ui-btn-success" id="<?=$itemIDS['SEND']?>" data-send="PUSH" disabled><?=GetMessage("EXTREME_PUSH_BUTTON_PUSH")?></button>
        </td>
    </tr>
    <tr>
        <td style="text-align: center" colspan="2" width="100%">
            <div class="adm-info-message-wrap adm-info-message-green" id="<?=$itemIDS['REZULT_BLOCK']?>" style="display: none">
                <div class="adm-info-message">
                    <div class="adm-info-message-title" id="<?=$itemIDS['REZULT_MESSAGE']?>"></div>
                    <div class="adm-info-message-icon"></div>
                </div>
            </div>
        </td>
    </tr>
<?$tabControl->EndTab();
$tabControl->BeginNextTab();?>
<?=GetMessage("EXTREME_PUSH_INSTRUCTIONS")?>
<?$tabControl->EndTab();
$tabControl->End();?>
<div id="<?=$itemIDS['PUSH_USERS_BLOCK']?>">
    <?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/extremelook/include/gridUsersPush.php");?>
</div>
<?$jsParams = array(
    "IDS"           => $itemIDS,
    "ADMINS"        => $admins,
    "ROZNICA"       => $roznica,
    "PARTNERS"      => $partners,
    "ALLUSERS"      => $allUsersToJS,
    "COUNT_PARTS"   => array(
        "MESSAGE"       => GetMessage('EXTREME_PUSH_COUNTS_USERS_BY_PUSH'),
        "COUNT"         => $count
    )
)?>
<script>
    BX.ready(function(){
        var activeTab = <?=CUtil::PhpToJSObject($activeTab)?>;
        selectTab(activeTab.split('=')[1]);
    });
    function selectTab(action) {
        if(action === 'Push') {BX.style(BX('PushUsers'), 'display', 'block');}
        else {BX.style(BX('PushUsers'), 'display', 'none');}
    }
</script>
    <script type="text/javascript">
        var <?=$obName?> = new PUSHNotificationExtreme(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    </script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>