(window.webpackJsonp=window.webpackJsonp||[]).push([[23],{wITW:function(e,t,r){"use strict";r.r(t);function n(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function o(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?n(Object(r),!0).forEach((function(t){i(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):n(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function i(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var s={data:function(){return{deleting:!1,deletingModalOpen:!1}},computed:{site:function(){return this.$root.sites[this.$route.params.site]||{}},serverId:function(){return this.$route.params.server},siteId:function(){return this.$route.params.site}},methods:{deleteSite:function(){var e=this;this.deleting=!0,axios.delete("/api/servers/".concat(this.serverId,"/sites/").concat(this.siteId)).then((function(t){var r=t.data;e.$router.push("/servers/".concat(e.serverId)),e.$root.servers=o(o({},e.$root.server),{},i({},e.serverId,r))})).catch((function(){e.$root.flashMessage("Cannot delete site at the moment. There might be a process running on this server.","error",5e3)})).finally((function(){e.deleting=!1,e.deletingModalOpen=!1}))}}},a=r("KHd+"),c=Object(a.a)(s,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("site-layout",[r("template",{slot:"content"},[r("flash"),e._v(" "),r("confirm-modal",{attrs:{confirming:e.deleting,open:e.deletingModalOpen,confirmHeading:"Delete site",confirmText:"Are you sure you want to delete this site ?"},on:{confirm:e.deleteSite,close:function(t){e.deletingModalOpen=!1}}}),e._v(" "),r("card",{attrs:{title:"Delete site"}},[r("info",[e._v("\n                This will permanently remove all files related to this site\n                from your server.\n            ")]),e._v(" "),r("red-button",{staticClass:"mt-5",attrs:{label:"Delete site"},on:{click:function(t){e.deletingModalOpen=!0}}})],1)],1)],2)}),[],!1,null,null,null);t.default=c.exports}}]);