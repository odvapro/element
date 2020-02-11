<template>
	<div>
		<Detail
			:tableCode="$route.params.tableCode"
			:name="$route.name"
			:id="$route.params.id"
			@cancel="cancel"
			@openDetail="openDetail"
		/>
	</div>
</template>
<script>
	import Detail from '@/components/tviews/Detail.vue'
	export default
	{
		components: {Detail},
		methods:
		{
			cancel()
			{
				this.$router.go(-1);
			},
			openDetail({tableCode,id})
			{
				this.$router.push({name:'tableDetail', params:{tableCode:tableCode, id:id }});
			}
		}
	}
</script>