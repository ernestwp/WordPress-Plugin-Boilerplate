const path                    = require( 'path' );
const CleanWebpackPlugin      = require( 'clean-webpack-plugin' );
const MiniCssExtractPlugin    = require( 'mini-css-extract-plugin' );
const OptimizeCssAssetsPlugin = require( 'optimize-css-assets-webpack-plugin' );
const CopyWebpackPlugin       = require( 'copy-webpack-plugin' );

module.exports = {
	watch: false,
	devtool: 'source-map',
	entry: {
		backend: './src/assets/src/backend/index.js',
		frontend: './src/assets/src/frontend/index.js',
	},
	output: {
		path: path.join( __dirname, './src/assets/dist/' ),
		filename: '[name]/bundle.min.js'
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env']
					}
				}
			},
			{
				test: /\.scss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'sass-loader'
				]
			},
			{
				test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
				include: path.join( __dirname, './src/assets/src/frontend/' ),
				exclude: /node_modules/,
				loader: 'url-loader',
				options: {
					limit: 10, // Convert images < 10kb to base64 strings
					name: 'img/[name].[ext]',
				},
			},
		],
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: '[name]/bundle.min.css'
		} ),
		new CleanWebpackPlugin( [
			'./src/assets/dist/*'
		] ),
		new CopyWebpackPlugin( [
			{
				from: './src/assets/src/backend/img',
				to: './backend/img'
			},
			{
				from: './src/assets/src/frontend/img',
				to: './frontend/img'
			}
		] ),
		new OptimizeCssAssetsPlugin( {
			assetNameRegExp: /\.css$/g,
			cssProcessor: require( 'cssnano' ),
			cssProcessorOptions: {
				map: {
					inline: false
				}
			},
			cssProcessorPluginOptions: {
				preset: ['default', {
					discardComments: {
						removeAll: true
					}
				}]
			},
			canPrint: true
		} )
	]
};