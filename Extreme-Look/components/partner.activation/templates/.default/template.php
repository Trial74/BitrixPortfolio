<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?use Bitrix\Main\Loader;
Loader::includeModule("highloadblock"); //юзаем хайблоки
use Bitrix\Highloadblock as HL,
    Bitrix\Main\Entity;
$APPLICATION->SetAdditionalCss("/bitrix/templates/enext/fonts/graphillcg/stylesheet.css");
$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if(empty($request['group']) || empty($request['link'])){
    echo '<div class="text_error">Ошибка. Ссылка невалидна. Обратитесь к Вашему менеджеру по работе с партнёрами</div>'; //Если в ссылке отсутствует инвайт или группа партнёра то выводим ошибку
}
elseif(iconv_strlen($request['link']) <> 7 || array_search($request['group'], $arResult['GROUPS']) === false)
    echo '<div class="text_error">Ошибка. Ссылка невалидна. Обратитесь к Вашему менеджеру по работе с партнёрами</div>'; //если количество символов в инвайте не равно 7 выводим ошибку
else{
    $hlbl = 4; //В 4м блоке инфа о инвайтах
    $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "filter" => array('UF_LINK_NAME' => $request['link'], 'UF_USE_LINK' => '0')
    ));
    $arData = $rsData->Fetch();
    if(empty($arData) && isset($arData))
        echo '<div class="text_error">Ошибка. Ссылка невалидна. Обратитесь к Вашему менеджеру по работе с партнёрами</div>'; //Если инвайт не найден или использован - ошибка
    else{ //Проверка на инвайт пройдена запускаем скрипт
        $form = true; //Выводим форму
        if(!empty($_POST['EMAIL'])) { //Если в посте есть поле с почтой
            $data = CUser::GetList(($by = "ID"), ($order = "ASC"), //Выбираем пользователей
                array(
                    'EMAIL' => $_POST['EMAIL'], //Ищем по почте
                    'ACTIVE' => 'Y' //Активных
                )
            );
            $arUser = $data->Fetch(); //вытаскиваем с объекта
            if(!empty($arUser)) { //Если пользователь существует
                $newUser = false; //Вырубаем создание нового пользователя
                $form = false; //Вырубаем форму
                updateUserGroupsFrom_PartnerActivation($arUser['ID'], $request['group'], $arResult['GROUPS']); //Загоняем пользователя в группу партнёров, функция в инит
                $user = new CUser;
                $fields = Array(
                    "PHONE_NUMBER" => $_POST['KONTACT_TEL'],
                    "UF_MAP_ADDRESS" => $_POST['ADDRES_SALE'] . $_POST['TEL_BY_KLIENTS'],
                    "UF_MIN_SUMM" => 0
                 );
                $user->Update($arUser['ID'], $fields);
                $to = 'it@extreme-look.ru, sales@extreme-look.ru';
                $subject = 'Активация партнёра';
                $message = 'Активирован аккаунт пользователя на партнёрский<br />ID пользователя: ' . $arUser['ID'] . '<br />Логин пользователя: ' . $arUser['NAME'] . '<br />Данные из формы заполненные пользователем<br />ФИО: ' . $_POST['FIO'] . '<br />Название торговой точки: ' . $_POST['NAME_SALE'] . '<br />Адрес торговой точки:<br />Страна: ' . $_POST['ADDRES_SALE_COUNT'] . '<br />Населённый пункт: ' . $_POST['ADDRES_SALE_SITY'] . '<br />Улица, дом: ' . $_POST['ADDRES_SALE_STREET'] . '<br />Контактный телефон для клиентов: ' . $_POST['TEL_BY_KLIENTS'] . '<br />Адрес эл.почты: ' . $_POST['EMAIL'] . '<br />Контактный телефон: ' . $_POST['KONTACT_TEL'];
                $headers = 'From: no-reply@extreme-look.ru' . "\r\n";
                $headers .= 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                mail($to, $subject, $message, $headers);
            }
            else{ //Если пользователь не найден
                $login = explode('@', $_POST['EMAIL'])[0]; //отделяем название почты для логина
                $password = randString(8);
                $user = new CUser; //Создаём объект пользователя
                $arFields = array( //Массив данных для нового пользователя
                    "NAME" => $_POST['FIO'], //Поле имя
                    "LOGIN" => $login, //Логин пользователя
                    "EMAIL" => $_POST['EMAIL'], //Почта пользователя
                    "LID" => "ru",
                    "ACTIVE" => "Y",
                    "PASSWORD" => $password,
                    "CONFIRM_PASSWORD" => $password, //Повторяем пароль
                    "GROUP_ID" => array(3, 7, 4, intval($request['group'])), //Группы пользователя стандартные + партнёрская (Можно выбрать в настройках компонента)
                    "UF_MAP_ADDRESS" => $_POST['ADDRES_SALE'] . $_POST['TEL_BY_KLIENTS'], //Пока не работающая хрень, по сути поле заполнит 1С при выгрузке
                    "UF_MIN_SUMM" => intval($request['group']) != 9 ? 0 : 25000
                );
                $newUserID = $user->Add($arFields); //Регаем юзера получаем ИД

                if(intval($newUserID) > 0) { //Вернул ID пользователя?
                    global $USER;
                    $USER->Authorize($newUserID, true); //Авторизуем пользователя
                }
                else{
                    $usError = $user->LAST_ERROR; //Логаем ошибку
                    mailToAdministrator('Ошибка активации', 'Текст ошибки: ' . $usError); //Пуляем себе на почту
                }

                $to = 'it@extreme-look.ru, sales@extreme-look.ru';
                $subject = 'Активация партнёра';
                $message = 'Зарегестрирован новый партнёрский аккаунт <br />ID пользователя: ' . $newUserID . '<br />Данные из формы заполненные пользователем<br />ФИО: ' . $_POST['FIO'] . '<br />Название торговой точки: ' . $_POST['NAME_SALE'] . '<br />Адрес торговой точки:<br />Страна: ' . $_POST['ADDRES_SALE_COUNT'] . '<br />Населённый пункт: ' . $_POST['ADDRES_SALE_SITY'] . '<br />Улица, дом: ' . $_POST['ADDRES_SALE_STREET'] . '<br />Контактный телефон для клиентов: ' . $_POST['TEL_BY_KLIENTS'] . '<br />Адрес эл.почты: ' . $_POST['EMAIL'] . '<br />Контактный телефон: ' . $_POST['KONTACT_TEL'];
                $headers = 'From: no-reply@extreme-look.ru' . "\r\n";
                $headers .= 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                mail($to, $subject, $message, $headers);

                $newUser = true; //Врубаем переменную для сообщения
                $form = false; //Вырубаем форму
            }
        }?>
        <?if($newUser && !$form){?>
            <?
            if($arData['UF_CRON_ID'])
                CAgent::Delete($arData['UF_CRON_ID']);
            $updData = array(
                "UF_USE_LINK" => '1',
                "UF_USE_NAME" => $_POST['FIO'],
                "UF_CRON_ID"  => ''
            );
            $resultUpd = $entity_data_class::update($arData['ID'], $updData);?>

           <?echo"<div class='success-text-first'>Поздравляем! <br />Аккаунт партнёра активирован, Вы можете оформить свой первый партнёрский заказ! <a href='https://extreme-look.ru/catalog/'>Перейти к покупкам</a>. " . "<br /><br />" . "Данные от аккаунта:" . "<br />" . "Логин: " . $login . "<br />" . "Пароль: " . $password . "<br />" . "В целях безопасности рекомендуем сменить пароль в <a href='https://extreme-look.ru/personal/private/'>личном кабинете</a> на новый.</div>"?>
        <?}elseif(!$newUser && !$form){
            if($arData['UF_CRON_ID'])
                CAgent::Delete($arData['UF_CRON_ID']);
            $updData = array(
                "UF_USE_LINK" => '1',
                "UF_USE_NAME" => $_POST['FIO'],
                "UF_CRON_ID"  => ''
            );
            $resultUpd = $entity_data_class::update($arData['ID'], $updData);
            ?>
            <?echo"<div class='success-text-second'>Ваш аккаунт под логином " . $arUser['LOGIN'] . " на сайте EXTREME LOOK - изменён на партнёрский.<br /> Цены на сайте для Вас изменились. Желаем приятного сотрудничества с нами. <a href='https://extreme-look.ru/'>Перейти на главную</a></div>"?>
        <?}?>
        <?if($form){?>
            <form action="/partners/activation/<?=$request['group'] . "/" . $request['link']?>" method="post" enctype="multipart/form-data" id="form_activation">
                <table class="table-stat-part">
                    <tr>
                        <td colspan="2" style="border-image: initial;">
                            <div class="ex-contact-text">Активация партнера</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-image: initial;">
                            <input type="text" placeholder="ФИО" class="form-control" name="FIO" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-image: initial;"><div class="ex-adres-text">Адрес торговой точки:</div></td>
                    </tr>
                    <tr>
                        <td style="border-image: initial;"><input type="text" placeholder="Страна" class="form-control" name="ADDRES_SALE_COUNT" /></td>
                        <td style="border-image: initial;"><input type="text" placeholder="Населенный пункт" class="form-control" name="ADDRES_SALE_SITY" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-image: initial;"><input type="text" placeholder="Улица, дом" class="form-control" name="ADDRES_SALE_STREET" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-image: initial;">
                            <input type="text" placeholder="Название торговой точки" class="form-control" name="NAME_SALE" />
                        </td>
                    </tr>
                    <tr>
                        <td style="border-image: initial;"><input type="text" placeholder="Контактный телефон для клиентов" class="form-control" name="TEL_BY_KLIENTS" /></td>
                        <td style="border-image: initial;"><input type="text" placeholder="Адрес эл.почты" class="form-control" name="EMAIL" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-image: initial;">
                            <input type="tel" placeholder="Контактный телефон" class="form-control" name="KONTACT_TEL" pattern="2[0-9]{3}-[0-9]{3}" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-image: initial;">
                            <div class="checkbox">
                                <input type="checkbox" class="custom-checkbox" value="" checked="" id="PERSONAL" name="PERSONAL">
                                <label for="PERSONAL">Нажимая кнопку «Отправить», я даю свое согласие на обработку моих персональных данных, в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных», на условиях и для целей, определенных в Согласии на обработку персональных данных</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-image: initial;">
                            <div class="checkbox">
                                <input type="checkbox" class="custom-checkbox" value="" checked="" id="PERSONAL_2" name="PERSONAL_2">
                                <label for="PERSONAL_2">Я ознакомился с условиями сотрудничества</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-image: initial;">
                            <div class="button-shop-part">
                                <button type="submit" name="web_form_submit" value="Отправить" id="send_form_result">
                                    <span>АКТИВИРОВАТЬ</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
            <script type="text/javascript" src="https://ajax.microsoft.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
            <script>
                $(function(){
                    $('#PERSONAL').on('change', function(){
                        if($('#PERSONAL').prop('checked') && $('#PERSONAL_2').prop('checked')){
                            $('#send_form_result').attr('disabled', false);
                        }else{
                            $('#send_form_result').attr('disabled', true);
                        }
                    });
                    $('#PERSONAL_2').on('change', function(){
                        if($('#PERSONAL').prop('checked') && $('#PERSONAL_2').prop('checked')){
                            $('#send_form_result').attr('disabled', false);
                        }else{
                            $('#send_form_result').attr('disabled', true);
                        }
                    });
                });

                jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
                    phone_number = phone_number.replace(/\s+/g, "");
                    return this.optional(element) || phone_number.length > 9 &&
                        phone_number.match(/^((8|\+7|380|\+380|39|\+39|48|\+48|49|\+49)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/);
                }, "Ошибка формата телефона");

                $(document).ready(function(){
                    $("#form_activation").validate({
                        rules:{
                            FIO:{
                                required: true,
                                minlength: 4,
                                maxlength: 70
                            },
                            NAME_SALE:{
                                required: true,
                                minlength: 6,
                                maxlength: 50
                            },
                            ADDRES_SALE_COUNT:{
                                required: true,
                                minlength: 3,
                                maxlength: 50
                            },
                            ADDRES_SALE_SITY:{
                                required: true,
                                minlength: 3,
                                maxlength: 50
                            },
                            ADDRES_SALE_STREET:{
                                required: true,
                                minlength: 10,
                                maxlength: 70
                            },
                            TEL_BY_KLIENTS:{
                                phoneUS: true,
                                required: true,
                                minlength: 7,
                                maxlength: 18
                            },
                            EMAIL:{
                                email : true,
                                required: true,
                                minlength: 6,
                                maxlength: 50
                            },
                            KONTACT_TEL:{
                                phoneUS : true,
                                required: true,
                                minlength: 7,
                                maxlength: 18
                            },
                            PERSONAL:{
                                required: true
                            },
                            PERSONAL_2:{
                                required: true
                            }
                        },
                        messages:{
                            FIO:{
                                required: " Поле ФИО обязательно для заполнения",
                                minlength: " Поле должно иметь минимум 4 символа",
                                maxlength: " Максимальное число символов - 16"
                            },
                            NAME_SALE:{
                                required: " Поле 'Название' обязательно для заполнения",
                                minlength: " Поле должно иметь минимум 6 символов",
                                maxlength: " Максимальное число символов - 50"
                            },
                            ADDRES_SALE_COUNT:{
                                required: " Поле 'Страна торговой точки' обязательно для заполнения",
                                minlength: " Поле должно иметь минимум 3 символов",
                                maxlength: " Максимальное число символов - 50"
                            },
                            ADDRES_SALE_SITY:{
                                required: " Поле 'Город торговой точки' обязательно для заполнения",
                                minlength: " Поле должно иметь минимум 3 символов",
                                maxlength: " Максимальное число символов - 50"
                            },
                            ADDRES_SALE_STREET:{
                                required: " Поле 'Улица, дом торговой точки' обязательно для заполнения",
                                minlength: " Поле должно иметь минимум 10 символов",
                                maxlength: " Максимальное число символов - 70"
                            },
                            TEL_BY_KLIENTS:{
                                number: " Поле 'Телефон' должно состоять из чисел номера телефона",
                                required: " Поле 'Телефон' обязательно для заполнения",
                                minlength: " Поле должно иметь минимум 6 символов",
                                maxlength: " Максимальное число символов - 13"
                            },
                            EMAIL:{
                                email: " Некорректный Email адрес",
                                required: " Поле 'Email' обязательно для заполнения",
                                minlength: " Поле должно иметь минимум 6 символов",
                                maxlength: " Максимальное число символов - 50"
                            },
                            KONTACT_TEL:{
                                number: " Поле 'Телефон' должно состоять из чисел номера телефона",
                                required: " Поле 'Телефон' обязательно для заполнения",
                                minlength: " Поле должно иметь минимум 6 символов",
                                maxlength: " Максимальное число символов - 13"
                            },
                            PERSONAL:{
                                required: "Подтвердите соглашение о персональных данных"
                            },
                            PERSONAL_2:{
                                required: "Подтвердите соглашение с условиями сотрудничества"
                            }
                        }
                    });
                });
            </script>
        <?}?>
    <?}?>
<?}?>