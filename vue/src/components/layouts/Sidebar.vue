<template>
	<div>
		<ul>
			<li v-for="item in tables"><router-link :to="'/table/' + item.code">{{item.code}}</router-link></li>
		</ul>
	</div>
</template>
<script>
	export default
	{
		data()
		{
			return {
				tables: []
			}
		},
		async mounted()
		{
			var result = await this.$axios({
				method: 'get',
				url: '/api/el/getTables'
			});

			if (!result.data.success)
				return false;

			this.tables = result.data.tables;
		}
	}
</script>