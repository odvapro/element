(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d209b0e"],{a9aa:function(t,e,s){"use strict";s.r(e);var n=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"settings-popup-row-params"},[s("p",{staticClass:"el-empty"},[t._v("No settings")]),s("div",{staticClass:"popup__buttons"},[s("button",{staticClass:"el-gbtn",on:{click:function(e){return t.cancel()}}},[t._v("Cancel")]),s("button",{staticClass:"el-btn",on:{click:function(e){return t.save()}}},[t._v("Save settigns")])])])},i=[],a={props:["settings","isRequired"],data:function(){return{required:!1}},methods:{cancel:function(){this.$emit("cancel")},save:function(){this.$emit("save",{})},setStatus:function(t){this.required=t,this.$emit("changeSettings",{required:t})}},mounted:function(){this.required=this.isRequired,this.setStatus(this.required)}},u=a,c=s("2877"),r=Object(c["a"])(u,n,i,!1,null,null,null);e["default"]=r.exports}}]);
//# sourceMappingURL=chunk-2d209b0e.3028240e.js.map