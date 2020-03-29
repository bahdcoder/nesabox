(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[5],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/Index.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Dashboard/Index.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
      loading: true,
      showOnlyOwnServers: false,
      table: {
        headers: [{
          label: 'Name',
          value: 'name'
        }, {
          label: 'IP Address',
          value: 'ip_address'
        }, {
          label: 'Status',
          value: 'status'
        }, {
          label: 'Type',
          value: 'type'
        }]
      }
    };
  },
  computed: {
    servers: function servers() {
      return this.showOnlyOwnServers ? this.$root.allServers.servers : this.$root.allServers.servers.concat(this.$root.allServers.team_servers);
    }
  },
  mounted: function mounted() {
    var _this = this;

    axios.get('/api/servers').then(function (_ref) {
      var data = _ref.data;
      _this.loading = false;
      _this.$root.allServers = data;
    });
  },
  methods: {
    routeToServer: function routeToServer(server) {
      this.$router.push("/servers/".concat(server.id));
    },
    toggleShowOwnServers: function toggleShowOwnServers() {
      this.showOnlyOwnServers = !this.showOnlyOwnServers;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/Index.vue?vue&type=template&id=48cd2f5e&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Dashboard/Index.vue?vue&type=template&id=48cd2f5e& ***!
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
  return _c("layout", [
    _c(
      "div",
      {
        staticClass:
          "bg-white px-4 py-5 border-b border-gray-200 sm:px-6 rounded-t"
      },
      [
        _c(
          "div",
          {
            staticClass:
              "-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap"
          },
          [
            _c("div", { staticClass: "ml-4 mt-2" }, [
              _c(
                "h3",
                { staticClass: "text-lg leading-6 font-medium text-gray-900" },
                [_vm._v("\n                    Servers\n                ")]
              )
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "ml-4 mt-2 flex-shrink-0 flex items-center" },
              [
                _c(
                  "div",
                  {
                    staticClass:
                      "hidden md:flex mr-3 items-center justify-center"
                  },
                  [
                    _c(
                      "span",
                      {
                        staticClass:
                          "relative inline-block flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-50 focus:outline-none focus:shadow-outline",
                        class: {
                          "bg-gray-200": !_vm.showOnlyOwnServers,
                          "bg-sha-green-600": _vm.showOnlyOwnServers
                        },
                        attrs: {
                          role: "checkbox",
                          tabindex: "0",
                          "aria-checked": _vm.showOnlyOwnServers
                        },
                        on: { click: _vm.toggleShowOwnServers }
                      },
                      [
                        _c(
                          "span",
                          {
                            staticClass:
                              "relative inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200",
                            class: {
                              "translate-x-5": _vm.showOnlyOwnServers,
                              "translate-x-0": !_vm.showOnlyOwnServers
                            },
                            attrs: { "aria-hidden": "true" }
                          },
                          [
                            _c(
                              "span",
                              {
                                staticClass:
                                  "absolute inset-0 h-full w-full flex items-center justify-center transition-opacity",
                                class: {
                                  "opacity-0 ease-out duration-100":
                                    _vm.showOnlyOwnServers,
                                  "opacity-100 ease-in duration-200": !_vm.showOnlyOwnServers
                                }
                              },
                              [
                                _c(
                                  "svg",
                                  {
                                    staticClass: "h-3 w-3 text-gray-400",
                                    attrs: {
                                      fill: "none",
                                      viewBox: "0 0 12 12"
                                    }
                                  },
                                  [
                                    _c("path", {
                                      attrs: {
                                        d: "M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2",
                                        stroke: "currentColor",
                                        "stroke-width": "2",
                                        "stroke-linecap": "round",
                                        "stroke-linejoin": "round"
                                      }
                                    })
                                  ]
                                )
                              ]
                            ),
                            _vm._v(" "),
                            _c(
                              "span",
                              {
                                staticClass:
                                  "absolute inset-0 h-full w-full flex items-center justify-center transition-opacity",
                                class: {
                                  "opacity-100 ease-in duration-200":
                                    _vm.showOnlyOwnServers,
                                  "opacity-0 ease-out duration-100": !_vm.showOnlyOwnServers
                                }
                              },
                              [
                                _c(
                                  "svg",
                                  {
                                    staticClass: "h-3 w-3 text-sha-green-500",
                                    attrs: {
                                      fill: "currentColor",
                                      viewBox: "0 0 12 12"
                                    }
                                  },
                                  [
                                    _c("path", {
                                      attrs: {
                                        d:
                                          "M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
                                      }
                                    })
                                  ]
                                )
                              ]
                            )
                          ]
                        )
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "span",
                      { staticClass: "inline-block ml-3 text-gray-800" },
                      [_vm._v("Show only own servers")]
                    )
                  ]
                ),
                _vm._v(" "),
                _c(
                  "span",
                  { staticClass: "inline-flex rounded-md shadow-sm" },
                  [
                    _c("v-button", {
                      attrs: {
                        component: "router-link",
                        to: "/servers/create",
                        label: "Add new server"
                      }
                    })
                  ],
                  1
                )
              ]
            )
          ]
        )
      ]
    ),
    _vm._v(" "),
    !_vm.loading && _vm.servers.length === 0
      ? _c(
          "div",
          {
            staticClass:
              "w-full flex px-6 py-12 justify-center items-center bg-white shadow"
          },
          [_c("p", [_vm._v("No servers yet.")])]
        )
      : _vm._e(),
    _vm._v(" "),
    !_vm.loading && _vm.servers.length !== 0
      ? _c(
          "div",
          { staticClass: "w-full bg-white" },
          [
            _c("v-table", {
              attrs: { headers: _vm.table.headers, rows: _vm.servers },
              on: { "row-clicked": _vm.routeToServer },
              scopedSlots: _vm._u(
                [
                  {
                    key: "row",
                    fn: function(ref) {
                      var row = ref.row
                      var header = ref.header
                      return [
                        header.value === "ip_address"
                          ? _c(
                              "span",
                              {
                                staticClass:
                                  "inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                              },
                              [
                                _vm._v(
                                  "\n                    " +
                                    _vm._s(row.ip_address) +
                                    "\n                "
                                )
                              ]
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "type"
                          ? _c(
                              "span",
                              {
                                staticClass:
                                  "inline-flex text-xs leading-5 font-semibold rounded-full capitalize text-gray-700"
                              },
                              [
                                _vm._v(
                                  "\n                    " +
                                    _vm._s(row.type) +
                                    "\n                "
                                )
                              ]
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "status"
                          ? _c("table-status", {
                              attrs: { status: row.status }
                            })
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "name"
                          ? _c("div", { staticClass: "flex items-center" }, [
                              _c(
                                "div",
                                { staticClass: "flex-shrink-0 h-6 w-6" },
                                [
                                  row.provider !== "linode"
                                    ? _c("v-svg", {
                                        staticClass: "w-6 h-6",
                                        attrs: { icon: row.provider }
                                      })
                                    : _vm._e(),
                                  _vm._v(" "),
                                  row.provider === "linode"
                                    ? _c("v-svg", {
                                        attrs: {
                                          icon: row.provider,
                                          width: 30,
                                          height: 30
                                        }
                                      })
                                    : _vm._e()
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c("div", { staticClass: "ml-4" }, [
                                _c(
                                  "div",
                                  {
                                    staticClass:
                                      "text-sm leading-5 font-medium text-gray-900"
                                  },
                                  [
                                    _vm._v(
                                      "\n                            " +
                                        _vm._s(row[header.value]) +
                                        "\n                        "
                                    )
                                  ]
                                )
                              ])
                            ])
                          : _vm._e()
                      ]
                    }
                  }
                ],
                null,
                false,
                3869922580
              )
            })
          ],
          1
        )
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Dashboard/Index.vue":
/*!************************************************!*\
  !*** ./resources/js/Pages/Dashboard/Index.vue ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_48cd2f5e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=48cd2f5e& */ "./resources/js/Pages/Dashboard/Index.vue?vue&type=template&id=48cd2f5e&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Dashboard/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_48cd2f5e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_48cd2f5e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Dashboard/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Dashboard/Index.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/Pages/Dashboard/Index.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Dashboard/Index.vue?vue&type=template&id=48cd2f5e&":
/*!*******************************************************************************!*\
  !*** ./resources/js/Pages/Dashboard/Index.vue?vue&type=template&id=48cd2f5e& ***!
  \*******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_48cd2f5e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=48cd2f5e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/Index.vue?vue&type=template&id=48cd2f5e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_48cd2f5e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_48cd2f5e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);