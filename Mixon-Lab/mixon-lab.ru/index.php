<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $arSettings;?>
<?//BLOCK_FIRST//
$APPLICATION->IncludeComponent("bitrix:main.include", "block_first",
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/mix_first_block.php"
	),
	false,
	array("HIDE_ICONS" => "Y")
);?>
<?//SECOND_BLOCK//
$APPLICATION->IncludeComponent("bitrix:main.include", "block_second",
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/mix_second_block.php"
	),
	false,
	array("HIDE_ICONS" => "Y")
);?>
<?//THIRD_BLOCK//
$APPLICATION->IncludeComponent("bitrix:main.include", "block_third",
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/mix_third_block.php"
	),
	false,
	array("HIDE_ICONS" => "Y")
);?>
<?//FOURTH_BLOCK//
$APPLICATION->IncludeComponent("bitrix:main.include", "block_fourth",
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/mix_fourth_block.php"
	),
	false,
	array("HIDE_ICONS" => "Y")
);?>
<?//FOURTH_BLOCK//
$APPLICATION->IncludeComponent("bitrix:main.include", "block_fifth",
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/mix_fifth_block.php"
	),
	false,
	array("HIDE_ICONS" => "Y")
);?>
<?//FOURTH_BLOCK//
$APPLICATION->IncludeComponent("bitrix:main.include", "block_sixth",
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/mix_sixth_block.php"
	),
	false,
	array("HIDE_ICONS" => "Y")
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>