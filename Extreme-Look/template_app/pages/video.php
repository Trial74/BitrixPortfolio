<?
CModule::IncludeModule("iblock");

$arSelect = ["ID", "IBLOCK_ID", "NAME", 'PREVIEW_PICTURE', "DETAIL_PICTURE", 'PROPERTY_LINK'];
$arFilter = ["IBLOCK_ID" => 36, "ACTIVE" => "Y"];

$res = CIBlockElement::GetList(['SORT' => 'ASC'], $arFilter, false, false, $arSelect);?>

<div class="block">
	<div class="video">
	  <?while($video = $res->Fetch()){
		if(!$video['PROPERTY_LINK_VALUE'])
		  continue;
		$parts = parse_url($video['PROPERTY_LINK_VALUE']);
		parse_str($parts['query'], $query);
		$video_code = "https://www.youtube.com/embed/".$query['v'];

		if (preg_match("#embed#", $video['PROPERTY_LINK_VALUE'])){
			$video_code = $video['PROPERTY_LINK_VALUE'];
		}

		  ?>
		  <div class="card">
			<div class="card-content card-content-padding">
			  <iframe class="ytb-video" width="100%" height="215" src="<?=$video_code?>?modestbranding=1&enablejsapi=1&rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>
			<!-- <div class="card-footer">
			  <a href="#" onclick="window.open('https://www.youtube.com', '_blank', 'location=yes');"></a>
			  <a class="external" target="_blank" href="<?=$video['PROPERTY_LINK_VALUE']?>">Смотреть на youtube</a>
			</div> -->
		  </div>
	  <?}?>
	</div>
</div>
