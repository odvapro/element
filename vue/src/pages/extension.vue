<template>
	<div>
		extension page
		{{ paramsString }}
	</div>
</template>
<script>
	export default
	{
		data()
		{
			return {
				paramsString:'',
			}
		},
		watch:{
			$route (to, from)
			{
				console.log(to);
				this.paramsString = to.params.pathMatch;
			}
		},
		mounted()
		{
			let params        = this.$route.params;
			this.paramsString = params.pathMatch;
		},
	}
</script>