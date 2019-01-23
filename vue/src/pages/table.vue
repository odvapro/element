<template>
	<div>
		<table>
			<th v-for="item in columns">{{item.field}}</th>
			<tr v-for="item in tableData">
				<td v-for="sub in item">{{sub}}</td>
			</tr>
		</table>
	</div>
</template>
<script>
	export default
	{
		data()
		{
			return {
				columns: [],
				tableData: []
			}
		},
		methods:
		{
			/**
			 * Достать названия полей таблицы
			 */
			async getColumnsHead(tableName)
			{
				var data = new FormData();

				if (typeof tableName == 'undefined')
					return false;

				data.append('table_name', tableName);

				var resultCol = await this.$axios({
					method: 'post',
					url: '/table/getColumns',
					data: data
				});

				if (!resultCol.data.success)
					return false;

				this.columns = resultCol.data.columns;
			},
			/**
			 * Достать содержимое таблицы
			 */
			async getTableData(tableName)
			{
				if (typeof tableName == 'undefined')
					return false;

				var data = new FormData();

				data.append('table_name', tableName);

				var resultData = await this.$axios({
					method: 'post',
					url: '/table/getTableData',
					data: data
				});

				if (!resultData.data.success)
					return false;

				this.tableData = resultData.data.result;
			}
		},
		async mounted()
		{
			var tableName = this.$route.params.name;
			this.getColumnsHead(tableName);
			this.getTableData(tableName);
		}
	}
</script>