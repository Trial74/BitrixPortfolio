<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(empty($arResult))
	return;?>

<ul class="footer-menu first-footer-menu">
	<?foreach($arResult as $itemIdex => $arItem){?>
		<li>
			<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?><i class="icon-arrow-right"></i></a>
		</li>
	<?}?>
    <li>
        <a href="/live/">Live<i class="icon-arrow-right"></i></a>
    </li>
    <li>
        <a href="/blog/">Блог<i class="icon-arrow-right"></i></a>
    </li>
    <li>
        <a href="/news/subscribe/">Подписаться на рассылку<i class="icon-arrow-right"></i></a>
    </li>
</ul>