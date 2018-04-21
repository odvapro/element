<div id="app">
	<h3>Вкладки</h3><br/>
	<el-tag
		:key="tag.id"
		v-for="tag in dynamicTabs"
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
	></el-input>
	<el-button v-else class="button-new-tag" size="small" @click="showInput">+ Новая Вкладка</el-button>
	<br/>
	<br/>
	<br/>
	<h3>Содержимое вкладки</h3><br/>
	<el-transfer
		v-model="selectedFields"
		:props="{key: 'value', label: 'desc'}"
		:titles="transferTitles"
		:data="filterFields"
		@change="chnageTransfer"
	></el-transfer>
</div>
<script>
	var Main = {
		data() {
			return {
				fields         : {{ fieldsForTabsJSON }},
				tableName      : '{{ tableInfo['table'] }}',
				selectedFields : [],
				selectedTabId  : false,
				dynamicTabs    : {{ tabsJson }},
				transferTitles : ['Доступные поля', 'Содержимое Вкалдки'],
				inputVisible   : false,
				inputValue     : ''
			};
		},
		computed:
		{
			// show available fields
			filterFields()
			{
				let resultFields = [];
				let self = this;
				this.fields.forEach(function(aField)
				{
					if(aField.tab == false || aField.tab ==  self.selectedTabId)
						resultFields.push(aField);
				})
				return resultFields;
			}
		},
		methods:
		{
			// remove tab, and remove all fields from tab
			// remove tab from database
			handleClose(tag)
			{
				this.dynamicTabs.splice(this.dynamicTabs.indexOf(tag), 1);
				this.fields.forEach(function(aField)
				{
					if(aField.tab == tag.id)
						aField.tab = false;
				});
				$.ajax({
					url      : el.config.baseUri+'settings/removeTab',
					type     :'POST',
					dataType :'json',
					data     : {tabId:tag.id}
				}).done(function(e)
				{
					if(e.success !== true)
						return el.message('что-то пошло не так');
				});
			},
			selectTab(tag)
			{
				let setStatus = !tag.selected;
				this.selectedTabId = (setStatus)?tag.id:false;
				this.dynamicTabs.forEach(function(aTag)
				{
					aTag.selected = false;
				})
				tag.selected = setStatus;

				// select seleted tab fields
				this.selectedFields = [];
				let self = this;
				this.fields.forEach(function(aField)
				{
					aField.disabled = !setStatus;
					if(aField.tab == tag.id)
						self.selectedFields.push(aField.value);
				});
			},
			showInput()
			{
				this.inputVisible = true;
				this.$nextTick(_ => {
					this.$refs.saveTagInput.$refs.input.focus();
				});
			},
			// making new tab
			handleInputConfirm()
			{
				let self = this;
				$.ajax({
					url      : el.config.baseUri+'settings/addTab',
					type     :'POST',
					dataType :'json',
					data     : {tableName:self.tableName,tabName:self.inputValue}
				}).done(function(e)
				{
					if(e.success !== true)
						return el.message('что-то пошло не так')
					self.dynamicTabs.push({
						name     :inputValue,
						id       :e.id,
						closable :true,
						selected :false
					});
				});

				let inputValue = this.inputValue;
				if (inputValue)
					this.inputVisible = false;
				this.inputValue = '';
			},
			// sets fields current tab or set them to false
			// if fields moved to right set them tab
			// else false
			chnageTransfer(rightFields,direction)
			{
				let self = this;
				let changedFields = [];
				this.fields.forEach(function(aField)
				{
					if(aField.tab == self.selectedTabId && !rightFields.includes(aField.value))
					{
						aField.tab = false;
						changedFields.push({field:aField.value,tab:false});
					}
					if(aField.tab != self.selectedTabId && rightFields.includes(aField.value))
					{
						aField.tab = self.selectedTabId;
						changedFields.push({field:aField.value,tab:self.selectedTabId});
					}
				});
				$.ajax({
					url      : el.config.baseUri+'settings/updateTabs',
					type     :'POST',
					dataType :'json',
					data     : {tableName:self.tableName, changedFields:changedFields}
				}).done(function(e)
				{
					if(e.success !== true)
						return el.message('что-то пошло не так');
				});
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