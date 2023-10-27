<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if((isset($_POST['ID']) && !empty($_POST['ID'])) && (isset($_POST['value']) && !empty($_POST['value']))){
    $to = 'it@extreme-look.ru, distributor@extreme-look.ru, sales01@extreme-look.ru, sales02@extreme-look.ru, sales@extreme-look.ru';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $subject = 'Ошибка в сертификате';
    $message = 'Сообщение об ошибке сертификата от партнёра с ID - ' . $_POST['ID'] . '<br />Сообщение пользователя: ' . $_POST['value'];
    mail($to, $subject, $message, $headers);
    echo json_encode(
        [
            "result" => "Сообщение успешно отправлено, в ближайшее время мы отреагируем на ваше обращение!"
        ]
    );
    exit();
}
else{
    echo json_encode(
        [
            "result" => "Ошибка. Сообщение не отправлено. Обратитесь в <a href=\"mailto:it@extreme-look.ru\">техническую поддержку</a>"
        ]
    );
    exit();
}?>