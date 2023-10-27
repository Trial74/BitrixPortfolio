<?
class extreme_B24{

    public static function addContact($arrFields, $addDeal = false){
        if(!empty($arrFields) && static::checkFields($arrFields)){
            require_once (__DIR__.'/contact_hook/crest.php');
            $resultContactCRM = CRest::call(
                'crm.contact.add',
                [
                    "fields" =>
                        [
                            "NAME" => $arrFields['NAME'],
                            "EMAIL" => [["VALUE" => $arrFields['MAIL'], "VALUE_TYPE" => "HOME"]],
                            "PHONE" => [["VALUE" => $arrFields['PHONE'], "VALUE_TYPE" => "MOBILE"]],
                            "OPENED" => "Y",
                            "ASSIGNED_BY_ID" => $arrFields['ASSIGNED'],
                            "TYPE_ID" => 4,
                            "SOURCE_ID" => 9
                        ],
                    "params" => [
                        "REGISTER_SONET_EVENT" => "Y"
                    ]
                ]
            );
            /*if($resultContactCRM['result']) static::setLog([0 => 'Контакт - ' . $resultContactCRM['result'] . ' добавлен', 'path' => 'contact_hook']);
            else static::setLog([0 => 'Ошибка добавления контакта', 'path' => 'contact_hook', 'msg' => $resultContactCRM['error']]);*/
            if($addDeal && !empty($arrFields['THEME'])){
                $resultContactCRM['THEME'] = $arrFields['THEME'];
                $resultContactCRM['ASSIGNED'] = $arrFields['ASSIGNED'];
                static::addDeal($resultContactCRM);
            }
        }//else static::setLog([0 => 'Не заданы обязательные поля', 'path' => 'contact_hook']);
    }

    protected static function addDeal($contactCRM){
        if(!empty($contactCRM['result'])){
            require_once (__DIR__.'/deal_hook/crest.php');
            $resultDealCRM = CRestDeal::call(
                'crm.deal.add',
                [
                    "fields" =>
                        [
                            "TITLE" => $contactCRM['THEME'],
                            "TYPE_ID" => "GOODS",
                            "STAGE_ID" => "NEW",
                            "CONTACT_ID" => $contactCRM['result'],
                            "OPENED" => "Y",
                            "ASSIGNED_BY_ID" => $contactCRM['ASSIGNED']
                        ],
                    "params" => [ "REGISTER_SONET_EVENT" => "Y" ]
                ]
            );
            /*if($resultDealCRM['result']) static::setLog([1 => 'Сделка - ' . $resultDealCRM['result'] . ' добавлена', 'path' => 'deal_hook']);
            else static::setLog([1 => 'Ошибка добавления сделки', 'path' => 'deal_hook']);*/
        }
    }

    protected static function checkFields($fields){

        if(!empty($fields) && is_array($fields)){
            foreach ($fields as $key => $field){
                if($key === 'MAIL' && empty($field)) return false;
                elseif($key === 'PHONE' && empty($field)) return false;
                elseif($key === 'ASSIGNED' && empty($field)) return false;
            }
        }else return false;

        return true;
    }

    public static function setLog($arData)
    {
        $path = __DIR__ . '/log/' . $arData['path'] . '/';
        $path .= date("Y-m-d/H") . '/';

        if (!file_exists($path))
            @mkdir($path, 0775, true);

        $path .= time() . '_' . rand(1, 9999999) . 'log';
        file_put_contents($path . '.txt', var_export($arData, true));
    }
}
?>