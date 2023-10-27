let mix = require('laravel-mix');
let path = require('path');

mix.webpackConfig({
	module: {
		loaders: [
			{
				test: /\.css$/, loaders: [
					'css-loader'
				]
			}
		],
		rules: [
			{
				test: /\.js$/,
				loader: 'babel-loader',
				query: {
					presets: ['env'],
					plugins: ["transform-object-assign"]
				}
			}
		]
	}
}).setPublicPath('./');


mix.js('./js/app.js', './js/scripts.js');
/*
mix.styles([
	'./node_modules/framework7/css/framework7.css',
	'./node_modules/@fortawesome/fontawesome-free/css/solid.min.css',
	'./node_modules/@fortawesome/fontawesome-free/css/brands.min.css',
	'./node_modules/@fortawesome/fontawesome-free/css/fontawesome.min.css',
	'./assets/css/styles.css'
], './public/styles.css');
*/