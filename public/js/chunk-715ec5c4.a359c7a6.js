(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-715ec5c4"],{4988:function(e,t,n){},"61f0":function(e,t,n){"use strict";n.r(t);var s=function(){var e=this,t=e.$createElement,n=e._self._c||t;return e.show?n("Detail",{attrs:{tableCode:e.$route.params.tableCode,name:e.$route.name,id:e.$route.params.id,updatedElAt:e.updatedElAt},on:{"update:id":function(t){return e.$set(e.$route.params,"id",t)},"update:updatedElAt":function(t){e.updatedElAt=t},"update:updated-el-at":function(t){e.updatedElAt=t},cancel:e.cancel,openDetail:e.openDetail,saveElement:e.saveElementDetail,removeElement:e.removeElementDetail,createElement:e.createElementDetail}}):e._e()},a=[],i=(n("96cf"),n("3b8d")),r=n("b97e"),l=n("a13b"),c={components:{Detail:r["a"]},mixins:[l["a"]],data:function(){return{show:!1,updatedElAt:new Date}},methods:{cancel:function(){this.$router.go(-1)},openDetail:function(e){var t=e.tableCode,n=e.id;this.$router.push({name:"tableDetail",params:{tableCode:t,id:n}})},saveElementDetail:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.saveElement(t);case 2:n=e.sent,this.updatedElAt=new Date,n.data.success&&this.ElMessage(this.$t("elMessages.element_saved"));case 5:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),createElementDetail:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.createElement(t);case 2:n=e.sent,n.data.success?(this.openDetail({tableCode:t.tableCode,id:n.data.lastid}),this.ElMessage(this.$t("elMessages.element_created"))):this.ElMessage.error(this.$t("elMessages.cant_create_element"));case 4:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),removeElementDetail:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.removeElement(t);case 2:n=e.sent,n&&this.ElMessage(this.$t("elMessages.element_removed")),this.$router.go(-1);case 5:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}()},mounted:function(){var e=this;this.$store.state.tables.tables.length>0&&(this.show=!0),this.$store.subscribe((function(t,n){"setTables"==t.type&&(e.show=!0)}))}},o=c,u=n("2877"),d=Object(u["a"])(o,s,a,!1,null,null,null);t["default"]=d.exports},"851c":function(e,t,n){"use strict";n("4988")},a13b:function(e,t,n){"use strict";n("ac6a"),n("96cf");var s=n("3b8d"),a=n("4328"),i=n.n(a);t["a"]={methods:{saveElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.$store.dispatch("saveSelectedElement",t);case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),createElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(t){var n,s,a,r,l;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:n=this.$store.getters.getPrimaryKeyCode(t.tableCode),s={},e.t0=regeneratorRuntime.keys(t.selectedElement);case 3:if((e.t1=e.t0()).done){e.next=10;break}if(a=e.t1.value,n!=a){e.next=7;break}return e.abrupt("continue",3);case 7:s[a]=t.selectedElement[a],e.next=3;break;case 10:return r=i.a.stringify({insert:{table:t.tableCode,values:[s]}}),e.next=13,this.$axios.post("/el/insert/",r);case 13:return l=e.sent,e.abrupt("return",l);case 15:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),removeElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(t){var n,s;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return n=this.$store.getters.getPrimaryKeyCode(t.tableCode),e.next=3,this.$store.dispatch("removeRecord",{delete:{table:t.tableCode,where:{operation:"and",fields:[{code:n,operation:"IS",value:t.selectedElement[n]}]}}});case 3:return s=e.sent,e.abrupt("return",s);case 5:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}()}}},b97e:function(e,t,n){"use strict";var s=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"detail"},[s("div",{staticClass:"detail-head"},[s("div",{staticClass:"detail-head__burger",class:{hidden:e.isShowSidebar}},[s("MobileBurger")],1),s("div",{staticClass:"detail-head-name"},[s("div",{staticClass:"detail-icon-wrapper"},[s("svg",{attrs:{width:"14",height:"13"}},[s("use",{attrs:{"xlink:href":"#tableicon"}})])]),"tableAddElement"!=e.name?[s("div",{staticClass:"detail-name-wrapper"},[s("div",{staticClass:"detail-head-label"},[e._v(e._s(e.$t("pages.table.edit_element")))]),s("div",{staticClass:"detail-head-descr"},[e._v(e._s(e.tableCode))])]),s("div",{staticClass:"detail-head__buttons"},[s("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),s("button",{staticClass:"el-gbtn",on:{click:e.remove}},[e._v(e._s(e.$t("remove")))]),s("button",{staticClass:"el-btn",on:{click:e.saveElement}},[e._v(e._s(e.$t("save")))])])]:[s("div",{staticClass:"detail-name-wrapper"},[s("div",{staticClass:"detail-head-label"},[e._v(e._s(e.$t("pages.table.new_element")))]),s("div",{staticClass:"detail-head-descr"},[e._v(e._s(e.tableCode))])]),s("div",{staticClass:"detail-head__buttons"},[s("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),s("button",{staticClass:"el-btn",on:{click:e.createElement}},[e._v(e._s(e.$t("create")))])])]],2)]),s("div",{staticClass:"detail-feilds"},e._l(e.selectedElement,(function(t,a){return s("div",{key:"selItem"+a,staticClass:"detail-feild"},[s("div",{staticClass:"detail-field__name-wrap"},[s("img",{staticClass:"detail-field__icon-image",attrs:{src:n("53a4")("./assets"+e.columnEmSettings(a).type_info.iconPath)}}),s("div",{staticClass:"detail-field-name"},[s("span",[e._v(e._s(e.getColumnName(a)))]),s("small",[e._v(e._s(a))])])]),s("div",{staticClass:"detail-field-box"},[e.columns[a]?s("MainField",{attrs:{mode:"edit",view:"detail",fieldName:e.columns[a].em.settings.code,params:{value:t,settings:e.$store.getters.getColumnSettings(e.tableCode,a,e.selectedElement)}},on:{onChange:e.changeFieldValue}}):e._e()],1)])})),0),s("div",{staticClass:"detail__buttons"},["tableAddElement"!=e.name?[s("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),s("button",{staticClass:"el-gbtn",on:{click:e.remove}},[e._v(e._s(e.$t("remove")))]),s("button",{staticClass:"el-btn",on:{click:e.saveElement}},[e._v(e._s(e.$t("save")))])]:[s("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),s("button",{staticClass:"el-btn",on:{click:e.createElement}},[e._v(e._s(e.$t("create")))])]],2)])},a=[],i=(n("8e6e"),n("ac6a"),n("456d"),n("7f7f"),n("96cf"),n("3b8d")),r=n("bd86"),l=n("6e3e"),c=(n("4328"),n("25bd")),o=n("2f62");function u(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);t&&(s=s.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,s)}return n}function d(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?u(Object(n),!0).forEach((function(t){Object(r["a"])(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var m={props:["tableCode","name","id","element","updatedElAt"],components:{MainField:l["a"],MobileBurger:c["a"]},computed:d({},Object(o["b"])(["isShowSidebar"])),data:function(){return{columns:{},selectedElement:{},fieldNames:{}}},watch:{id:function(e){this.selectedElement.id=e},updatedElAt:function(e){this.selectElement()}},mounted:function(){this.selectElement(),this.fieldNames=Object.assign(this.fieldNames,this.$store.getters.getTableFieldsNames(this.tableCode))},methods:{selectElement:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(){var t,n,s;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if(t={select:{},where:[],order:[]},t.select.from=this.tableCode,n=this.$store.getters.getPrimaryKeyCode(this.tableCode),this.columns=this.$store.getters.getColumns(this.tableCode),"tableAddElement"==this.name||this.element){e.next=11;break}return t.select.where={operation:"and",fields:[{code:n,operation:"IS",value:this.id}]},e.next=8,this.$store.dispatch("selectElement",t);case 8:this.$set(this,"selectedElement",this.$store.state.tables.selectedElement),e.next=12;break;case 11:for(s in this.columns)this.$set(this.selectedElement,s,""),this.$set(this.selectedElement,s,""),this.element&&"undefined"!=typeof this.element[s]&&this.$set(this.selectedElement,s,this.element[s]);case 12:case"end":return e.stop()}}),e,this)})));function t(){return e.apply(this,arguments)}return t}(),changeFieldValue:function(e){this.selectedElement[e.settings.fieldCode]=e.value},columnEmSettings:function(e){if("undefined"!=typeof this.columns[e])return this.columns[e].em;throw new Error("No column with code ".concat(e))},getColumnName:function(e){return this.columnEmSettings(e).name||e},saveElement:function(){this.$emit("saveElement",{selectedElement:this.selectedElement,tableCode:this.tableCode})},createElement:function(){this.$emit("createElement",{selectedElement:this.selectedElement,tableCode:this.tableCode})},cancel:function(){this.$emit("cancel")},remove:function(){this.$emit("removeElement",{selectedElement:this.selectedElement,tableCode:this.tableCode})}}},h=m,b=(n("851c"),n("2877")),p=Object(b["a"])(h,s,a,!1,null,null,null);t["a"]=p.exports}}]);
//# sourceMappingURL=chunk-715ec5c4.a359c7a6.js.map