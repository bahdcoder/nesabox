(window.webpackJsonp=window.webpackJsonp||[]).push([[19],{yv8F:function(t,e,s){"use strict";s.r(e);var r=s("j5TT"),o=(s("p77/"),s("c3yf"),s("AvDn"),{components:{codemirror:r.codemirror},data:function(){return{codeMirrorOptions:{theme:"lucario",tabSize:4,line:!0,mode:"shell",lineNumbers:!0,readOnly:!0},fetchingLogs:!0,logs:""}},computed:{site:function(){return this.$root.sites[this.$route.params.site]||{}},server:function(){return this.$root.servers[this.$route.params.server]||{}},serverId:function(){return this.$route.params.server},siteId:function(){return this.$route.params.site}},methods:{fetchLogs:function(){var t=this;axios.get("/api/servers/".concat(this.serverId,"/sites/").concat(this.siteId,"/logs")).then((function(e){var s=e.data;t.fetchingLogs=!1,t.logs=s}))},siteMounted:function(){this.server&&"load_balancer"===this.server.type&&this.$router.push("/servers/".concat(this.server.id))}},mounted:function(){this.fetchLogs()},watch:{site:function(){this.fetchLogs()}}}),i=s("KHd+"),n=Object(i.a)(o,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("site-layout",{on:{mounted:t.siteMounted}},[s("template",{slot:"content"},[s("flash"),t._v(" "),s("card",{attrs:{title:"PM2 Logs"}},[s("info",[t._v("\n                The site logs will be updated in real time.\n            ")]),t._v(" "),s("codemirror",{class:{"remove-bottom-border-radius":t.fetchingLogs,"mt-4":!0},attrs:{options:t.codeMirrorOptions},model:{value:t.logs,callback:function(e){t.logs=e},expression:"logs"}}),t._v(" "),t.fetchingLogs?s("div",{staticClass:"w-full h-6 flex justify-center rounded-b",staticStyle:{background:"#2b3e50"}},[s("pulse")],1):t._e()],1)],1)],2)}),[],!1,null,null,null);e.default=n.exports}}]);