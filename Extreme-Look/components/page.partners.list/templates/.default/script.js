$(document).ready(function () {
    $(document).on('click', '#see-all', function () {
        let el = this.parentNode.parentNode;
        $(el).toggleClass('active');
        $(this).toggleClass('active');
        $(el).find('.map_points').toggleClass('active');
        if ($(el).innerHeight() > 240)
            $(el).css('height', '');
        else
            $(el).css('height', $(el).find('.map_points').innerHeight());
    });

    $(document).on("click", ".marker-personal-show", function () {
        $('html, body').animate({
            scrollTop: $(".h1_last").offset().top
        }, 500);
    });
});