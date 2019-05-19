<template>
	<div class="em-string">
		<template v-if="mode == 'edit'">
			<input
				type="text"
				class="el-inp-noborder"
				@change="changeValue"
				:value="fieldValue"
				placeholder="Empty"
			/>
		</template>
		<template v-else>
			{{ fieldValue }}
			<span v-if="!fieldValue" class="el-empty">Empty</span>
		</template>
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
	.em-string
	{
		line-height: 49px;
		font-size: 12px;
		color: #677387;
		text-transform: capitalize;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		position: absolute;
		width:100%;
		left:0px;
		top:0px;
		height: 100%;
		padding-left: 10px;
		padding-right: 10px;
	}
</style>