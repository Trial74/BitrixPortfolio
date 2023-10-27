<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?CModule::IncludeModule("iblock");
function readMesage ($ELEMENT_ID){
    $PROPERTY_CODE = "SEE_MESSAGE";
    $PROPERTY_VALUE = "185";
    CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));
}
$res = CIBlockElement::GetList(
  ['ID' => 'DESC'],
  [
      "IBLOCK_ID" => 37,
      "ID" => $_REQUEST['im_id'],
      "ACTIVE"=>"Y",
      [
          "LOGIC" => "OR",
          "MODIFIED_USER_ID"=>$USER->GetID(),
          "PROPERTY_PUSH_TOKEN" => isset($_SESSION['PUSH_TOKEN']) ? $_SESSION['PUSH_TOKEN'] : 'push_token',
      ]
  ], false, false);?>
<div class="block">
    <div class="articles">
        <?while ($article = $res->GetNextElement()) {
            $arProps = $article->GetProperties();
            $article = $article->GetFields();?>
            <div class="card see" data-id="<?=$article['ID']?>">
                <div class="card-content">
                    <div class="card-title">
                      <div class="date-im"><?=date("d.m.Y H:i", strtotime($article['DATE_CREATE']))?></div>
                      <div class="name-im"><?=$article['NAME']?></div>
                    </div>
                    <div class="prev-im"><?=$article['PREVIEW_TEXT']?></div>
                </div>
                <div class="card-footer">
                    <a href="?page=im&time=<?=time()?>" style="display: block" data-id="<?=$article['ID']?>" class="btn btn-buy by-notification"><span>Назад</span></a>
                </div>
            </div>
        <?if(!$arProps['SEE_MESSAGE']['VALUE'])readMesage($article['ID']);
        }?>
    </div>
</div>
