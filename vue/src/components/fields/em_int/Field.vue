<template>
	<div class="em-int">
		<template v-if="mode == 'edit'">
			<input
				type="number"
				class="el-inp-noborder em-int-number"
				@change="changeValue"
				:value="fieldValue"
				:placeholder="$t('empty')"
			/>
		</template>
		<template v-else>
			{{ fieldValue }}
			<span v-if="!fieldValue" class="el-empty">{{$t('empty')}}</span>
		</template>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
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
			changeValue(event)
			{
				this.$emit('onChange', {
					value     : event.target.value,
					settings  : this.fieldSettings
				});
			}
		}
	}
</script>
<style lang="scss">
	.em-int
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
	.detail-field-box .em-int{padding:0px;}

	.em-int-number::-webkit-outer-spin-button,
	.em-int-number::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
</style>