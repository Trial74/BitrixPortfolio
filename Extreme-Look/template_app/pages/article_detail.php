<?
CModule::IncludeModule("iblock");

if(isset($_GET['id'])){
  $res = CIBlockElement::GetByID($_GET['id']);
    if($ar_res = $res->GetNext()){
      $article = $ar_res;
    }else{
      $article = false;
    }
}else{
  $article = false;
}
?>

<?if($article):?>
<div class="block block--article">
<div class="block-title"><?=$article['NAME']?></div>
<p><?=$article['DETAIL_TEXT']?></p>
</div>
<?endif;?>
