(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{nlrc:function(e,t,r){"use strict";r.r(t);function i(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);t&&(i=i.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,i)}return r}function o(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var n={data:function(){return{table:{headers:[{label:"Profile name",value:"profileName"},{label:"Provider",value:"provider"},{label:"",value:"actions"}]},serverOptions:[{label:"Digital Ocean",value:"digital-ocean"},{label:"Linode",value:"linode"},{label:"Vultr",value:"vultr"}],form:{provider:"",profileName:"",accessToken:"",apiKey:"",apiToken:""},deletingProvider:null,deleting:!1}},computed:{credentials:function(){var e=this,t=[];return Object.keys(this.$root.auth.providers).forEach((function(r){t=t.concat(e.$root.auth.providers[r].map((function(e){return function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?i(Object(r),!0).forEach((function(t){o(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):i(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},e,{provider:{"digital-ocean":"Digital Ocean",linode:"Linode",vultr:"Vultr"}[r]})})))})),t},apiKeyLabel:function(){return{"digital-ocean":{label:"API Token",name:"apiToken",link:"https://cloud.digitalocean.com/account/api/tokens"},linode:{label:"Access Token",name:"accessToken",link:"https://cloud.linode.com/profile/tokens"},vultr:{label:"API Key",name:"apiKey",link:"https://my.vultr.com/settings/#settingsapi"}}[this.form.provider]||{}}},mounted:function(){this.initializeForm("/api/settings/server-providers")},methods:{submit:function(){var e=this;this.submitForm().then((function(t){e.form={profileName:"",accessToken:"",apiKey:"",apiToken:""},e.$root.auth=t,e.$root.flashMessage("Provider added successfully. You can now create servers using your new ".concat(e.form.provider," credentials."))}))},setDeletingProvider:function(e){this.deletingProvider=e},deleteProfile:function(){var e=this;this.deleting=!0,axios.delete("/api/settings/server-providers/".concat(this.deletingProvider.id)).then((function(t){var r=t.data;e.$root.flashMessage("Profile has been deleted."),e.$root.auth=r,e.deletingProvider=null,e.deleting=!1})).catch((function(){e.$root.flashMessage("Failed deleting profile."),e.deleting=!1,e.deletingProvider=null}))},closeConfirmDelete:function(){this.deletingProvider=null,this.deleting=!1}}},l=r("KHd+"),a=Object(l.a)(n,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("account-layout",[r("template",{slot:"content"},[r("confirm-modal",{attrs:{confirming:e.deleting,open:!!e.deletingProvider,confirmHeading:"Delete provider profile",confirmText:"Are you sure you want to delete your "+(e.deletingProvider&&e.deletingProvider.profileName)+" profile for "+(e.deletingProvider&&e.deletingProvider.provider)+" ?"},on:{confirm:e.deleteProfile,close:e.closeConfirmDelete}}),e._v(" "),r("card",{staticClass:"mb-5",attrs:{title:"New Server Provider"}},[r("flash",{staticClass:"my-2"}),e._v(" "),r("form",{on:{submit:function(t){return t.preventDefault(),e.submit(t)}}},[r("v-radio",{attrs:{id:"provider",label:"Provider",options:e.serverOptions,errors:e.formErrors.provider},model:{value:e.form.provider,callback:function(t){e.$set(e.form,"provider",t)},expression:"form.provider"}}),e._v(" "),r("div",{staticClass:"w-full mt-5"},[e.form.provider?r("text-input",{attrs:{name:"profileName",label:"Profile name",errors:e.formErrors.profileName,help:"This should be a memorable name to identify the provider api key. It can also be the name of the account."},model:{value:e.form.profileName,callback:function(t){e.$set(e.form,"profileName",t)},expression:"form.profileName"}}):e._e()],1),e._v(" "),r("div",{staticClass:"w-full mt-5"},[e.form.provider?r("text-input",{attrs:{name:e.apiKeyLabel.name,label:e.apiKeyLabel.label},model:{value:e.form[e.apiKeyLabel.name],callback:function(t){e.$set(e.form,e.apiKeyLabel.name,t)},expression:"form[apiKeyLabel.name]"}},[r("template",{slot:"help"},[r("small",{staticClass:"text-gray-600"},[e._v("\n                                Generate an "+e._s(e.apiKeyLabel.label)+" for\n                                "+e._s(e.form.provider)+" here\n                                "),r("a",{staticClass:"ml-1 text-sha-green-500",attrs:{href:e.apiKeyLabel.link,target:"_blank"}},[e._v(e._s(e.apiKeyLabel.link))])])])],2):e._e()],1),e._v(" "),r("div",{staticClass:"flex justify-end w-full w-full mt-5"},[r("v-button",{staticClass:"w-full md:w-1/5",attrs:{type:"submit",loading:e.submitting,disabled:e.submitting,label:"Add provider"}})],1)],1)],1),e._v(" "),r("card",{attrs:{title:"Active Providers",table:!0,rowsCount:e.credentials.length,emptyTableMessage:"No providers yet."}},[r("v-table",{attrs:{headers:e.table.headers,rows:e.credentials},scopedSlots:e._u([{key:"row",fn:function(t){var i=t.row,o=t.header;return[["profileName","provider"].includes(o.value)?r("span",{staticClass:"text-gray-800 text-sm"},[e._v("\n                        "+e._s(i[o.value])+"\n                    ")]):e._e(),e._v(" "),"actions"===o.value?r("delete-button",{on:{click:function(t){return e.setDeletingProvider(i)}}}):e._e()]}}])})],1)],1)],2)}),[],!1,null,null,null);t.default=a.exports}}]);