<?
CModule::IncludeModule("iblock");
$res = CIBlockElement::GetList(array('ID' => 'DESC'), array("IBLOCK_ID" => 37, "ID" => $_GET['im_id'], "ACTIVE"=>"Y"), false, false);
$arRes = $res->fetch();
prent_r($arRes);?>

<div class="block">
    <div class="articles">
        <div class="card see" data-id="<?=$arRes['ID']?>">
            <div class="card-content">
                <div class="card-title">
                    <div class="date-im"><?=date("d.m.Y H:i", strtotime($arRes['DATE_CREATE']))?></div>
                    <div class="name-im"><?=$arRes['NAME']?></div>
                </div>
                <div class="prev-im"><?=$arRes['PREVIEW_TEXT']?></div>
                <?if(!empty($arRes['PREVIEW_PICTURE'])){?>
                    <div class="prev-im-picture-block">
                        <img class="prev-im-picture" src="<?=CFile::GetPath($arRes['PREVIEW_PICTURE'])?>">
                    </div>
                <?}?>
            </div>
            <div class="card-footer">
                <a href="?page=notice/list" style="display: block" data-id="<?=$arRes['ID']?>" class="btn btn-buy by-notification"><span>Назад</span></a>
            </div>
        </div>
    </div>
</div>

<?$PUSH_USER = $USER->IsAuthorized() ? $USER->GetByID($USER->GetID())->fetch() : false;

if(!$PUSH_USER) {
/*    $PUSH_TOKEN = !!! РАЗОБРАТЬСЯ С ЭТИМ ДЕРЬМОМ!
        (isset($_SESSION['PUSH_TOKEN']) && !empty($_SESSION['PUSH_TOKEN'])) ? $_SESSION['PUSH_TOKEN'] :
            ($USER->IsAuthorized() && !empty($USER->GetByID($USER->GetID())->fetch()['UF_FIREBASE_TOKEN'])) ?
                $USER->GetByID($USER->GetID())->fetch()['UF_FIREBASE_TOKEN'] :
                false;*/

    if(isset($_SESSION['PUSH_TOKEN']) && !empty($_SESSION['PUSH_TOKEN'])){
        $PUSH_TOKEN = $_SESSION['PUSH_TOKEN'];
    }else{
        if($USER->IsAuthorized() && !empty($USER->GetByID($USER->GetID())->fetch()['UF_FIREBASE_TOKEN'])){
            $PUSH_TOKEN = $USER->GetByID($USER->GetID())->fetch()['UF_FIREBASE_TOKEN'];
        }else{
            $PUSH_TOKEN = false;
        }
    }

    if($PUSH_TOKEN) {
        $filter = Array
        (
            "UF_FIREBASE_TOKEN" => $PUSH_TOKEN,
        );
        $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $filter);
        $resUser = $rsUsers->fetch();
    }
}else $resUser = array('ID' => $PUSH_USER['ID']);

if($resUser){
    $OBprops = CIBlockElement::GetProperty(37, $_GET['im_id'], array("sort" => "asc"), Array("CODE"=>"USERS_VIEWED"));
    while ($valUs = $OBprops->GetNext())
    {
        $ViewedUsers[] = $valUs['VALUE'];
    }
    if(!in_array($resUser['ID'], $ViewedUsers)) {
        array_push($ViewedUsers, $resUser['ID']);
        $ELEMENT_ID = $_GET['im_id'];
        $PROPERTY_CODE = "USERS_VIEWED";
        $PROPERTY_VALUE = $ViewedUsers;
        CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, 37, array($PROPERTY_CODE => $PROPERTY_VALUE));
    }
}
?>