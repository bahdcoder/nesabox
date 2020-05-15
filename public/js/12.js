(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[12],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Create.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Create.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(n); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  beforeRouteEnter: function beforeRouteEnter(to, from, next) {
    next(function (vm) {
      if (!vm.$root.auth.can_create_more_servers) {
        vm.$router.push('/account/subscription');
      }
    });
  },
  data: function data() {
    return {
      errors: {},
      loading: false,
      created: false,
      deployCommand: '',
      databases: [{
        label: 'Mongo DB v4.2',
        value: 'mongodb'
      }, {
        label: 'MySQL v5.7',
        value: 'mysql'
      }, {
        label: 'MySQL v8.0',
        value: 'mysql8'
      }, {
        label: 'MariaDB v10.13',
        value: 'mariadb'
      }, {
        label: 'Postgresql v11',
        value: 'postgresql'
      }],
      regionsAndSizes: {
        'digital-ocean': [],
        linode: [],
        vultr: []
      },
      serverTypes: [{
        label: 'Default',
        value: 'default'
      }, {
        label: 'Load balancer',
        value: 'load_balancer'
      }, {
        label: 'Database',
        value: 'database'
      }],
      providers: [{
        icon: 'digital-ocean',
        name: 'Digital Ocean'
      }, {
        icon: 'linode',
        name: 'Linode'
      }, {
        icon: 'vultr',
        name: 'Vultr'
      }, {
        icon: 'custom',
        width: 60,
        height: 60,
        name: 'Custom Provider'
      }],
      form: {
        name: '',
        provider: '',
        credential_id: '',
        databases: []
      }
    };
  },
  computed: {
    regions: function regions() {
      if (!this.form.provider) return [];

      if (this.form.provider === 'custom') {
        return [];
      }

      return this.regionsAndSizes[this.form.provider].regions;
    },
    sizes: function sizes() {
      if (!this.form.provider) return [];

      if (this.form.provider === 'custom') {
        return [];
      }

      return this.regionsAndSizes[this.form.provider].sizes;
    },
    credentials: function credentials() {
      if (!this.form.provider) return [];

      if (this.form.provider === 'custom') {
        return [];
      }

      return (this.$root.auth.providers[this.form.provider] || []).map(function (credential) {
        return {
          label: credential.profileName,
          value: credential.id
        };
      });
    },
    showServerName: function showServerName() {
      if (!this.form.provider) {
        return false;
      }

      if (this.form.provider !== 'custom' && !this.form.credential_id) {
        return false;
      }

      if (this.form.provider === 'custom') {
        return true;
      }

      return true;
    }
  },
  methods: {
    create: function create() {
      var _this = this;

      this.loading = true;
      axios.post('/api/servers', this.form).then(function (_ref) {
        var data = _ref.data;

        if (_this.form.provider === 'custom') {
          _this.created = true;
          _this.deployCommand = data.deploy_command;
        } else {
          _this.$router.push('/dashboard');
        }
      })["catch"](function (_ref2) {
        var response = _ref2.response;

        if (response.status === 422) {
          _this.errors = response.data.errors;
        }

        _this.$root.flashMessage(response.data.message || _this.provider !== 'custom' ? 'Failed to create your server. Please check your provider to make sure you can create servers. Also, check to see if your API token credentials are still valid.' : 'Failed to create server.', 'error', 8000);
      })["finally"](function () {
        _this.loading = false;
      });
    },
    setProvider: function setProvider(provider) {
      this.form.provider = provider.icon;
      this.form.credential_id = '';
    },
    selectDatabase: function selectDatabase(database) {
      var mysqlDatabases = ['mysql', 'mysql8', 'mariadb'];

      var databases = _toConsumableArray(this.form.databases);

      if (databases.includes(database.value)) {
        databases = databases.filter(function (db) {
          return db !== database.value;
        });
      } else {
        if (mysqlDatabases.includes(database.value)) {
          databases = databases.filter(function (db) {
            return !mysqlDatabases.includes(db);
          });
        }

        databases = [].concat(_toConsumableArray(databases), [database.value]);
      }

      this.form = _objectSpread({}, this.form, {
        databases: databases
      });
    },
    copyCommand: function copyCommand() {
      var command = document.getElementById('command');
      command.select();
      command.setSelectionRange(0, 99999);
      document.execCommand('copy');
    }
  },
  mounted: function mounted() {
    var _this2 = this;

    return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
      var _yield$axios$get, data;

      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              _context.next = 2;
              return axios.get('/api/servers/regions');

            case 2:
              _yield$axios$get = _context.sent;
              data = _yield$axios$get.data;
              _this2.regionsAndSizes = data;

            case 5:
            case "end":
              return _context.stop();
          }
        }
      }, _callee);
    }))();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Create.vue?vue&type=template&id=c88c5a38&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Create.vue?vue&type=template&id=c88c5a38& ***!
  \************************************************************************************************************************************************************************************************************/
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
    !_vm.created
      ? _c("div", [
          _c("h2", { staticClass: "mb-4 font-semibold text-2xl" }, [
            _vm._v("Create server")
          ]),
          _vm._v(" "),
          _c(
            "form",
            {
              staticClass: "w-full",
              attrs: { method: "POST" },
              on: {
                submit: function($event) {
                  $event.preventDefault()
                  return _vm.create($event)
                }
              }
            },
            [
              _c(
                "div",
                { staticClass: "shadow sm:rounded-md sm:overflow-hidden" },
                [
                  _c(
                    "div",
                    {
                      staticClass: "px-4 md:px-12 py-5 md:py-12 bg-white sm:p-6"
                    },
                    [
                      _c("div", { staticClass: "mb-4" }, [
                        _c(
                          "label",
                          { staticClass: "block w-full font-semibold" },
                          [_vm._v("Server provider")]
                        ),
                        _vm._v(" "),
                        _c(
                          "small",
                          { staticClass: "text-gray-600 inline-block" },
                          [
                            _vm._v(
                              "Select your server provider. Nesabox will\n                            connect using your provider's API and provision\n                            a server with the specs you select. If you have\n                            already provisioned a server on any provider,\n                            select custom provider. Nesabox will connect to\n                            your already provisioned server.\n                        "
                            )
                          ]
                        )
                      ]),
                      _vm._v(" "),
                      _c(
                        "div",
                        {
                          staticClass: "grid grid-cols-1 md:grid-cols-4 gap-4"
                        },
                        _vm._l(_vm.providers, function(provider) {
                          return _c(
                            "div",
                            {
                              key: provider.icon,
                              staticClass:
                                "py-8 rounded-sm flex items-center flex-col justify-center w-full cursor-pointer border-gray-300 hover:border-sha-green-500",
                              class: {
                                "border-2 border-sha-green-500":
                                  _vm.form.provider === provider.icon,
                                "border opacity-50":
                                  _vm.form.provider !== provider.icon
                              },
                              on: {
                                click: function($event) {
                                  return _vm.setProvider(provider)
                                }
                              }
                            },
                            [
                              _c("v-svg", {
                                attrs: {
                                  icon: provider.icon,
                                  width: provider.width,
                                  height: provider.height
                                }
                              }),
                              _vm._v(" "),
                              _c("p", { staticClass: "mt-3" }, [
                                _vm._v(
                                  "\n                                " +
                                    _vm._s(provider.name) +
                                    "\n                            "
                                )
                              ])
                            ],
                            1
                          )
                        }),
                        0
                      ),
                      _vm._v(" "),
                      _vm.form.provider
                        ? _c("hr", { staticClass: "my-6 md:my-12" })
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.form.provider && _vm.form.provider !== "custom"
                        ? _c(
                            "div",
                            { staticClass: "w-full mt-5" },
                            [
                              _c(
                                "select-input",
                                {
                                  attrs: {
                                    name: "credential",
                                    options: _vm.credentials,
                                    label: "Provider Credential"
                                  },
                                  model: {
                                    value: _vm.form.credential_id,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "credential_id", $$v)
                                    },
                                    expression: "form.credential_id"
                                  }
                                },
                                [
                                  _c("template", { slot: "help" }, [
                                    _c(
                                      "small",
                                      { staticClass: "text-gray-600" },
                                      [
                                        _vm._v(
                                          "\n                                    Select the " +
                                            _vm._s(_vm.form.provider) +
                                            " api key\n                                    to be used to create this server.\n                                    "
                                        ),
                                        _vm.credentials.length === 0
                                          ? _c(
                                              "span",
                                              [
                                                _vm._v(
                                                  "\n                                        You do not have any\n                                        " +
                                                    _vm._s(_vm.form.provider) +
                                                    " credentials yet.\n                                        "
                                                ),
                                                _c(
                                                  "router-link",
                                                  {
                                                    staticClass:
                                                      "rounded text-white bg-sha-green-500 p-1 text-xs px-2",
                                                    attrs: {
                                                      to:
                                                        "/account/server-providers"
                                                    }
                                                  },
                                                  [_vm._v("Add one here")]
                                                )
                                              ],
                                              1
                                            )
                                          : _vm._e()
                                      ]
                                    )
                                  ])
                                ],
                                2
                              )
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.showServerName
                        ? _c(
                            "div",
                            { staticClass: "w-full mt-8" },
                            [
                              _c("text-input", {
                                attrs: {
                                  name: "name",
                                  label: "Server name",
                                  errors: _vm.errors.name,
                                  placeholder: "exasperant-sand-dunes-093",
                                  help:
                                    "Choose a memorable name that helps you easily find this server. This could be the name of your project."
                                },
                                model: {
                                  value: _vm.form.name,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "name", $$v)
                                  },
                                  expression: "form.name"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.showServerName
                        ? _c(
                            "div",
                            { staticClass: "w-full mt-8" },
                            [
                              _c("select-input", {
                                attrs: {
                                  name: "type",
                                  label: "Server type",
                                  errors: _vm.errors.type,
                                  options: _vm.serverTypes,
                                  help:
                                    "The default installs everything you need to run sites on a server. The load balancer provisions only nginx, optimizes it for load balancing, with no additional software."
                                },
                                model: {
                                  value: _vm.form.type,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "type", $$v)
                                  },
                                  expression: "form.type"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.showServerName && _vm.form.provider !== "custom"
                        ? _c(
                            "div",
                            { staticClass: "w-full mt-8" },
                            [
                              _c("select-input", {
                                attrs: {
                                  name: "region",
                                  label: "Region",
                                  options: _vm.regions,
                                  errors: _vm.errors.region,
                                  help:
                                    "Select the region / data center where this server should be provisioned. If you are horizontally scaling, make sure you select the same region for all your resources."
                                },
                                model: {
                                  value: _vm.form.region,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "region", $$v)
                                  },
                                  expression: "form.region"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.showServerName && _vm.form.provider !== "custom"
                        ? _c(
                            "div",
                            { staticClass: "w-full mt-8" },
                            [
                              _c("select-input", {
                                attrs: {
                                  name: "size",
                                  label: "Size",
                                  options: _vm.sizes,
                                  errors: _vm.errors.size,
                                  help:
                                    "Select the size of this server. RAM, GB and vCPUs."
                                },
                                model: {
                                  value: _vm.form.size,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "size", $$v)
                                  },
                                  expression: "form.size"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.showServerName && _vm.form.provider === "custom"
                        ? _c(
                            "div",
                            { staticClass: "w-full mt-8" },
                            [
                              _c("text-input", {
                                attrs: {
                                  name: "size",
                                  label: "Size",
                                  type: "number",
                                  placeholder: "4",
                                  errors: _vm.errors.size,
                                  help:
                                    "Provide the RAM of your server in GB. It'll be used to set the SWAP size."
                                },
                                model: {
                                  value: _vm.form.size,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "size", $$v)
                                  },
                                  expression: "form.size"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.showServerName && _vm.form.provider === "custom"
                        ? _c(
                            "div",
                            { staticClass: "w-full mt-8" },
                            [
                              _c("text-input", {
                                attrs: {
                                  name: "region",
                                  label: "Region",
                                  errors: _vm.errors.region,
                                  placeholder: "New York 1",
                                  help:
                                    "Provide the region of your custom server. This can help you identify the location of the server in future."
                                },
                                model: {
                                  value: _vm.form.region,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "region", $$v)
                                  },
                                  expression: "form.region"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.showServerName && _vm.form.provider === "custom"
                        ? _c(
                            "div",
                            { staticClass: "w-full mt-8" },
                            [
                              _c("text-input", {
                                attrs: {
                                  name: "ip_address",
                                  label: "IP Address",
                                  placeholder: "196.50.6.1",
                                  errors: _vm.errors.ip_address,
                                  help:
                                    "Provide the public IPv4 address of your custom server. We'll use this so we can connect to your server."
                                },
                                model: {
                                  value: _vm.form.ip_address,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "ip_address", $$v)
                                  },
                                  expression: "form.ip_address"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.showServerName && _vm.form.provider === "custom"
                        ? _c(
                            "div",
                            { staticClass: "w-full mt-8" },
                            [
                              _c("text-input", {
                                attrs: {
                                  name: "private_ip_address",
                                  placeholder: "196.50.6.1",
                                  label: "Private IP Address",
                                  errors: _vm.errors.private_ip_address,
                                  help:
                                    "Provide the private IPv4 address of your custom server. This is optional, and is useful if you are setting up a network of servers."
                                },
                                model: {
                                  value: _vm.form.private_ip_address,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.form,
                                      "private_ip_address",
                                      $$v
                                    )
                                  },
                                  expression: "form.private_ip_address"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.form.type && _vm.form.type !== "load_balancer"
                        ? _c("hr", { staticClass: "my-6 md:my-12" })
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.form.type && _vm.form.type !== "load_balancer"
                        ? _c("div", { staticClass: "w-full mt-8" }, [
                            _c(
                              "h3",
                              {
                                staticClass:
                                  "leading-6 font-semibold text-gray-900"
                              },
                              [
                                _vm._v(
                                  "\n                            Databases\n                        "
                                )
                              ]
                            ),
                            _vm._v(" "),
                            _c("small", { staticClass: "mt-1 text-gray-500" }, [
                              _vm._v(
                                "\n                            Check the databases you need installed on this\n                            server.\n                        "
                              )
                            ]),
                            _vm._v(" "),
                            _c(
                              "div",
                              { staticClass: "mt-4" },
                              [
                                _c(
                                  "fieldset",
                                  _vm._l(_vm.databases, function(database) {
                                    return _c(
                                      "div",
                                      {
                                        key: database.value,
                                        staticClass: "mt-4"
                                      },
                                      [
                                        _c(
                                          "div",
                                          {
                                            staticClass:
                                              "relative flex items-start"
                                          },
                                          [
                                            _c(
                                              "div",
                                              {
                                                staticClass:
                                                  "absolute flex items-center h-5"
                                              },
                                              [
                                                _c("input", {
                                                  staticClass:
                                                    "form-checkbox h-4 w-4 text-sha-green-600 transition duration-150 ease-in-out",
                                                  attrs: {
                                                    type: "checkbox",
                                                    id: database.value
                                                  },
                                                  domProps: {
                                                    checked: _vm.form.databases.includes(
                                                      database.value
                                                    )
                                                  },
                                                  on: {
                                                    change: function($event) {
                                                      return _vm.selectDatabase(
                                                        database
                                                      )
                                                    }
                                                  }
                                                })
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "div",
                                              {
                                                staticClass:
                                                  "pl-7 text-sm leading-5"
                                              },
                                              [
                                                _c(
                                                  "label",
                                                  {
                                                    staticClass:
                                                      "font-medium text-gray-700",
                                                    attrs: {
                                                      for: database.value
                                                    }
                                                  },
                                                  [
                                                    _vm._v(
                                                      "\n                                                " +
                                                        _vm._s(database.label) +
                                                        "\n                                            "
                                                    )
                                                  ]
                                                )
                                              ]
                                            )
                                          ]
                                        )
                                      ]
                                    )
                                  }),
                                  0
                                ),
                                _vm._v(" "),
                                _c("flash", { staticClass: "my-4" })
                              ],
                              1
                            )
                          ])
                        : _vm._e()
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    {
                      staticClass:
                        "flex flex-wrap flex-wrap-reverse md:block px-4 py-3 bg-gray-50 text-right sm:px-6"
                    },
                    [
                      _c(
                        "router-link",
                        {
                          staticClass:
                            "w-full md:w-auto inline-flex rounded-md shadow-sm",
                          attrs: { to: "/dashboard" }
                        },
                        [
                          _c(
                            "button",
                            {
                              staticClass:
                                "w-full py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out",
                              attrs: { type: "button" }
                            },
                            [
                              _vm._v(
                                "\n                            Cancel\n                        "
                              )
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "span",
                        {
                          staticClass:
                            "mb-3 md:mb-0 w-full md:w-auto inline-flex rounded-md shadow-sm"
                        },
                        [
                          _c("v-button", {
                            attrs: {
                              type: "submit",
                              disabled: _vm.loading,
                              loading: _vm.loading,
                              label: "Deploy Server"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                ]
              )
            ]
          )
        ])
      : _vm._e(),
    _vm._v(" "),
    _vm.created && _vm.form.provider === "custom"
      ? _c("div", [
          _c("h2", { staticClass: "mb-4 font-semibold text-2xl" }, [
            _vm._v("Create server")
          ]),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "shadow sm:rounded-md sm:overflow-hidden" },
            [
              _c(
                "div",
                { staticClass: "p-5 bg-white" },
                [
                  _c("p", { staticClass: "w-full mb-4 text-gray-900" }, [
                    _vm._v(
                      "\n                    Almost there! Login to your server as root and run the\n                    following command. This would provision your server so\n                    that it can be managed by us. Once done, your server\n                    will become active on this dashboard.\n                "
                    )
                  ]),
                  _vm._v(" "),
                  _c("textarea", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.deployCommand,
                        expression: "deployCommand"
                      }
                    ],
                    staticClass:
                      "w-full bg-gray-100 shadow-sm px-4 py-3 text-xs text-gray-600 border border-gray-200 rounded",
                    attrs: { id: "command", readonly: "" },
                    domProps: { value: _vm.deployCommand },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.deployCommand = $event.target.value
                      }
                    }
                  }),
                  _vm._v(" "),
                  _c("v-button", {
                    staticClass: "mt-4",
                    attrs: { label: "Copy command" },
                    on: { click: _vm.copyCommand }
                  }),
                  _vm._v(" "),
                  _c("v-trans-button", {
                    staticClass: "mt-4",
                    attrs: { label: "Go to dashboard" },
                    on: {
                      click: function($event) {
                        return _vm.$router.push("/dashboard")
                      }
                    }
                  })
                ],
                1
              )
            ]
          )
        ])
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Servers/Create.vue":
/*!***********************************************!*\
  !*** ./resources/js/Pages/Servers/Create.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Create_vue_vue_type_template_id_c88c5a38___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Create.vue?vue&type=template&id=c88c5a38& */ "./resources/js/Pages/Servers/Create.vue?vue&type=template&id=c88c5a38&");
/* harmony import */ var _Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Create.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Servers/Create.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Create_vue_vue_type_template_id_c88c5a38___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Create_vue_vue_type_template_id_c88c5a38___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Servers/Create.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Servers/Create.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Create.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Create.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Create.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Servers/Create.vue?vue&type=template&id=c88c5a38&":
/*!******************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Create.vue?vue&type=template&id=c88c5a38& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_template_id_c88c5a38___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Create.vue?vue&type=template&id=c88c5a38& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Create.vue?vue&type=template&id=c88c5a38&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_template_id_c88c5a38___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_template_id_c88c5a38___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);