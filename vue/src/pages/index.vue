<template>
	<div>
	</div>
</template>
<script>
	export default
	{
		/**
		 * Хук при загрузке страницы
		 */
		async mounted()
		{
			this.$router.push('settings');
		}
	}
</script>