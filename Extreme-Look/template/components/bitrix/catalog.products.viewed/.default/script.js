(function() {
	'use strict';

	if(!!window.JCCatalogProductsViewedComponent)
		return;

	window.JCCatalogProductsViewedComponent = function(params) {
		this.container = document.querySelector('[data-entity="' + params.container + '"]');

		if(params.initiallyShowHeader) {
			BX.ready(BX.delegate(this.showHeader, this));
		}
	};

	window.JCCatalogProductsViewedComponent.prototype =	{
		showHeader: function(animate) {
			var parentNode = BX.findParent(this.container, {attr: {'data-entity': 'parent-container'}});
			if(!!parentNode) {
				parentNode.removeAttribute('style');

				var header = parentNode.querySelector('[data-entity="header"]');
				if(!!header && header.getAttribute('data-showed') != 'true') {
					if(animate) {
						header.style.display = '';
						new BX.easing({
							duration: 2000,
							start: {opacity: 0},
							finish: {opacity: 100},
							transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
							step: function(state){
								header.style.opacity = state.opacity / 100;
							},
							complete: function(){
								header.removeAttribute('style');
								header.setAttribute('data-showed', 'true');
							}
						}).animate();
					} else {
						header.removeAttribute('style');
						header.setAttribute('data-showed', 'true');
					}
				}
			}
		}
	}
})();