(window.webpackJsonp=window.webpackJsonp||[]).push([[8],{nvNv:function(t,e,s){"use strict";s.r(e);function r(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,r)}return s}function n(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}var a={data:function(){return{form:{email:"",password:"",remember:null},loading:!1,success:null,errors:{email:[]}}},methods:{submit:function(){var t=this;this.loading=!0,axios.post("/password/email",this.form).then((function(){t.success="You password reset mail has been sent.",t.form={email:""},t.errors={},t.loading=!1})).catch((function(e){var s=e.response;t.loading=!1,422===s.status?t.errors=s.data.errors:t.errors=function(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?r(Object(s),!0).forEach((function(e){n(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):r(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}({},t.errors,{email:[s.data.message||"Failed to send email."]})}))}}},o=s("KHd+"),i=Object(o.a)(a,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"min-h-screen bg-gray-100 flex flex-col justify-center py-12 px-3 sm:px-6 lg:px-8"},[s("div",{staticClass:"sm:mx-auto sm:w-full sm:max-w-md"},[s("img",{staticClass:"mx-auto h-8 w-auto",attrs:{src:"/assets/images/logo.svg",alt:"Workflow"}}),t._v(" "),s("h2",{staticClass:"mt-6 text-center text-3xl leading-9 font-bold text-gray-800"},[t._v("\n            Forgot your password ?\n        ")]),t._v(" "),s("p",{staticClass:"mt-2 text-center text-sm leading-5 text-gray-600 max-w"},[t._v("\n            Or\n            "),s("router-link",{staticClass:"font-medium text-sha-green-500 hover:text-sha-green-400 focus:outline-none focus:underline transition ease-in-out duration-150",attrs:{to:"/auth/login"}},[t._v("\n                sign in to your account\n            ")])],1)]),t._v(" "),s("div",{staticClass:"mt-8 sm:mx-auto sm:w-full sm:max-w-md"},[s("div",{staticClass:"bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10"},[t.success?s("div",{staticClass:"my-3 text-green-500 text-center"},[t._v("\n                "+t._s(t.success)+"\n            ")]):t._e(),t._v(" "),s("form",{attrs:{method:"POST"},on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[s("text-input",{attrs:{name:"email",label:"Email Address",errors:t.errors.email},model:{value:t.form.email,callback:function(e){t.$set(t.form,"email",e)},expression:"form.email"}}),t._v(" "),s("div",{staticClass:"mt-6"},[s("span",{staticClass:"block w-full rounded-md shadow-sm"},[s("v-button",{attrs:{loading:t.loading,type:"submit",label:"Send Password reset link",full:!0}})],1)])],1)])])])}),[],!1,null,null,null);e.default=i.exports}}]);