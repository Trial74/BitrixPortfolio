<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");
use Bitrix\Highloadblock as HL,
    Bitrix\Main\GroupTable,
    Bitrix\Main\Entity,
    Bitrix\Main\UI\Extension;

Extension::load("ui.hint");
Extension::load("ui.buttons");
Extension::load("ui.forms");
\CJSCore::init("sidepanel");

$APPLICATION->SetPageProperty("title", "Скриншоты из телеграм бота");
$APPLICATION->SetTitle("Скриншоты из телеграм бота");?>

<?
$resData = array();
$hlblock = HL\HighloadBlockTable::getById(15)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$obName = 'ob_'.preg_replace('/[^a-zA-Z0-9_]/', 'x', 'extreme_tbot_managers');

$itemIDS = array(
    "GRID_ID"       => $obName . "_bot_users_managers_grid",
    'OPEN_FILES'    => $obName . '_button_open_files',
    'DATE_INPUT'    => $obName . '_input_date',
    'COUNTER'       => $obName . '_count_icon'
);

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

//** Формируем поля для таблички **//
$uiColumps = [
    ['id' => 'NAME', 'name' => 'Имя в телеге', 'sort' => 'UF_NAME', 'default' => true],
    ['id' => 'LOGIN', 'name' => 'Логин в телеге', 'sort' => 'UF_LOGIN', 'default' => true],
    ['id' => 'FILES', 'name' => 'Каталог с файлами', 'sort' => 'UF_FILES', 'default' => true],
];

$uifilter = Array(
    "!UF_FILES" => ''
);

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
    $whArray[] = [
        'data' =>[
            "NAME"  => $arData['UF_NAME'],
            "LOGIN" => $arData['UF_LOGIN'],
            "FILES" => '<button data-user="'.$arData['UF_FILES'].'" class="ui-btn button-file-user">' . $arData['UF_FILES'] . '</button>'
        ]
    ];
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>

    <div class="tbot_screen-form-date">
        <div class="ui-ctl">
            <div class="ui-ctl-tag tbot_screen-tag"><?=GetMessage("EXTREME_TBOT_SCR_SELECT_DATE")?></div>
            <input class="ui-ctl-element" id="<?=$itemIDS['DATE_INPUT']?>" type="date">
        </div>
        <div>
            <button class="ui-btn ui-btn-disabled ui-btn-wait" id="<?=$itemIDS['OPEN_FILES']?>"><?=GetMessage("EXTREME_TBOT_SCR_OPEN_FILES")?><i class="ui-btn-counter tbot_screen-button-date-count" id="<?=$itemIDS['COUNTER']?>">0</i></button>
        </div>
    </div>

<?
$APPLICATION->IncludeComponent( //Компонент грида (чекай доки, комментировать параметры не буду)
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
);
?>

<!--    <table id="table_result" width="100%">
        <thead>
        <tr class="adm-list-table-header">
            <td class="adm-list-table-cell" style="width:20%;"><div class="adm-list-table-cell-inner"><?/*=GetMessage("EXTREME_TBOT_SCR_HEAD_LOGIN")*/?></div></td>
            <td class="adm-list-table-cell" style="width:80%;"><div class="adm-list-table-cell-inner"><?/*=GetMessage("EXTREME_TBOT_SCR_HEAD_FILES")*/?></div></td>
        </tr>
        </thead>
        <tbody>
        <?/*foreach($resData as $data){*/?>
            <tr>
                <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?/*=$data['UF_LOGIN']*/?></div></td>
                <td class="adm-list-table-cell">
                    <div class="adm-list-table-cell-inner">
                        <button data-user="<?/*=$data['UF_FILES']*/?>" class="ui-btn button-file-user"><?/*=$data['UF_FILES']*/?></button>
                    </div>
                </td>
            </tr>
        <?/*}*/?>
        </tbody>
    </table>-->

<?$jsParams = array(
    "IDS" => $itemIDS,
    "INIT" => 'N'
)?>
    <script type="text/javascript">
        var <?=$obName?> = new TelegramBotExtreme(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    </script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>