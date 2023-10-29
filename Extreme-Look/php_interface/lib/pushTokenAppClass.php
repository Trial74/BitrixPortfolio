<?  //**  Класс по работе с приложением и пользователем, определение версии приложения типа устройства обновление токена  **//
    //**  для push уведомлений. Автоматическая авторизация по токену устройства пользователя by Vlados                    **//
namespace VladClasses;
use CUser,
    CModule,
    CIBlockElement,
    COption,
    \Bitrix\Main\Loader;


class pushTokenAppClass
{

    private $PUSH_TOKEN = false;
    private $APP_VERSION = false;
    private $BITRIX_SM_GUEST_ID = false;
    private $thisUser = false;
    private $BITRIX_SM_LAST_VISIT = false;
    private $appIBlockElement = false;
    private $APP = '';
    private $DEVICE = '';
    private $APPDESKTOP = false;
    private $ONCE = false;

    private function __getPGU()
    {
        CModule::IncludeModule("iblock");
        $this->PUSH_TOKEN = isset($_COOKIE['PUSH_TOKEN']) && !empty($_COOKIE['PUSH_TOKEN']) ? $_COOKIE['PUSH_TOKEN'] : false;
        $this->APP_VERSION = isset($_COOKIE['APP_VERSION']) && !empty($_COOKIE['APP_VERSION']) ? $_COOKIE['APP_VERSION'] : 2.01; //С версии 2.20 начался учёт версий в профилях пользователей
        $this->BITRIX_SM_GUEST_ID = isset($_COOKIE['BITRIX_SM_GUEST_ID']) && !empty($_COOKIE['BITRIX_SM_GUEST_ID']) ? $_COOKIE['BITRIX_SM_GUEST_ID'] : false;
        $this->BITRIX_SM_LAST_VISIT = isset($_COOKIE['BITRIX_SM_LAST_VISIT']) && !empty($_COOKIE['BITRIX_SM_LAST_VISIT']) ? $_COOKIE['BITRIX_SM_LAST_VISIT'] : false;
        $this->thisUser = new CUser;
        $this->appIBlockElement = new CIBlockElement;

        if(isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) { //Оператор для приложения. Идентифицируем устройство и приложение у пользователя
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            if ($userAgent === 'extreme-look-app' || $userAgent === 'extreme-look-app-vlad') {
                switch ($userAgent) {
                    case 'extreme-look-app-vlad':
                        $this->APP = 'Новое';
                        $this->DEVICE = 'Android';
                        $this->APPDESKTOP = true;
                        break;
                    case 'extreme-look-app':
                        $this->APP = 'Старое';
                        $this->DEVICE = 'Android';
                        $this->APPDESKTOP = true;
                        break;
                }
            } elseif ($userAgent === 'extreme-look-apple' || $userAgent === 'extreme-look-apple-vlad') {
                switch ($userAgent) {
                    case 'extreme-look-apple':
                        $this->DEVICE = 'iPhone';
                        $this->APP = 'Старое';
                        $this->APPDESKTOP = true;
                        break;
                    case 'extreme-look-apple-vlad':
                        $this->DEVICE = 'iPhone';
                        $this->APP = 'Новое';
                        $this->APPDESKTOP = true;
                        break;
                }
            }
        }
    }

    private function GetListAPPS($e){ //Функция запрашивает элемент в списке возвращает результат или false
        $arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_COUNT_IN", "PROPERTY_US_GU", "PROPERTY_APP_TOKEN", "PROPERTY_DEVICE", "PROPERTY_APP_V", "PROPERTY_APP_VERSION");
        $arFilter = Array(
            "IBLOCK_ID"=>IntVal(112),
            "ACTIVE_DATE"=>"Y",
            "ACTIVE"=>"Y",
            array(
                "LOGIC" => "OR",
                array("PROPERTY_US_ID" => $this->thisUser->IsAuthorized() ? $this->thisUser->GetID() : 'none', "PROPERTY_DEVICE" => $this->DEVICE, "APP_V" => $this->APP), //Поиск по пользователю
                array("PROPERTY_APP_TOKEN" => $this->PUSH_TOKEN ? $this->PUSH_TOKEN : 'none' , "PROPERTY_DEVICE" => $this->DEVICE, "APP_V" => $this->APP), //Поиск по токену в записи
                array("PROPERTY_GU_ID" => $this->BITRIX_SM_GUEST_ID ? $this->BITRIX_SM_GUEST_ID : 'none', "PROPERTY_DEVICE" => $this->DEVICE, "APP_V" => $this->APP), //Поиск по гостю
            ),
        );
        $resultElem = CIBlockElement::GetList(Array(), $arFilter, false, array(), $arSelect);
        $resultElemFetch = $resultElem->fetch();
        if(isset($resultElemFetch) && !empty($resultElemFetch)){
            switch($e){
                case 1: //параметр для получения результирующего массива списка
                    return array(
                        "ID" => $resultElemFetch['ID'], //Идентификатор элемента
                        "COUNT" => $resultElemFetch['PROPERTY_COUNT_IN_VALUE'], //количество посещений
                        "PROPERTY_APP_TOKEN" => $resultElemFetch['PROPERTY_APP_TOKEN_VALUE'],
                        "PROPERTY_APP_VERSION" => $resultElemFetch['PROPERTY_APP_VERSION_VALUE']
                    );
                break;
                case 2: //Параметр для проверки есть ли результат выборки
                    return true;
                default:
                    return false;
                break;
            }
        }
        else return false;
    }

    private function getGuestByToken(){ //Функция проверяет есть ли пользователь с токеном с которым зашёл гость возвращает поля такого пользователя либо false
        $filter = Array
        (
            "UF_FIREBASE_TOKEN" => $this->PUSH_TOKEN
        );
        $arParameters = array(
            "ID",
            "UF_FIREBASE_TOREN",
            "UF_AUTH_APP"
        );
        $rsUsers = $this->thisUser->GetList(($by="id"), ($order="desc"), $filter, $arParameters); // выбираем пользователей
        $arResUs = $rsUsers->Fetch();
        if(isset($arResUs) && !empty($arResUs)) return $arResUs;
        else return false;
    }

    private function getUserByToken(){ //Функция проверяет есть ли токен у пользователя и возвращает поле с токеном если есть
        $actualUser = $this->thisUser->GetByID($this->thisUser->GetID())->Fetch();
        $userPush = isset($actualUser['UF_FIREBASE_TOKEN']) && !empty($actualUser['UF_FIREBASE_TOKEN']) ? true : false;
        if($userPush) return $actualUser['UF_FIREBASE_TOKEN'];
        else return false;
    }

    private function getUserVersionApp(){ //Функция возвращает версию приложения которую использует пользователь
        $actualUser = $this->thisUser->GetByID($this->thisUser->GetID())->Fetch();
        $userVersionApp = isset($actualUser['UF_APP_VERSION']) && !empty($actualUser['UF_APP_VERSION']) ? true : false;
        if($userVersionApp)
            if((float)$actualUser['UF_APP_VERSION'] == (float)$this->APP_VERSION) return true;
            else return false;
        else return false;
    }

    private function setPushFieldUser($TOKEN, $VERSION){ //Функция обновления токена и версии приложения у пользователя
        if($TOKEN)
            $fields = Array(
                "UF_FIREBASE_TOKEN"  => $TOKEN,
                "UF_APP_VERSION"     => $VERSION
            );
        else
            $fields = Array(
                "UF_APP_VERSION"     => $VERSION
            );
        $this->thisUser->Update($this->thisUser->GetID(), $fields);
    }

    private function updArray($e, $ID_USER = false){ //Функция формирования массива для обновления записи в списке
        $resultFetch = $this->GetListAPPS(1);
        switch($e){
            case 1:
                return array(
                    "ID" => $resultFetch['ID'],
                    "ARRAY" => array(
                        "APP_TOKEN" => (float)$resultFetch['PROPERTY_APP_TOKEN'] != (float)$this->PUSH_TOKEN ? $this->PUSH_TOKEN : $resultFetch['PROPERTY_APP_TOKEN'],
                        "COUNT_IN" => $resultFetch['COUNT'] + 1, //Увеличиваем посещение на единицу
                        "US_ID" => $this->thisUser->IsAuthorized() ? $this->thisUser->GetID() : '',
                        "GU_ID" => $this->thisUser->IsAuthorized() ? '' : $this->BITRIX_SM_GUEST_ID,
                        "LAST_SESS" => $this->BITRIX_SM_LAST_VISIT ? $this->BITRIX_SM_LAST_VISIT : 'none', //Обновляем время сессии
                        "APP_VERSION" => !empty($resultFetch['PROPERTY_APP_VERSION']) && ((float)$resultFetch['PROPERTY_APP_VERSION'] != (float)$this->APP_VERSION) ? $this->APP_VERSION : 2.01
                    )
                );
            break;
            case 2:
                return array(
                    "ID" => $resultFetch['ID'],
                    "ARRAY" => array(
                        "APP_TOKEN" => (float)$resultFetch['PROPERTY_APP_TOKEN'] != (float)$this->PUSH_TOKEN ? $this->PUSH_TOKEN : $resultFetch['PROPERTY_APP_TOKEN'],
                        "COUNT_IN" => $resultFetch['COUNT'] + 1, //Увеличиваем посещение на единицу
                        "US_ID" => $ID_USER ? $ID_USER : '',
                        "GU_ID" => $ID_USER ? '' : $this->BITRIX_SM_GUEST_ID,
                        "LAST_SESS" => $this->BITRIX_SM_LAST_VISIT ? $this->BITRIX_SM_LAST_VISIT : 'none', //Обновляем время сессии
                        "APP_VERSION" => !empty($resultFetch['PROPERTY_APP_VERSION']) && ((float)$resultFetch['PROPERTY_APP_VERSION'] != (float)$this->APP_VERSION) ? $this->APP_VERSION : 2.01
                    )
                );
            break;
            default:
                return false;
            break;
        }
    }

    private function setArray($e, $ID_USER = false){ //Функция формирования массива для добавления записи в список
        switch($e){
            case 1:
                return array(
                    893 => $this->PUSH_TOKEN ? $this->PUSH_TOKEN : 'Нет токена',  //Токен
                    900 => $this->thisUser->IsAuthorized() ? $this->thisUser->GetID() : '',
                    894 => $this->thisUser->IsAuthorized() ? '' : $this->BITRIX_SM_GUEST_ID,  //Пользователь/гость
                    895 => 1,  //Количество заходов
                    896 => $this->DEVICE,  //Устройство
                    897 => $this->APP,  //Приложение
                    1218 => $this->APP_VERSION, //Версия приложения
                    899 => $this->BITRIX_SM_LAST_VISIT ? $this->BITRIX_SM_LAST_VISIT : 'none',  //Последняя сессия
                );
            break;
            case 2:
                return array(
                    893 => $this->PUSH_TOKEN ? $this->PUSH_TOKEN : 'Нет токена',  //Токен
                    900 => $this->thisUser->IsAuthorized() ? $this->thisUser->GetID() : '',
                    894 => $this->thisUser->IsAuthorized() ? '' : $this->BITRIX_SM_GUEST_ID,  //Пользователь/гость
                    895 => 1,  //Количество заходов
                    896 => $this->DEVICE,  //Устройство
                    897 => $this->APP,  //Приложение
                    1218 => $this->APP_VERSION, //Версия приложения
                    899 => $this->BITRIX_SM_LAST_VISIT ? $this->BITRIX_SM_LAST_VISIT : 'none',  //Последняя сессия
                );
            break;
            default:
            return false;
            break;
        }
    }

    private function updateANDaddElemList($e, $arr){ //функция обновления или записи элемента в список 1-обновление 2-запись
        switch ($e){
            case 1:
                $this->appIBlockElement->SetPropertyValuesEx($arr['ID'], false, $arr['ARRAY']);
            break;
            case 2:
                $arLoadProductArray = Array(
                    "MODIFIED_BY" => $this->thisUser->GetID(),
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => 112,
                    "PROPERTY_VALUES" => $arr,
                    "NAME" => "Вход с приложения",
                    "ACTIVE" => "Y"
                );
                $this->appIBlockElement->Add($arLoadProductArray);
            break;
        }
    }

    private function updateListsandUser(){ //Операторы для обновления иди добавления записей в базу возвращают номера событий для дампа
        if($this->PUSH_TOKEN){ //Есть пуш токен?
            if($this->thisUser->IsAuthorized()){ //Пользователь авторизован?
                if($pushUser = $this->getUserByToken()){ //У пользователя есть пуш токен
                    if(strcmp($pushUser, $_COOKIE['PUSH_TOKEN']) == 0){ //Токен пользователя совпадает с актуальным в куках?
                        if($this->GetListAPPS(2)){ //Есть в списке? просто обновляем актуальное время сессии
                            $arPROPERTY = $this->updArray(1); //Формируем массив для обновления
                            $this->updateANDaddElemList(1, $arPROPERTY); //Обновляем запись в базе
                            if(!$this->getUserVersionApp()) //Проверяем совпадает ли версия приложения с указанной в профиле
                                $this->setPushFieldUser(false, $this->APP_VERSION); //Обновляем версию приложения в профиле пользователя
                            return 1;
                        }
                        else{ //Нет в списке
                            $arPROPERTY = $this->setArray(1); //Формируем массив для добавления в базу
                            $this->updateANDaddElemList(2, $arPROPERTY); //Добавляем запись в базу
                            if(!$this->getUserVersionApp()) //Проверяем совпадает ли версия приложения с указанной в профиле
                                $this->setPushFieldUser(false, $this->APP_VERSION); //Обновляем версию приложения в профиле пользователя
                            return 2;
                        }
                    }else{ //Не совпадает проверяем есть ли в списке и обновляем
                        if($this->GetListAPPS(2)){ //Есть в списке?
                            $this->setPushFieldUser($this->PUSH_TOKEN, $this->APP_VERSION); //Обновляем у пользователя токен и версию приложения
                            $arPROPERTY = $this->updArray(1); //Формируем массив для обновления
                            $this->updateANDaddElemList(1, $arPROPERTY); //Обновляем запись в базе
                            return 3;
                        }
                        else{ //Нет в списке
                            $this->setPushFieldUser($this->PUSH_TOKEN, $this->APP_VERSION); //Обновляем у пользователя токен и версию приложения
                            $arPROPERTY = $this->setArray(1); //Формируем массив для добавления в базу
                            $this->updateANDaddElemList(2, $arPROPERTY); //Добавляем запись в базу
                            return 4;
                        }
                    }
                }
                else{ //У пользователя нет пуш токена
                    if($this->GetListAPPS(2)){ //Есть в списке? Добавляем пользователю и обновляем в списке
                        $this->setPushFieldUser($this->PUSH_TOKEN, $this->APP_VERSION); //Добавляем пользователю токен и версию приложения
                        $arPROPERTY = $this->updArray(1); //Формируем массив для обновления
                        $this->updateANDaddElemList(1, $arPROPERTY); //Обновляем запись в базе
                        return 5;
                    }
                    else{ //Нет в списке - Добавляем пользователю и добавляем в список
                        $this->setPushFieldUser($this->PUSH_TOKEN, $this->APP_VERSION); //Добавляем пользователю токен и версию приложения
                        $arPROPERTY = $this->setArray(1); //Формируем массив для обновления
                        $this->updateANDaddElemList(2, $arPROPERTY); //Добавляем запись в базу
                        return 6;
                    }
                }
            }
            else{ //Гость
                if($this->GetListAPPS(2)){ //Есть в списке?
                    if($arUser = $this->getGuestByToken()){ //Есть ли пользователь с таким токеном? Чтобы привязать запись к пользователю или пометить как гость
                        $arPROPERTY = $this->updArray(2, $arUser['ID']); //Формируем массив с ID пользователя
                        $this->updateANDaddElemList(1, $arPROPERTY); //Обновляем список добавляя ID пользователя
                        return 7;
                    }
                    else{
                        $arPROPERTY = $this->updArray(1); //Формируем массив для обновления без ID пользователя
                        $this->updateANDaddElemList(1, $arPROPERTY); //Обновляем запись без ID пользователя
                        return 8;
                    }
                }
                else{ //Нет в списке
                    if($arUser = $this->getGuestByToken()){ //Есть ли пользователь с таким токеном? Чтобы привязать запись к пользователю или пометить как гость
                        $arPROPERTY = $this->setArray(2, $arUser['ID']); //Формируем массив с ID пользователя
                        $this->updateANDaddElemList(2, $arPROPERTY); //Добавляем запись в базу с ID пользователя
                        return 9;
                    }
                    else{
                        $arPROPERTY = $this->setArray(1); //Формируем массив без ID пользователя
                        $this->updateANDaddElemList(2, $arPROPERTY); //Добавляем запись в базу без ID пользователя
                        return 10;
                    }
                }
            }
        }
        else{ return 0; //Нет токена (!!-- Нет токена значит старое приложение, заблокировал сбор статистики по старому приложению чтобы не забивать базу если надо сделать подсчёт расскоментируй и покури несколько дней в админке в колонке "Приложение" помечается текстом "Старое". --!!)
            /*if($this->thisUser->IsAuthorized()) { //Пользователь авторизован
                if($this->GetListAPPS(2)){ //Есть в списке?
                    $arPROPERTY = $this->updArray(1, $this->thisUser->GetId()); //Формируем массив для обновления
                    $this->updateANDaddElemList(1, $arPROPERTY); //Обновляем запись в базе
                    return 11;
                }
                else{ //Нет в списке
                    $arPROPERTY = $this->setArray(1); //Формируем массив для добавления
                    $this->updateANDaddElemList(2, $arPROPERTY); //Добавляем запись в базу
                    return 12;
                }
            }
            else{ //Гость
                if($this->GetListAPPS(2)){ //Есть в списке?
                    $arPROPERTY = $this->updArray(1); //Формируем массив для обновления
                    $this->updateANDaddElemList(1, $arPROPERTY); //Обновляем запись в базе
                    return 13;
                }
                else{ //Нет в списке
                    $arPROPERTY = $this->setArray(1); //Формируем массив для добавления
                    $this->updateANDaddElemList(2, $arPROPERTY); //Добавляем запись в базу
                    return 14;
                }
            }*/
        }
    }

    public function update(){
        $this->__getPGU();
        if(!$this->thisUser->IsAuthorized() && $this->PUSH_TOKEN && $this->APPDESKTOP) {
            $TOKEN = $this->getGuestByToken();
            $actUser = $this->thisUser->GetByID($TOKEN['ID'])->fetch();
            if ($TOKEN && $actUser['UF_AUTH_APP'] && $actUser['UF_FIREBASE_TOKEN'] == $this->PUSH_TOKEN) {
                $this->thisUser->Authorize($TOKEN['ID']);
            }
        }
        if(!$this->ONCE) {
            if ($this->APPDESKTOP)
                $this->updateListsandUser();
            $this->ONCE = true;
        }
    }
}