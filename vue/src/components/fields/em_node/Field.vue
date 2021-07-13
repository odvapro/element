<template>
	<div class="em-node">
		<List
			:searchText.sync="query"
			@onopen="getNodes()"
			:settings="{placeholder: $t('empty')}"
		>
			<template v-slot:selected>
				<ListOption
					v-if="localFieldValue.id"
					@remove="removeItem"
					:current=true
				>{{ localFieldValue.name }}</ListOption>
			</template>
			<ListOption
				v-for="listItem in list"
				:key="listItem.code"
				@select="selectItem(listItem)"
			>{{ listItem.name }}</ListOption>
			<div class="em-node__footer" @click="createItem()" v-if="newTag">
				<div class="em-node__btn">
					{{$t('create')}}
				</div>
				<div class="em-node__new-tag">
					{{newTag}}
				</div>
			</div>
		</List>
		<DetailPopup
			:visible.sync="showDetail"
			:tableCode="detailTableCode"
			:name="detailName"
			:element="currentElement"
			@createElement="createElement"
		></DetailPopup>
	</div>
</template>
<script>
	import DetailPopup from '@/components/popups/DetailPopup';
	import qs from 'qs';
	export default
	{
		components:{DetailPopup},
		props: ['fieldValue','fieldSettings','mode','view'],
		data()
		{
			return {
				detailTableCode: false,
				detailName: false,
				showDetail: false,
				currentElement: false,
				list: [],
				query: '',
				localFieldValue:this.fieldValue,
				newTag: '',
				nodesTimeout: '',
			};
		},
		watch:
		{
			query(value)
			{
				this.newTag = this.query;
				this.getNodes();
			},
			fieldValue:
			{
				handler(value)
				{
					this.$set(this, 'localFieldValue', value);
				},
				deep: true,
			},
		},
		mounted()
		{
			this.$set(this, 'localFieldValue', this.fieldValue);
		},
		methods:
		{
			async createElement(data)
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(data.tableCode);
				let setColumns  = [];
				let setValues  = [];
				for(let fieldCode in data.selectedElement)
				{
					if(primaryKeyCode == fieldCode) continue;
					setColumns.push(fieldCode);
					setValues.push(data.selectedElement[fieldCode]);
				}

				let insertData = qs.stringify({
					insert:
					{
						table   :data.tableCode,
						columns :setColumns,
						values  :[setValues],
					},
				});
				let result = await this.$axios.post('/el/insert/',insertData);
				if(result.data.success == true)
				{
					let listItem = {};
					listItem['id'] = result.data.lastid;
					listItem[this.fieldSettings.nodeSearchCode] = this.newTag;
					this.selectItem(listItem);
					this.ElMessage(this.$t('elMessages.element_created'));
					this.showDetail = false;
				}
				else
					this.ElMessage.error(this.$t('elMessages.cant_create_element'));
			},
			bindDefaultColumnValues()
			{
				this.currentElement = [];
				let table = this.$store.getters.getTable(this.fieldSettings.nodeTableCode);
				for (let column in table.columns)
					this.currentElement[column] = '';

				this.currentElement[this.fieldSettings.nodeSearchCode] = this.newTag;
			},
			popupForCreateElement()
			{
				this.bindDefaultColumnValues();
				this.detailTableCode = this.fieldSettings.nodeTableCode;
				this.detailName      = 'tableAddElement';
				this.showDetail      = true;
			},
			/**
			 * Send change current value
			 */
			changeValue(newValue)
			{
				this.$emit('onChange', {
					value     : newValue,
					settings  : this.fieldSettings,
				});
			},

			/**
			 * Get nodes from server
			 */
			async getNodes()
			{
				clearTimeout(this.nodesTimeout);
				this.nodesTimeout = setTimeout(async ()=> {
					var data = new FormData();
					data.append('nodeFieldCode', this.fieldSettings.nodeFieldCode);
					data.append('nodeTableCode', this.fieldSettings.nodeTableCode);
					data.append('nodeSearchCode', this.fieldSettings.nodeSearchCode);
					data.append('q', this.query);

					let result = await this.$axios({
						method : 'POST',
						data   : data,
						headers: { 'Content-Type': 'multipart/form-data' },
						url    : '/field/em_node/index/autoComplete/'
					});

					if (!result.data.success)
						return false;
					this.list = result.data.result;
				}, 1000);
			},
			createItem()
			{
				this.popupForCreateElement();
			},
			/**
			 * Выбор опции
			 */
			selectItem(listItem)
			{
				this.localFieldValue = listItem;
				this.$emit('onChange', {
					value    : this.localFieldValue,
					settings : this.fieldSettings,
				});
			},

			/**
			 * Удаление выбранной опции
			 */
			removeItem()
			{
				this.localFieldValue = '';
				this.$emit('onChange', {
					value     : this.localFieldValue,
					settings  : this.fieldSettings,
				});
			},
		},
	};
</script>
<style lang="scss">
	.em-node__new-tag
	{
		height: 20px;
		line-height: 20px;
		padding: 0 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		color: #7C7791;
		white-space: nowrap;
		text-overflow: ellipsis;
		max-width: calc(100% - 20px);
		overflow: hidden;
	}
	.em-node__footer
	{
		position: absolute;
		bottom: -28px;
		left: -1px;
		height: 29px;
		display: flex;
		padding: 0 9px;
		align-items: center;
		background-color: #f7f8f9;
		width: calc(100% + 2px);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-top: none;
		border-radius: 2px;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
	}
	.em-node__btn
	{
		font-size: 10px;
		color: #7C7791;
		margin-right: 15px;
	}
	.em-node
	{
		width:100%;
		height:100%;
		position: absolute;
		top:0px;
		left:0px;
		cursor: pointer;
		.list{padding-left:10px; }
	}
	.detail-field-box .em-node .list{padding-left:0px;}
</style>
