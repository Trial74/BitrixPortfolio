<? if (!$USER->IsAuthorized()): ?>
	<? $APPLICATION->IncludeComponent(
	'bitrix:system.auth.registration',
	'',
	[
		'AUTH_RESULT' => $APPLICATION->arAuthResult
	]
	); ?>
<? else: ?>
	<div class="block">
		<div class="block-footer text-align-center">
			Вы успешно зарегистрировались
		</div>
		<a href="/page-home/" data-view=".view-main" class="button login-screen-close">
			На главную
		</a>
	</div>
<? endif; ?>