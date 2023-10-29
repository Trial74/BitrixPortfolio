(function() {
    'use strict';

    if (!!window.JCFirstBlockSlider)
        return;

    window.JCFirstBlockSlider = function(params) {
        this.container = params.container;
        this.imgContainerName = params.imgContainerName
        this.countSlides = params.countSlides;

        BX.ready(BX.delegate(this.init, this));
    };

    window.JCFirstBlockSlider.prototype = {
        init: function () {
            var containerImg;
            for (var i = 1; i <= this.countSlides; i++) {
                containerImg = $('#' + this.container + '-slide0' + i + '> a > #' + this.imgContainerName + '_img-' + i);
                if(window.screen.width > 991){
                    containerImg.css('background-image', "url('" + containerImg.data('imageSrc') + "')");
                }else{
                    containerImg.css('background-image', 'url("' + containerImg.data('mobileImageSrc') + '")');
                }
            }
        }
    }
})();