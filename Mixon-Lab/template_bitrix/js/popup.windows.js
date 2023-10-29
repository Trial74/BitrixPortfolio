BX.ready(function(){
    var autoHide = true,
        offsetTop = 1,
        offsetLeft = 0,
        lightShadow = true,
        closeIcon = true,
        closeByEsc = true,
        overlay = {
            backgroundColor: '#888694',
            opacity: '80'
        },
        getOffer = new BX.PopupWindow('get-offer', window.body, {
            titleBar: {
                content: BX.create("DIV", {
                    html: 'Получить предложение',
                    'props': {'className': 'mix-nav-popup'}
                })
            },
            content: BX.create("DIV", {
                props: {'className': 'mix-popup-content'},
                children: [
                    BX.create('FORM', {
                        attrs: {
                            action: 'POST',
                            id: 'form-get-offer'
                        },
                        children: [
                            BX.create('DIV', {
                                props: {
                                    'className': 'text-field__icon text-field__icon_name'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'text-field__input form-control'
                                        },
                                        attrs: {
                                            type: 'text',
                                            placeholder: 'Имя',
                                            name: 'OFFER-NAME'
                                        }
                                    })
                                ]
                            }),
                            BX.create('DIV', {
                                props: {
                                    'className': 'text-field__icon text-field__icon_phone'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'text-field__input form-control'
                                        },
                                        attrs: {
                                            type: 'text',
                                            placeholder: 'Телефон',
                                            name: 'OFFER-PHONE'
                                        }
                                    })
                                ]
                            }),
                            BX.create('DIV', {
                                props: {
                                    'className': 'text-field__icon text-field__icon_email'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'text-field__input form-control'
                                        },
                                        attrs: {
                                            type: 'text',
                                            placeholder: 'Email',
                                            name: 'OFFER-MAIL'
                                        }
                                    })
                                ]
                            }),
                            BX.create('DIV', {
                                props: {
                                    'className': 'form-check'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'form-check-input'
                                        },
                                        attrs: {
                                            type: 'checkbox',
                                            id: 'label-check-offer',
                                            checked: 'checked',
                                            name: 'OFFER-PERS'
                                        }
                                    }),
                                    BX.create('LABEL', {
                                        props: {
                                            'className': 'form-check-label'
                                        },
                                        attrs: {
                                            for: 'label-check-offer'
                                        },
                                        text: 'Даю согласие на обработку персональных данных и соглашаюсь с политикой конфиденциальности'
                                    })
                                ]
                            })
                        ]
                    }),
                    BX.create('DIV', {
                        props: {
                            'className': 'mix-form-button-block mix-flex'
                        },
                        attrs: {
                            id: 'button-offer'
                        },
                        events: {
                            click: BX.proxy(function(){setForm('get-offer');}, this)
                        },
                        text: 'Отправить'
                    })
                ]
            }),
            autoHide: autoHide,
            offsetTop: offsetTop,
            offsetLeft: offsetLeft,
            lightShadow: lightShadow,
            closeIcon: closeIcon,
            closeByEsc: closeByEsc,
            overlay: overlay
        }),
        getPrice = new BX.PopupWindow('get-price', window.body, {
            titleBar: {
                content: BX.create("DIV", {
                    html: 'Узнать стоимость моей идеи',
                    props: {'className': 'mix-nav-popup'}
                })
            },
            content: BX.create("DIV", {
                props: {'className': 'mix-popup-content'},
                children: [
                    BX.create('FORM', {
                        attrs:{
                            action: 'POST',
                            id: 'form-get-price'
                        },
                        children:[
                            BX.create('DIV', {
                                props: {
                                    'className': 'text-field__icon text-field__icon_name'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'text-field__input form-control'
                                        },
                                        attrs: {
                                            type: 'text',
                                            placeholder: 'Имя',
                                            name: 'PRICE-NAME'
                                        }
                                    })
                                ]
                            }),
                            BX.create('DIV', {
                                props: {
                                    'className': 'text-field__icon text-field__icon_phone'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'text-field__input form-control'
                                        },
                                        attrs: {
                                            type: 'text',
                                            placeholder: 'Телефон',
                                            name: 'PRICE-PHONE'
                                        }
                                    })
                                ]
                            }),
                            BX.create('DIV', {
                                props: {
                                    'className': 'text-field__icon text-field__icon_email'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'text-field__input form-control'
                                        },
                                        attrs: {
                                            type: 'text',
                                            placeholder: 'Email',
                                            name: 'PRICE-MAIL'
                                        }
                                    })
                                ]
                            }),
                            BX.create('DIV', {
                                props: {
                                    'className': 'text-field__icon text-field__icon_proiz'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'text-field__input form-control'
                                        },
                                        attrs: {
                                            type: 'text',
                                            placeholder: 'Что будете производить?',
                                            name: 'PRICE-PROIZ'
                                        }
                                    })
                                ]
                            }),
                            BX.create('DIV', {
                                props: {
                                    'className': 'text-field__icon text-field__icon_obiem'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'text-field__input form-control'
                                        },
                                        attrs: {
                                            type: 'text',
                                            placeholder: 'Примерный объём',
                                            name: 'PRICE-OBIEM'
                                        }
                                    })
                                ]
                            }),
                            BX.create('DIV', {
                                props: {
                                    'className': 'form-check'
                                },
                                children: [
                                    BX.create('INPUT', {
                                        props: {
                                            'className': 'form-check-input'
                                        },
                                        attrs: {
                                            type: 'checkbox',
                                            id: 'label-check-price',
                                            checked: 'checked',
                                            name: 'PRICE-PERS'
                                        }
                                    }),
                                    BX.create('LABEL', {
                                        props: {
                                            'className': 'form-check-label'
                                        },
                                        attrs: {
                                            for: 'label-check-price'
                                        },
                                        text: 'Даю согласие на обработку персональных данных и соглашаюсь с политикой конфиденциальности'
                                    })
                                ]
                            })
                        ]
                    }),
                    BX.create('DIV', {
                        props: {
                            'className': 'mix-form-button-block mix-popup-price-block mix-flex'
                        },
                        attrs: {
                            id: 'button-price'
                        },
                        events: {
                            click: BX.proxy(function(){setForm('get-price');}, this)
                        },
                        text: 'Узнать стоимость'
                    })
                ]
            }),
            autoHide : autoHide,
            offsetTop : offsetTop,
            offsetLeft : offsetLeft,
            lightShadow : lightShadow,
            closeIcon : closeIcon,
            closeByEsc : closeByEsc,
            overlay: overlay
        }),
        formsucc = new BX.PopupWindow('form-succ', window.body, {
            titleBar: {
                content: BX.create("DIV", {
                    html: 'Спасибо<br />Сообщение успешно отправлено',
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
                                   click: BX.proxy(function(e){ formsucc.close(); }, this)
                               },
                               text: 'Ок'
                           })
                       ]
                }),
            autoHide : autoHide,
            offsetTop : offsetTop,
            offsetLeft : offsetLeft,
            lightShadow : lightShadow,
            closeIcon : false,
            closeByEsc : closeByEsc,
            overlay: overlay
        }),
        pAJAX = '/ajax/form-ajax.php';

    $('div[data-popup="get-offer"]').click(function(e) {
        openPopup(getOffer, e)
    });

    $('div[data-popup="get-price"]').click(function(e) {
        openPopup(getPrice, e)
    });

    function openPopup(popup, e) {
        if(!e)
            e = window.event;
        popup.show();
        return BX.PreventDefault(e);
    };

    function closePopup(popup, e) {
        if(!e)
            e = window.event;
        popup.close();
        return BX.PreventDefault(e);
    };

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

    function validateMail(mail){
        var regex = '^[-\\w.]+@([A-z0-9][-A-z0-9]+\\.)+[A-z]{2,4}$'
        return mail.match(regex);
    };

    function validatePhone(phone){
        var regex = '^(\\+?(\\d{1,3})?[\\- ]?)?(\\(?\\d{3}\\)?[\\- ]?)?[\\d\\- ]{7,10}$'
        return phone.match(regex);
    };

    function validateName(name){
        var regex = '^[a-zA-Zа-яА-Я][a-zA-Zа-яА-Я0-9-_\\.]{1,20}$'
        return name.match(regex);
    };

    function clearForm(action){
        $('form#form-'+action).trigger("reset");
        if(action == 'get-price') getPrice.close();
        if(action == 'get-offer') getOffer.close();
    }

    function messageErr(input, action, message = ''){
        if(action == 'add'){
            if(input.hasClass('is-invalid'))
                input.removeClass('is-invalid').parent().next().remove();
            input.addClass('is-invalid').parent().after($('<div class="invalid-feedback" style="display: block;">' + message + '</div>'));
            return true
        }
        if(action == 'rem'){
            if(input.hasClass('is-invalid'))
                input.removeClass('is-invalid').parent().next().remove();
        }
        return false;
    }

    function setForm(action){
        var formOBJ = $('form#form-' + action).serializeObject();
        if(typeof formOBJ == 'object' && Object.keys(formOBJ).length != 0){
            var AJdata = {}
            for(key in formOBJ){
                if(key.split('-')[1] == 'NAME'){
                    if(formOBJ[key] == ''){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Поле обязательно для заполнения');
                        return;
                    }
                    if(!validateName(formOBJ[key])){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Неверный формат имени');
                        return
                    }
                    if($('form#form-' + action + ' input[name=' + key + ']').hasClass('is-invalid'))
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'rem');
                }

                if(key.split('-')[1] == 'PHONE'){
                    if(formOBJ[key] == ''){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Поле обязательно для заполнения');
                        return;
                    }
                    if(!validatePhone(formOBJ[key])){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Неверный формат номера телефона');
                        return
                    }
                    if($('form#form-' + action + ' input[name=' + key + ']').hasClass('is-invalid'))
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'rem');
                }

                if(key.split('-')[1] == 'MAIL'){
                    if(formOBJ[key] == ''){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Поле обязательно для заполнения');
                        return;
                    }
                    if(!validateMail(formOBJ[key])){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Неверный формат почты');
                        return;
                    }
                    if($('form#form-' + action + ' input[name=' + key + ']').hasClass('is-invalid'))
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'rem');
                }

                if(key.split('-')[1] == 'PROIZ'){
                    if(formOBJ[key] == ''){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Поле обязательно для заполнения');
                        return;
                    }
                    if(!validateName(formOBJ[key])){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Неверный формат поля');
                        return
                    }
                    if($('form#form-' + action + ' input[name=' + key + ']').hasClass('is-invalid'))
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'rem');
                }

                if(key.split('-')[1] == 'OBIEM'){
                    if(formOBJ[key] == ''){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Поле обязательно для заполнения');
                        return;
                    }
                    if($('form#form-' + action + ' input[name=' + key + ']').hasClass('is-invalid'))
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'rem');
                }

                if(key.split('-')[1] == 'PERS'){
                    if(formOBJ[key] == 'off'){
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'add', 'Дайте согласие на обработку персональных данных');
                        return;
                    }
                    if($('form#form-' + action + ' input[name=' + key + ']').hasClass('is-invalid'))
                        messageErr($('form#form-' + action + ' input[name=' + key + ']'), 'rem');
                }
            }
            AJdata['data'] = formOBJ;
            AJdata['action'] = action;
            BX.ajax.post(
                pAJAX,
                AJdata,
                function(data){
                    var result = JSON.parse(data);
                    if(!result.error) {
                        clearForm(action);
                        formsucc.show();
                    }
                }
            );
        }
    }
});