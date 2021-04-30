const merge = require('webpack-merge');
const { DefinePlugin } = require('webpack');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const common = require('./webpack.common.js');

const { localhost: proxy } = require('./proxyServer.json');

module.exports = merge(common, {
    mode: 'development',
    devtool: 'cheap-module-source-map',
    optimization: { minimize: false },
    watch: true,
    watchOptions: {
        ignored: ['node_modules/**'],
    },
    plugins: [
        new BrowserSyncPlugin({ files: '**/*.php', proxy }),
        new DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify('development'),
        }),
    ],
});
