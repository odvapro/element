<template>
	<div class="em_list">
		<List
			:selectVal="selectedItem"
			:list="fieldSettings.list"
			@onChange="changeData"
		/>
	</div>
</template>
<script>
	import List from '@/components/forms/List.vue';
	export default
	{
		components: { List },
		props: ['fieldValue','fieldSettings','mode', 'view'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				showPopup: false,
				selectedItem: ''
			}
		},
		methods:
		{
			/**
			 * Изменить тип поля
			 */
			async changeData(data)
			{
				this.$emit('onChange', {
					value: data.value,
					settings: this.fieldSettings
				});
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			for (var settingItem of this.fieldSettings.list)
			{
				if (settingItem.key != this.fieldValue)
					continue;

				this.selectedItem = settingItem.value;
				break;
			}
		}
	}
</script>
<style lang="scss">
	.em_list
	{
		width:100%;
		height:100%;
		position: absolute;
		top:0px;
		left:0px;
		cursor: pointer;
		.list{padding-left:10px; }
	}
</style>