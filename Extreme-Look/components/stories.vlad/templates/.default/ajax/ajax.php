<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>
<?use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");
use Bitrix\Highloadblock as HL,
    Bitrix\Main\Entity;

$hlbl = 6;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array("ID" => $_POST['id'])
));

while($arData = $rsData->Fetch()){
    $res[] = $arData;
}

$views = (int)$res['UF_VIEW'];
$ios = (int)$res['UF_DEVICE_IOS'];
$android = (int)$res['UF_DEVICE_ANDROID'];
$users = $res['UF_USER'];

if($_POST['device'] == 'ios') ++$ios;
if($_POST['device'] == 'android') ++$android;
if($_POST['user']) $users .= ', ' . $_POST['user'];

$data = array(
    "UF_VIEW" => ++$views,
    "UF_USER" => $users,
    "UF_DEVICE_IOS" => $ios,
    "UF_DEVICE_ANDROID" => $android
);

$result = $entity_data_class::update($_POST['id'], $data);

echo json_encode(
    [
        "data" => $arData,
        "res" => $res,
        "ios" => $ios,
        "view" => $views + 1
    ]
);
?>