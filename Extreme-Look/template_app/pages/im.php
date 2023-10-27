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
    "ACTIVE"=>"Y",
    [
      "LOGIC" => "OR",
      "MODIFIED_USER_ID"=>$USER->GetID(),
      "PROPERTY_PUSH_TOKEN" => isset($_SESSION['PUSH_TOKEN']) ? $_SESSION['PUSH_TOKEN'] : 'push_token',
    ]
  ], false, false);?>
<div class="block" style="margin: 0">
    <div class="articles">
        <div class="block-title">Ваши уведомления</div>
        <?while ($article = $res->GetNextElement()){
            $arProps = $article->GetProperties();
            $article = $article->GetFields();?>
            <div class="card <?=$arProps['SEE_MESSAGE']['VALUE']?"see":"noSee"?>" data-id="<?=$article['ID']?>">
                <div class="card-content" style="height: 90px; overflow: hidden;">
                  <div class="card-title">
                      <div class="date-im"><?=date("d.m.Y H:i", strtotime($article['DATE_CREATE']))?></div>
                      <div class="name-im"><?=$article['NAME']?></div>
                  </div>
                  <div class="prev-im"><?=$article['PREVIEW_TEXT']?></div>
                </div>
                <div class="card-footer">
                    <a href="?page=im/detail&im_id=<?=$article['ID']?>" data-id="<?=$article['ID']?>" class="btn btn-buy by-notification"><span>Прочитать</span></a>
                </div>
            </div>
        <?}?>
    </div>
</div>
