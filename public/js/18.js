(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[18],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Logs.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Sites/Logs.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_codemirror__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-codemirror */ "./node_modules/vue-codemirror/dist/vue-codemirror.js");
/* harmony import */ var vue_codemirror__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_codemirror__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var codemirror_lib_codemirror_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! codemirror/lib/codemirror.css */ "./node_modules/codemirror/lib/codemirror.css");
/* harmony import */ var codemirror_lib_codemirror_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(codemirror_lib_codemirror_css__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var codemirror_theme_lucario_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! codemirror/theme/lucario.css */ "./node_modules/codemirror/theme/lucario.css");
/* harmony import */ var codemirror_theme_lucario_css__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(codemirror_theme_lucario_css__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var codemirror_mode_shell_shell_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! codemirror/mode/shell/shell.js */ "./node_modules/codemirror/mode/shell/shell.js");
/* harmony import */ var codemirror_mode_shell_shell_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(codemirror_mode_shell_shell_js__WEBPACK_IMPORTED_MODULE_3__);
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
  components: {
    codemirror: vue_codemirror__WEBPACK_IMPORTED_MODULE_0__["codemirror"]
  },
  data: function data() {
    var codeMirrorOptions = {
      theme: 'lucario',
      tabSize: 4,
      line: true,
      mode: 'shell',
      lineNumbers: true,
      readOnly: true
    };
    return {
      codeMirrorOptions: codeMirrorOptions,
      fetchingLogs: true,
      logs: ''
    };
  },
  computed: {
    site: function site() {
      return this.$root.sites[this.$route.params.site] || {};
    },
    serverId: function serverId() {
      return this.$route.params.server;
    },
    siteId: function siteId() {
      return this.$route.params.site;
    }
  },
  methods: {
    fetchLogs: function fetchLogs() {
      var _this = this;

      axios.get("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/logs")).then(function (_ref) {
        var logs = _ref.data;
        _this.fetchingLogs = false;
        _this.logs = logs;
      });
    }
  },
  mounted: function mounted() {
    this.fetchLogs();
  },
  watch: {
    site: function site() {
      this.fetchLogs();
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Logs.vue?vue&type=template&id=2b76a55b&":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Sites/Logs.vue?vue&type=template&id=2b76a55b& ***!
  \********************************************************************************************************************************************************************************************************/
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
    "site-layout",
    [
      _c(
        "template",
        { slot: "content" },
        [
          _c("flash"),
          _vm._v(" "),
          _c(
            "card",
            { attrs: { title: "PM2 Logs" } },
            [
              _c("info", [
                _vm._v(
                  "\n                The site logs will be updated in real time.\n            "
                )
              ]),
              _vm._v(" "),
              _c("codemirror", {
                class: {
                  "remove-bottom-border-radius": _vm.fetchingLogs,
                  "mt-4": true
                },
                attrs: { options: _vm.codeMirrorOptions },
                model: {
                  value: _vm.logs,
                  callback: function($$v) {
                    _vm.logs = $$v
                  },
                  expression: "logs"
                }
              }),
              _vm._v(" "),
              _vm.fetchingLogs
                ? _c(
                    "div",
                    {
                      staticClass: "w-full h-6 flex justify-center rounded-b",
                      staticStyle: { background: "#2b3e50" }
                    },
                    [_c("pulse")],
                    1
                  )
                : _vm._e()
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

/***/ "./resources/js/Pages/Sites/Logs.vue":
/*!*******************************************!*\
  !*** ./resources/js/Pages/Sites/Logs.vue ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Logs_vue_vue_type_template_id_2b76a55b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Logs.vue?vue&type=template&id=2b76a55b& */ "./resources/js/Pages/Sites/Logs.vue?vue&type=template&id=2b76a55b&");
/* harmony import */ var _Logs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Logs.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Sites/Logs.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Logs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Logs_vue_vue_type_template_id_2b76a55b___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Logs_vue_vue_type_template_id_2b76a55b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Sites/Logs.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Sites/Logs.vue?vue&type=script&lang=js&":
/*!********************************************************************!*\
  !*** ./resources/js/Pages/Sites/Logs.vue?vue&type=script&lang=js& ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Logs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Logs.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Logs.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Logs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Sites/Logs.vue?vue&type=template&id=2b76a55b&":
/*!**************************************************************************!*\
  !*** ./resources/js/Pages/Sites/Logs.vue?vue&type=template&id=2b76a55b& ***!
  \**************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Logs_vue_vue_type_template_id_2b76a55b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Logs.vue?vue&type=template&id=2b76a55b& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Logs.vue?vue&type=template&id=2b76a55b&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Logs_vue_vue_type_template_id_2b76a55b___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Logs_vue_vue_type_template_id_2b76a55b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);