(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/controllers/roles"],{

/***/ "./resources/assets/js/controllers/roles.js":
/*!**************************************************!*\
  !*** ./resources/assets/js/controllers/roles.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {var SELECT_CLASS = 'permissions_select_all';
var UNSELECT_CLASS = 'permissions_select_none';
var CHECK_TRIGGERS = '#' + SELECT_CLASS + ', #' + UNSELECT_CLASS;
jQuery(document).ready(function () {
  jQuery('.type-empty-field').on('click', CHECK_TRIGGERS, function () {
    var checked = jQuery(this).attr('id') === SELECT_CLASS ? 'checked' : false;
    jQuery('input[type="checkbox"][name^="resource[permissions]"]').attr('checked', checked);
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ 1:
/*!********************************************************!*\
  !*** multi ./resources/assets/js/controllers/roles.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/controllers/roles.js */"./resources/assets/js/controllers/roles.js");


/***/ })

},[[1,"/js/manifest","/js/vendor"]]]);