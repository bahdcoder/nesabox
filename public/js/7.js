(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{bTy8:function(e,t,n){"use strict";n.r(t);var a={data:function(){return{updating:!1,cancellingSubscription:!1,cancelling:!1,plans:[{name:"Free",key:"free",price:0,features:[{name:"1 Server",bold:!0},{name:"Unlimited Sites"},{name:"Unlimited Deployments"},{name:"Push To Deploy"}]},{name:"Pro",key:"pro",price:5,id:parseInt("579858"),features:[{name:"Unlimited Servers",bold:!0},{name:"Unlimited Sites"},{name:"Unlimited Deployments"},{name:"Push To Deploy"}]},{name:"Business",key:"business",price:15,id:parseInt("579859"),features:[{name:"Unlimited Servers"},{name:"Unlimited Sites"},{name:"Unlimited Teams"},{name:"Unlimited Collaboratos",bold:!0}]}]}},computed:{subscription:function(){return this.user.subscription},user:function(){return this.$root.auth}},mounted:function(){window.Paddle.Setup({vendor:parseInt("40489")})},methods:{getPlanLabel:function(e){return"free"===this.subscription.plan?"Upgrade to ".concat(e.name," plan"):"business"===this.subscription.plan?"Downgrade to ".concat(e.name," plan"):"pro"===this.subscription.plan?"free"===e.key?"Downgrade to free plan":"Upgrade to Business plan":void 0},openPaddle:function(e){var t=this;"free"!==e.key?"free"===this.subscription.plan?window.Paddle.Checkout.open({product:e.id,email:this.user.email,successCallback:function(e){t.updating=!0,setTimeout((function(){window.location.href="/account/subscription"}),5e3)}}):this.updatePlan(e):this.toggleCancellingSubscription()},cancelSubscription:function(){var e=this;this.cancelling=!0,axios.delete("/api/subscription/cancel").then((function(t){var n=t.data;e.$root.auth=n,e.$root.flashMessage("Your plan has been cancelled.")})).catch((function(t){var n=t.response;n.data&&n.data.message?e.$root.flashMessage(n.data.message,"error"):e.$root.flashMessage("Failed to cancel subscription.","error")})).finally((function(){e.cancelling=!1,e.toggleCancellingSubscription()}))},toggleCancellingSubscription:function(){this.cancellingSubscription=!this.cancellingSubscription},updatePlan:function(e){var t=this;this.updating=!0,axios.patch("/api/subscription/update",{plan:e.key}).then((function(n){var a=n.data;t.$root.auth=a,t.$root.flashMessage("Subscription has been updated to ".concat(e.key))})).catch((function(e){var n=e.response;n.data&&n.data.message?t.$root.flashMessage(n.data.message,"error"):t.$root.flashMessage("Failed to update subscription plan.","error")})).finally((function(){t.updating=!1}))}}},i=n("KHd+"),s=Object(i.a)(a,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("account-layout",[n("template",{slot:"content"},[n("confirm-modal",{attrs:{confirming:e.cancelling,open:e.cancellingSubscription,confirmHeading:"Cancel subscription",confirmText:"Are you sure you want cancel your subscription ? You would loose all features in the "+e.subscription.plan+" plan"},on:{confirm:e.cancelSubscription,close:e.toggleCancellingSubscription}}),e._v(" "),n("flash"),e._v(" "),n("card",{staticClass:"mb-6",attrs:{title:"Update your nesabox plan"}},[e.updating?n("v-button",{attrs:{label:"Updating subscription",loading:!0}}):n("div",{staticClass:"w-full flex flex-wrap"},e._l(e.plans,(function(t){return n("div",{key:t.key,staticClass:"w-full sm:w-1/2 lg:w-1/3 px-2 mt-4 md:mt-0"},[n("div",{staticClass:"bg-white shadow rounded overflow-hidden"},[n("div",{staticClass:"bg-sha-green-500 text-white px-3 py-3 text-xl"},[n("div",{staticClass:"flex justify-between items-center font-medium"},[n("div",[e._v(e._s(t.name))]),e._v(" "),n("div",[e._v("$"+e._s(t.price)+" / month")])])]),e._v(" "),n("div",{staticClass:"bg-white px-4 py-4"},e._l(t.features,(function(t){return n("div",{key:t.name,staticClass:"mt-4"},[n("div",{staticClass:"flex items-center"},[n("svg",{staticClass:"w-6 h-6 text-sha-green-500",attrs:{fill:"none",stroke:"currentColor","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24"}},[n("path",{attrs:{d:"M5 13l4 4L19 7"}})]),e._v(" "),n("div",{staticClass:"ml-2",class:{"font-medium":t.bold}},[e._v("\n                                        "+e._s(t.name)+"\n                                    ")])])])})),0)]),e._v(" "),e.subscription.plan===t.key?n("p",{staticClass:"mt-4 text-center font-medium text-lg"},[e._v("\n                        Your current plan\n                    ")]):n(e.getPlanLabel(t).match(/Downgrade/)?"red-button":"v-button",{tag:"component",staticClass:"mt-4",attrs:{full:!0,label:e.getPlanLabel(t)},on:{click:function(n){return e.openPaddle(t)}}})],1)})),0)],1)],1)],2)}),[],!1,null,null,null);t.default=s.exports}}]);