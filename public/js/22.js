(window.webpackJsonp=window.webpackJsonp||[]).push([[22],{ED2U:function(e,t,r){"use strict";r.r(t);var s=r("j5TT");r("p77/"),r("c3yf"),r("AvDn");function o(e){return function(e){if(Array.isArray(e))return i(e)}(e)||function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return i(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);"Object"===r&&e.constructor&&(r=e.constructor.name);if("Map"===r||"Set"===r)return Array.from(r);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return i(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function i(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,s=new Array(t);r<t;r++)s[r]=e[r];return s}function n(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);t&&(s=s.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,s)}return r}function a(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?n(Object(r),!0).forEach((function(t){l(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):n(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function l(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var c={components:{codemirror:s.codemirror},data:function(){var e={theme:"lucario",tabSize:4,line:!0,mode:"shell",lineNumbers:!0,readOnly:!0};return{deploying:!1,deployScript:"",savingScript:!1,quickDeploying:!1,updatingBalancedServers:!1,viewLatestDeploymentLogs:!1,form:{provider:"",repository:"",branch:"master"},branch:"",balancedServersForm:{servers:[],port:"80"},deployScriptCodeMirrorOptions:a({},e,{readOnly:!1}),codeMirrorOptions:e}},computed:{site:function(){return this.$root.sites[this.$route.params.site]||{}},familyServers:function(){return this.server&&this.server.id?this.server.family_servers:[]},server:function(){return this.$root.servers[this.$route.params.server]||{}},serverId:function(){return this.$route.params.server},siteId:function(){return this.$route.params.site},repoOptions:function(){var e=this.$root.auth.source_control;return Object.keys(this.$root.auth.source_control).filter((function(t){return e[t]})).map((function(e){return{label:e,value:e}}))}},methods:{updateBalancedServers:function(){var e=this;this.updatingBalancedServers=!0,axios.patch("/api/servers/".concat(this.server.id,"/sites/").concat(this.site.id,"/upstream"),this.balancedServersForm).then((function(t){var r=t.data;e.$root.servers=a({},e.$root.servers,l({},r.id,r)),e.$root.flashMessage("Balanced servers updated.")})).catch((function(){e.$root.flashMessage("Failed to update balanced servers","error")})).finally((function(){e.updatingBalancedServers=!1}))},submit:function(){var e=this;this.submitForm().then((function(t){e.$root.sites=a({},e.$root.sites,l({},e.siteId,t))}))},selectServer:function(e,t){this.balancedServersForm=a({},this.balancedServersForm,e?{servers:[].concat(o(this.balancedServersForm.servers),[t])}:{servers:this.balancedServersForm.servers.filter((function(e){return e!==t}))})},copyDeploymentTriggerUrl:function(){var e=document.getElementById("deployment_trigger_url");e.select(),e.setSelectionRange(0,99999),document.execCommand("copy")},quickDeploy:function(){var e=this,t=arguments.length>0&&void 0!==arguments[0]&&arguments[0];this.quickDeploying=!0,axios.post("/api/servers/".concat(this.serverId,"/sites/").concat(this.siteId,"/push-to-deploy")).then((function(r){var s=r.data;e.$root.sites=a({},e.$root.sites,l({},s.id,s)),e.$root.flashMessage("Quick deploy has been ".concat(t?"disabled":"enabled"," for this site."))})).catch((function(r){var s=r.response;e.$root.flashMessage(s.data.message||!t?"Failed to disable push to deploy.":"Failed to enable push to deploy. This might be because you have not granted access to this repository organisation.","error")})).finally((function(){e.quickDeploying=!1}))},deploy:function(){var e=this;this.deploying=!0,axios.post("/api/servers/".concat(this.serverId,"/sites/").concat(this.siteId,"/deployments")).then((function(t){var r=t.data;e.$root.sites=a({},e.$root.sites,l({},r.id,r)),e.viewLatestDeploymentLogs=!0})).catch((function(){e.$root.flashMessage("Failed to trigger deployment.","error")})).finally((function(){e.deploying=!1}))},siteMounted:function(){this.branch=this.site.repository_branch,this.deployScript=this.site.before_deploy_script,this.viewLatestDeploymentLogs=this.site.deploying,this.balancedServersForm=a({},this.balancedServersForm,{port:this.site.balanced_servers.length>0?this.site.balanced_servers[0].port:"80",servers:this.site.balanced_servers.map((function(e){return e.balanced_server_id}))})},saveScript:function(){var e=this;this.savingScript=!0,axios.put("/api/servers/".concat(this.serverId,"/sites/").concat(this.siteId),{before_deploy_script:this.deployScript}).then((function(t){var r=t.data;e.$root.flashMessage("Deploy script saved."),e.$root.sites=a({},e.$root.sites,l({},e.siteId,r))})).finally((function(){e.savingScript=!1}))},toggleViewLatestDeploymentLogs:function(){this.viewLatestDeploymentLogs=!this.viewLatestDeploymentLogs}},mounted:function(){this.initializeForm("/api/servers/".concat(this.serverId,"/sites/").concat(this.siteId,"/install-repository")),1===this.repoOptions.length&&(this.form=a({},this.form,{provider:this.repoOptions[0].value}))},watch:{site:function(e){this.deployScript=e.before_deploy_script,this.viewLatestDeploymentLogs=e.deploying}}},p=r("KHd+"),d=Object(p.a)(c,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("site-layout",{on:{mounted:e.siteMounted}},[r("template",{slot:"content"},[r("notifications",{attrs:{notifications:e.server?e.server.unread_notifications:[]}}),e._v(" "),r("flash"),e._v(" "),"load_balancer"!==e.server.type&&e.site.installing_repository?r("card",{attrs:{title:"Installing repository"}},[r("div",{staticClass:"w-full border border-blue-500 bg-blue-100 flex items-center rounded text-blue-900 px-2 py-3 text-sm"},[e._v("\n                Installing repository\n                "),r("spinner",{staticClass:"ml-3 text-blue-800 w-4 h-4"})],1)]):e._e(),e._v(" "),"load_balancer"===e.server.type||e.site.repository?e._e():r("card",{attrs:{title:"Install Repository"}},[!e.site.repository&&e.repoOptions.length>0&&!e.site.installing_repository?r("form",{on:{submit:function(t){return t.preventDefault(),e.submit(t)}}},[r("v-radio",{attrs:{id:"provider",options:e.repoOptions,label:"Provider",errors:e.formErrors.provider},model:{value:e.form.provider,callback:function(t){e.$set(e.form,"provider",t)},expression:"form.provider"}}),e._v(" "),r("div",{staticClass:"w-full mt-5"},[r("text-input",{attrs:{name:"repository",label:"Repository",placeholder:"user/repository",errors:e.formErrors.repository,help:"This should match the path to your repository."},model:{value:e.form.repository,callback:function(t){e.$set(e.form,"repository",t)},expression:"form.repository"}})],1),e._v(" "),r("div",{staticClass:"w-full mt-5"},[r("text-input",{attrs:{name:"branch",label:"Branch",errors:e.formErrors.branch,help:"All deployments would be triggered from this branch."},model:{value:e.form.branch,callback:function(t){e.$set(e.form,"branch",t)},expression:"form.branch"}})],1),e._v(" "),r("div",{staticClass:"flex justify-end w-full w-full mt-5"},[r("v-button",{staticClass:"w-full md:w-1/5",attrs:{type:"submit",loading:e.submitting,disabled:e.submitting,label:"Install repository"}})],1)],1):e._e(),e._v(" "),0!==e.repoOptions.length||e.site.installing_repository?e._e():r("router-link",{staticClass:"w-full border border-blue-500 bg-blue-100 flex items-center rounded text-blue-900 px-2 py-3 text-sm",attrs:{to:"/account/source-control"}},[e._v("\n                You have not configured any git repository providers yet. To\n                setup a site, connect your git repository provider here.\n            ")])],1),e._v(" "),"load_balancer"!==e.server.type&&e.site.repository&&!e.site.installing_repository?r("div",[r("card",{staticClass:"mb-6",attrs:{title:"Deployment"}},[r("template",{slot:"header"},[r("div",{staticClass:"flex justify-between items-center"},[r("h3",{staticClass:"text-lg leading-6 font-medium text-gray-900 capitalize"},[e._v("\n                            Deployment\n                        ")]),e._v(" "),r("v-button",{attrs:{loading:e.site.deploying,label:"Deploy Now"},on:{click:e.deploy}},[r("template",{slot:"loader"},[r("pulse",{staticClass:"py-1 mr-3"}),e._v(" "),r("span",[e._v("Deploying ")])],1)],2)],1)]),e._v(" "),r("info",[e._v("\n                    If you enable Push to deploy, this site would\n                    automatically be deployed when you push (or merge) to\n                    the "+e._s(e.site.repository_branch)+" branch of this\n                    repository.\n                ")]),e._v(" "),r("div",{staticClass:"flex flex-wrap items-center justify-between mt-5"},[e.site.push_to_deploy?r("red-button",{attrs:{loading:e.quickDeploying,label:"Disable push to deploy"},on:{click:function(t){return e.quickDeploy(!0)}}}):r("v-button",{attrs:{loading:e.quickDeploying,label:"Enable push to deploy"},on:{click:e.quickDeploy}}),e._v(" "),e.site.latest_deployment||e.site.deploying?r("span",{staticClass:"text-sha-green-500 cursor-pointer hover:text-sha-green-400 transition ease-in-out duration-50 mt-3 md:mt-0",on:{click:e.toggleViewLatestDeploymentLogs}},[e._v("\n                        "+e._s(e.viewLatestDeploymentLogs?"Hide":"View")+"\n                        latest deployment logs\n                    ")]):e._e()],1),e._v(" "),e.viewLatestDeploymentLogs&&(e.site.latest_deployment||e.site.deploying)?r("div",{staticClass:"mt-3"},[e.site.latest_deployment?r("codemirror",{class:{"remove-bottom-border-radius":e.site.deploying},attrs:{options:e.codeMirrorOptions},model:{value:e.site.latest_deployment.log,callback:function(t){e.$set(e.site.latest_deployment,"log",t)},expression:"site.latest_deployment.log"}}):e._e(),e._v(" "),e.site.deploying?r("div",{staticClass:"w-full h-6 flex justify-center rounded-b",staticStyle:{background:"#2b3e50"}},[r("pulse")],1):e._e()],1):e._e()],2),e._v(" "),r("card",{staticClass:"mb-6",attrs:{title:"Deploy script"}},[r("codemirror",{attrs:{options:e.deployScriptCodeMirrorOptions},model:{value:e.deployScript,callback:function(t){e.deployScript=t},expression:"deployScript"}}),e._v(" "),r("v-button",{staticClass:"mt-4",attrs:{label:"Save script",loading:e.savingScript},on:{click:e.saveScript}})],1),e._v(" "),r("card",{attrs:{title:"Deployment trigger url"}},[r("div",{staticClass:"text-sm text-gray-800"},[e._v("\n                    Creating a slack bot to ease your deployments ? Or using\n                    a service like Circle CI and want to trigger deployments\n                    after all tests pass ? Make a GET or POST request to\n                    this endpoint to trigger a deployment.\n                ")]),e._v(" "),r("text-input",{staticClass:"text-xs mt-5",attrs:{name:"deployment_trigger_url",readonly:"",value:e.site.deployment_trigger_url}}),e._v(" "),r("v-button",{staticClass:"mt-4",attrs:{label:"Copy to Clipboard"},on:{click:e.copyDeploymentTriggerUrl}})],1)],1):e._e(),e._v(" "),"load_balancer"===e.server.type?r("card",{attrs:{title:"Balancing servers"}},[r("info",[e._v("\n                Below is a list of all of the servers this load balancer\n                will distribute traffic to. Only servers in the same region\n                as the load balancer are shown here.\n            ")]),e._v(" "),r("div",{staticClass:"mt-6"},[r("label",{staticClass:"block text-sm font-medium leading-5 text-gray-700",attrs:{for:""}},[e._v("Balanced servers:")]),e._v(" "),r("small",{staticClass:"text-gray-600"},[e._v("Select all the servers this load balancer would\n                    distribute traffic to:")]),e._v(" "),e._l(e.familyServers,(function(t){return r("checkbox",{key:t.id,staticClass:"mt-4",attrs:{name:t.id,label:t.name,checked:e.balancedServersForm.servers.includes(t.id)},on:{input:function(r){return e.selectServer(r,t.id)}}})}))],2),e._v(" "),r("text-input",{staticClass:"mt-5",attrs:{name:"port",label:"Port",help:"By default, the load balancer will direct traffic to port 80. You can change the default port here."},model:{value:e.balancedServersForm.port,callback:function(t){e.$set(e.balancedServersForm,"port",t)},expression:"balancedServersForm.port"}}),e._v(" "),r("v-button",{staticClass:"mt-4",attrs:{label:"Update balanced servers",disabled:0===e.familyServers.length,loading:e.updatingBalancedServers},on:{click:e.updateBalancedServers}})],1):e._e()],1)],2)}),[],!1,null,null,null);t.default=d.exports}}]);