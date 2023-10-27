<?
require $_SERVER['DOCUMENT_ROOT'] . '/api/telegram/vendor/autoload.php';

/**
Состояния пользователя:
 * 10 - Главное меню
 * 1 - Отправить фото для конкурса
 * 2 - Представиться
 * 3 - Выбрать площадку
 * 4 - выбрал wildberries
 * 5 - выбрал ozon
 **/

use GuzzleHttp\Client,
    Bitrix\Main\Loader;

    Loader::includeModule("highloadblock");

    use Bitrix\Highloadblock as HL;

class Api extends Client
{
    protected $conftoken;
    protected $MESSBOT;
    protected $HL;
    protected $sessUS;
    protected $DIR_COMMENTS;
    protected $DIR_MEDIA;
    public $basicChatData;

    public function __construct()
    {
        parent::__construct();
        $this->conftoken = '6053476998:AAGaN9LU8AYP63SHMVGRCqK5hUZveC5_Jl8';
        $this->MESSBOT = require 'Mess.php';

        $entity = HL\HighloadBlockTable::compileEntity(HL\HighloadBlockTable::getById(15)->fetch());
        $this->HL = $entity->getDataClass();
        $this->DIR_COMMENTS = $_SERVER['DOCUMENT_ROOT'] . '/upload/telegram_bot/comments';
        $this->DIR_MEDIA = 'https://extreme-look.ru/upload/telegram_bot/messages/';
    }

    public function init() {
        $this->basicChatData = $this->getDataChat();
        if(!empty($user = $this->getUserByIdTelegram($this->getIdUserByTelegram()))){
            $this->sessUS = $user;
            $this->setTimeUser();
            $this->checkIdChat();
        }else{
            $this->setUserByHL();
        }
    }

    private function checkIdChat(): bool {
        if(!$this->getIdChat()){
            $this->sessUS['UF_CHAT_ID'] = $this->getIdChatByTelegram();
            $this->HL::update($this->sessUS['ID'], $this->sessUS);
            return true;
        } else return false;
    }

    private function getIdUserByTelegram(): int {
        if(array_key_exists('message', $this->basicChatData)) return (int)$this->basicChatData['message']['from']['id'];
        elseif (array_key_exists('callback_query', $this->basicChatData)) return (int)$this->basicChatData['callback_query']['from']['id'];
    }

    private function getIdChatByTelegram(): int {
        if(array_key_exists('message', $this->basicChatData)) return (int)$this->basicChatData['message']['chat']['id'];
        elseif (array_key_exists('callback_query', $this->basicChatData)) return (int)$this->basicChatData['callback_query']['chat']['id'];
    }

    private function getNameUserByTelegram(): string {
        if(array_key_exists('message', $this->basicChatData)) return (string)$this->basicChatData['message']['from']['first_name'];
        elseif (array_key_exists('callback_query', $this->basicChatData)) return (string)$this->basicChatData['callback_query']['from']['first_name'];
    }

    private function getLoginUserByTelegram(): string {
        if(array_key_exists('message', $this->basicChatData)) return (string)$this->basicChatData['message']['from']['username'];
        elseif (array_key_exists('callback_query', $this->basicChatData)) return (string)$this->basicChatData['callback_query']['from']['username'];
    }

    public function getMessage(): mixed { return array_key_exists('message', $this->basicChatData) ? $this->basicChatData['message']['text'] : false; }

    public function getCallBackQuery(): mixed { return array_key_exists('callback_query', $this->basicChatData) ? $this->basicChatData['callback_query']['data'] : false; }

    public function getPhotoMess(): mixed { return array_key_exists('photo', $this->basicChatData['message']) ? $this->basicChatData['message']['photo'] : false; }

    public function getChatData(): array {
        return array_key_exists('message', $this->basicChatData) ? $this->basicChatData['message'] : $this->basicChatData['callback_query'];
    }

    public function getMess(string $mess): mixed { return $this->MESSBOT[$mess]; }

    public function getKeyboard(string $keyboard): mixed { return $this->MESSBOT[$keyboard]; }

    public function getPhoto(): mixed { return $this->basicChatData['message']['photo']; }

    public function getDataChat(): array { return json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR); }

    private function getUserByIdTelegram(int $id_t): mixed {

        $rsData = $this->HL::getList(array(
            "select" => array("*"),
            "filter" => array('UF_ID' => $id_t)
        ))->fetch();

        if(!empty($rsData)){
            return $rsData;
        }else{
            return false;
        }

    }

    private function getUserByHighload($id){

        $rsData = $this->HL::getList(array(
            "select" => array("*"),
            "filter" => array('ID' => $id),
        ))->fetch();

        if(!empty($rsData)){
            return $rsData;
        }else{
            return false;
        }
    }

    public function getStateUser(): mixed {

        $rsData = $this->HL::getList(array(
            "select" => array("UF_STATE"),
            "filter" => array('ID' => $this->sessUS['ID']),
        ))->fetch();

        return !empty($rsData) ? $rsData['UF_STATE'] : false;
    }

    public function getLikeName(): mixed { return !empty($this->sessUS['UF_LIKE_NAME']) ? $this->sessUS['UF_LIKE_NAME'] : false; }

    public function getIdChat(): mixed { return !empty($this->sessUS['UF_CHAT_ID']) ? $this->sessUS['UF_CHAT_ID'] : false; }

    public function getPathImgByUser(): mixed { return !empty($this->sessUS['UF_FILES']) ? $this->sessUS['UF_FILES'] : false; }

    private function setUserByHL(): bool {
        $data = array(
            "UF_ID" => $this->getIdUserByTelegram(),
            "UF_CHAT_ID" => $this->getIdChatByTelegram(),
            "UF_NAME" => $this->getNameUserByTelegram(),
            "UF_LOGIN" => $this->getLoginUserByTelegram(),
            "UF_LIKE_NAME" => '',
            "UF_STATE" => 10,
            "UF_TIME"=> date("d.m.Y H:i:s"),
            "UF_FIRST_TIME" => date("d.m.Y H:i:s")
        );

        if($idUser = $this->HL::add($data)){
            $this->sessUS = $this->getUserByHighload($idUser);
            return true;
        }else{
            return false;
        }
    }

    protected function setTimeUser(): bool {

        $this->sessUS['UF_TIME'] = date("d.m.Y H:i:s");

        if($this->HL::update($this->sessUS['ID'], $this->sessUS)) return true;
        else return false;

    }

    public function setStateUser($state): bool {

        if(!empty($this->sessUS)){

            $this->sessUS['UF_STATE'] = $state;

            if($this->HL::update($this->sessUS['ID'], $this->sessUS)) return true;
            else return false;
        }else{
            vlog('Ошибка. Нет пользователя.');
            return false;
        }

    }

    public function setLikeNameUser(): array {
        if(!empty($this->sessUS)){

            $str = $this->getMessage();
            if(strlen($str) > 15){
                return array('RESULT' => false, 'MESS' => 'Ваше сообщение слишком длинное, не похоже на имя 🤔 Введите пожалуйста ещё раз 🤗');
            }else{

                $fc = mb_strtoupper(mb_substr($str, 0, 1));
                $this->sessUS['UF_LIKE_NAME'] = $fc.mb_substr($str, 1);
                $this->HL::update($this->sessUS['ID'], $this->sessUS);
                return array('RESULT' => true);
            }
        }else{
            return array('RESULT' => false, 'MESS' => 'Произошла ошибка, попробуйте ещё раз пожалуйста');
        }
    }

    private function setPathImgByUser(): bool {
        $this->sessUS['UF_FILES'] = "/" . $this->getIdUserByTelegram() . "/";
        $this->HL::update($this->sessUS['ID'], $this->sessUS);
        return true;
    }

    public function sendPhoto($url, $message): array {
        $params = array('chat_id' => $this->basicChatData['message']['chat']['id'], 'photo' => $url, 'caption' => $message, 'parse_mode' => "html");
        $response = $this->requestToTelegramPhoto($params);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendPhotoCallBackData($url, $message): array {
        $params = array('chat_id' => $this->basicChatData['callback_query']['message']['chat']['id'], 'photo' => $url, 'caption' => $message, 'parse_mode' => "html");
        $response = $this->requestToTelegramPhoto($params);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendMessage(string $message): mixed {
        $params = array('chat_id' => $this->basicChatData['message']['chat']['id'], 'text' => $message);
        $response = $this->requestToTelegram($params);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendMessageCallBackData(string $message, int $state = 10): mixed {
        $params = array('chat_id' => $this->basicChatData['callback_query']['message']['chat']['id'], 'text' => $message, 'parse_mode' => 'HTML', 'disable_web_page_preview' => true);

        if($state != false) $this->setStateUser($state);

        $response = $this->requestToTelegram($params);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendMessageCallBackWithInlineKeyboard(string $message, array $inlineKeyboard): mixed {
        $params = array('chat_id' => $this->basicChatData['callback_query']['message']['chat']['id'], 'text' => $message, 'parse_mode' => 'HTML', 'disable_web_page_preview' => true, 'reply_markup' => array('inline_keyboard' => $inlineKeyboard));
        $response = $this->requestToTelegram($params);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendMessageWithInlineKeyboard(string $message, array $inlineKeyboard): mixed {
        $params = array('chat_id' => $this->basicChatData['message']['chat']['id'], 'text' => $message, 'parse_mode' => 'HTML', 'disable_web_page_preview' => true, 'reply_markup' => array('inline_keyboard' => $inlineKeyboard));
        $response = $this->requestToTelegram($params);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendMessageWithBaseKeyboard(string $message, array $keyboard): mixed {
        $params = array('chat_id' => $this->basicChatData['message']['chat']['id'],'text' => $message, 'reply_markup' => array('keyboard' => $keyboard, 'resize_keyboard' => true));
        $response = $this->requestToTelegram($params);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    protected function requestToTelegram(array $params): mixed {
        return $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage", ['json' => $params], ['http_errors' => false]);
    }

    protected function requestToTelegramPhoto(array $params): mixed {
        return $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendPhoto", ['json' => $params], ['http_errors' => false]);
    }

    public function isPhoto(): bool { return array_key_exists('photo', $this->basicChatData['message']); }

    public function savePhoto(): array {
        $photo = $this->getPhoto();
        $file_id = $photo[count($photo) - 1]['file_id'];
        $response = $this->request('GET', "https://api.telegram.org/bot$this->conftoken/getFile?file_id=$file_id");

        if($response->getStatusCode() == '200'){
            $content = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            $file_path = $content['result']['file_path'];
            $file_from_tgrm = "https://api.telegram.org/file/bot$this->conftoken/$file_path";
            $ext =  end(explode(".", $file_path));
            $name_our_new_file = date("d-m-Y_H:i:s") . "." . $ext;

            if(!$this->getPathImgByUser()) {
                mkdir($this->DIR_COMMENTS ."/" . $this->getIdUserByTelegram(), 0755);
                $this->setPathImgByUser();
            }

            if(copy($file_from_tgrm, $this->DIR_COMMENTS ."/" . $this->getIdUserByTelegram() . "/" . $name_our_new_file)){
                return array('RESULT' => true, 'MESS' => 'Скриншот успешно сохранён, удачи в розыгрыше! 🤗');
            } else {
                return array('RESULT' => false, 'MESS' => 'Ой.. Чтото пошло не так, попробуйте ещё раз. 😅');
            }
        } else {
            vlog('Ошибка бота - ' . $response->getStatusCode());
            return array('RESULT' => false, 'MESS' => 'Ой.. Чтото пошло не так. Ошибка - '. $response->getStatusCode() . '. Поробуйте отправить изображение ещё раз или напишите в нашу тех поддержку it@extreme-look.ru 😅');
        }
    }

    public function sendMessByUserChat($chat, $message, $format): mixed {

        if(empty($chat) || empty($message) || empty($format)) return false;

        $params = array('chat_id' => $chat, 'text' => $message, 'parse_mode' => $format);
        $response = $this->requestToTelegram($params);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendPhotoByUserChat(int $chat, string $message, string $photo, string $format): mixed {

        if(empty($chat) || empty($format) || empty($photo)) return false;

        $params = array('chat_id' => $chat, 'photo' => ($this->DIR_MEDIA . $photo), 'caption' => $message, 'parse_mode' => $format);
        $response = $this->requestToTelegramPhoto($params);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}