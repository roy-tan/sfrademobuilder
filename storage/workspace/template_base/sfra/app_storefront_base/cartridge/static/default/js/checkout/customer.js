/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/checkout/customer.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/checkout/customer.js":
/*!****************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/checkout/customer.js ***!
  \****************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var formHelpers = __webpack_require__(/*! ./formErrors */ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/checkout/formErrors.js");
var scrollAnimate = __webpack_require__(/*! ../components/scrollAnimate */ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/scrollAnimate.js");
var createErrorNotification = __webpack_require__(/*! ../components/errorNotification */ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/errorNotification.js");
var GUEST_FORM = '#guest-customer';
var REGISTERED_FORM = '#registered-customer';
var ERROR_SECTION = '.customer-error';

/**
 * @returns {boolean} If guest is active, registered is not visible
 */
function isGuestFormActive() {
    return $(REGISTERED_FORM).hasClass('d-none');
}

/**
 * Clear any previous errors in the customer form.
 */
function clearErrors() {
    $(ERROR_SECTION).children().remove();
    formHelpers.clearPreviousErrors('.customer-information-block');
}

/**
 * @param {Object} customerData - data includes checkout related customer information
 * @param {Object} orderData - data includes checkout related order information
 */
function updateCustomerInformation(customerData, orderData) {
    var $container = $('.customer-summary');
    var $summaryDetails = $container.find('.summary-details');
    var email = customerData.profile && customerData.profile.email ? customerData.profile.email : orderData.orderEmail;
    $summaryDetails.find('.customer-summary-email').text(email);
    if (customerData.registeredUser) {
        $container.find('.edit-button').hide();
    } else {
        $container.find('.edit-button').show();
    }
}


/**
 * Handle response from the server for valid or invalid form fields.
 * @param {Object} defer - the deferred object which will resolve on success or reject.
 * @param {Object} data - the response data with the invalid form fields or
 *  valid model data.
 */
function customerFormResponse(defer, data) {
    var parentForm = isGuestFormActive() ? GUEST_FORM : REGISTERED_FORM;
    var formSelector = '.customer-section ' + parentForm;

    // highlight fields with errors
    if (data.error) {
        if (data.fieldErrors.length) {
            data.fieldErrors.forEach(function (error) {
                if (Object.keys(error).length) {
                    formHelpers.loadFormErrors(formSelector, error);
                }
            });
        }

        if (data.customerErrorMessage) {
            createErrorNotification(ERROR_SECTION, data.customerErrorMessage);
        }

        if (data.fieldErrors.length || data.customerErrorMessage || (data.serverErrors && data.serverErrors.length)) {
            defer.reject(data);
        }

        if (data.cartError) {
            window.location.href = data.redirectUrl;
            defer.reject();
        }
    } else {
        // Populate the Address Summary

        $('body').trigger('checkout:updateCheckoutView', {
            order: data.order,
            customer: data.customer,
            csrfToken: data.csrfToken
        });
        scrollAnimate($('.shipping-form'));
        defer.resolve(data);
    }
}

/**
 *
 * @param {boolean} registered - wether a registered login block will be used
 */
function chooseLoginBlock(registered) {
    $(ERROR_SECTION).find('.alert').remove();
    $('#password').val('');
    if (registered) {
        $(REGISTERED_FORM).removeClass('d-none');
        $(GUEST_FORM).addClass('d-none');
        $('#email').val($('#email-guest').val());
    } else {
        $(REGISTERED_FORM).addClass('d-none');
        $(GUEST_FORM).removeClass('d-none');
        $('#email').val('');
    }
}

module.exports = {

    /**
     * Listeners for customer form
     */
    initListeners: function () {
        // 1. password
        var customerLogin = '.js-login-customer';
        var cancelLogin = '.js-cancel-login';
        var registered;
        if (customerLogin.length !== 0) {
            $('body').on('click', customerLogin, function (e) {
                registered = true;
                e.preventDefault();
                chooseLoginBlock(registered);
            });
        }
        if (cancelLogin.length !== 0) {
            $('body').on('click', cancelLogin, function (e) {
                registered = false;
                e.preventDefault();
                chooseLoginBlock(registered);
            });
        }
    },

    methods: {
        clearErrors: clearErrors,
        updateCustomerInformation: updateCustomerInformation,
        customerFormResponse: customerFormResponse,
        isGuestFormActive: isGuestFormActive
    },

    vars: {
        GUEST_FORM: GUEST_FORM,
        REGISTERED_FORM: REGISTERED_FORM
    }

};


/***/ }),

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/checkout/formErrors.js":
/*!******************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/checkout/formErrors.js ***!
  \******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var scrollAnimate = __webpack_require__(/*! ../components/scrollAnimate */ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/scrollAnimate.js");

/**
 * Display error messages and highlight form fields with errors.
 * @param {string} parentSelector - the form which contains the fields
 * @param {Object} fieldErrors - the fields with errors
 */
function loadFormErrors(parentSelector, fieldErrors) { // eslint-disable-line
    // Display error messages and highlight form fields with errors.
    $.each(fieldErrors, function (attr) {
        $('*[name=' + attr + ']', parentSelector)
            .addClass('is-invalid')
            .siblings('.invalid-feedback')
            .html(fieldErrors[attr]);
    });
    // Animate to top of form that has errors
    scrollAnimate($(parentSelector));
}

/**
 * Clear the form errors.
 * @param {string} parentSelector - the parent form selector.
 */
function clearPreviousErrors(parentSelector) {
    $(parentSelector).find('.form-control.is-invalid').removeClass('is-invalid');
    $('.error-message').hide();
}

module.exports = {
    loadFormErrors: loadFormErrors,
    clearPreviousErrors: clearPreviousErrors
};


/***/ }),

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/errorNotification.js":
/*!***************************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/errorNotification.js ***!
  \***************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function (element, message) {
    var errorHtml = '<div class="alert alert-danger alert-dismissible ' +
        'fade show" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' + message + '</div>';

    $(element).append(errorHtml);
};


/***/ }),

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/scrollAnimate.js":
/*!***********************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/scrollAnimate.js ***!
  \***********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function (element) {
    var position = element && element.length ? element.offset().top : 0;
    $('html, body').animate({
        scrollTop: position
    }, 500);
    if (!element) {
        $('.logo-home').focus();
    }
};


/***/ })

/******/ });
//# sourceMappingURL=customer.js.map