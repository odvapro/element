(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5eafd914"],{"38ee":function(e,t,i){},aef5:function(e,t,i){"use strict";i.r(t);var l=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"em-list"},[i("List",{attrs:{searchText:e.searchText,settings:{placeholder:e.$t("empty")},multiple:"true"===e.fieldSettings.multiple},on:{"update:searchText":function(t){e.searchText=t},"update:search-text":function(t){e.searchText=t}},scopedSlots:e._u([{key:"selected",fn:function(){return e._l(e.selectedItems,(function(t){return i("ListOption",{key:t.key,attrs:{current:!0},on:{remove:function(i){return e.removeItem(t)}}},[e._v(e._s(t.value))])}))},proxy:!0}])},e._l(e.itemsList,(function(t){return i("ListOption",{key:t.key,on:{select:function(i){return e.selectItem(t)}}},[e._v(e._s(t.value))])})),1)],1)},s=[],n={props:["fieldValue","fieldSettings","mode","view"],data:function(){return{showPopup:!1,searchText:"",localFieldValue:this.fieldValue}},computed:{selectedItems:function(){var e=this;return this.fieldSettings.list&&this.localFieldValue&&this.localFieldValue.length?this.fieldSettings.list.filter((function(t){return-1!==e.localFieldValue.indexOf(t.key)})):[]},itemsList:function(){var e=this;return this.fieldSettings.list&&this.fieldSettings.list.length?this.fieldSettings.list.filter((function(t){return-1!==t.value.indexOf(e.searchText)})):[]}},watch:{fieldValue:{handler:function(e){this.$set(this,"localFieldValue",e)},deep:!0}},methods:{selectItem:function(e){this.localFieldValue instanceof Array||(this.localFieldValue=[]),-1===this.localFieldValue.indexOf(e.key)&&(this.fieldSettings.multiple?this.localFieldValue.push(e.key):this.localFieldValue=[e.key],this.$emit("onChange",{value:this.localFieldValue,settings:this.fieldSettings}))},removeItem:function(e){var t=this.localFieldValue.indexOf(e.key);this.localFieldValue.splice(t,1);var i=0==this.localFieldValue.length?"":this.localFieldValue;this.$emit("onChange",{value:i,settings:this.fieldSettings})}}},a=n,u=(i("bb29"),i("2877")),c=Object(u["a"])(a,l,s,!1,null,null,null);t["default"]=c.exports},bb29:function(e,t,i){"use strict";i("38ee")}}]);
//# sourceMappingURL=chunk-5eafd914.4676a513.js.map