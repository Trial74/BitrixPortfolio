<?require_once($_SERVER["DOCUMENT_ROOT"] . "/partners/functions_partner.php");

$filter = Array("GROUPS_ID" => [9,11,12,13], "UF_PAGE_PART" => true);
$rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter, array('SELECT' => ['ID', 'UF_COUNTRY', 'UF_SITY_PAT', 'UF_MAP_ADDRESS', 'UF_PAGE_PART', 'UF_DOP_INFO'])); // выбираем пользователей
$users = [];
while ($arUser = $rsUsers->Fetch()):?>
	<? $users[] = $arUser;
endwhile;?>

<?
$countries = [];
?>
<?foreach($users as $city => $user):?>
	<?

	 $country = GetCountryByID(codeCountry($user['UF_COUNTRY'][0]));
	 $countries[$country] = $country;

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
	ksort($countries);
foreach($countries as $c => $arrRus){
    if($c == 'Россия'){
        $arrRussian['Россия'] = 'Россия';
        //unset($countries[$c]);
        array_shift($countries);
        $countries = array_merge($arrRussian, $countries);
    }
}
?>
<style>
    .slider.sl_m{
        font-size: 20px;
        cursor: pointer;
        margin-bottom: 20px;
        display: flex;
        border: 1px solid #9788ff;
        border-radius: 7px;
        background: #f1eeff;
        padding: 5px;
    }
    .country-name{
        height: 20px;
        width: 100px;
        transform: translate(0, 100%);
        text-align: center;
        font-weight: bold;
        color: black;
    }
    @media (max-width: 412px){
        .country-name {
            width: 80px;
            transform: translate(0, 36%);
        }
        .slider.sl_m img{
            width: 30%;
            height: 30%;

        }
    }
</style>
<div class="block">
	<ul class="list partners-flags row">
		<?foreach($countries as $key => $country){?>
			<?if(strlen($country)){?>
            <a href="/page-partners/partner-country=<?=$country?>/">
                <div class="slider sl_m">
                    <img src='/partners/country/<?=$key == 'Арабские Эмираты' ? 'ОАЭ' : $country?>.png'>
                    <div class="country-name" <?=$key == 'Азербайджан' ? 'style="font-size: 16px;margin-top: 4px;"' : ''?>><?=$key == 'Арабские Эмираты' ? 'ОАЭ' : $country?></div>
                </div>
            </a>
			<?}?>
		<?}?>
	</ul>
</div>
