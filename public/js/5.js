(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[5],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Subscription.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/Subscription.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      updating: false,
      cancellingSubscription: false,
      cancelling: false,
      plans: [{
        name: 'Free',
        key: 'free',
        price: 0,
        features: [{
          name: '1 Server',
          bold: true
        }, {
          name: 'Unlimited Sites'
        }, {
          name: 'Unlimited Deployments'
        }, {
          name: 'Push To Deploy'
        }]
      }, {
        name: 'Pro',
        key: 'pro',
        price: 5,
        id: parseInt("579858"),
        features: [{
          name: 'Unlimited Servers',
          bold: true
        }, {
          name: 'Unlimited Sites'
        }, {
          name: 'Unlimited Deployments'
        }, {
          name: 'Push To Deploy'
        }]
      }, {
        name: 'Business',
        key: 'business',
        price: 15,
        id: parseInt("579859"),
        features: [{
          name: 'Unlimited Servers'
        }, {
          name: 'Unlimited Sites'
        }, {
          name: 'Unlimited Teams'
        }, {
          name: 'Unlimited Collaboratos',
          bold: true
        }]
      }]
    };
  },
  computed: {
    subscription: function subscription() {
      return this.user.subscription;
    },
    user: function user() {
      return this.$root.auth;
    }
  },
  mounted: function mounted() {
    window.Paddle.Setup({
      vendor: parseInt("40489")
    });
  },
  methods: {
    getPlanLabel: function getPlanLabel(plan) {
      if (this.subscription.plan === 'free') {
        return "Upgrade to ".concat(plan.name, " plan");
      }

      if (this.subscription.plan === 'business') {
        return "Downgrade to ".concat(plan.name, " plan");
      }

      if (this.subscription.plan === 'pro') {
        if (plan.key === 'free') {
          return 'Downgrade to free plan';
        }

        return 'Upgrade to Business plan';
      }
    },
    openPaddle: function openPaddle(selectedPlan) {
      var _this = this;

      if (selectedPlan.key === 'free') {
        this.toggleCancellingSubscription();
        return;
      }

      if (this.subscription.plan !== 'free') {
        this.updatePlan(selectedPlan);
        return;
      }

      window.Paddle.Checkout.open({
        product: selectedPlan.id,
        email: this.user.email,
        successCallback: function successCallback(data) {
          _this.updating = true;
          setTimeout(function () {
            window.location.href = '/account/subscription';
          }, 5000);
        }
      });
    },
    cancelSubscription: function cancelSubscription() {
      var _this2 = this;

      this.cancelling = true;
      axios["delete"]('/api/subscription/cancel').then(function (_ref) {
        var data = _ref.data;
        _this2.$root.auth = data;

        _this2.$root.flashMessage("Your plan has been cancelled.");
      })["catch"](function (_ref2) {
        var response = _ref2.response;

        if (response.data && response.data.message) {
          _this2.$root.flashMessage(response.data.message, 'error');
        } else {
          _this2.$root.flashMessage('Failed to cancel subscription.', 'error');
        }
      })["finally"](function () {
        _this2.cancelling = false;

        _this2.toggleCancellingSubscription();
      });
    },
    toggleCancellingSubscription: function toggleCancellingSubscription() {
      this.cancellingSubscription = !this.cancellingSubscription;
    },
    updatePlan: function updatePlan(selectedPlan) {
      var _this3 = this;

      this.updating = true;
      axios.patch('/api/subscription/update', {
        plan: selectedPlan.key
      }).then(function (_ref3) {
        var data = _ref3.data;
        _this3.$root.auth = data;

        _this3.$root.flashMessage("Subscription has been updated to ".concat(selectedPlan.key));
      })["catch"](function (_ref4) {
        var response = _ref4.response;

        if (response.data && response.data.message) {
          _this3.$root.flashMessage(response.data.message, 'error');
        } else {
          _this3.$root.flashMessage('Failed to update subscription plan.', 'error');
        }
      })["finally"](function () {
        _this3.updating = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Subscription.vue?vue&type=template&id=044b98f0&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/Subscription.vue?vue&type=template&id=044b98f0& ***!
  \******************************************************************************************************************************************************************************************************************/
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
          _c("confirm-modal", {
            attrs: {
              confirming: _vm.cancelling,
              open: _vm.cancellingSubscription,
              confirmHeading: "Cancel subscription",
              confirmText:
                "Are you sure you want cancel your subscription ? You would loose all features in the " +
                _vm.subscription.plan +
                " plan"
            },
            on: {
              confirm: _vm.cancelSubscription,
              close: _vm.toggleCancellingSubscription
            }
          }),
          _vm._v(" "),
          _c("flash"),
          _vm._v(" "),
          _c(
            "card",
            {
              staticClass: "mb-6",
              attrs: { title: "Update your nesabox plan" }
            },
            [
              !_vm.updating
                ? _c(
                    "div",
                    { staticClass: "w-full flex flex-wrap" },
                    _vm._l(_vm.plans, function(plan) {
                      return _c(
                        "div",
                        {
                          key: plan.key,
                          staticClass:
                            "w-full sm:w-1/2 lg:w-1/3 px-2 mt-4 md:mt-0"
                        },
                        [
                          _c(
                            "div",
                            {
                              staticClass:
                                "bg-white shadow rounded overflow-hidden"
                            },
                            [
                              _c(
                                "div",
                                {
                                  staticClass:
                                    "bg-sha-green-500 text-white px-3 py-3 text-xl"
                                },
                                [
                                  _c(
                                    "div",
                                    {
                                      staticClass:
                                        "flex justify-between items-center font-medium"
                                    },
                                    [
                                      _c("div", [_vm._v(_vm._s(plan.name))]),
                                      _vm._v(" "),
                                      _c("div", [
                                        _vm._v(
                                          "$" + _vm._s(plan.price) + " / month"
                                        )
                                      ])
                                    ]
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "div",
                                { staticClass: "bg-white px-4 py-4" },
                                _vm._l(plan.features, function(feature) {
                                  return _c(
                                    "div",
                                    { key: feature.name, staticClass: "mt-4" },
                                    [
                                      _c(
                                        "div",
                                        { staticClass: "flex items-center" },
                                        [
                                          _c(
                                            "svg",
                                            {
                                              staticClass:
                                                "w-6 h-6 text-sha-green-500",
                                              attrs: {
                                                fill: "none",
                                                stroke: "currentColor",
                                                "stroke-linecap": "round",
                                                "stroke-linejoin": "round",
                                                "stroke-width": "2",
                                                viewBox: "0 0 24 24"
                                              }
                                            },
                                            [
                                              _c("path", {
                                                attrs: { d: "M5 13l4 4L19 7" }
                                              })
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "div",
                                            {
                                              staticClass: "ml-2",
                                              class: {
                                                "font-medium": feature.bold
                                              }
                                            },
                                            [
                                              _vm._v(
                                                "\n                                        " +
                                                  _vm._s(feature.name) +
                                                  "\n                                    "
                                              )
                                            ]
                                          )
                                        ]
                                      )
                                    ]
                                  )
                                }),
                                0
                              )
                            ]
                          ),
                          _vm._v(" "),
                          _vm.subscription.plan === plan.key
                            ? _c(
                                "p",
                                {
                                  staticClass:
                                    "mt-4 text-center font-medium text-lg"
                                },
                                [
                                  _vm._v(
                                    "\n                        Your current plan\n                    "
                                  )
                                ]
                              )
                            : _c(
                                _vm.getPlanLabel(plan).match(/Downgrade/)
                                  ? "red-button"
                                  : "v-button",
                                {
                                  tag: "component",
                                  staticClass: "mt-4",
                                  attrs: {
                                    full: true,
                                    label: _vm.getPlanLabel(plan)
                                  },
                                  on: {
                                    click: function($event) {
                                      return _vm.openPaddle(plan)
                                    }
                                  }
                                }
                              )
                        ],
                        1
                      )
                    }),
                    0
                  )
                : _c("v-button", {
                    attrs: { label: "Updating subscription", loading: true }
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

/***/ "./resources/js/Pages/Account/Subscription.vue":
/*!*****************************************************!*\
  !*** ./resources/js/Pages/Account/Subscription.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Subscription_vue_vue_type_template_id_044b98f0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Subscription.vue?vue&type=template&id=044b98f0& */ "./resources/js/Pages/Account/Subscription.vue?vue&type=template&id=044b98f0&");
/* harmony import */ var _Subscription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Subscription.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Account/Subscription.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Subscription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Subscription_vue_vue_type_template_id_044b98f0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Subscription_vue_vue_type_template_id_044b98f0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Account/Subscription.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Account/Subscription.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/Pages/Account/Subscription.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Subscription.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Subscription.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Account/Subscription.vue?vue&type=template&id=044b98f0&":
/*!************************************************************************************!*\
  !*** ./resources/js/Pages/Account/Subscription.vue?vue&type=template&id=044b98f0& ***!
  \************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_template_id_044b98f0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Subscription.vue?vue&type=template&id=044b98f0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/Subscription.vue?vue&type=template&id=044b98f0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_template_id_044b98f0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_template_id_044b98f0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);