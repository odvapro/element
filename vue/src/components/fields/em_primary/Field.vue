<template>
	<div class="em-primary" @click="openEditPage()">
		{{ fieldValue }}
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings','mode'],
		data()
		{
			return {
				localFieldValue:''
			}
		},
		mounted()
		{
			this.localFieldValue = this.fieldValue;
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			openEditPage(event)
			{
				this.$emit('openEdit');
			}
		}
	}
</script>
<style lang="scss">
	.em-primary
	{
		background: rgba(58, 132, 6, 0.04);
		border-radius: 2px;
		line-height: 25px;
		height: 25px;
		padding:0 10px;
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
		cursor: pointer;
		display: inline-block;
		&:hover{background: rgba(58, 132, 6, 0.1);}
	}
</style>