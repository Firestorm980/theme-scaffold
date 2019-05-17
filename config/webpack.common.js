/* global process, module, require */

const path = require( 'path' );
const CleanWebpackPlugin = require( 'clean-webpack-plugin' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const FixStyleOnlyEntriesPlugin = require( 'webpack-fix-style-only-entries' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const StyleLintPlugin = require( 'stylelint-webpack-plugin' );
const WebpackBar = require( 'webpackbar' );
const merge = require( 'webpack-merge' );

const isProduction = 'production' === process.env.NODE_ENV;

// Config files.
const settings = require( './webpack.settings.js' );

/**
* Configure CSS entries.
*/
const configureEntriesCss = () => {
	const entries = {};

	for ( const [ key, value ] of Object.entries( settings.entries.css ) ) {
		entries[ key ] = path.resolve( process.cwd(), value );
	}

	return entries;
};

/**
* Configure JS entries.
*/
const configureEntriesJs = () => {
	const entries = {};

	for ( const [ key, value ] of Object.entries( settings.entries.js ) ) {
		entries[ key ] = path.resolve( process.cwd(), value );
	}

	return entries;
};

/**
* Configure ES Lint Loader
*/
const configureEslintLoader = () => {
	return {
		test: /\.js$/,
		enforce: 'pre',
		loader: 'eslint-loader',
		options: {
			fix: true,
		}
	};
};

/**
* Configure Babel Loader
* @param {Array} browserlist Array of browsers to pass.
*/
const configureBabelLoader = ( browserlist ) => {
	return {
		test: /\.js$/,
		use: {
			loader: 'babel-loader',
			options: {
				babelrc: false,
				exclude: [
					/core-js/,
					/regenerator-runtime/,
				],
				presets: [
					['@babel/preset-env', {
						loose: true,
						modules: false,
						// debug: true,
						corejs: 3,
						useBuiltIns: 'usage',
						targets: {
							browsers: browserlist,
						},
					}],
				],
				plugins: ['@babel/plugin-syntax-dynamic-import'],
				cacheDirectory: true,
				sourceMap: ! isProduction,
			},
		},
	};
};

/**
* Configure Styles Loader
*/
const configureStylesLoader = () => {
	return {
		test: /\.css$/,
		use: [
			{
				loader: MiniCssExtractPlugin.loader,
			},
			{
				loader: 'css-loader',
				options: {
					sourceMap: ! isProduction,
					// We copy fonts etc. using CopyWebpackPlugin.
					url: false,
				},
			},
			{
				loader: 'postcss-loader',
				options: {
					sourceMap: ! isProduction,
				},
			},
		],
	};
};

const defaults = {
	output: {
		path: path.resolve( process.cwd(), settings.paths.dist.base ),
	},

	// Console stats output.
	// @link https://webpack.js.org/configuration/stats/#stats
	stats: settings.stats,

	// External objects.
	externals: {
		jquery: 'jQuery',
	},

	// Performance settings.
	performance: {
		maxAssetSize: settings.performance.maxAssetSize,
	},

	plugins: [

		// Remove the extra JS files Webpack creates for CSS entries.
		// This should be fixed in Webpack 5.
		new FixStyleOnlyEntriesPlugin( {
			silent: true,
		} ),

		// Extract CSS into individual files.
		new MiniCssExtractPlugin( {
			filename: settings.filename.css,
			chunkFilename: '[id].css',
		} ),

		// Copy static assets to the `dist` folder.
		new CopyWebpackPlugin( [
			{
				from: settings.copyWebpackConfig.from,
				to: settings.copyWebpackConfig.to,
				context: path.resolve( process.cwd(), settings.paths.src.base ),
			},
		] ),

		// Lint CSS.
		new StyleLintPlugin( {
			context: path.resolve( process.cwd(), settings.paths.src.css ),
			files: '**/*.css',
		} ),

		// Fancy WebpackBar.
		new WebpackBar(),
	],
};

const es5 = merge.smart( defaults, {
	name: 'es5',
	entry: configureEntriesJs(),
	output: {
		filename: settings.filename.es5,
	},
	module: {
		rules: [
			configureEslintLoader(),
			configureBabelLoader( [
				'> 1%',
				'last 2 versions',
				'Firefox ESR',
			] ),
		]
	},
	plugins: [
		// Clean the `dist` folder on build.
		new CleanWebpackPlugin( {
			cleanAfterEveryBuildPatterns: ['**/*.js']
		} ),
	]
} );

const es6 = merge.smart( defaults, {
	name: 'es6',
	entry: configureEntriesJs(),
	output: {
		filename: settings.filename.js,
	},
	module: {
		rules: [
			configureEslintLoader(),
			configureBabelLoader( [
				// The last two versions of each browser, excluding versions
				// that don't support <script type="module">.
				'last 2 Chrome versions', 'not Chrome < 60',
				'last 2 Safari versions', 'not Safari < 10.1',
				'last 2 iOS versions', 'not iOS < 10.3',
				'last 2 Firefox versions', 'not Firefox < 54',
				'last 2 Edge versions', 'not Edge < 15',
			] ),
		]
	},
	plugins: [
		// Clean the `dist` folder on build.
		new CleanWebpackPlugin( {
			cleanAfterEveryBuildPatterns: ['**/*.mjs']
		} ),
	]
} );

const styles = merge.smart( defaults, {
	name: 'styles',
	entry: configureEntriesCss(),
	module: {
		rules: [
			configureStylesLoader()
		]
	},
	plugins: [
		// Clean the `dist` folder on build.
		new CleanWebpackPlugin( {
			cleanAfterEveryBuildPatterns: ['**/*.css']
		} ),
	]
} );

module.exports = [
	es5,
	es6,
	styles
];
