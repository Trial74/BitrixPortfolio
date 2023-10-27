let routes = [
	{
		path: '/',
		url: '/?' + MOBILE_GET + '=Y',
	},
	{
		path: '/page-:pageName/:pageData?/',
		async: function (routeTo, routeFrom, resolve, reject) {
			var router = this;

			var app = router.app;

			if( window.reinitPage !== true )
				app.preloader.show();

			window.reinitPage = false;

			var data = {
				page: routeTo.params.pageName.split('.').join('/')
			}

			data[MOBILE_GET] =  'Y'; // Временный параметр для отладки

			if( routeTo.params.pageData != undefined ){
				
				//routeTo.params.pageData = routeTo.params.pageData.split('--').join('=').split('qq').join('?');
				var splited = routeTo.params.pageData.split('|');

				if( splited.length > 0 ){
					for( var key in splited ){
						var splitedParam = splited[key].split('=');
						
						if( splitedParam[1] != undefined )
							data[splitedParam[0]] = splitedParam[1];//.split('--').join('=').split('qq').join('?');
					}
				}
			}

			app.request.get(SITE_TEMPLATE_PATH + 'pages/index.php', data, function (data) {
				app.preloader.hide();

				resolve(
				{
					template: '{{ data }}',
				},
				{
					animate: false,
					context: { data: data }
				}
				);

				app.methods.cartUpdate();
			});
		}
	},
	{
		path: '(.*)',
		url: SITE_TEMPLATE_PATH + 'pages/index.php?page=404',
	}
];

export default routes;