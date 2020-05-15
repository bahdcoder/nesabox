(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[4],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/SourceControl.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/SourceControl.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      urls: {},
      disconnecting: {
        github: false,
        gitlab: false
      }
    };
  },
  computed: {
    providers: function providers() {
      var _this = this;

      return Object.keys(this.urls).map(function (provider) {
        return {
          provider: provider,
          url: _this.urls[provider],
          connected: _this.$root.auth.source_control[provider]
        };
      });
    }
  },
  mounted: function mounted() {
    var _this2 = this;

    axios.get('/settings/source-control').then(function (_ref) {
      var data = _ref.data;
      _this2.urls = data;
    });
  },
  methods: {
    disconnect: function disconnect(provider) {
      var _this3 = this;

      this.disconnecting = _objectSpread({}, this.disconnecting, _defineProperty({}, provider.provider, true));
      axios.post("/api/settings/source-control/".concat(provider.provider, "/unlink")).then(function (_ref2) {
        var user = _ref2.data;
        _this3.$root.auth = user;

        _this3.$root.flashMessage("".concat(provider.provider, " has been unlinked."));
      })["catch"](function () {
        _this3.$root.flashMessage("Failed to unlink ".concat(provider.provider, "."), 'error');
      })["finally"](function () {
        _this3.disconnecting = _objectSpread({}, _this3.disconnecting, _defineProperty({}, provider.provider, false));
      });
    },
    isDisconnecting: function isDisconnecting(provider) {
      return this.disconnecting[provider];
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/SourceControl.vue?vue&type=template&id=6bed9e67&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/SourceControl.vue?vue&type=template&id=6bed9e67& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
    "account-layout",
    [
      _c(
        "template",
        { slot: "content" },
        [
          _c("flash"),
          _vm._v(" "),
          _vm._l(_vm.providers, function(provider) {
            return _c(
              "card",
              {
                key: provider.provider,
                staticClass: "mb-6",
                attrs: { title: provider.provider }
              },
              [
                provider.connected
                  ? _c("red-button", {
                      attrs: {
                        loading: _vm.isDisconnecting(provider.provider),
                        label: "Disconnect " + provider.provider
                      },
                      on: {
                        click: function($event) {
                          return _vm.disconnect(provider)
                        }
                      }
                    })
                  : _c("v-button", {
                      attrs: {
                        component: "a",
                        href: provider.url,
                        label: "Connect to " + provider.provider
                      }
                    })
              ],
              1
            )
          })
        ],
        2
      )
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Account/SourceControl.vue":
/*!******************************************************!*\
  !*** ./resources/js/Pages/Account/SourceControl.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SourceControl_vue_vue_type_template_id_6bed9e67___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SourceControl.vue?vue&type=template&id=6bed9e67& */ "./resources/js/Pages/Account/SourceControl.vue?vue&type=template&id=6bed9e67&");
/* harmony import */ var _SourceControl_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SourceControl.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Account/SourceControl.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SourceControl_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SourceControl_vue_vue_type_template_id_6bed9e67___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SourceControl_vue_vue_type_template_id_6bed9e67___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Account/SourceControl.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Account/SourceControl.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/Pages/Account/SourceControl.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SourceControl_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./SourceControl.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/SourceControl.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SourceControl_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Account/SourceControl.vue?vue&type=template&id=6bed9e67&":
/*!*************************************************************************************!*\
  !*** ./resources/js/Pages/Account/SourceControl.vue?vue&type=template&id=6bed9e67& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SourceControl_vue_vue_type_template_id_6bed9e67___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./SourceControl.vue?vue&type=template&id=6bed9e67& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/SourceControl.vue?vue&type=template&id=6bed9e67&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SourceControl_vue_vue_type_template_id_6bed9e67___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SourceControl_vue_vue_type_template_id_6bed9e67___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);