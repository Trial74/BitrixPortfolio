<?
return [
        'MAIN' => "Чем я могу Вам сегодня помочь? 🙂",
        'GETAREA' => ", выберите площадку на которой Вы приобрели продукт",
        'ERRLOADIMGUSER' => "Вам необходимо загрузить в этот чат фото комментария сделанного на странице товара 'Extreme-Look/Lashmaker/Mixon' который вы заказали на ",
        'WHATNAME' => "Как к Вам можно обращаться?",
        'BUY' => "Приобрести нашу продукцию вы можете:\n".
            "На официальном сайте - <a href='https://extreme-look.ru'>Extreme Look</a>\n".
            "Официальный магазин OZON - <a href='https://www.ozon.ru/seller/extreme-look-817815/?miniapp=seller_817815'>Extreme Look - OZON</a>\n" .
            "Официальный магазин Wildberries - <a href='https://www.wildberries.ru/seller/56540'>Extreme Look - WB</a>",
        'CONTEST' => "Чтобы принять участие в розыгрыше и получить шанс выиграть ежемесячный бокс с нашими продуктами, необходимо выполнить следующие условия:\n\n".
            "<pre>1. Купить продукты Extreme Look, Lashmaker или Mixon на: <a href='https://www.wildberries.ru/seller/56540'>Wildberries</a> или <a href='https://www.ozon.ru/seller/extreme-look-817815/?miniapp=seller_817815'>OZON</a> (Продавец EXTREME LOOK).</pre>\n" .
            "<pre>2. Написать отзыв о купленном продукте на странице товара на Wildberries или Озон.</pre>\n".
            "<pre>3. Отправить скриншот отзыва и скриншот покупки из личного кабинета в телеграм-бот.</pre>\n\n".
            "🔹️ Один победитель будет случайным образом выбран в первую неделю каждого месяца и объявлен в официальном телеграм-канале <a href='https://t.me/extreme_look_ru'>Extreme Look</a>\n\n".
            "🔹️ Обратите внимание, что к участию допускаются только заказы, оформленные у официальных продавцов бренда -  ИП Хитрина на Wildberries и  на Озон. Рекомендуется проверять продавца перед покупкой.\n\n".
            "🔹️ Доставка приза осуществляется только по территории Российской Федерации.\n\n".
            "🔹️ Призы могут быть заменены без предупреждения в зависимости от наличия продукции.\n\n".
            "🔹️ Выполнение каждого условия обязательно для участия в розыгрыше.",
        'CONTACTS' => "<b>Наши контакты:</b>\n".
            "Горячая линия - <a href='tel:88003507215'>8 (800) 350-72-15</a>\n".
            "Email - <a href='mailto:info@extreme-look.ru'>info@extreme-look.ru</a>\n" .
            "Группа Вконтакте - <a href='https://vk.com/extreme_look'>Extreme-Look VK</a>\n\n" .
            "<b>Контакты MIXON - контрактное производство</b>\n" .
            "Email - <a href='mailto:info@mixon-lab.ru'>info@mixon-lab.ru</a>\n" .
            "Телефон по вопросам производства - <a href='tel:89227421468'>8(922)742-14-68</a>\n" .
            "Группа ВК - <a href='https://vk.com/mixon_lab'>MIXON VK</a>",
        'ERROR' => "Я не могу разобрать Ваш ответ. Если у Вас остались вопросы, свяжитесь, пожалуйста, с нами по адресу <a href='mailto:info@extreme-look.ru'>info@extreme-look.ru</a> либо по номеру <a href='tel:88003507215'>8 (800) 350-72-15</a>",
        'FIRSTKEYBOARD' => array(
            array(['text' => 'Купить продукцию Extreme Look, Lashmaker, Mixon?', 'callback_data' => 'buy']),
            array(['text' => 'Учавствовать в конкурсе с отзывом', 'callback_data' => 'contest']),
            array(['text' => 'Как с вами связаться?', 'callback_data' => 'contacts']),
            //array(['text' => 'Тест', 'callback_data' => 'test'])
        ),
        'AREASKEYBOARD' => array(
            array(['text' => 'Wildberries', 'callback_data' => 'wildberries_help']),
            array(['text' => 'Ozon', 'callback_data' => 'ozon_help'])
        ),
        'MAINMENU' => array(
            array(['text' => 'Главное меню', 'callback_data' => 'start'])
        ),
        'RECHOOSEAREA' => array(
            array(['text' => 'Выбрать другую площадку', 'callback_data' => 'rechoosearea'])
        ),
        'NAMEUSER' => array(
            array(['text' => 'Да', 'callback_data' => 'nameyes']),
            array(['text' => 'Изменить имя', 'callback_data' => 'namerename'])
        ),
        'HELP' => array(
            array(['text' => 'Нет, справлюсь', 'callback_data' => 'helpno']),
            array(['text' => 'Да, подскажите', 'callback_data' => 'helpyes'])
        ),
        'AFTERUPIMAGE' => array(
            array(['text' => 'Главное меню', 'callback_data' => 'start']),
            array(['text' => 'Отправить скриншот для другой платформы', 'callback_data' => 'rechoosearea'])
        )
];