(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3e831a22"],{1006:function(e,t,n){"use strict";n.r(t);n("7f7f");var i=function(){var e=this,t=e._self._c;return t("div",{staticClass:"em-matrix"},["detail"==e.view?t("div",{staticClass:"em-matrix-table"},[e.fieldValue.matrixValue.length?t("table",[t("tr",[e._l(e.headFields,(function(n){return t("th",[e._v("\n\t\t\t\t\t"+e._s(n.name)+"\n\t\t\t\t\t"),t("small",[e._v(e._s(n.key))])])})),t("th")],2),e._l(e.fieldValue.matrixValue,(function(n,i){return t("tr",[e._l(n,(function(n,i){return"true"==e.getColumnSettings(i).visibility?t("td",[t("MainField",{attrs:{mode:"view",view:"detail",fieldName:e.getColumnSettings(i).settings.code,params:{value:n,settings:e.$store.getters.getColumnSettings(e.fieldSettings.finalTableCode,i,n)}}})],1):e._e()})),t("td",{staticClass:"em-matrix-table__edit-btns"},[t("div",{staticClass:"em-matrix-field__hover-btns"},[t("div",{staticClass:"em-matrix-field em-matrix-field__edit",on:{click:function(t){return e.popupForEditMatrixColumn(n,i)}}},[e._v("\n\t\t\t\t\t\t\t"+e._s(e.$t("edit"))+"\n\t\t\t\t\t\t")]),t("div",{staticClass:"em-matrix-field em-matrix-field__remove",on:{click:function(t){return e.removeMatrixElement({tableCode:e.fieldSettings.finalTableCode,selectedElement:n})}}},[e._v("\n\t\t\t\t\t\t\t"+e._s(e.$t("remove"))+"\n\t\t\t\t\t\t")])])])],2)}))],2):e._e(),t("div",{staticClass:"em-matrix-row-add"},[t("button",{on:{click:function(t){return e.popupForCreateMatrixElement()}}},[t("div",{staticClass:"em-matrix-row-add__icon"},[t("svg",{attrs:{width:"9",height:"9"}},[t("use",{attrs:{"xlink:href":"#plus-gray"}})])]),t("div",{staticClass:"em-matrix-row-add__text"},[e._v(" New Element")])])])]):t("div",[t("span",{staticClass:"el-empty"},[e._v("Matrix field")])]),t("DetailPopup",{attrs:{visible:e.showDetail,tableCode:e.detailTableCode,name:e.detailName,id:e.detailTableId,element:e.currentElement},on:{"update:visible":function(t){e.showDetail=t},saveElement:e.savePopupMatrixElement,createElement:e.createPopupMatrixElement,removeElement:e.removePopupMatrixElement}})],1)},a=[],s=(n("ac4d"),n("8a81"),n("5df3"),n("1c4c"),n("6b54"),n("768b")),l=(n("ac6a"),n("6e3e")),r=n("a13b"),o=n("2520");function c(e,t){var n="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!n){if(Array.isArray(e)||(n=u(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var i=0,a=function(){};return{s:a,n:function(){return i>=e.length?{done:!0}:{done:!1,value:e[i++]}},e:function(e){throw e},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var s,l=!0,r=!1;return{s:function(){n=n.call(e)},n:function(){var e=n.next();return l=e.done,e},e:function(e){r=!0,s=e},f:function(){try{l||null==n.return||n.return()}finally{if(r)throw s}}}}function u(e,t){if(e){if("string"==typeof e)return d(e,t);var n={}.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?d(e,t):void 0}}function d(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,i=Array(t);n<t;n++)i[n]=e[n];return i}var m={props:["fieldValue","fieldSettings","mode","view"],components:{DetailPopup:o["a"],MainField:l["a"]},mixins:[r["a"]],data:function(){return{showDetail:!1,detailTableCode:!1,detailTableId:!1,detailName:!1,currentElement:!1,headFields:[]}},computed:{detailElement:function(){return this.$store.state.tables.selectedElement}},methods:{updateMatrixTableElement:function(e){var t,n=this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode),i=c(this.fieldValue.matrixValue.entries());try{for(i.s();!(t=i.n()).done;){var a=Object(s["a"])(t.value,2),l=a[0],r=a[1];if(+r[n]==+e[n])return this.fieldValue.matrixValue[l]=e}}catch(o){i.e(o)}finally{i.f()}},createMatrixTableElement:function(e){"undefined"==typeof this.fieldValue.matrixValue&&this.$set(this.fieldValue,"matrixValue",[]);this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode);this.fieldValue.matrixValue.push(e)},removeMatrixTableElement:function(e){var t,n=this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode),i=c(this.fieldValue.matrixValue.entries());try{for(i.s();!(t=i.n()).done;){var a=Object(s["a"])(t.value,2),l=a[0],r=a[1];if(+r[n]==+e[n])return this.fieldValue.matrixValue.splice(l,1)}}catch(o){i.e(o)}finally{i.f()}},popupForEditMatrixColumn:function(e,t){this.currentElement=!1;var n=this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode);this.detailTableId=e[n],this.detailTableCode=this.fieldSettings.finalTableCode,this.detailName=!1,this.showDetail=!0},bindDefaultColumnValues:function(){this.currentElement=[];var e=this.$store.getters.getTable(this.fieldSettings.finalTableCode);for(var t in e.columns)this.currentElement[t]="";this.currentElement[this.fieldSettings.finalTableField]=this.detailElement[this.fieldSettings.localField]},popupForCreateMatrixElement:function(){this.bindDefaultColumnValues(),this.detailTableCode=this.fieldSettings.finalTableCode,this.detailName="tableAddElement",this.showDetail=!0},savePopupMatrixElement:function(e,t){t.data.success&&(this.updateMatrixTableElement(e.selectedElement),this.ElMessage(this.$t("elMessages.element_saved")),this.showDetail=!1)},createPopupMatrixElement:function(e,t){if(1==t.data.success){var n=this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode);e.selectedElement[n]=t.data.lastid,this.createMatrixTableElement(e.selectedElement),this.ElMessage(this.$t("elMessages.element_created")),this.showDetail=!1}else this.ElMessage.error(this.$t("elMessages.cant_create_element"))},removeMatrixElement:function(e){this.removeElement(e),this.showDetail=!1,this.ElMessage(this.$t("elMessages.element_removed")),this.removeMatrixTableElement(e.selectedElement)},removePopupMatrixElement:function(e,t){this.showDetail=!1,this.ElMessage(this.$t("elMessages.element_removed")),this.removeMatrixTableElement(e.selectedElement)},getColumnSettings:function(e){var t,n=c(this.fieldSettings.columnsSettings);try{for(n.s();!(t=n.n()).done;){var i=t.value;if(i.settings=this.$store.getters.getColumnSettings(this.fieldSettings.finalTableCode,e,!1),i.key==e)return i.name=""!=i.name?i.name:i.key,i}}catch(a){n.e(a)}finally{n.f()}return!1},setHeadLine:function(){if("undefined"!=typeof this.fieldValue.matrixValue&&this.fieldValue.matrixValue.length)for(var e in this.fieldValue.matrixValue[0]){var t=this.getColumnSettings(e);"true"==t.visibility&&this.headFields.push(t)}}},mounted:function(){this.setHeadLine()}},f=m,h=(n("e710"),n("2877")),p=Object(h["a"])(f,i,a,!1,null,null,null);t["default"]=p.exports},2520:function(e,t,n){"use strict";n("7f7f");var i=function(){var e=this,t=e._self._c;return t("div",{staticClass:"detail-popup"},[t("Popup",{attrs:{visible:e.isPopupVisible},on:{"update:visible":function(t){e.isPopupVisible=t}}},[t("Detail",{attrs:{tableCode:e.tableCode,name:e.name,id:e.id,element:e.element,updatedElAt:e.updatedElAt},on:{"update:updatedElAt":function(t){e.updatedElAt=t},"update:updated-el-at":function(t){e.updatedElAt=t},cancel:e.cancel,openDetail:e.openDetail,saveElement:e.saveDetailElement,removeElement:e.removeDetailElement,createElement:e.createDetailElement}})],1)],1)},a=[],s=(n("96cf"),n("3b8d")),l=n("b97e"),r=n("a13b"),o={props:["tableCode","name","id","visible","element"],components:{Detail:l["a"]},mixins:[r["a"]],data:function(){return{updatedElAt:new Date}},computed:{isPopupVisible:{get:function(){return this.visible},set:function(e){this.$emit("update:visible",e)}}},methods:{cancel:function(){this.isPopupVisible=!1},openDetail:function(e){this.$emit("openDetail",e)},saveDetailElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.saveElement(t);case 2:if(n=e.sent,n.data.success){e.next=5;break}return e.abrupt("return",this.ElMessage(n.data.message));case 5:this.$emit.apply(this,["saveElement"].concat([t,n])),this.updatedElAt=new Date;case 7:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),createDetailElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.createElement(t);case 2:if(n=e.sent,n.data.success){e.next=5;break}return e.abrupt("return",this.ElMessage(n.data.message));case 5:this.$emit.apply(this,["createElement"].concat([t,n]));case 6:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),removeDetailElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.removeElement(t);case 2:if(n=e.sent,n.data.success){e.next=5;break}return e.abrupt("return",this.ElMessage(n.data.message));case 5:this.$emit.apply(this,["removeElement"].concat([t,n]));case 6:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}()}},c=o,u=(n("3c52"),n("2877")),d=Object(u["a"])(c,i,a,!1,null,null,null);t["a"]=d.exports},"3c52":function(e,t,n){"use strict";n("c98e")},a13b:function(e,t,n){"use strict";n("ac6a"),n("96cf");var i=n("3b8d"),a=n("4328"),s=n.n(a);t["a"]={methods:{saveElement:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.$store.dispatch("saveSelectedElement",t);case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),createElement:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n,i,a,l,r;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:n=this.$store.getters.getPrimaryKeyCode(t.tableCode),i={},e.t0=regeneratorRuntime.keys(t.selectedElement);case 3:if((e.t1=e.t0()).done){e.next=10;break}if(a=e.t1.value,n!=a){e.next=7;break}return e.abrupt("continue",3);case 7:i[a]=t.selectedElement[a],e.next=3;break;case 10:return l=s.a.stringify({insert:{table:t.tableCode,values:[i]}}),e.next=13,this.$axios.post("/el/insert/",l);case 13:return r=e.sent,e.abrupt("return",r);case 15:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}(),removeElement:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(t){var n,i;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return n=this.$store.getters.getPrimaryKeyCode(t.tableCode),e.next=3,this.$store.dispatch("removeRecord",{delete:{table:t.tableCode,where:{operation:"and",fields:[{code:n,operation:"IS",value:t.selectedElement[n]}]}}});case 3:return i=e.sent,e.abrupt("return",i);case 5:case"end":return e.stop()}}),e,this)})));function t(t){return e.apply(this,arguments)}return t}()}}},b97e:function(e,t,n){"use strict";n("7f7f");var i=function(){var e=this,t=e._self._c;return t("div",{staticClass:"detail"},[t("ConfirmPopup",{attrs:{name:"confirmRemove",buttons:e.confirmDeleteButtons,message:e.removeMessage},on:{confirm:e.onConfirmRemove,cancel:e.onCancelRemove}}),t("div",{staticClass:"detail-head"},[t("div",{staticClass:"detail-head__burger",class:{hidden:e.isShowSidebar}},[t("MobileBurger")],1),t("div",{staticClass:"detail-head-name"},[t("div",{staticClass:"detail-icon-wrapper"},[t("svg",{attrs:{width:"14",height:"13"}},[t("use",{attrs:{"xlink:href":"#tableicon"}})])]),"tableAddElement"!=e.name?[t("div",{staticClass:"detail-name-wrapper"},[t("div",{staticClass:"detail-head-label"},[e._v(e._s(e.$t("pages.table.edit_element")))]),t("div",{staticClass:"detail-head-descr"},[e._v(e._s(e.tableCode))])]),t("div",{staticClass:"detail-head__buttons"},[t("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),t("button",{staticClass:"el-gbtn",on:{click:e.confirmRemove}},[e._v(e._s(e.$t("remove")))]),t("button",{staticClass:"el-btn",on:{click:e.saveElement}},[e._v(e._s(e.$t("save")))])])]:[t("div",{staticClass:"detail-name-wrapper"},[t("div",{staticClass:"detail-head-label"},[e._v(e._s(e.$t("pages.table.new_element")))]),t("div",{staticClass:"detail-head-descr"},[e._v(e._s(e.tableCode))])]),t("div",{staticClass:"detail-head__buttons"},[t("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),t("button",{staticClass:"el-btn",on:{click:e.createElement}},[e._v(e._s(e.$t("create")))])])]],2)]),t("div",{staticClass:"detail-feilds"},e._l(e.selectedElement,(function(i,a){return t("div",{key:"selItem".concat(a),staticClass:"detail-feild"},[t("div",{staticClass:"detail-field__name-wrap"},[t("img",{staticClass:"detail-field__icon-image",attrs:{src:n("53a4")("./assets".concat(e.columnEmSettings(a).type_info.iconPath))}}),t("div",{staticClass:"detail-field-name"},[t("span",[e._v(e._s(e.getColumnName(a)))]),t("small",[e._v(e._s(a))])])]),t("div",{staticClass:"detail-field-box"},[e.columns[a]?t("MainField",{attrs:{mode:"edit",view:"detail",fieldName:e.columns[a].em.settings.code,params:{value:i,settings:e.$store.getters.getColumnSettings(e.tableCode,a,e.selectedElement)}},on:{onChange:e.changeFieldValue}}):e._e()],1)])})),0),t("div",{staticClass:"detail__buttons"},["tableAddElement"!=e.name?[t("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),t("button",{staticClass:"el-gbtn",on:{click:e.confirmRemove}},[e._v(e._s(e.$t("remove")))]),t("button",{staticClass:"el-btn",on:{click:e.saveElement}},[e._v(e._s(e.$t("save")))])]:[t("button",{staticClass:"el-gbtn",on:{click:e.cancel}},[e._v(e._s(e.$t("cancel")))]),t("button",{staticClass:"el-btn",on:{click:e.createElement}},[e._v(e._s(e.$t("create")))])]],2)],1)},a=[],s=(n("8e6e"),n("ac6a"),n("456d"),n("96cf"),n("3b8d")),l=n("bd86"),r=n("6e3e"),o=n("25bd"),c=n("2f62");function u(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);t&&(i=i.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,i)}return n}function d(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?u(Object(n),!0).forEach((function(t){Object(l["a"])(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var m={props:["tableCode","name","id","element","updatedElAt"],components:{MainField:r["a"],MobileBurger:o["a"]},computed:d({},Object(c["b"])(["isShowSidebar"])),data:function(){return{columns:{},selectedElement:{},fieldNames:{},removeMessage:this.$t("popups.confirmDeletePopup.remove"),confirmDeleteButtons:{confirm:{text:this.$t("popups.confirmDeletePopup.confirm_delete")},cancel:{text:this.$t("popups.confirmDeletePopup.cancel")}}}},watch:{id:function(e){this.selectedElement.id=e},updatedElAt:function(e){this.selectElement()}},mounted:function(){this.selectElement(),this.fieldNames=Object.assign(this.fieldNames,this.$store.getters.getTableFieldsNames(this.tableCode))},methods:{selectElement:function(){var e=Object(s["a"])(regeneratorRuntime.mark((function e(){var t,n,i;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if(t={select:{},where:[],order:[]},t.select.from=this.tableCode,n=this.$store.getters.getPrimaryKeyCode(this.tableCode),this.columns=this.$store.getters.getColumns(this.tableCode),"tableAddElement"==this.name||this.element){e.next=11;break}return t.select.where={operation:"and",fields:[{code:n,operation:"IS",value:this.id}]},e.next=8,this.$store.dispatch("selectElement",t);case 8:this.$set(this,"selectedElement",this.$store.state.tables.selectedElement),e.next=12;break;case 11:for(i in this.columns)this.$set(this.selectedElement,i,""),this.$set(this.selectedElement,i,""),this.element&&"undefined"!=typeof this.element[i]&&this.$set(this.selectedElement,i,this.element[i]);case 12:case"end":return e.stop()}}),e,this)})));function t(){return e.apply(this,arguments)}return t}(),changeFieldValue:function(e){this.selectedElement[e.settings.fieldCode]=e.value},columnEmSettings:function(e){if("undefined"!=typeof this.columns[e])return this.columns[e].em;throw new Error("No column with code ".concat(e))},getColumnName:function(e){return this.columnEmSettings(e).name||e},saveElement:function(){this.$emit("saveElement",{selectedElement:this.selectedElement,tableCode:this.tableCode})},createElement:function(){this.$emit("createElement",{selectedElement:this.selectedElement,tableCode:this.tableCode})},cancel:function(){this.$emit("cancel")},confirmRemove:function(){this.$modal.show("confirmRemove")},onConfirmRemove:function(){this.remove(),this.$modal.hide("confirmRemove")},onCancelRemove:function(){this.$modal.hide("confirmRemove")},remove:function(){this.$emit("removeElement",{selectedElement:this.selectedElement,tableCode:this.tableCode})}}},f=m,h=(n("d160"),n("2877")),p=Object(h["a"])(f,i,a,!1,null,null,null);t["a"]=p.exports},c98e:function(e,t,n){},d160:function(e,t,n){"use strict";n("f63a")},e710:function(e,t,n){"use strict";n("ebb7")},ebb7:function(e,t,n){},f63a:function(e,t,n){}}]);
//# sourceMappingURL=chunk-3e831a22.0c064153.js.map