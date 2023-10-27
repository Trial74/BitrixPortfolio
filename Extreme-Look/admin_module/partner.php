<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
\Bitrix\Main\UI\Extension::load("ui.buttons");
use Bitrix\Main\FileTablel;
IncludeModuleLangFile(__FILE__);

$APPLICATION->SetTitle('Сертификаты партнёров');

if($_REQUEST["ajax"] == "Y")
{
    CAdminMessage::ShowMessage(array(
        "MESSAGE" => GetMessage("main_cache_finished"),
        "HTML" => true,
        "TYPE" => "OK",
    ));
    require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin_js.php");
}
$GRID_ID = 'part_list';
$whArray = array();
$uifilter = array();
$resArrayRows = array();
$resArrayJSid = array();

$grid_options = new Bitrix\Main\Grid\Options($GRID_ID);
$sort = $grid_options->GetSorting(['sort' => ['DATE_CREATE' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$nav_params = $grid_options->GetNavParams();
$nav = new Bitrix\Main\UI\PageNavigation($GRID_ID);
$nav->allowAllRecords(true)
    ->setPageSize($nav_params['nPageSize'])
    ->initFromUri();
if ($nav->allRecordsShown()) {
    $nav_params = false;
} else {
    $nav_params['iNumPage'] = $nav->getCurrentPage();
}

//** ФОРМИРОВАНИЕ МАССИВОВ СТРОК СТОЛБЦОВ И ФИЛЬТРА ДЛЯ ВЫБОРКИ НАЧАЛО **//
$uiFilter = [
    ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => false],
    ['id' => 'NAME', 'name' => 'Имя', 'sort' => 'NAME', 'default' => true],
    ['id' => 'COUNTRY', 'name' => 'Страна', 'sort' => 'UF_COUNTRY', 'default' => true],
    ['id' => 'SITY', 'name' => 'Город', 'sort' => 'UF_SITY', 'default' => true],
    ['id' => 'MAP_ADDRESS', 'name' => 'Адресс на карте', 'sort' => 'UF_MAP_ADDRESS', 'default' => true]
];

$uiColumns = [
    ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true],
    ['id' => 'LAST_LOGIN', 'name' => 'Последняя авторизация', 'sort' => 'LAST_LOGIN', 'default' => true],
    ['id' => 'NAME', 'name' => 'Имя', 'sort' => 'NAME', 'default' => true],
    ['id' => 'COUNTRY', 'name' => 'Страна', 'sort' => 'UF_COUNTRY', 'default' => true],
    ['id' => 'SITY', 'name' => 'Город', 'sort' => 'UF_SITY', 'default' => true],
    ['id' => 'MAP_ADDRESS', 'name' => 'Адресс на карте', 'sort' => 'UF_MAP_ADDRESS', 'default' => true],
    ['id' => 'SERTIFICAT', 'name' => 'Сертификат', 'sort' => 'UF_SERTIFICAT', 'default' => true]
];

$filterOption = new Bitrix\Main\UI\Filter\Options($GRID_ID);
$filterData = $filterOption->getFilter([]);

foreach ($filterData as $k => $v) {
    if ($k === 'FIND' && strlen($v) > 0) {
        $uifilter['ID']             = '%' . $filterData['FIND'];
    }
    if ($k === 'ID' && strlen($v) > 0) {
        $uifilter['ID']             =   '%' . $filterData['ID'];
    }
    if ($k === 'NAME' && strlen($v) > 0) {
        $uifilter['WORK_COMPANY']   =   '%' . $filterData['NAME'];
    }
    if ($k === 'COUNTRY' && strlen($v) > 0) {
        $uifilter['UF_COUNTRY']     =   '%' . $filterData['COUNTRY'];
    }
    if ($k === 'SITY' && strlen($v) > 0) {
        $uifilter['UF_SITY']        =   '%' . $filterData['SITY'];
    }
    if ($k === 'MAP_ADDRESS' && strlen($v) > 0) {
        $uifilter['UF_MAP_ADDRESS'] =   '%' . $filterData['MAP_ADDRESS'];
    }
}

$uifilter['GROUPS_ID'] =  ALL_PART; //Партнёры
//$uifilter['UF_PAGE_PART'] = true; //Отображаемые на странице партнёров
$uifilter['ACTIVE'] = "Y"; //Только активные
//** ФОРМИРОВАНИЕ МАССИВОВ СТРОК СТОЛБЦОВ И ФИЛЬТРА ДЛЯ ВЫБОРКИ КОНЕЦ **//

$allPartsUsers = CUser::GetList(($by = $sort['sort']), ($order = $sort), array('GROUPS_ID' => [9,11,12,13], 'ACTIVE' => "Y"), array());
$rsUsers = CUser::GetList(($by = $sort['sort']), ($order = $sort), $uifilter, array('NAV_PARAMS' => ["nPageSize" => $nav_params['nPageSize'], "iNumPage" => $nav_params['iNumPage']], 'SELECT' => ['ID', 'UF_COUNTRY', 'UF_COUNTRY_URL', 'UF_SITY_PAT', 'UF_SITY_URL', 'UF_MAP_ADDRESS', 'UF_SERTIFICATE'])); //Выборка
$nav->setRecordCount($rsUsers->selectedRowsCount()); //Рассчитывает количество страниц в таблице для навигации

while($arData = $rsUsers->Fetch()){
    if($arData['UF_SERTIFICATE']){ //Если есть сертификат формируем вывод в поле
        $fileSert = CFile::GetPath($arData['UF_SERTIFICATE']);
        $sert = "<span id='bx_file_uf_sertificate_file_disp_0' class='adm-input-file-preview' style='min-width: 120px; min-height:100px;'>
				<a title='Увеличить' href='" . $fileSert . "' target=_blank><img src='" . $fileSert . "' border='0' alt='' width='141' height='200'></a></span>";
        $actions = [
            [
                'text'    => 'Сгенерировать/перевыпустить сертификат',
                'onclick' => 'StartSertOne("' . $arData["ID"] . '");'
            ],
            [
                'text'    => 'Удалить сертификат',
                'onclick' => 'StartDelSert("' . $arData["ID"] . '");'
            ]
        ];
    }
    else{ //Нет сертификата из менюшки убираем пункт "Удалить сертификат"
        $actions = [
            [
                'text'    => 'Сгенерировать/перевыпустить сертификат',
                'onclick' => 'StartSertOne("' . $arData["ID"] . '");'
            ]
        ];
    }

    $resArrayRows[] = [
        'data' =>[
            "ID" => "<a href='https://extreme-look.ru/bitrix/admin/user_edit.php?lang=ru&ID=" . $arData["ID"] . "' target='_blank'>" . $arData["ID"] . "</a>",
            "LAST_LOGIN" => $arData['LAST_LOGIN'],
            "ACTIVE" => $arData['ACTIVE'] = 'Y' ? 'Да' : 'Нет',
            "NAME" => "<a href='https://extreme-look.ru/bitrix/admin/user_edit.php?lang=ru&ID=" . $arData["ID"] . "' target='_blank'>" . $arData['WORK_COMPANY'] . "</a>",
            "COUNTRY" => $arData["UF_COUNTRY"][0],
            "SITY" => $arData['UF_SITY_PAT'][0],
            "MAP_ADDRESS" => $arData['UF_MAP_ADDRESS'][0],
            "SERTIFICAT" => $arData['UF_SERTIFICATE'] ? $sert : 'Нет'
        ],
        'actions' => $actions
    ];

    $resArrayJSid[] = [
        "ID" => $arData["ID"]
    ];
}

$aTabs = array(
    array("DIV" => "add_serts", "TAB" => GetMessage('EXTREME_PARTNER_TAB_1')),
    array("DIV" => "instr_serts", "TAB" => GetMessage('EXTREME_PARTNER_TAB_2'))
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>

<?$tabControl->Begin();
$tabControl->BeginNextTab();?>

<tbody id="prop"></tbody>
    <tr class="heading">
        <td colspan="2"><b><?=GetMessage('EXTREME_PARTNER_RELOAD_ALL_SERT')?></b></td>
    </tr>
    <tr>
        <td align="center" colspan="2">
            <button type="button" id="start_all_sert_button" class="ui-btn ui-btn-success" OnClick="StartSertAll();"><?=GetMessage('EXTREME_PARTNER_GEN_ALL_PART')?></button>
        </td>
    </tr>

<?$tabControl->EndTab();
$tabControl->BeginNextTab();?>

<tr>
    <td width="100%">
        <span>
            <?=GetMessage("EXTREME_PARTNER_INSTR")?>
        </span>
    </td>
</tr>
<?$tabControl->EndTab();
$tabControl->End();?>

<?$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [
        'FILTER_ID' => $GRID_ID,
        'GRID_ID' => $GRID_ID,
        'FILTER' => $uiFilter,
        'ENABLE_LIVE_SEARCH' => true,
        'ENABLE_LABEL' => true
    ]
);?>

<table width="100%">
    <tr>
        <td align="center" colspan="2" id="result"></td>
    </tr>
</table>

<?$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $GRID_ID,
        'COLUMNS' => $uiColumns,
        'ROWS' => $resArrayRows,
        'SHOW_ROW_CHECKBOXES' => false,
        'NAV_OBJECT' => $nav,
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'PAGE_SIZES' => [
            ['NAME' => "5", 'VALUE' => '5'],
            ['NAME' => '10', 'VALUE' => '10'],
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
        'ALLOW_COLUMNS_SORT'        => false,
        'ALLOW_COLUMNS_RESIZE'      => true,
        'ALLOW_HORIZONTAL_SCROLL'   => true,
        'ALLOW_SORT'                => true,
        'ALLOW_PIN_HEADER'          => true,
        'AJAX_OPTION_HISTORY'       => 'N',
        'TOTAL_ROWS_COUNT' => $allPartsUsers->SelectedRowsCount()
    ]
);?>
    <script language="JavaScript">
        function StartSertAll(){
            var partnersIDs = <?=json_encode($resArrayJSid)?>,
                countIDs = partnersIDs.length, i = 0, partner = {};
            while(i < countIDs){
                partner['ID'] = partnersIDs[i].ID;
                partner['data'] = 'add';
                i++;
                BX.ajax.post(
                    '/bitrix/admin/extremelook_sert_ajax.php',
                    partner,
                    function (data) {
                        var result = JSON.parse(data);
                    }
                );
            }
        }
        function StartSertOne(id){
            var partner = {};
                partner['ID'] = id;
                partner['data'] = 'add';

            if(!!partner['ID']) {
                BX.ajax.post(
                    '/bitrix/admin/extremelook_sert_ajax.php',
                    partner,
                    function (data) {
                        var result = JSON.parse(data);
                        BX.adjust(BX("result"), {html: result.result});
                        reloadGridSert();
                    }
                );
            }
        }
        function StartDelSert(id){
            var partner = {};
            partner['ID'] = id;
            partner['data'] = 'delete';

            if(!!partner['ID']) {
                BX.ajax.post(
                    '/bitrix/admin/extremelook_sert_ajax.php',
                    partner,
                    function (data) {
                        var result = JSON.parse(data);
                        BX.adjust(BX("result"), {html: result.result});
                        reloadGridSert();
                    }
                );
            }
        }
        function reloadGridSert(){
            var reloadParams = { apply_filter: 'Y', clear_nav: 'Y' };
            var gridObject = BX.Main.gridManager.getById(<?=json_encode($GRID_ID)?>); // Идентификатор грида
            if (gridObject.hasOwnProperty('instance')){
                gridObject.instance.reloadTable('POST', reloadParams);
            }
        }
    </script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>