(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[6],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Teams.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/Teams.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      table: {
        headers: [{
          label: 'Name',
          value: 'name'
        }, {
          label: '',
          value: 'actions'
        }]
      },
      form: {
        name: ''
      }
    };
  },
  computed: {
    subscription: function subscription() {
      return this.$root.auth.subscription;
    },
    teams: function teams() {
      return this.$root.auth.teams;
    }
  },
  mounted: function mounted() {
    this.initializeForm('/api/teams');
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.submitForm().then(function (user) {
        _this.$root.auth = user;
        _this.form = {
          name: ''
        };

        _this.$root.flashMessage('Team has been added.');
      })["catch"](function (_ref) {
        var response = _ref.response;

        _this.$root.flashMessage(response.data.message || 'Failed creating team.', 'error');
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Teams.vue?vue&type=template&id=f091cb4a&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/Teams.vue?vue&type=template&id=f091cb4a& ***!
  \***********************************************************************************************************************************************************************************************************/
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
          _vm.subscription.plan !== "business"
            ? _c(
                "card",
                { attrs: { title: "Upgrade your plan to add teams" } },
                [
                  _c("v-button", {
                    attrs: {
                      label: "Upgrade to Business",
                      component: "router-link",
                      to: "/account/subscription"
                    }
                  })
                ],
                1
              )
            : _c(
                "div",
                [
                  _c(
                    "card",
                    { staticClass: "mb-5", attrs: { title: "Add new team" } },
                    [
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
                          _c("text-input", {
                            attrs: {
                              name: "name",
                              label: "Name",
                              placeholder: "Artisans",
                              errors: _vm.formErrors.name,
                              help: "Provide a name for your team."
                            },
                            model: {
                              value: _vm.form.name,
                              callback: function($$v) {
                                _vm.$set(_vm.form, "name", $$v)
                              },
                              expression: "form.name"
                            }
                          }),
                          _vm._v(" "),
                          _c("v-button", {
                            staticClass: "mt-5",
                            attrs: {
                              type: "submit",
                              label: "Add team",
                              loading: _vm.submitting
                            }
                          })
                        ],
                        1
                      )
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "card",
                    {
                      attrs: {
                        table: true,
                        title: "Teams",
                        rowsCount: _vm.teams.length,
                        emptyTableMessage: "No teams yet."
                      }
                    },
                    [
                      _c("v-table", {
                        attrs: { headers: _vm.table.headers, rows: _vm.teams },
                        scopedSlots: _vm._u([
                          {
                            key: "row",
                            fn: function(ref) {
                              var row = ref.row
                              var header = ref.header
                              return [
                                header.value === "name"
                                  ? _c(
                                      "span",
                                      { staticClass: "text-gray-800" },
                                      [_vm._v(_vm._s(row[header.value]))]
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                header.value === "actions"
                                  ? _c(
                                      "div",
                                      [
                                        _c(
                                          "router-link",
                                          {
                                            staticClass:
                                              "border-2 border-blue-500 p-1 rounded hover:bg-blue-100 shadow mr-3",
                                            attrs: {
                                              tag: "button",
                                              to: {
                                                name: "account.team.team-id",
                                                params: {
                                                  id: row.id
                                                }
                                              }
                                            }
                                          },
                                          [
                                            _c(
                                              "svg",
                                              {
                                                staticClass: "text-blue-500",
                                                attrs: {
                                                  width: "20",
                                                  height: "20",
                                                  fill: "none",
                                                  "stroke-linecap": "round",
                                                  "stroke-linejoin": "round",
                                                  "stroke-width": "2",
                                                  stroke: "currentColor",
                                                  viewBox: "0 0 24 24"
                                                }
                                              },
                                              [
                                                _c("path", {
                                                  attrs: {
                                                    d:
                                                      "M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                                  }
                                                })
                                              ]
                                            )
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "button",
                                          {
                                            staticClass:
                                              "border-2 border-blue-500 p-1 rounded hover:bg-blue-100 shadow mr-3",
                                            attrs: { type: "button" }
                                          },
                                          [
                                            _c(
                                              "svg",
                                              {
                                                staticClass: "text-blue-500",
                                                attrs: {
                                                  width: "20",
                                                  height: "20",
                                                  fill: "none",
                                                  "stroke-linecap": "round",
                                                  "stroke-linejoin": "round",
                                                  "stroke-width": "2",
                                                  stroke: "currentColor",
                                                  viewBox: "0 0 24 24"
                                                }
                                              },
                                              [
                                                _c("path", {
                                                  attrs: {
                                                    d:
                                                      "M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                                                  }
                                                })
                                              ]
                                            )
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c("delete-button")
                                      ],
                                      1
                                    )
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
        1
      )
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Account/Teams.vue":
/*!**********************************************!*\
  !*** ./resources/js/Pages/Account/Teams.vue ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Teams_vue_vue_type_template_id_f091cb4a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Teams.vue?vue&type=template&id=f091cb4a& */ "./resources/js/Pages/Account/Teams.vue?vue&type=template&id=f091cb4a&");
/* harmony import */ var _Teams_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Teams.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Account/Teams.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Teams_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Teams_vue_vue_type_template_id_f091cb4a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Teams_vue_vue_type_template_id_f091cb4a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Account/Teams.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Account/Teams.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/Pages/Account/Teams.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Teams_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Teams.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Teams.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Teams_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Account/Teams.vue?vue&type=template&id=f091cb4a&":
/*!*****************************************************************************!*\
  !*** ./resources/js/Pages/Account/Teams.vue?vue&type=template&id=f091cb4a& ***!
  \*****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Teams_vue_vue_type_template_id_f091cb4a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Teams.vue?vue&type=template&id=f091cb4a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Teams.vue?vue&type=template&id=f091cb4a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Teams_vue_vue_type_template_id_f091cb4a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Teams_vue_vue_type_template_id_f091cb4a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);