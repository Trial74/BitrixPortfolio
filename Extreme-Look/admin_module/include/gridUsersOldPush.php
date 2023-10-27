<?$GRID_ID_OLD = 'push_old_users';
$gridOld_options = new Bitrix\Main\Grid\Options($GRID_ID_OLD);
$sortOld = $gridOld_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$navOld_params = $gridOld_options->GetNavParams();
$navOld = new Bitrix\Main\UI\PageNavigation($GRID_ID_OLD);
$navOld->allowAllRecords(true)
    ->setPageSize($navOld_params['nPageSize'])
    ->initFromUri();
if ($navOld->allRecordsShown()) {
    $navOld_params = false;
} else {
    $navOld_params['iNumPage'] = $navOld->getCurrentPage();
}
$uifilterOld = array();
$countOld = 0;
$uifilterOld['ACTIVE'] = 'Y';
$uifilterOld['UF_FIREBASE_TOKEN'] = '%';
$uifilterOld[] = array(
    "LOGIC" => "OR",
    array("=UF_APP_VERSION" => 2.01),
    array("=UF_APP_VERSION" => '')
);

$uiFilterOld = [
    ['id' => 'ID',          'name' => 'ID',                     'sort' => 'ID',         'default'   => true],
    ['id' => 'NAME',        'name' => 'Имя',                    'sort' => 'NAME',       'default'   => true],
    ['id' => 'STATUS',      'name' => 'Статус',                 'sort' => 'GROUPS_ID',  'default'   => true],
    ['id' => 'LAST_LOGIN',  'name' => 'Последняя авторизация',  'type' => 'date',       'sort'      => 'LAST_LOGIN', 'default' => true],
    ['id' => 'ACTIVE',      'name' => 'Активен',                'type' => 'checkbox',   'sort'      => 'ACTIVE',     'default' => true]
];

$uiColumnsOld = [
    ['id' => 'ID',          'name' => 'ID',                     'sort' => 'ID',         'default' => true],
    ['id' => 'NAME',        'name' => 'NAME',                   'sort' => 'NAME',       'default' => true],
    ['id' => 'STATUS',      'name' => 'Статус',                 'sort' => 'GROUPS_ID',  'default' => true],
    ['id' => 'LAST_LOGIN',  'name' => 'Последняя авторизация',  'sort' => 'LAST_LOGIN', 'default' => true],
    ['id' => 'ACTIVE',      'name' => 'Активен',                'sort' => 'ACTIVE',     'default' => true],
];

$filterOptionOld = new Bitrix\Main\UI\Filter\Options($GRID_ID_OLD);
$filterDataOld = $filterOptionOld->getFilter([]);
foreach ($filterDataOld as $k => $v) {
    if ($k === 'FIND' && strlen($v) > 0)
        $uifilterOld['ID']           = '%' . $filterDataOld['FIND'];
    if ($k === 'ACTIVE' && strlen($v) > 0)
        $uifilterOld['ACTIVE']       = '%' . $filterDataOld['ACTIVE'];
    if ($k === 'LAST_LOGIN_from' && strlen($v) > 0) {
        $uifilterOld['LAST_LOGIN_1'] = $filterDataOld['LAST_LOGIN_from'];
        $uifilterOld['LAST_LOGIN_2'] = $filterDataOld['LAST_LOGIN_to'];
    }
    if ($k === 'STATUS' && strlen($v) > 0)
        $uifilterOld['GROUPS_ID']    = $filterDataOld['STATUS'];
}
unset($k, $v);
$usersOld = CUser::GetList(($by = $sortOld['sort']), ($order = $sortOld), $uifilterOld, array('NAV_PARAMS' => ["nPageSize" => $navOld_params['nPageSize'], "iNumPage" => $navOld_params['iNumPage']]));
$allUsersOld = CUser::GetList(($by = 'ID'), ($order = 'asc'), array('ACTIVE' => 'Y', 'UF_FIREBASE_TOKEN' => '%',
array(
    "LOGIC" => "OR",
    array("=UF_APP_VERSION" => 2.01),
    array("UF_APP_VERSION" => '')
)), array());

$navOld->setRecordCount($usersOld->selectedRowsCount());
while($arDataALLOld = $allUsersOld->Fetch()){
    $countOld++;
    $groupsUserOld = CUser::GetUserGroup($arDataALLOld['ID']);
    if(in_array(1, $groupsUserOld))
        $adminsOld .= !empty($adminsOld) ? ',' . $arDataALLOld['ID'] : $arDataALLOld['ID'];
    elseif(!empty(array_uintersect(ROZN, $groupsUserOld, "strcasecmp")))
        $roznOld .= !empty($roznOld) ? ',' . $arDataALLOld['ID'] : $arDataALLOld['ID'];
    elseif(!empty(array_uintersect(ALL_PART, $groupsUserOld, "strcasecmp")))
        $partnersOld .= !empty($partnersOld) ? ',' . $arDataALLOld['ID'] : $arDataALLOld['ID'];
}
while($arDataOld = $usersOld->Fetch()){
    $groupsUserOld = CUser::GetUserGroup($arDataOld['ID']);
    if(in_array(1, $groupsUserOld))
        $statusOld = 'Администратор';
    elseif(in_array(10, $groupsUserOld))
        $statusOld = 'Розничный';
    elseif(in_array(9,  $groupsUserOld))
        $statusOld = 'Партнёр';
    elseif(in_array(11, $groupsUserOld))
        $statusOld = 'Партнер "Золото"';
    elseif(in_array(12, $groupsUserOld))
        $statusOld = 'Партнер "Серебро"';
    elseif(in_array(13, $groupsUserOld))
        $statusOld = 'Партнер "Платина"';
    elseif(in_array(8,  $groupsUserOld))
        $statusOld = 'Администратор интернет-магазина';
    elseif(in_array(26,  $groupsUserOld))
        $statusOld = 'Партнёр 16%';
    elseif(in_array(27,  $groupsUserOld))
        $statusOld = 'Партнёр 22%';
    elseif(in_array(28,  $groupsUserOld))
        $statusOld = 'Партнёр 31%';
    elseif(in_array(29,  $groupsUserOld))
        $statusOld = 'Партнёр 44%';
    elseif(in_array(30,  $groupsUserOld))
        $statusOld = 'Партнёр 50%';
    elseif(in_array(31,  $groupsUserOld))
        $statusOld = 'Партнёр 60%';
    elseif(in_array(32,  $groupsUserOld))
        $statusOld = 'Партнёр 50% (без кешбэка)';
    $resArrayRowsOld[] = [
        'data' =>[
            "ID" => $arDataOld["ID"],
            "NAME" => "<a href='https://extreme-look.ru/bitrix/admin/user_edit.php?lang=ru&ID=" . $arDataOld["ID"] . "' target='_blank'>" . $arDataOld['NAME'] . "</a>",
            "STATUS" => $statusOld,
            "LAST_LOGIN" => $arDataOld['LAST_LOGIN'],
            "ACTIVE" => $arDataOld['ACTIVE'] = 'Y' ? 'Да' : 'Нет'
        ],
        'actions' => [
            [
                'text'    => GetMessage('EXTREME_PUSH_BUTTON_ADD_IN_LIST'),
                'onclick' => 'addUserOld(' . $arDataOld["ID"] . ')'
            ]
        ],
    ];
    $statusOld = '';
}
$allUsersOldToJS = $adminsOld . ',' . $roznOld . ',' . $partnersOld;
?>

<?$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [
        'FILTER_ID' => $GRID_ID_OLD,
        'GRID_ID' => $GRID_ID_OLD,
        'FILTER' => $uiFilterOld,
        'ENABLE_LIVE_SEARCH' => true,
        'ENABLE_LABEL' => true
    ]
);?>
<?$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $GRID_ID_OLD,
        'COLUMNS' => $uiColumnsOld,
        'ROWS' => $resArrayRowsOld,
        'NAV_OBJECT' => $navOld,
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'PAGE_SIZES' => [
            ['NAME' => '10', 'VALUE' => '10'],
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
            ['NAME' => '100', 'VALUE' => '100'],
            ['NAME' => '200', 'VALUE' => '200']
        ],
        'AJAX_OPTION_JUMP'          => 'Y',
        'SHOW_CHECK_ALL_CHECKBOXES' => true,
        'SHOW_ROW_CHECKBOXES'       => true,
        'SHOW_ROW_ACTIONS_MENU'     => true,
        'SHOW_GRID_SETTINGS_MENU'   => true,
        'SHOW_NAVIGATION_PANEL'     => true,
        'SHOW_PAGINATION'           => true,
        'SHOW_SELECTED_COUNTER'     => true,
        'SHOW_TOTAL_COUNTER'        => true,
        'SHOW_PAGESIZE'             => true,
        'SHOW_ACTION_PANEL'         => true,
        'ALLOW_COLUMNS_SORT'        => false,
        'ALLOW_COLUMNS_RESIZE'      => true,
        'ALLOW_HORIZONTAL_SCROLL'   => true,
        'ALLOW_SORT'                => true,
        'ALLOW_PIN_HEADER'          => true,
        'AJAX_OPTION_HISTORY'       => 'N',
        'TOTAL_ROWS_COUNT'          => $allUsersOld->SelectedRowsCount(),
        'ENABLE_COLLAPSIBLE_ROWS'   => true
    ]
);?>
<script>
    function addUsersOld(arrval){
        var chekAdd = BX('adddelold').checked;

        if(chekAdd){
            if(!BX('arrusersold').value.trim()){
                BX('arrusersold').value = arrval;
                btnSendDisOld();
            }
            else{
                BX('arrusersold').value += ',';
                BX('arrusersold').value += arrval;
                btnSendDisOld();
            }
        }
        else{
            clearOld();
            BX('arrusersold').value = arrval;
            btnSendDisOld();
        }
    }
    function addUserOld(id) {
        if(!BX('arrusersold').value.trim()){
            BX('arrusersold').value = id;
            btnSendDisOld()
        }
        else
            BX('arrusersold').value += ',' + id;
    }
    function clearOld(){
        BX('arrusersold').value = "";
        btnSendDisOld()
    }
    function btnSendDisOld(){
        if(BX('arrusersold').value.trim() && BX('tittle-pushold').value.trim() && BX('message-pushold').value.trim()){
            BX.adjust(BX('sendold'), {props: {disabled: false}});
        }
        else{
            BX.adjust(BX('sendold'), {props: {disabled: true}});
        }
    }
    BX.bind(BX('addAdminold'), 'click', function(){
        addUsersOld(<?=CUtil::PhpToJSObject($adminsOld)?>);
    });
    BX.bind(BX('addRozold'), 'click', function(){
        addUsersOld(<?=CUtil::PhpToJSObject($roznOld)?>);
    });
    BX.bind(BX('addPartold'), 'click', function(){
        addUsersOld(<?=CUtil::PhpToJSObject($partnersOld)?>);
    });
    BX.bind(BX('addAllold'), 'click', function(){
        clearOld();
        addUsersOld(<?=CUtil::PhpToJSObject($allUsersOldToJS)?>);
    });
    BX.bind(BX('add_selects_controlold'), 'click', function() { //Допилить
        var elements = document.querySelectorAll('tr.main-grid-row-checked'),
            count = 0, ids = '';
        while(count < elements.length){
            if(!elements[count].dataset.id || elements[count].dataset.id === 'template_0') {count++;continue;}
            if(ids === '')
                ids = elements[count].dataset.id;
            else
                ids += ',' + elements[count].dataset.id;
            count++;
        }
        addUsersOld(ids);
    });
    BX.bind(BX('tittle-pushold'), 'keyup', function() {
        var count = BX(this).value.length,
            label = BX('label-countold');
        BX.adjust(label, {text: count + '/40'});
        if(count > 25 && count < 30)
            BX.adjust(label, {props: {className : 'label-warning'}});
        if(BX(this).value.length > 35)
            BX.adjust(label, {props: {className : 'label-warningg'}});
        if(count < 25)
            BX.adjust(label, {props: {className : ''}});
        btnSendDisOld();
    });
    BX.bind(BX('message-pushold'), 'keyup', function() {
        var count = BX(this).value.length,
            textarea = BX('textarea-countold');

        BX.adjust(textarea, {text: count + '/120'});
        if(count > 100 && count < 110)
            BX.adjust(textarea, {props: {className : 'label-warning'}});
        if(BX(this).value.length > 110)
            BX.adjust(textarea, {props: {className : 'label-warningg'}});
        if(count < 100)
            BX.adjust(textarea, {props: {className : ''}});
        btnSendDisOld();
    });
    BX.bind(BX('clearold'), 'click', function() {
        clearOld();
    });
    BX.bind(BX('sendold'), 'click', function(e) {
        var ids = BX('arrusersold').value,
            arrData = {};
        arrData['action'] = e.target.dataset.send;
        arrData['ids']      = ids.split(',');
        arrData['tittle']   = BX("tittle-pushold").value;
        arrData['message']  = BX("message-pushold").value;
        BX.ajax.post(
            '/bitrix/admin/extremelook_push_ajax.php',
            arrData,
            function (data) {
                var resultOBJ = JSON.parse(data),
                    result = resultOBJ.result,
                    count = 0, succ = 0, fail = 0;
                while(count < result.length){
                    if(result[count].success)
                        succ++;
                    else
                        fail++;
                    count++;
                }
                clearOld();
                BX.adjust(BX('result-messageold'), {
                    html: 'Всего отправлено PUSH уведомлений: ' + count + '<br />Удачно отправленных: ' + succ + '<br />Неудачно отправленнх: ' + fail
                });
                BX.adjust(BX('rez-blockold'), {
                    style: {'display': 'block'}
                });
            }
        );
    });
</script>
