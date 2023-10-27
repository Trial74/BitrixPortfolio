<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<!-- **ВЫВОДИТ СПИСОК ГОРОДОВ ВЫБРАННОЙ СТРАНЫ** -->

<?use VladClasses\UriPartClassRoute;?>
<?$_uriMTemp = new UriPartClassRoute;?>
<?$_arUrlResC = $_uriMTemp->cUrlc($_SERVER['REQUEST_URI']);?>

<?$users = array();
$filter = Array("GROUPS_ID" => [9,11,12,13], "UF_PAGE_PART" => true, "UF_COUNTRY_URL" => $arParams['NAME_COUNTRY'], "ACTIVE" => "Y");
$rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter, array('SELECT' => ['ID,', 'UF_COUNTRY', 'UF_COUNTRY_URL', 'UF_SITY_PAT', 'UF_SITY_URL']));

while ($arUser = $rsUsers->Fetch()){
    $users[] = $arUser;
}

$arResultSityes = $_uriMTemp->sortSityes($users);

if(!isset($users) || empty($users)){?>
    <div class="part-error-message">
        <span>В данной стране нет партнёров либо допущена ошибка в URL обратитесь в службу <a href="mailto:it@extreme-look.ru">технической поддержки</a></span>
    </div>
    <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
    die();
}
if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) && $_arUrlResC['count'] == 1){?>
    <nav class="part_breadcrumb">
        <ol class="breadcrumb-part">
            <li class="partner-country breadcrumb-item">
                <a href="/partners">Страны</a>
            </li>
            <li class="partner-country breadcrumb-item">
                <?=$users[0]['UF_COUNTRY'][0]?>
            </li>
        </ol>
    </nav>
    <div class="sity-active">
        <div class="block-sity-active">
            <img src='country/<?=$users[0]['UF_COUNTRY'][0]?>.png'>
            <div class="country-name"><?=$users[0]['UF_COUNTRY'][0]?></div>
        </div>
    </div>
<div class="list-sity-p">
    <?foreach ($arResultSityes as $key => $sityes){?>
        <a class="link-p-b" href="<?=$arParams['NAME_COUNTRY'] . '/' . $sityes['SITY_U']?>">
            <div class="slider partner_box__city"<?=count($arResultSityes) < 10 ? ' style="font-size:x-large;padding-bottom:7px;"' : ''?>>
                <b><?=$sityes['SITY']?></b>
            </div>
        </a>
    <?}?>
</div>
<?unset($arResultSityes, $sityes);
}
elseif (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) && $_arUrlResC['count'] >= 2){
    $APPLICATION->IncludeComponent(
        "altop:page.partners.list",
        ".default",
        array(
            "NAME_C" => $users[0]['UF_COUNTRY'][0],
            "NAME_S" => $users[0]['UF_SITY_PAT'][0],
            "NAME_COUNTRY" => $arParams['NAME_COUNTRY'],
            "NAME_SITY" => $_arUrlResC['arPath'][1],
            "COMPONENT_TEMPLATE" => ".default",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO"
        ),
        false
    );
}
