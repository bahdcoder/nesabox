(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{Mg50:function(t,n,e){"use strict";e.r(n);function r(t,n){var e=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);n&&(r=r.filter((function(n){return Object.getOwnPropertyDescriptor(t,n).enumerable}))),e.push.apply(e,r)}return e}function o(t){for(var n=1;n<arguments.length;n++){var e=null!=arguments[n]?arguments[n]:{};n%2?r(Object(e),!0).forEach((function(n){c(t,n,e[n])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(e)):r(Object(e)).forEach((function(n){Object.defineProperty(t,n,Object.getOwnPropertyDescriptor(e,n))}))}return t}function c(t,n,e){return n in t?Object.defineProperty(t,n,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[n]=e,t}var i={data:function(){return{urls:{},disconnecting:{github:!1,gitlab:!1}}},computed:{providers:function(){var t=this;return Object.keys(this.urls).map((function(n){return{provider:n,url:t.urls[n],connected:t.$root.auth.source_control[n]}}))}},mounted:function(){var t=this;axios.get("/settings/source-control").then((function(n){var e=n.data;t.urls=e}))},methods:{disconnect:function(t){var n=this;this.disconnecting=o(o({},this.disconnecting),{},c({},t.provider,!0)),axios.post("/api/settings/source-control/".concat(t.provider,"/unlink")).then((function(e){var r=e.data;n.$root.auth=r,n.$root.flashMessage("".concat(t.provider," has been unlinked."))})).catch((function(){n.$root.flashMessage("Failed to unlink ".concat(t.provider,"."),"error")})).finally((function(){n.disconnecting=o(o({},n.disconnecting),{},c({},t.provider,!1))}))},isDisconnecting:function(t){return this.disconnecting[t]}}},s=e("KHd+"),u=Object(s.a)(i,(function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("account-layout",[e("template",{slot:"content"},[e("flash"),t._v(" "),t._l(t.providers,(function(n){return e("card",{key:n.provider,staticClass:"mb-6",attrs:{title:n.provider}},[n.connected?e("red-button",{attrs:{loading:t.isDisconnecting(n.provider),label:"Disconnect "+n.provider},on:{click:function(e){return t.disconnect(n)}}}):e("v-button",{attrs:{component:"a",href:n.url,label:"Connect to "+n.provider}})],1)}))],2)],2)}),[],!1,null,null,null);n.default=u.exports}}]);