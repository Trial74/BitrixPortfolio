(function(window){
    'use strict';

    if(window.JCFormFeedBack)
        return;

    window.JCFormFeedBack = function(arParams) {
                this.ajax = '/ajax/form-ajax.php';
        this.AJdata = {};
        this.formFeed = arParams.IDS.FORM_ID;
        this.formFeedButton = arParams.IDS.BUTTON_ID;
        this.formFeedObjectInpts = {};
        BX.ready(BX.delegate(this.init, this));
    };

    window.JCFormFeedBack.prototype = {
        init: function() {
            $.fn.serializeObject = function(){
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
                $('#'+$(this).attr('id') + ' input[type="checkbox"]:not(:checked)').each(function(){
                    o[this.name] = 'off';
                });
                return o;
            };
            this.formsucc = new BX.PopupWindow('form-succ-feed', window.body, {
                titleBar: {
                    content: BX.create("DIV", {
                        html: 'Спасибо<br />Заявка успешно отправлена',
                        props: {'className': 'mix-nav-popup'}
                    })
                },
                content:
                    BX.create('DIV', {
                        props: {
                            'className': 'mix-popup-content'
                        },
                        text: 'Ваше обращение будет рассмотрено в ближайшее время',
                        children: [
                            BX.create('DIV', {
                                props: {
                                    'className': 'mix-form-button-block mix-popup-price-block mix-flex'
                                },
                                events: {
                                    click: BX.proxy(function(e){ this.formsucc.close(); }, this)
                                },
                                text: 'Ок'
                            })
                        ]
                    }),
                autoHide : true,
                offsetTop : 1,
                offsetLeft : 0,
                lightShadow : true,
                closeIcon : true,
                closeByEsc : true,
                overlay: {
                    backgroundColor: '#888694',
                    opacity: '80'
                }
            });
            BX.bind(BX(this.formFeedButton), 'click', BX.delegate(this.pushButton, this));
        },
        pushButton: function(){
            if(this.validateForm()){
                this.AJdata['action'] = 'get-feedback';
                delete this.formFeedObjectInpts['CONTACTS-PERS']
                this.AJdata['data'] = this.formFeedObjectInpts;
                this.setForm(this.AJdata, this.formsucc);
            }
        },
        validateForm: function (){
            this.formFeedObjectInpts = $('#'+this.formFeed).serializeObject();
            for(var key in this.formFeedObjectInpts) {
                if(key.split('-')[1] == 'NAME'){
                    if(this.formFeedObjectInpts[key] === ''){
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'add', 'Поле обязательно для заполнения');
                        return;
                    }
                    if(!this.validateName(this.formFeedObjectInpts[key])){
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'add', 'Неверный формат имени');
                        return
                    }
                    if($('#'+this.formFeed+" input[name=" + key + "]").hasClass('is-invalid'))
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'rem');
                }
                if(key.split('-')[1] == 'PHONE'){
                    if(this.formFeedObjectInpts[key] === ''){
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'add', 'Поле обязательно для заполнения');
                        return;
                    }
                    if(!this.validatePhone(this.formFeedObjectInpts[key])){
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'add', 'Неверный формат телефона');
                        return
                    }
                    if($('#'+this.formFeed+" input[name=" + key + "]").hasClass('is-invalid'))
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'rem');
                }
                if(key.split('-')[1] == 'MAIL'){
                    if(this.formFeedObjectInpts[key] === ''){
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'add', 'Поле обязательно для заполнения');
                        return;
                    }
                    if(!this.validateMail(this.formFeedObjectInpts[key])){
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'add', 'Неверный формат почты');
                        return
                    }
                    if($('#'+this.formFeed+" input[name=" + key + "]").hasClass('is-invalid'))
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'rem');
                }
                if(key.split('-')[1] == 'PERS'){
                    if(this.formFeedObjectInpts[key] == 'off'){
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'add', 'Дайте согласие на обработку персональных данных');
                        return;
                    }
                    if($('#'+this.formFeed+" input[name=" + key + "]").hasClass('is-invalid'))
                        this.messageErr($('#'+this.formFeed+" input[name=" + key + "]"), 'rem');
                }
            }
            return true;
        },
        setForm: function(data, formsucc = '') {
            BX.ajax.post(
                this.ajax,
                data,
                function(data){
                    var result = JSON.parse(data);
                    if(!result.error){

                        formsucc.show();
                    }
                    else{
                        console.log(result);
                    }
                });
        },
        messageErr: function(input, action, message = ''){
            if(action == 'add'){
                if(input.hasClass('is-invalid'))
                    input.removeClass('is-invalid').next().remove();
                input.addClass('is-invalid').after($('<div class="invalid-feedback" style="display: block;">' + message + '</div>'));
                return true
            }
            if(action == 'rem'){
                if(input.hasClass('is-invalid'))
                    input.removeClass('is-invalid').next().remove();
            }
            return false;
        },
        validateMail: function(mail){
            var regex = '^[-\\w.]+@([A-z0-9][-A-z0-9]+\\.)+[A-z]{2,4}$'
            return mail.match(regex);
        },
        validatePhone: function(phone){
            var regex = '^(\\+?(\\d{1,3})?[\\- ]?)?(\\(?\\d{3}\\)?[\\- ]?)?[\\d\\- ]{7,10}$'
            return phone.match(regex);
        },
        validateName: function(name){
            var regex = '^[a-zA-Zа-яА-Я][a-zA-Zа-яА-Я0-9-_\\.]{1,20}$'
            return name.match(regex);
        },
        clearForm: function() {
            $('#'+this.formFeed).trigger("reset");
        }
    };
})(window);