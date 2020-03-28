(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[8],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Single.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Single.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
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
      projectTypes: [{
        label: 'Static HTML',
        value: 'html'
      }, {
        label: 'Nodejs',
        value: 'nodejs'
      }],
      form: {
        directory: '/',
        type: 'nodejs',
        name: ''
      },
      table: {
        headers: [{
          label: 'Domain',
          value: 'name'
        }, {
          label: 'Repository',
          value: 'repository'
        }, {
          label: 'Type',
          value: 'type'
        }, {
          label: 'Status',
          value: 'status'
        }]
      }
    };
  },
  mounted: function mounted() {
    this.initializeForm("/api/servers/".concat(this.serverId, "/sites"));
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.submitForm().then(function (server) {
        _this.form = {
          directory: '/',
          type: 'nodejs',
          name: ''
        };
        _this.$root.servers = _objectSpread({}, _this.$root.servers, _defineProperty({}, server.id, server));

        _this.$root.flashMessage('Server has been created.');
      });
    },
    routeToSite: function routeToSite(site) {
      if (site.status !== 'active') {
        return;
      }

      this.$router.push("/servers/".concat(this.serverId, "/sites/").concat(site.id));
    }
  },
  computed: {
    serverId: function serverId() {
      return this.$route.params.server;
    },
    server: function server() {
      return this.$root.servers[this.serverId];
    },
    sites: function sites() {
      if (!this.server) {
        return [];
      }

      return this.$root.servers[this.serverId].sites;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Single.vue?vue&type=template&id=fedb9fa0&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Single.vue?vue&type=template&id=fedb9fa0& ***!
  \************************************************************************************************************************************************************************************************************/
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
          _c(
            "card",
            { staticClass: "mb-4 md:mb-8", attrs: { title: "New Site" } },
            [
              _c("flash"),
              _vm._v(" "),
              _c(
                "form",
                {
                  on: {
                    submit: function($event) {
                      $event.preventDefault()
                      return _vm.submit($event)
                    }
                  }
                },
                [
                  _c(
                    "div",
                    { staticClass: "w-full mt-5" },
                    [
                      _c("text-input", {
                        attrs: {
                          name: "domain",
                          label: "Root domain",
                          errors: _vm.formErrors.name,
                          placeholder: "www.example.com",
                          help:
                            "You can host multiple sites on a server. To add a new site, provide the domain name the site would be hosted on."
                        },
                        model: {
                          value: _vm.form.name,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "name", $$v)
                          },
                          expression: "form.name"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "w-full mt-5" },
                    [
                      _c("select-input", {
                        attrs: {
                          name: "type",
                          label: "Project type",
                          options: _vm.projectTypes,
                          errors: _vm.formErrors.type,
                          placeholder: "www.example.com",
                          help:
                            "If your project is a Single Page application, static html site or related, select Static HTML. Select Nodejs for a standard Nodejs application."
                        },
                        model: {
                          value: _vm.form.type,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "type", $$v)
                          },
                          expression: "form.type"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "w-full mt-5" },
                    [
                      _c("text-input", {
                        attrs: {
                          name: "directory",
                          placeholder: "/dist",
                          label: "Web directory",
                          errors: _vm.formErrors.directory,
                          help:
                            "If your app builds into a sub directory such as /dist or /build, set the web root to the build directory. The Nginx configuration will point to it."
                        },
                        model: {
                          value: _vm.form.directory,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "directory", $$v)
                          },
                          expression: "form.directory"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "flex justify-end w-full w-full mt-5" },
                    [
                      _c("v-button", {
                        staticClass: "w-full md:w-1/5",
                        attrs: {
                          type: "submit",
                          label: "Add site",
                          disabled: _vm.submitting
                        }
                      })
                    ],
                    1
                  )
                ]
              )
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "card",
            {
              attrs: {
                title: "Active Sites",
                rowsCount: _vm.sites.length,
                table: true,
                emptyTableMessage: "No sites on this server yet."
              }
            },
            [
              _c("v-table", {
                attrs: { headers: _vm.table.headers, rows: _vm.sites },
                on: { "row-clicked": _vm.routeToSite },
                scopedSlots: _vm._u([
                  {
                    key: "row",
                    fn: function(ref) {
                      var row = ref.row
                      var header = ref.header
                      return [
                        header.value === "name"
                          ? _c("span", [
                              _vm._v(
                                "\n                        " +
                                  _vm._s(row.name) +
                                  "\n                    "
                              )
                            ])
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "type"
                          ? _c("span", { staticClass: "capitalize" }, [
                              _vm._v(
                                "\n                        " +
                                  _vm._s(row.type) +
                                  "\n                    "
                              )
                            ])
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "status"
                          ? _c("table-status", {
                              attrs: { status: row.status }
                            })
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "repository"
                          ? _c("div", { staticClass: "flex " }, [
                              !row.repository
                                ? _c(
                                    "svg",
                                    {
                                      staticClass: "mr-3",
                                      attrs: {
                                        width: "24",
                                        height: "24",
                                        viewBox: "0 0 24 24",
                                        fill: "none",
                                        xmlns: "http://www.w3.org/2000/svg"
                                      }
                                    },
                                    [
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M10 14L12 12M12 12L14 10M12 12L10 10M12 12L14 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z",
                                          stroke: "#4A5568",
                                          "stroke-width": "2",
                                          "stroke-linecap": "round",
                                          "stroke-linejoin": "round"
                                        }
                                      })
                                    ]
                                  )
                                : _vm._e(),
                              _vm._v(
                                "\n                        " +
                                  _vm._s(row.repository || "None") +
                                  "\n                    "
                              )
                            ])
                          : _vm._e()
                      ]
                    }
                  }
                ])
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

/***/ "./resources/js/Pages/Servers/Single.vue":
/*!***********************************************!*\
  !*** ./resources/js/Pages/Servers/Single.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Single_vue_vue_type_template_id_fedb9fa0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Single.vue?vue&type=template&id=fedb9fa0& */ "./resources/js/Pages/Servers/Single.vue?vue&type=template&id=fedb9fa0&");
/* harmony import */ var _Single_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Single.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Servers/Single.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Single_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Single_vue_vue_type_template_id_fedb9fa0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Single_vue_vue_type_template_id_fedb9fa0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Servers/Single.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Servers/Single.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Single.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Single.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Single.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Servers/Single.vue?vue&type=template&id=fedb9fa0&":
/*!******************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Single.vue?vue&type=template&id=fedb9fa0& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_template_id_fedb9fa0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Single.vue?vue&type=template&id=fedb9fa0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Single.vue?vue&type=template&id=fedb9fa0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_template_id_fedb9fa0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_template_id_fedb9fa0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);