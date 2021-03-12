<template>
	<div class="em-list-settings">
		<div class="em-list-settings__line">
			<div class="em-list-settings__key-block">{{$t('fieldEmList.settings.key')}}</div>
			<div class="em-list-settings__key-block">{{$t('fieldEmList.settings.value')}}</div>
		</div>
		<div
			class="em-list-settings__line"
			v-for="listItem, index in localSettings.list"
			:key="index"
		>
			<div class="em-list-settings__key-block">
				<input
					type="text"
					class="el-inp-noborder"
					v-model="listItem.key"
					:placeholder="$t('fieldEmList.settings.enter_key')"
				/>
			</div>
			<div class="em-list-settings__value-block">
				<input
					type="text"
					class="el-inp-noborder"
					v-model="listItem.value"
					:placeholder="$t('fieldEmList.settings.enter_value')"
				/>
			</div>
			<div class="em-list-settings__remove-block">
				<div class="em-list-settings__remove-item" @click.stop="removeValue(index)">
					<svg width="12" height="12">
						<use xlink:href="#plus-white"></use>
					</svg>
				</div>
			</div>
		</div>
		<div class="em-list-settings__add-line">
			<button class="el-gbtn" @click="addValues()">{{$t('fieldEmList.settings.add_option')}}</button>
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
					list: [
						{key: '', value: ''}
					]
				},
			}
		},
		methods:
		{
			/**
			 * Удалить значение поля
			 */
			removeValue(fieldIndex)
			{
				this.localSettings.list.splice(fieldIndex, 1);
			},
			/**
			 * Добавить значения в список значений филда
			 */
			addValues()
			{
				this.localSettings.list.push({key: '', value: ''});
			},
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
					list: this.localSettings.list
				};

				this.$emit('save', formData);
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			for(var index in this.localSettings)
			{
				if(typeof this.settings[index] == 'undefined')
					continue;

				this.$set(this.localSettings, index, this.settings[index])
			}
		}
	}
</script>
<style lang="scss">
	.em-list-settings{margin-top:30px;}
	.em-list-settings .popup__buttons{margin-top:30px;}
	.em-list-settings__line
	{
		height: 40px;
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		display: flex;
		align-items: center;
		&:first-child{border-top: 1px solid rgba(103, 115, 135, 0.1);}
	}
	.em-list-settings__key-block
	{
		width:230px;
		border-right:1px solid rgba(103, 115, 135, 0.1);
		height: 100%;
		padding-left:10px;
		line-height:40px;
		color:#677387;
		font-size: 12px;
	}
	.em-list-settings__value-block
	{
		width:230px;
		border-right:1px solid rgba(103, 115, 135, 0.1);
		height: 100%;
		padding-left:10px;
	}
	.em-list-settings__remove-block{padding-left:10px; }
	.em-list-settings__add-line{margin-top:20px; }
	.em-list-settings__remove-item
	{
		width: 24px;
		height: 24px;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 2px;
		cursor: pointer;
		&:hover {background-color: rgba(103, 115, 135, 0.1); }
		svg{stroke:#677387;transform: rotate(45deg);}
	}
	@media (max-width: 768px)
	{
		.em-list-settings__key-block, .em-list-settings__value-block { max-width: 150px; }
		.em-list-settings .popup__buttons { margin-top: auto; }
		.em-list-settings
		{
			flex-grow: 1;
			display: flex;
			flex-direction: column;
		}
	}
</style>
