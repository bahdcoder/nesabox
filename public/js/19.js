(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[19],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/SshKeys.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/SshKeys.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      form: {
        name: '',
        key: ''
      },
      deleting: false,
      selectedKey: null,
      headers: [{
        label: 'Name',
        value: 'name'
      }, {
        label: 'Status',
        value: 'status'
      }, {
        label: '',
        value: 'actions'
      }],
      wrapper: this.$route.name === 'account.ssh-keys' ? 'account-layout' : 'server-layout'
    };
  },
  computed: {
    keys: function keys() {
      if (this.wrapper === 'account-layout') {
        return this.$root.auth.sshkeys;
      }

      return this.server.sshkeys || [];
    },
    server: function server() {
      return this.$root.servers[this.$route.params.server] || {};
    }
  },
  methods: {
    serverMounted: function serverMounted() {
      this.initializeForm(this.wrapper === 'account-info' ? '/api/me/sshkeys' : "/api/servers/".concat(this.server.id, "/sshkeys"));
    },
    submit: function submit() {
      var _this = this;

      this.submitForm().then(function (user) {
        _this.form = {
          name: '',
          key: ''
        };

        if (_this.wrapper === 'account-info') {
          _this.$root.auth = user;
        } else {
          _this.$root.servers = _objectSpread({}, _this.$root.servers, _defineProperty({}, _this.server.id, user));
        }

        _this.$root.flashMessage('Ssh key saved.');
      })["catch"](function (_ref) {
        var response = _ref.response;

        _this.$root.flashMessage(response.data.message || 'Failed adding key.', 'error');
      });
    },
    deleteKey: function deleteKey() {
      var _this2 = this;

      this.deleting = true;
      axios["delete"](this.wrapper === 'account-info' ? "/api/me/sshkeys/".concat(this.selectedKey.id) : "/api/servers/".concat(this.server.id, "/sshkeys/").concat(this.selectedKey.id)).then(function (_ref2) {
        var user = _ref2.data;

        if (_this2.wrapper === 'account-info') {
          _this2.$root.auth = user;
        } else {
          _this2.$root.servers = _objectSpread({}, _this2.$root.servers, _defineProperty({}, _this2.server.id, user));
        }

        _this2.$root.flashMessage('Ssh key deleted.');
      })["catch"](function (_ref3) {
        var response = _ref3.response;

        _this2.$root.flashMessage(response.data.message || 'Failed deleting SSH key.', 'error');
      })["finally"](function () {
        _this2.selectedKey = null;
        _this2.deleting = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/SshKeys.vue?vue&type=template&id=1a8a7a81&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Account/SshKeys.vue?vue&type=template&id=1a8a7a81& ***!
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
  return _c(
    _vm.wrapper,
    { tag: "component", on: { mounted: _vm.serverMounted } },
    [
      _c(
        "template",
        { slot: "content" },
        [
          _c("flash"),
          _vm._v(" "),
          _c("confirm-modal", {
            attrs: {
              open: !!_vm.selectedKey,
              confirming: _vm.deleting,
              confirmHeading: "Delete SSH key",
              confirmText:
                "Are you sure you want to delete the ssh key " +
                (_vm.selectedKey && _vm.selectedKey.name) +
                "?"
            },
            on: {
              confirm: _vm.deleteKey,
              close: function($event) {
                _vm.selectedKey = null
              }
            }
          }),
          _vm._v(" "),
          _c("card", { staticClass: "mb-6", attrs: { title: "Add SSH key" } }, [
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
                _vm.wrapper === "account-layout"
                  ? _c("info", { staticClass: "mb-5" }, [
                      _vm._v(
                        "\n                    These keys would be added to every new server you\n                    provision.\n                "
                      )
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _c("text-input", {
                  attrs: {
                    name: "name",
                    label: "Name",
                    placeholder: "Macbook-Pro",
                    errors: _vm.formErrors.name,
                    help: "Provide a memorable name for this SSH key."
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
                _c("textarea-input", {
                  staticClass: "mt-6",
                  attrs: {
                    name: "name",
                    label: "Public key",
                    component: "textarea",
                    errors: _vm.formErrors.key,
                    help: "This is the public key of your SSH key pair."
                  },
                  model: {
                    value: _vm.form.key,
                    callback: function($$v) {
                      _vm.$set(_vm.form, "key", $$v)
                    },
                    expression: "form.key"
                  }
                }),
                _vm._v(" "),
                _c("v-button", {
                  staticClass: "mt-5",
                  attrs: {
                    type: "submit",
                    label: "Add key",
                    loading: _vm.submitting
                  }
                })
              ],
              1
            )
          ]),
          _vm._v(" "),
          _c(
            "card",
            {
              attrs: {
                title: "Active SSH Keys",
                table: true,
                rowsCount: _vm.keys.length,
                emptyTableMessage:
                  _vm.wrapper === "account-info"
                    ? "No SSH keys yet."
                    : "No SSH keys added to this server yet."
              }
            },
            [
              _c("v-table", {
                attrs: { headers: _vm.headers, rows: _vm.keys },
                scopedSlots: _vm._u([
                  {
                    key: "row",
                    fn: function(ref) {
                      var row = ref.row
                      var header = ref.header
                      return [
                        header.value === "status"
                          ? _c("table-status", {
                              attrs: { status: row.status }
                            })
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "actions"
                          ? _c("delete-button", {
                              on: {
                                click: function($event) {
                                  _vm.selectedKey = row
                                }
                              }
                            })
                          : _vm._e(),
                        _vm._v(" "),
                        ["name"].includes(header.value)
                          ? _c(
                              "span",
                              { staticClass: "text-gray-800 text-sm" },
                              [
                                _vm._v(
                                  "\n                        " +
                                    _vm._s(row[header.value]) +
                                    "\n                    "
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

/***/ "./resources/js/Pages/Account/SshKeys.vue":
/*!************************************************!*\
  !*** ./resources/js/Pages/Account/SshKeys.vue ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SshKeys_vue_vue_type_template_id_1a8a7a81___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SshKeys.vue?vue&type=template&id=1a8a7a81& */ "./resources/js/Pages/Account/SshKeys.vue?vue&type=template&id=1a8a7a81&");
/* harmony import */ var _SshKeys_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SshKeys.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Account/SshKeys.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SshKeys_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SshKeys_vue_vue_type_template_id_1a8a7a81___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SshKeys_vue_vue_type_template_id_1a8a7a81___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Account/SshKeys.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Account/SshKeys.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/Pages/Account/SshKeys.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SshKeys_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./SshKeys.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/SshKeys.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SshKeys_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Account/SshKeys.vue?vue&type=template&id=1a8a7a81&":
/*!*******************************************************************************!*\
  !*** ./resources/js/Pages/Account/SshKeys.vue?vue&type=template&id=1a8a7a81& ***!
  \*******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SshKeys_vue_vue_type_template_id_1a8a7a81___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./SshKeys.vue?vue&type=template&id=1a8a7a81& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Account/SshKeys.vue?vue&type=template&id=1a8a7a81&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SshKeys_vue_vue_type_template_id_1a8a7a81___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SshKeys_vue_vue_type_template_id_1a8a7a81___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);