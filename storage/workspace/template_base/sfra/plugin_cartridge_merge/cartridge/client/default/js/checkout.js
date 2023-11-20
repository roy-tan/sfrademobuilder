'use strict';

var processInclude = require('base/util');

$(document).ready(function () {
    // Apple pay
    if (window.dw &&
        window.dw.applepay &&
        window.ApplePaySession &&
        window.ApplePaySession.canMakePayments()) {
        $('body').addClass('apple-pay-enabled');
    }

    // Instore pickup
    try {
        processInclude(require('instorepickup/checkout/checkout'));
        processInclude(require('instorepickup/checkout/instore'));
    } catch (ex) {
        // plugin not in use
    }
});
