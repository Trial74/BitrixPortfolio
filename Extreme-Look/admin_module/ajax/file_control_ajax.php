<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>
<? //** AJAX ОБРАБОТЧИК ДЛЯ КОНТРОЛЯ РАЗМЕРА ФАЙЛОВ (by VLADOS) **// ?>
<?use Bitrix\Main\Loader;?>

<?if($_POST && $_POST['action'] == 'startBase') {
    $arFilesCache = array();

    if(!empty($_POST['module']) && isset($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] >= 0) {
        $limit = $_POST['limit'] == 0 ? '' : 'LIMIT ' . $_POST['limit'];
        $result = $DB->Query('SELECT FILE_NAME, SUBDIR, FILE_SIZE FROM b_file WHERE MODULE_ID = "' . $_POST['module'] . '" ORDER BY FILE_SIZE DESC ' . $limit);
    }
    else{
        echo errorAJAX('Неверно передан один из параметров запроса к базе');
        die();
    }

    while ($row = $result->Fetch()) {
        $arFilesCache[] = [get_size($row['FILE_SIZE']), $row['FILE_NAME'], 'https://extreme-look.ru/upload/' . $row['SUBDIR'] . '/' . $row['FILE_NAME']];
    }

    echo json_encode(
        [
            "result" => 'OK',
            "message" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
                                <div class=\"adm-info-message\">
                                    <div class=\"adm-info-message-title\">
                                    Информация загружена успешно
                                    </div>
                                    <div class=\"adm-info-message-icon\"></div>
                                </div>
                            </div>",
            "tablestr" => $arFilesCache
        ]
    );
}
else{
    echo json_encode(
        [
            "result" => 'Faile',
            "message" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
                            <div class=\"adm-info-message\">
                                <div class=\"adm-info-message-title\">
                                Ошибка запроса
                                </div>
                                <div class=\"adm-info-message-icon\"></div>
                            </div>
                        </div>"
        ]
    );
}

function errorAJAX($message){
    return json_encode(
        [
            "result" => 'Faile',
            "message" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
                            <div class=\"adm-info-message\">
                                <div class=\"adm-info-message-title\">
                                $message
                                </div>
                                <div class=\"adm-info-message-icon\"></div>
                            </div>
                        </div>"
        ]
    );
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
?>
