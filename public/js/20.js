(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[20],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Ssl.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Sites/Ssl.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************/
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
      installing: false,
      installCustom: false,
      uninstalling: false,
      showUninstallConfirmation: false,
      form: {
        certificate: '',
        privateKey: ''
      },
      errors: {}
    };
  },
  computed: {
    site: function site() {
      return this.$root.sites[this.$route.params.site] || {};
    },
    server: function server() {
      return this.$root.servers[this.$route.params.server] || {};
    },
    serverId: function serverId() {
      return this.$route.params.server;
    },
    siteId: function siteId() {
      return this.$route.params.site;
    }
  },
  methods: {
    install: function install() {
      var _this = this;

      this.installing = true;
      axios.post("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/lets-encrypt")).then(function (_ref) {
        var site = _ref.data;
        _this.$root.sites = _objectSpread({}, _this.$root.sites, _defineProperty({}, _this.siteId, site));
      })["finally"](function () {
        _this.installing = false;
      });
    },
    installCustomCertificate: function installCustomCertificate() {
      var _this2 = this;

      this.installing = true;
      axios.post("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/custom-ssl"), this.form).then(function (_ref2) {
        var site = _ref2.data;
        _this2.$root.sites = _objectSpread({}, _this2.$root.sites, _defineProperty({}, _this2.siteId, site));
        _this2.installCustom = false;
      })["catch"](function (_ref3) {
        var response = _ref3.response;

        if (response.status === 422) {
          _this2.errors = response.data.errors;
        }
      })["finally"](function () {
        _this2.installing = false;
      });
    },
    uninstall: function uninstall() {
      var _this3 = this;

      this.uninstalling = true;
      axios.post("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/uninstall-ssl")).then(function (_ref4) {
        var site = _ref4.data;
        _this3.$root.sites = _objectSpread({}, _this3.$root.sites, _defineProperty({}, _this3.siteId, site));
      })["catch"](function (_ref5) {
        var response = _ref5.response;

        _this3.$root.flashMessage(response.data.message);
      })["finally"](function () {
        _this3.uninstalling = false;
        _this3.showUninstallConfirmation = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Ssl.vue?vue&type=template&id=12e5e8e0&":
/*!*******************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Sites/Ssl.vue?vue&type=template&id=12e5e8e0& ***!
  \*******************************************************************************************************************************************************************************************************/
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
          _c("confirm-modal", {
            attrs: {
              confirming: _vm.uninstalling,
              open: _vm.showUninstallConfirmation,
              confirmHeading: "Uninstall SSL",
              confirmText:
                "Are you sure you want to uninstall ssl from this site ? This would delete the existing ssl certificate."
            },
            on: {
              confirm: _vm.uninstall,
              close: function($event) {
                _vm.showUninstallConfirmation = false
              }
            }
          }),
          _vm._v(" "),
          !_vm.site.ssl_certificate_installed && !_vm.installCustom
            ? _c(
                "card",
                { attrs: { title: "Ssl certificate" } },
                [
                  _c("info", { staticClass: "mb-3" }, [
                    _vm._v(
                      "\n                To obtain a valid Let's Encrypt certificate, make sure your\n                DNS configuration for " +
                        _vm._s(_vm.site.name) +
                        " has an A record\n                pointing to " +
                        _vm._s(_vm.server.ip_address) +
                        ". This would be verified\n                when obtaining the certificate. Otherwise, you can provide a\n                custom certificate to be installed on the server.\n            "
                    )
                  ]),
                  _vm._v(" "),
                  _c("v-button", {
                    attrs: {
                      loading:
                        _vm.installing || _vm.site.installing_certificate,
                      label: "Install ssl certificate"
                    },
                    on: { click: _vm.install }
                  }),
                  _vm._v(" "),
                  !_vm.installing && !_vm.site.installing_certificate
                    ? _c("v-trans-button", {
                        staticClass: "mt-2 md:mt-0",
                        attrs: { label: "Install custom certificate" },
                        on: {
                          click: function($event) {
                            _vm.installCustom = true
                          }
                        }
                      })
                    : _vm._e()
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.site.ssl_certificate_installed
            ? _c(
                "card",
                { attrs: { title: "Ssl Certificate" } },
                [
                  _c("info", [
                    _vm._v(
                      "\n                Ssl certificate for " +
                        _vm._s(_vm.site.name) +
                        " is installed and active.\n            "
                    )
                  ]),
                  _vm._v(" "),
                  _c("red-button", {
                    staticClass: "mt-3",
                    attrs: { label: "Uninstall certificate" },
                    on: {
                      click: function($event) {
                        _vm.showUninstallConfirmation = true
                      }
                    }
                  })
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.installCustom
            ? _c("card", { attrs: { title: "Custom ssl certificate" } }, [
                _c(
                  "form",
                  {
                    on: {
                      submit: function($event) {
                        $event.preventDefault()
                        return _vm.installCustomCertificate($event)
                      }
                    }
                  },
                  [
                    _c("textarea-input", {
                      attrs: {
                        errors: _vm.errors.privateKey,
                        name: "privateKey",
                        rows: 6,
                        label: "Private key",
                        help: "This is the private key of your ssl certificate"
                      },
                      model: {
                        value: _vm.form.privateKey,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "privateKey", $$v)
                        },
                        expression: "form.privateKey"
                      }
                    }),
                    _vm._v(" "),
                    _c("textarea-input", {
                      staticClass: "mt-3",
                      attrs: {
                        errors: _vm.errors.certificate,
                        name: "certificate",
                        rows: 6,
                        label: "Certificate",
                        help: "This is the actual certificate content."
                      },
                      model: {
                        value: _vm.form.certificate,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "certificate", $$v)
                        },
                        expression: "form.certificate"
                      }
                    }),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "flex flex-wrap justify-end mt-3" },
                      [
                        _c("v-trans-button", {
                          staticClass: "mt-2 md:mt-0",
                          attrs: { label: "Cancel" },
                          on: {
                            click: function($event) {
                              _vm.installCustom = false
                            }
                          }
                        }),
                        _vm._v(" "),
                        _c("v-button", {
                          staticClass: "md:ml-3",
                          attrs: {
                            type: "submit",
                            label: "Install ssl certificate"
                          }
                        })
                      ],
                      1
                    )
                  ],
                  1
                )
              ])
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

/***/ "./resources/js/Pages/Sites/Ssl.vue":
/*!******************************************!*\
  !*** ./resources/js/Pages/Sites/Ssl.vue ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Ssl_vue_vue_type_template_id_12e5e8e0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Ssl.vue?vue&type=template&id=12e5e8e0& */ "./resources/js/Pages/Sites/Ssl.vue?vue&type=template&id=12e5e8e0&");
/* harmony import */ var _Ssl_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Ssl.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Sites/Ssl.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Ssl_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Ssl_vue_vue_type_template_id_12e5e8e0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Ssl_vue_vue_type_template_id_12e5e8e0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Sites/Ssl.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Sites/Ssl.vue?vue&type=script&lang=js&":
/*!*******************************************************************!*\
  !*** ./resources/js/Pages/Sites/Ssl.vue?vue&type=script&lang=js& ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Ssl_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Ssl.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Ssl.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Ssl_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Sites/Ssl.vue?vue&type=template&id=12e5e8e0&":
/*!*************************************************************************!*\
  !*** ./resources/js/Pages/Sites/Ssl.vue?vue&type=template&id=12e5e8e0& ***!
  \*************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Ssl_vue_vue_type_template_id_12e5e8e0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Ssl.vue?vue&type=template&id=12e5e8e0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Ssl.vue?vue&type=template&id=12e5e8e0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Ssl_vue_vue_type_template_id_12e5e8e0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Ssl_vue_vue_type_template_id_12e5e8e0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);