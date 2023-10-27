<div class="tabs-wrap">
	<div class="tabs__list" data-entity="main-tabs">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="tabs__scroll">
						<ul class="tabs__tabs">
                            <li class="tabs__tab" data-entity="tab" data-value="hit">Бестселлер</li>
                            <li class="tabs__tab" data-entity="tab" data-value="recomend">Эксклюзивно в EXTREME LOOK</li>
                            <li class="tabs__tab" data-entity="tab" data-value="new">Новинки</li>
                            <li class="tabs__tab" data-entity="tab" data-value="lash_outlet">LASH OUTLET</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabs__content" data-entity="main-tabs-content">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
                    <div class="tabs__box" data-entity="tab-content" data-value="recomend">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/tabs_recomend.php",
                                "AREA_FILE_RECURSIVE" => "N",
                                "EDIT_MODE" => "html",
                            ),
                            false,
                            array("HIDE_ICONS" => "Y")
                        );?>
                    </div>
                    <div class="tabs__box" data-entity="tab-content" data-value="hit">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/tabs_hit.php",
                                "AREA_FILE_RECURSIVE" => "N",
                                "EDIT_MODE" => "html",
                            ),
                            false,
                            array("HIDE_ICONS" => "Y")
                        );?>
                    </div>
                    <div class="tabs__box" data-entity="tab-content" data-value="new">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/tabs_new.php",
                                "AREA_FILE_RECURSIVE" => "N",
                                "EDIT_MODE" => "html",
                            ),
                            false,
                            array("HIDE_ICONS" => "Y")
                        );?>
                    </div>
                    <div class="tabs__box" data-entity="tab-content" data-value="lash_outlet">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/tabs_lash_outlet.php",
                                "AREA_FILE_RECURSIVE" => "N",
                                "EDIT_MODE" => "html",
                            ),
                            false,
                            array("HIDE_ICONS" => "Y")
                        );?>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>