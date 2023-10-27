<?CModule::IncludeModule("iblock");
$pageDB = CIBlockElement::GetList(
  array('ID' => 'DESC'),
  array(
    "IBLOCK_ID" => 65,
    "ID" =>$_GET["prom_id"],
    "ACTIVE"=>"Y"
  ), false, false, array('NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT'));?>
<?if(!empty($pageDB) && $page = $pageDB->Fetch()){?>
    <div class="prom-main-block block-strong strong-support">
        <div class="prom-name-app"><?=$page['NAME']?></div>
        <div class="prom-img-block-app">
            <img width="100%" class="prom-img-app" src="<?=CFile::GetPath($page['PREVIEW_PICTURE'])?>" />
        </div>
        <div class="container-text"><?=$page['DETAIL_TEXT'];?></div>
    </div>
<?}else{?>
    <div class="prom-main-block block-strong strong-support">
        Статья не найдена.
    </div>
<?}?>

