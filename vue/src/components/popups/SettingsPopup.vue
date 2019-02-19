<template>
	<div class="settings-popup-overlay">
		<div class="settings-popup-wrapper" v-click-outside="closePopup">
			<div class="settings-popup-title">Name field settings</div>
			<div class="settings-popup-row-params">
				<div class="settings-popup-item-wrapper">
					Required
				</div>
				<div class="settings-popup-item-wrapper">
					<div class="settings-popup-radio-wrapper">
						<div class="settings-popup-radio-btn active">Yes</div>
						<div class="settings-popup-radio-btn">No</div>
					</div>
				</div>
			</div>
			<div class="settings-popup-row-params">
				<div class="settings-popup-item-wrapper">
					Choose redactor
				</div>
				<div class="settings-popup-item-wrapper">
					<div class="settings-popup-radio-wrapper">
						<div class="settings-popup-radio-btn active">Text</div>
						<div class="settings-popup-radio-btn">Code</div>
						<div class="settings-popup-radio-btn">Visual</div>
					</div>
				</div>
			</div>
			<div class="settings-popup-row-params">
				<div class="settings-popup-item-wrapper">
					Choose linked table
				</div>
				<div class="settings-popup-item-wrapper">
					<div class="settings-popup-table-select-btn">
						<MainField :fieldValue="{fieldName: 'EmTagsField', value: 'section'}"/>
					</div>
				</div>
			</div>
			<div class="settings-popup-row-params">
				<div class="settings-popup-item-wrapper">
					Choose search field
				</div>
				<div class="settings-popup-item-wrapper">
					<div class="setting-popup-item-table-search">
						<input type="text" placeholder="set title">
					</div>
				</div>
			</div>
			<div class="settings-popup-row-params">
				<div class="settings-popup-item-wrapper">
					Data field sample
				</div>
				<div class="settings-popup-item-wrapper">
					<div class="setting-popup-item-input-wrapper">
						12/12/1991 12:30
					</div>
				</div>
			</div>
			<div class="settings-popup-bottom-btns">
				<button class="settings-popup-bottom-btn-cancel">Cancel</button>
				<button class="settings-popup-bottom-btn-save">Save</button>
			</div>
			<div class="settings-popup-close-icon" @click="$store.commit('setActivePopup', false)"></div>
		</div>
	</div>
</template>
<script>
	import MainField from '@/components/fields/MainField.vue';
	export default
	{
		components: {MainField},
		methods:
		{
			/**
			 * Закрыть попап
			 */
			closePopup()
			{
				this.$store.commit('setActivePopup', false);
			}
		}
	}
</script>
<style lang="scss">
	.settings-popup-close-icon
	{
		width: 20px;
		height: 20px;
		position: absolute;
		top: 20px;
		right: 20px;
		cursor: pointer;
		&:before, &:after
		{
			width: calc(100% - 8px);
			height: 2px;
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			margin: auto;
			content: '';
			background-color: rgba(25, 28, 33, 0.7);
			transform: rotate(45deg);
		}
		&:after
		{
			transform: rotate(-45deg);
		}
	}
	.settings-popup-bottom-btns
	{
		display: flex;
		justify-content: center;
	}
	.settings-popup-bottom-btn-save
	{
		background: rgba(25, 28, 33, 0.7);
		border-radius: 2px;
		font-size: 12px;
		color: rgba(103, 115, 135, 0.7);
		padding: 7px 11px;
		border: none;
		color: #fff;
	}
	.settings-popup-bottom-btn-cancel
	{
		background: rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		font-size: 12px;
		color: rgba(103, 115, 135, 0.7);
		padding: 7px 11px;
		border: none;
		margin-right: 15px;
	}
	.setting-popup-item-input-wrapper
	{
		font-size: 10px;
		color: #191C21;
	}
	.setting-popup-item-table-search
	{
		font-size: 10px;
		color: rgba(25, 28, 33, 0.4);
		input
		{
			border: none;
		}
	}
	.settings-popup-table-select-btn
	{
		padding: 4px 8px;
		position: relative;
		border-radius: 2px;
		display: inline-block;
		font-size: 10px;
		color: #7C7791;
	}
	.settings-popup-radio-btn
	{
		padding: 8px 9px;
		color: rgba(103, 115, 135, 0.7);
		cursor: pointer;
		&.active
		{
			background-color: rgba(103, 115, 135, 0.1);
		}
	}
	.settings-popup-radio-wrapper
	{
		display: inline-flex;
		border: 1px solid rgba(103, 115, 135, 0.4);
		border-radius: 2px;
	}
	.settings-popup-row-params
	{
		display: flex;
		align-items: center;
		margin-bottom: 20px;
		height: 31px;
	}
	.settings-popup-item-wrapper
	{
		font-size: 12px;
		color: #191C21;
		width: 175px;
	}
	.settings-popup-title
	{
		font-size: 16px;
		color: #191C21;
		font-family: $rMedium;
		margin-bottom: 24px;
	}
	.settings-popup-overlay
	{
		position: fixed;
		top: 0;
		left: 0;
		background: rgba(124, 119, 145, 0.1);
		width: 100%;
		height: 100%;
		z-index: 100;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.settings-popup-wrapper
	{
		position: relative;
		width: 565px;
		background: #FFFFFF;
		box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.08);
		border-radius: 2px;
		padding: 20px;
	}
</style>