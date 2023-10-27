<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
	$arResult["AUTH_URL"] = '?' . MOBILE_GET . '=Y&page=register';
?>

	<div class="block">
		<p class="registerText"><?=GetMessage("REGISTER_TEXT")?></p>

		<div class="bx-authform-description-container">
			<div class="bold"><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></div>
		</div>

		<div class="bx-authform-description-container">
			<div class="bold"><span class="bx-authform-starrequired">*</span> - <?=GetMessage("AUTH_REQ")?></div>
		</div>

		<?
		if(!empty($arParams["~AUTH_RESULT"])):
			$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
		?>
			<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success text-color-green":"alert-danger text-color-red")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
		<?endif?>

		<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
			<div class="alert alert-success"><?echo GetMessage("AUTH_EMAIL_SENT")?></div>
		<?else:?>

		<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
			<div class="alert alert-warning"><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></div>
		<?endif?>
	</div>
	<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" class="list no-hairlines-md">
		<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
		<?endif?>
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="REGISTRATION" />
		
		<ul>
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">
						<?=GetMessage("AUTH_NAME")?>
					</div>
					<div class="item-input-wrap">
						<input type="text" name="USER_NAME" value="<?=$arResult["USER_NAME"]?>">
						<span class="input-clear-button"></span>
					</div>
				</div>
			</li>
			
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">
						<?=GetMessage("AUTH_LAST_NAME")?>
					</div>
					<div class="item-input-wrap">
						<input type="text" name="USER_LAST_NAME" value="<?=$arResult["USER_LAST_NAME"]?>">
						<span class="input-clear-button"></span>
					</div>
				</div>
			</li>
			
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">
						<span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_LOGIN_MIN")?>
					</div>
					<div class="item-input-wrap">
						<input type="text" name="USER_LOGIN" value="<?=$arResult["USER_LOGIN"]?>">
						<span class="input-clear-button"></span>
					</div>
				</div>
			</li>
			
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">
						<span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_PASSWORD_REQ")?>
					</div>
					<div class="item-input-wrap">
						<input type="password" name="USER_PASSWORD" value="<?=$arResult["USER_PASSWORD"]?>">
						<span class="input-clear-button"></span>
					</div>
				</div>
			</li>
			
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">
						<span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_CONFIRM")?>
					</div>
					<div class="item-input-wrap">
						<input type="password" name="USER_CONFIRM_PASSWORD" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>">
						<span class="input-clear-button"></span>
					</div>
				</div>
			</li>
			
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">
						<span class="bx-authform-starrequired">*</span> <?=GetMessage("AUTH_EMAIL")?>
					</div>
					<div class="item-input-wrap">
						<input type="text" name="USER_EMAIL" value="<?=$arResult["USER_EMAIL"]?>">
						<span class="input-clear-button"></span>
					</div>
				</div>
			</li>
			
			<?if ($arResult["USE_CAPTCHA"] == "Y"):?>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				
				<li class="item-content item-input">
					<div class="item-inner">
						<div class="item-title item-label">
							<span class="bx-authform-starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>
						</div>
						<div class="item-input-wrap">
							<input type="text" name="captcha_word" autocomplete="off" value="">
							<span class="input-clear-button"></span>
						</div>
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="230" height="48" alt="CAPTCHA" />
					</div>
				</li>
			<?endif?>
			<? /*
			<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
				<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>

					<div class="bx-authform-formgroup-container">
						<div class="bx-authform-label-container"><?if ($arUserField["MANDATORY"]=="Y"):?><span class="bx-authform-starrequired">*</span><?endif?><?=$arUserField["EDIT_FORM_LABEL"]?></div>
						<div class="bx-authform-input-container">
							<?
							$APPLICATION->IncludeComponent(
								"bitrix:system.field.edit",
								$arUserField["USER_TYPE"]["USER_TYPE_ID"],
								array(
									"bVarsFromForm" => $arResult["bVarsFromForm"],
									"arUserField" => $arUserField,
									"form_name" => "bform"
								),
								null,
								array("HIDE_ICONS"=>"Y")
							);
							?>
						</div>
					</div>

				<?endforeach;?>
			<?endif;?>
			*/ ?>
			<div class="block">
				<input type="submit" class="button submit" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" />
			</div>
		</ul>
	</form>

<script type="text/javascript">
document.bform.USER_NAME.focus();
</script>

<?endif?>