<?
set_include_path(__DIR__);
try{
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

    require 'src/Api.php';
    $bot = new Api();

    $bot->init();

    $message = $bot->getMessage();
    $callBackQuery = $bot->getCallBackQuery();
    $photo = empty($callBackQuery) ? $bot->getPhotoMess() : false;
    $stateUser = $bot->getStateUser();
    $likeName = $bot->getLikeName();

    if(!empty($callBackQuery)){

        switch ($callBackQuery){
            case 'buy':

                $bot->sendMessageCallBackData($bot->getMess('BUY'), 10);

            break;
            case 'contest':

                $bot->sendMessageCallBackData($bot->getMess('CONTEST'), false);
                sleep(2);
                if(!empty($likeName))
                    $bot->sendMessageCallBackWithInlineKeyboard('Ваше имя ' . $likeName . ' всё верно?', $bot->getKeyboard('NAMEUSER'));
                else
                    $bot->sendMessageCallBackData($bot->getMess('WHATNAME'), 2);

            break;
            case 'contacts':

                $bot->sendMessageCallBackData($bot->getMess('CONTACTS'), 10);

            break;
            case 'wildberries_help': case 'ozon_help':

                if($callBackQuery == 'wildberries_help') $bot->setStateUser(4);
                elseif($callBackQuery == 'ozon_help') $bot->setStateUser(5);

                $bot->sendMessageCallBackWithInlineKeyboard($likeName . ', Вам подсказать, как сделать скриншот отзыва?', $bot->getKeyboard('HELP'));

            break;
            case 'helpno':

                $area = $stateUser == 4 ? 'Wildberries' : 'Ozon';
                $bot->setStateUser(1);
                $bot->sendMessageCallBackData('Ожидаю скриншот Вашего комментария на '. $area .' 😊', false);

                break;
            case 'helpyes':

                if($stateUser == 4){

                    $bot->sendPhotoCallBackData('AgACAgIAAxkBAAICmWUfsrik5e6-SfeG9G2O2C6W-GD_AAKe0DEbqrj5SCbiBmqWT0wNAQADAgADeAADMAQ', "1. Зайдите в купленные товары и кликните на продукт бренда Extreme-Look/Lashmaker/MIXON. Товар должен быть приобретён у продавца Extreme-Look");
                    sleep(2);
                    $bot->sendPhotoCallBackData('AgACAgIAAxkBAAICm2UfsxPFVF21TXz_w6YujBKoEFRcAAKn0DEbqrj5SOSJpA-kduMyAQADAgADeAADMAQ', "2. Нажмите на кнопку 'Отзывы'.\n3. Выберите опцию 'Написать отзыв'.");
                    sleep(2);
                    $bot->sendPhotoCallBackData('AgACAgIAAxkBAAICnWUfsyeGibz7b6_OpuBLl2RZQxz4AAKo0DEbqrj5SLSZDyG2uJYvAQADAgADeQADMAQ', "4. Добавьте фотографию (по желанию) и напишите отзыв.\n5. Подтвердите свой отзыв, нажав на кнопку 'Отправить отзыв'.");
                    sleep(1);
                    $bot->sendMessageCallBackData('Ожидаю скриншот Вашего комментария на Wildberries 😊', 4);

                }elseif($stateUser == 5){

                    $bot->sendPhotoCallBackData('AgACAgIAAxkBAAICVmUern8HoP9oQ5V-wr4ybxjTPODbAAIl0TEbqrjxSG7eK5sgBMfaAQADAgADeQADMAQ', "1. Зайдите в купленные товары и кликните на продукт бренда Extreme-Look/Lashmaker/MIXON. Товар должен быть приобретён у продавца Extreme-Look");
                    sleep(2);
                    $bot->sendPhotoCallBackData('AgACAgIAAxkBAAICdWUfnIPS6tkJJze9K5tOghJCK6omAAJR0DEbqrj5SD_2KPRF1PvRAQADAgADeAADMAQ', "2. Нажмите кнопку 'Написать'");
                    sleep(2);
                    $bot->sendPhotoCallBackData('AgACAgIAAxkBAAICd2UfnMUQ-bJAZC4hqUroHukHnm7iAAJW0DEbqrj5SPSfNnzzR546AQADAgADeQADMAQ', "4. Добавьте фото при желании и напишите отзыв\n5. Подтвердите нажатием на 'Отправить отзыв'");
                    sleep(1);
                    $bot->sendMessageCallBackData('Ожидаю скриншот Вашего комментария на Ozon 😊', 5);

                }

                break;
            case 'start':

                $bot->setStateUser(10);
                $bot->sendMessageCallBackWithInlineKeyboard($bot->getMess('MAIN'), $bot->getKeyboard('FIRSTKEYBOARD'));

            break;
            case 'rechoosearea': case 'nameyes':

                $bot->setStateUser(3);
                $bot->sendMessageCallBackWithInlineKeyboard($likeName . $bot->getMess('GETAREA'), $bot->getKeyboard('AREASKEYBOARD'));

            break;
            case 'namerename':

                $bot->sendMessageCallBackData('Как к Вам можно обращаться?', 2);

            break;
            case 'test':

                $bot->sendMessageCallBackData('Тест', 10);

            break;
            default:

                $bot->sendMessageCallBackData('Ой, произошла ошибка');

            break;
        }

    }elseif(!empty($message)){

        if($message == '/start' || $message == '/help') {

            $bot->setStateUser(10);
            $bot->sendMessageWithInlineKeyboard($bot->getMess('MAIN'), $bot->getKeyboard('FIRSTKEYBOARD'));

        }elseif($stateUser == 2){

            $resSetName = $bot->setLikeNameUser();

            if(!$resSetName['RESULT']){
                $bot->sendMessage($resSetName['MESS']);
            }else{
                $bot->setStateUser(3);
                $bot->sendMessageWithInlineKeyboard($bot->getLikeName() . $bot->getMess('GETAREA'), $bot->getKeyboard('AREASKEYBOARD'));
            }

        }elseif($stateUser == 3){

            $bot->sendMessageWithInlineKeyboard($bot->getMess('ERROR'), $bot->getKeyboard('MAINMENU'));

        } else {
            $bot->sendMessageWithInlineKeyboard($bot->getMess('ERROR'), $bot->getKeyboard('MAINMENU'));
        }

    }elseif(!empty($photo)){
        if($stateUser == 4 || $stateUser == 5 || $stateUser == 1){

            if($bot->isPhoto()){
                $result = $bot->savePhoto();
                if($result['RESULT']){
                    $bot->setStateUser(10);
                    $bot->sendMessageWithInlineKeyboard($result['MESS'], $bot->getKeyboard('AFTERUPIMAGE'));
                }else{
                    $bot->sendMessage($result['MESS']);
                }
                
            }else{
                $bot->sendMessageWithInlineKeyboard($bot->getMess('ERRLOADIMGUSER') . ($stateUser == 4 ? 'Wildberries' : 'Ozon'), $bot->getKeyboard('RECHOOSEAREA'));
            }

        }
    }else{

        $bot->sendMessageWithInlineKeyboard($bot->getMess('ERROR'), $bot->getKeyboard('MAINMENU'));

    }
}catch (Exception $e){
    vlog('Ошибка - '.$e->getMessage());
}
