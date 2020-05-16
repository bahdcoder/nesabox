(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{"1PdU":function(t,e,s){"use strict";s.r(e);var r={data:function(){return{sending:!1,form:{email:"",password:"",remember:null},errors:{email:[]},loading:!1,github_login_url:window.github_login_url}},methods:{submit:function(){var t=this;this.loading=!0,axios.post("/login",this.form).then((function(){window.location.href="/dashboard"})).catch((function(e){var s=e.response;t.loading=!1,t.errors=s.data.errors}))}}},a=s("KHd+"),i=Object(a.a)(r,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"min-h-screen bg-gray-100 flex flex-col justify-center py-12 px-3 sm:px-6 lg:px-8"},[s("div",{staticClass:"sm:mx-auto sm:w-full sm:max-w-md"},[s("img",{staticClass:"mx-auto h-8 w-auto",attrs:{src:"/assets/images/logo.svg",alt:"Workflow"}}),t._v(" "),s("h2",{staticClass:"mt-6 text-center text-3xl leading-9 font-bold text-gray-800"},[t._v("\n            Sign in to your account\n        ")]),t._v(" "),s("p",{staticClass:"mt-2 text-center text-sm leading-5 text-gray-600 max-w"},[t._v("\n            Or\n            "),s("router-link",{staticClass:"font-medium text-sha-green-500 hover:text-sha-green-400 focus:outline-none focus:underline transition ease-in-out duration-150",attrs:{to:"/auth/register"}},[t._v("\n                sign up for a free account\n            ")])],1)]),t._v(" "),s("div",{staticClass:"mt-8 sm:mx-auto sm:w-full sm:max-w-md"},[s("div",{staticClass:"bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10"},[s("form",{attrs:{method:"POST"},on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[s("text-input",{attrs:{name:"email",label:"Email Address",errors:t.errors.email},model:{value:t.form.email,callback:function(e){t.$set(t.form,"email",e)},expression:"form.email"}}),t._v(" "),s("text-input",{staticClass:"mt-6",attrs:{name:"password",type:"password",label:"Password",errors:t.errors.password},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}}),t._v(" "),s("div",{staticClass:"mt-6 flex items-center justify-between"},[s("div",{staticClass:"flex items-center"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.form.remember,expression:"form.remember"}],staticClass:"form-checkbox h-4 w-4 text-sha-green-500 transition duration-150 ease-in-out",attrs:{type:"checkbox",id:"remember_me"},domProps:{checked:Array.isArray(t.form.remember)?t._i(t.form.remember,null)>-1:t.form.remember},on:{change:function(e){var s=t.form.remember,r=e.target,a=!!r.checked;if(Array.isArray(s)){var i=t._i(s,null);r.checked?i<0&&t.$set(t.form,"remember",s.concat([null])):i>-1&&t.$set(t.form,"remember",s.slice(0,i).concat(s.slice(i+1)))}else t.$set(t.form,"remember",a)}}}),t._v(" "),s("label",{staticClass:"ml-2 block text-sm leading-5 text-gray-900",attrs:{for:"remember_me"}},[t._v("\n                            Remember me\n                        ")])]),t._v(" "),s("div",{staticClass:"text-sm leading-5"},[s("router-link",{staticClass:"font-medium text-sha-green-500 hover:text-sha-green-600 focus:outline-none focus:underline transition ease-in-out duration-150",attrs:{to:"/auth/forgot-password"}},[t._v("\n                            Forgot your password?\n                        ")])],1)]),t._v(" "),s("div",{staticClass:"mt-6"},[s("span",{staticClass:"block w-full rounded-md shadow-sm"},[s("v-button",{attrs:{loading:t.loading,type:"submit",label:"Sign in",full:!0}})],1)])],1),t._v(" "),s("div",{staticClass:"mt-6"},[t._m(0),t._v(" "),s("div",{staticClass:"mt-6 flex justify-center w-full"},[s("div",{staticClass:"w-full md:w-1/3"},[s("span",{staticClass:"w-full inline-flex rounded-md shadow-sm"},[s("a",{staticClass:"w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out",attrs:{type:"button",href:t.github_login_url}},[s("svg",{staticClass:"h-5 h-5",attrs:{fill:"currentColor",viewBox:"0 0 20 20"}},[s("path",{attrs:{"fill-rule":"evenodd",d:"M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z","clip-rule":"evenodd"}})])])])])])])])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"relative"},[e("div",{staticClass:"absolute inset-0 flex items-center"},[e("div",{staticClass:"w-full border-t border-gray-300"})]),this._v(" "),e("div",{staticClass:"relative flex justify-center text-sm leading-5"},[e("span",{staticClass:"px-2 bg-white text-gray-500"},[this._v("\n                            Or continue with\n                        ")])])])}],!1,null,null,null);e.default=i.exports}}]);