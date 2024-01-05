<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">Use Mask</div>
			<div class="popup__field-input">
				<Checkbox
					:checked.sync="localSettings.useMask"
				></Checkbox>
			</div>
		</div>
		<div class="popup__field" v-if="localSettings.useMask">
			<div class="popup__field-name">Mask (use #)</div>
			<div class="popup__field-input">
				<input
					class="el-inp-noborder"
					placeholder="Enter mask value"
					type="text"
					v-model="localSettings.mask"
				/>
			</div>
		</div>

		<div class="popup__buttons">
			<button @click="cancel()" class="el-gbtn">{{$t('cancel')}}</button>
			<button @click="save()" class="el-btn">{{$t('save_settings')}}</button>
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
				localSettings :
				{
					useMask  : false,
					mask  : '',
				},
				errors: {}
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
				this.$emit('save', this.localSettings);
			}
		},
		mounted()
		{
			for(let index in this.localSettings)
			{
				if(typeof this.settings[index] == 'undefined')
					continue;

				if (this.settings[index] === 'false' || this.settings[index] === 'true')
					this.$set(this.localSettings, index, (this.settings[index] === 'true'));
				else
					this.$set(this.localSettings, index, this.settings[index])
			}
		}
	}
</script>