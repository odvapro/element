<template>
	<div class="detail">
		<div class="detail-head">
			<div class="detail-head__burger"><MobileBurger/></div>
			<div class="detail-head-name">
				<div class="detail-icon-wrapper">
					<svg width="14" height="13">
						<use xlink:href="#tableicon"></use>
					</svg>
				</div>
				<template v-if="name != 'tableAddElement'">
					<div class="detail-name-wrapper">
						<div class="detail-head-label">{{$t('pages.table.edit_element')}}</div>
						<div class="detail-head-descr">{{ tableCode }}</div>
					</div>
					<div class="detail-head__buttons">
						<button @click="cancel" class="el-gbtn">{{$t('cancel')}}</button>
						<button @click="remove" class="el-gbtn">{{$t('remove')}}</button>
						<button @click="saveElement" class="el-btn">{{$t('save')}}</button>
					</div>
				</template>
				<template v-else>
					<div class="detail-name-wrapper">
						<div class="detail-head-label">{{$t('pages.table.new_element')}}</div>
						<div class="detail-head-descr">{{ tableCode }}</div>
					</div>
					<div class="detail-head__buttons">
						<button @click="cancel" class="el-gbtn">{{$t('cancel')}}</button>
						<button @click="createElement" class="el-btn">{{$t('create')}}</button>
					</div>
				</template>
			</div>
		</div>
		<div class="detail-feilds">
			<div class="detail-feild" v-for="(column,columnCode) in selectedElement">
				<div class="detail-field__name-wrap">
					<img class="detail-field__icon-image" :src="require(`@/assets${columnEmSettings(columnCode).type_info.iconPath}`)">
					<div class="detail-field-name">
						<span>{{ getColumnName(columnCode) }}</span>
						<small>{{ columnCode }}</small>
					</div>
				</div>
				<div class="detail-field-box">
					<MainField
						v-if="columns[columnCode]"
						mode="edit"
						view="detail"
						:fieldName="columns[columnCode].em.settings.code"
						:params="{
							value     : column,
							settings  : $store.getters.getColumnSettings(tableCode, columnCode, selectedElement)
						}"
						@onChange="changeFieldValue"
					/>
				</div>
			</div>
		</div>
		<div class="detail__buttons">
			<template v-if="name != 'tableAddElement'">
				<button @click="cancel" class="el-gbtn">{{$t('cancel')}}</button>
				<button @click="remove" class="el-gbtn">{{$t('remove')}}</button>
				<button @click="saveElement" class="el-btn">{{$t('save')}}</button>
			</template>
			<template v-else>
				<button @click="cancel" class="el-gbtn">{{$t('cancel')}}</button>
				<button @click="createElement" class="el-btn">{{$t('create')}}</button>
			</template>
		</div>
	</div>
</template>
<script>
	import MainField from '@/components/fields/MainField.vue';
	import qs from 'qs';
	import MobileBurger from '@/components/blocks/MobileBurger.vue';

	export default
	{
		props: ['tableCode', 'name', 'id', 'element', 'updatedElAt'],
		components: {MainField, MobileBurger},
		data()
		{
			return {
				columns:{},
				selectedElement:{},
				fieldNames:{}
			}
		},
		watch:
		{
			id(newId)
			{
				this.selectedElement.id = newId;
			},
			updatedElAt(val)
			{
				this.selectElement();
			},
		},
		mounted()
		{
			this.selectElement();
			this.fieldNames = Object.assign(this.fieldNames, this.$store.getters.getTableFieldsNames(this.tableCode));
		},
		methods:
		{
			async selectElement()
			{
				let requestParams = {
					select : {},
					where  : [],
					order  : [],
				};
				requestParams.select.from = this.tableCode;
				let primaryKeyCode        = this.$store.getters.getPrimaryKeyCode(this.tableCode);
				this.columns              = this.$store.getters.getColumns(this.tableCode);
				if(this.name != 'tableAddElement' && !this.element)
				{
					requestParams.select.where = {
						operation:'and',
						fields:[
							{
								code      : primaryKeyCode,
								operation : 'IS',
								value     : this.id
							}
						]
					};
					await this.$store.dispatch('selectElement',requestParams);
					this.$set(this, 'selectedElement', this.$store.state.tables.selectedElement);
				}
				else
				{
					for(let columnCode in this.columns)
					{
						this.$set(this.selectedElement, columnCode,'');
						this.$set(this.selectedElement, columnCode, '');
						if (this.element && typeof this.element[columnCode] != 'undefined')
							this.$set(this.selectedElement, columnCode, this.element[columnCode]);
					}
				}
			},
			/**
			 * Триггер изменения значения филда
			 * Переносим значение в наш стейт
			 * @fieldValue {
			 *     value,
			 *     settings(основной формат)
			 * }
			 */
			changeFieldValue(fieldValue)
			{
				this.selectedElement[fieldValue.settings.fieldCode] = fieldValue.value;
			},

			/**
			 * Return em object of column
			 */
			columnEmSettings(columnCode)
			{
				if(typeof this.columns[columnCode] != 'undefined')
					return this.columns[columnCode].em;
				throw new Error(`No column with code ${columnCode}`);
			},

			/**
			 * Returns column name or code
			 */
			getColumnName(columnCode)
			{
				return this.columnEmSettings(columnCode).name || columnCode;
			},

			/**
			 * Сохранение элемента
			 */
			saveElement()
			{
				this.$emit('saveElement', {
					selectedElement : this.selectedElement,
					tableCode       : this.tableCode,
				});
			},

			/**
			 * Создание элемента переход на страницу редактирования
			 */
			createElement()
			{
				this.$emit('createElement', {
					selectedElement : this.selectedElement,
					tableCode       : this.tableCode,
				});
			},

			/**
			 * Отмена редактирования
			 */
			cancel()
			{
				this.$emit('cancel');
			},

			/**
			 * Удаление элемента
			 */
			remove()
			{
				this.$emit('removeElement', {
					selectedElement : this.selectedElement,
					tableCode       : this.tableCode,
				});
			},
		},
	};
</script>
<style lang="scss">
	.detail
	{
		padding: 23px 0 0px 21px;
		height: 100%;
	    overflow: auto;
	}
	.detail-head
	{
		display: flex;
		margin-bottom: 28px;
		padding-right: 95px;
		align-items: center;
	}
	.detail-head-name
	{
		display: flex;
		align-items: center;
		width:100%;
	}
	.detail-icon-wrapper
	{
		margin-right: 3px;
		font-weight: 500;
		line-height: normal;
		font-size: 20px;
		color: #000000;
	}
	.detail-head-label
	{
		font-family: $rBold;
		font-size: 20px;
		color: #191C21;
		line-height: 22px;
		text-transform: capitalize;
	}
	.detail-head-descr
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		text-transform: lowercase;
	}
	.detail-name-wrapper{padding-left:7px; }
	.detail-feilds{border-top: 1px solid rgba(103, 115, 135, 0.1);}
	.detail-feild
	{
		min-height: 40px;
		display: flex;
		align-items: flex-start;
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
	}
	.detail-field__name-wrap {display: flex; margin-top: 6px;padding-left: 21px;}
	.detail-field__icon-image{margin-right: 15px;width: 13px;}
	.detail-field-name
	{
		width:140px;
		flex-shrink:0;
		span
		{
			display:block;
			font-size: 12px;
			line-height: normal;
			color: #191C21;
			text-transform: capitalize;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		small
		{
			display:block;
			font-size: 10px;
			line-height: normal;
			color: rgba(103, 115, 135, 0.4);
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
	}
	.detail-field-box
	{
		position: relative;
		min-width: 400px;
		min-height: 40px;
		border-left: 1px solid rgba(103, 115, 135, 0.1);
		padding-left: 14px;
		display: flex;
		align-items: center;
		.em-string,
		.em-list,
		.em-node,
		.em-user,
		.em-file-item-col,
		.em-date-wr
		{
			max-height: 40px;
			position: relative;
		}
		.em-string
		{
			max-height: 16px;
			line-height: 1.15;
		}
		.ql-editor
		{
			padding: 0;
		}
	}
	.detail-head__buttons
	{
		flex-grow: 1;
		text-align: right;
		button{
			margin-left:10px;
		}
	}
	.detail__buttons { display: none; }
	.detail-head__burger { margin-right: 20px; }
	@media (max-width: 768px)
	{
		.detail { padding: 10px 0 0px 10px; }
		.detail-head
		{
			padding-right: 14px;
			min-width: 320px;
		}
		.detail-head-name { flex-wrap: wrap; }
		.detail-head__buttons { display: none; }
		.detail-name-wrapper { flex-basis: 90%; margin-bottom: 0; }
		.detail-icon-wrapper { flex-basis: 4%; margin-bottom: 0; }
		.detail-field-box { min-width: 150px; }
		.detail__buttons
		{
			display: block;
			padding-top: 20px;
			margin-top: auto;
			button { margin-left: 10px; &:first-child { margin-left: 0; } }
		}
	}
</style>
