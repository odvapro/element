<template>
	<a
		class="el-export"
		target="blank"
		:href="linkToDownloadCsv"
	>Export to CSV</a>
</template>
<script>
	export default
	{
		props: ['tview'],
		computed:
		{
			linkToDownloadCsv()
			{
				return process.env.VUE_APP_API_ENDPOINT + 'tview/exportCsv?tviewId=' + this.tview.id;
			}
		}
	}
</script>
<style lang="scss">
	.el-export
	{
		text-decoration: none;
		color: inherit;
	}
</style>
