<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

if($arResult["ERROR_MESSAGE"])
	ShowError($arResult["ERROR_MESSAGE"]);

$arServices = $arResult["AUTH_SERVICES_ICONS"];

if(!empty($arResult["AUTH_SERVICES"])) {?>
	<div class="soc-serv-main">
		<div class="soc-serv-title"><?=Loc::getMessage("SS_GET_COMPONENT_INFO")?></div>
		<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "flat",
			array(
				"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
				"CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
				"AUTH_URL" => $arResult["CURRENTURL"],
				"POST" => $arResult["POST"],
				"SHOW_TITLES" => "N",
				"FOR_SPLIT" => "Y",
				"AUTH_LINE" => "N",
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?>
<?}
if(isset($arResult["DB_SOCSERV_USER"]) && $arParams["SHOW_PROFILES"] != "N") {?>
	<div class="soc-serv-title-acc"><?=Loc::getMessage("SS_YOUR_ACCOUNTS")?></div>
	<div class="soc-serv-accounts">
		<table cellspacing="0" cellpadding="8">
			<?foreach($arResult["DB_SOCSERV_USER"] as $key => $arUser) {
				if(!$icon = htmlspecialcharsbx($arResult["AUTH_SERVICES_ICONS"][$arUser["EXTERNAL_AUTH_ID"]]["ICON"]))
					$icon = "openid";
				$authID = ($arServices[$arUser["EXTERNAL_AUTH_ID"]]["NAME"])? $arServices[$arUser["EXTERNAL_AUTH_ID"]]["NAME"]: $arUser["EXTERNAL_AUTH_ID"];?>
				<tr class="soc-serv-personal">
					<td class="bx-ss-icons">
						<i class="bx-ss-icon <?=$icon?>">&nbsp;</i>
						<?if($arUser["PERSONAL_LINK"] != "") {?>
							<a class="soc-serv-link" target="_blank" href="<?=$arUser['PERSONAL_LINK']?>"><?=$authID?></a>
						<?}?>
					</td>
					<td class="soc-serv-name"><?=$arUser["VIEW_NAME"]?></td>
					<td class="split-item-actions">
						<?if(in_array($arUser["ID"], $arResult["ALLOW_DELETE_ID"])) {?>
							<a class="split-delete-item" href="<?=htmlspecialcharsbx($arUser['DELETE_LINK'])?>" onclick="return confirm('<?=Loc::getMessage('SS_PROFILE_DELETE_CONFIRM')?>')" title="<?=Loc::getMessage('SS_DELETE')?>"></a>
						<?}?>
					</td>
				</tr>
			<?}?>
		</table>
	</div>
<?}
if(!empty($arResult["AUTH_SERVICES"])) {?>
	</div>
<?}?>