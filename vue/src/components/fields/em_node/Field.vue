<template>
	<div class="em-node" :class="fieldSettings.disabled == 'true' ? 'disabled' : ''">
		<List
			:searchText.sync="query"
			@onopen="getNodes()"
			:settings="{placeholder: $t('empty')}"
			:multiple="fieldSettings.multiple === 'true'"
		>
			<template v-slot:selected>
				<ListOption
					v-for="localFieldItem in selectedItems"
					@remove="removeItem(localFieldItem)"
					@click.native.stop="openNodePage(localFieldItem)"
					:key="localFieldItem.id"
					:current=true
					class="em-node__tag"
				>{{ localFieldItem.name }}</ListOption>
			</template>
			<ListOption
				v-for="listItem in list"
				:key="listItem.code"
				@select="selectItem(listItem)"
			>{{ listItem.name }}</ListOption>
			<div class="em-node__footer" @click="createItem()" v-if="newTag">
				<div class="em-node__btn"> {{$t('create')}} </div>
				<div class="em-node__new-tag"> {{newTag}} </div>
			</div>
		</List>
		<DetailPopup
			:visible.sync="showDetail"
			:tableCode="detailTableCode"
			:name="detailName"
			:element="currentElement"
			@createElement="createElement"
		></DetailPopup>
		<DetailPopup
			:visible.sync="showDetailInfo"
			:tableCode="detailTableCode"
			:name="detailName"
			:id="detailInfoId"
		></DetailPopup>
	</div>
</template>
<script>
	import DetailPopup from '@/components/popups/DetailPopup';
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
				showDetailInfo:false,
				currentElement: false,
				list: [],
				query: '',
				localFieldValue: this.fieldValue,
				storableData: [],
				newTag: '',
				nodesTimeout: '',
				detailInfoId:false
			};
		},
		watch:
		{
			query(value)
			{
				this.newTag = this.query;
				this.getNodes();
			}
		},
		computed:
		{
			selectedItems()
			{
				if (!this.localFieldValue || !this.localFieldValue.length)
					return [];

				return this.localFieldValue;
			}
		},
		methods:
		{
			openNodePage(node)
			{
				this.detailTableCode = this.fieldSettings.nodeTableCode;
				this.detailName = node.name;
				this.detailInfoId = node.primaryKey;
				this.showDetailInfo = true;
			},
			async createElement(data, result)
			{
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
				if( !(this.localFieldValue instanceof Array) )
					this.localFieldValue = [];

				if(this.localFieldValue.indexOf(listItem) !== -1)
					return;

				if(this.fieldSettings.multiple === 'true')
					this.localFieldValue.push(listItem);
				else
					this.localFieldValue = [listItem];

				this.$emit('onChange', {
					value    : this.localFieldValue,
					settings : this.fieldSettings,
				});
			},

			/**
			 * Удаление выбранной опции
			 */
			removeItem(localFieldItem)
			{
				if(this.fieldSettings.multiple === 'true')
				{
					let keyIndex = this.localFieldValue.indexOf(localFieldItem);
					this.localFieldValue.splice(keyIndex,1);
				}
				else
					this.localFieldValue = '';

				this.$emit('onChange', {
					value     : this.localFieldValue,
					settings  : this.fieldSettings
				});
			},
		}
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
		.list__search
		{
			width:calc(100% + 10px);
			min-width: 200px;
		}
	}
	.detail-field-box .em-node .list{padding-left:0px;}
	.em-node__tag span
	{
		height: 28px;
		line-height: 28px;
		padding:0 14px 0 10px;
		position: relative;
		&::after{
			content: '';
			position: absolute;
			width: 5px;
			height: 5px;
			right:5px;
			top:5px;
			border-top:2px solid #929292;
			border-right:2px solid #929292;
		}
	}
	.em-node
	{
		.list__search-popup-head .em-node__tag span{padding-right: 30px;}
		.list-option__remove{right:13px;}
	}

	.em-node.disabled
	{
		pointer-events: none;
  		opacity: 0.5;
	}
</style>
