BX.ready(function() {
	var tabs = document.querySelector('.spsp-tabs');
	if(!!tabs) {
		BX.addClass(tabs, 'owl-carousel');
		$(tabs).owlCarousel({								
			autoWidth: true,
			nav: true,
			navText: ['<i class=\"icon-arrow-left\"></i>', '<i class=\"icon-arrow-right\"></i>'],
			navContainer: '.spsp-tabs-scroll',
			dots: false,			
		});
	}

	$( ".spsp-procents-block > div" ).click(function() {
		if(!$(this).hasClass('active')){
			var activeNode = $(this).parent().find('.active')[0];

			$(activeNode).removeClass('active');
			$('.spsp-info-terms#' + $(activeNode).attr('id')).removeClass('active');
			$(this).addClass('active');
			$('.spsp-info-terms#' + $(this).attr('id')).addClass('active');
		}
	});
});