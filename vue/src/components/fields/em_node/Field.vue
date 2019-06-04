<template>
	<div class="em-node__item-wrapper">
		<template v-if="view == 'detail'">
			<span>hello</span>
		</template>
		<template v-else>
			<div v-if="fieldValue.id" class="em-node__item">
				{{ fieldValue.name }}
			</div>
			<span v-else class="el-empty">Empty</span>
		</template>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue(event)
			{
				this.$emit('onChange', {
					value    : event.target.value,
					settings : this.fieldSettings
				});
			}
		}
	}
</script>
<style lang="scss">
	.em-node__item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
		position: relative;
		cursor: pointer;
	}
</style>