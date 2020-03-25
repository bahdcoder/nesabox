(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[2],{

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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      loading: true,
      allServers: {
        servers: [],
        team_servers: []
      },
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
      return this.allServers.servers.concat(this.allServers.team_servers);
    }
  },
  mounted: function mounted() {
    var _this = this;

    axios.get('/api/servers').then(function (_ref) {
      var data = _ref.data;
      _this.loading = false;
      _this.allServers = data;
    });
  },
  methods: {
    routeToServer: function routeToServer(server) {
      this.$router.push("/servers/".concat(server.id));
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
            _c("div", { staticClass: "ml-4 mt-2 flex-shrink-0" }, [
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
            ])
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
                          ? _c(
                              "span",
                              {
                                staticClass:
                                  "px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize",
                                class: {
                                  "bg-green-100 text-green-800":
                                    row.status === "active",
                                  "bg-blue-100 text-blue-800": [
                                    "initializing",
                                    "installing"
                                  ].includes(row.status)
                                }
                              },
                              [
                                _vm._v(
                                  "\n                    " +
                                    _vm._s(row.status) +
                                    "\n                "
                                )
                              ]
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "name"
                          ? _c("div", { staticClass: "flex items-center" }, [
                              _c(
                                "div",
                                { staticClass: "flex-shrink-0 h-6 w-6" },
                                [
                                  _c("v-svg", {
                                    staticClass: "w-6 h-6",
                                    attrs: { icon: row.provider }
                                  })
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
                1737942817
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