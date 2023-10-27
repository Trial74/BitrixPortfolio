<?
CModule::IncludeModule("iblock");

$res = CIBlockElement::GetList(['SORT' => 'ASC'], ["IBLOCK_ID" => 35, "ACTIVE"=>"Y"], false, false);?>
<div class="block">
	<div class="articles">
		  <?while($article = $res->Fetch()){//var_dump($article)
			$img_id = $article['PREVIEW_PICTURE'] ? $article['PREVIEW_PICTURE'] : ($article['DETAIL_PICTURE'] ? $article['DETAIL_PICTURE'] : '');
			if($img_id){
				$file = CFile::ResizeImageGet($img_id, array('width'=>600, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			}
			?>
			  <div class="card">
				<?if($img_id){?>
				  <div class="card-header" style="background-image: url(<?=$file['src']?>)" v-align="center">
				  </div>
				<?}?>
				<div class="card-content card-content-padding">
				  <div class="card-title"><?=$article['NAME']?></div>
				  <p><?=$article['PREVIEW_TEXT']?></p>
				</div>
				<div class="card-footer">
					<a href="/page-article_detail/id=<?=$article['ID']?>/" class="link">подробнее</a>
				</div>
			  </div>
		  <?}?>
	</div>
</div>
