<template>
	<div class="em-tags-wrapper" @click="togglePopup()">
		<div class="em-tags-item-wrapper">
			<div class="em-tags-item">
				{{selectedItem}}
			</div>
		</div>
		<div class="em-tags-search" @click.stop v-if="showPopup" v-click-outside="closePopup">
			<div class="em-tags-search-popup-head">
				<div class="em-tags-search-item">
					{{selectedItem}}
				</div>
			</div>
			<div class="em-tags-search-popup-item" v-for="type in fieldSettings.values">
				<div class="em-tags-search-icon">
					<svg width="6" height="5">
						<use xlink:href="#lines"></use>
					</svg>
				</div>
				<div class="em-tags-search-item" @click="changeData(type)">
					{{type.name}}
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import TagSearch from '@/components/popups/TagSearch.vue';
	import TagItem from '@/components/forms/TagItem.vue';
	export default
	{
		components: {TagItem, TagSearch},
		props: ['fieldValue', 'fieldSettings'],
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
			changeData(data)
			{
				this.selectedItem = data.name;
				this.$emit('onChange', {data: data, column: this.fieldSettings.fieldCode, table: this.fieldSettings.tableCode});
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.selectedItem = this.fieldValue;
		}
	}
</script>
<style lang="scss">
	.em-tags-item
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
	.em-tags-search-popup-head
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
	.em-tags-search
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
	.em-tags-search-icon
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
	.em-tags-search-item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
		position: relative;
	}
	.em-tags-search-popup-item
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