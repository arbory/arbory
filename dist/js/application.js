(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/application"],{

/***/ "./resources/assets/js/Admin/AdminPanel.js":
/*!*************************************************!*\
  !*** ./resources/assets/js/Admin/AdminPanel.js ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return AdminPanel; });
/* harmony import */ var _Services_Navigator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Services/Navigator */ "./resources/assets/js/Admin/Services/Navigator.js");
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance"); }

function _iterableToArrayLimit(arr, i) { if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === "[object Arguments]")) { return; } var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var AdminPanel =
/*#__PURE__*/
function () {
  /**
   * @param {FieldRegistry} registry
   */
  function AdminPanel(registry) {
    _classCallCheck(this, AdminPanel);

    this.registry = registry;
  }
  /**
   * @param {FieldRegistry} registry
   */


  _createClass(AdminPanel, [{
    key: "initialize",

    /**
     * @return {void}
     */
    value: function initialize() {
      this.navigator = new _Services_Navigator__WEBPACK_IMPORTED_MODULE_0__["default"]();
      this.registerEventHandlers();
    }
    /**
     * @return {void}
     */

  }, {
    key: "registerEventHandlers",
    value: function registerEventHandlers() {
      var _this = this;

      var body = jQuery('body');
      body.on('nestedfieldsitemadd', 'section.nested', function (event) {
        _this.initializeFields(event.target);
      });
      body.ready(function () {
        _this.initializeFields(body[0]);
      });
    }
    /**
     * @return {void}
     */

  }, {
    key: "initializeFields",
    value: function initializeFields(scope) {
      var _loop = function _loop() {
        var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
            _ = _Object$entries$_i[0],
            definition = _Object$entries$_i[1];

        jQuery(scope).find(definition.selector).each(function (key, element) {
          new definition.handler(element, definition.config);
        });
      };

      for (var _i = 0, _Object$entries = Object.entries(this.registry.definitions); _i < _Object$entries.length; _i++) {
        _loop();
      }
    }
  }, {
    key: "registry",
    set: function set(registry) {
      this._registry = registry;
    }
    /**
     * @return {FieldRegistry}
     */
    ,
    get: function get() {
      return this._registry;
    }
  }]);

  return AdminPanel;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/Admin/FieldRegistry.js":
/*!****************************************************!*\
  !*** ./resources/assets/js/Admin/FieldRegistry.js ***!
  \****************************************************/
/*! exports provided: FIELD_TYPE_DEFINITIONS, Definition, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "FIELD_TYPE_DEFINITIONS", function() { return FIELD_TYPE_DEFINITIONS; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Definition", function() { return Definition; });
/* harmony import */ var _Fields_RichText__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Fields/RichText */ "./resources/assets/js/Admin/Fields/RichText.js");
/* harmony import */ var _Fields_RichTextCompact__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Fields/RichTextCompact */ "./resources/assets/js/Admin/Fields/RichTextCompact.js");
/* harmony import */ var _Fields_IconPicker__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Fields/IconPicker */ "./resources/assets/js/Admin/Fields/IconPicker.js");
/* harmony import */ var _Fields_Sortable__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Fields/Sortable */ "./resources/assets/js/Admin/Fields/Sortable.js");
/* harmony import */ var _Fields_Slug__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Fields/Slug */ "./resources/assets/js/Admin/Fields/Slug.js");
/* harmony import */ var _Fields_ObjectRelation__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./Fields/ObjectRelation */ "./resources/assets/js/Admin/Fields/ObjectRelation.js");
/* harmony import */ var _Fields_MapCoordinates__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./Fields/MapCoordinates */ "./resources/assets/js/Admin/Fields/MapCoordinates.js");
/* harmony import */ var _Fields_File__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Fields/File */ "./resources/assets/js/Admin/Fields/File.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }









var FIELD_TYPE_DEFINITIONS = [{
  handler: _Fields_RichText__WEBPACK_IMPORTED_MODULE_0__["default"],
  config: _Fields_RichText__WEBPACK_IMPORTED_MODULE_0__["CONFIG_EDITOR"],
  selector: '.type-richText.full'
}, {
  handler: _Fields_RichTextCompact__WEBPACK_IMPORTED_MODULE_1__["default"],
  config: _Fields_RichTextCompact__WEBPACK_IMPORTED_MODULE_1__["CONFIG_EDITOR_COMPACT"],
  selector: '.type-richText.compact'
}, {
  handler: _Fields_IconPicker__WEBPACK_IMPORTED_MODULE_2__["default"],
  selector: '.type-icon-picker'
}, {
  handler: _Fields_Sortable__WEBPACK_IMPORTED_MODULE_3__["default"],
  config: {
    vendor: _Fields_Sortable__WEBPACK_IMPORTED_MODULE_3__["CONFIG_JQUERY_SORTABLE"]
  },
  selector: '.type-sortable'
}, {
  handler: _Fields_Slug__WEBPACK_IMPORTED_MODULE_4__["default"],
  selector: '.type-slug > .localization'
}, {
  handler: _Fields_Slug__WEBPACK_IMPORTED_MODULE_4__["default"],
  selector: '.type-slug:not(.i18n)'
}, {
  handler: _Fields_ObjectRelation__WEBPACK_IMPORTED_MODULE_5__["default"],
  selector: _Fields_ObjectRelation__WEBPACK_IMPORTED_MODULE_5__["default"].getSelector()
}, {
  handler: _Fields_MapCoordinates__WEBPACK_IMPORTED_MODULE_6__["default"],
  selector: '.type-map-coordinates'
}, {
  handler: _Fields_File__WEBPACK_IMPORTED_MODULE_7__["default"],
  selector: '.type-file'
}];
var Definition =
/*#__PURE__*/
function () {
  /**
   * @param {Object} data
   */
  function Definition(data) {
    _classCallCheck(this, Definition);

    this._handler = data.handler;
    this._config = data.config;
    this._selector = data.selector;
  }
  /**
   * @return {string}
   */


  _createClass(Definition, [{
    key: "selector",
    get: function get() {
      return this._selector;
    }
    /**
     * @param {string} value
     */
    ,
    set: function set(value) {
      this._selector = value;
    }
    /**
     * @return {Object}
     */

  }, {
    key: "config",
    get: function get() {
      return this._config;
    }
    /**
     * @param {Object} value
     */
    ,
    set: function set(value) {
      this._config = value;
    }
    /**
     * @return {Object}
     */

  }, {
    key: "handler",
    get: function get() {
      return this._handler;
    }
    /**
     * @param {Object} value
     */
    ,
    set: function set(value) {
      this._handler = value;
    }
  }]);

  return Definition;
}();

var FieldRegistry =
/*#__PURE__*/
function () {
  /**
   * @param {Array} data
   */
  function FieldRegistry(data) {
    _classCallCheck(this, FieldRegistry);

    var definitions = [];
    var _iteratorNormalCompletion = true;
    var _didIteratorError = false;
    var _iteratorError = undefined;

    try {
      for (var _iterator = data[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
        var definition = _step.value;
        definitions.push(new Definition(definition));
      }
    } catch (err) {
      _didIteratorError = true;
      _iteratorError = err;
    } finally {
      try {
        if (!_iteratorNormalCompletion && _iterator["return"] != null) {
          _iterator["return"]();
        }
      } finally {
        if (_didIteratorError) {
          throw _iteratorError;
        }
      }
    }

    this._definitions = definitions;
  }
  /**
   * @return {Array<Definition>}
   */


  _createClass(FieldRegistry, [{
    key: "addDefinition",

    /**
     * @param {Array} definition
     */
    value: function addDefinition(definition) {
      this._definitions.push(new Definition(definition));
    }
    /**
     * @param field
     * @return {?Definition}
     */

  }, {
    key: "getDefinition",
    value: function getDefinition(field) {
      var _iteratorNormalCompletion2 = true;
      var _didIteratorError2 = false;
      var _iteratorError2 = undefined;

      try {
        for (var _iterator2 = this.definitions[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
          var definition = _step2.value;

          if (definition.handler === field) {
            return definition;
          }
        }
      } catch (err) {
        _didIteratorError2 = true;
        _iteratorError2 = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion2 && _iterator2["return"] != null) {
            _iterator2["return"]();
          }
        } finally {
          if (_didIteratorError2) {
            throw _iteratorError2;
          }
        }
      }

      return null;
    }
  }, {
    key: "definitions",
    get: function get() {
      return this._definitions;
    }
    /**
     * @param value {Array<Definition>}
     */
    ,
    set: function set(value) {
      this._definitions = value;
    }
  }]);

  return FieldRegistry;
}();

/* harmony default export */ __webpack_exports__["default"] = (new FieldRegistry(FIELD_TYPE_DEFINITIONS));

/***/ }),

/***/ "./resources/assets/js/Admin/Fields/File.js":
/*!**************************************************!*\
  !*** ./resources/assets/js/Admin/Fields/File.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return File; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var File =
/*#__PURE__*/
function () {
  /**
   * @param element
   */
  function File(element) {
    _classCallCheck(this, File);

    this.element = element;
    this.registerEventHandlers();
  }
  /**
   * @return {void}
   */


  _createClass(File, [{
    key: "registerEventHandlers",
    value: function registerEventHandlers() {
      var field = this.getField();
      field.find('input').on('change', function () {
        field.find('input.remove').val(0);
      });
      field.find('button.remove').on('click', function () {
        field.find('input.remove').val(1);
        field.find('.thumbnail').hide();
        field.find('.file-details').hide();
      });
    }
  }, {
    key: "getField",
    value: function getField() {
      return jQuery(this.element);
    }
  }]);

  return File;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/Admin/Fields/IconPicker.js":
/*!********************************************************!*\
  !*** ./resources/assets/js/Admin/Fields/IconPicker.js ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return IconPicker; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var IconPicker =
/*#__PURE__*/
function () {
  function IconPicker(element) {
    _classCallCheck(this, IconPicker);

    this.element = element;

    if (!jQuery(this.element).data('initialized')) {
      this.registerEventHandlers();
    }
  }

  _createClass(IconPicker, [{
    key: "registerEventHandlers",
    value: function registerEventHandlers() {
      var _this = this;

      var field = jQuery(this.element);
      var select = field.find('select');
      var picker = field.find('.value > .contents');
      var selected = picker.find('.selected');
      var items = picker.find('.items');
      jQuery(window).click(function () {
        items.removeClass('active');
      });

      if (!this.isInteractive()) {
        selected.addClass('disabled');
      }

      field.on('click', function (event) {
        event.stopPropagation();
      });
      selected.on('click', function () {
        if (!_this.isInteractive()) {
          return false;
        }

        items.toggleClass('active');
      });
      items.on('click', 'li', function (event) {
        if (!_this.isInteractive()) {
          return false;
        }

        var target = jQuery(event.target);
        var item = target.is('li') ? target : target.closest('li');
        var index = item.index();
        select.val(select.find('option').eq(index).val());
        selected.html(item.html());
        items.removeClass('active');
      });
      field.data('initialized', true);
    }
  }, {
    key: "isInteractive",
    value: function isInteractive() {
      var disabled = jQuery(this.element).data('disabled');
      var interactive = jQuery(this.element).data('interactive') == 1;
      return interactive && !disabled;
    }
  }]);

  return IconPicker;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/Admin/Fields/MapCoordinates.js":
/*!************************************************************!*\
  !*** ./resources/assets/js/Admin/Fields/MapCoordinates.js ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery, $) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return MapCoordinates; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var MapCoordinates =
/*#__PURE__*/
function () {
  /**
   * @param element
   * @param {Object} config
   */
  function MapCoordinates(element, config) {
    _classCallCheck(this, MapCoordinates);

    this.element = element;
    this.config = config;
    this.registerEventHandlers();
  }
  /**
   * @return {void}
   */


  _createClass(MapCoordinates, [{
    key: "registerEventHandlers",
    value: function registerEventHandlers() {
      var field = this.getField();
      this.canvas = field.find('.canvas');
      this.coordinatesInput = this.getInput();
      this.map = new google.maps.Map(this.canvas[0], {
        zoom: this.coordinatesInput.data('zoom'),
        center: this.getCenterPosition()
      });
      google.maps.event.addDomListener(this.canvas[0], 'mousedown', function (e) {
        e.stopPropagation();
      });
      this.marker = new google.maps.Marker({
        map: this.map,
        draggable: this.isInteractive()
      });
      this.search = new google.maps.places.SearchBox(this.getSearchField()[0]);
      this.moveMarkerToInputValue();
      this.bindEvents();
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this = this;

      google.maps.event.addListener(this.map, 'click', function (event) {
        if (!_this.isInteractive()) {
          return false;
        }

        _this.clearSearch();

        _this.marker.setPosition(event.latLng);

        _this.writeCoordinates(_this.marker);
      });
      google.maps.event.addListener(this.marker, 'dragend', function () {
        _this.clearSearch();

        _this.writeCoordinates(_this.marker);
      });
      this.coordinatesInput.on('change', function () {
        _this.moveMarkerToInputValue();
      });
      this.map.addListener('bounds_changed', function () {
        _this.search.setBounds(_this.map.getBounds());
      });
      this.search.addListener('places_changed', function () {
        if (!_this.isInteractive()) {
          return false;
        }

        var places = _this.search.getPlaces();

        if (places.length === 0) {
          return;
        }

        var place = places[0];

        _this.map.setCenter(place.geometry.location);

        _this.marker.setPosition(place.geometry.location);

        _this.writeCoordinates(_this.marker);
      });
    }
  }, {
    key: "getCoordinates",
    value: function getCoordinates() {
      return this.convertToSemicolon(this.coordinatesInput.val());
    }
  }, {
    key: "convertToSemicolon",
    value: function convertToSemicolon(value) {
      return value.replace(' ', '').replace(',', ';');
    }
  }, {
    key: "hasValidInputLatLng",
    value: function hasValidInputLatLng() {
      var coordinates = this.getCoordinates();
      return coordinates.length >= 3 && coordinates.includes(';');
    }
  }, {
    key: "getInputLatLng",
    value: function getInputLatLng() {
      var coordinates = this.getCoordinates().split(';');
      return [parseFloat(coordinates[0]), parseFloat(coordinates[1])];
    }
  }, {
    key: "getCenterPosition",
    value: function getCenterPosition() {
      var field = this.getField();
      var input = this.getInput();

      if (this.hasValidInputLatLng()) {
        var coordinates = this.getInputLatLng();
        return {
          lat: coordinates[0],
          lng: coordinates[1]
        };
      }

      return {
        lat: input.data('latitude'),
        lng: input.data('longitude')
      };
    }
  }, {
    key: "moveMarkerToInputValue",
    value: function moveMarkerToInputValue() {
      if (!this.hasValidInputLatLng()) {
        return;
      }

      var coordinates = this.getInputLatLng();
      this.marker.setPosition(new google.maps.LatLng(coordinates[0], coordinates[1]));
    }
  }, {
    key: "writeCoordinates",
    value: function writeCoordinates(marker) {
      this.coordinatesInput.val([marker.position.lat(), marker.position.lng()].join(';'));
    }
  }, {
    key: "clearSearch",
    value: function clearSearch() {
      this.getSearchField().val(null);
    }
  }, {
    key: "getSearchField",
    value: function getSearchField() {
      return this.getField().find('.search-input');
    }
  }, {
    key: "getField",
    value: function getField() {
      return jQuery(this.element);
    }
  }, {
    key: "getInput",
    value: function getInput() {
      var field = this.getField();
      return field.find("input.coordinates-input");
    }
  }, {
    key: "isInteractive",
    value: function isInteractive() {
      var disabled = this.getInput().prop('disabled');
      var interactive = $(this.element).data('interactive');
      return interactive && !disabled;
    }
  }]);

  return MapCoordinates;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/Admin/Fields/ObjectRelation.js":
/*!************************************************************!*\
  !*** ./resources/assets/js/Admin/Fields/ObjectRelation.js ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery, $) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ObjectRelation; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/**
 * @typedef {Object} Options
 * @property {number} limit
 * @property {string} grouped
 * @property {number} indent
 */
var ObjectRelation =
/*#__PURE__*/
function () {
  _createClass(ObjectRelation, null, [{
    key: "getSelector",

    /**
     * @return {string}
     */
    value: function getSelector() {
      return '.type-object-relation';
    }
    /**
     * @param element
     * @param {Object} config
     */

  }]);

  function ObjectRelation(element, config) {
    _classCallCheck(this, ObjectRelation);

    this.element = element;
    this.config = config;
    this.selected = new Set(this.getInitialValues());
    this.registerEventHandlers();
  }
  /**
   * @return {void}
   */


  _createClass(ObjectRelation, [{
    key: "registerEventHandlers",
    value: function registerEventHandlers() {
      var _this = this;

      var field = this.getField();
      var related = this.getRelatedElement();
      var relational = this.getRelationalElement();
      var relationalItems = relational.find('.item');
      relationalItems.on('click', function (event) {
        var item = jQuery(event.target);

        if (!item.hasClass('item')) {
          item = item.closest('.item');
        }

        _this.selectRelation(item);
      });

      if (this.isGrouped()) {
        relational.on('click', '.group > .title', function (event) {
          var group = jQuery(event.target).closest('.group');
          group.find('.item').each(function (_, element) {
            _this.selectRelation(jQuery(element));
          });
        });
      }

      if (this.isSingular()) {
        jQuery(window).click(function () {
          relational.removeClass('active');
        });
        related.on('click', function () {
          if (!_this.isInteractive()) {
            return false;
          }

          jQuery(ObjectRelation.getSelector() + ' .relations.active').removeClass('active');
          relational.toggleClass('active');
        });
        field.children('.value').on('click', function (event) {
          return event.stopPropagation();
        });
        relationalItems.on('click', function () {
          return relational.toggleClass('active');
        });
      } else {
        this.getRelatedElement().on('click', '.item', function (event) {
          var item = jQuery(event.target);

          if (!item.hasClass('item')) {
            item = item.closest('.item');
          }

          _this.removeRelation(item);
        });
      }
    }
    /**
     * @param item
     */

  }, {
    key: "selectRelation",
    value: function selectRelation(item) {
      if (!this.isInteractive()) {
        return false;
      }

      var selectedItem = item.clone();
      var key = item.data('key');

      if (this.selected.has(key) || !this.isSingular() && this.hasLimit() && this.selected.size >= this.getLimit()) {
        return;
      }

      if (this.isSingular()) {
        this.selected.clear();
        this.getRelationalElement().find('.item').attr('data-inactive', 'false');
      }

      this.selected.add(key);
      item.attr('data-inactive', 'true');

      if (this.isSingular()) {
        this.getRelatedElement().html(selectedItem);
      } else {
        this.getRelatedElement().append(selectedItem);
      }

      this.updateSelectedInputElement();
    }
    /**
     * @param item
     */

  }, {
    key: "removeRelation",
    value: function removeRelation(item) {
      if (!this.isInteractive()) {
        return false;
      }

      var key = item.data('key');
      this.selected["delete"](key);
      item.remove();
      this.getRelationalElement().find('.item[data-key=' + key + ']').attr('data-inactive', 'false');
      this.updateSelectedInputElement();
    }
    /**
     * @return {Array}
     */

  }, {
    key: "getInitialValues",
    value: function getInitialValues() {
      var selected = [];
      var items = this.getRelatedElement().find('.item');
      items.each(function (key, element) {
        var item = jQuery(element);
        selected.push(item.data('key'));
      });
      return selected;
    }
    /**
     * @return {void}
     */

  }, {
    key: "updateSelectedInputElement",
    value: function updateSelectedInputElement() {
      this.getRelatedIdInputElement().val(Array.from(this.selected).join(','));
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getField",
    value: function getField() {
      return jQuery(this.element);
    }
    /**
     * @return {boolean}
     */

  }, {
    key: "hasLimit",
    value: function hasLimit() {
      return this.getLimit() > 0;
    }
    /**
     * @return {Number}
     */

  }, {
    key: "getLimit",
    value: function getLimit() {
      return parseInt(this.getOptions().limit);
    }
    /**
     * @return {boolean}
     */

  }, {
    key: "hasIndentation",
    value: function hasIndentation() {
      return this.getOptions().indent !== undefined;
    }
    /**
     * @return {boolean}
     */

  }, {
    key: "isSingular",
    value: function isSingular() {
      return this.getField().hasClass('single');
    }
    /**
     * @return {boolean}
     */

  }, {
    key: "isGrouped",
    value: function isGrouped() {
      return this.getOptions().grouped !== undefined;
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getRelatedIdInputElement",
    value: function getRelatedIdInputElement() {
      return this.getField().find('[data-name=related_id]');
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getRelatedElement",
    value: function getRelatedElement() {
      return this.getField().find('.related');
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getRelationalElement",
    value: function getRelationalElement() {
      return this.getField().find('.relations');
    }
    /**
     * @return {Options}
     */

  }, {
    key: "getOptions",
    value: function getOptions() {
      var element = this.getField().find('.object-relation');
      return {
        limit: element.data('limit'),
        grouped: element.data('grouped'),
        indent: element.data('indent')
      };
    }
  }, {
    key: "isInteractive",
    value: function isInteractive() {
      var disabled = $(this.element).data('disabled');
      var interactive = $(this.element).data('interactive');
      return interactive && !disabled;
    }
  }]);

  return ObjectRelation;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/Admin/Fields/RichText.js":
/*!******************************************************!*\
  !*** ./resources/assets/js/Admin/Fields/RichText.js ***!
  \******************************************************/
/*! exports provided: CONFIG_EDITOR, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "CONFIG_EDITOR", function() { return CONFIG_EDITOR; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return RichText; });
/* harmony import */ var ckeditor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ckeditor */ "./node_modules/ckeditor/ckeditor.js");
/* harmony import */ var ckeditor__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(ckeditor__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var ckeditor_adapters_jquery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ckeditor/adapters/jquery */ "./node_modules/ckeditor/adapters/jquery.js");
/* harmony import */ var ckeditor_adapters_jquery__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(ckeditor_adapters_jquery__WEBPACK_IMPORTED_MODULE_1__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance"); }

function _iterableToArrayLimit(arr, i) { if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === "[object Arguments]")) { return; } var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var CONFIG_EDITOR = {
  language: 'en',
  entities_latin: false,
  forcePasteAsPlainText: true,
  height: '400px',
  allowedContent: true,
  format_tags: 'p;h2;h3',
  toolbar: [['Bold', 'Italic'], ['Format'], ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Subscript', 'Superscript'], ['NumberedList', 'BulletedList'], ['Link', 'Unlink', 'Anchor', 'Image', 'MediaEmbed'], ['Source', 'Maximize', 'ShowBlocks']],
  extraPlugins: 'mediaembed',
  coreStyles_bold: {
    element: 'b',
    overrides: 'strong'
  },
  coreStyles_italic: {
    element: 'i',
    overrides: 'em'
  }
};

var RichText =
/*#__PURE__*/
function () {
  function RichText(element) {
    var config = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    _classCallCheck(this, RichText);

    if (element.id in CKEDITOR.instances) {
      CKEDITOR.instances[element.id].destroy();
    }

    this.element = element;
    this.config = config;
    this.initialize();
  }

  _createClass(RichText, [{
    key: "initialize",
    value: function initialize() {
      var token = this.getToken();
      var textarea = this.getTextarea();
      var config = Object.assign(this.getDefaultConfig(), {
        width: '100%',
        height: textarea.outerHeight(),
        filebrowserImageBrowseUrl: '/admin/filemanager?type=Images',
        filebrowserImageUploadUrl: '/admin/file-manager/upload?type=Images&responseType=json&_token=' + token,
        filebrowserBrowseUrl: '/admin/filemanager?type=Files',
        filebrowserUploadUrl: '/admin/file-manager/upload?type=Files&responseType=json&_token=' + token
      });

      if (!textarea.attr('id')) {
        textarea.attr('id', 'richtext_' + String(new Date().getTime()).replace(/\D/gi, ''));
      }

      if (textarea.data('attachment-upload-url')) {
        config.filebrowserUploadUrl = textarea.data('attachment-upload-url');
      }

      if (textarea.data('external-stylesheet')) {
        config.contentsCss = textarea.data('external-stylesheet');
      }

      for (var _i = 0, _Object$entries = Object.entries(this.getCustomConfig()); _i < _Object$entries.length; _i++) {
        var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
            key = _Object$entries$_i[0],
            value = _Object$entries$_i[1];

        config[key] = value;
      }

      CKEDITOR.replace(this.element, config);
      this.registerEventHandlers(textarea);
    }
  }, {
    key: "registerEventHandlers",
    value: function registerEventHandlers() {
      var _this = this;

      var textarea = this.getTextarea();
      var form = textarea.closest("form");
      form.on('beforevalidation', function () {
        for (var instance in CKEDITOR.instances) {
          if (CKEDITOR.instances.hasOwnProperty(instance)) {
            CKEDITOR.instances[instance].updateElement();
          }
        }
      });
      textarea.on('richtextresume', function () {
        if (!textarea.data('richtext-suspended')) {
          return;
        }

        textarea.show();
        textarea.data('richtext-suspended', false);

        _this.initialize();
      });
      textarea.on('richtextsuspend', function () {
        if (textarea.data('richtext-suspended')) {
          return;
        }

        CKEDITOR.instances[textarea.attr('id')].destroy();
        textarea.hide();
        textarea.data('richtext-suspended', true);
      });
      textarea.on('focusprepare', function () {
        if (textarea.data('richtext-suspended')) {
          return;
        }

        CKEDITOR.instances[textarea.attr('id')].focus();
      });
    }
  }, {
    key: "getCustomConfig",
    value: function getCustomConfig() {
      return this.config;
    }
  }, {
    key: "getTextarea",
    value: function getTextarea() {
      return jQuery(this.element);
    }
  }, {
    key: "getDefaultConfig",
    value: function getDefaultConfig() {
      return CONFIG_EDITOR;
    }
  }, {
    key: "getToken",
    value: function getToken() {
      var token = document.head.querySelector('meta[name="csrf-token"]');
      return token ? token.content : null;
    }
  }]);

  return RichText;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/Admin/Fields/RichTextCompact.js":
/*!*************************************************************!*\
  !*** ./resources/assets/js/Admin/Fields/RichTextCompact.js ***!
  \*************************************************************/
/*! exports provided: CONFIG_EDITOR_COMPACT, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "CONFIG_EDITOR_COMPACT", function() { return CONFIG_EDITOR_COMPACT; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return RichTextCompact; });
/* harmony import */ var _RichText__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RichText */ "./resources/assets/js/Admin/Fields/RichText.js");
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }


var CONFIG_EDITOR_COMPACT = {
  language: 'en',
  entities_latin: false,
  forcePasteAsPlainText: true,
  height: '400px',
  allowedContent: true,
  format_tags: 'p;h2;h3',
  toolbar: [['Bold', 'Italic'], ['Subscript', 'Superscript'], ['Link', 'Unlink'], ['Maximize', 'ShowBlocks']],
  forceEnterMode: true,
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode: CKEDITOR.ENTER_BR,
  autoParagraph: false
};

var RichTextCompact =
/*#__PURE__*/
function (_RichText) {
  _inherits(RichTextCompact, _RichText);

  function RichTextCompact() {
    _classCallCheck(this, RichTextCompact);

    return _possibleConstructorReturn(this, _getPrototypeOf(RichTextCompact).apply(this, arguments));
  }

  _createClass(RichTextCompact, [{
    key: "getDefaultConfig",
    value: function getDefaultConfig() {
      return CONFIG_EDITOR_COMPACT;
    }
  }]);

  return RichTextCompact;
}(_RichText__WEBPACK_IMPORTED_MODULE_0__["default"]);



/***/ }),

/***/ "./resources/assets/js/Admin/Fields/Slug.js":
/*!**************************************************!*\
  !*** ./resources/assets/js/Admin/Fields/Slug.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Slug; });
/* harmony import */ var _Services_SlugApiHandler__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../Services/SlugApiHandler */ "./resources/assets/js/Admin/Services/SlugApiHandler.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var Slug =
/*#__PURE__*/
function () {
  /**
   * @param element
   * @param config
   */
  function Slug(element, config) {
    _classCallCheck(this, Slug);

    this.element = element;
    this.config = config;
    this.translatable = this.getField().hasClass('localization');
    this.initial = this.getFieldInput().val().length === 0;
    this.registerEventHandlers();
  }
  /**
   * @return {void}
   */


  _createClass(Slug, [{
    key: "registerEventHandlers",
    value: function registerEventHandlers() {
      var _this = this;

      this.getFieldGenerateButton().on('click', function () {
        return _this.generate();
      });

      if (this.initial) {
        this.getFieldInput().on('keyup', function () {
          return _this.updateFieldLinkValue();
        });
        this.getFromFieldInput().on('blur', function () {
          return _this.generate();
        });
      }
    }
    /**
     * @return {void}
     */

  }, {
    key: "generate",
    value: function generate() {
      var _this2 = this;

      this.getApi().createFrom(this.getFromFieldInput().val()).done(function (value) {
        _this2.getFieldInput().val(value);

        _this2.updateFieldLinkValue();
      });
    }
    /**
     * @return {SlugApiHandler}
     */

  }, {
    key: "getApi",
    value: function getApi() {
      return new _Services_SlugApiHandler__WEBPACK_IMPORTED_MODULE_0__["default"](this.getGeneratorUrl(), {
        parent_id: this.getNodeParentId(),
        object_id: this.getObjectId(),
        model_table: this.getModelTable(),
        column_name: this.getFieldName(),
        locale: this.getFieldLocale()
      });
    }
    /**
     * @return {void}
     */

  }, {
    key: "updateFieldLinkValue",
    value: function updateFieldLinkValue() {
      this.getFieldLink().find('span:last').text(this.getFieldInput().val());
    }
    /**
     * @return {string}
     */

  }, {
    key: "getGeneratorUrl",
    value: function getGeneratorUrl() {
      return this.getFieldInput().data('generatorUrl');
    }
    /**
     * @return {string}
     */

  }, {
    key: "getFromFieldName",
    value: function getFromFieldName() {
      return this.getFieldInput().data('fromFieldName');
    }
    /**
     * @return {string}
     */

  }, {
    key: "getNodeParentId",
    value: function getNodeParentId() {
      return this.getFieldInput().data('nodeParentId');
    }
    /**
     * @return {int}
     */

  }, {
    key: "getObjectId",
    value: function getObjectId() {
      return this.getFieldInput().data('objectId');
    }
    /**
     * @return {int}
     */

  }, {
    key: "getModelTable",
    value: function getModelTable() {
      return this.getFieldInput().data('modelTable');
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getField",
    value: function getField() {
      return jQuery(this.element);
    }
    /**
     * @return {string}
     */

  }, {
    key: "getFieldName",
    value: function getFieldName() {
      return this.getField().data('name');
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getFieldInput",
    value: function getFieldInput() {
      return this.getField().find('.value > input');
    }
    /**
     * @return string
     */

  }, {
    key: "getFieldLocale",
    value: function getFieldLocale() {
      return this.getField().closest('.localization').data('locale');
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getFieldGenerateButton",
    value: function getFieldGenerateButton() {
      return this.getField().find('.button.generate');
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getFieldLink",
    value: function getFieldLink() {
      return this.getField().find('.link:first');
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getFromField",
    value: function getFromField() {
      var selector = '.field';
      var attributes = '[data-name=' + this.getFromFieldName() + ']';

      if (this.translatable) {
        selector = '.localization';
        attributes += '[data-locale=' + this.getFieldLocale() + ']';
      }

      return this.getGroup().find(selector + attributes);
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getFromFieldInput",
    value: function getFromFieldInput() {
      return this.getFromField().find('.value > input');
    }
    /**
     * @return {jQuery}
     */

  }, {
    key: "getGroup",
    value: function getGroup() {
      return this.getField().closest('fieldset.item,.body');
    }
  }]);

  return Slug;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/Admin/Fields/Sortable.js":
/*!******************************************************!*\
  !*** ./resources/assets/js/Admin/Fields/Sortable.js ***!
  \******************************************************/
/*! exports provided: CONFIG_JQUERY_SORTABLE, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "CONFIG_JQUERY_SORTABLE", function() { return CONFIG_JQUERY_SORTABLE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Sortable; });
/* harmony import */ var jquery_ui_ui_widgets_sortable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery-ui/ui/widgets/sortable */ "./node_modules/jquery-ui/ui/widgets/sortable.js");
/* harmony import */ var jquery_ui_ui_widgets_sortable__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery_ui_ui_widgets_sortable__WEBPACK_IMPORTED_MODULE_0__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }


var CONFIG_JQUERY_SORTABLE = {
  items: '> .item'
};

var Sortable =
/*#__PURE__*/
function () {
  function Sortable(element, config) {
    _classCallCheck(this, Sortable);

    this.element = element;
    this.config = config;
    this.container = jQuery(this.element).find('.body:first')[0];
    this.registerEventHandlers();
  }

  _createClass(Sortable, [{
    key: "registerEventHandlers",
    value: function registerEventHandlers() {
      var _this = this;

      var container = jQuery(this.container);
      var handlers = {};
      handlers.update = this.handleUpdate();

      handlers.stop = function (event, ui) {
        ui.item.find('textarea').trigger('richtextresume');
      };

      handlers.start = function (event, ui) {
        ui.item.find('textarea').trigger('richtextsuspend');
      };

      this.sortable = jQuery(this.container).sortable(Object.assign(handlers, this.config.vendor)); // Normal has many layout

      container.on('click', '> .item > .sortable-navigation .button', function (event) {
        return _this.manualSort(event);
      }); // Panel layouts

      container.on('click', '> .item > header .sortable-navigation.button', function (event) {
        return _this.manualSort(event);
      });
      container.on('DOMNodeInserted DOMNodeRemoved', function () {
        return _this.handleUpdate();
      });
    }
  }, {
    key: "handleUpdate",
    value: function handleUpdate() {
      var _this2 = this;

      var items = this.getItems();
      items.each(function (index) {
        _this2.setLocationInput(items.eq(index), index);
      });
    }
  }, {
    key: "manualSort",
    value: function manualSort(event) {
      var itemSelector = '.item';
      var target = jQuery(event.target);
      var button = target.is('button') ? target : target.closest('button');
      var item = button.closest(itemSelector);
      var text = item.find('textarea');
      text.trigger('richtextsuspend');

      if (button.hasClass('move-down')) {
        item.insertAfter(item.next(itemSelector));
      }

      if (button.hasClass('move-up')) {
        item.insertBefore(item.prev(itemSelector));
      }

      text.trigger('richtextresume');
      this.handleUpdate();
    }
  }, {
    key: "setLocationInput",
    value: function setLocationInput(item, locationIndex) {
      // Expects that the position input is always a direct descendant of the fieldset.item entry
      var sortByName = this.getSortByName();
      var positionInput = item.find("> input[data-name=\"".concat(sortByName, "\"]")).first();

      if (positionInput.length) {
        positionInput.val(locationIndex);
      }
    }
  }, {
    key: "getItems",
    value: function getItems() {
      return jQuery(this.container).find('> .item');
    }
  }, {
    key: "getSortByName",
    value: function getSortByName() {
      return jQuery(this.element).data('sortBy');
    }
  }]);

  return Sortable;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/Admin/Services/Navigator.js":
/*!*********************************************************!*\
  !*** ./resources/assets/js/Admin/Services/Navigator.js ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Navigator; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Navigator =
/*#__PURE__*/
function () {
  function Navigator() {
    _classCallCheck(this, Navigator);

    this.registerEventListeners();
  }

  _createClass(Navigator, [{
    key: "registerEventListeners",
    value: function registerEventListeners() {}
  }]);

  return Navigator;
}();



/***/ }),

/***/ "./resources/assets/js/Admin/Services/SlugApiHandler.js":
/*!**************************************************************!*\
  !*** ./resources/assets/js/Admin/Services/SlugApiHandler.js ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return SlugApiHandler; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var SlugApiHandler =
/*#__PURE__*/
function () {
  /**
   * @param {string} apiUrl
   * @param {Object} parameters
   */
  function SlugApiHandler(apiUrl) {
    var parameters = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    _classCallCheck(this, SlugApiHandler);

    this.apiUrl = apiUrl;
    this.parameters = parameters;
  }
  /**
   * @param {string} value
   */


  _createClass(SlugApiHandler, [{
    key: "createFrom",
    value: function createFrom(value) {
      return jQuery.get(this.apiUrl, Object.assign({
        from: value
      }, this.parameters));
    }
  }]);

  return SlugApiHandler;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/application.js":
/*!********************************************!*\
  !*** ./resources/assets/js/application.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Admin_FieldRegistry__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Admin/FieldRegistry */ "./resources/assets/js/Admin/FieldRegistry.js");
/* harmony import */ var _Admin_AdminPanel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Admin/AdminPanel */ "./resources/assets/js/Admin/AdminPanel.js");


var adminPanel = new _Admin_AdminPanel__WEBPACK_IMPORTED_MODULE_1__["default"](_Admin_FieldRegistry__WEBPACK_IMPORTED_MODULE_0__["default"]);
adminPanel.initialize();
/* harmony default export */ __webpack_exports__["default"] = (adminPanel);

/***/ }),

/***/ 2:
/*!**************************************************!*\
  !*** multi ./resources/assets/js/application.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/application.js */"./resources/assets/js/application.js");


/***/ })

},[[2,"/js/manifest","/js/vendor"]]]);