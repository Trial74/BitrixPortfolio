<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));
$containerName = 'mix-top-menu-'.$obName;
$openButton = 'mix-open-button-'.$obName;
$openButtonDesc = 'mix-open-button-desctop-'.$obName;
$contactBlock = 'mix-contact-block-'.$obName;?>
<div class="senter-block mix-top-menu mix-flex" id="<?=$containerName?>">
    <?foreach ($arResult as $key => $item){?>
        <div class="mix-item-menu">
            <a class="mix-item-link" href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
        </div>
    <?}?>
</div>
<div class="dropdown">
    <div data-bs-toggle="dropdown" aria-expanded="false" class="mix-open-menu desctop" id="<?=$openButtonDesc?>">MENU<i class="mix-icon-top-menu"></i></div>
    <ul class="dropdown-menu mix-dropdown-menu">
        <li class="float-start"><a href="#">Блог</a></li>
        <li class="float-start"><a href="#">Карьера</a></li>
        <li class="float-start"><a href="#">Где купить</a></li>
    </ul>
</div>
<div class="mix-top-contacts mix-flex right-block">
    <div class="mix-phone-block"><a href="#"></a></div>
    <div class="mix-flex mix-messendger">
        <div class="mix-mes-whatsapp me-3"><a target="_blank" href="https://wa.me/79227421468"></a></div>
        <div class="mix-mes-telegram"><a target="_blank" href="https://t.me/mixon_manager"></a></div>
    </div>
</div>
<div class="mix-open-menu" id="<?=$openButton?>">MENU<i class="mix-icon-top-menu"></i></div>

<script type="text/javascript">
    if(window.screen.width < 992) {
        var <?=$obName?> = new JSMixTopMenu({
            container: '<?=$containerName?>',
            openButton: '<?=$openButton?>',
            contactBlock: '<?=$contactBlock?>',
            menu: <?=CUtil::PhpToJSObject($arResult, false, true);?>
        });
    }
</script>
