<?//***НОВЫЕ ПУШ УВЕДОМЛЕНИЯ By Vlad 22.09.2022 (список уведомлений пользователя)***//

$PUSH_TOKEN = isset($_SESSION['PUSH_TOKEN']) ?
    !empty($_SESSION['PUSH_TOKEN']) ?
        $_SESSION['PUSH_TOKEN'] :
        ($USER->IsAuthorized() ?
            (!empty($USER->GetByID($USER->GetID())->fetch()['UF_FIREBASE_TOKEN']) ?
            $USER->GetByID($USER->GetID())->fetch()['UF_FIREBASE_TOKEN'] :
            false) :
        false) :
    false;

$PUSH_USER = $USER->IsAuthorized() ? $USER->GetByID($USER->GetID())->fetch() : false;
$GROUPS_USER = $PUSH_USER ? $USER->GetUserGroup($USER->GetID()) : false;
$FILTER_NOTICE = false;
$PARAM_ACCESS = array( //Параметры доступа к записям относительно групп пользователей
    "ALL"           => 2687,
    "ROZN"          => 2688,
    "OLD_PART"      => 2690,
    "NEW_PART"      => 2691,
    "INDIVIDUALLY"  => 2692,
    "ADMIN"         => 2693
);
$BREAK = false;

if($PUSH_USER){ //Юзер авторизован. Вытаскиваем доступные для него уведомления
    if(!empty($GROUPS_USER)){ //Есть группы пользователя
        $FILTER_NOTICE = pushFilter($PARAM_ACCESS, $GROUPS_USER, $PUSH_TOKEN, true);
    }else{ //Нет групп у пользователя
        $FILTER_NOTICE = pushFilter($PARAM_ACCESS, false, $PUSH_TOKEN, true);
    }
    $res = CIBlockElement::GetList(array('ID' => 'DESC'), array("IBLOCK_ID" => 37, "ACTIVE"=>"Y",  $FILTER_NOTICE), false, false);
}else{ //Юзер не авторизован. Тормозим вывод
    $BREAK = true;
}?>
<div class="block" style="margin: 0">
    <div class="articles">
        <div class="block-title">Ваши уведомления</div>
        <?if(!$BREAK){
            while($article = $res->GetNextElement()){
                $article = $article->GetFields();

                $OBprops = CIBlockElement::GetProperty(37, $article['ID'], array("sort" => "asc"), Array("CODE" => "USERS_VIEWED"));

                while ($valUs = $OBprops->GetNext())
                     $ViewedUsers[] = $valUs['VALUE'];?>

                <div class="card <?=in_array($PUSH_USER['ID'], $ViewedUsers) ? "see" : "noSee"?>" data-id="<?=$article['ID']?>">
                    <div class="card-content" style="height: 90px; overflow: hidden;">
                        <div class="card-title">
                            <div class="date-im"><?=date("d.m.Y H:i", strtotime($article['DATE_CREATE']))?></div>
                            <div class="name-im"><?=$article['NAME']?></div>
                        </div>
                        <div class="prev-im"><?=$article['PREVIEW_TEXT']?></div>
                    </div>
                    <div class="card-footer">
                        <a href="?page=notice/detail&im_id=<?=$article['ID']?>" data-id="<?=$article['ID']?>" class="btn btn-buy by-notification"><span>Прочитать</span></a>
                    </div>
                </div>
                <?unset($ViewedUsers);?>
            <?}?>
        <?}?>
    </div>
</div>