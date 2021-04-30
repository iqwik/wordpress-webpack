const path = require('path');

const pathTheme = () => path.resolve(__dirname, '../wp-content/themes/iqwik');
const pathProd = () => path.join(pathTheme(), '/assets/');
const pathDev = () => path.join(pathProd(), 'src');

module.exports = {
    pathTheme,
    pathProd,
    pathDev,
};
