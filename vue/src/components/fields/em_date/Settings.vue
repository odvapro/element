<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">Current timestamp <span class="em_field--beta">(beta)</span></div>
			<div class="popup__field-input">
				<Checkbox
					:checked.sync="localSettings.current_timestamp"
				></Checkbox>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">{{$t('fieldEmDate.settings.include_time')}}</div>
			<div class="popup__field-input">
				<Checkbox
					:checked.sync="localSettings.includeTime"
				></Checkbox>
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
					current_timestamp: false,
					includeTime: false,
				}
			}
		},
		mounted()
		{
			if(typeof this.settings.includeTime != 'undefined')
				this.localSettings.includeTime = this.settings.includeTime === "true";

			if(typeof this.settings.current_timestamp != 'undefined')
				this.localSettings.current_timestamp = this.settings.current_timestamp === "true";
		},
		methods:
		{
			save()
			{
				this.$emit('save', this.localSettings);
			},
			cancel()
			{
				this.$emit('cancel');
			},
		},
	}
</script>
