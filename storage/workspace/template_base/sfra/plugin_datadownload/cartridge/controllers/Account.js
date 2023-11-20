'use strict';

var server = require('server');
server.extend(module.superModule);

var userLoggedIn = require('*/cartridge/scripts/middleware/userLoggedIn');

server.get('DataDownload',
    server.middleware.https,
    userLoggedIn.validateLoggedIn,
    function (req, res, next) {
        var customer = req.currentCustomer.raw;
        var dataDownloadHelper = require('~/cartridge/scripts/dataDownloadHelper');
        var site = require('dw/system/Site');

        var fileName = site.current.name
            + '_' + customer.profile.firstName
            + '_' + customer.profile.lastName
            + '.json';

        var profileData = dataDownloadHelper.getProfileData(customer);

        res.setHttpHeader(res.base.CONTENT_DISPOSITION, 'attachment; filename="' + fileName + '"');
        res.setContentType('application/octet-stream');
        res.print(profileData);

        next();
    }
);

module.exports = server.exports();
