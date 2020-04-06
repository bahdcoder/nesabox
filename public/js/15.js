(window.webpackJsonp=window.webpackJsonp||[]).push([[15],{fX0U:function(e,r,t){"use strict";t.r(r);function s(e){return function(e){if(Array.isArray(e))return a(e)}(e)||function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}(e)||function(e,r){if(!e)return;if("string"==typeof e)return a(e,r);var t=Object.prototype.toString.call(e).slice(8,-1);"Object"===t&&e.constructor&&(t=e.constructor.name);if("Map"===t||"Set"===t)return Array.from(t);if("Arguments"===t||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t))return a(e,r)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function a(e,r){(null==r||r>e.length)&&(r=e.length);for(var t=0,s=new Array(r);t<r;t++)s[t]=e[t];return s}function o(e,r){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);r&&(s=s.filter((function(r){return Object.getOwnPropertyDescriptor(e,r).enumerable}))),t.push.apply(t,s)}return t}function n(e){for(var r=1;r<arguments.length;r++){var t=null!=arguments[r]?arguments[r]:{};r%2?o(Object(t),!0).forEach((function(r){l(e,r,t[r])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):o(Object(t)).forEach((function(r){Object.defineProperty(e,r,Object.getOwnPropertyDescriptor(t,r))}))}return e}function l(e,r,t){return r in e?Object.defineProperty(e,r,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[r]=t,e}var i={data:function(){return{form:{servers:[],ports:""},deleting:!1,deletingRule:null,headers:[{label:"Name",value:"name"},{label:"Port",value:"port"},{label:"From IP Address",value:"from"},{label:"Status",value:"status"},{label:"",value:"actions"}],firewallForm:{name:"",from:"",port:""},addingRule:!1,errors:{},updatingNetwork:!1}},computed:{server:function(){return this.$root.servers[this.$route.params.server]||{}},familyServers:function(){return this.server&&this.server.id?this.server.family_servers:[]},rules:function(){return this.server.firewall_rules||[]}},methods:{closeConfirmDelete:function(){this.deleting=!1,this.deletingRule=null},deleteRule:function(){var e=this;axios.delete("/api/servers/".concat(this.server.id,"/firewall-rules/").concat(this.deletingRule.id)).then((function(r){var t=r.data;e.$root.servers=n({},e.$root.servers,l({},t.id,t))})).catch((function(r){var t=r.response;e.$root.flashMessage(t.data.message||"Failed deleting firewall rule.","error")})).finally((function(){e.deleting=!1,e.deletingRule=null}))},selectServer:function(e,r){this.form=n({},this.form,e?{servers:[].concat(s(this.form.servers),[r])}:{servers:this.form.servers.filter((function(e){return e!==r}))})},updateNetwork:function(){var e=this;this.updatingNetwork=!0,axios.patch("/api/servers/".concat(this.server.id,"/network"),n({},this.form,{ports:this.form.ports.split(",")})).then((function(r){var t=r.data;e.$root.flashMessage("Network has been updated."),e.$root.servers=n({},e.$root.servers,l({},t.id,t))})).catch((function(r){var t=r.response;e.$root.flashMessage(t.data.message||"Failed updating network.","error")})).finally((function(){e.updatingNetwork=!1}))},serverMounted:function(){this.form=n({},this.form,{ports:(this.server.friend_servers||[]).length>0?(this.server.friend_servers||[])[0].ports:"",servers:(this.server.friend_servers||[]).map((function(e){return e.friend_server_id}))})},addRule:function(){var e=this;this.addingRule=!0,axios.post("/api/servers/".concat(this.server.id,"/firewall-rules"),n({},this.firewallForm,{from:this.firewallForm.from.split(",")})).then((function(r){var t=r.data;e.firewallForm={name:"",port:"",from:""},e.$root.servers=n({},e.$root.servers,l({},t.id,t))})).catch((function(r){var t=r.response;if(422===t.status){e.errors=t.data.errors;var s=!1;Object.keys(t.data.errors).forEach((function(e){e.match(/from/)&&"from"!==e&&(s=!0)})),s&&(e.errors=n({},e.errors,{from:["Some of the ip addresses are invalid. Please check again."]}))}})).finally((function(){e.addingRule=!1}))}}},u=t("KHd+"),c=Object(u.a)(i,(function(){var e=this,r=e.$createElement,t=e._self._c||r;return t("server-layout",{on:{mounted:e.serverMounted}},[t("template",{slot:"content"},[t("flash"),e._v(" "),t("confirm-modal",{attrs:{confirming:e.deleting,open:!!e.deletingRule,confirmHeading:"Delete firewall rule",confirmText:"Are you sure you want to delete the firewall rule "+(e.deletingRule&&e.deletingRule.name)+"?"},on:{confirm:e.deleteRule,close:e.closeConfirmDelete}}),e._v(" "),"digital-ocean"===e.server.provider?t("card",{staticClass:"mb-6",attrs:{title:"Server network"}},[t("info",[e._v("\n                Below is a list of all of the other servers that can access\n                this server. You can expose a specific port to a selected\n                list of servers. This is really helpful when using a server\n                as a separate database, cache, or queue worker.\n            ")]),e._v(" "),t("div",{staticClass:"mt-6"},[t("label",{staticClass:"block text-sm font-medium leading-5 text-gray-700",attrs:{for:""}},[e._v("Can connect to")]),e._v(" "),t("small",{staticClass:"text-gray-600"},[e._v("Select all the servers this server would have access\n                    to.")]),e._v(" "),e._l(e.familyServers,(function(r){return t("checkbox",{key:r.id,staticClass:"mt-4",attrs:{name:r.id,label:r.name,checked:e.form.servers.includes(r.id)},on:{input:function(t){return e.selectServer(t,r.id)}}})}))],2),e._v(" "),t("text-input",{staticClass:"mt-4",attrs:{name:"from",label:"Ports",errors:e.errors.ports,placeholder:"27017,6379",help:"Provide which ports you want this server to have access to. You can add multiple ports separated by commas. For example, if you want this server to be able to access a Mongodb server and Redis server, provide ports 27017,6379"},model:{value:e.form.ports,callback:function(r){e.$set(e.form,"ports",r)},expression:"form.ports"}}),e._v(" "),t("v-button",{staticClass:"mt-4",attrs:{label:"Update network",disabled:0===e.familyServers.length,loading:e.updatingNetwork},on:{click:e.updateNetwork}})],1):e._e(),e._v(" "),t("card",{staticClass:"mb-6",attrs:{title:"New firewall rule"}},[t("form",{on:{submit:function(r){return r.preventDefault(),e.addRule(r)}}},[t("info",[e._v('\n                    If you do not provide a "FROM IP ADDRESS", the specified\n                    port will be open to any IP address on the internet.\n                ')]),e._v(" "),t("text-input",{staticClass:"mt-4",attrs:{name:"name",label:"Name",errors:e.errors.name,placeholder:"Websockets app",help:"Give this firewall rule a memorable name."},model:{value:e.firewallForm.name,callback:function(r){e.$set(e.firewallForm,"name",r)},expression:"firewallForm.name"}}),e._v(" "),t("text-input",{staticClass:"mt-4",attrs:{name:"port",label:"Port",placeholder:"6001",errors:e.errors.port},model:{value:e.firewallForm.port,callback:function(r){e.$set(e.firewallForm,"port",r)},expression:"firewallForm.port"}}),e._v(" "),t("text-input",{staticClass:"mt-4",attrs:{name:"from",errors:e.errors.from,label:"From IP Address",placeholder:"196.50.6.1,196.520.16.31",help:"You can add multiple IP addresses separated by commas"},model:{value:e.firewallForm.from,callback:function(r){e.$set(e.firewallForm,"from",r)},expression:"firewallForm.from"}}),e._v(" "),t("v-button",{staticClass:"mt-5",attrs:{type:"submit",label:"Add rule",loading:e.addingRule}})],1)]),e._v(" "),t("card",{attrs:{title:"Firewall rules",table:!0,rowsCount:e.rules.length,emptyTableMessage:"No rules added to this server."}},[t("v-table",{attrs:{headers:e.headers,rows:e.rules},scopedSlots:e._u([{key:"row",fn:function(r){var s=r.row,a=r.header;return["status"===a.value?t("table-status",{attrs:{status:s.status}}):e._e(),e._v(" "),"actions"===a.value?t("delete-button",{on:{click:function(r){e.deletingRule=s}}}):e._e(),e._v(" "),["name","port","from"].includes(a.value)?t("span",{staticClass:"text-gray-800 text-sm"},[e._v("\n                        "+e._s(s[a.value])+"\n                    ")]):e._e()]}}])})],1)],1)],2)}),[],!1,null,null,null);r.default=c.exports}}]);