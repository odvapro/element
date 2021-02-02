<template>
	<div class="settings-popup-row-params">
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
					includeTime: false,
				}
			}
		},
		mounted()
		{
			if(typeof this.settings.includeTime != 'undefined')
				this.localSettings.includeTime = this.settings.includeTime === "true";
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
