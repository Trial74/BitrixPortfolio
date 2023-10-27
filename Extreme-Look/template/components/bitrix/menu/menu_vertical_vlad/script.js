(function() {
	'use strict';

	if(!!window.JCCatalogMenu)
		return;

	window.JCCatalogMenu = function(params) {
		this.catalogMenu = BX(params.container);
		this.catalogMenuSetActive = params.setActive;
		this.catalogMenuOpenLast = params.openLast;
		this.catalogMenuMoved = false;

		this.menuIcon = document.body.querySelector('[data-entity="menu-icon"]');

		BX.ready(BX.delegate(this.init, this));
	};
	window.JCCatalogMenu.prototype = {
		init: function() {
			var subMenuAll = this.catalogMenu.querySelectorAll('[data-entity="dropdown-menu"]');
			if(!!subMenuAll) {
				for(var i in subMenuAll) {
					if(subMenuAll.hasOwnProperty(i)) {
						var subMenuLiActive = subMenuAll[i].querySelector('.active');
						if(!!subMenuLiActive) {
							var parentMenuLi = BX.findParent(subMenuAll[i], {tagName: 'LI'});
							if(!!parentMenuLi)
								BX.addClass(parentMenuLi, 'active');
						}
					}
				}
			}

			this.adjustCatalogMenu();
			BX.bind(window, 'resize', BX.proxy(this.adjustCatalogMenu, this));

			this.checkCatalogMenuTop();
			BX.bind(window, 'resize', BX.proxy(this.checkCatalogMenuTop, this));
			BX.bind(window, 'scroll', BX.proxy(this.checkCatalogMenuTop, this));

				//if(!!this.menuIcon)
					//this.hoverIntentIcon(this.menuIcon);

				if(window.innerWidth >= 992){
					if(!BX.hasClass(this.menuIcon, 'hover'))
						BX.addClass(this.menuIcon, 'hover');
					if(!BX.hasClass(this.catalogMenu, 'active'))
						BX.addClass(this.catalogMenu, 'active');
				}
				else{
					if(BX.hasClass(this.menuIcon, 'hover'))
						BX.removeClass(this.menuIcon, 'hover');
					if(BX.hasClass(this.catalogMenu, 'active'))
						BX.removeClass(this.catalogMenu, 'active');
				}

			var dropDownAll = this.catalogMenu.querySelectorAll('[data-entity="dropdown"]');
			if(!!dropDownAll) {
				for(var i in dropDownAll) {
					if(dropDownAll.hasOwnProperty(i)) {
						BX.bind(dropDownAll[i], 'mouseenter', BX.delegate(function() {
							var target = BX.proxy_context;

							BX.addClass(target, 'hover');

							if(window.innerWidth >= 992) {
								var parentMenu = BX.findParent(target, {attrs: {'data-entity': 'dropdown-menu'}});
								if(!!parentMenu && BX.hasClass(parentMenu, 'catalog-menu')) {
									var dropDownMenu = target.querySelector('[data-entity="dropdown-menu"]');
									if(!!dropDownMenu) {
										BX.style(dropDownMenu, 'left', $(this.catalogMenu).width() + 'px');
										BX.style(dropDownMenu, 'top', this.calculateHeight(true) + 'px');
									}
								}
							}
						}, this));

						BX.bind(dropDownAll[i], 'mouseleave', BX.delegate(function() {
							var target = BX.proxy_context;

							BX.removeClass(target, 'hover');

							if(window.innerWidth >= 992) {
								var parentMenu = BX.findParent(target, {attrs: {'data-entity': 'dropdown-menu'}});
								if(!!parentMenu && BX.hasClass(parentMenu, 'catalog-menu')) {
									var dropDownMenu = target.querySelector('[data-entity="dropdown-menu"]');
									if(!!dropDownMenu) {
										BX.style(dropDownMenu, 'left', '');
									}
								}
							}
						}, this));
					}
				}
			}
		},
		calculateHeight: function(top = false){
			if(!top){
				if($('.bx-panel').length > 0){
					if($('.top-panel.fixed').length > 0)
						return document.documentElement.clientHeight - 72 - $('.bx-panel').height();
					else
						return document.documentElement.clientHeight - 144 - $('.bx-panel').height();
				}else{
					if($('.top-panel.fixed').length > 0)
						return document.documentElement.clientHeight - 72;
					else{
						return document.documentElement.clientHeight - 144;
					}
				}
			}else{
				if($('.bx-panel').length > 0){
					if($('.top-panel.fixed').length > 0)
						return 72 + $('.bx-panel').height();
					else
						return 144 + $('.bx-panel').height();
				}else{
					if($('.top-panel.fixed').length > 0)
						return 72;
					else{
						return 144;
					}
				}
			}
		},
		adjustCatalogMenu: function() {
			if(window.innerWidth >= 992) {
				$(this.catalogMenu).css('height', this.calculateHeight());
				if(!!this.menuIcon) {
					if(!!this.catalogMenuMoved) {
						this.menuIcon.appendChild(this.catalogMenu);
						this.catalogMenuMoved = false;

						this.resetCatalogMenu();
					}

					BX.unbind(this.menuIcon, 'click', BX.proxy(this.showHideCatalogMenu, this));
				}

				BX.unbind(document, 'click', BX.proxy(this.checkDropDownMenu, this));
				BX.unbind(document, 'keydown', BX.proxy(this.checkDropDownMenuKeydown, this));
			} else {
				if(!this.catalogMenuMoved) {
					var insertNode = document.body.querySelector('.page-wrapper');
					if(!!insertNode) {
						insertNode.appendChild(this.catalogMenu);
						this.catalogMenuMoved = true;

						this.adjustMainMenu();

						$(this.catalogMenu).scrollbar({
							disableBodyScroll: true
						});
						if(BX.hasClass(this.catalogMenu.parentNode, 'scroll-wrapper'))
							this.catalogMenu = this.catalogMenu.parentNode;

						if(this.catalogMenuSetActive)
							this.setCatalogMenuActive();
					}
				}

				if(!!this.menuIcon)
					BX.bind(this.menuIcon, 'click', BX.proxy(this.showHideCatalogMenu, this));

				BX.bind(document, 'click', BX.proxy(this.checkDropDownMenu, this));
				BX.bind(document, 'keydown', BX.proxy(this.checkDropDownMenuKeydown, this));
			}
		},

		hoverIntentIcon: function(icon) {
			$(icon).hoverIntent({
				over: BX.delegate(function() {
					if(window.innerWidth >= 992) {
						BX.addClass(icon, 'hover');
						BX.addClass(this.catalogMenu, 'active');

						document.body.appendChild(
							BX.create('DIV', {
								props: {
									className: 'modal-backdrop catalog-menu__backdrop fadeInBig'
								}
							})
						);
					}
				}, this),
				out: BX.delegate(function() {
					if(window.innerWidth >= 992) {
						BX.removeClass(icon, 'hover');
						BX.removeClass(this.catalogMenu, 'active');

						var catalogMenuBack = document.body.querySelector('.catalog-menu__backdrop');
						if(!!catalogMenuBack) {
							new BX.easing({
								duration: 300,
								start: {opacity: 100},
								finish: {opacity: 0},
								transition: BX.easing.transitions.linear,
								step: function(state) {
									catalogMenuBack.style.opacity = state.opacity / 100;
								},
								complete: function() {
									BX.remove(catalogMenuBack);
								}
							}).animate();
						}
					}
				}, this),
				timeout: 150
			});
		},

		resetCatalogMenu: function() {
			if(BX.hasClass(this.catalogMenu, 'scroll-wrapper')) {
				var catalogMenu = this.catalogMenu.querySelector('.scroll-content');
				if(!!catalogMenu) {
					if(BX.hasClass(this.catalogMenu, 'active'))
						BX.addClass(catalogMenu, 'active');
					$(catalogMenu).scrollbar('destroy');
					this.catalogMenu = catalogMenu;
				}
			}

			if(BX.hasClass(this.catalogMenu, 'active')) {
				BX.removeClass(this.catalogMenu, 'active');
				BX.removeClass(document.body, 'slide-menu-option-1-active');
			}

			var dropDownMenuAll = this.catalogMenu.querySelectorAll('[data-entity="dropdown-menu"]');
			if(!!dropDownMenuAll) {
				for(var i in dropDownMenuAll) {
					if(dropDownMenuAll.hasOwnProperty(i)) {
						BX.style(dropDownMenuAll[i], 'top', '');
						BX.style(dropDownMenuAll[i], 'bottom', '');

						if(BX.hasClass(dropDownMenuAll[i], 'active'))
							BX.removeClass(dropDownMenuAll[i], 'active');

						if(BX.hasClass(dropDownMenuAll[i], 'scroll-content'))
							$(dropDownMenuAll[i]).scrollbar('destroy');
					}
				}
			}

			var mainMenu = this.catalogMenu.querySelector('[data-role="mainMenu"]');
			if(!!mainMenu)
				BX.remove(mainMenu);
		},

		checkCatalogMenuTop: function() {
			var topPanelThead = document.body.querySelector('.top-panel__thead');

			if(document.getElementById('bx-panel') === null){ bxPanel = 0;} //Мой код если админ панели нет высота равна 0
			else{ var bxPanel = document.getElementById('bx-panel').offsetHeight;} //Если админ панель есть вычисляем высоту панели

			if(window.innerWidth < 992) {
				if(!!topPanelThead)
					BX.style(this.catalogMenu, 'top', 114 + bxPanel + 'px');//topPanelThead.getBoundingClientRect().top + topPanelThead.offsetHeight + 'px');
			} else {
				BX.style(this.catalogMenu, 'top', '');

				if($(this.catalogMenu).height() !== this.calculateHeight())
					BX.style(this.catalogMenu, 'height', this.calculateHeight() + 'px');
			}
		},

		adjustMainMenu: function() {
			var topMenu = document.body.querySelector('.horizontal-multilevel-menu'),
				mainMenu = !!topMenu && BX.clone(topMenu);

			if(!!mainMenu) {
				var mainMenuLiAll = BX.findChild(mainMenu, {tagName: 'LI'}, true, true);
				if(!!mainMenuLiAll) {
					for(var i in mainMenuLiAll) {
						if(mainMenuLiAll.hasOwnProperty(i)) {
							var mainMenuLiA = BX.findChild(mainMenuLiAll[i], {tagName: 'A'}, false, false);
							if(!!mainMenuLiA) {
								BX.adjust(mainMenuLiA, {
									html: '<span class="catalog-menu-text">' + mainMenuLiA.innerText + '</span>'
								});
								if(mainMenuLiAll[i].getAttribute('data-entity') == 'dropdown') {
									mainMenuLiA.appendChild(BX.create('SPAN', {
										props: {
											className: 'catalog-menu-arrow'
										},
										children: [
											BX.create('I', {
												props: {
													className: 'icon-arrow-right'
												}
											})
										]
									}));
								}

								var dropDownMenuTitle = BX.create('LI', {
									attrs: {
										'data-entity': 'title'
									},
									children: [
										BX.create('I', {
											props: {
												className: 'icon-arrow-left catalog-menu-back'
											}
										}),
										BX.create('SPAN', {
											props: {
												className: 'catalog-menu-title'
											},
											html: mainMenuLiA.innerText
										}),
										BX.create('I', {
											props: {
												className: 'icon-close catalog-menu-close'
											}
										})
									]
								});
							}

							var dropDownMenu = mainMenuLiAll[i].querySelector('[data-entity="dropdown-menu"]');
							if(!!dropDownMenu) {
								if(!!dropDownMenuTitle)
									BX.prepend(dropDownMenuTitle, dropDownMenu);

								BX.adjust(dropDownMenu, {
									props: {
										className: ''
									},
									attrs: {
										'data-entity': ''
									}
								});

								var dropDownMenuContainer = BX.create('DIV', {
									props: {
										className: 'catalog-menu-dropdown-menu scrollbar-inner'
									},
									attrs: {
										'data-entity': 'dropdown-menu'
									}
								});
								dropDownMenuContainer.appendChild(dropDownMenu);

								BX.append(dropDownMenuContainer, mainMenuLiAll[i]);
							}
						}
					}
				}

				BX.prepend(BX.create('LI', {
					attrs: {
						'data-entity': 'title'
					},
					children: [
						BX.create('I', {
							props: {
								className: 'icon-arrow-left catalog-menu-back'
							}
						}),
						BX.create('SPAN', {
							props: {
								className: 'catalog-menu-title'
							},
							html: BX.message('MAIN_MENU')
						}),
						BX.create('I', {
							props: {
								className: 'icon-close catalog-menu-close'
							}
						})
					]
				}), mainMenu);

				BX.adjust(mainMenu, {
					props: {
						className: ''
					},
					attrs: {
						'id': '',
						'data-entity': ''
					}
				});

				var mainMenuContainer = BX.create('DIV', {
					props: {
						className: 'catalog-menu-dropdown-menu scrollbar-inner'
					},
					attrs: {
						'data-entity': 'dropdown-menu'
					}
				});
				mainMenuContainer.appendChild(mainMenu);

				var mainMenuLi = BX.create('LI', {
					attrs: {
						'data-entity': 'dropdown',
						'data-role': 'mainMenu'
					},
					children: [
						BX.create('A', {
							attrs: {
								'href': 'javascript:void(0);'
							},
							children: [
								BX.create('SPAN', {
									props: {
										className: 'catalog-menu-text'
									},
									html: BX.message('MAIN_MENU')
								}),
								BX.create('SPAN', {
									props: {
										className: 'catalog-menu-arrow'
									},
									children: [
										BX.create('I', {
											props: {
												className: 'icon-arrow-right'
											}
										})
									]
								})
							]
						}),
					]
				});
				mainMenuLi.appendChild(mainMenuContainer);

				var subMenuAll = mainMenuLi.querySelectorAll('[data-entity="dropdown-menu"]');
				if(!!subMenuAll) {
					for(var i in subMenuAll) {
						if(subMenuAll.hasOwnProperty(i)) {
							var subMenuLiActive = subMenuAll[i].querySelector('.active');
							if(!!subMenuLiActive) {
								var parentMenuLi = BX.findParent(subMenuAll[i], {tagName: 'LI'});
								if(!!parentMenuLi)
									BX.addClass(parentMenuLi, 'active');
							}
						}
					}
				}

				BX.prepend(mainMenuLi, this.catalogMenu);
			}
		},

		setCatalogMenuActive: function() {
			var catalogMenuLiActiveAll = BX.findChild(this.catalogMenu, {tagName: 'li', className: 'active'}, true, true);
			if(!!catalogMenuLiActiveAll) {
				for(var i in catalogMenuLiActiveAll) {
					if(catalogMenuLiActiveAll.hasOwnProperty(i)) {
						var dropDownMenu = catalogMenuLiActiveAll[i].querySelector('[data-entity="dropdown-menu"]');
						if(!!dropDownMenu && !BX.hasClass(dropDownMenu, 'active'))
							BX.addClass(dropDownMenu, 'active');
					}
				}
			}

			var dropDownMenuActiveAll = BX.findChild(this.catalogMenu, {className: 'active', attrs: {'data-entity': 'dropdown-menu'}}, true, true);
			if(!!dropDownMenuActiveAll) {
				var dropDownMenuLastActive = dropDownMenuActiveAll[dropDownMenuActiveAll.length - 1];
				if(!!dropDownMenuLastActive) {
					if(BX.hasClass(this.catalogMenu, 'scroll-wrapper')) {
						var catalogMenu = this.catalogMenu.querySelector('.scroll-content');
						if(!!catalogMenu) {
							var catalogMenuTop = Math.abs(parseInt(BX.style(catalogMenu.parentNode, 'top'), 10));
							if(!!catalogMenuTop && catalogMenuTop > 0)
								BX.style(catalogMenu, 'top', catalogMenuTop + 'px');

							catalogMenu.scrollTop = 0;

							$(catalogMenu).scrollbar('destroy');

							this.catalogMenu = catalogMenu;
						}
					}

					if(!BX.hasClass(dropDownMenuLastActive, 'scroll-content')) {
						$(dropDownMenuLastActive).scrollbar({
							disableBodyScroll: true,
							onInit: function() {
								if(BX.hasClass(dropDownMenuLastActive, 'active'))
									BX.removeClass(dropDownMenuLastActive, 'active');
							}
						});
					}
				}
			}
		},

		showHideCatalogMenu: function(event) {
			if(!BX.hasClass(this.catalogMenu, 'active')) {
				BX.addClass(this.catalogMenu, 'active');
				BX.addClass(document.body, 'slide-menu-option-1-active');
			} else {
				BX.removeClass(this.catalogMenu, 'active');
				BX.removeClass(document.body, 'slide-menu-option-1-active');
			}
			event.stopPropagation();
		},

		checkDropDownMenu: function(event) {
			if(BX.hasClass(this.catalogMenu, 'active')) {
				var catalogMenuLi = event.target.tagName == 'LI' ? event.target : BX.findParent(event.target, {tagName: 'LI'});
				if(!!catalogMenuLi) {
					if(catalogMenuLi.getAttribute('data-entity') == 'dropdown') {
						var isCatalogMenuMainMenu = catalogMenuLi.hasAttribute('data-role') && catalogMenuLi.getAttribute('data-role') == 'mainMenu',
							isCatalogMenuArrow = BX.hasClass(event.target, 'catalog-menu-arrow') || (BX.findParent(event.target, {className: 'catalog-menu-arrow'}) ? true : false);

						if(!!isCatalogMenuMainMenu || this.catalogMenuOpenLast || (!this.catalogMenuOpenLast && !!isCatalogMenuArrow)) {
							BX.PreventDefault(event);

							var dropDownMenu = catalogMenuLi.querySelector('[data-entity="dropdown-menu"]');
							if(!!dropDownMenu) {
								var parentMenu = BX.findParent(dropDownMenu, {attrs: {'data-entity': 'dropdown-menu'}});
								if(!!parentMenu && BX.hasClass(parentMenu, 'scroll-content')) {
									var parentMenuScrollTop = parentMenu.scrollTop,
										parentMenuTop = Math.abs(parseInt(BX.style(parentMenu.parentNode, 'top'), 10)),
										parentMenuBottom = Math.abs(parseInt(BX.style(parentMenu.parentNode, 'bottom'), 10));

									if(!!parentMenuTop && parentMenuTop > 0)
										BX.style(parentMenu, 'top', parentMenuTop + 'px');

									if(!!parentMenuBottom && parentMenuBottom > 0)
										BX.style(parentMenu, 'bottom', parentMenuBottom * -1 + 'px');

									if(BX.hasClass(parentMenu.parentNode, 'active'))
										BX.addClass(parentMenu, 'active');

									$(parentMenu).scrollbar('destroy');

									if(BX.hasClass(parentMenu, 'catalog-menu'))
										this.catalogMenu = parentMenu;
								}

								$(dropDownMenu).scrollbar({
									disableBodyScroll: true,
									onInit: function() {
										dropDownMenu = dropDownMenu.parentNode;
										if(!!parentMenuScrollTop && parentMenuScrollTop > 0) {
											BX.style(dropDownMenu, 'top', parentMenuScrollTop + 'px');
											BX.style(dropDownMenu, 'bottom', parentMenuScrollTop * -1 + 'px');
										}
									}
								});

								new BX.easing({
									duration: 300,
									start: {left: 100},
									finish: {left: 0},
									transition: BX.easing.transitions.linear,
									step: function(state) {
										BX.style(dropDownMenu, 'left', state.left + '%');
									},
									complete: function() {
										BX.addClass(dropDownMenu, 'active');
										BX.style(dropDownMenu, 'left', '');
									}
								}).animate();
							}
						}
					} else if(catalogMenuLi.getAttribute('data-entity') == 'title') {
						var parentMenu = BX.findParent(catalogMenuLi, {attrs: {'data-entity': 'dropdown-menu'}});
						if(!!parentMenu) {
							var parentParentMenu = BX.findParent(parentMenu, {attrs: {'data-entity': 'dropdown-menu'}});
							if(!!parentParentMenu) {
								$(parentParentMenu).scrollbar({
									disableBodyScroll: true,
									onInit: BX.delegate(function() {
										var parentParentMenuTop = Math.abs(parseInt(BX.style(parentParentMenu, 'top'), 10));
										if(!!parentParentMenuTop && parentParentMenuTop > 0) {
											BX.style(parentParentMenu.parentNode, 'top', parentParentMenuTop + 'px');
											BX.style(parentParentMenu, 'top', '');
										}

										var parentParentMenuBottom = Math.abs(parseInt(BX.style(parentParentMenu, 'bottom'), 10));
										if(!!parentParentMenuBottom && parentParentMenuBottom > 0) {
											BX.style(parentParentMenu.parentNode, 'bottom', parentParentMenuBottom * -1 + 'px');
											BX.style(parentParentMenu, 'bottom', '');
										}

										if(BX.hasClass(parentParentMenu, 'active'))
											BX.removeClass(parentParentMenu, 'active');

										if(BX.hasClass(parentParentMenu.parentNode, 'catalog-menu'))
											this.catalogMenu = parentParentMenu.parentNode;
									}, this)
								});
							}

							if(BX.hasClass(parentMenu, 'scroll-content')) {
								if(BX.hasClass(parentMenu.parentNode, 'active'))
									BX.addClass(parentMenu, 'active');

								$(parentMenu).scrollbar('destroy');
							}

							new BX.easing({
								duration: 300,
								start: {left: 0},
								finish: {left: 100},
								transition: BX.easing.transitions.linear,
								step: function(state) {
									BX.style(parentMenu, 'left', state.left + '%');
								},
								complete: function() {
									BX.removeClass(parentMenu, 'active');
									BX.style(parentMenu, 'left', '');
								}
							}).animate();
						}
					}
				}
				event.stopPropagation();
			}
		},

		checkDropDownMenuKeydown: function(event) {
			if(BX.hasClass(this.catalogMenu, 'active') && event.keyCode == 27) {
				BX.removeClass(this.catalogMenu, 'active');
				BX.removeClass(document.body, 'slide-menu-option-1-active');
				event.stopPropagation();
			}
		}
	}
})();