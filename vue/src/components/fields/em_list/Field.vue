<template>
	<ListView v-if="selectedItem" :selectVal="selectedItem" :list="settings.list" @onChange="changeData"/>
</template>
<script>
	import ListView from '@/components/layouts/ListView.vue';
	export default
	{
		components: { ListView },
		props: ['fieldValue','fieldSettings','fieldCode', 'tableCode','mode', 'view'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				showPopup: false,
				selectedItem: '',
				settings: {}
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
					settings: this.settings,
					tableCode: this.tableCode,
					fieldCode: this.fieldCode
				});
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.settings = this.fieldSettings;
			for (var settingItem of this.settings.list)
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
	.em-list__item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
		position: relative;
		cursor: pointer;
	}
	.em-list__search-popup-head
	{
		height: 49px;
		display: flex;
		align-items: center;
		padding: 0 9px;
		font-size: 10px;
		background-color: rgba(103, 115, 135, 0.1);
		color: rgba(25, 28, 33, 0.4);
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
	}
	.em-list__search
	{
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		width: 193px;
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		background: white;
		position: absolute;
		top: -1px;
		background: white;
		z-index: 2;
		left: -1px;
	}
	.em-list__search-icon
	{
		width: 6px;
		height: 14px;
		display: flex;
		align-items: center;
		margin-right: 8px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: contain;
		}
	}
	.em-list__search-item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
		position: relative;
	}
	.em-list__search-popup-item
	{
		display: flex;
		padding: 0 9px;
		align-items: center;
		height: 30px;
		cursor: pointer;
		&:hover
		{
			background-color: rgba(103, 115, 135, 0.1);
		}
	}
</style>