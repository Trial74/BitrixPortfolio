<?
$filter = Array("GROUPS_ID" => [9], '!=WORK_COUNTRY' => false, "!=WORK_CITY" => false);
$rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter, array('SELECT' => ['ID', 'UF_COUNTRY', 'UF_MAP_ADDRESS'])); // выбираем пользователей
$users = [];
while ($arUser = $rsUsers->Fetch()):?>
	<? $users[] = $arUser;
endwhile;?>

<?
$countries = [];
?>
<?foreach($users as $city => $user):?>
	<?
	if ($user['WORK_COUNTRY'] > 0 && $user['WORK_CITY']) {
		foreach ($user['UF_COUNTRY'] as $country) {
			$info = explode('|', $country);
			$country = GetCountryByID(trim($info[0]));
			
			$countries[$country] = $country;
		}
	}
	?>
<?endforeach?>
<?
	asort($countries);
?>

<div class="list">
	<ul>
		<?foreach($countries as $country):?>
			<li>
				<a href="/page-partners/partner-country=<?=$country?>/" class="item-link">
					<div class="item-content">
						<div class="item-inner">
							<div class="item-title">
								<?= $country?>
							</div>
						</div>
					</div>
				</a>
			</li>
		<? endforeach; ?>
	</ul>
</div>