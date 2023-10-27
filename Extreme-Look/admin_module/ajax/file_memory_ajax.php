<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>
<? //** AJAX ОБРАБОТЧИК ДЛЯ ОЧИСТКИ МЕСТА НА САЙТЕ (by VLADOS) **// ?>
<?use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");
use Bitrix\Highloadblock as HL,
    Bitrix\Main\Entity;
if($_POST && $_POST['action'] == 'start') {
    $start = microtime(true);
    $message = true;
    $hlbl = 7;
    $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rootDir = $_POST['root_dir']; //Каталог очистки передаётся со страницы запуска
    $backupDir = $_POST['backup_dir']; //Каталог с бекапом передаётся со страницы запуска
    $RootDirOpen = opendir($rootDir); //Открываем каталог очистки
    $i = 1; //Счётчик найденных файлов
    $limit = $_POST['limit'] ? $_POST['limit'] : 0; //Лимит по каталогам первого уровня передаётся со страницы запуска
    $rootDirCounts = 0; //Счётчик обхода каталогов первого уровня
    $sleep = $_POST['sleep'] ? $_POST['sleep'] : 0; //Значение пропуска каталогов передаётся со страницы запуска
    $sleepCount = 0; //Счётчик пропуска каталогов
    $summSize = 0;
    $searchInBaseCount = 0;//Счётчик найденых в базе файлов
    $clearCount = 0; //Счётчик удалённых файлов
    $arFilesCache = array(); //Массив для хранения информации
    $result = $DB->Query('SELECT FILE_NAME, SUBDIR FROM b_file WHERE MODULE_ID = "iblock"');

    while ($row = $result->Fetch()) {
        $arFilesCache[$row['FILE_NAME']] = $row['SUBDIR'];
    }

    function get_size( $bytes )
    {
        if ( $bytes < 1000 * 1024 ) {
            return number_format( $bytes / 1024, 2 ) . " KB";
        }
        elseif ( $bytes < 1000 * 1048576 ) {
            return number_format( $bytes / 1048576, 2 ) . " MB";
        }
        elseif ( $bytes < 1000 * 1073741824 ) {
            return number_format( $bytes / 1073741824, 2 ) . " GB";
        }
        else {
            return number_format( $bytes / 1099511627776, 2 ) . " TB";
        }
    }

    function search_file($path){ //Ищет файлы определённых расширенйи в каталоге, выдаёт массив на выходе
        $arFiles = [];
        $arExtensions = [
            0 => '/*.jpeg',
            1 => '/*.jpg',
            2 => '/*.webp',
            3 => '/*.gif',
            4 => '/*.png'
        ];

        foreach ($arExtensions as $extension){
            foreach (glob($path . $extension) as $file) {
                $arFiles[$file] = filesize($file) ? filesize($file) : 'Error size';
            }
        }
        return $arFiles ? $arFiles : false;
    }

    function search_file_in_base($arbase, $fileName){
        if (array_key_exists($fileName, $arbase)) { //Файл с диска есть в списке файлов базы - пропуск
            return true;
        } else return false;
    }

    function delete_file($level, $pathToDir, $pathToFile){
        $result = [];
        if(unlink($pathToFile)){ //Удаляем файл
            $result['removieFile'] = true;
        }
        else $result['removieFile'] = false;
        if($level > 1){ //Нужно удалять директории только второго и выше уровней
            if(!search_file($pathToDir)){ //Если директория пустая удаляем её
                if(rmdir($pathToDir)){ //Проверяем удачно ли удалена директория
                    $result['removieDir'] = true;
                }
                else $result['removieDir'] = false;
            }
            else $result['removieDir'] = false;
        }
        else $result['removieDir'] = false;
        return $result; //Возвращаем результат
    }

    while(false !== ($rootDirRead = readdir($RootDirOpen))) { //Чтение главного каталога upload
        if($limit != 0 && $rootDirCounts == $limit) break;
        if ($rootDirRead == '.' || $rootDirRead == '..' || $rootDirRead == '.DS_Store') {
            continue;
        }
        if($sleep != 0 && $sleepCount <= $sleep){
            $sleepCount++;
            continue;
        }
        $serchFilesByFirstDir = search_file("$rootDir/$rootDirRead"); //Поиск в папках первого уровня
        if($serchFilesByFirstDir){
            foreach($serchFilesByFirstDir as $key => $files){
                $searchInBase = search_file_in_base($arFilesCache, basename($key));
                if($searchInBase) $searchInBaseCount++;
                else {
                    CopyDirFiles("$rootDir/$rootDirRead/" . basename($key), "$backupDir/$rootDirRead/" . basename($key)); //Копия в бэкап
                    $resultDel = delete_file(1,"$rootDir/$rootDirRead", "$rootDir/$rootDirRead/" . basename($key)); //Удаляем файл и его каталог
                    $clearCount++;
                }
                $summSize = $summSize + $files;
                $i++;
            }
        }
        $firstDearOpen = opendir("$rootDir/$rootDirRead");
        while (false !== ($firstDirRead = readdir($firstDearOpen))) { //Чтение дочерних каталогов в upload
            if ($firstDirRead == '.' || $firstDirRead == '..' || $rootDirRead == '.DS_Store') {
                continue;
            }
            $explodeDir = explode(".", $firstDirRead);
            if(count($explodeDir) > 1) continue; //Если файл то пропускаем
            $searchFilesBySecondDir = search_file("$rootDir/$rootDirRead/$firstDirRead"); //Поиск в папках второго уровня
            if($searchFilesBySecondDir){
                foreach ($searchFilesBySecondDir as $key => $files) {
                    $searchInBase = search_file_in_base($arFilesCache, basename($key));
                    if($searchInBase) $searchInBaseCount++;
                    else {
                        CopyDirFiles("$rootDir/$rootDirRead/$firstDirRead/" . basename($key), "$backupDir/$rootDirRead/$firstDirRead/" . basename($key)); //Копия в бэкап
                        $resultDel = delete_file(2,"$rootDir/$rootDirRead/$firstDirRead", "$rootDir/$rootDirRead/$firstDirRead/" . basename($key));
                        $clearCount++;
                    }
                    $summSize = $summSize + $files;
                    $i++;
                }
            }
        }
        closedir($firstDearOpen);
        $rootDirCounts++;
    }
    closedir($RootDirOpen);

    $i = $i == 0 ? 0 : $i - 1;
    $summSize = get_size($summSize);
    $end = round(microtime(true) - $start, 4);

    if($i > 0) {
        $addData = array(
            "UF_SIZE_CLEAR" => $summSize,
            "UF_QUANTITY_DIR" => $rootDirCounts,
            "UF_SEARCH_BASE" => $searchInBaseCount,
            "UF_CLEAR_SESS" => $clearCount,
            "UF_TIME_WORK" => $end,
            "UF_ALL_FILES" => $i
        );
        $entity_data_class::add($addData);
    }
    echo json_encode(
        [
            "result" => 'OK',
            "message" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
                                        <div class=\"adm-info-message\">
                                            <div class=\"adm-info-message-title\">
                                            Очистка завершена<br />
                                            Всего файлов найдено:  $i<br />
                                            Каталогов первого уровня пройдено:  $rootDirCounts<br />
                                            Найдено файлов в базе:  $searchInBaseCount<br />
                                            Удалено файлов за запуск:  $clearCount<br />
                                            Очищено: $summSize<br />
                                            Время выполнения скрипта: $end сек.
                                            </div>
                                            <div class=\"adm-info-message-icon\"></div>
                                        </div>
                                    </div>"
        ]
    );
}
elseif($_POST && $_POST['action'] == 'countdir'){
    $rootDir = $_POST['root_dir'];
    $RootDirOpen = opendir($rootDir);
    $countDir = 0;
    while(false !== ($rootDirRead = readdir($RootDirOpen))) { //Чтение главного каталога upload
        if ($rootDirRead == '.' || $rootDirRead == '..') {
         continue;
        }
        $countDir++;
    }
    echo json_encode(
        [
            "result" => 'OK',
            "message" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
                                        <div class=\"adm-info-message\">
                                            <div class=\"adm-info-message-title\">Количество дочерних каталогов в папке: $countDir</div>
                                            <div class=\"adm-info-message-icon\"></div>
                                        </div>
                                    </div>"
        ]
    );
}
elseif($_POST && $_POST['action'] == 'namedir'){

    $iter = $_POST['val'];
    $iterCount = 0;
    $rootDir = $_POST['root_dir'];
    $rootDirOpen = opendir($rootDir);
    $search = false;

    while(false !== ($rootDirRead = readdir($rootDirOpen))) {
        if ($rootDirRead == '.' || $rootDirRead == '..') {
            continue;
        }
        if($iterCount == $iter){
            $search = true;
            echo json_encode(
                [
                    "result" => 'OK',
                    "message" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
                                        <div class=\"adm-info-message\">
                                            <div class=\"adm-info-message-title\">$rootDirRead</div>
                                            <div class=\"adm-info-message-icon\"></div>
                                        </div>
                                    </div>"
                ]
            );
            break;
        }
        $iterCount++;
    }
    if(!$search)
        echo json_encode(
            [
                "result" => 'Error',
                "message" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
                                        <div class=\"adm-info-message\">
                                            <div class=\"adm-info-message-title\">Каталог не найден - превышено число итераций поиска</div>
                                            <div class=\"adm-info-message-icon\"></div>
                                        </div>
                                    </div>"
            ]
        );

}
else{
    echo json_encode(
        [
            "result" => 'Error',
            "message" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
                                        <div class=\"adm-info-message\">
                                            <div class=\"adm-info-message-title\">Ошибочка вышла</div>
                                            <div class=\"adm-info-message-icon\"></div>
                                        </div>
                                    </div>"
        ]
    );
}
?>
