<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<link rel="stylesheet" href="/bitrix/components/altop/stories.vlad/templates/.default/style_vlad.css?<?=time()?>">
<?
global $USER;
$USER->IsAuthorized() ? $UAuth = $USER->GetId() : $UAuth = false;
?>
<div class="container-fluid block-opt">
    <div class="min-opt">
        <div class="stor-item">
            <div id="f-stor">
                <img src="/bitrix/components/altop/stories.vlad/templates/.default/img/prev/prev1.png?<?=time()?>" alt="Сторис 1" class="round">
            </div>
        </div>
        <div class="stor-item">
            <div id="s-stor">
                <img src="/bitrix/components/altop/stories.vlad/templates/.default/img/prev/prev2.jpg?<?=time()?>" alt="Сторис 2" class="round">
            </div>
        </div>
        <div class="stor-item">
            <div id="th-stor">
                <img src="/bitrix/components/altop/stories.vlad/templates/.default/img/prev/prev3.png?<?=time()?>" alt="Сторис 3" class="round">
            </div>
        </div>
        <div class="stor-item">
            <div id="fo-stor">
                <img src="/bitrix/components/altop/stories.vlad/templates/.default/img/prev/prev4.png?<?=time()?>" alt="Сторис 4" class="round">
            </div>
        </div>
    </div>
</div>
<script>
var user = <?=CUtil::PhpToJSObject($UAuth)?>;
var device = <?=CUtil::PhpToJSObject(MOBILE_OS)?>;
</script>