<template>
	<div class="em-string">
		<template v-if="mode == 'edit'">
			<input
				type="text"
				class="el-inp-noborder"
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
		input { word-break: break-all; }
	}
	.detail-field-box .em-string{padding:0px;}
	.detail-field-box .em-string .el-inp-noborder{line-height:1.15}
</style>
