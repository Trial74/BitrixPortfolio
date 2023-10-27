<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<!-- **ВЫВОДИТ СПИСОК СТРАН ПАРТНЁРОВ** -->

<?use VladClasses\UriPartClassRoute;?>
<?$_uriMTemp = new UriPartClassRoute;?>
<?$_arUrlResC = $_uriMTemp->cUrlc($_SERVER['REQUEST_URI']);?>

<?$users = array();
$filter = Array("GROUPS_ID" => [9,11,12,13], "UF_PAGE_PART" => true, "ACTIVE" => "Y");
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter, array('SELECT' => ['ID,', 'UF_COUNTRY', 'UF_COUNTRY_URL']));

while ($arUser = $rsUsers->Fetch()){
    $users[] = $arUser;
}

$arResultCountries = $_uriMTemp->sortCountry($users);

if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) && $_arUrlResC['count'] == 0) {
    foreach ($arResultCountries as $key => $countries) {?>
        <a class="link-p-b" href="<?=$countries['COUNTRY_U']?>">
            <div class="partner_box">
                <div class="slider sl_m">
                    <img src='country/<?=$countries['COUNTRY']?>.png'>
                    <div class="country-name"><?=$countries['COUNTRY']?></div>
                </div>
            </div>
        </a>
    <?}
    unset($arResultCountries, $countries);?>
    <div class="stat-part">
        <a href="https://extreme-look.ru/partners/stat-partnyerom/">
            <div class="block-part">
                <img class="avat-part" src='country/avatar.svg'>
                <div class="text-part">Стать партнёром EXTREME LOOK</div>
                <img class="arrow" src='country/arrow-right.svg'>
            </div>
        </a>
    </div>
<?}
elseif (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) && $_arUrlResC['count'] >= 1){
    $APPLICATION->IncludeComponent(
        "altop:page.partner.sity",
        ".default",
        array(
            "NAME_COUNTRY" => $_arUrlResC['arPath'][0],
            "COMPONENT_TEMPLATE" => ".default",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO"
        ),
        false
    );
}