const path = require('path');
const webpack = require( 'webpack' );

module.exports = {
	entry: {
		foundation: "./assets/js/foundation.js",
	},
	output: {
		filename: 'foundation.min.js',
		path: path.resolve(__dirname, 'assets/js'),
	},
	mode: 'production',
	module: {
		rules: [
			{
				test: /\.(js)$/,
				exclude: /node_modules/,
				use: {
					loader: "babel-loader",
					options: {
						presets: ['@babel/preset-env']
					}
				}
			},
		]
	},
	externals: {
		"jquery": "jQuery"
	},
	plugins: [
		new webpack.ProvidePlugin( {
			$: "jquery",
			jQuery: "jquery"
		} ),
	]
};