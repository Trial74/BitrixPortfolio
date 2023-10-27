$(document).ready(function () {

	$(document).on('click', '#see-all', function () {
		let el = this.parentNode.parentNode;
			$(el).toggleClass('active');
			$(this).toggleClass('active');
			$(el).find('.map_points').toggleClass('active');
			if($(el).innerHeight() > 240)
				$(el).css('height', '');
			else
				$(el).css('height', $(el).find('.map_points').innerHeight());
	});

	$('.partner_box .slider').click(function () {
		$('html, body').animate({
			scrollTop: $("nav.part_breadcrumb").offset().top
		}, 500);
		$('.ct_child_city').html($(this).parent().find(".child").html());
		$('.all_ct').hide();
		$('.ct_child_city').show();
		$('.ct_child_map').html("");
		$('.part_breadcrumb').css('display', 'block');
		$('.stat-part').css('display', 'none');
		$('.breadcrumb').append(
			"<li class='partner-countries breadcrumb-item'><a href='#'>Страны</a></li>" +
			"<li class='partner-country breadcrumb-item'><a href='#'>" + $(this).find("div").html() + "</a></li>"
		);
	});

	$(document).on('click', '.partner-countries', function () {
		$('.all_ct').css('display', 'block');
		$('.breadcrumb').html("");
		$('.part_breadcrumb').css('display', 'none');
		$('.ct_child_map').css('display', 'none');
		$('.stat-part').css('display', '');
		$('.ct_child_map').html("");
		$('.ct_child_city').html("");
	});

	$(document).on('click', '.partner-country', function () {
		$('.ct_child_map').css('display', 'none');
		$('.ct_child_city').css('display', '');
		if ($('ol.breadcrumb li').length > 2)
			$('ol.breadcrumb li:last-child').remove();
	});

	$(document).on("click", ".sp_country", function () {
		$('.ct_child_city').show();
		$('.slider .active').removeClass("active");
		$('.sp_city').html("");
		$('.ct_child_map').html("");
		$('.ct_child_map').hide();
	});

	$(document).on("click", ".ct_child_city .slider", function () {
		$('.ct_child_city').hide();
		$('.slider.active').removeClass("active");
		$(this).toggleClass('active');
		$('.ct_child_map').show();
		$('.ct_child_map').html($(this).next('div').html());
		$('.partner-country').removeClass('active');
		$('.breadcrumb').append("<li class='partner-sity breadcrumb-item active'><a href='#'>" + $(this).html() + "</a></li>");
	});

	$(document).on("click", ".marker-personal-show", function () {
		$('html, body').animate({
			scrollTop: $(".h1_last").offset().top
		}, 500);
	});
});