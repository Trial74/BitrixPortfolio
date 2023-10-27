<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
                    //** СТРАНИЦА С ГЕНЕРАТОРОМ ССЫЛОК, ПОСТАРАЛСЯ ВСЁ ПРОКОММЕНТИРОВАТЬ, ЧИТАЙ ВНИМАТЕЛЬНЕЕ, ВСЁ ПРОСТО, ЕСЛИ ЧТО ЧЕКАЙ ДОКИ БИТРЫ (by VLADOS) **//
IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");
use Bitrix\Highloadblock as HL,
    Bitrix\Main\GroupTable,
    Bitrix\Main\Entity;
    \Bitrix\Main\UI\Extension::load("ui.hint");

$APPLICATION->SetPageProperty("title", "Ссылки для активация партнёра");
$APPLICATION->SetTitle("Генератор ссылок");

$resArrayRows = array();
$whArray = array();
$uifilter = array();
$message = true;
$GRID_ID = 'links_list';
$hlbl = 4; //Хайблок который хранит сгенеринные ссылки
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass(); //Получем данные

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
    ['id' => 'LINK', 'name' => 'Значение ссылки', 'sort' => 'UF_LINK_NAME', 'default' => true],
    ['id' => 'CREATE', 'name' => 'Кем создана', 'sort' => 'UF_CR_NAME', 'default' => true],
    ['id' => 'ACTIVE', 'name' => 'Актиность', 'sort' => 'UF_USE_LINK', 'default' => true],
    ['id' => '24_HOUR', 'name' => '48 часов', 'sort' => 'UF_24_HOUR', 'default' => true],
    ['id' => 'USE_NAME', 'name' => 'Кем использована', 'sort' => 'UF_USE_NAME', 'default' => true],
    ['id' => 'USED', 'name' => 'Использована', 'sort' => 'UF_USE_LINK', 'default' => true],
    ['id' => 'NAME_P', 'name' => 'Имя партнёра', 'sort' => 'UF_NAME_PART', 'default' => true],
    ['id' => 'EMAIL_P', 'name' => 'Почта партнёра', 'sort' => 'UF_EMAIL_PART', 'default' => true],
    ['id' => 'PART_GROUP', 'name' => 'Статус партнёра', 'sort' => 'UF_GROUP_PART', 'default' => true]
];

//** Формируем поля для таблички **//
$uiColumps = [
    ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true],
    ['id' => 'LINK', 'name' => 'Ссылка', 'sort' => 'UF_LINK_NAME', 'default' => true],
    ['id' => 'ACTIVE', 'name' => 'Активность', 'sort' => 'UF_USE_LINK', 'default' => true],
    ['id' => 'CREATE', 'name' => 'Кем создана', 'sort' => 'UF_CR_NAME', 'default' => true],
    ['id' => '24_HOUR', 'name' => '48 часов', 'sort' => 'UF_24_HOUR', 'default' => true],
    ['id' => 'USED', 'name' => 'Использована', 'sort' => 'UF_USE_NAME', 'default' => true],
    ['id' => 'DATE_CREATE', 'name' => 'Дата создания', 'sort' => 'UF_DATE_TIME', 'default' => true],
    ['id' => 'NAME_P', 'name' => 'Имя партнёра', 'sort' => 'UF_NAME_PART', 'default' => true],
    ['id' => 'EMAIL_P', 'name' => 'Почта партнёра', 'sort' => 'UF_EMAIL_PART', 'default' => true],
    ['id' => 'DATE_ACTIVE', 'name' => 'Активна до', 'sort' => 'UF_DATE_ACTIVE', 'default' => true],
    ['id' => 'PART_GROUP', 'name' => 'Статус партнёра', 'sort' => 'UF_GROUP_PART', 'default' => true]
];

$filterOption = new Bitrix\Main\UI\Filter\Options($GRID_ID); //Инициализация фильтра + поиска
$filterData = $filterOption->getFilter([]); //Вытягиваем полученный от фильтра массив поиска (который пользователь ввёл в общее поле либо в конкретное поле/поля)
foreach ($filterData as $k => $v) { //Перебираем
    if ($k === 'FIND' && strlen($v) > 0) {
        $uifilter = Array(
                            "LOGIC"             =>  "OR",
                            Array(
                                "ID"            =>  $filterData['FIND']
                            ),
                            Array(
                                "UF_LINK_NAME"  =>  $filterData['FIND']
                            ),
                            Array(
                                "UF_CR_NAME"    =>  $filterData['FIND']
                            ),
                            Array(
                                "UF_24_HOUR"    =>  $filterData['FIND']
                            ),
                            Array(
                                "UF_USE_NAME"   =>  $filterData['FIND']
                            ),
                            Array(
                                "UF_DATE_TIME"  =>  $filterData['FIND']
                            ),
                            Array(
                                "UF_NAME_PART"  =>  $filterData['FIND']
                            ),
                            Array(
                                "UF_EMAIL_PART" =>  $filterData['FIND']
                            ),
                            Array(
                                "UF_DATE_ACTIVE"=>  $filterData['FIND']
                            ),
                            Array(
                                "UF_GROUP_PART"=>  $filterData['FIND']
                            )
                        );
    }
    //** Проверяем по какому полю пользователь ищет и подставляем туда НАЧАЛО **//
    if ($k === 'LINK' && strlen($v) > 0) {
        $uifilter['UF_LINK_NAME']   =   '%' . $filterData['LINK'] .         '%';
    }
    if ($k === 'CREATE' && strlen($v) > 0) {
        $uifilter['UF_CR_NAME']     =   '%' . $filterData['CREATE'] .       '%';
    }
    if ($k === 'USED' && strlen($v) > 0) {
        $uifilter['UF_USE_LINK']    =   '%' . $filterData['USED'] .         '%';
    }
    if ($k === '24_HOUR' && strlen($v) > 0) {
        $uifilter['UF_24_HOUR']     =   '%' . $filterData['24_HOUR'] .      '%';
    }
    if ($k === 'USE_NAME' && strlen($v) > 0) {
        $uifilter['UF_USE_NAME']    =   '%' . $filterData['USE_NAME'] .     '%';
    }
    if ($k === 'DATE_CREATE' && strlen($v) > 0) {
        $uifilter['UF_DATE_TIME']   =   '%' . $filterData['DATE_CREATE'] .  '%';
    }
    if ($k === 'NAME_P' && strlen($v) > 0) {
        $uifilter['UF_NAME_PART']   =   '%' . $filterData['NAME_P'] .       '%';
    }
    if ($k === 'EMAIL_P' && strlen($v) > 0) {
        $uifilter['UF_EMAIL_PART']  =   '%' . $filterData['EMAIL_P'] .      '%';
    }
    if ($k === 'DATE_ACTIVE' && strlen($v) > 0) {
        $uifilter['UF_DATE_ACTIVE'] =   '%' . $filterData['DATE_ACTIVE'] .  '%';
    }
    if ($k === 'PART_GROUP' && strlen($v) > 0) {
        $uifilter['UF_GROUP_PART'] =   '%' . $filterData['PART_GROUP'] .  '%';
    }
    //** Проверяем по какому полю пользователь ищет и подставляем туда КОНЕЦ **//
}
    //** Делаем выборку по фильтру пользователя НАЧАЛО **//
$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => $sort['sort'],
    "filter" => $uifilter,
    "count_total" => true,
    "offset" => $nav->getOffset(),
    "limit" => $nav->getLimit(),
));
    //** Делаем выборку по фильтру пользователя КОНЕЦ **//

$nav->setRecordCount($rsData->getCount()); //Рассчитывает количество страниц в таблице для навигации
//**  Формирование массива для грида НАЧАЛО **//
while($arData = $rsData->Fetch()){
    $arData["UF_CRON_ID"] ? $cronID = $arData["UF_CRON_ID"] : $cronID = false;
    $actions = [
        [
            'text'    => GetMessage('EXTREME_GENERATOR_MENU_COPY_LINK'),
            'onclick' => 'copyLink("' . $arData["UF_LINK_NAME"] . '", "' . $arData['UF_GROUP_PART'] . '");'
        ],
        [
            'text'    => GetMessage('EXTREME_GENERATOR_MENU_PUSH_BY_PART'),
            'onclick' => "sendMailPart('" . $arData['UF_EMAIL_PART'] . "', '" . $arData['UF_NAME_PART'] . "', '" . $arData["UF_LINK_NAME"] . "');"
        ],
        [
            'text'    => GetMessage('EXTREME_GENERATOR_MENU_DEL'),
            'onclick' => "delLink(" . $arData['ID'] . ", " . $cronID . ");"
        ]
    ];
    if($arData['UF_USE_LINK'] == "1")
        unset($actions[0], $actions[1]);

    if($arData['UF_EMAIL_PART'] == '' && isset($actions[1]))
        unset($actions[1]);

    $actions = array_values($actions);

    if(!empty($arData['UF_GROUP_PART'])){
        $rsGroup = CGroup::GetByID($arData['UF_GROUP_PART']);
        $arrGroup = $rsGroup->fetch();
        $groupName = $arrGroup['NAME'];
    } else $groupName = '';

    $whArray[] = [
        'data' =>[
            "ID"    => $arData["ID"],
            "LINK"  => $arData['UF_USE_LINK'] ? '<div class="link_name_r"><s> ' . $arData["UF_LINK_NAME"] . ' </s></div>' : '<div class="link_name_g">' . $arData["UF_LINK_NAME"] . '</div>',
            "CREATE"        => $arData["UF_CR_NAME"],
            "ACTIVE"        => $arData['UF_USE_LINK'] ? 'Нет' : 'Да',
            "24_HOUR"       => $arData['UF_24_HOUR'] == '1' ? '48 часов' : 'Своя дата',
            "USED"          => $arData['UF_USE_NAME'] ? $arData['UF_USE_NAME'] : 'Нет',
            "DATE_ACTIVE"   => $arData['UF_DATE_ACTIVE'] ? $arData['UF_DATE_ACTIVE'] : 'Нет',
            "NAME_P"        => $arData['UF_NAME_PART'] ? $arData['UF_NAME_PART'] : 'Нет',
            "EMAIL_P"       => $arData['UF_EMAIL_PART'] ? $arData['UF_EMAIL_PART'] : 'Нет',
            "DATE_CREATE"   => $arData['UF_DATE_TIME'],
            "PART_GROUP"    => $groupName
        ],
        'actions' => $actions
    ];
}
//**  Формирование массива для грида КОНЕЦ **//

$aTabs = array( //Массив для вкладок на странице
    array("DIV" => "add_links", "TAB" => GetMessage('EXTREME_GENERATOR_TAB_1'), "ICON"=>"main_user_edit", "TITLE" => GetMessage('EXTREME_GENERATOR_MESS_ADD_LINK')),
    array("DIV" => "instruktions", "TAB" => GetMessage('EXTREME_GENERATOR_TAB_2'), "ICON"=>"main_user_edit")
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
<?$tabControl->Begin();

$tabControl->BeginNextTab();?>

<!-- Форма создания ссылки **НАЧАЛО** -->
    <tr>
        <td colspan="2" align="center">
            <table cellspacing=1 cellpadding=0 border=0 id="prop" class="internal">
                <?  $arDoW = array(
                    "0"  => GetMessage("EXTREME_GENERATOR_DELETE_LINK"),
                    "1"  => GetMessage("EXTREME_GENERATOR_EMAIL_PART"),
                    "2"  => GetMessage("EXTREME_GENERATOR_NAME_PART"),
                    "3"  => GetMessage("EXTREME_GENERATOR_NUMB_USE"),
                    "4"  => GetMessage("EXTREME_GENERATOR_NUMB_USE_CAL"),
                    "5"  => GetMessage("EXTREME_GENERATOR_WHAT_PARTNER")
                );
                ?>
                <tr class="heading">
                    <?foreach($arDoW as $strVal=>$strDoW){?>
                        <?if($strVal == 4){?>
                            <td width="250px"><?=$strDoW?></td>
                        <?}else{?>
                            <td><?=$strDoW?></td>
                        <?}?>
                    <?}?>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div style="width: 100%; text-align: center; margin: 10px 0;">
                <input class="adm-btn-big" onclick="addPropLink();" type="button" value="Еще" title="<?=GetMessage('EXTREME_GENERATOR_ADD_ONE_LINK')?>">
            </div>
        </td>
    </tr>
        <tr>
            <td colspan="2">
                <div style="width: 100%; text-align: center; margin: 10px 0;">
                    <td align="center" colspan="2" id="result"></td>
                </div>
            </td>
        </tr>
<!-- Форма создания ссылки **КОНЕЦ** -->

<?$tabControl->BeginNextTab();?>

<!-- Инструкция **НАЧАЛО** -->
    <tr>
        <td width="100%">
            <span>
                <?=GetMessage("EXTREME_GENERATOR_INSTR")?>
            </span>
        </td>
    </tr>
<!-- Инструкция **КОНЕЦ** -->

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
           id="butSaveLink"
           value="Создать ссылку(и)"
           title="Создать и сохранить"
           onclick="formSubmit()"
    />

<?$tabControl->End();?>

<?$APPLICATION->IncludeComponent( //Компонент фильтра для грида
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
        <td>
            <div id="message_rez"></div>
        </td>
    </tr>
</table>
<?$APPLICATION->IncludeComponent( //Компонент грида (чекай доки, комментировать параметры не буду)
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $GRID_ID,
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
<script>
    window.onload = function() {
        addPropLink();
        BX.UI.Hint.init(BX('prop'));
    };
    
    function validateEmail(email) {
        var pattern  = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern .test(email);
    }
    function sendMailPart(mail, name = false, linkID) {
        var data = {};
        data['action'] = 'sendMail';
        if(!validateEmail(mail))
            BX('message_rez').innerHTML = "<div class=\"adm-info-message-wrap adm-info-message-red\"><div class=\"adm-info-message\"><div class=\"adm-info-message-title\">Почта неккоректна</div><div class=\"adm-info-message-icon\"></div></div></div>";
        else {
            data['mail'] = mail;
            data['name'] = name;
            data['linkID'] = linkID;
            BX.ajax.post(
              '/bitrix/admin/extremelook_links_ajax.php',
              data,
                function (data) {
                  var result = JSON.parse(data);
                  BX('message_rez').innerHTML = result.message;
              }
            );
        }
    }
    function formSubmit()
    {
        var data = {},
            dataFormLinks = {},
            paramsLinks = BX('prop'),
            rowsNewLinks = paramsLinks.querySelectorAll("tr[data-parent='EX_NEW_PROP_LINK']"),
            row = 0;
        BX('message_rez').innerHTML = '';
        if(rowsNewLinks.length > 1){
            while(row < rowsNewLinks.length){

                dataFormLinks[row] = {
                    'EX_EMAIL_PART'         : rowsNewLinks[row].querySelector('#EX_EMAIL_PART_' + rowsNewLinks[row].id).value,
                    'EX_NAME_PART'          : rowsNewLinks[row].querySelector('#EX_NAME_PART_' + rowsNewLinks[row].id).value,
                    'EX_ID_USE_48_HOUR'     : rowsNewLinks[row].querySelector('#EX_ID_USE_48_HOUR_' + rowsNewLinks[row].id).checked ? "1" : "0",
                    'EX_DATE_ACTIVE_LINK'   : rowsNewLinks[row].querySelector('#EX_DATE_ACTIVE_LINK_' + rowsNewLinks[row].id).value,
                    'EX_GROUP_PARTNER'      : rowsNewLinks[row].querySelector('#EX_ID_WHAT_PARTNER_' + rowsNewLinks[row].id).value
                };
                row++;
            }
            data['action'] = 'addManyLink';
        }
        else{
            dataFormLinks[0] = {
                'EX_EMAIL_PART'         : rowsNewLinks[0].querySelector('#EX_EMAIL_PART_' + rowsNewLinks[0].id).value,
                'EX_NAME_PART'          : rowsNewLinks[0].querySelector('#EX_NAME_PART_' + rowsNewLinks[0].id).value,
                'EX_ID_USE_48_HOUR'     : rowsNewLinks[0].querySelector('#EX_ID_USE_48_HOUR_' + rowsNewLinks[0].id).checked ? "1" : "0",
                'EX_DATE_ACTIVE_LINK'   : rowsNewLinks[0].querySelector('#EX_DATE_ACTIVE_LINK_' + rowsNewLinks[0].id).value,
                'EX_GROUP_PARTNER'      : rowsNewLinks[0].querySelector('#EX_ID_WHAT_PARTNER_' + rowsNewLinks[row].id).value
            };
            data['action'] = 'addOneLink';
        }

        data['linksdata'] = dataFormLinks;

        BX.ajax.post(
            '/bitrix/admin/extremelook_links_ajax.php',
            data,
            function (data) {
                var result = JSON.parse(data);
                BX('message_rez').innerHTML = result.message;
                reloadGridSert(); //Обновляем грид
                rowsNewLinks.forEach(e => e.parentNode.removeChild(e)); //Очищаем таблицу
                addPropLink(); //Генерим новое поле в таблицу
            }
        );
    }
    function reloadGridSert() {
        var reloadParams = { apply_filter: 'Y', clear_nav: 'Y' };
        var gridObject = BX.Main.gridManager.getById(<?=json_encode($GRID_ID)?>); // Идентификатор грида
        if (gridObject.hasOwnProperty('instance')){
            gridObject.instance.reloadTable('POST', reloadParams);
        }
    }
    function getRandomInt() {
        return Math.floor(Math.random() * 8999999) + 1000000;
    }
    function copyLink(link, idGroup) {
        BX('message_rez').innerHTML = "<div class=\"adm-info-message-wrap adm-info-message-green\"><div class=\"adm-info-message\"><div class=\"adm-info-message-title\">Cсылка с идентификатором " + link + " успешно скопирована</div><div class=\"adm-info-message-icon\"></div></div></div>";
        navigator.clipboard.writeText('https://extreme-look.ru/partners/activation/' + idGroup + '/' + link);
    }
    function delLink(idLink, cronID = false) {
        var data = {};
        BX('message_rez').innerHTML = '';
        data['action'] = 'delLink';
        data['idLink'] = idLink;
        data['cronID'] = cronID;
        BX.ajax.post(
            '/bitrix/admin/extremelook_links_ajax.php',
            data,
            function (data) {
                var result = JSON.parse(data);
                BX('message_rez').innerHTML = result.message;
                reloadGridSert();
            }
        );
    }
    function delTRLinc(id) {
        BX.cleanNode(document.getElementById(id), true);
    }
    function cal_block(idlink) {
        let tr = document.getElementById('EX_BLOCK_NONE_' + idlink),
            check = document.getElementById('EX_ID_USE_48_HOUR_' + idlink).checked;
        if(check) document.getElementById('EX_DATE_ACTIVE_LINK_' + idlink).value = '';
        tr.classList.toggle("none");
    }

    function addPropLink() {
        var table = document.getElementById('prop'),
            tableBody = table.getElementsByTagName("tbody")[0],
            r = getRandomInt();

        BX.append(BX.create({
                tag: 'tr',
                props: {
                    id: r
                },
                dataset: {
                    parent: 'EX_NEW_PROP_LINK'
                },
                children: [
                    BX.create({
                        tag: 'td',
                        props:{
                            align: 'center'
                        },
                        children:[
                            BX.create({
                                tag: 'div',
                                style: {position: 'static'},
                                props:{
                                    className: 'main-ui-item-icon-block main-ui-show'
                                },
                                children:[
                                    BX.create({
                                        tag: 'span',
                                        style: {position: 'static'},
                                        props:{
                                            className: 'main-ui-item-icon main-ui-delete'
                                        },
                                        attrs: {
                                            onclick: 'delTRLinc(' + r + ')'
                                        }
                                    })
                                ]
                            })
                        ]
                    }),
                    BX.create({
                        tag: 'td',
                        props:{
                            align: 'center'
                        },
                        children:[
                            BX.create({
                                tag: 'input',
                                props:{
                                    id: "EX_EMAIL_PART_" + r,
                                    type: 'text',
                                    maxlength: 100,
                                    size: 20
                                }
                            })
                        ]
                    }),
                    BX.create({
                        tag: 'td',
                        props:{
                            align: 'center'
                        },
                        children: [
                            BX.create({
                                tag: 'input',
                                props:{
                                    id: "EX_NAME_PART_" + r,
                                    type: 'text',
                                    maxlength: 100,
                                    size: 15
                                }
                            })
                        ]
                    }),
                    BX.create({
                        tag: 'td',
                        props:{
                            align: 'center'
                        },
                        children: [
                            BX.create({
                                tag: 'input',
                                props:{
                                    id: 'EX_ID_USE_48_HOUR_' + r,
                                    value: 'Y',
                                    checked: 'checked',
                                    type: 'checkbox',
                                    className: 'adm-designed-checkbox',
                                }
                            }),
                            BX.create({
                                tag: 'label',
                                attrs:{
                                    for: 'EX_ID_USE_48_HOUR_' + r,
                                    OnClick: 'if(this.checked) cal_block(' + r + '); else cal_block(' + r + ');'
                                },
                                props:{
                                    className: 'adm-designed-checkbox-label',
                                    value: 'Y',
                                    checked: 'checked',
                                    type: 'checkbox'
                                }
                            })
                        ]
                    }),
                    BX.create({
                        tag: 'td',
                        props:{
                            align: 'center'
                        },
                        children: [
                            BX.create({
                                tag: 'div',
                                props:{
                                    id: 'EX_BLOCK_NONE_' + r,
                                    className: 'none'
                                },
                                children:[
                                    BX.create({
                                        tag: 'div',
                                        props:{
                                            className: 'adm-input-wrap adm-calendar-inp adm-calendar-first'
                                        },
                                        children:[
                                            BX.create({
                                                tag: 'input',
                                                props:{
                                                    id: 'EX_DATE_ACTIVE_LINK_' + r,
                                                    className: 'adm-input adm-calendar-from',
                                                    size: 30,
                                                    type: 'text'
                                                }
                                            }),
                                            BX.create({
                                                tag: 'span',
                                                attrs:{
                                                    onClick: "BX.calendar({node:this, field:'EX_DATE_ACTIVE_LINK_" + r +"', form: '', bTime: true, bHideTime: false});",
                                                },
                                                props:{
                                                    name: "EX_DATE_ACTIVE_LINK_" + r,
                                                    className: 'adm-calendar-icon',
                                                    size: 30,
                                                    title: 'Нажмите для выбора даты'
                                                }
                                            })
                                        ]
                                    })
                                ]
                            })
                        ]
                    }),
                    BX.create({
                        tag: 'td',
                        props:{
                            align: 'center'
                        },
                        children: [
                            BX.create({
                                tag: 'select',
                                props:{
                                    id: 'EX_ID_WHAT_PARTNER_' + r,
                                },
                                children: [
                                    BX.create({
                                        tag: 'option',
                                        props: {
                                            text: 'Партнёр 15-24',
                                            value: 33
                                        },
                                        attrs: {
                                            selected: 'selected'
                                        }
                                    }),
                                    BX.create({
                                        tag: 'option',
                                        props: {
                                            text: 'Партнёр 25-49',
                                            value: 34
                                        }
                                    }),
                                    BX.create({
                                        tag: 'option',
                                        props: {
                                            text: 'Партнёр 50-99',
                                            value: 35
                                        }
                                    }),
                                    BX.create({
                                        tag: 'option',
                                        props: {
                                            text: 'Партнёр 100-199',
                                            value: 36
                                        }
                                    }),
                                    BX.create({
                                        tag: 'option',
                                        props: {
                                            text: 'Партнёр 200-499',
                                            value: 37
                                        }
                                    }),
                                    BX.create({
                                        tag: 'option',
                                        props: {
                                            text: 'Партнёр 500',
                                            value: 38
                                        }
                                    })
                                ]
                            })
                        ]
                    }),
                ]
        }),
        tableBody);
    }
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>