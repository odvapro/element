(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0de8c9"],{"85b8":function(e,t,i){"use strict";i.r(t);var n=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"settings-popup-row-params"},[i("div",{staticClass:"popup__field"},[i("div",{staticClass:"popup__field-name"},[e._v("\n\t\t\tRequired\n\t\t")]),i("div",{staticClass:"popup__field-input"},[i("input",{directives:[{name:"model",rawName:"v-model",value:e.required,expression:"required"}],staticClass:"el-inp-noborder",attrs:{type:"text",placeholder:"Enter email"},domProps:{value:e.required},on:{input:function(t){t.target.composing||(e.required=t.target.value)}}})])]),i("div",{staticClass:"popup__field"},[i("div",{staticClass:"popup__field-name"},[e._v("\n\t\t\tChecked status in DB\n\t\t")]),i("div",{staticClass:"popup__field-input"},[i("input",{directives:[{name:"model",rawName:"v-model",value:e.checkedString,expression:"checkedString"}],staticClass:"el-inp-noborder",attrs:{type:"text",placeholder:"Enter checked string"},domProps:{value:e.checkedString},on:{input:function(t){t.target.composing||(e.checkedString=t.target.value)}}})])]),i("div",{staticClass:"popup__field"},[i("div",{staticClass:"popup__field-name"},[e._v("\n\t\t\tUnchecked status in DB\n\t\t")]),i("div",{staticClass:"popup__field-input"},[i("input",{directives:[{name:"model",rawName:"v-model",value:e.uncheckedString,expression:"uncheckedString"}],staticClass:"el-inp-noborder",attrs:{type:"text",placeholder:"Enter unchecked string"},domProps:{value:e.uncheckedString},on:{input:function(t){t.target.composing||(e.uncheckedString=t.target.value)}}})])]),i("div",{staticClass:"popup__buttons"},[i("button",{staticClass:"el-gbtn",on:{click:function(t){return e.cancel()}}},[e._v("Cancel")]),i("button",{staticClass:"el-btn",on:{click:function(t){return e.save()}}},[e._v("Save settigns")])])])},s=[],c={props:["settings","isRequired"],data:function(){return{required:!1,checkedString:"1",uncheckedString:"0"}},methods:{cancel:function(){this.$emit("cancel")},save:function(){var e={required:this.required,checkedString:this.checkedString,uncheckedString:this.uncheckedString};this.$emit("save",e)}},mounted:function(){"undefined"!=typeof this.settings.checkedString&&(this.checkedString=this.settings.checkedString),"undefined"!=typeof this.settings.uncheckedString&&(this.uncheckedString=this.settings.uncheckedString)}},r=c,a=i("2877"),d=Object(a["a"])(r,n,s,!1,null,null,null);t["default"]=d.exports}}]);
//# sourceMappingURL=chunk-2d0de8c9.955f1e9f.js.map