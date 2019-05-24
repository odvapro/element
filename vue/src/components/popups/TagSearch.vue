<template>
	<div class="tag-search" @click.stop>
		<div class="tag-search-popup-head">
			<div class="tag-search-item">
				{{defaultTagValue}}
			</div>
		</div>
		<div class="tag-search-popup-item" v-for="type in types">
			<div class="tag-search-icon">
				<svg width="6" height="5">
					<use xlink:href="#lines"></use>
				</svg>
			</div>
			<div class="tag-search-item" @click="changeFieldType(type)">
				{{type.name}}
			</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['types', 'defaultValue'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				defaultTagValue: ''
			}
		},
		methods:
		{

		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.defaultTagValue = this.defaultValue;
		}
	}
</script>
<style lang="scss">
	.tag-search-popup-head
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
	.tag-search
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
	.tag-search-icon
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
	.tag-search-item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
		position: relative;
	}
	.tag-search-popup-item
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