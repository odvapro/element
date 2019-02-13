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
			 * Достать все таблицы
			 */
			getTableList()
			{
				return this.$store.state.tables.tablesList;
			},
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
				await this.$store.dispatch('getColumns', typeof this.getActiveTable == 'undefined' ? this.getTableList[0].code : this.getActiveTable);
				await this.$store.commit('setTableInfo',
				{
					code: typeof this.getActiveTable == 'undefined' ?
					this.getTableList[0].code :
					this.getActiveTable
				});
				await this.$store.dispatch('select',
				{
					select:
					{
						from: typeof this.getActiveTable == 'undefined' ? this.getTableList[0].code : this.getActiveTable,
						page: typeof this.getPage == 'undefined' ? 1 : this.getPage
					}
				});
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		async mounted()
		{
			await this.getTableContent();
		}
	}
</script>