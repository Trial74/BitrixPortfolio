<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$comments = EX_live_comments(['IBLOCK_ID' => 122, 'ACTIVE'=> 'Y', 'PROPERTY_LIVE_ID_POST' => $arResult['ID']]);
$arResult['COUNT_COMMENTS'] = $comments['COUNT_COMMENTS'];
global $USER;
if($comments['COUNT_COMMENTS'] > 0){

    foreach($comments['COMMENTS'] as &$comment){
        $exp = explode(" ", $comment['DATE_CREATE']);
        $comment['DATE_CREATE'] = EX_date_format($exp[0]);
        if($USER->IsAuthorized() && in_array($USER->GetID(), $comment['LIVE_COMMENT_USERS_LIKES']))
            $comment['LIKED'] = true;
        else
            $comment['LIKED'] = false;
    }
}

$arResult['COMMENTS'] = $comments['COMMENTS'];

unset($comment, $comments, $exp);