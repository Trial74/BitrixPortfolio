<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

\Bitrix\Main\UI\Extension::load("ui.buttons");
IncludeModuleLangFile(__FILE__);

if(!$USER->IsAdmin() || !$USER->GetID() == 10354){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$APPLICATION->SetTitle(GetMessage('EXTREME_MOBILEAPP_USERS_TITTLE'));

$whArray = array();
$resArrayRows = array();
$uifilter = array();
$obName = 'ob_'.preg_replace('/[^a-zA-Z0-9_]/', 'x', 'mobile_app_users');
$itemIDS = array(
    "GRID_ID"           => $obName . "_app_list",
    "RELOAD_GRID"       => $obName . "_reload_grid_button",
    "ACTUALIZE"         => $obName . "_remove_dubl_button",
    "ACT_VERSIONS"      => $obName . "_actual_versions_button",
    "REM_NO_TOKEN"      => $obName . "_remove_no_token_button",
    "REZULT_BLOCK"      => $obName . "_result_block",
    "REZULT_MESSAGE"    => $obName . "_result_message_block"
);
$grid_options = new Bitrix\Main\Grid\Options($itemIDS['GRID_ID']);
$sort = $grid_options->GetSorting(['sort' => ['DATE_CREATE' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$nav_params = $grid_options->GetNavParams();
$nav = new Bitrix\Main\UI\PageNavigation($itemIDS['GRID_ID']);
$nav->allowAllRecords(true)
    ->setPageSize($nav_params['nPageSize'])
    ->initFromUri();
if ($nav->allRecordsShown()) {
    $nav_params = false;
} else {
    $nav_params['iNumPage'] = $nav->getCurrentPage();
}

$uiFilter = [
    ['id' => 'ID', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_ID'), 'sort' => 'ID', 'default' => true],
    ['id' => 'APP_TOKEN', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_APP_TOKEN'), 'sort' => 'PROPERTY_APP_TOKEN', 'default' => true],
    ['id' => 'US_ID', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_US_ID'), 'sort' => 'PROPERTY_US_ID', 'default' => true],
    ['id' => 'GU_ID', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_GU_ID'), 'sort' => 'PROPERTY_GU_ID', 'default' => true],
    ['id' => 'DEVICE', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_DEVICE'), 'sort' => 'PROPERTY_DEVICE', 'default' => true],
    ['id' => 'APP_V', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_APP_V'), 'sort' => 'PROPERTY_APP_V', 'default' => true],
    ['id' => 'APP_VERSION', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_APP_VERSION'), 'sort' => 'PROPERTY_APP_VERSION', 'default' => true],
];

$uiColumns = [
    ['id' => 'ID', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_ID'), 'sort' => 'ID', 'default' => true],
    ['id' => 'APP_TOKEN', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_APP_TOKEN'), 'sort' => 'PROPERTY_APP_TOKEN', 'default' => true],
    ['id' => 'US_ID', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_US_ID'), 'sort' => 'PROPERTY_US_ID', 'default' => true],
    ['id' => 'GU_ID', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_GU_ID'), 'sort' => 'PROPERTY_GU_ID', 'default' => true],
    ['id' => 'COUNT_IN', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_COUNT_IN'), 'sort' => 'PROPERTY_COUNT_IN', 'default' => true],
    ['id' => 'DEVICE', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_DEVICE'), 'sort' => 'PROPERTY_DEVICE', 'default' => true],
    ['id' => 'APP_V', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_APP_V'), 'sort' => 'PROPERTY_APP_V', 'default' => true],
    ['id' => 'APP_VERSION', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_APP_VERSION'), 'sort' => 'PROPERTY_APP_VERSION', 'default' => true],
    ['id' => 'LAST_SESS', 'name' => GetMessage('EXTREME_MOBILEAPP_USERS_GRID_LAST_SESS'), 'sort' => 'PROPERTY_LAST_SESS', 'default' => true]
];

$filterOption = new Bitrix\Main\UI\Filter\Options($itemIDS['GRID_ID']);
$filterData = $filterOption->getFilter([]);

foreach ($filterData as $k => $v) {
    if(isset($filterData['FIND']) && !empty($filterData['FIND'])) { //Поле FIND в фильтре строка по умолчанию если пользователь просто вводит чтото в поле поиска без определённого критерия
        $uifilter['PROPERTY_US_ID'] = '%' . $filterData['FIND'] . '';
    }
    else
        $uifilter['PROPERTY_US_ID'] = $filterData['US_ID'] ? '%' . $filterData['US_ID'] . '%' : '';

    $uifilter['PROPERTY_APP_TOKEN'] = $filterData['APP_TOKEN'] ? $filterData['APP_TOKEN'] : '';
    $uifilter['PROPERTY_GU_ID'] = $filterData['GU_ID'] ? '%' . $filterData['GU_ID'] . '%' : '';
    $uifilter['PROPERTY_DEVICE'] = $filterData['DEVICE'] ? '%' . $filterData['DEVICE'] . '%' : '';
    $uifilter['PROPERTY_APP_V'] = $filterData['APP_V'] ? $filterData['APP_V'] : '';
    $uifilter['PROPERTY_APP_VERSION'] = $filterData['APP_V'] ? $filterData['APP_VERSION'] : '';
}

$uifilter['IBLOCK_ID'] =  IntVal(112);
$uifilter['ACTIVE_DATE'] = "Y";
$uifilter['ACTIVE'] = "Y";

$arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_APP_TOKEN", "PROPERTY_US_ID", "PROPERTY_GU_ID", "PROPERTY_COUNT_IN", "PROPERTY_DEVICE", "PROPERTY_APP_V", "PROPERTY_LAST_SESS", "PROPERTY_APP_VERSION");
$res = CIBlockElement::GetList($sort['sort'], $uifilter, false, array("nPageSize" => $nav_params['nPageSize'], "iNumPage" => $nav_params['iNumPage']), $arSelect);
$resAll = CIBlockElement::GetList(array(), array("IBLOCK_ID" => IntVal(112), "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"), false, array(), array("ID"));
$nav->setRecordCount($res->selectedRowsCount());

while($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();
    $uiRows[] = [
        'data' =>[
            "ID" => $arFields["ID"],
            "APP_TOKEN" => $arFields['PROPERTY_APP_TOKEN_VALUE'] === GetMessage('EXTREME_MOBILEAPP_USERS_FALSE_TOKEN') ? "<span style='color: darkred;'>".GetMessage('EXTREME_MOBILEAPP_USERS_FALSE_TOKEN')."</span>" : "<span style='color: green;'><b>".GetMessage('EXTREME_MOBILEAPP_USERS_TRUE_TOKEN')."</b></span>",
            "US_ID" => '<a href="/bitrix/admin/user_edit.php?lang=ru&ID=' . $arFields['PROPERTY_US_ID_VALUE'] . '" target="_blank">' . $arFields['PROPERTY_US_ID_VALUE'] . '</a>',
            "GU_ID" => $arFields['PROPERTY_GU_ID_VALUE'],
            "COUNT_IN" => $arFields["PROPERTY_COUNT_IN_VALUE"],
            "DEVICE" => $arFields['PROPERTY_DEVICE_VALUE'],
            "APP_V" => $arFields['PROPERTY_APP_V_VALUE'],
            "APP_VERSION" => $arFields['PROPERTY_APP_VERSION_VALUE'],
            "LAST_SESS" => $arFields['PROPERTY_LAST_SESS_VALUE']
        ],
        'actions' =>[]
    ];
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
<?$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [
        'FILTER_ID' => $itemIDS['GRID_ID'],
        'GRID_ID' => $itemIDS['GRID_ID'],
        'FILTER' => $uiFilter,
        'ENABLE_LIVE_SEARCH' => true,
        'ENABLE_LABEL' => true
    ]
);?>
<table width="100%">
    <tbody>
        <tr>
            <td>
                <button class="ui-btn ui-btn-success" id="<?=$itemIDS['RELOAD_GRID']?>"><?=GetMessage('EXTREME_MOBILEAPP_USERS_RELOAD_LIST')?></button>
                <button class="ui-btn ui-btn-success" id="<?=$itemIDS['ACT_VERSIONS']?>"><?=GetMessage('EXTREME_MOBILEAPP_USERS_ACTUAL_VERSIONS_APP')?></button>
                <button class="ui-btn ui-btn-success" id="<?=$itemIDS['ACTUALIZE']?>" disabled><?=GetMessage('EXTREME_MOBILEAPP_USERS_RELOAD_DUBLI_ACTUAL')?></button>

            </td>
        </tr>
        <tr>
            <td colspan="2" width="100%">
                <div class="adm-info-message-wrap adm-info-message-green" id="<?=$itemIDS['REZULT_BLOCK']?>" style="display: none">
                    <div class="adm-info-message">
                        <div class="adm-info-message-title" id="<?=$itemIDS['REZULT_MESSAGE']?>"></div>
                        <div class="adm-info-message-icon"></div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<?$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $itemIDS['GRID_ID'],
        'COLUMNS' => $uiColumns,
        'ROWS' => $uiRows,
        'SHOW_ROW_CHECKBOXES' => false,
        'NAV_OBJECT' => $nav,
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'PAGE_SIZES' => [
            ['NAME' => '5', 'VALUE' => '5'],
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
            ['NAME' => '100', 'VALUE' => '100'],
            ['NAME' => '200', 'VALUE' => '200'],
            ['NAME' => '500', 'VALUE' => '500']
        ],
        'AJAX_OPTION_JUMP'          => 'N',
        'SHOW_CHECK_ALL_CHECKBOXES' => true,
        'SHOW_ROW_ACTIONS_MENU'     => true,
        'SHOW_GRID_SETTINGS_MENU'   => true,
        'SHOW_NAVIGATION_PANEL'     => true,
        'SHOW_PAGINATION'           => true,
        'SHOW_SELECTED_COUNTER'     => true,
        'SHOW_TOTAL_COUNTER'        => true,
        'SHOW_PAGESIZE'             => true,
        'SHOW_ACTION_PANEL'         => false,
        'ALLOW_COLUMNS_SORT'        => true,
        'ALLOW_COLUMNS_RESIZE'      => true,
        'ALLOW_HORIZONTAL_SCROLL'   => true,
        'ALLOW_SORT'                => true,
        'ALLOW_PIN_HEADER'          => true,
        'AJAX_OPTION_HISTORY'       => 'N',
        'TOTAL_ROWS_COUNT' => $resAll->SelectedRowsCount()
    ]
);
$jsParams = array(
    "IDS" => $itemIDS
);
?>
<script>
    var <?=$obName?> = new MobileAppUsers(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>