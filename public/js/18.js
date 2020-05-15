(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[18],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Files.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Sites/Files.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************/
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
  components: {
    codemirror: vue_codemirror__WEBPACK_IMPORTED_MODULE_0__["codemirror"]
  },
  data: function data() {
    var codeMirrorOptions = {
      theme: 'lucario',
      tabSize: 4,
      line: true,
      mode: 'shell',
      lineNumbers: true
    };
    return {
      fetchingEcosystemFile: false,
      fetchingNginxConfigFile: false,
      updatingEcosystemFile: false,
      updatingNginxConfigFile: false,
      ecosystemFile: '',
      nginxConfigFile: '',
      fetchingCustomFile: false,
      customFileFetched: false,
      fileContent: '',
      updatingCustomFile: false,
      codeMirrorOptions: codeMirrorOptions
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
    fetchPm2File: function fetchPm2File() {
      var _this = this;

      this.fetchingEcosystemFile = true;
      axios.get("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/ecosystem-file")).then(function (_ref) {
        var data = _ref.data;
        _this.ecosystemFile = data;
      })["catch"](function () {
        _this.$root.flashMessage('Failed to fetch the ecosystem file.', 'error');
      })["finally"](function () {
        _this.fetchingEcosystemFile = false;
      });
    },
    fetchNginxConfigFile: function fetchNginxConfigFile() {
      var _this2 = this;

      this.fetchingNginxConfigFile = true;
      axios.get("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/nginx-config")).then(function (_ref2) {
        var data = _ref2.data;
        _this2.nginxConfigFile = data;
      })["catch"](function () {
        _this2.$root.flashMessage('Failed to fetch the nginx configuration file.', 'error');
      })["finally"](function () {
        _this2.fetchingNginxConfigFile = false;
      });
    },
    updateNginxConfigFile: function updateNginxConfigFile() {
      var _this3 = this;

      this.updatingNginxConfigFile = true;
      axios.post("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/nginx-config"), {
        nginxConfig: this.nginxConfigFile
      }).then(function () {
        _this3.$root.flashMessage('Nginx configuration file updated.');

        _this3.nginxConfigFile = '';
      })["catch"](function () {
        _this3.$root.flashMessage('Failed to update the nginx configuration file.', 'error');
      })["finally"](function () {
        _this3.updatingNginxConfigFile = false;
      });
    },
    updatePm2File: function updatePm2File() {
      var _this4 = this;

      this.updatingEcosystemFile = true;
      axios.post("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/ecosystem-file"), {
        ecosystemFile: this.ecosystemFile
      }).then(function () {
        _this4.$root.flashMessage('Ecosystem file updated.');

        _this4.ecosystemFile = '';
      })["catch"](function () {
        _this4.$root.flashMessage('Failed to update the ecosystem file.', 'error');
      })["finally"](function () {
        _this4.updatingEcosystemFile = false;
      });
    },
    siteMounted: function siteMounted() {
      this.form = {
        path: "/home/nesa/".concat(this.site.name, "/.env")
      };
    },
    fetchFile: function fetchFile() {
      var _this5 = this;

      this.fetchingCustomFile = true;
      axios.post("/api/sites/".concat(this.site.id, "/get-file-contents"), this.form).then(function (_ref3) {
        var fileContent = _ref3.data;
        _this5.customFileFetched = true;
        _this5.fileContent = fileContent;
      })["catch"](function (_ref4) {
        var response = _ref4.response;

        _this5.$root.flashMessage(response.data.message || 'Failed to fetch this file.');
      })["finally"](function () {
        _this5.fetchingCustomFile = false;
      });
    },
    updateFile: function updateFile() {
      var _this6 = this;

      this.updatingCustomFile = true;
      axios.post("/api/sites/".concat(this.site.id, "/update-file-contents"), {
        fileContent: this.fileContent,
        path: this.form.path
      }).then(function (_ref5) {
        var fileContent = _ref5.data;
        _this6.customFileFetched = false;
        _this6.fileContent = '';

        _this6.$root.flashMessage('File content updated.');
      })["catch"](function (_ref6) {
        var response = _ref6.response;

        _this6.$root.flashMessage(response.data || 'Failed to update this file.');
      })["finally"](function () {
        _this6.updatingCustomFile = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Files.vue?vue&type=template&id=4ec34f7b&":
/*!*********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Sites/Files.vue?vue&type=template&id=4ec34f7b& ***!
  \*********************************************************************************************************************************************************************************************************/
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
    { on: { mounted: _vm.siteMounted } },
    [
      _c(
        "template",
        { slot: "content" },
        [
          _c("flash"),
          _vm._v(" "),
          _vm.site.type === "nodejs" && _vm.server.type !== "load_balancer"
            ? _c(
                "card",
                { staticClass: "mb-6", attrs: { title: "PM2 Ecosystem file" } },
                [
                  _c("info", [
                    _vm._v(
                      "\n                This is the PM2 configuration for your site. Here you can\n                define environment secrets. The content of this file is\n                never saved on our servers.\n            "
                    )
                  ]),
                  _vm._v(" "),
                  !_vm.ecosystemFile
                    ? _c("v-button", {
                        staticClass: "mt-4",
                        attrs: {
                          loading: _vm.fetchingEcosystemFile,
                          label: "Edit Ecosystem file"
                        },
                        on: { click: _vm.fetchPm2File }
                      })
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.ecosystemFile
                    ? _c("codemirror", {
                        staticClass: "my-4",
                        attrs: { options: _vm.codeMirrorOptions },
                        model: {
                          value: _vm.ecosystemFile,
                          callback: function($$v) {
                            _vm.ecosystemFile = $$v
                          },
                          expression: "ecosystemFile"
                        }
                      })
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.ecosystemFile
                    ? _c("v-button", {
                        staticClass: "mt-4",
                        attrs: {
                          loading: _vm.updatingEcosystemFile,
                          label: "Update Ecosystem file"
                        },
                        on: { click: _vm.updatePm2File }
                      })
                    : _vm._e()
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _c(
            "card",
            {
              staticClass: "mb-5",
              attrs: { title: "Nginx configuration file" }
            },
            [
              _c("info", [
                _vm._v(
                  "\n                This is the Nginx configuration for your site.\n                "
                ),
                _vm.site.type === "nodejs"
                  ? _c("span", { staticClass: "ml-1" }, [
                      _vm._v(
                        "Make sure the proxy port here matches the port your\n                    application is running on."
                      )
                    ])
                  : _vm._e()
              ]),
              _vm._v(" "),
              !_vm.nginxConfigFile
                ? _c("v-button", {
                    staticClass: "mt-4",
                    attrs: {
                      loading: _vm.fetchingNginxConfigFile,
                      label: "Edit Nginx Configuration"
                    },
                    on: { click: _vm.fetchNginxConfigFile }
                  })
                : _vm._e(),
              _vm._v(" "),
              _vm.nginxConfigFile
                ? _c("codemirror", {
                    staticClass: "my-4",
                    attrs: { options: _vm.codeMirrorOptions },
                    model: {
                      value: _vm.nginxConfigFile,
                      callback: function($$v) {
                        _vm.nginxConfigFile = $$v
                      },
                      expression: "nginxConfigFile"
                    }
                  })
                : _vm._e(),
              _vm._v(" "),
              _vm.nginxConfigFile
                ? _c("v-button", {
                    staticClass: "mt-4",
                    attrs: {
                      loading: _vm.updatingNginxConfigFile,
                      label: "Update Nginx file"
                    },
                    on: { click: _vm.updateNginxConfigFile }
                  })
                : _vm._e()
            ],
            1
          ),
          _vm._v(" "),
          _vm.server.type !== "load_balancer"
            ? _c("card", { attrs: { title: "Custom file" } }, [
                _vm.customFileFetched
                  ? _c(
                      "div",
                      { staticClass: "w-full" },
                      [
                        _c("text-input", {
                          staticClass: "mb-3",
                          attrs: {
                            name: "path",
                            value: _vm.form.path,
                            readonly: true
                          }
                        }),
                        _vm._v(" "),
                        _c("codemirror", {
                          attrs: { options: _vm.codeMirrorOptions },
                          model: {
                            value: _vm.fileContent,
                            callback: function($$v) {
                              _vm.fileContent = $$v
                            },
                            expression: "fileContent"
                          }
                        }),
                        _vm._v(" "),
                        _c("v-button", {
                          staticClass: "mt-3",
                          attrs: {
                            label: "Update file",
                            loading: _vm.updatingCustomFile
                          },
                          on: { click: _vm.updateFile }
                        })
                      ],
                      1
                    )
                  : _c(
                      "form",
                      {
                        on: {
                          submit: function($event) {
                            $event.preventDefault()
                            return _vm.fetchFile($event)
                          }
                        }
                      },
                      [
                        _c("text-input", {
                          attrs: {
                            name: "path",
                            label: "Path to file",
                            help:
                              "Here you can edit a custom file on this site. Make sure this file is not version controlled, because editing it might break deployments."
                          },
                          model: {
                            value: _vm.form.path,
                            callback: function($$v) {
                              _vm.$set(_vm.form, "path", $$v)
                            },
                            expression: "form.path"
                          }
                        }),
                        _vm._v(" "),
                        _c("v-button", {
                          staticClass: "mt-3",
                          attrs: {
                            label: "Fetch file",
                            type: "submit",
                            loading: _vm.fetchingCustomFile
                          }
                        })
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

/***/ "./resources/js/Pages/Sites/Files.vue":
/*!********************************************!*\
  !*** ./resources/js/Pages/Sites/Files.vue ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Files_vue_vue_type_template_id_4ec34f7b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Files.vue?vue&type=template&id=4ec34f7b& */ "./resources/js/Pages/Sites/Files.vue?vue&type=template&id=4ec34f7b&");
/* harmony import */ var _Files_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Files.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Sites/Files.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Files_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Files_vue_vue_type_template_id_4ec34f7b___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Files_vue_vue_type_template_id_4ec34f7b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Sites/Files.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Sites/Files.vue?vue&type=script&lang=js&":
/*!*********************************************************************!*\
  !*** ./resources/js/Pages/Sites/Files.vue?vue&type=script&lang=js& ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Files_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Files.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Files.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Files_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Sites/Files.vue?vue&type=template&id=4ec34f7b&":
/*!***************************************************************************!*\
  !*** ./resources/js/Pages/Sites/Files.vue?vue&type=template&id=4ec34f7b& ***!
  \***************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Files_vue_vue_type_template_id_4ec34f7b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Files.vue?vue&type=template&id=4ec34f7b& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Files.vue?vue&type=template&id=4ec34f7b&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Files_vue_vue_type_template_id_4ec34f7b___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Files_vue_vue_type_template_id_4ec34f7b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);