<template>
	<div v-if="extComponent" class="extensions__wrapper">
		<component
			v-bind:is="extComponent"
			:url="paramsString"
		></component>
	</div>
</template>
<script>
	export default
	{
		data()
		{
			return {
				paramsString : '',
				extname      : false,
				extCode      : false
			}
		},
		watch:
		{
			$route (to, from)
			{
				this.paramsString = to.params.pathMatch;
			}
		},
		computed:
		{
			extComponent()
			{
				let componenUrl = this.$route.params.extname + '/';
				if (this.$route.params.pathMatch)
					componenUrl += this.$route.params.pathMatch;

				const component = () => import(`@/components/extensions/${componenUrl}index.vue`).then(m => m.default);
				return component;
			}
		},
	}
</script>
<style>
	.extensions__wrapper{padding: 23px 20px 23px 21px; }
</style>