(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-22f5ed81"],{"125a":function(e,t,i){},"16e6":function(e,t,i){"use strict";var s=i("ab0e"),n=i.n(s);n.a},"1e0a":function(e,t,i){"use strict";i.r(t);var s=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"index__wrapper"},[i("div",{staticClass:"index__head"},[e.table?i("div",{staticClass:"index__head-name"},[i("div",{staticClass:"index__icon-wrapper"},[i("svg",{attrs:{width:"14",height:"13"}},[i("use",{attrs:{"xlink:href":"#tableicon"}})])]),i("div",{staticClass:"index__name-wrapper"},[i("div",{staticClass:"index__overide-name"},[e._v(e._s(e.table.name))]),i("div",{staticClass:"index__real-name"},[e._v(e._s(e.table.code))])])]):e._e(),i("div",{staticClass:"index__head-options"},[i("ul",{staticClass:"index__head-options-list"},[i("li",{staticClass:"index__menu-item"},[e._v("Views")]),i("li",{staticClass:"index__menu-item",class:{active:e.popups.isPropertiesPopupShow},on:{click:function(t){return e.openPopup("isPropertiesPopupShow")}}},[e._v("\n\t\t\t\t\tProperties\n\t\t\t\t\t"),e.popups.isPropertiesPopupShow&&e.propertiesPopupData?i("Properties",{directives:[{name:"click-outside",rawName:"v-click-outside:isPropertiesPopupShow",value:e.closePopup,expression:"closePopup",arg:"isPropertiesPopupShow"}],attrs:{columns:e.propertiesPopupData}}):e._e()],1),i("li",{staticClass:"index__menu-item",class:{active:e.popups.isSortPopupShow},on:{click:function(t){return e.openPopup("isSortPopupShow")}}},[e._v("\n\t\t\t\t\tSort\n\t\t\t\t\t"),e.popups.isSortPopupShow?i("SortPopup",{directives:[{name:"click-outside",rawName:"v-click-outside:isSortPopupShow",value:e.closePopup,expression:"closePopup",arg:"isSortPopupShow"}],attrs:{columns:e.table.columns,tview:e.activeTview}}):e._e()],1),i("li",{staticClass:"index__menu-item",class:{active:e.popups.isFiltersPopupShow},on:{click:function(t){return e.openPopup("isFiltersPopupShow")}}},[e._v("\n\t\t\t\t\tFilter\n\t\t\t\t\t"),e.popups.isFiltersPopupShow?i("FiltersPopup",{directives:[{name:"click-outside",rawName:"v-click-outside:isFiltersPopupShow",value:e.closePopup,expression:"closePopup",arg:"isFiltersPopupShow"}],attrs:{columns:e.table.columns,tview:e.activeTview}}):e._e()],1)]),i("button",{staticClass:"index__add-btn el-btn",on:{click:function(t){return e.addElement()}}},[i("svg",{attrs:{width:"12",height:"12"}},[i("use",{attrs:{"xlink:href":"#plus"}})]),e._v("\n\t\t\t\tAdd Element\n\t\t\t")])])]),e.table&&e.activeTview?i("Table",{attrs:{table:e.table,tview:e.activeTview}}):e._e()],1)},n=[],r=(i("ac4d"),i("8a81"),i("ac6a"),i("7f7f"),function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"table-vertical-scroll",on:{mousemove:function(t){return e.resizeColumn(t,e.columnDrug.col)},mouseup:function(t){return e.endResize(t,e.columnDrug.col)}}},[i("div",{staticClass:"table__min-width",style:{"min-width":e.getTableMinWidth+"px"}},[i("div",{staticClass:"table-row no-hover"},[i("div",{staticClass:"table-item table__many-box"},[i("Checkbox",{attrs:{checked:e.checkAll},on:{"update:checked":function(t){e.checkAll=t},change:e.checkAllRows}}),i("svg",{staticClass:"table__many-arrow",attrs:{width:"7",height:"13"},on:{click:function(t){e.openedEditRowIndex="all"}}},[i("use",{attrs:{"xlink:href":"#arrow"}})]),"all"===e.openedEditRowIndex?i("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:e.closeEditModal,expression:"closeEditModal"}],staticClass:"table__many-modal"},[i("ul",[i("li",{staticClass:"table__many-delete",on:{click:function(t){return e.removeSelected()}}},[e._v("Delete")])])]):e._e()],1),e._l(e.table.columns,function(t){return t.visible?i("div",{staticClass:"table-item",style:{width:t.width+"px","min-width":t.width+"px"}},[i("div",{staticClass:"table-item-img"},[i("img",{attrs:{src:t.em.type_info.iconPath,alt:""}})]),i("div",{staticClass:"table-item-name-wrapper"},[i("div",{staticClass:"table-item-overide-name"},[e._v(e._s(e.getOverideName(t)))]),i("div",{staticClass:"table-item-real-name"},[e._v(e._s(t.field))])]),i("div",{staticClass:"drug-col",on:{mousedown:function(i){return e.reginsterEventResize(i,t)}}})]):e._e()}),i("div",{staticClass:"table-item"},[i("div",{staticClass:"table__add-column-item"},[i("div",{staticClass:"table__add-col-img"},[i("svg",{attrs:{width:"12",height:"12"}},[i("use",{attrs:{"xlink:href":"#plus-white"}})])]),i("span",{staticClass:"table__add-col-label"},[e._v("Add field")])])])],2),e._l(e.tableContent.items,function(t,s){return i("div",{staticClass:"table-row"},[i("div",{staticClass:"table-item table__many-box"},[i("Checkbox",{attrs:{checked:e.selectedRows[s]},on:{"update:checked":function(t){return e.$set(e.selectedRows,s,t)}}}),i("svg",{staticClass:"table__many-arrow",attrs:{width:"7",height:"13"},on:{click:function(t){e.openedEditRowIndex=s}}},[i("use",{attrs:{"xlink:href":"#arrow"}})]),e.openedEditRowIndex===s?i("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:e.closeEditModal,expression:"closeEditModal"}],staticClass:"table__many-modal"},[i("ul",[i("li",{on:{click:function(i){return e.openDetail(t,s)}}},[e._v("Edit")]),i("li",{staticClass:"table__many-delete",on:{click:function(i){return e.remove(t,s)}}},[e._v("Delete")])])]):e._e()],1),e._l(e.table.columns,function(n,r){return n.visible&&t[n.field]?i("div",{staticClass:"table-item",style:{width:n.width+"px","min-width":n.width+"px"}},[i("MainField",{attrs:{mode:"edit",params:{fieldName:t[n.field].fieldName,value:t[n.field].value,settings:e.$store.getters.getColumnSettings(e.$route.params.tableCode,n,t)}},on:{onChange:e.changeFieldValue,openEdit:function(i){return e.openDetail(t,s)}}})],1):e._e()}),e._m(0,!0)],2)})],2),e.tableContent.total_pages>1?i("Pagination",{attrs:{maxPage:e.tableContent.total_pages,current:e.tableContent.current,currentLimit:e.tableContent.limit},on:{change:e.selectPage}}):e._e()],1)}),a=[function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"table-item"},[i("div",{staticClass:"table-empty-col"})])}],o=(i("55dd"),i("96cf"),i("3b8d")),l=i("6e3e"),c=i("e902"),u=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"pagination-wrapper"},[i("ul",e._l(e.getPaginatorArr,function(t){return i("li",{class:{active:e.current==t,points:"..."==t},on:{click:function(i){return e.setPage(t)}}},[e._v(e._s("..."!=t?t:""))])}),0),i("div",{staticClass:"pagination__text"},[e._v("\n\t\tElements per page - "),i("span",{attrs:{contenteditable:"true"},on:{input:e.setLimit}},[e._v(e._s(e.currentLimit))]),e._v("*\n\t")])])},d=[],p={props:["current","maxPage","currentLimit"],data:function(){return{range:2,limit:this.currentLimit?this.currentLimit:20,page:1}},computed:{getPaginatorArr:function(){var e=[1],t=2*this.range+1,i=0,s=1;s=this.current-this.range<=1?1:this.current+this.range>=this.maxPage?this.maxPage-t:this.current-this.range;for(var n=s;n<this.maxPage;n++)if(!(n<=1)&&!(n>=this.maxPage)&&-1===e.indexOf(n)&&(e.push(n),i++,i>=t))break;e.push(this.maxPage);var r=[],a=1;for(var o in e){var l=e[o];l-a>1&&r.push("..."),r.push(l),a=l}return r}},methods:{setPage:function(e){if("..."==e||e==this.current)return!1;this.page=e,this.$emit("change",{page:e,limit:this.limit})},setLimit:function(e){if(this.page=1,+e.target.innerText<=0)return!1;this.limit=+e.target.innerText,this.$emit("change",{page:1,limit:this.limit})}}},h=p,f=(i("16e6"),i("2877")),m=Object(f["a"])(h,u,d,!1,null,null,null),v=m.exports,w=i("d230"),g={props:["table","tview"],mixins:[w["a"]],components:{MainField:l["a"],Pagination:v,Checkbox:c["a"]},data:function(){return{columnDrug:{isDrug:!1,posX:0,start:0,top:0,width:0,col:""},openProperties:!1,openedEditRowIndex:!1,checkAll:!1,selectedRows:{}}},computed:{tableContent:function(){return this.$store.state.tables.tableContent},getTableMinWidth:function(){var e=0;for(var t in this.table.columns)e+=+this.table.columns[t].width;return e+300},getUrlTableName:function(){return this.$route.params.tableCode}},watch:{"table.columns":{handler:function(e,t){0==this.columnDrug.isDrug&&this.saveColumnsParams()},deep:!0},"$route.fullPath":function(){this.setDefaulParams(),this.getTableContent()}},methods:{areSelected:function(e){return this.selectedRows.indexOf(e),!1},checkAllRows:function(e){if(!1===e)this.selectedRows={};else for(var t in this.selectedRows=[],this.tableContent.items)this.selectedRows[parseInt(t)]=!0},closeEditModal:function(){this.openedEditRowIndex=!1},changeFieldValue:function(e){this.$store.dispatch("saveFieldValue",e)},getOverideName:function(e){return"undefined"==typeof e.em.name||""==e.em.name?e.field:e.em.name},saveColumnsParams:function(){var e=this.table.columns,t={};for(var i in t["tviewId"]=this.tview.id,t["params"]={},t["params"]["columns"]={},e){if("undefined"==typeof e[i].width)return!1;t["params"]["columns"][i]={width:e[i].width,visible:e[i].visible},"undefined"==typeof this.tview.settings.columns&&this.$set(this.tview.settings,"columns",{}),this.$set(this.tview.settings.columns,i,{}),this.$set(this.tview.settings.columns[i],"width",e[i].width),this.$set(this.tview.settings.columns[i],"visible",e[i].visible?"true":"false")}this.$store.dispatch("saveColumnsWith",t)},setDefaulParams:function(){var e={};for(var t in this.table.columns)e=this.table.columns[t],"undefined"==typeof this.tview.settings.columns?(this.$set(e,"width",140),this.$set(e,"visible",!0)):"undefined"==typeof this.tview.settings.columns[t]?(this.$set(e,"width",140),this.$set(e,"visible",!0)):(this.$set(e,"width",this.tview.settings.columns[t].width),"undefined"==typeof this.tview.settings.columns[t].visible?this.$set(e,"visible",!0):this.$set(e,"visible","true"===this.tview.settings.columns[t].visible))},getTableContent:function(){var e=Object(o["a"])(regeneratorRuntime.mark(function e(){var t;return regeneratorRuntime.wrap(function(e){while(1)switch(e.prev=e.next){case 0:return t={select:{}},"undefined"!=typeof this.tview.filter.operation&&(t.select.where=this.tview.filter),"undefined"!=typeof this.tview.sort&&(t.select.order=this.tview.sort),t.select.from=this.$route.params.tableCode,t.select.page=this.$route.params.page,t.select.tview=this.$route.params.tview,this.$route.params.limit&&(t.limit=this.$route.params.limit),e.next=9,this.$store.dispatch("select",t);case 9:case"end":return e.stop()}},e,this)}));function t(){return e.apply(this,arguments)}return t}(),reginsterEventResize:function(e,t){this.columnDrug.start=e.target.getBoundingClientRect().left,this.columnDrug.width=t.width,this.columnDrug.isDrug=!0,this.columnDrug.posX=e.pageX,this.columnDrug.col=t},endResize:function(e,t){this.columnDrug.isDrug&&this.saveColumnsParams(),this.columnDrug.isDrug=!1},resizeColumn:function(e,t){if(!this.columnDrug.isDrug)return!1;t.width=Math.abs(this.columnDrug.posX-e.pageX-this.columnDrug.width),t.width<110&&(t.width=110),t.width>600&&(t.width=600)},selectPage:function(e){this.$store.dispatch("selectPage",e),this.$router.push("/table/".concat(this.table.code,"/tview/").concat(this.tview.id,"/page/").concat(e.page,"/limit/").concat(e.limit))},removeSelected:function(){var e=Object(o["a"])(regeneratorRuntime.mark(function e(){var t,i,s,n,r;return regeneratorRuntime.wrap(function(e){while(1)switch(e.prev=e.next){case 0:t=this.$store.getters.getPrimaryKeyCode(this.table.code),i={operation:"or",fields:[]},s=[],e.t0=regeneratorRuntime.keys(this.selectedRows);case 4:if((e.t1=e.t0()).done){e.next=15;break}if(n=e.t1.value,!1!==this.selectedRows[n]){e.next=8;break}return e.abrupt("continue",4);case 8:if("undefined"!=typeof this.tableContent.items[n]){e.next=10;break}return e.abrupt("continue",4);case 10:s.push(parseInt(n)),r=this.tableContent.items[n],i.fields.push({code:t,operation:"IS",value:r[t].value}),e.next=4;break;case 15:return e.next=17,this.$store.dispatch("removeRecord",{rowIndex:s,delete:{table:this.table.code,where:i}});case 17:this.selectedRows={},this.openedEditRowIndex=!1,this.checkAll=!1;case 20:case"end":return e.stop()}},e,this)}));function t(){return e.apply(this,arguments)}return t}(),remove:function(){var e=Object(o["a"])(regeneratorRuntime.mark(function e(t,i){var s;return regeneratorRuntime.wrap(function(e){while(1)switch(e.prev=e.next){case 0:return s=this.$store.getters.getPrimaryKeyCode(this.table.code),e.next=3,this.$store.dispatch("removeRecord",{rowIndex:i,delete:{table:this.table.code,where:{operation:"and",fields:[{code:s,operation:"IS",value:t[s].value}]}}});case 3:this.openedEditRowIndex=!1;case 4:case"end":return e.stop()}},e,this)}));function t(t,i){return e.apply(this,arguments)}return t}(),openDetail:function(e,t){var i=this.$store.getters.getPrimaryKeyCode(this.table.code);this.$router.push({name:"tableDetail",params:{tableCode:this.table.code,id:e[i].value}})}},mounted:function(){this.setDefaulParams(),this.getTableContent()}},b=g,_=(i("996b"),Object(f["a"])(b,r,a,!1,null,null,null)),C=_.exports,x=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"filters-popup__wrapper"},[i("div",{staticClass:"filters-popup__rows"},e._l(e.filter,function(t,s){return i("div",{staticClass:"filters-popup__row"},[i("div",{staticClass:"filters-popup__operators-wrapper"},[s>0?i("Select",{staticClass:"filters-popup__select",attrs:{defaultText:e.defaultOper}},e._l(e.binOperations,function(t){return i("SelectOption",{key:t,class:{active:e.defaultOper==t},nativeOn:{click:function(i){return e.selectLogic(t)}}},[e._v(e._s(t))])}),1):e._e(),i("Select",{staticClass:"filters-popup__select",attrs:{defaultText:t.name?t.name:t.code}},e._l(e.columns,function(s){return i("SelectOption",{key:s.field,class:{active:t.code==s.field},nativeOn:{click:function(i){return e.selectField(t,s)}}},[e._v(e._s(s.em.name?s.em.name:s.field))])}),1),i("Select",{staticClass:"filters-popup__select",attrs:{defaultText:t.operation}},e._l(e.operations,function(s){return i("SelectOption",{key:s,class:{active:t.operation==s},nativeOn:{click:function(i){return e.selectOperation(t,s)}}},[e._v(e._s(s))])}),1),i("input",{directives:[{name:"model",rawName:"v-model",value:t.value,expression:"filterItem.value"}],staticClass:"filters-popup__filter-input el-inp",attrs:{type:"text",placeholder:"Value"},domProps:{value:t.value},on:{input:function(i){i.target.composing||e.$set(t,"value",i.target.value)}}})],1),i("div",{staticClass:"filters-popup__delete-row-icon-wrapper",on:{click:function(t){return t.stopPropagation(),e.deleteRowFilter(s)}}},[i("div",{staticClass:"filters-popup__delete-row-icon"},[i("svg",{attrs:{width:"12",height:"12"}},[i("use",{attrs:{"xlink:href":"#plus-white"}})])])])])}),0),i("button",{staticClass:"el-gbtn",on:{click:e.addFilterRow}},[e._v("Add filter")])])},y=[],k=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"select"},[i("button",{directives:[{name:"click-outside",rawName:"v-click-outside",value:e.closeDropdown,expression:"closeDropdown"}],staticClass:"select__trigger",class:{active:e.active},on:{click:e.toggleDropdown}},[i("div",{staticClass:"select__content",domProps:{innerHTML:e._s(e.content)}}),i("span",{staticClass:"select__arrow"},[i("svg",{attrs:{width:"10",height:"6",viewBox:"0 0 10 6",fill:"none",xmlns:"http://www.w3.org/2000/svg"}},[i("path",{attrs:{d:"M9.26399 0.171389L9.26396 0.171427L5.00466 4.43907L0.736982 0.171389C0.580928 0.0153346 0.327417 0.0153346 0.171362 0.171389C0.0153076 0.327444 0.0153076 0.580955 0.171362 0.737009L4.71346 5.27911C4.79126 5.3569 4.88943 5.39615 4.99628 5.39615C5.09399 5.39615 5.20081 5.35738 5.27909 5.27911L9.82063 0.737571C9.98584 0.581446 9.98569 0.327466 9.82962 0.171389C9.67356 0.0153346 9.42005 0.0153346 9.26399 0.171389Z",fill:"#677387",stroke:"#677387","stroke-width":"0.1"}})])])]),i("transition",{attrs:{name:"fade"}},[e.active?i("ul",{staticClass:"select__dropdown"},[e._t("default")],2):e._e()])],1)},S=[],P={props:["defaultText"],data:function(){return{active:!1,value:"",content:!1}},mounted:function(){this.content=this.defaultText},methods:{toggleDropdown:function(){this.active=!this.active},closeDropdown:function(){this.active=!1},setContent:function(e){this.content=e}}},$=P,O=(i("1ed7"),Object(f["a"])($,k,S,!1,null,null,null)),R=O.exports,F=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("li",{on:{click:e.select}},[e._t("default")],2)},E=[],T={template:"",name:"SelectOption",methods:{select:function(e){var t=e.target;this.$parent.setContent(t.innerHTML),this.$parent.toggleDropdown()}}},D=T,N=Object(f["a"])(D,F,E,!1,null,null,null),A=N.exports,I={components:{Select:R,SelectOption:A},mixins:[w["a"]],props:["columns","tview"],data:function(){return{select:{columnSelect:!1,operationSelect:!1,binSelect:!1},operations:["IS","IS NOT","CONTAINS","DOES NOT CONTAIN","START WITH","ENDS WITH","IS EMPTY","IS NOT EMPTY"],binOperations:["AND","OR"],defaultOper:"AND",filter:[],newFilters:[],sourceFilters:[],sendRequest:!0}},watch:{filter:{handler:function(){this.newFilters=[],this.buildNewFilter(this.newFilters,JSON.parse(JSON.stringify(this.filter))),this.tview.filter=0==this.newFilters[0].fields.length?[]:this.newFilters[0];var e=this;if(!this.sendRequest)return!1;this.saveFilters(),this.sendRequest=!1,setTimeout(function(){e.sendRequest=!0,e.saveFilters()},2e3)},deep:!0}},methods:{selectField:function(e,t){e.code=t.field,e.name=t.em.name},selectOperation:function(e,t){e.operation=t},selectLogic:function(e){this.defaultOper=e},saveFilters:function(){var e=Object(o["a"])(regeneratorRuntime.mark(function e(){var t,s,n,r;return regeneratorRuntime.wrap(function(e){while(1)switch(e.prev=e.next){case 0:return t=this,s=i("4328"),n=s.stringify({filters:0==this.newFilters[0].fields.length?[]:this.newFilters[0],tviewId:this.tview.id}),e.next=5,this.$store.dispatch("select",{select:{from:t.tview.table,page:1,tview:t.tview.id,where:0==this.newFilters[0].fields.length?"":t.newFilters[0],order:t.tview.sort}});case 5:return e.next=7,this.$axios({method:"GET",url:"/tview/saveFilters/",params:n,paramsSerializer:function(e){return n}});case 7:if(r=e.sent,r.data.success){e.next=10;break}return e.abrupt("return",!1);case 10:case"end":return e.stop()}},e,this)}));function t(){return e.apply(this,arguments)}return t}(),buildNewFilter:function(e,t){if("undefined"==typeof t)return!1;e.push({operation:this.defaultOper,fields:[]});var i=!0,s=!1,n=void 0;try{for(var r,a=t[Symbol.iterator]();!(i=(r=a.next()).done);i=!0){var o=r.value;e[0].fields.push({code:o.code,operation:o.operation,value:o.value})}}catch(l){s=!0,n=l}finally{try{i||null==a.return||a.return()}finally{if(s)throw n}}},getFirstElement:function(e){for(var t in e)return e[t]},addFilterRow:function(){this.filter.push({code:this.getFirstElement(this.columns).field,operation:this.operations[0],value:"",select:JSON.parse(JSON.stringify(this.select))})},deleteRowFilter:function(e){this.filter.splice(e,1)},getColumnNameByCode:function(e,t){for(var i in e)if(e[i].field==t)return e[i].em.name;return t},parseFilers:function(e,t){if("undefined"!=typeof e.fields)return this.defaultOper=e.operation,this.parseFilers(e.fields,e.operation),!1;for(var i in e)"undefined"==typeof e[i].fields?(this.$set(e[i],"select",JSON.parse(JSON.stringify(this.select))),this.$set(e[i],"name",this.getColumnNameByCode(this.columns,e[i].code)),this.filter.push(JSON.parse(JSON.stringify(e[i])))):this.parseFilers(e[i].fields,e[i].operation)}},mounted:function(){this.parseFilers(this.tview.filter),this.sourceFilters=this.tview.filter}},j=I,L=(i("5040"),Object(f["a"])(j,x,y,!1,null,null,null)),M=L.exports,V=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"sort-popup__wrapper"},[i("div",{staticClass:"sort-popup__rows"},e._l(e.sortArray,function(t,s){return i("div",{staticClass:"sort-popup__row"},[i("div",{staticClass:"sort-popup__operators-wrapper"},[i("Select",{staticClass:"sort-popup__select",attrs:{defaultText:t.selectedColumn.name?t.selectedColumn.name:t.selectedColumn.code}},e._l(e.columns,function(s){return i("SelectOption",{key:s.field,class:{active:s.field==t.selectedColumn.code},attrs:{value:s.field},nativeOn:{click:function(i){return e.selectCol(t,s)}}},[e._v(e._s(s.em.name?s.em.name:s.field))])}),1),i("Select",{staticClass:"sort-popup__select",attrs:{defaultText:t.default}},e._l(e.sortValues,function(s,n){return i("SelectOption",{key:n,class:{active:t.default==s},nativeOn:{click:function(i){return e.selectSorting(t,n,s)}}},[e._v(e._s(s))])}),1)],1),i("div",{staticClass:"sort-popup__delete-row-icon-wrapper",on:{click:function(t){return t.stopPropagation(),e.deleteRowSort(s)}}},[i("div",{staticClass:"sort-popup__delete-row-icon"},[i("svg",{attrs:{width:"12",height:"12"}},[i("use",{attrs:{"xlink:href":"#plus-white"}})])])])])}),0),i("button",{staticClass:"el-gbtn",on:{click:e.addSortRow}},[e._v("Add sort")])])},J=[],q=(i("28a5"),{props:["tview","columns"],components:{Select:R,SelectOption:A},data:function(){return{sortParams:"",sortArray:[],sortValues:{ASC:"Ascenging",DESC:"Descending"},finalSort:[],sendRequest:!0}},watch:{sortArray:{handler:function(){if(!this.sendRequest)return!1;var e=this;this.buildSortParams(),e.sendRequest=!1,setTimeout(function(){e.sendRequest=!0,e.buildSortParams()},2e3)},deep:!0}},methods:{selectCol:function(e,t){e.selectedColumn.code=t.field,e.selectedColumn.name=t.em.name},selectSorting:function(e,t,i){e.selectedSort=t,e.default=i},buildSortParams:function(){var e=Object(o["a"])(regeneratorRuntime.mark(function e(){var t,s,n,r,a,o,l,c,u,d,p,h;return regeneratorRuntime.wrap(function(e){while(1)switch(e.prev=e.next){case 0:for(t=[],s=!0,n=!1,r=void 0,e.prev=4,a=this.sortArray[Symbol.iterator]();!(s=(o=a.next()).done);s=!0)l=o.value,c=[],c.push(l.selectedColumn.code),c.push(l.selectedSort),t.push(c.join(" "));e.next=12;break;case 8:e.prev=8,e.t0=e["catch"](4),n=!0,r=e.t0;case 12:e.prev=12,e.prev=13,s||null==a.return||a.return();case 15:if(e.prev=15,!n){e.next=18;break}throw r;case 18:return e.finish(15);case 19:return e.finish(12);case 20:return this.finalStrSort=t,this.sortParams=t,this.tview.sort=t,u=this,d=i("4328"),p=d.stringify({sort:t,tviewId:this.tview.id}),e.next=28,this.$store.dispatch("select",{select:{from:u.tview.table,page:1,tview:u.tview.id,where:"undefined"==typeof u.tview.filter.fields?"":u.tview.filter,order:t}});case 28:return e.next=30,this.$axios({method:"GET",url:"/tview/saveSort/",params:p,paramsSerializer:function(e){return p}});case 30:if(h=e.sent,h.data.success){e.next=33;break}return e.abrupt("return",!1);case 33:case"end":return e.stop()}},e,this,[[4,8,12,20],[13,,15,19]])}));function t(){return e.apply(this,arguments)}return t}(),getFirstElement:function(e){for(var t in e)return e[t]},addSortRow:function(){var e={},t=this.getFirstElement(this.columns);this.$set(e,"default","Ascenging"),this.$set(e,"selectedSort","ASC"),this.$set(e,"selectedColumn",{code:t.field,name:t.em.name}),this.$set(e,"popups",{isOpenSelectColumn:!1,isOpenSelectSort:!1}),this.sortArray.push(e)},deleteRowSort:function(e){this.sortArray.splice(e,1)},getColumnNameByCode:function(e,t){for(var i in e)if(e[i].field==t)return e[i].em.name;return t},parseSortParam:function(){var e=this.tview.sort,t=!0,i=!1,s=void 0;try{for(var n,r=e[Symbol.iterator]();!(t=(n=r.next()).done);t=!0){var a=n.value,o=a.trim().split(" ")[0],l=a.trim().split(" ")[1],c={};this.$set(c,"default",this.sortValues[l]),this.$set(c,"selectedSort",l),this.$set(c,"selectedColumn",{code:o,name:this.getColumnNameByCode(this.columns,o)}),this.$set(c,"popups",{isOpenSelectColumn:!1,isOpenSelectSort:!1}),this.sortArray.push(c)}}catch(u){i=!0,s=u}finally{try{t||null==r.return||r.return()}finally{if(i)throw s}}}},mounted:function(){this.sortParams=this.tview.sort,this.parseSortParam()}}),z=q,B=(i("588e"),Object(f["a"])(z,V,J,!1,null,null,null)),W=B.exports,X=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"properties-popup"},[i("div",{staticClass:"properties-list"},e._l(e.columns,function(e){return i("PropertyItem",{key:e.field,attrs:{column:e}})}),1)])},H=[],K=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"property-item-list-item",on:{click:function(e){e.stopPropagation()}}},[i("div",{staticClass:"property-item-small-icon"},[i("svg",{attrs:{width:"6",height:"5"}},[i("use",{attrs:{"xlink:href":"#lines"}})])]),i("div",[i("div",{staticClass:"property-item-big-icon"},[i("img",{attrs:{src:e.column.em.type_info.iconPath,alt:""}})])]),i("div",{staticClass:"property-item-names-wrapper"},[i("div",{staticClass:"property-item-overide-name"},[e._v(e._s(e.column.field))]),i("div",{staticClass:"property-item-real-name"},[e._v(e._s(e.column.field))])]),i("div",{staticClass:"property-item-checkbox-wrapper"},[i("Checkbox",{attrs:{checked:e.column.visible},on:{"update:checked":function(t){return e.$set(e.column,"visible",t)}}})],1)])},U=[],G={props:["column"],components:{Checkbox:c["a"]}},Y=G,Z=(i("6412"),Object(f["a"])(Y,K,U,!1,null,null,null)),Q=Z.exports,ee={props:["columns"],components:{PropertyItem:Q}},te=ee,ie=(i("a805"),Object(f["a"])(te,X,H,!1,null,null,null)),se=ie.exports,ne={mixins:[w["a"]],components:{Table:C,Properties:se,FiltersPopup:M,SortPopup:W},metaInfo:function(){var e=this.table?this.table.name:"";return{title:"Table: ".concat(e)}},data:function(){return{table:!1,popups:{isPropertiesPopupShow:!1,isFiltersPopupShow:!1,isSortPopupShow:!1},propertiesPopupData:{}}},computed:{activeTview:function(){var e=this.$route.params.tview,t=!0,i=!1,s=void 0;try{for(var n,r=this.table.tviews[Symbol.iterator]();!(t=(n=r.next()).done);t=!0){var a=n.value;if(a.id==e)return a}}catch(o){i=!0,s=o}finally{try{t||null==r.return||r.return()}finally{if(i)throw s}}}},methods:{openPopup:function(e){this.popups[e]=!0},closePopup:function(e,t){this.popups[t]=!1},activeTable:function(){this.table=this.$store.getters.getTable(this.$route.params.tableCode),this.propertiesPopupData=this.table.columns},addElement:function(){this.$router.push("/table/".concat(this.table.code,"/add/"))}},mounted:function(){this.activeTable()},watch:{"$route.fullPath":function(){this.activeTable()}}},re=ne,ae=(i("ddc5"),Object(f["a"])(re,s,n,!1,null,null,null));t["default"]=ae.exports},"1ed7":function(e,t,i){"use strict";var s=i("6a34"),n=i.n(s);n.a},2760:function(e,t,i){var s={"./em_check/Field.vue":["590e","chunk-8cc33598"],"./em_file/Field.vue":["21e6","chunk-17835836"],"./em_list/Field.vue":["aef5","chunk-0d958725"],"./em_node/Field.vue":["f2d4","chunk-8bebb14a"],"./em_primary/Field.vue":["b0b8","chunk-73d49498"],"./em_string/Field.vue":["5054","chunk-05ab712a"],"./em_text/Field.vue":["91a4","chunk-f08091fa"]};function n(e){var t=s[e];return t?i.e(t[1]).then(function(){var e=t[0];return i(e)}):Promise.resolve().then(function(){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t})}n.keys=function(){return Object.keys(s)},n.id="2760",e.exports=n},"28a5":function(e,t,i){"use strict";var s=i("aae3"),n=i("cb7c"),r=i("ebd6"),a=i("0390"),o=i("9def"),l=i("5f1b"),c=i("520a"),u=i("79e5"),d=Math.min,p=[].push,h="split",f="length",m="lastIndex",v=4294967295,w=!u(function(){RegExp(v,"y")});i("214f")("split",2,function(e,t,i,u){var g;return g="c"=="abbc"[h](/(b)*/)[1]||4!="test"[h](/(?:)/,-1)[f]||2!="ab"[h](/(?:ab)*/)[f]||4!="."[h](/(.?)(.?)/)[f]||"."[h](/()()/)[f]>1||""[h](/.?/)[f]?function(e,t){var n=String(this);if(void 0===e&&0===t)return[];if(!s(e))return i.call(n,e,t);var r,a,o,l=[],u=(e.ignoreCase?"i":"")+(e.multiline?"m":"")+(e.unicode?"u":"")+(e.sticky?"y":""),d=0,h=void 0===t?v:t>>>0,w=new RegExp(e.source,u+"g");while(r=c.call(w,n)){if(a=w[m],a>d&&(l.push(n.slice(d,r.index)),r[f]>1&&r.index<n[f]&&p.apply(l,r.slice(1)),o=r[0][f],d=a,l[f]>=h))break;w[m]===r.index&&w[m]++}return d===n[f]?!o&&w.test("")||l.push(""):l.push(n.slice(d)),l[f]>h?l.slice(0,h):l}:"0"[h](void 0,0)[f]?function(e,t){return void 0===e&&0===t?[]:i.call(this,e,t)}:i,[function(i,s){var n=e(this),r=void 0==i?void 0:i[t];return void 0!==r?r.call(i,n,s):g.call(String(n),i,s)},function(e,t){var s=u(g,e,this,t,g!==i);if(s.done)return s.value;var c=n(e),p=String(this),h=r(c,RegExp),f=c.unicode,m=(c.ignoreCase?"i":"")+(c.multiline?"m":"")+(c.unicode?"u":"")+(w?"y":"g"),b=new h(w?c:"^(?:"+c.source+")",m),_=void 0===t?v:t>>>0;if(0===_)return[];if(0===p.length)return null===l(b,p)?[p]:[];var C=0,x=0,y=[];while(x<p.length){b.lastIndex=w?x:0;var k,S=l(b,w?p:p.slice(x));if(null===S||(k=d(o(b.lastIndex+(w?0:x)),p.length))===C)x=a(p,x,f);else{if(y.push(p.slice(C,x)),y.length===_)return y;for(var P=1;P<=S.length-1;P++)if(y.push(S[P]),y.length===_)return y;x=C=k}}return y.push(p.slice(C)),y}]})},"2d19":function(e,t,i){},"2f21":function(e,t,i){"use strict";var s=i("79e5");e.exports=function(e,t){return!!e&&s(function(){t?e.call(null,function(){},1):e.call(null)})}},4735:function(e,t,i){},5040:function(e,t,i){"use strict";var s=i("4735"),n=i.n(s);n.a},"55dd":function(e,t,i){"use strict";var s=i("5ca1"),n=i("d8e8"),r=i("4bf8"),a=i("79e5"),o=[].sort,l=[1,2,3];s(s.P+s.F*(a(function(){l.sort(void 0)})||!a(function(){l.sort(null)})||!i("2f21")(o)),"Array",{sort:function(e){return void 0===e?o.call(r(this)):o.call(r(this),n(e))}})},"588e":function(e,t,i){"use strict";var s=i("a6e2"),n=i.n(s);n.a},"5c77":function(e,t,i){},6412:function(e,t,i){"use strict";var s=i("5c77"),n=i.n(s);n.a},"6a34":function(e,t,i){},"6e3e":function(e,t,i){"use strict";var s=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",[i(e.columnContent,{tag:"component",attrs:{fieldValue:e.params.value,fieldSettings:e.params.settings,mode:e.mode},on:{onChange:e.changeValue,openEdit:e.openEdit}})],1)},n=[],r={props:["params","mode"],computed:{columnContent:function(){var e=this;return"undefined"!=typeof this.params&&function(){return i("2760")("./".concat(e.params.fieldName,"/Field.vue"))}}},methods:{changeValue:function(e){this.$emit("onChange",e)},openEdit:function(){this.$emit("openEdit")}}},a=r,o=i("2877"),l=Object(o["a"])(a,s,n,!1,null,null,null);t["a"]=l.exports},"7ecb":function(e,t,i){},"7f97":function(e,t,i){"use strict";var s=i("8663"),n=i.n(s);n.a},8663:function(e,t,i){},"996b":function(e,t,i){"use strict";var s=i("7ecb"),n=i.n(s);n.a},a6e2:function(e,t,i){},a805:function(e,t,i){"use strict";var s=i("2d19"),n=i.n(s);n.a},ab0e:function(e,t,i){},d230:function(e,t,i){"use strict";i("96cf");var s=i("3b8d");i("ac4d"),i("8a81"),i("ac6a");t["a"]={methods:{getTableByCode:function(e,t){var i=!0,s=!1,n=void 0;try{for(var r,a=t[Symbol.iterator]();!(i=(r=a.next()).done);i=!0){var o=r.value;if(o.code==e)return o}}catch(l){s=!0,n=l}finally{try{i||null==a.return||a.return()}finally{if(s)throw n}}},getDefaultTview:function(e){var t=!0,i=!1,s=void 0;try{for(var n,r=e.tviews[Symbol.iterator]();!(t=(n=r.next()).done);t=!0){var a=n.value;if("1"===a.default)return a}}catch(o){i=!0,s=o}finally{try{t||null==r.return||r.return()}finally{if(i)throw s}}},setTviewSetting:function(){var e=Object(s["a"])(regeneratorRuntime.mark(function e(t,s,n){var r,a,o,l,c,u;return regeneratorRuntime.wrap(function(e){while(1)switch(e.prev=e.next){case 0:if(r=this.getDefaultTview(t),a=r.settings,o=i("4328"),"undefined"==typeof a[s])a={},this.$set(a,s,n);else for(l in n)a[s][l]=n[l];return c=o.stringify({tviewId:r.id,params:a}),e.next=5,this.$axios({method:"POST",data:c,url:"/el/setTviewSettings/"});case 5:if(u=e.sent,u.data.success){e.next=8;break}return e.abrupt("return",!1);case 8:r.settings=a;case 9:case"end":return e.stop()}},e,this)}));function t(t,i,s){return e.apply(this,arguments)}return t}()}}},ddc5:function(e,t,i){"use strict";var s=i("125a"),n=i.n(s);n.a},e902:function(e,t,i){"use strict";var s=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"checkbox"},[i("label",{staticClass:"checkbox__label"},[i("input",{directives:[{name:"model",rawName:"v-model",value:e.isCheched,expression:"isCheched"}],staticClass:"checkbox__input",attrs:{type:"checkbox"},domProps:{checked:Array.isArray(e.isCheched)?e._i(e.isCheched,null)>-1:e.isCheched},on:{change:[function(t){var i=e.isCheched,s=t.target,n=!!s.checked;if(Array.isArray(i)){var r=null,a=e._i(i,r);s.checked?a<0&&(e.isCheched=i.concat([r])):a>-1&&(e.isCheched=i.slice(0,a).concat(i.slice(a+1)))}else e.isCheched=n},function(t){return e.change()}]}}),i("span",[i("svg",{attrs:{width:"7",height:"7"}},[i("use",{attrs:{"xlink:href":"#check"}})])])])])},n=[],r={props:{checked:{type:Boolean,default:!1}},data:function(){return{isCheched:!1}},watch:{checked:function(e,t){this.isCheched=e}},methods:{change:function(){this.$emit("update:checked",this.isCheched),this.$emit("change",this.isCheched)}},mounted:function(){this.isCheched=this.checked}},a=r,o=(i("7f97"),i("2877")),l=Object(o["a"])(a,s,n,!1,null,null,null);t["a"]=l.exports}}]);
//# sourceMappingURL=chunk-22f5ed81.9dc72419.js.map