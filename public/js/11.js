(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{DUoa:function(e,t,s){"use strict";s.r(t);var r={data:function(){return{loading:!0,showOnlyOwnServers:!1,table:{headers:[{label:"Name",value:"name"},{label:"IP Address",value:"ip_address"},{label:"Status",value:"status"},{label:"Type",value:"type"}]}}},computed:{servers:function(){return this.showOnlyOwnServers?this.$root.allServers.servers:this.$root.allServers.servers.concat(this.$root.allServers.team_servers)}},mounted:function(){var e=this;axios.get("/api/servers").then((function(t){var s=t.data;e.loading=!1,e.$root.allServers=s}))},methods:{routeToServer:function(e){this.$router.push("/servers/".concat(e.id))},toggleShowOwnServers:function(){this.showOnlyOwnServers=!this.showOnlyOwnServers}}},a=s("KHd+"),l=Object(a.a)(r,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("layout",[s("div",{staticClass:"bg-white px-4 py-5 border-b border-gray-200 sm:px-6 rounded-t"},[s("div",{staticClass:"-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap"},[s("div",{staticClass:"ml-4 mt-2"},[s("h3",{staticClass:"text-lg leading-6 font-medium text-gray-900"},[e._v("\n                    Servers\n                ")])]),e._v(" "),s("div",{staticClass:"ml-4 mt-2 flex-shrink-0 flex items-center"},[s("span",{staticClass:"inline-flex rounded-md shadow-sm"},[s("v-button",{attrs:{component:"router-link",to:"/servers/create",label:"Add new server"}})],1)])])]),e._v(" "),e.loading||0!==e.servers.length?e._e():s("div",{staticClass:"w-full flex px-6 py-12 justify-center items-center bg-white shadow"},[s("p",[e._v("No servers yet.")])]),e._v(" "),e.loading||0===e.servers.length?e._e():s("div",{staticClass:"w-full bg-white"},[s("v-table",{attrs:{headers:e.table.headers,rows:e.servers},on:{"row-clicked":e.routeToServer},scopedSlots:e._u([{key:"row",fn:function(t){var r=t.row,a=t.header;return["ip_address"===a.value?s("span",{staticClass:"inline-flex text-xs leading-5 font-semibold rounded-full capitalize"},[e._v("\n                    "+e._s(r.ip_address)+"\n                ")]):e._e(),e._v(" "),"type"===a.value?s("span",{staticClass:"inline-flex text-xs leading-5 font-semibold rounded-full capitalize text-gray-700"},[e._v("\n                    "+e._s(r.type.split("_").join(" "))+"\n                ")]):e._e(),e._v(" "),"status"===a.value?s("table-status",{attrs:{status:r.status}}):e._e(),e._v(" "),"name"===a.value?s("div",{staticClass:"flex items-center"},[s("div",{staticClass:"flex-shrink-0 h-6 w-6"},["linode"!==r.provider?s("v-svg",{staticClass:"w-6 h-6",attrs:{icon:r.provider}}):e._e(),e._v(" "),"linode"===r.provider?s("v-svg",{attrs:{icon:r.provider,width:30,height:30}}):e._e()],1),e._v(" "),s("div",{staticClass:"ml-4"},[s("div",{staticClass:"text-sm leading-5 font-medium text-gray-900"},[e._v("\n                            "+e._s(r[a.value])+"\n                        ")])])]):e._e()]}}],null,!1,652408091)})],1)])}),[],!1,null,null,null);t.default=l.exports}}]);