<template>
	<div class="settings-users-wrapper">
		<div class="settings-users-head">
			<div class="settings-users-row-data">
				<div class="settings-users-item">
					<div class="settings-users-item-title">Login</div>
				</div>
				<div class="settings-users-item">
					<div class="settings-users-item-title">Name</div>
				</div>
				<div class="settings-users-item"></div>
			</div>
			<div class="settings-users-row" v-for="item in settings">
				<div class="settings-users-row-data">
					<div class="settings-users-item">
						<svg width="7" height="13" class="settings-users-item-img" :class="{active: item.isShow}" @click="setRowSetting(item)">
							<use xlink:href="#arrow"></use>
						</svg>
						<div class="settings-users-item-code">Code</div>
					</div>
					<div class="settings-users-item">
						<div class="settings-users-item-flag">
							<EmCheckField :fieldValue="{value: true}"/>
						</div>
					</div>
					<div class="settings-users-item"></div>
				</div>
				<div class="settings-users-row-setting">
					<div class="settings-users-row-setting-item" :class="{active: item.isShow}">
						<div class="settings-users-item">
							id
						</div>
						<div class="settings-users-item centered">
							<EmTagsField :fieldValue="{value: 'String'}"/>
						</div>
						<div class="settings-users-item centered">
							<button>settings</button>
						</div>
					</div>
					<div class="settings-users-row-setting-item" :class="{active: item.isShow}">
						<div class="settings-users-item">
							id
						</div>
						<div class="settings-users-item centered">
							<EmTagsField :fieldValue="{value: 'String'}"/>
						</div>
						<div class="settings-users-item centered">
							<button>settings</button>
						</div>
					</div>
					<div class="settings-users-row-setting-item" :class="{active: item.isShow}">
						<div class="settings-users-item">
							id
						</div>
						<div class="settings-users-item centered">
							<EmTagsField :fieldValue="{value: 'String'}"/>
						</div>
						<div class="settings-users-item centered">
							<button>settings</button>
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
					{isShow: false},
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
	.settings-users-item-img
	{
		margin-right: 11px;
		cursor: pointer;
		transition: all 0.3s;
		&.active
		{
			transform: rotate(90deg);
		}
	}
	.settings-users-item-flag
	{
		display: flex;
		width: 100%;
		justify-content: center;
	}
	.settings-users-item-name
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
	}
	.settings-users-item-code
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
	}
	.settings-users-item-title
	{
		color: #677387;
		font-size: 12px;
	}
	.settings-users-row-setting-item
	{
		display: flex;
		overflow: hidden;
		background: rgba(103, 115, 135, 0.1);
		transition: all 0.3s;
		height: 0;
		padding: 0 8px;
		.settings-users-item
		{
			border-right: none;
		}
		.settings-users-item:first-child
		{
			padding-left: 30px;
		}
		&.active
		{
			height: 39px;
			border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		}
	}
	.settings-users-row-data
	{
		display: flex;
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		padding: 0 8px;
	}
	.settings-users-row
	{
		display: flex;
		flex-direction: column;
		position: relative;
		.settings-users-row-setting-item.active:first-child
		{
			border-top: none;
		}
		.settings-users-row-setting-item.active:last-child
		{
			border-bottom: none;
		}
		&:last-child
		{
			border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		}
	}
	.settings-users-item
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