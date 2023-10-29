BX.ready(function(){

    const op = {
        '__LOGO': {
            tittle: 'Техническое задание<br />на разработку Логотипа',
            first: {
                tittle: 'Стоимость разработки логотипа:',
                subtittle: '10 000 ₽'
            },
            second: {
                tittle: 'Что входит в разработку логотипа:',
                subtittle: 'Разработка фирменного знака на основании технического задания'
            },
            third: {
                tittle: 'Время разработки логотипа:',
                subtittle: 'До 10 рабочих дней, + возможность внести правки (3 раза)'
            }
        },
        '__ETIC': {
            tittle: 'Техническое задание<br />на разработку Этикетки',
            first: {
                tittle: 'Стоимость разработки 1 этикетки:',
                subtittle: '5 000 ₽'
            },
            second: {
                tittle: 'Что входит в разработку этикетки:',
                subtittle: 'Разработка дизайна этикетки на основании технического задания'
            },
            third: {
                tittle: 'Время разработки этикетки:',
                subtittle: 'До 10 рабочих дней, + возможность внести правки (3 раза)'
            }
        },
        '__UPACK': {
            tittle: 'Техническое задание<br />на разработку Упаковки',
            first: {
                tittle: 'Стоимость разработки 1 упаковки:',
                subtittle: '7 000 ₽'
            },
            second: {
                tittle: 'Что входит в разработку упаковки:',
                subtittle: 'Разработка дизайна упаковки на основании технического задания'
            },
            third: {
                tittle: 'Время разработки упаковки:',
                subtittle: 'До 10 рабочих дней, + возможность внести правки (3 раза)'
            }
        }
    },
    ajax = '/bitrix/templates/enext_mixon/components/bitrix/main.include/form_private_design/ajax/ajax.php';
    var arRes = {},
        getOffer = new BX.PopupWindow('form-succ', window.body, {
        titleBar: {
            content:
                BX.create("DIV", {
                    props: {
                        'className': 'mix-pr-popup-tittle-succ'
                    },
                    children: [
                        BX.create("img", {
                            attrs: {
                                src: '/bitrix/templates/enext_mixon/images/icons/popup/form_success.png'
                            }
                        }),
                        BX.create("DIV", {
                            html: 'Спасибо.<br />Ваша заявка успешно отправлена',
                            'props': {'className': 'mix-nav-popup'}
                        })
                    ]
                })
        },
        content:
            BX.create("DIV", {
                props: {'className': 'mix-popup-content'},
                children: [
                    BX.create('DIV', {
                        props: {
                            'className': 'mix-form-succ-subtittle-block'
                        },
                        text: 'Мы уже приступили к разработке дизайна по вашему проекту'
                    }),
                    BX.create('DIV', {
                        props: {
                            'className': 'mix-form-button-block mix-flex'
                        },
                        attrs: {
                            id: 'mix-pr-button-ok'
                        },
                        text: 'Ок'
                    })
                ]
            }),
        autoHide : true,
        offsetTop : 1,
        offsetLeft : 0,
        lightShadow : true,
        closeIcon : false,
        closeByEsc : true,
        overlay: {
            backgroundColor: '#888694', opacity: '80'
        }
    });

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

    $(".mix-pr-top-menu-item").click(function () {
        $(".mix-pr-top-menu-item").removeClass("active");
        $(".form-private-design").removeClass("active");
        $(this).addClass("active");
        $("#mix-form-private-design" + $(this).data('form')).addClass("active");
        setBlockInfo($(this).data('form'));
    });

    $("input[name='radio_STYLE']").click(function() {
        $("label.form-check-label_21").removeClass("active");
        if ($(this).is(":checked")) { $(this).next().addClass("active"); }
    });

    $("input[name='radio_LOGO']").click(function() {
        $("label.form-check-label_23 > .form-check-label_23_check").removeClass("active");
        if ($(this).is(":checked")) { $(this).prev().addClass("active"); }
    });

    $("input[name='radio_PRICE_GROUP_UPAK']").click(function() {
        $("label.form-check-label_12 > .form-check-label_12_check.upak").removeClass("active");
        if ($(this).is(":checked")) { $(this).prev().prev().addClass("active"); }
    });

    $("input[name='radio_PRICE_GROUP_ETIC']").click(function() {
        $("label.form-check-label_12 > .form-check-label_12_check.etic").removeClass("active");
        if ($(this).is(":checked")) { $(this).prev().prev().addClass("active"); }
    });

    BX.bind(
        BX('mix-pr-submit__logo'), 'click',
        BX.proxy(function (e){pushForm($("#mix-form-private-design" + $(this).data('form')).serializeObject(), $(this).data('form'))})
    );
    BX.bind(
        BX('mix-pr-submit__etic'), 'click',
        BX.proxy(function (e){pushForm($("#mix-form-private-design" + $(this).data('form')).serializeObject(), $(this).data('form'))})
    );
    BX.bind(
        BX('mix-pr-submit__upack'), 'click',
        BX.proxy(function (e){pushForm($("#mix-form-private-design" + $(this).data('form')).serializeObject(), $(this).data('form'))})
    );
    BX.bind(
        BX('mix-pr-button-ok'), 'click',
        BX.proxy(function (e){getOffer.close();})
    );

    $('input.form-control').keyup(function(){
        if($(this).hasClass('is-invalid') && $(this).val().trim() == '') return
        else if($(this).hasClass('is-invalid') && $(this).val().trim() != '') $(this).removeClass('is-invalid').next().remove();
    });

    function pushForm(data, form) {
        if(typeof data != "object")
            return

        //Проверяем форму
        for(key in data) {
            if(data[key].trim() === '' && key != 'DOP_INFO'){
                var input = $("form#mix-form-private-design" + form + " input[name=" + key + "]");
                input.addClass('is-invalid').after($('<div id="validationServer03Feedback" class="invalid-feedback">Поле обязательно к заполнению</div>'));
                $('html, body').animate({
                    scrollTop: $('[for=' + input.attr('id')).offset().top
                }, 500);
                return
            }
        }

        //Собираем форму
        for(key in data){
            if(key == 'NAME_FORM')
                arRes[key] =
                    {
                        'LABEL': 'Название формы',
                        'VALUE': data[key].trim()
                    }
            else
                arRes[$('label[data-id='+$("form#mix-form-private-design" + form + " input[name=" + key + "]").data('label')+'] > span').text()] =
                    {
                        'LABEL': $('label[data-id=' + $("form#mix-form-private-design" + form + " input[name=" + key + "]").data('label') + '] > div').text(),
                        'VALUE': data[key].trim()
                    }
        }

        //Отправляем форму
        var dAJAX = {
            action: 'pushForm',
            data: arRes
        }
        BX.ajax.post(
            ajax,
            dAJAX,
            function(data){
                var result = JSON.parse(data);
                if(!result.error)
                    if(result.resHL)
                        getOffer.show();

                else{
                    console.log(result);
                }
            });
        arRes = {};
    }

    function setBlockInfo(data){
       var blockInfo = $("#mix-pr-info-block"),
           mainTittle = blockInfo.find('.mix-pr-exercise-tittle'),
           f_tittle = blockInfo.find('.mix-pr-first-tittle'),
           s_tittle = blockInfo.find('.mix-pr-second-tittle'),
           t_tittle = blockInfo.find('.mix-pr-third-tittle'),
           f_subtittle = blockInfo.find('.mix-pr-first-subtittle'),
           s_subtittle = blockInfo.find('.mix-pr-second-subtittle'),
           t_subtittle = blockInfo.find('.mix-pr-third-subtittle');

       mainTittle.html(op[data].tittle);
       f_tittle.text(op[data].first.tittle);
       f_subtittle.text(op[data].first.subtittle);
       s_tittle.text(op[data].second.tittle);
       s_subtittle.text(op[data].second.subtittle);
       t_tittle.text(op[data].third.tittle);
       t_subtittle.text(op[data].third.subtittle);
    }

});