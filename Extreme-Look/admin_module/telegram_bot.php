<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");
use Bitrix\Highloadblock as HL,
    Bitrix\Main\GroupTable,
    Bitrix\Main\Entity,
    \Bitrix\Main\UI\Extension;

Extension::load("ui.hint");
Extension::load("ui.buttons");
Extension::load("ui.notification");
Extension::load("ui.confetti");

if(!$USER->IsAdmin() || !$USER->GetID() == 10354){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$APPLICATION->SetPageProperty("title", "Телеграм бот Extreme-Look");
$APPLICATION->SetTitle("Телеграм бот Extreme-Look");?>


<?$aTabs = array(
    array("DIV" => "Push", "ONSELECT" => "selectTab('Push')", "TAB" => GetMessage('EXTREME_TBOT_TAB_1'))
);

$obName = 'ob_'.preg_replace('/[^a-zA-Z0-9_]/', 'x', 'extreme_tbot');
$itemIDS = array(
    "GRID_ID"           => $obName . "_bot_users_grid",
    "ARRUSERS"          => $obName . "_arr_users_textarea",
    "MESSAGE_PUSH"      => $obName . "_message_push_textarea",
    "BUTTON_SEND"       => $obName . "_button_send",
    "BUTTON_SEND_ADMIN" => $obName . "_button_send_admin",
    "REZULT_BLOCK"      => $obName . "_result_block",
    "REZULT_MESSAGE"    => $obName . "_result_message",
    "URL_IMAGE"         => $obName . "_image_message",
    "SELECT_FORMATS"    => $obName . "_select_formats"
);
$resArrayRows = array();
$whArray = array();
$uifilter = array();
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$activeTab = $tabControl->ActiveTabParam();
$hlblock = HL\HighloadBlockTable::getById(15)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$grid_options = new Bitrix\Main\Grid\Options($GRID_ID); //Инициализируем грид
$sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]); //Формирование сортировки по умолчанию, при загрузке страницы, пока пользователь не выбрал свою сортировку
$nav_params = $grid_options->GetNavParams(); //Строим сортировку для навбара грида
$nav = new Bitrix\Main\UI\PageNavigation($GRID_ID); //Формируем постраничную навигацию
$nav->allowAllRecords(true)
    ->setPageSize($nav_params['nPageSize']) //Количество страниц
    ->initFromUri();
if ($nav->allRecordsShown()) {
    $nav_params = false;
} else {
    $nav_params['iNumPage'] = $nav->getCurrentPage(); //На какой странице пользователь
}

//** Формируем поля для фильтра **//
$uiFilter = [
    ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => false],
    ['id' => 'US_ID', 'name' => 'ИД Юзера', 'sort' => 'UF_ID', 'default' => true],
    ['id' => 'CHAT_ID', 'name' => 'ИД Чата', 'sort' => 'UF_CHAT_ID', 'default' => true],
    ['id' => 'NAME', 'name' => 'Имя в телеге', 'sort' => 'UF_NAME', 'default' => true],
    ['id' => 'LOGIN', 'name' => 'Логин в телеге', 'sort' => 'UF_LOGIN', 'default' => true],
    ['id' => 'LIKE_NAME', 'name' => 'Как представился', 'sort' => 'UF_LIKE_NAME', 'default' => true],
    ['id' => 'STATE', 'name' => 'Состояние пользователя', 'sort' => 'UF_STATE', 'default' => true],
    ['id' => 'FILES', 'name' => 'Каталог с файлами', 'sort' => 'UF_FILES', 'default' => true],
    ['id' => 'TIME', 'name' => 'Последний раз писал', 'sort' => 'UF_TIME', 'default' => true],
    ['id' => 'FIRST_TIME', 'name' => 'Первый раз написал', 'sort' => 'UF_FIRST_TIME', 'default' => true]
];

//** Формируем поля для таблички **//
$uiColumps = [
    ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true],
    ['id' => 'US_ID', 'name' => 'ИД Юзера', 'sort' => 'UF_ID', 'default' => true],
    ['id' => 'CHAT_ID', 'name' => 'ИД Чата', 'sort' => 'UF_CHAT_ID', 'default' => true],
    ['id' => 'NAME', 'name' => 'Имя в телеге', 'sort' => 'UF_NAME', 'default' => true],
    ['id' => 'LOGIN', 'name' => 'Логин в телеге', 'sort' => 'UF_LOGIN', 'default' => true],
    ['id' => 'LIKE_NAME', 'name' => 'Как представился', 'sort' => 'UF_LIKE_NAME', 'default' => true],
    ['id' => 'STATE', 'name' => 'Состояние пользователя', 'sort' => 'UF_STATE', 'default' => true],
    ['id' => 'FILES', 'name' => 'Каталог с файлами', 'sort' => 'UF_FILES', 'default' => true],
    ['id' => 'TIME', 'name' => 'Последний раз писал', 'sort' => 'UF_TIME', 'default' => true],
    ['id' => 'FIRST_TIME', 'name' => 'Первый раз написал', 'sort' => 'UF_FIRST_TIME', 'default' => true]
];

$filterOption = new Bitrix\Main\UI\Filter\Options($GRID_ID); //Инициализация фильтра + поиска
$filterData = $filterOption->getFilter([]); //Вытягиваем полученный от фильтра массив поиска (который пользователь ввёл в общее поле либо в конкретное поле/поля)

foreach ($filterData as $k => $v) { //Перебираем
    if ($k === 'FIND' && strlen($v) > 0) {
        $uifilter = Array(
            "LOGIC"                 =>  "OR",
            Array( "ID"             =>  $filterData['FIND'] ),
            Array( "UF_CHAT_ID"     =>  $filterData['FIND'] ),
            Array( "UF_NAME"        =>  $filterData['FIND'] ),
            Array( "UF_LOGIN"       =>  $filterData['FIND'] ),
            Array( "UF_LIKE_NAME"   =>  $filterData['FIND'] ),
            Array( "UF_STATE"       =>  $filterData['FIND'] ),
            Array( "UF_FILES"       =>  $filterData['FIND'] ),
            Array( "UF_TIME"        =>  $filterData['FIND'] ),
            Array( "UF_FIRST_TIME"  =>  $filterData['FIND'] )
        );
    }
    //** Проверяем по какому полю пользователь ищет и подставляем туда НАЧАЛО **//
    if ($k === 'US_ID' && strlen($v) > 0) {
        $uifilter['UF_ID']   =   '%' . $filterData['US_ID'] .         '%';
    }
    if ($k === 'CHAT_ID' && strlen($v) > 0) {
        $uifilter['UF_CHAT_ID']     =   '%' . $filterData['CHAT_ID'] .       '%';
    }
    if ($k === 'NAME' && strlen($v) > 0) {
        $uifilter['UF_NAME']    =   '%' . $filterData['NAME'] .         '%';
    }
    if ($k === 'LOGIN' && strlen($v) > 0) {
        $uifilter['UF_LOGIN']     =   '%' . $filterData['LOGIN'] .      '%';
    }
    if ($k === 'LIKE_NAME' && strlen($v) > 0) {
        $uifilter['UF_LIKE_NAME']    =   '%' . $filterData['LIKE_NAME'] .     '%';
    }
    if ($k === 'STATE' && strlen($v) > 0) {
        $uifilter['UF_STATE']   =   '%' . $filterData['STATE'] .  '%';
    }
    if ($k === 'FILES' && strlen($v) > 0) {
        $uifilter['UF_FILES']   =   '%' . $filterData['FILES'] .       '%';
    }
    if ($k === 'TIME' && strlen($v) > 0) {
        $uifilter['UF_TIME']  =   '%' . $filterData['TIME'] .      '%';
    }
    if ($k === 'FIRST_TIME' && strlen($v) > 0) {
        $uifilter['UF_FIRST_TIME'] =   '%' . $filterData['FIRST_TIME'] .  '%';
    }
    //** Проверяем по какому полю пользователь ищет и подставляем туда КОНЕЦ **//
}

$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => $sort['sort'],
    "filter" => $uifilter,
    "count_total" => true,
    "offset" => $nav->getOffset(),
    "limit" => $nav->getLimit(),
));

$nav->setRecordCount($rsData->getCount()); //Рассчитывает количество страниц в таблице для навигации

while($arData = $rsData->Fetch()){
    $actions = [
        [
            'text'    => GetMessage('EXTREME_TBOT_SEND_MESS_ACTION'),
            'onclick' => $obName.".addUser(".$arData["UF_ID"].")"
        ]
    ];
    $whArray[] = [
        'data' =>[
            "ID"           => $arData["ID"],
            "US_ID"         => $arData['UF_ID'],
            "CHAT_ID"       => $arData["UF_CHAT_ID"],
            "NAME"       => $arData['UF_NAME'],
            "LOGIN"      => $arData['UF_LOGIN'],
            "LIKE_NAME"         => $arData['UF_LIKE_NAME'],
            "STATE"  => $arData['UF_STATE'],
            "FILES"       => $arData['UF_FILES'],
            "TIME"      => $arData['UF_TIME'],
            "FIRST_TIME"  => $arData['UF_FIRST_TIME'],
        ],
        'actions' => $actions
    ];
}
//**  Формирование массива для грида КОНЕЦ **//

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
<?$tabControl->Begin();
$tabControl->BeginNextTab();?>

<tr class="heading">
    <td colspan="2">
        <?=GetMessage('EXTREME_TBOT_SEND_MESS_TITTLE')?>
    </td>
</tr>
<tr>
    <td colspan="2">
        <textarea name="SEND_USERS" class="arrusers" id="<?=$itemIDS['ARRUSERS']?>" rows="10" disabled></textarea>
    </td>
</tr>
<tr>
    <td width="50%">
        <?=GetMessage('EXTREME_TBOT_MESSAGE_TEXTAREA')?>
    </td>
    <td width="50%">
        <textarea id="<?=$itemIDS['MESSAGE_PUSH']?>" class="message-push" cols="50" rows="10"></textarea>
    </td>
</tr>
<tr>
    <td width="50%">
        <?=GetMessage('EXTREME_TBOT_MESSAGE_IMAGE')?>
    </td>
    <td width="50%">
        <input id="<?=$itemIDS['URL_IMAGE']?>" type="text" style="width: 51%;">
    </td>
</tr>
<tr>
    <td width="50%">
        <?=GetMessage('EXTREME_TBOT_FORMAT_TITTLE')?>
    </td>
    <td width="50%">
        <select name="FORMAT" id="<?=$itemIDS['SELECT_FORMATS']?>" class="typeselect">
            <option value="MarkdownV2" selected="">MarkdownV2</option>
            <option value="HTML">HTML</option>
            <option value="Markdown">Markdown (устаревший)</option>
        </select>
    </td>
</tr>
<tr>
    <td width="50%">
        <?=GetMessage('EXTREME_TBOT_MESSAGE_CRIB_TITTLE')?>
    </td>
    <td width="50%">
        <ul style="list-style:none"><?=GetMessage('EXTREME_TBOT_MESSAGE_CRIB')?></ul>
    </td>
</tr>
<tr>
    <td width="50%">
        <?=GetMessage("EXTREME_TBOT_SEND_MESSAGE_BUTTON_LABEL")?>
    </td>
    <td width="50%">
        <button class="ui-btn ui-btn-success" id="<?=$itemIDS['BUTTON_SEND']?>" data-send="BOT_PUSH" disabled><?=GetMessage("EXTREME_TBOT_SEND_MESSAGE_BUTTON")?></button>
    </td>
</tr>
<tr>
    <td colspan="2" width="100%">
        <button class="ui-btn ui-btn-success" id="<?=$itemIDS['BUTTON_SEND_ADMIN']?>" data-send="BOT_PUSH" disabled><?=GetMessage("EXTREME_TBOT_SEND_MESSAGE_BUTTON_ADMIN_LABEL")?></button>
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
$tabControl->End();?>

<?$APPLICATION->IncludeComponent( //Компонент фильтра для грида
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
    <tr>
        <td colspan="2" width="100%">
            <button class="ui-btn ui-btn-success" onclick="reloadGrid()"><?=GetMessage("EXTREME_TBOT_RELOAD_GRID")?></button>
        </td>
    </tr>
</table>
<?$APPLICATION->IncludeComponent( //Компонент грида (чекай доки, комментировать параметры не буду)
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $itemIDS['GRID_ID'],
        'COLUMNS' => $uiColumps,
        'ROWS' => $whArray,
        'SHOW_ROW_CHECKBOXES' => false,
        'NAV_OBJECT' => $nav,
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'PAGE_SIZES' => [
            ['NAME' => '5', 'VALUE' => '5'],
            ['NAME' => '10', 'VALUE' => '10'],
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
            ['NAME' => 'Все', 'VALUE' => '1000']
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
        'AJAX_OPTION_HISTORY'       => 'N'
    ]
);?>

<?$jsParams = array(
    "IDS" => $itemIDS,
    "INIT" => 'Y'
)?>
<script type="text/javascript">
    var <?=$obName?> = new TelegramBotExtreme(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    function reloadGrid(){
        var reloadParams = { apply_filter: 'Y', clear_nav: 'Y' };
        var gridObject = BX.Main.gridManager.getById(<?=json_encode($itemIDS['GRID_ID'])?>);
        if (gridObject.hasOwnProperty('instance')){
            gridObject.instance.reloadTable('POST', reloadParams);
            BX.UI.Notification.Center.notify({
                content: "Информация в гриде обновлёна",
                position: "bottom-left",
                autoHideDelay: 2000,
                closeButton: false,
            });
            BX.UI.Confetti.fire();
        }
    }
</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
