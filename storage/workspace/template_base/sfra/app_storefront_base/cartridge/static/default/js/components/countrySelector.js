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
/******/ 	return __webpack_require__(__webpack_require__.s = "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/countrySelector.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/countrySelector.js":
/*!*************************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/countrySelector.js ***!
  \*************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var keyboardAccessibility = __webpack_require__(/*! ./keyboardAccessibility */ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/keyboardAccessibility.js");

module.exports = function () {
    $('.country-selector a').click(function (e) {
        e.preventDefault();
        var action = $('.page').data('action');
        var localeCode = $(this).data('locale');
        var localeCurrencyCode = $(this).data('currencycode');
        var queryString = $('.page').data('querystring');
        var url = $('.country-selector').data('url');

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            data: {
                code: localeCode,
                queryString: queryString,
                CurrencyCode: localeCurrencyCode,
                action: action
            },
            success: function (response) {
                $.spinner().stop();
                if (response && response.redirectUrl) {
                    window.location.href = response.redirectUrl;
                }
            },
            error: function () {
                $.spinner().stop();
            }
        });
    });

    keyboardAccessibility('.navbar-header .country-selector',
        {
            40: function ($countryOptions) { // down
                if ($(this).is(':focus')) {
                    $countryOptions.first().focus();
                } else {
                    $(':focus').next().focus();
                }
            },
            38: function ($countryOptions) { // up
                if ($countryOptions.first().is(':focus') || $(this).is(':focus')) {
                    $(this).focus();
                    $(this).removeClass('show');
                } else {
                    $(':focus').prev().focus();
                }
            },
            27: function () { // escape
                $(this).focus();
                $(this).removeClass('show').children('.dropdown-menu').removeClass('show');
            },
            9: function () { // tab
                $(this).removeClass('show').children('.dropdown-menu').removeClass('show');
            }
        },
        function () {
            if (!($(this).hasClass('show'))) {
                $(this).addClass('show');
            }
            return $(this).find('.dropdown-country-selector').children('a');
        }
    );

    $('.navbar-header .country-selector').on('focusin', function () {
        $(this).addClass('show').children('.dropdown-menu').addClass('show');
    });
};


/***/ }),

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/keyboardAccessibility.js":
/*!*******************************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/keyboardAccessibility.js ***!
  \*******************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function (selector, keyFunctions, preFunction) {
    $(selector).on('keydown', function (e) {
        var key = e.which;
        var supportedKeyCodes = [37, 38, 39, 40, 27];
        if (supportedKeyCodes.indexOf(key) >= 0) {
            e.preventDefault();
        }
        var returnedScope = preFunction.call(this);
        if (keyFunctions[key]) {
            keyFunctions[key].call(this, returnedScope);
        }
    });
};


/***/ })

/******/ });
//# sourceMappingURL=countrySelector.js.map