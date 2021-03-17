<template>
	<div v-if="extComponent" class="extensions__wrapper">
		<div class="extensions__wrapper-burger"><MobileBurger/></div>
		<component
			v-bind:is="extComponent"
			:url="paramsString"
		></component>
	</div>
</template>
<script>
	import MobileBurger from '@/components/blocks/MobileBurger.vue';

	export default
	{
		components: {MobileBurger},
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
				let componenUrl = this.$route.params.extname ;
				const component = () => import(`@/components/extensions/${componenUrl}/index.vue`).then(m => m.default);
				return component;
			}
		},
	}
</script>
<style>
	.extensions__wrapper{padding: 23px 20px 23px 21px; }
	.extensions__wrapper-burger{ margin-bottom: 20px; }
	@media (max-width: 768px)
	{
		.extensions__wrapper { min-width: 375px; }
	}
</style>
