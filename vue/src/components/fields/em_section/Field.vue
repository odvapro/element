<template>
	<div class="em-section">
		<List
			:searchText.sync="query"
			@onopen="getNodes()"
			:settings="{placeholder: $t('empty')}"
			:multiple="fieldSettings.multiple === 'true'"
		>
			<template v-slot:selected>
				<ListOption
					v-for="localFieldItem in localFieldValue"
					@remove="removeItem(localFieldItem)"
					:key="localFieldItem.id"
					:current=true
				>{{ localFieldItem.name }}</ListOption>
			</template>
			<ListSection
				v-for="listItem in list"
				:key="listItem.code"
				@select="selectItem"
				@remove="removeItem"
				:settings=fieldSettings
				:item=listItem
				:listValue=fieldValue
				:selected=isSelected(listItem)
			>{{ listItem.name }}</ListSection>
			<div class="em-section__footer" @click="createItem()" v-if="newTag">
				<div class="em-section__btn"> {{$t('create')}} </div>
				<div class="em-section__new-tag"> {{newTag}} </div>
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
	// начало
	// по умолчанию достаем разделы первого уровня с пометкой - есть дочерние или нет
	// при раскрытии докгружаем подразделы с дочерними и тд
	//
	// поиск
	// при поиске ищем разделы по совпадению в названии и подгужаем родителей и дочерние
	//
	// множественное поле
	import DetailPopup from '@/components/popups/DetailPopup';
	import ListSection from '@/components/forms/ListSection';
	export default
	{
		components:{DetailPopup,ListSection},
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
			}
		},
		mounted()
		{
			this.$set(this, 'localFieldValue', this.fieldValue);
		},
		methods:
		{
			isSelected(listItem)
			{
				let selected = false;
				for (let selectedSectionKey in this.localFieldValue)
				{
					if(listItem.id == this.localFieldValue[selectedSectionKey].id)
					{
						selected = true
						break;
					}
				}
				return selected;
			},
			async createElement(data, result)
			{
				if(result.data.success == true)
				{
					let listItem = {};
					listItem['id'] = result.data.lastid;
					listItem[this.fieldSettings.sectionSearchCode] = this.newTag;
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
				let table = this.$store.getters.getTable(this.fieldSettings.sectionTableCode);
				for (let column in table.columns)
					this.currentElement[column] = '';

				this.currentElement[this.fieldSettings.sectionSearchCode] = this.newTag;
			},
			popupForCreateElement()
			{
				this.bindDefaultColumnValues();
				this.detailTableCode = this.fieldSettings.sectionTableCode;
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
					data.append('sectionTableCode', this.fieldSettings.sectionTableCode);
					data.append('sectionFieldCode', this.fieldSettings.sectionFieldCode);
					data.append('sectionSearchCode', this.fieldSettings.sectionSearchCode);
					data.append('sectionParentsFieldCode', this.fieldSettings.sectionParentsFieldCode);
					data.append('q', this.query);

					let result = await this.$axios({
						method : 'POST',
						data   : data,
						headers: { 'Content-Type': 'multipart/form-data' },
						url    : '/field/em_section/index/autoComplete/'
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
				let resArray = [];
				if(this.fieldSettings.multiple === 'true')
				{
					this.localFieldValue.push(listItem);
					this.localFieldValue.forEach(item=>{
						resArray.push(item);
					});
				}
				else
				{
					this.localFieldValue = [listItem];
					resArray.push(listItem);
				}

				this.$emit('onChange', {
					value    : resArray,
					settings : this.fieldSettings,
				});
			},

			/**
			 * Удаление выбранной опции
			 */
			removeItem(localFieldItem)
			{
				let resArray = [];
				if(this.fieldSettings.multiple === 'true')
				{
					let keyIndex = this.localFieldValue.indexOf(localFieldItem);
					this.localFieldValue.splice(keyIndex,1);
					this.localFieldValue.forEach(item=>{
						resArray.push(item);
					});
				}
				else
					this.localFieldValue = [];

				this.$emit('onChange', {
					value     : !resArray.lenght ? '' : resArray,
					settings  : this.fieldSettings
				});
			},
		},
	};
</script>
<style lang="scss">
	.em-section__new-tag
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
	.em-section__footer
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
	.em-section__btn
	{
		font-size: 10px;
		color: #7C7791;
		margin-right: 15px;
	}
	.em-section
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
	.detail-field-box .em-section .list{padding-left:0px;}
</style>
