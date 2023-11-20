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
/******/ 	return __webpack_require__(__webpack_require__.s = "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/consentTracking.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/consentTracking.js":
/*!*************************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/consentTracking.js ***!
  \*************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var focusHelper = __webpack_require__(/*! ../components/focus */ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/focus.js");

/**
 * Renders a modal window that will track the users consenting to accepting site tracking policy
 */
function showConsentModal() {
    if (!$('.tracking-consent').data('caonline')) {
        return;
    }

    var urlContent = $('.tracking-consent').data('url');
    var urlAccept = $('.tracking-consent').data('accept');
    var urlReject = $('.tracking-consent').data('reject');
    var textYes = $('.tracking-consent').data('accepttext');
    var textNo = $('.tracking-consent').data('rejecttext');
    var textHeader = $('.tracking-consent').data('heading');

    var htmlString = '<!-- Modal -->'
        + '<div class="modal show" id="consent-tracking" aria-modal="true" role="dialog" style="display: block;">'
        + '<div class="modal-dialog">'
        + '<!-- Modal content-->'
        + '<div class="modal-content">'
        + '<div class="modal-header">'
        + textHeader
        + '</div>'
        + '<div class="modal-body"></div>'
        + '<div class="modal-footer">'
        + '<div class="button-wrapper">'
        + '<button class="affirm btn btn-primary" data-url="' + urlAccept + '" autofocus data-dismiss="modal">'
        + textYes
        + '</button>'
        + '<button class="decline btn btn-primary" data-url="' + urlReject + '" data-dismiss="modal" >'
        + textNo
        + '</button>'
        + '</div>'
        + '</div>'
        + '</div>'
        + '</div>'
        + '</div>';
    $.spinner().start();
    $('body').append(htmlString);

    $.ajax({
        url: urlContent,
        type: 'get',
        dataType: 'html',
        success: function (response) {
            $('.modal-body').html(response);
            $('#consent-tracking').modal('show');
        },
        error: function () {
            $('#consent-tracking').remove();
        }
    });

    $('#consent-tracking .button-wrapper button').click(function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function () {
                $('#consent-tracking').remove();
                $.spinner().stop();
            },
            error: function () {
                $('#consent-tracking').remove();
                $.spinner().stop();
            }
        });
    });
}

module.exports = function () {
    if ($('.consented').length === 0 && $('.tracking-consent').hasClass('api-true')) {
        showConsentModal();
    }

    if ($('.tracking-consent').hasClass('api-true')) {
        $('.tracking-consent').click(function () {
            showConsentModal();
        });
    }

    $('body').on('shown.bs.modal', '#consent-tracking', function () {
        $('#consent-tracking').siblings().attr('aria-hidden', 'true');
        $('#consent-tracking .close').focus();
    });

    $('body').on('hidden.bs.modal', '#consent-tracking', function () {
        $('#consent-tracking').siblings().attr('aria-hidden', 'false');
    });

    $('body').on('keydown', '#consent-tracking', function (e) {
        var focusParams = {
            event: e,
            containerSelector: '#consent-tracking',
            firstElementSelector: '.affirm',
            lastElementSelector: '.decline',
            nextToLastElementSelector: '.affirm'
        };
        focusHelper.setTabNextFocus(focusParams);
    });
};


/***/ }),

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/focus.js":
/*!***************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/focus.js ***!
  \***************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = {
    setTabNextFocus: function (focusParams) {
        var KEYCODE_TAB = 9;
        var isTabPressed = (focusParams.event.key === 'Tab' || focusParams.event.keyCode === KEYCODE_TAB);

        if (!isTabPressed) {
            return;
        }

        var firstFocusableEl = $(focusParams.containerSelector + ' ' + focusParams.firstElementSelector);
        var lastFocusableEl = $(focusParams.containerSelector + ' ' + focusParams.lastElementSelector);

        if ($(focusParams.containerSelector + ' ' + focusParams.lastElementSelector).is(':disabled')) {
            lastFocusableEl = $(focusParams.containerSelector + ' ' + focusParams.nextToLastElementSelector);
            if ($('.product-quickview.product-set').length > 0) {
                var linkElements = $(focusParams.containerSelector + ' a#fa-link.share-icons');
                lastFocusableEl = linkElements[linkElements.length - 1];
            }
        }

        if (focusParams.event.shiftKey) /* shift + tab */ {
            if ($(':focus').is(firstFocusableEl)) {
                lastFocusableEl.focus();
                focusParams.event.preventDefault();
            }
        } else /* tab */ {
            if ($(':focus').is(lastFocusableEl)) { // eslint-disable-line
                firstFocusableEl.focus();
                focusParams.event.preventDefault();
            }
        }
    }
};


/***/ })

/******/ });
//# sourceMappingURL=consentTracking.js.map