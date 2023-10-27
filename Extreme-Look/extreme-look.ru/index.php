<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "extreme, look, материалы, наращивание, ресницы, клей, для, наращивания, резниц");
$APPLICATION->SetTitle("EXTREME LOOK");
global $arSettings;?>
<?//BLOCK_STORIS//

/*if(VERSION == 'mobile' && (MOBILE_OS == 'android' || MOBILE_OS == 'ios')){
    $APPLICATION->IncludeComponent(
        "altop:stories.vlad",
        ".default",
        array(
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "3",
            "CACHE_GROUPS" => "N"
        ),
        false
    );
}*/
?>
<? //MENU_HORISONTAL//
if(VERSION == 'mobile' && (MOBILE_OS == 'android' || MOBILE_OS == 'ios')){
        $APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"horizontal_one_level", 
	array(
		"ROOT_MENU_TYPE" => "more",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_TIME" => "3",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "more",
		"USE_EXT" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"COMPONENT_TEMPLATE" => "horizontal_one_level",
		"DELAY" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_GROUPS" => "N"
	),
	false
);
}
?>
<?//BLOCK_SLIDER//
if(in_array("SLIDER", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_slider.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}

//BLOCK_BANNERS//
if(in_array("BANNERS", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_banners.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}

//BLOCK_ADVANTAGES//
if(in_array("ADVANTAGES", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_advantages.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}

//BLOCK_TABS//
if(in_array("TABS", $arSettings["HOME_PAGE"]["VALUE"])) {
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/owlCarousel/owl.carousel.min.css");?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_tabs.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}

//BLOCK_CATALOG_SECTIONS//
if(in_array("SECTIONS", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_catalog_sections.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}

//BLOCK_BRANDS//
if(in_array("BRANDS", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_brands.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}

//BLOCK_SERVICES//
if(in_array("SERVICES", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_services.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}

//BANNERS STAT PART; BLOG; PROMOTIONS//
if(in_array("PROMOTIONS", $arSettings["HOME_PAGE"]["VALUE"])) {?>
    <div class="content-wrapper">
        <div class="col-xs-12">
            <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => SITE_DIR."include/block_banners_links.php"
                ),
                false,
                array("HIDE_ICONS" => "Y")
            );?>
        </div>
    </div>
<?}


//BLOCK_GALLERY//
if(in_array("GALLERY", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_gallery.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}

//BLOCK_NEWS//
if(in_array("NEWS", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_news.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}

//BLOCK_ARTICLES//
if(in_array("ARTICLES", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_blog.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}?>

<?//BLOCK_VIDEO//?>
<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR."include/block_video.php"
    ),
    false,
    array("HIDE_ICONS" => "Y")
);?>

<?//BLOCK_LOCATION//
if(in_array("LOCATION", $arSettings["HOME_PAGE"]["VALUE"])) {?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_location.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>