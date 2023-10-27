<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;?>

<?global $USER;
$arGroupPartners = array(9,11,12,13);
$GroupRoznic = array(10);
$USPart = false;
$USRoznic = false;
$UNAuth = false;
$news = false;
$arFields = array();
$getNews = $_GET['news'] ? true : false;

$arGroups = $USER->GetUserGroup($USER->GetID());
$resultGRPart = array_intersect($arGroupPartners, $arGroups);
$resultGRRoznic = array_intersect($GroupRoznic, $arGroups);

if(!empty($resultGRPart)) $USPart = true; //Партнёр
elseif(!empty($resultGRRoznic)) $USRoznic = true; //Розница
else $UNAuth = true; //Неавторизован

$arSelect = Array("ID", "NAME", "PREVIEW_TEXT", "DETAIL_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "DATE_ACTIVE_FROM", "PROPERTY_BY_PARTNER", "PROPERTY_BY_ROZNICA");

if(!$getNews){ //Список новостей
    if ($USPart) {
        $arFilter = Array("IBLOCK_ID" => IntVal(111), "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "PROPERTY_BY_PARTNER_VALUE" => "Да");
    }
    if ($USRoznic || $UNAuth) {
        $arFilter = Array("IBLOCK_ID" => IntVal(111), "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "PROPERTY_BY_ROZNICA_VALUE" => "Да");
    }
    if ($USER->IsAdmin()) {
        $arFilter = Array("IBLOCK_ID" => IntVal(111), "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
    }
    $res = CIBlockElement::GetList(array("id" => "desc"), $arFilter, false, array(), $arSelect);
    while ($ob = $res->GetNextElement()) {
        array_push($arFields, $ob->GetFields());
    }
}
else{//Конкретная новость
    if ($USPart) {
        $arFilter = Array("IBLOCK_ID" => IntVal(111), "ID" => $_GET['news'], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "PROPERTY_BY_PARTNER_VALUE" => "Да");
    }
    if ($USRoznic || $UNAuth) {
        $arFilter = Array("IBLOCK_ID" => IntVal(111), "ID" => $_GET['news'], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "PROPERTY_BY_ROZNICA_VALUE" => "Да");
    }
    if ($USER->IsAdmin()) {
        $arFilter = Array("IBLOCK_ID" => IntVal(111), "ID" => $_GET['news'], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
    }
    $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
    $resNews = $res->fetch();
    if(!$UNAuth) { //Заносим идентификатор пользователя просмотревшего новость только если пользователь авторизован (капитан очевидность)
        $OBprops = CIBlockElement::GetProperty(111, $_GET['news'], array("sort" => "asc"), Array("CODE"=>"OZN"));
        $VALUES = array();
        while ($valUs = $OBprops->GetNext())
        {
            $ViewedUsers[] = $valUs['VALUE'];
        }
        if(!in_array($USER->GetID(), $ViewedUsers)) {
            array_push($ViewedUsers, $USER->GetID());
            $ELEMENT_ID = $_GET['news'];  // код элемента
            $PROPERTY_CODE = "OZN";  // код свойства
            $PROPERTY_VALUE = $ViewedUsers;  // значение свойства
            CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, 111, array($PROPERTY_CODE => $PROPERTY_VALUE));
        }
    }
}?>
<style>
    .mb-title-container{
        width: 100%;
        display: table;
        padding: 16px 24px;
        -moz-border-radius: 5px 5px 0 0;
        -webkit-border-radius: 5px 5px 0 0;
        -khtml-border-radius: 5px 5px 0 0;
        border-radius: 5px 5px 0 0;
        background-color: #f1f6f7;
    }
    .mb-block-container{
        margin-bottom: 18px;
        padding: 24px 23px 11px;
        color: #3d4b52;
        border: 1px solid #e3ecef;
        border-top: none;
        -moz-border-radius: 0 0 5px 5px;
        -webkit-border-radius: 0 0 5px 5px;
        -khtml-border-radius: 0 0 5px 5px;
        border-radius: 0 0 5px 5px;
    }
    .mb-title__icon {
        display: table-cell;
        vertical-align: middle;
        padding-right: 12px;
    }
    .mb-title__val {
        display: table-cell;
        vertical-align: middle;
        color: #3d4b52;
    }
    .mb-title__val > h4{
        font-family: 'Graphik LCG';
        font-weight: 500;
    }
    .mess-err > button{
        margin-top: 15px;
    }
    .grid * {
        box-sizing: border-box;
    }
    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        column-gap: 30px;
        row-gap: 30px;
        padding-left: 50px;
        padding-right: 50px;
    }
    /* Кол-во блоков на разных экранах */
    @media only screen and (max-width: 1200px) {
        .grid {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }
    @media only screen and (max-width: 900px) {
        .grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    @media only screen and (max-width: 600px) {
        .grid {
            grid-template-columns: 1fr;
        }
    }
    .grid-item {
        box-shadow: 0 0px 5px rgb(0 0 0 / 20%), 0 2px 6px rgb(0 0 0 / 20%);
        transition: box-shadow .3s;
        width: 100%;
        height: 100%;
        border-radius: 5px;
    }
    .grid-item .image {
        height: 250px;
        overflow: hidden;
    }
    .grid-item .info {
        position: relative;
        height: calc(100% - 250px);
        padding: 16px 14px 80px 14px;
    }
    .grid-item .image img  {
        transition: transform 280ms ease-in-out;
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .grid-item:hover .image img  {
        transform: scale(1.1);
    }
    .info h2 {
        font-family: 'Graphik LCG';
        font-size: 24px;
        font-weight: 500;
        margin: 0;
        text-align: start;
    }
    .info-text p {
        font-size: 15px;
        line-height: 20px;
        font-family: 'Graphik LCG';
        margin-bottom: 10px;
    }
    .info-text p:last-child {
        margin-bottom: 0;
    }
    .grid-item .button-wrap {
        display: block;
        width: 100%;
        position: absolute;
        bottom: 14px;
        left: 0;
        text-align: center;
    }
    @media (max-width: 991px){
        .info h2 {
            font-size: 18px;
        }
        .spsp-main-profile{
            margin-left: -18px;
            margin-right: -18px;
        }
        .mb-personal-data-inner{
            margin-top: 30px;
            float: none;
        }
        .mb-personal-data-inner > button,
        .mb-personal-data-inner > a{
            margin-top: 15px;
        }
        .grid {
            padding-left: 5px;
            padding-right: 5px;
        }
    }
</style>
<?if(!$getNews){//Список новостей?>
    <div class="spsp-main-profile">
        <div class="mb-personal-data">
            <div class="mb-title-container">
                <div class="mb-title">
                    <div class="mb-title__icon"><i class="fas fa-rss"></i></div>
                    <div class="mb-title__val">Новости для наших клиентов</div>
                </div>
            </div>
            <div class="mb-block-container">
                <div class="row">
                    <section class="grid">
                        <?if(!empty($arFields)){?>
                            <?foreach($arFields as $key => $Field){?>
                                <article class="grid-item">
                                    <div class="image">
                                        <img src="<?=CFile::GetPath($Field["PREVIEW_PICTURE"]);?>" />
                                    </div>
                                    <div class="info">
                                        <h2><?=$Field['NAME']?></h2>
                                        <div class="info-text">
                                            <p><?=$Field['PREVIEW_TEXT']?></p>
                                        </div>
                                        <div class="button-wrap">
                                            <a class="btn btn-buy" href="?news=<?=$Field['ID']?>"><span>Подробнее</span></a>
                                        </div>
                                    </div>
                                </article>
                            <?}?>
                        <?}else{?>
                            <p>Нет новостей</p>
                        <?}?>
                    </section>
                </div>
            </div>
        </div>
    </div>
<?}else{//Конкретная новость?>
        <div class="spsp-main-profile">
            <div class="mb-personal-data">
                <div class="mb-title-container">
                    <div class="mb-title">
                        <div class="mb-title__icon"><i class="fas fa-rss"></i></div>
                        <div class="mb-title__val"><h4><?=!empty($resNews['NAME']) ? $resNews['NAME'] : 'Ошибка - новость не найдена'?></h4></div>
                    </div>
                </div>
                <div class="mb-block-container">
                    <div class="row">
                        <section class="grid" style="grid-template-columns: 1fr;">
                            <div class="image">
                                <img src="<?=CFile::GetPath($resNews["DETAIL_PICTURE"]);?>" />
                            </div>
                            <div class="info">
                                <div class="info-text">
                                    <p><?=!empty($resNews['DETAIL_TEXT']) ? $resNews['DETAIL_TEXT'] : ''?></p>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
<?}?>