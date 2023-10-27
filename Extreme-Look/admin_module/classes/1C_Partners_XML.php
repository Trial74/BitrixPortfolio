<?
class importPartXML{
    public function getCountPart($file = false){
        if($file)
            if($array_XML = $this->getJSON_XML($file))
                return array(
                    'count' => $this->countPart($array_XML),
                    'cash'  => $this->countCash($array_XML)
                );
        return false;
    }
    public function getCashTable($fileName = false){
        if($fileName) $fileName = DIR_1C_XML . $fileName;
        else return false;
        if($array_XML = $this->getJSON_XML($fileName)) return $this->cashTable($array_XML);
        else return false;
    }
    private function getJSON_XML($file){
        if(empty($file) || !file_exists($file)) return false;

        $users_XML = simplexml_load_file($file);
        $json_XML = json_encode($users_XML);
        $array_XML = json_decode($json_XML, true);
        return $array_XML;
    }
    private function countPart($array_XML){
        $count = 0;
        foreach ($array_XML['Контрагенты']['Контрагент'] as $key => $user) {
            if ($user['ЯвляетсяПартнером'] == 'true' && $user['ПометкаУдаления'] == 'false')
                $count++;
        }
        return $count;
    }
    private function countCash($array_XML){
        $count = 0;
        foreach ($array_XML['Контрагенты']['Контрагент'] as $key => $user) {
            if ($user['ОстаткиБонусов'] != '0' && $user['ПометкаУдаления'] == 'false')
                $count++;
        }
        return $count;
    }
    private function cashTable($array_XML){
        $arrTable = array();
        vlog(0);
        foreach ($array_XML['Контрагенты']['Контрагент'] as $key => $user) {
            vlog(1);
            $mail = $this->searchMail($user);
            $userSite = false;
            vlog(2);
            if ($mail !== ''){
                $filter = array("EMAIL" => $mail,);
                $rsUsers = CUser::GetList(($by = "id"), ($order = "asc"), $filter); //ищем пользователя на сайте
                if($arrUser = $rsUsers->fetch()) $userSite = count($arrUser) > 0 ? $arrUser['ID'] : false;
            }
            if($user['ЯвляетсяПартнером'] == 'true' && $user['ПометкаУдаления'] == 'false' && $user['ОстаткиБонусов'] != '0')
                $arrTable[] = array(
                    'name' => $user['Наименование'],
                    'email' => $mail == '' ? 'Почта не указана' : $mail,
                    'user' => $userSite,
                    'cash' => $user['ОстаткиБонусов']
                );
        }
        return $arrTable;
    }
    private function searchMail($arrXML = array()){ //Поиск почты партнёра
        if(!$this->countTouchContact($arrXML, 0)) //Если два контакта
            if(isset($arrXML['Контакты']['Контакт']))
                foreach ($arrXML['Контакты']['Контакт'] as $key_contacts => $contacts){
                    if (preg_match("/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}+/i", trim($contacts['Значение']))) {
                        $mail = trim($arrXML['Контакты']['Контакт'][$key_contacts]['Значение']);
                        break;
                    }
                    else $mail = ''; //Если два контакта и нет почты
                }
            else $mail = ''; //Если нет контактов
        else //Если один контакт
            if(preg_match("/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}+/i", trim($arrXML['Контакты']['Контакт']['Значение'])))
                $mail = trim($arrXML['Контакты']['Контакт']['Значение']);
            else $mail = ''; //Если один контакт и он не почта
        return $mail;
    }
    private function countTouchContact($arrXML = array()){
        if(array_key_exists('Значение', $arrXML['Контакты']['Контакт']))
            return true; //Один контакт
        else
            return false; //Несколько контаков
    }
}