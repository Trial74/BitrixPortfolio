<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if(!defined("BX_GMAP_SCRIPT_LOADED")) {
	CUtil::InitJSCore();
	if($arParams["DEV_MODE"] != "Y") {
		$this->addExternalJS((CMain::IsHTTPS() ? "https" : "http")."://maps.google.com/maps/api/js?key=".$arParams["API_KEY"]."&language=".LANGUAGE_ID);
		define("BX_GMAP_SCRIPT_LOADED", 1);
	}
}

$this->addExternalJS($templateFolder."/infobubble_min.js");

if($arParams["BX_EDITOR_RENDER_MODE"] == "Y") {
	echo "<img src='".$templateFolder."/images/preview.png' border='0' />";
} else {
	$arTransParams = array(
		"INIT_MAP_TYPE" => $arParams["INIT_MAP_TYPE"],
		"INIT_MAP_LON" => $arResult["POSITION"]["google_lon"],
		"INIT_MAP_LAT" => $arResult["POSITION"]["google_lat"],
		"INIT_MAP_SCALE" => $arResult["POSITION"]["google_scale"],
		"MAP_WIDTH" => $arParams["MAP_WIDTH"],
		"MAP_HEIGHT" => $arParams["MAP_HEIGHT"],
		"CONTROLS" => $arParams["CONTROLS"],
		"OPTIONS" => $arParams["OPTIONS"],
		"MAP_ID" => $arParams["MAP_ID"],
		"API_KEY" => $arParams["API_KEY"],
	);

	if($arParams["DEV_MODE"] == "Y") {
		$arTransParams["DEV_MODE"] = "Y";
		if($arParams["WAIT_FOR_EVENT"])
			$arTransParams["WAIT_FOR_EVENT"] = $arParams["WAIT_FOR_EVENT"];
	}?>

	<?$APPLICATION->IncludeComponent("bitrix:map.google.system", ".default",
		$arTransParams,
		false,
		array("HIDE_ICONS" => "Y")
	);?>
	
	<script type="text/javascript">
		BX.message({
			MARKER_ICON: '<?=$templateFolder?>/images/marker-icon.png',
			MARKER_ICON_HOVER: '<?=$templateFolder?>/images/marker-icon-hover.png',
			MARKER_ICON_ACTIVE: '<?=$templateFolder?>/images/marker-icon-active.png'
		});
		
		if(!window.BX_GMapAddPlacemark) {
			function BX_GMapAddPlacemark(arPlacemark, map_id) {
				var map = GLOBAL_arMapObjects[map_id];
				if(null == map)
					return false;
				
				if(!arPlacemark.LAT || !arPlacemark.LON)
					return false;
				
				var obPlacemark = new google.maps.Marker({
					position: new google.maps.LatLng(arPlacemark.LAT, arPlacemark.LON),
					map: map,
					icon: map_id == 'object' ? BX.message('MARKER_ICON_HOVER') : BX.message('MARKER_ICON')
				});
				
				if(BX.type.isNotEmptyString(arPlacemark.TEXT)) {
					obPlacemark.infoBubble = new InfoBubble({					
						content: arPlacemark.TEXT,
						shadowStyle: 0,
						padding: 0,
						borderRadius: 5,
						arrowSize: 0,
						borderWidth: 0
					});
					
					google.maps.event.addListener(obPlacemark, 'click', function() {
						if(null != window['__bx_google_marker_active_' + map_id]) {
							window['__bx_google_marker_active_' + map_id].setIcon(BX.message('MARKER_ICON'));
							window['__bx_google_marker_active_' + map_id].infoBubble.close();
						}

						this.setIcon(BX.message('MARKER_ICON_ACTIVE'));
						this.infoBubble.open(this.map, this);
						window['__bx_google_marker_active_' + map_id] = this;
					});
					
					google.maps.event.addListener(obPlacemark.infoBubble, 'closeclick', function() {
						obPlacemark.setIcon(BX.message('MARKER_ICON'));
						window['__bx_google_marker_active_' + map_id] = null;
					});
				}

				if(map_id != 'object') {
					google.maps.event.addListener(obPlacemark, 'mouseover', function() {
						if(window['__bx_google_marker_active_' + map_id] != this)
							this.setIcon(BX.message('MARKER_ICON_HOVER'));
					});
					google.maps.event.addListener(obPlacemark, 'mouseout', function() {
						if(window['__bx_google_marker_active_' + map_id] != this)
							this.setIcon(BX.message('MARKER_ICON'));
					});
				}

				window['placemarks_' + map_id].push(obPlacemark);
				
				return obPlacemark;
			}
		}

		if(!window.BX_GMapSetCenter) {
			function BX_GMapSetCenter(placemarks, map_id) {
				var map = window.GLOBAL_arMapObjects[map_id];
				if(null == map)
					return false;
				
				var markersBounds = new google.maps.LatLngBounds();
				for(var i = 0; i < placemarks.length; i++) {
					markersBounds.extend(placemarks[i].position);
				}
				map.setCenter(markersBounds.getCenter(), map.fitBounds(markersBounds));
				
				if(window.innerWidth >= 992 && map_id == 'contacts') {
					var caption = document.body.querySelector('.contacts-item-caption');
					if(!!caption) {
						var offsetX = caption.getBoundingClientRect().left + caption.offsetWidth;
						if(offsetX > 0) {
							map.panBy(offsetX * -1, 0);
							window['BX_Dragend_' + map_id] = false;
							google.maps.event.addListener(map, 'bounds_changed', function() {
								if(!window['BX_Dragend_' + map_id]) {
									var markersBounds = map.getBounds();
									for(var i = 0; i < placemarks.length; i++) {
										markersBounds.extend(placemarks[i].position);
									}
									map.setCenter(markersBounds.getCenter(), map.fitBounds(markersBounds));
									window['BX_Dragend_' + map_id] = true;
								}
							});
						}
					}
				}
			}
		}
		
		if(!window.BXWaitForMap_view) {
			function BXWaitForMap_view(map_id) {				
				if(null == window.GLOBAL_arMapObjects)
					return;
			
				if(window.GLOBAL_arMapObjects[map_id]) {
					window['placemarks_' + map_id] = [];
					window['BX_SetPlacemarks_' + map_id]();
					
					if(window['placemarks_' + map_id].length > 1) {
						BX_GMapSetCenter(window['placemarks_' + map_id], map_id);
						BX.bind(window, 'resize', function() {
							BX_GMapSetCenter(window['placemarks_' + map_id], map_id);
						});
						BX.addCustomEvent(window, 'slideMenu', function() {
							BX_GMapSetCenter(window['placemarks_' + map_id], map_id);
						});
					}
				} else {
					setTimeout('BXWaitForMap_view(\'' + map_id + '\')', 300);
				}
			}
		}
		
		<?if(is_array($arResult['POSITION']['PLACEMARKS']) && ($cnt = count($arResult['POSITION']['PLACEMARKS']))) {?>
			function BX_SetPlacemarks_<?=$arParams['MAP_ID']?>() {
				<?for($i = 0; $i < $cnt; $i++) {?>
					BX_GMapAddPlacemark(<?=CUtil::PhpToJsObject($arResult['POSITION']['PLACEMARKS'][$i])?>, '<?=$arParams["MAP_ID"]?>');
				<?}?>
			}
			
			function BXShowMap_<?=$arParams['MAP_ID']?>() {
				BXWaitForMap_view('<?=$arParams["MAP_ID"]?>');
			}

			BX.ready(BXShowMap_<?=$arParams['MAP_ID']?>);
		<?}?>
	</script>
<?}