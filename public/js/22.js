(window.webpackJsonp=window.webpackJsonp||[]).push([[22],{mJnk:function(t,e,r){"use strict";r.r(e);function i(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,i)}return r}function n(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}var s={data:function(){return{installing:!1}},computed:{site:function(){return this.$root.sites[this.$route.params.site]||{}},server:function(){return this.$root.servers[this.$route.params.server]||{}},serverId:function(){return this.$route.params.server},siteId:function(){return this.$route.params.site}},methods:{install:function(){var t=this;this.installing=!0,axios.post("/api/servers/".concat(this.serverId,"/sites/").concat(this.siteId,"/lets-encrypt")).then((function(e){var r=e.data;t.$root.sites=function(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?i(Object(r),!0).forEach((function(e){n(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):i(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}({},t.$root.sites,n({},t.siteId,r))})).finally((function(){t.installing=!1}))}}},a=r("KHd+"),o=Object(a.a)(s,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("site-layout",[r("template",{slot:"content"},[r("flash"),t._v(" "),t.site.ssl_certificate_installed?r("card",{attrs:{title:"Ssl Certificate"}},[r("info",[t._v("\n                Ssl certificate for "+t._s(t.site.name)+" is installed and active. It will be automatically renewed.\n            ")])],1):r("card",{attrs:{title:"Ssl certificate"}},[r("info",{staticClass:"mb-3"},[t._v("\n                To obtain a valid Let's Encrypt certificate, make sure your DNS configuration for "+t._s(t.site.name)+" has an A record pointing to "+t._s(t.server.ip_address)+". This would be verified when obtaining the certificate.\n            ")]),t._v(" "),r("v-button",{attrs:{loading:t.installing||t.site.installing_certificate,label:"Install ssl certificate"},on:{click:t.install}})],1)],1)],2)}),[],!1,null,null,null);e.default=o.exports}}]);