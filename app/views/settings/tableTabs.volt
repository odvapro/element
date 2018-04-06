<div id="app">
	<h3>Вкладки</h3><br/>
	<el-tag
		:key="tag.id"
		v-for="tag in dynamicTags"
		:closable="tag.closable"
		:disable-transitions="false"
		@click.native.stop="selectTab(tag)"
		:class="{ active: tag.selected }"
		@close="handleClose(tag)">
		{{ '{{tag.name}}' }}
	</el-tag>
	<el-input
		class="input-new-tag"
		v-if="inputVisible"
		v-model="inputValue"
		ref="saveTagInput"
		size="mini"
		@keyup.enter.native="handleInputConfirm"
		@blur="handleInputConfirm"
	></el-input>
	<el-button v-else class="button-new-tag" size="small" @click="showInput">+ Новая Вкладка</el-button>
	<br/>
	<br/>
	<br/>
	<h3>Содержимое вкладки</h3><br/>
	<el-transfer
		v-model="selectedFields"
		:props="{key: 'value', label: 'desc',placeholder:'Нет полей'}"
		:titles="['Доступные поля', 'Содержимое Вкалдки']"
		:data="filterFields"
	></el-transfer>
</div>
<script>
	var Main = {
		data() {
			return {
				fields: [
					{value:1,desc:'dasdas',tab:1},
					{value:2,desc:'dasdas',tab:1},
					{value:3,desc:'dasdas',tab:1},
				],
				selectedFields: [],
				dynamicTags: [
					{
						name:'Основаное',
						id:1,
						closable:false,
						selected:false
					},
					{
						name:'Основаное2',
						id:2,
						closable:true,
						selected:true
					},
					{
						name:'Основаное3',
						id:3,
						closable:true,
						selected:false
					}

				],
				inputVisible: false,
				inputValue: ''
			};
		},
		computed:
		{
			filterFields()
			{
				let resultFields = [];
				let selectedTabId = false;
				this.dynamicTags.forEach(function(aTag)
				{
					if(aTag.selected === true)
						selectedTabId = aTag.id;
				});
				this.fields.forEach(function(aField)
				{
					if(aField.tab == selectedTabId)
						resultFields.push(aField);
				})
				return resultFields;
			}
		},
		methods:
		{
			handleClose(tag)
			{
		        this.dynamicTags.splice(this.dynamicTags.indexOf(tag), 1);
		    },
		    selectTab(tag)
		    {
		    	let setStatus = !tag.selected;
				this.dynamicTags.forEach(function(aTag)
				{
		    		aTag.selected = false;
				})
		    	tag.selected = setStatus;
		    },
			showInput()
			{
				this.inputVisible = true;
				this.$nextTick(_ => {
					this.$refs.saveTagInput.$refs.input.focus();
				});
			},
			handleInputConfirm()
			{
				let inputValue = this.inputValue;
				if (inputValue)
					this.dynamicTags.push({
						name:inputValue,
						id:3,
						closable:true,
						selected:false
					});
				this.inputVisible = false;
				this.inputValue = '';
			}
		}
	}
	var Ctor = Vue.extend(Main)
	new Ctor().$mount('#app')
</script>
<style>
  .el-tag{cursor: pointer;}
  .el-tag + .el-tag {margin-left: 10px; }
  .el-tag.active{
  	color: #fff;
  	background-color: #409EFF;
  }
  .el-tag.active .el-icon-close{color:#fff;}
  .button-new-tag {
    margin-left: 10px;
    height: 32px;
    line-height: 30px;
    padding-top: 0;
    padding-bottom: 0;
  }
  .input-new-tag {
    width: 90px;
    margin-left: 10px;
    vertical-align: bottom;
  }
  .el-transfer-panel{
  	width:400px;
  }
</style>