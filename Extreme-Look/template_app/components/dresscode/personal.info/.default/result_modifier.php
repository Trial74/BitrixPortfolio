<?
$arResult['USER']['PERSONAL_PHOTO_INPUT'] = CFile::InputFile("PERSONAL_PHOTO", 20, $arResult["USER"]["PERSONAL_PHOTO"], false, 0, "IMAGE");
$arResult["USER"]["PERSONAL_PHOTO_HTML"] = CFile::ShowImage($arResult["USER"]["PERSONAL_PHOTO"], 100, 100, "border=0", "", true);

?>