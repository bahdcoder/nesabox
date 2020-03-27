(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[7],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Single.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Sites/Single.vue?vue&type=script&lang=js& ***!
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
        provider: '',
        repository: '',
        branch: 'master'
      }
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
    },
    repoOptions: function repoOptions() {
      var sourceControl = this.$root.auth.source_control;
      var enabledProviders = Object.keys(this.$root.auth.source_control).filter(function (provider) {
        return sourceControl[provider];
      });
      return enabledProviders.map(function (provider) {
        return {
          label: provider,
          value: provider
        };
      });
    }
  },
  methods: {
    submit: function submit() {
      this.submitForm();
    }
  },
  mounted: function mounted() {
    this.initializeForm("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/install-repository"));
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Single.vue?vue&type=template&id=21e49a74&":
/*!**********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Sites/Single.vue?vue&type=template&id=21e49a74& ***!
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
    "site-layout",
    [
      _c(
        "template",
        { slot: "content" },
        [
          !_vm.site.repository
            ? _c(
                "card",
                { attrs: { title: "Install Repository" } },
                [
                  _vm.repoOptions.length > 0
                    ? _c(
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
                          _c("v-radio", {
                            staticClass: "mt-4",
                            attrs: {
                              id: "provider",
                              options: _vm.repoOptions,
                              label: "Provider",
                              errors: _vm.formErrors.provider
                            },
                            model: {
                              value: _vm.form.provider,
                              callback: function($$v) {
                                _vm.$set(_vm.form, "provider", $$v)
                              },
                              expression: "form.provider"
                            }
                          }),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "w-full mt-5" },
                            [
                              _c("text-input", {
                                attrs: {
                                  name: "repository",
                                  label: "Repository",
                                  placeholder: "user/repository",
                                  errors: _vm.formErrors.repository,
                                  help:
                                    "This should match the path to your repository."
                                },
                                model: {
                                  value: _vm.form.repository,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "repository", $$v)
                                  },
                                  expression: "form.repository"
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
                                  name: "branch",
                                  label: "Branch",
                                  errors: _vm.formErrors.branch,
                                  help:
                                    "All deployments would be triggered from this branch."
                                },
                                model: {
                                  value: _vm.form.branch,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "branch", $$v)
                                  },
                                  expression: "form.branch"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            {
                              staticClass: "flex justify-end w-full w-full mt-5"
                            },
                            [
                              _c("v-button", {
                                staticClass: "w-full md:w-1/5",
                                attrs: {
                                  type: "submit",
                                  disabled: _vm.submitting,
                                  label: "Install repository"
                                }
                              })
                            ],
                            1
                          )
                        ],
                        1
                      )
                    : _c(
                        "router-link",
                        {
                          staticClass:
                            "w-full border border-blue-500 bg-blue-100 flex items-center rounded text-blue-900 px-2 py-3 text-sm",
                          attrs: { to: "/" }
                        },
                        [
                          _vm._v(
                            "\n                You have not configured any git repository providers yet. To\n                setup a site, connect your git repository provider here.\n            "
                          )
                        ]
                      )
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

/***/ "./resources/js/Pages/Sites/Single.vue":
/*!*********************************************!*\
  !*** ./resources/js/Pages/Sites/Single.vue ***!
  \*********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Single_vue_vue_type_template_id_21e49a74___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Single.vue?vue&type=template&id=21e49a74& */ "./resources/js/Pages/Sites/Single.vue?vue&type=template&id=21e49a74&");
/* harmony import */ var _Single_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Single.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Sites/Single.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Single_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Single_vue_vue_type_template_id_21e49a74___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Single_vue_vue_type_template_id_21e49a74___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Sites/Single.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Sites/Single.vue?vue&type=script&lang=js&":
/*!**********************************************************************!*\
  !*** ./resources/js/Pages/Sites/Single.vue?vue&type=script&lang=js& ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Single.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Single.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Sites/Single.vue?vue&type=template&id=21e49a74&":
/*!****************************************************************************!*\
  !*** ./resources/js/Pages/Sites/Single.vue?vue&type=template&id=21e49a74& ***!
  \****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_template_id_21e49a74___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Single.vue?vue&type=template&id=21e49a74& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Single.vue?vue&type=template&id=21e49a74&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_template_id_21e49a74___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Single_vue_vue_type_template_id_21e49a74___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);