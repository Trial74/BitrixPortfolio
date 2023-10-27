(function (window){
    'use strict';

    if(window.TelegramBotExtreme)
        return;

    window.TelegramBotExtreme = function (arParams) {
        this.visual = arParams.IDS;
        this.ajaxPush = '/bitrix/admin/extremelook_push_ajax.php';
        if(arParams.INIT === 'Y') this.init();
        else {
            this.date = new Date();
            this.initManager();
        }
    }
    window.TelegramBotExtreme.prototype = {
        init: function () {
            BX.bind(BX(this.visual.MESSAGE_PUSH), 'keyup', BX.proxy(this.btnSendDis, this));
            BX.bind(BX(this.visual.BUTTON_SEND), 'click', BX.proxy(this.send, this));
            BX.bind(BX(this.visual.BUTTON_SEND_ADMIN), 'click', BX.proxy(this.sendAdmin, this));
        },
        initManager: function () {
            var buttonsFileUser = Array.from(document.querySelectorAll('button.button-file-user'));

            document.getElementById(this.visual.DATE_INPUT).valueAsDate = this.date;

            BX.bind(BX(this.visual.OPEN_FILES), 'click', BX.proxy(this.getFilesByDate, this));
            BX.bind(BX(this.visual.DATE_INPUT), 'change', BX.proxy(this.getCountFilesByDate, this));

            for(var keyH in buttonsFileUser)
                BX.bind(BX(buttonsFileUser[keyH]), 'click', BX.proxy(this.getFilesByUser, this));

            this.getCountFilesByDate();
        },
        addUser: function (id) {
            if(!BX(this.visual.ARRUSERS).value.trim()){
                BX(this.visual.ARRUSERS).value = id;
                this.btnSendDis()
            }
            else
                BX(this.visual.ARRUSERS).value += ',' + id;
        },
        clear: function () {
            BX(this.visual.ARRUSERS).value = "";
            this.btnSendDis()
        },
        btnSendDis: function () {
            if(BX(this.visual.ARRUSERS).value.trim() && BX(this.visual.MESSAGE_PUSH).value.trim()){
                BX.adjust(BX(this.visual.BUTTON_SEND), {props: {disabled: false}});
            }
            else{
                BX.adjust(BX(this.visual.BUTTON_SEND), {props: {disabled: true}});
            }
            if(BX(this.visual.MESSAGE_PUSH).value.trim()){
                BX.adjust(BX(this.visual.BUTTON_SEND_ADMIN), {props: {disabled: false}});
            }
            else{
                BX.adjust(BX(this.visual.BUTTON_SEND_ADMIN), {props: {disabled: true}});
            }

        },
        sendAdmin: function (e) {
            this.send(e, true);
        },
        sendUser: function (e) {
            this.send(e, false);
        },
        send: function(e, admin = false){
            var ids = BX(this.visual.ARRUSERS).value,
                select = document.getElementById(this.visual.SELECT_FORMATS),
                arrData = {},
                _this = this;
            arrData['action']   = e.target.dataset.send;
            arrData['format']   = select.options[select.selectedIndex].value;
            arrData['ids']      = admin ? [499750619] : ids.split(',');
            arrData['message']  = BX(this.visual.MESSAGE_PUSH).value;
            arrData['photo']    = BX(this.visual.URL_IMAGE).value;

            BX.ajax.post(
                this.ajaxPush,
                arrData,
                BX.proxy(function (data) {
                        var resultOBJ = JSON.parse(data),
                            result = resultOBJ.result;

                        _this.clear();
                        if(result){
                            BX.adjust(BX(_this.visual.REZULT_MESSAGE), {
                                html: 'Всего отправлено PUSH уведомлений: ' + result.all + '<br />Удачно отправленных: ' + result.succ + '<br />Неудачно отправленнх: ' + result.fail
                            });
                            BX.adjust(BX(_this.visual.REZULT_BLOCK), {
                                style: {'display': 'block'}
                            });
                        }else{
                            BX.adjust(BX(_this.visual.REZULT_MESSAGE), {
                                html: 'Ошибка'
                            });
                            BX.adjust(BX(_this.visual.REZULT_BLOCK), {
                                style: {'display': 'block'}
                            });
                        }
                    }
                ), this);
        },
        getCountFilesByDate: function () {
            var result, arrData = {action: 'countFiles',
                date: BX(this.visual.DATE_INPUT).value.replace(/(\d*)-(\d*)-(\d*)/, '$3-$2-$1')},
                domCounter = BX(this.visual.COUNTER),
                domButton = BX(this.visual.OPEN_FILES);

            if(!BX.hasClass(domButton, 'ui-btn-disabled')){
                BX.addClass(domButton, 'ui-btn-disabled');
                BX.addClass(domButton, 'ui-btn-wait');
            }

            BX.ajax.post(
                "/api/telegram/admin.php",
                arrData,
                BX.proxy(function (data) {
                        var resultOBJ = JSON.parse(data),
                            result = resultOBJ.result,
                            error = resultOBJ.error;

                        if(!error){
                            BX.adjust(domCounter, {text: result});
                            if(BX.hasClass(domButton, 'ui-btn-disabled')){
                                BX.removeClass(domButton, 'ui-btn-disabled');
                                BX.removeClass(domButton, 'ui-btn-wait');
                            }
                        }
                        else
                            BX.adjust(domCounter, {text: 'error'});

                    }
                ), this);
        },
        getFilesByDate: function (e) {
            this.openSlidePanel({
                action: 'getFiles',
                date: BX(this.visual.DATE_INPUT).value.replace(/(\d*)-(\d*)-(\d*)/, '$3-$2-$1')
            });
        },
        getFilesByUser: function (e) {
            this.openSlidePanel({
                action: 'getFilesUser',
                user: BX(e.target).dataset['user']
            });
        },
        openSlidePanel: function (arrData) {
            BX.SidePanel.Instance.open("files.telegram.bot", {
                cacheable: false,
                mobileFriendly: true,
                contentCallback: function(slider) {
                    return new Promise(function(resolve, reject) {
                        BX.ajax.post(
                            "/api/telegram/admin.php",
                            arrData,
                            BX.proxy(function (data) {
                                    var resultOBJ = JSON.parse(data);
                                    resolve(resultOBJ.result);
                                }
                            ), this);
                    });
                }
            });
        }
    }

})(window);