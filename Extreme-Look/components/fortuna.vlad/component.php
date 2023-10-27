<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $USER;
if($this->StartResultCache(false, $userAuth = $USER->IsAuthorized())) {
    if (!$userAuth) {
        $arResult['isAuth'] = false;
    }else {
        $arResult['isAuth'] = true;
        $this->abortResultCache();
    }
    $this->IncludeComponentTemplate();
}?>