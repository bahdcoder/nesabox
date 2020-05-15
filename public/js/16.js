(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[16],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(n); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
        servers: [],
        ports: ''
      },
      deleting: false,
      deletingRule: null,
      headers: [{
        label: 'Name',
        value: 'name'
      }, {
        label: 'Port',
        value: 'port'
      }, {
        label: 'From IP Address',
        value: 'from'
      }, {
        label: 'Status',
        value: 'status'
      }, {
        label: '',
        value: 'actions'
      }],
      firewallForm: {
        name: '',
        from: '',
        port: ''
      },
      addingRule: false,
      errors: {},
      updatingNetwork: false
    };
  },
  computed: {
    server: function server() {
      return this.$root.servers[this.$route.params.server] || {};
    },
    familyServers: function familyServers() {
      if (!this.server || !this.server.id) return [];
      return this.server.family_servers;
    },
    rules: function rules() {
      return this.server.firewall_rules || [];
    }
  },
  methods: {
    closeConfirmDelete: function closeConfirmDelete() {
      this.deleting = false;
      this.deletingRule = null;
    },
    deleteRule: function deleteRule() {
      var _this = this;

      axios["delete"]("/api/servers/".concat(this.server.id, "/firewall-rules/").concat(this.deletingRule.id)).then(function (_ref) {
        var server = _ref.data;
        _this.$root.servers = _objectSpread({}, _this.$root.servers, _defineProperty({}, server.id, server));
      })["catch"](function (_ref2) {
        var response = _ref2.response;

        _this.$root.flashMessage(response.data.message || 'Failed deleting firewall rule.', 'error');
      })["finally"](function () {
        _this.deleting = false;
        _this.deletingRule = null;
      });
    },
    selectServer: function selectServer(checked, server) {
      if (checked) {
        this.form = _objectSpread({}, this.form, {
          servers: [].concat(_toConsumableArray(this.form.servers), [server])
        });
      } else {
        this.form = _objectSpread({}, this.form, {
          servers: this.form.servers.filter(function (s) {
            return s !== server;
          })
        });
      }
    },
    updateNetwork: function updateNetwork() {
      var _this2 = this;

      this.updatingNetwork = true;
      axios.patch("/api/servers/".concat(this.server.id, "/network"), _objectSpread({}, this.form, {
        ports: this.form.ports.split(',')
      })).then(function (_ref3) {
        var server = _ref3.data;

        _this2.$root.flashMessage('Network has been updated.');

        _this2.$root.servers = _objectSpread({}, _this2.$root.servers, _defineProperty({}, server.id, server));
      })["catch"](function (_ref4) {
        var response = _ref4.response;

        _this2.$root.flashMessage(response.data.message || 'Failed updating network.', 'error');
      })["finally"](function () {
        _this2.updatingNetwork = false;
      });
    },
    serverMounted: function serverMounted() {
      this.form = _objectSpread({}, this.form, {
        ports: (this.server.friend_servers || []).length > 0 ? (this.server.friend_servers || [])[0].ports : '',
        servers: (this.server.friend_servers || []).map(function (server) {
          return server.friend_server_id;
        })
      });
    },
    addRule: function addRule() {
      var _this3 = this;

      this.addingRule = true;
      axios.post("/api/servers/".concat(this.server.id, "/firewall-rules"), _objectSpread({}, this.firewallForm, {
        from: this.firewallForm.from.split(',')
      })).then(function (_ref5) {
        var server = _ref5.data;
        _this3.firewallForm = {
          name: '',
          port: '',
          from: ''
        };
        _this3.$root.servers = _objectSpread({}, _this3.$root.servers, _defineProperty({}, server.id, server));
      })["catch"](function (_ref6) {
        var response = _ref6.response;

        if (response.status === 422) {
          _this3.errors = response.data.errors;
          var invalidIpAddresses = false;
          Object.keys(response.data.errors).forEach(function (error) {
            if (error.match(/from/) && error !== 'from') {
              invalidIpAddresses = true;
            }
          });

          if (invalidIpAddresses) {
            _this3.errors = _objectSpread({}, _this3.errors, {
              from: ['Some of the ip addresses are invalid. Please check again.']
            });
          }
        }
      })["finally"](function () {
        _this3.addingRule = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14& ***!
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
    "server-layout",
    { on: { mounted: _vm.serverMounted } },
    [
      _c(
        "template",
        { slot: "content" },
        [
          _c("flash"),
          _vm._v(" "),
          _c("confirm-modal", {
            attrs: {
              confirming: _vm.deleting,
              open: !!_vm.deletingRule,
              confirmHeading: "Delete firewall rule",
              confirmText:
                "Are you sure you want to delete the firewall rule " +
                (_vm.deletingRule && _vm.deletingRule.name) +
                "?"
            },
            on: { confirm: _vm.deleteRule, close: _vm.closeConfirmDelete }
          }),
          _vm._v(" "),
          _vm.server.provider === "digital-ocean"
            ? _c(
                "card",
                { staticClass: "mb-6", attrs: { title: "Server network" } },
                [
                  _c("info", [
                    _vm._v(
                      "\n                Below is a list of all of the other servers that can access\n                this server. You can expose a specific port to a selected\n                list of servers. This is really helpful when using a server\n                as a separate database, cache, or queue worker.\n            "
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "mt-6" },
                    [
                      _c(
                        "label",
                        {
                          staticClass:
                            "block text-sm font-medium leading-5 text-gray-700",
                          attrs: { for: "" }
                        },
                        [_vm._v("Can connect to")]
                      ),
                      _vm._v(" "),
                      _c("small", { staticClass: "text-gray-600" }, [
                        _vm._v(
                          "Select all the servers this server would allow access\n                    to."
                        )
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.familyServers, function(server) {
                        return _c("checkbox", {
                          key: server.id,
                          staticClass: "mt-4",
                          attrs: {
                            name: server.id,
                            label: server.name,
                            checked: _vm.form.servers.includes(server.id)
                          },
                          on: {
                            input: function($event) {
                              return _vm.selectServer($event, server.id)
                            }
                          }
                        })
                      })
                    ],
                    2
                  ),
                  _vm._v(" "),
                  _c("text-input", {
                    staticClass: "mt-4",
                    attrs: {
                      name: "from",
                      label: "Ports",
                      errors: _vm.errors.ports,
                      placeholder: "27017,6379",
                      help:
                        "Provide which ports you want this server to expose. You can add multiple ports separated by commas. For example, if you want this server to be allow access to mongodb and redis, provide ports 27017,6379"
                    },
                    model: {
                      value: _vm.form.ports,
                      callback: function($$v) {
                        _vm.$set(_vm.form, "ports", $$v)
                      },
                      expression: "form.ports"
                    }
                  }),
                  _vm._v(" "),
                  _c("v-button", {
                    staticClass: "mt-4",
                    attrs: {
                      label: "Update network",
                      disabled: _vm.familyServers.length === 0,
                      loading: _vm.updatingNetwork
                    },
                    on: { click: _vm.updateNetwork }
                  })
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _c(
            "card",
            { staticClass: "mb-6", attrs: { title: "New firewall rule" } },
            [
              _c(
                "form",
                {
                  on: {
                    submit: function($event) {
                      $event.preventDefault()
                      return _vm.addRule($event)
                    }
                  }
                },
                [
                  _c("info", [
                    _vm._v(
                      '\n                    If you do not provide a "FROM IP ADDRESS", the specified\n                    port will be open to any IP address on the internet.\n                '
                    )
                  ]),
                  _vm._v(" "),
                  _c("text-input", {
                    staticClass: "mt-4",
                    attrs: {
                      name: "name",
                      label: "Name",
                      errors: _vm.errors.name,
                      placeholder: "Websockets app",
                      help: "Give this firewall rule a memorable name."
                    },
                    model: {
                      value: _vm.firewallForm.name,
                      callback: function($$v) {
                        _vm.$set(_vm.firewallForm, "name", $$v)
                      },
                      expression: "firewallForm.name"
                    }
                  }),
                  _vm._v(" "),
                  _c("text-input", {
                    staticClass: "mt-4",
                    attrs: {
                      name: "port",
                      label: "Port",
                      placeholder: "6001",
                      errors: _vm.errors.port
                    },
                    model: {
                      value: _vm.firewallForm.port,
                      callback: function($$v) {
                        _vm.$set(_vm.firewallForm, "port", $$v)
                      },
                      expression: "firewallForm.port"
                    }
                  }),
                  _vm._v(" "),
                  _c("text-input", {
                    staticClass: "mt-4",
                    attrs: {
                      name: "from",
                      errors: _vm.errors.from,
                      label: "From IP Address",
                      placeholder: "196.50.6.1,196.520.16.31",
                      help:
                        "You can add multiple IP addresses separated by commas"
                    },
                    model: {
                      value: _vm.firewallForm.from,
                      callback: function($$v) {
                        _vm.$set(_vm.firewallForm, "from", $$v)
                      },
                      expression: "firewallForm.from"
                    }
                  }),
                  _vm._v(" "),
                  _c("v-button", {
                    staticClass: "mt-5",
                    attrs: {
                      type: "submit",
                      label: "Add rule",
                      loading: _vm.addingRule
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
                title: "Firewall rules",
                table: true,
                rowsCount: _vm.rules.length,
                emptyTableMessage: "No rules added to this server."
              }
            },
            [
              _c("v-table", {
                attrs: { headers: _vm.headers, rows: _vm.rules },
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
                                  _vm.deletingRule = row
                                }
                              }
                            })
                          : _vm._e(),
                        _vm._v(" "),
                        ["name", "port", "from"].includes(header.value)
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

/***/ "./resources/js/Pages/Servers/Network.vue":
/*!************************************************!*\
  !*** ./resources/js/Pages/Servers/Network.vue ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Network.vue?vue&type=template&id=f03edb14& */ "./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14&");
/* harmony import */ var _Network_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Network.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Network_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Servers/Network.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Network.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Network.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14&":
/*!*******************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14& ***!
  \*******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Network.vue?vue&type=template&id=f03edb14& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Network.vue?vue&type=template&id=f03edb14&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Network_vue_vue_type_template_id_f03edb14___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);