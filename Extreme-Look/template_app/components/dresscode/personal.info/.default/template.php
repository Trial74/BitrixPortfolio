<style>
    .block > #personalForm{
        background: white;
        border-radius: 10px;
        padding-bottom: 10px;
    }
    .list .item-inner.block-input{
        border: 1px solid #d8d4d4;
        padding: 7px 5px 0 10px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .md .item-input-wrap:after,
    .ios .item-input-wrap:after{
        height: 0;
    }
    .md .list .item-content,
    .ios .list .item-content{
        padding-left: 10px;
        padding-right: 10px;
    }
    .md .item-input-wrap .input-clear-button,
    .ios .item-input-wrap .input-clear-button{
        bottom: 20px;
        right: 5px;
    }
    .ios .input-clear-button{
        margin-top: -19px;
    }
    .ios .list ul:before,
    .ios .list ul:after{
        height: 0;
    }
    .md .input-clear-button:after{
        background-size: 11px 12px;
    }
    .md .input-clear-button{
        width: 15px;
        height: 15px;
    }
    .ios .item-input-wrap{
        margin-bottom: -5px;
    }
    #personalForm .block-title{
        font-size: 20px;
        font-weight: 600;
        text-transform: none;
        color: black;
    }
    .view-main > .page[data-name='personal/index']{
        background: linear-gradient(to top, #21d2fd, #a435ff) !important;
    }
    .md .block > .button,
    .ios .block > .button{
        color: white;
        background: #a139ff;
        border: none;
        border-radius: 4px;
        text-transform: none;
    }
    .md .block > button.button.logout-link,
    .ios .block > button.button.logout-link,
    .md .block > button.button.del-ac-link,
    .ios .block > button.button.del-ac-link{
        color: #a139ff;
        background: #eeebff;
    }
    .photo-user{
        text-align: center;
    }
    .block-personal-img{
        width: 100px;
        height: 100px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .block-personal-img:after{
        content: "";
        background: url('/bitrix/templates/mobileapp/images/new_photo_user.png') no-repeat;
        background-size: contain;
        width: 20px;
        height: 20px;
        right: -10px;
        top: -10px;
        position: absolute;
    }
    #personal-photo-app{
        border-radius: 100px;
    }
    .greeting,
    .name-user{
        color: black;
        text-align: center;
        font-weight: 600;
    }
    .greeting{
        font-size: 12px;
    }
    .name-user{
        font-size: 24px;
    }
    .psevdo{
        height: 3px;
        background: #d4cfef;
        border-radius: 10px;
        width: 40px;
        margin: 10px auto 30px auto;
    }
    .typefile{
        width: 0;
        height: 0;
        position: absolute;
        left: -1000px;
        top: 0;
        opacity: 0;
    }
    .ios .list ul{
        background: transparent;
    }
</style>

<?
if(isset($arResult['USER']['PERSONAL_PHOTO']) && !empty($arResult['USER']['PERSONAL_PHOTO'])){
    $renderImage = CFile::ResizeImageGet($arResult['USER']['PERSONAL_PHOTO'], Array("width" => 100, "height" => 100));
}else{
    $renderImage['src'] = '/bitrix/templates/mobileapp/images/no_avatar_app.png';
}
?>
<form class="list no-hairlines-md" id="personalForm">
    <div class="row">
        <div class="col-100 medium-50">
            <div class="psevdo"></div>
        </div>
        <!--<div class="col-100 medium-50 photo-user">
            <div class="block-personal-img">
                <input name="PERSONAL_PHOTO" id="personal_photo_input" class="typefile" size="20" type="file">
                <img id="personal-photo-app" src="<?/*=$renderImage['src']*/?>" alt="Персональное фото">
            </div>
        </div>-->
        <div class="col-100 medium-50 greeting"><?=GetMessage("GREETING")?></div>
        <div class="col-100 medium-50 name-user"><?=$arResult["USER"]["NAME"]?></div>
    </div>
    <div class="block-title">
        <?=GetMessage("PERSONAL_DATA")?>
    </div>
	<ul>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label"><?=GetMessage('PROFILE_LAST_NAME')?> <?=GetMessage('PROFILE_NAME')?> <?=GetMessage('PROFILE_SECOND_NAME')?></div>
				<div class="item-input-wrap">
					<input type="text" name="FIO" value="<?=$arResult["USER"]["NAME"]?> <?=$arResult["USER"]["LAST_NAME"]?> <?=$arResult["USER"]["SECOND_NAME"]?>">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label">
					<?=GetMessage('EMAIL')?>
				</div>
				<div class="item-input-wrap">
					<input type="text" name="EMAIL" value="<?=$arResult["USER"]["EMAIL"]?>">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label">
					<?=GetMessage('USER_MOBILE')?>
				</div>
				<div class="item-input-wrap">
					<input type="text" name="USER_MOBILE" value="<?=$arResult["USER"]["PERSONAL_MOBILE"]?>">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label">
					<?=GetMessage('USER_CITY')?>
				</div>
				<div class="item-input-wrap">
					<input type="text" name="USER_CITY" value="<?if(!empty($arResult["USER"]["PERSONAL_CITY"])):?><?=$arResult["USER"]["PERSONAL_CITY"]?><?else:?><?=$arResult["USER"]["CITY_NAME"]?><?endif;?>">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label">
					<?=GetMessage('USER_ZIP')?>
				</div>
				<div class="item-input-wrap">
					<input type="text" name="USER_ZIP" value="<?=$arResult["USER"]["PERSONAL_ZIP"]?>">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label">
					<?=GetMessage('USER_STREET')?>
				</div>
				<div class="item-input-wrap">
					<input type="text" name="USER_STREET" value="<?=$arResult["USER"]["PERSONAL_STREET"]?>">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
	</ul>
	<div class="block-title">
		<?=GetMessage("CHANGE_PASS")?>
	</div>
	<ul>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label">
					<?=GetMessage("PASS")?>
				</div>
				<div class="item-input-wrap">
					<input type="password" name="USER_PASSWORD" value="">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label">
					<?=GetMessage("REPASS")?>
				</div>
				<div class="item-input-wrap">
					<input type="password" name="USER_PASSWORD_CONFIRM" value="">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
	</ul>
	<div class="block">
		<button class="button" type="submit"><?=GetMessage("SAVE")?></button>
	</div>
    <div class="block">
        <button class="button logout-link panel-close ripple-color-white" type="button"><?=GetMessage("EXIT")?></button>
    </div>
    <!--<div class="block">
        <button class="button del-ac-link panel-close ripple-color-white" type="button"><?/*=GetMessage("DEL")*/?></button>
    </div>-->
</form>
