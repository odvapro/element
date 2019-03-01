<template>
	<div class="em-list__wrapper" @click="togglePopup()">
		<div class="em-list__item-wrapper">
			<div class="em-list__item">
				{{selectedItem}}
			</div>
		</div>
		<div class="em-list__search" v-if="showPopup" v-click-outside="closePopup">
			<div class="em-list__search-popup-head">
				<div class="em-list__search-item">
					{{selectedItem}}
				</div>
			</div>
			<div class="em-list__search-popup-item" v-for="listItem in settings.list">
				<div class="em-list__search-icon">
					<svg width="6" height="5">
						<use xlink:href="#lines"></use>
					</svg>
				</div>
				<div class="em-list__search-item" @click="changeData(listItem)">
					{{listItem.value}}
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue', 'fieldSettings'],
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
			 * Открыть/Закрыть попап
			 */
			togglePopup()
			{
				this.showPopup = !this.showPopup;
			},
			/**
			 * Закрыть попап
			 */
			closePopup()
			{
				this.showPopup = false;
			},
			/**
			 * Изменить тип поля
			 */
			async changeData(data)
			{
				let qs = require('qs');

				let request = qs.stringify({
					tableCode       : this.fieldSettings.tableCode,
					fieldCode       : this.fieldSettings.fieldCode,
					primaryKey      : this.fieldSettings.primaryKey.fieldCode,
					primaryKeyValue : this.fieldSettings.primaryKey.value,
					selectedValue   : data.key
				});

				let result = await this.$axios({
					method: 'POST',
					url: '/api/field/em_list/index/saveSelectedItem/',
					data: request
				});

				if (!result.data.success)
					return false;

				this.$emit('onChange', {value: data.value, settings: this.settings});
				this.selectedItem = data.value;
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.settings = this.fieldSettings;
			this.selectedItem = this.fieldValue;
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