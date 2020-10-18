(window.webpackJsonp=window.webpackJsonp||[]).push([[1],{qdBu:function(e,t,r){"use strict";r.r(t);function s(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);t&&(s=s.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,s)}return r}function a(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?s(Object(r),!0).forEach((function(t){o(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):s(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function o(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var n={data:function(){return{form:{name:"",key:""},deleting:!1,selectedKey:null,headers:[{label:"Name",value:"name"},{label:"Status",value:"status"},{label:"",value:"actions"}],wrapper:"account.ssh-keys"===this.$route.name?"account-layout":"server-layout"}},computed:{keys:function(){return"account-layout"===this.wrapper?this.$root.auth.sshkeys:this.server.sshkeys||[]},server:function(){return this.$root.servers[this.$route.params.server]||{}}},methods:{serverMounted:function(){this.initializeForm("/api/servers/".concat(this.server.id,"/sshkeys"))},submit:function(){var e=this;this.submitForm().then((function(t){e.form={name:"",key:""},"account-layout"===e.wrapper?e.$root.auth=t:e.$root.servers=a(a({},e.$root.servers),{},o({},e.server.id,t)),e.$root.flashMessage("Ssh key saved.")})).catch((function(t){var r=t.response;e.$root.flashMessage(r.data.message||"Failed adding key.","error")}))},deleteKey:function(){var e=this;this.deleting=!0,axios.delete("account-layout"===this.wrapper?"/api/me/sshkeys/".concat(this.selectedKey.id):"/api/servers/".concat(this.server.id,"/sshkeys/").concat(this.selectedKey.id)).then((function(t){var r=t.data;"account-layout"===e.wrapper?e.$root.auth=r:e.$root.servers=a(a({},e.$root.servers),{},o({},e.server.id,r)),e.$root.flashMessage("Ssh key deleted.")})).catch((function(t){var r=t.response;e.$root.flashMessage(r.data.message||"Failed deleting SSH key.","error")})).finally((function(){e.selectedKey=null,e.deleting=!1}))}},mounted:function(){"account-layout"===this.wrapper&&this.initializeForm("/api/me/sshkeys")}},i=r("KHd+"),l=Object(i.a)(n,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r(e.wrapper,{tag:"component",on:{mounted:e.serverMounted}},[r("template",{slot:"content"},[r("flash"),e._v(" "),r("confirm-modal",{attrs:{open:!!e.selectedKey,confirming:e.deleting,confirmHeading:"Delete SSH key",confirmText:"Are you sure you want to delete the ssh key "+(e.selectedKey&&e.selectedKey.name)+"?"},on:{confirm:e.deleteKey,close:function(t){e.selectedKey=null}}}),e._v(" "),r("card",{staticClass:"mb-6",attrs:{title:"Add SSH key"}},[r("form",{on:{submit:function(t){return t.preventDefault(),e.submit(t)}}},["account-layout"===e.wrapper?r("info",{staticClass:"mb-5"},[e._v("\n                    These keys would be added to every new server you\n                    provision.\n                ")]):e._e(),e._v(" "),r("text-input",{attrs:{name:"name",label:"Name",placeholder:"Macbook-Pro",errors:e.formErrors.name,help:"Provide a memorable name for this SSH key."},model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}}),e._v(" "),r("textarea-input",{staticClass:"mt-6",attrs:{name:"name",label:"Public key",component:"textarea",errors:e.formErrors.key,help:"This is the public key of your SSH key pair."},model:{value:e.form.key,callback:function(t){e.$set(e.form,"key",t)},expression:"form.key"}}),e._v(" "),r("v-button",{staticClass:"mt-5",attrs:{type:"submit",label:"Add key",loading:e.submitting}})],1)]),e._v(" "),r("card",{attrs:{title:"Active SSH Keys",table:!0,rowsCount:e.keys.length,emptyTableMessage:"account-layout"===e.wrapper?"No SSH keys yet.":"No SSH keys added to this server yet."}},[r("v-table",{attrs:{headers:e.headers,rows:e.keys},scopedSlots:e._u([{key:"row",fn:function(t){var s=t.row,a=t.header;return["status"===a.value?r("table-status",{attrs:{status:s.status}}):e._e(),e._v(" "),"actions"===a.value?r("delete-button",{on:{click:function(t){e.selectedKey=s}}}):e._e(),e._v(" "),["name"].includes(a.value)?r("span",{staticClass:"text-gray-800 text-sm"},[e._v("\n                        "+e._s(s[a.value])+"\n                    ")]):e._e()]}}])})],1)],1)],2)}),[],!1,null,null,null);t.default=l.exports}}]);