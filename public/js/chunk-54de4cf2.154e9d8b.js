(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-54de4cf2"],{"2ef6":function(e,t,n){"use strict";n("4012")},4012:function(e,t,n){},c1f4:function(e,t,n){"use strict";n.r(t);var u=function(){var e=this,t=e._self._c;return t("div",{staticClass:"em-matrix-filter"},[e.value?t("Select",{staticClass:"filters-popup__select",attrs:{defaultText:e.value.field&&e.value.field.code||e.$t("empty")}},e._l(e.columns,(function(n,u){return t("SelectOption",{key:u,nativeOn:{click:function(t){return e.changeFieldValue(n)}}},[e._v(e._s(n))])})),1):e._e(),e.showInput?t("input",{staticClass:"filters-popup__filter-input el-inp",attrs:{type:"text",placeholder:e.$t("value")},domProps:{value:e.filter.value.value},on:{keyup:e.changeInputValue}}):e._e()],1)},i=[],a=(n("ac6a"),n("8615"),n("96cf"),n("3b8d")),s={props:["filter","settings"],data:function(){return{isManyToMany:!1,columns:null,value:{field:null,value:null}}},computed:{showInput:function(){return!/IS NOT EMPTY|IS EMPTY/.test(this.filter.operation)}},methods:{changeInputValue:function(e){this.value.value=e.target.value,this.saveValue()},changeFieldValue:function(e){this.value.field=e,this.saveValue()},saveValue:function(){this.$emit("onchange",this.value)},getColumns:function(){var e=Object(a["a"])(regeneratorRuntime.mark((function e(){var t,n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:t=this.$store.getters.getColumns(this.settings.finalTableCode),n=Object.values(t).map((function(e){return e.field})),this.$set(this,"columns",n);case 3:case"end":return e.stop()}}),e,this)})));function t(){return e.apply(this,arguments)}return t}(),setDefaultValue:function(){this.filter.value&&this.$set(this,"value",JSON.parse(JSON.stringify(this.filter.value)))}},mounted:function(){var e=Object(a["a"])(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:this.getColumns(),this.setDefaultValue();case 2:case"end":return e.stop()}}),e,this)})));function t(){return e.apply(this,arguments)}return t}()},l=s,r=(n("2ef6"),n("2877")),c=Object(r["a"])(l,u,i,!1,null,null,null);t["default"]=c.exports}}]);
//# sourceMappingURL=chunk-54de4cf2.154e9d8b.js.map