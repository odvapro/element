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
				if(!this.extCode)
					return false;

				return eval(this.extCode);
			}
		},
		methods:
		{
			loadCss(styles)
			{
				let styleLinkCode = `ext${this.extname}`;
				if(styles != '' && styles !== false && window.importStyles.indexOf(styleLinkCode) == -1)
				{
					var newSS       = document.createElement('style');
					newSS.innerHTML = styles;
					newSS.type      = 'text/css';
					document.getElementsByTagName("head")[0].appendChild(newSS);
					window.importStyles.push(styleLinkCode);
				}
			}
		},
		async mounted()
		{
			this.paramsString = this.$route.params.pathMatch;
			this.extname      = this.$route.params.extname;

			var result = await this.$axios({
					method : 'get',
					url    : '/extensions/getCode/',
					params : {extension:this.extname},
				});

			if (!result.data.success)
				return false;

			this.extCode = result.data.code;
			this.loadCss(result.data.styles);
		}
	}
</script>
<style>
	.extensions__wrapper{padding: 23px 20px 23px 21px; }
</style>