(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[9],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Auth/Login.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Auth/Login.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      sending: false,
      form: {
        email: '',
        password: '',
        remember: null
      },
      errors: {
        email: []
      },
      loading: false,
      github_login_url: window.github_login_url
    };
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.loading = true;
      axios.post('/login', this.form).then(function () {
        window.location.href = '/dashboard';
      })["catch"](function (_ref) {
        var response = _ref.response;
        _this.loading = false;
        _this.errors = response.data.errors;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Auth/Login.vue?vue&type=template&id=a2ac2cea&":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Auth/Login.vue?vue&type=template&id=a2ac2cea& ***!
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
    "div",
    {
      staticClass:
        "min-h-screen bg-gray-100 flex flex-col justify-center py-12 px-3 sm:px-6 lg:px-8"
    },
    [
      _c("div", { staticClass: "sm:mx-auto sm:w-full sm:max-w-md" }, [
        _c("img", {
          staticClass: "mx-auto h-8 w-auto",
          attrs: { src: "/assets/images/logo.svg", alt: "Workflow" }
        }),
        _vm._v(" "),
        _c(
          "h2",
          {
            staticClass:
              "mt-6 text-center text-3xl leading-9 font-bold text-gray-800"
          },
          [_vm._v("\n            Sign in to your account\n        ")]
        ),
        _vm._v(" "),
        _c(
          "p",
          {
            staticClass:
              "mt-2 text-center text-sm leading-5 text-gray-600 max-w"
          },
          [
            _vm._v("\n            Or\n            "),
            _c(
              "router-link",
              {
                staticClass:
                  "font-medium text-sha-green-500 hover:text-sha-green-400 focus:outline-none focus:underline transition ease-in-out duration-150",
                attrs: { to: "/auth/register" }
              },
              [
                _vm._v(
                  "\n                sign up for a free account\n            "
                )
              ]
            )
          ],
          1
        )
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "mt-8 sm:mx-auto sm:w-full sm:max-w-md" }, [
        _c(
          "div",
          { staticClass: "bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10" },
          [
            _c(
              "form",
              {
                attrs: { method: "POST" },
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
                    name: "email",
                    label: "Email Address",
                    errors: _vm.errors.email
                  },
                  model: {
                    value: _vm.form.email,
                    callback: function($$v) {
                      _vm.$set(_vm.form, "email", $$v)
                    },
                    expression: "form.email"
                  }
                }),
                _vm._v(" "),
                _c("text-input", {
                  staticClass: "mt-6",
                  attrs: {
                    name: "password",
                    type: "password",
                    label: "Password",
                    errors: _vm.errors.password
                  },
                  model: {
                    value: _vm.form.password,
                    callback: function($$v) {
                      _vm.$set(_vm.form, "password", $$v)
                    },
                    expression: "form.password"
                  }
                }),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "mt-6 flex items-center justify-between" },
                  [
                    _c("div", { staticClass: "flex items-center" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.remember,
                            expression: "form.remember"
                          }
                        ],
                        staticClass:
                          "form-checkbox h-4 w-4 text-sha-green-500 transition duration-150 ease-in-out",
                        attrs: { type: "checkbox", id: "remember_me" },
                        domProps: {
                          checked: Array.isArray(_vm.form.remember)
                            ? _vm._i(_vm.form.remember, null) > -1
                            : _vm.form.remember
                        },
                        on: {
                          change: function($event) {
                            var $$a = _vm.form.remember,
                              $$el = $event.target,
                              $$c = $$el.checked ? true : false
                            if (Array.isArray($$a)) {
                              var $$v = null,
                                $$i = _vm._i($$a, $$v)
                              if ($$el.checked) {
                                $$i < 0 &&
                                  _vm.$set(
                                    _vm.form,
                                    "remember",
                                    $$a.concat([$$v])
                                  )
                              } else {
                                $$i > -1 &&
                                  _vm.$set(
                                    _vm.form,
                                    "remember",
                                    $$a.slice(0, $$i).concat($$a.slice($$i + 1))
                                  )
                              }
                            } else {
                              _vm.$set(_vm.form, "remember", $$c)
                            }
                          }
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "label",
                        {
                          staticClass:
                            "ml-2 block text-sm leading-5 text-gray-900",
                          attrs: { for: "remember_me" }
                        },
                        [
                          _vm._v(
                            "\n                            Remember me\n                        "
                          )
                        ]
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "text-sm leading-5" },
                      [
                        _c(
                          "router-link",
                          {
                            staticClass:
                              "font-medium text-sha-green-500 hover:text-sha-green-600 focus:outline-none focus:underline transition ease-in-out duration-150",
                            attrs: { to: "/auth/forgot-password" }
                          },
                          [
                            _vm._v(
                              "\n                            Forgot your password?\n                        "
                            )
                          ]
                        )
                      ],
                      1
                    )
                  ]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "mt-6" }, [
                  _c(
                    "span",
                    { staticClass: "block w-full rounded-md shadow-sm" },
                    [
                      _c("v-button", {
                        attrs: {
                          loading: _vm.loading,
                          type: "submit",
                          label: "Sign in",
                          full: true
                        }
                      })
                    ],
                    1
                  )
                ])
              ],
              1
            ),
            _vm._v(" "),
            _c("div", { staticClass: "mt-6" }, [
              _vm._m(0),
              _vm._v(" "),
              _c("div", { staticClass: "mt-6 flex justify-center w-full" }, [
                _c("div", { staticClass: "w-full md:w-1/3" }, [
                  _c(
                    "span",
                    { staticClass: "w-full inline-flex rounded-md shadow-sm" },
                    [
                      _c(
                        "a",
                        {
                          staticClass:
                            "w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out",
                          attrs: { type: "button", href: _vm.github_login_url }
                        },
                        [
                          _c(
                            "svg",
                            {
                              staticClass: "h-5 h-5",
                              attrs: {
                                fill: "currentColor",
                                viewBox: "0 0 20 20"
                              }
                            },
                            [
                              _c("path", {
                                attrs: {
                                  "fill-rule": "evenodd",
                                  d:
                                    "M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z",
                                  "clip-rule": "evenodd"
                                }
                              })
                            ]
                          )
                        ]
                      )
                    ]
                  )
                ])
              ])
            ])
          ]
        )
      ])
    ]
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "relative" }, [
      _c("div", { staticClass: "absolute inset-0 flex items-center" }, [
        _c("div", { staticClass: "w-full border-t border-gray-300" })
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "relative flex justify-center text-sm leading-5" },
        [
          _c("span", { staticClass: "px-2 bg-white text-gray-500" }, [
            _vm._v(
              "\n                            Or continue with\n                        "
            )
          ])
        ]
      )
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Auth/Login.vue":
/*!*******************************************!*\
  !*** ./resources/js/Pages/Auth/Login.vue ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Login_vue_vue_type_template_id_a2ac2cea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Login.vue?vue&type=template&id=a2ac2cea& */ "./resources/js/Pages/Auth/Login.vue?vue&type=template&id=a2ac2cea&");
/* harmony import */ var _Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Login.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Auth/Login.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Login_vue_vue_type_template_id_a2ac2cea___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Login_vue_vue_type_template_id_a2ac2cea___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Auth/Login.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Auth/Login.vue?vue&type=script&lang=js&":
/*!********************************************************************!*\
  !*** ./resources/js/Pages/Auth/Login.vue?vue&type=script&lang=js& ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Login.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Auth/Login.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Auth/Login.vue?vue&type=template&id=a2ac2cea&":
/*!**************************************************************************!*\
  !*** ./resources/js/Pages/Auth/Login.vue?vue&type=template&id=a2ac2cea& ***!
  \**************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_template_id_a2ac2cea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Login.vue?vue&type=template&id=a2ac2cea& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Auth/Login.vue?vue&type=template&id=a2ac2cea&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_template_id_a2ac2cea___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_template_id_a2ac2cea___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);