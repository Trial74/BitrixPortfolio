<style>
    .text-error-re{
        font-family: 'Graphik LCG';
        font-weight: bold;
    }
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
    .md .block > .button,
    .ios .block > .button{
        color: white;
        background: #a139ff;
        border: none;
        border-radius: 4px;
        text-transform: none;
    }
</style>
<div class="block">
	<div class="text-error-re">Нашли ошибку?<br />Пожалуйста, опишите подробно, что именно происходит не так - это очень нам поможет.</div>
</div>
<?if($USER->IsAuthorized()){
    $userName = $USER->GetFirstName();
    $emailUser = $USER->GetEmail();
}
else{
    $userName = false;
    $emailUser = false;
}?>
<form class="list no-hairlines-md" id="errorReport">
	<ul>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label">Ваше имя</div>
				<div class="item-input-wrap">
					<input type="text" name="name" value="<?=$userName ? $userName : ''?>">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
		<li class="item-content item-input item-input-with-info">
			<div class="item-inner block-input">
				<div class="item-title item-label">E-Mail</div>
				<div class="item-input-wrap">
					<input type="text" name="email" value="<?=$emailUser ? $emailUser : ''?>">
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
		<li class="item-content item-input">
			<div class="item-inner block-input">
				<div class="item-title item-label">Сообщение</div>
				<div class="item-input-wrap">
					<textarea name="message"></textarea>
					<span class="input-clear-button"></span>
				</div>
			</div>
		</li>
	</ul>
	<div class="block">
		<button class="button" type="submit">Отправить</button>
	</div>
</form>