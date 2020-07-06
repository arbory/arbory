(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/controllers/nodes"],{

/***/ "./resources/assets/js/controllers/nodes.js":
/*!**************************************************!*\
  !*** ./resources/assets/js/controllers/nodes.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var COOKIE_NAME_NODES = 'nodes';

var Node =
/*#__PURE__*/
function () {
  function Node(element) {
    _classCallCheck(this, Node);

    this.element = element;
    this.id = element.data('id');
    this.level = element.data('level');
  }

  _createClass(Node, [{
    key: "getStorage",
    value: function getStorage() {
      return NodeStore.get(this.id);
    }
  }, {
    key: "makeSortable",
    value: function makeSortable(params) {
      return this.element.parent().sortable(params);
    }
  }, {
    key: "toggleChildVisibility",
    value: function toggleChildVisibility() {
      this.getStorage().setCollapsed(!this.getStorage().isCollapsed());

      if (this.isCollapsed()) {
        this.element.removeClass('collapsed');
      } else {
        this.element.addClass('collapsed');
      }
    }
  }, {
    key: "isCollapsed",
    value: function isCollapsed() {
      return this.element.hasClass('collapsed');
    }
  }, {
    key: "getParent",
    value: function getParent() {
      var parent = 'li[data-level=' + --this.level + ']';
      return new Node(this.element.closest(parent));
    }
  }, {
    key: "getLeftSibling",
    value: function getLeftSibling() {
      return new Node(this.element.prev('li[data-id]'));
    }
  }, {
    key: "getRightSibling",
    value: function getRightSibling() {
      return new Node(this.element.next('li[data-id]'));
    }
  }]);

  return Node;
}();

var NodeStore =
/*#__PURE__*/
function () {
  function NodeStore() {
    _classCallCheck(this, NodeStore);
  }

  _createClass(NodeStore, null, [{
    key: "getStored",
    value: function getStored() {
      if (typeof jQuery.cookie(COOKIE_NAME_NODES) === 'undefined') {
        NodeStore.save({});
      }

      return JSON.parse(jQuery.cookie(COOKIE_NAME_NODES));
    }
  }, {
    key: "get",
    value: function get(id) {
      var stored = this.getStored();

      if (typeof stored[id] === 'undefined') {
        stored[id] = true;
        NodeStore.save(stored);
      }

      return new NodeStoreItem(id, stored[id]);
    }
  }, {
    key: "saveItem",
    value: function saveItem(store) {
      var stored = this.getStored();
      stored[store.id] = store.getContents();
      NodeStore.save(stored);
    }
  }, {
    key: "save",
    value: function save(data) {
      jQuery.cookie(COOKIE_NAME_NODES, JSON.stringify(data));
    }
  }]);

  return NodeStore;
}();

var NodeStoreItem =
/*#__PURE__*/
function () {
  function NodeStoreItem(id) {
    var contents = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    _classCallCheck(this, NodeStoreItem);

    this.id = id;
    this.contents = contents;
  }

  _createClass(NodeStoreItem, [{
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
      NodeStore.saveItem(this);
    }
  }]);

  return NodeStoreItem;
}();

jQuery(document).ready(function () {
  var body = jQuery('body.controller-nodes');
  var collection = jQuery('.collection');
  body.on('click', '.dialog .node-cell label', function () {
    jQuery('.dialog .node-cell label').removeClass('selected');
    jQuery(this).addClass('selected');
  });
  collection.ready(function () {
    var token = jQuery('[name=_token]:first').val();
    var nodes = collection.find('ul[data-level] > li');
    nodes.each(function () {
      var node = new Node(jQuery(this));
      node.element.on('click', '> .collapser-cell > .collapser', function () {
        return node.toggleChildVisibility();
      });
      node.makeSortable({
        items: '> li',
        stop: function stop(event, ui) {
          node = new Node(ui.item);
          jQuery.post('/admin/nodes/api/node_reposition', {
            _token: token,
            id: node.id,
            toLeftId: node.getLeftSibling().id,
            toRightId: node.getRightSibling().id
          });
        }
      });
    });
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/stylesheets/application.scss":
/*!*******************************************************!*\
  !*** ./resources/assets/stylesheets/application.scss ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/assets/stylesheets/controllers/sessions.scss":
/*!****************************************************************!*\
  !*** ./resources/assets/stylesheets/controllers/sessions.scss ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*****************************************************************************************************************************************************************!*\
  !*** multi ./resources/assets/js/controllers/nodes.js ./resources/assets/stylesheets/application.scss ./resources/assets/stylesheets/controllers/sessions.scss ***!
  \*****************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/js/controllers/nodes.js */"./resources/assets/js/controllers/nodes.js");
__webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/stylesheets/application.scss */"./resources/assets/stylesheets/application.scss");
module.exports = __webpack_require__(/*! /Users/sabineabele/Projects/arbory/resources/assets/stylesheets/controllers/sessions.scss */"./resources/assets/stylesheets/controllers/sessions.scss");


/***/ })

},[[0,"/js/manifest","/js/vendor"]]]);