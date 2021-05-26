<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">{{$t('min')}}</div>
			<div class="popup__field-input">
				<div class="em-int__field-input">
					<input
						class="el-inp"
						type="number"
						v-model="localSettings.min.value"
						:disabled="!localSettings.min.enabled"
					>
					<Checkbox
						:checked.sync="localSettings.min.enabled"
					></Checkbox>
				</div>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">{{$t('max')}}</div>
			<div class="popup__field-input">
				<div class="em-int__field-input">
					<input
						class="el-inp"
						type="number"
						v-model="localSettings.max.value"
						:disabled="!localSettings.max.enabled"
					>
					<Checkbox
						:checked.sync="localSettings.max.enabled"
					></Checkbox>
				</div>
			</div>
		</div>
		<div class="popup__buttons">
			<button @click="cancel" class="el-gbtn">{{$t('cancel')}}</button>
			<button @click="save" class="el-btn">{{$t('save_settings')}}</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['settings'],
		data()
		{
			return {
				localSettings:
				{
					min: {
						value: 0,
						enabled: false,
					},
					max: {
						value: 0,
						enabled: false,
					},
				},
			};
		},
		mounted()
		{
			let valuesCodes = ['min','max'];
			for (let valueCode of valuesCodes)
				if (typeof this.settings[valueCode] != 'undefined')
				{
					this.localSettings[valueCode].value = +this.settings[valueCode].value || 0;
					this.localSettings[valueCode].enabled = !!+this.settings[valueCode].enabled;
				}
		},
		methods:
		{
			validate()
			{
				if (this.localSettings.min.enabled && this.localSettings.max.enabled)
					return this.localSettings.min.value <= this.localSettings.max.value;

				return true;
			},
			save()
			{
				if (this.validate())
				{
					return this.$emit('save', {
						max: {
							enabled: +this.localSettings.max.enabled,
							value: this.localSettings.max.value,
						},
						min: {
							enabled: +this.localSettings.min.enabled,
							value: this.localSettings.min.value,
						},
					});
				}
				this.ElMessage.error(this.$t('fieldEmInteger.settings.min_max'));
			},
			cancel()
			{
				this.$emit('cancel');
			},
		},
	};
</script>
<style lang="scss">
	.em-int__field-input
	{
		display: flex;
		align-items: center;
		input { margin-right: 10px; }
	}
</style>
