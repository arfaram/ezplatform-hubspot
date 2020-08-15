//TODO file might not be neeed and definition could move to ez.config.js
const path = require('path');

module.exports = (eZConfig, eZConfigManager) => {
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-location-view-js',
        newItems: [
            path.resolve(__dirname, '../public/js/hubspot.create.message.js'),
        ],
    });
};