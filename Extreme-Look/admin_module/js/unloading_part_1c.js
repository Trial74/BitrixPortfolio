BX.namespace('BX.EX.unloadingPart');

(function() {
    'use strict';
    BX.EX.unloadingPart = {
        init: function (parameters) {
            this.ajaxUrl = parameters.ajaxUrl || false;
            this.tableFiles = parameters.tableFiles || false;
            this.tableCash = parameters.tableCash || false;
            this.fileRequest();
        },
        fileRequest: function () {
            BX.ajax.post(
                this.ajaxUrl,
                {
                    action: 'requestFiles'
                },
                function (data) {
                    var resultOBJ = JSON.parse(data);
                    if(!resultOBJ.error){
                        if(!resultOBJ.noFiles){
                            BX.EX.unloadingPart.addInTable(resultOBJ.result);
                        }else{
                            BX.EX.unloadingPart.addInTable(resultOBJ.message, resultOBJ.noFiles, resultOBJ.error);
                        }
                    }else{
                        BX.EX.unloadingPart.addInTable(resultOBJ.message, resultOBJ.noFiles, resultOBJ.error);
                    }
                }
            );
        },
        addInTable: function (data, noFiles = false, error = false) {
            var tableDOM = BX.create('TBODY');
            if(!!error){
                tableDOM.appendChild(BX.create('TR', {
                    children: [
                        BX.create('TD', {
                            attrs: {
                                colspan: 5
                            },
                            props:{
                                className: 'ex_c-err'
                            },
                            text: data
                        })
                    ]
                }));
                $(this.tableFiles).append(tableDOM);
            }else{
                if(!!noFiles){
                    tableDOM.appendChild(BX.create('TR', {
                        children: [
                            BX.create('TD', {
                                attrs: {
                                    colspan: 5
                                },
                                props:{
                                    className: 'ex_c-err'
                                },
                                text: data
                            })
                        ]
                    }));
                    $(this.tableFiles).append(tableDOM);
                }else{
                    data.forEach(item => {
                        tableDOM.appendChild(BX.create('TR', {
                            children: [
                                BX.create('TD', {
                                    text: item.basename
                                }),
                                BX.create('TD', {
                                    text: item.date
                                }),
                                BX.create('TD', {
                                    text: item.size
                                }),
                                BX.create('TD', {//<button class="ui-btn ui-btn-wait">Кнопарь</button><button class="ui-btn ui-btn-active">.ui-btn-active</button>
                                    children: [
                                        BX.create('BUTTON', {
                                            text: 'Сформировать',
                                            props: {
                                                className: 'ui-btn ui-btn-active'
                                            },
                                            dataset: {
                                                filename: item.basename
                                            },
                                            events: {
                                                click: BX.proxy(this.clickCashButton, this)
                                            }
                                        })
                                    ]
                                }),
                                BX.create('TD', {
                                    html: 'Партнёров в файле: ' + item.countPart + '<br />' + 'С бонусами: ' + item.countCash
                                })
                            ]
                        }));
                    });
                    $(this.tableFiles).append(tableDOM);
                }
            }
        },
        addInTableCashResult: function(data){
            var tableDOM = BX.create('TBODY');
            if(Array.isArray(data)){
                if(data.length > 0){
                    data.forEach(item => {
                        tableDOM.appendChild(BX.create('TR', {
                            children: [
                                BX.create('TD', {
                                    text: item.name
                                }),
                                BX.create('TD', {
                                    text: item.email
                                }),
                                BX.create('TD', {
                                    html: '<a href="/user_edit.php?lang=ru&ID=' + item.user + '" target=_blank>' + item.name + '</a>'
                                }),
                                BX.create('TD', {
                                    text: item.cash
                                })
                            ]
                        }));
                    });
                }else{
                    tableDOM.appendChild(BX.create('TR', {
                        children: [
                            BX.create('TD', {
                                attrs: {
                                    colspan: 5
                                },
                                props:{
                                    className: 'ex_c-err'
                                },
                                text: 'Нет данных'
                            })
                        ]
                    }));
                }
            }else{
                tableDOM.appendChild(BX.create('TR', {
                    children: [
                        BX.create('TD', {
                            attrs: {
                                colspan: 5
                            },
                            props:{
                                className: 'ex_c-err'
                            },
                            text: 'Ошибка данных'
                        })
                    ]
                }));
            };

            if($(this.tableCash).find('tbody').length){
                $(this.tableCash).find('tbody').remove();
            }
            $(this.tableCash).append(tableDOM);
            $(this.tableCash).show();
            $('html, body').animate({
                scrollTop: $(this.tableCash).offset().top // класс объекта к которому приезжаем
            }, 300);
        },
        clickCashButton: function (event) {
            $(event.target).addClass('ui-btn-wait');
            BX.ajax.post(
                this.ajaxUrl,
                {
                    action: 'buildingTableCash',
                    file: $(event.target).data('filename')
                },
                function (data) {
                    var resultOBJ = JSON.parse(data);
                    $(event.target).removeClass('ui-btn-wait');
                    BX.EX.unloadingPart.addInTableCashResult(resultOBJ.result);
                }
            );
        }
    }
})();