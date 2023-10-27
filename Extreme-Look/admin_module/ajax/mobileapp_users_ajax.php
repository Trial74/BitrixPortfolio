<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>
<?use Bitrix\Main\Loader,
      Bitrix\Iblock\Component\Base;

header('Content-Type: application/json');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if($request->isAjaxRequest()) {
    $action = $request['action'];
    $filter = array();
    $trueResult = 0;
    $falseResult = 0;
    $allResult = 0;
    $omitted = 0;
    $resultMessage = array();
    if($action == 'actualTokenByProfile'){
        /**Первый этап поиск в списке без токенов и актуализация из профиля пользователя начало**/
        $filter['IBLOCK_ID'] =  IntVal(112);
        $filter['ACTIVE_DATE'] = "Y";
        $filter['ACTIVE'] = "Y";
        $filter['PROPERTY_US_ID'] = '%';
        $filter['>=PROPERTY_APP_VERSION'] = '2.01';
        $filter['PROPERTY_APP_TOKEN'] = 'Нет токена';

        $arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_APP_TOKEN", "PROPERTY_US_ID", "PROPERTY_GU_ID", "PROPERTY_COUNT_IN", "PROPERTY_DEVICE", "PROPERTY_APP_V", "PROPERTY_LAST_SESS", "PROPERTY_APP_VERSION");
        $res = CIBlockElement::GetList(array(), $filter, false, array(), $arSelect);
        $allResult = $res->SelectedRowsCount();
        while($ob = $res->GetNextElement())
        {
            $fields = $ob->GetFields();
            if(!empty($fields['PROPERTY_US_ID_VALUE'])){
                $token = CUser::GetByID($fields['PROPERTY_US_ID_VALUE'])->fetch()['UF_FIREBASE_TOKEN'];
                if(!empty($token)) {
                    CIBlockElement::SetPropertyValuesEx($fields['ID'], false, array('APP_TOKEN' => $token));
                    $trueResult++;
                }else $falseResult++;
            }
            else $falseResult++;
        }
        $resultMessage['FIRST'] = array(
            "TRUE"  => 'Удачно актуализированных записей: ' . $trueResult,
            "FALSE" => 'Неудачно актуализированных записей: ' . $falseResult,
            "ALL"   => 'Всего записей в обработке: ' . $allResult
        );
        unset($filter, $arSelect, $res, $ob, $fields, $token);
        $trueResult = 0;
        $falseResult = 0;
        $allResult = 0;
        /**Первый этап поиск в списке без токенов и актуализация из профиля пользователя конец**/
        /**Второй этап поиск в списке старых приложений без пушей начало**/
        $filter['IBLOCK_ID'] =  IntVal(112);
        $filter['ACTIVE_DATE'] = "Y";
        $filter['ACTIVE'] = "Y";
        $filter[] = array(
            "LOGIC" => "OR",
            array("<PROPERTY_APP_VERSION" => '2.20'),
            array("=PROPERTY_APP_VERSION" => false),
        );

        $arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_US_ID", "PROPERTY_APP_VERSION");
        $res = CIBlockElement::GetList(array(), $filter, false, array(), $arSelect);
        $allResult = $res->SelectedRowsCount();
        while($ob = $res->GetNextElement())
        {
            $fields = $ob->GetFields();
        }
        $resultMessage['SECOND'] = array(
            "TRUE"  => 'Удалено неактуальных: ' . $trueResult,
            "FALSE" => 'Неудачно удалённых неактуальных: ' . $falseResult,
            "ALL"   => 'Всего неактуальных: ' . $allResult
        );
        /**Второй этап поиск в списке старых приложений без пушей конец**/

        Base::sendJsonAnswer(array(
            "result" => true,
            "message" => $resultMessage
        ));
    }else if($action == 'actualVersionByProfile'){
        $filter['IBLOCK_ID'] =  IntVal(112);
        $filter['ACTIVE_DATE'] = "Y";
        $filter['ACTIVE'] = "Y";
        $filter['PROPERTY_US_ID'] = '%';

        $arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_US_ID", "PROPERTY_APP_VERSION");
        $res = CIBlockElement::GetList(array(), $filter, false, array(), $arSelect);
        $allResult = $res->SelectedRowsCount();
        while($ob = $res->GetNextElement())
        {
            $fields = $ob->GetFields();
            if(!empty($fields['PROPERTY_US_ID_VALUE'])) {
                $version = CUser::GetByID($fields['PROPERTY_US_ID_VALUE'])->fetch()['UF_APP_VERSION'];
                if($version) {
                    if((int)$version === (int)$fields['PROPERTY_APP_VERSION_VALUE'])
                        $omitted++;
                    else {
                        CIBlockElement::SetPropertyValuesEx($fields['ID'], false, array('APP_VERSION' => $version));
                        $trueResult++;
                    }
                }else $falseResult++;
            }
        }
        $resultMessage = array(
            "TRUE"  => 'Актуализированно: ' . $trueResult,
            "FALSE" => 'Неудачно актуализированно: ' . $falseResult,
            "OMITT" => 'Пропущено потому что актуальны: ' . $omitted,
            "ALL"   => 'Всего пользователей: ' . $allResult
        );
        Base::sendJsonAnswer(array(
            "result" => true,
            "message" => $resultMessage
        ));
    }
}else{
    Base::sendJsonAnswer(array(
        "result" => false,
        "message" => "Ошибка: отсутствует массив POST"
    ));
}