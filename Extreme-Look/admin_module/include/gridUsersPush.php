<?$grid_options = new Bitrix\Main\Grid\Options($itemIDS['GRID_ID']);
$sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
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
$uifilter = array();
$count = 0;
$uifilter['ACTIVE'] = 'Y';
$uifilter['UF_FIREBASE_TOKEN'] = '%';
$uifilter[">=UF_APP_VERSION"] = 2.20;

$uiFilter = [
    ['id' => 'ID',          'name' => 'ID',                     'sort' => 'ID',         'default'   => true],
    ['id' => 'NAME',        'name' => 'Имя',                    'sort' => 'NAME',       'default'   => true],
    ['id' => 'STATUS',      'name' => 'Статус',                 'sort' => 'GROUPS_ID',  'default'   => true],
    ['id' => 'LAST_LOGIN',  'name' => 'Последняя авторизация',  'type' => 'date',       'sort'      => 'LAST_LOGIN', 'default' => true],
    ['id' => 'ACTIVE',      'name' => 'Активен',                'type' => 'checkbox',   'sort'      => 'ACTIVE',     'default' => true]
];

$uiColumns = [
    ['id' => 'ID',          'name' => 'ID',                     'sort' => 'ID',         'default' => true],
    ['id' => 'NAME',        'name' => 'NAME',                   'sort' => 'NAME',       'default' => true],
    ['id' => 'STATUS',      'name' => 'Статус',                 'sort' => 'GROUPS_ID',  'default' => true],
    ['id' => 'LAST_LOGIN',  'name' => 'Последняя авторизация',  'sort' => 'LAST_LOGIN', 'default' => true],
    ['id' => 'ACTIVE',      'name' => 'Активен',                'sort' => 'ACTIVE',     'default' => true],
];

$filterOption = new Bitrix\Main\UI\Filter\Options($itemIDS['GRID_ID']);
$filterData = $filterOption->getFilter([]);
foreach ($filterData as $k => $v) {
    if ($k === 'FIND' && strlen($v) > 0)
        $uifilter['ID']           = '%' . $filterData['FIND'];
    if ($k === 'ACTIVE' && strlen($v) > 0)
        $uifilter['ACTIVE']       = '%' . $filterData['ACTIVE'];
    if ($k === 'LAST_LOGIN_from' && strlen($v) > 0) {
        $uifilter['LAST_LOGIN_1'] = $filterData['LAST_LOGIN_from'];
        $uifilter['LAST_LOGIN_2'] = $filterData['LAST_LOGIN_to'];
    }
    if ($k === 'STATUS' && strlen($v) > 0)
        $uifilter['GROUPS_ID']    = $filterData['STATUS'];
}
unset($k, $v);
$users = CUser::GetList(($by = $sort['sort']), ($order = $sort), $uifilter, array('NAV_PARAMS' => ["nPageSize" => $nav_params['nPageSize'], "iNumPage" => $nav_params['iNumPage']]));
$allUsers = CUser::GetList(($by = 'ID'), ($order = 'asc'), array('ACTIVE' => 'Y', 'UF_FIREBASE_TOKEN' => '%', ">=UF_APP_VERSION" => 2.20, array()));

$nav->setRecordCount($users->selectedRowsCount());
while($arDataALL = $allUsers->Fetch()){
    $count++;
    $groupsUser = CUser::GetUserGroup($arDataALL['ID']);
    if(in_array(1, $groupsUser))
        $admins .= !empty($admins) ? ',' . $arDataALL['ID'] : $arDataALL['ID'];
    elseif(!empty(array_uintersect(ROZN, $groupsUser, "strcasecmp")))
        $roznica .= !empty($roznica) ? ',' . $arDataALL['ID'] : $arDataALL['ID'];
    elseif(!empty(array_uintersect(ALL_PART, $groupsUser, "strcasecmp")))
        $partners .= !empty($partners) ? ',' . $arDataALL['ID'] : $arDataALL['ID'];
}
while($arData = $users->Fetch()){
    $groupsUser = CUser::GetUserGroup($arData['ID']);
    if(in_array(1, $groupsUser))
        $status = 'Администратор';
    elseif(in_array(10, $groupsUser))
        $status = 'Розничный';
    elseif(in_array(9,  $groupsUser))
        $status = 'Партнёр';
    elseif(in_array(11, $groupsUser))
        $status = 'Партнер "Золото"';
    elseif(in_array(12, $groupsUser))
        $status = 'Партнер "Серебро"';
    elseif(in_array(13, $groupsUser))
        $status = 'Партнер "Платина"';
    elseif(in_array(8,  $groupsUser))
        $status = 'Администратор интернет-магазина';
    elseif(in_array(26,  $groupsUser))
        $status = 'Партнёр 16%';
    elseif(in_array(27,  $groupsUser))
        $status = 'Партнёр 22%';
    elseif(in_array(28,  $groupsUser))
        $status = 'Партнёр 31%';
    elseif(in_array(29,  $groupsUser))
        $status = 'Партнёр 44%';
    elseif(in_array(30,  $groupsUser))
        $status = 'Партнёр 50%';
    elseif(in_array(31,  $groupsUser))
        $status = 'Партнёр 60%';
    elseif(in_array(32,  $groupsUser))
        $status = 'Партнёр 50% (без кешбэка)';
    $resArrayRows[] = [
        'data' =>[
            "ID" => "<a href='https://extreme-look.ru/bitrix/admin/user_edit.php?lang=ru&ID=" . $arData["ID"] . "' target='_blank'>" . $arData['ID'] . "</a>",
            "NAME" => "<a href='https://extreme-look.ru/bitrix/admin/user_edit.php?lang=ru&ID=" . $arData["ID"] . "' target='_blank'>" . $arData['NAME'] . ' ' . $arData['SECOND_NAME'] . ' ' . $arData['LAST_NAME'] . "</a>",
            "STATUS" => $status,
            "LAST_LOGIN" => $arData['LAST_LOGIN'],
            "ACTIVE" => $arData['ACTIVE'] = 'Y' ? 'Да' : 'Нет'
        ],
        'actions' => [
            [
                'text'    => GetMessage('EXTREME_PUSH_BUTTON_ADD_IN_LIST'),
                'onclick' => $obName.".addUser(".$arData["ID"].")"
            ]
        ],
    ];
    $status = '';
}
$allUsersToJS = $admins . ',' . $roznica . ',' . $partners;?>

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
<?$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $itemIDS['GRID_ID'],
        'COLUMNS' => $uiColumns,
        'ROWS' => $resArrayRows,
        'NAV_OBJECT' => $nav,
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
        'TOTAL_ROWS_COUNT'          => $allUsers->SelectedRowsCount(),
        'ENABLE_COLLAPSIBLE_ROWS'   => true
    ]
);?>