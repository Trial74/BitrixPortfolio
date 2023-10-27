<?
namespace VladClasses;
use CUser,
    \Bitrix\Main\Loader;

class ImportPartXML{

    private $DIR_LOG = '/var/www/u0907786/data/www/extreme-look.ru/bitrix/modules/extremelook/log/log_import_contr_cash.txt';

    public function startHandlingFiles(){
        if($arrFiles = glob(DIR_1C_XML . "contragents___*.xml"))
            array_multisort(array_map('filemtime', $arrFiles), SORT_DESC, $arrFiles);
        else return false;
        if($arrFiles && is_array($arrFiles)){
            if(count($arrFiles) === 1){
                $analysisFile = $this->getCountPart($arrFiles[0]);
                if($analysisFile['count'] > 0 && $analysisFile['cash'] > 0)
                    return $arrFiles;
                else{
                    rename(DIR_1C_XML . basename($arrFiles[0]), DIR_1C_XML_AR . basename($arrFiles[0]));
                    unset($arrFiles[0]);
                    return false;
                }
            }
            $dateFirstFile = date("d.m.y", filectime($arrFiles[0]));
            foreach($arrFiles as $key => $file){
                if(strtotime($dateFirstFile) === strtotime(date("d.m.y", filectime($file)))){
                    $analysisFile = $this->getCountPart($file);
                    if($analysisFile['count'] > 0 && $analysisFile['cash'] > 0)
                        continue;
                    else{
                        rename(DIR_1C_XML . basename($file), DIR_1C_XML_AR . basename($file));
                        unset($arrFiles[$key]);
                    }
                }else {
                    rename(DIR_1C_XML . basename($file), DIR_1C_XML_AR . basename($file));
                    unset($arrFiles[$key]);
                }
            }
            if(count($arrFiles) > 0) return $arrFiles;
        }
        return false;
    }
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
    public function formatFileSize($size){
        $a = array("B", "KB", "MB", "GB", "TB", "PB");
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size,2)." ".$a[$pos];
    }
    public function moveToArchive($file){
        rename(DIR_1C_XML . basename($file), DIR_1C_XML_AR . basename($file));
    }
    public function addLog($text = '...', $start = false, $end = false){
        if($start) {
            $firstLine = date('d-m-Y_H:i:s') . ' Старт выгрузки ------------------------------------------';
            file_put_contents($this->DIR_LOG, $firstLine . PHP_EOL, FILE_APPEND);
        }elseif($end){
            $endLine = date('d-m-Y_H:i:s') . ' Конец выгрузки --------------------------------------------';
            file_put_contents($this->DIR_LOG, $endLine . PHP_EOL, FILE_APPEND);
        }else{
            file_put_contents($this->DIR_LOG, $text . PHP_EOL, FILE_APPEND);
        }
    }
    public function setCashPart($idPart, $cash){
        if(empty($idPart) || empty($cash)) {
            if(empty($idPart))
                $this->addLog('В функцию обновления бонусов не передан параметр (ID партнёра)');
            if(empty($cash))
                $this->addLog('В функцию обновления бонусов не передан параметр (Бонусы)');
            return 400;
        }
        $OBJUser = new CUser;
        $partner = $OBJUser->GetByID($idPart)->fetch();

        if(!empty($partner)){
            if((int)$partner['UF_CASH_PART'] === (int)$cash){
                $this->addLog('Пользователь - ' . $idPart . ' | ' . $partner['EMAIL'] . ' Количество бонусов не изменилось');
                return 300;
            }elseif((int)$cash < 0){
                $this->addLog('Пользователь - ' . $idPart . ' | ' . $partner['EMAIL'] . ' Количество бонусов в выгрузке отрицательное. Бонусы не обновлены');
                return 301;
            }else{
                $fields = Array(
                    "UF_CASH_PART" => (string)$cash
                );
                if($OBJUser->Update($idPart, $fields)){
                    $this->addLog('Обновлено значение бонусов у партнёра - ' . $idPart . ' | ' . $partner['EMAIL'] . '. Прошлое значение: ' . $partner['UF_CASH_PART'] . ', новое значение: ' . $cash);
                }else{
                    $this->addLog('Ошибка обновление пользователя: ' . $idPart . ' | ' . $partner['EMAIL'] . ' Текст ошибки: ' . $OBJUser->LAST_ERROR);
                }
                return 200;
            }
        }else{
            $this->addLog('Пользователь - ' . $idPart . ' не найден');
            return 404;
        }
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
        foreach ($array_XML['Контрагенты']['Контрагент'] as $key => $user) {
            $mail = $this->searchMail($user);
            $userSite = false;
            if ($mail !== ''){
                $filter = array("EMAIL" => $mail,);
                $rsUsers = CUser::GetList(($by = "id"), ($order = "asc"), $filter);
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
    private function searchMail($arrXML = array()){
        if(!$this->countTouchContact($arrXML, 0))
            if(isset($arrXML['Контакты']['Контакт']))
                foreach ($arrXML['Контакты']['Контакт'] as $key_contacts => $contacts){
                    if (preg_match("/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}+/i", trim($contacts['Значение']))) {
                        $mail = trim($arrXML['Контакты']['Контакт'][$key_contacts]['Значение']);
                        break;
                    }
                    else $mail = '';
                }
            else $mail = '';
        else
            if(preg_match("/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}+/i", trim($arrXML['Контакты']['Контакт']['Значение'])))
                $mail = trim($arrXML['Контакты']['Контакт']['Значение']);
            else $mail = '';
        return $mail;
    }
    private function countTouchContact($arrXML = array()){
        if(array_key_exists('Значение', $arrXML['Контакты']['Контакт']))
            return true;
        else
            return false;
    }
}