const urlapi = require('url');
const siteUrl = '%SITE_URL%', // example `http://site-url.com/`
	themeName = '%THEME_NAME%'; // example `project-name`. Theme name should not have spaces!!!
const URL = urlapi.parse(siteUrl);

module.exports = {
	files: ["assets/css/*.css","*.php", "parts/**/*.php", "templates/*.php", "assets/js/global.js"],
	proxy: siteUrl,
	serveStatic: ["./"],
	reloadDelay: 1000,

	rewriteRules: [
		{
			match: new RegExp( URL.path.substring(1) + "wp-content/themes/" + themeName + "/assets/css",'g' ),
			fn: function () {
				return "assets/css"
			}
		},
		{
			match: new RegExp( URL.path.substring(1) + "wp-content/themes/" + themeName + "/assets/js",'g' ),
			fn: function () {
				return "assets/js"
			}
		},
		{
			match: /AIzaSyBgg23TIs_tBSpNQa8RC0b7fuV4SOVN840/g,
			replace: "AIzaSyAZteVk16ICKxgLgH87g1D0nnG5_bC2xPI"
		}
	],
};