/* window resizer */
$( document ).ready(function() {

    $('.info-card .buttons-panel a').click(function(e) { // prevent to top action
        e.preventDefault();
    });
    //console.log('userAgent = ' + window.navigator.userAgent);
    // if($(window).width() < 647 && !(window.navigator.userAgent.toLowerCase().indexOf('instagram') !== -1) && (window.navigator.userAgent.toLowerCase().indexOf('applewebkit') !== -1)) {
    if($(window).width() < 647 && !(window.navigator.userAgent.toLowerCase().indexOf('instagram') !== -1)) {
        $(".slide").delay(150).queue(function (next) {
            $(this).css('height', `${window.innerHeight}px`);

            next();
        });
        $(window).resize(() => {
            $(".slide").delay(150).queue(function (next) {
                $(this).css('height', `${window.innerHeight}px`);
                next();
            });
        });
    }

    window.addEventListener("touchmove", function(event) {
        var keysData = ['iti_']
        var className = event.target.className

        var isDisableScroll = true

        for(var i = 0; i < keysData.length;i++) {
            if (className.indexOf(keysData[i]) != -1) {
                isDisableScroll = false
                break;
            }
        }

        if (window.navigator.userAgent.toLowerCase().indexOf('instagram') != -1) {
            isDisableScroll = false
        }

        if (isDisableScroll) {
            event.preventDefault()
        }
    }, {passive: false});

    $('.inner-next').click(function() {
        if (isDisableElements) {
            return
        }
        $slideshow.slick('slickNext');
    });
    $('.inner-prev').click(function() {
        if (isDisableElements) {
            return
        }
        $slideshow.slick('slickPrev');
    });
});