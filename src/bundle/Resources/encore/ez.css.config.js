const path = require('path');

module.exports = (Encore) => {
    Encore.addEntry('ezplatform-admin-ui-hubspot-css', [
        path.resolve(__dirname, '../public/css/style.css')
    ]);
};