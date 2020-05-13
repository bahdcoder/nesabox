(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[13],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Meta.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Meta.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
      deleting: false,
      deletingModalOpen: false
    };
  },
  computed: {
    server: function server() {
      return this.$root.servers[this.$route.params.server] || {};
    }
  },
  methods: {
    deleteServer: function deleteServer() {
      var _this = this;

      this.deleting = true;
      axios["delete"]("/api/servers/".concat(this.server.id)).then(function () {
        _this.$router.push("/dashboard");
      })["catch"](function () {
        _this.$root.flashMessage('Failed deleting server.', 'error');
      })["finally"](function () {
        _this.deleting = false;
        _this.deletingModalOpen = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Meta.vue?vue&type=template&id=ea4aa126&":
/*!**********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Meta.vue?vue&type=template&id=ea4aa126& ***!
  \**********************************************************************************************************************************************************************************************************/
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
    [
      _c(
        "template",
        { slot: "content" },
        [
          _c("flash"),
          _vm._v(" "),
          _c("confirm-modal", {
            attrs: {
              confirming: _vm.deleting,
              open: _vm.deletingModalOpen,
              confirmHeading: "Delete server",
              confirmText:
                "Are you sure you want to delete your server " +
                _vm.server.name +
                "?"
            },
            on: {
              confirm: _vm.deleteServer,
              close: function($event) {
                _vm.deletingModalOpen = false
              }
            }
          }),
          _vm._v(" "),
          _c(
            "card",
            { attrs: { title: "Delete server" } },
            [
              _c("info", [
                _vm._v(
                  "\n                This will permanently delete this server from our records.\n                "
                ),
                _vm.server.provider !== "custom"
                  ? _c("span", [
                      _vm._v(
                        "We won't delete this server from your\n                    " +
                          _vm._s(_vm.server.provider) +
                          " account."
                      )
                    ])
                  : _vm._e()
              ]),
              _vm._v(" "),
              _c("red-button", {
                staticClass: "mt-5",
                attrs: { label: "Delete server" },
                on: {
                  click: function($event) {
                    _vm.deletingModalOpen = true
                  }
                }
              })
            ],
            1
          )
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

/***/ "./resources/js/Pages/Servers/Meta.vue":
/*!*********************************************!*\
  !*** ./resources/js/Pages/Servers/Meta.vue ***!
  \*********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Meta_vue_vue_type_template_id_ea4aa126___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Meta.vue?vue&type=template&id=ea4aa126& */ "./resources/js/Pages/Servers/Meta.vue?vue&type=template&id=ea4aa126&");
/* harmony import */ var _Meta_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Meta.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Servers/Meta.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Meta_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Meta_vue_vue_type_template_id_ea4aa126___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Meta_vue_vue_type_template_id_ea4aa126___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Servers/Meta.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Servers/Meta.vue?vue&type=script&lang=js&":
/*!**********************************************************************!*\
  !*** ./resources/js/Pages/Servers/Meta.vue?vue&type=script&lang=js& ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Meta_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Meta.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Meta.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Meta_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Servers/Meta.vue?vue&type=template&id=ea4aa126&":
/*!****************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Meta.vue?vue&type=template&id=ea4aa126& ***!
  \****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Meta_vue_vue_type_template_id_ea4aa126___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Meta.vue?vue&type=template&id=ea4aa126& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Meta.vue?vue&type=template&id=ea4aa126&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Meta_vue_vue_type_template_id_ea4aa126___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Meta_vue_vue_type_template_id_ea4aa126___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);