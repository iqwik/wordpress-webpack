const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const { pathProd, pathDev } = require('./constants');
const version = Number(new Date());

const { cacheLoader, cssProcessing } = require('./utils')

module.exports = {
    entry: {
        app: path.join(pathDev(), '/js/index.js'),
    },
    output: {
        path: pathProd(),
        globalObject: 'self',
        filename: 'js/[name].min.js',
    },
    stats: {
        children: false
    },
    resolve: {
        extensions: ['.wasm', '.mjs', '.jsx', '.js', '.json']
    },
    module: {
        rules: [
            {
                test: /\.(js)x?$/,
                exclude: /node_modules/,
                use: [
                    { ...cacheLoader() },
                    {
                        loader: 'babel-loader',
                    }
                ],
            },
            {
                test: /\.js$/,
                enforce: "pre",
                use: ['source-map-loader'],
            },
            cssProcessing(),
            {
                test: /\.(png|jpe?g|gif)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            limit: 8192,
                            context: pathProd(),
                            name: `[path][name].[ext]?ver=${version}`,
                            publicPath: './assets/'
                        },
                    },
                    'img-loader'
                ]
            },
            {
                test: /\.(eot|svg|ttf|woff|woff2)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        limit: 8192,
                        context: pathProd(),
                        name: '[path][name].[ext]',
                        publicPath: './assets/'
                    }
                },
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '../app.min.css',
        }),
    ],
    // target: 'web',
};
