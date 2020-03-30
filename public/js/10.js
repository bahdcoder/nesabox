(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[10],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(n); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      form: {
        servers: []
      },
      updatingNetwork: false
    };
  },
  computed: {
    server: function server() {
      return this.$root.servers[this.$route.params.server] || {};
    },
    familyServers: function familyServers() {
      if (!this.server || !this.server.id) return [];
      return this.server.family_servers;
    }
  },
  methods: {
    selectServer: function selectServer(checked, server) {
      if (checked) {
        this.form = _objectSpread({}, this.form, {
          servers: [].concat(_toConsumableArray(this.form.servers), [server])
        });
      } else {
        this.form = _objectSpread({}, this.form, {
          servers: this.form.servers.filter(function (s) {
            return s !== server;
          })
        });
      }
    },
    updateNetwork: function updateNetwork() {
      var _this = this;

      this.updatingNetwork = true;
      axios.patch("/api/servers/".concat(this.server.id, "/network"), this.form).then(function (_ref) {
        var server = _ref.data;

        _this.$root.flashMessage('Network has been updated.');

        _this.$root.servers = _objectSpread({}, _this.$root.servers, _defineProperty({}, server.id, server));
      })["catch"](function (_ref2) {
        var response = _ref2.response;

        _this.$root.flashMessage(response.data.message || 'Failed updating network.');
      })["finally"](function () {
        _this.updatingNetwork = false;
      });
    },
    serverMounted: function serverMounted() {
      this.form = _objectSpread({}, this.form, {
        servers: this.server.friend_servers || []
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14& ***!
  \*************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "server-layout",
    { on: { mounted: _vm.serverMounted } },
    [
      _c(
        "template",
        { slot: "content" },
        [
          _c("flash"),
          _vm._v(" "),
          _vm.server.provider === "digital-ocean"
            ? _c(
                "card",
                { attrs: { title: "Server network" } },
                [
                  _c("info", [
                    _vm._v(
                      "\n                Below is a list of all of the other servers this server may access. With a server's network, you can use a server as a separate database, cache or queue worker. Only servers from the same provider, and in the same region as this server would be listed here. \n            "
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "mt-6" },
                    [
                      _c(
                        "label",
                        {
                          staticClass:
                            "block text-sm font-medium leading-5 text-gray-700",
                          attrs: { for: "" }
                        },
                        [_vm._v("Can connect to")]
                      ),
                      _vm._v(" "),
                      _c("small", { staticClass: "text-gray-600" }, [
                        _vm._v(
                          "Select all the servers this server would have access to."
                        )
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.familyServers, function(server) {
                        return _c("checkbox", {
                          key: server.id,
                          staticClass: "mt-4",
                          attrs: {
                            name: server.id,
                            label: server.name,
                            checked: _vm.form.servers.includes(server.id)
                          },
                          on: {
                            input: function($event) {
                              return _vm.selectServer($event, server.id)
                            }
                          }
                        })
                      })
                    ],
                    2
                  ),
                  _vm._v(" "),
                  _c("v-button", {
                    staticClass: "mt-4",
                    attrs: {
                      label: "Update network",
                      disabled: _vm.familyServers.length === 0,
                      loading: _vm.updatingNetwork
                    },
                    on: { click: _vm.updateNetwork }
                  })
                ],
                1
              )
            : _vm._e()
        ],
        1
      )
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Servers/Network.vue":
/*!************************************************!*\
  !*** ./resources/js/Pages/Servers/Network.vue ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Network.vue?vue&type=template&id=f03edb14& */ "./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14&");
/* harmony import */ var _Network_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Network.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Network_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Servers/Network.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Network.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14&":
/*!*******************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14& ***!
  \*******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Network.vue?vue&type=template&id=f03edb14& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);