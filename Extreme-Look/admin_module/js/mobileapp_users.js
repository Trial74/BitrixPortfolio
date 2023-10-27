(function (window){
    'use strict';

    if(window.MobileAppUsers)
        return;

    window.MobileAppUsers = function (arParams) {
        this.visual = arParams.IDS;
        this.ajax = '/bitrix/admin/extremelook_mobileapp_users_ajax.php'
        this.init();

    }
    window.MobileAppUsers.prototype = {
        init: function(){
            BX.bind(BX(this.visual.RELOAD_GRID), 'click', BX.proxy(this.reloadGrid, this));
            BX.bind(BX(this.visual.ACTUALIZE), 'click', BX.proxy(this.actualize, this));
            BX.bind(BX(this.visual.ACT_VERSIONS), 'click', BX.proxy(this.actualVer, this));
        },
        reloadGrid: function(){
            var reloadParams = { apply_filter: 'Y', clear_nav: 'Y' };
            var gridObject = BX.Main.gridManager.getById(this.visual.GRID_ID);
            if (gridObject.hasOwnProperty('instance')){
                gridObject.instance.reloadTable('POST', reloadParams);
            }
        },
        actualize: function(){
            var _this = this, arrData = {};
            BX.toggleClass(BX(this.visual.ACTUALIZE), 'ui-btn-wait');
            arrData['action'] = 'actualTokenByProfile';
            BX.ajax.post(
                this.ajax,
                arrData,
                BX.proxy(function (data) {
                    var resultOBJ = JSON.parse(data),
                        result = resultOBJ.result;
                    if(result) {
                        BX.adjust(BX(_this.visual.REZULT_MESSAGE), {
                            html: '<b>'+resultOBJ.message.FIRST.TRUE+'</b><br />' +
                                  '<b>'+resultOBJ.message.FIRST.FALSE+'</b><br />' +
                                  '<b>'+resultOBJ.message.FIRST.ALL+'</b><br />'+
                                  '<b>'+resultOBJ.message.SECOND.TRUE+'</b><br />'+
                                  '<b>'+resultOBJ.message.SECOND.FALSE+'</b><br />'+
                                  '<b>'+resultOBJ.message.SECOND.ALL+'</b>'
                        });
                    }
                    else
                        BX.adjust(BX(_this.visual.REZULT_MESSAGE), {
                            html: '<b>Ошибка</b>'
                        });
                    BX.adjust(BX(_this.visual.REZULT_BLOCK), {
                        style: {'display': 'block'}
                    });

                    _this.reloadGrid();
                    BX.toggleClass(BX(_this.visual.ACTUALIZE), 'ui-btn-wait');
                }
                ), this);
        },
        actualVer: function(){
            var _this = this, arrData = {};
            BX.toggleClass(BX(this.visual.ACT_VERSIONS), 'ui-btn-wait');
            arrData['action'] = 'actualVersionByProfile';
            BX.ajax.post(
                this.ajax,
                arrData,
                BX.proxy(function (data) {
                        var resultOBJ = JSON.parse(data),
                            result = resultOBJ.result;
                        if(result) {
                            BX.adjust(BX(_this.visual.REZULT_MESSAGE), {
                                html: '<b>'+resultOBJ.message.TRUE+'</b><br />' +
                                    '<b>'+resultOBJ.message.FALSE+'</b><br />' +
                                    '<b>'+resultOBJ.message.OMITT+'</b><br />' +
                                    '<b>'+resultOBJ.message.ALL+'</b>'
                            });
                        }
                        else
                            BX.adjust(BX(_this.visual.REZULT_MESSAGE), {
                                html: '<b>Ошибка</b>'
                            });
                        BX.adjust(BX(_this.visual.REZULT_BLOCK), {
                            style: {'display': 'block'}
                        });

                        _this.reloadGrid();
                        BX.toggleClass(BX(_this.visual.ACT_VERSIONS), 'ui-btn-wait');
                        BX.adjust(BX(_this.visual.ACTUALIZE), {props: {disabled: false}});
                    }
                ), this);
        }
    }

})(window);