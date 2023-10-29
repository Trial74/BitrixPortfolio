(function() {
    'use strict';

    if(!!window.JSMixTopMenu || window.screen.width > 992)
        return;

    window.JSMixTopMenu = function(params) {
        this.mixTopMenu = BX(params.container);
        this.mixButtonOpenMenu = BX(params.openButton);
        this.mixContactBlock = BX(params.contactBlock);
        this.mixTopMenuItems = params.menu;
        BX.addCustomEvent(this, 'showMixMobileMenu', BX.proxy(this.showMixMobileMenu, this));
        BX.ready(BX.delegate(this.init, this));
    };
    window.JSMixTopMenu.prototype = {
        init: function() {
            this.sPanel = document.body.querySelector('.slide-panel');
            if(!!this.sPanel) {
                BX.bind(this.mixButtonOpenMenu, 'click', BX.proxy(this.showCloseMixMobileMenu, this));
            }
        },
        showCloseMixMobileMenu: function(event){
            if(!BX.hasClass(this.sPanel, 'active'))
                BX.onCustomEvent(this, 'showMixMobileMenu', [event]);
        },
        showMixMobileMenu: function(e){

            if(!!this.sPanel && this.mixTopMenuItems.length > 0) {

                var menuItems = BX.create('DIV', {
                    props: {
                        className: 'slide-panel__top-menu-items-block mix-flex'
                    }
                });
                
                this.mixTopMenuItems.forEach(function(item) {
                    menuItems.appendChild(
                        BX.create('A', {
                            props: {
                                className: 'slide-panel__top-menu-item mix-flex'
                            },
                            attrs: {
                                href: item.LINK ? item.LINK : '/'
                            },
                            children: [
                                BX.create('span', {
                                    text: item.TEXT
                                })
                            ]
                        })
                    );
                });

                menuItems.appendChild(
                    BX.create('DIV', {
                        props: {
                            className: 'slide-panel__top-menu-item mix-footer-slide-menu mix-flex'
                        },
                        children:[
                            BX.create('A', {
                                props: {
                                    className: 'mix-footer-tel'
                                },
                                attrs: {
                                    href: '#'
                                }
                            }),
                            BX.create('DIV', {
                                props: {
                                    className: 'mix-social-links'
                                },
                                children: [
                                    BX.create('DIV', {
                                        props: {
                                            className: 'mix-flex mix-messendger'
                                        },
                                        children: [
                                            BX.create('DIV', {
                                                props: {
                                                    className: 'mix-mes-whatsapp'
                                                },
                                                children: [
                                                    BX.create('A', {
                                                        attrs: {
                                                            href: 'https://wa.me/79227421468',
                                                            target: '_blank'
                                                        }
                                                    })
                                                ]
                                            }),
                                            BX.create('DIV', {
                                                props: {
                                                    className: 'mix-mes-telegram'
                                                },
                                                children: [
                                                    BX.create('A', {
                                                        attrs: {
                                                            href: 'https://t.me/mixon_manager',
                                                            target: '_blank'
                                                        }
                                                    })
                                                ]
                                            })
                                        ]
                                    })
                                ]
                            })
                        ]
                    })
                );

                this.sPanel.appendChild(
                    BX.create('DIV', {
                        props: {
                            className: 'slide-panel__top-menu-item mix-flex'
                        },
                        children: [
                            menuItems
                        ]
                    })
                );
            }
            var scrollWidth = window.innerWidth - document.body.clientWidth;
            if(scrollWidth > 0) {
                BX.style(document.body, 'padding-right', scrollWidth + 'px');
            }

            var scrollTop = BX.GetWindowScrollPos().scrollTop;
            if(!!scrollTop && scrollTop > 0)
                BX.style(document.body, 'top', '-' + scrollTop + 'px');

            BX.addClass(document.body, 'slide-panel-active')
            BX.addClass(this.sPanel, 'active');
            document.body.appendChild(
                BX.create('DIV', {
                    props: {
                        className: 'modal-backdrop slide-panel__backdrop fadeInBig'
                    }
                })
            );
            e.stopPropagation();
        }
    }
})(window);