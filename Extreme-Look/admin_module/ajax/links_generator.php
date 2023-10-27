<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>
                            <? //** AJAX ОБРАБОТЧИК ДЛЯ ГЕНЕРАТОРА ССЫЛОК, ВСЁ ИНТУИТИВНО ПОНЯТНО, КОММЕНТОВ ПО МИНИМУМУ, ВЗАВИСИМОСТИ ОТ POST action ВЫПОЛНЯЕТСЯ ДЕЙСТВИЕ (by VLADOS) **// ?>
<?use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");
use Bitrix\Highloadblock as HL,
    Bitrix\Main\Entity,
    Bitrix\Main\Mail\Event,
    Bitrix\Iblock\Component\Base;

header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$message = true;
$hlbl = 4;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
if($request->isAjaxRequest()) {
    $action = $request['action'];
    if($action == 'addOneLink') {
        global $USER;
        $link = randString(7);
        $date = new DateTime('+2 days');
        $data = array(
            "UF_LINK_NAME" => $link,
            "UF_USE_LINK" => false,
            "UF_CR_NAME" => $USER->GetFullName(),
            "UF_USE_NAME" => '',
            "UF_EMAIL_PART" => $request['linksdata'][0]['EX_EMAIL_PART'],
            "UF_NAME_PART" => $request['linksdata'][0]['EX_NAME_PART'],
            "UF_24_HOUR" => $request['linksdata'][0]['EX_ID_USE_48_HOUR'],
            "UF_DATE_TIME" => date("d.m.Y H:i"),
            "UF_DATE_ACTIVE" => $request['linksdata'][0]['EX_DATE_ACTIVE_LINK'] ? $request['linksdata'][0]['EX_DATE_ACTIVE_LINK'] : $date->format('d.m.Y H:i'),
            "UF_GROUP_PART" => $request['linksdata'][0]['EX_GROUP_PARTNER']
        );
        if ($result = $entity_data_class::add($data)) {
            if ($request['linksdata'][0]['EX_ID_USE_48_HOUR']) { //Если выбрана ссылка на 48 часов создаём агента на удаление ссылки спустя двое суток

                $idAgent = CAgent::AddAgent(
                    "delLinkAgentPart(" . $result->getId() . ");",  // имя функции удаления ссылки в инит
                    "",
                    "N",
                    0,
                    "",
                    "Y",
                    $request['linksdata'][0]['EX_DATE_ACTIVE_LINK'] ? $request['linksdata'][0]['EX_DATE_ACTIVE_LINK'] : $date->format('d.m.Y H:i')//дата запуска - спустя двое суток или выбранная пользователем дата
                );
                $updData = array(
                    "UF_CRON_ID" => $idAgent,
                );
                $resultUpd = $entity_data_class::update($result->getId(), $updData);
            }
            Base::sendJsonAnswer(array(
                "result" => $result,
                "message" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
                                    <div class=\"adm-info-message\">
                                        <div class=\"adm-info-message-title\">Cсылка с идентификатором " . $link . " успешно добавлена</div>
                                        <div class=\"adm-info-message-icon\"></div>
                                    </div>
                                </div>"
            ));
        } else {
            Base::sendJsonAnswer(array(
                "result" => $result,
                "message" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
                                    <div class=\"adm-info-message\">
                                        <div class=\"adm-info-message-title\">Ошибка добавления ссылки Код ошибки: 0</div>
                                        <div class=\"adm-info-message-icon\"></div>
                                    </div>
                                </div>"
            ));
        }
    } elseif ($action == 'addManyLink') {
        if (count($request['linksdata']) > 1) {
            global $USER;
            $date = new DateTime('+2 days');
            $linksforrezmessage = '';
            foreach ($request['linksdata'] as $key => $linkData) {
                $link = randString(7);
                $data = array(
                    "UF_LINK_NAME" => $link,
                    "UF_USE_LINK" => false,
                    "UF_CR_NAME" => $USER->GetFullName(),
                    "UF_USE_NAME" => '',
                    "UF_EMAIL_PART" => $request['linksdata'][$key]['EX_EMAIL_PART'],
                    "UF_NAME_PART" => $request['linksdata'][$key]['EX_NAME_PART'],
                    "UF_24_HOUR" => $request['linksdata'][$key]['EX_ID_USE_48_HOUR'],
                    "UF_DATE_TIME" => date("d.m.Y H:i"),
                    "UF_DATE_ACTIVE" => $request['linksdata'][$key]['EX_DATE_ACTIVE_LINK'] ? $request['linksdata'][$key]['EX_DATE_ACTIVE_LINK'] : $date->format('d.m.Y H:i'),
                    "UF_GROUP_PART" => $request['linksdata'][$key]['EX_GROUP_PARTNER']
                );
                if ($result = $entity_data_class::add($data)) {
                    if ($request['linksdata'][$key]['EX_ID_USE_48_HOUR']) { //Если выбрана ссылка на 48 часов создаём агента на удаление ссылки спустя двое суток

                        $idAgent = CAgent::AddAgent(
                            "delLinkAgentPart(" . $result->getId() . ");",  // имя функции удаления ссылки в инит
                            "",
                            "N",
                            0,
                            "",
                            "Y",
                            $request['linksdata'][$key]['EX_DATE_ACTIVE_LINK'] ? $request['linksdata'][$key]['EX_DATE_ACTIVE_LINK'] : $date->format('d.m.Y H:i') //дата запуска - спустя двое суток или выбранная пользователем дата
                        );
                        $updData = array(
                            "UF_CRON_ID" => $idAgent,
                        );
                        $resultUpd = $entity_data_class::update($result->getId(), $updData);
                    }
                    if ($linksforrezmessage == '')
                        $linksforrezmessage = $link;
                    else
                        $linksforrezmessage .= ', ' . $link;
                }
            }
            Base::sendJsonAnswer(array(
                "result" => 'error',
                "message" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
                                <div class=\"adm-info-message\">
                                    <div class=\"adm-info-message-title\">Ссылки: " . $linksforrezmessage . " успешно созданы</div>
                                    <div class=\"adm-info-message-icon\"></div>
                                </div>
                            </div>"
            ));
        } else {
            Base::sendJsonAnswer(array(
                "result" => 'error',
                "message" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
                                <div class=\"adm-info-message\">
                                    <div class=\"adm-info-message-title\">Чтото пошло не так, обратитесь в разработчику Код ошибки: 1</div>
                                    <div class=\"adm-info-message-icon\"></div>
                                </div>
                            </div>"
            ));
        }
    } elseif($action == 'delLink'){
        if (isset($request['idLink']) && isset($request['cronID'])) {
            if ($request['cronID'])
                CAgent::Delete($request['cronID']);
            $result = $entity_data_class::delete($request['idLink']);
            Base::sendJsonAnswer(array(
                "result" => $result,
                "message" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
                                <div class=\"adm-info-message\">
                                    <div class=\"adm-info-message-title\">Cсылка с идентификатором " . $request['idLink'] . " успешно удалена</div>
                                    <div class=\"adm-info-message-icon\"></div>
                                </div>
                            </div>"
            ));
        } else
            Base::sendJsonAnswer(array(
                "result" => 'error',
                "message" => 'error POST id link or ID cron'
            ));
    } elseif($action == 'sendMail') {
        if ((isset($request['mail']) && !empty($request['mail'])) && (isset($request['linkID']) && !empty($request['linkID']))) {

            $resultEvent = Event::send(array(
                "EVENT_NAME" => "NEW_PARTNER",
                "LID" => "s1",
                'MESSAGE_ID' => 175,
                "C_FIELDS" => array(
                    "MESSAGE_SUBJECT" => 'Ссылка для активации партнёрского аккаунта на сайте Extreme Look',
                    "EMAIL_TO" => $request['mail'],
                    "NAME_PART" => isset($request['name']) && !empty($request['name']) ? $request['name'] . ',' : 'Уважаемый партнёр!',
                    "LINK" => $request['linkID']
                ),
            ));

            if ($resultEvent->getId()) {
                Base::sendJsonAnswer(array(
                    "result" => 'success',
                    "message" => "<div class=\"adm-info-message-wrap adm-info-message-green\">
                                <div class=\"adm-info-message\">
                                    <div class=\"adm-info-message-title\">Ссылка - " . $request['linkID'] . " успешна отправлена на почту партнёра - " . $request['mail'] . "</div>
                                    <div class=\"adm-info-message-icon\"></div>
                                </div>
                            </div>"
                ));
            } else {
                Base::sendJsonAnswer(array(
                    "result" => 'error',
                    "message" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
                                    <div class=\"adm-info-message\">
                                        <div class=\"adm-info-message-title\">Ошибка отправки. Ошибка функции Event::send</div>
                                        <div class=\"adm-info-message-icon\"></div>
                                    </div>
                                </div>"
                ));
            }
        } else {
            Base::sendJsonAnswer(array(
                "result" => 'error',
                "message" => "<div class=\"adm-info-message-wrap adm-info-message-red\">
                                <div class=\"adm-info-message\">
                                    <div class=\"adm-info-message-title\">Ошибка отправки. Нехватает данных для AJAX'а</div>
                                    <div class=\"adm-info-message-icon\"></div>
                                </div>
                            </div>"
            ));
        }
    }
}
else{
    Base::sendJsonAnswer(array(
        "result" => 'error',
        "message" => "Ошибка: отсутствует массив POST"
    ));
}
?>