(window.webpackJsonp=window.webpackJsonp||[]).push([[14],{"3lC/":function(e,a,t){"use strict";t.r(a);function s(e,a){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);a&&(s=s.filter((function(a){return Object.getOwnPropertyDescriptor(e,a).enumerable}))),t.push.apply(t,s)}return t}function r(e){for(var a=1;a<arguments.length;a++){var t=null!=arguments[a]?arguments[a]:{};a%2?s(Object(t),!0).forEach((function(a){n(e,a,t[a])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):s(Object(t)).forEach((function(a){Object.defineProperty(e,a,Object.getOwnPropertyDescriptor(t,a))}))}return e}function n(e,a,t){return a in e?Object.defineProperty(e,a,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[a]=t,e}var o={data:function(){return{form:{name:""},deleteUser:null,addingDatabase:!1,deletingDatabaseUser:!1,errors:{},databasesTable:{headers:[{label:"Name",value:"name"},{label:"Status",value:"status"},{label:"",value:"actions"}]},databasesUsersTable:{headers:[{label:"Name",value:"name"},{label:"Database",value:"database"},{label:"Permission",value:"permission"},{label:"Status",value:"status"},{label:"",value:"actions"}]},addUserForm:{database:"",name:"",password:"",readonly:!1},deletingDatabase:!1,deleteDatabase:null,addingDatabaseUser:!1,databaseUserErrors:{}}},computed:{server:function(){return this.$root.servers[this.$route.params.server]||{}},databases:function(){return this.server&&this.server.id?this.server.database_instances.filter((function(e){return"mongodb"===e.type})).map((function(e){return r({},e,{label:e.name,value:e.id})})):[]},databaseUsers:function(){return this.server&&this.server.id?this.server.database_users_instances.filter((function(e){return"mongodb"===e.type&&0!==e.databases.length})).map((function(e){return r({},e,{label:e.name,value:e.id,database:e.databases[0]?e.databases[0].name:null,permission:e.read_only?"READ":"READ/WRITE"})})):[]}},methods:{deleteDbUser:function(){var e=this;this.deletingDatabaseUser=!0,axios.delete("/api/servers/".concat(this.server.id,"/databases/").concat(this.deleteUser.databases[0].id,"/mongodb/delete-users/").concat(this.deleteUser.id)).then((function(a){var t=a.data;e.$root.servers=r({},e.$root.servers,n({},t.id,t)),e.$root.flashMessage("Database user has been queued for deleting.")})).catch((function(a){var t=a.response;e.$root.flashMessage(t.data.message||"Failed to delete database user.","error")})).finally((function(){e.deletingDatabaseUser=!1,e.deleteUser=null}))},closeConfirmDeleteDatabaseUser:function(){this.deleteUser=null,this.deletingDatabaseUser=!1},deleteDb:function(){var e=this;this.deletingDatabase=!0,axios.delete("/api/servers/".concat(this.server.id,"/databases/").concat(this.deleteDatabase.id,"/mongodb/delete-databases")).then((function(a){var t=a.data;e.$root.servers=r({},e.$root.servers,n({},t.id,t)),e.$root.flashMessage("Database has been queued for deleting.")})).catch((function(a){var t=a.response;e.$root.flashMessage(t.data.message||"Failed to delete database.","error")})).finally((function(){e.deletingDatabase=!1,e.deleteDatabase=null}))},setDeletingDatabase:function(e){this.deleteDatabase=e},setDeletingDatabaseUser:function(e){this.deleteUser=e},closeConfirmDeleteDatabase:function(){this.deleteDatabase=null,this.deletingDatabase=!1},addDatabase:function(){var e=this;this.addingDatabase=!0,axios.post("/api/servers/".concat(this.server.id,"/databases/mongodb/add"),this.form).then((function(a){var t=a.data;e.$root.servers=r({},e.$root.servers,n({},t.id,t)),e.form={name:""},e.errors={},e.$root.flashMessage("Database has been added successfully.")})).catch((function(a){var t=a.response;422===t.status?e.errors=t.data.errors:e.$root.flashMessage("Failed to add database to server.","error")})).finally((function(){e.addingDatabase=!1}))},addDatabaseUser:function(){var e=this;this.addingDatabaseUser=!0,axios.post("/api/servers/".concat(this.server.id,"/databases/").concat(this.addUserForm.database,"/mongodb/add-users"),this.addUserForm).then((function(a){var t=a.data;e.$root.servers=r({},e.$root.servers,n({},t.id,t)),e.addUserForm={database:"",name:"",password:"",readonly:!1},e.errors={},e.$root.flashMessage("Database user has been queued.")})).catch((function(a){var t=a.response;422===t.status?e.databaseUserErrors=t.data.errors:e.$root.flashMessage("Failed to add database user to server.","error")})).finally((function(){e.addingDatabaseUser=!1}))}}},d=t("KHd+"),l=Object(d.a)(o,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("server-layout",[t("template",{slot:"content"},[t("flash"),e._v(" "),t("confirm-modal",{attrs:{confirming:e.deletingDatabase,open:!!e.deleteDatabase,confirmHeading:"Delete database",confirmText:"Are you sure you want to delete your database "+(e.deleteDatabase&&e.deleteDatabase.name)+" ? All data will be lost, with all users."},on:{confirm:e.deleteDb,close:e.closeConfirmDeleteDatabase}}),e._v(" "),t("confirm-modal",{attrs:{confirming:e.deletingDatabaseUser,open:!!e.deleteUser,confirmHeading:"Delete database user",confirmText:"Are you sure you want to delete your database user "+(e.deleteUser&&e.deleteUser.name)+" ? This user would lose access to this database."},on:{confirm:e.deleteDbUser,close:e.closeConfirmDeleteDatabaseUser}}),e._v(" "),t("card",{staticClass:"mb-6",attrs:{title:"Add Mongodb Database"}},[t("form",{on:{submit:function(a){return a.preventDefault(),e.addDatabase(a)}}},[t("text-input",{attrs:{name:"name",label:"Database name",errors:e.errors.name,help:"When you add a database, you can then add users to that database for authentication."},model:{value:e.form.name,callback:function(a){e.$set(e.form,"name",a)},expression:"form.name"}}),e._v(" "),t("v-button",{staticClass:"mt-4",attrs:{type:"submit",label:"Add database",loading:e.addingDatabase}})],1)]),e._v(" "),t("card",{staticClass:"mb-6",attrs:{title:"Mongodb databases",table:!0,emptyTableMessage:"No databases have been added yet.",rowsCount:e.databases.length}},[t("v-table",{attrs:{headers:e.databasesTable.headers,rows:e.databases},scopedSlots:e._u([{key:"row",fn:function(a){var s=a.row,r=a.header;return["status"===r.value?t("table-status",{attrs:{status:s.status}}):e._e(),e._v(" "),"actions"===r.value?t("delete-button",{on:{click:function(a){return e.setDeletingDatabase(s)}}}):e._e(),e._v(" "),"name"===r.value?t("span",{staticClass:"text-gray-800 text-sm"},[e._v("\n                        "+e._s(s.name)+"\n                    ")]):e._e()]}}])})],1),e._v(" "),t("card",{staticClass:"mb-6",attrs:{title:"Add Mongodb users"}},[e.databases.length>0?t("form",{on:{submit:function(a){return a.preventDefault(),e.addDatabaseUser(a)}}},[t("select-input",{attrs:{name:"database",label:"Database",options:e.databases,help:"Select the database this user would be stored in. This user would also be able to access the selected database."},model:{value:e.addUserForm.database,callback:function(a){e.$set(e.addUserForm,"database",a)},expression:"addUserForm.database"}}),e._v(" "),t("div",{staticClass:"mt-4"},[t("text-input",{attrs:{name:"name",label:"Name",errors:e.databaseUserErrors.name,help:"This would be the username for the database user."},model:{value:e.addUserForm.name,callback:function(a){e.$set(e.addUserForm,"name",a)},expression:"addUserForm.name"}})],1),e._v(" "),t("div",{staticClass:"mt-4"},[t("text-input",{attrs:{name:"password",label:"Password",errors:e.databaseUserErrors.password,help:"This would be the password for the database user. The password and username would be required to authenticate as this user."},model:{value:e.addUserForm.password,callback:function(a){e.$set(e.addUserForm,"password",a)},expression:"addUserForm.password"}})],1),e._v(" "),t("div",{staticClass:"mt-6"},[t("checkbox",{attrs:{name:"readonly",label:"Readonly",checked:e.addUserForm.readonly,help:"This user would have READ and WRITE access to the selected database. Check this if you want to grant only read access."},on:{input:function(a){e.addUserForm=Object.assign({},e.addUserForm,{readonly:!e.addUserForm.readonly})}}})],1),e._v(" "),t("v-button",{staticClass:"mt-6",attrs:{label:"Add database user",type:"submit",loading:e.addingDatabaseUser}})],1):t("info",[e._v("\n                To add MongoDB users, create a database.\n            ")])],1),e._v(" "),t("card",{attrs:{title:"Mongodb users",table:!0,rowsCount:e.databaseUsers.length,emptyTableMessage:"No database users yet."}},[t("v-table",{attrs:{headers:e.databasesUsersTable.headers,rows:e.databaseUsers},scopedSlots:e._u([{key:"row",fn:function(a){var s=a.row,r=a.header;return["status"===r.value?t("table-status",{attrs:{status:s.status}}):e._e(),e._v(" "),"actions"===r.value?t("delete-button",{on:{click:function(a){return e.setDeletingDatabaseUser(s)}}}):e._e(),e._v(" "),["name","database"].includes(r.value)?t("span",[e._v("\n                        "+e._s(s[r.value])+"\n                    ")]):e._e(),e._v(" "),"permission"===r.value?t("span",{staticClass:"text-xs text-gray-700"},[e._v("\n                        "+e._s(s.permission)+"\n                    ")]):e._e()]}}])})],1)],1)],2)}),[],!1,null,null,null);a.default=l.exports}}]);