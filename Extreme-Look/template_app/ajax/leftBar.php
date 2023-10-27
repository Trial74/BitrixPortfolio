<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once( $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH .  '/config.php');
$new_version = "&new_version=Y";
global $USER;
$countNoReadMessage = 0;
CModule::IncludeModule("iblock");?>

<div class="page">
    <div class="page-content" >
        <div class="block user-block user-in-menu-custom-vlad">
            <div class="row">
                <?if($USER->IsAuthorized()){?>
                    <div class="col-35">
                        <div class="avatar-user">
                            <style>
                               .avatar-user{
                                   position: relative;
                               }
                               .avatar-user > img{
                                   display: block;
                                   margin: 0 auto;
                               }
                               .im-badge{
                                   position: absolute;
                                   right: 5px;
                                   top: 0;
                               }
                            </style>
                            <?if($USER->IsAuthorized() && $USER->GetParam('PERSONAL_PHOTO')){?>
                                <?=CFile::ShowImage($USER->GetParam('PERSONAL_PHOTO'), 50, 50, "style=border-radius:100px", "", false)?>
                                <a href="/?page=notice/list&<?=MOBILE_GET?>=Y&time=<?=time()?>" data-view=".view-main" class="im-badge panel-close ripple-color-white">
                                    <?if($countNoReadMessage > 0){?>
                                        <span class="badge color-extr"><?= $countNoReadMessage?></span>
                                    <?}else{?>
                                        <span class="badge color-gray"><?= $countNoReadMessage?></span>
                                    <?}?>
                                </a>
                            <?}elseif(!$USER->GetParam('PERSONAL_PHOTO') && $USER->IsAuthorized()){?>
                                <img src="/upload/medialibrary/42d/no-avatar-user-app.png" width="50px" height="50px" alt="Нет аватара">
                                <a href="/?page=notice/list&<?=MOBILE_GET?>=Y&time=<?=time()?>" data-view=".view-main" class="im-badge panel-close ripple-color-white">
                                    <?if($countNoReadMessage > 0){?>
                                        <span class="badge color-extr"><?= $countNoReadMessage?></span>
                                    <?}else{?>
                                        <span class="badge color-gray"><?= $countNoReadMessage?></span>
                                    <?}?>
                                </a>
                            <?}?>
                        </div>
                    </div>
                <?}?>
                <div class="col-65 username-col" <?=!$USER->IsAuthorized() ? 'style="width:100%;"' : ''?>>
                    <?if(!$USER->IsAuthorized()){?>
                        <div class="username no-login">Вы вошли<br />как гость!</div>
                        <a href="#" data-login-screen="#login-screen" class="link login-screen-open login-extr">Авторизоваться<svg xmlns="http://www.w3.org/2000/svg" width="8px" height="13px" viewBox="0 0 8 13"><polygon fill="white" transform="translate(1.500000, 6.500000) rotate(-45.000000) translate(-1.500000, -6.500000)" points="6 11 6 2 4 2 4 9 -3 9 -3 11 5 11"/></svg></a>
                        <?/*
                        <br/>
                        <a href="#" data-login-screen="#login-screen" class="link login-screen-open ripple-color-white">
                          Регистрация
                        </a>
                        */?>
                    <?}else{?>
                        <div class="username"><?=$USER->GetFirstName()?></div>
                        <a href="/page-personal.index/" class="link panel-close"><svg xmlns="http://www.w3.org/2000/svg" width="8px" height="13px" viewBox="0 0 8 13"><polygon fill="white" transform="translate(1.500000, 6.500000) rotate(-45.000000) translate(-1.500000, -6.500000)" points="6 11 6 2 4 2 4 9 -3 9 -3 11 5 11"/></svg></a>
                    <?}?>
                </div>
            </div>
        </div>
        <div class="list links-list">
            <ul>
                <li>
                    <a href="/page-home/?g=1<?=$new_version?>" data-view=".view-main" data-name="home" class="panel-close ripple-color-white">
                        <span>Главная</span>
                    </a>
                </li>
                <?if($USER->IsAuthorized()){?>
                    <li>
                        <a href="/?page=personal/orders&<?=MOBILE_GET?>=Y" data-view=".view-main" data-name="personal/orders" class="panel-close ripple-color-white">
                            <span>Мои Заказы</span>
                        </a>
                    </li><!--!$USER->IsAdmin() &&-->
                    <?if($USER->GetId() == 10354){?>
                        <li>
                            <a href="/?page=test&<?=MOBILE_GET?>=Y&basket=Y" data-view=".view-main" data-name="test" class="panel-close ripple-color-white">
                                <span>Тестовая страница</span>
                            </a>
                        </li>
                        <li>
                            <a href="/?page=redirect" data-view=".view-main" data-name="redirect" class="panel-close ripple-color-white">
                                <span>Редирект</span>
                            </a>
                        </li>
                    <?}?>
                    <?if(getNewPartner()){?>
                        <li>
                            <a href="/?page=partner&<?=MOBILE_GET?>=Y" data-view=".view-main" data-name="test" class="panel-close ripple-color-white">
                                <span>Кабинет партнёра</span>
                            </a>
                        </li>
                    <?}?>
                <?}?>
                <li>
                    <a href="/page-catalog.index/" data-view=".view-main" data-name="catalog/index" class="panel-close ripple-color-white">
                        <span>Каталог</span>
                    </a>
                </li>
                <li>
                    <a href="/page-delivery/" data-view=".view-main" data-name="delivery" class="panel-close ripple-color-white">
                        <span>Доставка</span>
                    </a>
                </li>
                <li>
                    <a href="/?page=stat-partner&<?=MOBILE_GET?>=Y" data-view=".view-main" data-name="stat-partner" class=" panel-close ripple-color-white">
                        <span>Стать партнёром</span>
                    </a>
                </li>
                <li>
                    <a href="/page-wholesale/?n=1<?=$new_version?>" data-view=".view-main" data-name="wholesale" class="panel-close ripple-color-white">
                        <span>Оптовые цены</span>
                    </a>
                </li>
                <li>
                    <a href="/page-contacts/" data-view=".view-main" data-name="contacts" class="panel-close ripple-color-white">
                        <span>Контакты</span>
                    </a>
                </li>
                <li>
                    <a href="/page-error_report/" data-view=".view-main" data-name="error_report" class="panel-close ripple-color-white">
                        <span>Сообщить об ошибке</span>
                    </a>
                </li>
                <?if($USER->IsAuthorized() && !isset($_COOKIE['new_version'])){?>
                    <li>
                        <a href="#" class="logout-link panel-close ripple-color-white">
                            <span>Выход</span>
                        </a>
                    </li>
                <?}?>
            </ul>
        </div>
        <div class="block-flex-left-bar">
            <div id="version-app"></div>
            <div class="top_icon_soc block user-block user-in-menu-custom-vlad">
                <span>Social</span>
                <a class="open-other-link" href="javascript: void(0)" data-open="https://vk.com/extreme_look">
                  <img src="<?=SITE_TEMPLATE_PATH?>/images/icons/Vkonatkte_ex.png" />
                </a>
                <a class="open-other-link" href="javascript: void(0)" data-open="https://www.instagram.com/extreme_look.ru/">
                  <img src="<?=SITE_TEMPLATE_PATH?>/images/icons/Instagram_ex.png" />
                </a>
                <a class="open-other-link" href="javascript: void(0)" data-open="https://facebook.com/extremelookofficial">
                  <img src="<?=SITE_TEMPLATE_PATH?>/images/icons/Facebook_ex.png" />
                </a>
                <a class="open-other-link" href="javascript: void(0)" data-open="https://www.youtube.com/user/Nitrogirll">
                  <img src="<?=SITE_TEMPLATE_PATH?>/images/icons/youtube_new_ex.png" />
                </a>
            </div>
        </div>
    </div>
</div>