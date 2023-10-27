<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if($_SESSION['SESS_LAST_HOST'] == 'en.extreme-look.ru'){ //Мой код меняем название разделов на англоязычные если пользователь находится на английской версии сайта
    foreach ($arResult as $key => $re){
        $rsSections = CIBlockSection::GetList( //Делаем выборку по ID раздела основного торгового каталога
            array(),
            array(
                "IBLOCK_ID" => 23,
                "NAME" => $arResult[$key]['TEXT'] //Выборка по названию раздела, бред, но разрабы не дали ID раздела
            ),
            false,
            array("ID", "NAME", "UF_EN_NAME") //Запрашиваем поле с английским названием
        );
        $arSect = $rsSections->Fetch();
        $arResult[$key]['TEXT'] = $arSect['UF_EN_NAME']; //Заносим в результирующий массив заменяя русское название на английское
    }
}