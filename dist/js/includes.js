(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/includes"],{

/***/ "./resources/assets/js/Admin/Tree/Item.js":
/*!************************************************!*\
  !*** ./resources/assets/js/Admin/Tree/Item.js ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Item; });
/* harmony import */ var _Store__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Store */ "./resources/assets/js/Admin/Tree/Store.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var Item =
/*#__PURE__*/
function () {
  /**
   * @param {jQuery<HTMLElement>}element
   * @param {Store} store
   */
  function Item(element, store) {
    _classCallCheck(this, Item);

    this.element = element;
    this.store = store;
    this.id = element.data('id');
    this.level = element.data('level');
  }
  /**
   * @returns {Store|null}
   */


  _createClass(Item, [{
    key: "getStorage",
    value: function getStorage() {
      return this.store ? this.store.get(this.id) : null;
    }
    /**
     * @param {Object} params
     *
     * @returns {jQuery}
     */

  }, {
    key: "makeSortable",
    value: function makeSortable(params) {
      return this.element.parent().sortable(params);
    }
  }, {
    key: "toggleChildVisibility",
    value: function toggleChildVisibility() {
      var storage = this.getStorage();

      if (storage) {
        storage.setCollapsed(!storage.isCollapsed());
      }

      if (this.isCollapsed()) {
        this.element.removeClass('collapsed');
      } else {
        this.element.addClass('collapsed');
      }
    }
    /**
     * @returns {Boolean}
     */

  }, {
    key: "isCollapsed",
    value: function isCollapsed() {
      return this.element.hasClass('collapsed');
    }
    /**
     * @returns {Item}
     */

  }, {
    key: "getParent",
    value: function getParent() {
      var parent = 'li[data-level=' + --this.level + ']';
      return new Item(this.element.closest(parent), this.store);
    }
    /**
     * @returns {Item}
     */

  }, {
    key: "getLeftSibling",
    value: function getLeftSibling() {
      return new Item(this.element.prev('li[data-id]'), this.store);
    }
    /**
     * @returns {Item}
     */

  }, {
    key: "getRightSibling",
    value: function getRightSibling() {
      return new Item(this.element.next('li[data-id]'), this.store);
    }
  }]);

  return Item;
}();



/***/ }),

/***/ "./resources/assets/js/Admin/Tree/Store.js":
/*!*************************************************!*\
  !*** ./resources/assets/js/Admin/Tree/Store.js ***!
  \*************************************************/
/*! exports provided: stores, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "stores", function() { return stores; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Store; });
/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! js-cookie */ "./node_modules/js-cookie/src/js.cookie.js");
/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(js_cookie__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _StoreItem__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./StoreItem */ "./resources/assets/js/Admin/Tree/StoreItem.js");
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var stores = {};

var Store =
/*#__PURE__*/
function () {
  /**
   * @param {string} storeName
   */
  function Store(storeName) {
    _classCallCheck(this, Store);

    this.storeName = storeName;
    stores[storeName] = this;
  }
  /**
   * @returns {Object}
   */


  _createClass(Store, [{
    key: "getStored",
    value: function getStored() {
      var storedData = js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.getJSON(this.storeName);

      if (_typeof(storedData) !== 'object') {
        this.save({});
        return {};
      }

      return storedData;
    }
    /**
     * @param {*} id
     * @returns {NodeStoreItem}
     */

  }, {
    key: "get",
    value: function get(id) {
      var stored = this.getStored();

      if (typeof stored[id] === 'undefined') {
        stored[id] = true;
        this.save(stored);
      }

      return new _StoreItem__WEBPACK_IMPORTED_MODULE_1__["default"](id, stored[id], this);
    }
    /**
     * @param {StoreItem} store
     */

  }, {
    key: "saveItem",
    value: function saveItem(store) {
      var stored = this.getStored();
      stored[store.id] = store.getContents();
      this.save(stored);
    }
    /**
     * @param {*} data
     */

  }, {
    key: "save",
    value: function save(data) {
      js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.set(this.storeName, data);
    }
    /**
     * @param {String} storeName
     * @returns {Store}
     */

  }], [{
    key: "getInstance",
    value: function getInstance(storeName) {
      return stores[storeName];
    }
  }]);

  return Store;
}();



/***/ }),

/***/ "./resources/assets/js/Admin/Tree/StoreItem.js":
/*!*****************************************************!*\
  !*** ./resources/assets/js/Admin/Tree/StoreItem.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return NodeStoreItem; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var NodeStoreItem =
/*#__PURE__*/
function () {
  /**
   *
   * @param id
   * @param contents
   * @param store
   */
  function NodeStoreItem(id) {
    var contents = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    var store = arguments.length > 2 ? arguments[2] : undefined;

    _classCallCheck(this, NodeStoreItem);

    this.id = id;
    this.contents = contents;
    this.store = store;
  }
  /**
   * @returns {*}
   */


  _createClass(NodeStoreItem, [{
    key: "getContents",
    value: function getContents() {
      return this.contents;
    }
    /**
     * @returns {Boolean}
     */

  }, {
    key: "isCollapsed",
    value: function isCollapsed() {
      return this.contents;
    }
  }, {
    key: "setCollapsed",
    value: function setCollapsed(state) {
      this.contents = state;
      this.save();
    }
  }, {
    key: "save",
    value: function save() {
      if (this.store) {
        this.store.saveItem(this);
      }
    }
  }]);

  return NodeStoreItem;
}();



/***/ }),

/***/ "./resources/assets/js/Admin/Tree/Tree.js":
/*!************************************************!*\
  !*** ./resources/assets/js/Admin/Tree/Tree.js ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Tree; });
/* harmony import */ var _Item__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Item */ "./resources/assets/js/Admin/Tree/Item.js");
/* harmony import */ var _Store__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Store */ "./resources/assets/js/Admin/Tree/Store.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }




var Tree =
/*#__PURE__*/
function () {
  /**
   * @param {HTMLElement} collectionElement
   * @param {String} repositionUrl
   * @param {String} storeName
   */
  function Tree(collectionElement) {
    var repositionUrl = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    var storeName = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

    _classCallCheck(this, Tree);

    this.repositionUrl = repositionUrl;
    this.collection = jQuery(collectionElement);
    this.store = storeName ? new _Store__WEBPACK_IMPORTED_MODULE_1__["default"](storeName) : null;
    this.initialize();
  }

  _createClass(Tree, [{
    key: "initialize",
    value: function initialize() {
      var _this = this;

      this.collection.data('tree', this);
      jQuery('body').on('click', '.dialog .node-cell label', this.onDialogLabelClick.bind(this));
      jQuery(document).ready(function () {
        var items = _this.collection.find('ul[data-level] > li');

        items.each(function (index, element) {
          var item = new _Item__WEBPACK_IMPORTED_MODULE_0__["default"](jQuery(element), _this.store);
          item.element.on('click', '> .collapser-cell > .collapser', function () {
            return item.toggleChildVisibility();
          }); // Note: this approach does not allow changing parent for nodes, need to add connectWith

          item.makeSortable({
            items: '> li',
            stop: function stop(event, ui) {
              item = new _Item__WEBPACK_IMPORTED_MODULE_0__["default"](ui.item, _this.store);

              _this.send(item);
            }
          });
        });
      });
    }
  }, {
    key: "onDialogLabelClick",
    value: function onDialogLabelClick(e) {
      jQuery('.dialog .node-cell label').removeClass('selected');
      jQuery(e.target).addClass('selected');
    }
  }, {
    key: "send",
    value: function send(item) {
      jQuery.post(this.repositionUrl, {
        _token: Tree.getToken(),
        id: item.id,
        toLeftId: item.getLeftSibling().id,
        toRightId: item.getRightSibling().id
      });
    }
    /**
     * @returns {String}
     */

  }], [{
    key: "getToken",
    value: function getToken() {
      return jQuery('[name=_token]:first').val();
    }
  }]);

  return Tree;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/accordion.js":
/*!**************************************************!*\
  !*** ./resources/assets/js/include/accordion.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery, $) {jQuery(document).ready(function () {
  $('.js-accordion-trigger').on('click', function (event) {
    event.preventDefault();
    var animationSpeed = 150;
    var accordion = $(event.target).closest('.accordion');
    var accordionContent = $(accordion).children('.body');
    var accordionToggle = $(accordion).find('.fa');
    accordionContent.slideToggle(animationSpeed);
    accordionToggle.toggleClass('fa-minus');
    accordionToggle.toggleClass('fa-plus');
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/ajax.js":
/*!*********************************************!*\
  !*** ./resources/assets/js/include/ajax.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(document).ready(function () {
  var body = jQuery('body');
  body.on('contentreplace', function (e, content, selector) {
    if (content && 'status' in content && 'getResponseHeader' in content) {
      // use content only if the response has valid 200 and html content type
      var status = content.status;

      if (status !== 200) {
        return;
      }

      var content_type = content.getResponseHeader("content-type");

      if (!content_type || !content_type.match(/html/)) {
        return;
      }

      content = content.responseText;
    }

    var new_node;

    if (typeof selector !== 'undefined') {
      // selector given, find matching node in given content
      content = jQuery('<html />').append(content);
      new_node = content.find(selector);
    } else {
      // no selector given, whole content is the new node
      new_node = content;
    } // old_node defaults to event target if no selector given


    var old_node = jQuery(e.target);

    if (typeof selector !== 'undefined') {
      // but matches self or descendants if selector is given
      if (!old_node.is(selector)) {
        old_node = old_node.find(selector);
      }
    }

    old_node.replaceWith(new_node);
    new_node.trigger('contentloaded');
  }); // use setTimeout to trigger this after all scripts have been loaded
  // and attached their initial handlers for this event

  setTimeout(function () {
    body.trigger('contentloaded');
    body.trigger('contentdone');
  }, 0);
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/ajaxbox.js":
/*!************************************************!*\
  !*** ./resources/assets/js/include/ajaxbox.js ***!
  \************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony import */ var magnific_popup__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! magnific-popup */ "./node_modules/magnific-popup/dist/jquery.magnific-popup.js");
/* harmony import */ var magnific_popup__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(magnific_popup__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var jquery_ui_ui_widgets_draggable__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery-ui/ui/widgets/draggable */ "./node_modules/jquery-ui/ui/widgets/draggable.js");
/* harmony import */ var jquery_ui_ui_widgets_draggable__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery_ui_ui_widgets_draggable__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _modules_UrlBuilder__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../modules/UrlBuilder */ "./resources/assets/js/modules/UrlBuilder.js");



jQuery(document).ready(function () {
  var ajaxbox_link_selector = 'a.ajaxbox';
  var xhr;
  var body = jQuery('body');
  var cached_modals = {};

  var open_ajax_box = function open_ajax_box(params) {
    var magnific_popup_params = {
      showCloseBtn: false,
      modal: params.modal,
      callbacks: {
        open: function open() {
          this.contentContainer.trigger('ajaxboxaftershow', [this, params]);
        },
        beforeClose: function beforeClose() {
          this.contentContainer.trigger('ajaxboxbeforeclose');
        }
      }
    };

    if (params.type === 'image') {
      magnific_popup_params.items = {
        src: params.url
      };
      magnific_popup_params.type = "image";
    } else {
      magnific_popup_params.items = {
        src: params.content,
        type: "inline"
      };
    }

    jQuery.magnificPopup.open(magnific_popup_params);
    return;
  };

  var close_ajax_box = function close_ajax_box() {
    jQuery.magnificPopup.close();
  };

  body.on('ajaxboxaftershow', function (e, ajaxbox, params) {
    ajaxbox.contentContainer.addClass('ajaxbox-inner'); // enable drag with header

    if (ajaxbox.wrap.draggable !== undefined) {
      ajaxbox.wrap.draggable({
        handle: ajaxbox.contentContainer.find('section header').first()
      });
    } // insert close button if header exists and box is not modal


    if (!params.modal) {
      var close_container = ajaxbox.contentContainer.first();

      if (params.type !== 'image') {
        close_container = ajaxbox.contentContainer.find('section header').first();
      }

      if (close_container.length > 0) {
        var close_icon = jQuery('<i />').addClass('fa fa-times');
        var close_button = jQuery('<button />').attr('type', 'button').addClass('button secondary close only-icon').append(close_icon);
        close_button.on('click', function () {
          close_ajax_box();
        });
        close_container.append(close_button);
      }
    } // focus on cancel button in footer if found


    var cancel_button = ajaxbox.contentContainer.find('section footer .button[data-type="cancel"]').first();

    if (cancel_button.length > 0) {
      cancel_button.bind('click', function () {
        body.trigger('ajaxboxclose');
        return false;
      });
      cancel_button.focus();
    }

    ajaxbox.contentContainer.trigger('contentloaded');
    ajaxbox.contentContainer.trigger('ajaxboxdone', params);
  });
  body.on('ajaxboxinit', function (e) {
    var target = jQuery(e.target); // init links

    var links = target.is(ajaxbox_link_selector) ? target : target.find(ajaxbox_link_selector);
    links.on('click', function () {
      var link = jQuery(this);
      var params = {
        url: new _modules_UrlBuilder__WEBPACK_IMPORTED_MODULE_2__["default"](link.attr('href')).add({
          ajax: 1
        }).getUrl(),
        modal: link.is('[data-modal]'),
        trigger: link,
        cache: link.is('[data-cache]')
      };

      if (link.attr('rel') === 'image') {
        params.type = 'image';
      }

      link.trigger('ajaxboxopen', params);
      return false;
    });
  });
  body.on('ajaxboxopen', function (e, params) {
    if ('cache' in params && params.cache === true) {
      var cached = params.url in cached_modals;

      if (cached) {
        params.content = cached_modals[params.url];
      }
    } // params expects either url or content


    if ('content' in params) {
      open_ajax_box(params);
    } else if ('url' in params) {
      if ('trigger' in params) {
        params.trigger.trigger('loadingstart');
      }

      if (xhr) {
        xhr.abort();
      }

      xhr = jQuery.ajax({
        url: params.url,
        type: 'get',
        success: function success(data) {
          params.content = data;
          open_ajax_box(params);

          if (params.cache) {
            cached_modals[params.url] = data;
          }
        }
      });
    }
  });
  body.on('ajaxboxdone', function (e, params) {
    if (params && 'trigger' in params) {
      params.trigger.trigger('loadingend');
    }

    jQuery(e.target).find('.dialog').trigger('contentdone');
  });
  body.on('ajaxboxclose', function () {
    close_ajax_box();
  }); // attach ajaxboxinit to all loaded content

  body.on('contentloaded', function (e) {
    // reinit ajaxbox for all content that gets replaced via ajax
    jQuery(e.target).trigger('ajaxboxinit');
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/constructor.js":
/*!****************************************************!*\
  !*** ./resources/assets/js/include/constructor.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(document).ready(function () {
  jQuery('body').on('click', '.constructor-dialog .js-select-block', function (e) {
    e.preventDefault();
    var target = jQuery(e.target);
    var name = target.data('name');
    var field = target.data('field');
    var constructor = jQuery('body').find(".type-constructor[data-namespaced-name=\"".concat(field, "\"]"));
    var templates = constructor.data('templates');

    if (name in templates) {
      constructor.trigger('nestedfieldscreate', {
        target_block: constructor,
        template: jQuery(templates[name])
      });
      jQuery('body').trigger('ajaxboxclose');
    }
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/dialogs.js":
/*!************************************************!*\
  !*** ./resources/assets/js/include/dialogs.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(function () {
  var body = jQuery('body');
  body.on('contentdone', function (e) {
    jQuery(e.target).find(".dialog").addBack('.dialog').addClass('initialized');
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/field.type_associated_set.js":
/*!******************************************************************!*\
  !*** ./resources/assets/js/include/field.type_associated_set.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(document).ready(function () {
  var body = jQuery('body');
  jQuery(document).bind('associatedsetsinit', function (e) {
    var target_selector = '.field.type-associated-set';
    var target = jQuery(e.target);

    if (!target.is(target_selector)) {
      target = target.find(target_selector);
    }

    target.each(function () {
      var block = jQuery(this);
      var checkboxes = block.find('input.keep');
      checkboxes.bind('click', function () {
        var checkbox = jQuery(this);
        var destroy = checkbox.siblings('input.destroy');
        destroy.val(checkbox.prop('checked') ? 'false' : 'true');
      });
    });
  });
  body.on('contentloaded', function (e, event_params) {
    jQuery(e.target).trigger('associatedsetsinit', event_params);
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/field.type_date_or_datetime_or_time.js":
/*!****************************************************************************!*\
  !*** ./resources/assets/js/include/field.type_date_or_datetime_or_time.js ***!
  \****************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony import */ var jquery_ui_ui_widgets_datepicker__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery-ui/ui/widgets/datepicker */ "./node_modules/jquery-ui/ui/widgets/datepicker.js");
/* harmony import */ var jquery_ui_ui_widgets_datepicker__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery_ui_ui_widgets_datepicker__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var jquery_ui_timepicker_addon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery-ui-timepicker-addon */ "./node_modules/jquery-ui-timepicker-addon/dist/jquery-ui-timepicker-addon.js");
/* harmony import */ var jquery_ui_timepicker_addon__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery_ui_timepicker_addon__WEBPACK_IMPORTED_MODULE_1__);


jQuery(document).ready(function () {
  var body = jQuery('body');

  var chevron_icons_show = function chevron_icons_show(instance) {
    // Set timeout to execute this after datepicker has been initialized
    setTimeout(function () {
      jQuery(instance.dpDiv[0]).find('.ui-datepicker-prev').removeClass().addClass('button only-icon previous').html('<i class="fa fa-chevron-left"></i>');
      jQuery(instance.dpDiv[0]).find('.ui-datepicker-next').removeClass().addClass('button only-icon next').html('<i class="fa fa-chevron-right"></i>');
    }, 0);
  }; // initialize date/datetime/time pickers


  body.on('calendarsinit', function (e) {
    var block = jQuery(e.target);
    var options = {
      controlType: 'select',
      showHour: true,
      showMinute: true,
      showTimezone: false,
      showMillisec: false,
      showMicrosec: false,
      changeMonth: true,
      changeYear: true,
      beforeShow: function beforeShow(input, instance) {
        chevron_icons_show(instance);
      },
      onChangeMonthYear: function onChangeMonthYear(year, month, instance) {
        chevron_icons_show(instance);
      }
    };
    block.find('.date-picker').each(function () {
      var picker = jQuery(this);
      var opt = options;
      opt.dateFormat = picker.data('date-format') || 'yy-mm-dd';
      opt.minDate = picker.data('min-date');
      opt.maxDate = picker.data('max-date');
      picker.datepicker(opt);
    });
    block.find('.datetime-picker').each(function () {
      var picker = jQuery(this);
      var opt = options;
      opt.dateFormat = picker.data('date-format') || 'yy-mm-dd';
      opt.pickerTimeFormat = picker.data('time-format') || 'HH:mm';
      opt.minDate = picker.data('min-date');
      opt.maxDate = picker.data('max-date');
      picker.datetimepicker(opt);
    });
    block.find('.time-picker').each(function () {
      var picker = jQuery(this);
      var opt = options;
      opt.pickerTimeFormat = picker.data('time-format') || 'HH:mm';
      picker.timepicker(options);
    });
  });
  body.on('contentloaded', function (e) {
    jQuery(e.target).trigger('calendarsinit');
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/filter.js":
/*!***********************************************!*\
  !*** ./resources/assets/js/include/filter.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery, $) {jQuery(document).ready(function () {
  var filterOpenButton = $('.js-filter-trigger');
  var filterWindow = $('.form-filter');
  var contentWindow = $('#main > .content');
  var searchInputName = 'search';
  filterWindow.submit(function (eventObj) {
    addSearchToFilter();
    return true;
  });

  function openCloseFilter() {
    filterWindow.toggleClass('show');
    contentWindow.toggleClass('show-filter');
  }

  filterOpenButton.on('click', function () {
    openCloseFilter();
  });

  function addSearchToFilter() {
    $('<input />').attr('type', 'hidden').attr('name', searchInputName).attr('value', $("#".concat(searchInputName)).val()).appendTo(filterWindow);
  }

  $('body').on('contentdone', '.js-save-filter-dialog', initSaveFilterDialog);

  function initSaveFilterDialog(event) {
    var dialog = $(event.target);
    var form = dialog.find('form');
    form.on('submit', function () {
      addSearchToFilter();
      dialog.find('[name="filter"]').val(filterWindow.serialize());
      return true;
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/loader.js":
/*!***********************************************!*\
  !*** ./resources/assets/js/include/loader.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(function () {
  jQuery('body').on('loadingstart', '.button', function (e) {
    var button = jQuery(e.target);

    if (button.hasClass('loading')) {
      return;
    }

    button.addClass('loading');
    button.data('disabled-before-loading', button.prop('disabled'));
    button.prop('disabled', true);
    var loader = jQuery('<i />').addClass('loader fa fa-spin fa-spinner');
    button.append(loader);
  });
  jQuery('body').on('loadingend', '.button', function (e) {
    var button = jQuery(e.target);
    button.find('.loader').remove();
    var disabled_before_loading = button.data('disabled-before-loading');

    if (typeof disabled_before_loading !== 'undefined') {
      if (!disabled_before_loading) {
        button.prop('disabled', false);
      }
    }

    button.removeClass('loading');
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/localization.js":
/*!*****************************************************!*\
  !*** ./resources/assets/js/include/localization.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(function () {
  var body = jQuery('body');
  var overlay = jQuery('<div />').addClass('localization-menu-overlay').appendTo(body);
  overlay.bind('click', function () {
    body.trigger('localizationmenucloseall');
  });
  body.bind('localizationinit', function (e) {
    var block = jQuery(e.target);
    e.stopPropagation();
    var fields;

    if (block.is('.field.i18n')) {
      fields = block;
    } else {
      fields = block.find('.field.i18n');
    }

    if (fields.length < 1) {
      return;
    }

    fields.bind('localizationmenuopen', function () {
      var field = jQuery(this); // close all other open menus

      body.trigger('localizationmenucloseall');
      var menu = field.data('localization-menu');
      field.attr('data-localization-menu-open', true);
      menu.appendTo(body);
      field.trigger('localizationmenuposition');
      overlay.show();
      menu.show();
      return;
    });
    fields.bind('localizationmenuclose', function () {
      var field = jQuery(this);
      var menu = field.data('localization-menu');
      var localization_switch = field.data('localization-switch');
      menu.hide().appendTo(localization_switch);
      overlay.hide();
      field.removeAttr('data-localization-menu-open');
      return;
    });
    fields.bind('localizationmenutoggle', function () {
      var field = jQuery(this);
      var event = field.attr('data-localization-menu-open') ? 'localizationmenuclose' : 'localizationmenuopen';
      field.trigger(event);
    });
    fields.bind('localizationmenuposition', function () {
      var field = jQuery(this);

      if (!field.attr('data-localization-menu-open')) {
        return;
      }

      var menu = field.data('localization-menu');
      var trigger = field.data('localization-switch-trigger');
      var triggerOffset = trigger.offset();
      menu.css({
        left: triggerOffset.left + trigger.outerWidth() - menu.outerWidth(),
        top: triggerOffset.top + trigger.outerHeight()
      });
    });
    fields.find('.localization-switch .trigger').click(function () {
      jQuery(this).closest('.field.i18n').trigger('localizationmenutoggle');
    });
    fields.find('.localization-menu-items button').click(function () {
      var button = jQuery(this);
      var locale = button.attr('data-locale');
      var menu = button.closest('.localization-menu-items');
      var field = menu.data('field');
      var localization_box = field.find('.localization[data-locale="' + locale + '"]');
      body.trigger('localizationmenucloseall');
      localization_box.trigger('localizationlocaleactivate');
    });
    fields.bind('localizationlocaleset', function (e, params) {
      var field = jQuery(this);
      var locale = params.locale;
      var localization_boxes = field.find('.localization[data-locale]');
      var target_box = localization_boxes.filter('[data-locale="' + locale + '"]');
      var other_boxes = localization_boxes.not(target_box);
      target_box.addClass('active');
      other_boxes.removeClass('active');
      var trigger_label = field.find('.localization-switch .trigger .label');
      trigger_label.text(locale);
    });
    fields.find('.localization').bind('localizationlocaleactivate', function () {
      var localization_box = jQuery(this);
      var locale = localization_box.attr('data-locale');
      var form = localization_box.closest('form');
      form.find('.field.i18n').trigger('localizationlocaleset', {
        locale: locale
      });
      body.trigger('settingssave', ["arbory.i18n.locale", locale]);
    });
    var input_selector = 'input[type!="hidden"],textarea,select';
    fields.find(input_selector).bind('focusprepare', function (e) {
      var localization_box = jQuery(e.target).closest('.localization');

      if (localization_box.length < 1) {
        return;
      } // focus target is inside a i18n localization box


      if (!localization_box.is('.active')) {
        localization_box.trigger('localizationlocaleactivate');
      }
    });
    fields.each(function () {
      var field = jQuery(this);
      var localization_switch = field.find('.localization-switch').first();
      field.data('localization-switch', localization_switch);
      field.data('localization-switch-trigger', localization_switch.find('.trigger').first());
      var menu = localization_switch.find('menu').first();
      field.data('localization-menu', menu);
      menu.data('field', field);
    });
  });
  body.bind('localizationmenucloseall', function () {
    body.find('.field.i18n[data-localization-menu-open]').trigger('localizationmenuclose');
  }); // attach localizationinit to all loaded content

  body.on('contentloaded', function (e) {
    // reinit localization for all content that gets replaced via ajax
    jQuery(e.target).trigger('localizationinit');
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/mass.js":
/*!*********************************************!*\
  !*** ./resources/assets/js/include/mass.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {var COOKIE_NAME_NODES = 'bulk';
jQuery(document).ready(function ($) {
  /**
   * @type {*|jQuery|HTMLElement}
   */
  var bulkGrid = $('.bulk-edit-grid');
  var bulkActions;
  var bulkEditRows;
  var bulkEditHeader;
  var bulkUrl;
  /**
   * Modify action url
   */

  function modifyUrl() {
    bulkActions.attr('href', getUrlWithIds);
  }
  /**
   * Serialize all checkboxes and concatenate with url
   * @returns {string}
   */


  function getUrlWithIds() {
    var ids = bulkEditRows.filter(':checked').serializeArray();
    return bulkUrl + (bulkUrl.indexOf('?') >= 0 ? '&' : '?') + $.param(ids);
  }
  /**
   * Check all rows if header checkbox is checked
   */


  function allChecked() {
    bulkEditRows.prop("checked", this.checked);
    modifyUrl();
  }
  /**
   * Useful when DOM changed
   */


  function updateSelectors() {
    bulkActions = $('.js-bulk-edit-button', bulkGrid);
    bulkUrl = bulkActions.attr('href');
    bulkEditRows = $('.js-bulk-edit-row-checkbox', bulkGrid);
    bulkEditHeader = $('.js-bulk-edit-header-checkbox', bulkGrid);
  }
  /**
   * Disable/enable form fields
   * @param target
   */


  function prepareFormEvents(target) {
    target.find('input.bulk-control').on('change', function (e) {
      target.find('[name="resource[' + $(this).attr('data-target') + ']"]').prop("disabled", !this.checked);
    });
  }
  /**
   * Events for grid checkboxes
   */


  function prepareGridEvents() {
    bulkEditRows.on('change', modifyUrl);
    bulkEditHeader.on('change', allChecked);
  }
  /**
   * Init grid events and try to init bulk form
   */


  $('body').on('contentloaded', function (e, event_params) {
    $(e.target).trigger('bulkforminit', event_params);
  });
  /**
   * Bulk edit form event
   */

  $(document).bind('bulkforminit', function (e) {
    var target = $(e.target);

    if (bulkGrid.length) {
      updateSelectors();
      prepareGridEvents();
    }

    target = target.find('.edit-resources');
    prepareFormEvents(target);
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/menu.js":
/*!*********************************************!*\
  !*** ./resources/assets/js/include/menu.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! js-cookie */ "./node_modules/js-cookie/src/js.cookie.js");
/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(js_cookie__WEBPACK_IMPORTED_MODULE_0__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }


var COOKIE_NAME_MENU = 'menu';
var SELECTOR_MENU_ITEM = 'li[data-name]';

var Menu =
/*#__PURE__*/
function () {
  function Menu(element) {
    _classCallCheck(this, Menu);

    this.element = element;
  }

  _createClass(Menu, [{
    key: "getItemElements",
    value: function getItemElements() {
      return this.element.find(SELECTOR_MENU_ITEM);
    }
  }, {
    key: "getItems",
    value: function getItems() {
      var _this = this;

      var items = [];
      jQuery.each(this.getItemElements(), function (key, element) {
        items.push(new MenuItem(_this, jQuery(element)));
      });
      return items;
    }
  }, {
    key: "collapseItems",
    value: function collapseItems() {
      jQuery.each(this.getItems(), function (key, menuItem) {
        menuItem.collapseItems();
      });
    }
  }, {
    key: "isCompact",
    value: function isCompact() {
      return this.element.closest('body').hasClass('side-compact');
    }
  }]);

  return Menu;
}();

var MenuItem =
/*#__PURE__*/
function () {
  function MenuItem(menu, element) {
    _classCallCheck(this, MenuItem);

    this.menu = menu;
    this.element = element;
    this.name = element.data('name');
  }

  _createClass(MenuItem, [{
    key: "getStorage",
    value: function getStorage() {
      return MenuStore.get(this.name);
    }
  }, {
    key: "getChildBlockElement",
    value: function getChildBlockElement() {
      return this.element.find('.block:first');
    }
  }, {
    key: "getChildElements",
    value: function getChildElements() {
      return this.getChildBlockElement().children(SELECTOR_MENU_ITEM);
    }
  }, {
    key: "hasChildren",
    value: function hasChildren() {
      return this.getChildElements().length;
    }
  }, {
    key: "getIconElement",
    value: function getIconElement() {
      // TODO: fix typo
      return this.element.children('.trigger').find('.collapser i');
    }
  }, {
    key: "toggleItems",
    value: function toggleItems() {
      if (!this.hasChildren()) {
        return;
      }

      if (this.menu.isCompact()) {
        this.menu.collapseItems();
      }

      this.isCollapsed() ? this.expandItems() : this.collapseItems();
    }
  }, {
    key: "isCollapsed",
    value: function isCollapsed() {
      return this.menu.isCompact() ? !this.element.hasClass('open') : this.element.hasClass('collapsed');
    }
  }, {
    key: "collapseItems",
    value: function collapseItems() {
      this.menu.isCompact() ? this.element.removeClass('open') : this.element.addClass('collapsed');
    }
  }, {
    key: "expandItems",
    value: function expandItems() {
      this.menu.isCompact() ? this.element.addClass('open') : this.element.removeClass('collapsed');
    }
  }, {
    key: "updateIcon",
    value: function updateIcon() {
      var icon = this.getIconElement();
      icon.toggleClass('fa-chevron-right', this.menu.isCompact());

      if (!this.menu.isCompact()) {
        icon.toggleClass('fa-chevron-down', this.isCollapsed());
        icon.toggleClass('fa-chevron-up', !this.isCollapsed());
      }
    }
  }]);

  return MenuItem;
}();

var MenuStore =
/*#__PURE__*/
function () {
  function MenuStore() {
    _classCallCheck(this, MenuStore);
  }

  _createClass(MenuStore, null, [{
    key: "getStored",
    value: function getStored() {
      var storedData = js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.getJSON(COOKIE_NAME_MENU);

      if (typeof storedData === 'undefined') {
        MenuStore.save({});
        return {};
      }

      return storedData;
    }
  }, {
    key: "get",
    value: function get(id) {
      var stored = this.getStored();

      if (typeof stored[id] === 'undefined') {
        stored[id] = null;
        MenuStore.save(stored);
      }

      return new MenuStoreItem(id, stored[id]);
    }
  }, {
    key: "saveItem",
    value: function saveItem(store) {
      var stored = this.getStored();
      stored[store.id] = store.getContents();
      MenuStore.save(stored);
    }
  }, {
    key: "save",
    value: function save(data) {
      js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.set(COOKIE_NAME_MENU, JSON.stringify(data));
    }
  }]);

  return MenuStore;
}();

var MenuStoreItem =
/*#__PURE__*/
function () {
  function MenuStoreItem(id) {
    var contents = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    _classCallCheck(this, MenuStoreItem);

    this.id = id;
    this.contents = contents;
  }

  _createClass(MenuStoreItem, [{
    key: "getContents",
    value: function getContents() {
      return this.contents;
    }
  }, {
    key: "isCollapsed",
    value: function isCollapsed() {
      return this.contents;
    }
  }, {
    key: "setCollapsed",
    value: function setCollapsed(state) {
      this.contents = state;
      this.save();
    }
  }, {
    key: "save",
    value: function save() {
      MenuStore.saveItem(this);
    }
  }]);

  return MenuStoreItem;
}();

jQuery(document).ready(function () {
  var menu = new Menu(jQuery('aside nav > ul'));
  jQuery.each(menu.getItems(), function (key, menuItem) {
    menuItem.updateIcon();
    menuItem.element.find('.trigger:first').on('click', function () {
      menuItem.toggleItems();
      menuItem.updateIcon();

      if (!menu.isCompact() && menuItem.hasChildren()) {
        menuItem.getStorage().setCollapsed(menuItem.isCollapsed());
      }
    });
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/nested_fields.js":
/*!******************************************************!*\
  !*** ./resources/assets/js/include/nested_fields.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(document).ready(function () {
  var body = jQuery('body');
  jQuery(document).bind('nestedfieldsinit', function (e) {
    var target = jQuery(e.target);

    if (!target.is('.nested')) {
      target = target.find('.nested');
    }

    target.each(function () {
      var block = jQuery(this);
      var list = block.find('.list').first();
      var block_name = block.attr('data-name');
      var item_selector = '.item[data-name="' + block_name + '"]';
      var new_item_selector = '.item[data-name="' + block_name + '"].new';
      var existing_item_selector = '.item[data-name="' + block_name + '"]:not(.new)';
      block.click(function (event, event_params) {
        var trigger = jQuery(event.target); // webkit browsers go beyond button node when setting click target

        if (!trigger.is('button')) {
          trigger = trigger.parents('button').first();
        }

        if (!trigger.is('button.add-nested-item') && !trigger.is('button.remove-nested-item')) {
          // irrelevant click
          return;
        } // skip click on disabled buttons


        if (trigger.prop("disabled")) {
          return;
        }

        var target_block = trigger.parents('.nested').first();

        if (target_block.attr('data-name') !== block_name) {
          return; // only react to own clicks
        }

        if (trigger.is('.add-nested-item')) {
          var template = null;

          if (target_block.is('.polymorphic')) {
            var type_select = target_block.find('footer select.template-types');
            template = jQuery(type_select.find('option:selected').data('template'));
          } else {
            template = jQuery(target_block.data('arbory-template'));
          }

          block.trigger('nestedfieldscreate', {
            target_block: target_block,
            original_params: event_params,
            template: template
          });
        } else if (trigger.is('.remove-nested-item')) {
          block.trigger('nestedfieldsremove', {
            target_block: target_block,
            item: trigger.parents(item_selector).first(),
            original_params: event_params
          });
        }

        return;
      });
      block.on('nestedfieldscreate', function (e, params) {
        var target_block = params.target_block;
        var event_params = params.original_params;
        var template = params.template;

        if (target_block.attr('data-name') !== block_name) {
          return; // only react to own clicks
        }

        if (template.length !== 1) {
          return null;
        }

        var new_item = template;
        new_item.addClass('new');
        new_item.appendTo(list);
        new_item.trigger('nestedfieldsreindex', event_params);

        if (event_params && event_params.no_animation) {
          new_item.trigger('nestedfieldsitemadd', event_params);
          new_item.trigger('contentloaded', event_params);
        } else {
          if (new_item.is('tr, td')) {
            new_item.css({
              opacity: 1
            }).hide();
            new_item.fadeIn('normal', function () {
              new_item.trigger('nestedfieldsitemadd', event_params);
              new_item.trigger('contentloaded', event_params);
            });
          } else {
            new_item.css({
              opacity: 0
            });
            new_item.slideDown('fast', function () {
              new_item.css({
                opacity: 1
              }).hide();
              new_item.fadeIn('fast', function () {
                new_item.trigger('nestedfieldsitemadd', event_params);
                new_item.trigger('contentloaded', event_params);
              });
            });
          }
        }
      });
      block.on('nestedfieldsremove', function (e, params) {
        var target_block = params.target_block;
        var item = params.item;
        var event_params = params.original_params;

        if (target_block.attr('data-name') !== block_name) {
          return; // only react to own clicks
        }

        var removeItem = function removeItem(item) {
          item.trigger('contentbeforeremove', event_params);
          var parent = item.parent();
          var destroy_inputs = item.find('input.destroy');

          if (destroy_inputs.length > 0) {
            // mark as destroyable and hide
            destroy_inputs.val(true);
            item.hide();
          } else {
            item.remove();
          }

          target_block.trigger('nestedfieldsreindex', event_params);
          parent.trigger('contentremoved', event_params);
        };

        item.addClass('removed');
        item.trigger('nestedfieldsitemremove', event_params);

        if (event_params && event_params.no_animation) {
          removeItem(item);
        } else {
          item.fadeOut('fast', function () {
            if (item.is('tr,td')) {
              removeItem(item);
            } else {
              item.css({
                opacity: 0
              }).show().slideUp('fast', function () {
                removeItem(item);
              });
            }
          });
        }
      });
      block.on('nestedfieldsreindex', function () {
        // update data-index attributes and names/ids for all fields inside a block
        // in case of nested blocks, this bubbles up and gets called for each parent block also
        // so that each block can update it's own index in the names
        // only new items are changed.
        // existing items always preserve their original indexes
        // new item indexes start from largest of existing item indexes + 1
        var first_available_new_index = 0;
        var existing_items = block.find(existing_item_selector);
        existing_items.each(function () {
          var index = jQuery(this).attr('data-index');

          if (typeof index === 'undefined') {
            return;
          }

          index = index * 1;

          if (index >= first_available_new_index) {
            first_available_new_index = index + 1;
          }
        });
        var new_items = block.find(new_item_selector);
        var index = first_available_new_index;
        var changeable_attributes = [];
        new_items.each(function () {
          var item = jQuery(this);
          item.attr('data-index', index); // this matches both of these syntaxes in attribute values:
          //
          //  resource[foo_attributes][0][bar]  /  resource[foo][_template_][bar]
          //  resource_foo_attributes_0_bar     /  resource_foo__template__bar
          //

          var matchPattern = new RegExp('(\\[|_)' + block_name + '(\\]\\[|_)(\\d*|_template_)?(\\]|_)');
          var searchPattern = new RegExp('((\\[|_)' + block_name + '(\\]\\[|_))(\\d*|_template_)?(\\]|_)', 'g');
          var replacePattern = '$1' + index + '$5';
          var attrs = ['name', 'id', 'for']; // collect changeable attributes

          item.find('input,select,textarea,button,label').each(function () {
            for (var i = 0; i < attrs.length; i++) {
              var attr = jQuery(this).attr(attrs[i]);

              if (attr && attr.match(matchPattern)) {
                var params = {
                  element: this,
                  attribute: attrs[i],
                  old_value: attr,
                  new_value: attr.replace(searchPattern, replacePattern)
                };

                if (params.old_value === params.new_value) {
                  continue;
                }

                changeable_attributes.push(params);
              }
            }
          });
          index++;
        }); // perform change in two parts:
        // at first change all changeable attributes to unique temporary strings for ALL affected items
        // and then change the attributes to actual values
        // this is needed so that any code in external beforeattributechange / attributechange handlers
        // does not encounter ID collisions during the process (multiple elements temporarily sharing the same ID)
        // change to temporary values

        var temp_value_prefix = 'nestedfieldsreindex_temporary_value_';
        jQuery.each(changeable_attributes, function (attribute_index, params) {
          var element = jQuery(params.element);
          element.trigger('beforeattributechange', params);
          element.attr(params.attribute, temp_value_prefix + attribute_index);
        }); // change to actual new values

        jQuery.each(changeable_attributes, function (attribute_index, params) {
          var element = jQuery(params.element);
          element.attr(params.attribute, params.new_value);
          element.trigger('attributechanged', params);
        });
      });
      block.on('sortableupdate', function () {
        block.trigger('nestedfieldsreindex');
      });
      block.on('nestedfieldsitemadd', function (e) {
        var item = jQuery(e.target);

        if (item.attr('data-name') !== block_name) {
          return; // the added item does not belong to this block
        } // focus first visibile field in item


        item.find('input, select, textarea').filter(':visible').first().focus();
      });
    });
  });
  body.on('contentloaded', function (e, event_params) {
    jQuery(e.target).trigger('nestedfieldsinit', event_params);
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/nodes.js":
/*!**********************************************!*\
  !*** ./resources/assets/js/include/nodes.js ***!
  \**********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony import */ var jquery_ui_ui_widgets_sortable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery-ui/ui/widgets/sortable */ "./node_modules/jquery-ui/ui/widgets/sortable.js");
/* harmony import */ var jquery_ui_ui_widgets_sortable__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery_ui_ui_widgets_sortable__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Admin_Tree_Tree__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Admin/Tree/Tree */ "./resources/assets/js/Admin/Tree/Tree.js");


var COOKIE_NAME_NODES = 'nodes';
jQuery(document).ready(function () {
  var collection = jQuery('body.controller-nodes .collection');
  var tree = new _Admin_Tree_Tree__WEBPACK_IMPORTED_MODULE_1__["default"](collection, collection.data('reposition-url') || '/admin/nodes/api/node_reposition', collection.data('store-name') || COOKIE_NAME_NODES);
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/notifications.js":
/*!******************************************************!*\
  !*** ./resources/assets/js/include/notifications.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

jQuery(function () {
  var body = jQuery('body');
  var container = body.children('.notifications').first();
  var icon_base_class = 'icon fa';
  var icons_by_type = {
    info: 'fa-info',
    success: 'fa-check',
    error: 'fa-times-circle'
  };
  var notifications = {};
  var close_icon = jQuery('<i />').addClass('fa fa-times');
  var close_button = jQuery('<button type="button" />').addClass('close button only-icon').append(close_icon).attr('title', container.attr('data-close-text'));
  close_button.click(function () {
    var notification_id = jQuery(this).closest('.notification').attr('data-id');
    body.trigger('notificationremove', notification_id);
  });

  var get_params = function get_params(custom_params) {
    var random_id;

    do {
      random_id = Math.random().toString(16).slice(2);
    } while (typeof notifications[random_id] !== 'undefined'); // set defaults and then override with custom_params


    var params = {
      id: random_id,
      type: 'info',
      closable: true,
      // default closable notifications to automatic closing after a timeout;
      // default non-closable notifications to never close automatically
      duration: 'closable' in custom_params && !custom_params.closable ? null : 5,
      message: '',
      html: null,
      icon: 'type' in custom_params && custom_params.type in icons_by_type ? icons_by_type[custom_params.type] : icons_by_type.info
    };
    jQuery.extend(params, custom_params);
    return params;
  };

  var get_notification_ids = function get_notification_ids(params) {
    var notification_ids = [];

    if (typeof params === 'string') {
      // locate notification by id
      notification_ids.push(params);
    } else if (_typeof(params) === 'object') {
      // match multiple notifications by params
      jQuery.each(notifications, function (notification_id, notification) {
        var notification_params = notification.data('params');
        var all_params_match = true;
        jQuery.each(params, function (param, value) {
          if (typeof notification_params[param] === 'undefined' || notification_params[param] !== value) {
            all_params_match = false;
            return false;
          }
        });

        if (all_params_match) {
          notification_ids.push(notification_id);
        }
      });
    }

    return notification_ids;
  };

  body.on('notificationsinit', function () {
    body.on('notificationadd', function (e, custom_params) {
      // adds or updates a notification
      var notification;
      var params = get_params(custom_params);
      var is_new = false;

      if (typeof notifications[params.id] === 'undefined') {
        is_new = true;
        notification = jQuery('<div />').addClass('notification').attr('data-id', params.id);
        notification.append(jQuery('<i />'));
        notification.append(jQuery('<div />').addClass('content'));
        notifications[params.id] = notification;
        notification.hide();
        notification.appendTo(container);
      }

      notification = notifications[params.id];
      notification.data('params', params);
      notification.attr('data-type', params.type);
      notification.children('i').removeClass().addClass(icon_base_class + ' ' + params.icon); // check whether notification already have close button added

      if (params.closable && notification.find('.close').length === 0) {
        notification.append(close_button.clone(true));
      } else if (!params.closable) {
        notification.find('.close').remove();
      }

      if (typeof params.html !== 'string') {
        params.html = jQuery('<div />').addClass('message').text(params.message);
      }

      notification.find('.content').html(params.html);

      if (is_new) {
        notification.fadeIn('slow', function () {
          body.trigger('notificationadded', {
            notification: notification
          });
        });
      } else {
        body.trigger('notificationupdated', {
          notification: notification
        });
      }
    });
    body.on('notificationremove', function (e, params) {
      // removes single or multiple notifications
      var removable_notification_ids = get_notification_ids(params);
      jQuery.each(removable_notification_ids, function (index, notification_id) {
        if (typeof notifications[notification_id] === 'undefined') {
          return;
        }

        var notification = notifications[notification_id];
        var timer = notification.data('removal-timer');
        clearTimeout(timer);
        notification.fadeOut('fast', function () {
          notification.css({
            opacity: 0
          }).show().slideUp('fast', function () {
            notification.remove();
          });
        });
        delete notifications[notification_id];
      });
    });
    body.on('notificationremovedelayed', function (e, removal_params) {
      // sets up removal timer for a single notification
      // accepts id and duration in removal_params
      var notification_id = removal_params.id;

      if (typeof notifications[notification_id] === 'undefined') {
        return;
      }

      var notification = notifications[notification_id];
      notification.data('removal-timer', setTimeout(function () {
        body.trigger('notificationremove', notification_id);
      }, removal_params.duration * 1000));
    });
    body.on('notificationadded notificationupdated', function (e, event_params) {
      if (!('notification' in event_params)) {
        return;
      }

      var notification = event_params.notification;
      var params = notification.data('params');
      var timer = notification.data('removal-timer');
      clearTimeout(timer);

      if (params.duration) {
        var removal_params = {
          id: params.id,
          duration: params.duration
        };
        body.trigger('notificationremovedelayed', removal_params);
      }
    });
    body.on('notificationaddflash', '.flash', function () {
      // convert .flash notice to notification
      var params = {
        type: jQuery(this).attr('data-type'),
        message: jQuery(this).text().trim()
      };
      var id = jQuery(this).attr('data-id');

      if (typeof id !== 'undefined') {
        params.id = id;
      }

      body.trigger('notificationadd', params);
      jQuery(this).remove();
    });
  });
  body.trigger('notificationsinit'); // attach notificationaddflash to all loaded content

  body.on('contentloaded', function (e) {
    jQuery(e.target).find('.flash').trigger('notificationaddflash');
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/pagination.js":
/*!***************************************************!*\
  !*** ./resources/assets/js/include/pagination.js ***!
  \***************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony import */ var url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! url */ "./node_modules/url/url.js");
/* harmony import */ var url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(url__WEBPACK_IMPORTED_MODULE_0__);

jQuery(function () {
  var body = jQuery('body');
  body.on('contentloaded', function (e) {
    jQuery(e.target).find('.pagination select[name="page"]').on('change', function () {
      var val = jQuery(this).val();

      if (val) {
        window.location.href = url__WEBPACK_IMPORTED_MODULE_0___default.a.format({
          query: {
            page: val
          }
        });
      }
    });
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/remote_validator.js":
/*!*********************************************************!*\
  !*** ./resources/assets/js/include/remote_validator.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery, $) {var RemoteValidator = function RemoteValidator(form) {
  // self
  var v = this;
  var body = jQuery('body'); // selector for field input matching

  var input_selector = 'input[type!="hidden"],textarea,select';
  var submit_elements_selector = 'input[type="submit"], input[type="image"], button';
  v.form = form;
  v.clicked_button = null;
  v.form.on('click', submit_elements_selector, function (event) {
    var target = jQuery(event.target); // webkit sends inner button elements as event targets instead of the button
    // so catch if the click is inside a button element and change the target if needed

    var closest_button = target.closest('button');

    if (closest_button.length > 0) {
      target = closest_button;
    } // register only submit buttons - buttons with type="submit" or without type attribute at all
    // direct target[0].type property is used because of inconsistent attr() method return values
    // between older and newer jQuery versions


    if (target.is('button') && target[0].type !== 'submit') {
      return;
    }

    v.clicked_button = target;
  });
  v.form.on('ajax:beforeSend', function (event, xhr) {
    xhr.validation_id = 'v' + new Date().getTime() + Math.random();
    v.form.attr('data-validation-id', xhr.validation_id);

    if (v.clicked_button) {
      v.clicked_button.trigger('loadingstart');
    }
  });
  v.form.on('ajax:complete', function (event, xhr) {
    var json_response;
    var event_params = {
      validation_id: xhr.validation_id
    };

    switch (xhr.status) {
      case 303:
        // validation + saving ok
        try {
          json_response = jQuery.parseJSON(xhr.responseText);
        } catch (error) {
          v.form.trigger('validation:fail', [v, event_params]);
          break;
        }

        event_params.response = json_response;
        v.form.trigger('validation:ok', [v, event_params]);
        break;

      case 200:
        // validation ok
        event_params.response = xhr;
        v.form.trigger('validation:ok', [v, event_params]);
        break;

      case 422:
        // validation returned errors
        try {
          json_response = jQuery.parseJSON(xhr.responseText);
        } catch (error) {
          v.form.trigger('validation:fail', [v, event_params]);
          break;
        }

        event_params.response = json_response;
        var errors = [];
        jQuery.each(json_response.errors, function (fieldName, fieldErrors) {
          jQuery.each(fieldErrors, function (index, error) {
            if (fieldName.indexOf('.') > -1) {
              var nameParts = fieldName.split('.');
              fieldName = nameParts.shift() + '[' + nameParts.join('][') + ']';
            }

            var error_object = {
              message: error,
              fieldName: fieldName
            };
            errors.push(error_object);
          });
        });
        jQuery.each(errors, function (index, error) {
          var field = null;
          var eventTarget = null;
          field = v.form.find('[name="' + error.fieldName + '"],[name="' + error.fieldName + '[]"]').filter(':not([type="hidden"])').first();
          event_params.error = error;

          if (field && field.length > 0) {
            eventTarget = field;
          } else {
            eventTarget = v.form;
          }

          eventTarget.trigger('validation:error', [v, event_params]);
        });
        break;

      default:
        // something wrong in the received response
        v.form.trigger('validation:fail', [v, event_params]);
        break;
    }

    v.form.trigger('validation:end', [v, event_params]);
  });
  v.form.on('validation:ok', function (event, v, event_params) {
    if (!event_params || !event_params.response) {
      return;
    }

    if ('url' in event_params.response) {
      // json redirect url received
      event.preventDefault(); // prevent validator's built in submit_form on ok

      document.location.href = event_params.response.url;
    } else if ('getResponseHeader' in event_params.response) {
      event.preventDefault(); // prevent validator's built in submit_form on ok

      body.trigger('contentreplace', [event_params.response, "> header"]);
      body.trigger('contentreplace', [event_params.response, "> aside"]);
      body.trigger('contentreplace', [event_params.response, "> main"]);
    }
  });
  v.form.on('validation:error', function (event, v, event_params) {
    var error_node = null;
    var error = event_params.error;
    var target = jQuery(event.target);
    var form = target.is('form') ? target : target.closest('form');

    if (target.is(input_selector)) {
      // i18n fields contain a child .field element
      var field_box = target.parents('.field:not(.localization)').first();

      if (field_box.length !== 1) {
        return;
      }

      var wrap = field_box.is('.i18n') ? target.closest('.localization') : field_box;
      var error_box = wrap.find('.error-box');

      if (error_box.length < 1) {
        error_box = jQuery('<div class="error-box"><div class="error"></div></div>');
        error_box.appendTo(wrap.find('.value').first());
      }

      error_node = error_box.find('.error');
      error_node.attr('data-validation-id', event_params.validation_id);
      error_node.text(error.message);
      field_box.addClass('has-error');

      if (field_box.is('.i18n')) {
        wrap.addClass('has-error');
      }
    } else if (target.is('form')) {
      var form_error_box = form.find('.form-error-box');

      if (form_error_box.length < 1) {
        var form_error_box_container = form.find('.body').first();

        if (form_error_box_container.length < 1) {
          form_error_box_container = form;
        }

        form_error_box = jQuery('<div class="form-error-box"></div>');
        form_error_box.prependTo(form_error_box_container);
      } // reuse error node if it has the same text


      form_error_box.find('.error').each(function () {
        if (error_node) {
          return;
        }

        if (jQuery(this).text() === error.message) {
          error_node = jQuery(this);
        }
      });
      var new_error_node = !error_node;

      if (!error_node) {
        error_node = jQuery('<div class="error"></div>');
      }

      error_node.attr('data-validation-id', event_params.validation_id);
      error_node.text(error.message);

      if (new_error_node) {
        error_node.appendTo(form_error_box);
      }

      form.addClass('has-error'); // Scroll to form_error_box

      form_error_box.parent().scrollTop(form_error_box.offset().top - form_error_box.parent().offset().top + form_error_box.parent().scrollTop());
    }

    form.find('.button.loading').trigger('loadingend');
  });
  v.form.on('validation:end', function (event, v, event_params) {
    // remove all errors left from earlier validations
    var last_validation_id = form.attr('data-validation-id');

    if (event_params.validation_id !== last_validation_id) {
      // do not go further if this is not the last validation
      return;
    }

    event_params.except_validation_id = last_validation_id;
    form.trigger('validation:clearerrors', [v, event_params]); // if error fields still exist, focus to first visible
    // locate first input inside visible error fields,
    // but for i18n fields exclude inputs inside .localization without .has-error

    var focus_target = form.find('.field.has-error').filter(':visible').find(input_selector).not('.localization:not(.has-error) *').first();
    focus_target.trigger('focusprepare');
    focus_target.focus();
  });
  v.form.on('validation:clearerrors', function (event, v, event_params) {
    // trigger this to clear existing errors in form
    // optional event_params.except_validation_id can be used
    // to preserve errors created by that specific validation
    var except_validation_id = event_params && 'except_validation_id' in event_params ? event_params.except_validation_id : null; // remove field errors

    form.find('.field.has-error').each(function () {
      var error_boxes;
      var field = jQuery(this); // in case of i18n fields there may be multiple error boxes inside a single field

      error_boxes = field.find('.error-box');
      error_boxes.each(function () {
        var error_box = jQuery(this);
        var error_node = error_box.find('.error');

        if (!except_validation_id || error_node.attr('data-validation-id') !== except_validation_id) {
          if (field.is('.i18n')) {
            error_box.closest('.localization').removeClass('has-error');
          }

          error_box.remove();
        }
      }); // see if any error boxes are left in the field.

      error_boxes = field.find('.error-box');

      if (error_boxes.length < 1) {
        field.removeClass('has-error');
      }
    }); // remove form errors

    if (form.hasClass('has-error')) {
      var form_error_box = form.find('.form-error-box');
      var form_errors_remain = false;
      form_error_box.find('.error').each(function () {
        var error_node = jQuery(this);

        if (!except_validation_id || error_node.attr('data-validation-id') !== except_validation_id) {
          error_node.remove();
        } else {
          form_errors_remain = true;
        }
      });

      if (!form_errors_remain) {
        form_error_box.remove();
        form.removeClass('has-error');
      }
    }
  });
  jQuery(document).on('validation:ok validation:error validation:fail', 'form', function (event, validator) {
    if (validator !== v || !v.form[0]) {
      return;
    }

    switch (event.type) {
      case 'validation:ok':
        // validation passed
        v.submit_form();
        break;

      case 'validation:error':
        // validation error
        v.clicked_button = null;
        break;

      case 'validation:fail':
        // fail (internal validation failure, not a user error)
        v.submit_form();
        break;
    }
  });
};

RemoteValidator.prototype.submit_form = function () {
  var v = this; // add originally clicked submit button to form as a hidden field

  if (v.clicked_button) {
    var button = v.clicked_button.first();
    var name = button.attr('name');

    if (name) {
      var input = v.form.find('input[type="hidden"][name="' + name + '"]');

      if (input.length < 1) {
        input = jQuery('<input />').attr('type', 'hidden').attr('name', button.attr('name'));
        input.appendTo(v.form);
      }

      input.val(button.val());
    }
  }

  v.form[0].submit();
};

jQuery(function () {
  // define validation handlers
  jQuery(document).on('validation:init', 'form', function (event) {
    var _this = this;

    if (event.isDefaultPrevented()) {
      return;
    }

    var form = jQuery(event.target);

    if (form.data('validator')) {
      // multiple validators on a single form are not supported
      // a validator already exists. return
      return;
    }

    form.data('validator', new RemoteValidator(form)); // validation initalized finished, add data attribute for it (used by automatized test, etc)

    form.attr("data-remote-validation-initialized", true);
    jQuery('.main .primary .button[name=save], .main .primary .button[name=save_and_return], .edit-resources .primary .button[name=save]').click(function (event) {
      event.preventDefault();
      form.trigger('beforevalidation');
      var formData = new FormData(_this);
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function beforeSend(xhr) {
          form.trigger('ajax:beforeSend', [xhr]);
        },
        complete: function complete(xhr) {
          form.trigger('ajax:complete', [xhr]);
        }
      });
    });
  }); // attach remote validation to any new default forms after any content load

  jQuery('body').on('contentloaded', function (event) {
    var block = jQuery(event.target);
    var forms = block.is('form[data-remote-validation]') ? block : block.find('form[data-remote-validation]');
    forms.trigger('validation:init');
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/search.js":
/*!***********************************************!*\
  !*** ./resources/assets/js/include/search.js ***!
  \***********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony import */ var _modules_UrlBuilder__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/UrlBuilder */ "./resources/assets/js/modules/UrlBuilder.js");

jQuery(function () {
  'use strict';

  var body = jQuery('body');
  body.on('searchinit', 'form', function (e) {
    var request;
    var timeout;
    var form = jQuery(e.target);
    var all_selector = 'input, select';
    var filterForm = jQuery('#grid-filter'); // Set up options.

    var options = form.data('search-options');
    var defaults = {
      result_blocks: {
        main_section: {
          result_selector: 'section',
          target: 'main > section:first'
        }
      },
      rebind: false
    };
    options = jQuery.extend(true, defaults, options);
    var elements = {
      inputs: jQuery(),
      submit: jQuery()
    };

    var collect_all_elements = function collect_all_elements() {
      elements.inputs = jQuery(all_selector);
      elements.submit = form.find('button[type="submit"]');
    };

    var set_previous_values = function set_previous_values() {
      elements.inputs.each(function () {
        var input = jQuery(this);
        input.data('previous-value', get_current_value(input));
      });
    };

    var get_current_value = function get_current_value(input) {
      if (input.is('input[type="checkbox"]:not(:checked)')) {
        return '';
      } else if (!input.is('input[type="checkbox"]:checked')) {
        return input.val();
      } else {
        return input.val() || '';
      }
    };

    var start_search = function start_search() {
      // Cancel previous timeout.
      clearTimeout(timeout); // Store previous values for all inputs.

      set_previous_values(); // Cancel previous unfinished request.

      if (request) {
        request.abort();
      }

      timeout = setTimeout(function () {
        elements.submit.trigger('loadingstart'); // Construct url.

        var form_url = form.attr('action');
        var url = new _modules_UrlBuilder__WEBPACK_IMPORTED_MODULE_0__["default"]({
          baseUrl: form_url
        });
        url.add(form.serializeArray());
        url.add(filterForm.serializeArray());

        if ('replaceState' in window.history) {
          window.history.replaceState(window.history.state, window.title, url.getUrl());
        }

        url.add({
          ajax: 1
        }); // Send request.

        request = jQuery.ajax({
          url: url.getUrl(),
          success: function success(response) {
            form.trigger('searchresponse', response);
            form.trigger('searchend');
          }
        });
      }, 200);
    };

    var stop_search = function stop_search() {
      elements.submit.trigger('loadingend');
    };

    var start_search_if_value_changed = function start_search_if_value_changed() {
      var input = jQuery(this);

      if (get_current_value(input) === input.data('previous-value')) {
        return;
      }

      form.trigger('searchstart');
    };

    if (filterForm.length) {
      form.on('submit', function (eventObj) {
        filterForm.trigger('submit');
        return false;
      });
    }

    form.on('searchresponse', function (e, response) {
      response = jQuery('<div />').append(response); // For each result block find its content in response and copy it
      // to its target container.

      for (var key in options.result_blocks) {
        if (options.result_blocks.hasOwnProperty(key)) {
          var block = options.result_blocks[key];
          var content = response.find(block.result_selector).first().html();
          jQuery(block.target).html(content);
          jQuery(block.target).trigger('contentloaded');
        }
      }

      if (options.rebind) {
        collect_all_elements();
      }
    });
    form.on('change keyup', all_selector, start_search_if_value_changed);
    form.on('searchstart', start_search);
    form.on('searchend', stop_search);
    collect_all_elements();
    set_previous_values();
  });
  jQuery('.view-index form.search').trigger('searchinit');
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/sidebar.js":
/*!************************************************!*\
  !*** ./resources/assets/js/include/sidebar.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(function () {
  var body = jQuery('body');
  var side_compact_overlay = jQuery('<div />').addClass('side-compact-overlay').appendTo(body);
  side_compact_overlay.bind('click', function () {
    body.trigger('sidecompactcloseall');
  });
  var first_level_side_items = jQuery();
  body.on('sidecompactcloseall', function () {
    first_level_side_items.filter('.open').trigger('sidecompactitemclose');
  });
  body.on('contentloaded', function (e) {
    var header = jQuery(e.target).find('header').addBack().filter('body > header');

    if (header.length < 1) {
      return;
    }

    header.on('click', function () {
      // add additional trigger on header to close opened compact submenu
      // because header is above the side compact overlay
      if (!body.hasClass('side-compact') || first_level_side_items.filter('.open').length < 1) {
        return;
      }

      body.trigger('sidecompactcloseall');
      return false;
    });
  });
  body.on('contentloaded', function (e) {
    var sidebar = jQuery(e.target).find('aside').addBack().filter('body > aside');

    if (sidebar.length < 1) {
      return;
    }

    first_level_side_items = sidebar.find('nav > ul > li');
    first_level_side_items.on('sidecompactitemopen', function () {
      body.trigger('sidecompactcloseall');
      jQuery(this).addClass('open');
      side_compact_overlay.show();
    });
    first_level_side_items.on('sidecompactitemclose', function () {
      jQuery(this).removeClass('open');
      side_compact_overlay.hide();
    });
    first_level_side_items.on('sidecompacttoggle', function () {
      var item = jQuery(this);
      var event = item.is('.open') ? 'sidecompactitemclose' : 'sidecompactitemopen';
      item.trigger(event);
    });
    sidebar.find('.compacter button').on('click', function () {
      var button = jQuery(this);
      var icon = button.find('i').first();
      var title_attribute;

      if (body.hasClass('side-compact')) {
        body.trigger('sidecompactcloseall');
        body.trigger('settingssave', ["arbory.side.compact", false]);
        body.removeClass('side-compact');
        icon.addClass('fa-angle-double-left').removeClass('fa-angle-double-right');
        title_attribute = 'title-collapse';
      } else {
        body.trigger('settingssave', ["arbory.side.compact", true]);
        body.addClass('side-compact');
        icon.addClass('fa-angle-double-right').removeClass('fa-angle-double-left');
        title_attribute = 'title-expand';
      }

      button.attr('title', button.data(title_attribute));
      body.trigger('sidecompactchange');
    });
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/sortable.js":
/*!*************************************************!*\
  !*** ./resources/assets/js/include/sortable.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(document).ready(function () {
  var body = jQuery('body');
  body.on('sortableinit', function (e) {
    var target = jQuery(e.target);

    if (!target.is('[data-sortable]')) {
      target = target.find('[data-sortable]');
    }

    target.each(function () {
      var list = jQuery(this);

      if (list.is('.ui-sortable')) {
        return; // already initialized
      }

      list.sortable({
        cursor: "move",
        delay: 150,
        distance: 5,
        forcePlaceholderSize: true,
        handle: '> .handle',
        items: "> .item",
        scroll: true,
        start: function start(e, ui) {
          ui.item.trigger('sortablestart');
        },
        stop: function stop(e, ui) {
          ui.item.trigger('sortablestop');
        },
        update: function update(event, ui) {
          ui.item.trigger('sortableupdate');
        }
      });
      list.on('sortablereindex', function () {
        list.find('> .item:visible > input[type="hidden"].item-position').each(function (i) {
          jQuery(this).attr('value', i);
        });
      });
      list.on('sortableupdate contentloaded contentremoved', function () {
        // item dragged to a new position
        // or
        // new content loaded or removed somewhere inside the list (possibly item added/removed)
        list.trigger('sortablereindex');
      });
      list.trigger('sortablereindex');
    });
  });
  body.on('contentloaded', function (e, event_params) {
    jQuery(e.target).trigger('sortableinit', event_params);
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/store_settings.js":
/*!*******************************************************!*\
  !*** ./resources/assets/js/include/store_settings.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {jQuery(function () {
  var body = jQuery('body');
  var settings_path = body.data('settings-path');
  body.on('settingssave', function (event, key_or_settings, value) {
    if (!settings_path) {
      return;
    }

    var settings = key_or_settings;

    if (typeof settings === "string") {
      settings = {};
      settings[key_or_settings] = value;
    }

    jQuery.ajax({
      url: settings_path,
      data: {
        "settings": settings
      },
      type: 'POST',
      dataType: 'json'
    });
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/include/toolbox.js":
/*!************************************************!*\
  !*** ./resources/assets/js/include/toolbox.js ***!
  \************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony import */ var _modules_UrlBuilder__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/UrlBuilder */ "./resources/assets/js/modules/UrlBuilder.js");

jQuery(function () {
  var body = jQuery('body');
  var xhr;
  var overlay = jQuery('<div />').addClass('toolbox-overlay').appendTo(body);
  overlay.bind('click', function () {
    body.trigger('toolboxcloseall');
  });
  body.bind('toolboxinit', function (e) {
    var target = jQuery(e.target);
    e.stopPropagation();
    var toolboxes;

    if (target.is('.toolbox')) {
      toolboxes = target;
    } else {
      toolboxes = target.find('.toolbox');
    }

    if (toolboxes.length < 1) {
      return;
    }

    toolboxes.bind('toolboxopen', function () {
      var toolbox = jQuery(this); // close all other open toolboxes

      body.trigger('toolboxcloseall');

      if (xhr) {
        xhr.abort();
      }

      if (toolbox.data("url")) {
        var url = new _modules_UrlBuilder__WEBPACK_IMPORTED_MODULE_0__["default"](toolbox.data("url")).add({
          ajax: 1
        }).getUrl();
        xhr = jQuery.ajax({
          url: url,
          type: 'get',
          success: function success(data) {
            toolbox.trigger('toolboxbuild', data);
          }
        });
      } else {
        toolbox.trigger('toolboxbuild');
      }

      return;
    });
    toolboxes.bind('toolboxbuild', function (e, data) {
      var toolbox = jQuery(this);
      var menu = toolbox.data('toolbox-menu');
      var items_container = toolbox.find('.toolbox-items ul');
      toolbox.attr('data-toolbox-open', true);
      menu.appendTo(body);
      toolbox.trigger('toolboxposition');
      overlay.show();
      menu.show();

      if (data !== undefined) {
        items_container.html(data);
      }

      items_container.trigger('contentloaded');
    });
    toolboxes.bind('toolboxclose', function () {
      var toolbox = jQuery(this);
      var menu = toolbox.data('toolbox-menu');
      menu.hide().appendTo(toolbox);
      overlay.hide();
      toolbox.removeAttr('data-toolbox-open');
      return;
    });
    toolboxes.bind('toolboxtoggle', function () {
      var toolbox = jQuery(this);
      var event = toolbox.attr('data-toolbox-open') ? 'toolboxclose' : 'toolboxopen';
      toolbox.trigger(event);
    });
    toolboxes.bind('toolboxposition', function () {
      var toolbox = jQuery(this);

      if (!toolbox.attr('data-toolbox-open')) {
        return;
      }

      var menu = toolbox.data('toolbox-menu');
      var trigger = toolbox.find('.trigger');
      var triggerOffset = trigger.offset();
      var triggerCenterX = triggerOffset.left + trigger.outerWidth() / 2;
      var menuWidth = menu.outerWidth();
      var openToRight = jQuery(document).width() - triggerCenterX - menuWidth - 50 > 0;
      var beak = menu.children('i').first();

      if (openToRight) {
        menu.css({
          left: triggerCenterX - 23,
          top: triggerOffset.top + trigger.outerHeight()
        });
        beak.css({
          left: 18
        });
      } else {
        menu.css({
          left: triggerCenterX - menuWidth + 20,
          top: triggerOffset.top + trigger.outerHeight()
        });
        beak.css({
          left: menuWidth - 24
        });
      }
    });
    toolboxes.find('.trigger').click(function () {
      jQuery(this).closest('.toolbox').trigger('toolboxtoggle');
    });
    toolboxes.find('.toolbox-items ul').on('contentloaded', function (e) {
      var container = jQuery(e.target);
      var toolbox = container.data("toolbox");
      var trigger = toolbox.find('.trigger');
      var items = container.find('li');
      var buttons = items.find('.button');
      buttons.click(function () {
        toolbox.trigger('toolboxclose');
      }); // forward loader events from item buttons to main toolbox trigger

      buttons.on('loadingstart', function () {
        trigger.trigger('loadingstart');
      });
      buttons.on('loadingend', function () {
        trigger.trigger('loadingend');
      });
    });
    toolboxes.each(function () {
      var toolbox = jQuery(this);
      var menu = toolbox.find('menu').first();
      toolbox.data('toolbox-menu', menu);
      var items_container = toolbox.find('.toolbox-items ul');
      items_container.data('toolbox', toolbox);
      toolbox.addClass('initialized');
    });
  });
  jQuery(window).bind('resize', function () {
    jQuery('.toolbox[data-toolbox-open]').trigger('toolboxposition');
  });
  body.bind('toolboxcloseall', function () {
    body.find('.toolbox[data-toolbox-open]').trigger('toolboxclose');
  }); // attach toolboxinit to all loaded content

  body.on('contentloaded', function (e) {
    jQuery(e.target).trigger('toolboxinit');
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/modules/UrlBuilder.js":
/*!***************************************************!*\
  !*** ./resources/assets/js/modules/UrlBuilder.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return UrlBuilder; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var UrlBuilder =
/*#__PURE__*/
function () {
  function UrlBuilder(params) {
    _classCallCheck(this, UrlBuilder);

    if (params === undefined) {
      params = {};
    }

    var keepCurrentQuery = true;

    if (params === false || params.baseUrl !== undefined) {
      keepCurrentQuery = false;
    }

    if (typeof params === 'string') {
      params = {
        baseUrl: params
      };
    } // setup members


    this.path = '';
    this.query = {}; // get url

    var baseUrl = params.baseUrl || location.href; // remove anchor

    baseUrl = baseUrl.split('#').shift(); // split url

    var urlParts = baseUrl.split('?');
    this.path = urlParts.shift();

    if (keepCurrentQuery && urlParts.length > 0) {
      var queryParts = urlParts.shift().split('&');

      for (var i = 0; i < queryParts.length; i++) {
        if (queryParts[i].length > 0) {
          var value;
          var variable = queryParts[i].split('=');
          var name = variable.shift();

          if (variable.length > 0) {
            value = decodeURIComponent(variable.shift());
          } else {
            value = '';
          }

          if (decodeURIComponent(name).substr(decodeURIComponent(name).length - 2, 2) === '[]') {
            name = decodeURIComponent(name);
          }

          if (name.substr(name.length - 2, 2) === '[]') {
            name = name.substr(0, name.length - 2);

            if (this.query[name] === undefined || !(this.query[name] instanceof Array)) {
              this.query[name] = [];
            }

            this.query[name].push(value);
          } else {
            this.query[name] = value;
          }
        }
      }
    }

    if (params.keep !== undefined && params.keep instanceof Array) {
      var filteredQuery = {};

      for (var a = 0; a < params.keep.length; a++) {
        if (this.query[params.keep[a]] !== undefined) {
          filteredQuery[params.keep[a]] = this.query[params.keep[a]];
        }
      }

      this.query = filteredQuery;
    }
  }

  _createClass(UrlBuilder, [{
    key: "add",
    value: function add(params, value) {
      if (params instanceof Array) {
        for (var i = 0; i < params.length; i++) {
          if (params[i].name !== undefined && params[i].value !== undefined) {
            var name = params[i].name;

            if (name.substr(name.length - 2, 2) === '[]') {
              name = name.substr(0, name.length - 2);

              if (this.query[name] === undefined || !(this.query[name] instanceof Array)) {
                this.query[name] = [];
              }

              this.query[name].push(params[i].value);
            } else {
              this.query[params[i].name] = params[i].value;
            }
          }
        }
      } else {
        if (params instanceof Object) {
          for (var a in params) {
            if (params.hasOwnProperty(a)) {
              this.query[a] = params[a];
            }
          }
        } else {
          if (typeof params === 'string') {
            if (value === undefined) {
              var temp = new UrlBuilder('?' + params);

              for (var b in temp.query) {
                if (temp.query.hasOwnProperty(b)) {
                  this.query[b] = temp.query[b];
                }
              }
            } else {
              this.query[params] = value;
            }
          }
        }
      }

      return this;
    }
  }, {
    key: "removeAll",
    value: function removeAll(preserveParams) {
      for (var i in this.query) {
        if (preserveParams === undefined || jQuery.inArray(i, preserveParams) === -1) {
          this.remove(i);
        }
      }

      return this;
    }
  }, {
    key: "remove",
    value: function remove(name) {
      delete this.query[name];
      return this;
    }
  }, {
    key: "get",
    value: function get(name) {
      if (this.query[name] !== undefined) {
        return this.query[name];
      }

      return null;
    }
  }, {
    key: "getUrl",
    value: function getUrl() {
      var query = '';
      var isFirst = true;

      for (var i in this.query) {
        if (this.query.hasOwnProperty(i)) {
          if (!isFirst) {
            query += '&';
          } else {
            isFirst = false;
          }

          if (this.query[i] instanceof Array) {
            query += i + '[]=' + this.query[i].map(encodeURIComponent).join('&' + i + '[]=');
          } else {
            query += i + '=' + encodeURIComponent(this.query[i]);
          }
        }
      }

      return this.path + '?' + query;
    }
  }]);

  return UrlBuilder;
}();


/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ 1:
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** multi ./resources/assets/js/include/accordion.js ./resources/assets/js/include/ajax.js ./resources/assets/js/include/ajaxbox.js ./resources/assets/js/include/constructor.js ./resources/assets/js/include/dialogs.js ./resources/assets/js/include/field.type_associated_set.js ./resources/assets/js/include/field.type_date_or_datetime_or_time.js ./resources/assets/js/include/filter.js ./resources/assets/js/include/loader.js ./resources/assets/js/include/localization.js ./resources/assets/js/include/mass.js ./resources/assets/js/include/menu.js ./resources/assets/js/include/nested_fields.js ./resources/assets/js/include/nodes.js ./resources/assets/js/include/notifications.js ./resources/assets/js/include/pagination.js ./resources/assets/js/include/remote_validator.js ./resources/assets/js/include/search.js ./resources/assets/js/include/sidebar.js ./resources/assets/js/include/sortable.js ./resources/assets/js/include/store_settings.js ./resources/assets/js/include/toolbox.js ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/accordion.js */"./resources/assets/js/include/accordion.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/ajax.js */"./resources/assets/js/include/ajax.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/ajaxbox.js */"./resources/assets/js/include/ajaxbox.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/constructor.js */"./resources/assets/js/include/constructor.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/dialogs.js */"./resources/assets/js/include/dialogs.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/field.type_associated_set.js */"./resources/assets/js/include/field.type_associated_set.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/field.type_date_or_datetime_or_time.js */"./resources/assets/js/include/field.type_date_or_datetime_or_time.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/filter.js */"./resources/assets/js/include/filter.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/loader.js */"./resources/assets/js/include/loader.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/localization.js */"./resources/assets/js/include/localization.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/mass.js */"./resources/assets/js/include/mass.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/menu.js */"./resources/assets/js/include/menu.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/nested_fields.js */"./resources/assets/js/include/nested_fields.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/nodes.js */"./resources/assets/js/include/nodes.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/notifications.js */"./resources/assets/js/include/notifications.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/pagination.js */"./resources/assets/js/include/pagination.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/remote_validator.js */"./resources/assets/js/include/remote_validator.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/search.js */"./resources/assets/js/include/search.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/sidebar.js */"./resources/assets/js/include/sidebar.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/sortable.js */"./resources/assets/js/include/sortable.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/store_settings.js */"./resources/assets/js/include/store_settings.js");
module.exports = __webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/include/toolbox.js */"./resources/assets/js/include/toolbox.js");


/***/ })

},[[1,"/js/manifest","/js/vendor"]]]);