(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-cbd97a82"],{2520:function(e,t,n){"use strict";n("7f7f");var s=function(){var e=this,t=e._self._c;return t("div",{staticClass:"detail-popup"},[t("Popup",{attrs:{visible:e.isPopupVisible},on:{"update:visible":function(t){e.isPopupVisible=t}}},[t("Detail",{attrs:{tableCode:e.tableCode,name:e.name,id:e.id,element:e.element,updatedElAt:e.updatedElAt},on:{"update:updatedElAt":function(t){e.updatedElAt=t},"update:updated-el-at":function(t){e.updatedElAt=t},cancel:e.cancel,openDetail:e.openDetail,saveElement:e.saveDetailElement,removeElement:e.removeDetailElement,createElement:e.createDetailElement}})],1)],1)},a=[],i=(n("96cf"),n("3b8d")),r=n("b97e"),l=n("a13b"),o={props:["tableCode","name","id","visible","element"],components:{Detail:r["a"]},mixins:[l["a"]],data:function(){return{updatedElAt:new Date}},computed:{isPopupVisible:{get:function(){return this.visible},set:function(e){this.$emit("update:visible",e)}}},methods:{cancel:function(){this.isPopupVisible=!1},openDetail:function(e){this.$emit("openDetail",e)},saveDetailElement:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.saveElement(t);case 2:if(n=e.sent,n.data.success){e.next=5;break}return e.abrupt("return",this.ElMessage(n.data.message));case 5:this.$emit.apply(this,["saveElement"].concat([t,n])),this.updatedElAt=new Date;case 7:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),createDetailElement:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.createElement(t);case 2:if(n=e.sent,n.data.success){e.next=5;break}return e.abrupt("return",this.ElMessage(n.data.message));case 5:this.$emit.apply(this,["createElement"].concat([t,n]));case 6:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),removeDetailElement:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.removeElement(t);case 2:if(n=e.sent,n.data.success){e.next=5;break}return e.abrupt("return",this.ElMessage(n.data.message));case 5:this.$emit.apply(this,["removeElement"].concat([t,n]));case 6:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}()}},c=o,u=(n("8750"),n("2877")),d=Object(u["a"])(c,s,a,!1,null,null,null);t["a"]=d.exports},"58f6":function(e,t,n){},"72ef":function(e,t,n){"use strict";n("58f6")},8652:function(e,t,n){},8750:function(e,t,n){"use strict";n("c284")},"8e9e":function(e,t,n){"use strict";n("8652")},a13b:function(e,t,n){"use strict";n("ac6a"),n("96cf");var s=n("3b8d"),a=n("4328"),i=n.n(a);t["a"]={methods:{saveElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.$store.dispatch("saveSelectedElement",t);case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),createElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(t){var n,s,a,r,l;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:n=this.$store.getters.getPrimaryKeyCode(t.tableCode),s={},e.t0=regeneratorRuntime.keys(t.selectedElement);case 3:if((e.t1=e.t0()).done){e.next=10;break}if(a=e.t1.value,n!=a){e.next=7;break}return e.abrupt("continue",3);case 7:s[a]=t.selectedElement[a],e.next=3;break;case 10:return r=i.a.stringify({insert:{table:t.tableCode,values:[s]}}),e.next=13,this.$axios.post("/el/insert/",r);case 13:return l=e.sent,e.abrupt("return",l);case 15:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),removeElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(t){var n,s;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return n=this.$store.getters.getPrimaryKeyCode(t.tableCode),e.next=3,this.$store.dispatch("removeRecord",{delete:{table:t.tableCode,where:{operation:"and",fields:[{code:n,operation:"IS",value:t.selectedElement[n]}]}}});case 3:return s=e.sent,e.abrupt("return",s);case 5:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}()}}},b97e:function(e,t,n){"use strict";n("7f7f");var s=function(){var e=this,t=e._self._c;return t("div",{staticClass:"detail"},[t("ConfirmPopup",{attrs:{name:"confirmRemove",buttons:e.confirmDeleteButtons,message:e.removeMessage},on:{confirm:e.onConfirmRemove,cancel:e.onCancelRemove}}),t("div",{staticClass:"detail-head"},[t("div",{staticClass:"detail-head__burger",class:{hidden:e.isShowSidebar}},[t("MobileBurger")],1),t("div",{staticClass:"detail-head-name"},[t("div",{staticClass:"detail-icon-wrapper"},[t("svg",{attrs:{width:"14",height:"13"}},[t("use",{attrs:{"xlink:href":"#tableicon"}})])]),"tableAddElement"!=e.name?[t("div",{staticClass:"detail-name-wrapper"},[t("div",{staticClass:"detail-head-label"},[e._v(e._s(e.$t("pages.table.edit_element")))]),t("div",{staticClass:"detail-head-descr"},[e._v(e._s(e.tableCode))])]),t("div",{staticClass:"detail-head__buttons"},[t("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),t("button",{staticClass:"el-gbtn",on:{click:e.confirmRemove}},[e._v(e._s(e.$t("remove")))]),t("button",{staticClass:"el-btn",on:{click:e.saveElement}},[e._v(e._s(e.$t("save")))])])]:[t("div",{staticClass:"detail-name-wrapper"},[t("div",{staticClass:"detail-head-label"},[e._v(e._s(e.$t("pages.table.new_element")))]),t("div",{staticClass:"detail-head-descr"},[e._v(e._s(e.tableCode))])]),t("div",{staticClass:"detail-head__buttons"},[t("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),t("button",{staticClass:"el-btn",on:{click:e.createElement}},[e._v(e._s(e.$t("create")))])])]],2)]),t("div",{staticClass:"detail-feilds"},e._l(e.selectedElement,(function(s,a){return t("div",{key:"selItem".concat(a),staticClass:"detail-feild"},[t("div",{staticClass:"detail-field__name-wrap"},[t("img",{staticClass:"detail-field__icon-image",attrs:{src:n("53a4")("./assets".concat(e.columnEmSettings(a).type_info.iconPath))}}),t("div",{staticClass:"detail-field-name"},[t("span",[e._v(e._s(e.getColumnName(a)))]),t("small",[e._v(e._s(a))])])]),t("div",{staticClass:"detail-field-box"},[e.columns[a]?t("MainField",{attrs:{mode:"edit",view:"detail",fieldName:e.columns[a].em.settings.code,params:{value:s,settings:e.$store.getters.getColumnSettings(e.tableCode,a,e.selectedElement)}},on:{onChange:e.changeFieldValue}}):e._e()],1)])})),0),t("div",{staticClass:"detail__buttons"},["tableAddElement"!=e.name?[t("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),t("button",{staticClass:"el-gbtn",on:{click:e.confirmRemove}},[e._v(e._s(e.$t("remove")))]),t("button",{staticClass:"el-btn",on:{click:e.saveElement}},[e._v(e._s(e.$t("save")))])]:[t("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),t("button",{staticClass:"el-btn",on:{click:e.createElement}},[e._v(e._s(e.$t("create")))])]],2)],1)},a=[],i=(n("8e6e"),n("ac6a"),n("456d"),n("96cf"),n("3b8d")),r=n("bd86"),l=n("6e3e"),o=(n("4328"),n("25bd")),c=n("2f62");function u(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);t&&(s=s.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,s)}return n}function d(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?u(Object(n),!0).forEach((function(t){Object(r["a"])(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var m={props:["tableCode","name","id","element","updatedElAt"],components:{MainField:l["a"],MobileBurger:o["a"]},computed:d({},Object(c["b"])(["isShowSidebar"])),data:function(){return{columns:{},selectedElement:{},fieldNames:{},removeMessage:this.$t("popups.confirmDeletePopup.remove"),confirmDeleteButtons:{confirm:{text:this.$t("popups.confirmDeletePopup.confirm_delete")},cancel:{text:this.$t("popups.confirmDeletePopup.cancel")}}}},watch:{id:function(e){this.selectedElement.id=e},updatedElAt:function(e){this.selectElement()}},mounted:function(){this.selectElement(),this.fieldNames=Object.assign(this.fieldNames,this.$store.getters.getTableFieldsNames(this.tableCode))},methods:{selectElement:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(){var t,n,s;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if(t={select:{},where:[],order:[]},t.select.from=this.tableCode,n=this.$store.getters.getPrimaryKeyCode(this.tableCode),this.columns=this.$store.getters.getColumns(this.tableCode),"tableAddElement"==this.name||this.element){e.next=11;break}return t.select.where={operation:"and",fields:[{code:n,operation:"IS",value:this.id}]},e.next=8,this.$store.dispatch("selectElement",t);case 8:this.$set(this,"selectedElement",this.$store.state.tables.selectedElement),e.next=12;break;case 11:for(s in this.columns)this.$set(this.selectedElement,s,""),this.$set(this.selectedElement,s,""),this.element&&"undefined"!=typeof this.element[s]&&this.$set(this.selectedElement,s,this.element[s]);case 12:case"end":return e.stop()}}),e,this)})));function t(){return e.apply(this,arguments)}return t}(),changeFieldValue:function(e){this.selectedElement[e.settings.fieldCode]=e.value},columnEmSettings:function(e){if("undefined"!=typeof this.columns[e])return this.columns[e].em;throw new Error("No column with code ".concat(e))},getColumnName:function(e){return this.columnEmSettings(e).name||e},saveElement:function(){this.$emit("saveElement",{selectedElement:this.selectedElement,tableCode:this.tableCode})},createElement:function(){this.$emit("createElement",{selectedElement:this.selectedElement,tableCode:this.tableCode})},cancel:function(){this.$emit("cancel")},confirmRemove:function(){this.$modal.show("confirmRemove")},onConfirmRemove:function(){this.remove(),this.$modal.hide("confirmRemove")},onCancelRemove:function(){this.$modal.hide("confirmRemove")},remove:function(){this.$emit("removeElement",{selectedElement:this.selectedElement,tableCode:this.tableCode})}}},h=m,p=(n("72ef"),n("2877")),f=Object(p["a"])(h,s,a,!1,null,null,null);t["a"]=f.exports},c284:function(e,t,n){},f2d4:function(e,t,n){"use strict";n.r(t);n("7f7f");var s=function(){var e=this,t=e._self._c;return t("div",{staticClass:"em-node"},[t("List",{attrs:{searchText:e.query,settings:{placeholder:e.$t("empty")},multiple:"true"===e.fieldSettings.multiple},on:{"update:searchText":function(t){e.query=t},"update:search-text":function(t){e.query=t},onopen:function(t){return e.getNodes()}},scopedSlots:e._u([{key:"selected",fn:function(){return e._l(e.localFieldValue,(function(n){return t("ListOption",{key:n.id,attrs:{current:!0},on:{remove:function(t){return e.removeItem(n)}}},[e._v(e._s(n.name))])}))},proxy:!0}])},[e._l(e.list,(function(n){return t("ListOption",{key:n.code,on:{select:function(t){return e.selectItem(n)}}},[e._v(e._s(n.name))])})),e.newTag?t("div",{staticClass:"em-node__footer",on:{click:function(t){return e.createItem()}}},[t("div",{staticClass:"em-node__btn"},[e._v(" "+e._s(e.$t("create"))+" ")]),t("div",{staticClass:"em-node__new-tag"},[e._v(" "+e._s(e.newTag)+" ")])]):e._e()],2),t("DetailPopup",{attrs:{visible:e.showDetail,tableCode:e.detailTableCode,name:e.detailName,element:e.currentElement},on:{"update:visible":function(t){e.showDetail=t},createElement:e.createElement}})],1)},a=[],i=(n("ac6a"),n("96cf"),n("3b8d")),r=n("2520"),l={components:{DetailPopup:r["a"]},props:["fieldValue","fieldSettings","mode","view"],data:function(){return{detailTableCode:!1,detailName:!1,showDetail:!1,currentElement:!1,list:[],query:"",localFieldValue:this.fieldValue,newTag:"",nodesTimeout:""}},watch:{query:function(e){this.newTag=this.query,this.getNodes()}},mounted:function(){this.$set(this,"localFieldValue",this.fieldValue)},methods:{createElement:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t,n){var s;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:1==n.data.success?(s={},s["id"]=n.data.lastid,s[this.fieldSettings.nodeSearchCode]=this.newTag,this.selectItem(s),this.ElMessage(this.$t("elMessages.element_created")),this.showDetail=!1):this.ElMessage.error(this.$t("elMessages.cant_create_element"));case 1:case"end":return e.stop()}}),e,this)})));function t(t,n){return e.apply(this,arguments)}return t}(),bindDefaultColumnValues:function(){this.currentElement=[];var e=this.$store.getters.getTable(this.fieldSettings.nodeTableCode);for(var t in e.columns)this.currentElement[t]="";this.currentElement[this.fieldSettings.nodeSearchCode]=this.newTag},popupForCreateElement:function(){this.bindDefaultColumnValues(),this.detailTableCode=this.fieldSettings.nodeTableCode,this.detailName="tableAddElement",this.showDetail=!0},getNodes:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(){var t=this;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:clearTimeout(this.nodesTimeout),this.nodesTimeout=setTimeout(Object(i["a"])(regeneratorRuntime.mark((function e(){var n,s;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return n=new FormData,n.append("nodeFieldCode",t.fieldSettings.nodeFieldCode),n.append("nodeTableCode",t.fieldSettings.nodeTableCode),n.append("nodeSearchCode",t.fieldSettings.nodeSearchCode),n.append("q",t.query),e.next=7,t.$axios({method:"POST",data:n,headers:{"Content-Type":"multipart/form-data"},url:"/field/em_node/index/autoComplete/"});case 7:if(s=e.sent,s.data.success){e.next=10;break}return e.abrupt("return",!1);case 10:t.list=s.data.result;case 11:case"end":return e.stop()}}),e)}))),1e3);case 2:case"end":return e.stop()}}),e,this)})));function t(){return e.apply(this,arguments)}return t}(),createItem:function(){this.popupForCreateElement()},selectItem:function(e){var t=[];"true"===this.fieldSettings.multiple?(this.localFieldValue.push(e),this.localFieldValue.forEach((function(e){t.push(e)}))):(this.localFieldValue=[e],t.push(e)),this.$emit("onChange",{value:t,settings:this.fieldSettings})},removeItem:function(e){var t=[];if("true"===this.fieldSettings.multiple){var n=this.localFieldValue.indexOf(e);this.localFieldValue.splice(n,1),this.localFieldValue.forEach((function(e){t.push(e)}))}else this.localFieldValue=[];this.$emit("onChange",{value:t.lenght?t:"",settings:this.fieldSettings})}}},o=l,c=(n("8e9e"),n("2877")),u=Object(c["a"])(o,s,a,!1,null,null,null);t["default"]=u.exports}}]);
//# sourceMappingURL=chunk-cbd97a82.5cd90029.js.map