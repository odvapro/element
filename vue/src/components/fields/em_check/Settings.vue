<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">
				Required
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" placeholder="Enter email" v-model="required">
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Checked status in DB
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" placeholder="Enter checked string" v-model="checkedString">
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Unchecked status in DB
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" placeholder="Enter unchecked string" v-model="uncheckedString">
			</div>
		</div>
		<div class="popup__buttons">
			<button @click="cancel()" class="el-gbtn">Cancel</button>
			<button @click="save()" class="el-btn">Save settigns</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['settings','isRequired'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				required: false,
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
					required: this.required,
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