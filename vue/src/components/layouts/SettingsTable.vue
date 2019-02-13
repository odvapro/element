<template>
	<div class="settings-table-wrapper">
		<div class="settings-table-head">
			<div class="settings-table-row-data">
				<div class="settings-table-item">
					<div class="settings-table-item-title">Code</div>
				</div>
				<div class="settings-table-item">
					<div class="settings-table-item-title">Name</div>
				</div>
				<div class="settings-table-item">
					<div class="settings-table-item-title">Show</div>
				</div>
				<div class="settings-table-item"></div>
			</div>
			<div class="settings-table-row" v-for="item in settings">
				<div class="settings-table-row-data">
					<div class="settings-table-item">
						<svg width="7" height="13" class="settings-table-item-img" :class="{active: item.isShow}" @click="setRowSetting(item)">
							<use xlink:href="#arrow"></use>
						</svg>
						<div class="settings-table-item-code">Code</div>
					</div>
					<div class="settings-table-item">
						<div class="settings-table-item-name">Name</div>
					</div>
					<div class="settings-table-item">
						<div class="settings-table-item-flag">
							<EmCheckField :fieldValue="{value: true}"/>
						</div>
					</div>
					<div class="settings-table-item"></div>
				</div>
				<div class="settings-table-row-setting">
					<div class="settings-table-row-setting-item" :class="{active: item.isShow}">
						<div class="settings-table-item">
							id
						</div>
						<div class="settings-table-item category-font">
							Identifier
						</div>
						<div class="settings-table-item centered">
							<EmTagsField :fieldValue="{value: 'String'}"/>
						</div>
						<div class="settings-table-item centered">
							<button @click.prevent.stop="$store.commit('setActivePopup', true)">settings</button>
						</div>
					</div>
					<div class="settings-table-row-setting-item" :class="{active: item.isShow}">
						<div class="settings-table-item">
							id
						</div>
						<div class="settings-table-item category-font">
							Identifier
						</div>
						<div class="settings-table-item centered">
							<EmTagsField :fieldValue="{value: 'String'}"/>
						</div>
						<div class="settings-table-item centered">
							<button @click.prevent.stop="$store.commit('setActivePopup', true)">settings</button>
						</div>
					</div>
					<div class="settings-table-row-setting-item" :class="{active: item.isShow}">
						<div class="settings-table-item">
							id
						</div>
						<div class="settings-table-item category-font">
							Identifier
						</div>
						<div class="settings-table-item centered">
							<EmTagsField :fieldValue="{value: 'String'}"/>
						</div>
						<div class="settings-table-item centered">
							<button @click.prevent.stop="$store.commit('setActivePopup', true)">settings</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import EmTagsField from '@/components/fields/EmTagsField.vue';
	import EmCheckField from '@/components/fields/EmCheckField.vue';
	export default
	{
		components: { EmCheckField, EmTagsField },
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				settings:
				[
					{isShow: false},
					{isShow: false},
					{isShow: false},
					{isShow: false}
				]
			}
		},
		methods:
		{
			/**
			 * Открыть/закрыть настройки колонок
			 */
			setRowSetting(settingItem)
			{
				settingItem.isShow = !settingItem.isShow;
			}
		}
	}
</script>
<style lang="scss">
	.settings-table-item-img
	{
		margin-right: 11px;
		cursor: pointer;
		transition: all 0.3s;
		&.active
		{
			transform: rotate(90deg);
		}
	}
	.settings-table-item-flag
	{
		display: flex;
		width: 100%;
		justify-content: center;
	}
	.settings-table-item-name
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
	}
	.settings-table-item-code
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
	}
	.settings-table-item-title
	{
		color: #677387;
		font-size: 12px;
	}
	.settings-table-row-setting-item
	{
		display: flex;
		overflow: hidden;
		background: rgba(103, 115, 135, 0.1);
		transition: all 0.3s;
		height: 0;
		padding: 0 8px;
		.settings-table-item
		{
			border-right: none;
		}
		.settings-table-item:first-child
		{
			padding-left: 30px;
		}
		&.active
		{
			height: 39px;
			border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		}
	}
	.settings-table-row-data
	{
		display: flex;
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		padding: 0 8px;
	}
	.settings-table-row
	{
		display: flex;
		flex-direction: column;
		position: relative;
		.settings-table-row-setting-item.active:first-child
		{
			border-top: none;
		}
		.settings-table-row-setting-item.active:last-child
		{
			border-bottom: none;
		}
		&:last-child
		{
			border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		}
	}
	.settings-table-item
	{
		display: flex;
		word-break: break-word;
		align-items: center;
		height: 39px;
		overflow: hidden;
		padding: 0 11px;
		position: relative;
		min-width: 140px;
		width: 140px;
		color: rgba(25, 28, 33, 0.7);
		font-size: 12px;
		border-right: 1px solid rgba(103, 115, 135, 0.1);
		&:last-child
		{
			border-right: none;
		}
		&.centered
		{
			justify-content: center;
		}
		&.category-font
		{
			color: #191C21;
		}
		button
		{
			color: rgba(25, 28, 33, 0.7);
			background-color: transparent;
			border: none;
			cursor: pointer;
		}
	}
</style>