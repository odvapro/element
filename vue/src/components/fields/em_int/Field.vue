<template>
	<div class="em-int">
		<template v-if="mode == 'edit'">
			<input
				ref="intInput"
				type="number"
				class="el-inp-noborder em-int-number"
				@change="changeValue"
				:value="localFieldValue"
				:placeholder="$t('empty')"
			/>
		</template>
		<template v-else>
			{{ localFieldValue }}
			<span v-if="!localFieldValue" class="el-empty">{{$t('empty')}}</span>
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
				localFieldValue: 0,
			};
		},
		mounted()
		{
			this.setDefaultSettings();
			this.localFieldValue = +this.fieldValue || 0;
		},
		methods:
		{
			setDefaultSettings()
			{
				if (!this.fieldSettings.max)
					this.fieldSettings.max = {
						enabled: false,
						value: 0,
					};
				if (!this.fieldSettings.min)
					this.fieldSettings.min = {
						enabled: false,
						value: 0,
					};
			},
			validate(value)
			{
				if (this.fieldSettings.max
					&& !!+this.fieldSettings.max.enabled
					&& this.fieldSettings.max.value
				)
					return value <= +this.fieldSettings.max.value;

				if (this.fieldSettings.min
					&& !!+this.fieldSettings.min.enabled
					&& this.fieldSettings.min.value
				)
					return value >= +this.fieldSettings.min.value;
				return true;
			},
			/**
			 * Send change current value
			 */
			changeValue(event)
			{
				if (!this.validate(+event.target.value))
				{
					this.$refs.intInput.value = this.localFieldValue;

					return this.ElMessage.error(this.$t('fieldEmInteger.min_max_error', {
						min: (this.fieldSettings.min && this.fieldSettings.min.value) ? this.fieldSettings.min.value : 0,
						max: (this.fieldSettings.max && this.fieldSettings.max.value) ? this.fieldSettings.max.value : 0,
					}));
				}
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
		width:100%;
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
