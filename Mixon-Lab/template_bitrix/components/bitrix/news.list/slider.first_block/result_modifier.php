<?php
foreach($arResult['ITEMS'] as &$item){
    foreach ($item['PROPERTIES'] as $key => &$property){
        switch ($key){
            case 'PICTURE':
                if(!empty($property['VALUE']))
                    $path = CFile::GetPath($property['VALUE']);
                else
                    $path = 'N';
                $property['VALUE'] = $path;
                unset($path);
            break;
            case 'PICTURE_MOBILE':
                if(!empty($property['VALUE']))
                    $path = CFile::GetPath($property['VALUE']);
                else
                    $path = 'N';
                $property['VALUE'] = $path;
                unset($path);
            break;
            case 'LINK':
                if(empty($property['VALUE']))
                    $property['VALUE'] = 'N';
                unset($link);
                break;
        }
    }
}
unset($key);