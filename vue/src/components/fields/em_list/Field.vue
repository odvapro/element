<template>
	<div class="em-list">
		<List
			:searchText.sync="searchText"
			:settings="{placeholder: $t('empty')}"
		>
			<template v-slot:selected>
				<ListOption
					v-for="listItem in selectedItems"
					:key="listItem.key"
					@remove="removeItem(listItem)"
				>{{ listItem.value }}</ListOption>
			</template>
			<ListOption
				v-for="listItem in itemsList"
				:key="listItem.key"
				@select="selectItem(listItem)"
			>{{ listItem.value }}</ListOption>
		</List>
	</div>
</template>
<script>
	import List from '@/components/forms/List.vue';
	import ListOption from '@/components/forms/ListOption.vue';
	export default
	{
		components: { List, ListOption },
		props: ['fieldValue','fieldSettings','mode', 'view'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				showPopup: false,
				searchText:'',
				localFieldValue:this.fieldValue
			}
		},

		computed:
		{
			/**
			 * Возврощает список выбранных элементов
			 * [{key:<key code>,value:<value>}]
			 */
			selectedItems()
			{
				if(this.localFieldValue.length == 0)
					return [];
				return this.fieldSettings.list.filter(listItem=>{
					if(this.localFieldValue.indexOf(listItem.key) !== -1)
						return true;
				});
			},

			/**
			 * Выдаем отфильтрованный список опций
			 */
			itemsList()
			{
				return this.fieldSettings.list.filter(listItem=>{
					if(listItem.value.indexOf(this.searchText) !== -1)
						return true;
				});
			},
		},
		methods:
		{
			/**
			 * Выбор опции
			 */
			selectItem(listItem)
			{
				this.localFieldValue = [listItem.key];
				this.$emit('onChange', {
					value: this.localFieldValue,
					settings: this.fieldSettings
				});
			},

			/**
			 * Удаление выбранной опции
			 */
			removeItem(listItem)
			{
				let keyIndex = this.localFieldValue.indexOf(listItem.key);
				this.localFieldValue.splice(keyIndex,1);
				let curValue = (this.localFieldValue.length == 0)?'':this.localFieldValue;
				this.$emit('onChange', {
					value     : curValue,
					settings  : this.fieldSettings
				});
			}
		}
	}
</script>
<style lang="scss">
	.em-list
	{
		width:100%;
		height:100%;
		position: absolute;
		top:0px;
		left:0px;
		cursor: pointer;
		.list{padding-left:10px; }
	}
	.detail-field-box .em-list .list{padding-left:0px;}
</style>