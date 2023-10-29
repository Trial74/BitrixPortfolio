<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
			if(!$isSiteClosed) {
				if(!CSite::inDir(SITE_DIR."index.php")) {?>
								</div>
							</div>
						</div>
					</div>
					<?
				}
			}?>
			<div class="footer-wrapper">
				<div class="container-fluid">
                    <div class="footer mix-flex container-lg hidden-sm">
                        <div class="mix-flex mix-footer-block">
                            <div class="mix-flex first-footer-block">
                                <div class="mix-footer-logo">
                                    <a href="<?=SITE_DIR?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/logo/mixon-logo.png" /></a>
                                </div>
                                <div class="mix-copyright">
                                    MIXON © 2023<br />
                                    Производитель средств для ресниц и бровей<br />
                                    Все права защищены
                                </div>
                            </div>
                            <div class="mix-footer-menu">
                                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR."include/footer_menu.php"
                                    ),
                                    false,
                                    array("HIDE_ICONS" => "Y")
                                );?>
                            </div>
                            <div class="mix-footer-menu-second">
                                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR."include/footer_menu_second.php"
                                    ),
                                    false,
                                    array("HIDE_ICONS" => "Y")
                                );?>
                            </div>
                            <div class="mix-flex mix-footer-contact">
                                <div class="mix-footer-tel"><a href="#"></a></div>
                                <div class="mix-footer-mail"><a href="mailto:info@mixon-lab.ru">info@mixon-lab.ru</a></div>
                                <div class="mix-footer-conf"><a href="agreement/?id=2">Политика конфиденциальности</a></div>
                            </div>
                            <div class="mix-social-links">
                                <div class="mix-flex mix-messendger">
                                    <div class="mix-mes-whatsapp p-10"><a target="_blank" href="https://wa.me/79227421468"></a></div>
                                    <div class="mix-mes-telegram p-10"><a target="_blank" href="https://t.me/mixon_manager"></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer mix-flex mix-mw hidden-xs">
                        <div class="mix-flex mix-footer-block">
                            <div class="mix-flex first-footer-block">
                                <div class="mix-footer-logo">
                                    <a href="<?=SITE_DIR?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/logo/mixon-logo.png" /></a>
                                </div>
                                <div class="mix-copyright">
                                    MIXON © 2022<br />
                                    Производитель средств для ресниц и бровей<br />
                                    Все права защищены
                                </div>
                            </div>
                            <div class="mix-flex mix-footer-contact">
                                <div class="mix-flex mix-messendger">
                                    <div class="mix-mes-whatsapp"><a target="_blank" href="https://wa.me/79227421468"></a></div>
                                    <div class="mix-mes-telegram p-10"><a target="_blank" href="https://t.me/mixon_manager"></a></div>
                                </div>
                                <div class="mix-footer-tel"><a href="#"></a></div>
                                <div class="mix-footer-mail"><a href="mailto:info@mixon.com">info@mixon.com</a></div>
                            </div>
                        </div>
                        <div class="mix-footer-conf"><a href="agreement/?id=2">Политика конфиденциальности</a></div>
                    </div>
                </div>
            </div>
            <?//SLIDE_PANEL//?>
            <div class="slide-panel"></div>
			<?if($isSiteBg && !$isWideScreenMode) {?>
				</div>
			<?}
			if(!$isSiteClosed) {?>
				</div>
			<?}?>
		</div>
		<?//SCROLL_UP//?>
		<a class="scroll-up" href="javascript:void(0)"><i class="icon-arrow-up"></i></a>
		<?//JS//?>
		<script type="text/javascript">
			BX.message({
				SITE_ID: "<?=SITE_ID?>",
				SITE_DIR: "<?=SITE_DIR?>",				
				SITE_SERVER_NAME: "<?=SITE_SERVER_NAME?>",
				SITE_TEMPLATE_PATH: "<?=SITE_TEMPLATE_PATH?>",
				SITE_CHARSET: "<?=SITE_CHARSET?>",
				LANGUAGE_ID: "<?=LANGUAGE_ID?>",
				COOKIE_NAME: "<?=Bitrix\Main\Config\Option::get('main', 'cookie_name', 'BITRIX_SM')?>",
				SLIDE_PANEL_SEARCH_TITLE: "<?=GetMessageJS('ENEXT_SLIDE_PANEL_SEARCH_TITLE')?>",				
				SLIDE_PANEL_UNDEFINED_ERROR: "<?=GetMessageJS('ENEXT_SLIDE_PANEL_UNDEFINED_ERROR')?>"
			});
			//IE fix for "jumpy" fixed background
			if(navigator.userAgent.match(/MSIE 10/i) || navigator.userAgent.match(/Trident\/7\./) || navigator.userAgent.match(/Edge\/12\./)) {
				$("body").on("mousewheel", function () {
					event.preventDefault();
					var wd = event.wheelDelta;
					var csp = window.pageYOffset;
					window.scrollTo(0, csp - wd);
				});
			}
		</script>
		<?=$APPLICATION->ShowProperty("countersScriptsBodyEnd");?>
	</body>
</html>