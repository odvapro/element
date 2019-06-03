<template>
	<div class="detail">
		<div class="detail-head">
			<div class="detail-head-name">
				<div class="detail-icon-wrapper">
					<svg width="14" height="13">
						<use xlink:href="#tableicon"></use>
					</svg>
				</div>
				<template v-if="$route.name != 'tableAddElement'">
					<div class="detail-name-wrapper">
						<div class="detail-head-label">Edit element</div>
						<div class="detail-head-descr">{{ tableCode }}</div>
					</div>
					<div class="detail-head__buttons">
						<button @click="cancel" class="el-gbtn">Cancel</button>
						<button @click="remove" class="el-gbtn">Remove</button>
						<button @click="saveElement" class="el-btn">Save</button>
					</div>
				</template>
				<template v-else>
					<div class="detail-name-wrapper">
						<div class="detail-head-label">New Element</div>
						<div class="detail-head-descr">{{ tableCode }}</div>
					</div>
					<div class="detail-head__buttons">
						<button @click="cancel" class="el-gbtn">Cancel</button>
						<button @click="createElement" class="el-btn">Create</button>
					</div>
				</template>
			</div>
		</div>
		<div class="detail-feild" v-for="(column,columnCode) in selectedElement">
			<div class="detail-field-name">
				<span>{{ columnCode }}</span>
				<small>{{ columnCode }}</small>
			</div>
			<div class="detail-field-box">
				<MainField
					mode="edit"
					view="detail"
					:params="{
						fieldName : column.fieldName,
						value     : column.value,
						settings  : $store.getters.getColumnSettings(tableCode, columns[columnCode], selectedElement)
					}"
					@onChange="changeFieldValue"
				/>
			</div>
		</div>
	</div>
</template>
<script>
	import MainField from '@/components/fields/MainField.vue';
	import qs from 'qs';
	export default
	{
		components: {MainField},
		data()
		{
			return {
				columns:{},
				tableCode:false,
				selectedElement:{}
			}
		},
		mounted()
		{
			let requestParams = {
				select : {},
				where  : [],
				order  : [],
			};
			requestParams.select.from = this.$route.params.tableCode;
			let primaryKeyCode        = this.$store.getters.getPrimaryKeyCode(this.$route.params.tableCode);
			this.columns              = this.$store.getters.getColumns(this.$route.params.tableCode);
			this.tableCode            = this.$route.params.tableCode;
			if(this.$route.name != 'tableAddElement')
			{
				requestParams.select.where = {
					operation:'and',
					fields:[
						{
							code      : primaryKeyCode,
							operation : 'IS',
							value     : this.$route.params.id
						}
					]
				}
				this.$store.dispatch('selectElement',requestParams).then(()=>
				{
					this.selectedElement = this.$store.state.tables.selectedElement;
				});
			}
			else
			{
				for(let columnCode in this.columns)
				{
					this.selectedElement[columnCode] = {
						value     :'',
						fieldName :this.columns[columnCode].em.type_info.code
					}
				}
			}

		},
		methods:
		{
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
				this.selectedElement[fieldValue.settings.fieldCode].value = fieldValue.value;
			},

			/**
			 * Сохранение элемента
			 */
			saveElement()
			{
				this.$store.dispatch('saveSelectedElement',{
					selectedElement : this.selectedElement,
					tableCode       : this.tableCode
				}).then(()=>
				{
					this.ElMessage('👌 Element saved!');
				});
			},

			/**
			 * Создание элемента переход на страницу редактирования
			 */
			async createElement()
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.tableCode);
				let setColumns  = [];
				let setValues  = [];
				for(let fieldCode in this.selectedElement)
				{
					if(primaryKeyCode == fieldCode) continue;
					setColumns.push(fieldCode);
					setValues.push(this.selectedElement[fieldCode].value);
				}

				var data = qs.stringify({
					insert:
					{
						table   :this.tableCode,
						columns :setColumns,
						values  :setValues
					}
				});
				let result = await this.$axios.post('/el/insert/',data);
				if(result.data.success == true)
				{
					this.$router.push({name:'tableDetail', params:{tableCode:this.tableCode, id:result.data.lastid }});
					this.ElMessage('Element created!');
				}
				else
					this.ElMessage.error('Cant create element!');
			},

			/**
			 * Отмена редактирования
			 */
			cancel()
			{
				this.$router.go(-1);
			},

			/**
			 * Удаление элемента
			 */
			async remove()
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.tableCode);
				await this.$store.dispatch('removeRecord', {
					delete:
					{
						table: this.tableCode,
						where:
						{
							operation:'and',
							fields:[
								{
									code      : primaryKeyCode,
									operation : 'IS',
									value     : this.selectedElement[primaryKeyCode].value
								}
							]
						}
					}
				}).then(()=>
				{
					this.cancel();
					this.ElMessage('Element removed!');
				});
			}
		}
	}
</script>
<style lang="scss">
	.detail {padding: 23px 0 23px 21px; }
	.detail-head
	{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		margin-bottom: 13px;
		padding-right: 95px;
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
	.detail-feild{
		min-height: 50px;
		display: flex;
		align-items: center;
	}
	.detail-field-name
	{
		width:200px;
		flex-shrink:0;
		span
		{
			display:block;
			font-size: 12px;
			line-height: normal;
			color: #191C21;

		}
		small
		{
			display:block;
			font-size: 10px;
			line-height: normal;
			color: rgba(103, 115, 135, 0.4);
		}
	}
	.detail-field-box
	{
		position: relative;
		min-width: 200px;
		height: 49px;
	}
	.detail-head__buttons
	{
		flex-grow: 1;
		text-align: right;
		button{
			margin-left:10px;
		}
	}
</style>