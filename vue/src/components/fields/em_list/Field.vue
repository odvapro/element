<template>
	<div class="em_list">
		<List
			:selected="fieldValue"
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
			}
		},
		methods:
		{
			/**
			 * Изменить тип поля
			 */
			async changeData(data)
			{
				let curValue = (data.value.length == 0)?'':data.value;
				this.$emit('onChange', {
					value: curValue,
					settings: this.fieldSettings
				});
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