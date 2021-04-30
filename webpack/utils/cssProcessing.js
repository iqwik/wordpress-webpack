const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const cacheLoader = require('./cacheLoader');

const { pathDev } = require('../constants.js');

const cssProcessing = () => {
    return {
        test: /\.(sa|sc|c)ss$/,
        exclude: /node_modules/,
        use: [
            {
                loader: MiniCssExtractPlugin.loader,
                options: {
                    esModule: false,
                },
            },
            { ...cacheLoader() },
            {
                loader: 'css-loader',
            },
            {
                loader: 'postcss-loader',
                options: {
                    postcssOptions: {
                        plugins: ['autoprefixer'],
                    },
                },
            },
            {
                loader: 'sass-loader',
                options: {
                    implementation: require('node-sass'),
                    sourceMap: true,
                },
            },
            {
                loader: 'sass-resources-loader',
                options: {
                    // Common SCSS variables
                    resources: [path.join(pathDev(), '/css/_variables.scss')],
                }
            },
        ],
    }
}

module.exports = cssProcessing
