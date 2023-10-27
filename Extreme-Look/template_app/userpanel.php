<div class="block user-block">
	<div class="row">
		<div class="col-35 avatar-col">
			<? if ( !$USER->IsAuthorized() ): ?>
				<img src="<?=NO_AVATAR?>"/>
			<? else: ?>
				<? if($photo_id = $USER->GetParam('PERSONAL_PHOTO')): ?>
					<img src="<?=CFile::GetPath($photo_id)?>">
				<? else: ?>
					<img src="<?=NO_AVATAR?>"/>
				<? endif; ?>
			<? endif; ?>
		</div>
		<div class="col-65 username-col">
			<? if ( !$USER->IsAuthorized() ): ?>
				<a href="#" data-login-screen="#login-screen" class="link login-screen-open ripple-color-white">
					Авторизация
				</a>
				<br/>
				<a href="#" data-login-screen="#login-screen" class="link login-screen-open ripple-color-white">
					Регистрация
				</a>
			<? else: ?>
				<?=$USER->GetFirstName()?>
				<?=$USER->GetLastName()?>
				<div>
					<br/>
					<a href="#" class="link login-screen-open ripple-color-white">
						Выход
					</a>
				</div>
			<? endif; ?>
		</div>
	</div>
</div>