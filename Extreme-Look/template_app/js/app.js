'use strict';

import {Dom7 as $$} from 'framework7';
import Framework7 from 'framework7/dist/framework7.esm.bundle.js';
import routes from './routes.js';

window.$$ = $$;
window.app = app;

String.prototype.replaceAll = function(search, replace){
  return this.split(search).join(replace);
}

window.app = new Framework7({
	root: '#app',
	id: 'ru.extreme_look.extremelook',
	name: 'Extreme Look',
	theme: window.theme || 'auto',
	statusbar: {
		overlay: window.theme == "ios" ? true : 'auto'
	},
	dialog: {
		buttonCancel: 'Отмена',
		backdrop: true,
		closeByBackdropClick: true
	},
	methods: {
		goToStart: function(){
			// window.location.protocol + '//' + window.location.hostname +
			window.location.href = '/?' + MOBILE_GET + '=Y';
			//app.router.navigate('/page-home/', { reloadCurrent: true, ignoreCache: false });
		},
		logOut: function(){
			app.dialog.confirm('Подтвердите выход', function(){
				app.preloader.show();
				let data = {action: 'logout'};
				data[MOBILE_GET] = 'Y';
				app.request.json(SITE_TEMPLATE_PATH + 'ajax/account.php', data, function (data) {
					app.toast.show({
						text: '<div style="text-align: center !important;">Вы успешно вышли из профиля</div>',
						position: 'top',
						closeTimeout: 2000,
						destroyOnClose: true
					});
					app.methods.goToStart();
					app.preloader.hide();
				});
			});
		},
		logIn: function(username, password){
			//app.preloader.show();
			let data = { action: 'login', username: username, password: password };
			data[MOBILE_GET] = 'Y';
			app.request.json(SITE_TEMPLATE_PATH + 'ajax/account.php', data, function (data) {

				window.login = data;
				if( data.result ){
					app.toast.show({
						text: '<div style="text-align: center !important;">Успешная авторизация</div>',
						position: 'top',
						closeTimeout: 2000,
						destroyOnClose: true
					});
					//app.preloader.hide();

					//app.loginScreen.close('#login-screen');
					setTimeout(function(){
						app.methods.goToStart();
					}, 150);

				}
				else{
					app.toast.show({
						text: '<div style="text-align: center !important;">Неверный логин или пароль!</div>',
						position: 'center',
						closeTimeout: 2000,
						destroyOnClose: true
					});
					//app.preloader.hide();
				}
			}
		},
		stopVideos: function(){
			$$('.ytb-video').each(function(){
				this.contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
			});
		},
		goToExternal: function(url){
			app.panel.left.close();
			app.preloader.show();
		},
		catalogView: function(type, param){
			var data = {
				param: param,
				type: type
			};

			data[MOBILE_GET] = 'Y';
			
			app.preloader.show();
			
			app.request.json(SITE_TEMPLATE_PATH + 'ajax/catalog.php', data, function (data) {
				app.methods.refreshPage();
			});
		},
		cartUpdate: function(data, callback){
			if( data == undefined || data == null )
				data = {};

			data[MOBILE_GET] = 'Y';

			app.request.json(SITE_TEMPLATE_PATH + 'ajax/basket.php', data, function (data) {
				window.cart = data.basket;

				var cartBadge = $$('.main-cart-link .badge');
				var cartPage = $$('.page[data-name="personal/cart"]');
				var orderLink = cartPage.find('.order-link');

				cartBadge.removeClass('shown').html(cart.length);

				if( cartBadge.length > 0 && cart.length > 0)
					cartBadge.addClass('shown');

				if( cartPage.length >= 1 ){
					if( cart.length > 0 ){

						for(var ID in cart.items){
							cartPage.find('.cart-item[data-id="' + cart.items[ID].ID + '"] .item-subtitle .price')
								.html(cart.items[ID].PRICE_LABEL);
						}

						if( USER.authorized ){
						if( cart.partner && !cart.passMin )
								orderLink
									.addClass('disabled')
									.html(cart.price_partner_text);
							else
								orderLink
									.removeClass('disabled')
									.html(cart.price_order_text);
						}
						else{
							orderLink
							.attr('href', '#')
							.removeClass('disabled external')
							.css('width', '100%')
							.html('Авторизоваться').click(function(){
								$$('.login-screen-open').click();
								app.preloader.hide();
							})
							.prev().remove();
						}
					}
					else{
						cartPage.find('.empty-cart')
							.css({ display: 'block', opacity: 0 });
						$$('.order-toolbar, .cart-list, .coupon-fab').css('display', 'none');

						app.methods.pageVerticalize();
					}
				}

				if( callback != undefined )
					callback(data.result, data);
			}, function(){
				if( callback != undefined )
					callback(false);
			});
		},
		inCart: function(productId){
			return cart.items[productId] != undefined;
		},
		initPtr: function(goTo, currentPage){
			currentPage = currentPage || $$('.page-current');

			var ptr = currentPage.find('.ptr-content');

			if( ptr.length !== 0 ){
				if( app.ptr.get(ptr) === undefined )
					app.ptr.create();

				ptr.on('ptr:refresh',  function() {
					app.ptr.done(ptr);

					if( goTo == undefined ){
						app.preloader.show();
						window.location.reload();

						return;
					}

					app.router.navigate(goTo, { reloadCurrent: true, ignoreCache: false });
				});
			}
		},
		refreshPage: function(){
			app.router.navigate($$('.page-current').data('path'), { reloadCurrent: true, ignoreCache: false });
		},
		viewport: function(){
			var e = window;
			var a = 'inner';
			if (!('innerWidth' in window)){
				a = 'client';
				e = document.documentElement || document.body;
			}
			return { width : e[ a+'Width' ] , height : e[ a+'Height' ] }
		},
		cityButton: function(){
			console.log(MOBILE_GET);
			var url = '/page-citybuy/';
			if( window.geo.city == 0 ){
				app.methods.citySelectManual('button', true, function(){
					app.router.navigate(url, { reloadCurrent: false, ignoreCache: false });
				});
				return;
			}

			app.router.navigate(url, { reloadCurrent: false, ignoreCache: false });
		},
		citySelectManual: function(whyManual, force, callback){
			return; // Временное (нет) отключение
			if(window.geo.city != 0 && force != true)
				return;

			var sSelect = app.smartSelect.get(), cityName;

			if( sSelect === undefined ){
				sSelect = app.smartSelect.create({
					el: '.smart-select-city',
					//pageBackLinkText: 'Назад',
					view: mainView,
					virtualList: true,
					pageTitle: 'Ручной выбор города',
					openIn: 'page',
					searchbar: true,
					searchbarPlaceholder: 'Поиск',
					on: {
						opened: function(){
							$$('.smart-select-page .link.back').html('<i class="fa fa-chevron-left"></i>');
						},
						close: function(){
							app.methods.saveCity(sSelect.valueEl.innerText, callback);
						}
					}
				});
			}

			sSelect.open();
			/*
			dialog = app.dialog.create({
				    title: 'Ручной выбор города',
					verticalButtons: true,
					content: content,
					buttons: [
						{
							text: 'Применить',
							cssClass: 'text-align-center',
							onClick: function(){
								if( callback )
									callbac(cityName);
							}
						}
					]
			});

			dialog.open();
			*/
		},
		citySelectAuto: function(locateEnabled){
			return; // like a manual 
			if(window.geo.city != 0)
				return;

			navigator.geolocation.getCurrentPosition(function(pos){
				window.geo.coords = pos.coords;
				window.geo.attempts = 0;

				var data = { lat: geo.coords.latitude, lon: geo.coords.longitude };
				data[MOBILE_GET] = 'Y';

				app.request.json(SITE_TEMPLATE_PATH + 'ajax/coordinates.php', data, function(response){
					if( response.result ){
						app.dialog.create({
							text: 'Мы угадали?',
							title: 'Ваш город - ' + response.city,
							buttons: [
								{
									text: 'изменить',
									onClick: function(){
										app.methods.citySelectManual('wrong', true);
									}
								},
								{
									text: 'да',
									cssClass: 'dialog-button-bold',
									onClick: function(){
										app.methods.saveCity(response.city);
									}
								}
							]
						}).open();
					}
					else{
						app.methods.citySelectManual('unknown');
					}
				});
			},
			function(err){
				if( err.message.toLowerCase().search('timeout') >= 0 ){
					window.geo.attempts++;

					if( window.geo.attempts >= 2 )
						app.methods.citySelectManual('timeout');
					else
						app.methods.citySelectAuto();

					return;
				}

				app.methods.citySelectManual('disabled');
			},
			{
				//enableHighAccuracy: false,
				timeout: 20000,
				maximumAge: 0
			});
		},
		saveCity: function(cityName, callback){
			var data = {};

			data[MOBILE_GET] = 'Y';
			data['city'] = cityName;

			window.geo.city = cityName;
			$$('.current-city').text(cityName);

			app.request.json(SITE_TEMPLATE_PATH + 'ajax/saveCity.php', data, function (data) {
				if( callback )
					callback();
			});
		},
		pageVerticalize(page){
			page = page || $$('.page.page-current');

			page.find('.verticalize').each(function(){
				var margin = (app.methods.viewport().height - $$(this).height()) / 2 - 60;
				var _this = $$(this);

				_this.css('margin-top', margin + 'px');

				setTimeout(function() {
					_this.animate({ opacity: 1 }, { duration: 400 });
				}, 300);
			});
		},
		onPageResize: function(){
			var currentPage = $$('.page.page-current');

			app.methods.pageVerticalize(currentPage);

			switch( currentPage.data('name') ){
				case 'home':
					var slider = $$('iframe');

					if( slider.length <= 0 )
						return;

					var height = slider.width() / 1.78;
					slider.animate({ height: height });
					break;
				case 'auth':

					break;
			}
		},
		pageActions: function(pageName, currentPage){

			currentPage = currentPage || $$('.page.page-current');

			app.methods.pageVerticalize(currentPage);

			switch(pageName){
				case 'home':
					if(navigator.permissions != undefined){
						navigator.permissions.query({name:'geolocation'}).then(function(result) {
							if (result.state != 'denied')
								app.methods.citySelectAuto();
							else
								app.methods.citySelectManual('disabled');
						});
					}
						
					var slider = $$('iframe');

					if( slider.length <= 0 )
						return;

					var height = slider.width() / 1.78;
					slider.animate({ height: height });
					break;
				case 'ca2talog/element':
					var toCartLink = $$('.add-to-cart');

					/*
					if( !toCartLink.hasClass('not-available') ){
						toCartLink.addClass('disabled');
						toCartLink.text('Проверка...');

						if( app.methods.inCart(toCartLink.data('id')) ){
							toCartLink.text('В корзине');
						}
						else{
							toCartLink.removeClass('disabled');
							toCartLink.text('Добавить в корзину');
						}
					}
					*/
					break;
				case 'personal/cart':
					/////////////////////////
					break;
				case 'catalog/element':
				case 'catalog/section':
					$$('.basket-item[data-cart]').removeAttr('data-cart');
					$$('.product-buy-block input.qty').val(0);
					$$('.product-buy-block .minus').addClass('disabled');
					
					$$('.product-buy-block').each(function(){
						var block = $$(this);
						var itemId = block.data('id');
						var newVal = 0;
						
						
						if( cart.items[itemId] != undefined ){
							newVal = cart.items[itemId].QUANTITY;
							block.closest('.basket-item').attr('data-cart', (cart.items[itemId].ID));
						}
						
						block.find('input.qty').val(newVal);
						
						if( newVal > 0 )
							block.find('.minus').removeClass('disabled');
						
						block.removeClass('checking');
					});
					break;
			}
		},
		iosSend(method, message){
			try {
				webkit.messageHandlers[method].postMessage(message);
			} catch(err) {
				app.methods.appError(err);
			}
		},
		appError: function(text){
			app.preloader.hide();

			app.dialog.alert(text);

			// TODO: log error into Files
		}
	},
	routes: routes,
	panel: {
		swipe: 'left',
		swipeActiveArea: 100,
		swipeOnlyClose: window.theme == 'ios' ? true : false
	},
});

var mainView = app.views.create('.view-main', {
  url: '/'
});

app.methods.initPtr();
app.methods.onPageResize(); // Первый ресайз вызываем мануально
app.methods.pageActions(window.LOADED_PAGE);

app.methods.cartUpdate();

$$(document).on('change', 'select[name="cities"]', function () {
	app.toast.show({
		text: '<div style="text-align: center !important;">Выбран город: ' + $$(this).val() + '</div>',
		position: 'top',
		closeTimeout: 2000,
		destroyOnClose: true
	});
});

$$(document).on('click', '.change-city', function(){
	app.methods.citySelectManual('change', true);
	//app.dialog.alert('Функция деактивирована');
})
.on('page:init page:reinit', function (e, page) {
	if( e.type == 'page:reinit' )
		window.reinitPage = true;

	//page.$el.data('path', page.route.url);
})
.on('click', '.clear-cart', function(){
	app.dialog.confirm('Вы уверены, что хотите очистить содержимое корзины?', function(){
		app.preloader.show();
		app.methods.cartUpdate({action: 'clear'},function(result){
			app.preloader.hide();
		});
	});
})
.on('click', '.logout-link', function(){
	app.methods.logOut();
})
.on('click', '.error-report', function(){
	app.methods.errorReport();
})
.on('click', '.set-coupon', function(){
	app.dialog.prompt('Введите штрих код скидочной карты', 'Получить скидку', function(value){
		app.preloader.show();

		app.methods.cartUpdate({action: 'coupon', value: value},function(result){
			app.preloader.hide();
		});
	});
})
.on('click', '.add-to-cart', function(){

	var clicked = $$(this);
	clicked.addClass('disabled');

	app.methods.cartUpdate({ id: clicked.data('id'), action: 'add' }, function(result){

		var message = 'Ошибка добавления, товар закончился';

		if( result ){
			message = 'Товар добавлен в корзину';
			clicked.text('В корзине');
		}

		app.toast.show({
			text: '<div style="text-align: center !important;">' + message + '</div>',
			position: 'center',
			closeTimeout: 2000,
			destroyOnClose: true
		});
	});
})
.on('click', '.share-link', function(){
	var text = $$(this).data('share');
	
	if( window.platform == 'ios' ){
		app.methods.iosSend('shareLink', { link: text });
		return;
	}
	else if( window.Android != undefined ){
		window.Android.shareText(text);
		return;
	}
	
	app.dialog.alert(text);
})
.on('click', '.cart-item-delete', function(){
	var item = $$(this);
	var swipeElement = item.closest('.cart-item');

	app.swipeout.close(swipeElement);

	app.dialog.confirm('Подтвердите удаление', function(){
		swipeElement.addClass('disabled');

		app.methods.cartUpdate({
			id: swipeElement.data('id'),
			action: 'setQty',
			qty: 0
		}, function(res){
			if(res){
				app.methods.cartUpdate();
				app.swipeout.delete(swipeElement);
			}
			else
				app.dialog.alert('Ошибка удаления');
		});
	});
})
.on('tab:show', '.products-tabs .tab', function() {
	var swiper = $$(this).find('.swiper-container');

	if( !swiper.hasClass('init') ){
		swiper.addClass('init');
		app.swiper.create(swiper, { spaceBetween: 50, pagination: JSON.parse(swiper.data('pagination')) });
	}
});

$$(document)
.on('change', '.basketQty .qty', function(){
	var _this = $$(this);
	var cartItem = _this.closest('.basket-item');
	var minVal	= _this.closest('.cart.basketQty').length > 0 ? 1 : 0;
	var id = cartItem.data('id');
	var minus = cartItem.find('.minus');
	var action = 'setQty';
	
	if( minVal == 0 ){
		var dataCart = cartItem.attr('data-cart')
		if( cartItem.data('cart') == null )
			action = 'add';
		else
			id = cartItem.attr('data-cart');
	}
	
	cartItem.addClass('disabled');
	
	if( parseInt(_this.val()) <= minVal )
		_this.val(minVal);
	
	app.methods.cartUpdate({
		id: id,
		action: action,
		qty: _this.val()
	}, function(result, data){
		if( $$('.page-current').data('name').indexOf('catalog/') >= 0 ){
			$$('.page-current .basket-item').removeAttr('data-cart');
			
			for( var id in cart.items ){
				$$('.basket-item[data-id="' + id + '"]').attr('data-cart', cart.items[id].ID);
			}
		}
		
		cartItem.removeClass('disabled');

		_this.val(_this.val());
		
		minus.removeClass('disabled');

		if( parseInt(_this.val()) <= minVal ){
			minus.addClass('disabled');
		}
	});
})
.on('click', '.basketQty .link', function(){
	var wrap = $$(this).closest('.basketQty');
	var qtyInput = wrap.find('.qty');
	var qty = parseInt(qtyInput.val());

	if( $$(this).hasClass('minus') )
		qty -= 1;
	else
		qty += 1;
	
	qtyInput.val(qty).change();
});

$$(document)
.on('click', '.catalog-sort', function(){



	app.dialog.create({
		title: 'Порядок сортировки',
		cssClass: 'text-align-center',
		text: '',
		buttons: [
			{
				text: 'По цене вверх',
				cssClass: 'text-align-center',
				onClick: function(){ app.methods.catalogView('sort', 'price-up'); }
			},
			{
				text: 'По цене вниз',
				cssClass: 'text-align-center',
				onClick: function(){ app.methods.catalogView('sort', 'price-down'); }
			},
			{
				text: 'По популярности вверх',
				cssClass: 'text-align-center',
				onClick: function(){ app.methods.catalogView('sort', 'views-up'); }
			},
			{
				text: 'По популярности вниз',
				cssClass: 'text-align-center',
				onClick: function(){ app.methods.catalogView('sort', 'views-down'); }
			}
		],
		verticalButtons: true
	}).open();



})
.on('click', '.catalog-view', function(){



	app.dialog.create({
		title: 'Отображать товары',
		cssClass: 'text-align-center',
		text: '',
		buttons: [
			{
				text: 'Списком',
				cssClass: 'text-align-center',
				onClick: function(){ app.methods.catalogView('view', 'list'); }
			},
			{
				text: 'Плиткой',
				cssClass: 'text-align-center',
				onClick: function(){ app.methods.catalogView('view', 'squares'); }
			}
		],
		verticalButtons: true
	}).open();


});

$$(document).on('infinite', '.infinite-scroll-content', function () {
	var curPage = $$('.page.page-current');
	var itemsWrap = $$('.infinite-items');
	var length = itemsWrap.find('.infinite-item').length;
	
	if( length < PRODUCTS_PER_PAGE ){
		app.infiniteScroll.destroy('.infinite-scroll-content');
		$$('.infinite-scroll-preloader').remove();
		return;
	}
	
	// Exit, if loading in progress
	if (curPage.data('infinite') == '1') return;
	
	curPage.data('infinite', '1');
	
	app.request.get($$('.page-current').data('ajax'), { PAGEN_2: (Math.ceil(length / PRODUCTS_PER_PAGE) + 1), loaded: length }, function (data) {
		data = data.trim()
		
		itemsWrap.append(data);
		app.methods.pageActions(curPage.data('name'), curPage);
		curPage.data('infinite', '0');
		
		if( data.length < 10 || data.indexOf('last-infinite') >= 0 ){
			app.infiniteScroll.destroy('.infinite-scroll-content');
			$$('.infinite-scroll-preloader').remove();
			return;
		}
	});
});

$$('#authForm').on('submit', function (ev) {
	ev.preventDefault();
	var form = $$(this);

	app.methods.logIn(form.find('[name="username"]').val(), form.find('[name="password"]').val());
});
$$(document).on('submit', 'form#errorReport', function (ev) {
	ev.preventDefault();
	
	var _this = $$(this);
	console.log(_this);
	name 	= _this.find('input[name=name]');
	email 	= _this.find('input[name=email]');
	message = _this.find('textarea');
	
	data = app.form.convertToData(_this);
	
	for(var key in data){
		if( data[key].length <= 0 ){
			var name = _this.find('[name=' + key + ']').closest('.item-inner').children('.item-title').html().trim();
			
			app.toast.show({
				text: 'Заполние поле "' + name + '"',
				position: 'bottom',
				closeTimeout: 1500,
				destroyOnClose: true
			});
			
			return
		}
	}
	
	app.preloader.show();
	
	data[MOBILE_GET] = 'Y';

	app.request.json(SITE_TEMPLATE_PATH + 'ajax/error_report.php', data, function (data) {
		app.toast.show({
			text: 'Спасибо за вашу помощь',
			position: 'bottom',
			closeTimeout: 2000,
			destroyOnClose: true
		});
		app.preloader.hide();
		
		_this.find('input, textarea').val('');
	}, function(){
		app.preloader.hide();
	});
	
});


$$(window).on('resize', function(){
	if( window.resizeTimeout !== undefined )
		clearTimeout(window.resizeTimeout);

	window.resizeTimeout = setTimeout(function () {
		app.methods.onPageResize();
	}, 111);
});

$$(document).on('submit', '#personalForm', function(ev){
	ev.preventDefault();
	app.preloader.show();

	var form = $$(this);
	var data = app.form.convertToData(form);

	data[MOBILE_GET] = 'Y';
	data['action'] = 'edit';

	app.request.json(SITE_TEMPLATE_PATH + 'ajax/account.php', data, function (data) {
		data = data.result;

		var time = 4000;

		app.preloader.hide();

		if( data.reload ){
			time = 3000;
			app.router.navigate($$('.page.page-current').data('path'), { reloadCurrent: true, ignoreCache: false });
		}

		app.toast.show({
			text: '<div style="text-align: center !important;">' + data.message + '</div>',
			position: 'bottom',
			closeTimeout: time,
			destroyOnClose: true
		});

	}, function(){

	});
});

$$(document)
.on('page:init page:reinit', function (e, page) {
	if( e.type == 'page:reinit' ){
		//page.$el.data
		//console.log(page);
		app.methods.pageActions(page.$el.data('name'), page.$el);
	}
	
	//page.$el.data('path', page.route.url);
	
})
.on('page:mounted', function (e, page) {
		//console.log('page:mounted - ' + page.route.path);

	var currentPage = page.$el;

	app.methods.initPtr(currentPage.data('path'), currentPage);

	app.methods.pageActions(currentPage.data('name'), currentPage);

	currentPage.find('.link.external').once('click', function (e) {
		app.methods.goToExternal($$(this).attr('href'));
	});
});

$$('.link.external').once('click', function (e) {
	app.methods.goToExternal($$(this).attr('href'));
});


var closeClicks = 0;

var closeToast = app.toast.create({
	text: '<div style="text-align: center;">Нажмите ещё раз, чтобы закрыть приложение</div>',
	position: 'bottom',
	closeTimeout: 2000,
	on: {
		close: function(){
			closeClicks = 0;
		}
	}
});

window.backPressed = () => {
	var backLink = $$('.page-current .back-link');

	var dialog = app.dialog.get();
	var popover = app.popover.get();
	var smartSelect = app.smartSelect.get();

	if( $$('.preloader-modal').length >= 1 )
		return 'true';
	else if( $$('.panel-left').hasClass('panel-active') ) {
		app.panel.get('left').close();
		return 'true';
	}
	else if($$('#login-screen').hasClass('modal-in')){
		app.loginScreen.close('#login-screen');
		return 'true';
	}
	else if( dialog !== undefined ){
		dialog.close();
		return 'true';
	}
	else if( smartSelect !== undefined && smartSelect.opened == true ){
		smartSelect.close();
		return 'true';
	}
	else if( popover !== undefined ){
		popover.close();
		return 'true';
	}
	else if( $$('.page-current').data('name') == 'home' ){
		closeToast.open();

		if( closeClicks == 1 )
			return 'close';

		closeClicks++;

		return 'false';
	}

	if( backLink.length >= 1 )
		backLink[0].click();

	return 'true';
}