<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use Bitrix\Main\Loader;

\Bitrix\Main\UI\Extension::load("ui.hint");
\Bitrix\Main\UI\Extension::load("ui.buttons");

Loader::includeModule("highloadblock");

use Bitrix\Highloadblock as HL,
    Bitrix\Main\GroupTable,
    Bitrix\Main\Entity;

IncludeModuleLangFile(__FILE__);

$APPLICATION->SetTitle('Конструктор товаров MIXON - Настройки');

if(!$USER->IsAdmin() || !$USER->GetID() == 10354){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$hlbl = 13;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$settingsJSON = '';
$settingsParse = array();

if(isset($_REQUEST['C_SETTINGS']) && !empty($_REQUEST['C_SETTINGS'])){
    $requesrError = false;
    $data = array();

    if(isset($_REQUEST['C_SETTINGS']['OBJ_SETTING']) && !empty($_REQUEST['C_SETTINGS']['OBJ_SETTING']) && is_array($_REQUEST['C_SETTINGS']['OBJ_SETTING'])){
        if(empty($_REQUEST['C_SETTINGS']['OBJ_SETTING']['LOGS']))
            $_REQUEST['C_SETTINGS']['OBJ_SETTING']['LOGS'] = 'N';
        if(empty($_REQUEST['C_SETTINGS']['OBJ_SETTING']['REDUCTION']))
            $_REQUEST['C_SETTINGS']['OBJ_SETTING']['REDUCTION'] = 0;
        if(empty($_REQUEST['C_SETTINGS']['OBJ_SETTING']['MAX_CIRC']))
            $_REQUEST['C_SETTINGS']['OBJ_SETTING']['MAX_CIRC'] = 0;

        $data['UF_OBJECT_SETTINGS'] = json_encode($_REQUEST['C_SETTINGS']['OBJ_SETTING']);
    }else $requesrError = true;

    if(!$requesrError){
        $data["UF_HIDE_SECTIONS"] = isset($_REQUEST['C_SETTINGS']['HIDE_SECTIONS']) ? $_REQUEST['C_SETTINGS']['HIDE_SECTIONS'] : array();
        $data["UF_HIDE_ITEMS"] = isset($_REQUEST['C_SETTINGS']['HIDE_ITEMS']) ? $_REQUEST['C_SETTINGS']['HIDE_ITEMS'] : array();
        $data["UF_SECTION_DIZAIN"] = isset($_REQUEST['C_SETTINGS']['DIZAIN_SECTION']) ? $_REQUEST['C_SETTINGS']['DIZAIN_SECTION'] : '';
        $data["UF_SECTION_UPAK"] = isset($_REQUEST['C_SETTINGS']['UPAK_SECTION']) ? $_REQUEST['C_SETTINGS']['UPAK_SECTION'] : '';
        $data["UF_SECTION_UR"] = isset($_REQUEST['C_SETTINGS']['UR_SECTION']) ? $_REQUEST['C_SETTINGS']['UR_SECTION'] : '';
        $data["UF_SKU_PROPS"] = isset($_REQUEST['C_SETTINGS']['SKU_SETTINGS']) ? $_REQUEST['C_SETTINGS']['SKU_SETTINGS'] : array();
        $data["UF_QUANT_SERVICES"] = isset($_REQUEST['C_SETTINGS']['QUANT_SERV']) ? $_REQUEST['C_SETTINGS']['QUANT_SERV'] : array();
        $data["UF_SKU_HIDDEN"] = isset($_REQUEST['C_SETTINGS']['SKUHIDDEN']) ? $_REQUEST['C_SETTINGS']['SKUHIDDEN'] : '';

        $result = $entity_data_class::update("1", $data);
    }
}


$skuPropertyes = \Bitrix\Iblock\PropertyTable::getList(array(
    'filter' => array('IBLOCK_ID'=>125, 'ACTIVE'=>'Y', '!%CODE' => array('CML2_', 'MORE', 'FILES')),
))->fetchAll();

foreach($skuPropertyes as $arProperty)
{
    $arSuProperty[$arProperty['ID']] = array(
        'ID'=> $arProperty['ID'],
        'NAME' => $arProperty['NAME'],
        'CODE' => $arProperty['CODE']
    );
}

$rsData = $entity_data_class::getList(array(
    "select" => array("UF_*"),
    "filter" => array('ID' => 1)
))->fetch();

$settingsJSON = json_decode($rsData['UF_OBJECT_SETTINGS'], true);

if(!empty($settingsJSON) && is_array($settingsJSON)){
    $settingsParse['LOGS'] = (string)$settingsJSON['LOGS'];
    $settingsParse['REDUCTION'] = (int)$settingsJSON['REDUCTION'];
    $settingsParse['VOLUNITS'] = (string)$settingsJSON['VOLUMES'];
    $settingsParse['MAX_CIRC'] = (int)$settingsJSON['MAX_CIRC'];
}else{
    $settingsParse['ERROR'] = true;
}

$rsSection = \Bitrix\Iblock\SectionTable::getList(array(
    'filter' => array(
        'IBLOCK_ID' => 124,
        'DEPTH_LEVEL' => array(1, 2),
    ),
    'select' =>  array('ID', 'IBLOCK_SECTION_ID', 'ACTIVE', 'SORT', 'NAME', 'DEPTH_LEVEL', 'DESCRIPTION'),
));

while ($arSection = $rsSection->fetch())
{
    $arSectionRes[] = $arSection;
}

$rsItems = \Bitrix\Iblock\Elements\ElementCatalogMixonTable::getList([
    'select' => ['ID', 'NAME', 'IBLOCK_SECTION_ID'],
    'filter' => ['=ACTIVE' => 'Y'],
    'order' => ['ID' => 'ASC']
])->fetchAll();

if(!empty($rsItems)){
    $servicesSects = array(
        $rsData['UF_SECTION_DIZAIN'],
        $rsData['UF_SECTION_UPAK'],
        $rsData['UF_SECTION_UR']
    );
    $servQuant = array();

    foreach ($rsItems as $item){
        if(array_search($item['IBLOCK_SECTION_ID'], $servicesSects) !== false){
            $servQuant[] = $item;
        }
    }
    unset($item, $servicesSects);
}else{
    $servQuant = array();
}

$aTabs = array(
    array("DIV" => "settings", "TAB" => GetMessage('MIXON_CONSTRUCTOR_CONTROL_TAB_1'), "ICON"=>"main_user_edit", "TITLE" => GetMessage('MIXON_CONSTRUCTOR_CONTROL_TAB_1'))
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
<?if($requesrError){
    $messageRequesrError = new CAdminMessage(GetMessage("MIXON_CONSTRUCTOR_ERROR_REQUEST"));
    echo $messageRequesrError->Show();
}
if(isset($settingsParse['ERROR'])){
    $messageSettingsParseError = new CAdminMessage(GetMessage("MIXON_CONSTRUCTOR_ERROR_PARSE"));
    echo $messageSettingsParseError->Show();
}?>
<form method="POST">
<?$tabControl->Begin();
$tabControl->BeginNextTab();?>
    <tr class="heading">
        <td colspan="2"><b><?=GetMessage('MIXON_CONSTRUCTOR_TABLE_HEAD')?></b></td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_HIDE_SECTIONS')?></td>
        <td width="50%">
            <select size="12" name="C_SETTINGS[HIDE_SECTIONS][]" multiple>
                <option value<?=empty($rsData['UF_HIDE_SECTIONS']) ? ' selected' : ''?>><?=GetMessage('MIXON_CONSTRUCTOR_NO_SELECT')?></option>
                <?foreach ($arSectionRes as $sect){
                    $key = array_search($sect['ID'], $rsData['UF_HIDE_SECTIONS']);
                    $level = $sect['DEPTH_LEVEL'] == 1 ? ' . ' : ' . . ';?>
                    <option value="<?=$sect['ID']?>"<?=$key || $key === 0 ? ' selected' : ''?>><?=$level . $sect['NAME']?></option>
                <?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_HIDE_ITEMS')?></td>
        <td width="50%">
            <select size="12" name="C_SETTINGS[HIDE_ITEMS][]" multiple>
                <option value<?=empty($rsData['UF_HIDE_ITEMS']) ? ' selected' : ''?>><?=GetMessage('MIXON_CONSTRUCTOR_NO_SELECT')?></option>
                <?foreach ($rsItems as $item){
                    $key = array_search($item['ID'], $rsData['UF_HIDE_ITEMS']);?>
                    <option value="<?=$item['ID']?>"<?=$key || $key === 0 ? ' selected' : ''?>><?=$item['NAME']?></option>
                <?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_DIZAIN_SECTION')?></td>
        <td width="50%">
            <select name="C_SETTINGS[DIZAIN_SECTION]">
                <?foreach ($arSectionRes as $sect){
                    $key = $sect['ID'] === $rsData['UF_SECTION_DIZAIN'];
                    $level = $sect['DEPTH_LEVEL'] == 1 ? ' . ' : ' . . ';?>
                    <option value="<?=$sect['ID']?>"<?=$key || $key === 0 ? ' selected' : ''?>><?=$level . $sect['NAME']?></option>
                <?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_UPAK_SECTION')?></td>
        <td width="50%">
            <select name="C_SETTINGS[UPAK_SECTION]">
                <?foreach ($arSectionRes as $sect){
                    $key = $sect['ID'] === $rsData['UF_SECTION_UPAK'];
                    $level = $sect['DEPTH_LEVEL'] == 1 ? ' . ' : ' . . ';?>
                    <option value="<?=$sect['ID']?>"<?=$key || $key === 0 ? ' selected' : ''?>><?=$level . $sect['NAME']?></option>
                <?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_UR_SECTION')?></td>
        <td width="50%">
            <select name="C_SETTINGS[UR_SECTION]">
                <?foreach ($arSectionRes as $sect){
                    $key = $sect['ID'] === $rsData['UF_SECTION_UR'];
                    $level = $sect['DEPTH_LEVEL'] == 1 ? ' . ' : ' . . ';?>
                    <option value="<?=$sect['ID']?>"<?=$key || $key === 0 ? ' selected' : ''?>><?=$level . $sect['NAME']?></option>
                <?}?>
            </select>
        </td>
    </tr>
    <tr class="heading">
        <td colspan="2"><b><?=GetMessage('MIXON_CONSTRUCTOR_PROPERTIES_PRODUCT')?></b></td>
    </tr>
    <tr></tr>
    <tr class="heading">
        <td colspan="2"><b><?=GetMessage('MIXON_CONSTRUCTOR_SKU_HEAD')?></b></td>
    </tr>
    <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_SKU_PROPS')?></td>
    <td width="50%">
        <select name="C_SETTINGS[SKU_SETTINGS][]" size="8" multiple>
            <option value<?=empty($rsData['UF_SKU_PROPS']) ? ' selected' : ''?>><?=GetMessage('MIXON_CONSTRUCTOR_NO_SELECT')?></option>
            <?foreach ($arSuProperty as $prop){
                $key = array_search($prop['ID'], $rsData['UF_SKU_PROPS']);?>
                <option value="<?=$prop['ID']?>"<?=$key || $key === 0 ? ' selected' : ''?>><?='[' . $prop['CODE'] . '] ' . $prop['NAME']?></option>
            <?}?>
        </select>
    </td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_SKU_HIDDEN')?></td>
        <td width="50%">
            <input type="text" name="C_SETTINGS[SKUHIDDEN]" size="30" value="<?=$rsData['UF_SKU_HIDDEN']?>">
        </td>
    </tr>
    <tr class="heading">
        <td colspan="2"><b><?=GetMessage('MIXON_CONSTRUCTOR_SETTINGS_CONSTRUCTOR')?></b></td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_LOGS')?></td>
        <td width="50%">
            <input type="checkbox" name="C_SETTINGS[OBJ_SETTING][LOGS]" id="logs" value="Y" <?=$settingsParse['LOGS'] === 'Y' ? 'checked' : ''?> class="adm-designed-checkbox">
            <label class="adm-designed-checkbox-label" for="logs"></label>
        </td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_REDUCTION_TEXT')?></td>
        <td width="50%">
            <input type="text" name="C_SETTINGS[OBJ_SETTING][REDUCTION]" size="30" value="<?=$settingsParse['REDUCTION']?>">
        </td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_VOLUMES')?></td>
        <td width="50%">
            <input type="text" name="C_SETTINGS[OBJ_SETTING][VOLUMES]" size="30" value="<?=$settingsParse['VOLUNITS']?>">
        </td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_MAX_CIRC')?></td>
        <td width="50%">
            <input type="text" name="C_SETTINGS[OBJ_SETTING][MAX_CIRC]" size="30" value="<?=$settingsParse['MAX_CIRC']?>">
        </td>
    </tr>
    <tr>
        <td width="50%"><?=GetMessage('MIXON_CONSTRUCTOR_QUANT_SERVICES')?></td>
        <td width="50%">
            <select size="12" name="C_SETTINGS[QUANT_SERV][]" multiple>
                <option value<?=empty($rsData['UF_QUANT_SERVICES']) ? ' selected' : ''?>><?=GetMessage('MIXON_CONSTRUCTOR_NO_SELECT')?></option>
                <?foreach ($servQuant as $item){
                    $key = array_search($item['ID'], $rsData['UF_QUANT_SERVICES']);?>
                    <option value="<?=$item['ID']?>"<?=$key || $key === 0 ? ' selected' : ''?>><?=$item['NAME']?></option>
                <?}?>
            </select>
        </td>
    </tr>


<?$tabControl->EndTab();
$tabControl->Buttons(
    array(
        "disabled" => false,
        "btnApply" => false,
        "back_url" => false,
        "btnSave" => false
    )
);?>
    <input class="adm-btn-save"
           type="submit"
           id="butStart"
           value="<?=GetMessage('MIXON_CONSTRUCTOR_APPLY_BUTTON')?>"
           title="<?=GetMessage('MIXON_CONSTRUCTOR_APPLY_BUTTON')?>"
           onclick="save()"
    />
<?$tabControl->End();?>
</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>