(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-7ae42368"],{"21e6":function(e,t,i){"use strict";i.r(t);var a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"em-file-item-col",on:{click:function(t){return e.openPopup()}}},[e._l(e.localValue,function(t){return t.noShow?e._e():i("div",{staticClass:"em-file-item-wrapper"},[i("img",{attrs:{src:"image"==t.type?t.sizes.small.path:"/images/fileicon.png",alt:""}})])}),0==e.countFiels?[i("span",{staticClass:"el-empty"},[e._v("Empty")])]:e._e(),e.showPopup?i("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:e.closePopup,expression:"closePopup"}],staticClass:"em-file__edit"},[e._l(e.localValue,function(t,a){return t.noShow?e._e():i("div",{staticClass:"em-file__edit-item"},[i("img",{staticClass:"em-file__edit-attach",attrs:{src:"image"==t.type?t.sizes.small.path:"/images/fileicon.png",alt:""}}),i("a",{attrs:{href:"javascript:void(0);"},on:{click:function(t){return e.removeFile(a)}}},[e._v("remove")])])}),0==e.countFiels?[e._m(0)]:e._e(),i("button",{staticClass:"el-gbtn",on:{click:function(t){return e.openSubPopup()}}},[e._v("Add file")]),e.showSubPopup?i("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:e.closeSubPopup,expression:"closeSubPopup"}],staticClass:"em-file__upload-popup"},[i("div",{staticClass:"em-file__upload-tab-wrapper"},[i("div",{staticClass:"em-file__upload-tabs-head"},e._l(e.tabs,function(t){return i("div",{staticClass:"em-file__upload-tab-item",class:{active:t.active},on:{click:function(i){return e.setActiveTab(t)}}},[e._v(e._s(t.name))])}),0),i("div",{staticClass:"em-file__upload-tabs-content-wrapper"},["Upload"==e.activeTab?i("div",{staticClass:"em-file__file-tab"},[i("input",{ref:"emFile",staticClass:"em-file",attrs:{type:"file",multiple:"true",name:"file",id:"file"},on:{change:function(t){return e.uploadFile("file")}}}),i("label",{staticClass:"el-btn",attrs:{for:"file"}},[e._v("Choose File")])]):e._e(),"Upload by link"==e.activeTab?i("div",{staticClass:"em-file__link-tab"},[i("input",{directives:[{name:"model",rawName:"v-model",value:e.link,expression:"link"}],staticClass:"el-inp em-file__embed-input",attrs:{type:"text",placeholder:"Paste link"},domProps:{value:e.link},on:{change:function(t){return e.uploadFile("link")},input:function(t){t.target.composing||(e.link=t.target.value)}}}),i("button",{staticClass:"el-btn"},[e._v("Embed Link")])]):e._e()])])]):e._e()],2):e._e()],2)},n=[function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"em-file__empty-pop"},[i("span",{staticClass:"el-empty"},[e._v("No files")])])}],l=(i("7f7f"),i("4917"),i("ac4d"),i("8a81"),i("ac6a"),i("b54a"),i("96cf"),i("3b8d")),s={props:["fieldValue","fieldSettings","mode","view"],data:function(){return{localValue:!1,showPopup:!1,showSubPopup:!1,tabs:[{name:"Upload",active:!0},{name:"Upload by link",active:!1}],activeTab:"Upload",link:""}},computed:{countFiels:function(){var e=0;if(!this.localValue)return e;for(var t in this.localValue)this.localValue[t].noShow||e++;return e},fieldCode:function(){return"undefined"!==typeof this.fieldSettings.fieldCode&&this.fieldSettings.fieldCode},tableCode:function(){return"undefined"!==typeof this.fieldSettings.tableCode&&this.fieldSettings.tableCode}},methods:{uploadFile:function(){var e=Object(l["a"])(regeneratorRuntime.mark(function e(t){var i,a,n,l,s,r,o,u,c,p,f,d,h,v;return regeneratorRuntime.wrap(function(e){while(1)switch(e.prev=e.next){case 0:if(this.localValue||this.$set(this,"localValue",[]),i=new FormData,i.append("tableCode",this.tableCode),i.append("fieldCode",this.fieldCode),i.append("primaryKey",this.fieldSettings.primaryKey.fieldCode),i.append("primaryKeyValue",this.fieldSettings.primaryKey.value),"link"!=t){e.next=11;break}i.append("typeUpload","link"),i.append("link",this.link),e.next=37;break;case 11:if("file"!=t){e.next=36;break}if("undefined"!=typeof this.$refs.emFile&&0!=this.$refs.emFile.length){e.next=14;break}return e.abrupt("return");case 14:for(a=!0,n=!1,l=void 0,e.prev=17,s=this.$refs.emFile.files[Symbol.iterator]();!(a=(r=s.next()).done);a=!0)o=r.value,i.append("".concat(this.fieldCode,"[]"),o);e.next=25;break;case 21:e.prev=21,e.t0=e["catch"](17),n=!0,l=e.t0;case 25:e.prev=25,e.prev=26,a||null==s.return||s.return();case 28:if(e.prev=28,!n){e.next=31;break}throw l;case 31:return e.finish(28);case 32:return e.finish(25);case 33:i.append("typeUpload","file"),e.next=37;break;case 36:return e.abrupt("return");case 37:return"table"==this.view&&i.append("prepareForSave",!0),e.next=40,this.$axios({method:"POST",data:i,headers:{"Content-Type":"multipart/form-data"},url:"/field/em_file/index/upload/"});case 40:if(u=e.sent,u.data.success){e.next=43;break}return e.abrupt("return",!1);case 43:for(c=!0,p=!1,f=void 0,e.prev=46,d=u.data.value[Symbol.iterator]();!(c=(h=d.next()).done);c=!0)v=h.value,this.localValue.push(v);e.next=54;break;case 50:e.prev=50,e.t1=e["catch"](46),p=!0,f=e.t1;case 54:e.prev=54,e.prev=55,c||null==d.return||d.return();case 57:if(e.prev=57,!p){e.next=60;break}throw f;case 60:return e.finish(57);case 61:return e.finish(54);case 62:this.sendValue(),this.closeSubPopup();case 64:case"end":return e.stop()}},e,this,[[17,21,25,33],[26,,28,32],[46,50,54,62],[55,,57,61]])}));function t(t){return e.apply(this,arguments)}return t}(),removeFile:function(){var e=Object(l["a"])(regeneratorRuntime.mark(function e(t){return regeneratorRuntime.wrap(function(e){while(1)switch(e.prev=e.next){case 0:if("undefined"==typeof this.localValue[t].new){e.next=4;break}return this.$delete(this.localValue,t),this.sendValue(),e.abrupt("return");case 4:this.$set(this.localValue[t],"delete",!0),this.$set(this.localValue[t],"noShow",!0),this.sendValue();case 7:case"end":return e.stop()}},e,this)}));function t(t){return e.apply(this,arguments)}return t}(),sendValue:function(e){this.$emit("onChange",{value:this.localValue,settings:this.fieldSettings,tableCode:this.tableCode,fieldCode:this.fieldCode})},setPreviewForImage:function(e,t){null==e.match(/\.(jpeg|jpg|gif|png)$/)&&(e=!1),this.setPreview(e,t)},setPreview:function(e,t){var i=this.localValue[t];i.type=0==e?"no-image":"image",i.sizes={small:{path:e}},this.$set(this.localValue,t,i)},setActiveTab:function(e){var t=!0,i=!1,a=void 0;try{for(var n,l=this.tabs[Symbol.iterator]();!(t=(n=l.next()).done);t=!0){var s=n.value;s.active=!1}}catch(r){i=!0,a=r}finally{try{t||null==l.return||l.return()}finally{if(i)throw a}}e.active=!0,this.activeTab=e.name},openPopup:function(){this.showPopup=!0},openSubPopup:function(){this.showSubPopup=!0},closePopup:function(){this.showPopup=!1,this.closeSubPopup()},closeSubPopup:function(){this.showSubPopup=!1}},watch:{fieldValue:function(e){this.localValue=e}},created:function(){this.localValue=this.fieldValue}},r=s,o=(i("b242"),i("2877")),u=Object(o["a"])(r,a,n,!1,null,null,null);t["default"]=u.exports},"2bf0":function(e,t,i){},"386b":function(e,t,i){var a=i("5ca1"),n=i("79e5"),l=i("be13"),s=/"/g,r=function(e,t,i,a){var n=String(l(e)),r="<"+t;return""!==i&&(r+=" "+i+'="'+String(a).replace(s,"&quot;")+'"'),r+">"+n+"</"+t+">"};e.exports=function(e,t){var i={};i[e]=t(r),a(a.P+a.F*n(function(){var t=""[e]('"');return t!==t.toLowerCase()||t.split('"').length>3}),"String",i)}},4917:function(e,t,i){"use strict";var a=i("cb7c"),n=i("9def"),l=i("0390"),s=i("5f1b");i("214f")("match",1,function(e,t,i,r){return[function(i){var a=e(this),n=void 0==i?void 0:i[t];return void 0!==n?n.call(i,a):new RegExp(i)[t](String(a))},function(e){var t=r(i,e,this);if(t.done)return t.value;var o=a(e),u=String(this);if(!o.global)return s(o,u);var c=o.unicode;o.lastIndex=0;var p,f=[],d=0;while(null!==(p=s(o,u))){var h=String(p[0]);f[d]=h,""===h&&(o.lastIndex=l(u,n(o.lastIndex),c)),d++}return 0===d?null:f}]})},b242:function(e,t,i){"use strict";var a=i("2bf0"),n=i.n(a);n.a},b54a:function(e,t,i){"use strict";i("386b")("link",function(e){return function(t){return e(this,"a","href",t)}})}}]);
//# sourceMappingURL=chunk-7ae42368.924bb34f.js.map