(function (window) {
    'use strict';

    if(window.JCLiveItem)
        return;

    window.JCLiveItem = function (arParams) {

        this.arParams = arParams;
        this.idItem = this.arParams.ID_ITEM;
        this.iblockID = this.arParams.IBLOCK;
        this.iblockCommentID = this.arParams.IBLOCK_COMMENT;
        this.coverFrameBlock =      this.arParams.IDS.COVER_BLOCK_FRAME;
        this.vBlock =               this.arParams.IDS.VIDEO_BLOCK_ID;
        this.lBlock =               this.arParams.IDS.LIKE_BLOCK_ID;
        this.linfoblock =           this.arParams.IDS.LIKE_INFO_BLOCK;
        this.buttonComment =        this.arParams.IDS.ADD_COMMENT;
        this.formComment =          this.arParams.IDS.FORM_COMMENT;
        this.commentID =            this.arParams.IDS.COMMENT_ID;
        this.butComment =           this.arParams.IDS.COMMENT_BUTTON_ID;
        this.repostBut =            this.arParams.IDS.REPOST_BLOCK_ID;
        this.shareBlock =           this.arParams.IDS.SHARE_BLOCK_ID;
        this.shareContent =         this.arParams.IDS.SHARE_CONTENT_BLOCK_ID;
        this.likeError =            this.arParams.IDS.LIKE_ERROR;
        this.resultCommentBlock =   this.arParams.IDS.RESULT_COMMENT_BLOCK;
        this.countLikesBlock =      this.arParams.IDS.COUNT_LIKES_BLOCK;
        this.idVideo = this.arParams.ID_VIDEO;
        this.title = this.arParams.TITLE;
        this.description = this.arParams.DESCRIPTION;
        this.image = this.arParams.IMG;
        this.mobile = this.arParams.MOBILE;
        this.ajaxURL = this.arParams.ajaxURL;
        this.USER = this.arParams.USER;
        this.VIEWED = this.arParams.US_VIEWED;
        this.COUNT_VIEW = this.arParams.COUNT_VIEW;
        this.timeUpdateInterval = '';
        this.support;
        this.animEndEventNames;
        this.onEndAnimation;
        this.eventtype;
        this.fullScreen = false;
        this.pauseProgress = false;
        this.width;
        this.height;
        this.defaultSize = 640;
        this.coeffPopup = 150;
        this.coeffPlayer = 150;
        this.coeffProgress = 40;

        BX.ready(BX.delegate(this.init, this));
    }

    window.JCLiveItem.prototype = {
        init: function() {
            $.fn.serializeObject = function()
            {
                var o = {};
                var a = this.serializeArray();
                $.each(a, function() {
                    if (o[this.name] !== undefined) {
                        if (!o[this.name].push) {
                            o[this.name] = [o[this.name]];
                        }
                        o[this.name].push(this.value || '');
                    } else {
                        o[this.name] = this.value || '';
                    }
                });
                return o;
            };
            this.player = {};
            this.statusVideo = -1;
            this.openVideo = false;
            this.share = Ya.share2(this.shareContent, {
                content: {
                    url: 'https://extreme-look.ru' + document.location.pathname,
                    title: this.title,
                    description: this.description,
                    image: this.image
                },
                theme: {
                    services: 'moimir,odnoklassniki,vkontakte,telegram,viber,whatsapp,twitter',
                    lang: 'ru',
                    limit: 7,
                    size: 'm',
                    bare: false,
                    shape: 'normal',
                    popupDirection: 'top',
                    popupPosition: 'outer',
                    curtain: true
                },
                hooks: {
                    onshare: BX.delegate(this.onShare, this)
                }
            });
            BX.bind(BX(this.vBlock), 'click', BX.delegate(this.openFrameVideo, this));
            BX.bind(BX(this.lBlock), 'click', BX.delegate(this.setLikes, this));
            BX.bind(BX(this.butComment), 'click', BX.delegate(this.addFormComment, this));
            BX.bind(BX(this.buttonComment), 'click', BX.delegate(this.setComment, this));
            $(".live-comment-like").on( "click", $.proxy(this.likeComment, this));
            $(".live-comment-button").on( "click", $.proxy(this.answerComment, this));
            BX.bind(BX(this.likeError), 'click', BX.delegate(function (event) {
                event.stopPropagation();
            }, this));
            BX.bind(BX(this.repostBut), 'click', BX.delegate(function (event) {
                if($('#' + this.shareBlock).hasClass('d-none'))
                    $('#' + this.shareBlock).removeClass('d-none').addClass('d-flex');
                else
                    $('#' + this.shareBlock).removeClass('d-flex').addClass('d-none');
            }, this));
            BX.bind(document, 'click', BX.delegate(function(event) {
                if(event.target.id !== this.repostBut)
                    if($('#' + this.shareBlock).hasClass('d-flex'))
                        $('#' + this.shareBlock).removeClass('d-flex').addClass('d-none');
            }, this));
            this.support = { animations : window.Modernizr.cssanimations };
            this.animEndEventNames = { 'WebkitAnimation' : 'webkitAnimationEnd', 'OAnimation' : 'oAnimationEnd', 'msAnimation' : 'MSAnimationEnd', 'animation' : 'animationend' };
            this.animEndEventName = this.animEndEventNames[ window.Modernizr.prefixed( 'animation' ) ];
            this.onEndAnimation = function( el, callback ) {
                var onEndCallbackFn = function( ev ) {
                    if( support.animations ) {
                        if( ev.target != this ) return;
                        this.removeEventListener( this.animEndEventName, onEndCallbackFn );
                    }
                    if( callback && typeof callback === 'function' ) { callback.call(); }
                };
                if( this.support.animations ) {
                    el.addEventListener( this.animEndEventName, onEndCallbackFn );
                }
                else {
                    onEndCallbackFn();
                }
            };
            this.eventtype = this.mobilecheck() ? 'touchstart' : 'click';
        },
        mobilecheck: function() {
            var check = false;
            (function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
            return check;
        },
        clickCover: function(){
            this.YT_Player_YouTube('coverPopupShow')
        },
        openFrameVideo: function (event){
            if(event.srcElement.id === 'full-screen')
                this.fullScreen = true;
            else
                this.fullScreen = false;
            var _this = this, popup = BX.PopupWindowManager.create("live-video-popup", null, {
                content: BX.create('div', {
                    attrs:{
                        className: 'live-main-block-frame'
                    },
                    children: [
                        BX.create('DIV', {
                            props: {
                                id: this.coverFrameBlock
                            },
                            style:{
                                position: 'absolute',
                                'min-width': '100%',
                                'min-height': '100%',
                            },
                        }),
                        BX.create('DIV', {
                            props: {
                                id: 'live-video-popup-content'
                            }
                        }),
                        BX.create('DIV', {
                            attrs:{
                                id: 'live-line-progress'
                            },
                            style:{
                                width: this.mobile == 'Y' ? window.screen.width - 80 + 'px' : this.fullScreen ? window.screen.height - this.coeffPlayer - this.coeffProgress + 'px' : '600px'
                            }
                        }),
                        BX.create('DIV', {
                            attrs: {
                                id: 'live-progress-viewed'
                            },
                            children:[
                                BX.create('DIV', {
                                    attrs:{
                                        id: 'live-progress-viewed-duration'
                                    },
                                    text: '0:00'
                                })
                            ]
                        }),
                        BX.create('DIV', {
                            attrs: {
                                id: 'live-progress-hidden'
                            },
                            style:{
                                width: this.mobile == 'Y' ? window.screen.width - 80 + 'px' : this.fullScreen ? window.screen.height - this.coeffPlayer - this.coeffProgress + 'px' : '600px'
                            },
                            events: {
                                click: BX.proxy(this.progress, this)
                            }
                        }),
                        BX.create('DIV', {
                            attrs: {
                                id: 'live-name-video-player'
                            },
                            text: this.title
                        }),
                        BX.create('DIV', {
                            attrs: {
                                className: 'align-items-center live-count-detail-video-player d-none d-lg-flex'
                            },
                            text: this.COUNT_VIEW
                        })
                    ]
                }),
                width: this.mobile == 'Y' ? window.screen.width - 20 : this.fullScreen ? window.screen.height - this.coeffPopup : this.defaultSize,
                height: this.mobile == 'Y' ? window.screen.width - 10 : this.fullScreen ? window.screen.height - this.coeffPopup : this.defaultSize,
                zIndex: 100,
                closeIcon: {
                    opacity: 1
                },
                //titleBar: this.title,
                closeByEsc: true, // закрытие окна по esc
                darkMode: true, // окно будет светлым или темным
                autoHide: true, // закрытие при клике вне окна
                draggable: true, // можно двигать или нет
                resizable: false, // можно ресайзить
                min_height: this.fullScreen ? window.screen.height : this.defaultSize, // минимальная высота окна
                min_width: this.fullScreen ? window.screen.height : this.defaultSize, // минимальная ширина окна
                lightShadow: false, // использовать светлую тень у окна
                angle: false, // появится уголок
                overlay: {
                    backgroundColor: 'black',
                    opacity: 500
                },
                events: {
                    onPopupShow: function () {
                        $(this.popupContainer).css('width', _this.mobile == 'Y' ? window.screen.width - 20 : _this.fullScreen ? window.screen.height - _this.coeffPopup : _this.defaultSize);
                        $(this.popupContainer).css('height', _this.mobile == 'Y' ? window.screen.width - 10 : _this.fullScreen ? window.screen.height - _this.coeffPopup : _this.defaultSize);
                        $('#live-progress-hidden').css('width', _this.mobile == 'Y' ? window.screen.width - 80 + 'px' : _this.fullScreen ? window.screen.height - _this.coeffPlayer - _this.coeffProgress + 'px' : '600px');
                        $('#live-line-progress').css('width', _this.mobile == 'Y' ? window.screen.width - 80 + 'px' : _this.fullScreen ? window.screen.height - _this.coeffPlayer - _this.coeffProgress + 'px' : '600px');
                        if(_this.fullScreen) $('#live-video-popup .popup-window-close-icon').css('top', '20px');
                        else $('#live-video-popup .popup-window-close-icon').css('top', '-30px');

                        BX.delegate(_this.YT_Player_YouTube('popupShow'), _this);
                    },
                    onPopupClose: function () {
                        BX.delegate(_this.YT_Player_YouTube('popupClose'), _this);
                    }
                }
            });
            popup.show();
            if(!this.openVideo)
                this.setView();

        },
        YT_Player_YouTube: function (action){

            this.width = this.mobile == 'Y' ? window.screen.width - 40 : this.fullScreen ? window.screen.height - this.coeffPlayer : this.defaultSize;
            this.height = this.mobile == 'Y' ? window.screen.width - 40 : this.fullScreen ? window.screen.height - this.coeffPlayer : this.defaultSize;

            switch (this.statusVideo) {
                case -1: //-1 Воспроизведение не начато
                    if(action == 'popupShow') //Запуск видео только после открытия окна
                        this.onYouTubeIframeAPIReady();
                    break;
                case 0: //0 Воспроизведение завершено
                    this.player.setSize(this.width, this.height);
                    if(action == 'popupShow' || action == 'coverPopupShow') { //Запуск видео только после открытия окна или по клику на cover
                        this.player.setPlaybackQuality(this.fullScreen ? 'hd1080' : 'hd720');
                        this.player.playVideo();
                    }
                    break;
                case 1: //1 Воспроизведение
                    if(action == 'popupClose' || action == 'coverPopupShow') { //Ставим на паузу только после закрытия или по клику на cover
                        this.player.pauseVideo();
                        this.pauseProgress = true;
                    }
                    break;
                case 2: //2 Пауза
                    this.player.setSize(this.width, this.height);
                    if(action == 'popupShow' || action == 'coverPopupShow') { //Запуск видео только после открытия окна или по клику на cover
                        this.player.setPlaybackQuality(this.fullScreen ? 'hd1080' : 'hd720');
                        this.player.playVideo();
                    }
                    break;
                default:
                    console.log("Ошибка");
            }
        },
        onYouTubeIframeAPIReady: function(){
            var _this = this;
            this.player = new YT.Player('live-video-popup-content', {
                height: this.mobile == 'Y' ? window.screen.width - 40 : this.fullScreen ? window.screen.height - this.coeffPlayer : this.defaultSize,
                width: this.mobile == 'Y' ? window.screen.width - 40 : this.fullScreen ? window.screen.height - this.coeffPlayer : this.defaultSize,
                videoId: this.idVideo,
                playerVars: {
                    'autoplay': 1,
                    'disablekb': 1,
                    'controls': 0,
                    'enablejsapi': 1,
                    'iv_load_policy': 3,
                    'fs': 0,
                    'modestbranding': 0,
                    'rel': 0,
                    'showinfo': 0,
                    'loop': 1,
                    'playlist': [this.idVideo],
                    'origin': location.hostname
                },
                events: {
                    'onReady': BX.delegate(_this.onPlayerReady, _this),
                    'onStateChange': BX.delegate(_this.onStateChangePlayer, _this),
                    'onError': BX.delegate(_this.onError, _this)
                }
            });

            //https://developers.google.com/youtube/player_parameters.html?playerVersion=HTML5&hl=ru#Parameters
            //https://developers.google.com/youtube/iframe_api_reference?hl=ru#Getting_Started
        },
        onPlayerReady: function (event){
            event.target.setVolume(100);
            event.target.playVideo();
            BX.bind(BX(this.coverFrameBlock), 'click', BX.delegate(this.clickCover, this));
            this.timeUpdateInterval = setInterval(function () {
                if(!this.pauseProgress)
                    this.JCLiveItem.prototype.updateProgressBar(event);
            }, 1000);
        },
        onError: function(event){
            if(event.data === "150" || event.data === "101"){ //Не всегда с первого раза запускается, необходимо пересоздать плеер
                event.target.destroy();
                this.onYouTubeIframeAPIReady();
            }
        },
        updateProgressBar: function(event){
            var line_width = $('#live-line-progress').width();
            var persent = (event.target.getCurrentTime() / event.target.getDuration());
            var minutes = Math.floor(Math.floor(event.target.getCurrentTime()) / 60) - (Math.floor(Math.floor(event.target.getCurrentTime()) / 60 / 60) * 60);
            var seconds = Math.floor(event.target.getCurrentTime()) % 60 < 10 ? ':0'+ Math.floor(event.target.getCurrentTime()) % 60 : ':' + Math.floor(event.target.getCurrentTime()) % 60;
            $('#live-progress-viewed').css('width', persent * line_width);
            $('#live-progress-viewed-duration').text(minutes + '' + seconds);

        },
        progress: function(event){
            var line_width = $('#live-line-progress').width();
            var persent = (this.player.getCurrentTime() / this.player.getDuration());
            var minutes = Math.floor(Math.floor(this.player.getCurrentTime()) / 60) - (Math.floor(Math.floor(this.player.getCurrentTime()) / 60 / 60) * 60);
            var seconds = Math.floor(this.player.getCurrentTime()) % 60 < 10 ? ':0'+ Math.floor(this.player.getCurrentTime()) % 60 : ':' + Math.floor(this.player.getCurrentTime()) % 60;
            $('#live-progress-viewed').css('width', persent * line_width);
            $('#live-progress-viewed-duration').text(minutes + '' + seconds);

            this.player.seekTo(this.player.getDuration() * ((event.pageX - $('#live-line-progress').offset().left) / $('#live-line-progress').width()));
        },
        onStateChangePlayer: function (event){ //Событие реагирующее на действия с видео
            this.statusVideo = event.data;
        },
        setView: function (){
            var data = {
                'action': 'setView',
                'id': this.idItem,
                'iblock': this.iblockID
            };

            if(this.USER != 'N'){ //Авторизован
                if(this.VIEWED === 'Y') { //Авторизован Просмотрено
                    return;
                }
                else{ //Авторизован Не просмотрено
                    data['user'] = 'Y';
                    data['userID'] = this.USER.ID
                }
            }else{ //Не авторизован
                data['user'] = 'N'; //Ставим пометку чтобы аякс знал что пользователь не авторизован
                if(typeof localStorage.viewLive !== "undefined"){ //Есть записи о просмотрах в локальном хранилище браузера
                    var ls = JSON.parse(localStorage.viewLive); //Парсим хранилище
                    if (typeof ls[this.idItem] !== "undefined") {//Не авторизован видео просмотрено
                        return; //Счётчик не обновляем
                    }else{ //Не авторизован видео НЕ просмотрено
                        ls[this.idItem] = "Y"; //Добавляем ключ объекта с ИД видео
                        localStorage.viewLive = JSON.stringify(ls); //Добавляем в локальное хранилище
                    }
                }else{ //Нет записей в локальном хранилище
                    var obj = {}; //Создаём объект для локального хранилища
                    obj[data['id']] = 'Y'; //Создаём первый ключ с ИД видео
                    localStorage.viewLive = JSON.stringify(obj); //Заносим в локальное хранилище
                }
            }
            BX.ajax({
                url: this.ajaxURL,
                method: 'POST',
                dataType: 'json',
                timeout: 60,
                data,
                onsuccess: BX.delegate(function (result) { console.log(result.error) }),
                onfailure: BX.delegate(function (result) { console.log(result); })
            });

            this.openVideo = true; //Ставим метку на этой странице чтобы при выключении и включении видео функция не срабатывала
        },
        setLikes: function (){
            var data = {
                'action': 'setLike',
                'id': this.idItem,
                'iblock': this.iblockID
            };
            if(this.USER == 'N'){
                $(".live-error-likes").html('Чтобы ставить лайки пожалуста <a href="/personal/private/?login=yes">авторизуйтесь</a> или <a href="/personal/private/?register=yes">зарегестрируйтесь</a> на сайте').animate({
                    opacity: 1,
                    left: 0,
                }, 500, function() {
                    setTimeout(function () {
                        $(".live-error-likes").animate({
                            opacity: 0,
                            left: "-600px",
                        }, 500);
                    },2000);
                });
            }else{
                if(!$('#' + this.linfoblock).hasClass('live-liked')){
                    var _this = this;
                    data['user'] = 'Y';
                    data['userID'] = this.USER.ID;
                    BX.ajax({
                        url: this.ajaxURL,
                        method: 'POST',
                        dataType: 'json',
                        timeout: 60,
                        data,
                        onsuccess: BX.delegate(function (result) {
                            $('#' + _this.linfoblock).addClass('live-liked');
                            $('#' + _this.countLikesBlock).text(result.data);
                        }),
                        onfailure: BX.delegate(function (result) { console.log(result); })
                    });
                }else{ //Лайк уже поставлен пользователем
                    $(".live-error-likes").text('Вы уже ставили лайк').animate({
                        opacity: 1,
                        left: 0
                    }, 500, function() {
                        setTimeout(function () {
                            $(".live-error-likes").animate({
                                opacity: 0,
                                left: "-600px"
                            }, 500);
                        },2000);
                    });
                }
            }
        },
        addFormComment: function(){
            $('textarea[name="LIVE_TEXT_COMMENT"]').focus();
        },
        setComment: function(){
            var dataForm = $('#' + this.formComment).serializeObject();
            if(this.validCommentForm(dataForm)){
                var data = {
                    'action': 'setComment',
                    'id': this.idItem,
                    'iblock': this.iblockCommentID,
                    'name': dataForm.LIVE_NAME_COMMENT,
                    'text': dataForm.LIVE_TEXT_COMMENT,
                    'user': this.USER == 'N' ? 'N' : this.USER.ID
                }, _this = this;
                $('#' + this.buttonComment).prop("disabled", true);
                BX.ajax({
                    url: this.ajaxURL,
                    method: 'POST',
                    dataType: 'json',
                    timeout: 60,
                    data,
                    async: false,
                    onsuccess: BX.delegate(function (result) {
                        if(result.error === 'N'){
                            $('#' + _this.formComment)[0].reset();
                            $('#' + _this.resultCommentBlock).animate({
                                width: '100%'
                            }, 500, function() {
                                setTimeout(function () {
                                    $('#' + _this.resultCommentBlock).animate({
                                        width: 0,
                                    }, 500, function(){
                                        $('#' + _this.buttonComment).prop("disabled", false);
                                    }).text('').removeClass('p-5');
                                },5000);
                            }).addClass('p-5').text('Комментарий успешно оставлен. Он появится после прохождения модерации');
                        }
                        else{
                            console.log('Ошибка');
                        }
                    }),
                    onfailure: BX.delegate(function (result) { console.log(result); })
                });
            }
        },
        validCommentForm: function(data){
            for(var key in data) {
                var tag = key == 'LIVE_TEXT_COMMENT' ? 'textarea' : 'input',
                    input = $("form#" + this.formComment + " " + tag + "[name=" + key + "]");
                if(data[key].trim() === ''){
                    if(key == 'LIVE_CAPTCHA')
                        input.addClass('is-invalid');
                    else
                        input.addClass('is-invalid').after($('<div class="invalid-feedback">Поле обязательно к заполнению</div>'));
                    return false;
                }
                else{
                    if(input.hasClass('is-invalid')){
                        if(key == 'LIVE_CAPTCHA')
                            input.removeClass('is-invalid');
                        else
                            input.removeClass('is-invalid').next().remove();
                    };
                }
                if(key == 'LIVE_CAPTCHA'){
                    var captcha = {
                        'action': 'checkCaptcha',
                        'user': this.USER,
                        'captcha_word': data[key],
                        'captcha_code': data['CAPTCHA_CODE']
                    }, resultComm = false;

                    BX.ajax({
                        url: this.ajaxURL,
                        method: 'POST',
                        dataType: 'json',
                        timeout: 60,
                        data: captcha,
                        async: false,
                        onsuccess: BX.delegate(function (result) {
                            if(result.error === 'Y'){
                                resultComm = false;
                                $('input[name="LIVE_CAPTCHA"]').addClass('is-invalid').after($('<div style="top:-15px;" class="position-absolute invalid-feedback">Капча введена неверно</div>'));
                            }
                            else{
                                if($('input[name="LIVE_CAPTCHA"]').hasClass('is-invalid'))
                                    $('input[name="LIVE_CAPTCHA"]').removeClass('is-invalid').next().remove()
                                resultComm = true;
                            }
                        }),
                        onfailure: BX.delegate(function (result) { this.statusComment = false; })
                    });
                }else resultComm = true;
            }
            return resultComm;
        },
        onShare: function(name){
            if($('#' + this.shareBlock).hasClass('d-flex'))
                $('#' + this.shareBlock).removeClass('d-flex').addClass('d-none');
            var share = {
                'action': 'setShare',
                'user': this.USER,
                'id': this.idItem,
                'iblock': this.iblockID
            };
            BX.ajax({
                url: this.ajaxURL,
                method: 'POST',
                dataType: 'json',
                timeout: 60,
                data: share,
                onsuccess: BX.delegate(function (result) {
                    if(result.error === 'N')
                        console.log(result);
                }),
                onfailure: BX.delegate(function (result) { console.log(result); })
            });
        },
        answerComment: function(event){
            $('textarea[name="LIVE_TEXT_COMMENT"]').focus().val('@' + $('#' + this.commentID + '_' + $(event.target).data('id-comment')).find('.live-comment-user-name').text() + '\r\n');
        },
        likeComment: function(event){
            if(this.USER !== 'N'){
                if(!$(event.target).hasClass('liked')){
                    var data = {
                        'action': 'setLikeComment',
                        'user': this.USER == 'N' ? 'N' : this.USER.ID,
                        'id': $(event.target).data('id-comment'),
                        'iblock': this.iblockCommentID
                    }, target = event.target;
                    BX.ajax({
                        url: this.ajaxURL,
                        method: 'POST',
                        dataType: 'json',
                        timeout: 60,
                        data: data,
                        onsuccess: BX.delegate(function (result) {
                            if(result.error === 'N'){
                                console.log(result.debug);
                                $(target).addClass('liked').next().text('- ' + result.data + ' нравится');
                            }
                        }),
                        onfailure: BX.delegate(function (result) { console.log(result); })
                    });
                }else{
                    $(event.target).prev().show().animate({
                        opacity: 1
                    }, 500, function(){
                        setTimeout(function () {
                            $(event.target).prev().animate({
                                opacity: 0
                            }, 500);
                        },2000);
                    }).text('Вы уже ставили лайк на этот комментарий');
                }
            }else{
                $(event.target).prev().show().animate({
                    opacity: 1
                }, 500, function(){
                    setTimeout(function () {
                        $(event.target).prev().animate({
                            opacity: 0
                        }, 500);
                    },2000);
                }).html('Чтобы ставить лайки пожалуста <a href="/personal/private/?login=yes">авторизуйтесь</a> или <a href="/personal/private/?register=yes">зарегестрируйтесь</a> на сайте');
            }
        }
    }
})(window);

