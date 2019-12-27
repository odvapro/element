<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">
				{{ $t('fieldEmCheck.settings.checked_status_in_db') }}
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" :placeholder="$t('fieldEmCheck.settings.enter_checked_string')" v-model="checkedString">
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{ $t('fieldEmCheck.settings.unchecked_status_in_db') }}
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" :placeholder="$t('fieldEmCheck.settings.enter_unchecked_string')" v-model="uncheckedString">
			</div>
		</div>
		<div class="popup__buttons">
			<button @click="cancel()" class="el-gbtn">{{ $t('cancel') }}</button>
			<button @click="save()" class="el-btn">{{ $t('save_settings') }}</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['settings'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				checkedString:'1',
				uncheckedString:'0'
			}
		},
		methods:
		{
			/**
			 * Cancel editing settgins
			 */
			cancel()
			{
				this.$emit('cancel');
			},

			/**
			 * Save settings
			 */
			save()
			{
				let formData = {
					checkedString: this.checkedString,
					uncheckedString: this.uncheckedString,
				}
				this.$emit('save',formData);
			}
		},
		mounted()
		{
			if(typeof this.settings.checkedString != 'undefined')
				this.checkedString = this.settings.checkedString;
			if(typeof this.settings.uncheckedString != 'undefined')
				this.uncheckedString = this.settings.uncheckedString;
		}
	}
</script>