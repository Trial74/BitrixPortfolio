(function (window) {
	if(!!window.JCCatalogProductSubscribe) {
		return;
	}

	 var subscribeButton = function(params) {
		subscribeButton.superclass.constructor.apply(this, arguments);		
		this.buttonNode = BX.create("button", {
			children: [
				BX.create('span', {
					text: params.textBut, //BX.message('CPST_SUBSCRIBE_BUTTON_NAME'),
				})
			],
			attrs: {
				className: params.className
			},
			events : this.contextEvents
		});
	};
	BX.extend(subscribeButton, BX.PopupWindowButton);

	window.JCCatalogProductSubscribe = function(params) {		
		this.buttonId = params.buttonId;
		this.buttonClass = params.buttonClass;
		this.productId = params.productId;
		this.jsObject = params.jsObject;
		this.ajaxUrl = '/bitrix/components/bitrix/catalog.product.subscribe/ajax.php';
		this.ajaxUrlList = '/bitrix/components/bitrix/catalog.product.subscribe.list/ajax.php'; //Путь до компонента аякса в котором отписываем пользователя
		this.ajaxUrlReSub = '/bitrix/templates/enext/components/bitrix/catalog.product.subscribe/customSubscribeVlad/ajax.php'
		this.ajaxPath = params.ajaxPath;
		this.alreadySubscribed = params.alreadySubscribed;
		this.urlListSubscriptions = params.urlListSubscriptions;
		this.listOldItemId = {};
		this.landingId = params.landingId;
		this.listSubVlad = params.LIST_SUBSCRIPTIONS;

		this.elemButtonSubscribe = null;
		this.elemPopupWin = null;		

		this._elemButtonSubscribeClickHandler = BX.delegate(this.subscribe, this);
		this._elemButtonUnSubscribeClickHandler = BX.delegate(this.UnSubscribe, this); //Мой код делегируем функцию отписки
		this._elemHiddenClickHandler = BX.delegate(this.checkSubscribe, this);

		BX.ready(BX.delegate(this.init,this));
	};

	window.JCCatalogProductSubscribe.prototype.init = function() {
		if(!!this.buttonId) {
			this.elemButtonSubscribe = BX(this.buttonId);
			this.elemHiddenSubscribe = BX(this.buttonId + '_hidden');
		}

		if(!!this.elemButtonSubscribe) {
			BX.bind(this.elemButtonSubscribe, 'click', this._elemButtonSubscribeClickHandler);
		}

		if(!!this.elemHiddenSubscribe) {
			BX.bind(this.elemHiddenSubscribe, 'click', this._elemHiddenClickHandler);
		}

		this.setButton(this.alreadySubscribed);
		
		BX.ajax({
			url: this.ajaxPath,
			method: 'POST',
			dataType: 'json',
			timeout: 60,
			data: {
				action: 'checkAlreadySubscribed',
				productId: this.productId
			},
			onsuccess: BX.delegate(function(result) {
				this.setButton(result.alreadySubscribed);
			}, this)
		});
	};

	window.JCCatalogProductSubscribe.prototype.checkSubscribe = function() {
		if(!this.elemHiddenSubscribe || !this.elemButtonSubscribe)
			return;

		if(this.listOldItemId.hasOwnProperty(this.elemButtonSubscribe.dataset.item)) {
			this.setButton(true);
		} else {
			BX.ajax({
				method: 'POST',
				dataType: 'json',
				url: this.ajaxUrl,
				data: {
					sessid: BX.bitrix_sessid(),
					checkSubscribe: 'Y',
					itemId: this.elemButtonSubscribe.dataset.item
				},
				onsuccess: BX.delegate(function(result) {					
					if(result.subscribe) {
						this.setButton(true);
						this.listOldItemId[this.elemButtonSubscribe.dataset.item] = true;
					} else {
						this.setButton(false);
					}
				}, this)
			});
		}
	};

	window.JCCatalogProductSubscribe.prototype.subscribe = function() {
		this.elemButtonSubscribe = BX.proxy_context;
		if(!this.elemButtonSubscribe)
			return false;

		BX.ajax({
			method: 'POST',
			dataType: 'json',
			url: this.ajaxUrl,
			data: {
				sessid: BX.bitrix_sessid(),
				subscribe: 'Y',
				itemId: this.elemButtonSubscribe.dataset.item,
				siteId: BX.message('SITE_ID'),
				landingId: this.landingId
			},
			onsuccess: BX.delegate(function(result) {
				if(result.success) {
					this.createSuccessPopup(result);
					this.setButton(true);
					this.listOldItemId[this.elemButtonSubscribe.dataset.item] = true;
				} else if(result.contactFormSubmit) {
					$('body').css("top", ""); //Мой код фикс глюка шаблона не появляется окошко в предпросмотре товара убираем топы окна предпросмотра в боди
					this.initPopupWindow();
					this.elemPopupWin.setTitleBar(false); //BX.message('CPST_SUBSCRIBE_POPUP_TITLE')
					var form = this.createContentForPopup(result);
					this.elemPopupWin.setContent(form);
					this.elemPopupWin.setButtons([
						new subscribeButton({
							className: 'btn btn-buy',
							textBut: 'Сообщить о поступлении',
							events: {
								click: BX.delegate(function() {
									if(!this.validateContactField(result.contactTypeData)) {
										return false;
									}
									BX.ajax.submitAjax(form, {
										method: 'POST',
										url: this.ajaxUrl,
										processData: true,
										onsuccess: BX.delegate(function (resultForm) {
											resultForm = BX.parseJSON(resultForm, {});
											if(resultForm.success) {
												this.createSuccessPopup(resultForm);
												this.setButton(true);
												this.listOldItemId[this.elemButtonSubscribe.dataset.item] = true;
											} else if(resultForm.error) {
												if(resultForm.hasOwnProperty('setButton')) {
													this.listOldItemId[this.elemButtonSubscribe.dataset.item] = true;
													this.setButton(true);
												}
												var errorMessage = BX.create("span", {
													props: {
														className: 'alert alert-error alert-show'
													},
													html: resultForm.hasOwnProperty('typeName') ? resultForm.message.replace('USER_CONTACT', resultForm.typeName) : resultForm.message
												});
												BX('bx-catalog-subscribe-form-notify').innerHTML = '';
												BX('bx-catalog-subscribe-form-notify').appendChild(errorMessage);
											}
										}, this)
									});
								}, this)
							}
						})
					]);
					this.elemPopupWin.show();
				} else if(result.error) {
					if(result.hasOwnProperty('setButton')) {
						this.listOldItemId[this.elemButtonSubscribe.dataset.item] = true;
						this.setButton(true);
					}
					this.showWindowWithAnswer({status: 'error', message: result.message});
				}				
			}, this)
		});
	};

	window.JCCatalogProductSubscribe.prototype.UnSubscribe = function() {
		BX.ajax({
			method: 'POST',
			dataType: 'json',
			url: this.ajaxUrlList,
			data: {
				sessid: BX.bitrix_sessid(),
				deleteSubscribe: 'Y',
				itemId: this.productId,
				listSubscribeId: this.listSubVlad[this.productId]
			},
			onsuccess: BX.delegate(function(result) {
				if(result.success) {
					this.initPopupWindow();
					this.elemPopupWin.setTitleBar(false); //BX.message('CPST_SUBSCRIBE_POPUP_TITLE')
					var content = BX.create('DIV', {
						props:{
							className: 'popup-ex-content'
						},
						children: [
							BX.create('DIV', {
								props: {
									className: 'img-sub-ex'
								},
								children: [
									BX.create('img', {
										attrs: {
											width: '70px',
											src: '/bitrix/templates/enext/fonts/icon_extreme/subscribe/icon_2.png'
										}
									})
								]
							}),
							BX.create('DIV' ,{
								props: {
									className: 'ex-sub-info'
								},
								children: [
									BX.create('span', {
										props: {
											className: 'popup-window-titlebar-text-ex-sub'
										},
										text: 'Узнать о поступлении товара'
									}),
									BX.create('span', {
										props: {
											className: 'alert-ex-suc-sub'
										},
										html: 'Вы успешно отписались от товара.'
									})
								]
							})
						]
					});

					this.elemPopupWin.setContent(content);
					this.elemPopupWin.setButtons(
						[
							new subscribeButton({
								className: 'btn btn-buy',
								events: {
									click : BX.delegate(function() { this.elemPopupWin.close(); }, this)
								},
								textBut: 'Ок'
							})
						]
					);
					this.elemPopupWin.show();
					this.setButton(false);

					//window.location.reload();
				} else {
					console.log(result.message);
				}
			}, this)
		});
	};

	window.JCCatalogProductSubscribe.prototype.validateContactField = function(contactTypeData) {
		var inputFields = BX.findChildren(BX('bx-catalog-subscribe-form'), {'tag': 'input', 'attribute': {id: 'userContact'}}, true);
		if(!inputFields.length || typeof contactTypeData !== 'object') {
			var errorMessage = BX.create("span", {
				props: {
					className: 'alert alert-error alert-show'
				},
				html: BX.message('CPST_SUBSCRIBE_VALIDATE_UNKNOW_ERROR')
			});
			BX('bx-catalog-subscribe-form-notify').innerHTML = '';
			BX('bx-catalog-subscribe-form-notify').appendChild(errorMessage);			
			return false;
		}

		var contactTypeId, contactValue, useContact, errors = [], useContactErrors = [];
		for(var k = 0; k < inputFields.length; k++) {
			contactTypeId = inputFields[k].getAttribute('data-id');
			contactValue = inputFields[k].value;
			useContact = BX('bx-contact-use-'+contactTypeId);
			if(useContact && useContact.value == 'N') {
				useContactErrors.push(true);
				continue;
			}
			if(!contactValue.length) {
				errors.push(BX.message('CPST_SUBSCRIBE_VALIDATE_ERROR_EMPTY_FIELD').replace('#FIELD#', contactTypeData[contactTypeId].contactLable));
			}
		}

		if(inputFields.length == useContactErrors.length) {
			var errorMessage = BX.create("span", {
				props: {
					className: 'alert alert-error alert-show'
				},
				html: BX.message('CPST_SUBSCRIBE_VALIDATE_ERROR')
			});
			BX('bx-catalog-subscribe-form-notify').innerHTML = '';
			BX('bx-catalog-subscribe-form-notify').appendChild(errorMessage);
			return false;
		}

		if(errors.length) {
			var errorMessage = BX.create("span", {
				props: {
					className: 'alert alert-error alert-show'
				},
				style: {
					display: 'block'
				},
				html: errors.join("<br />")				
			});
			BX('bx-catalog-subscribe-form-notify').innerHTML = '';
			BX('bx-catalog-subscribe-form-notify').appendChild(errorMessage);
			return false;
		}

		return true;
	};

	window.JCCatalogProductSubscribe.prototype.reloadCaptcha = function() {
		var form = BX('bx-catalog-subscribe-form'),
			captchaSid = BX.findChild(form, {attribute: {name: "captcha_sid"}}, true, false),
			captchaImg = BX.findChild(form, {attribute: {id: "captcha_img"}}, true, false),
			captchaWord = BX.findChild(form, {attribute: {name: "captcha_word"}}, true, false);
		BX.ajax.get(this.ajaxUrl + '?reloadCaptcha=Y', '', function(captchaCode) {
			if(!!captchaSid)
				captchaSid.value = captchaCode;
			if(!!captchaImg)
				captchaImg.src = '/bitrix/tools/captcha.php?captcha_sid=' + captchaCode + '';
			if(!!captchaWord)
				captchaWord.value = "";
		});
	};

	window.JCCatalogProductSubscribe.prototype.createContentForPopup = function(responseData) {
		if(!responseData.hasOwnProperty('contactTypeData')) {
			return null;
		}

		var contactTypeData = responseData.contactTypeData, contactCount = Object.keys(contactTypeData).length,
			styleInputForm = '', manyContact = 'N', content = document.createDocumentFragment();

		content.appendChild(
			BX.create('DIV', {
				props:{
					className: 'popup-ex-content'
				},
				children: [
					BX.create('DIV', {
						props: {
							className: 'img-sub-ex'
						},
						children: [
							BX.create('img', {
								attrs: {
									width: '70px',
									src: '/bitrix/templates/enext/fonts/icon_extreme/subscribe/icon_1.png'
								}
							})
						]
					}),
					BX.create('DIV' ,{
						props: {
							className: 'ex-sub-info'
						},
						children: [
							BX.create('span', {
								props: {
									className: 'popup-window-titlebar-text-ex-sub'
								},
								text: 'Узнать о поступлении товара'
							}),
							BX.create('span', {
								props: {
									className: 'alert-ex-suc-sub'
								},
								html: 'Мы обязательно сообщим Вам, <br />когда в продаже появится выбранный Вами товар!'
							}),
							BX.create('DIV', {
								props: {
									className: 'form-group form-group-checkbox ex-group-check'
								},
								children: [
									BX.create('DIV', {
										props: {
											className: 'checkbox'
										},
										children: [
											BX.create('label', {
												props: {
													className: 'main-user-consent-request'
												},
												children: [
													BX.create('input',{
														props: {
															type: 'checkbox',
															value: 'Y',
															name: 'sub_news'
														},
														attrs:{
															checked: 'checked'
														}
													}),
													BX.create('span', {
														props: {
															className: 'check-cont'
														},
														children: [
															BX.create('span', {
																props: {
																	className: 'check'
																},
																children: [
																	BX.create('i', {
																		props: {
																			className: 'icon-ok-b'
																		}
																	})
																]
															})
														]
													}),
													BX.create('a', {
														props: {
															className: 'check-title'
														},
														html: 'Получать информацию о новинках, закрытых<br /> распродажах, акциях и о многом другом'
													})
												]
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

		content.appendChild(BX.create('DIV', {
			props: {
				id: 'bx-catalog-subscribe-form-notify',
				className: 'bx-catalog-subscribe-form-notify'
			}
		}));

		if(contactCount > 1) {
			manyContact = 'Y';
			styleInputForm = 'display: none;';
			content.appendChild(BX.create('DIV', {
				props: {
					className: 'bx-catalog-subscribe-form-caption'
				},
				text: BX.message('CPST_SUBSCRIBE_MANY_CONTACT_NOTIFY')
			}));
		}

		for(var k in contactTypeData) {
			if(contactCount > 1) {
				content.appendChild(BX.create('div', {
					props: {
						className: 'form-group'
					},
					children: [
						BX.create('DIV', {
							props: {
								className: 'checkbox'
							},
							children: [
								BX.create('input', {
									props: {
										type: 'hidden',
										id: 'bx-contact-use-'+k,
										name: 'contact['+k+'][use]',
										value: 'N'
									}
								}),
								BX.create('input', {
									props: {
										id: 'bx-contact-checkbox-'+k,
										type: 'checkbox'
									}
								}),
								BX.create('label', {									
									attrs: {
										onclick: this.jsObject+'.selectContactType('+k+', event);'
									},
									children: [
										BX.create('SPAN', {
											props: {
												className: 'check-cont'
											},
											children: [
												BX.create('SPAN', {
													props: {
														className: 'check'
													},
													children: [
														BX.create('I', {
															props: {
																className: 'icon-ok-b'
															}
														})
													]
												})
											]
										}),
										BX.create('SPAN', {
											props: {
												className: 'check-title'
											},
											text: contactTypeData[k].contactLable
										})
									]
								})
							]
						})
					]
				}));
			}
			content.appendChild(BX.create('DIV', {
				props: {
					id: 'bx-catalog-subscribe-form-container-'+k,
					className: 'form-group',
					style: styleInputForm
				},
				children: [
					BX.create('input', {
						props: {
							id: 'userContact',
							className: 'form-control',
							type: 'text',
							name: 'contact['+k+'][user]'
						},
						attrs: {
							'data-id': k,
							placeholder: 'Email адрес'//contactTypeData[k].contactLable
						}
					})
				]
			}));
		}
		if(responseData.hasOwnProperty('captchaCode')) {
			content.appendChild(BX.create('DIV', {
				props: {
					className: 'form-group captcha'
				},
				children: [					
					BX.create('DIV', {
						props: {className: 'pic'},
						children: [							
							BX.create('img', {
								props: {
									id: 'captcha_img',
									src: '/bitrix/tools/captcha.php?captcha_sid=' + responseData.captchaCode + ''
								},
								attrs: {
									width: '100',
									height: '36',
									alt: 'CAPTCHA',
									onclick: this.jsObject+'.reloadCaptcha();'
								}
							})
						]
					}),					
					BX.create('input', {
						props: {							
							id: 'captcha_word',
							className: 'form-control',
							type: 'text',
							name: 'captcha_word'
						},
						attrs: {
							maxlength: '5',
							placeholder: BX.message('CPST_ENTER_WORD_PICTURE')
						}
					}),
					BX.create('input', {
						props: {
							type: 'hidden',
							id: 'captcha_sid',
							name: 'captcha_sid',
							value: responseData.captchaCode
						}
					})
				]
			}));
		}
		var form = BX.create('form', {
			props: {
				id: 'bx-catalog-subscribe-form'
			},
			children: [
				BX.create('input', {
					props: {
						type: 'hidden',
						name: 'manyContact',
						value: manyContact
					}
				}),
				BX.create('input', {
					props: {
						type: 'hidden',
						name: 'sessid',
						value: BX.bitrix_sessid()
					}
				}),
				BX.create('input', {
					props: {
						type: 'hidden',
						name: 'itemId',
						value: this.elemButtonSubscribe.dataset.item
					}
				}),
				BX.create('input', {
					props: {
						type: 'hidden',
						name: 'landingId',
						value: this.landingId
					}
				}),
				BX.create('input', {
					props: {
						type: 'hidden',
						name: 'siteId',
						value: BX.message('SITE_ID')
					}
				}),
				BX.create('input', {
					props: {
						type: 'hidden',
						name: 'contactFormSubmit',
						value: 'Y'
					}
				})
			]
		});

		form.appendChild(content);

		return form;
	};

	window.JCCatalogProductSubscribe.prototype.selectContactType = function(contactTypeId, event) {				
		var contactInput = BX('bx-catalog-subscribe-form-container-'+contactTypeId),
			visibility = '',
			checkboxInput = BX('bx-contact-checkbox-'+contactTypeId);
		
		if(!contactInput) {
			return false;
		}
		
		if(checkboxInput != event.target) {
			if(checkboxInput.checked) {
				checkboxInput.checked = false;
			} else {
				checkboxInput.checked = true;
			}
		}
		
		if(contactInput.currentStyle) {
			visibility = contactInput.currentStyle.display;
		} else if(window.getComputedStyle) {
			var computedStyle = window.getComputedStyle(contactInput, null);
			visibility = computedStyle.getPropertyValue('display');
		}

		if(visibility === 'none') {
			BX('bx-contact-use-'+contactTypeId).value = 'Y';
			BX.style(contactInput, 'display', '');
		} else {
			BX('bx-contact-use-'+contactTypeId).value = 'N';
			BX.style(contactInput, 'display', 'none');
		}
	};

	window.JCCatalogProductSubscribe.prototype.createSuccessPopup = function(result) {
		$('body').css("top", ""); //Мой код фикс глюка шаблона не появляется окошко в предпросмотре товара убираем топы окна предпросмотра в боди
		this.initPopupWindow();
		this.elemPopupWin.setTitleBar(false); //BX.message('CPST_SUBSCRIBE_POPUP_TITLE')
		var content = BX.create('DIV', {
			props:{
				className: 'popup-ex-content'
			},
			children: [
				BX.create('DIV', {
					props: {
						className: 'img-sub-ex'
					},
					children: [
						BX.create('img', {
							attrs: {
								width: '70px',
								src: '/bitrix/templates/enext/fonts/icon_extreme/subscribe/icon_2.png'
							}
						})
					]
				}),
				BX.create('DIV' ,{
					props: {
						className: 'ex-sub-info'
					},
					children: [
						BX.create('span', {
							props: {
								className: 'popup-window-titlebar-text-ex-sub'
							},
							text: 'Узнать о поступлении товара'
						}),
						BX.create('span', {
							props: {
								className: 'alert-ex-suc-sub'
							},
							html: 'Вы успешно подписались на уведомление о поступлении товара.<br /> Вы получите уведомление когда товар поступит в продажу.'
						})
					]
				})
			]
		});

		this.elemPopupWin.setContent(content);
		this.elemPopupWin.setButtons(
			[
				new subscribeButton({
					className: 'btn btn-buy',
					events: {
						click : BX.delegate(function() { this.elemPopupWin.close(); }, this)
					},
					textBut: 'Ок'
				})
			]
		);
		this.elemPopupWin.show();
	};

	window.JCCatalogProductSubscribe.prototype.initPopupWindow = function() {
		this.elemPopupWin = BX.PopupWindowManager.create('CatalogSubscribe_'+this.buttonId, null, {
			autoHide: false,
			offsetLeft: 0,
			offsetTop: 0,
			overlay: {
				opacity: 50
			},
			closeByEsc: true,
			titleBar: true,
			closeIcon: true,
			className: 'bx-catalog-subscribe-popup-window',
			contentColor: 'white'
		});

		var close = BX.findChild(BX("CatalogSubscribe_" + this.buttonId), {className: "popup-window-close-icon"}, true, false);
		if(!!close)
			close.innerHTML = "<i class='icon-close'></i>";
	};

	window.JCCatalogProductSubscribe.prototype.setButton = function(statusSubscription) {
		this.alreadySubscribed = Boolean(statusSubscription);
		if(this.alreadySubscribed) { //Мой код в этом месте определяется есть подписка на товар или нет.
			BX.ajax({//Мой код если подписка на товар есть делаем кнопку на отписку перед этим обновляем глобал обновляя объект подписок пользователя для последующего удаления подписок со страницы раздела/товара
				url: this.ajaxUrlReSub,
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: {
					action: 'reArSubUser'
				},
				onsuccess: BX.delegate(function(result) {
					this.listSubVlad = result.global; //Обновляем глобал
					BX.adjust(this.elemButtonSubscribe, {
						props: {
							disabled: false, //Не блокируем кнопку
							title: BX.message("CPST_TITLE_ALREADY_SUBSCRIBED")
						},
						html: "<i class='icon-mail-sub'></i><span>" + BX.message("CPST_TITLE_ALREADY_SUBSCRIBED") + "</span>"
					});

					BX.addClass(this.elemButtonSubscribe, 'sub-btn-vlad');
					BX.unbind(this.elemButtonSubscribe, 'click', this._elemButtonSubscribeClickHandler);
					BX.bind(this.elemButtonSubscribe, 'click', this._elemButtonUnSubscribeClickHandler);
				}, this)
			});
		} else {
			BX.ajax({ //Мой код если подписки на товар нет делаем кнопку на подписку перед этим обновляем глобал обновляя объект подписок пользователя для последующего удаления подписок со страницы раздела/товара
				url: this.ajaxUrlReSub,
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: {
					action: 'reArSubUser'
				},
				onsuccess: BX.delegate(function(result) {
					this.listSubVlad = result.global; //Обновляем глобал
					BX.adjust(this.elemButtonSubscribe, {
						props: {
							disabled: false,
							title: BX.message("CPST_SUBSCRIBE_BUTTON_NAME")
						},
						html: "<i class='icon-mail'></i><span>" + BX.message("CPST_SUBSCRIBE_BUTTON_NAME") + "</span>"
					});
					BX.removeClass(this.elemButtonSubscribe, 'sub-btn-vlad');
					BX.bind(this.elemButtonSubscribe, 'click', this._elemButtonSubscribeClickHandler);
				}, this)
			});
		}
	};
	
	window.JCCatalogProductSubscribe.prototype.showWindowWithAnswer = function(answer) {
		answer = answer || {};
		if(!answer.message) {
			if(answer.status == 'success') {
				answer.message = BX.message('CPST_STATUS_SUCCESS');
			} else {
				answer.message = BX.message('CPST_STATUS_ERROR');
			}
		}
		var messageBox = BX.create('DIV', {
			props: {
				className: 'bx-catalog-subscribe-alert'
			},
			children: [
				BX.create("span", {
					props: {
						className: 'alert' + (answer.status == 'success' ? ' alert-success' : ' alert-error') + ' alert-show'
					},
					html: answer.message
				})
			]
		});
		var currentPopup = BX.PopupWindowManager.getCurrentPopup();
		if(currentPopup) {
			currentPopup.destroy();
		}
		var idTimeout = setTimeout(function () {
			var w = BX.PopupWindowManager.getCurrentPopup();
			if(!w || w.uniquePopupId != 'bx-catalog-subscribe-status-action') {
				return;
			}
			w.close();
			w.destroy();
		}, 3500);
		var popupConfirm = BX.PopupWindowManager.create('bx-catalog-subscribe-status-action', null, {
			autoHide: false,
			offsetLeft: 0,
			offsetTop: 0,
			overlay: {
				opacity: 100
			},
			closeByEsc: true,
			titleBar: true,
			closeIcon: true,
			className: 'bx-catalog-subscribe-popup-window',
			contentColor: 'white',
			onPopupClose: function () {
				this.destroy();
				clearTimeout(idTimeout);
			}
		});
		var close = BX.findChild(BX("bx-catalog-subscribe-status-action"), {className: "popup-window-close-icon"}, true, false);
		if(!!close)
			close.innerHTML = "<i class='icon-close'></i>";
		popupConfirm.setTitleBar(BX.message('CPST_SUBSCRIBE_POPUP_TITLE'));
		popupConfirm.setContent(messageBox);
		popupConfirm.setButtons(false);
		popupConfirm.show();
		BX('bx-catalog-subscribe-status-action').onmouseover = function(e) {
			clearTimeout(idTimeout);
		};
		BX('bx-catalog-subscribe-status-action').onmouseout = function(e) {
			idTimeout = setTimeout(function () {
				var w = BX.PopupWindowManager.getCurrentPopup();
				if (!w || w.uniquePopupId != 'bx-catalog-subscribe-status-action') {
					return;
				}
				w.close();
				w.destroy();
			}, 3500);
		};
	};
})(window);
