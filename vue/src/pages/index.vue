<template>
	<div>
		<Table/>
	</div>
</template>
<script>
	import Table from '@/components/layouts/Table.vue';
	export default
	{
		components: { Table },
		computed:
		{
			/**
			 * Достать из урла активную таблицу
			 */
			getActiveTable()
			{
				return this.$route.params.tableName;
			},
			/**
			 * Достать из урла номер страницы
			 */
			getPage()
			{
				return this.$route.params.page;
			}
		},
		methods:
		{
			/**
			 * Достать содержимое таблицы
			 */
			async getTableContent()
			{
				await this.$store.dispatch('getColumns', this.getActiveTable);
				await this.$store.commit('setTableInfo', {code: this.getActiveTable});
				await this.$store.dispatch('select', {select: { from: this.getActiveTable, page: this.getPage}});
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		async mounted()
		{
			await this.$store.dispatch('getTables');
			await this.getTableContent();
		}
	}
</script>