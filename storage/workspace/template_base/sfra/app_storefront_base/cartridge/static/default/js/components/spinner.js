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
/******/ 	return __webpack_require__(__webpack_require__.s = "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/spinner.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/spinner.js":
/*!*****************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/components/spinner.js ***!
  \*****************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Show a spinner inside a given element
 * @param {element} $target - Element to block by the veil and spinner.
 *                            Pass body to block the whole page.
 */
function addSpinner($target) {
    var $veil = $('<div class="veil"><div class="underlay"></div></div>');
    $veil.append('<div class="spinner"><div class="dot1"></div><div class="dot2"></div></div>');
    if ($target.get(0).tagName === 'IMG') {
        $target.after($veil);
        $veil.css({ width: $target.width(), height: $target.height() });
        if ($target.parent().css('position') === 'static') {
            $target.parent().css('position', 'relative');
        }
    } else {
        $target.append($veil);
        if ($target.css('position') === 'static') {
            $target.parent().css('position', 'relative');
            $target.parent().addClass('veiled');
        }
        if ($target.get(0).tagName === 'BODY') {
            $veil.find('.spinner').css('position', 'fixed');
        }
    }
    $veil.click(function (e) {
        e.stopPropagation();
    });
}

/**
 * Remove existing spinner
 * @param  {element} $veil - jQuery pointer to the veil element
 */
function removeSpinner($veil) {
    if ($veil.parent().hasClass('veiled')) {
        $veil.parent().css('position', '');
        $veil.parent().removeClass('veiled');
    }
    $veil.off('click');
    $veil.remove();
}

// element level spinner:
$.fn.spinner = function () {
    var $element = $(this);
    var Fn = function () {
        this.start = function () {
            if ($element.length) {
                addSpinner($element);
            }
        };
        this.stop = function () {
            if ($element.length) {
                var $veil = $('.veil');
                removeSpinner($veil);
            }
        };
    };
    return new Fn();
};

// page-level spinner:
$.spinner = function () {
    var Fn = function () {
        this.start = function () {
            addSpinner($('body'));
        };
        this.stop = function () {
            removeSpinner($('.veil'));
        };
    };
    return new Fn();
};


/***/ })

/******/ });
//# sourceMappingURL=spinner.js.map