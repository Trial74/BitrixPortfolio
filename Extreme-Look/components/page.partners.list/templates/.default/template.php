<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<!-- **ВЫВОДИТ СПИСОК ПАРТНЁРОВ В ВЫБРАННОМ ГОРОДЕ** -->
<?use VladClasses\UriPartClassRoute;?>
<?$_uriMTemp = new UriPartClassRoute;?>
<?$_arUrlResC = $_uriMTemp->cUrlc($_SERVER['REQUEST_URI']);?>

<?function regular($string){ //вытягивает номер телефона из строки
    if(!empty($string) && $string !== 'Array') {
        preg_match('/(\+?\d*)?[\s\-\.]?((\(\d+\)|\d+)[\s\-\.]?)?(\d[\s\-\.]?){6,7}/x', $string, $matches);
        return $matches[0];
    }
    else
        return false;
}?>

<?$users = array();
$filter = Array("GROUPS_ID" => [9,11,12,13], "UF_PAGE_PART" => true, "UF_SITY_URL" => $arParams['NAME_SITY'], "ACTIVE" => "Y");
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter, array('SELECT' => ['ID,', 'UF_COUNTRY', 'UF_COUNTRY_URL', 'UF_SITY_PAT', 'UF_SITY_URL', 'UF_MAP_ADDRESS', 'UF_PAGE_PART', 'UF_DOP_INFO']));

while ($arUser = $rsUsers->Fetch()){
    $users[] = $arUser;
}

if(!isset($users) || empty($users)){?>
    <div class="part-error-message">
        <span>В данном городе нет партнёров либо допущена ошибка в URL обратитесь в службу <a href="mailto:it@extreme-look.ru">технической поддержки</a></span>
    </div>
    <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
    die();
}

$arResultPartners = $_uriMTemp->sortPartners($users, $arParams['NAME_SITY']);

if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) && $_arUrlResC['count'] == 2){?>
    <nav class="part_breadcrumb">
        <ol class="breadcrumb-part">
            <li class="partner-country breadcrumb-item">
                <a href="/partners">Страны</a>
            </li class="partner-country breadcrumb-item">
            <li class="partner-country breadcrumb-item">
                <a href="/partners/<?=$arParams['NAME_COUNTRY']?>"><?=$arParams['NAME_C']?></a>
            </li>
            <li class="partner-country breadcrumb-item">
                <?=$arResultPartners[0]['UF_SITY_PAT'][0]?>
            </li>
        </ol>
    </nav>
<?$APPLICATION->SetPageProperty("title", "Партнеры нашей компании в городе " . $arResultPartners[0]['UF_SITY_PAT'][0]);?>
    <div class="list-parts">Список партнёров в городе <span><?=$arResultPartners[0]['UF_SITY_PAT'][0]?></span></div>
  <?foreach($arResultPartners as $key => $partners){?>
      <div class="partner_box__partner">
          <div class="avatar-part"></div>
          <div class="partner_box__partner__name"><?=$partners['WORK_COMPANY'];?></div>
          <?if(is_array($partners['UF_MAP_ADDRESS'])):?>
              <div class="partner-points">
                  <div class="map_points">
                      <?foreach( $partners['UF_MAP_ADDRESS'] as $addressKey => $address ){?>
                          <div class="uf_dop_info"><?=$address?>, <?=$partners['UF_DOP_INFO'][$addressKey] && $partners['UF_DOP_INFO'][$addressKey] !== 'Array' ? $partners['UF_DOP_INFO'][$addressKey] : ''?></div>
                          <div class="coordinats">
                              <?if(regular($partners['UF_DOP_INFO'][$addressKey])):?>
                                  <div class="tel_part"><a href="tel:<?=regular($partners['UF_DOP_INFO'][$addressKey])?>"><?=regular($partners['UF_DOP_INFO'][$addressKey])?></a></div>
                              <?endif;?>
                              <div class="box_marker">
                                  <a data-marker=<?=$partners['UF_SITY_PAT'][$addressKey]?>_<?=$partners['ID']?> data-marker-key="0" href="javascript: void(0);" class="marker-personal-show" <?=regular($partners['UF_DOP_INFO'][$addressKey]) ? '' : 'style="padding-left: 0;"'?>>Показать на карте</a>
                              </div>
                          </div>
                      <?}?>
                  </div>
                  <?if(count($partners['UF_MAP_ADDRESS']) > 1):?>
                      <div class="see-all">
                          <div id="see-all" class="text-see">
                              <svg color="#787878" viewBox="0 0 512 512" style="width:20px; enable-background:new 0 0 512 512;" xml:space="preserve">
                                <g>
                                    <path d="M509.121,125.966c-3.838-3.838-10.055-3.838-13.893,0L256.005,365.194L16.771,125.966c-3.838-3.838-10.055-3.838-13.893,0    c-3.838,3.838-3.838,10.055,0,13.893l246.18,246.175c1.842,1.842,4.337,2.878,6.947,2.878c2.61,0,5.104-1.036,6.946-2.878    l246.17-246.175C512.959,136.021,512.959,129.804,509.121,125.966z"/>
                                </g>
                            </svg>
                          </div>
                      </div>
                  <?endif;?>
              </div>
          <?endif;?>
      </div>
    <?}
}
elseif (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) && $_arUrlResC['count'] >= 3){?>
Ошибка в адресе
<?}
