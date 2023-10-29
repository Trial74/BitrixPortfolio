<?

namespace VladClasses;


class UriPartClassRoute
{
    public static function sortCountry($users){
        $Nodubl = array();
        $resultArray = array();
        foreach ($users as $key => $sort){
            if (count($sort['UF_COUNTRY']) > 1) {
                foreach ($sort['UF_COUNTRY'] as $keyDu => $D) {
                    if (in_array($D, $Nodubl)) {
                        continue;
                    } else {
                        array_push($Nodubl, $D);
                        array_push($resultArray, array('COUNTRY' => $D, "COUNTRY_U" => $sort['UF_COUNTRY_URL']));
                    }
                }
            }
            else{
                if (in_array($sort['UF_COUNTRY'][0], $Nodubl)) {
                    continue;
                } else {
                    array_push($Nodubl, $sort['UF_COUNTRY'][0]);
                    array_push($resultArray, array('COUNTRY' => $sort['UF_COUNTRY'][0], "COUNTRY_U" => $sort['UF_COUNTRY_URL'][0]));
                }
            }
        }

        $resArr = array_combine($Nodubl, $resultArray);
        asort($resArr);

        if (array_key_exists('Россия', $resArr)) {
            $arRus = $resArr['Россия'];
            unset($resArr['Россия']);
            array_unshift($resArr, $arRus);
            $resArr = array_values($resArr);
        }

        return $resArr;
    }
    public static function sortSityes($users){
        $Nodubl = array();
        $resultArray = array();
        foreach ($users as $key => $sort){
            if (count($sort['UF_SITY_PAT']) > 1) {
                foreach ($sort['UF_SITY_PAT'] as $keyDu => $D) {
                    if (in_array($D, $Nodubl)) {
                        continue;
                    } else {
                        array_push($Nodubl, $D);
                        array_push($resultArray, array('SITY' => $D, "SITY_U" => $sort['UF_SITY_URL'][$keyDu]));
                    }
                }
            }
            else{
                if (in_array($sort['UF_SITY_PAT'][0], $Nodubl)) {
                    continue;
                } else {
                    array_push($Nodubl, $sort['UF_SITY_PAT'][0]);
                    array_push($resultArray, array('SITY' => $sort['UF_SITY_PAT'][0], "SITY_U" => $sort['UF_SITY_URL'][0]));
                }
            }
        }

        $resArr = array_combine($Nodubl, $resultArray);
        asort($resArr);

        $resArr = array_values($resArr);

        return $resArr;
    }

    public static function sortPartners($users, $sity){
        $resultsArrUs = array();
        foreach($users as $key => $arUsers){
            if(count($arUsers["UF_SITY_URL"]) > 1){
                foreach($arUsers["UF_SITY_URL"] as $keyS => $valSit){
                    if($valSit !== $sity){
                       unset(
                           $arUsers["UF_MAP_ADDRESS"][$keyS],
                           $arUsers["UF_DOP_INFO"][$keyS],
                           $arUsers["UF_SITY_PAT"][$keyS],
                           $arUsers["UF_SITY_URL"][$keyS],

                       );
                    }
                }
                $arUsers["UF_MAP_ADDRESS"] = array_values($arUsers["UF_MAP_ADDRESS"]);
                $arUsers["UF_DOP_INFO"] = array_values($arUsers["UF_DOP_INFO"]);
                $arUsers["UF_SITY_PAT"] = array_values($arUsers["UF_SITY_PAT"]);
                $arUsers["UF_SITY_URL"] = array_values($arUsers["UF_SITY_URL"]);
            }
            array_push($resultsArrUs,
                array(
                "ID" => $arUsers["ID"],
                "WORK_COMPANY" => $arUsers["WORK_COMPANY"],
                "UF_MAP_ADDRESS" => $arUsers["UF_MAP_ADDRESS"],
                "UF_DOP_INFO" => $arUsers["UF_DOP_INFO"],
                "UF_SITY_PAT" => $arUsers["UF_SITY_PAT"],
                "UF_SITY_URL" => $arUsers["UF_SITY_URL"]
            ));
        }
        return $resultsArrUs;
    }

    public static function cUrl($value)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }
    public static function cUrlc($url)
    {
        $arUrl = explode('/', parse_url($url, PHP_URL_PATH));
        array_shift($arUrl);
        array_shift($arUrl);
        $arUrl = array_values($arUrl);
        foreach ($arUrl as $key => &$value) {
            if (isset($value) && empty($value)) {
                unset($arUrl[$key]);
            }
        }
        unset($value);
        return $result = array(
            'count' => count($arUrl),
            'arPath' => $arUrl
        );
    }
}