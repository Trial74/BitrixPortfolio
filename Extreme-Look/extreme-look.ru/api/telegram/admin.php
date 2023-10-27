<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>

<?use Bitrix\Iblock\Component\Base,
      Bitrix\Main\Loader;
Loader::includeModule("highloadblock");

use Bitrix\Highloadblock as HL;

header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$dir = $_SERVER['DOCUMENT_ROOT'] . '/upload/telegram_bot/comments';
$action = $request['action'];
$error = true;
$result = '';

if($action != 'countFiles'){
    $arData = array();
    $hlblock = HL\HighloadBlockTable::getById(15)->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "filter" => array('!UF_FILES' => ''),
    ));

    while ($resEData = $rsData->Fetch()){
        $arData[$resEData['UF_ID']] = array(
            'NAME' => $resEData['UF_NAME'],
            'LOGIN' => $resEData['UF_LOGIN']
        );
    }

    $resultDom = "<style>
    *{
      box-sizing:border-box;
    }
    body{
      margin:0;
    }
    .flex{
      display:flex;
      flex-wrap:wrap;
    }
    .item{
      height: 300px;
      width:calc( ( 100% - 300px ) / 4 );
      margin:0 0 20px;
    }
    .item:nth-child(3n-1){
      margin-left:20px;
      margin-right:20px;
    }
    .item>img{
      height: inherit;
      object-fit: contain;
    }
    </style>
    <div class=flex>";
}
$date = $request['date'];
$date_new = date_format(date_modify(date_create($date), "-1 month"), "d.m.Y");

$date = strtotime($date); //Выставленная дата
$date_new = strtotime($date_new); //Дата на месяц раньше выставленной

if($action == 'getFilesUser'){
    $user = $request['user'];
    $files = array_values(array_diff(scandir($dir . $user), array('..', '.')));


    foreach ($files as $file){
        $resultDom .= "<div class=item><img width='300px' src=/upload/telegram_bot/comments$user$file alt=$file/></div>";
    }

    $resultDom .= "</div>";

    $error = false;
    $result = $resultDom;
}elseif($action == 'countFiles' || $action == 'getFiles'){
    $count = 0;
    $allFiles = array();

    if ($md = opendir($dir)) {
        $smd =  array_values(array_diff(scandir($dir), array('..', '.')));
        foreach ($smd as $d){
            if($od = opendir($dir.DIRECTORY_SEPARATOR.$d)){
                while ($rod = readdir($od)){
                    if ($rod=='.' || $rod=='..') continue;

                    $allFiles[] = array('file' => $rod, 'user' => $d, 'name' => $arData[$d]['NAME'], 'login' => $arData[$d]['LOGIN']);
                }
                closedir($od);
            }
        }
    }
    foreach ($allFiles as $key => $file){
        $ex = explode("_", $file['file']);
        if(count($ex) == 2){
            if(strtotime($ex[0]) <= $date && strtotime($ex[0]) >= $date_new){
                if($action == 'countFiles'){
                    $count++;
                }elseif($action == 'getFiles'){
                    $resultDom .= "<div class=item><img width='300px' src=/upload/telegram_bot/comments".DIRECTORY_SEPARATOR.$file['user'].DIRECTORY_SEPARATOR.$file['file']." alt=".$file['user']."/><span>Имя: ".$file['name']." Логин: ".$file['login']."</span></div>";
                }
            }

        }
    }
    if ($action != 'countFiles'){
        $result = $resultDom .= "</div>";
    }else{
        $result = $count;
    }
    $error = false;
}

Base::sendJsonAnswer(array(
    'error' => $error,
    'result' => $result
));?>