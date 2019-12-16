<template>
	<div class="">
		<div class="popup__field">
			<div class="popup__field-name">Include Time</div>
			<div class="popup__field-input">
				<Checkbox
					:checked.sync="localSettings.includeTime"
				></Checkbox>
			</div>
		</div>
		<div class="popup__buttons">
			<button @click="cancel" class="el-gbtn">Cancel</button>
			<button @click="save" class="el-btn">Save settigns</button>
		</div>
	</div>
</template>
<script>
	import Checkbox from '@/components/forms/Checkbox.vue';
	export default
	{
		components: {Checkbox},
		props: ['settings'],
		data()
		{
			return {
				localSettings:
				{
					includeTime: false,
					required: true,
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
