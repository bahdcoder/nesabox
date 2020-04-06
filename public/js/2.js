(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[2],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Index.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/Index.vue?vue&type=script&lang=js& ***!
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      form: {
        name: '',
        email: ''
      },
      passwordForm: {
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      },
      passwordErrors: {},
      submitting: false,
      updating: false
    };
  },
  mounted: function mounted() {
    this.form = {
      name: this.$root.auth.name,
      email: this.$root.auth.email
    };
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.submitting = true;
      axios.put("/api/me", this.form).then(function (_ref) {
        var user = _ref.data;
        _this.$root.auth = user;

        _this.$root.flashMessage('Profile updated.');
      })["catch"](function (_ref2) {
        var response = _ref2.response;

        if (response.status === 422) {
          _this.errors = response.data.errors;
        } else {
          _this.$root.flashMessage(response.data.message || 'Failed updating profile.', 'error');
        }
      })["finally"](function () {
        _this.submitting = false;
      });
    },
    update: function update() {
      var _this2 = this;

      this.updating = true;
      axios.put("/api/me/password", this.passwordForm).then(function (_ref3) {
        var user = _ref3.data;

        _this2.$root.flashMessage('Password updated.');

        _this2.passwordErrors = {};
      })["catch"](function (_ref4) {
        var response = _ref4.response;

        if (response.status === 422) {
          _this2.passwordErrors = response.data.errors;
        } else {
          _this2.$root.flashMessage(response.data.message || 'Failed updating password.', 'error');
        }
      })["finally"](function () {
        _this2.updating = false;
        _this2.passwordForm = {
          current_password: '',
          new_password: '',
          new_password_confirmation: ''
        };
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Index.vue?vue&type=template&id=61034b77&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/Index.vue?vue&type=template&id=61034b77& ***!
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
          _c(
            "card",
            {
              staticClass: "mb-6",
              attrs: { title: "Change Profile Information" }
            },
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
                    attrs: { name: "name", label: "Name" },
                    model: {
                      value: _vm.form.name,
                      callback: function($$v) {
                        _vm.$set(_vm.form, "name", $$v)
                      },
                      expression: "form.name"
                    }
                  }),
                  _vm._v(" "),
                  _c("text-input", {
                    staticClass: "mt-5",
                    attrs: { name: "email", label: "Email" },
                    model: {
                      value: _vm.form.email,
                      callback: function($$v) {
                        _vm.$set(_vm.form, "email", $$v)
                      },
                      expression: "form.email"
                    }
                  }),
                  _vm._v(" "),
                  _c("v-button", {
                    staticClass: "mt-5",
                    attrs: {
                      type: "submit",
                      label: "Update Profile",
                      loading: _vm.submitting
                    }
                  })
                ],
                1
              )
            ]
          ),
          _vm._v(" "),
          _c("card", { attrs: { title: "Change your password" } }, [
            _c(
              "form",
              {
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                    return _vm.update($event)
                  }
                }
              },
              [
                _c("text-input", {
                  attrs: {
                    type: "password",
                    name: "current_password",
                    label: "Current password",
                    errors: _vm.passwordErrors.current_password
                  },
                  model: {
                    value: _vm.passwordForm.current_password,
                    callback: function($$v) {
                      _vm.$set(_vm.passwordForm, "current_password", $$v)
                    },
                    expression: "passwordForm.current_password"
                  }
                }),
                _vm._v(" "),
                _c("text-input", {
                  staticClass: "mt-5",
                  attrs: {
                    type: "password",
                    name: "new_password",
                    label: "New password",
                    errors: _vm.passwordErrors.new_password
                  },
                  model: {
                    value: _vm.passwordForm.new_password,
                    callback: function($$v) {
                      _vm.$set(_vm.passwordForm, "new_password", $$v)
                    },
                    expression: "passwordForm.new_password"
                  }
                }),
                _vm._v(" "),
                _c("text-input", {
                  staticClass: "mt-5",
                  attrs: {
                    type: "password",
                    name: "new_password_confirmation",
                    label: "New password confirmation"
                  },
                  model: {
                    value: _vm.passwordForm.new_password_confirmation,
                    callback: function($$v) {
                      _vm.$set(
                        _vm.passwordForm,
                        "new_password_confirmation",
                        $$v
                      )
                    },
                    expression: "passwordForm.new_password_confirmation"
                  }
                }),
                _vm._v(" "),
                _c("v-button", {
                  staticClass: "mt-5",
                  attrs: {
                    label: "Update password",
                    type: "submit",
                    loading: _vm.updating
                  }
                })
              ],
              1
            )
          ])
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

/***/ "./resources/js/Pages/Account/Index.vue":
/*!**********************************************!*\
  !*** ./resources/js/Pages/Account/Index.vue ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_61034b77___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=61034b77& */ "./resources/js/Pages/Account/Index.vue?vue&type=template&id=61034b77&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Account/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_61034b77___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_61034b77___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Account/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Account/Index.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/Pages/Account/Index.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Account/Index.vue?vue&type=template&id=61034b77&":
/*!*****************************************************************************!*\
  !*** ./resources/js/Pages/Account/Index.vue?vue&type=template&id=61034b77& ***!
  \*****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_61034b77___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=61034b77& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Index.vue?vue&type=template&id=61034b77&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_61034b77___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_61034b77___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);