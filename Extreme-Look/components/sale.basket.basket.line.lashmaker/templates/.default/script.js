'use strict';

function BitrixSmallCart() {}

BitrixSmallCart.prototype = {
	activate: function() {
		this.cartElement = BX(this.cartId);
		BX.addCustomEvent(window, 'OnBasketChange', this.closure('refreshCart', {'action': 'addToCart'}));
		BX.addCustomEvent(window, 'OnBasketDelayChange', this.closure('refreshCart', {'action': 'addToDelay'}));
	},
	
	closure: function(fname, data) {
		var obj = this;
		return data
			? function(){obj[fname](data)}
			: function(arg1){obj[fname](arg1)};
	},
	
	refreshCart: function(data) {
		data.sessid = BX.bitrix_sessid();
		data.siteId = this.siteId;
		data.templateName = this.templateName;
		data.arParams = this.arParams;
		BX.ajax({
			url: this.ajaxPath,
			method: 'POST',
			dataType: 'html',
			data: data,
			onsuccess: data.action == 'addToDelay' ? this.closure('setCartDelayBody') : this.closure('setCartBody')
		});
	},
		
	setCartBody: function(result) {		
		if(this.cartElement) {
			this.cartElement.innerHTML = result;
			var cart = this.cartElement.querySelector('[data-entity="cart"]');
			if(cart) {
				BX.addClass(cart, "shake shake-constant");
				setTimeout(function(){
					BX.removeClass(cart, "shake shake-constant");
				}, 1000);
			}
		}
	},

	setCartDelayBody: function(result) {
		if(this.cartElement) {
			this.cartElement.innerHTML = result;
			var cartDelay = this.cartElement.querySelector('[data-entity="delay"]');
			if(cartDelay) {
				BX.addClass(cartDelay, "shake shake-constant");
				setTimeout(function(){
					BX.removeClass(cartDelay, "shake shake-constant");
				}, 1000);
			}
		}
	}
};
