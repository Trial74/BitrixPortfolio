<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//<!-- **ГЕНЕРАТОР СЕРТИФИКАТОВ ДЛЯ ПАРТНЁРОВ by VLADOS Мой код** -->//?>
<?//<!-- **Компонент работает по AJAX из админки, не использовать в шаблоне html! Не изменять ничего без тестов, иначе всё поплывёт** -->//?>
<?include($_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/lib/qrCodeLib/qrlib.php');//Библиотека QR?>
<?include($_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/lib/ImgLibClass.php');//Моя библиотека по работе с фотками?>
<?//!!use ajax не подхватывает, классы подключил напрямую!! use VladClasses\ImageLibrary, //Моя библиотека по работе с фотками
     use Bitrix\Main\FileTable; //Класс для работы с файлами и изображениями?>
<?$filter = Array("GROUPS_ID" => ALL_PART, "ID" => $arParams['ID_PARTNER'], "ACTIVE" => "Y");
$rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter, array('SELECT' => ['ID,', 'UF_COUNTRY', 'UF_COUNTRY_URL', 'UF_SITY_PAT', 'UF_SITY_URL', 'UF_MAP_ADDRESS', 'UF_SERTIFICATE']));
$user = $rsUsers->fetch();
if((isset($user['WORK_COMPANY']) && !empty($user['WORK_COMPANY']) && iconv_strlen($user['WORK_COMPANY']) <= 70) &&
    (isset($arParams['DATA']) && !empty($arParams['DATA']) && $arParams['DATA'] == 'add') &&
    $user
) //Проверка полей перед запуском генераторов
{
    $i = 0;
    $arGroups = CUser::GetUserGroup($arParams['ID_PARTNER']);
   /* while($i < count($arGroups)){
        switch($arGroups[$i]){ //Выбираем необходимый макет в зависимости от статуса партнёра
            case 9:
                $maket = 'sert_partner';
                break;
            case 11:
                $maket = 'sert_gold';
                break;
            case 12:
                $maket = 'sert_serebro';
                break;
            case 13:
                $maket = 'sert_platina';
                break;
        }
        $i++;
    }*/
    $maket = 'sert_all';
    $_imageClass = new VladClasses\ImageLibrary; //Мой класс по обработке изображений
    $_QRClass = new QRcode; //Класс генератора QR

    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'imgQRResult'.DIRECTORY_SEPARATOR; //Папка с результатами
    $errorCorrectionLevel = 'Q'; //Параметры чекай доку https://snipp.ru/php/qr-code
    $matrixPointSize = 4; //Параметры чекай доку https://snipp.ru/php/qr-code
    $rand = mt_rand(); //Генерация случайной строки для имени файла сертификата

    //Задаём строку для шифрования
    $text = 'https://extreme-look.ru/partner/' . $arParams['ID_PARTNER'];

    $md5 = md5($text . '|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png'; //Имя файла с QR кодом
    $filenameS = $PNG_TEMP_DIR . $md5; //Путь по серваку
    $filenameU = $templateFolder . '/imgQRResult/' . $md5; //Путь по URL
    QRcode::png($text, $filenameS, $errorCorrectionLevel, $matrixPointSize, 2); //Генерим QR код

    $text = $user['WORK_COMPANY'];//Задаём текст для наложения (название кампании партнёра)
    $validationText = $_imageClass->validatortext($text); //Валидируем текст на количество символов чтобы сделать перенос на новую строку если текст не влазит. Вернёт false если более 70 символов
    $_imageClass->load(__DIR__ . '/imgSerts/' . $maket . '.png'); //Загружаем макет. Метод вернет true или false
    $_imageClass->ftext($validationText); //Накладываем текст (функция настроена под размеры конкретно данного макета, при изменении макета поменяй функцию класса ImageLibrary!)
    $_imageClass->watermark($filenameS); //Накладываем QR код (функция настроена под размеры конкретно данного макета, при изменении макета поменяй функцию класса ImageLibrary!)
    $_imageClass->save(__DIR__ . '/imgSertResult/sert_' . $rand . '.png', IMAGETYPE_PNG); //Сохраняем готовый сертификат партнёра

    $userObj = new CUser;
    if(isset($user['UF_SERTIFICATE']) && !empty($user['UF_SERTIFICATE'])){
         CFile::Delete($user['UF_SERTIFICATE']);
         $fields = Array(
             "UF_SERTIFICATE" => ''
         );
         $userObj->Update($arParams['ID_PARTNER'], $fields);
    }
    $fields = Array(
        "UF_SERTIFICATE" => CFile::MakeFileArray(dirname(__FILE__).DIRECTORY_SEPARATOR.'imgSertResult'.DIRECTORY_SEPARATOR.'sert_' . $rand . '.png')
    );
    $userObj->Update($arParams['ID_PARTNER'], $fields);
    unlink(dirname(__FILE__).DIRECTORY_SEPARATOR.'imgSertResult'.DIRECTORY_SEPARATOR.'sert_' . $rand . '.png');
    unlink($filenameS);//Удаляем файл чтобы не складировались, если нужно оставлять файлы на серваке закомментируй
    echo json_encode(
        [
            "result" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
				<div class=\"adm-info-message\">
					<div class=\"adm-info-message-title\">Сертификат для партнёра с ID-".$arParams['ID_PARTNER']." успешно сгенерирован.</div>
					<div class=\"adm-info-message-icon\"></div>
				</div>
			</div>"
        ]
    );
    unset($rsUsers, $user, $i, $arGroups, $maket);
    exit();
}elseif(isset($arParams['DATA']) && !empty($arParams['DATA']) && $arParams['DATA'] == 'delete' && $user){//Удаление сертификата у партнёра
    if(isset($user['UF_SERTIFICATE']) && !empty($user['UF_SERTIFICATE'])){
        CFile::Delete($user['UF_SERTIFICATE']);
        $userObj = new CUser;
        $fields = Array(
            "UF_SERTIFICATE" => ''
        );
        $userObj->Update($arParams['ID_PARTNER'], $fields);
        echo json_encode(
            [
                "result" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
				<div class=\"adm-info-message\">
					<div class=\"adm-info-message-title\">Сертификат пользователя:".$arParams['ID_PARTNER']." успешно удалён</div>
					<div class=\"adm-info-message-icon\"></div>
				</div>
			</div>"
            ]
        );
    }
    else{
        echo json_encode(
            [
                "result" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
				<div class=\"adm-info-message\">
					<div class=\"adm-info-message-title\">У пользователя:".$arParams['ID_PARTNER']." нет сертификата</div>
					<div class=\"adm-info-message-icon\"></div>
				</div>
			</div>"
            ]
        );
    }
}
else{ //Поля не прошли проверку возвращаем объект ошибки убиваем скрипт
    echo json_encode(
        [
            "result" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
				<div class=\"adm-info-message\">
					<div class=\"adm-info-message-title\">Ошибка в работе AJAX - Либо пользователь с ID:".$arParams['ID_PARTNER']." не найден, либо он не партнёр, либо поля у пользователя заполнены неверно, либо какоето из обязательных полей не заполнено совсем, либо поле Организация превышает 70 символов (с учётом пробелов).</div>
					<div class=\"adm-info-message-icon\"></div>
				</div>
			</div>"
        ]
    );
    unset($rsUsers, $user, $i, $arGroups, $maket);
    exit();
}
