(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[6],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Team.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/Team.vue?vue&type=script&lang=js& ***!
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
          label: 'Email',
          value: 'email'
        }, {
          label: 'Status',
          value: 'status'
        }, {
          label: '',
          value: 'actions'
        }]
      },
      form: {
        email: ''
      }
    };
  },
  computed: {
    subscription: function subscription() {
      return this.$root.auth.subscription;
    },
    teamInvites: function teamInvites() {
      var _this = this;

      var team = this.$root.auth.teams.find(function (team) {
        return team.id === _this.$route.params.id;
      });
      console.log(team, '>>team');
      return team.invites;
    }
  },
  mounted: function mounted() {
    this.initializeForm("/api/teams/".concat(this.$route.params.id, "/invites"));
  },
  methods: {
    submit: function submit() {
      var _this2 = this;

      this.submitForm().then(function (user) {
        _this2.$root.auth = user;
        _this2.form = {
          email: ''
        };

        _this2.$root.flashMessage('Team invite has been sent.');
      })["catch"](function (response) {
        _this2.$root.flashMessage(response.data.message || 'Failed sending team invite.', 'error');
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Team.vue?vue&type=template&id=0c847fc8&":
/*!**********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/Team.vue?vue&type=template&id=0c847fc8& ***!
  \**********************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var this$1 = this
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
                    {
                      staticClass: "mb-5",
                      attrs: {
                        title: "Invite new team member",
                        hasBackButton: "",
                        backHandler: function() {
                          return this$1.$router.push({ name: "account.teams" })
                        }
                      }
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
                            attrs: {
                              name: "email",
                              label: "Email",
                              placeholder: "member@email.com",
                              errors: _vm.formErrors.name,
                              help: "Provide an email to invite to team."
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
                          _c("v-button", {
                            staticClass: "mt-5",
                            attrs: {
                              type: "submit",
                              label: "Add team member",
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
                        title: "Team Members",
                        rowsCount: _vm.teamInvites.length,
                        emptyTableMessage: "No team invites sent yet."
                      }
                    },
                    [
                      _c("v-table", {
                        attrs: {
                          headers: _vm.table.headers,
                          rows: _vm.teamInvites
                        },
                        scopedSlots: _vm._u([
                          {
                            key: "row",
                            fn: function(ref) {
                              var row = ref.row
                              var header = ref.header
                              return [
                                header.value === "email"
                                  ? _c(
                                      "span",
                                      { staticClass: "text-gray-800" },
                                      [_vm._v(_vm._s(row[header.value]))]
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                header.value === "status"
                                  ? _c("table-status", {
                                      attrs: { status: row.status }
                                    })
                                  : _vm._e(),
                                _vm._v(" "),
                                header.value === "actions"
                                  ? _c("div", [_c("delete-button")], 1)
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

/***/ "./resources/js/Pages/Account/Team.vue":
/*!*********************************************!*\
  !*** ./resources/js/Pages/Account/Team.vue ***!
  \*********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Team_vue_vue_type_template_id_0c847fc8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Team.vue?vue&type=template&id=0c847fc8& */ "./resources/js/Pages/Account/Team.vue?vue&type=template&id=0c847fc8&");
/* harmony import */ var _Team_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Team.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Account/Team.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Team_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Team_vue_vue_type_template_id_0c847fc8___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Team_vue_vue_type_template_id_0c847fc8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Account/Team.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Account/Team.vue?vue&type=script&lang=js&":
/*!**********************************************************************!*\
  !*** ./resources/js/Pages/Account/Team.vue?vue&type=script&lang=js& ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Team_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Team.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Team.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Team_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Account/Team.vue?vue&type=template&id=0c847fc8&":
/*!****************************************************************************!*\
  !*** ./resources/js/Pages/Account/Team.vue?vue&type=template&id=0c847fc8& ***!
  \****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Team_vue_vue_type_template_id_0c847fc8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Team.vue?vue&type=template&id=0c847fc8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Team.vue?vue&type=template&id=0c847fc8&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Team_vue_vue_type_template_id_0c847fc8___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Team_vue_vue_type_template_id_0c847fc8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);