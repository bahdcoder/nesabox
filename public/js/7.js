(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[7],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
        name: ''
      },
      deleteUser: null,
      addingDatabase: false,
      deletingDatabaseUser: false,
      errors: {},
      databasesTable: {
        headers: [{
          label: 'Name',
          value: 'name'
        }, {
          label: 'Status',
          value: 'status'
        }, {
          label: '',
          value: 'actions'
        }]
      },
      databasesUsersTable: {
        headers: [{
          label: 'Name',
          value: 'name'
        }, {
          label: 'Database',
          value: 'database'
        }, {
          label: 'Permission',
          value: 'permission'
        }, {
          label: 'Status',
          value: 'status'
        }, {
          label: '',
          value: 'actions'
        }]
      },
      addUserForm: {
        database: '',
        name: '',
        password: '',
        readonly: false
      },
      deletingDatabase: false,
      deleteDatabase: null,
      addingDatabaseUser: false,
      databaseUserErrors: {}
    };
  },
  computed: {
    server: function server() {
      return this.$root.servers[this.$route.params.server] || {};
    },
    databases: function databases() {
      if (!this.server || !this.server.id) {
        return [];
      }

      return this.server.database_instances.filter(function (db) {
        return db.type === 'mongodb';
      }).map(function (db) {
        return _objectSpread({}, db, {
          label: db.name,
          value: db.id
        });
      });
    },
    databaseUsers: function databaseUsers() {
      if (!this.server || !this.server.id) {
        return [];
      }

      return this.server.database_users_instances.filter(function (db) {
        return db.type === 'mongodb' && db.databases.length !== 0;
      }).map(function (db) {
        return _objectSpread({}, db, {
          label: db.name,
          value: db.id,
          database: db.databases[0] ? db.databases[0].name : null,
          permission: db.read_only ? 'READ' : 'READ/WRITE'
        });
      });
    }
  },
  methods: {
    deleteDbUser: function deleteDbUser() {
      var _this = this;

      this.deletingDatabaseUser = true;
      axios["delete"]("/api/servers/".concat(this.server.id, "/databases/").concat(this.deleteUser.databases[0].id, "/mongodb/delete-users/").concat(this.deleteUser.id)).then(function (_ref) {
        var server = _ref.data;
        _this.$root.servers = _objectSpread({}, _this.$root.servers, _defineProperty({}, server.id, server));

        _this.$root.flashMessage('Database user has been queued for deleting.');
      })["catch"](function (_ref2) {
        var response = _ref2.response;

        _this.$root.flashMessage(response.data.message || 'Failed to delete database user.', 'error');
      })["finally"](function () {
        _this.deletingDatabaseUser = false;
        _this.deleteUser = null;
      });
    },
    closeConfirmDeleteDatabaseUser: function closeConfirmDeleteDatabaseUser() {
      this.deleteUser = null;
      this.deletingDatabaseUser = false;
    },
    deleteDb: function deleteDb() {
      var _this2 = this;

      this.deletingDatabase = true;
      axios["delete"]("/api/servers/".concat(this.server.id, "/databases/").concat(this.deleteDatabase.id, "/mongodb/delete-databases")).then(function (_ref3) {
        var server = _ref3.data;
        _this2.$root.servers = _objectSpread({}, _this2.$root.servers, _defineProperty({}, server.id, server));

        _this2.$root.flashMessage('Database has been queued for deleting.');
      })["catch"](function (_ref4) {
        var response = _ref4.response;

        _this2.$root.flashMessage(response.data.message || 'Failed to delete database.', 'error');
      })["finally"](function () {
        _this2.deletingDatabase = false;
        _this2.deleteDatabase = null;
      });
    },
    setDeletingDatabase: function setDeletingDatabase(database) {
      this.deleteDatabase = database;
    },
    setDeletingDatabaseUser: function setDeletingDatabaseUser(user) {
      this.deleteUser = user;
    },
    closeConfirmDeleteDatabase: function closeConfirmDeleteDatabase() {
      this.deleteDatabase = null;
      this.deletingDatabase = false;
    },
    addDatabase: function addDatabase() {
      var _this3 = this;

      this.addingDatabase = true;
      axios.post("/api/servers/".concat(this.server.id, "/databases/mongodb/add"), this.form).then(function (_ref5) {
        var server = _ref5.data;
        _this3.$root.servers = _objectSpread({}, _this3.$root.servers, _defineProperty({}, server.id, server));
        _this3.form = {
          name: ''
        };
        _this3.errors = {};

        _this3.$root.flashMessage('Database has been added successfully.');
      })["catch"](function (_ref6) {
        var response = _ref6.response;

        if (response.status === 422) {
          _this3.errors = response.data.errors;
        } else {
          _this3.$root.flashMessage('Failed to add database to server.', 'error');
        }
      })["finally"](function () {
        _this3.addingDatabase = false;
      });
    },
    addDatabaseUser: function addDatabaseUser() {
      var _this4 = this;

      this.addingDatabaseUser = true;
      axios.post("/api/servers/".concat(this.server.id, "/databases/").concat(this.addUserForm.database, "/mongodb/add-users"), this.addUserForm).then(function (_ref7) {
        var server = _ref7.data;
        _this4.$root.servers = _objectSpread({}, _this4.$root.servers, _defineProperty({}, server.id, server));
        _this4.addUserForm = {
          database: '',
          name: '',
          password: '',
          readonly: false
        };
        _this4.errors = {};

        _this4.$root.flashMessage('Database user has been queued.');
      })["catch"](function (_ref8) {
        var response = _ref8.response;

        if (response.status === 422) {
          _this4.databaseUserErrors = response.data.errors;
        } else {
          _this4.$root.flashMessage('Failed to add database user to server.', 'error');
        }
      })["finally"](function () {
        _this4.addingDatabaseUser = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=template&id=9cb90d3a&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=template&id=9cb90d3a& ***!
  \***********************************************************************************************************************************************************************************************************************/
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
    [
      _c(
        "template",
        { slot: "content" },
        [
          _c("flash"),
          _vm._v(" "),
          _c("confirm-modal", {
            attrs: {
              confirming: _vm.deletingDatabase,
              open: !!_vm.deleteDatabase,
              confirmHeading: "Delete database",
              confirmText:
                "Are you sure you want to delete your database " +
                (_vm.deleteDatabase && _vm.deleteDatabase.name) +
                " ? All data will be lost, with all users."
            },
            on: { confirm: _vm.deleteDb, close: _vm.closeConfirmDeleteDatabase }
          }),
          _vm._v(" "),
          _c("confirm-modal", {
            attrs: {
              confirming: _vm.deletingDatabaseUser,
              open: !!_vm.deleteUser,
              confirmHeading: "Delete database user",
              confirmText:
                "Are you sure you want to delete your database user " +
                (_vm.deleteUser && _vm.deleteUser.name) +
                " ? This user would lose access to this database."
            },
            on: {
              confirm: _vm.deleteDbUser,
              close: _vm.closeConfirmDeleteDatabaseUser
            }
          }),
          _vm._v(" "),
          _c(
            "card",
            { staticClass: "mb-6", attrs: { title: "Add Mongodb Database" } },
            [
              _c(
                "form",
                {
                  on: {
                    submit: function($event) {
                      $event.preventDefault()
                      return _vm.addDatabase($event)
                    }
                  }
                },
                [
                  _c("text-input", {
                    attrs: {
                      name: "name",
                      label: "Database name",
                      errors: _vm.errors.name,
                      help:
                        "When you add a database, you can then add users to that database for authentication."
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
                  _c("v-button", {
                    staticClass: "mt-4",
                    attrs: {
                      type: "submit",
                      label: "Add database",
                      loading: _vm.addingDatabase
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
              staticClass: "mb-6",
              attrs: {
                title: "Mongodb databases",
                table: true,
                emptyTableMessage: "No databases have been added yet.",
                rowsCount: _vm.databases.length
              }
            },
            [
              _c("v-table", {
                attrs: {
                  headers: _vm.databasesTable.headers,
                  rows: _vm.databases
                },
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
                                  return _vm.setDeletingDatabase(row)
                                }
                              }
                            })
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "name"
                          ? _c(
                              "span",
                              { staticClass: "text-gray-800 text-sm" },
                              [
                                _vm._v(
                                  "\n                        " +
                                    _vm._s(row.name) +
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
          ),
          _vm._v(" "),
          _c(
            "card",
            { staticClass: "mb-6", attrs: { title: "Add Mongodb users" } },
            [
              _vm.databases.length > 0
                ? _c(
                    "form",
                    {
                      on: {
                        submit: function($event) {
                          $event.preventDefault()
                          return _vm.addDatabaseUser($event)
                        }
                      }
                    },
                    [
                      _c("select-input", {
                        attrs: {
                          name: "database",
                          label: "Database",
                          options: _vm.databases,
                          help:
                            "Select the database this user would be stored in. This user would also be able to access the selected database."
                        },
                        model: {
                          value: _vm.addUserForm.database,
                          callback: function($$v) {
                            _vm.$set(_vm.addUserForm, "database", $$v)
                          },
                          expression: "addUserForm.database"
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-4" },
                        [
                          _c("text-input", {
                            attrs: {
                              name: "name",
                              label: "Name",
                              errors: _vm.databaseUserErrors.name,
                              help:
                                "This would be the username for the database user."
                            },
                            model: {
                              value: _vm.addUserForm.name,
                              callback: function($$v) {
                                _vm.$set(_vm.addUserForm, "name", $$v)
                              },
                              expression: "addUserForm.name"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-4" },
                        [
                          _c("text-input", {
                            attrs: {
                              name: "password",
                              label: "Password",
                              errors: _vm.databaseUserErrors.password,
                              help:
                                "This would be the password for the database user. The password and username would be required to authenticate as this user."
                            },
                            model: {
                              value: _vm.addUserForm.password,
                              callback: function($$v) {
                                _vm.$set(_vm.addUserForm, "password", $$v)
                              },
                              expression: "addUserForm.password"
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "mt-6" },
                        [
                          _c("checkbox", {
                            attrs: {
                              name: "readonly",
                              label: "Readonly",
                              checked: _vm.addUserForm.readonly,
                              help:
                                "This user would have READ and WRITE access to the selected database. Check this if you want to grant only read access."
                            },
                            on: {
                              input: function($event) {
                                _vm.addUserForm = Object.assign(
                                  {},
                                  _vm.addUserForm,
                                  { readonly: !_vm.addUserForm.readonly }
                                )
                              }
                            }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c("v-button", {
                        staticClass: "mt-6",
                        attrs: {
                          label: "Add database user",
                          type: "submit",
                          loading: _vm.addingDatabaseUser
                        }
                      })
                    ],
                    1
                  )
                : _c("info", [
                    _vm._v(
                      "\n                To add MongoDB users, create a database.\n            "
                    )
                  ])
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "card",
            {
              attrs: {
                title: "Mongodb users",
                table: true,
                rowsCount: _vm.databaseUsers.length,
                emptyTableMessage: "No database users yet."
              }
            },
            [
              _c("v-table", {
                attrs: {
                  headers: _vm.databasesUsersTable.headers,
                  rows: _vm.databaseUsers
                },
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
                                  return _vm.setDeletingDatabaseUser(row)
                                }
                              }
                            })
                          : _vm._e(),
                        _vm._v(" "),
                        ["name", "database"].includes(header.value)
                          ? _c("span", [
                              _vm._v(
                                "\n                        " +
                                  _vm._s(row[header.value]) +
                                  "\n                    "
                              )
                            ])
                          : _vm._e(),
                        _vm._v(" "),
                        header.value === "permission"
                          ? _c(
                              "span",
                              { staticClass: "text-xs text-gray-700" },
                              [
                                _vm._v(
                                  "\n                        " +
                                    _vm._s(row.permission) +
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

/***/ "./resources/js/Pages/Servers/Databases/Mongodb.vue":
/*!**********************************************************!*\
  !*** ./resources/js/Pages/Servers/Databases/Mongodb.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Mongodb_vue_vue_type_template_id_9cb90d3a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Mongodb.vue?vue&type=template&id=9cb90d3a& */ "./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=template&id=9cb90d3a&");
/* harmony import */ var _Mongodb_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Mongodb.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Mongodb_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Mongodb_vue_vue_type_template_id_9cb90d3a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Mongodb_vue_vue_type_template_id_9cb90d3a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Servers/Databases/Mongodb.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Mongodb_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Mongodb.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Mongodb_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=template&id=9cb90d3a&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=template&id=9cb90d3a& ***!
  \*****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Mongodb_vue_vue_type_template_id_9cb90d3a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Mongodb.vue?vue&type=template&id=9cb90d3a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Servers/Databases/Mongodb.vue?vue&type=template&id=9cb90d3a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Mongodb_vue_vue_type_template_id_9cb90d3a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Mongodb_vue_vue_type_template_id_9cb90d3a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);