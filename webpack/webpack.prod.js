const merge = require('webpack-merge');
const { DefinePlugin } = require('webpack');
const ReplaceInFileWebpackPlugin = require('replace-in-file-webpack-plugin');

const common = require('./webpack.common.js');

const { pathTheme } = require('./constants');

module.exports = merge(common, {
    mode: 'production',
    devtool: false,
    optimization: { minimize: true },
    plugins: [
        new DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify('production'),
        }),
        new ReplaceInFileWebpackPlugin([{
            dir: pathTheme(),
            files: ['version.php'],
            rules: [{
                search: new RegExp(/\$bundle_version\s=\s\'\d+\'/, 'gi'),
                replace: () => `$bundle_version = '${version}'`
            }]
        }]),
    ]
});
