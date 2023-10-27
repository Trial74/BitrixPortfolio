<? $linkPage = 'home';
if(isset($_SESSION['prev_page'])){
    $cnt = is_countable($_SESSION['prev_page']) ? count($_SESSION['prev_page']) : 0;
    if($cnt > 0){
        $linkPage = $_SESSION['prev_page'][$cnt-1];
        if($linkPage == $pageName){
            if( isset($_SESSION['prev_page'][$cnt-2]) )
                $linkPage = $_SESSION['prev_page'][$cnt-2];
            else
                $linkPage = 'home';
        }
    }
}?>

<div class="navbar">
	<div class="navbar-inner">
		<div class="left">
			<?if($pageName != 'home' && $pageName != 'payrez'){?>
				<?if(isset($_GET['back-url'])){?>
					<a href="<?=$_GET['back-url']?>" class="link external back-link ripple-color-red">
						<i class="ex-prev-nav"></i>
					</a>
                <?}elseif($pageName == 'personal/order'){?>
                    <a href="/?<?=MOBILE_GET?>=Y&page=<?=$linkPage?>&basket=Y" class="link external back-link ripple-color-red">
                        <i class="ex-prev-nav"></i>
                    </a>
				<?}elseif(isset($_SESSION['external'])){?>
					<a href="/?<?=MOBILE_GET?>=Y&page=<?=$linkPage?>&go-back=Y" class="link external back-link ripple-color-red">
						<i class="ex-prev-nav"></i>
					</a>
				<?}else{?>
					<a href="#" class="link back back-link ripple-color-red">
						<i class="ex-prev-nav"></i>
					</a>
				<?}?>
			<?}?>
		</div>
		<div class="title sliding  <?=isset($_COOKIE['new_version']) ? 'no_search' : ''?>" style="text-align: center;">
			<?=(isset($pageTitles[$pageName]) && !empty($pageTitles[$pageName])) ? $pageTitles[$pageName] : 'Extreme Look'?>
		</div>
		<?if(isset($_COOKIE['new_version']) && !$searchEnabled){?>
				<a class="link searchbar-enable" data-searchbar=" .searchbar_form">
					<i class="fas fa-search"></i>
				</a>
		<?}?>
		<?if($pageName != '404'){?>
			<div class="right">
				<?if($searchEnabled){?>
					<a class="link searchbar-enable" data-searchbar=".page-current .searchbar_<?=$pageHash?>">
						<i class="fas fa-search"></i>
					</a>
				<?}?>
				<?if(in_array($pageName, SHARED_PAGES)){?>
                    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/ya-share.css?<?=time()?>">
					<a href="javascript: void(0);" class="link icon-only ripple-color-red share-link" data-page="<?=$pageName?>" data-share="<?=$shareText?>">
						<i class="icon fas fa-share-alt"></i>
					</a>
                    <div id="ex-share-app-<?=$pageName?>" style="display: none">
                        <div class="navigation-share-content">
                            <div class="navigation-share-content-block">
                                <div class="ya-share2 ya-share2_inited" data-copy="extraItem" data-services="vkontakte,facebook,odnoklassniki,viber,whatsapp,telegram">
                                    <div class="ya-share2__container ya-share2__container_size_m ya-share2__container_color-scheme_normal ya-share2__container_shape_normal">
                                        <ul class="ya-share2__list ya-share2__list_direction_horizontal">
                                            <li class="ya-share2__item ya-share2__item_service_vkontakte"><a class="ya-share2__link open-other-link" data-open="https://vk.com/share.php?url=<?=$shareText?>&utm_source=share2" href="javascript:void(0);" title="ВКонтакте"><span class="ya-share2__badge"><span class="ya-share2__icon"></span></span><span class="ya-share2__title">ВКонтакте</span></a></li>
                                            <li
                                                    class="ya-share2__item ya-share2__item_service_facebook"><a class="ya-share2__link open-other-link" data-open="https://www.facebook.com/sharer.php?src=sp&amp;u=<?=$shareText?>&utm_source=share2" href="javascript:void(0);" title="Facebook"><span class="ya-share2__badge"><span class="ya-share2__icon"></span></span><span class="ya-share2__title">Facebook</span></a></li>
                                            <li
                                                    class="ya-share2__item ya-share2__item_service_odnoklassniki"><a class="ya-share2__link open-other-link" data-open="https://connect.ok.ru/offer?url=<?=$shareText?>&utm_source=share2" href="javascript:void(0);" title="Одноклассники"><span class="ya-share2__badge"><span class="ya-share2__icon"></span></span><span class="ya-share2__title">Одноклассники</span></a></li>
                                            <li
                                                    class="ya-share2__item ya-share2__item_service_viber"><a class="ya-share2__link open-other-link" data-open="viber://forward?text=<?=$shareText?>&utm_source=share2" href="javascript:void(0);" title="Viber"><span class="ya-share2__badge"><span class="ya-share2__icon"></span></span><span class="ya-share2__title">Viber</span></a></li>
                                            <li
                                                    class="ya-share2__item ya-share2__item_service_whatsapp"><a class="ya-share2__link open-other-link" data-open="https://api.whatsapp.com/send?text=<?=$shareText?>&utm_source=share2" href="javascript:void(0);" title="WhatsApp"><span class="ya-share2__badge"><span class="ya-share2__icon"></span></span><span class="ya-share2__title">WhatsApp</span></a></li>
                                            <li
                                                    class="ya-share2__item ya-share2__item_service_telegram"><a class="ya-share2__link open-other-link" data-open="https://t.me/share/url?url=<?=$shareText?>&utm_source=share2" href="javascript:void(0);" title="Telegram"><span class="ya-share2__badge"><span class="ya-share2__icon"></span></span><span class="ya-share2__title">Telegram</span></a></li>
                                            <li class="ya-share2__item ya-share2__item_copy">
                                                <a class="ya-share2__link ya-share2__link_copy" onclick="app.methods.copyShare('', '<?=$shareText?>')" href="javascript:void(0);" title="Скопировать ссылку">
                                                    <span class="ya-share2__badge ya-share2__badge_copy">
                                                        <span class="ya-share2__icon ya-share2__icon_copy"></span>
                                                    </span>
                                                    <span class="ya-share2__title">Скопировать ссылку</span>
                                                </a>
                                                <input class="ya-share2__input_copy" id="share_input_copy" value="<?=$shareText?>">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?}?>
				<?if(!in_array($pageName, ['personal/cart'])){?>
                    <?if(!$USER->IsAuthorized()){?>
                        <a href="#" data-login-screen="#login-screen" class="link login-screen-open panel-close ripple-color-red">
                            <i class="ex-profile-nav"></i>
                        </a>
                        <?}else{?>
                        <a href="/page-personal.index/" class="link ripple-color-red">
                            <i class="ex-profile-nav"></i>
                        </a>
                    <?}?>
                <?}?>
			</div>
			<?if($searchEnabled){?>
				<form class="searchbar searchbar_<?=$pageHash?> searchbar-expandable searchbar-init" data-search-in=".item-title,.search-text" data-search-container=".list">
					<div class="searchbar-inner">
						<div class="searchbar-input-wrap">
							<input class="search-input" type="search" placeholder="Найти">
							<i class="searchbar-icon"></i>
							<span class="input-clear-button"></span>
						</div>
						<span class="searchbar-disable-button">Сброс</span>
					</div>
				</form>
			<?}?>
			<?if(isset($_COOKIE['new_version'])){?>
				<form action="?page=search&extreme-mobile=Y&new_version=Y" method="post" class="searchbar searchbar_form searchbar-expandable searchbar-init">
					<div class="searchbar-inner">
						<div class="searchbar-input-wrap">
							<input class="search-input-form search-from-site" autocomplete="off" name="search" type="text" placeholder="Найти товар">
							<i class="searchbar-icon"></i>
							<span class="input-clear-button"></span>
						</div>
						<span class="searchbar-disable-button">Сброс</span>
					</div>
				</form>
			<?}?>
		<?}?>
	</div>
</div>
