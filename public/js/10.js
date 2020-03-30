(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[10],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Scheduler.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Scheduler.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  mounted: function mounted() {
    this.form = {
      command: '',
      user: 'nesa',
      cron1: '*',
      cron2: '*',
      cron3: '*',
      cron4: '*',
      cron5: '*',
      frequency: 'everyMinute'
    };
    this.initializeForm("/api/servers/".concat(this.$route.params.server, "/cron-jobs"));
  },
  data: function data() {
    return {
      logs: '',
      deletingJob: false,
      selectedJob: null,
      showLogsModal: false,
      showDeleteModal: false,
      tableHeaders: [{
        label: 'Cron',
        value: 'cron'
      }, {
        label: 'User',
        value: 'user'
      }, {
        label: 'Status',
        value: 'status'
      }, {
        label: 'Command',
        value: 'command'
      }, {
        label: '',
        value: 'logs'
      }, {
        label: '',
        value: 'delete'
      }],
      frequencies: [{
        label: 'Every Minute',
        value: 'everyMinute'
      }, {
        label: 'Every Five Minutes',
        value: 'everyFiveMinutes'
      }, {
        label: 'Every Ten Minutes',
        value: 'everyTenMinutes'
      }, {
        label: 'Hourly',
        value: 'hourly'
      }, {
        label: 'Nightly',
        value: 'daily'
      }, {
        label: 'Weekly',
        value: 'weekly'
      }, {
        label: 'Monthly',
        value: 'monthly'
      }, {
        label: 'Custom cron',
        value: 'custom'
      }]
    };
  },
  computed: {
    server: function server() {
      return this.$root.servers[this.$route.params.server] || {};
    },
    jobs: function jobs() {
      return this.server.jobs || [];
    }
  },
  methods: {
    hideLogs: function hideLogs() {
      this.selectedJob = null;
      this.showLogsModal = false;
    },
    serverMounted: function serverMounted() {
      if (this.server.type === 'load_balancer') {
        this.$router.push("/servers/".concat(this.server.id));
      }
    },
    showConfirmDeleteModal: function showConfirmDeleteModal(job) {
      this.showDeleteModal = true;
      this.selectedJob = job;
    },
    showLogs: function showLogs(job) {
      var _this = this;

      this.showLogsModal = true;
      axios.post("/api/servers/".concat(this.server.id, "/cron-jobs/").concat(job.id, "/log")).then(function (_ref) {
        var logs = _ref.data;
        _this.logs = logs;
        _this.selectedJob = job;
      })["catch"](function (_ref2) {
        var response = _ref2.response;
        _this.showLogsModal = false;

        _this.$root.flashMessage(response.data.message || 'Failed fetching logs for job.', 'error');
      });
    },
    submit: function submit() {
      var _this2 = this;

      if (this.form.frequency === 'custom') {
        this.form = _objectSpread({}, this.form, {
          cron: "".concat(this.form.cron1).concat(this.form.cron2).concat(this.form.cron3).concat(this.form.cron4).concat(this.form.cron5)
        });
      }

      this.submitForm().then(function (server) {
        _this2.$root.servers = _objectSpread({}, _this2.$root.servers, _defineProperty({}, server.id, server));
        _this2.form = {
          command: '',
          user: '',
          frequency: 'everyMinute'
        };

        _this2.$root.flashMessage('Schedule job added.');
      });
    },
    deleteJob: function deleteJob() {
      var _this3 = this;

      this.deletingJob = true;
      axios["delete"]("/api/servers/".concat(this.server.id, "/cron-jobs/").concat(this.selectedJob.id)).then(function (_ref3) {
        var server = _ref3.data;
        _this3.$root.servers = _objectSpread({}, _this3.$root.servers, _defineProperty({}, server.id, server));

        _this3.$root.flashMessage('Cron job has been deleted.');
      })["catch"](function (_ref4) {
        var response = _ref4.response;

        _this3.$root.flashMessage(response.data.message || 'Failed deleting job.', 'error');
      })["finally"](function () {
        _this3.deletingJob = false;
        _this3.selectedJob = null;
        _this3.showDeleteModal = false;
      });
    },
    closeConfirmDeleteJob: function closeConfirmDeleteJob() {
      this.deletingJob = false;
      this.showDeleteModal = false;
      this.selectedJob = null;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Scheduler.vue?vue&type=template&id=232b02fa&":
/*!***************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Scheduler.vue?vue&type=template&id=232b02fa& ***!
  \***************************************************************************************************************************************************************************************************************/
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
              open: _vm.showDeleteModal,
              confirming: _vm.deletingJob,
              confirmHeading: "Delete scheduled job",
              confirmText:
                "Are you sure you want to delete the scheduled job " +
                (_vm.selectedJob && _vm.selectedJob.command) +
                " ?"
            },
            on: { confirm: _vm.deleteJob, close: _vm.closeConfirmDeleteJob }
          }),
          _vm._v(" "),
          _c("modal", { attrs: { open: _vm.showLogsModal } }, [
            _c("div", { staticClass: "p-6" }, [
              _vm.selectedJob
                ? _c(
                    "div",
                    [
                      _c("h2", { staticClass: "text-lg mb-3 text-gray-800" }, [
                        _vm._v(
                          "\n                        Job logs (" +
                            _vm._s(_vm.selectedJob && _vm.selectedJob.slug) +
                            ")\n                    "
                        )
                      ]),
                      _vm._v(" "),
                      _c("v-codemirror", { attrs: { value: _vm.logs } })
                    ],
                    1
                  )
                : _c(
                    "div",
                    {
                      staticClass:
                        "w-full h-12 flex justify-center items-center rounded",
                      staticStyle: { background: "#2b3e50" }
                    },
                    [_c("pulse")],
                    1
                  ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "w-full md:flex md:justify-end" },
                [
                  _c("v-trans-button", {
                    staticClass: "mt-4",
                    attrs: { label: "Close" },
                    on: { click: _vm.hideLogs }
                  })
                ],
                1
              )
            ])
          ]),
          _vm._v(" "),
          _c(
            "card",
            { staticClass: "mb-6", attrs: { title: "New Scheduled Job" } },
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
                      name: "command",
                      label: "Command",
                      errors: _vm.formErrors.command
                    },
                    model: {
                      value: _vm.form.command,
                      callback: function($$v) {
                        _vm.$set(_vm.form, "command", $$v)
                      },
                      expression: "form.command"
                    }
                  }),
                  _vm._v(" "),
                  _c("text-input", {
                    staticClass: "mt-5",
                    attrs: {
                      name: "user",
                      label: "User",
                      errors: _vm.formErrors.user,
                      help:
                        "This is the user the cron job would be executed with."
                    },
                    model: {
                      value: _vm.form.user,
                      callback: function($$v) {
                        _vm.$set(_vm.form, "user", $$v)
                      },
                      expression: "form.user"
                    }
                  }),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "mt-4" },
                    [
                      _c("v-radio", {
                        attrs: {
                          id: "frequency",
                          label: "Frequency",
                          options: _vm.frequencies,
                          errors: _vm.formErrors.frequency
                        },
                        model: {
                          value: _vm.form.frequency,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "frequency", $$v)
                          },
                          expression: "form.frequency"
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          attrs: {
                            target: "_blank",
                            href: "https://crontab.guru/"
                          }
                        },
                        [
                          _vm.form.frequency === "custom"
                            ? _c("info", { staticClass: "mt-4" }, [
                                _c("div", [
                                  _vm._v(
                                    "\n                                You can generate a valid cron expression\n                                using\n                                "
                                  ),
                                  _c(
                                    "span",
                                    { staticClass: "ml-1 font-semibold" },
                                    [_vm._v("this tool")]
                                  )
                                ])
                              ])
                            : _vm._e()
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _vm.form.frequency === "custom"
                        ? _c(
                            "div",
                            { staticClass: "grid grid-cols-5 gap-4 mt-3" },
                            [
                              _c("text-input", {
                                staticClass: "text-center",
                                attrs: { name: "cron-1" },
                                model: {
                                  value: _vm.form.cron1,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "cron1", $$v)
                                  },
                                  expression: "form.cron1"
                                }
                              }),
                              _vm._v(" "),
                              _c("text-input", {
                                staticClass: "text-center",
                                attrs: { name: "cron-2" },
                                model: {
                                  value: _vm.form.cron2,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "cron2", $$v)
                                  },
                                  expression: "form.cron2"
                                }
                              }),
                              _vm._v(" "),
                              _c("text-input", {
                                staticClass: "text-center",
                                attrs: { name: "cron-3" },
                                model: {
                                  value: _vm.form.cron3,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "cron3", $$v)
                                  },
                                  expression: "form.cron3"
                                }
                              }),
                              _vm._v(" "),
                              _c("text-input", {
                                staticClass: "text-center",
                                attrs: { name: "cron-4" },
                                model: {
                                  value: _vm.form.cron4,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "cron4", $$v)
                                  },
                                  expression: "form.cron4"
                                }
                              }),
                              _vm._v(" "),
                              _c("text-input", {
                                staticClass: "text-center",
                                attrs: { name: "cron-5" },
                                model: {
                                  value: _vm.form.cron5,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "cron5", $$v)
                                  },
                                  expression: "form.cron5"
                                }
                              })
                            ],
                            1
                          )
                        : _vm._e()
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("v-button", {
                    staticClass: "mt-5",
                    attrs: {
                      type: "submit",
                      label: "Schedule Job",
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
                title: "Scheduled jobs",
                table: true,
                rowsCount: _vm.jobs.length,
                emptyTableMessage:
                  "No scheduled jobs running on this server yet."
              }
            },
            [
              _c("v-table", {
                attrs: { headers: _vm.tableHeaders, rows: _vm.jobs },
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
                        header.value === "delete"
                          ? _c("delete-button", {
                              on: {
                                click: function($event) {
                                  return _vm.showConfirmDeleteModal(row)
                                }
                              }
                            })
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "logs"
                          ? _c(
                              "button",
                              {
                                staticClass:
                                  "border-2 border-gray-500 p-1 rounded hover:bg-gray-100 shadow",
                                on: {
                                  click: function($event) {
                                    return _vm.showLogs(row)
                                  }
                                }
                              },
                              [
                                _c(
                                  "svg",
                                  {
                                    attrs: {
                                      fill: "none",
                                      stroke: "currentColor",
                                      "stroke-linecap": "round",
                                      "stroke-linejoin": "round",
                                      "stroke-width": "2",
                                      viewBox: "0 0 24 24",
                                      width: "20",
                                      height: "20"
                                    }
                                  },
                                  [
                                    _c("path", {
                                      attrs: {
                                        d:
                                          "M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                      }
                                    })
                                  ]
                                )
                              ]
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        ["slug", "user", "cron", "frequency"].includes(
                          header.value
                        )
                          ? _c(
                              "span",
                              { staticClass: "text-gray-700 text-sm" },
                              [
                                _vm._v(
                                  "\n                        " +
                                    _vm._s(row[header.value]) +
                                    "\n                    "
                                )
                              ]
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "command"
                          ? _c("code", { staticClass: "text-red-400" }, [
                              _vm._v(
                                "\n                        " +
                                  _vm._s(row.command) +
                                  "\n                    "
                              )
                            ])
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

/***/ "./resources/js/Pages/Servers/Scheduler.vue":
/*!**************************************************!*\
  !*** ./resources/js/Pages/Servers/Scheduler.vue ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Scheduler_vue_vue_type_template_id_232b02fa___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Scheduler.vue?vue&type=template&id=232b02fa& */ "./resources/js/Pages/Servers/Scheduler.vue?vue&type=template&id=232b02fa&");
/* harmony import */ var _Scheduler_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Scheduler.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Servers/Scheduler.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Scheduler_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Scheduler_vue_vue_type_template_id_232b02fa___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Scheduler_vue_vue_type_template_id_232b02fa___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Servers/Scheduler.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Servers/Scheduler.vue?vue&type=script&lang=js&":
/*!***************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Scheduler.vue?vue&type=script&lang=js& ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Scheduler_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Scheduler.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Scheduler.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Scheduler_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Servers/Scheduler.vue?vue&type=template&id=232b02fa&":
/*!*********************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Scheduler.vue?vue&type=template&id=232b02fa& ***!
  \*********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Scheduler_vue_vue_type_template_id_232b02fa___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Scheduler.vue?vue&type=template&id=232b02fa& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Scheduler.vue?vue&type=template&id=232b02fa&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Scheduler_vue_vue_type_template_id_232b02fa___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Scheduler_vue_vue_type_template_id_232b02fa___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);