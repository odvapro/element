<template>
	<div class="em-list-settings">
		<div class="popup__field">
			<div class="popup__field-name">Multiple</div>
			<div class="popup__field-input">
				<Checkbox
					:checked.sync="localSettings.multiple"
				></Checkbox>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">Options List</div>
		</div>
		<div class="em-list__table">
			<div class="em-list-settings__line">
				<div class="em-list-settings__key-block">{{$t('fieldEmList.settings.key')}}</div>
				<div class="em-list-settings__key-block">{{$t('fieldEmList.settings.value')}}</div>
				<div class="em-list-settings__color-block em-list-settings__color-block--header">Color</div>
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
				<div class="em-list-settings__color-block">
					<Select class="em-list-settings__select-color">
						<template v-slot:selected>
							<div class="em-list-settings__color-thumb-wrap">
								<span class="em-list-settings__color-thumb" :style="{background: getColor(listItem.color).rgb }"></span>
								{{ getColor(listItem.color).name }}
							</div>
						</template>
						<SelectOption
							v-for="color in colors"
							:key="color.rgb"
							class="em-list-settings__color-thumb-wrap"
							@click.native="selectColor(listItem,color)"
						>
							<span class="em-list-settings__color-thumb" :style="{background: color.rgb }"></span>
							{{ color.name }}
						</SelectOption>
					</Select>

				</div>
				<div class="em-list-settings__remove-block">
					<div class="em-list-settings__remove-item" @click.stop="removeValue(index)">
						<svg width="12" height="12">
							<use xlink:href="#plus-white"></use>
						</svg>
					</div>
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
						{key: '', value: '', color:''}
					],
					multiple:false
				},
				colors:[
					{rgb:'#F1F0F0',name:'Light gray'},
					{rgb:'#E3E2E0',name:'Gray'},
					{rgb:'#ECE0DB',name:'Brown'},
					{rgb:'#F5DFCC',name:'Orange'},
					{rgb:'#FAECCC',name:'Yellow'},
					{rgb:'#DEECDC',name:'Green'},
					{rgb:'#D6E4EE',name:'Blue'},
					{rgb:'#E6DEED',name:'Purple'},
					{rgb:'#F1E1E9',name:'Pink'},
					{rgb:'#FAE3DE',name:'Red'},
				]
			}
		},
		methods:
		{
			selectColor(listItem,color)
			{
				listItem.color = color.rgb;
			},
			getColor(rgb)
			{
				let resColor = false;
				for(let color of this.colors)
					if(rgb == color.rgb)
					{
						resColor = color;
						break;
					}

				if(resColor === false)
					return this.colors[1];
				return resColor;
			},
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
				let randColor = Math.floor(Math.random()*this.colors.length);
				randColor = this.colors[randColor].rgb;
				this.localSettings.list.push({key: '', value: '', color:randColor});
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
					list: this.localSettings.list,
					multiple: this.localSettings.multiple
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

				if (this.settings[index] === 'false' || this.settings[index] === 'true')
					this.$set(this.localSettings, index, (this.settings[index] === 'true'));
				else
					this.$set(this.localSettings, index, this.settings[index])
			}
		}
	}
</script>
<style lang="scss">
	.settings-table__popup-list{width:680px; }
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
	.em-list-settings__color-block{
		width:145px;
		border-right:1px solid rgba(103, 115, 135, 0.1);
		height: 100%;
		color:#677387;
		font-size: 12px;
		line-height:40px;
		&--header{padding-left: 10px;}
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
	.em-list-settings__color-thumb-wrap{
		display: flex;
		align-items: center;
	}
	.em-list-settings__color-thumb
	{
		display: inline-block;
		width: 18px;
		height: 18px;
		border-radius: 2px;
		border:1px solid rgba(0,0,0, 0.25);
		margin-right: 7px;
	}
	.em-list-settings__select-color
	{
		.select__trigger
		{
			width: 100%;
			height: 39px;
			border:0px;
		}
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
