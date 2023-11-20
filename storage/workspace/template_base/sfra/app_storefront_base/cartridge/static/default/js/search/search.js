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
/******/ 	return __webpack_require__(__webpack_require__.s = "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/search/search.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/search/search.js":
/*!************************************************************************************************************************!*\
  !*** ../storefront-reference-architecture/cartridges/app_storefront_base/cartridge/client/default/js/search/search.js ***!
  \************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Update DOM elements with Ajax results
 *
 * @param {Object} $results - jQuery DOM element
 * @param {string} selector - DOM element to look up in the $results
 * @return {undefined}
 */
function updateDom($results, selector) {
    var $updates = $results.find(selector);
    $(selector).empty().html($updates.html());
}

/**
 * Keep refinement panes expanded/collapsed after Ajax refresh
 *
 * @param {Object} $results - jQuery DOM element
 * @return {undefined}
 */
function handleRefinements($results) {
    $('.refinement.active').each(function () {
        $(this).removeClass('active');
        var activeDiv = $results.find('.' + $(this)[0].className.replace(/ /g, '.'));
        activeDiv.addClass('active');
        activeDiv.find('button.title').attr('aria-expanded', 'true');
    });

    updateDom($results, '.refinements');
}

/**
 * Parse Ajax results and updated select DOM elements
 *
 * @param {string} response - Ajax response HTML code
 * @return {undefined}
 */
function parseResults(response) {
    var $results = $(response);
    var specialHandlers = {
        '.refinements': handleRefinements
    };

    // Update DOM elements that do not require special handling
    [
        '.grid-header',
        '.header-bar',
        '.header.page-title',
        '.product-grid',
        '.show-more',
        '.filter-bar'
    ].forEach(function (selector) {
        updateDom($results, selector);
    });

    Object.keys(specialHandlers).forEach(function (selector) {
        specialHandlers[selector]($results);
    });
}

/**
 * This function retrieves another page of content to display in the content search grid
 * @param {JQuery} $element - the jquery element that has the click event attached
 * @param {JQuery} $target - the jquery element that will receive the response
 * @return {undefined}
 */
function getContent($element, $target) {
    var showMoreUrl = $element.data('url');
    $.spinner().start();
    $.ajax({
        url: showMoreUrl,
        method: 'GET',
        success: function (response) {
            $target.append(response);
            $.spinner().stop();
        },
        error: function () {
            $.spinner().stop();
        }
    });
}

/**
 * Update sort option URLs from Ajax response
 *
 * @param {string} response - Ajax response HTML code
 * @return {undefined}
 */
function updateSortOptions(response) {
    var $tempDom = $('<div>').append($(response));
    var sortOptions = $tempDom.find('.grid-footer').data('sort-options').options;
    sortOptions.forEach(function (option) {
        $('option.' + option.id).val(option.url);
    });
}

module.exports = {
    filter: function () {
        // Display refinements bar when Menu icon clicked
        $('.container').on('click', 'button.filter-results', function () {
            $('.refinement-bar, .modal-background').show();
            $('.refinement-bar').siblings().attr('aria-hidden', true);
            $('.refinement-bar').closest('.row').siblings().attr('aria-hidden', true);
            $('.refinement-bar').closest('.tab-pane.active').siblings().attr('aria-hidden', true);
            $('.refinement-bar').closest('.container.search-results').siblings().attr('aria-hidden', true);
            $('.refinement-bar .close').focus();
        });
    },

    closeRefinements: function () {
        // Refinements close button
        $('.container').on('click', '.refinement-bar button.close, .modal-background', function () {
            $('.refinement-bar, .modal-background').hide();
            $('.refinement-bar').siblings().attr('aria-hidden', false);
            $('.refinement-bar').closest('.row').siblings().attr('aria-hidden', false);
            $('.refinement-bar').closest('.tab-pane.active').siblings().attr('aria-hidden', false);
            $('.refinement-bar').closest('.container.search-results').siblings().attr('aria-hidden', false);
            $('.btn.filter-results').focus();
        });
    },

    resize: function () {
        // Close refinement bar and hide modal background if user resizes browser
        $(window).resize(function () {
            $('.refinement-bar, .modal-background').hide();
            $('.refinement-bar').siblings().attr('aria-hidden', false);
            $('.refinement-bar').closest('.row').siblings().attr('aria-hidden', false);
            $('.refinement-bar').closest('.tab-pane.active').siblings().attr('aria-hidden', false);
            $('.refinement-bar').closest('.container.search-results').siblings().attr('aria-hidden', false);
        });
    },

    sort: function () {
        // Handle sort order menu selection
        $('.container').on('change', '[name=sort-order]', function (e) {
            e.preventDefault();

            $.spinner().start();
            $(this).trigger('search:sort', this.value);
            $.ajax({
                url: this.value,
                data: { selectedUrl: this.value },
                method: 'GET',
                success: function (response) {
                    $('.product-grid').empty().html(response);
                    $.spinner().stop();
                },
                error: function () {
                    $.spinner().stop();
                }
            });
        });
    },

    showMore: function () {
        // Show more products
        $('.container').on('click', '.show-more button', function (e) {
            e.stopPropagation();
            var showMoreUrl = $(this).data('url');
            e.preventDefault();

            $.spinner().start();
            $(this).trigger('search:showMore', e);
            $.ajax({
                url: showMoreUrl,
                data: { selectedUrl: showMoreUrl },
                method: 'GET',
                success: function (response) {
                    $('.grid-footer').replaceWith(response);
                    updateSortOptions(response);
                    $.spinner().stop();
                },
                error: function () {
                    $.spinner().stop();
                }
            });
        });
    },

    applyFilter: function () {
        // Handle refinement value selection and reset click
        $('.container').on(
            'click',
            '.refinements li button, .refinement-bar button.reset, .filter-value button, .swatch-filter button',
            function (e) {
                e.preventDefault();
                e.stopPropagation();

                $.spinner().start();
                $(this).trigger('search:filter', e);
                $.ajax({
                    url: $(this).data('href'),
                    data: {
                        page: $('.grid-footer').data('page-number'),
                        selectedUrl: $(this).data('href')
                    },
                    method: 'GET',
                    success: function (response) {
                        parseResults(response);
                        $.spinner().stop();
                    },
                    error: function () {
                        $.spinner().stop();
                    }
                });
            });
    },

    showContentTab: function () {
        // Display content results from the search
        $('.container').on('click', '.content-search', function () {
            if ($('#content-search-results').html() === '') {
                getContent($(this), $('#content-search-results'));
            }
        });

        // Display the next page of content results from the search
        $('.container').on('click', '.show-more-content button', function () {
            getContent($(this), $('#content-search-results'));
            $('.show-more-content').remove();
        });
    }
};


/***/ })

/******/ });
//# sourceMappingURL=search.js.map