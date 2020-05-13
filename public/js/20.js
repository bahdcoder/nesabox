(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[20],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Sites/Single.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Sites/Single.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_codemirror__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-codemirror */ "./node_modules/vue-codemirror/dist/vue-codemirror.js");
/* harmony import */ var vue_codemirror__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_codemirror__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var codemirror_lib_codemirror_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! codemirror/lib/codemirror.css */ "./node_modules/codemirror/lib/codemirror.css");
/* harmony import */ var codemirror_lib_codemirror_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(codemirror_lib_codemirror_css__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var codemirror_theme_lucario_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! codemirror/theme/lucario.css */ "./node_modules/codemirror/theme/lucario.css");
/* harmony import */ var codemirror_theme_lucario_css__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(codemirror_theme_lucario_css__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var codemirror_mode_shell_shell_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! codemirror/mode/shell/shell.js */ "./node_modules/codemirror/mode/shell/shell.js");
/* harmony import */ var codemirror_mode_shell_shell_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(codemirror_mode_shell_shell_js__WEBPACK_IMPORTED_MODULE_3__);
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  components: {
    codemirror: vue_codemirror__WEBPACK_IMPORTED_MODULE_0__["codemirror"]
  },
  data: function data() {
    var codeMirrorOptions = {
      theme: 'lucario',
      tabSize: 4,
      line: true,
      mode: 'shell',
      lineNumbers: true,
      readOnly: true
    };
    return {
      deploying: false,
      deployScript: '',
      savingScript: false,
      quickDeploying: false,
      updatingBalancedServers: false,
      viewLatestDeploymentLogs: false,
      form: {
        provider: '',
        repository: '',
        branch: 'master'
      },
      branch: '',
      balancedServersForm: {
        servers: [],
        port: '80'
      },
      deployScriptCodeMirrorOptions: _objectSpread({}, codeMirrorOptions, {
        readOnly: false
      }),
      codeMirrorOptions: codeMirrorOptions
    };
  },
  computed: {
    site: function site() {
      return this.$root.sites[this.$route.params.site] || {};
    },
    familyServers: function familyServers() {
      if (!this.server || !this.server.id) return [];
      return this.server.family_servers;
    },
    server: function server() {
      return this.$root.servers[this.$route.params.server] || {};
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
    updateBalancedServers: function updateBalancedServers() {
      var _this = this;

      this.updatingBalancedServers = true;
      axios.patch("/api/servers/".concat(this.server.id, "/sites/").concat(this.site.id, "/upstream"), this.balancedServersForm).then(function (_ref) {
        var server = _ref.data;
        _this.$root.servers = _objectSpread({}, _this.$root.servers, _defineProperty({}, server.id, server));

        _this.$root.flashMessage('Balanced servers updated.');
      })["catch"](function () {
        _this.$root.flashMessage('Failed to update balanced servers', 'error');
      })["finally"](function () {
        _this.updatingBalancedServers = false;
      });
    },
    submit: function submit() {
      var _this2 = this;

      this.submitForm().then(function (site) {
        _this2.$root.sites = _objectSpread({}, _this2.$root.sites, _defineProperty({}, _this2.siteId, site));
      });
    },
    selectServer: function selectServer(checked, server) {
      if (checked) {
        this.balancedServersForm = _objectSpread({}, this.balancedServersForm, {
          servers: [].concat(_toConsumableArray(this.balancedServersForm.servers), [server])
        });
      } else {
        this.balancedServersForm = _objectSpread({}, this.balancedServersForm, {
          servers: this.balancedServersForm.servers.filter(function (s) {
            return s !== server;
          })
        });
      }
    },
    copyDeploymentTriggerUrl: function copyDeploymentTriggerUrl() {
      var command = document.getElementById('deployment_trigger_url');
      command.select();
      command.setSelectionRange(0, 99999);
      document.execCommand('copy');
    },
    quickDeploy: function quickDeploy() {
      var _this3 = this;

      var disabling = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      this.quickDeploying = true;
      axios.post("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/push-to-deploy")).then(function (_ref2) {
        var site = _ref2.data;
        _this3.$root.sites = _objectSpread({}, _this3.$root.sites, _defineProperty({}, site.id, site));

        _this3.$root.flashMessage("Quick deploy has been ".concat(disabling ? 'disabled' : 'enabled', " for this site."));
      })["catch"](function (_ref3) {
        var response = _ref3.response;

        _this3.$root.flashMessage(response.data.message || !disabling ? 'Failed to disable push to deploy.' : 'Failed to enable push to deploy. This might be because you have not granted access to this repository organisation.', 'error');
      })["finally"](function () {
        _this3.quickDeploying = false;
      });
    },
    deploy: function deploy() {
      var _this4 = this;

      this.deploying = true;
      axios.post("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/deployments")).then(function (_ref4) {
        var site = _ref4.data;
        _this4.$root.sites = _objectSpread({}, _this4.$root.sites, _defineProperty({}, site.id, site));
        _this4.viewLatestDeploymentLogs = true;
      })["catch"](function () {
        _this4.$root.flashMessage('Failed to trigger deployment.', 'error');
      })["finally"](function () {
        _this4.deploying = false;
      });
    },
    siteMounted: function siteMounted() {
      this.branch = this.site.repository_branch;
      this.deployScript = this.site.before_deploy_script;
      this.viewLatestDeploymentLogs = this.site.deploying;
      this.balancedServersForm = _objectSpread({}, this.balancedServersForm, {
        port: this.site.balanced_servers.length > 0 ? this.site.balanced_servers[0].port : '80',
        servers: this.site.balanced_servers.map(function (server) {
          return server.balanced_server_id;
        })
      });
    },
    saveScript: function saveScript() {
      var _this5 = this;

      this.savingScript = true;
      axios.put("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId), {
        before_deploy_script: this.deployScript
      }).then(function (_ref5) {
        var site = _ref5.data;

        _this5.$root.flashMessage('Deploy script saved.');

        _this5.$root.sites = _objectSpread({}, _this5.$root.sites, _defineProperty({}, _this5.siteId, site));
      })["finally"](function () {
        _this5.savingScript = false;
      });
    },
    toggleViewLatestDeploymentLogs: function toggleViewLatestDeploymentLogs() {
      this.viewLatestDeploymentLogs = !this.viewLatestDeploymentLogs;
    }
  },
  mounted: function mounted() {
    this.initializeForm("/api/servers/".concat(this.serverId, "/sites/").concat(this.siteId, "/install-repository"));

    if (this.repoOptions.length === 1) {
      this.form = _objectSpread({}, this.form, {
        provider: this.repoOptions[0].value
      });
    }
  },
  watch: {
    site: function site(_site) {
      this.deployScript = _site.before_deploy_script;
      this.viewLatestDeploymentLogs = _site.deploying;
    }
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
    { on: { mounted: _vm.siteMounted } },
    [
      _c(
        "template",
        { slot: "content" },
        [
          _c("notifications", {
            attrs: {
              notifications: _vm.server ? _vm.server.unread_notifications : []
            }
          }),
          _vm._v(" "),
          _c("flash"),
          _vm._v(" "),
          _vm.server.type !== "load_balancer" && _vm.site.installing_repository
            ? _c("card", { attrs: { title: "Installing repository" } }, [
                _c(
                  "div",
                  {
                    staticClass:
                      "w-full border border-blue-500 bg-blue-100 flex items-center rounded text-blue-900 px-2 py-3 text-sm"
                  },
                  [
                    _vm._v(
                      "\n                Installing repository\n                "
                    ),
                    _c("spinner", { staticClass: "ml-3 text-blue-800 w-4 h-4" })
                  ],
                  1
                )
              ])
            : _vm._e(),
          _vm._v(" "),
          _vm.server.type !== "load_balancer" && !_vm.site.repository
            ? _c(
                "card",
                { attrs: { title: "Install Repository" } },
                [
                  !_vm.site.repository &&
                  _vm.repoOptions.length > 0 &&
                  !_vm.site.installing_repository
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
                                  loading: _vm.submitting,
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
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.repoOptions.length === 0 &&
                  !_vm.site.installing_repository
                    ? _c(
                        "router-link",
                        {
                          staticClass:
                            "w-full border border-blue-500 bg-blue-100 flex items-center rounded text-blue-900 px-2 py-3 text-sm",
                          attrs: { to: "/account/source-control" }
                        },
                        [
                          _vm._v(
                            "\n                You have not configured any git repository providers yet. To\n                setup a site, connect your git repository provider here.\n            "
                          )
                        ]
                      )
                    : _vm._e()
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.server.type !== "load_balancer" &&
          _vm.site.repository &&
          !_vm.site.installing_repository
            ? _c(
                "div",
                [
                  _c(
                    "card",
                    { staticClass: "mb-6", attrs: { title: "Deployment" } },
                    [
                      _c("template", { slot: "header" }, [
                        _c(
                          "div",
                          { staticClass: "flex justify-between items-center" },
                          [
                            _c(
                              "h3",
                              {
                                staticClass:
                                  "text-lg leading-6 font-medium text-gray-900 capitalize"
                              },
                              [
                                _vm._v(
                                  "\n                            Deployment\n                        "
                                )
                              ]
                            ),
                            _vm._v(" "),
                            _c(
                              "v-button",
                              {
                                attrs: {
                                  loading: _vm.site.deploying,
                                  label: "Deploy Now"
                                },
                                on: { click: _vm.deploy }
                              },
                              [
                                _c(
                                  "template",
                                  { slot: "loader" },
                                  [
                                    _c("pulse", { staticClass: "py-1 mr-3" }),
                                    _vm._v(" "),
                                    _c("span", [_vm._v("Deploying ")])
                                  ],
                                  1
                                )
                              ],
                              2
                            )
                          ],
                          1
                        )
                      ]),
                      _vm._v(" "),
                      _c("info", [
                        _vm._v(
                          "\n                    If you enable Push to deploy, this site would\n                    automatically be deployed when you push (or merge) to\n                    the " +
                            _vm._s(_vm.site.repository_branch) +
                            " branch of this\n                    repository.\n                "
                        )
                      ]),
                      _vm._v(" "),
                      _c(
                        "div",
                        {
                          staticClass:
                            "flex flex-wrap items-center justify-between mt-5"
                        },
                        [
                          _vm.site.push_to_deploy
                            ? _c("red-button", {
                                attrs: {
                                  loading: _vm.quickDeploying,
                                  label: "Disable push to deploy"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.quickDeploy(true)
                                  }
                                }
                              })
                            : _c("v-button", {
                                attrs: {
                                  loading: _vm.quickDeploying,
                                  label: "Enable push to deploy"
                                },
                                on: { click: _vm.quickDeploy }
                              }),
                          _vm._v(" "),
                          _vm.site.latest_deployment || _vm.site.deploying
                            ? _c(
                                "span",
                                {
                                  staticClass:
                                    "text-sha-green-500 cursor-pointer hover:text-sha-green-400 transition ease-in-out duration-50 mt-3 md:mt-0",
                                  on: {
                                    click: _vm.toggleViewLatestDeploymentLogs
                                  }
                                },
                                [
                                  _vm._v(
                                    "\n                        " +
                                      _vm._s(
                                        _vm.viewLatestDeploymentLogs
                                          ? "Hide"
                                          : "View"
                                      ) +
                                      "\n                        latest deployment logs\n                    "
                                  )
                                ]
                              )
                            : _vm._e()
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _vm.viewLatestDeploymentLogs &&
                      (_vm.site.latest_deployment || _vm.site.deploying)
                        ? _c(
                            "div",
                            { staticClass: "mt-3" },
                            [
                              _vm.site.latest_deployment
                                ? _c("codemirror", {
                                    class: {
                                      "remove-bottom-border-radius":
                                        _vm.site.deploying
                                    },
                                    attrs: { options: _vm.codeMirrorOptions },
                                    model: {
                                      value: _vm.site.latest_deployment.log,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.site.latest_deployment,
                                          "log",
                                          $$v
                                        )
                                      },
                                      expression: "site.latest_deployment.log"
                                    }
                                  })
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.site.deploying
                                ? _c(
                                    "div",
                                    {
                                      staticClass:
                                        "w-full h-6 flex justify-center rounded-b",
                                      staticStyle: { background: "#2b3e50" }
                                    },
                                    [_c("pulse")],
                                    1
                                  )
                                : _vm._e()
                            ],
                            1
                          )
                        : _vm._e()
                    ],
                    2
                  ),
                  _vm._v(" "),
                  _c(
                    "card",
                    { staticClass: "mb-6", attrs: { title: "Deploy script" } },
                    [
                      _c("codemirror", {
                        attrs: { options: _vm.deployScriptCodeMirrorOptions },
                        model: {
                          value: _vm.deployScript,
                          callback: function($$v) {
                            _vm.deployScript = $$v
                          },
                          expression: "deployScript"
                        }
                      }),
                      _vm._v(" "),
                      _c("v-button", {
                        staticClass: "mt-4",
                        attrs: {
                          label: "Save script",
                          loading: _vm.savingScript
                        },
                        on: { click: _vm.saveScript }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "card",
                    { attrs: { title: "Deployment trigger url" } },
                    [
                      _c("div", { staticClass: "text-sm text-gray-800" }, [
                        _vm._v(
                          "\n                    Creating a slack bot to ease your deployments ? Or using\n                    a service like Circle CI and want to trigger deployments\n                    after all tests pass ? Make a GET or POST request to\n                    this endpoint to trigger a deployment.\n                "
                        )
                      ]),
                      _vm._v(" "),
                      _c("text-input", {
                        staticClass: "text-xs mt-5",
                        attrs: {
                          name: "deployment_trigger_url",
                          readonly: "",
                          value: _vm.site.deployment_trigger_url
                        }
                      }),
                      _vm._v(" "),
                      _c("v-button", {
                        staticClass: "mt-4",
                        attrs: { label: "Copy to Clipboard" },
                        on: { click: _vm.copyDeploymentTriggerUrl }
                      })
                    ],
                    1
                  )
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.server.type === "load_balancer"
            ? _c(
                "card",
                { attrs: { title: "Balancing servers" } },
                [
                  _c("info", [
                    _vm._v(
                      "\n                Below is a list of all of the servers this load balancer\n                will distribute traffic to. Only servers in the same region\n                as the load balancer are shown here.\n            "
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
                        [_vm._v("Balanced servers:")]
                      ),
                      _vm._v(" "),
                      _c("small", { staticClass: "text-gray-600" }, [
                        _vm._v(
                          "Select all the servers this load balancer would\n                    distribute traffic to:"
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
                            checked: _vm.balancedServersForm.servers.includes(
                              server.id
                            )
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
                    staticClass: "mt-5",
                    attrs: {
                      name: "port",
                      label: "Port",
                      help:
                        "By default, the load balancer will direct traffic to port 80. You can change the default port here."
                    },
                    model: {
                      value: _vm.balancedServersForm.port,
                      callback: function($$v) {
                        _vm.$set(_vm.balancedServersForm, "port", $$v)
                      },
                      expression: "balancedServersForm.port"
                    }
                  }),
                  _vm._v(" "),
                  _c("v-button", {
                    staticClass: "mt-4",
                    attrs: {
                      label: "Update balanced servers",
                      disabled: _vm.familyServers.length === 0,
                      loading: _vm.updatingBalancedServers
                    },
                    on: { click: _vm.updateBalancedServers }
                  })
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