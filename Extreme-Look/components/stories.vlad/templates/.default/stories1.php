<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>HeriaPro app</title>

        <meta property="og:title" content="HeriaPro app" />
        <meta property="og:description" content="" />
        <meta property="og:image" content="/preview.jpg" />
        <meta name="description" content=""/>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/slick.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css?<?=time()?>" />
        <link rel="stylesheet" type="text/css" href="css/animate.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat|Roboto:300,500" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
              integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="css/intlTelInput.min.css">
        <style>
            body:before{
                position:absolute; width:0; height:0; overflow:hidden; z-index:-1;
                content:url('img/canva.png');
            }
            body {
                height: 100%;
            }
        </style>
    </head>
    <body>
    <?
    $_SERVER['HTTP_USER_AGENT'] == 'extreme-look-app' || $_SERVER['HTTP_USER_AGENT'] == 'extreme-look-app-vlad' ? $app = true : $app = false;
    ?>
        <div class="slider single-item">
            <div class="slide slick-slide">
                <div class="slide-inner slide-video">
                    <div class="slide-content">
                        <img class="canva" src="img/canva.png" />
                        <video preload="auto" autoplay loop muted playsinline>
                            <source src="/bitrix/components/altop/stories.vlad/templates/.default/video/storis1/stor1_1.mp4" type='video/mp4;codecs="avc1.42E01E, mp4a.40.2"'>
                        </video>
                        <div class="sound-control sound-control_unmute"></div>
                        <div class="exit-storis"></div>
                        <div class="inner-next"></div>
                        <div class="inner-prev"></div>
                        <div class="iplay"></div>
                        <a id="modal-btn-id-f175c22e55bf" class="stop-slider" onclick="goTo(1)" href="#" style="padding: 0; min-height: 5px; position: absolute;
                           bottom: 0!important;
                           width: 100%!important;
                           height: 80px!important;
                           background: transparent !important;
                          ">
                        </a>
                    </div>
                </div>
            </div>
            <div class="slide slick-slide">
                <div class="slide-inner slide-video">
                    <div class="slide-content">
                        <img class="canva" src="img/canva.png" />
                        <video preload="auto" autoplay loop muted playsinline>
                            <source src="/bitrix/components/altop/stories.vlad/templates/.default/video/storis1/stor1_2.mp4" type='video/mp4;codecs="avc1.42E01E, mp4a.40.2"'>
                        </video>
                        <div class="sound-control sound-control_unmute"></div>
                        <div class="exit-storis"></div>
                        <div class="inner-next"></div>
                        <div class="inner-prev"></div>
                        <div class="iplay"></div>
                        <a id="modal-btn-id-f175c22e55bf" class="stop-slider" onclick="goTo(2)" href="#" style="padding: 0; min-height: 5px; position: absolute;
                           bottom: 0!important;
                           width: 100%!important;
                           height: 80px!important;
                           background: transparent !important;
                          ">
                        </a>
                    </div>
                </div>
            </div>
            <div class="slide slick-slide">
                <div class="slide-inner slide-video">
                    <div class="slide-content">
                        <img class="canva" src="img/canva.png" />
                        <video preload="auto" autoplay loop muted playsinline>
                            <source src="/bitrix/components/altop/stories.vlad/templates/.default/video/storis1/stor1_3.mp4" type='video/mp4;codecs="avc1.42E01E, mp4a.40.2"'>
                        </video>
                        <div class="sound-control sound-control_unmute"></div>
                        <div class="exit-storis"></div>
                        <div class="inner-next"></div>
                        <div class="inner-prev"></div>
                        <div class="iplay"></div>
                        <a id="modal-btn-id-f175c22e55bf" class="stop-slider" onclick="goTo(3)" href="#" style="padding: 0; min-height: 5px; position: absolute;
                           bottom: 0!important;
                           width: 100%!important;
                           height: 80px!important;
                           background: transparent !important;
                          ">
                        </a>
                    </div>
                </div>
            </div>
            <!-- next -->
        </div>
        <div class="progressBarContainer">
            <div><span data-slick-index="0" class="progressBar"></span></div>
            <div><span data-slick-index="1" class="progressBar"></span></div>
            <div><span data-slick-index="2" class="progressBar"></span></div>
            <!-- progressbar -->
        </div>

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <script src="js/animatedModal.min.js"></script>

    <script src="js/intlTelInput.min.js"></script>
    <script>
        $(".user-phone-input").each(function(index, element) {
            //console.log(element)
            window.intlTelInput(element, {
                initialCountry: "us",
                preferredCountries: ['us', 'ca', 'de', 'fr', 'es', 'it', 'br'],
                separateDialCode: true
            });
        });
    </script>
    <script>
        window.addEventListener("DOMContentLoaded", function() {
            [].forEach.call( document.querySelectorAll('.user-phone-input'), function(input) {
                var keyCode;
                function mask(event) {
                    event.keyCode && (keyCode = event.keyCode);
                    var pos = this.selectionStart;
                    if (pos < 3) event.preventDefault();
                    var matrix = "   (___) ___ ____",
                        i = 0,
                        def = matrix.replace(/\D/g, ""),
                        val = this.value.replace(/\D/g, ""),
                        new_value = matrix.replace(/[_\d]/g, function(a) {
                            return i < val.length ? val.charAt(i++) || def.charAt(i) : a
                        });
                    i = new_value.indexOf("_");
                    if (i != -1) {
                        i < 5 && (i = 3);
                        new_value = new_value.slice(0, i)
                    }
                    var reg = matrix.substr(0, this.value.length).replace(/_+/g,
                        function(a) {
                            return "\\d{1," + a.length + "}"
                        }).replace(/[+()]/g, "\\$&");
                    reg = new RegExp("^" + reg + "$");
                    if (!reg.test(this.value) || this.value.length < 5 || keyCode > 47 && keyCode < 58) this.value = new_value;
                    if (event.type == "blur" && this.value.length < 5)  this.value = ""
                }

                input.addEventListener("input", mask, false);
                input.addEventListener("focus", mask, false);
                input.addEventListener("blur", mask, false);
                input.addEventListener("keydown", mask, false)
            });
        });
    </script>

    <script type="text/javascript">
        var stopFlag = true;
        var isVideo = false;
        var currentVideo;
        var stopTicking = false;
        var poster = 'null';
        var setLabel = false;
        var isDisableElements = false;
        var isPlaySound = false;
        var progressBarIndex = 0; // содержит индекс текущей кнопки
        var slidesNumber = 3;
        var slides = slidesNumber - 1;
        var app = <?=json_encode($app)?>; //Приложение или сайт?

        // Плашка Oo
        var widgetTitle = '';
        var widgetDescription = ``;
        var widgetBtnTitle = '';
        var widgetBtnLink = '#';
        var isShowWidget = false;
        var widgetBtnColor = '#583a88';
        var widgetUtmsTrack = false;

        //--------------------------
        function goTo(id) { //ссылки в сторисах
            var uri;
            switch (id) {
                case 1:
                    if(app)
                        uri = '/page-catalog.section/section-id=1382/';
                    else
                        uri = '/catalog/resnitsy/';
                    break;
                case 2:
                    if(app)
                        uri = '/page-catalog.section/section-id=1380/';
                    else
                        uri = '/catalog/instrumenty_3/';
                    break;
                case 3:
                    if(app)
                        uri = '/page-catalog.section/section-id=1379/';
                    else
                        uri = '/catalog/gigiena_i_bezopasnost_1/';
                    break;
                default:
                    uri = '/';
            }
            if(app)
                window.parent.app.router.navigate(uri); //Для редиректа в приложении надо постучаться к методу (ссылку формируй правильно! Через параметры)
            else
                window.parent.document.location.replace(uri);
            $('#storis-block', parent.document).removeClass('active-storis');
            window.frameElement.remove();
        }
        function stopSlider () {
            stopTicking = true;
            if (currentVideo && currentVideo.length && currentVideo.length !== 0) {
                currentVideo[0].pause();
            }
        }
        function startSlider () {
            stopTicking = false;
            if (currentVideo && currentVideo.length && currentVideo.length !== 0) {
                currentVideo[0].play();
            }
        }
        function isPhoneNumber (value) {
            return value.match(/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im)
        }
        function isEmail (value) {
            return value.match(/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/)
        }
        $(document).ready(function () {
            // console.log(window.location.search)
            //$('.btn-safe-utms')
            if (window.location.search) {
                var search = window.location.search.substring(1);
                var querySelectors = search;

                $('.btn-safe-utms').each(function() {
                    var elemHref = $(this).attr('href');
                    if (elemHref.indexOf('?') == -1) {
                        $(this).attr('href', elemHref + '?' + querySelectors)
                    } else {
                        $(this).attr('href', elemHref + '&' + querySelectors)
                    }
                })
            }

            $('.form-submit').click(function (e) {
                e.preventDefault();

                var formData = $(this).parent().parent();
                var formId = $(this).data('formId');

                var nameInput = formData.find('.user-name-input');
                var isNameHide = nameInput.data('hide');
                var phoneInput = formData.find('.user-phone-input');
                var isPhoneHide = phoneInput.data('hide');
                var emailInput = formData.find('.user-email-input');
                var isEmailHide = emailInput.data('hide');

                nameInput.css('border-color', '#aaa');
                phoneInput.css('border-color', '#aaa');
                emailInput.css('border-color', '#aaa');

                var name = nameInput.val()
                var phone = phoneInput.val()
                phone = (phoneInput.parent().find('.iti__selected-dial-code').text().trim() + ' ' + phone.split('-').join('').trim())
                var email = emailInput.val()

                var firstLevelValidate = true;
                if (name.trim() == '' && !isNameHide) {
                    nameInput.css('border-color', 'red');
                    firstLevelValidate = false
                }

                if (phone.trim().length < 17 && !isPhoneHide) {
                    phoneInput.css('border-color', 'red');
                    firstLevelValidate = false
                }

                if (email.trim() == '' && !isEmailHide) {
                    emailInput.css('border-color', 'red');
                    firstLevelValidate = false
                }

                if (!isEmail(email) && !isEmailHide) {
                    emailInput.css('border-color', 'red');
                    firstLevelValidate = false
                }

                if (!firstLevelValidate) {
                    return;
                }

                $.ajax({
                    type: "POST",
                    beforeSend: function(request) {
                        request.setRequestHeader("X-Source-Uri", window.location.href.split('/')[2]);
                    },
                    url: 'https://form.slide.page/api/catch',
                    contentType: "application/json",
                    data: JSON.stringify({
                        phone: phone,
                        name: name,
                        email: email,
                        formId: formId
                    }),
                    success: (data) => {
                        //console.log('success')
                    },
                    error: () => {
                        //console.log('error req')
                    },
                    dataType: "json"
                });

                formData.parent().parent().addClass('success')
            });

            $('.special-form-btn').each(function() {
                $(this).animatedModal()
            });

            $('.stop-slider').click(function() {
                stopSlider();

                $('.slider').slick("slickSetOption", "swipe", false);
            });

            $('.start-slider').click(function() {
                startSlider();

                $('.slider').slick("slickSetOption", "swipe", true);
            });

            const slidesContents = $('body');
            if (setLabel === undefined || setLabel === true) {
                slidesContents.eq(slidesContents.length - 1).append(`<div class="cr-wrap">
            <style>
              .cr-wrap {
                position: absolute;
                top: 35px;
                left: 2.5%;
                width: max-content;
                z-index: 5555
              }
              @media(max-width: 647px) {
                .cr-wrap {
                  position: absolute;
                  top: 35px;
                  left: 15px;
                  width: max-content;
                  z-index: 5555
                }
              }

              .cr {
                text-transform: none!important;
                opacity: .85;
                align-items: center!important;
                height: 35px;
                padding: 0;
                display: flex;
                background: transparent;
                font-size: 12px;
              }
              .cr:hover {
                text-decoration: none;
                opacity: 1;
              }
              .cr strong {
                text-transform: none!important;
                font-weight: 300;
              }
              .cr span {
              background: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='iso-8859-1'%3F%3E%3C!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0) --%3E%3Csvg version='1.1' id='Capa_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 50 50' style='enable-background:new 0 0 50 50;' xml:space='preserve'%3E%3Cpath style='fill:%23D75A4A;' d='M24.85,10.126c2.018-4.783,6.628-8.125,11.99-8.125c7.223,0,12.425,6.179,13.079,13.543 c0,0,0.353,1.828-0.424,5.119c-1.058,4.482-3.545,8.464-6.898,11.503L24.85,48L7.402,32.165c-3.353-3.038-5.84-7.021-6.898-11.503 c-0.777-3.291-0.424-5.119-0.424-5.119C0.734,8.179,5.936,2,13.159,2C18.522,2,22.832,5.343,24.85,10.126z'/%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3Cg%3E%3C/g%3E%3C/svg%3E%0A") no-repeat center center;
                width: 25px;
                height: 15px;
                vertical-align: bottom;
                line-height: initial;
                padding: 0 5px;
                margin-left: 6px;

              }
              .cr-icon {
                position: relative;
                top: -7px;
              }
              .cr-icon img {
                width: 16px;
                height: 16px;
                border-radius: 50%;
              }
              .cr-content {
                text-align: left;
                margin-left: 10px;
                font-weight: 100;
              }
              .cr-content > div {
                font-weight: 100;
                text-shadow: 0 0 2px #111;
              }
            </style>
            <a class='cr' href='http://slide.page/' target='_blank' >
              <div class="cr-icon">
                <img src="img/logo.png">
              </div>
              <div class="cr-content">
                <div style="font-size: 14px; font-weight: 400;">slide.page</div>
                <div style="color: #ddd;">Made with <span></span></div>
              </div>
            </a>
          </div>`)
            }
            $slideshow = $('.slider').slick({
                centerMode: true,
                dots: false,
                centerPadding: '60px',
                variableWidth: true,
                lazyLoad: 'ondemand',
                infinite: false,
                focusOnSelect: true,
                speed: 300,
                touchThreshold: 15,

                prevArrow: '<a class="slick-prev">  </a>',
                nextArrow: '<a class="slick-next">  </a><a class="slick-replay">  </a>',
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        variableWidth: true,
                        lazyLoad: 'ondemand',
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1,
                    }
                },
                    {
                        breakpoint: 480,
                        settings: {
                            arrows: true,
                            lazyLoad: 'ondemand',
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 1,
                        }
                    }
                ]
            });

            var defaultTime = 10;
            // Ticking machine
            var percentTime; // начальное положение прогрессбара
            var tick; // заполняет полоски с указанным интервалом
            var time = defaultTime; // время показа одного слайда
            var unmuteClass = 'sound-control_unmute';
            $('.progressBarContainer .progressBar').each(function (index) {
                var progress = "<div class='inProgress inProgress" + index + "'></div>";
                $(this).html(progress);
            });
            var isMuted = true;
            var videos = $("video");
            var soundControls = $('.sound-control');

            var onceSoundControlMode = 'default';
            soundControls.click(function(e) {
                if (isDisableElements) {
                    return
                }
                e.stopPropagation();
                if (onceSoundControlMode == 'default') {
                    isMuted = !isMuted;
                    soundControls.toggleClass(unmuteClass, isMuted);
                    $(videos).prop('muted', isMuted);
                }

                if (onceSoundControlMode == 'all_videos') {
                    var video = $(this).parent().find('video');
                    var curentSoundStatus = video.prop('muted');
                    isMuted = !curentSoundStatus;
                    soundControls.toggleClass(unmuteClass, !curentSoundStatus);
                    video.prop('muted', !curentSoundStatus)
                }

            });
            function checkIsVideoPlaying(videoElement) {
                return videoElement.currentTime > 0 && !videoElement.paused && !videoElement.ended
                    && videoElement.readyState > 2;
            }

            function startProgressbar() {
                resetProgressbar();
                const ItemsForFill = [];

                for (var i = 0; i < progressBarIndex; i++) {
                    $('.inProgress').eq(i).css({
                        width: '100%'
                    })
                }

                currentVideo = $('.slide').eq(progressBarIndex).find('video');
                isVideo = currentVideo.length !== 0;
                soundControls.hide();
                videos.each(function() {
                    var video = $(this);
                    if (progressBarIndex != 0) {
                        onceSoundControlMode = 'default';
                        video.prop('muted', isMuted)
                    }
                    video.get(0).currentTime = 0;
                    video.get(0).pause();
                });
                if (isVideo) {
                    var soundControl = $('.slide').eq(progressBarIndex).find('.sound-control');
                    $(soundControl).show();
                    currentVideo.on(
                        "timeupdate",
                        function(event){
                            var that = this
                            time = this.duration
                            if (isNaN(time)) {
                                var loadVideoTimer = setInterval(function() {
                                    if (!isNaN(that.duration)) {
                                        clearInterval(loadVideoTimer);
                                        time = that.duration
                                    }
                                })
                            }
                        });
                    var isPlaying = checkIsVideoPlaying(currentVideo);
                    if (!isPlaying) {
                        currentVideo[0].play();
                    }
                } else {
                    $('video').off();
                    time = defaultTime
                }

                percentTime = 0;
                tick = setInterval(interval, 10);
            }

            function interval() {
                if (stopTicking) {
                    return;
                }
                if (!($('.slider .slick-track div[data-slick-index="' + progressBarIndex + '"]').hasClass(
                    "slick-center"))) {
                    progressBarIndex = $('.slider .slick-track div[class="slide slick-slide slick-current slick-center"]')
                        .data("slickIndex");
                    startProgressbar();
                } else {
                    if (stopFlag) {
                        percentTime += 1 / time;
                        $('.inProgress' + progressBarIndex).css({
                            width: percentTime + "%"
                        });

                        if (percentTime >= 100) {
                            $('.slider').slick('slickNext');
                            progressBarIndex++;
                            currentVideo = $('.slide').eq(progressBarIndex).find('video');
                            isVideo = currentVideo.length !== 0;
                            if (isVideo) {
                                currentVideo.on(
                                    "timeupdate",
                                    function(event) {
                                        var that = this;
                                        time = this.duration;
                                        //console.log(time)
                                        if (isNaN(time)) {
                                            var loadVideoTimer = setInterval(function() {
                                                if (!isNaN(that.duration)) {
                                                    clearInterval(loadVideoTimer);
                                                    time = that.duration
                                                }
                                            })
                                        }
                                    });
                                currentVideo[0].currentTime = 0
                            } else {
                                $('video').off();
                                time = defaultTime
                            }

                            if (progressBarIndex > slides) { // если индекс текущей кнопки больше количества слайдов - останавливает воспроизведение
                                stopProgressbar()
                            } else {
                                startProgressbar();
                            }
                        }
                    }
                }
            }
            function resetProgressbar() {
                $('.inProgress').css({
                    width: 0 + '%'
                });
                clearInterval(tick);
            }
            function stopProgressbar() { /* останавливает воспроизведение слайдера на последнем слайде */
                $('#storis-block', parent.document).removeClass('active-storis');
                window.frameElement.remove();
                $('.inProgress .inProgress' + slides).css({
                    width: 100 + '%'
                });
                clearInterval(tick);
            }
            startProgressbar();
            // End ticking machine
            $('.progressBarContainer div').click(function () { // меняет кнопку на прогрессбаре при клике
                if (isDisableElements) {
                    return
                }
                clearInterval(tick);
                var goToThisIndex = $(this).find("span").data("slickIndex");
                $('.slider').slick('slickGoTo', goToThisIndex, false);
                startProgressbar();
            });
            $('.slide.slick-slide').click(function (e) { // меняет кнопку на прогрессбаре по клику на слайд
                if ($(this).hasClass('slick-current')) {
                    return;
                }
                if (isDisableElements) {
                    return
                }
                clearInterval(tick);
                var goToThisIndex = $(this).find("span").data("slickIndex");
                $('.slider').slick('slickGoTo', goToThisIndex, false);
                startProgressbar();
            });

            $('.slick-replay').click(function () { // возвращает на первый слайд
                clearInterval(tick);
                var goToThisIndex = $(this).find("span").data("slickIndex");
                $('.slider').slick('slickGoTo', 0);
                startProgressbar();
            });
            $('.exit-storis').click(function() {
                $('#storis-block', parent.document).removeClass('active-storis');
                window.frameElement.remove();
            });

            /* Для мобилок. Отслеживает свайп с последнего слайда */
            var $div = $('.slick-track .slick-slide:last-child');
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === "class") {
                        if(progressBarIndex === slides){
                            /*$('#storis-block', parent.document).removeClass('active-storis');
                            window.frameElement.remove(); //по клику на последнем слайдере убиваем фрейм*/
                        }
                        clearInterval(tick);
                        var goToThisIndex = $(this).find("span").data("slickIndex");
                        $('.slider').slick('slickGoTo', 0);
                        startProgressbar();
                    }
                });
            });
            observer.observe($div[0], {
                attributes: true
            });

            var longTouchInterval;
            $('.slide-content').on('touchstart', function(e){
                if($(e.target).parents('.animated').length != 0) {
                    return
                }
                if (isDisableElements) {
                    return
                }
                longTouchInterval = setTimeout(function() {
                    stopSlider();
                    if (currentVideo.length > 0) {
                        currentVideo[0].pause()
                    }
                }, 50)
            });

            $('.slide-content').on('touchend', function(e) {
                if($(e.target).parents('.animated').length != 0) {
                    return
                }

                if (isDisableElements) {
                    return
                }
                if (longTouchInterval) {
                    clearTimeout(longTouchInterval);
                    if (stopTicking) {
                        startSlider();
                        if (currentVideo.length > 0) {
                            currentVideo[0].play()
                        }
                    }
                }
            });

            window.oncontextmenu = function(event) {
                event.preventDefault();
                event.stopPropagation();
                return false;
            };

            $('.slide').each(function() {
                var video = $(this).find('video');
                var isVideo = video.length && video.length > 0;
                if (isVideo) {
                    $(this).find('.iplay').css('zIndex', 4)
                }
            });
            var oncePlayed = false;
            $('.iplay')[0].addEventListener('click', function(){
                if (!oncePlayed) {
                    oncePlayed = true;
                    $('.iplay').html('');
                    isDisableElements = false;
                    if (isPlaySound) {
                        $('.slide').eq(0).find('.sound-control')[0].click()
                    }
                    onceSoundControlMode = 'all_videos';
                    startSlider();
                    $('.slider').slick("slickSetOption", "swipe", true);
                }
            });
            $('.iplay').click(function(e) {
                e.stopPropagation();
                var video = $(this).parent().parent().find('video')[0];
                var videos = $('video')
                if (!checkIsVideoPlaying(video)) {
                    //video.play()
                    videos.each(function(index, element){
                        if (index != 0) {
                            $(element).prop('muted', true)
                        }
                        setTimeout(function() {
                            element.play()
                        }, 500)
                    });
                    startSlider();
                    startProgressbar();
                }
            });
            if (isShowWidget) {
                $('.progressBarContainer').before(`
            <style>
              .info-card {
                position: absolute;
                bottom: 0;
                width: calc(100% + 2px);
                background-color: rgba(0, 0, 0, 0.75);
                padding: 20px;
                z-index: 4;
                border-radius: 20px 20px 0 0;
                margin-left: -1px;
              }
            .info-card .buttons-panel ul {
              padding: 0;
              display: flex;
              width: 100%;
              margin-bottom: 0;
              justify-content: center;
            }
            .description {
              margin-bottom: 15px;
            }
            @media(max-width: 647px) {
              .info-card {
                display: block;
              }
            }
            .buttons-panel li:last-child {
              margin: 0;
            }
            .info-card .buttons-panel a {
              background: initial;
              width: 100%;
              text-transform: initial;
              display: flex;
              flex-flow: column;
              align-items: center;
              padding: 0;
              text-decoration: none;
            }
            .info-card .buttons-panel a span {
              font-size: 12px;
              text-align: center;
            }
            .info-card .buttons-panel a img {
              width: 100%;
              margin-top: 2px;
              margin-bottom: 2px;
              max-width: 37px;
              border-radius: 50%;
            }
            .info-card-info {
              text-align: left;
              max-height: 0;
              transition: max-height .4s;
              overflow-y: auto;
            }
            .info-card-info.active-info {
              text-align: left;
              max-height: 60vh;
              transition: max-height .4s;
            }
            .info-card-info .title {
              font-size: 22px;
              margin-top: 1rem;
            }
            .info-card .info-card-info a {
              background: initial;
              display: initial;
              padding: 0;
              margin: 0;
              color: #fff;
              text-decoration: underline;
              text-transform: initial;
            }
            .info-card-more img {
              transition: .2s ease;
              transition-delay: .2s;
            }
            .info-card-more.active-info img {
              transform: rotate(-180deg);
              transition: .2s ease;
            }
            @media(min-width: 647px) {
              .info-card-info::-webkit-scrollbar {
                background: rgba(0, 0, 0, 0.8);
                width: 5px;
              }
              .info-card-info.active-info {
                max-height: 230px;
              }
              .info-card-info::-webkit-scrollbar-thumb {
                background: #222;
              }
              .info-card {
                display: flex;
                flex-flow: row;
                max-width: 768px;
                left: 50%;
                transform: translate(-50%, -0);
                background-color: rgba(0, 0, 0, 0.6);
              }
              .info-card-info {
                order: 0;
                width: 80%;
                text-align: left;
                max-height: 200px;
                padding-right: 15px;
                transition: max-height .4s;
                scrollbar-color: #222 rgba(0, 0, 0, 0.8);
                scrollbar-width: thin;
              }
              .info-card-info .title {
                margin-top: 0;
              }
              .info-card .buttons-panel {
                width: 20%;
                min-width: max-content;
                order: 1;
                display: flex;
                border-left: 1px solid #333;
                padding-left: 15px;
              }
              .info-card .buttons-panel ul {
                justify-content: center;
                flex-flow: column;
              }
              .info-card .buttons-panel a {
                align-items: center;
                flex-flow: row;
              }
              .buttons-panel li {
                display: flex;
                padding: 5px 0;
              }
              .info-card .buttons-panel a span {
                margin-left: 10px;
              }
              .info-card .buttons-panel a img {
                max-width: 30px;
                margin-bottom: 0;
              }
              .info-card-more {
                display: none!important;
              }
            }
            @media(min-width: 647px) {
              .slider {
                margin: 20px auto 0;
              }
            }
            @media(max-width: 647px) {
              .slide {
                height: 100vh;
              }
              .slider {
                top: 0;
              }
              .slick-replay {
                bottom: 155px;
              }
            }
            @media (max-width: 1600px) and (min-width: 647px) {
              .slide {
                min-width: initial;
                min-height: initial;
                max-height: initial;
                max-width: initial;
              }
              .slide .slide-inner {
                  min-height: initial;
              }
            }
            .create-info-card {
              width: 80%; margin-top: 13px; padding: 0 10px;
            }
            .create-info-card a {
              background-color: #583a88!important;
              padding: 17px 20px!important;
              text-align: center!important;
              border-radius: 12px;
              color: #fff;
            }

            .info-card.create-info .buttons-panel a {
              justify-content: center;
            }
            .info-card.create-info .create-info-card a:hover {
              background-color: #613f96!important;
            }
            .info-card.create-info .buttons-panel ul {
              display: flex;
              align-items: center;
              justify-content: space-around;
            }
            .info-card.create-info .buttons-panel ul li {
              margin: 0;
            }
            .info-card.create-info .buttons-panel a img {
              width: 50px;
            }
            .info-card.create-info {
                padding: 10px 10px 10px 10px;
            }
            .create-info-card {
              padding: 10px!important;
            }
            .create-info .info-card-info {
              padding-left: 15px;
            }
            @media(min-width: 647px) {
              .create-info-card {
                width: 100%;
              }
              .create-info .info-card-info {
                padding-left: 0;
              }
              .create-info {
                padding: 25px!important;
              }
            }

            @media(min-width: 647px) {
              .first-li a {
                margin-top: initial;
              }
            }
            @media(max-width: 647px) {
              .first-li {
                margin-right: 20px;
                width: 100%;
              }

              .first-li button {
                width: 100%;
              }
            }

            .first-li button {
              height: 57px;
              border: none;
              outline: none;
              border: none;
              padding: 5px 7px;
              border-radius: 7px;
              background-color: ${widgetBtnColor};
              }


            /* modal flattening fix */

            @media(min-width: 647px) {
              .slide {
                width: 242px;
                height: 430px;
              }
              @media (min-height: 700px) {
                .slide {
                  width: 242px;
                  height: 430px;
                }
              }
              @media (min-height: 800px) {
                .slide {
                  width: 298px;
                  height: 530px;
                }
              }
              @media (min-height: 900px) {
                .slide {
                  width: 354px;
                  height: 630px;
                }
              }
              @media (min-height: 1000px) {
                .slide {
                  width: 411px;
                  height: 730px;
                }
              }
              @media (min-height: 1100px) {
                .slide {
                  width: 467px;
                  height: 830px;
                }
              }
              @media (min-height: 1200px) {
                .slide {
                  width: 523px;
                  height: 930px;
                }
              }
              @media (min-height: 1300px) {
                .slide {
                  width: 579px;
                  height: 1030px;
                }
              }
              @media (min-height: 1400px) {
                .slide {
                  width: 636px;
                  height: 1130px;
                }
              }
              @media (min-height: 1500px) {
                .slide {
                  width: 692px;
                  height: 1200px;
                }
              }
            }

            </style>
            `);
                $('.slider').after(`
            <div class="info-card">
                <div class="buttons-panel">
                  <ul>
                    <li class="first-li">
                      <a href="${widgetBtnLink}">
                        <button style="width: 99%">${widgetBtnTitle}</button>
                      </a>
                    </li>
                    <li class="info-card-more">
                      <a href="#">
                        <img src="https://storyland-common-storage.storage.yandexcloud.net/b93ccfce-ef30-4fa8-b70b-91af36df8823.svg">
                        <span>Подробнее</span>
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="info-card-info">
                  <p class="title">
                    ${widgetTitle}
                  </p>
                  <p style="white-space: break-spaces;" class="description">

                  </p>
                </div>
              </div>
            `);
                $('.info-card').find('.description').html(widgetDescription);
                var info_card_more = document.querySelector('.info-card-more');
                var info_card_info = document.querySelector('.info-card-info');
                info_card_more.onclick = function() {
                    info_card_info.classList.toggle('active-info');
                    info_card_more.classList.toggle('active-info');
                }
                setTimeout(function() {
                    $('.first-li').find('a').click(function() {
                        if($(this).attr('href')) {
                            matomoTrack('form-card', 'button-click', $(this).attr('href'));
                            location.href = $(this).attr('href');
                        }
                    })
                }, 500)
                if (widgetUtmsTrack) {
                    if (window.location.search) {
                        var search = window.location.search.substring(1);
                        var querySelectors = search

                        $('.first-li').find('a').each(function() {
                            var elemHref = $(this).attr('href')
                            if (elemHref.indexOf('?') == -1) {
                                $(this).attr('href', elemHref + '?' + querySelectors)
                            } else {
                                $(this).attr('href', elemHref + '&' + querySelectors)
                            }
                        })
                    }
                }
                isPlaySound = true;
            }
            if (isPlaySound) {
                var firstSlideVideo2 = $('.slide').eq(0).find('video');
                if (firstSlideVideo2.length && firstSlideVideo2.length > 0) {
                    firstSlideVideo2.attr('poster', poster);
                    stopSlider();
                    isDisableElements = true;
                    firstSlideVideo2[0].pause();
                    firstSlideVideo2.parent().find('.iplay').html("<img src='img/video-play-btn.png' alt='play' />")
                    $('.slider').slick("slickSetOption", "swipe", false);
                }
            }
        });
        function matomoTrack (category, action, name, value) {
            if (window._paq && window._paq.push) {
                try {
                    let analParams = ['trackEvent']
                    analParams.push(category)
                    if (action !== undefined) { analParams.push(action) }
                    if (name !== undefined) {
                        if (!isMobile()) {
                            analParams.push(name)
                        }
                    }
                    if (value !== undefined) { analParams.push(value) }
                    window._paq.push(analParams)
                } catch (e) {
                    console.log('request to matomo is failed')
                }
            }
        }
    </script>
    <script src="js/script.js"></script>
    </body>
</html>
