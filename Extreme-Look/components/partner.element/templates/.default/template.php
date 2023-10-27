<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!-- **ВЫВОДИТ ПАРТНЁРА** -->
<?use VladClasses\UriPartClassRoute;?>
<?$_uriMTemp = new UriPartClassRoute;?>
<?$_arUrlResC = $_uriMTemp->cUrlc($_SERVER['REQUEST_URI']);?>

<?if(!isset($_SERVER['REQUEST_URI']) || empty($_SERVER['REQUEST_URI']) || $_arUrlResC['count'] <> 1 || !isset($_arUrlResC['arPath'][0]) || empty($_arUrlResC['arPath'][0])){?>
    <div class="part-error-message">
        <span>В данном городе нет такого партнёра либо допущена ошибка в URL обратитесь в службу <a href="mailto:it@extreme-look.ru">технической поддержки</a></span>
    </div>
    <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
    die();
}?>
<?function regular($string){ //вытягивает номер телефона из строки
    if(!empty($string) && $string !== 'Array') {
        preg_match('/(\+?\d*)?[\s\-\.]?((\(\d+\)|\d+)[\s\-\.]?)?(\d[\s\-\.]?){6,7}/x', $string, $matches);
        return $matches[0];
    }
    else
        return false;
}?>

<?$users = array();
$filter = Array("GROUPS_ID" => ALL_PART, "UF_PAGE_PART" => true, "ID" => $_arUrlResC['arPath'][0], "ACTIVE" => "Y");
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter, array('SELECT' => ['ID,', 'UF_COUNTRY', 'UF_COUNTRY_URL', 'UF_SITY_PAT', 'UF_SITY_URL', 'UF_MAP_ADDRESS', 'UF_PAGE_PART', 'UF_DOP_INFO']));
$user = $rsUsers->Fetch();

if(!isset($user) || empty($user)){?>
    <div class="part-error-message">
        <span>В данном городе нет такого партнёра либо допущена ошибка в URL обратитесь в службу <a href="mailto:it@extreme-look.ru">технической поддержки</a></span>
    </div>
    <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
    die();
}

if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])){?>
    <nav class="part_breadcrumb">
        <ol class="breadcrumb-part">
            <li class="partner-country breadcrumb-item">
                <?=$user['UF_COUNTRY'][0]?>
            </li>
            <li class="partner-country breadcrumb-item">
                <?=$user['UF_SITY_PAT'][0]?>
            </li>
        </ol>
    </nav>
<?$APPLICATION->SetPageProperty("title", "Партнер нашей компании в городе " . $user['UF_SITY_PAT'][0]);?>
    <div class="list-parts">Партнёр в городе <span><?=$user['UF_SITY_PAT'][0]?></span></div>
      <div class="partner_box__partner">
          <div class="avatar-part"></div>
          <div class="partner_box__partner__name"><?=$user['WORK_COMPANY'];?></div>
          <?if(is_array($user['UF_MAP_ADDRESS'])):?>
              <div class="partner-points">
                  <div class="map_points">
                      <?foreach($user['UF_MAP_ADDRESS'] as $addressKey => $address){?>
                          <div class="uf_dop_info"><?=$address?>, <?=$user['UF_DOP_INFO'][$addressKey] && $user['UF_DOP_INFO'][$addressKey] !== 'Array' ? $user['UF_DOP_INFO'][$addressKey] : ''?></div>
                      <?}?>
                  </div>
                  <?if(count($user['UF_MAP_ADDRESS']) > 1):?>
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
<?}?>