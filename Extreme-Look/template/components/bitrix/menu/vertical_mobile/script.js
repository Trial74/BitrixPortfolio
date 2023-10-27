(function() {
	'use strict';

	if(!!window.JCSlideMenuHover)
		return;

	window.JCSlideMenuHover = function(params) {
		this.slideMenu = BX(params.container);
		/*$(this.slideMenu).scrollbar({
			disableBodyScroll: true
		});*/
		if(BX.hasClass(this.slideMenu.parentNode, 'scroll-wrapper'))
			this.slideMenu = this.slideMenu.parentNode;

		this.slideMenuSetActive = params.setActive;
		this.slideMenuOpenLast = params.openLast;

		this.slideMenuLoaded = true;

		this.menuIcon = document.body.querySelector('[data-entity="menu-icon"]');

		BX.ready(BX.delegate(this.init, this));
	};

	window.JCSlideMenuHover.prototype = {
		init: function() {
			var dropDownMenuAll = this.slideMenu.querySelectorAll('[data-entity="dropdown-menu"]');
			if(!!dropDownMenuAll) {
				for(var i in dropDownMenuAll) {
					if(dropDownMenuAll.hasOwnProperty(i)) {
						this.hoverIntentDropDownMenu(dropDownMenuAll[i]);

						var dropDownMenuLiActive = dropDownMenuAll[i].querySelector('.active');
						if(!!dropDownMenuLiActive) {
							var parentMenuLi = BX.findParent(dropDownMenuAll[i], {tagName: 'LI'});
							if(!!parentMenuLi)
								BX.addClass(parentMenuLi, 'active');
						}
					}
				}
			}

			this.adjustSlideMenu();
			BX.bind(window, 'resize', BX.proxy(this.adjustSlideMenu, this));

			this.resetDropDownAll();
			BX.bind(window, 'resize', BX.proxy(this.resetDropDownAll, this));

			this.checkSlideMenuTop();
			BX.bind(window, 'resize', BX.proxy(this.checkSlideMenuTop, this));
			BX.bind(window, 'scroll', BX.proxy(this.checkSlideMenuTop, this));
		},

		adjustSlideMenu: function() {
			if(window.innerWidth >= 1043) {
				if(!this.slideMenuLoaded) {
					this.slideMenuLoaded = true;

					this.resetSlideMenu();
				}

				if(!!this.menuIcon)
					BX.unbind(this.menuIcon, 'click', BX.proxy(this.showHideSlideMenu, this));

				BX.unbind(document, 'click', BX.proxy(this.checkDropDownMenu, this));
				BX.unbind(document, 'keydown', BX.proxy(this.checkDropDownMenuKeydown, this));
			} else {
				if(!!this.slideMenuLoaded) {
					this.slideMenuLoaded = false;

					this.adjustMainMenu();

					if(this.slideMenuSetActive)
						this.setSlideMenuActive();
				}

				if(!!this.menuIcon)
					BX.bind(this.menuIcon, 'click', BX.proxy(this.showHideSlideMenu, this));
			}
		},

		resetDropDownAll: function() {
			var dropDownAll = this.slideMenu.querySelectorAll('[data-entity="dropdown"]');
			if(!!dropDownAll) {
				for(var i in dropDownAll) {
					if(dropDownAll.hasOwnProperty(i)) {
						this.hoverIntentLi(dropDownAll[i]);
					}
				}
			}
		},

		hoverIntentLi: function(item) {
			$(item).hoverIntent({
				over: function() {
					BX.addClass(this, 'hover');
					if(window.innerWidth >= 1043) {
						var dropDownMenu = this.querySelector('[data-entity="dropdown-menu"]'),
							dropdowncont = $(".slide-menu-dropdown-menu");
						if(!!dropDownMenu) {
							if(BX.hasClass(dropDownMenu.parentNode, 'scroll-wrapper'))
								dropDownMenu = dropDownMenu.parentNode;

							var parentElem = $(this).get(0).getBoundingClientRect(),
								dropElem = $(dropDownMenu).get(0).getBoundingClientRect(),
								h = $(window).height(),
								parentMenu = BX.findParent(this, {tagName: 'UL'});
							if(!!parentMenu) {
								if(BX.hasClass(parentMenu.parentNode, 'scroll-wrapper'))
									parentMenu = parentMenu.parentNode;
								BX.style(dropDownMenu, 'left', parentMenu.getBoundingClientRect().left + parentMenu.offsetWidth + 'px'); //выводит выпадающее меню от левого края
								if(h < dropElem.bottom) {
									BX.style(dropDownMenu, 'top', parentElem.top - (dropElem.bottom - h) + 'px');
									BX.style(dropDownMenu, 'height', 'auto');
									BX.style(dropdowncont, 'height', 'auto');
								}
								else {
									BX.style(dropDownMenu, 'top', parentElem.top + 'px'); //выводит выпадающее меню от верхнего края
									BX.style(dropDownMenu, 'height', 'auto');
									BX.style(dropdowncont, 'height', 'auto');
								}
							}
						}
					}
				},
				out: function() {
					BX.removeClass(this, 'hover');
					if(window.innerWidth >= 1043) {
						var dropDownMenu = this.querySelector('[data-entity="dropdown-menu"]');
						if(!!dropDownMenu) {
							if(BX.hasClass(dropDownMenu.parentNode, 'scroll-wrapper'))
								dropDownMenu = dropDownMenu.parentNode;

							BX.style(dropDownMenu, 'left', '');
						}
					}
				},
				timeout: 200
			});
		},

		hoverIntentDropDownMenu: function(dropDownMenu) {
			$(dropDownMenu).hoverIntent({
				over: BX.delegate(function() {
					if(window.innerWidth >= 1043) {
						var parentMenu = BX.findParent(dropDownMenu, {tagName: 'UL'});
						if(!!parentMenu && BX.hasClass(parentMenu, 'scroll-content')) {
							var parentMenuTop = Math.abs(parseInt(BX.style(parentMenu.parentNode, 'top'), 10));
							if(!!parentMenuTop && parentMenuTop > 0)
								BX.style(parentMenu, 'top', parentMenuTop + 'px');

							var parentMenuLeft = Math.abs(parseInt(BX.style(parentMenu.parentNode, 'left'), 10));
							if(!!parentMenuLeft && parentMenuLeft > 0)
								BX.style(parentMenu, 'left', parentMenuLeft + 'px');

							$(parentMenu).scrollbar('destroy');

							if(BX.hasClass(parentMenu, 'slide-menu'))
								this.slideMenu = parentMenu;
						}

						$(dropDownMenu).scrollbar({
							disableBodyScroll: true,
							onInit: function() {
								var dropDownMenuTop = Math.abs(parseInt(BX.style(dropDownMenu, 'top'), 10));
								if(!!dropDownMenuTop && dropDownMenuTop > 0) {
									BX.style(dropDownMenu.parentNode, 'top', dropDownMenuTop + 'px');
									BX.style(dropDownMenu, 'top', '');
								}

								var dropDownMenuLeft = Math.abs(parseInt(BX.style(dropDownMenu, 'left'), 10));
								if(!!dropDownMenuLeft && dropDownMenuLeft > 0) {
									BX.style(dropDownMenu.parentNode, 'left', dropDownMenuLeft + 'px');
									BX.style(dropDownMenu, 'left', '');
								}
							}
						});
					}
				}, this),
				out: BX.delegate(function() {
					if(window.innerWidth >= 1043) {
						if(BX.hasClass(dropDownMenu, 'scroll-content')) {
							var dropDownMenuTop = Math.abs(parseInt(BX.style(dropDownMenu.parentNode, 'top'), 10));
							if(!!dropDownMenuTop && dropDownMenuTop > 0)
								BX.style(dropDownMenu, 'top', dropDownMenuTop + 'px');

							var dropDownMenuLeft = Math.abs(parseInt(BX.style(dropDownMenu.parentNode, 'left'), 10));
							if(!!dropDownMenuLeft && dropDownMenuLeft > 0)
								BX.style(dropDownMenu, 'left', dropDownMenuLeft + 'px');

							$(dropDownMenu).scrollbar('destroy');
						}

						var parentMenu = BX.findParent(dropDownMenu, {tagName: 'UL'});
					}
				}, this),
				timeout: 0
			});
		},

		resetSlideMenu: function() {
			var dropDownMenuAll = this.slideMenu.querySelectorAll('[data-entity="dropdown-menu"]');
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

			var mainMenu = this.slideMenu.querySelector('[data-role="mainMenu"]');
			if(!!mainMenu)
				BX.remove(mainMenu);

			if(BX.hasClass(this.slideMenu, 'active')) {
				BX.removeClass(this.slideMenu, 'active');
				BX.removeClass(document.body, 'slide-menu-option-1-active');
			}

			if(!BX.hasClass(this.slideMenu, 'scroll-wrapper')) {
				/*$(this.slideMenu).scrollbar({
					disableBodyScroll: true,
					onInit: BX.delegate(function() {
						BX.style(this.slideMenu, 'top', '');
					}, this)
				});*/
				if(BX.hasClass(this.slideMenu.parentNode, 'scroll-wrapper'))
					this.slideMenu = this.slideMenu.parentNode;
			}
		},

		checkSlideMenuTop: function() {


			var dropDownMenuAll = this.slideMenu.querySelectorAll('[data-entity="dropdown-menu"]');
			if(!!dropDownMenuAll) {

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
									html: '<span class="slide-menu-text">' + mainMenuLiA.innerText + '</span>'
								});
								if(mainMenuLiAll[i].getAttribute('data-entity') == 'dropdown') {
									mainMenuLiA.appendChild(BX.create('SPAN', {
										props: {
											className: 'slide-menu-arrow'
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
												className: 'icon-arrow-left slide-menu-back'
											}
										}),
										BX.create('SPAN', {
											props: {
												className: 'slide-menu-title'
											},
											html: mainMenuLiA.innerText
										}),
										BX.create('I', {
											props: {
												className: 'icon-close slide-menu-close'
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
										className: 'slide-menu-dropdown-menu scrollbar-inner'
									}
								});
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
								className: 'icon-arrow-left slide-menu-back'
							}
						}),
						BX.create('SPAN', {
							props: {
								className: 'slide-menu-title'
							},
							html: BX.message('MAIN_MENU')
						}),
						BX.create('I', {
							props: {
								className: 'icon-close slide-menu-close'
							}
						})
					]
				}), mainMenu);

				BX.adjust(mainMenu, {
					props: {
						className: 'slide-menu-dropdown-menu scrollbar-inner'
					},
					attrs: {
						'id': '',
						'data-entity': 'dropdown-menu'
					}
				});

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
										className: 'slide-menu-text'
									},
									html: BX.message('MAIN_MENU')
								}),
								BX.create('SPAN', {
									props: {
										className: 'slide-menu-arrow'
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
				mainMenuLi.appendChild(mainMenu);

				var dropDownMenuAll = mainMenuLi.querySelectorAll('[data-entity="dropdown-menu"]');
				if(!!dropDownMenuAll) {
					for(var i in dropDownMenuAll) {
						if(dropDownMenuAll.hasOwnProperty(i)) {
							var dropDownMenuLiActive = dropDownMenuAll[i].querySelector('.active');
							if(!!dropDownMenuLiActive) {
								var parentMenuLi = BX.findParent(dropDownMenuAll[i], {tagName: 'LI'});
								if(!!parentMenuLi)
									BX.addClass(parentMenuLi, 'active');
							}
						}
					}
				}

				if(BX.hasClass(this.slideMenu, 'scroll-wrapper')) {
					var slideMenuUl = this.slideMenu.querySelector('.scroll-content');
					if(!!slideMenuUl)
						BX.prepend(mainMenuLi, slideMenuUl);
				} else {
					BX.prepend(mainMenuLi, this.slideMenu);
				}
			}
		},

		setSlideMenuActive: function() {
			var slideMenuLiActiveAll = BX.findChild(this.slideMenu, {tagName: 'li', className: 'active'}, true, true);
			if(!!slideMenuLiActiveAll) {
				for(var i in slideMenuLiActiveAll) {
					if(slideMenuLiActiveAll.hasOwnProperty(i)) {
						var dropDownMenu = slideMenuLiActiveAll[i].querySelector('[data-entity="dropdown-menu"]');
						if(!!dropDownMenu && !BX.hasClass(dropDownMenu, 'active'))
							BX.addClass(dropDownMenu, 'active');
					}
				}
			}

			var dropDownMenuActiveAll = BX.findChild(this.slideMenu, {tagName: 'ul', className: 'active'}, true, true);
			if(!!dropDownMenuActiveAll) {
				var dropDownMenuLastActive = dropDownMenuActiveAll[dropDownMenuActiveAll.length - 1];
				if(!!dropDownMenuLastActive) {
					if(BX.hasClass(this.slideMenu, 'scroll-wrapper')) {
						var slideMenuUl = this.slideMenu.querySelector('.scroll-content');
						if(!!slideMenuUl) {
							var slideMenuTop = Math.abs(parseInt(BX.style(slideMenuUl.parentNode, 'top'), 10));
							if(!!slideMenuTop && slideMenuTop > 0)
								BX.style(slideMenuUl, 'top', slideMenuTop + 'px');

							slideMenuUl.scrollTop = 0;

							$(slideMenuUl).scrollbar('destroy');

							this.slideMenu = slideMenuUl;
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

		showHideSlideMenu: function(event) {
			var menuIc = document.getElementById('cont-menu-left-id');
			if(!BX.hasClass(this.slideMenu, 'active')) {
				BX.addClass(this.slideMenu, 'active');
				BX.addClass(document.body, 'slide-menu-option-1-active');
				menuIc.style.display = 'block';
			} else {
				BX.removeClass(this.slideMenu, 'active');
				BX.removeClass(document.body, 'slide-menu-option-1-active');
				menuIc.style.display = 'none';
			}
			event.stopPropagation();
		},

		checkDropDownMenu: function(event) {
			if(BX.hasClass(this.slideMenu, 'active')) {
				var slideMenuLi = event.target.tagName == 'LI' ? event.target : BX.findParent(event.target, {tagName: 'LI'});
				if(!!slideMenuLi) {
					if(slideMenuLi.getAttribute('data-entity') == 'dropdown') {
						var isSlideMenuMainMenu = slideMenuLi.hasAttribute('data-role') && slideMenuLi.getAttribute('data-role') == 'mainMenu',
							isSlideMenuArrow = BX.hasClass(event.target, 'slide-menu-arrow') || (BX.findParent(event.target, {className: 'slide-menu-arrow'}) ? true : false);

						if(!!isSlideMenuMainMenu || this.slideMenuOpenLast || (!this.slideMenuOpenLast && !!isSlideMenuArrow)) {
							BX.PreventDefault(event);

							var dropDownMenu = slideMenuLi.querySelector('[data-entity="dropdown-menu"]');
							if(!!dropDownMenu) {
								var parentMenu = BX.findParent(dropDownMenu, {tagName: 'UL'});
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

									if(BX.hasClass(parentMenu, 'slide-menu'))
										this.slideMenu = parentMenu;
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
					} else if(slideMenuLi.getAttribute('data-entity') == 'title') {
						var parentMenu = BX.findParent(slideMenuLi, {tagName: 'UL'});
						if(!!parentMenu) {
							var parentParentMenu = BX.findParent(parentMenu, {tagName: 'UL'});
							if(parentParentMenu) {
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
			if(BX.hasClass(this.slideMenu, 'active') && event.keyCode == 27) {
				BX.removeClass(this.slideMenu, 'active');
				BX.removeClass(document.body, 'slide-menu-option-1-active');
				event.stopPropagation();
			}
		}
	}
})();