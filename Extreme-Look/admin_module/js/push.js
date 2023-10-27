(function (window){
    'use strict';

    if(window.PUSHNotificationExtreme)
        return;

    window.PUSHNotificationExtreme = function (arParams) {
        this.admins     = arParams.ADMINS;
        this.roznica    = arParams.ROZNICA;
        this.partners   = arParams.PARTNERS;
        this.all        = arParams.ALLUSERS;
        this.visual     = arParams.IDS;
        this.countParts = arParams.COUNT_PARTS;
        this.ajaxPush   = '/bitrix/admin/extremelook_push_ajax.php';
        this.init();
    }
    window.PUSHNotificationExtreme.prototype = {
        init: function() {
            if(!!this.countParts.COUNT && !!this.visual.COUNT_BLOCK)
                BX.adjust(BX(this.visual.COUNT_BLOCK), {html: "<b>" + this.countParts.MESSAGE + this.countParts.COUNT + "</b>"});

            BX.bind(BX(this.visual.ADDADMIN), 'click', BX.proxy(this.addAdmins, this));
            BX.bind(BX(this.visual.ADDROZ), 'click', BX.proxy(this.addRoznica, this));
            BX.bind(BX(this.visual.ADDPART), 'click', BX.proxy(this.addPartners, this));
            BX.bind(BX(this.visual.ADDALL), 'click', BX.proxy(this.addAll, this));
            BX.bind(BX(this.visual.CLEAR), 'click', BX.proxy(this.clear, this));

            BX.bind(BX(this.visual.TITTLE_PUSH), 'keyup', BX.proxy(this.inputTittle, this));
            BX.bind(BX(this.visual.MESSAGE_PUSH), 'keyup', BX.proxy(this.inputMessage, this));

            BX.bind(BX(this.visual.SEND), 'click', BX.proxy(this.send, this));
        },
        inputTittle: function(e){
            var count = BX(e.target).value.length;
            BX.adjust(BX(this.visual.LABEL_COUNT), {text: count + '/60'});
            if(count > 35 && count < 45)
                BX.adjust(BX(this.visual.LABEL_COUNT), {props: {className : 'label-warning'}});
            if(BX(e.target).value.length > 55)
                BX.adjust(BX(this.visual.LABEL_COUNT), {props: {className : 'label-warningg'}});
            if(count < 35)
                BX.adjust(BX(this.visual.LABEL_COUNT), {props: {className : ''}});
            this.btnSendDis();
        },
        inputMessage: function(e){
            var count = BX(e.target).value.length;
            BX.adjust(BX(this.visual.TEXTAREA_COUNT), {text: count + '/160'});
            if(count > 130 && count < 150)
                BX.adjust(BX(this.visual.TEXTAREA_COUNT), {props: {className : 'label-warning'}});
            if(BX(e.target).value.length > 150)
                BX.adjust(BX(this.visual.TEXTAREA_COUNT), {props: {className : 'label-warningg'}});
            if(count < 130)
                BX.adjust(BX(this.visual.TEXTAREA_COUNT), {props: {className : ''}});
            this.btnSendDis();
        },
        addAdmins: function(){
            this.addUsers(this.admins);
        },
        addRoznica: function(){
            this.addUsers(this.roznica);
        },
        addPartners: function(){
            this.addUsers(this.partners);
        },
        addAll: function(){
            this.clear();
            this.addUsers(this.all);
        },
        addUsers: function (arrval) {
            var chekAdd = BX(this.visual.ADDDEL).checked;
            if(chekAdd){
                if(!BX(this.visual.ARRUSERS).value.trim()){
                    BX(this.visual.ARRUSERS).value = arrval;
                    this.btnSendDis();
                }
                else{
                    BX(this.visual.ARRUSERS).value += ',';
                    BX(this.visual.ARRUSERS).value += arrval;
                    this.btnSendDis();
                }
            }
            else{
                this.clear();
                BX(this.visual.ARRUSERS).value = arrval;
                this.btnSendDis();
            }
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
            if(BX(this.visual.ARRUSERS).value.trim() && BX(this.visual.TITTLE_PUSH).value.trim() && BX(this.visual.MESSAGE_PUSH).value.trim()){
                BX.adjust(BX(this.visual.SEND), {props: {disabled: false}});
            }
            else{
                BX.adjust(BX(this.visual.SEND), {props: {disabled: true}});
            }
        },
        send: function(e){
            var ids = BX(this.visual.ARRUSERS).value,
                arrData = {},
                _this = this;
            arrData['action']   = e.target.dataset.send;
            arrData['ids']      = ids.split(',');
            arrData['tittle']   = BX(this.visual.TITTLE_PUSH).value;
            arrData['message']  = BX(this.visual.MESSAGE_PUSH).value;
            arrData['name_var'] = BX(this.visual.NAME_VARIABLE).value;
            arrData['url']      = BX(this.visual.URL_PUSH).value;
            arrData['image']    = BX(this.visual.URL_IMAGE).value;
            BX.ajax.post(
                this.ajaxPush,
                arrData,
                BX.proxy(function (data) {
                    var resultOBJ = JSON.parse(data),
                        result = resultOBJ.result,
                        count = 0, succ = 0, fail = 0;
                    while(count < result.length){
                        if(result[count].success)
                            succ++;
                        else
                            fail++;
                        count++;
                    }
                    _this.clear();
                    BX.adjust(BX(_this.visual.REZULT_MESSAGE), {
                        html: 'Всего отправлено PUSH уведомлений: ' + count + '<br />Удачно отправленных: ' + succ + '<br />Неудачно отправленнх: ' + fail
                    });
                    BX.adjust(BX(_this.visual.REZULT_BLOCK), {
                        style: {'display': 'block'}
                    });
                }
            ), this);
        }
    }

})(window);