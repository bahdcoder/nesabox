(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/ServerProvider.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/ServerProvider.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
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
          label: 'Profile name',
          value: 'profileName'
        }, {
          label: 'Provider',
          value: 'provider'
        }, {
          label: '',
          value: 'actions'
        }]
      },
      serverOptions: [{
        label: 'Digital Ocean',
        value: 'digital-ocean'
      }, {
        label: 'Linode',
        value: 'linode'
      }, {
        label: 'Vultr',
        value: 'vultr'
      }],
      form: {
        provider: '',
        profileName: '',
        accessToken: '',
        apiKey: '',
        apiToken: ''
      },
      deletingProvider: null,
      deleting: false
    };
  },
  computed: {
    credentials: function credentials() {
      var _this = this;

      var providers = [];
      Object.keys(this.$root.auth.providers).forEach(function (provider) {
        providers = providers.concat(_this.$root.auth.providers[provider].map(function (_) {
          return _objectSpread({}, _, {
            provider: {
              'digital-ocean': 'Digital Ocean',
              linode: 'Linode',
              vultr: 'Vultr'
            }[provider]
          });
        }));
      });
      return providers;
    },
    apiKeyLabel: function apiKeyLabel() {
      return {
        'digital-ocean': {
          label: 'API Token',
          name: 'apiToken',
          link: 'https://cloud.digitalocean.com/account/api/tokens'
        },
        linode: {
          label: 'Access Token',
          name: 'accessToken',
          link: 'https://cloud.linode.com/profile/tokens'
        },
        vultr: {
          label: 'API Key',
          name: 'apiKey',
          link: 'https://my.vultr.com/settings/#settingsapi'
        }
      }[this.form.provider] || {};
    }
  },
  mounted: function mounted() {
    this.initializeForm('/api/settings/server-providers');
  },
  methods: {
    submit: function submit() {
      var _this2 = this;

      this.submitForm().then(function (data) {
        _this2.form = {
          profileName: '',
          accessToken: '',
          apiKey: '',
          apiToken: ''
        };
        _this2.$root.auth = data;

        _this2.$root.flashMessage("Provider added successfully. You can now create servers using your new ".concat(_this2.form.provider, " credentials."));
      });
    },
    setDeletingProvider: function setDeletingProvider(provider) {
      this.deletingProvider = provider;
    },
    deleteProfile: function deleteProfile() {
      var _this3 = this;

      this.deleting = true;
      axios["delete"]("/api/settings/server-providers/".concat(this.deletingProvider.id)).then(function (_ref) {
        var user = _ref.data;

        _this3.$root.flashMessage("Profile has been deleted.");

        _this3.$root.auth = user;
        _this3.deletingProvider = null;
        _this3.deleting = false;
      })["catch"](function () {
        _this3.$root.flashMessage('Failed deleting profile.');

        _this3.deleting = false;
        _this3.deletingProvider = null;
      });
    },
    closeConfirmDelete: function closeConfirmDelete() {
      this.deletingProvider = null;
      this.deleting = false;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/ServerProvider.vue?vue&type=template&id=4ebe7702&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/ServerProvider.vue?vue&type=template&id=4ebe7702& ***!
  \********************************************************************************************************************************************************************************************************************/
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
              confirming: _vm.deleting,
              open: !!_vm.deletingProvider,
              confirmHeading: "Delete provider profile",
              confirmText:
                "Are you sure you want to delete your " +
                (_vm.deletingProvider && _vm.deletingProvider.profileName) +
                " profile for " +
                (_vm.deletingProvider && _vm.deletingProvider.provider) +
                " ?"
            },
            on: { confirm: _vm.deleteProfile, close: _vm.closeConfirmDelete }
          }),
          _vm._v(" "),
          _c(
            "card",
            { staticClass: "mb-5", attrs: { title: "New Server Provider" } },
            [
              _c("flash", { staticClass: "my-2" }),
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
                  _c("v-radio", {
                    attrs: {
                      id: "provider",
                      label: "Provider",
                      options: _vm.serverOptions,
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
                      _vm.form.provider
                        ? _c("text-input", {
                            attrs: {
                              name: "profileName",
                              label: "Profile name",
                              errors: _vm.formErrors.profileName,
                              help:
                                "This should be a memorable name to identify the provider api key. It can also be the name of the account."
                            },
                            model: {
                              value: _vm.form.profileName,
                              callback: function($$v) {
                                _vm.$set(_vm.form, "profileName", $$v)
                              },
                              expression: "form.profileName"
                            }
                          })
                        : _vm._e()
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "w-full mt-5" },
                    [
                      _vm.form.provider
                        ? _c(
                            "text-input",
                            {
                              attrs: {
                                name: _vm.apiKeyLabel.name,
                                label: _vm.apiKeyLabel.label
                              },
                              model: {
                                value: _vm.form[_vm.apiKeyLabel.name],
                                callback: function($$v) {
                                  _vm.$set(_vm.form, _vm.apiKeyLabel.name, $$v)
                                },
                                expression: "form[apiKeyLabel.name]"
                              }
                            },
                            [
                              _c("template", { slot: "help" }, [
                                _c("small", { staticClass: "text-gray-600" }, [
                                  _vm._v(
                                    "\n                                Generate an " +
                                      _vm._s(_vm.apiKeyLabel.label) +
                                      " for\n                                " +
                                      _vm._s(_vm.form.provider) +
                                      " here\n                                "
                                  ),
                                  _c(
                                    "a",
                                    {
                                      staticClass: "ml-1 text-sha-green-500",
                                      attrs: {
                                        href: _vm.apiKeyLabel.link,
                                        target: "_blank"
                                      }
                                    },
                                    [_vm._v(_vm._s(_vm.apiKeyLabel.link))]
                                  )
                                ])
                              ])
                            ],
                            2
                          )
                        : _vm._e()
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
                          loading: _vm.submitting,
                          disabled: _vm.submitting,
                          label: "Add provider"
                        }
                      })
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "card",
            {
              attrs: {
                title: "Active Providers",
                table: true,
                rowsCount: _vm.credentials.length,
                emptyTableMessage: "No providers yet."
              }
            },
            [
              _c("v-table", {
                attrs: { headers: _vm.table.headers, rows: _vm.credentials },
                scopedSlots: _vm._u([
                  {
                    key: "row",
                    fn: function(ref) {
                      var row = ref.row
                      var header = ref.header
                      return [
                        ["profileName", "provider"].includes(header.value)
                          ? _c("span", [
                              _vm._v(
                                "\n                        " +
                                  _vm._s(row[header.value]) +
                                  "\n                    "
                              )
                            ])
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "actions"
                          ? _c(
                              "button",
                              {
                                staticClass:
                                  "border-2 border-red-500 p-2 rounded hover:bg-red-100 shadow",
                                on: {
                                  click: function($event) {
                                    return _vm.setDeletingProvider(row)
                                  }
                                }
                              },
                              [
                                _c(
                                  "svg",
                                  {
                                    staticClass: "cursor-pointer",
                                    attrs: {
                                      width: "20",
                                      height: "20",
                                      fill: "none",
                                      viewBox: "0 0 24 24",
                                      xmlns: "http://www.w3.org/2000/svg"
                                    }
                                  },
                                  [
                                    _c("path", {
                                      attrs: {
                                        d:
                                          "M10 14L12 12M12 12L14 10M12 12L10 10M12 12L14 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z",
                                        stroke: "#f05252",
                                        "stroke-width": "2",
                                        "stroke-linecap": "round",
                                        "stroke-linejoin": "round"
                                      }
                                    })
                                  ]
                                )
                              ]
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
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Account/ServerProvider.vue":
/*!*******************************************************!*\
  !*** ./resources/js/Pages/Account/ServerProvider.vue ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ServerProvider_vue_vue_type_template_id_4ebe7702___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ServerProvider.vue?vue&type=template&id=4ebe7702& */ "./resources/js/Pages/Account/ServerProvider.vue?vue&type=template&id=4ebe7702&");
/* harmony import */ var _ServerProvider_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ServerProvider.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Account/ServerProvider.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ServerProvider_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ServerProvider_vue_vue_type_template_id_4ebe7702___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ServerProvider_vue_vue_type_template_id_4ebe7702___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Account/ServerProvider.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Account/ServerProvider.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/Pages/Account/ServerProvider.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServerProvider_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ServerProvider.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/ServerProvider.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServerProvider_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Account/ServerProvider.vue?vue&type=template&id=4ebe7702&":
/*!**************************************************************************************!*\
  !*** ./resources/js/Pages/Account/ServerProvider.vue?vue&type=template&id=4ebe7702& ***!
  \**************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ServerProvider_vue_vue_type_template_id_4ebe7702___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ServerProvider.vue?vue&type=template&id=4ebe7702& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/ServerProvider.vue?vue&type=template&id=4ebe7702&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ServerProvider_vue_vue_type_template_id_4ebe7702___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ServerProvider_vue_vue_type_template_id_4ebe7702___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);