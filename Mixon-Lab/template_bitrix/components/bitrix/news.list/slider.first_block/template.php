<?php
$obName = 'mix'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));
$mainContainerName = 'mix-slide-top-'.$obName;
$imgContainerName = 'mix-img-slide-'.$obName;
$countSlides = count($arResult['ITEMS']);
$i = 1;?>

<section class="splide" id="<?=$mainContainerName?>">
    <div class="splide__track">
        <ul class="splide__list">
            <?foreach($arResult['ITEMS'] as $item){?>
                <div class="splide__slide">
                    <a href="<?=$item['PROPERTIES']['LINK']['VALUE'] == 'N' ? 'javascript:void(0);' : $item['PROPERTIES']['LINK']['VALUE'] ?>">
                        <div class="mix-first mix-flex"
                             <?=$item['PROPERTIES']['LINK']['VALUE'] == 'N' ? 'data-popup="' . $item['PROPERTIES']['POPUP']['VALUE'] . '"' : '' ?>
                             id="<?=$imgContainerName . '_img-' . $i?>"
                             data-image-src="<?=$item['PROPERTIES']['PICTURE']['VALUE']?>"
                             data-mobile-image-src="<?=$item['PROPERTIES']['PICTURE_MOBILE']['VALUE']?>"
                        ></div>
                    </a>
                </div>
                <?$i++;?>
            <?}?>
        </ul>
    </div>
</section>

<script type="text/javascript">
    var <?=$obName?> = new JCFirstBlockSlider({
            container: '<?=$mainContainerName?>',
            imgContainerName: '<?=$imgContainerName?>',
            countSlides: <?=$countSlides?>
        });

    new Splide('<?="#".$mainContainerName?>', {
        type: 'loop',
        pagination: true,
        autoplay: true,
        lazyLoad: true,
        rewind: true,
        wheel: false,
        perPage: 1,
        arrows: false
    }).mount();
</script>
