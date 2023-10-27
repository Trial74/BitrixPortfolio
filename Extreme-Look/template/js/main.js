(function(window) {
	'use strict';

	BX.ready(function() {	
		//SITE_BG//	
		var pageBg = document.body.querySelector('.page-bg');
		if(!!pageBg) {
			pageBg.style.opacity = 0;
			new BX.easing({
				duration: 300,
				start: {opacity: 0},
				finish: {opacity: 100},
				transition: BX.easing.transitions.linear,
				step: function(state){
					pageBg.style.opacity = state.opacity / 100;
				}
			}).animate();
		}
		
		//TOP_PANEL//
		var topPanelContainer = document.body.querySelector('.top-panel-wrapper'),		
			topPanel = !!topPanelContainer && topPanelContainer.querySelector('.top-panel'),
			topPanelThead = !!topPanel && topPanel.querySelector('.top-panel__thead'),
			topPanelTfoot = !!topPanel && topPanel.querySelector('.top-panel__tfoot');

		if(!!topPanelThead && !!topPanelTfoot) {
			function showHideTopPanelTfoot() {
				if(window.innerWidth < 992) {
					if(window.location.pathname.indexOf('/personal/') > -1)
						BX.addClass(topPanelTfoot, 'hidden-xs hidden-sm');
				} else {
					if(BX.hasClass(topPanelTfoot, 'hidden-xs hidden-sm'))
						BX.removeClass(topPanelTfoot, 'hidden-xs hidden-sm');
				}
			}

			showHideTopPanelTfoot();
			BX.bind(window, 'resize', function() {
				showHideTopPanelTfoot();
			});
			
			var isTopPanelFixed = false,
				lastScrollTop = 0;

			function checkTopTopPanel() {
				var scrollTop = !!document.body.style.top ? Math.abs(parseInt(BX.style(document.body, 'top'), 10)) : BX.GetWindowScrollPos().scrollTop,
					topPanelContainerTop = topPanelContainer.offsetTop,
					homeMobil = document.body.querySelector('.mobile-home'), //Мой код блок с лого над менюшкой
					pageWrapper = document.body.querySelector('.page-container-wrapper');

				if(document.getElementById('bx-panel') === null){ bxPanel = 0;} //Мой код если админ панели нет высота равна 0
				else{ var bxPanel = document.getElementById('bx-panel').offsetHeight;} //Если админ панель есть вычисляем высоту панели

				if(window.innerWidth < 992) {
					var topPanelTheadHeight = topPanelThead.offsetHeight,
						topPanelTfootHeight = topPanelTfoot.offsetHeight,
						homeMobilHeight = homeMobil.offsetHeight;

					BX.style(pageWrapper, 'padding-top', 70 + 'px'); //Мой код отступаем основным блоком на высоту менюшки + высоту админ панели (если есть) если с мобилки

					if(scrollTop >= 0) { //Мой код фиксируем сразу кароче
						if(!isTopPanelFixed) {
							isTopPanelFixed = true;
							BX.style(topPanelContainer, 'height', topPanelTheadHeight + topPanelTfootHeight + 'px');
							BX.style(topPanelContainer, 'paddingTop', topPanelTheadHeight + 'px');
							BX.addClass(homeMobil, 'fixed'); //Мой код фиксируем блок с лого
							BX.addClass(topPanelThead, 'fixed');
							BX.addClass(topPanelTfoot, 'fixed visible'); //Мой код фиксируем обе менюшки сразу
							BX.style(topPanelTfoot, 'top', 58 + bxPanel + 'px');//Мой код при фиксе делаем отступ на высоту блока лого + высота админ панели если есть.
							new BX.easing({
								step: function(state) {
									if(!!isTopPanelFixed)
										BX.style(topPanelTfoot, 'top', state.top + 'px');
								}
							});
						} /*else {//Мой код убираем анимацию скрытия меню при скролле
							if(!BX.hasClass(topPanelTfoot, 'visible') && scrollTop < lastScrollTop) {
								BX.style(topPanelTfoot, 'top', '-' + topPanelTfootHeight + 'px');								
								BX.addClass(topPanelTfoot, 'fixed visible');
								new BX.easing({
									duration: 300,
									start: {top: - topPanelTfootHeight},
									finish: {top: topPanelTheadHeight},
									transition: BX.easing.transitions.linear,
									step: function(state) {
										if(!!isTopPanelFixed)
											BX.style(topPanelTfoot, 'top', state.top + 'px');
									}
								}).animate();
							} else if(!!BX.hasClass(topPanelTfoot, 'visible') && scrollTop > lastScrollTop) {
								//BX.removeClass(topPanelTfoot, 'visible');
								new BX.easing({
									duration: 300,							
									start: {top: topPanelTheadHeight},
									finish: {top: - topPanelTfootHeight},
									transition: BX.easing.transitions.linear,
									step: function(state) {										
										BX.style(topPanelTfoot, 'top', state.top + 'px');
									}
								}).animate();
							}
						}*/
					} else if(!!isTopPanelFixed && scrollTop < topPanelContainerTop) {
						isTopPanelFixed = false;					
						topPanelContainer.removeAttribute('style');
						BX.removeClass(topPanelThead, 'fixed');
						BX.removeClass(homeMobil, 'fixed');
						topPanelTfoot.removeAttribute('style');
						BX.removeClass(topPanelTfoot, 'fixed');
						BX.removeClass(topPanelTfoot, 'visible');
					}				
				} else {
					if(!isTopPanelFixed && scrollTop >= topPanelContainerTop) {
						isTopPanelFixed = true;
						BX.style(topPanelContainer, 'height', topPanel.offsetHeight + 'px');
						BX.addClass(topPanel, 'fixed');	
					} else if(!!isTopPanelFixed && scrollTop < topPanelContainerTop) {
						isTopPanelFixed = false;				
						topPanelContainer.removeAttribute('style');
						BX.removeClass(topPanel, 'fixed');
					}
				}
				lastScrollTop = scrollTop;
			}

			checkTopTopPanel();
			BX.bind(window, 'scroll', function() {
				checkTopTopPanel();
			});

			BX.bind(window, 'resize', function() {
				if(window.innerWidth < 992 && !!BX.hasClass(topPanel, 'fixed')) {
					BX.removeClass(topPanel, 'fixed');
					BX.style(topPanelContainer, 'height', topPanelThead.offsetHeight + topPanelTfoot.offsetHeight + 'px');				
					BX.addClass(topPanelThead, 'fixed');
					BX.addClass(homeMobil, 'fixed');
				} else if(window.innerWidth >= 992 && !!BX.hasClass(topPanelThead, 'fixed')) {
					BX.removeClass(topPanelThead, 'fixed');
					if(!!BX.hasClass(topPanelTfoot, 'fixed')) {
						BX.removeClass(topPanelTfoot, 'fixed');
						BX.removeClass(topPanelTfoot, 'visible');
						topPanelTfoot.removeAttribute('style');
					}
					BX.style(topPanelContainer, 'height', topPanel.offsetHeight + 'px');
					BX.addClass(topPanel, 'fixed');
				}
			});
		}
		
		//SEARCH//	
		var btnShowSearch = document.body.querySelector('[data-entity="showSearch"]');	
		if(!!btnShowSearch) {
			BX.bind(btnShowSearch, 'click', function(e) {
				var topPanelSearch = document.body.querySelector('.top-panel__search'),
					slidePanel = document.body.querySelector('.slide-panel');
				if(!!topPanelSearch && !!slidePanel) {
					slidePanel.appendChild(
						BX.create('DIV', {
							props: {
								className: 'slide-panel__title-wrap'
							},
							children: [
								BX.create('I', {
									props: {
										className: 'icon-search'
									}
								}),						
								BX.create('SPAN', {
									props: {
										className: 'slide-panel__title'
									},
									html: BX.message('SLIDE_PANEL_SEARCH_TITLE')
								}),
								BX.create('SPAN', {
									props: {
										className: 'slide-panel__close'
									},
									children: [
										BX.create('I', {
											props: {
												className: 'icon-close'
											}
										})
									]
								})
							]
						})
					);

					slidePanel.appendChild(
						BX.create('DIV', {
							props: {
								className: 'slide-panel__content scrollbar-inner'
							}
						})
					);

					var slidePanelContent = slidePanel.querySelector('.slide-panel__content');
					if(!!slidePanelContent) {
						slidePanelContent.appendChild(topPanelSearch);
						$(slidePanelContent).scrollbar();
						
						var slidePanelInput = slidePanelContent.querySelector('[name="q"]');
						if(!!slidePanelInput)
							slidePanelInput.focus();
					}

					var scrollWidth = window.innerWidth - document.body.clientWidth;
					if(scrollWidth > 0) {
						BX.style(document.body, 'padding-right', scrollWidth + 'px');
						
						if(!!topPanel) {
							if(BX.hasClass(topPanel, 'fixed'))
								BX.style(topPanel, 'padding-right', scrollWidth + 'px');						
							if(!!topPanelThead && BX.hasClass(topPanelThead, 'fixed'))
								BX.style(topPanelThead, 'padding-right', scrollWidth + 'px');						
							if(!!topPanelTfoot && BX.hasClass(topPanelTfoot, 'fixed'))
								BX.style(topPanelTfoot, 'padding-right', scrollWidth + 'px');
						}

						var sectionPanel = document.body.querySelector('.catalog-section-panel');
						if(!!sectionPanel && BX.hasClass(sectionPanel, 'fixed'))
							BX.style(sectionPanel, 'padding-right', scrollWidth + 'px');
						
						var tabsPanel = document.body.querySelector('[data-entity="tabs"]');
						if(!!tabsPanel && BX.hasClass(tabsPanel, 'fixed'))
							BX.style(tabsPanel, 'padding-right', scrollWidth + 'px');
					}
					
					var scrollTop = BX.GetWindowScrollPos().scrollTop;
					if(!!scrollTop && scrollTop > 0)
						BX.style(document.body, 'top', '-' + scrollTop + 'px');
							
					BX.addClass(document.body, 'slide-panel-active');
					BX.addClass(slidePanel, 'active');
				
					document.body.appendChild(
						BX.create('DIV', {
							props: {
								className: 'modal-backdrop slide-panel__backdrop fadeInBig'
							}
						})
					);

					e.stopPropagation();
				}
			});
		}
		
		//SHARE//
		var shareIcon = document.body.querySelector('[data-entity="showShare"]'),
			shareContent = document.body.querySelector('[data-entity="shareContent"]'),
			shareIconSite = document.body.querySelector('[data-entity="showShareSite"]'),
			shareContentSite = document.body.querySelector('[data-entity="shareContentSite"]');

		if((!!shareIcon && !!shareContent) || (!!shareIconSite && !!shareContentSite)) {

			BX.bind(shareIcon, 'click', function() {
				if(BX.isNodeHidden(shareContent)) {
					BX.style(shareContent, 'display', 'flex');
					BX.addClass(shareIcon, 'active');
				} else {
					BX.style(shareContent, 'display', 'none');
					BX.removeClass(shareIcon, 'active');
				}
			});

			BX.bind(shareIconSite, 'click', function() {
				if(BX.isNodeHidden(shareContentSite)) {
					BX.style(shareContentSite, 'display', 'flex');
					BX.addClass(shareIconSite, 'active');
				} else {
					BX.style(shareContentSite, 'display', 'none');
					BX.removeClass(shareIconSite, 'active');
				}
			});

			if(!!shareIcon && !!shareContent){
				BX.bind(document, 'click', function(event) {
					if(!BX.isNodeHidden(shareContent) &&
						!BX.findParent(event.target, {attr: {'data-entity': 'showShare'}}, false) && event.target.getAttribute('data-entity') != 'showShare' &&
						!BX.findParent(event.target, {attr: {'data-entity': 'shareContent'}}, false) && event.target.getAttribute('data-entity') != 'shareContent'
					) {
						BX.style(shareContent, 'display', 'none');
						BX.removeClass(shareIcon, 'active');
						event.stopPropagation();
					}
				});
			}
			if(!!shareIconSite && !!shareContentSite) {
				BX.bind(document, 'click', function (event) {
					if (!BX.isNodeHidden(shareContentSite) &&
						!BX.findParent(event.target, {attr: {'data-entity': 'showShareSite'}}, false) && event.target.getAttribute('data-entity') != 'showShareSite' &&
						!BX.findParent(event.target, {attr: {'data-entity': 'shareContentSite'}}, false) && event.target.getAttribute('data-entity') != 'shareContentSite'
					) {
						BX.style(shareContentSite, 'display', 'none');
						BX.removeClass(shareIconSite, 'active');
						event.stopPropagation();
					}
				});
			}
		}
		
		//TABS//
		var tabsContainer = document.body.querySelector('[data-entity="main-tabs"]'),
			tabsTabs = !!tabsContainer && tabsContainer.querySelector('.tabs__tabs'),
			tabs = !!tabsContainer && tabsContainer.querySelectorAll('[data-entity="tab"]'),
			tabsContentContainer = document.body.querySelector('[data-entity="main-tabs-content"]'),		
			tabValue, targetTab,
			haveActive = false, i;			
		
		if(!!tabsTabs) {
			BX.loadScript(BX.message('SITE_TEMPLATE_PATH') + '/js/owlCarousel/owl.carousel.min.js', function() {
				BX.addClass(tabsTabs, 'owl-carousel');
				$(tabsTabs).owlCarousel({								
					autoWidth: true,
					nav: true,
					navText: ['<i class=\"icon-arrow-left\"></i>', '<i class=\"icon-arrow-right\"></i>'],
					navContainer: '.tabs__scroll',
					dots: false,			
				});
				if(!!tabs) {
					for(var i in tabs) {
						if(tabs.hasOwnProperty(i) && BX.type.isDomNode(tabs[i])) {				
							tabValue = tabs[i].getAttribute('data-value');
							if(tabValue) {
								targetTab = tabsContentContainer.querySelector('[data-value="' + tabValue + '"]');
								if(BX.type.isDomNode(targetTab)) {
									if(!haveActive) {
										BX.addClass(tabs[i], 'active');								
										BX.show(targetTab);
										haveActive = true;
									} else {
										BX.removeClass(tabs[i], 'active');								
										BX.hide(targetTab);
									}				
									BX.bind(tabs[i], 'click', function(event) {
										BX.PreventDefault(event);

										var targetTabValue = this.getAttribute('data-value'),
											j, k;
										
										if(!BX.hasClass(this, 'active') && targetTabValue) {
											var tabsContent = tabsContentContainer.querySelectorAll('[data-entity="tab-content"]');
											if(!!tabsContent) {
												for(var j in tabsContent) {
													if(tabsContent.hasOwnProperty(j) && BX.type.isDomNode(tabsContent[j])) {
														if(tabsContent[j].getAttribute('data-value') == targetTabValue) {
															BX.show(tabsContent[j]);
														} else {
															BX.hide(tabsContent[j]);
														}
													}
												}
											}
											for(k in tabs) {
												if(tabs.hasOwnProperty(k) && BX.type.isDomNode(tabs[k])) {
													if(tabs[k].getAttribute('data-value') == targetTabValue) {
														BX.addClass(tabs[k], 'active');
													} else {
														BX.removeClass(tabs[k], 'active');
													}
												}
											}
										}
									});
								}
							}
						}
					}
				}
			});
		}

		//НАЧАЛО ТАБЫ В ТОВАРАХ Мой код//
		var tabsContainerEl = document.body.querySelector('[data-entity="main-element-tabs"]'),
			tabsTabsEl = !!tabsContainerEl && tabsContainerEl.querySelector('.tabs-el__tabs'),
			tabsEl = !!tabsContainerEl && tabsContainerEl.querySelectorAll('[data-entity="tab-el"]'),
			tabsContentContainerEl = document.body.querySelector('[data-entity="main-tabs-content-element"]'),
			tabValueEl, targetTabEl,
			haveActiveEl = false, iEl;

		if(!!tabsTabsEl) {
			BX.loadScript(BX.message('SITE_TEMPLATE_PATH') + '/js/owlCarousel/owl.carousel.min.js', function() {
				BX.addClass(tabsTabsEl, 'owl-carousel');
				$(tabsTabsEl).owlCarousel({
					autoWidth: true,
					nav: true,
					navText: ['<i class=\"icon-arrow-left\"></i>', '<i class=\"icon-arrow-right\"></i>'],
					navContainer: '.tabs-el__scroll',
					dots: false,
				});
				if(!!tabsEl) {
					for(var iEl in tabsEl) {
						if(tabsEl.hasOwnProperty(iEl) && BX.type.isDomNode(tabsEl[iEl])) {
							tabValueEl = tabsEl[iEl].getAttribute('data-value');
							if(tabValueEl) {
								targetTabEl = tabsContentContainerEl.querySelector('[data-value="' + tabValueEl + '"]');
								if(BX.type.isDomNode(targetTabEl)) {
									if(!haveActiveEl) {
										BX.addClass(tabsEl[iEl], 'active');
										BX.show(targetTabEl);
										haveActiveEl = true;
									} else {
										BX.removeClass(tabsEl[iEl], 'active');
										BX.hide(targetTabEl);
									}
									BX.bind(tabsEl[iEl], 'click', function(event) {
										BX.PreventDefault(event);

										var targetTabValueEl = this.getAttribute('data-value'),
											j, k;

										if(!BX.hasClass(this, 'active') && targetTabValueEl) {
											var tabsContentEl = tabsContentContainerEl.querySelectorAll('[data-entity="tab-el-content"]');
											if(!!tabsContentEl) {
												for(var j in tabsContentEl) {
													if(tabsContentEl.hasOwnProperty(j) && BX.type.isDomNode(tabsContentEl[j])) {
														if(tabsContentEl[j].getAttribute('data-value') == targetTabValueEl) {
															BX.show(tabsContentEl[j]);
														} else {
															BX.hide(tabsContentEl[j]);
														}
													}
												}
											}
											for(k in tabsEl) {
												if(tabsEl.hasOwnProperty(k) && BX.type.isDomNode(tabsEl[k])) {
													if(tabsEl[k].getAttribute('data-value') == targetTabValueEl) {
														BX.addClass(tabsEl[k], 'active');
													} else {
														BX.removeClass(tabsEl[k], 'active');
													}
												}
											}
										}
									});
								}
							}
						}
					}
				}
			});
		}
		//КОНЕЦ ТАБЫ В ТОВАРАХ Мой код//


		//SLIDE_PANEL//
		//POPUP_PANEL//
		var slidePanel = document.body.querySelector('.slide-panel');
		
		function slidePanelClose() {
			var slidePanelBack = document.body.querySelector('.slide-panel__backdrop'),
				slidePanelSearch = slidePanel.querySelector('.top-panel__search');
				btnShowSearch = document.body.querySelector('[data-entity="showSearch"]');
			
			BX.removeClass(slidePanel, 'active');
			if(!!slidePanelSearch)
				btnShowSearch.parentNode.insertBefore(slidePanelSearch, btnShowSearch.nextSibling);
			BX.cleanNode(slidePanel);
			new BX.easing({
				duration: 300,
				start: {opacity: 100},
				finish: {opacity: 0},
				transition: BX.easing.transitions.linear,
				step: function(state){
					slidePanelBack.style.opacity = state.opacity / 100;
				},
				complete: function(){
					BX.remove(slidePanelBack);
				}
			}).animate();			
			
			BX.removeClass(document.body, 'slide-panel-active');
			
			var popupPanel = document.body.querySelector('.popup-panel');
			if(!popupPanel) {
				BX.style(document.body, 'padding-right', '');
				
				var scrollTop = Math.abs(parseInt(BX.style(document.body, 'top'), 10));
				if(!!scrollTop && scrollTop > 0) {
					window.scrollTo(0, scrollTop);
					BX.style(document.body, 'top', '');
				}
				
				if(!!pageBg)
					BX.style(pageBg, 'margin-right', '');
				
				if(!!topPanel) {
					BX.style(topPanel, 'padding-right', '');
					if(!!topPanelThead)
						BX.style(topPanelThead, 'padding-right', '');
					if(!!topPanelTfoot)
						BX.style(topPanelTfoot, 'padding-right', '');
				}

				var sectionPanel = document.body.querySelector('.catalog-section-panel');
				if(!!sectionPanel)
					BX.style(sectionPanel, 'padding-right', '');

				var tabsPanel = document.body.querySelector('[data-entity="tabs"]');
				if(!!tabsPanel)
					BX.style(tabsPanel, 'padding-right', '');

				var objectsMap = document.body.querySelector('.objects-map');
				if(!!objectsMap)
					BX.style(objectsMap, 'padding-right', '');
			}
		}

		function popupPanelClose() {
			var popupPanel = document.body.querySelector('.popup-panel'),
				popupPanelBack = document.body.querySelector('.popup-panel__backdrop');
			
			if(popupPanel.hasAttribute('data-location-href'))
				window.history.pushState('', document.title, popupPanel.getAttribute('data-location-href'));
			
			new BX.easing({
				duration: 300,
				start: {opacity: 100},
				finish: {opacity: 0},
				transition: BX.easing.transitions.linear,
				step: function(state){
					popupPanel.style.opacity = state.opacity / 100;
					popupPanelBack.style.opacity = state.opacity / 100;
				},
				complete: function(){
					BX.remove(popupPanel);
					BX.remove(popupPanelBack);
				}
			}).animate();			
			
			BX.removeClass(document.body, 'popup-panel-active');
			
			BX.style(document.body, 'padding-right', '');
			
			var scrollTop = Math.abs(parseInt(BX.style(document.body, 'top'), 10));
			if(!!scrollTop && scrollTop > 0) {
				window.scrollTo(0, scrollTop);
				BX.style(document.body, 'top', '');
			}
			
			if(!!pageBg)
				BX.style(pageBg, 'margin-right', '');
			
			if(!!topPanel) {
				BX.style(topPanel, 'padding-right', '');
				if(!!topPanelThead)
					BX.style(topPanelThead, 'padding-right', '');
				if(!!topPanelTfoot)
					BX.style(topPanelTfoot, 'padding-right', '');
			}

			var sectionPanel = document.body.querySelector('.catalog-section-panel');
			if(!!sectionPanel)
				BX.style(sectionPanel, 'padding-right', '');

			var tabsPanel = document.body.querySelector('[data-entity="tabs"]');
			if(!!tabsPanel)
				BX.style(tabsPanel, 'padding-right', '');

			var objectsMap = document.body.querySelector('.objects-map');
			if(!!objectsMap)
				BX.style(objectsMap, 'padding-right', '');
		}

		BX.bind(document, 'click', function(e) {		
			if(!!slidePanel && BX.hasClass(slidePanel, 'active')) {
				if(BX.hasClass(e.target, 'slide-panel__close') ||
					BX.findParent(e.target, {className: 'slide-panel__close'}) || (
					!BX.findParent(e.target, {className: 'slide-panel'}) &&
					!BX.findParent(e.target, {className: 'main-user-consent-request-popup'}) &&
					!BX.findParent(e.target, {className: 'iti--container'})
				)) {
					slidePanelClose();
					e.stopPropagation();
				}
			} else {
				var popupPanel = document.body.querySelector('.popup-panel');
				if(!!popupPanel) {
					if(BX.hasClass(e.target, 'popup-panel__close') ||
						BX.findParent(e.target, {className: 'popup-panel__close'}) || (
						!BX.findParent(e.target, {className: 'popup-panel'}) &&
						!BX.findParent(e.target, {className: 'main-user-consent-request-popup'}) &&
						!BX.findParent(e.target, {className: 'iti--container'}) &&
						!BX.findParent(e.target, {className: 'popup-window'}) &&
						!BX.hasClass(e.target, 'popup-window-overlay')
					)) {
						popupPanelClose();
						e.stopPropagation();
					}
				}
			}
		});
		BX.bind(document, 'keydown', function(e) {
			if(e.keyCode == 27) {
				if(!!slidePanel && BX.hasClass(slidePanel, 'active')) {
					slidePanelClose();
					e.stopPropagation();
				} else {
					var popupPanel = document.body.querySelector('.popup-panel');
					if(!!popupPanel) {
						popupPanelClose();
						e.stopPropagation();
					}
				}
			}
		});
		BX.bind(window, 'resize', function(e) {
			if(!!slidePanel && BX.hasClass(slidePanel, 'active') && !!slidePanel.querySelector('.top-panel__search') && window.innerWidth >= 992) {
				slidePanelClose();
				e.stopPropagation();
			}
		});
		
		//SCROLL_UP//
		var upButton = document.body.querySelector('.scroll-up');
		if(!!upButton) {
			BX.bind(upButton, "click", function() {
				var windowScroll = BX.GetWindowScrollPos();
				new BX.easing({
					duration: 500,
					start: {scroll: windowScroll.scrollTop},
					finish: {scroll: 0},
					transition: BX.easing.makeEaseOut(BX.easing.transitions.quart),
					step: function(state) {
						window.scrollTo(0, state.scroll);
					}
				}).animate();
			});
			BX.bind(window, 'scroll', function() {			
				var scrollTop = BX.GetWindowScrollPos().scrollTop;			
				if(scrollTop > 150) {
					upButton.style.bottom = '22px';
				} else {
					upButton.style.bottom = '';
				}
			});
		}
	});

	if(window.BX) {
		BX.scrollToNode = function(node) {
			var obNode = BX(node),
				arNodePos = BX.pos(obNode),
				topPanel = document.body.querySelector('.top-panel'),
				topPanelHeight = 0,
				topPanelThead = !!topPanel && topPanel.querySelector('.top-panel__thead'),
				topPanelTfoot = !!topPanel && topPanel.querySelector('.top-panel__tfoot'),	
				sectionPanel = document.body.querySelector('.catalog-section-panel'),
				sectionPanelHeight = !!sectionPanel ? sectionPanel.offsetHeight : 0,
				tabsPanel = document.body.querySelector('[data-entity="tabs"]'),
				tabsPanelHeight = !!tabsPanel ? tabsPanel.offsetHeight : 0;
				
			if(window.innerWidth < 992) {
				if(!!topPanelThead) {
					topPanelHeight = topPanelThead.offsetHeight;
					if(!!topPanelTfoot && !!BX.hasClass(topPanelTfoot, 'visible'))
						topPanelHeight += topPanelTfoot.offsetHeight;
				}
			} else {
				if(!!topPanel)
					topPanelHeight = topPanel.offsetHeight;
			}
			
			window.scrollTo(arNodePos.left, arNodePos.top - topPanelHeight - sectionPanelHeight - tabsPanelHeight - (window.innerWidth < 992 ? 0 : 40));
		}
	}
})(window);