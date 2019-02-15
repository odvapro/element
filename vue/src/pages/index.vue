<template>
	<div>
	</div>
</template>
<script>
	export default
	{
		methods:
		{
			/**
			 * Отобразить первую талцицу
			 */
			viewFirstTable()
			{
				if (typeof this.$store.state.tables.tables[0] == 'undefined')
					return false;

				let table = this.$store.state.tables.tables[0],
					tviews = this.$store.state.tables.tables[0].tviews,
					url = '',
					activeTview = [];

				if (tviews.length > 0)
				{
					for (var tview of tviews)
					{
						if (tview.default != 1)
							continue;

						activeTview = tview;
						break;
					}

					if (typeof activeTview.id == 'undefined')
						activeTview = tviews[0];
				}

				url = `/table/${table.code}/tview/${activeTview.id}/page/1/`;

				this.$router.push(url);
			}
		},
		async mounted()
		{
			// определяем первую таблицу - делаем ее активной
			this.viewFirstTable();
		}
	}
</script>