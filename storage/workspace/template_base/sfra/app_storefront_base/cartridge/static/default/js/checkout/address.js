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
/******/ 	return __webpack_require__(__webpack_require__.s = "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/checkout/address.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/checkout/address.js":
/*!***************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/checkout/address.js ***!
  \***************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Populate the Billing Address Summary View
 * @param {string} parentSelector - the top level DOM selector for a unique address summary
 * @param {Object} address - the address data
 */
function populateAddressSummary(parentSelector, address) {
    $.each(address, function (attr) {
        var val = address[attr];
        $('.' + attr, parentSelector).text(val || '');
    });
}

/**
 * returns a formed <option /> element
 * @param {Object} shipping - the shipping object (shipment model)
 * @param {boolean} selected - current shipping is selected (for PLI)
 * @param {order} order - the Order model
 * @param {Object} [options] - options
 * @returns {Object} - the jQuery / DOMElement
 */
function optionValueForAddress(shipping, selected, order, options) {
    var safeOptions = options || {};
    var isBilling = safeOptions.type && safeOptions.type === 'billing';
    var className = safeOptions.className || '';
    var isSelected = selected;
    var isNew = !shipping;
    if (typeof shipping === 'string') {
        return $('<option class="' + className + '" disabled>' + shipping + '</option>');
    }
    var safeShipping = shipping || {};
    var shippingAddress = safeShipping.shippingAddress || {};

    if (isBilling && isNew && !order.billing.matchingAddressId) {
        shippingAddress = order.billing.billingAddress.address || {};
        isNew = false;
        isSelected = true;
        safeShipping.UUID = 'manual-entry';
    }

    var uuid = safeShipping.UUID ? safeShipping.UUID : 'new';
    var optionEl = $('<option class="' + className + '" />');
    optionEl.val(uuid);

    var title;

    if (isNew) {
        title = order.resources.addNewAddress;
    } else {
        title = [];
        if (shippingAddress.firstName) {
            title.push(shippingAddress.firstName);
        }
        if (shippingAddress.lastName) {
            title.push(shippingAddress.lastName);
        }
        if (shippingAddress.address1) {
            title.push(shippingAddress.address1);
        }
        if (shippingAddress.address2) {
            title.push(shippingAddress.address2);
        }
        if (shippingAddress.city) {
            if (shippingAddress.state) {
                title.push(shippingAddress.city + ',');
            } else {
                title.push(shippingAddress.city);
            }
        }
        if (shippingAddress.stateCode) {
            title.push(shippingAddress.stateCode);
        }
        if (shippingAddress.postalCode) {
            title.push(shippingAddress.postalCode);
        }
        if (!isBilling && safeShipping.selectedShippingMethod) {
            title.push('-');
            title.push(safeShipping.selectedShippingMethod.displayName);
        }

        if (title.length > 2) {
            title = title.join(' ');
        } else {
            title = order.resources.newAddress;
        }
    }
    optionEl.text(title);

    var keyMap = {
        'data-first-name': 'firstName',
        'data-last-name': 'lastName',
        'data-address1': 'address1',
        'data-address2': 'address2',
        'data-city': 'city',
        'data-state-code': 'stateCode',
        'data-postal-code': 'postalCode',
        'data-country-code': 'countryCode',
        'data-phone': 'phone'
    };
    $.each(keyMap, function (key) {
        var mappedKey = keyMap[key];
        var mappedValue = shippingAddress[mappedKey];
        // In case of country code
        if (mappedValue && typeof mappedValue === 'object') {
            mappedValue = mappedValue.value;
        }

        optionEl.attr(key, mappedValue || '');
    });

    var giftObj = {
        'data-is-gift': 'isGift',
        'data-gift-message': 'giftMessage'
    };

    $.each(giftObj, function (key) {
        var mappedKey = giftObj[key];
        var mappedValue = safeShipping[mappedKey];
        optionEl.attr(key, mappedValue || '');
    });

    if (isSelected) {
        optionEl.attr('selected', true);
    }

    return optionEl;
}

/**
 * returns address properties from a UI form
 * @param {Form} form - the Form element
 * @returns {Object} - a JSON object with all values
 */
function getAddressFieldsFromUI(form) {
    var address = {
        firstName: $('input[name$=_firstName]', form).val(),
        lastName: $('input[name$=_lastName]', form).val(),
        address1: $('input[name$=_address1]', form).val(),
        address2: $('input[name$=_address2]', form).val(),
        city: $('input[name$=_city]', form).val(),
        postalCode: $('input[name$=_postalCode]', form).val(),
        stateCode: $('select[name$=_stateCode],input[name$=_stateCode]', form).val(),
        countryCode: $('select[name$=_country]', form).val(),
        phone: $('input[name$=_phone]', form).val()
    };
    return address;
}

module.exports = {
    methods: {
        populateAddressSummary: populateAddressSummary,
        optionValueForAddress: optionValueForAddress,
        getAddressFieldsFromUI: getAddressFieldsFromUI
    },

    showDetails: function () {
        $('.btn-show-details').on('click', function () {
            var form = $(this).closest('form');

            form.attr('data-address-mode', 'details');
            form.find('.multi-ship-address-actions').removeClass('d-none');
            form.find('.multi-ship-action-buttons .col-12.btn-save-multi-ship').addClass('d-none');
        });
    },

    addNewAddress: function () {
        $('.btn-add-new').on('click', function () {
            var $el = $(this);
            if ($el.parents('#dwfrm_billing').length > 0) {
                // Handle billing address case
                $('body').trigger('checkout:clearBillingForm');
                var $option = $($el.parents('form').find('.addressSelector option')[0]);
                $option.attr('value', 'new');
                var $newTitle = $('#dwfrm_billing input[name=localizedNewAddressTitle]').val();
                $option.text($newTitle);
                $option.prop('selected', 'selected');
                $el.parents('[data-address-mode]').attr('data-address-mode', 'new');
            } else {
                // Handle shipping address case
                var $newEl = $el.parents('form').find('.addressSelector option[value=new]');
                $newEl.prop('selected', 'selected');
                $newEl.parent().trigger('change');
            }
        });
    }
};


/***/ })

/******/ });
//# sourceMappingURL=address.js.map