<?
	define('AJAX_REQUEST', (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 'Y'));
	#core
	const MOBILE_GET = 'extreme-mobile';
	const CACHE_TYPE = 'A';
	const CACHE_TIME = '86400';
	
	#catalog
	const CATALOG_IBLOCK = 23;
	const PRODUCTS_PER_PAGE = 25;
	const SERVICE_FEE_ID = 6987;
	const MIN_SUMM_PARTNER_CONST = 25000;

	#promotions
    const PROMOTIONS_IBLOCK = 65;

    #blog
    const BLOG_IBLOCK = 64;
	
	#images
	const WATERMARK_PATH = SITE_TEMPLATE_PATH . '/images/watermark.png';
	const NOIMAGE_PATH = SITE_TEMPLATE_PATH . '/images/empty.png';
    const NOIMAGE_SMALL_PATH = SITE_TEMPLATE_PATH . '/images/empty_small.png';
	const NO_AVATAR = SITE_TEMPLATE_PATH . '/images/no-avatar.png';

	#shared pages
    const SHARED_PAGES = array(
        0 => 'catalog/element',
        1 => 'catalog/section',
        2 => 'catalog/index',
        3 => 'stat-partner',
        4 => 'delivery',
        5 => 'wholesale',
        6 => 'contacts'
    );
	
	#branding
	const BROWSER_TITLE = 'Товары для LASH-мастера EXTREME LOOK';
		
	function modifyUrl($params = [], $clear = true){
		$url = '/';
		$default = [
			MOBILE_GET 	=> 'Y'
		];
		$_exploded = explode('&', $_SERVER['QUERY_STRING']);
		foreach($_exploded as $exp){
			$exp = explode('=', $exp);
			$default[$exp[0]] = $exp[1];
		}
		if(!isset($default['page']))
			$default['page'] = 'home';
		if($clear){
			$default = [
				'page'		=> $default['page'],
				MOBILE_GET 	=> 'Y'
			];
		}
		$params = array_merge($default, $params);
		foreach($params as $key => $val) {
			$url .= strlen($url) == 1 ? '?' : '&';
			$url .= $key . '=' . $val;
		}
		return $url;
	}

	function pushFilter($PARAM_ACCESS, $GROUPS_USER, $PUSH_TOKEN, $AUTH = false){
	    if($AUTH){
            global $USER;
            $DEFAULT_GROUPS = array(
                'ROZN'      => ROZN,
                'OLD_PART'  => OLD_PART,
                'NEW_PART'  => NEW_PART
            );

            $resultFilter = array(
                "LOGIC" => "OR"
            );

            if($PUSH_TOKEN)
                $resultFilter[] = array("PROPERTY_PUSH_TOKEN" => $PUSH_TOKEN, "PROPERTY_PUSH_USER" => $USER->GetID());

            $resultFilter["PROPERTY_PUSH_USER"] = $USER->GetID();
            array_push($resultFilter, array("PROPERTY_GROUPS_USERS" => 2687));

            if($USER->IsAdmin())
                array_push($resultFilter, array("PROPERTY_GROUPS_USERS" => 2693));

            if(!empty($GROUPS_USER) && $AUTH){
                foreach ($DEFAULT_GROUPS as $key => $group) {
                    if (!empty(array_intersect($GROUPS_USER, $group)))
                        array_push($resultFilter, array("PROPERTY_GROUPS_USERS" => $PARAM_ACCESS[$key]));
                }
            }
            return $resultFilter;
        }else return false;
    }
?>