<template>
	<div class="upload-popup" @click.stop>
		<div class="upload-popup-head">
			Add file
		</div>
		<div class="upload-tab-wrapper">
			<div class="upload-tabs-head">
				<div class="upload-tab-item" @click="setActiveTab(item)" v-for="item in tabs" :class="{active: item.active}">{{item.name}}</div>
			</div>
			<div class="upload-tabs-content-wrapper">
				<div class="ulpoad-tab-content" v-if="activeTab == 'Upload'">
					<div class="upload-input-file-wrapper">
						<InputFile/>
					</div>
				</div>
				<div class="upload-tab-content" v-if="activeTab == 'Upload by link'">
					<div class="upload-tab-input-wrapper">
						<input type="text" placeholder="Paste link">
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import InputFile from '@/components/forms/InputFile.vue';
	export default
	{
		components: { InputFile },
		/**
		 * Глоабальные переменные странциы
		 */
		data()
		{
			return {
				tabs:
				[
					{ name: 'Upload', active: true },
					{ name: 'Upload by link', active: false },
				],
				activeTab: 'Upload'
			}
		},
		methods:
		{
			/**
			 * Задать активность табу
			 */
			setActiveTab(tab)
			{
				for (var item of this.tabs)
					item.active = false;

				tab.active = true;
				this.activeTab = tab.name;
			}
		}
	}
</script>
<style lang="scss">
	.upload-popup
	{
		background: #FFFFFF;
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		width: 326px;
		position: absolute;
		z-index: 2;
		top: -1px;
		left: -1px;
	}
	.upload-popup-head
	{
		height: 49px;
		background: rgba(103, 115, 135, 0.1);
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		padding: 0 9px;
		color: rgba(25, 28, 33, 0.4);
		font-size: 10px;
		display: flex;
		align-items: center;
		margin-bottom: 5px;
	}
	.upload-tab-item
	{
		font-size: 10px;
		color: rgba(25, 28, 33, 0.7);;
		padding: 0 6px;
		height: 25px;
		line-height: 25px;
		margin-right: 8px;
		border-bottom: 2px solid transparent;
		cursor: pointer;
		&:last-child
		{
			margin-right: unset;
		}
		&.active
		{
			border-bottom: 2px solid #191C21;
			color: #191C21;
		}
	}
	.upload-tabs-head
	{
		display: flex;
		height: 25px;
		align-items: center;
		border-bottom: 2px solid rgba(103, 115, 135, 0.1);
	}
	.upload-tab-wrapper
	{
		padding: 0 9px;
	}
	.upload-tabs-content-wrapper
	{
		height: 68px;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.upload-tab-input-wrapper
	{
		background: #FFFFFF;
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		height: 30px;
		min-width: 255px;
		input
		{
			border: none;
			font-size: 10px;
			padding: 0 11px;
			width: 100%;
			height: 100%;
			box-sizing: border-box;
		}
	}
</style>