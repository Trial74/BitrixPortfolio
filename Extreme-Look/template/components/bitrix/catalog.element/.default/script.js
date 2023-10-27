(function(window){
	'use strict';

	if(window.JCCatalogElement)
		return;
	
	window.JCCatalogElement = function(arParams) {
		this.productType = 0;

		this.offersView = '';
		
		this.config = {
			useCatalog: true,			
			showQuantity: true,
			showPrice: true,
			showAbsent: true,
			showOldPrice: false,
			showPercent: false,
			showSkuProps: false,
			showOfferGroup: false,
			useCompare: false,
			useSubscribe: false,	
			usePopup: false,
			useMagnifier: false,
			usePriceRanges: false,
			basketAction: ['BUY'],
			minOrderSum: 0,
			showSlider: false,
			sliderInterval: 5000,
			useEnhancedEcommerce: false,
			dataLayerName: 'dataLayer',
			brandProperty: false,
			alt: '',
			title: '',
			magnifierZoomPercent: 100
		};

		this.checkQuantity = false;
		this.skuItemCheckQuantity = false;
		this.maxQuantity = 0;
		this.skuItemMaxQuantity = 0;
		this.maxPcQuantity = 0;
		this.maxSqMQuantity = 0;
		this.minQuantity = 0;
		this.skuItemMinQuantity = 0;
		this.minPcQuantity = 0;
		this.minSqMQuantity = 0;
		this.stepQuantity = 1;
		this.skuItemStepQuantity = 1;
		this.stepPcQuantity = 1;
		this.stepSqMQuantity = 0.01;
		this.isDblQuantity = false;
		this.skuItemIsDblQuantity = false;
		this.canBuy = true;
		this.skuItemCanBuy = true;
		this.canSubscription = true;
		this.currentIsSet = false;
		this.updateViewedCount = false;
		
		this.currentPriceMode = '';
		this.skuItemCurrentPriceMode = '';
		this.currentPrices = [];
		this.skuItemCurrentPrices = [];
		this.currentPriceSelected = 0;
		this.skuItemCurrentPriceSelected = 0;
		this.currentQuantityRanges = [];
		this.currentQuantityRangeSelected = 0;
		this.currentMeasure = [];

		this.precision = 6;
		this.precisionFactor = Math.pow(10, this.precision);

		this.visual = {};
		this.basketMode = '';
		this.product = {
			checkQuantity: false,
			maxQuantity: 0,
			maxPcQuantity: 0,
			maxSqMQuantity: 0,
			stepQuantity: 1,
			stepPcQuantity: 1,
			stepSqMQuantity: 0.01,			
			isDblQuantity: false,
			canBuy: true,
			canSubscription: true,
			name: '',
			pict: {},
			id: 0,
			iblockId: 0,
			addUrl: '',
			buyUrl: '',
			slider: {},
			sliderCount: 0,			
			useSlider: false,
			sliderPict: []
		};
		this.mess = {};

		this.basketData = {			
			quantity: 'quantity',
			props: 'prop',
			basketUrl: '',
			sku_props: '',
			sku_props_var: 'basket_props',
			add_url: '',
			buy_url: ''
		};
		
		this.compareData = {
			compareName: '',
			comparePath: '',
			compareUrl: '',
			compareDeleteUrl: ''
		};

		this.object = {
			id: 0,
			name: '',
			address: '',
			timezone: '',
			workingHours: {},
			phone: {},				
			email: {},
			skype: {},
			callbackForm: false
		};

		this.skuItemObject = {
			id: 0,
			name: '',
			address: '',
			timezone: '',
			workingHours: {},
			phone: {},				
			email: {},
			skype: {},
			callbackForm: false
		};

		this.moreProducts = {
			ids: ''
		};

		this.defaultPict = {
			preview: null,
			detail: null
		};

		this.offers = [];
		this.offerNum = 0;
		this.treeProps = [];
		this.selectedValues = {};

		this.skuItemContainer = null;
		this.skuItemNum = 0;
		this.skuItem = [];

		this.mouseTimer = null;
		this.isTouchDevice = BX.hasClass(document.documentElement, 'bx-touch');
		this.touch = null;
		this.slider = {
			interval: null,
			progress: null,
			paused: null,
			controls: []
		};

		this.obProduct = null;
		this.obQuantity = null;
		this.obSkuItemQuantity = null;
		this.obQuantityUp = null;
		this.obSkuItemQuantityUp = null;
		this.obQuantityDown = null;
		this.obSkuItemQuantityDown = null;
		this.obPcQuantity = null;
		this.obSkuItemPcQuantity = null;
		this.obPcQuantityUp = null;
		this.obSkuItemPcQuantityUp = null;
		this.obPcQuantityDown = null;
		this.obSkuItemPcQuantityDown = null;
		this.obSqMQuantity = null;
		this.obSkuItemSqMQuantity = null;
		this.obSqMQuantityUp = null;
		this.obSkuItemSqMQuantityUp = null;
		this.obSqMQuantityDown = null;
		this.obSkuItemSqMQuantityDown = null;
		this.obPrice = null;
		this.obPriceNotSet = null;
		this.obPriceCurrent = null;
		this.obSkuItemPriceCurrent = null;
		this.obPriceOld = null;
		this.obSkuItemPriceOld = null;
		this.obPriceDiscount = null;
		this.obSkuItemPriceDiscount = null;
		this.obPricePercent = null;
		this.obPricePercentVal = null;
		this.obTotalCost = null;
		this.obTotalCostVal = null;
		
		this.obTree = null;
		this.obPriceRanges = null;		
		this.obBuyBtn = null;
		this.obSkuItemBuyBtn = null;
		this.obAddToBasketBtn = null;
		this.obAddToMarketplaceBtnOzon = null;
		this.obSkuItemAddToBasketBtn = null;
		this.obBasketActions = null;
		this.obPartnersBtn = null;
		this.obPartners = null;
		this.obPartnersMess = null;
		this.obAskPrice = null;
		this.obNotAvail = null;
		this.obSubscribe = null;
		this.obQuickOrder = null;
		this.obSelectSku = null;
		this.obObjectBtn = null;
		this.obArticle = {};
		this.obSkuProps = null;
		this.obSkuPropsSecond = null;
		this.obMainSkuProps = null;
		this.obMainSkuPropsSecond = null;
		this.obBigSlider = null;
		this.obMeasure = null;
		this.obQuantityLimit = {
			all: null,
			value: null
		};
		this.obQuantityLimitNotAvl = {};		
		this.obDelay = null;
		this.obSkuItemDelay = null;
		this.obCompare = null;
		this.obSkuItemCompare = null;
		this.obSkuItems = null;
		this.obConstructor = null;
		this.obGeoDelivery = null;

		this.node = {};
		this.shortCardNodes = {};
		
		this.magnify = {
			enabled: false,
			obBigImg: null,
			obBigSlider: null,
			height: 0,
			width: 0,
			timer: 0
		};
		this.currentImg = {
			id: 0,
			src: '',
			width: 0,
			height: 0
		};
		this.viewedCounter = {
			path: '/bitrix/components/bitrix/catalog.element/ajax.php',
			params: {
				AJAX: 'Y',
				SITE_ID: '',
				PRODUCT_ID: 0,
				PARENT_ID: 0
			}
		};

		this.obPopupWin = null;		
		this.basketUrl = '';
		this.basketParams = {};

		this.errorCode = 0;

		if(typeof arParams === 'object') {
			this.params = arParams;
			this.initConfig();

			if(this.params.MESS) {
				this.mess = this.params.MESS;
			}

			switch(this.productType) {
				case 0: //no catalog
				case 1: //product
				case 2: //set
					this.initProductData();
					break;
				case 3: //sku
					this.initOffersData();
					break;
				default:
					this.errorCode = -1;
			}

			this.initBasketData();
			this.initCompareData();

			this.initObjectData();

			this.initMoreProductsData();
		}

		if(this.errorCode === 0) {
			BX.ready(BX.delegate(this.init, this));
		}

		this.width;
		this.height;
		this.params = {};
		this.mobile = arParams.MOBILE;
		this.statusVideo = -1;
		this.player;
		this.pauseProgress = false;
		this.defaultSize = 640;
		this.coeffPopup = 200;
		this.coeffPlayer = 200;
		this.coeffProgress = 40;
		this.idVideo;
		this.nameLive = '';
		this.countWatch = 0;
		this.coverFrameBlock = 'coverLiveElem';
		this.countError = 2;
		this.beforeShutdown = 0;
		this.popupLive;
		this.btnOzon;
		this.btnWB;
	};

	window.JCCatalogElement.prototype = {
		getEntity: function(parent, entity, additionalFilter) {
			if(!parent || !entity)
				return null;

			additionalFilter = additionalFilter || '';

			return parent.querySelector(additionalFilter + '[data-entity="' + entity + '"]');
		},

		getEntities: function(parent, entity, additionalFilter) {
			if(!parent || !entity)
				return {length: 0};

			additionalFilter = additionalFilter || '';

			return parent.querySelectorAll(additionalFilter + '[data-entity="' + entity + '"]');
		},
			
		setOffer: function(offerNum) {
			this.offerNum = parseInt(offerNum);
			this.setCurrent();
		},

		init: function() {
			var i = 0,
				j = 0,
				treeItems = null;

			this.obProduct = BX(this.visual.ID);
			if(!this.obProduct) {
				this.errorCode = -1;
			}

			this.obBigSlider = BX(this.visual.BIG_SLIDER_ID);
			this.node.videoImageContainer = this.getEntity(this.obProduct, 'videos-images-container');			
			this.node.sliderProgressBar = this.getEntity(this.obProduct, 'slider-progress-bar');
			this.node.sliderControlLeft = this.getEntity(this.obBigSlider, 'slider-control-left');
			this.node.sliderControlRight = this.getEntity(this.obBigSlider, 'slider-control-right');
			this.node.sliderMagnifier = this.getEntity(this.obBigSlider, 'slider-magnifier');

			if(!this.obBigSlider || !this.node.videoImageContainer) {
				this.errorCode = -2;
			}

			this.btnOzon = BX(this.visual.BUTTON_OZON);
			this.btnWB = BX(this.visual.BUTTON_WB);
			this.obSkuItems = BX(this.visual.SKU_ITEMS_ID);

			if(this.config.showPrice) {
				if(!this.obSkuItems) {
					this.obPrice = BX(this.visual.PRICE_ID);
					this.obPriceNotSet = this.getEntity(this.obPrice, 'price-current-not-set');
					this.obPriceCurrent = this.getEntity(this.obPrice, 'price-current');
					this.obPriceMeasure = this.getEntity(this.obPrice, 'price-measure');
					if(!this.obPrice && this.config.useCatalog) {
						this.errorCode = -16;
					} else {
						if(this.config.showOldPrice) {
							this.obPriceOld = BX(this.visual.OLD_PRICE_ID);
							this.obPriceDiscount = BX(this.visual.DISCOUNT_PRICE_ID);

							if(!this.obPriceOld || !this.obPriceDiscount) {
								this.config.showOldPrice = false;
							}
						}

						if(this.config.showPercent) {
							this.obPricePercent = BX(this.visual.DISCOUNT_PERCENT_ID);
							this.obPricePercentVal = this.getEntity(this.obPricePercent, 'dsc-perc-val');						
							if(!this.obPricePercent) {
								this.config.showPercent = false;
							}
						}
					}

					this.obBasketActions = BX(this.visual.BASKET_ACTIONS_ID);
					if(this.obBasketActions) {
						if(BX.util.in_array('BUY', this.config.basketAction)) {
							this.obBuyBtn = BX(this.visual.BUY_LINK);
						}
						if(BX.util.in_array('ADD', this.config.basketAction)) {
							this.obAddToBasketBtn = BX(this.visual.ADD_BASKET_LINK);
						}
					}

					this.obPartners = BX(this.visual.PARTNERS_ID);
					this.obPartnersMess = this.getEntity(this.obPartners, 'partners-message');
					this.obPartnersBtn = BX(this.visual.PARTNERS_LINK);

					this.obAskPrice = BX(this.visual.ASK_PRICE_LINK);
					this.obNotAvail = BX(this.visual.NOT_AVAILABLE_MESS);
					
					this.obQuickOrder = BX(this.visual.QUICK_ORDER_LINK);
				} else {
					if(BX.util.in_array('BUY', this.config.basketAction)) {
						this.obBuyBtn = this.getEntities(this.obSkuItems, 'buy');
					}
					if(BX.util.in_array('ADD', this.config.basketAction)) {
						this.obAddToBasketBtn = this.getEntities(this.obSkuItems, 'add');
					}

					this.obPartnersBtn = this.getEntities(this.obSkuItems, 'partner-link');

					this.obAskPrice = this.getEntities(this.obSkuItems, 'ask-price');
					this.obNotAvail = this.getEntities(this.obSkuItems, 'not-available');

					this.obSelectSku = BX(this.visual.SELECT_SKU_LINK);
				}
			}
			
			if(this.config.showQuantity) {				
				if(!this.obSkuItems) {
					this.node.quantity = this.getEntity(this.obProduct, 'quantity-block');

					this.obQuantity = BX(this.visual.QUANTITY_ID);
					if(this.visual.QUANTITY_UP_ID) {
						this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);
					}
					if(this.visual.QUANTITY_DOWN_ID) {
						this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
					}

					this.obPcQuantity = BX(this.visual.PC_QUANTITY_ID);				
					if(this.visual.PC_QUANTITY_UP_ID) {
						this.obPcQuantityUp = BX(this.visual.PC_QUANTITY_UP_ID);
					}
					if(this.visual.PC_QUANTITY_DOWN_ID) {
						this.obPcQuantityDown = BX(this.visual.PC_QUANTITY_DOWN_ID);
					}

					this.obSqMQuantity = BX(this.visual.SQ_M_QUANTITY_ID);				
					if(this.visual.SQ_M_QUANTITY_UP_ID) {
						this.obSqMQuantityUp = BX(this.visual.SQ_M_QUANTITY_UP_ID);
					}
					if(this.visual.SQ_M_QUANTITY_DOWN_ID) {
						this.obSqMQuantityDown = BX(this.visual.SQ_M_QUANTITY_DOWN_ID);
					}

					this.obTotalCost = BX(this.visual.TOTAL_COST_ID);
					this.obTotalCostVal = !!this.obTotalCost && this.getEntity(this.obTotalCost, 'total-cost');
				} else {
					this.obQuantity = this.getEntities(this.obSkuItems, 'quantity');
					this.obQuantityUp = this.getEntities(this.obSkuItems, 'quantity-up');
					this.obQuantityDown = this.getEntities(this.obSkuItems, 'quantity-down');

					this.obPcQuantity = this.getEntities(this.obSkuItems, 'pc-quantity');
					this.obPcQuantityUp = this.getEntities(this.obSkuItems, 'pc-quantity-up');
					this.obPcQuantityDown = this.getEntities(this.obSkuItems, 'pc-quantity-down');

					this.obSqMQuantity = this.getEntities(this.obSkuItems, 'sq-m-quantity');
					this.obSqMQuantityUp = this.getEntities(this.obSkuItems, 'sq-m-quantity-up');
					this.obSqMQuantityDown = this.getEntities(this.obSkuItems, 'sq-m-quantity-down');
				}
			}

			if(this.productType === 3 && !this.obSkuItems) {
				if(this.visual.TREE_ID) {
					this.obTree = BX(this.visual.TREE_ID);
					if(!this.obTree) {
						this.errorCode = -256;
					}
				}

				if(this.visual.QUANTITY_MEASURE) {
					this.obMeasure = BX(this.visual.QUANTITY_MEASURE);
				}

				if(this.visual.QUANTITY_LIMIT && this.config.showMaxQuantity !== 'N') {
					this.obQuantityLimit.all = BX(this.visual.QUANTITY_LIMIT);
					if(this.obQuantityLimit.all) {
						this.obQuantityLimit.value = this.getEntity(this.obQuantityLimit.all, 'quantity-limit-value');
						if(!this.obQuantityLimit.value) {
							this.obQuantityLimit.all = null;
						}
					}
				}

				if(this.visual.QUANTITY_LIMIT_NOT_AVAILABLE && this.config.showMaxQuantity !== 'N') {
					this.obQuantityLimitNotAvl.all = BX(this.visual.QUANTITY_LIMIT_NOT_AVAILABLE);
				}

				if(this.config.usePriceRanges) {
					this.obPriceRanges = this.getEntity(this.obProduct, 'price-ranges-block');
				}
			}

			if(this.visual.ARTICLE_ID) {
				this.obArticle.all = BX(this.visual.ARTICLE_ID);
				if(this.obArticle.all) {
					this.obArticle.value = this.getEntity(this.obArticle.all, 'article-value');
				}
			}

			if(this.config.showSkuProps) {
				this.obSkuProps = BX(this.visual.DISPLAY_PROP_DIV);
				this.obSkuPropsSecond = BX(this.visual.DISPLAY_PROP_DIV_SECOND);
				this.obMainSkuProps = BX(this.visual.DISPLAY_MAIN_PROP_DIV);
				this.obMainSkuPropsSecond = BX(this.visual.DISPLAY_MAIN_PROP_DIV_SECOND);
			}

			if(!this.obSkuItems) {
				this.obDelay = BX(this.visual.DELAY_LINK);
				
				if(this.config.useCompare)
					this.obCompare = BX(this.visual.COMPARE_LINK);
			} else {
				this.obDelay = this.getEntities(this.obSkuItems, 'delay');

				if(this.config.useCompare)
					this.obCompare = this.getEntities(this.obSkuItems, 'compare');
			}
			
			if(this.config.useSubscribe) {
				this.obSubscribe = BX(this.visual.SUBSCRIBE_LINK);
			}

			this.popupPanel = document.body.querySelector('.popup-panel');
			if(!!this.popupPanel) {
				this.popupPanelTitle = this.popupPanel.querySelector('.popup-panel__title-wrap');
				
				this.popupPanelContent = this.popupPanel.querySelector('.popup-panel__content');
				if(!!this.popupPanelContent && BX.hasClass(this.popupPanelContent, 'scroll-wrapper'))
					this.popupPanelContent = this.popupPanelContent.querySelector('.scroll-content');
			}
			
			this.obTabs = BX(this.visual.TABS_ID);
			this.obTabsBlock = this.getEntity(this.obTabs, 'tabs');
			this.obTabContainers = BX(this.visual.TAB_CONTAINERS_ID);
			this.initPopup();

			if(window.innerWidth < 992){
				BX.remove(BX(this.visual.TABS_ID));
				BX.style(BX(this.visual.TABS_ID_MOBILE), 'display', 'block');
			}else{
				BX.remove(BX(this.visual.TABS_ID_MOBILE));
				this.initTabs();
			}

			if(this.obTabsBlock) {
				this.tabsPanelFixed = false;
				this.tabsPanelScrolled = false;
				this.lastScrollTop = 0;
			}

			this.obPayBlock = this.obProduct.querySelector('.product-item-detail-pay-block');
			if(this.obPayBlock) {
				this.shortCardNodes.picture = this.getEntity(this.obPayBlock, 'short-card-picture');
				
				this.payBlockFixed = false;
				this.payBlockHidden = false;
				this.checkTopPayBlockScroll();
				BX.bind(window, 'scroll', BX.proxy(this.checkTopPayBlockScroll, this));
				
				this.payBlockMoved = false;
				this.checkTopPayBlockResize();
				BX.bind(window, 'resize', BX.proxy(this.checkTopPayBlockResize, this));
			}

			this.obObjectBtn = this.obProduct.querySelector('.product-item-detail-object-btn');
			if(!!this.obSkuItems)
				this.obObjectsBtn = this.getEntities(this.obSkuItems, 'object');
			
			if(this.config.showOfferGroup)
				this.obConstructor = BX(this.visual.CONSTRUCTOR_ID);
			
			this.obMoreProductsSectionsLinks = this.getEntity(this.obProduct, 'moreProductsSectionsLinks');

			this.obGeoDelivery = BX(this.visual.GEO_DELIVERY_ID);
			
			if(this.errorCode === 0) {
				//product slider events
				if(this.config.showSlider && !this.isTouchDevice) {
					BX.bind(this.obBigSlider, 'mouseenter', BX.proxy(this.stopSlider, this));
					BX.bind(this.obBigSlider, 'mouseleave', BX.proxy(this.cycleSlider, this));
				}

				if(this.isTouchDevice) {
					BX.bind(this.node.videoImageContainer, 'touchstart', BX.proxy(this.touchStartEvent, this));
					BX.bind(this.node.videoImageContainer, 'touchend', BX.proxy(this.touchEndEvent, this));
					BX.bind(this.node.videoImageContainer, 'touchcancel', BX.proxy(this.touchEndEvent, this));
				}

				BX.bind(this.node.sliderControlLeft, 'click', BX.proxy(this.slidePrev, this));
				BX.bind(this.node.sliderControlRight, 'click', BX.proxy(this.slideNext, this));

				if(this.config.showQuantity) {
					if(!this.obSkuItems) {
						if(this.obQuantityUp) {
							BX.bind(this.obQuantityUp, 'click', BX.delegate(this.quantityUp, this));
						}
						if(this.obQuantityDown) {
							BX.bind(this.obQuantityDown, 'click', BX.delegate(this.quantityDown, this));
						}
						if(this.obQuantity) {
							BX.bind(this.obQuantity, 'change', BX.delegate(this.quantityChange, this));
						}

						if(this.obPcQuantityUp) {
							BX.bind(this.obPcQuantityUp, 'click', BX.delegate(this.quantityUp, this));
						}
						if(this.obPcQuantityDown) {
							BX.bind(this.obPcQuantityDown, 'click', BX.delegate(this.quantityDown, this));
						}
						if(this.obPcQuantity) {
							BX.bind(this.obPcQuantity, 'change', BX.delegate(this.pcQuantityChange, this));
						}

						if(this.obSqMQuantityUp) {
							BX.bind(this.obSqMQuantityUp, 'click', BX.delegate(this.quantityUp, this));
						}
						if(this.obSqMQuantityDown) {
							BX.bind(this.obSqMQuantityDown, 'click', BX.delegate(this.quantityDown, this));
						}
						if(this.obSqMQuantity) {
							BX.bind(this.obSqMQuantity, 'change', BX.delegate(this.sqMQuantityChange, this));
						}
					} else {
						if(this.obQuantityUp) {
							for(var i in this.obQuantityUp) {
								if(this.obQuantityUp.hasOwnProperty(i) && BX.type.isDomNode(this.obQuantityUp[i])) {
									BX.bind(this.obQuantityUp[i], 'click', BX.delegate(this.quantityUp, this));
								}
							}
						}
						if(this.obQuantityDown) {
							for(var i in this.obQuantityDown) {
								if(this.obQuantityDown.hasOwnProperty(i) && BX.type.isDomNode(this.obQuantityDown[i])) {
									BX.bind(this.obQuantityDown[i], 'click', BX.delegate(this.quantityDown, this));
								}
							}
						}
						if(this.obQuantity) {
							for(var i in this.obQuantity) {
								if(this.obQuantity.hasOwnProperty(i) && BX.type.isDomNode(this.obQuantity[i])) {
									BX.bind(this.obQuantity[i], 'change', BX.delegate(this.quantityChange, this));
								}
							}
						}

						if(this.obPcQuantityUp) {
							for(var i in this.obPcQuantityUp) {
								if(this.obPcQuantityUp.hasOwnProperty(i) && BX.type.isDomNode(this.obPcQuantityUp[i])) {
									BX.bind(this.obPcQuantityUp[i], 'click', BX.delegate(this.quantityUp, this));
								}
							}
						}
						if(this.obPcQuantityDown) {
							for(var i in this.obPcQuantityDown) {
								if(this.obPcQuantityDown.hasOwnProperty(i) && BX.type.isDomNode(this.obPcQuantityDown[i])) {
									BX.bind(this.obPcQuantityDown[i], 'click', BX.delegate(this.quantityDown, this));
								}
							}
						}
						if(this.obPcQuantity) {
							for(var i in this.obPcQuantity) {
								if(this.obPcQuantity.hasOwnProperty(i) && BX.type.isDomNode(this.obPcQuantity[i])) {
									BX.bind(this.obPcQuantity[i], 'change', BX.delegate(this.pcQuantityChange, this));
								}
							}
						}

						if(this.obSqMQuantityUp) {
							for(var i in this.obSqMQuantityUp) {
								if(this.obSqMQuantityUp.hasOwnProperty(i) && BX.type.isDomNode(this.obSqMQuantityUp[i])) {
									BX.bind(this.obSqMQuantityUp[i], 'click', BX.delegate(this.quantityUp, this));
								}
							}
						}
						if(this.obSqMQuantityDown) {
							for(var i in this.obSqMQuantityDown) {
								if(this.obSqMQuantityDown.hasOwnProperty(i) && BX.type.isDomNode(this.obSqMQuantityDown[i])) {
									BX.bind(this.obSqMQuantityDown[i], 'click', BX.delegate(this.quantityDown, this));
								}
							}
						}
						if(this.obSqMQuantity) {
							for(var i in this.obSqMQuantity) {
								if(this.obSqMQuantity.hasOwnProperty(i) && BX.type.isDomNode(this.obSqMQuantity[i])) {
									BX.bind(this.obSqMQuantity[i], 'change', BX.delegate(this.sqMQuantityChange, this));
								}
							}
						}
					}
				}

				switch(this.productType) {
					case 0: //no catalog
					case 1: //product
					case 2: //set
						if(this.product.useSlider) {
							this.product.slider = {
								ID: this.visual.SLIDER_CONT_ID,
								CONT: BX(this.visual.SLIDER_CONT_ID),
								COUNT: this.product.sliderCount
							};
							this.product.slider.ITEMS = this.getEntities(this.product.slider.CONT, 'slider-control');

							for(j = 0; j < this.product.slider.ITEMS.length; j++) {
								BX.bind(this.product.slider.ITEMS[j], 'click', BX.delegate(this.selectSliderImg, this));
							}
							
							var i = 0;
							for(j = 0; j < this.product.sliderPict.length; j++) {
								if(!!this.product.sliderPict[i].VALUE && this.product.sliderPict[i].VALUE != '')
									i++;
							}
							this.setCurrentImg(this.product.sliderPict[i], true, true);							
							
							this.checkSliderControls(this.product.sliderCount);

							if(this.product.slider.ITEMS.length > 1) {
								this.initSlider();
							}
						}

						this.checkQuantityControls();
						this.geoDelivery();
						this.setAnalyticsDataLayer('showDetail');
						break;
					case 3: //sku
						if(!this.obSkuItems && this.offersView == 'PROPS') {
							treeItems = this.obTree.querySelectorAll('li');
							for(i = 0; i < treeItems.length; i++) {
								BX.bind(treeItems[i], 'click', BX.delegate(this.selectOfferProp, this));
							}
						}

						for(i = 0; i < this.offers.length; i++) {
							this.offers[i].SLIDER_COUNT = parseInt(this.offers[i].SLIDER_COUNT, 10) || 0;
							if(this.offers[i].SLIDER_COUNT === 0) {
								this.slider.controls[i] = {
									ID: '',
									COUNT: this.offers[i].SLIDER_COUNT,
									ITEMS: []
								};
							} else {
								for(j = 0; j < this.offers[i].SLIDER.length; j++) {
									this.offers[i].SLIDER[j].WIDTH = parseInt(this.offers[i].SLIDER[j].WIDTH, 10);
									this.offers[i].SLIDER[j].HEIGHT = parseInt(this.offers[i].SLIDER[j].HEIGHT, 10);
								}

								this.slider.controls[i] = {
									ID: this.visual.SLIDER_CONT_OF_ID + this.offers[i].ID,
									OFFER_ID: this.offers[i].ID,
									CONT: BX(this.visual.SLIDER_CONT_OF_ID + this.offers[i].ID),
									COUNT: this.offers[i].SLIDER_COUNT
								};

								this.slider.controls[i].ITEMS = this.getEntities(this.slider.controls[i].CONT, 'slider-control');
								for(j = 0; j < this.slider.controls[i].ITEMS.length; j++) {									
									BX.bind(this.slider.controls[i].ITEMS[j], 'click', BX.delegate(this.selectSliderImg, this));
								}
							}
						}

						if(!this.obSkuItems) {
							this.setCurrent();
							this.checkTargetOffer();
						} else {
							this.checkSliderControls(this.offers[this.offerNum].SLIDER_COUNT);

							if(this.slider.controls[this.offerNum].ID)
								this.product.slider = this.slider.controls[this.offerNum];
							else
								this.product.slider = {};
							
							i = 0;
							for(j = 0; j < this.offers[this.offerNum].SLIDER.length; j++) {
								if(!!this.offers[this.offerNum].SLIDER[j].VALUE && this.offers[this.offerNum].SLIDER[j].VALUE != '')
									i++;
							}
							this.setCurrentImg(this.offers[this.offerNum].SLIDER[i], true, true);
							
							if(this.offers[this.offerNum].SLIDER_COUNT > 1)
								this.initSlider();
						}
						break;
				}

				this.sPanel = document.body.querySelector('.slide-panel');

				if(!this.obSkuItems) {
					this.obBuyBtn && BX.bind(this.obBuyBtn, 'click', BX.proxy(this.buyBasket, this));
					this.obAddToBasketBtn && BX.bind(this.obAddToBasketBtn, 'click', BX.proxy(this.add2Basket, this));

					this.obPartnersBtn && BX.bind(this.obPartnersBtn, 'click', BX.proxy(this.partnerSiteRedirect, this));

					this.obAskPrice && BX.bind(this.obAskPrice, 'click', BX.proxy(this.sPanelForm, this));
					this.obNotAvail && BX.bind(this.obNotAvail, 'click', BX.proxy(this.sPanelForm, this));

					this.obDelay && BX.bind(this.obDelay, 'click', BX.proxy(this.delay, this));

					this.obCompare && BX.bind(this.obCompare, 'click', BX.proxy(this.compare, this));
				} else {
					if(this.obBuyBtn) {
						for(var i in this.obBuyBtn) {
							if(this.obBuyBtn.hasOwnProperty(i) && BX.type.isDomNode(this.obBuyBtn[i])) {
								BX.bind(this.obBuyBtn[i], 'click', BX.proxy(this.buyBasket, this));
							}
						}
					}
					if(this.obAddToBasketBtn) {
						for(var i in this.obAddToBasketBtn) {
							if(this.obAddToBasketBtn.hasOwnProperty(i) && BX.type.isDomNode(this.obAddToBasketBtn[i])) {
								BX.bind(this.obAddToBasketBtn[i], 'click', BX.proxy(this.add2Basket, this));
							}
						}
					}

					if(this.obPartnersBtn) {
						for(var i in this.obPartnersBtn) {
							if(this.obPartnersBtn.hasOwnProperty(i) && BX.type.isDomNode(this.obPartnersBtn[i])) {
								BX.bind(this.obPartnersBtn[i], 'click', BX.proxy(this.partnerSiteRedirect, this));
							}
						}
					}

					if(this.obAskPrice) {
						for(var i in this.obAskPrice) {
							if(this.obAskPrice.hasOwnProperty(i) && BX.type.isDomNode(this.obAskPrice[i])) {
								BX.bind(this.obAskPrice[i], 'click', BX.proxy(this.sPanelForm, this));
							}
						}
					}
					if(this.obNotAvail) {
						for(var i in this.obNotAvail) {
							if(this.obNotAvail.hasOwnProperty(i) && BX.type.isDomNode(this.obNotAvail[i])) {
								BX.bind(this.obNotAvail[i], 'click', BX.proxy(this.sPanelForm, this));
							}
						}
					}

					if(this.obDelay) {
						for(var i in this.obDelay) {
							if(this.obDelay.hasOwnProperty(i) && BX.type.isDomNode(this.obDelay[i])) {
								BX.bind(this.obDelay[i], 'click', BX.proxy(this.delay, this));
							}
						}
					}

					if(this.obCompare) {
						for(var i in this.obCompare) {
							if(this.obCompare.hasOwnProperty(i) && BX.type.isDomNode(this.obCompare[i])) {
								BX.bind(this.obCompare[i], 'click', BX.proxy(this.compare, this));
							}
						}
					}

					this.obSelectSku && BX.bind(this.obSelectSku, 'click', BX.proxy(this.scrollToSkuItems, this));
				}

				if(this.obBuyBtn || this.obAddToBasketBtn || this.obDelay || this.obCompare)
					this.checkComparedDelayedBuyedAdded();

				BX.addCustomEvent(this, 'sPanelFormRequest', BX.proxy(this.sPanelFormRequest, this));

				if(this.obCompare)
					BX.addCustomEvent('onCatalogDeleteCompare', BX.proxy(this.checkDeletedCompare, this));

				this.obObjectBtn && BX.bind(this.obObjectBtn, 'click', BX.proxy(this.object.callbackForm ? this.objectContactsForm : this.objectContacts, this));

				BX.addCustomEvent(this, 'getObjectWorkingHoursToday', BX.proxy(this.getObjectWorkingHoursToday, this));
				BX.addCustomEvent(this, 'objectWorkingHoursTodayReceived', BX.proxy(this.adjustObjectContacts, this));
				BX.addCustomEvent(this, 'objectContactsAdjusted', BX.proxy(this.object.callbackForm ? this.objectContactsFormRequest : this.objectContactsRequest, this));				
				
				if(!!this.obSkuItems) {
					if(this.offersView == 'OBJECTS')
						this.showObjectWorkingHoursToday();

					if(!!this.obObjectsBtn) {
						for(var i in this.obObjectsBtn) {
							if(this.obObjectsBtn.hasOwnProperty(i) && BX.type.isDomNode(this.obObjectsBtn[i])) {
								BX.bind(this.obObjectsBtn[i], 'click', BX.delegate(function(e) {
									this.checkCurrentSkuItem(BX.proxy_context);
									this.skuItemObject.callbackForm ? this.objectContactsForm(e) : this.objectContacts(e);
								}, this));
							}
						}
					}
				}
				
				BX.addCustomEvent(this, 'adjustSkuItemObjectContacts', BX.proxy(this.adjustSkuItemObjectContacts, this));
				BX.addCustomEvent(this, 'skuItemObjectContactsAdjusted', BX.delegate(function(sPanelContent) {
					this.skuItemObject.callbackForm ? this.objectContactsFormRequest(sPanelContent) : this.objectContactsRequest(sPanelContent);
				}, this));
				
				BX.bind(document, 'click', BX.delegate(function(e) {
					if(BX.hasClass(this.sPanel, 'active') && BX.findParent(e.target, {attrs: {id: this.visual.ID + (!!this.obSkuItems && this.offersView == 'OBJECTS' ? '_' + this.skuItem.ID : '') + '_contacts'}}) && BX.hasClass(e.target, 'icon-arrow-down')) {
						var workingHoursToday = BX.findParent(e.target, {attrs: {'data-entity': 'working-hours-today'}});
						if(!!workingHoursToday)
							BX.style(workingHoursToday, 'display', 'none');
						
						var workingHours = BX(this.visual.ID + (!!this.obSkuItems && this.offersView == 'OBJECTS' ? '_' + this.skuItem.ID : '') + '_contacts').querySelector('[data-entity="working-hours"]');
						if(!!workingHours)
							BX.style(workingHours, 'display', '');
						
						e.stopPropagation();
					}
				}, this));
				BX.bind(document, 'click', BX.delegate(function(e) {
					if(BX.hasClass(this.sPanel, 'active') && BX.findParent(e.target, {attrs: {id: this.visual.ID + (!!this.obSkuItems && this.offersView == 'OBJECTS' ? '_' + this.skuItem.ID : '') + '_contacts'}}) && BX.hasClass(e.target, 'icon-arrow-up')) {
						var workingHours = BX.findParent(e.target, {attrs: {'data-entity': 'working-hours'}});
						if(!!workingHours)
							BX.style(workingHours, 'display', 'none');
						
						var workingHoursToday = BX(this.visual.ID + (!!this.obSkuItems && this.offersView == 'OBJECTS' ? '_' + this.skuItem.ID : '') + '_contacts').querySelector('[data-entity="working-hours-today"]');
						if(!!workingHoursToday)
							BX.style(workingHoursToday, 'display', '');
						
						e.stopPropagation();
					}
				}, this));
				
				this.obMoreProductsSectionsLinks && BX.bind(this.obMoreProductsSectionsLinks, 'click', BX.proxy(this.changeMoreProductsSectionLink, this));

				this.obGeoDelivery && BX.bind(this.obGeoDelivery, 'click', BX.delegate(this.sPanelGeoDelivery, this));
				
				BX.addCustomEvent(this, 'sPanelGeoDeliveryRequest', BX.proxy(this.sPanelGeoDeliveryRequest, this));

				BX.addCustomEvent('onAjaxSuccess', BX.proxy(this.removeNodes, this));

				this.allowViewedCount(true);
			}
		},

		initConfig: function() {
			this.productType = parseInt(this.params.PRODUCT_TYPE, 10);

			this.offersView = this.params.OFFERS_VIEW;
			
			if(this.params.CONFIG.USE_CATALOG !== 'undefined' && BX.type.isBoolean(this.params.CONFIG.USE_CATALOG)) {
				this.config.useCatalog = this.params.CONFIG.USE_CATALOG;
			}

			this.config.showQuantity = this.params.CONFIG.SHOW_QUANTITY;
			this.config.showPrice = this.params.CONFIG.SHOW_PRICE;
			this.config.showPercent = this.params.CONFIG.SHOW_DISCOUNT_PERCENT;
			this.config.showOldPrice = this.params.CONFIG.SHOW_OLD_PRICE;
			this.config.showSkuProps = this.params.CONFIG.SHOW_SKU_PROPS;
			this.config.showOfferGroup = this.params.CONFIG.OFFER_GROUP;
			this.config.useCompare = this.params.CONFIG.DISPLAY_COMPARE;
			this.config.useSubscribe = this.params.CONFIG.USE_SUBSCRIBE;
			this.config.showMaxQuantity = this.params.CONFIG.SHOW_MAX_QUANTITY;
			this.config.relativeQuantityFactor = parseInt(this.params.CONFIG.RELATIVE_QUANTITY_FACTOR);
			this.config.usePriceRanges = this.params.CONFIG.USE_PRICE_COUNT;

			if(this.params.CONFIG.MAIN_PICTURE_MODE) {
				this.config.usePopup = BX.util.in_array('POPUP', this.params.CONFIG.MAIN_PICTURE_MODE);
				this.config.useMagnifier = BX.util.in_array('MAGNIFIER', this.params.CONFIG.MAIN_PICTURE_MODE);
			}

			if(this.params.CONFIG.ADD_TO_BASKET_ACTION) {
				this.config.basketAction = this.params.CONFIG.ADD_TO_BASKET_ACTION;
			}

			this.config.minOrderSum = this.params.CONFIG.MIN_ORDER_SUM;
			
			this.config.showSlider = this.params.CONFIG.SHOW_SLIDER === 'Y';

			if(this.config.showSlider && !this.isTouchDevice) {
				this.config.sliderInterval = parseInt(this.params.CONFIG.SLIDER_INTERVAL) || 5000;
			} else {
				this.config.sliderInterval = false;
			}

			this.config.useEnhancedEcommerce = this.params.CONFIG.USE_ENHANCED_ECOMMERCE === 'Y';
			this.config.dataLayerName = this.params.CONFIG.DATA_LAYER_NAME;
			this.config.brandProperty = this.params.CONFIG.BRAND_PROPERTY;

			this.config.alt = this.params.CONFIG.ALT || '';
			this.config.title = this.params.CONFIG.TITLE || '';

			this.config.magnifierZoomPercent = parseInt(this.params.CONFIG.MAGNIFIER_ZOOM_PERCENT) || 100;

			if(!this.params.VISUAL || typeof this.params.VISUAL !== 'object' || !this.params.VISUAL.ID) {
				this.errorCode = -1;
				return;
			}

			this.visual = this.params.VISUAL;
		},

		initProductData: function() {
			var i = 0,
				j = 0;

			if(this.params.PRODUCT && typeof this.params.PRODUCT === 'object') {
				if(this.config.showPrice) {
					this.currentPriceMode = this.params.PRODUCT.ITEM_PRICE_MODE;
					this.currentPrices = this.params.PRODUCT.ITEM_PRICES;
					this.currentPriceSelected = this.params.PRODUCT.ITEM_PRICE_SELECTED;
					this.currentQuantityRanges = this.params.PRODUCT.ITEM_QUANTITY_RANGES;
					this.currentQuantityRangeSelected = this.params.PRODUCT.ITEM_QUANTITY_RANGE_SELECTED;
				}
				
				if(this.config.showQuantity) {
					this.currentMeasure = this.params.PRODUCT.ITEM_MEASURE;

					this.product.checkQuantity = this.params.PRODUCT.CHECK_QUANTITY;
					this.product.isDblQuantity = this.params.PRODUCT.QUANTITY_FLOAT;
					
					if(this.product.checkQuantity) {
						this.product.maxQuantity = this.product.isDblQuantity
							? parseFloat(this.params.PRODUCT.MAX_QUANTITY)
							: parseInt(this.params.PRODUCT.MAX_QUANTITY, 10);
						this.product.maxPcQuantity = parseInt(this.params.PRODUCT.PC_MAX_QUANTITY, 10);
						this.product.maxSqMQuantity = parseFloat(this.params.PRODUCT.SQ_M_MAX_QUANTITY);
					}

					this.product.stepQuantity = this.product.isDblQuantity
						? parseFloat(this.params.PRODUCT.STEP_QUANTITY)
						: parseInt(this.params.PRODUCT.STEP_QUANTITY, 10);
					this.product.stepPcQuantity = parseInt(this.params.PRODUCT.PC_STEP_QUANTITY, 10);
					this.product.stepSqMQuantity = parseFloat(this.params.PRODUCT.SQ_M_STEP_QUANTITY);
					this.checkQuantity = this.product.checkQuantity;
					this.isDblQuantity = this.product.isDblQuantity;					
					this.stepQuantity = this.product.stepQuantity;
					this.stepPcQuantity = this.product.stepPcQuantity;
					this.stepSqMQuantity = this.product.stepSqMQuantity;
					this.maxQuantity = this.product.maxQuantity;
					this.maxPcQuantity = this.product.maxPcQuantity;
					this.maxSqMQuantity = this.product.maxSqMQuantity;
					this.minQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
					this.minPcQuantity = this.stepPcQuantity;
					this.minSqMQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].SQ_M_MIN_QUANTITY) : this.stepSqMQuantity;
					
					if(this.isDblQuantity) {
						this.stepQuantity = Math.round(this.stepQuantity * this.precisionFactor) / this.precisionFactor;
					}
					this.stepSqMQuantity = Math.round(this.stepSqMQuantity * this.precisionFactor) / this.precisionFactor;
				}

				this.product.canBuy = this.params.PRODUCT.CAN_BUY;
				this.canSubscription = this.product.canSubscription = this.params.PRODUCT.SUBSCRIPTION;

				this.product.name = this.params.PRODUCT.NAME;
				this.product.pict = this.params.PRODUCT.PICT;
				this.product.id = this.params.PRODUCT.ID;
				this.product.iblockId = this.params.PRODUCT.IBLOCK_ID;
				this.product.category = this.params.PRODUCT.CATEGORY;

				if(this.params.PRODUCT.ADD_URL) {
					this.product.addUrl = this.params.PRODUCT.ADD_URL;
				}

				if(this.params.PRODUCT.BUY_URL) {
					this.product.buyUrl = this.params.PRODUCT.BUY_URL;
				}

				if(this.params.PRODUCT.SLIDER_COUNT) {
					this.product.sliderCount = parseInt(this.params.PRODUCT.SLIDER_COUNT, 10) || 0;

					if(this.product.sliderCount > 0 && this.params.PRODUCT.SLIDER.length) {
						for(j = 0; j < this.params.PRODUCT.SLIDER.length; j++) {
							this.product.useSlider = true;
							this.params.PRODUCT.SLIDER[j].WIDTH = parseInt(this.params.PRODUCT.SLIDER[j].WIDTH, 10);
							this.params.PRODUCT.SLIDER[j].HEIGHT = parseInt(this.params.PRODUCT.SLIDER[j].HEIGHT, 10);
							if(this.params.PRODUCT.SLIDER[j].LIVE) {
								this.idVideo = this.params.PRODUCT.SLIDER[j].PARAMS.ID_VIDEO;
								this.nameLive = this.params.PRODUCT.SLIDER[j].PARAMS.NAME;
								this.countWatch = this.params.PRODUCT.SLIDER[j].PARAMS.LIVE_COUNT;
								BX.bind(BX('img_live'), 'click', BX.proxy(this.openFrameLiveVideo_elem, this));
							}
						}

						this.product.sliderPict = this.params.PRODUCT.SLIDER;
						i = 0;
						for(j = 0; j < this.product.sliderPict.length; j++) {
							if(!!this.product.sliderPict[i].VALUE && this.product.sliderPict[i].VALUE != '')
								i++;
						}
						this.setCurrentImg(this.product.sliderPict[i], false);
					}
				}
				
				this.currentIsSet = true;
			} else {
				this.errorCode = -1;
			}
		},

		initOffersData: function() {
			if(this.params.OFFERS && BX.type.isArray(this.params.OFFERS)) {
				this.offers = this.params.OFFERS;
				this.offerNum = 0;

				if(this.params.OFFER_SELECTED) {
					this.offerNum = parseInt(this.params.OFFER_SELECTED, 10) || 0;
				}

				if(this.params.TREE_PROPS) {
					this.treeProps = this.params.TREE_PROPS;
				}

				if(this.params.DEFAULT_PICTURE) {
					this.defaultPict.preview = this.params.DEFAULT_PICTURE.PREVIEW_PICTURE;
					this.defaultPict.detail = this.params.DEFAULT_PICTURE.DETAIL_PICTURE;
				}

				if(this.params.PRODUCT && typeof this.params.PRODUCT === 'object') {
					this.product.id = parseInt(this.params.PRODUCT.ID, 10);
					this.product.iblockId = parseInt(this.params.PRODUCT.IBLOCK_ID, 10);
					this.product.name = this.params.PRODUCT.NAME;
					this.product.category = this.params.PRODUCT.CATEGORY;
				}
			} else {
				this.errorCode = -1;
			}
		},

		initBasketData: function() {
			if(this.params.BASKET && typeof this.params.BASKET === 'object') {
				if(this.params.MARKETPLACES.ozon){
					this.obAddToMarketplaceBtnOzon = this.params.PRODUCT.OZON;
				}
				if(this.params.EX_QUANT_PROGRESS){
					this.exQuantProgress = this.params.EX_QUANT_PROGRESS;
				}

				if(this.params.BASKET.QUANTITY) {
					this.basketData.quantity = this.params.BASKET.QUANTITY;
				}

				if(this.params.BASKET.PROPS) {
					this.basketData.props = this.params.BASKET.PROPS;
				}

				if(this.params.BASKET.BASKET_URL) {
					this.basketData.basketUrl = this.params.BASKET.BASKET_URL;
				}

				if(this.productType === 3) {
					if(this.params.BASKET.SKU_PROPS) {
						this.basketData.sku_props = this.params.BASKET.SKU_PROPS;
					}
				}

				if(this.params.BASKET.ADD_URL_TEMPLATE) {
					this.basketData.add_url = this.params.BASKET.ADD_URL_TEMPLATE;
				}

				if(this.params.BASKET.BUY_URL_TEMPLATE) {
					this.basketData.buy_url = this.params.BASKET.BUY_URL_TEMPLATE;
				}

				if(this.basketData.add_url === '' && this.basketData.buy_url === '') {
					this.errorCode = -1024;
				}
			}
		},
			
		initCompareData: function() {
			if(this.config.useCompare) {
				if(this.params.COMPARE && typeof this.params.COMPARE === 'object') {
					if(this.params.COMPARE.COMPARE_NAME) {
						this.compareData.compareName = this.params.COMPARE.COMPARE_NAME;
					}

					if(this.params.COMPARE.COMPARE_PATH) {
						this.compareData.comparePath = this.params.COMPARE.COMPARE_PATH;
					}

					if(this.params.COMPARE.COMPARE_URL_TEMPLATE) {
						this.compareData.compareUrl = this.params.COMPARE.COMPARE_URL_TEMPLATE;
					} else {
						this.config.useCompare = false;
					}

					if(this.params.COMPARE.COMPARE_DELETE_URL_TEMPLATE) {
						this.compareData.compareDeleteUrl = this.params.COMPARE.COMPARE_DELETE_URL_TEMPLATE;
					} else {
						this.config.useCompare = false;
					}
				} else {
					this.config.useCompare = false;
				}
			}
		},

		initObjectData: function() {
			if(this.params.OBJECT && typeof this.params.OBJECT === 'object') {
				if(this.params.OBJECT.ID) {
					this.object.id = this.params.OBJECT.ID;
				}

				if(this.params.OBJECT.NAME) {
					this.object.name = this.params.OBJECT.NAME;
				}

				if(this.params.OBJECT.ADDRESS) {
					this.object.address = this.params.OBJECT.ADDRESS;
				}

				if(this.params.OBJECT.TIMEZONE) {
					this.object.timezone = this.params.OBJECT.TIMEZONE;
				}

				if(this.params.OBJECT.WORKING_HOURS) {
					this.object.workingHours = this.params.OBJECT.WORKING_HOURS;
				}

				if(this.params.OBJECT.PHONE) {
					this.object.phone = this.params.OBJECT.PHONE.VALUE;
					this.object.phoneDescription = this.params.OBJECT.PHONE.DESCRIPTION;
				}
				
				if(this.params.OBJECT.EMAIL) {
					this.object.email = this.params.OBJECT.EMAIL.VALUE;
					this.object.emailDescription = this.params.OBJECT.EMAIL.DESCRIPTION;
				}
				
				if(this.params.OBJECT.SKYPE) {
					this.object.skype = this.params.OBJECT.SKYPE.VALUE;
					this.object.skypeDescription = this.params.OBJECT.SKYPE.DESCRIPTION;
				}
				
				if(this.params.OBJECT.CALLBACK_FORM) {
					this.object.callbackForm = this.params.OBJECT.CALLBACK_FORM;
				}
			}
		},

		initMoreProductsData: function() {
			if(this.params.MORE_PRODUCTS && typeof this.params.MORE_PRODUCTS === 'object') {
				if(this.params.MORE_PRODUCTS.PRODUCTS_IDS) {
					this.moreProducts.ids = this.params.MORE_PRODUCTS.PRODUCTS_IDS;
				}
			}
		},

		initSlider: function() {
			if(this.node.sliderProgressBar) {
				if(this.slider.progress) {
					this.resetProgress();
				} else {
					this.slider.progress = new BX.easing({
						transition: BX.easing.transitions.linear,
						step: BX.delegate(function(state){
							this.node.sliderProgressBar.style.width = state.width / 10 + '%';
						}, this)
					});
				}
			}

			this.cycleSlider();
		},

		checkTargetOffer: function() {
			var data = {};
			data['action'] = 'checkTargetOffer';
			data['offers'] = this.offers;
			
			var search = window.location.search;
			if(!!search) {
				var getParams = search.substring(1).split("&");
				for(var i = 0; i < getParams.length; i++) {
					var getParam = getParams[i].split('=');
					data[getParam[0]] = typeof(getParam[1]) == 'undefined' ? '' : getParam[1];
				}
			}
			
			BX.ajax({
				url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: data,
				onsuccess: BX.delegate(function(result) {
					if(!!result.offerNum)
						this.setOffer(result.offerNum);
				}, this)
			});
		},

		checkComparedDelayedBuyedAdded: function() {
			var data = {};
			data['action'] = 'checkComparedDelayedBuyedAdded';

			switch(this.productType) {
				case 1: //product
				case 2: //set
					data['productId'] = this.product.id;
					break;
				case 3: //sku
					data['offers'] = this.offers;
					data['offerNum'] = this.offerNum;
					data['offersView'] = this.offersView;
					break;
			}

			if(this.obBuyBtn || this.obAddToBasketBtn)
				data['checkBuyedAdded'] = true;

			if(this.obDelay)
				data['checkDelayed'] = true;

			if(this.obCompare) {
				data['checkCompared'] = true;
				data['compareName'] = this.compareData.compareName;
				data['iblockId'] = this.product.iblockId;
			}

			BX.ajax({
				url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: data,
				onsuccess: BX.delegate(function(result) {
					if(this.obCompare) {
						if(!this.obSkuItems)
							this.setCompared(result.compared);
						if(result.comparedIds.length > 0)
							this.setCompareInfo(result.comparedIds);
					}
					
					if(this.obDelay) {
						if(!this.obSkuItems)
							this.setDelayed(result.delayed);
						if(result.delayedIds.length > 0)
							this.setDelayInfo(result.delayedIds);
					}
					
					if(this.obBuyBtn || this.obAddToBasketBtn) {
						if(!this.obSkuItems)
							this.setBuyedAdded(result.buyedAdded);
						if(result.buyedAddedIds.length > 0)
							this.setBuyAddInfo(result.buyedAddedIds);
					}
				}, this)
			});
		},

		setAnalyticsDataLayer: function(action) {
			if(!this.config.useEnhancedEcommerce || !this.config.dataLayerName)
				return;
			
			var item = {},
				info = {},
				variants = [],
				i, k, j, propId, skuId, propValues;

			switch(this.productType) {
				case 0: //no catalog
				case 1: //product
				case 2: //set
					item = {
						'id': this.product.id,
						'name': this.product.name,
						'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
						'category': this.product.category,
						'brand': BX.type.isArray(this.config.brandProperty) ? this.config.brandProperty.join('/') : this.config.brandProperty
					};
					break;
				case 3: //sku
					for(i in this.offers[!this.obSkuItems ? this.offerNum : this.skuItemNum].TREE) {
						if(this.offers[!this.obSkuItems ? this.offerNum : this.skuItemNum].TREE.hasOwnProperty(i)) {
							propId = i.substring(5);
							skuId = this.offers[!this.obSkuItems ? this.offerNum : this.skuItemNum].TREE[i];

							for(k in this.treeProps) {
								if(this.treeProps.hasOwnProperty(k) && this.treeProps[k].ID == propId) {
									for(j in this.treeProps[k].VALUES) {
										propValues = this.treeProps[k].VALUES[j];
										if(propValues.ID == skuId) {
											variants.push(propValues.NAME);
											break;
										}
									}
								}
							}
						}
					}

					item = {
						'id': this.offers[!this.obSkuItems ? this.offerNum : this.skuItemNum].ID,
						'name': this.offers[!this.obSkuItems ? this.offerNum : this.skuItemNum].NAME,
						'price': !this.obSkuItems ? (this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE) : (this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected] && this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].PRICE),
						'category': this.product.category,
						'brand': BX.type.isArray(this.config.brandProperty) ? this.config.brandProperty.join('/') : this.config.brandProperty,
						'variant': variants.join('/')
					};
					break;
			}

			switch(action) {
				case 'showDetail':
					info = {
						'event': 'showDetail',
						'ecommerce': {
							'currencyCode': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].CURRENCY || '',
							'detail': {
								'products': [{
									'name': item.name || '',
									'id': item.id || '',
									'price': item.price || 0,
									'brand': item.brand || '',
									'category': item.category || '',
									'variant': item.variant || ''
								}]
							}
						}
					};
					break;
				case 'addToCart':
					info = {
						'event': 'addToCart',
						'ecommerce': {
							'currencyCode': !this.obSkuItems ? (this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].CURRENCY || '') : (this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected] && this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].CURRENCY || ''),
							'add': {
								'products': [{
									'name': item.name || '',
									'id': item.id || '',
									'price': item.price || 0,
									'brand': item.brand || '',
									'category': item.category || '',
									'variant': item.variant || ''
								}]
							}
						}
					};

					if(this.config.showQuantity) {
						if((!this.obSkuItems && this.obQuantity && !this.obPcQuantity && !this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemQuantity && !this.obSkuItemPcQuantity && !this.obSkuItemSqMQuantity)) {
							info.ecommerce.add.products[0].quantity = !this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value;
						} else if((!this.obSkuItems && this.obPcQuantity && this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemPcQuantity && this.obSkuItemSqMQuantity)) {
							if(!this.obSkuItems) {
								if(this.currentMeasure.SYMBOL_INTL == 'pc. 1' || this.currentMeasure.SYMBOL_INTL == 'm2') {
									info.ecommerce.add.products[0].quantity = this.currentPrices[this.currentPriceSelected].SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value;
								} else {
									info.ecommerce.add.products[0].quantity = this.obQuantity.value;
								}
							} else {
								info.ecommerce.add.products[0].quantity = this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE ? this.obSkuItemPcQuantity.value : this.obSkuItemSqMQuantity.value;
							}
						}
					} else {
						if(!this.obSkuItems)
							info.ecommerce.add.products[0].quantity = this.currentPrices[this.currentPriceSelected] ? this.currentPrices[this.currentPriceSelected].MIN_QUANTITY : '';
						else
							info.ecommerce.add.products[0].quantity = this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected] ? this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].MIN_QUANTITY : '';
					}
					break;
			}
			
			window[this.config.dataLayerName] = window[this.config.dataLayerName] || [];
			window[this.config.dataLayerName].push(info);
		},
		initTabs: function() {
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
		},



		checkTouch: function(event) {
			if(!event || !event.changedTouches)
				return false;

			return event.changedTouches[0].identifier === this.touch.identifier;
		},

		touchStartEvent: function(event) {
			if(event.touches.length != 1)
				return;

			this.touch = event.changedTouches[0];
		},

		touchEndEvent: function(event) {
			if(!this.checkTouch(event))
				return;

			var deltaX = this.touch.pageX - event.changedTouches[0].pageX,
				deltaY = this.touch.pageY - event.changedTouches[0].pageY;

			if(Math.abs(deltaX) >= Math.abs(deltaY) + 10) {
				if(deltaX > 0) {
					this.slideNext();
				}

				if(deltaX < 0) {
					this.slidePrev();
				}
			}
		},

		cycleSlider: function(event) {
			event || (this.slider.paused = false);

			this.slider.interval && clearInterval(this.slider.interval);

			if(this.config.sliderInterval && !this.slider.paused) {
				if(this.slider.progress) {
					this.slider.progress.stop();

					var width = parseInt(this.node.sliderProgressBar.style.width);

					this.slider.progress.options.duration = this.config.sliderInterval * (100 - width) / 100;
					this.slider.progress.options.start = {width: width * 10};
					this.slider.progress.options.finish = {width: 1000};
					this.slider.progress.options.complete = BX.delegate(function(){
						this.slider.interval = true;
						this.slideNext();
					}, this);
					this.slider.progress.animate();
				} else {
					this.slider.interval = setInterval(BX.proxy(this.slideNext, this), this.config.sliderInterval);
				}
			}
		},

		stopSlider: function(event) {
			event || (this.slider.paused = true);

			this.slider.interval && (this.slider.interval = clearInterval(this.slider.interval));

			if(this.slider.progress) {
				this.slider.progress.stop();

				var width = parseInt(this.node.sliderProgressBar.style.width);

				this.slider.progress.options.duration = this.config.sliderInterval * width / 200;
				this.slider.progress.options.start = {width: width * 10};
				this.slider.progress.options.finish = {width: 0};
				this.slider.progress.options.complete = null;
				this.slider.progress.animate();
			}
		},

		resetProgress: function() {
			this.slider.progress && this.slider.progress.stop();
			this.node.sliderProgressBar.style.width = 0;
		},

		slideNext: function() {
			return this.slide('next');
		},

		slidePrev: function() {
			return this.slide('prev');
		},

		slide: function(type) {
			if(!this.product.slider || !this.product.slider.CONT)
				return;

			var active = this.getEntity(this.product.slider.CONT, 'slider-control', '.active'),
				next = this.getItemForDirection(type, active);

			BX.removeClass(active, 'active');
			this.selectSliderImg(next);

			this.slider.interval && this.cycleSlider();
		},

		getItemForDirection: function(direction, active) {
			var activeIndex = this.getItemIndex(active),
				delta = direction === 'prev' ? -1 : 1,
				itemIndex = (activeIndex + delta) % this.product.slider.COUNT;

			return this.eq(this.product.slider.ITEMS, itemIndex);
		},

		getItemIndex: function(item) {
			return BX.util.array_values(this.product.slider.ITEMS).indexOf(item);
		},

		eq: function(obj, i) {
			var len = obj.length,
				j = +i + (i < 0 ? len : 0);

			return j >= 0 && j < len ? obj[j] : {};
		},
		
		checkTopTabsBlockScroll: function() {
			var tabsPanel = this.obTabsBlock,
				tabsPanelHeight = tabsPanel.offsetHeight;
			if(!this.popupPanel) {
				var topPanel = document.querySelector('.top-panel'),
					topPanelHeight = 0,				
					topPanelThead = !!topPanel && topPanel.querySelector('.top-panel__thead'),
					topPanelTfoot = !!topPanel && topPanel.querySelector('.top-panel__tfoot'),				
					tabsPanelContainerTop = BX.pos(this.obTabs).top,					
					scrollTop = BX.GetWindowScrollPos().scrollTop;
				
				if(window.innerWidth < 992) {
					if(!!topPanelThead && !!BX.hasClass(topPanelThead, 'fixed')) {
						topPanelHeight = topPanelThead.offsetHeight;
						if(!!topPanelTfoot && !!BX.hasClass(topPanelTfoot, 'visible'))
							topPanelHeight += topPanelTfoot.offsetHeight;
					}
					
					if(scrollTop + topPanelHeight >= tabsPanelContainerTop) {
						if(!this.tabsPanelFixed) {
							this.tabsPanelFixed = true;
							BX.style(this.obTabs, 'height', tabsPanelHeight + 'px');				
							BX.style(tabsPanel, 'top', topPanelHeight + 'px');	
							BX.addClass(tabsPanel, 'fixed');
						} else {
							if(!this.tabsPanelScrolled && topPanelHeight > 0 && scrollTop < this.lastScrollTop) {
								this.tabsPanelScrolled = true;
								var tabsPanelScrolled = this.tabsPanelScrolled;
								/*new BX.easing({
									duration: 300,
									start: {top: Math.abs(parseInt(BX.style(tabsPanel, 'top'), 10))},
									finish: {top: topPanelHeight},
									transition: BX.easing.transitions.linear,
									step: function(state) {
										if(!!tabsPanelScrolled)
											BX.style(tabsPanel, 'top', state.top + 'px');
									}
								}).animate();*/
							} else if(!!this.tabsPanelScrolled && topPanelHeight > 0 && scrollTop > this.lastScrollTop) {								
								this.tabsPanelScrolled = false;
								/*new BX.easing({
									duration: 300,
									start: {top: Math.abs(parseInt(BX.style(tabsPanel, 'top'), 10))},
									finish: {top: topPanelHeight},
									transition: BX.easing.transitions.linear,
									step: function(state) {
										BX.style(tabsPanel, 'top', state.top + 'px');
									}
								}).animate();*/
							}
						}
					} else if(!!this.tabsPanelFixed && (scrollTop + topPanelHeight < tabsPanelContainerTop)) {
						this.tabsPanelFixed = false;
						this.tabsPanelScrolled = false;
						this.obTabs.removeAttribute('style');
						tabsPanel.removeAttribute('style');
						BX.removeClass(tabsPanel, 'fixed');
					}
				} else {
					if(!!topPanel && !!BX.hasClass(topPanel, 'fixed'))
						topPanelHeight = topPanel.offsetHeight;
					
					/*if(!this.tabsPanelFixed && (scrollTop + topPanelHeight >= tabsPanelContainerTop)) { //Мой код Заменил стандартные табы своими эта хрень которая фиксирует более не нужна
						this.tabsPanelFixed = true;
						BX.style(this.obTabs, 'height', tabsPanelHeight + 'px');
						BX.style(tabsPanel, 'top', topPanelHeight + 'px');
						BX.addClass(tabsPanel, 'fixed');
					} else if(!!this.tabsPanelFixed && (scrollTop + topPanelHeight < tabsPanelContainerTop)) {
						this.tabsPanelFixed = false;
						this.obTabs.removeAttribute('style');
						tabsPanel.removeAttribute('style');
						BX.removeClass(tabsPanel, 'fixed');
					}*/
				}
				this.lastScrollTop = scrollTop;
			} else if(!!this.popupPanelContent) {
				BX.bind(this.popupPanelContent, 'scroll', BX.delegate(function() {
					if(!this.tabsPanelFixed && this.popupPanelContent.scrollTop > 0) {
						this.tabsPanelFixed = true;
						BX.style(this.obTabs, 'height', tabsPanelHeight + 'px');
						//BX.style(tabsPanel, 'top', (!!this.popupPanelTitle ? this.popupPanelTitle.offsetHeight : 0) + 'px');
						//BX.addClass(tabsPanel, 'fixed');
					} else if(!!this.tabsPanelFixed && this.popupPanelContent.scrollTop == 0) {
						this.tabsPanelFixed = false;
						this.obTabs.removeAttribute('style');
						//tabsPanel.removeAttribute('style');
						//BX.removeClass(tabsPanel, 'fixed');
					}
				}, this));
			}
		},

		checkTopTabsBlockResize: function() {
			if(!this.popupPanel && !!BX.hasClass(this.obTabsBlock, 'fixed')) {
				var topPanel = document.querySelector('.top-panel'),
					topPanelHeight = 0,
					topPanelThead = !!topPanel && topPanel.querySelector('.top-panel__thead'),
					topPanelTfoot = !!topPanel && topPanel.querySelector('.top-panel__tfoot');					
				
				if(window.innerWidth < 992) {
					if(!!topPanelThead && !!BX.hasClass(topPanelThead, 'fixed')) {
						topPanelHeight = topPanelThead.offsetHeight;
						if(!!topPanelTfoot && !!BX.hasClass(topPanelTfoot, 'visible'))
							topPanelHeight += topPanelTfoot.offsetHeight;
					}
				} else {
					if(!!topPanel && !!BX.hasClass(topPanel, 'fixed'))
						topPanelHeight = topPanel.offsetHeight;
					this.tabsPanelScrolled = false;
				}
				
				BX.style(this.obTabsBlock, 'top', topPanelHeight + 'px');
			}
		},

		/*checkActiveTabsBlock: function() {
			if(!this.popupPanel) {
				var topPanel = document.querySelector('.top-panel'),
					topPanelHeight = 0,
					topPanelThead = !!topPanel && topPanel.querySelector('.top-panel__thead'),
					topPanelTfoot = !!topPanel && topPanel.querySelector('.top-panel__tfoot'),
					tabsPanel = this.obTabsBlock,
					tabsPanelHeight = 0,				
					containers = this.getEntities(this.obTabContainers, 'tab-container'),
					tabs = this.getEntities(this.obTabs, 'tab'),
					scrollTop = BX.GetWindowScrollPos().scrollTop;

				if(!!containers && !!tabs) {
					if(window.innerWidth < 992) {
						if(!!topPanelThead && !!BX.hasClass(topPanelThead, 'fixed')) {
							topPanelHeight = topPanelThead.offsetHeight;
							if(!!topPanelTfoot && !!BX.hasClass(topPanelTfoot, 'visible'))
								topPanelHeight += topPanelTfoot.offsetHeight;
						}
					} else {
						if(!!topPanel && !!BX.hasClass(topPanel, 'fixed'))
							topPanelHeight = topPanel.offsetHeight;
					}

					if(!!tabsPanel && !!BX.hasClass(tabsPanel, 'fixed'))
						tabsPanelHeight = tabsPanel.offsetHeight;

					var fullScrollTop = scrollTop + topPanelHeight + tabsPanelHeight;
					
					var containersLength = Object.keys(containers).length;
					for(var i in containers) {
						if(containers.hasOwnProperty(i) && BX.type.isDomNode(containers[i])) {
							var containerValue = containers[i].getAttribute('data-value');
							if(containerValue) {
								if(fullScrollTop >= BX.pos(containers[i]).top && fullScrollTop <= BX.pos(containers[containersLength - 1]).bottom) {
									for(var j in tabs) {
										if(tabs.hasOwnProperty(j) && BX.type.isDomNode(tabs[j])) {
											var tabValue = tabs[j].getAttribute('data-value');
											if(tabValue) {
												if(tabValue === containerValue)
													BX.addClass(tabs[j], 'active');
												else
													BX.removeClass(tabs[j], 'active');
											}
										}
									}
								} else if(fullScrollTop > BX.pos(containers[containersLength - 1]).bottom) {
									for(var j in tabs) {
										if(tabs.hasOwnProperty(j) && BX.type.isDomNode(tabs[j]))
											BX.removeClass(tabs[j], 'active');
									}
								}
							}
						}
					}
				}
			} else if(!!this.popupPanelContent) {
				BX.bind(this.popupPanelContent, 'scroll', BX.proxy(this.checkActivePopupPanelTabsBlock, this));
			}
		},*/

		/*checkActivePopupPanelTabsBlock: function(event) {
			var tabsPanel = this.obTabsBlock,
				tabsPanelHeight = 0,
				tabs = this.getEntities(this.obTabs, 'tab'),
				containers = this.getEntities(this.obTabContainers, 'tab-container'),
				containersLength = Object.keys(containers).length;
			
			if(!!tabsPanel && !!BX.hasClass(tabsPanel, 'fixed'))
				tabsPanelHeight = tabsPanel.offsetHeight;

			var fullScrollTop = event.target.scrollTop + tabsPanelHeight;
			
			for(var i in containers) {
				if(containers.hasOwnProperty(i) && BX.type.isDomNode(containers[i])) {
					var containerValue = containers[i].getAttribute('data-value');
					if(containerValue) {
						var containerOffset = containers[i].offsetParent.hasAttribute('data-entity')
							&& containers[i].offsetParent.getAttribute('data-entity') == 'product-container' 
							? (window.innerWidth < 992 ? 18 : 40) + tabsPanelHeight 
							: 0;
						
						if(fullScrollTop >= BX.pos(containers[i], true).top + containerOffset && fullScrollTop <= BX.pos(containers[containersLength - 1], true).bottom + containerOffset) {
							for(var j in tabs) {
								if(tabs.hasOwnProperty(j) && BX.type.isDomNode(tabs[j])) {
									var tabValue = tabs[j].getAttribute('data-value');
									if(tabValue) {
										if(tabValue === containerValue)
											BX.addClass(tabs[j], 'active');
										else
											BX.removeClass(tabs[j], 'active');
									}
								}
							}
						} else if(fullScrollTop > BX.pos(containers[containersLength - 1], true).bottom + containerOffset) {
							for(var j in tabs) {
								if(tabs.hasOwnProperty(j) && BX.type.isDomNode(tabs[j]))
									BX.removeClass(tabs[j], 'active');
							}
						}
					}
				}
			}
		},*/
		
		/*changeTab: function(event) {
			BX.PreventDefault(event);
			
			if(!this.popupPanel) {
				BX.unbind(window, 'scroll', BX.proxy(this.checkActiveTabsBlock, this));
			} else if(!!this.popupPanelContent) {
				BX.unbind(this.popupPanelContent, 'scroll', BX.proxy(this.checkActivePopupPanelTabsBlock, this));
			}
			
			var targetTabValue = BX.proxy_context && BX.proxy_context.getAttribute('data-value'),
				containers, tabs;

			if(!!targetTabValue) {
				containers = this.getEntities(this.obTabContainers, 'tab-container');
				for(var i in containers) {
					if(containers.hasOwnProperty(i) && BX.type.isDomNode(containers[i])) {
						if(containers[i].getAttribute('data-value') === targetTabValue) {
							if(!this.popupPanel) {
								var topPanel = document.querySelector('.top-panel'),
									topPanelHeight = 0,
									topPanelThead = !!topPanel && topPanel.querySelector('.top-panel__thead'),
									topPanelTfoot = !!topPanel && topPanel.querySelector('.top-panel__tfoot'),
									tabContainerTop = BX.pos(containers[i]).top,
									scrollTop = BX.GetWindowScrollPos().scrollTop;

								if(window.innerWidth < 992) {
									if(!!topPanelThead) {
										topPanelHeight = topPanelThead.offsetHeight;
										if(scrollTop + this.obTabsBlock.offsetHeight + topPanelHeight > tabContainerTop) {
											if(!!topPanelTfoot)
												topPanelHeight += topPanelTfoot.offsetHeight;
										}
									}
								} else {
									if(!!topPanel)
										topPanelHeight = topPanel.offsetHeight;
								}
								
								new BX.easing({
									duration: 500,
									start: {scroll: scrollTop},
									finish: {scroll: tabContainerTop - this.obTabsBlock.offsetHeight - topPanelHeight},
									transition: BX.easing.makeEaseOut(BX.easing.transitions.quint),
									step: BX.delegate(function(state) {
										window.scrollTo(0, state.scroll);
									}, this),
									complete: BX.delegate(function() {
										BX.bind(window, 'scroll', BX.proxy(this.checkActiveTabsBlock, this));
									}, this)
								}).animate();
							} else if(!!this.popupPanelContent) {
								var containerOffset = containers[i].offsetParent.hasAttribute('data-entity')
									&& containers[i].offsetParent.getAttribute('data-entity') == 'product-container' 
									? (window.innerWidth < 992 ? 18 : 40) + this.obTabsBlock.offsetHeight 
									: 0;

								new BX.easing({
									duration: 500,
									start: {scroll: this.popupPanelContent.scrollTop},
									finish: {scroll: BX.pos(containers[i], true).top + containerOffset - this.obTabsBlock.offsetHeight},
									transition: BX.easing.makeEaseOut(BX.easing.transitions.quint),
									step: BX.delegate(function(state) {
										this.popupPanelContent.scrollTo(0, state.scroll);
									}, this),
									complete: BX.delegate(function() {
										BX.bind(this.popupPanelContent, 'scroll', BX.proxy(this.checkActivePopupPanelTabsBlock, this));
									}, this)
								}).animate();
							}
						}
					}
				}

				tabs = this.getEntities(this.obTabs, 'tab');
				for(var i in tabs) {
					if(tabs.hasOwnProperty(i) && BX.type.isDomNode(tabs[i])) {
						if(tabs[i].getAttribute('data-value') === targetTabValue)
							BX.addClass(tabs[i], 'active');
						else
							BX.removeClass(tabs[i], 'active');
					}
				}
			}
		},*/

		checkTopPayBlockScroll: function() {
			var tabsPanel = this.obTabsBlock,
				tabsPanelHeight = 0,
				ghostTop = this.obProduct.querySelector('.product-item-detail-ghost-top'),
				ghostBottom = this.obProduct.querySelector('.product-item-detail-ghost-bottom'),
				productContainer = this.getEntity(this.obProduct, 'product-container'),
				payBlock = this.obPayBlock,				
				payBlockHeight = payBlock.offsetHeight - (!!this.obQuickOrder ? this.obQuickOrder.offsetHeight : 0),				
				skuItems = this.obSkuItems;
			
			if(!this.popupPanel) {
				var topPanel = document.querySelector('.top-panel'),
					topPanelHeight = 0,					
					ghostBottomTop = !!ghostBottom && BX.pos(ghostBottom).top,					
					productContainerBottom = !!productContainer && BX.pos(productContainer).bottom,
					payBlockWidth = payBlock.offsetWidth,
					skuItemsBottom = !!skuItems && BX.pos(skuItems).bottom,
					scrollTop = BX.GetWindowScrollPos().scrollTop;
				
				if(!!ghostTop && !!ghostBottomTop && !!productContainerBottom) {
					if(window.innerWidth >= 992) {
						if(productContainerBottom - (!!skuItems ? skuItemsBottom : ghostBottomTop) >= (!this.payBlockFixed ? payBlockHeight + 68 : payBlockHeight)) {
							if(!!topPanel && !!BX.hasClass(topPanel, 'fixed'))
								topPanelHeight = topPanel.offsetHeight;

							if(!!tabsPanel && !!BX.hasClass(tabsPanel, 'fixed'))
								tabsPanelHeight = tabsPanel.offsetHeight;

							var fullScrollTop = scrollTop + topPanelHeight + tabsPanelHeight;
							
							if(fullScrollTop >= (!!skuItems ? skuItemsBottom : ghostBottomTop)) {
								if(!this.payBlockFixed) {
									this.payBlockFixed = true;
									BX.style(ghostTop, 'paddingTop', payBlockHeight + 'px');
									BX.style(payBlock, 'top', topPanelHeight + tabsPanelHeight + 40 + 'px');
									BX.style(payBlock, 'width', payBlockWidth + 'px');
									BX.addClass(payBlock, 'product-item-detail-pay-block-fixed');
								} else {
									if(!this.payBlockHidden && (fullScrollTop > productContainerBottom - payBlockHeight - 40)) {
										this.payBlockHidden = true;
										BX.addClass(this.obPayBlock, 'product-item-detail-pay-block-hidden');
									} else if(!!this.payBlockHidden && (fullScrollTop <= productContainerBottom - payBlockHeight - 40)) {
										this.payBlockHidden = false;
										BX.removeClass(this.obPayBlock, 'product-item-detail-pay-block-hidden');
									}
								}
							} else if(!!this.payBlockFixed && (fullScrollTop < (!!skuItems ? skuItemsBottom : ghostBottomTop))) {
								this.payBlockFixed = false;
								this.payBlockHidden = false;
								ghostTop.removeAttribute('style');
								this.obPayBlock.removeAttribute('style');
								BX.removeClass(this.obPayBlock, 'product-item-detail-pay-block-fixed');
								BX.removeClass(this.obPayBlock, 'product-item-detail-pay-block-hidden');
							}
						}
					}
				}
			} else if(!!this.popupPanelContent) {
				BX.bind(this.popupPanelContent, 'scroll', BX.delegate(function() {
					var ghostBottomTop = !!ghostBottom && BX.pos(ghostBottom, true).top + 40 + tabsPanel.offsetHeight,						
						productContainerBottom = !!productContainer && BX.pos(productContainer, true).bottom,
						payBlockWidth = payBlock.offsetWidth,
						skuItemsBottom = !!skuItems && BX.pos(skuItems, true).bottom;

					if(!!ghostTop && !!ghostBottomTop && !!productContainerBottom) {
						if(window.innerWidth >= 992) {
							if(productContainerBottom - (!!skuItems ? skuItemsBottom : ghostBottomTop) >= (!this.payBlockFixed ? payBlockHeight + 68 : payBlockHeight)) {
								if(!!tabsPanel && !!BX.hasClass(tabsPanel, 'fixed'))
									tabsPanelHeight = tabsPanel.offsetHeight;

								var fullScrollTop = this.popupPanelContent.scrollTop + tabsPanelHeight;
								
								if(fullScrollTop >= (!!skuItems ? skuItemsBottom : ghostBottomTop)) {
									if(!this.payBlockFixed) {
										this.payBlockFixed = true;
										BX.style(ghostTop, 'paddingTop', payBlockHeight + 'px');
										BX.style(payBlock, 'top', (!!this.popupPanelTitle ? this.popupPanelTitle.offsetHeight : 0) + tabsPanelHeight + 40 + 'px');
										BX.style(payBlock, 'width', payBlockWidth + 'px');
										BX.addClass(payBlock, 'product-item-detail-pay-block-fixed');
									} else {
										if(!this.payBlockHidden && (fullScrollTop > productContainerBottom - payBlockHeight - 40)) {
											this.payBlockHidden = true;
											BX.addClass(this.obPayBlock, 'product-item-detail-pay-block-hidden');
										} else if(!!this.payBlockHidden && (fullScrollTop <= productContainerBottom - payBlockHeight - 40)) {
											this.payBlockHidden = false;
											BX.removeClass(this.obPayBlock, 'product-item-detail-pay-block-hidden');
										}
									}
								} else if(!!this.payBlockFixed && (fullScrollTop < (!!skuItems ? skuItemsBottom : ghostBottomTop))) {
									this.payBlockFixed = false;
									this.payBlockHidden = false;
									ghostTop.removeAttribute('style');
									this.obPayBlock.removeAttribute('style');
									BX.removeClass(this.obPayBlock, 'product-item-detail-pay-block-fixed');
									BX.removeClass(this.obPayBlock, 'product-item-detail-pay-block-hidden');
								}
							}
						}
					}
				}, this));
			}
		},

		checkTopPayBlockResize: function() {
			var insertNode = this.obProduct.querySelector('.product-item-detail-blocks'),
				ghostTop = this.obProduct.querySelector('.product-item-detail-ghost-top');
			
			if(window.innerWidth < 992) {
				if(!this.payBlockMoved && !!insertNode) {
					this.payBlockMoved = true;
					BX.prepend(this.obPayBlock, insertNode);
					
					if(!!this.payBlockFixed) {
						this.payBlockFixed = false;
						this.payBlockHidden = false;
						!!ghostTop && ghostTop.removeAttribute('style');
						this.obPayBlock.removeAttribute('style');
						BX.removeClass(this.obPayBlock, 'product-item-detail-pay-block-fixed');
						BX.removeClass(this.obPayBlock, 'product-item-detail-pay-block-hidden');
					}
				}
			} else {
				if(!!this.payBlockMoved && !!ghostTop) {
					this.payBlockMoved = false;
					BX.insertAfter(this.obPayBlock, ghostTop);
					
					this.checkTopPayBlockScroll();
				}
			}
		},

		initPopup: function() {
			if(this.config.usePopup) {
				this.node.videoImageContainer.style.cursor = 'zoom-in';
				BX.bind(this.node.videoImageContainer, 'click', BX.delegate(this.toggleMainPictPopup, this));
				BX.bind(this.node.sliderMagnifier, 'click', BX.delegate(this.toggleMainPictPopup, this));
				BX.bind(document, 'keyup', BX.proxy(this.closeByEscape, this));
				BX.bind(
					this.getEntity(this.obBigSlider, 'close-popup'),
					'click',
					BX.proxy(this.hideMainPictPopup, this)
				);
			}
		},

		checkSliderControls: function(count) {
			var display = count > 1 ? '' : 'none';
			
			this.node.sliderControlLeft && (this.node.sliderControlLeft.style.display = display);
			this.node.sliderControlRight && (this.node.sliderControlRight.style.display = display);
		},

		setCurrentImg: function(img, showImage, showShortCardImage) {
			var videos,
				images,
				videosImages = [],
				i = 0,
				j = 0,
				l;

			this.currentImg.id = img.ID;
			this.currentImg.src = img.SRC;
			this.currentImg.width = img.WIDTH;
			this.currentImg.height = img.HEIGHT;
			this.currentImg.live = img.LIVE ? img.LIVE : false;
			
			if(showImage && this.node.videoImageContainer) { 
				videos = this.getEntities(this.node.videoImageContainer, 'video');
				for(i = 0; i < videos.length; i++) {
					videosImages[j] = videos[i];
					j++
				}
				
				images = this.getEntities(this.node.videoImageContainer, 'image');
				for(i = 0; i < images.length; i++) {
					videosImages[j] = images[i];
					j++
				}
				
				l = videosImages.length;
				while (l--) {
					if(videosImages[l].getAttribute('data-id') == img.ID) {
						if(!BX.hasClass(videosImages[l], 'active')) {
							this.node.sliderProgressBar && this.resetProgress();
						}

						BX.addClass(videosImages[l], 'active');
					} else if(BX.hasClass(videosImages[l], 'active')) {
						BX.removeClass(videosImages[l], 'active');
						var iframe = BX.findChild(videosImages[l], {tagName: 'iframe'}, true, false);
						if(!!iframe)
							iframe.contentWindow.postMessage('{"event": "command", "func": "pauseVideo", "args": ""}', '*');
					}
				}
			}
			
			if(showShortCardImage && this.shortCardNodes.picture)
				this.shortCardNodes.picture.setAttribute('src', this.currentImg.src);
			
			if(this.config.useMagnifier && !this.isTouchDevice && !this.currentImg.live) {
				this.setMagnifierParams();

				if(showImage) {
					this.disableMagnifier(true);
				}
			}
		},
		
		setMagnifierParams: function() {
			var images = this.getEntities(this.node.videoImageContainer, 'image'),
				l = images.length,
				current;

			while(l--) {
				//disable image title show
				current = images[l].querySelector('img');
				if(!!current) {
					current.setAttribute('data-title', current.getAttribute('title') || '');
					current.removeAttribute('title');

					if(images[l].getAttribute('data-id') == this.currentImg.id) {
						BX.unbind(this.currentImg.node, 'mouseover', BX.proxy(this.enableMagnifier, this));

						this.currentImg.node = current;
						this.currentImg.node.style.backgroundImage = 'url(' + this.currentImg.src + ')';
						this.currentImg.node.style.backgroundSize = '100% auto';

						BX.bind(this.currentImg.node, 'mouseover', BX.proxy(this.enableMagnifier, this));
					}
				}
			}
		},

		enableMagnifier: function() {
			BX.bind(document, 'mousemove', BX.proxy(this.moveMagnifierArea, this));
		},

		disableMagnifier: function(animateSize) {
			if(!this.magnify.enabled)
				return;

			clearTimeout(this.magnify.timer);
			BX.removeClass(this.obBigSlider, 'magnified');
			this.magnify.enabled = false;

			this.currentImg.node.style.backgroundSize = '100% auto';
			if(animateSize) {
				//set initial size for css animation
				this.currentImg.node.style.height = this.magnify.height + 'px';
				this.currentImg.node.style.width = this.magnify.width + 'px';

				this.magnify.timer = setTimeout(
					BX.delegate(function(){
						this.currentImg.node.src = this.currentImg.src;
						this.currentImg.node.style.height = '';
						this.currentImg.node.style.width = '';
					}, this),
					250
				);
			} else {
				this.currentImg.node.src = this.currentImg.src;
				this.currentImg.node.style.height = '';
				this.currentImg.node.style.width = '';
			}

			BX.unbind(document, 'mousemove', BX.proxy(this.moveMagnifierArea, this));
		},

		moveMagnifierArea: function(e) {
			var posBigImg = BX.pos(this.currentImg.node),
				currentPos = this.inRect(e, posBigImg);

			if(this.inBound(posBigImg, currentPos)) {
				var posPercentX = (currentPos.X / this.currentImg.node.width) * 100,
					posPercentY = (currentPos.Y / this.currentImg.node.height) * 100,
					resolution, sliderWidth, w, h, zoomPercent;

				this.currentImg.node.style.backgroundPosition = posPercentX + '% ' + posPercentY + '%';

				if(!this.magnify.enabled) {
					clearTimeout(this.magnify.timer);
					BX.addClass(this.obBigSlider, 'magnified');

					//set initial size for css animation
					this.currentImg.node.style.height = (this.magnify.height = this.currentImg.node.clientHeight) + 'px';
					this.currentImg.node.style.width = (this.magnify.width = this.currentImg.node.offsetWidth) + 'px';

					resolution = this.currentImg.width / this.currentImg.height;
					sliderWidth = this.obBigSlider.offsetWidth;

					if(sliderWidth > this.currentImg.width && !BX.hasClass(this.obBigSlider, 'popup')) {
						w = sliderWidth;
						h = w / resolution;
						zoomPercent = 100;
					} else {
						w = this.currentImg.width;
						h = this.currentImg.height;
						zoomPercent = this.config.magnifierZoomPercent > 100 ? this.config.magnifierZoomPercent : 100;
					}

					//base64 transparent pixel
					this.currentImg.node.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQI12P4zwAAAgEBAKrChTYAAAAASUVORK5CYII=';
					this.currentImg.node.style.backgroundSize = zoomPercent + '% auto';

					//set target size
					this.magnify.timer = setTimeout(BX.delegate(function(){
							this.currentImg.node.style.height = h + 'px';
							this.currentImg.node.style.width = w + 'px';
						}, this),
						10
					);
				}

				this.magnify.enabled = true;
			} else {
				this.disableMagnifier(true);
			}
		},

		inBound: function(rect, point) {
			return (
				(point.Y >= 0 && rect.height >= point.Y)
				&& (point.X >= 0 && rect.width >= point.X)
			);
		},

		inRect: function(e, rect) {
			var wndSize = BX.GetWindowSize(),
				currentPos = {
					X: 0,
					Y: 0,
					globalX: 0,
					globalY: 0
				};

			currentPos.globalX = e.clientX + wndSize.scrollLeft;

			if(e.offsetX && e.offsetX < 0) {
				currentPos.globalX -= e.offsetX;
			}

			currentPos.X = currentPos.globalX - rect.left;
			currentPos.globalY = e.clientY + wndSize.scrollTop;

			if(e.offsetY && e.offsetY < 0) {
				currentPos.globalY -= e.offsetY;
			}

			currentPos.Y = currentPos.globalY - rect.top;

			return currentPos;
		},

		setProductMainPict: function(intPict) {
			var indexPict = -1,				
				i = 0,
				j = 0,
				value = '';
			
			if(this.product.sliderCount) {
				for(j = 0; j < this.product.sliderPict.length; j++) {
					if(intPict === this.product.sliderPict[j].ID) {
						indexPict = j;
						break;
					}
				}
				
				if(indexPict > -1) {
					if(this.product.sliderPict[indexPict]) {
						this.setCurrentImg(this.product.sliderPict[indexPict], true);
					}
					
					for(i = 0; i < this.product.slider.ITEMS.length; i++) {
						value = this.product.slider.ITEMS[i].getAttribute('data-value');

						if(value === intPict) {
							BX.addClass(this.product.slider.ITEMS[i], 'active');
						} else if(BX.hasClass(this.product.slider.ITEMS[i], 'active')) {
							BX.removeClass(this.product.slider.ITEMS[i], 'active');
						}
					}
				}
			}
		},
		
		selectSliderImg: function(target) {
			var strValue = '',
				arItem = [];
			target = BX.type.isDomNode(target) ? target : BX.proxy_context;

			if(target && target.hasAttribute('data-value')) {
				strValue = target.getAttribute('data-value');

				if(strValue.indexOf('_') !== -1) {
					arItem = strValue.split('_');
					this.setMainPict(arItem[0], arItem[1]);
				} else {
					this.setProductMainPict(strValue);
				}
			}
		},

		setMainPict: function(intSlider, intPict, shortCardPict) {
			var index = -1,
				indexPict = -1,
				i,
				j,
				value = '',
				strValue = '';

			for(i = 0; i < this.offers.length; i++) {
				if(intSlider === this.offers[i].ID) {
					index = i;
					break;
				}
			}

			if(index > -1) {
				if(this.offers[index].SLIDER_COUNT > 0) {
					for(j = 0; j < this.offers[index].SLIDER.length; j++) {
						if(intPict === this.offers[index].SLIDER[j].ID) {
							indexPict = j;
							break;
						}
					}

					if(indexPict > -1) {
						if(this.offers[index].SLIDER[indexPict]) {
							this.setCurrentImg(this.offers[index].SLIDER[indexPict], true, shortCardPict);
						}

						strValue = intSlider + '_' + intPict;

						for(i = 0; i < this.product.slider.ITEMS.length; i++) {
							value = this.product.slider.ITEMS[i].getAttribute('data-value');

							if(value === strValue) {
								BX.addClass(this.product.slider.ITEMS[i], 'active');
							} else if(BX.hasClass(this.product.slider.ITEMS[i], 'active')) {
								BX.removeClass(this.product.slider.ITEMS[i], 'active');
							}
						}
					}
				}
			}
		},

		setMainPictFromItem: function(index) {
			if(this.node.videoImageContainer) {
				var boolSet = false,
					obNewPict = {};

				if(this.offers[index]) {
					if(this.offers[index].DETAIL_PICTURE) {
						obNewPict = this.offers[index].DETAIL_PICTURE;
						boolSet = true;
					} else if(this.offers[index].PREVIEW_PICTURE) {
						obNewPict = this.offers[index].PREVIEW_PICTURE;
						boolSet = true;
					}
				}

				if(!boolSet) {
					if(this.defaultPict.detail) {
						obNewPict = this.defaultPict.detail;
						boolSet = true;
					} else if(this.defaultPict.preview) {
						obNewPict = this.defaultPict.preview;
						boolSet = true;
					}
				}

				if(boolSet) {
					this.setCurrentImg(obNewPict, true, true);
				}
			}
		},

		toggleMainPictPopup: function() {
			if(BX.hasClass(this.obBigSlider, 'popup')) {
				this.hideMainPictPopup();
			} else {
				this.showMainPictPopup();
			}
		},

		showMainPictPopup: function() {
			this.config.useMagnifier && this.disableMagnifier(false);
			BX.addClass(this.obBigSlider, 'popup');
			this.node.videoImageContainer.style.cursor = '';
			//remove double scroll bar
			document.body.style.overflow = 'hidden';
		},

		hideMainPictPopup: function() {
			this.config.useMagnifier && this.disableMagnifier(false);
			BX.removeClass(this.obBigSlider, 'popup');
			this.node.videoImageContainer.style.cursor = 'zoom-in';
			//remove double scroll bar
			document.body.style.overflow = '';
		},

		closeByEscape: function(event) {
			event = event || window.event;

			if(event.keyCode == 27) {
				this.hideMainPictPopup();
			}
		},

		checkCurrentSkuItem: function(target) {
			this.skuItemContainer = BX.findParent(target, {attrs: {'data-entity': 'sku-item'}});
			if(!!this.skuItemContainer) {
				this.skuItemNum = parseInt(this.skuItemContainer.getAttribute('data-num'));
				this.skuItem = this.offers[this.skuItemNum];
			
				this.obSkuItemCompare = this.skuItemContainer.querySelector('[data-entity="compare"]');
				
				this.obSkuItemPriceCurrent = this.skuItemContainer.querySelector('[data-entity="price-current"]');
				this.obSkuItemPriceOld = this.skuItemContainer.querySelector('[data-entity="price-old"]');
				this.obSkuItemPriceDiscount = this.skuItemContainer.querySelector('[data-entity="price-economy"]');

				this.obSkuItemQuantity = this.skuItemContainer.querySelector('[data-entity="quantity"]');
				this.obSkuItemQuantityUp = this.skuItemContainer.querySelector('[data-entity="quantity-up"]');
				this.obSkuItemQuantityDown = this.skuItemContainer.querySelector('[data-entity="quantity-down"]');
			
				this.obSkuItemPcQuantity = this.skuItemContainer.querySelector('[data-entity="pc-quantity"]');
				this.obSkuItemPcQuantityUp = this.skuItemContainer.querySelector('[data-entity="pc-quantity-up"]');
				this.obSkuItemPcQuantityDown = this.skuItemContainer.querySelector('[data-entity="pc-quantity-down"]');
			
				this.obSkuItemSqMQuantity = this.skuItemContainer.querySelector('[data-entity="sq-m-quantity"]');
				this.obSkuItemSqMQuantityUp = this.skuItemContainer.querySelector('[data-entity="sq-m-quantity-up"]');
				this.obSkuItemSqMQuantityDown = this.skuItemContainer.querySelector('[data-entity="sq-m-quantity-down"]');

				if(BX.util.in_array('BUY', this.config.basketAction)) {
					this.obSkuItemBuyBtn = this.skuItemContainer.querySelector('[data-entity="buy"]');
				}
				if(BX.util.in_array('ADD', this.config.basketAction)) {
					this.obSkuItemAddToBasketBtn = this.skuItemContainer.querySelector('[data-entity="add"]');
				}
				
				this.obSkuItemDelay = this.skuItemContainer.querySelector('[data-entity="delay"]');
			
				this.skuItemCanBuy = this.skuItem.CAN_BUY;			
				this.skuItemCurrentPriceMode = this.skuItem.ITEM_PRICE_MODE;
				this.skuItemCurrentPrices = this.skuItem.ITEM_PRICES;
				this.skuItemCurrentPriceSelected = this.skuItem.ITEM_PRICE_SELECTED;				
				this.skuItemCurrentQuantityRanges = this.skuItem.ITEM_QUANTITY_RANGES;
				this.skuItemCurrentQuantityRangeSelected = this.skuItem.ITEM_QUANTITY_RANGE_SELECTED;				
				this.skuItemCurrentMeasure = this.skuItem.ITEM_MEASURE;
				this.skuItemIsDblQuantity = this.skuItem.QUANTITY_FLOAT;
				this.skuItemCheckQuantity = this.skuItem.CHECK_QUANTITY;

				if(this.skuItemIsDblQuantity) {
					this.skuItemStepQuantity = Math.round(parseFloat(this.skuItem.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor;
					this.skuItemMaxQuantity = parseFloat(this.skuItem.MAX_QUANTITY);
					this.skuItemMinQuantity = this.skuItemCurrentPriceMode === 'Q' ? parseFloat(this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].MIN_QUANTITY) : this.skuItemStepQuantity;
				} else {
					this.skuItemStepQuantity = parseInt(this.skuItem.STEP_QUANTITY, 10);
					this.skuItemMaxQuantity = parseInt(this.skuItem.MAX_QUANTITY, 10);
					this.skuItemMinQuantity = this.skuItemCurrentPriceMode === 'Q' ? parseInt(this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].MIN_QUANTITY) : this.skuItemStepQuantity;
				}
				this.skuItemStepPcQuantity = parseInt(this.skuItem.PC_STEP_QUANTITY, 10);
				this.skuItemMaxPcQuantity = parseInt(this.skuItem.PC_MAX_QUANTITY, 10);
				this.skuItemMinPcQuantity = this.skuItemStepPcQuantity;
				this.skuItemStepSqMQuantity = Math.round(parseFloat(this.skuItem.SQ_M_STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor;
				this.skuItemMaxSqMQuantity = parseFloat(this.skuItem.SQ_M_MAX_QUANTITY);
				this.skuItemMinSqMQuantity = this.skuItemCurrentPriceMode === 'Q' ? parseFloat(this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_MIN_QUANTITY) : this.skuItemStepSqMQuantity;

				if(this.skuItem.OBJECT) {
					this.skuItemObject.id = this.skuItem.OBJECT.ID ? this.skuItem.OBJECT.ID : 0;
					this.skuItemObject.name = this.skuItem.OBJECT.NAME ? this.skuItem.OBJECT.NAME : '';
					this.skuItemObject.address = this.skuItem.OBJECT.ADDRESS ? this.skuItem.OBJECT.ADDRESS : '';
					this.skuItemObject.timezone = this.skuItem.OBJECT.TIMEZONE ? this.skuItem.OBJECT.TIMEZONE : '';
					this.skuItemObject.workingHours = this.skuItem.OBJECT.WORKING_HOURS ? this.skuItem.OBJECT.WORKING_HOURS : {};
					this.skuItemObject.workingHoursToday = this.skuItem.OBJECT.WORKING_HOURS_TODAY ? this.skuItem.OBJECT.WORKING_HOURS_TODAY : {};
					this.skuItemObject.phone = this.skuItem.OBJECT.PHONE.VALUE ? this.skuItem.OBJECT.PHONE.VALUE : {};
					this.skuItemObject.phoneDescription = this.skuItem.OBJECT.PHONE.DESCRIPTION ? this.skuItem.OBJECT.PHONE.DESCRIPTION : {};
					this.skuItemObject.email = this.skuItem.OBJECT.EMAIL.VALUE ? this.skuItem.OBJECT.EMAIL.VALUE : {};
					this.skuItemObject.emailDescription = this.skuItem.OBJECT.EMAIL.DESCRIPTION ? this.skuItem.OBJECT.EMAIL.DESCRIPTION : {};
					this.skuItemObject.skype = this.skuItem.OBJECT.SKYPE.VALUE ? this.skuItem.OBJECT.SKYPE.VALUE : {};
					this.skuItemObject.skypeDescription = this.skuItem.OBJECT.SKYPE.DESCRIPTION ? this.skuItem.OBJECT.SKYPE.DESCRIPTION : {};
					this.skuItemObject.callbackForm = this.skuItem.OBJECT.CALLBACK_FORM ? this.skuItem.OBJECT.CALLBACK_FORM : false;
				}
			}
		},

		quantityUp: function() {
			var curValue = 0,
				curPcValue = 0,
				curSqMValue = 0,
				boolSet = true,
				boolPcSet = true,
				boolSqMSet = true;

			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);
			
			if(this.errorCode === 0 && this.config.showQuantity && ((!this.obSkuItems && this.canBuy) || (!!this.obSkuItems && this.skuItemCanBuy))) {
				if((!this.obSkuItems && this.obQuantity) || (!!this.obSkuItems && this.obSkuItemQuantity)) {
					curValue = ((!this.obSkuItems && this.isDblQuantity) || (!!this.obSkuItems && this.skuItemIsDblQuantity)) ? parseFloat(!this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value) : parseInt(!this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value, 10);
					if(!isNaN(curValue)) {
						curValue += !this.obSkuItems ? this.stepQuantity : this.skuItemStepQuantity;
						if((!this.obSkuItems && this.checkQuantity) || (!!this.obSkuItems && this.skuItemCheckQuantity)) {
							if(curValue > (!this.obSkuItems ? this.maxQuantity : this.skuItemMaxQuantity))
								boolSet = false;
						}

						if(boolSet) {
							if((!this.obSkuItems && this.isDblQuantity) || (!!this.obSkuItems && this.skuItemIsDblQuantity))
								curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
							
							(!this.obSkuItems ? this.obQuantity : this.obSkuItemQuantity).value = curValue;
							
							this.setPrice();
						}
					}
				}
				
				if((!this.obSkuItems && this.obPcQuantity && this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemPcQuantity && this.obSkuItemSqMQuantity)) {
					curPcValue = parseInt(!this.obSkuItems ? this.obPcQuantity.value : this.obSkuItemPcQuantity.value, 10);
					if(!isNaN(curPcValue)) {
						curPcValue += !this.obSkuItems ? this.stepPcQuantity : this.skuItemStepPcQuantity;
						if((!this.obSkuItems && this.checkQuantity) || (!!this.obSkuItems && this.skuItemCheckQuantity)) {
							if(curPcValue > (!this.obSkuItems ? this.maxPcQuantity : this.skuItemMaxPcQuantity))
								boolPcSet = false;
						}
						
						if(boolPcSet)
							(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).value = curPcValue;
					}
					
					curSqMValue = parseFloat(!this.obSkuItems ? this.obSqMQuantity.value : this.obSkuItemSqMQuantity.value);
					if(!isNaN(curSqMValue)) {
						curSqMValue += !this.obSkuItems ? this.stepSqMQuantity : this.skuItemStepSqMQuantity;
						if((!this.obSkuItems && this.checkQuantity) || (!!this.obSkuItems && this.skuItemCheckQuantity)) {
							if(curSqMValue > (!this.obSkuItems ? this.maxSqMQuantity : this.skuItemMaxSqMQuantity))
								boolSqMSet = false;
						}
						
						if(boolSqMSet) {
							curSqMValue = Math.round(curSqMValue * this.precisionFactor) / this.precisionFactor;

							(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).value = curSqMValue;
						}
					}

					if(boolPcSet && boolSqMSet)
						this.setPrice();
				}
			}
		},
			
		quantityDown: function() {
			var curValue = 0,
				curPcValue = 0,
				curSqMValue = 0,
				boolSet = true,
				boolPcSet = true,
				boolSqMSet = true;
			
			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);
			
			if(this.errorCode === 0 && this.config.showQuantity && ((!this.obSkuItems && this.canBuy) || (!!this.obSkuItems && this.skuItemCanBuy))) {
				if((!this.obSkuItems && this.obQuantity) || (!!this.obSkuItems && this.obSkuItemQuantity)) {
					curValue = ((!this.obSkuItems && this.isDblQuantity) || (!!this.obSkuItems && this.skuItemIsDblQuantity)) ? parseFloat(!this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value) : parseInt(!this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value, 10);
					if(!isNaN(curValue)) {
						curValue -= !this.obSkuItems ? this.stepQuantity : this.skuItemStepQuantity;
						
						this.checkPriceRange(curValue);

						if(curValue < (!this.obSkuItems ? this.minQuantity : this.skuItemMinQuantity))
							boolSet = false;
						
						if(boolSet) {
							if((!this.obSkuItems && this.isDblQuantity) || (!!this.obSkuItems && this.skuItemIsDblQuantity))
								curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
							
							(!this.obSkuItems ? this.obQuantity : this.obSkuItemQuantity).value = curValue;

							this.setPrice();
						}
					}
				}
				
				if((!this.obSkuItems && this.obPcQuantity && this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemPcQuantity && this.obSkuItemSqMQuantity)) {
					curPcValue = parseInt(!this.obSkuItems ? this.obPcQuantity.value : this.obSkuItemPcQuantity.value, 10);
					if(!isNaN(curPcValue)) {
						curPcValue -= !this.obSkuItems ? this.stepPcQuantity : this.skuItemStepPcQuantity;

						if((!this.obSkuItems && !this.obQuantity && this.currentPrices[this.currentPriceSelected].SQ_M_PRICE) || (!!this.obSkuItems && this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE))
							this.checkPriceRange(curPcValue);
						
						if(curPcValue < (!this.obSkuItems ? this.minPcQuantity : this.skuItemMinPcQuantity))
							boolPcSet = false;
						
						if(boolPcSet)
							(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).value = curPcValue;
					}
				
					curSqMValue = parseFloat(!this.obSkuItems ? this.obSqMQuantity.value : this.obSkuItemSqMQuantity.value);
					if(!isNaN(curSqMValue)) {
						curSqMValue -= !this.obSkuItems ? this.stepSqMQuantity : this.skuItemStepSqMQuantity;

						if((!this.obSkuItems && !this.obQuantity && !this.currentPrices[this.currentPriceSelected].SQ_M_PRICE) || (!!this.obSkuItems && !this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE))
							this.checkPriceRange(curSqMValue);
						
						if(curSqMValue < (!this.obSkuItems ? this.minSqMQuantity : this.skuItemMinSqMQuantity))
							boolSqMSet = false;
						
						if(boolSqMSet) {
							curSqMValue = Math.round(curSqMValue * this.precisionFactor) / this.precisionFactor;

							(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).value = curSqMValue;
						}
					}

					if(boolPcSet && boolSqMSet)
						this.setPrice();
				}
			}
		},
		
		quantityChange: function() {
			var curValue = 0,
				curPcValue = 0,
				curSqMValue = 0,
				intCount,
				intPcCount,
				intSqMCount;

			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);

			if(this.errorCode === 0 && this.config.showQuantity) {
				if((!this.obSkuItems && this.canBuy) || (!!this.obSkuItems && this.skuItemCanBuy)) {
					curValue = ((!this.obSkuItems && this.isDblQuantity) || (!!this.obSkuItems && this.skuItemIsDblQuantity)) ? parseFloat(!this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value) : Math.round(!this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value);
					if(!isNaN(curValue)) {
						if((!this.obSkuItems && this.checkQuantity) || (!!this.obSkuItems && this.skuItemCheckQuantity)) {
							if(curValue > (!this.obSkuItems ? this.maxQuantity : this.skuItemMaxQuantity))
								curValue = !this.obSkuItems ? this.maxQuantity : this.skuItemMaxQuantity;
						}

						this.checkPriceRange(curValue);

						if(curValue < (!this.obSkuItems ? this.minQuantity : this.skuItemMinQuantity)) {
							curValue = !this.obSkuItems ? this.minQuantity : this.skuItemMinQuantity;
						} else {
							intCount = Math.round(Math.round(curValue * this.precisionFactor / (!this.obSkuItems ? this.stepQuantity : this.skuItemStepQuantity)) / this.precisionFactor) || 1;
							curValue = (intCount <= 1 ? (!this.obSkuItems ? this.stepQuantity : this.skuItemStepQuantity) : intCount * (!this.obSkuItems ? this.stepQuantity : this.skuItemStepQuantity));
							curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
						}

						(!this.obSkuItems ? this.obQuantity : this.obSkuItemQuantity).value = curValue;
					} else {
						(!this.obSkuItems ? this.obQuantity : this.obSkuItemQuantity).value = !this.obSkuItems ? this.minQuantity : this.skuItemMinQuantity;
					}
					
					if(!this.obSkuItems && this.obPcQuantity && this.obSqMQuantity) {
						curPcValue = this.currentMeasure.SYMBOL_INTL == 'pc. 1' ? this.obQuantity.value : Math.round(this.obQuantity.value / this.stepQuantity);
						if(!isNaN(curPcValue)) {
							if(this.checkQuantity) {
								if(curPcValue > this.maxPcQuantity)
									curPcValue = this.maxPcQuantity;
							}
							
							if(curPcValue < this.minPcQuantity) {
								curPcValue = this.minPcQuantity;
							} else {
								intPcCount = Math.round(Math.round(curPcValue * this.precisionFactor / this.stepPcQuantity) / this.precisionFactor) || 1;
								curPcValue = (intPcCount <= 1 ? this.stepPcQuantity : intPcCount * this.stepPcQuantity);
								curPcValue = Math.round(curPcValue * this.precisionFactor) / this.precisionFactor;
							}

							this.obPcQuantity.value = curPcValue;
						} else {
							this.obPcQuantity.value = this.minPcQuantity;
						}
						
						curSqMValue = this.currentMeasure.SYMBOL_INTL == 'm2' ? this.obQuantity.value : parseFloat((this.obQuantity.value * this.stepSqMQuantity) / this.stepQuantity);
						if(!isNaN(curSqMValue)) {
							if(this.checkQuantity) {
								if(curSqMValue > this.maxSqMQuantity)
									curSqMValue = this.maxSqMQuantity;
							}
							
							if(curSqMValue < this.minSqMQuantity) {
								curSqMValue = this.minSqMQuantity;
							} else {
								intSqMCount = Math.round(Math.round(curSqMValue * this.precisionFactor / this.stepSqMQuantity) / this.precisionFactor) || 1;
								curSqMValue = (intSqMCount <= 1 ? this.stepSqMQuantity : intSqMCount * this.stepSqMQuantity);
								curSqMValue = Math.round(curSqMValue * this.precisionFactor) / this.precisionFactor;
							}

							this.obSqMQuantity.value = curSqMValue;
						} else {
							this.obSqMQuantity.value = this.minSqMQuantity;
						}
					}
				} else {
					(!this.obSkuItems ? this.obQuantity : this.obSkuItemQuantity).value = !this.obSkuItems ? this.minQuantity : this.skuItemMinQuantity;
					if(!this.obSkuItems && this.obPcQuantity && this.obSqMQuantity) {
						this.obPcQuantity.value = this.minPcQuantity;
						this.obSqMQuantity.value = this.minSqMQuantity;
					}
				}

				this.setPrice();
			}
		},

		pcQuantityChange: function() {
			var curPcValue = 0,
				curSqMValue = 0,
				curValue = 0,
				intPcCount,
				intSqMCount,
				intCount;

			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);

			if(this.errorCode === 0 && this.config.showQuantity) {
				if((!this.obSkuItems && this.canBuy) || (!!this.obSkuItems && this.skuItemCanBuy)) {
					curPcValue = Math.round(!this.obSkuItems ? this.obPcQuantity.value : this.obSkuItemPcQuantity.value);
					if(!isNaN(curPcValue)) {
						if((!this.obSkuItems && this.checkQuantity) || (!!this.obSkuItems && this.skuItemCheckQuantity)) {
							if(curPcValue > (!this.obSkuItems ? this.maxPcQuantity : this.skuItemMaxPcQuantity))
								curPcValue = !this.obSkuItems ? this.maxPcQuantity : this.skuItemMaxPcQuantity;
						}
						
						if((!this.obSkuItems && !this.obQuantity && this.currentPrices[this.currentPriceSelected].SQ_M_PRICE) || (!!this.obSkuItems && this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE))
							this.checkPriceRange(curPcValue);

						if(curPcValue < (!this.obSkuItems ? this.minPcQuantity : this.skuItemMinPcQuantity)) {
							curPcValue = !this.obSkuItems ? this.minPcQuantity : this.skuItemMinPcQuantity;
						} else {
							intPcCount = Math.round(Math.round(curPcValue * this.precisionFactor / (!this.obSkuItems ? this.stepPcQuantity : this.skuItemStepPcQuantity)) / this.precisionFactor) || 1;
							curPcValue = (intPcCount <= 1 ? (!this.obSkuItems ? this.stepPcQuantity : this.skuItemStepPcQuantity) : intPcCount * (!this.obSkuItems ? this.stepPcQuantity : this.skuItemStepPcQuantity));
							curPcValue = Math.round(curPcValue * this.precisionFactor) / this.precisionFactor;
						}
						
						(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).value = curPcValue;
					} else {
						(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).value = !this.obSkuItems ? this.minPcQuantity : this.skuItemMinPcQuantity;
					}

					if((!this.obSkuItems && this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemSqMQuantity)) {
						curSqMValue = !this.obSkuItems ? parseFloat(this.obPcQuantity.value * this.stepSqMQuantity) : parseFloat(this.obSkuItemPcQuantity.value * this.skuItemStepSqMQuantity);
						if(!isNaN(curSqMValue)) {
							if((!this.obSkuItems && this.checkQuantity) || (!!this.obSkuItems && this.skuItemCheckQuantity)) {
								if(curSqMValue > (!this.obSkuItems ? this.maxSqMQuantity : this.skuItemMaxSqMQuantity))
									curSqMValue = !this.obSkuItems ? this.maxSqMQuantity : this.skuItemMaxSqMQuantity;
							}

							if((!this.obSkuItems && !this.obQuantity && !this.currentPrices[this.currentPriceSelected].SQ_M_PRICE) || (!!this.obSkuItems && !this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE))
								this.checkPriceRange(curSqMValue);
							
							if(curSqMValue < (!this.obSkuItems ? this.minSqMQuantity : this.skuItemMinSqMQuantity)) {
								curSqMValue = !this.obSkuItems ? this.minSqMQuantity : this.skuItemMinSqMQuantity;
							} else {
								intSqMCount = Math.round(Math.round(curSqMValue * this.precisionFactor / (!this.obSkuItems ? this.stepSqMQuantity : this.skuItemStepSqMQuantity)) / this.precisionFactor) || 1;
								curSqMValue = (intSqMCount <= 1 ? (!this.obSkuItems ? this.stepSqMQuantity : this.skuItemStepSqMQuantity) : intSqMCount * (!this.obSkuItems ? this.stepSqMQuantity : this.skuItemStepSqMQuantity));
								curSqMValue = Math.round(curSqMValue * this.precisionFactor) / this.precisionFactor;
							}

							(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).value = curSqMValue;
						} else {
							(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).value = !this.obSkuItems ? this.minSqMQuantity : this.skuItemMinSqMQuantity;
						}
					}

					if(!this.obSkuItems && this.obQuantity) {
						curValue = this.isDblQuantity ? parseFloat(this.currentPrices[this.currentPriceSelected].SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value) 
							: Math.round(this.currentPrices[this.currentPriceSelected].SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value);
						if(!isNaN(curValue)) {
							if(this.checkQuantity) {
								if(curValue > this.maxQuantity)
									curValue = this.maxQuantity;
							}

							this.checkPriceRange(curValue);

							if(curValue < this.minQuantity) {
								curValue = this.minQuantity;
							} else {
								intCount = Math.round(Math.round(curValue * this.precisionFactor / this.stepQuantity) / this.precisionFactor) || 1;
								curValue = (intCount <= 1 ? this.stepQuantity : intCount * this.stepQuantity);
								curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
							}

							this.obQuantity.value = curValue;
						} else {
							this.obQuantity.value = this.minQuantity;
						}
					}
				} else {
					(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).value = !this.obSkuItems ? this.minPcQuantity : this.skuItemMinPcQuantity;
					if((!this.obSkuItems && this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemSqMQuantity))
						(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).value = !this.obSkuItems ? this.minSqMQuantity : this.skuItemMinSqMQuantity;
					if(!this.obSkuItems && this.obQuantity)
						this.obQuantity.value = this.minQuantity;
				}

				this.setPrice();
			}
		},

		sqMQuantityChange: function() {
			var curSqMValue = 0,
				curPcValue = 0,
				curValue = 0,
				intSqMCount,
				intPcCount,
				intCount;

			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);

			if(this.errorCode === 0 && this.config.showQuantity) {
				if((!this.obSkuItems && this.canBuy) || (!!this.obSkuItems && this.skuItemCanBuy)) {
					curSqMValue = parseFloat(!this.obSkuItems ? this.obSqMQuantity.value : this.obSkuItemSqMQuantity.value);
					if(!isNaN(curSqMValue)) {
						if((!this.obSkuItems && this.checkQuantity) || (!!this.obSkuItems && this.skuItemCheckQuantity)) {
							if(curSqMValue > (!this.obSkuItems ? this.maxSqMQuantity : this.skuItemMaxSqMQuantity))
								curSqMValue = !this.obSkuItems ? this.maxSqMQuantity : this.skuItemMaxSqMQuantity;
						}

						if((!this.obSkuItems && !this.obQuantity && !this.currentPrices[this.currentPriceSelected].SQ_M_PRICE) || (!!this.obSkuItems && !this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE))
							this.checkPriceRange(curSqMValue);
						
						if(curSqMValue < (!this.obSkuItems ? this.minSqMQuantity : this.skuItemMinSqMQuantity)) {
							curSqMValue = !this.obSkuItems ? this.minSqMQuantity : this.skuItemMinSqMQuantity;
						} else {
							intSqMCount = Math.round(Math.round(curSqMValue * this.precisionFactor / (!this.obSkuItems ? this.stepSqMQuantity : this.skuItemStepSqMQuantity)) / this.precisionFactor) || 1;
							curSqMValue = (intSqMCount <= 1 ? (!this.obSkuItems ? this.stepSqMQuantity : this.skuItemStepSqMQuantity) : intSqMCount * (!this.obSkuItems ? this.stepSqMQuantity : this.skuItemStepSqMQuantity));
							curSqMValue = Math.round(curSqMValue * this.precisionFactor) / this.precisionFactor;
						}

						(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).value = curSqMValue;
					} else {
						(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).value = !this.obSkuItems ? this.minSqMQuantity : this.skuItemMinSqMQuantity;
					}
					
					if((!this.obSkuItems && this.obPcQuantity) || (!!this.obSkuItems && this.obSkuItemPcQuantity)) {
						curPcValue = !this.obSkuItems ? Math.round((this.obSqMQuantity.value * this.stepPcQuantity) / this.stepSqMQuantity) : Math.round((this.obSkuItemSqMQuantity.value * this.skuItemStepPcQuantity) / this.skuItemStepSqMQuantity);
						if(!isNaN(curPcValue)) {
							if((!this.obSkuItems && this.checkQuantity) || (!!this.obSkuItems && this.skuItemCheckQuantity)) {
								if(curPcValue > (!this.obSkuItems ? this.maxPcQuantity : this.skuItemMaxPcQuantity))
									curPcValue = !this.obSkuItems ? this.maxPcQuantity : this.skuItemMaxPcQuantity;
							}

							if((!this.obSkuItems && !this.obQuantity && this.currentPrices[this.currentPriceSelected].SQ_M_PRICE) || (!!this.obSkuItems && this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE))
								this.checkPriceRange(curPcValue);
							
							if(curPcValue < (!this.obSkuItems ? this.minPcQuantity : this.skuItemMinPcQuantity)) {
								curPcValue = !this.obSkuItems ? this.minPcQuantity : this.skuItemMinPcQuantity;
							} else {
								intPcCount = Math.round(Math.round(curPcValue * this.precisionFactor / (!this.obSkuItems ? this.stepPcQuantity : this.skuItemStepPcQuantity)) / this.precisionFactor) || 1;
								curPcValue = (intPcCount <= 1 ? (!this.obSkuItems ? this.stepPcQuantity : this.skuItemStepPcQuantity) : intPcCount * (!this.obSkuItems ? this.stepPcQuantity : this.skuItemStepPcQuantity));
								curPcValue = Math.round(curPcValue * this.precisionFactor) / this.precisionFactor;
							}

							(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).value = curPcValue;
						} else {
							(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).value = !this.obSkuItems ? this.minPcQuantity : this.skuItemMinPcQuantity;
						}
					}

					if(!this.obSkuItems && this.obQuantity) {
						curValue = this.isDblQuantity ? parseFloat(this.currentPrices[this.currentPriceSelected].SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value) 
							: Math.round(this.currentPrices[this.currentPriceSelected].SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value);
						if(!isNaN(curValue)) {
							if(this.checkQuantity) {
								if(curValue > this.maxQuantity)
									curValue = this.maxQuantity;
							}

							this.checkPriceRange(curValue);

							if(curValue < this.minQuantity) {
								curValue = this.minQuantity;
							} else {
								intCount = Math.round(Math.round(curValue * this.precisionFactor / this.stepQuantity) / this.precisionFactor) || 1;
								curValue = (intCount <= 1 ? this.stepQuantity : intCount * this.stepQuantity);
								curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
							}

							this.obQuantity.value = curValue;
						} else {
							this.obQuantity.value = this.minQuantity;
						}
					}
				} else {
					(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).value = !this.obSkuItems ? this.minSqMQuantity : this.skuItemMinSqMQuantity;
					if((!this.obSkuItems && this.obPcQuantity) || (!!this.obSkuItems && this.obSkuItemPcQuantity))
						(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).value = !this.obSkuItems ? this.minPcQuantity : this.skuItemMinPcQuantity;
					if(!this.obSkuItems && this.obQuantity)
						this.obQuantity.value = this.minQuantity;
				}

				this.setPrice();
			}
		},

		quantityProgress: function(quant){
			var active = 0, mess = 'Нет в наличии', result = '';
			if(quant >= Number(this.exQuantProgress.EX_MESS_RELATIVE_QUANTITY_VERY_FEW) && quant < Number(this.exQuantProgress.EX_MESS_RELATIVE_QUANTITY_FEW)){
				active = 1;mess = 'Очень мало';
			}
			else if(quant >= Number(this.exQuantProgress.EX_MESS_RELATIVE_QUANTITY_FEW) && quant < Number(this.exQuantProgress.EX_MESS_RELATIVE_QUANTITY_ENOUGH)){
				active = 2;mess = 'Мало';
			}
			else if(quant >= Number(this.exQuantProgress.EX_MESS_RELATIVE_QUANTITY_ENOUGH) && quant < Number(this.exQuantProgress.EX_MESS_RELATIVE_QUANTITY_MANY)){
				active = 3;mess = 'Достаточно';
			}
			else if(quant >= Number(this.exQuantProgress.EX_MESS_RELATIVE_QUANTITY_MANY) && quant < Number(this.exQuantProgress.EX_MESS_RELATIVE_QUANTITY_VERY_MANY)){
				active = 4;mess = 'Много';
			}
			else if(quant >= Number(this.exQuantProgress.EX_MESS_RELATIVE_QUANTITY_VERY_MANY)){
				active = 5;mess = 'Очень много';
			}
			result += '<div class="own-progress">'
			for (var i = 1; i <= 5; i++) {
				if(active != 0){
					active--;
					result += '<div class="own-pr active"><span></span></div>'
				}
				else{
					result += '<div class="own-pr"><span></span></div>'
				}
			}
			result += mess + '</div>';
			BX.adjust(BX(this.visual.QUANTITY_PROGRESS), {html: result});
		},

		quantitySet: function(index) {
			var strLimit, resetQuantity, resetPcQuantity, resetSqMQuantity;
			
			var newOffer = this.offers[index],
				oldOffer = this.offers[this.offerNum];

			if(this.errorCode === 0) {
				this.canBuy = newOffer.CAN_BUY;

				this.currentPriceMode = newOffer.ITEM_PRICE_MODE;
				this.currentPrices = newOffer.ITEM_PRICES;
				this.currentPriceSelected = newOffer.ITEM_PRICE_SELECTED;
				this.currentQuantityRanges = newOffer.ITEM_QUANTITY_RANGES;
				this.currentQuantityRangeSelected = newOffer.ITEM_QUANTITY_RANGE_SELECTED;
				this.currentMeasure = newOffer.ITEM_MEASURE;

				var price = this.currentPrices[this.currentPriceSelected],
					partnersUrl = newOffer.PARTNERS_URL;

				this.quantityProgress(Number(newOffer.MAX_QUANTITY)); //Мой код функция выводит много мало

				if(this.canBuy) {
					if(price && price.PRICE > 0) {
						if(!partnersUrl) {
							this.obDelay && BX.style(this.obDelay, 'display', '');
							this.obBuyBtn && BX.adjust(this.obBuyBtn, {props: {disabled: false}, style: {display: ''}});
							this.obAddToBasketBtn && BX.adjust(this.obAddToBasketBtn, {props: {disabled: false}, style: {display: ''}});
							this.obPartnersBtn && BX.style(this.obPartnersBtn, 'display', 'none');
							this.obPartnersMess && BX.style(this.obPartnersMess, 'display', 'none');
							if(this.obQuickOrder) {
								BX.style(this.obQuickOrder, 'display', '');								
								var quickOrderProductId = this.obQuickOrder.querySelector('[name="PRODUCT_ID"]');
								if(!!quickOrderProductId)
									quickOrderProductId.value = newOffer.ID;
							}
						} else {
							this.obDelay && BX.style(this.obDelay, 'display', 'none');
							this.obBuyBtn && BX.style(this.obBuyBtn, 'display', 'none');
							this.obAddToBasketBtn && BX.style(this.obAddToBasketBtn, 'display', 'none');
							this.obPartnersBtn && BX.adjust(this.obPartnersBtn, {props: {disabled: false}, style: {display: ''}});
							this.obPartnersMess && BX.style(this.obPartnersMess, 'display', '');
							this.obQuickOrder && BX.style(this.obQuickOrder, 'display', 'none');
						}
						this.node.quantity && BX.style(this.node.quantity, 'display', '');
						this.obAskPrice && BX.style(this.obAskPrice, 'display', 'none');
					} else {
						if(!partnersUrl) {
							this.obBuyBtn && BX.adjust(this.obBuyBtn, {props: {disabled: true}, style: {display: ''}});
							this.obAddToBasketBtn && BX.adjust(this.obAddToBasketBtn, {props: {disabled: true}, style: {display: ''}});
							this.obPartnersBtn && BX.style(this.obPartnersBtn, 'display', 'none');
						} else {
							this.obBuyBtn && BX.style(this.obBuyBtn, 'display', 'none');
							this.obAddToBasketBtn && BX.style(this.obAddToBasketBtn, 'display', 'none');
							this.obPartnersBtn && BX.adjust(this.obPartnersBtn, {props: {disabled: true}, style: {display: ''}});
						}
						this.obDelay && BX.style(this.obDelay, 'display', 'none');
						this.node.quantity && BX.style(this.node.quantity, 'display', 'none');
						this.obPartnersMess && BX.style(this.obPartnersMess, 'display', 'none');
						this.obAskPrice && BX.style(this.obAskPrice, 'display', '');
						this.obQuickOrder && BX.style(this.obQuickOrder, 'display', 'none');
					}
					this.obNotAvail && BX.style(this.obNotAvail, 'display', 'none');
					this.obSubscribe && BX.style(this.obSubscribe, 'display', 'none');
				} else {
					if(!partnersUrl) {
						this.obBuyBtn && BX.adjust(this.obBuyBtn, {props: {disabled: true}, style: {display: ''}});
						this.obAddToBasketBtn && BX.adjust(this.obAddToBasketBtn, {props: {disabled: true}, style: {display: ''}});
						this.obPartnersBtn && BX.style(this.obPartnersBtn, 'display', 'none');
					} else {
						this.obBuyBtn && BX.style(this.obBuyBtn, 'display', 'none');
						this.obAddToBasketBtn && BX.style(this.obAddToBasketBtn, 'display', 'none');
						this.obPartnersBtn && BX.adjust(this.obPartnersBtn, {props: {disabled: true}, style: {display: ''}});
					}					
					this.obDelay && BX.style(this.obDelay, 'display', 'none');
					this.node.quantity && BX.style(this.node.quantity, 'display', 'none');
					this.obPartnersMess && BX.style(this.obPartnersMess, 'display', 'none');
					this.obAskPrice && BX.style(this.obAskPrice, 'display', 'none');
					this.obNotAvail && BX.style(this.obNotAvail, 'display', '');
					this.obQuickOrder && BX.style(this.obQuickOrder, 'display', 'none');
					if(this.obSubscribe) {
						if(newOffer.CATALOG_SUBSCRIBE === 'Y') {
							BX.style(this.obSubscribe, 'display', '');
							this.obSubscribe.setAttribute('data-item', newOffer.ID);
							BX(this.visual.SUBSCRIBE_LINK + '_hidden').click();
						} else {
							BX.style(this.obSubscribe, 'display', 'none');
						}
					}
				}

				this.isDblQuantity = newOffer.QUANTITY_FLOAT;
				this.checkQuantity = newOffer.CHECK_QUANTITY;

				if(this.isDblQuantity) {
					this.stepQuantity = Math.round(parseFloat(newOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor;
					this.maxQuantity = parseFloat(newOffer.MAX_QUANTITY);
					this.minQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
				} else {
					this.stepQuantity = parseInt(newOffer.STEP_QUANTITY, 10);
					this.maxQuantity = parseInt(newOffer.MAX_QUANTITY, 10);
					this.minQuantity = this.currentPriceMode === 'Q' ? parseInt(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
				}
				this.stepPcQuantity = parseInt(newOffer.PC_STEP_QUANTITY, 10);
				this.maxPcQuantity = parseInt(newOffer.PC_MAX_QUANTITY, 10);
				this.minPcQuantity = this.stepPcQuantity;
				this.stepSqMQuantity = Math.round(parseFloat(newOffer.SQ_M_STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor;
				this.maxSqMQuantity = parseFloat(newOffer.SQ_M_MAX_QUANTITY);
				this.minSqMQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].SQ_M_MIN_QUANTITY) : this.stepSqMQuantity;
				
				if(this.config.showQuantity) {
					if(this.obQuantity) {
						var isDifferentMinQuantity = oldOffer.ITEM_PRICES.length
							&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED]
							&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED].MIN_QUANTITY != this.minQuantity;

						if(this.isDblQuantity) {
							resetQuantity = Math.round(parseFloat(oldOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor !== this.stepQuantity
								|| isDifferentMinQuantity
								|| oldOffer.MEASURE !== newOffer.MEASURE
								|| (
									this.checkQuantity
									&& parseFloat(oldOffer.MAX_QUANTITY) > this.maxQuantity
									&& parseFloat(this.obQuantity.value) > this.maxQuantity
								);
						} else {
							resetQuantity = parseInt(oldOffer.STEP_QUANTITY, 10) !== this.stepQuantity
								|| isDifferentMinQuantity
								|| oldOffer.MEASURE !== newOffer.MEASURE
								|| (
									this.checkQuantity
									&& parseInt(oldOffer.MAX_QUANTITY, 10) > this.maxQuantity
									&& parseInt(this.obQuantity.value, 10) > this.maxQuantity
								);
						}

						this.obQuantity.disabled = !this.canBuy;

						if(resetQuantity) {
							this.obQuantity.value = this.minQuantity;
						}
					}

					if(this.obMeasure) {
						if(newOffer.MEASURE) {
							BX.adjust(this.obMeasure, {html: newOffer.MEASURE});
						} else {
							BX.adjust(this.obMeasure, {html: ''});
						}
					}
					
					if(this.obPriceMeasure) {
						if(newOffer.MEASURE) {
							BX.adjust(this.obPriceMeasure, {html: '/' + newOffer.MEASURE});
						} else {
							BX.adjust(this.obPriceMeasure, {html: ''});
						}
					}
					
					if(this.obPcQuantity && this.obSqMQuantity) {
						if(this.currentMeasure.SYMBOL_INTL == 'pc. 1' || this.currentMeasure.SYMBOL_INTL == 'm2') {
							if(price.SQ_M_PRICE) {
								var isDifferentMinPcQuantity = oldOffer.ITEM_PRICES.length
									&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED]
									&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED].PC_MIN_QUANTITY != this.minPcQuantity;

								resetPcQuantity = parseInt(oldOffer.PC_STEP_QUANTITY, 10) !== this.stepPcQuantity
									|| isDifferentMinPcQuantity
									|| oldOffer.MEASURE !== newOffer.MEASURE
									|| (
										this.checkQuantity
										&& parseInt(oldOffer.PC_MAX_QUANTITY, 10) > this.maxPcQuantity
										&& parseInt(this.obPcQuantity.value, 10) > this.maxPcQuantity
									);
							} else {
								var isDifferentMinSqMQuantity = oldOffer.ITEM_PRICES.length
									&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED]
									&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED].SQ_M_MIN_QUANTITY != this.minSqMQuantity;

								resetSqMQuantity = Math.round(parseFloat(oldOffer.SQ_M_STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor !== this.stepSqMQuantity
									|| isDifferentMinSqMQuantity
									|| oldOffer.MEASURE !== newOffer.MEASURE
									|| (
										this.checkQuantity
										&& parseFloat(oldOffer.SQ_M_MAX_QUANTITY) > this.maxSqMQuantity
										&& parseFloat(this.obSqMQuantity.value) > this.maxSqMQuantity
									);
							}

							this.obPcQuantity.disabled = !this.canBuy;
							this.obSqMQuantity.disabled = !this.canBuy;

							if(resetPcQuantity || resetSqMQuantity) {
								this.obPcQuantity.value = this.minPcQuantity;
								this.obSqMQuantity.value = this.minSqMQuantity;
							}
							
							if(this.obPriceMeasure)
								BX.adjust(this.obPriceMeasure, {html: '/' + BX.message('CATALOG_ELEMENT_SQ_M_MESSAGE')});
							
							BX.style(this.obPcQuantity.parentNode, 'display', '');
							BX.style(this.obSqMQuantity.parentNode, 'display', '');
							BX.style(this.obQuantity.parentNode, 'display', 'none');
						} else {
							BX.style(this.obPcQuantity.parentNode, 'display', 'none');
							BX.style(this.obSqMQuantity.parentNode, 'display', 'none');
							BX.style(this.obQuantity.parentNode, 'display', '');
						}
					}
				}
				
				if(this.obQuantityLimit.all && this.obQuantityLimitNotAvl.all) {					
					if(this.canBuy) {
						if(!this.checkQuantity) {
							BX.adjust(this.obQuantityLimit.value, {html: ''});												
						} else {
							if(this.config.showMaxQuantity === 'M') {
								strLimit = (this.maxQuantity / this.stepQuantity >= this.config.relativeQuantityFactor)
									? BX.message('CATALOG_ELEMENT_RELATIVE_QUANTITY_MANY')
									: BX.message('CATALOG_ELEMENT_RELATIVE_QUANTITY_FEW');
							} else {
								strLimit = this.maxQuantity;
							}
							BX.adjust(this.obQuantityLimit.value, {html: strLimit});							
						}
						BX.adjust(this.obQuantityLimit.all, {style: {display: ''}});
						BX.adjust(this.obQuantityLimitNotAvl.all, {style: {display: 'none'}});
					} else {
						BX.adjust(this.obQuantityLimit.value, {html: ''});
						BX.adjust(this.obQuantityLimit.all, {style: {display: 'none'}});
						BX.adjust(this.obQuantityLimitNotAvl.all, {style: {display: ''}});
					}
				}

				if(this.config.usePriceRanges && this.obPriceRanges) {
					if(this.currentPriceMode === 'Q' && newOffer.PRICE_RANGES_HTML) {
						var rangesBody = this.getEntity(this.obPriceRanges, 'price-ranges-body');

						if(rangesBody) {
							rangesBody.innerHTML = newOffer.PRICE_RANGES_HTML;
						}
						
						this.obPriceRanges.style.display = '';
					} else {
						this.obPriceRanges.style.display = 'none';
					}

				}
			}
		},

		selectOfferProp: function(element) {
			var i = 0,
				strTreeValue = '',
				arTreeItem = [],
				lineContainer = null,
				rowItems = null,
				target = this.offersView == 'DROPDOWN_LIST' ? element : BX.proxy_context;
			
			if(target && target.hasAttribute('data-treevalue')) {
				if(BX.hasClass(target, 'selected'))
					return;

				if(typeof document.activeElement === 'object') {
					document.activeElement.blur();
				}

				strTreeValue = target.getAttribute('data-treevalue');
				arTreeItem = strTreeValue.split('_');
				this.searchOfferPropIndex(arTreeItem[0], arTreeItem[1]);
				
				lineContainer = BX.findParent(target, {attribute: {'data-entity': 'sku-line-block'}});
				rowItems = lineContainer && BX.findChildren(lineContainer, {tagName: 'li'}, true);
				if(rowItems && rowItems.length) {
					for(i = 0; i < rowItems.length; i++) {
						BX.removeClass(rowItems[i], 'selected');
					}
				}

				BX.addClass(target, 'selected');
				
				if(this.offersView == 'DROPDOWN_LIST') {
					var lineContainers = this.getEntities(this.obTree, 'sku-line-block');
					for(var k in lineContainers) {
						if(lineContainers.hasOwnProperty(k) && BX.type.isDomNode(lineContainers[k])) {
							var selectedItem = lineContainers[k].querySelector('.selected');
							if(!!selectedItem) {
								var currentOption = lineContainers[k].querySelector('[data-entity="current-option"]');
								if(!!currentOption)
									currentOption.innerHTML = selectedItem.innerHTML;
							}
						}
					}
				}
			}
		},

		searchOfferPropIndex: function(strPropID, strPropValue) {
			var strName = '',
				arShowValues = false,
				arCanBuyValues = [],
				allValues = [],
				index = -1,
				i, j,
				arFilter = {},
				tmpFilter = [];

			for(i = 0; i < this.treeProps.length; i++) {
				if(this.treeProps[i].ID === strPropID) {
					index = i;
					break;
				}
			}

			if(index > -1) {
				for(i = 0; i < index; i++) {
					strName = 'PROP_' + this.treeProps[i].ID;
					arFilter[strName] = this.selectedValues[strName];
				}

				strName = 'PROP_' + this.treeProps[index].ID;
				arFilter[strName] = strPropValue;

				for(i = index + 1; i < this.treeProps.length; i++) {
					strName = 'PROP_' + this.treeProps[i].ID;
					arShowValues = this.getRowValues(arFilter, strName);

					if(!arShowValues)
						break;

					allValues = [];

					if(this.config.showAbsent) {
						arCanBuyValues = [];
						tmpFilter = [];
						tmpFilter = BX.clone(arFilter, true);

						for(j = 0; j < arShowValues.length; j++) {
							tmpFilter[strName] = arShowValues[j];
							allValues[allValues.length] = arShowValues[j];
							if(this.getCanBuy(tmpFilter))
								arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					} else {
						arCanBuyValues = arShowValues;
					}

					if(this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues)) {
						arFilter[strName] = this.selectedValues[strName];
					} else {
						if(this.config.showAbsent) {
							arFilter[strName] = (arCanBuyValues.length ? arCanBuyValues[0] : allValues[0]);
						} else {
							arFilter[strName] = arCanBuyValues[0];
						}
					}

					this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
				}
				
				this.selectedValues = arFilter;
				this.changeInfo();
			}
		},

		updateRow: function(intNumber, activeId, showId, canBuyId) {
			var i = 0,
				value = '',
				isCurrent = false,
				rowItems = null;

			var lineContainer = this.getEntities(this.obTree, 'sku-line-block');

			if(intNumber > -1 && intNumber < lineContainer.length) {
				rowItems = lineContainer[intNumber].querySelectorAll('li');
				for(i = 0; i < rowItems.length; i++) {
					value = rowItems[i].getAttribute('data-onevalue');
					isCurrent = value === activeId;

					if(isCurrent) {
						BX.addClass(rowItems[i], 'selected');
					} else {
						BX.removeClass(rowItems[i], 'selected');
					}

					if(BX.util.in_array(value, canBuyId)) {
						BX.removeClass(rowItems[i], 'notallowed');
					} else {
						BX.addClass(rowItems[i], 'notallowed');
					}

					rowItems[i].style.display = BX.util.in_array(value, showId) ? '' : 'none';

					if(isCurrent) {
						lineContainer[intNumber].style.display = (value == 0 && canBuyId.length == 1) ? 'none' : '';
					}
				}
			}
		},

		getRowValues: function(arFilter, index) {
			var arValues = [],
				i = 0,
				j = 0,
				boolSearch = false,
				boolOneSearch = true;

			if(arFilter.length === 0) {
				for(i = 0; i < this.offers.length; i++) {
					if(!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
						arValues[arValues.length] = this.offers[i].TREE[index];
					}
				}
				boolSearch = true;
			} else {
				for(i = 0; i < this.offers.length; i++) {
					boolOneSearch = true;

					for(j in arFilter) {
						if(arFilter[j] !== this.offers[i].TREE[j]) {
							boolOneSearch = false;
							break;
						}
					}

					if(boolOneSearch) {
						if(!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
							arValues[arValues.length] = this.offers[i].TREE[index];
						}

						boolSearch = true;
					}
				}
			}

			return (boolSearch ? arValues : false);
		},

		getCanBuy: function(arFilter) {
			var i,
				j = 0,
				boolOneSearch = true,
				boolSearch = false;

			for(i = 0; i < this.offers.length; i++) {
				boolOneSearch = true;

				for(j in arFilter) {
					if(arFilter[j] !== this.offers[i].TREE[j]) {
						boolOneSearch = false;
						break;
					}
				}

				if(boolOneSearch) {
					if(this.offers[i].CAN_BUY) {
						boolSearch = true;
						break;
					}
				}
			}

			return boolSearch;
		},

		setCurrent: function() {
			var i,
				j = 0,
				strName = '',
				arShowValues = false,
				arCanBuyValues = [],
				arFilter = {},
				tmpFilter = [],
				current = this.offers[this.offerNum].TREE;

			for(i = 0; i < this.treeProps.length; i++) {
				strName = 'PROP_' + this.treeProps[i].ID;
				arShowValues = this.getRowValues(arFilter, strName);

				if(!arShowValues)
					break;

				if(BX.util.in_array(current[strName], arShowValues)) {
					arFilter[strName] = current[strName];
				} else {
					arFilter[strName] = arShowValues[0];
					this.offerNum = 0;
				}

				if(this.config.showAbsent) {
					arCanBuyValues = [];
					tmpFilter = [];
					tmpFilter = BX.clone(arFilter, true);

					for(j = 0; j < arShowValues.length; j++) {
						tmpFilter[strName] = arShowValues[j];

						if(this.getCanBuy(tmpFilter)) {
							arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					}
				} else {
					arCanBuyValues = arShowValues;
				}

				this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
			}
			
			this.selectedValues = arFilter;
			this.changeInfo();

			if(this.offersView == 'DROPDOWN_LIST') {
				var lineContainers = this.getEntities(this.obTree, 'sku-line-block');
				for(var k in lineContainers) {
					if(lineContainers.hasOwnProperty(k) && BX.type.isDomNode(lineContainers[k])) {
						var selectedItem = lineContainers[k].querySelector('.selected');
						if(!!selectedItem) {
							var currentOption = lineContainers[k].querySelector('[data-entity="current-option"]');
							if(!!currentOption)
								currentOption.innerHTML = selectedItem.innerHTML;
						}
					}
				}
			}
		},

		changeInfo: function() {
			var index = -1,
				j = 0,
				boolOneSearch = true,
				ojBlock = BX('oj-block'),
				eventData = {
					currentId: (this.offerNum > -1 ? this.offers[this.offerNum].ID : 0),
					newId: 0
				};

			var i, offerGroupNode;

			for(i = 0; i < this.offers.length; i++) {
				boolOneSearch = true;

				for(j in this.selectedValues) {
					if(this.selectedValues[j] !== this.offers[i].TREE[j]) {
						boolOneSearch = false;
						break;
					}
				}

				if(boolOneSearch) {
					index = i;
					break;
				}
			}

			if(index > -1) {
				this.drawImages(this.offers[index].SLIDER);
				this.checkSliderControls(this.offers[index].SLIDER_COUNT);

				for(i = 0; i < this.offers.length; i++) {
					if(this.slider.controls[i].ID) {
						if(i === index) {
							this.product.slider = this.slider.controls[i];
							this.slider.controls[i].CONT && BX.show(this.slider.controls[i].CONT);
						} else {
							this.slider.controls[i].CONT && BX.hide(this.slider.controls[i].CONT);
						}
					} else if(i === index) {
						this.product.slider = {};
					}
				}

				if(this.offers[index].SLIDER_COUNT > 0) {					
					j = 0;
					for(i = 0; i < this.offers[index].SLIDER.length; i++) {
						if(!!this.offers[index].SLIDER[i].VALUE && this.offers[index].SLIDER[i].VALUE != '')
							j++;
					}
					this.setMainPict(this.offers[index].ID, this.offers[index].SLIDER[j].ID, true);
				} else {
					this.setMainPictFromItem(index);
				}

				if(this.offers[index].SLIDER_COUNT > 1) {
					this.initSlider();
				} else {
					this.stopSlider();
				}

				if(this.obArticle.all) {
					BX.adjust(this.obArticle.value, {html: this.offers[index].ARTICLE});
				}

				if(this.config.showSkuProps) {
					if(this.obSkuProps) {
						var skuProps = this.obSkuProps.querySelectorAll('[data-entity="sku-props"]');
						for(var i in skuProps) {
							if(skuProps.hasOwnProperty(i)) {
								this.obSkuProps.removeChild(skuProps[i]);
							}
						}
						if(this.offers[index].DISPLAY_PROPERTIES) {
							BX.adjust(this.obSkuProps, {
								html: this.obSkuProps.innerHTML + this.offers[index].DISPLAY_PROPERTIES
							});
						}
					}

					if(this.obMainSkuProps) {
						var mainSkuProps = this.obMainSkuProps.querySelectorAll('[data-entity="sku-props"]');
						for(var i in mainSkuProps) {
							if(mainSkuProps.hasOwnProperty(i)) {
								this.obMainSkuProps.removeChild(mainSkuProps[i]);
							}
						}
						if(this.offers[index].DISPLAY_PROPERTIES_MAIN_BLOCK) {
							BX.adjust(this.obMainSkuProps, {
								html: this.obMainSkuProps.innerHTML + this.offers[index].DISPLAY_PROPERTIES_MAIN_BLOCK
							});
						}
					}

					if(this.obSkuPropsSecond) {
						var skuPropsSecond = this.obSkuPropsSecond.querySelectorAll('[data-entity="sku-props"]');
						for(var i in skuPropsSecond) {
							if(skuPropsSecond.hasOwnProperty(i)) {
								this.obSkuPropsSecond.removeChild(skuProps[i]);
							}
						}
						if(this.offers[index].DISPLAY_PROPERTIES) {
							BX.adjust(this.obSkuPropsSecond, {
								html: this.obSkuPropsSecond.innerHTML + this.offers[index].DISPLAY_PROPERTIES
							});
						}
					}
					if(this.obMainSkuPropsSecond) {
						var mainSkuPropsSecond = this.obMainSkuPropsSecond.querySelectorAll('[data-entity="sku-props"]');
						for(var i in mainSkuPropsSecond) {
							if(mainSkuPropsSecond.hasOwnProperty(i)) {
								this.obMainSkuPropsSecond.removeChild(mainSkuPropsSecond[i]);
							}
						}
						if(this.offers[index].DISPLAY_PROPERTIES_MAIN_BLOCK) {
							BX.adjust(this.obMainSkuPropsSecond, {
								html: this.obMainSkuPropsSecond.innerHTML + this.offers[index].DISPLAY_PROPERTIES_MAIN_BLOCK
							});
						}
					}
				}

				if(this.offers[index].RECEIPT_DATE){ //Мой код выводим дату поступления Ожидается информация
					if(!this.offers[index].CAN_BUY){
						BX.adjust(ojBlock, {style: {'display': 'block'}});
						BX.adjust(ojBlock.querySelector('.oj-data'), {text: 'Поступление: ' + this.offers[index].RECEIPT_DATE});
					}
					else{
						BX.adjust(ojBlock, {style: {'display': 'none'}});
					}
				}
				else{
					if(!this.offers[index].CAN_BUY){
						BX.adjust(ojBlock, {style: {'display': 'block'}});
						BX.adjust(ojBlock.querySelector('.oj-data'), {text: 'Ожидается информация'});
					}
					else{
						BX.adjust(ojBlock, {style: {'display': 'none'}});
					}
				}

				this.quantitySet(index);
				this.setPrice();				
				this.setDelayed(this.offers[index].DELAYED);
				this.setBuyedAdded(this.offers[index].BUYED_ADDED);
				this.setCompared(this.offers[index].COMPARED);
				this.setConstructor(index);
				
				this.offerNum = index;
				this.geoDelivery();
				this.setAnalyticsDataLayer('showDetail');
				this.incViewedCounter();

				eventData.newId = this.offers[this.offerNum].ID;
				//only for compatible catalog.store.amount custom templates
				BX.onCustomEvent('onCatalogStoreProductChange', [this.offers[this.offerNum].ID]);
				//new event
				BX.onCustomEvent('onCatalogElementChangeOffer', eventData);
				eventData = null;
			}
		},

		drawImages: function(images) {
			if(!this.node.videoImageContainer)
				return;

			var i, j = 0, img, entities = this.getEntities(this.node.videoImageContainer, 'image');
			for(i in entities) {
				if(entities.hasOwnProperty(i) && BX.type.isDomNode(entities[i])) {
					BX.remove(entities[i]);
				}
			}

			for(i = 0; i < images.length; i++) {
				if(!!images[i].VALUE && images[i].VALUE != '') {
					j++;
				} else {

					if(images[i].LIVE) {
						this.idVideo = images[i].PARAMS.ID_VIDEO;
						this.nameLive = images[i].PARAMS.NAME;
						this.countWatch = images[i].PARAMS.LIVE_COUNT;
						img = BX.create('DIV', {
							props: {
								className: 'block_by_live_image'
							},
							children: [
								BX.create('IMG', {
									props: {
										src: images[i].SRC,
										alt: this.config.alt,
										title: this.config.title
									}
								})
							]
						});
						BX.bind(BX(img), 'click', BX.proxy(this.openFrameLiveVideo_elem, this));
					}else{
						img = BX.create('IMG', {
							props: {
								src: images[i].SRC,
								alt: this.config.alt,
								title: this.config.title
							}
						})
					}

					if(i == j) {
						img.setAttribute('itemprop', 'image');
					}

					this.node.videoImageContainer.appendChild(
						BX.create('DIV', {
							attrs: {
								'data-entity': 'image',
								'data-id': images[i].ID
							},
							props: {
								className: 'product-item-detail-slider-image' + (i == j ? ' active' : '')
							},
							children: [img]
						})
					);
				}
			}
		},
		openFrameLiveVideo_elem: function(e){
			if($(e.target).parent().hasClass('active') || $(e.target).parent().parent().hasClass('active')){
				var _this = this;
				this.popupLive = BX.PopupWindowManager.create("live-video-popup", null, {
					content: BX.create('div', {
						attrs:{
							className: 'live-main-block-frame'
						},
						children: [
							BX.create('DIV', {
								props: {
									id: this.coverFrameBlock
								},
								style:{
									position: 'absolute',
									'min-width': '100%',
									'min-height': '100%',
								},
							}),
							BX.create('DIV', {
								props: {
									id: 'live-video-popup-content'
								}
							}),
							BX.create('DIV', {
								attrs:{
									id: 'live-line-progress'
								},
								style:{
									width: window.screen.width < 991 ? window.screen.width - 80 + 'px' : window.screen.height - this.coeffPlayer - this.coeffProgress + 'px'
								}
							}),
							BX.create('DIV', {
								attrs: {
									id: 'live-progress-viewed'
								},
								children:[
									BX.create('DIV', {
										attrs:{
											id: 'live-progress-viewed-duration'
										},
										text: '0:00'
									})
								]
							}),
							BX.create('DIV', {
								attrs: {
									id: 'live-progress-hidden'
								},
								style:{
									width: window.screen.width < 991 ? window.screen.width - 80 + 'px' : window.screen.height - this.coeffPlayer - this.coeffProgress + 'px'
								},
								events: {
									click: BX.proxy(this.progress, this)
								}
							})
							/*BX.create('DIV', {
								attrs: {
									id: 'live-name-video-player'
								},
								//text: this.nameLive
							})*/
							/*BX.create('DIV', {
								attrs: {
									className: 'align-items-center live-count-detail-video-player d-none d-lg-flex'
								},
								text: this.countWatch
							})*/
						]
					}),
					width: window.screen.width < 991 ? window.screen.width - 20 : window.screen.height - this.coeffPopup,
					height: window.screen.width < 991 ? window.screen.width - 10 : window.screen.height - this.coeffPopup,
					zIndex: 100,
					closeIcon: {
						opacity: 1
					},
					//titleBar: this.nameLive,
					closeByEsc: true, // закрытие окна по esc
					darkMode: true, // окно будет светлым или темным
					autoHide: true, // закрытие при клике вне окна
					draggable: true, // можно двигать или нет
					resizable: false, // можно ресайзить
					min_height: window.screen.height, // минимальная высота окна
					min_width: window.screen.height, // минимальная ширина окна
					lightShadow: false, // использовать светлую тень у окна
					angle: false, // появится уголок
					overlay: {
						backgroundColor: 'black',
						opacity: 500
					},
					events: {
						onPopupShow: function () {
							$(this.popupContainer).css('width', window.screen.width < 991 ? window.screen.width - 20 : window.screen.height - _this.coeffPopup);
							$(this.popupContainer).css('height', window.screen.width < 991 ? window.screen.width - 10 : window.screen.height - _this.coeffPopup);
							$('#live-progress-hidden').css('width', window.screen.width < 991 ? window.screen.width - 80 + 'px' : window.screen.height - _this.coeffPlayer - _this.coeffProgress + 'px');
							$('#live-line-progress').css('width', window.screen.width < 991 ? window.screen.width - 80 + 'px' : window.screen.height - _this.coeffPlayer - _this.coeffProgress + 'px');
							$('#live-video-popup .popup-window-close-icon').css('top', '-30px');
							BX.delegate(_this.YT_Player_YouTube_elem('popupShow'), _this);
						},
						onPopupClose: function () {
							BX.delegate(_this.YT_Player_YouTube_elem('popupClose'), _this);
						}
					}
				});
				this.popupLive.setAnimation('fading-slide');
				this.popupLive.show();
			}
		},
		YT_Player_YouTube_elem: function(action){

			this.width = window.screen.width < 991 ? window.screen.width - 40 : window.screen.height - this.coeffPlayer;
			this.height = window.screen.width < 991 ? window.screen.width - 40 : window.screen.height - this.coeffPlayer;

			switch (this.statusVideo) {
				case -1: //-1 Воспроизведение не начато
					if(action == 'popupShow') //Запуск видео только после открытия окна
						this.onYouTubeIframeAPIReady_elem();
					break;
				case 0: //0 Воспроизведение завершено
					this.player.setSize(this.width, this.height);
					if(action == 'popupShow' || action == 'coverPopupShow') { //Запуск видео только после открытия окна или по клику на cover
						this.player.setPlaybackQuality('hd1080');
						this.player.playVideo();
					}
					break;
				case 1: //1 Воспроизведение
					if(action == 'popupClose' || action == 'coverPopupShow') { //Ставим на паузу только после закрытия или по клику на cover
						this.player.pauseVideo();
						this.pauseProgress = true;
					}
					break;
				case 2: //2 Пауза
					this.player.setSize(this.width, this.height);
					if(action == 'popupShow' || action == 'coverPopupShow') { //Запуск видео только после открытия окна или по клику на cover
						this.player.setPlaybackQuality('hd1080');
						this.player.playVideo();
					}
					break;
				default:
					console.log("Ошибка");
			}
		},
		onYouTubeIframeAPIReady_elem: function(){
			var _this = this;
			this.player = new YT.Player('live-video-popup-content', {
				height: window.screen.width < 991 ? window.screen.width - 40 : window.screen.height - this.coeffPlayer,
				width: window.screen.width < 991 ? window.screen.width - 40 : window.screen.height - this.coeffPlayer,
				videoId: this.idVideo,
				playerVars: {
					'autoplay': 1,
					'disablekb': 1,
					'controls': 0,
					'enablejsapi': 1,
					'iv_load_policy': 3,
					'fs': 0,
					'modestbranding': 0,
					'rel': 0,
					'showinfo': 0,
					'loop': 1,
					'playlist': [this.idVideo],
					'origin': location.hostname
				},
				events: {
					'onReady': BX.delegate(_this.onPlayerReady, _this),
					'onStateChange': BX.delegate(_this.onStateChangePlayer, _this),
					'onError': BX.delegate(_this.onError, _this)
				}
			});
		},
		onPlayerReady: function (event){
			event.target.setVolume(100);
			event.target.playVideo();
			BX.bind(BX(this.coverFrameBlock), 'click', BX.delegate(this.clickCover, this));
			this.timeUpdateInterval = setInterval(function () {
				if(!this.pauseProgress)
					this.JCCatalogElement.prototype.updateProgressBar(event);
			}, 1000);
		},
		clickCover: function(){
			this.YT_Player_YouTube_elem('coverPopupShow')
		},
		onError: function(event){
			if(event.data === "150" || event.data === "101"){ //Не всегда с первого раза запускается, необходимо пересоздать плеер
				event.target.destroy();
				this.beforeShutdown++;
				if(this.beforeShutdown < this.countError)
					this.onYouTubeIframeAPIReady_elem();
				else{
					this.popupLive.close();
					this.beforeShutdown = 0;
				}
			}
		},
		updateProgressBar: function(event){
			var line_width = $('#live-line-progress').width();
			var persent = (event.target.getCurrentTime() / event.target.getDuration());
			var minutes = Math.floor(Math.floor(event.target.getCurrentTime()) / 60) - (Math.floor(Math.floor(event.target.getCurrentTime()) / 60 / 60) * 60);
			var seconds = Math.floor(event.target.getCurrentTime()) % 60 < 10 ? ':0'+ Math.floor(event.target.getCurrentTime()) % 60 : ':' + Math.floor(event.target.getCurrentTime()) % 60;
			$('#live-progress-viewed').css('width', persent * line_width);
			$('#live-progress-viewed-duration').text(minutes + '' + seconds);

		},
		progress: function(event){
			var line_width = $('#live-line-progress').width();
			var persent = (this.player.getCurrentTime() / this.player.getDuration());
			var minutes = Math.floor(Math.floor(this.player.getCurrentTime()) / 60) - (Math.floor(Math.floor(this.player.getCurrentTime()) / 60 / 60) * 60);
			var seconds = Math.floor(this.player.getCurrentTime()) % 60 < 10 ? ':0'+ Math.floor(this.player.getCurrentTime()) % 60 : ':' + Math.floor(this.player.getCurrentTime()) % 60;
			$('#live-progress-viewed').css('width', persent * line_width);
			$('#live-progress-viewed-duration').text(minutes + '' + seconds);

			this.player.seekTo(this.player.getDuration() * ((event.pageX - $('#live-line-progress').offset().left) / $('#live-line-progress').width()));
		},
		onStateChangePlayer: function (event){ //Событие реагирующее на действия с видео
			this.statusVideo = event.data;
		},
		checkPriceRange: function(quantity) {
			if(typeof quantity === 'undefined' || ((!this.obSkuItems && this.currentPriceMode != 'Q') || (!!this.obSkuItems && this.skuItemCurrentPriceMode != 'Q')))
				return;

			var range, found = false;

			if(!this.obSkuItems) {
				for(var i in this.currentQuantityRanges) {
					if(this.currentQuantityRanges.hasOwnProperty(i)) {
						range = this.currentQuantityRanges[i];

						if(parseInt(quantity) >= parseInt(range.SORT_FROM) && (range.SORT_TO == 'INF' || parseInt(quantity) <= parseInt(range.SORT_TO))) {
							found = true;
							this.currentQuantityRangeSelected = range.HASH;
							break;
						}
					}
				}
			} else {
				for(var i in this.skuItemCurrentQuantityRanges) {
					if(this.skuItemCurrentQuantityRanges.hasOwnProperty(i)) {
						range = this.skuItemCurrentQuantityRanges[i];

						if(parseInt(quantity) >= parseInt(range.SORT_FROM) && (range.SORT_TO == 'INF' || parseInt(quantity) <= parseInt(range.SORT_TO))) {
							found = true;
							this.skuItemCurrentQuantityRangeSelected = range.HASH;
							break;
						}
					}
				}
			}

			if(!found && (range = this.getMinPriceRange())) {
				!this.obSkuItems ? this.currentQuantityRangeSelected = range.HASH : this.skuItemCurrentQuantityRangeSelected = range.HASH;
			}

			if(!this.obSkuItems) {
				for(var k in this.currentPrices) {
					if(this.currentPrices.hasOwnProperty(k)) {
						if(this.currentPrices[k].QUANTITY_HASH == this.currentQuantityRangeSelected) {
							this.currentPriceSelected = k;
							break;
						}
					}
				}
			} else {
				for(var k in this.skuItemCurrentPrices) {
					if(this.skuItemCurrentPrices.hasOwnProperty(k)) {
						if(this.skuItemCurrentPrices[k].QUANTITY_HASH == this.skuItemCurrentQuantityRangeSelected) {
							this.skuItemCurrentPriceSelected = k;
							break;
						}
					}
				}
			}
		},

		getMinPriceRange: function() {
			var range;

			if(!this.obSkuItems) {
				for(var i in this.currentQuantityRanges) {
					if(this.currentQuantityRanges.hasOwnProperty(i)) {
						if(!range || parseInt(this.currentQuantityRanges[i].SORT_FROM) < parseInt(range.SORT_FROM)) {
							range = this.currentQuantityRanges[i];
						}
					}
				}
			} else {
				for(var i in this.skuItemCurrentQuantityRanges) {
					if(this.skuItemCurrentQuantityRanges.hasOwnProperty(i)) {
						if(!range || parseInt(this.skuItemCurrentQuantityRanges[i].SORT_FROM) < parseInt(range.SORT_FROM)) {
							range = this.skuItemCurrentQuantityRanges[i];
						}
					}
				}
			}

			return range;
		},

		checkQuantityControls: function() {
			if((!this.obSkuItems && this.obQuantity) || (!!this.obSkuItems && this.obSkuItemQuantity)) {
				var reachedTopLimit = !this.obSkuItems ? (this.checkQuantity && parseFloat(this.obQuantity.value) + this.stepQuantity > this.maxQuantity) : (this.skuItemCheckQuantity && parseFloat(this.obSkuItemQuantity.value) + this.skuItemStepQuantity > this.skuItemMaxQuantity),
					reachedBottomLimit = !this.obSkuItems ? (parseFloat(this.obQuantity.value) - this.stepQuantity < this.minQuantity) : (parseFloat(this.obSkuItemQuantity.value) - this.skuItemStepQuantity < this.skuItemMinQuantity);

				if(reachedTopLimit) {
					BX.addClass(!this.obSkuItems ? this.obQuantityUp : this.obSkuItemQuantityUp, 'product-item-detail-amount-btn-disabled');
				} else if(BX.hasClass(!this.obSkuItems ? this.obQuantityUp : this.obSkuItemQuantityUp, 'product-item-detail-amount-btn-disabled')) {
					BX.removeClass(!this.obSkuItems ? this.obQuantityUp : this.obSkuItemQuantityUp, 'product-item-detail-amount-btn-disabled');
				}

				if(reachedBottomLimit) {
					BX.addClass(!this.obSkuItems ? this.obQuantityDown : this.obSkuItemQuantityDown, 'product-item-detail-amount-btn-disabled');
				} else if(BX.hasClass(!this.obSkuItems ? this.obQuantityDown : this.obSkuItemQuantityDown, 'product-item-detail-amount-btn-disabled')) {
					BX.removeClass(!this.obSkuItems ? this.obQuantityDown : this.obSkuItemQuantityDown, 'product-item-detail-amount-btn-disabled');
				}

				if(reachedTopLimit && reachedBottomLimit) {
					(!this.obSkuItems ? this.obQuantity : this.obSkuItemQuantity).setAttribute('disabled', 'disabled');
				} else {
					(!this.obSkuItems ? this.obQuantity : this.obSkuItemQuantity).removeAttribute('disabled');
				}
			}
			
			if((!this.obSkuItems && this.obPcQuantity && this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemPcQuantity && this.obSkuItemSqMQuantity)) {
				var reachedPcTopLimit = !this.obSkuItems ? (this.checkQuantity && parseFloat(this.obPcQuantity.value) + this.stepPcQuantity > this.maxPcQuantity) : (this.skuItemCheckQuantity && parseFloat(this.obSkuItemPcQuantity.value) + this.skuItemStepPcQuantity > this.skuItemMaxPcQuantity),
					reachedPcBottomLimit = !this.obSkuItems ? (parseFloat(this.obPcQuantity.value) - this.stepPcQuantity < this.minPcQuantity) : (parseFloat(this.obSkuItemPcQuantity.value) - this.skuItemStepPcQuantity < this.skuItemMinPcQuantity);

				if(reachedPcTopLimit) {
					BX.addClass(!this.obSkuItems ? this.obPcQuantityUp : this.obSkuItemPcQuantityUp, 'product-item-detail-amount-btn-disabled');
				} else if(BX.hasClass(!this.obSkuItems ? this.obPcQuantityUp : this.obSkuItemPcQuantityUp, 'product-item-detail-amount-btn-disabled')) {
					BX.removeClass(!this.obSkuItems ? this.obPcQuantityUp : this.obSkuItemPcQuantityUp, 'product-item-detail-amount-btn-disabled');
				}

				if(reachedPcBottomLimit) {
					BX.addClass(!this.obSkuItems ? this.obPcQuantityDown : this.obSkuItemPcQuantityDown, 'product-item-detail-amount-btn-disabled');
				} else if(BX.hasClass(!this.obSkuItems ? this.obPcQuantityDown : this.obSkuItemPcQuantityDown, 'product-item-detail-amount-btn-disabled')) {
					BX.removeClass(!this.obSkuItems ? this.obPcQuantityDown : this.obSkuItemPcQuantityDown, 'product-item-detail-amount-btn-disabled');
				}

				if(reachedPcTopLimit && reachedPcBottomLimit) {
					(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).setAttribute('disabled', 'disabled');
				} else {
					(!this.obSkuItems ? this.obPcQuantity : this.obSkuItemPcQuantity).removeAttribute('disabled');
				}
			
				var reachedSqMTopLimit = !this.obSkuItems ? (this.checkQuantity && parseFloat(this.obSqMQuantity.value) + this.stepSqMQuantity > this.maxSqMQuantity) : (this.skuItemCheckQuantity && parseFloat(this.obSkuItemSqMQuantity.value) + this.skuItemStepSqMQuantity > this.skuItemMaxSqMQuantity),
					reachedSqMBottomLimit = !this.obSkuItems ? (parseFloat(this.obSqMQuantity.value) - this.stepSqMQuantity < this.minSqMQuantity) : (parseFloat(this.obSkuItemSqMQuantity.value) - this.skuItemStepSqMQuantity < this.skuItemMinSqMQuantity);

				if(reachedSqMTopLimit) {
					BX.addClass(!this.obSkuItems ? this.obSqMQuantityUp : this.obSkuItemSqMQuantityUp, 'product-item-detail-amount-btn-disabled');
				} else if(BX.hasClass(!this.obSkuItems ? this.obSqMQuantityUp : this.obSkuItemSqMQuantityUp, 'product-item-detail-amount-btn-disabled')) {
					BX.removeClass(!this.obSkuItems ? this.obSqMQuantityUp : this.obSkuItemSqMQuantityUp, 'product-item-detail-amount-btn-disabled');
				}

				if(reachedSqMBottomLimit) {
					BX.addClass(!this.obSkuItems ? this.obSqMQuantityDown : this.obSkuItemSqMQuantityDown, 'product-item-detail-amount-btn-disabled');
				} else if(BX.hasClass(!this.obSkuItems ? this.obSqMQuantityDown : this.obSkuItemSqMQuantityDown, 'product-item-detail-amount-btn-disabled')) {
					BX.removeClass(!this.obSkuItems ? this.obSqMQuantityDown : this.obSkuItemSqMQuantityDown, 'product-item-detail-amount-btn-disabled');
				}

				if(reachedSqMTopLimit && reachedSqMBottomLimit) {
					(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).setAttribute('disabled', 'disabled');
				} else {
					(!this.obSkuItems ? this.obSqMQuantity : this.obSkuItemSqMQuantity).removeAttribute('disabled');
				}
			}
		},

		setPrice: function() {
			var economyInfo = '',
				price;

			if((!this.obSkuItems && this.obQuantity && !this.obPcQuantity && !this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemQuantity && !this.obSkuItemPcQuantity && !this.obSkuItemSqMQuantity)) {
				this.checkPriceRange(!this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value);
			} else if((!this.obSkuItems && this.obPcQuantity && this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemPcQuantity && this.obSkuItemSqMQuantity)) {
				if(!this.obSkuItems) {
					if(this.currentMeasure.SYMBOL_INTL == 'pc. 1' || this.currentMeasure.SYMBOL_INTL == 'm2') {
						this.checkPriceRange(this.currentPrices[this.currentPriceSelected].SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value);
					} else {
						this.checkPriceRange(this.obQuantity.value);
					}
				} else {
					this.checkPriceRange(this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE ? this.obSkuItemPcQuantity.value : this.obSkuItemSqMQuantity.value);
				}
			}
			
			this.checkQuantityControls();
			
			price = !this.obSkuItems ? this.currentPrices[this.currentPriceSelected] : this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected];
			
			if((!this.obSkuItems && this.obPrice) || !!this.obSkuItems) {
				if(price) {
					if((!this.obSkuItems && this.obPriceCurrent) || (!!this.obSkuItems && this.obSkuItemPriceCurrent)) {
						if(price.SQ_M_PRICE) {
							BX.adjust(!this.obSkuItems ? this.obPriceCurrent : this.obSkuItemPriceCurrent, {
								html: BX.Currency.currencyFormat(price.SQ_M_PRICE, price.CURRENCY, true),
								style: {display: !this.obSkuItems ? (price.SQ_M_PRICE > 0 ? '' : 'none') : ''}
							});
						} else {
							BX.adjust(!this.obSkuItems ? this.obPriceCurrent : this.obSkuItemPriceCurrent, {
								html: BX.Currency.currencyFormat(price.PRICE, price.CURRENCY, true),
								style: {display: !this.obSkuItems ? (price.PRICE > 0 ? '' : 'none') : ''}
							});
						}
					}
					if(this.obPriceNotSet) {
						if(price.SQ_M_PRICE)
							BX.adjust(this.obPriceNotSet, {style: {display: price.SQ_M_PRICE > 0 ? 'none' : ''}});
						else
							BX.adjust(this.obPriceNotSet, {style: {display: price.PRICE > 0 ? 'none' : ''}});
					}
				} else {
					if(this.obPriceCurrent)
						BX.adjust(this.obPriceCurrent, {html: '', style: {display: 'none'}});
					if(this.obPriceNotSet)
						BX.adjust(this.obPriceNotSet, {style: {display: 'none'}});
				}
				
				if(price && price.PRICE !== price.BASE_PRICE) {
					if(this.config.showOldPrice) {
						(!this.obSkuItems ? this.obPriceOld : this.obSkuItemPriceOld) && BX.adjust(!this.obSkuItems ? this.obPriceOld : this.obSkuItemPriceOld, {
							style: {display: ''},
							html: BX.Currency.currencyFormat(price.SQ_M_BASE_PRICE ? price.SQ_M_BASE_PRICE : price.BASE_PRICE, price.CURRENCY, true)
						});
						
						if((!this.obSkuItems && this.obPriceDiscount) || (!!this.obSkuItems && this.obSkuItemPriceDiscount)) {
							economyInfo = BX.message('CATALOG_ELEMENT_ECONOMY_INFO_MESSAGE');
							economyInfo = economyInfo.replace('#ECONOMY#', BX.Currency.currencyFormat(price.SQ_M_DISCOUNT ? price.SQ_M_DISCOUNT : price.DISCOUNT, price.CURRENCY, true));
							BX.adjust(!this.obSkuItems ? this.obPriceDiscount : this.obSkuItemPriceDiscount, {style: {display: ''}, html: economyInfo});
						}
					}

					if(this.config.showPercent) {
						this.obPricePercent && BX.removeClass(this.obPricePercent, 'product-item-detail-marker-container-hidden');
						this.obPricePercentVal && BX.adjust(this.obPricePercentVal, {html: -price.PERCENT + '%'});
					}
				} else {
					if(this.config.showOldPrice) {
						this.obPriceOld && BX.adjust(this.obPriceOld, {style: {display: 'none'}, html: ''});						
						this.obPriceDiscount && BX.adjust(this.obPriceDiscount, {style: {display: 'none'}, html: ''});
					}

					if(this.config.showPercent) {
						this.obPricePercent && BX.addClass(this.obPricePercent, 'product-item-detail-marker-container-hidden');
						this.obPricePercentVal && BX.adjust(this.obPricePercentVal, {html: ''});
					}
				}
			}
			
			if(this.obTotalCost) {
				if(this.obQuantity && !this.obPcQuantity && !this.obSqMQuantity) {
					if(price && price.PRICE > 0) {
						if(this.obQuantity.value != 1) {
							BX.adjust(this.obTotalCost, {style: {display: ''}});
							this.obTotalCostVal && BX.adjust(this.obTotalCostVal, {html: BX.Currency.currencyFormat(price.PRICE * this.obQuantity.value, price.CURRENCY, true)});
						} else {
							BX.adjust(this.obTotalCost, {style: {display: 'none'}});
							this.obTotalCostVal && BX.adjust(this.obTotalCostVal, {html: ''});
						}
					} else {
						BX.adjust(this.obTotalCost, {style: {display: 'none'}});
						this.obTotalCostVal && BX.adjust(this.obTotalCostVal, {html: ''});
					}
				} else if(this.obPcQuantity && this.obSqMQuantity) {
					if(price && price.PRICE > 0) {
						if(this.currentMeasure.SYMBOL_INTL == 'pc. 1' || this.currentMeasure.SYMBOL_INTL == 'm2') {
							if(this.obPcQuantity.value != 1 || this.obSqMQuantity.value != 1) {
								BX.adjust(this.obTotalCost, {style: {display: ''}});
								this.obTotalCostVal && BX.adjust(this.obTotalCostVal, {html: BX.Currency.currencyFormat(price.PRICE * (price.SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value), price.CURRENCY, true)});
							} else {
								BX.adjust(this.obTotalCost, {style: {display: 'none'}});
								this.obTotalCostVal && BX.adjust(this.obTotalCostVal, {html: ''});
							}
						} else {
							if(this.obQuantity.value != 1) {
								BX.adjust(this.obTotalCost, {style: {display: ''}});
								this.obTotalCostVal && BX.adjust(this.obTotalCostVal, {html: BX.Currency.currencyFormat(price.PRICE * this.obQuantity.value, price.CURRENCY, true)});
							} else {
								BX.adjust(this.obTotalCost, {style: {display: 'none'}});
								this.obTotalCostVal && BX.adjust(this.obTotalCostVal, {html: ''});
							}
						}
					} else {
						BX.adjust(this.obTotalCost, {style: {display: 'none'}});
						this.obTotalCostVal && BX.adjust(this.obTotalCostVal, {html: ''});
					}
				}
			}
			
			if(this.config.minOrderSum > 0 && this.obQuickOrder) {
				if(this.obQuickOrder.style.display != 'none') {
					if(this.obQuantity && !this.obPcQuantity && !this.obSqMQuantity) {
						if(price.PRICE * this.obQuantity.value < this.config.minOrderSum)
							BX.addClass(this.obQuickOrder, 'product-item-detail-quick-order-hidden');
						else
							BX.removeClass(this.obQuickOrder, 'product-item-detail-quick-order-hidden');
					} else if(this.obPcQuantity && this.obSqMQuantity) {
						if(this.currentMeasure.SYMBOL_INTL == 'pc. 1' || this.currentMeasure.SYMBOL_INTL == 'm2') {
							if(price.PRICE * (price.SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value) < this.config.minOrderSum)
								BX.addClass(this.obQuickOrder, 'product-item-detail-quick-order-hidden');
							else
								BX.removeClass(this.obQuickOrder, 'product-item-detail-quick-order-hidden');
						} else {
							if(price.PRICE * this.obQuantity.value < this.config.minOrderSum)
								BX.addClass(this.obQuickOrder, 'product-item-detail-quick-order-hidden');
							else
								BX.removeClass(this.obQuickOrder, 'product-item-detail-quick-order-hidden');
						}
					}
				} else {
					BX.removeClass(this.obQuickOrder, 'product-item-detail-quick-order-hidden');
				}
			}
		},

		delay: function(e) {
			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);

			BX.adjust(!this.obSkuItems ? this.obDelay : this.obSkuItemDelay, {
				html: '<div class="product-item-detail-delay-loader"><div><span></span></div></div>'
			});
			
			var isDelayed = BX.hasClass(!this.obSkuItems ? this.obDelay : this.obSkuItemDelay, 'product-item-detail-delayed'),
				productId,
				quantity;
			
			switch(this.productType) {
				case 0: //no catalog
				case 1: //product
				case 2: //set
					productId = this.product.id;
					break;
				case 3: //sku
					if(!this.obSkuItems)
						productId = this.offers[this.offerNum].ID;
					else
						productId = this.skuItem.ID;
					break;
			}

			if(this.config.showQuantity) {
				if((!this.obSkuItems && this.obQuantity && !this.obPcQuantity && !this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemQuantity && !this.obSkuItemPcQuantity && !this.obSkuItemSqMQuantity)) {
					quantity = !this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value;
				} else if((!this.obSkuItems && this.obPcQuantity && this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemPcQuantity && this.obSkuItemSqMQuantity)) {
					if(!this.obSkuItems) {
						if(this.currentMeasure.SYMBOL_INTL == 'pc. 1' || this.currentMeasure.SYMBOL_INTL == 'm2') {
							quantity = this.currentPrices[this.currentPriceSelected].SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value;
						} else {
							quantity = this.obQuantity.value;
						}
					} else {
						quantity = this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE ? this.obSkuItemPcQuantity.value : this.obSkuItemSqMQuantity.value;
					}
				}
			} else {						
				if(!this.obSkuItems)
					quantity = this.currentPrices[this.currentPriceSelected] ? this.currentPrices[this.currentPriceSelected].MIN_QUANTITY : '';
				else
					quantity = this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected] ? this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].MIN_QUANTITY : '';
			}

			BX.ajax({
				method: 'POST',				
				dataType: 'json',				
				url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
				data: {					
					action: !isDelayed ? 'ADD_TO_DELAY' : 'DELETE_FROM_DELAY',
					siteId: BX.message('SITE_ID'),
					id: productId,
					quantity: quantity
				},
				onsuccess: BX.proxy(this.delayResult, this)
			});

			if(!!this.popupPanel)
				e.stopPropagation();
		},
			
		delayResult: function(arResult) {
			if(!!arResult.STATUS) {
				if(arResult.STATUS === 'ADDED') {				
					this.setDelayed(true);
					this.setBuyedAdded(false);
					if(!this.obSkuItems && this.offers.length > 0) {
						this.offers[this.offerNum].DELAYED = true;
						this.offers[this.offerNum].BUYED_ADDED = false;
					}
				} else if(arResult.STATUS === 'DELETED') {
					this.setDelayed(false);
					if(!this.obSkuItems && this.offers.length > 0)
						this.offers[this.offerNum].DELAYED = false;
				}
				BX.onCustomEvent('OnBasketDelayChange');
			} else {
				var isDelayed = BX.hasClass(!this.obSkuItems ? this.obDelay : this.obSkuItemDelay, 'product-item-detail-delayed');
				this.setDelayed(!isDelayed ? false : true);
			}
		},
			
		setDelayed: function(state) {
			if((!this.obSkuItems && !this.obDelay) || (!!this.obSkuItems && !this.obSkuItemDelay))
				return;

			BX.adjust(!this.obSkuItems ? this.obDelay : this.obSkuItemDelay, {
				props: {
					style: 'display: unset; padding: 0;',
					className: 'product-item-detail-delay' + (state ? 'ed' : ''),
					title: state ? BX.message('CATALOG_ELEMENT_DELAY_OK_MESSAGE') : BX.message('CATALOG_ELEMENT_DELAY_MESSAGE')
				},
				html: '<i class="' + (state ? 'ex-icon-izbr-card-detail-active' : 'ex-icon-izbr-card-detail') + '"></i>'
			});
		},
			
		setDelayInfo: function(delayedIds) {
			if(!BX.type.isArray(delayedIds))
				return;

			for(var i in this.offers) {
				if(this.offers.hasOwnProperty(i)) {
					if(!this.obSkuItems) {
						this.offers[i].DELAYED = BX.util.in_array(this.offers[i].ID, delayedIds);
					} else {
						this.skuItemContainer = this.obSkuItems.querySelector('[data-num="' + i + '"]');
						if(!!this.skuItemContainer) {
							this.obSkuItemDelay = this.skuItemContainer.querySelector('[data-entity="delay"]');
							this.setDelayed(BX.util.in_array(this.offers[i].ID, delayedIds));
						}
					}
				}
			}
		},

		setBuyedAdded: function(state) {

			var buyAddBtn = !this.obSkuItems ? (BX.util.in_array('BUY', this.config.basketAction) ? this.obBuyBtn : this.obAddToBasketBtn) : (BX.util.in_array('BUY', this.config.basketAction) ? this.obSkuItemBuyBtn : this.obSkuItemAddToBasketBtn);
			if(!buyAddBtn)
				return;

			if(state) {
				BX.adjust(buyAddBtn, {
					props: {
						className: 'btn btn-buy-ok'
					},
					html: '<i class="icon-ok-b"></i><span>' + BX.message('CATALOG_ELEMENT_ADD_BASKET_OK_MESSAGE') + '</span>'
				});
				BX.unbindAll(buyAddBtn);
				BX.bind(buyAddBtn, "click", BX.delegate(this.basketRedirect, this));				
			} else {
				BX.adjust(buyAddBtn, {
					props: {
						className: 'btn btn-buy'
					},
					html: '<i class="icon-cart"></i><span>' + (this.obAddToMarketplaceBtnOzon != null ? 'Купить на сайте' : BX.message('CATALOG_ELEMENT_ADD_BASKET_MESSAGE')) + '</span>'
				});
				BX.unbindAll(buyAddBtn);
				BX.bind(buyAddBtn, "click", BX.proxy(BX.util.in_array('BUY', this.config.basketAction) ? this.buyBasket : this.add2Basket, this));
			}
		},
			
		setBuyAddInfo: function(buyedAddedIds) {
			if(!BX.type.isArray(buyedAddedIds))
				return;

			for(var i in this.offers) {
				if(this.offers.hasOwnProperty(i)) {
					if(!this.obSkuItems) {
						this.offers[i].BUYED_ADDED = BX.util.in_array(this.offers[i].ID, buyedAddedIds);
					} else {
						this.skuItemContainer = this.obSkuItems.querySelector('[data-num="' + i + '"]');
						if(!!this.skuItemContainer) {
							this.obSkuItemBuyBtn = this.skuItemContainer.querySelector('[data-entity="buy"]');
							this.obSkuItemAddToBasketBtn = this.skuItemContainer.querySelector('[data-entity="add"]');
							this.setBuyedAdded(BX.util.in_array(this.offers[i].ID, buyedAddedIds));
						}
					}
				}
			}
		},

		compare: function(event) {
			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);

			var checkbox = this.getEntity(!this.obSkuItems ? this.obCompare : this.obSkuItemCompare, 'compare-checkbox'),
				target = BX.getEventTarget(event),
				checked = true;
			
			if(!!checkbox)
				checked = target === checkbox ? checkbox.checked : !checkbox.checked;
			
			var url = checked ? this.compareData.compareUrl : this.compareData.compareDeleteUrl,
				compareLink;
			
			if(!!url) {
				if(target !== checkbox) {
					BX.PreventDefault(event);
					this.setCompared(checked);
				}
				
				switch(this.productType) {
					case 0: // no catalog
					case 1: // product
					case 2: // set
						compareLink = url.replace('#ID#', this.product.id.toString());
						break;
					case 3: // sku
						if(!this.obSkuItems)
							compareLink = url.replace('#ID#', this.offers[this.offerNum].ID);
						else
							compareLink = url.replace('#ID#', this.skuItem.ID);
						break;
				}

				BX.ajax({
					method: 'POST',
					dataType: checked ? 'json' : 'html',
					url: compareLink + (compareLink.indexOf('?') !== -1 ? '&' : '?') + 'ajax_action=Y',
					onsuccess: checked ? BX.proxy(this.compareResult, this) : BX.proxy(this.compareDeleteResult, this)
				});
			}
		},
			
		compareResult: function(result) {
			if(!BX.type.isPlainObject(result))
				return;
			
			if(!this.obSkuItems && this.offers.length > 0)
				this.offers[this.offerNum].COMPARED = result.STATUS === 'OK';
			
			if(result.STATUS === 'OK')
				BX.onCustomEvent('OnCompareChange');
		},

		compareDeleteResult: function() {
			BX.onCustomEvent('OnCompareChange');

			if(!this.obSkuItems && this.offers && this.offers.length)
				this.offers[this.offerNum].COMPARED = false;
		},

		setCompared: function(state) {
			if((!this.obSkuItems && !this.obCompare) || (!!this.obSkuItems && !this.obSkuItemCompare))
				return;

			var checkbox = this.getEntity(!this.obSkuItems ? this.obCompare : this.obSkuItemCompare, 'compare-checkbox');
			if(!!checkbox)
				checkbox.checked = state;
			
			var title = this.getEntity(!this.obSkuItems ? this.obCompare : this.obSkuItemCompare, 'compare-title');
			if(!!title)
				title.innerHTML = state ? BX.message('CATALOG_ELEMENT_COMPARE_OK_MESSAGE') : BX.message('CATALOG_ELEMENT_COMPARE_MESSAGE');
		},
			
		setCompareInfo: function(comparedIds) {
			if(!BX.type.isArray(comparedIds))
				return;

			for(var i in this.offers) {
				if(this.offers.hasOwnProperty(i)) {
					if(!this.obSkuItems) {
						this.offers[i].COMPARED = BX.util.in_array(this.offers[i].ID, comparedIds);
					} else {
						this.skuItemContainer = this.obSkuItems.querySelector('[data-num="' + i + '"]');
						if(!!this.skuItemContainer) {
							this.obSkuItemCompare = this.skuItemContainer.querySelector('[data-entity="compare"]');
							this.setCompared(BX.util.in_array(this.offers[i].ID, comparedIds));
						}
					}
				}
			}
		},

		checkDeletedCompare: function(id) {
			switch(this.productType) {
				case 0: // no catalog
				case 1: // product
				case 2: // set
					if(this.product.id == id)
						this.setCompared(false);
					break;
				case 3: // sku
					var i = this.offers.length;
					while(i--) {
						if(this.offers[i].ID == id) {
							if(!this.obSkuItems) {
								this.offers[i].COMPARED = false;
								if(this.offerNum == i)
									this.setCompared(false);
							} else {
								this.skuItemContainer = this.obSkuItems.querySelector('[data-num="' + i + '"]');
								if(!!this.skuItemContainer) {
									this.obSkuItemCompare = this.skuItemContainer.querySelector('[data-entity="compare"]');
									this.setCompared(false);
								}
							}
							break;
						}
					}
			}
		},

		scrollToSkuItems: function() {
			if(!this.obSkuItems)
				return;
			
			if(!this.popupPanel) {
				var topPanel = document.querySelector('.top-panel'),
					topPanelHeight = 0,
					topPanelThead = !!topPanel && topPanel.querySelector('.top-panel__thead'),
					topPanelTfoot = !!topPanel && topPanel.querySelector('.top-panel__tfoot'),
					scrollTop = BX.GetWindowScrollPos().scrollTop;
				
				if(window.innerWidth < 992) {
					if(!!topPanelThead)
						topPanelHeight = topPanelThead.offsetHeight;
				} else {
					if(!!topPanel)
						topPanelHeight = topPanel.offsetHeight;
				}
				
				new BX.easing({
					duration: 500,
					start: {scroll: scrollTop},
					finish: {scroll: BX.pos(this.obSkuItems).top - (this.obTabsBlock ? this.obTabsBlock.offsetHeight : 0) - topPanelHeight},
					transition: BX.easing.makeEaseOut(BX.easing.transitions.quint),
					step: function(state) {
						window.scrollTo(0, state.scroll);
					}
				}).animate();
			} else if(!!this.popupPanelContent) {
				new BX.easing({
					duration: 500,
					start: {scroll: this.popupPanelContent.scrollTop},						
					finish: {scroll: this.obSkuItems.offsetTop + 40},
					transition: BX.easing.makeEaseOut(BX.easing.transitions.quint),
					step: BX.delegate(function(state) {
						this.popupPanelContent.scrollTo(0, state.scroll);
					}, this)
				}).animate();
			}
		},

		setConstructor: function(index) {
			if(!this.obConstructor)
				return;

			var newOffer = this.offers[index],
				oldOffer = this.offers[this.offerNum];

			if(newOffer.ID == oldOffer.ID)
				return;
			
			this.obConstructor.style.opacity = 0.2;
			BX.ajax({
				url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: {
					'action': 'setConstructor',
					'requestUri': window.location.href,
					'siteId': BX.message('SITE_ID'),
					'parameters': BX.message('CATALOG_ELEMENT_PARAMETERS'),
					'productId': newOffer.ID
				},
				onsuccess: BX.delegate(function(result) {
					if(!result.content || !result.JS) {
						this.obConstructor.innerHTML = '';
					} else {
						BX.ajax.processScripts(
							BX.processHTML(result.JS).SCRIPT,
							false,
							BX.delegate(function() {
								var processed = BX.processHTML(result.content, false);

								this.obConstructor.innerHTML = processed.HTML;
								
								if(result.imgWebp) {
									var srcList = {},
										images = this.obConstructor.querySelectorAll('img');
									
									if(!!images) {
										for(var i in images) {
											if(images.hasOwnProperty(i)) {
												var imageDataLazyloadSrc = images[i].getAttribute('data-lazyload-src'),
													imageSrc = images[i].getAttribute('src');

												if(!!imageDataLazyloadSrc && imageDataLazyloadSrc.substr(0, 4) !== 'http' && imageDataLazyloadSrc.substr(0, 11) !== 'data:image/' && imageDataLazyloadSrc.indexOf('.webp') == -1) {
													srcList[imageDataLazyloadSrc] = imageDataLazyloadSrc;
												} else if(!!imageSrc && imageSrc.substr(0, 4) !== 'http' && imageSrc.substr(0, 11) !== 'data:image/' && imageSrc.indexOf('.webp') == -1) {
													srcList[imageSrc] = imageSrc;
												}
											}
										}
									}

									if(Object.keys(srcList).length > 0)
										convertImgToWebp(srcList);
								}

								if(result.imgLazyLoad)
									imgLazyLoad();
								
								BX.ajax.processScripts(processed.SCRIPT);
							}, this)
						);
					}

					new BX.easing({
						duration: 2000,
						start: {opacity: 20},
						finish: {opacity: 100},
						transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
						step: BX.delegate(function(state) {
							this.obConstructor.style.opacity = state.opacity / 100;
						}, this),
						complete: BX.delegate(function() {
							this.obConstructor.removeAttribute('style');
						}, this)
					}).animate();
				}, this)
			});
		},

		initBasketUrl: function() {
			this.basketUrl = (this.basketMode === 'ADD' ? this.basketData.add_url : this.basketData.buy_url);

			switch(this.productType) {
				case 1: //product
				case 2: //set
					this.basketUrl = this.basketUrl.replace('#ID#', this.product.id.toString());
					break;
				case 3: //sku
					if(!this.obSkuItems)
						this.basketUrl = this.basketUrl.replace('#ID#', this.offers[this.offerNum].ID);
					else
						this.basketUrl = this.basketUrl.replace('#ID#', this.skuItem.ID);
					break;
			}

			this.basketParams = {
				'ajax_basket': 'Y'
			};

			if(this.config.showQuantity) {
				if((!this.obSkuItems && this.obQuantity && !this.obPcQuantity && !this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemQuantity && !this.obSkuItemPcQuantity && !this.obSkuItemSqMQuantity)) {
					this.basketParams[this.basketData.quantity] = !this.obSkuItems ? this.obQuantity.value : this.obSkuItemQuantity.value;
				} else if((!this.obSkuItems && this.obPcQuantity && this.obSqMQuantity) || (!!this.obSkuItems && this.obSkuItemPcQuantity && this.obSkuItemSqMQuantity)) {
					if(!this.obSkuItems) {
						if(this.currentMeasure.SYMBOL_INTL == 'pc. 1' || this.currentMeasure.SYMBOL_INTL == 'm2') {
							this.basketParams[this.basketData.quantity] = this.currentPrices[this.currentPriceSelected].SQ_M_PRICE ? this.obPcQuantity.value : this.obSqMQuantity.value;
						} else {
							this.basketParams[this.basketData.quantity] = this.obQuantity.value;
						}
					} else {
						this.basketParams[this.basketData.quantity] = this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].SQ_M_PRICE ? this.obSkuItemPcQuantity.value : this.obSkuItemSqMQuantity.value;
					}
				}
			} else {						
				if(!this.obSkuItems)
					this.basketParams[this.basketData.quantity] = this.currentPrices[this.currentPriceSelected] ? this.currentPrices[this.currentPriceSelected].MIN_QUANTITY : '';
				else
					this.basketParams[this.basketData.quantity] = this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected] ? this.skuItemCurrentPrices[this.skuItemCurrentPriceSelected].MIN_QUANTITY : '';
			}
			
			if(this.basketData.sku_props) {
				this.basketParams[this.basketData.sku_props_var] = this.basketData.sku_props;
			}
		},

		fillBasketProps: function() {
			if(!this.visual.BASKET_PROP_DIV)
				return;

			var i = 0,
				propCollection = null,
				foundValues = false,
				obBasketProps = null;
			
			obBasketProps = BX(this.visual.BASKET_PROP_DIV);
			if(!!obBasketProps) {
				propCollection = obBasketProps.getElementsByTagName('input');
				if(propCollection && propCollection.length) {
					for(i = 0; i < propCollection.length; i++) {
						if(!propCollection[i].disabled) {
							switch(propCollection[i].type.toLowerCase()) {
								case 'hidden':
									this.basketParams[propCollection[i].name] = propCollection[i].value;
									foundValues = true;
									break;
								case 'radio':
									if(propCollection[i].checked) {
										this.basketParams[propCollection[i].name] = propCollection[i].value;
										foundValues = true;
									}
									break;
								default:
									break;
							}
						}
					}
				}
			}

			if(!foundValues) {
				this.basketParams[this.basketData.props] = [];
				this.basketParams[this.basketData.props][0] = 0;
			}
		},

		showBasketPropsDropDownPopup: function(element, popupId) {
			var contentNode = element.querySelector('[data-entity="dropdownContent"]');

			if(!!this.obPopupWin)
				this.obPopupWin.close();

			this.obPopupWin = BX.PopupWindowManager.create('basketPropsDropDown_' + popupId + '_' + this.visual.ID, element, {
				autoHide: true,
				offsetLeft: 0,
				offsetTop: 3,
				overlay : false,
				draggable: {restrict: true},
				closeByEsc: true,
				className: 'bx-drop-down-popup-window',
				content: BX.clone(contentNode)
			});	
			
			contentNode.parentNode.appendChild(BX('basketPropsDropDown_' + popupId + '_' + this.visual.ID));
			
			this.obPopupWin.show();
		},

		selectBasketPropsDropDownPopupItem: function(element, valueId) {
			var wrapContainer = BX.findParent(element, {className: 'product-item-detail-basket-props-drop-down'}, false),
				currentValue = wrapContainer.querySelector('INPUT'),
				currentOption = wrapContainer.querySelector('[data-entity="current-option"]');
			
			currentValue.value = valueId;
			currentOption.innerHTML = element.innerHTML;
			
			BX.PopupWindowManager.getCurrentPopup().close();
		},
		
		sendToBasket: function() {
			if((!this.obSkuItems && !this.canBuy) || (!!this.obSkuItems && !this.skuItemCanBuy))
				return;
			
			this.initBasketUrl();
			this.fillBasketProps();
			BX.ajax({
				method: 'POST',
				dataType: 'json',
				url: this.basketUrl,
				data: this.basketParams,
				onsuccess: BX.proxy(this.basketResult, this)
			});
		},

		add2Basket: function(e) {
			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);
			
			this.basketMode = 'ADD';
			this.basket();
			
			if(!!this.popupPanel)
				e.stopPropagation();
		},

		buyBasket: function(e) {
			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);

			this.basketMode = 'BUY';
			this.basket();

			if(!!this.popupPanel)
				e.stopPropagation();
		},

		basket: function() {
			if((!this.obSkuItems && !this.canBuy) || (!!this.obSkuItems && !this.skuItemCanBuy))
				return;

			var buyAddBtn = !this.obSkuItems ? (BX.util.in_array('BUY', this.config.basketAction) ? this.obBuyBtn : this.obAddToBasketBtn) : (BX.util.in_array('BUY', this.config.basketAction) ? this.obSkuItemBuyBtn : this.obSkuItemAddToBasketBtn);
			if(!!buyAddBtn) {
				buyAddBtn.innerHTML = '<span class="btn-loader"><span><span></span></span></span>';
			}
			
			this.sendToBasket();
		},

		basketResult: function(arResult) {
			if(arResult.STATUS === 'OK') {
				if(this.basketMode == 'BUY') {
					this.basketRedirect();
				} else {				
					if(!this.obSkuItems) {
						var productPict,
							productPictContainer = document.querySelector('[data-entity="videos-images-container"]'),
							productPictContainerWidth = productPictContainer.offsetWidth;

						switch(this.productType) {
							case 1: //product
							case 2: //set
								productPict = this.product.pict.SRC;
								break;
							case 3: //sku
								productPict = this.offers[this.offerNum].DETAIL_PICTURE ? this.offers[this.offerNum].DETAIL_PICTURE.SRC : this.defaultPict.pict.SRC;
								break;
						}
					
						if(!!productPict) {
							document.body.appendChild(
								BX.create('IMG', {
									props: {
										className: 'animated-image'
									},
									style: {
										width: productPictContainerWidth + 'px',								
										position: 'absolute',
										'z-index': '1100'
									},
									attrs: {
										src: productPict
									}
								})
							);
						}
					
						var animatedImg = document.body.querySelector('.animated-image');
						if(!!animatedImg) {
							var topPanel = document.querySelector('.top-panel'),
								miniCart = topPanel.querySelector('.mini-cart__cart');
							
							new BX.easing({
								duration: 500,
								start: {
									width: productPictContainerWidth,
									left: BX.pos(productPictContainer).left,
									top: BX.pos(productPictContainer).top
								},
								finish: {
									width: 70,							
									left: BX.pos(miniCart).left,
									top: BX.pos(miniCart).top
								},
								transition: BX.easing.transitions.linear,
								step: BX.delegate(function(state) {
									animatedImg.style.width = state.width + 'px';							
									animatedImg.style.left = state.left + 'px';
									animatedImg.style.top = state.top + 'px';
								}, this),
								complete: BX.delegate(function() {
									BX.remove(animatedImg);
									this.setBuyedAdded(true);
									this.setDelayed(false);
									if(this.offers.length > 0) {
										this.offers[this.offerNum].BUYED_ADDED = true;
										this.offers[this.offerNum].DELAYED = false;
									}
									BX.onCustomEvent('OnBasketChange');
									this.setAnalyticsDataLayer('addToCart');
									
									if(!!this.popupPanel && this.popupPanel.hasAttribute('data-location-href') &&
										this.popupPanel.getAttribute('data-location-href').indexOf('/personal/cart/') > -1
									) {
										setTimeout(BX.delegate(function() {
											window.location.href = this.popupPanel.getAttribute('data-location-href');
										}, this), 1000);
									}
								}, this)
							}).animate();
						}
					} else {
						this.setBuyedAdded(true);
						this.setDelayed(false);
						BX.onCustomEvent('OnBasketChange');
						this.setAnalyticsDataLayer('addToCart');

						if(!!this.popupPanel && this.popupPanel.hasAttribute('data-location-href') &&
							this.popupPanel.getAttribute('data-location-href').indexOf('/personal/cart/') > -1
						) {
							setTimeout(BX.delegate(function() {
								window.location.href = this.popupPanel.getAttribute('data-location-href');
							}, this), 1000);
						}
					}
				}
			} else {
				this.setBuyedAdded(false);
			}
		},

		showOfferBasketPropsDropDownPopup: function(element, popupId) {
			var contentNode = element.querySelector('[data-entity="dropdownContent"]');

			if(!!this.obPopupWin)
				this.obPopupWin.close();

			this.obPopupWin = BX.PopupWindowManager.create('offerBasketPropsDropDown_' + popupId + '_' + this.visual.ID, element, {
				autoHide: true,
				offsetLeft: 0,
				offsetTop: 3,
				overlay : false,
				draggable: {restrict: true},
				closeByEsc: true,
				className: 'bx-drop-down-popup-window',
				content: BX.clone(contentNode)
			});	
			
			contentNode.parentNode.appendChild(BX('offerBasketPropsDropDown_' + popupId + '_' + this.visual.ID));
			
			this.obPopupWin.show();
		},

		selectOfferBasketPropsDropDownPopupItem: function(element) {
			BX.PopupWindowManager.getCurrentPopup().close();
			
			this.selectOfferProp(element);
		},

		showObjectWorkingHoursToday: function() {
			var skuItemHours = this.obSkuItems.querySelectorAll('[data-entity="hours"]');
			if(!!skuItemHours) {
				for(var i in skuItemHours) {
					if(skuItemHours.hasOwnProperty(i) && BX.type.isDomNode(skuItemHours[i])) {
						var skuItemContainer = BX.findParent(skuItemHours[i], {attrs: {'data-entity': 'sku-item'}});
						if(!!skuItemContainer) {
							var skuItemNum = parseInt(skuItemContainer.getAttribute('data-num')),
								skuItem = this.offers[skuItemNum];

							skuItemHours[i].innerHTML = '<div class="product-item-detail-scu-item-object-hours-loader"><div><span></span></div></div>' + BX.message('CATALOG_ELEMENT_OBJECT_LOADING');
							BX.removeClass(skuItemHours[i], 'product-item-detail-scu-item-object-hours-hidden');
							
							BX.delegate(function(index) {
								BX.ajax({
									url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
									method: 'POST',
									dataType: 'json',
									timeout: 60,
									data: {							
										action: 'objectWorkingHoursToday',
										siteCharset: BX.message('SITE_CHARSET'),
										timezone: skuItem.OBJECT ? skuItem.OBJECT.TIMEZONE : '',
										workingHours: skuItem.OBJECT ? skuItem.OBJECT.WORKING_HOURS : ''
									},
									onsuccess: BX.delegate(function(result) {
										var content = '',
											workingHoursToday = result.today;
										
										if(!!workingHoursToday) {
											for(var j in workingHoursToday) {
												if(workingHoursToday.hasOwnProperty(j)) {
													if(workingHoursToday[j].STATUS) {
														content += '<span class="product-item-detail-scu-item-object-hours-icon product-item-detail-scu-item-object-hours-icon-' + (workingHoursToday[j].STATUS == 'OPEN' ? 'open' : 'closed') + '"></span>';
													}
													if(workingHoursToday[j].WORK_START && workingHoursToday[j].WORK_END) {
														if(workingHoursToday[j].WORK_START != workingHoursToday[j].WORK_END) {
															content += workingHoursToday[j].WORK_START + ' - ' + workingHoursToday[j].WORK_END;
															if(workingHoursToday[j].BREAK_START && workingHoursToday[j].BREAK_END) {
																if(workingHoursToday[j].BREAK_START != workingHoursToday[j].BREAK_END) {
																	content += '<span class="product-item-detail-scu-item-object-hours-break">';
																		content += BX.message('CATALOG_ELEMENT_OBJECT_BREAK') + ' ' + workingHoursToday[j].BREAK_START + ' - ' + workingHoursToday[j].BREAK_END;
																	content += '</span>';
																}
															}
														} else {
															content += BX.message('CATALOG_ELEMENT_OBJECT_24_HOURS');
														}
													} else {
														content += BX.message('CATALOG_ELEMENT_OBJECT_OFF');
													}
												}
											}

											var skuItemContainer = BX.findParent(skuItemHours[index], {attrs: {'data-entity': 'sku-item'}});
											if(!!skuItemContainer) {
												var skuItemNum = parseInt(skuItemContainer.getAttribute('data-num'));
												
												this.offers[skuItemNum].OBJECT.WORKING_HOURS_TODAY = workingHoursToday;
											}
										}
										
										skuItemHours[index].innerHTML = content;
										if(content.length == 0)
											BX.addClass(skuItemHours[index], 'product-item-detail-scu-item-object-hours-hidden');
									}, this)
								});
							}, this)(i);
						}
					}
				}
			}
		},

		getObjectWorkingHoursToday: function(sPanelContent) {
			BX.ajax({
				url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: {							
					action: 'objectWorkingHoursToday',
					siteCharset: BX.message('SITE_CHARSET'),
					timezone: this.object.timezone,
					workingHours: this.object.workingHours
				},
				onsuccess: BX.delegate(function(result) {
					if(!!result.today)
						this.object.workingHoursToday = result.today;
					BX.onCustomEvent(this, 'objectWorkingHoursTodayReceived', [sPanelContent]);
				}, this)
			});
		},

		adjustObjectContacts: function(sPanelContent) {
			var content = '';
			
			if(this.object.address || Object.keys(this.object.workingHours).length > 0 || this.object.workingHoursToday || Object.keys(this.object.phone).length > 0 || Object.keys(this.object.email).length > 0 || Object.keys(this.object.skype).length > 0) {
				content += '<div class="slide-panel__contacts" id="' + this.visual.ID + '_contacts">';

					if(this.object.address) {
						content += '<div class="slide-panel__contacts-item">';
							content += '<div class="slide-panel__contacts-item__block">';
								content += '<div class="slide-panel__contacts-item__icon"><i class="icon-map-marker"></i></div>';
								content += '<div class="slide-panel__contacts-item__text">' + this.object.address + '</div>';
							content += '</div>';
						content += '</div>';
					}

					if(this.object.workingHoursToday) {
						for(var i in this.object.workingHoursToday) {
							if(this.object.workingHoursToday.hasOwnProperty(i)) {
								content += '<div class="slide-panel__contacts-item" data-entity="working-hours-today">';
									content += '<div class="slide-panel__contacts-item__hours-today">';
										content += '<div class="slide-panel__contacts-item__today-container">';
											content += '<div class="slide-panel__contacts-item__today">';
												content += '<span class="slide-panel__contacts-item__today-icon"><i class="icon-clock"></i></span>';
												content += '<span class="slide-panel__contacts-item__today-title">' + BX.message('CATALOG_ELEMENT_OBJECT_TODAY') + '</span>';
												if(this.object.workingHoursToday[i].STATUS) {
													content += '<span class="slide-panel__contacts-item__today-status slide-panel__contacts-item__today-status-' + (this.object.workingHoursToday[i].STATUS == 'OPEN' ? 'open' : 'closed') + '"></span>';
												}
											content += '</div>';
										content += '</div>';
										content += '<div class="slide-panel__contacts-item__hours-break">';
											content += '<div class="slide-panel__contacts-item__hours slide-panel__contacts-item__hours-first">';
												content += '<span class="slide-panel__contacts-item__hours-title">';
													if(this.object.workingHoursToday[i].WORK_START && this.object.workingHoursToday[i].WORK_END) {
														if(this.object.workingHoursToday[i].WORK_START != this.object.workingHoursToday[i].WORK_END) {
															content += this.object.workingHoursToday[i].WORK_START + ' - ' + this.object.workingHoursToday[i].WORK_END;
														} else {
															content += BX.message('CATALOG_ELEMENT_OBJECT_24_HOURS');
														}
													} else {
														content += BX.message('CATALOG_ELEMENT_OBJECT_OFF');
													}
												content += '</span>';
												content += '<span class="slide-panel__contacts-item__hours-icon"><i class="icon-arrow-down"></i></span>';
											content += '</div>';
											if(this.object.workingHoursToday[i].WORK_START && this.object.workingHoursToday[i].WORK_END) {
												if(this.object.workingHoursToday[i].WORK_START != this.object.workingHoursToday[i].WORK_END) {
													if(this.object.workingHoursToday[i].BREAK_START && this.object.workingHoursToday[i].BREAK_END) {
														if(this.object.workingHoursToday[i].BREAK_START != this.object.workingHoursToday[i].BREAK_END) {
															content += '<div class="slide-panel__contacts-item__break">';
																content += BX.message('CATALOG_ELEMENT_OBJECT_BREAK') + ' ' + this.object.workingHoursToday[i].BREAK_START + ' - ' + this.object.workingHoursToday[i].BREAK_END;
															content += '</div>';
														}
													}
												}
											}
										content += '</div>';
									content += '</div>';
								content += '</div>';
							}
						}
					}

					if(Object.keys(this.object.workingHours).length > 0) {
						content += '<div class="slide-panel__contacts-item" data-entity="working-hours"' + (this.object.workingHoursToday ? 'style="display: none;"' : '') + '>';
							var key = 0;
							for(var i in this.object.workingHours) {
								if(this.object.workingHours.hasOwnProperty(i)) {										
									content += '<div class="slide-panel__contacts-item__hours-today">';
										content += '<div class="slide-panel__contacts-item__today-container">';
											content += '<div class="slide-panel__contacts-item__today">';
												if(key == 0) {
													content += '<span class="slide-panel__contacts-item__today-icon"><i class="icon-clock"></i></span>';
												}
												content += '<span class="slide-panel__contacts-item__today-title">' + (this.object.workingHoursToday && this.object.workingHoursToday.hasOwnProperty(i) ? BX.message('CATALOG_ELEMENT_OBJECT_TODAY') : this.object.workingHours[i].NAME) + '</span>';
												if(this.object.workingHoursToday && this.object.workingHoursToday.hasOwnProperty(i) && this.object.workingHoursToday[i].STATUS) {
													content += '<span class="slide-panel__contacts-item__today-status slide-panel__contacts-item__today-status-' + (this.object.workingHoursToday[i].STATUS == 'OPEN' ? 'open' : 'closed') + '"></span>';
												}
											content += '</div>';
										content += '</div>';
										content += '<div class="slide-panel__contacts-item__hours-break">';
											content += '<div class="slide-panel__contacts-item__hours' + (key == 0 ? ' slide-panel__contacts-item__hours-first' : '') + '">';
												content += '<span class="slide-panel__contacts-item__hours-title">';
													if(this.object.workingHours[i].WORK_START && this.object.workingHours[i].WORK_END) {
														if(this.object.workingHours[i].WORK_START != this.object.workingHours[i].WORK_END) {
															content += this.object.workingHours[i].WORK_START + ' - ' + this.object.workingHours[i].WORK_END;
														} else {
															content += BX.message('CATALOG_ELEMENT_OBJECT_24_HOURS');
														}
													} else {
														content += BX.message('CATALOG_ELEMENT_OBJECT_OFF');
													}
												content += '</span>';
												if(this.object.workingHoursToday && key == 0) {
													content += '<span class="slide-panel__contacts-item__hours-icon"><i class="icon-arrow-up"></i></span>';
												}
											content += '</div>';
											if(this.object.workingHours[i].WORK_START && this.object.workingHours[i].WORK_END) {
												if(this.object.workingHours[i].WORK_START != this.object.workingHours[i].WORK_END) {
													if(this.object.workingHours[i].BREAK_START && this.object.workingHours[i].BREAK_END) {
														if(this.object.workingHours[i].BREAK_START != this.object.workingHours[i].BREAK_END) {
															content += '<div class="slide-panel__contacts-item__break">';
																content += BX.message('CATALOG_ELEMENT_OBJECT_BREAK') + ' ' + this.object.workingHours[i].BREAK_START + ' - ' + this.object.workingHours[i].BREAK_END;
															content += '</div>';
														}
													}
												}
											}
										content += '</div>';
									content += '</div>';
									key++;
								}
							}
						content += '</div>';
					}
					
					if(Object.keys(this.object.phone).length > 0) {
						for(var i in this.object.phone) {
							if(this.object.phone.hasOwnProperty(i)) {
								content += '<div class="slide-panel__contacts-item">';
									content += '<div class="slide-panel__contacts-item__block">';
										content += '<div class="slide-panel__contacts-item__icon"><i class="icon-phone"></i></div>';
										content += '<div class="slide-panel__contacts-item__text">';
											content += '<a class="slide-panel__contacts-item__phone slide-panel__contacts-item__link" href="tel:' + this.object.phone[i].replace(/[^\d\+]/g,'') + '">' + this.object.phone[i] + '</a>';
											if(this.object.phoneDescription.hasOwnProperty(i) && this.object.phoneDescription[i].length > 0) {
												content += '<span class="slide-panel__contacts-item__descr">' + this.object.phoneDescription[i] + '</span>';
											}
										content += '</div>';
									content += '</div>';
								content += '</div>';
							}
						}
					}
					
					if(Object.keys(this.object.email).length > 0) {
						for(var i in this.object.email) {
							if(this.object.email.hasOwnProperty(i)) {
								content += '<div class="slide-panel__contacts-item">';
									content += '<div class="slide-panel__contacts-item__block">';
										content += '<div class="slide-panel__contacts-item__icon"><i class="icon-mail"></i></div>';
										content += '<div class="slide-panel__contacts-item__text">';
											content += '<a class="slide-panel__contacts-item__link" href="mailto:' + this.object.email[i] + '">' + this.object.email[i] + '</a>';
											if(this.object.emailDescription.hasOwnProperty(i) && this.object.emailDescription[i].length > 0) {
												content += '<span class="slide-panel__contacts-item__descr">' + this.object.emailDescription[i] + '</span>';
											}
										content += '</div>';
									content += '</div>';
								content += '</div>';
							}
						}
					}

					if(Object.keys(this.object.skype).length > 0) {
						for(var i in this.object.skype) {
							if(this.object.skype.hasOwnProperty(i)) {
								content += '<div class="slide-panel__contacts-item">';
									content += '<div class="slide-panel__contacts-item__block">';
										content += '<div class="slide-panel__contacts-item__icon"><i class="fa fa-skype"></i></div>';
										content += '<div class="slide-panel__contacts-item__text">';
											content += '<a class="slide-panel__contacts-item__link" href="skype:' + this.object.skype[i] + '?chat">' + this.object.skype[i] + '</a>';
											if(this.object.skypeDescription.hasOwnProperty(i) && this.object.skypeDescription[i].length > 0) {
												content += '<span class="slide-panel__contacts-item__descr">' + this.object.skypeDescription[i] + '</span>';
											}
										content += '</div>';
									content += '</div>';
								content += '</div>';
							}
						}
					}
				
				content += '</div>';
			}

			this.object.content = content;
				
			BX.onCustomEvent(this, 'objectContactsAdjusted', [sPanelContent]);
		},

		adjustSkuItemObjectContacts: function(sPanelContent) {
			var content = '';
			
			if(this.skuItemObject.address || Object.keys(this.skuItemObject.workingHours).length > 0 || this.skuItemObject.workingHoursToday || Object.keys(this.skuItemObject.phone).length > 0 || Object.keys(this.skuItemObject.email).length > 0 || Object.keys(this.skuItemObject.skype).length > 0) {
				content += '<div class="slide-panel__contacts" id="' + this.visual.ID + '_' + this.skuItem.ID + '_contacts">';

					if(this.skuItemObject.address) {
						content += '<div class="slide-panel__contacts-item">';
							content += '<div class="slide-panel__contacts-item__block">';
								content += '<div class="slide-panel__contacts-item__icon"><i class="icon-map-marker"></i></div>';
								content += '<div class="slide-panel__contacts-item__text">' + this.skuItemObject.address + '</div>';
							content += '</div>';
						content += '</div>';
					}

					if(this.skuItemObject.workingHoursToday) {
						for(var i in this.skuItemObject.workingHoursToday) {
							if(this.skuItemObject.workingHoursToday.hasOwnProperty(i)) {
								content += '<div class="slide-panel__contacts-item" data-entity="working-hours-today">';
									content += '<div class="slide-panel__contacts-item__hours-today">';
										content += '<div class="slide-panel__contacts-item__today-container">';
											content += '<div class="slide-panel__contacts-item__today">';
												content += '<span class="slide-panel__contacts-item__today-icon"><i class="icon-clock"></i></span>';
												content += '<span class="slide-panel__contacts-item__today-title">' + BX.message('CATALOG_ELEMENT_OBJECT_TODAY') + '</span>';
												if(this.skuItemObject.workingHoursToday[i].STATUS) {
													content += '<span class="slide-panel__contacts-item__today-status slide-panel__contacts-item__today-status-' + (this.skuItemObject.workingHoursToday[i].STATUS == 'OPEN' ? 'open' : 'closed') + '"></span>';
												}
											content += '</div>';
										content += '</div>';
										content += '<div class="slide-panel__contacts-item__hours-break">';
											content += '<div class="slide-panel__contacts-item__hours slide-panel__contacts-item__hours-first">';
												content += '<span class="slide-panel__contacts-item__hours-title">';
													if(this.skuItemObject.workingHoursToday[i].WORK_START && this.skuItemObject.workingHoursToday[i].WORK_END) {
														if(this.skuItemObject.workingHoursToday[i].WORK_START != this.skuItemObject.workingHoursToday[i].WORK_END) {
															content += this.skuItemObject.workingHoursToday[i].WORK_START + ' - ' + this.skuItemObject.workingHoursToday[i].WORK_END;
														} else {
															content += BX.message('CATALOG_ELEMENT_OBJECT_24_HOURS');
														}
													} else {
														content += BX.message('CATALOG_ELEMENT_OBJECT_OFF');
													}
												content += '</span>';
												content += '<span class="slide-panel__contacts-item__hours-icon"><i class="icon-arrow-down"></i></span>';
											content += '</div>';
											if(this.skuItemObject.workingHoursToday[i].WORK_START && this.skuItemObject.workingHoursToday[i].WORK_END) {
												if(this.skuItemObject.workingHoursToday[i].WORK_START != this.skuItemObject.workingHoursToday[i].WORK_END) {
													if(this.skuItemObject.workingHoursToday[i].BREAK_START && this.skuItemObject.workingHoursToday[i].BREAK_END) {
														if(this.skuItemObject.workingHoursToday[i].BREAK_START != this.skuItemObject.workingHoursToday[i].BREAK_END) {
															content += '<div class="slide-panel__contacts-item__break">';
																content += BX.message('CATALOG_ELEMENT_OBJECT_BREAK') + ' ' + this.skuItemObject.workingHoursToday[i].BREAK_START + ' - ' + this.skuItemObject.workingHoursToday[i].BREAK_END;
															content += '</div>';
														}
													}
												}
											}
										content += '</div>';
									content += '</div>';
								content += '</div>';
							}
						}
					}

					if(Object.keys(this.skuItemObject.workingHours).length > 0) {
						content += '<div class="slide-panel__contacts-item" data-entity="working-hours"' + (this.skuItemObject.workingHoursToday ? 'style="display: none;"' : '') + '>';
							var key = 0;
							for(var i in this.skuItemObject.workingHours) {
								if(this.skuItemObject.workingHours.hasOwnProperty(i)) {										
									content += '<div class="slide-panel__contacts-item__hours-today">';
										content += '<div class="slide-panel__contacts-item__today-container">';
											content += '<div class="slide-panel__contacts-item__today">';
												if(key == 0) {
													content += '<span class="slide-panel__contacts-item__today-icon"><i class="icon-clock"></i></span>';
												}
												content += '<span class="slide-panel__contacts-item__today-title">' + (this.skuItemObject.workingHoursToday && this.skuItemObject.workingHoursToday.hasOwnProperty(i) ? BX.message('CATALOG_ELEMENT_OBJECT_TODAY') : this.skuItemObject.workingHours[i].NAME) + '</span>';
												if(this.skuItemObject.workingHoursToday && this.skuItemObject.workingHoursToday.hasOwnProperty(i) && this.skuItemObject.workingHoursToday[i].STATUS) {
													content += '<span class="slide-panel__contacts-item__today-status slide-panel__contacts-item__today-status-' + (this.skuItemObject.workingHoursToday[i].STATUS == 'OPEN' ? 'open' : 'closed') + '"></span>';
												}
											content += '</div>';
										content += '</div>';
										content += '<div class="slide-panel__contacts-item__hours-break">';
											content += '<div class="slide-panel__contacts-item__hours' + (key == 0 ? ' slide-panel__contacts-item__hours-first' : '') + '">';
												content += '<span class="slide-panel__contacts-item__hours-title">';
													if(this.skuItemObject.workingHours[i].WORK_START && this.skuItemObject.workingHours[i].WORK_END) {
														if(this.skuItemObject.workingHours[i].WORK_START != this.skuItemObject.workingHours[i].WORK_END) {
															content += this.skuItemObject.workingHours[i].WORK_START + ' - ' + this.skuItemObject.workingHours[i].WORK_END;
														} else {
															content += BX.message('CATALOG_ELEMENT_OBJECT_24_HOURS');
														}
													} else {
														content += BX.message('CATALOG_ELEMENT_OBJECT_OFF');
													}
												content += '</span>';
												if(this.skuItemObject.workingHoursToday && key == 0) {
													content += '<span class="slide-panel__contacts-item__hours-icon"><i class="icon-arrow-up"></i></span>';
												}
											content += '</div>';
											if(this.skuItemObject.workingHours[i].WORK_START && this.skuItemObject.workingHours[i].WORK_END) {
												if(this.skuItemObject.workingHours[i].WORK_START != this.skuItemObject.workingHours[i].WORK_END) {
													if(this.skuItemObject.workingHours[i].BREAK_START && this.skuItemObject.workingHours[i].BREAK_END) {
														if(this.skuItemObject.workingHours[i].BREAK_START != this.skuItemObject.workingHours[i].BREAK_END) {
															content += '<div class="slide-panel__contacts-item__break">';
																content += BX.message('CATALOG_ELEMENT_OBJECT_BREAK') + ' ' + this.skuItemObject.workingHours[i].BREAK_START + ' - ' + this.skuItemObject.workingHours[i].BREAK_END;
															content += '</div>';
														}
													}
												}
											}
										content += '</div>';
									content += '</div>';
									key++;
								}
							}
						content += '</div>';
					}
					
					if(Object.keys(this.skuItemObject.phone).length > 0) {
						for(var i in this.skuItemObject.phone) {
							if(this.skuItemObject.phone.hasOwnProperty(i)) {
								content += '<div class="slide-panel__contacts-item">';
									content += '<div class="slide-panel__contacts-item__block">';
										content += '<div class="slide-panel__contacts-item__icon"><i class="icon-phone"></i></div>';
										content += '<div class="slide-panel__contacts-item__text">';
											content += '<a class="slide-panel__contacts-item__phone slide-panel__contacts-item__link" href="tel:' + this.skuItemObject.phone[i].replace(/[^\d\+]/g,'') + '">' + this.skuItemObject.phone[i] + '</a>';
											if(this.skuItemObject.phoneDescription.hasOwnProperty(i) && this.skuItemObject.phoneDescription[i].length > 0) {
												content += '<span class="slide-panel__contacts-item__descr">' + this.skuItemObject.phoneDescription[i] + '</span>';
											}
										content += '</div>';
									content += '</div>';
								content += '</div>';
							}
						}
					}
					
					if(Object.keys(this.skuItemObject.email).length > 0) {
						for(var i in this.skuItemObject.email) {
							if(this.skuItemObject.email.hasOwnProperty(i)) {
								content += '<div class="slide-panel__contacts-item">';
									content += '<div class="slide-panel__contacts-item__block">';
										content += '<div class="slide-panel__contacts-item__icon"><i class="icon-mail"></i></div>';
										content += '<div class="slide-panel__contacts-item__text">';
											content += '<a class="slide-panel__contacts-item__link" href="mailto:' + this.skuItemObject.email[i] + '">' + this.skuItemObject.email[i] + '</a>';
											if(this.skuItemObject.emailDescription.hasOwnProperty(i) && this.skuItemObject.emailDescription[i].length > 0) {
												content += '<span class="slide-panel__contacts-item__descr">' + this.skuItemObject.emailDescription[i] + '</span>';
											}
										content += '</div>';
									content += '</div>';
								content += '</div>';
							}
						}
					}

					if(Object.keys(this.skuItemObject.skype).length > 0) {
						for(var i in this.skuItemObject.skype) {
							if(this.skuItemObject.skype.hasOwnProperty(i)) {
								content += '<div class="slide-panel__contacts-item">';
									content += '<div class="slide-panel__contacts-item__block">';
										content += '<div class="slide-panel__contacts-item__icon"><i class="fa fa-skype"></i></div>';
										content += '<div class="slide-panel__contacts-item__text">';
											content += '<a class="slide-panel__contacts-item__link" href="skype:' + this.skuItemObject.skype[i] + '?chat">' + this.skuItemObject.skype[i] + '</a>';
											if(this.skuItemObject.skypeDescription.hasOwnProperty(i) && this.skuItemObject.skypeDescription[i].length > 0) {
												content += '<span class="slide-panel__contacts-item__descr">' + this.skuItemObject.skypeDescription[i] + '</span>';
											}
										content += '</div>';
									content += '</div>';
								content += '</div>';
							}
						}
					}
				
				content += '</div>';
			}

			this.skuItemObject.content = content;

			BX.onCustomEvent(this, 'skuItemObjectContactsAdjusted', [sPanelContent]);
		},

		objectContactsFormRequest: function(sPanelContent) {
			BX.ajax({
				url: BX.message('SITE_DIR') + 'ajax/slide_panel.php',
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: {
					action: !!this.obSkuItems && this.offersView == 'OBJECTS' && !this.skuItemObject.id ? 'callback' : 'callback_objects'
				},
				onsuccess: BX.delegate(function(result) {
					if(!result.content || !result.JS) {
						sPanelContent.innerHTML = !!this.obSkuItems && this.offersView == 'OBJECTS' ? this.skuItemObject.content : this.object.content;
					} else {
						BX.ajax.processScripts(
							BX.processHTML(result.JS).SCRIPT,
							false,
							BX.delegate(function() {
								var processed = BX.processHTML(result.content),
									temporaryNode = BX.create('DIV');

								temporaryNode.innerHTML = processed.HTML;

								var sPanelFormObjectIdInput = temporaryNode.querySelector('[name="OBJECT_ID"]');
								if(!!sPanelFormObjectIdInput)
									sPanelFormObjectIdInput.value = !!this.obSkuItems && this.offersView == 'OBJECTS' ? this.skuItemObject.id : this.object.id;
								
								sPanelContent.innerHTML = (!!this.obSkuItems && this.offersView == 'OBJECTS' ? this.skuItemObject.content : this.object.content) + temporaryNode.innerHTML;
								
								BX.ajax.processScripts(processed.SCRIPT);
							}, this)
						);
					}
					
					$(sPanelContent).scrollbar();
				}, this)
			});
		},

		objectContactsForm: function(e) {
			if(!!this.sPanel) {
				this.sPanel.appendChild(
					BX.create('DIV', {
						props: {
							className: 'slide-panel__title-wrap'
						},
						children: [
							BX.create('I', {
								props: {
									className: 'icon-phone-call'
								}
							}),						
							BX.create('SPAN', {
								props: {
									className: 'slide-panel__title'
								},
								html: !!this.obSkuItems && this.offersView == 'OBJECTS' ? this.skuItemObject.name : this.object.name
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

				this.sPanel.appendChild(
					BX.create('DIV', {
						props: {
							className: 'slide-panel__content scrollbar-inner'
						},
						children: [
							BX.create('DIV', {
								props: {
									className: 'slide-panel__loader'
								},
								html: '<div><span></span></div>'
							})
						]
					})
				);

				var sPanelContent = this.sPanel.querySelector('.slide-panel__content');
				if(!!sPanelContent)
					BX.onCustomEvent(this, !!this.obSkuItems && this.offersView == 'OBJECTS' ? 'adjustSkuItemObjectContacts' : 'getObjectWorkingHoursToday', [sPanelContent]);

				if(!this.popupPanel) {
					var scrollWidth = window.innerWidth - document.body.clientWidth;
					if(scrollWidth > 0) {
						BX.style(document.body, 'padding-right', scrollWidth + 'px');

						var pageBg = document.querySelector('.page-bg');
						if(!!pageBg)
							BX.style(pageBg, 'margin-right', scrollWidth + 'px');
						
						var topPanel = document.querySelector('.top-panel');
						if(!!topPanel) {
							if(BX.hasClass(topPanel, 'fixed'))
								BX.style(topPanel, 'padding-right', scrollWidth + 'px');
							
							var topPanelThead = topPanel.querySelector('.top-panel__thead');
							if(!!topPanelThead && BX.hasClass(topPanelThead, 'fixed'))
								BX.style(topPanelThead, 'padding-right', scrollWidth + 'px');
							
							var topPanelTfoot = topPanel.querySelector('.top-panel__tfoot');
							if(!!topPanelTfoot && BX.hasClass(topPanelTfoot, 'fixed'))
								BX.style(topPanelTfoot, 'padding-right', scrollWidth + 'px');
						}

						if(!!this.obTabsBlock && !!this.tabsPanelFixed)
							BX.style(this.obTabsBlock, 'padding-right', scrollWidth + 'px');
					}

					var scrollTop = BX.GetWindowScrollPos().scrollTop;
					if(!!scrollTop && scrollTop > 0)
						BX.style(document.body, 'top', '-' + scrollTop + 'px');
				}

				BX.addClass(document.body, 'slide-panel-active');
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
		},
		
		objectContactsRequest: function(sPanelContent) {
			sPanelContent.innerHTML = !!this.obSkuItems && this.offersView == 'OBJECTS' ? this.skuItemObject.content : this.object.content;
			$(sPanelContent).scrollbar();
		},

		objectContacts: function(e) {
			if(!!this.sPanel) {
				this.sPanel.appendChild(
					BX.create('DIV', {
						props: {
							className: 'slide-panel__title-wrap'
						},
						children: [
							BX.create('I', {
								props: {
									className: 'icon-phone-call'
								}
							}),						
							BX.create('SPAN', {
								props: {
									className: 'slide-panel__title'
								},
								html: !!this.obSkuItems && this.offersView == 'OBJECTS' ? this.skuItemObject.name : this.object.name
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

				this.sPanel.appendChild(
					BX.create('DIV', {
						props: {
							className: 'slide-panel__content scrollbar-inner'
						},
						children: [
							BX.create('DIV', {
								props: {
									className: 'slide-panel__loader'
								},
								html: '<div><span></span></div>'
							})
						]
					})
				);

				var sPanelContent = this.sPanel.querySelector('.slide-panel__content');
				if(!!sPanelContent)
					BX.onCustomEvent(this, !!this.obSkuItems && this.offersView == 'OBJECTS' ? 'adjustSkuItemObjectContacts' : 'getObjectWorkingHoursToday', [sPanelContent]);

				if(!this.popupPanel) {
					var scrollWidth = window.innerWidth - document.body.clientWidth;
					if(scrollWidth > 0) {
						BX.style(document.body, 'padding-right', scrollWidth + 'px');

						var pageBg = document.querySelector('.page-bg');
						if(!!pageBg)
							BX.style(pageBg, 'margin-right', scrollWidth + 'px');
						
						var topPanel = document.querySelector('.top-panel');
						if(!!topPanel) {
							if(BX.hasClass(topPanel, 'fixed'))
								BX.style(topPanel, 'padding-right', scrollWidth + 'px');
							
							var topPanelThead = topPanel.querySelector('.top-panel__thead');
							if(!!topPanelThead && BX.hasClass(topPanelThead, 'fixed'))
								BX.style(topPanelThead, 'padding-right', scrollWidth + 'px');
							
							var topPanelTfoot = topPanel.querySelector('.top-panel__tfoot');
							if(!!topPanelTfoot && BX.hasClass(topPanelTfoot, 'fixed'))
								BX.style(topPanelTfoot, 'padding-right', scrollWidth + 'px');
						}

						if(!!this.obTabsBlock && !!this.tabsPanelFixed)
							BX.style(this.obTabsBlock, 'padding-right', scrollWidth + 'px');
					}

					var scrollTop = BX.GetWindowScrollPos().scrollTop;
					if(!!scrollTop && scrollTop > 0)
						BX.style(document.body, 'top', '-' + scrollTop + 'px');
				}
				
				BX.addClass(document.body, 'slide-panel-active');
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
		},
		
		partnerSiteRedirect: function() {
			if(!!this.obSkuItems)
				this.checkCurrentSkuItem(BX.proxy_context);

			var newTab = window.open('', '_blank'),
				productId;

			switch(this.productType) {
				case 1: //product
				case 2: //set
					productId = this.product.id;
					break;
				case 3: //sku
					if(!this.obSkuItems)
						productId = this.offers[this.offerNum].ID;
					else
						productId = this.skuItem.ID;
					break;
			}
			
			BX.ajax({
				url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: {							
					action: 'partnerSiteRedirect',
					productId: productId
				},
				onsuccess: function(result) {
					if(!!result.partnersUrl)
						newTab.location = result.partnersUrl;
				}
			});
		},

		sPanelFormRequest: function(action, productId, offerId, productName, productLink, objectId, sPanelContent) {
			BX.ajax({
				url: BX.message('SITE_DIR') + 'ajax/slide_panel.php',
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: {
					action: action
				},
				onsuccess: BX.delegate(function(result) {
					if(!result.content || !result.JS) {
						BX.cleanNode(sPanelContent);
						sPanelContent.appendChild(BX.create('DIV', {
							props: {
								className: 'slide-panel__form'
							},
							children: [
								BX.create('DIV', {							
									props: {
										className: 'alert alert-error alert-show'
									},
									html: BX.message('SLIDE_PANEL_UNDEFINED_ERROR')
								})
							]
						}));
					} else {
						BX.ajax.processScripts(
							BX.processHTML(result.JS).SCRIPT,
							false,
							BX.delegate(function() {
								var processed = BX.processHTML(result.content),
									temporaryNode = BX.create('DIV');

								temporaryNode.innerHTML = processed.HTML;
								
								var sPanelTitle = this.sPanel.querySelector('.slide-panel__title'),
									sPanelFormTitle = temporaryNode.querySelector('.slide-panel__form-title'),
									sPanelFormBtn = temporaryNode.querySelector('[type="submit"]');
								if(!!sPanelFormTitle) {
									sPanelTitle.innerHTML = sPanelFormTitle.innerHTML;
									if(!!sPanelFormBtn)
										sPanelFormBtn.innerHTML = '<span>' + sPanelFormTitle.innerHTML + '</span>';
									BX.remove(sPanelFormTitle);
								}

								var sPanelFormObjectIdInput = temporaryNode.querySelector('[name="OBJECT_ID"]');
								if(!!sPanelFormObjectIdInput && !!objectId)
									sPanelFormObjectIdInput.value = objectId;
								
								var sPanelFormProductIdInput = temporaryNode.querySelector('[name="PRODUCT_ID"]');
								if(!!sPanelFormProductIdInput && !!productId)
									sPanelFormProductIdInput.value = productId;

								var sPanelFormOfferIdInput = temporaryNode.querySelector('[name="OFFER_ID"]');
								if(!!sPanelFormOfferIdInput && !!offerId)
									sPanelFormOfferIdInput.value = offerId;

								var sPanelFormProductLinkInput = temporaryNode.querySelector('[name="PRODUCT_LINK"]');
								if(!!sPanelFormProductLinkInput && !!productLink)
									sPanelFormProductLinkInput.value = productLink;
								
								var sPanelFormMessageInput = temporaryNode.querySelector('[name="MESSAGE"]');
								if(!!sPanelFormMessageInput && !!productName) {
									var sPanelFormMessageInputPh = sPanelFormMessageInput.getAttribute('placeholder');
									BX.adjust(sPanelFormMessageInput, {
										attrs: {
											'placeholder': !!sPanelFormMessageInputPh ? (sPanelFormMessageInputPh + ' "' + productName + '"') : productName
										}
									});
								}

								sPanelContent.innerHTML = temporaryNode.innerHTML;
								
								BX.ajax.processScripts(processed.SCRIPT);
							}, this)
						);
					}
					
					$(sPanelContent).scrollbar();
				}, this)
			});
		},

		sPanelForm: function(e) {
			if(!!this.sPanel) {
				if(!!this.obSkuItems)
					this.checkCurrentSkuItem(BX.proxy_context);
				
				var target = BX.proxy_context && BX.proxy_context.getAttribute(!this.obSkuItems ? 'id' : 'data-entity'),
					action,
					iconClass,
					productId = this.product.id,
					offerId,
					productName,
					productLink = window.location.href.split('?')[0],
					objectId = !!this.obSkuItems && this.offersView == 'OBJECTS' ? this.skuItemObject.id : this.object.id;
				
				if((!this.obSkuItems && target == this.visual.ASK_PRICE_LINK) || (!!this.obSkuItems && target == 'ask-price')) {
					action = !!objectId ? 'ask_price_objects' : 'ask_price';
					iconClass = 'icon-comment';
				} else if((!this.obSkuItems && target == this.visual.NOT_AVAILABLE_MESS) || (!!this.obSkuItems && target == 'not-available')) {
					action = !!objectId ? 'not_available_objects' : 'not_available';
					iconClass = 'icon-clock';
				}
				
				switch(this.productType) {
					case 1: //product
					case 2: //set
						productName = this.product.name;
						break;
					case 3: //sku
						offerId = !this.obSkuItems ? this.offers[this.offerNum].ID : this.skuItem.ID;
						productName = !this.obSkuItems ? this.offers[this.offerNum].NAME : this.skuItem.NAME;
						productLink += '?OFFER_ID=' + offerId; 
						break;
				}

				this.sPanel.appendChild(
					BX.create('DIV', {
						props: {
							className: 'slide-panel__title-wrap'
						},
						children: [
							BX.create('I', {
								props: {
									className: iconClass
								}
							}),						
							BX.create('SPAN', {
								props: {
									className: 'slide-panel__title'
								}
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

				this.sPanel.appendChild(
					BX.create('DIV', {
						props: {
							className: 'slide-panel__content scrollbar-inner'
						},
						children: [
							BX.create('DIV', {
								props: {
									className: 'slide-panel__loader'
								},
								html: '<div><span></span></div>'
							})
						]
					})
				);
							
				var sPanelContent = this.sPanel.querySelector('.slide-panel__content');
				if(!!sPanelContent)
					BX.onCustomEvent(this, 'sPanelFormRequest', [action, productId, offerId, productName, productLink, objectId, sPanelContent]);
				
				if(!this.popupPanel) {
					var scrollWidth = window.innerWidth - document.body.clientWidth;
					if(scrollWidth > 0) {
						BX.style(document.body, 'padding-right', scrollWidth + 'px');

						var pageBg = document.querySelector('.page-bg');
						if(!!pageBg)
							BX.style(pageBg, 'margin-right', scrollWidth + 'px');
						
						var topPanel = document.querySelector('.top-panel');
						if(!!topPanel) {
							if(BX.hasClass(topPanel, 'fixed'))
								BX.style(topPanel, 'padding-right', scrollWidth + 'px');
							
							var topPanelThead = topPanel.querySelector('.top-panel__thead');
							if(!!topPanelThead && BX.hasClass(topPanelThead, 'fixed'))
								BX.style(topPanelThead, 'padding-right', scrollWidth + 'px');
							
							var topPanelTfoot = topPanel.querySelector('.top-panel__tfoot');
							if(!!topPanelTfoot && BX.hasClass(topPanelTfoot, 'fixed'))
								BX.style(topPanelTfoot, 'padding-right', scrollWidth + 'px');
						}
						
						if(!!this.obTabsBlock && !!this.tabsPanelFixed)
							BX.style(this.obTabsBlock, 'padding-right', scrollWidth + 'px');
					}

					var scrollTop = BX.GetWindowScrollPos().scrollTop;
					if(!!scrollTop && scrollTop > 0)
						BX.style(document.body, 'top', '-' + scrollTop + 'px');
				}
				
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
		},
		
		changeMoreProductsSectionLink: function(event) {
			var target = event.target;
			if(target.getAttribute('data-entity') == 'moreProductsSectionsLink' && !BX.hasClass(target, 'active')) {
				var moreProductsSectionLink = this.getEntities(this.obMoreProductsSectionsLinks, 'moreProductsSectionsLink');
				for(var i in moreProductsSectionLink) {
					if(moreProductsSectionLink.hasOwnProperty(i) && BX.type.isDomNode(moreProductsSectionLink[i])) {
						BX.removeClass(moreProductsSectionLink[i], 'active');
					}
				}

				BX.addClass(target, 'active');
				
				var sectionId = target.getAttribute('data-section-id');
				if(sectionId) {
					var itemProductsContainer = this.obProduct.querySelector('.product-item-detail-more-products');
					if(!!itemProductsContainer) {					
						itemProductsContainer.style.opacity = 0.2;
						BX.ajax({
							url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
							method: 'POST',
							dataType: 'json',
							timeout: 60,
							data: {
								'action': 'changeMoreProductsSectionLink',
								'requestUri': window.location.href,
								'siteId': BX.message('SITE_ID'),
								'parameters': BX.message('CATALOG_ELEMENT_PARAMETERS'),
								'productsIds': this.moreProducts.ids,
								'sectionId': sectionId
							},
							onsuccess: BX.delegate(function(result) {
								if(!result.content || !result.JS)
									return;

								BX.ajax.processScripts(
									BX.processHTML(result.JS).SCRIPT,
									false,
									BX.delegate(function() {
										var processed = BX.processHTML(result.content, false);

										itemProductsContainer.innerHTML = processed.HTML;

										if(result.imgWebp) {
											var srcList = {},
												images = itemProductsContainer.querySelectorAll('img');
											
											if(!!images) {
												for(var i in images) {
													if(images.hasOwnProperty(i)) {
														var imageDataLazyloadSrc = images[i].getAttribute('data-lazyload-src'),
															imageSrc = images[i].getAttribute('src');

														if(!!imageDataLazyloadSrc && imageDataLazyloadSrc.substr(0, 4) !== 'http' && imageDataLazyloadSrc.substr(0, 11) !== 'data:image/' && imageDataLazyloadSrc.indexOf('.webp') == -1) {
															srcList[imageDataLazyloadSrc] = imageDataLazyloadSrc;
														} else if(!!imageSrc && imageSrc.substr(0, 4) !== 'http' && imageSrc.substr(0, 11) !== 'data:image/' && imageSrc.indexOf('.webp') == -1) {
															srcList[imageSrc] = imageSrc;
														}
													}
												}
											}

											if(Object.keys(srcList).length > 0)
												convertImgToWebp(srcList);
										}

										if(result.imgLazyLoad)
											imgLazyLoad();

										new BX.easing({
											duration: 2000,
											start: {opacity: 20},
											finish: {opacity: 100},
											transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
											step: function(state) {
												itemProductsContainer.style.opacity = state.opacity / 100;
											},
											complete: function() {
												itemProductsContainer.removeAttribute('style');
											}
										}).animate();

										BX.ajax.processScripts(processed.SCRIPT);
									}, this)
								);
							}, this)
						});
					}
				}
			}
		},

		geoDelivery: function() {
			if(!!this.obGeoDelivery) {
				var geoDeliveryCity = this.getEntity(this.obGeoDelivery, 'city'),
					geoDeliveryFrom = this.obGeoDelivery.querySelector('.product-item-detail-geo-delivery-from'),
					geoDeliveryPrice = this.obGeoDelivery.querySelector('.product-item-detail-geo-delivery-price'),
					geoDeliveryLoader = this.obGeoDelivery.querySelector('.product-item-detail-geo-delivery-loader');
				
				if(!geoDeliveryLoader && !!geoDeliveryPrice) {
					BX.cleanNode(geoDeliveryPrice);
					geoDeliveryPrice.appendChild(BX.create('DIV', {
						props: {
							className: 'product-item-detail-geo-delivery-loader'
						},
						html: '<div><span></span></div>'
					}));
				}
				
				switch(this.productType) {
					case 1: //product
					case 2: //set
						var productId = this.product.id;
						break;
					case 3: //sku
						var productId = this.offers[this.offerNum].ID;
						break;
				}

				BX.ajax({
					url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
					method: 'POST',
					dataType: 'json',
					timeout: 60,
					data: {
						action: 'geoDelivery',
						siteId: BX.message('SITE_ID'),
						siteServerName: BX.message('SITE_SERVER_NAME'),
						parameters: BX.message('CATALOG_ELEMENT_PARAMETERS'),
						productId: productId
					},
					onsuccess: BX.delegate(function(result) {
						if(!result.data) {
							if(!!geoDeliveryCity)
								geoDeliveryCity.innerHTML = '';
							
							if(!!geoDeliveryFrom)
								geoDeliveryFrom.innerHTML = '';
							
							if(!!geoDeliveryPrice)
								geoDeliveryPrice.innerHTML = '';						
							
							this.obGeoDelivery.style.display = 'none';
						} else {
							if(!!geoDeliveryCity)
								geoDeliveryCity.innerHTML = !!result.data.CITY ? result.data.CITY : BX.message('CATALOG_ELEMENT_GEO_DELIVERY_LOCATION');
							
							if(!!geoDeliveryFrom)
								geoDeliveryFrom.innerHTML = !!result.data.MIN_PRICE ? BX.message('CATALOG_ELEMENT_GEO_DELIVERY_FROM') : BX.message('CATALOG_ELEMENT_GEO_DELIVERY_UNDEFINED');
							
							if(!!geoDeliveryPrice)
								geoDeliveryPrice.innerHTML = !!result.data.MIN_PRICE ? result.data.MIN_PRICE : '';
							
							this.obGeoDelivery.style.display = '';
						}
					}, this)
				});
			}
		},

		sPanelGeoDeliveryRequest: function(sPanelContent) {
			switch(this.productType) {
				case 1: //product
				case 2: //set
					var productId = this.product.id;
					break;
				case 3: //sku
					var productId = this.offers[this.offerNum].ID;
					break;
			}

			BX.ajax({
				url: BX.message('CATALOG_ELEMENT_TEMPLATE_PATH') + '/ajax.php',
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: {
					action: 'sPanelGeoDeliveryRequest',
					siteId: BX.message('SITE_ID'),
					siteServerName: BX.message('SITE_SERVER_NAME'),
					parameters: BX.message('CATALOG_ELEMENT_PARAMETERS'),
					productId: productId,
					geoDeliveryContainerId: this.visual.GEO_DELIVERY_ID
				},
				onsuccess: BX.delegate(function(result) {
					if(!result.content || !result.JS) {
						BX.cleanNode(sPanelContent);
						sPanelContent.appendChild(BX.create('DIV', {
							props: {
								className: 'slide-panel-geo-delivery'
							},
							children: [
								BX.create('DIV', {							
									props: {
										className: 'alert alert-error alert-show'
									},
									html: BX.message('SLIDE_PANEL_UNDEFINED_ERROR')
								})
							]
						}));
					} else {
						BX.ajax.processScripts(
							BX.processHTML(result.JS).SCRIPT,
							false,
							BX.delegate(function() {
								var processed = BX.processHTML(result.content);
								
								sPanelContent.innerHTML = processed.HTML;
								
								BX.ajax.processScripts(processed.SCRIPT);
							}, this)
						);
					}
					
					$(sPanelContent).scrollbar();
				}, this)
			});
		},

		sPanelGeoDelivery: function(e) {
			if(!!this.sPanel) {
				this.sPanel.appendChild(
					BX.create('DIV', {
						props: {
							className: 'slide-panel__title-wrap'
						},
						children: [
							BX.create('I', {
								props: {
									className: 'icon-delivery'
								}
							}),						
							BX.create('SPAN', {
								props: {
									className: 'slide-panel__title'
								},
								html: BX.message('CATALOG_ELEMENT_GEO_DELIVERY_SLIDE_PANEL_TITLE')
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
									
				this.sPanel.appendChild(
					BX.create('DIV', {
						props: {
							className: 'slide-panel__content scrollbar-inner'
						},
						children: [
							BX.create('DIV', {
								props: {
									className: 'slide-panel__loader'
								},
								html: '<div><span></span></div>'
							})
						]
					})
				);
							
				var sPanelContent = this.sPanel.querySelector('.slide-panel__content');
				if(!!sPanelContent)
					BX.onCustomEvent(this, 'sPanelGeoDeliveryRequest', [sPanelContent]);
				
				if(!this.popupPanel) {
					var scrollWidth = window.innerWidth - document.body.clientWidth;
					if(scrollWidth > 0) {
						BX.style(document.body, 'padding-right', scrollWidth + 'px');

						var pageBg = document.querySelector('.page-bg');
						if(!!pageBg)
							BX.style(pageBg, 'margin-right', scrollWidth + 'px');
						
						var topPanel = document.querySelector('.top-panel');
						if(!!topPanel) {
							if(BX.hasClass(topPanel, 'fixed'))
								BX.style(topPanel, 'padding-right', scrollWidth + 'px');
							
							var topPanelThead = topPanel.querySelector('.top-panel__thead');
							if(!!topPanelThead && BX.hasClass(topPanelThead, 'fixed'))
								BX.style(topPanelThead, 'padding-right', scrollWidth + 'px');
							
							var topPanelTfoot = topPanel.querySelector('.top-panel__tfoot');
							if(!!topPanelTfoot && BX.hasClass(topPanelTfoot, 'fixed'))
								BX.style(topPanelTfoot, 'padding-right', scrollWidth + 'px');
						}

						if(!!this.obTabsBlock && !!this.tabsPanelFixed)
							BX.style(this.obTabsBlock, 'padding-right', scrollWidth + 'px');
					}

					var scrollTop = BX.GetWindowScrollPos().scrollTop;
					if(!!scrollTop && scrollTop > 0)
						BX.style(document.body, 'top', '-' + scrollTop + 'px');
				}
				
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
		},

		removeNodes: function() {
			if(!this.popupPanel) {
				var sectionPic = document.body.querySelector('.catalog-section-pic');
				if(!!sectionPic)
					BX.remove(sectionPic);

				var sectionPanelWrapper = document.body.querySelector('.catalog-section-panel-wrapper');
				if(!!sectionPanelWrapper)
					BX.remove(sectionPanelWrapper);
			}
		},
			
		basketRedirect: function() {
			window.location.href = (this.basketData.basketUrl ? this.basketData.basketUrl : BX.message('CATALOG_ELEMENT_BASKET_URL'));
		},
		
		incViewedCounter: function() {
			if(this.currentIsSet && !this.updateViewedCount) {
				switch(this.productType) {
					case 1:
					case 2:
						this.viewedCounter.params.PRODUCT_ID = this.product.id;
						this.viewedCounter.params.PARENT_ID = this.product.id;
						break;
					case 3:
						this.viewedCounter.params.PARENT_ID = this.product.id;
						this.viewedCounter.params.PRODUCT_ID = this.offers[this.offerNum].ID;
						break;
					default:
						return;
				}

				this.viewedCounter.params.SITE_ID = BX.message('SITE_ID');
				this.updateViewedCount = true;
				BX.ajax.post(
					this.viewedCounter.path,
					this.viewedCounter.params,
					BX.delegate(function() {
						this.updateViewedCount = false;
					}, this)
				);
			}
		},

		allowViewedCount: function(update) {
			this.currentIsSet = true;

			if(update) {
				this.incViewedCounter();
			}
		}
	}
})(window);