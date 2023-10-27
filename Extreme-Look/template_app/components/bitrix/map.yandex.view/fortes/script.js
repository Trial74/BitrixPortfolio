var MAP = false;
window.lastMarker = false;

$(document).ready(function(){
	$(document).on('click', '.marker-personal-show', function(){		
		hideAllMarkers();
		
		var _this = $(this);
		
		try{
			var marker = markers[_this.data('marker')][_this.data('marker-key')];
			
			window.lastMarker = marker;
			
			marker.options.set('visible', true);
			
			MAP.setZoom(9);
			MAP.setCenter(marker.geometry.getCoordinates());
			
			marker.infowin.open(MAP, marker);
			
			$('html, body').animate({
				scrollTop: $(".bx-google-map").offset().top
			}, 1000);
			
			$('.all-partners-show').css({ display: 'block', 'padding-bottom': '4px' });
		} catch( ex ){
			console.log($('.item[data-value="' + _this.data('marker-key') + '"]'));
		}
	})
	.on('click', '.all-partners-show', function(){
		showAllMarkers();
		
		$(this).css('display', 'block');
		
		if( window.lastMarker )
			window.lastMarker.infowin.close();
	});
	
	$('#select-city').selectize({
		//closeAfterSelect: true,
		searchField: ['text', 'city'],
		render: {
			option: function (data, escape) {
				return "<div class='option' data-selectable data-city='" + data.city + "' data-value='" + data.value + "'>" + data.text + "</div>"
			}
		},
		onItemAdd: function(value, item){
			// Если выбран единственный город то перед этим скрываем все остальные
			if($('.selectize-input.items .item').length == 1)
				hideAllMarkers();
				
			for(var point in markers[value]){
				try{ markers[value][point].options.set('visible', true); } catch(ex){
					console.log($('.item[data-value="'+markerKey+'"]'));
				}
			}
		},
		onItemRemove: function(value){			
			if($('.selectize-input.items .item').length == 0){
				showAllMarkers();
			}
			else
				for( var point in markers[value] )
					try{ markers[value][point].options.set('visible', false); } catch(ex){ }
		}
	});
});

function hideAllMarkers(){
	for(var markerKey in markers){
		for(var point in markers[markerKey]){
			try{ markers[markerKey][point].options.set('visible', false); } catch(ex){ }
		}
	}
}

function showAllMarkers(){
	for(var markerKey in markers){
		for(var point in markers[markerKey]){
			try{ markers[markerKey][point].options.set('visible', true); } catch(ex){ }
		}
	}
	
	MAP.setZoom(3);
	MAP.setCenter(['52.921163578712694', '74.48143434999997']);
}










//////////////////////////////////////////////////

if (!window.BX_YMapAddPlacemark)
{
	window.BX_YMapAddPlacemark = function(map, arPlacemark)
	{
		if (null == map)
			return false;
		
		if( MAP == false )
			MAP = map;

		if(!arPlacemark.LAT || !arPlacemark.LON)
			return false;

		var props = {};
		if (null != arPlacemark.TEXT && arPlacemark.TEXT.length > 0)
		{
			var value_view = '';

			if (arPlacemark.TEXT.length > 0)
			{
				var rnpos = arPlacemark.TEXT.indexOf("\n");
				value_view = rnpos <= 0 ? arPlacemark.TEXT : arPlacemark.TEXT.substring(0, rnpos);
			}

			props.balloonContent = arPlacemark.TEXT.replace(/\n/g, '<br />');
			props.hintContent = value_view;
		}

		var obPlacemark = new ymaps.Placemark(
			[arPlacemark.LAT, arPlacemark.LON],
			props,
			{
				iconLayout: 'default#image',
				iconImageHref: '/images/mapPinMin.png',
				iconImageOffset: [-18, -38],
				balloonCloseButton: true
			}
		);

		map.geoObjects.add(obPlacemark);

		return obPlacemark;
	}
}

if (!window.BX_YMapAddPolyline)
{
	window.BX_YMapAddPolyline = function(map, arPolyline)
	{
		if (null == map)
			return false;

		if (null != arPolyline.POINTS && arPolyline.POINTS.length > 1)
		{
			var arPoints = [];
			for (var i = 0, len = arPolyline.POINTS.length; i < len; i++)
			{
				arPoints.push([arPolyline.POINTS[i].LAT, arPolyline.POINTS[i].LON]);
			}
		}
		else
		{
			return false;
		}

		var obParams = {clickable: true};
		if (null != arPolyline.STYLE)
		{
			obParams.strokeColor = arPolyline.STYLE.strokeColor;
			obParams.strokeWidth = arPolyline.STYLE.strokeWidth;
		}
		var obPolyline = new ymaps.Polyline(
			arPoints, {balloonContent: arPolyline.TITLE}, obParams
		);

		map.geoObjects.add(obPolyline);

		return obPolyline;
	}
}