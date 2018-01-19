/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
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
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/app.js":
/***/ (function(module, exports) {

/*
 * Usuwanie elementów
 */
makeDeleteRequest = function makeDeleteRequest(url) {

    var form = $('<form>', {
        'method': 'POST',
        'action': url
    });

    var token = $('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': window.Laravel.csrfToken
    });

    var hiddenInput = $('<input>', {
        'name': '_method',
        'type': 'hidden',
        'value': 'DELETE'
    });

    return form.append(token, hiddenInput).appendTo('body').submit();
};

makePOSTRequest = function makePOSTRequest(url) {
    
        var form = $('<form>', {
            'method': 'POST',
            'action': url
        });
    
        var token = $('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': window.Laravel.csrfToken
        });
    
        var hiddenInput = $('<input>', {
            'name': '_method',
            'type': 'hidden',
            'value': 'POST'
        });
    
        return form.append(token, hiddenInput).appendTo('body').submit();
    };

$(document).delegate('[data-action="delete"]', 'click', function (event) {
    event.preventDefault();

    var url = $(this).attr('href');
    swal({
        title: "Czy na pewno usunąć?",
        text: "Usunięcie jest zmianą nieodwracalną",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Tak, usuń",
        cancelButtonText: "Anuluj",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            makeDeleteRequest(url);
        }
    });
});


dtLanguage = {
    lengthMenu: "Pokaż _MENU_ pozycji",
    search: "Szukaj:",
    info: "Pozycje od _START_ do _END_ z _TOTAL_ łącznie",
    infoFiltered: "(przefiltrowane z  _MAX_ dostępnych pozycji)",
    zeroRecords: "Brak danych",
    infoEmpty: "Pozycji 0 z 0 dostępnych",
    processing: '<span style="margin-top: 50px;"><i class="fa fa-spin fa-spinner"></i> Trwa pobieranie danych</span>',
    paginate: {
        first: "Pierwsza",
        previous: "Poprzednia",
        next: "Następna",
        last: "Ostatnia"
    }
};

dtLengthMenu = [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'wszystko']];

BootstrapSelect = function () {
    var _init = function _init() {
        $('.bs-select').selectpicker({
            size: 5
        });
    };

    return {
        init: function init() {
            _init();
        }
    };
}();

DatePicker = function () {
    var _init2 = function _init2() {
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "bottom auto",
            autoclose: true,
            language: 'pl',
            clearBtn: true
        });
    };

    return {
        init: function init() {
            _init2();
        }
    };
}();

Tooltip = function () {
    var _init3 = function _init3() {
        $('[data-toggle="tooltip"]').tooltip();
    };

    return {
        init: function init() {
            _init3();
        }
    };
}();

/***/ }),

/***/ "./resources/assets/sass/app.scss":
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__("./resources/assets/js/app.js");
module.exports = __webpack_require__("./resources/assets/sass/app.scss");


/***/ })

/******/ });