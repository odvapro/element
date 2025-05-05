<template>
	<div class="em-matrix">
		<div class="em-matrix-table" v-if="view=='detail'">
			<table v-if="fieldValue.matrixValue.length">
				<tr>
					<th v-for="fieldObj of headFields">
						{{ fieldObj.name }}
						<small>{{ fieldObj.key }}</small>
					</th>
					<th></th>
				</tr>
				<tr v-for="(tableRow, rowIndex) in localValue.matrixValue">
					<td v-for="(columnItem,colKey) in tableRow" v-if="getColumnSettings(colKey).visibility == 'true'">
							<MainField
								mode="view"
								view="detail"
								:fieldName="getColumnSettings(colKey).settings.code"
								:params="{
									value     : columnItem,
									settings  : $store.getters.getColumnSettings(fieldSettings.finalTableCode, colKey, columnItem)
								}"
							/>
					</td>
					<td class="em-matrix-table__edit-btns">
						<div class="em-matrix-field__hover-btns">
							<div
								class="em-matrix-field em-matrix-field__edit"
								@click="popupForEditMatrixColumn(tableRow, rowIndex)"
							>
								{{$t('edit')}}
							</div>
							<div
								class="em-matrix-field em-matrix-field__remove"
								@click="removeMatrixElement({tableCode:fieldSettings.finalTableCode, selectedElement: tableRow})"
							>
								{{$t('remove')}}
							</div>
						</div>
					</td>
				</tr>
			</table>
			<div class="em-matrix-row-add">
				<button @click="popupForCreateMatrixElement()">
					<div class="em-matrix-row-add__icon">
						<svg width="9" height="9">
							<use xlink:href="#plus-gray"></use>
						</svg>
					</div>
					<div class="em-matrix-row-add__text"> New Element</div>
				</button>
			</div>
		</div>
		<div v-else>
			<span class="el-empty">Matrix field</span>
		</div>
		<DetailPopup
			:visible.sync="showDetail"
			:tableCode="detailTableCode"
			:name="detailName"
			:id="detailTableId"
			:element="currentElement"
			@saveElement="savePopupMatrixElement"
			@createElement="createPopupMatrixElement"
			@removeElement="removePopupMatrixElement"
		></DetailPopup>
	</div>
</template>
<script>
	import MainField from '@/components/fields/MainField.vue';
	import detailFunctions from '@/mixins/detailFunctions.js';
	import DetailPopup from '@/components/popups/DetailPopup';
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		components:{DetailPopup,MainField},
		mixins: [detailFunctions],
		data()
		{
			return {
				showDetail     : false,
				detailTableCode: false,
				detailTableId  : false,
				detailName     : false,
				currentElement : false,
				headFields : [],
				localValue: [],
			};
		},
		computed:
		{
			detailElement()
			{
				return this.$store.state.tables.selectedElement;
			},
		},
		methods:
		{
			updateMatrixTableElement(element)
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode);

				for (let [index, matrixValue] of this.fieldValue.matrixValue.entries())
					if (matrixValue[primaryKeyCode] == element[primaryKeyCode])
						return this.fieldValue.matrixValue[index] = element;
			},

			createMatrixTableElement(element)
			{
				if (typeof this.fieldValue.matrixValue == 'undefined')
					this.$set(this.fieldValue, 'matrixValue', []);

				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode);
				this.fieldValue.matrixValue.push(element);
			},

			removeMatrixTableElement(element)
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode);

				for (let [index, matrixValue] of this.localValue.matrixValue.entries())
					if (matrixValue[primaryKeyCode] == element[primaryKeyCode])
							return this.localValue.matrixValue.splice(index, 1);
			},

			popupForEditMatrixColumn(row, rowIndex)
			{
				this.currentElement  = false;
				let primaryKeyCode   = this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode);
				this.detailTableId   = row[primaryKeyCode];
				this.detailTableCode = this.fieldSettings.finalTableCode;
				this.detailName      = false;
				this.showDetail      = true;
			},

			bindDefaultColumnValues()
			{	
				this.currentElement = [];
				let table = this.$store.getters.getTable(this.fieldSettings.finalTableCode);
				for (let column in table.columns)
					this.currentElement[column] = '';
				this.currentElement[this.fieldSettings.finalTableField] = [{...this.fieldSettings.primaryKey, name: this.fieldSettings.primaryKey.value}] ;
			},

			popupForCreateMatrixElement()
			{
				this.bindDefaultColumnValues();
				this.detailTableCode = this.fieldSettings.finalTableCode;
				this.detailName      = 'tableAddElement';
				this.showDetail      = true;
			},

			savePopupMatrixElement(data, result)
			{
				if (result.data.success)
				{
					this.updateMatrixTableElement(data.selectedElement);
					this.ElMessage(this.$t('elMessages.element_saved'));
					this.showDetail = false;
				}
			},

			createPopupMatrixElement(data, result)
			{
				if(result.data.success == true)
				{
					let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.fieldSettings.finalTableCode);
					data.selectedElement[primaryKeyCode] = result.data.lastid;
					this.createMatrixTableElement(data.selectedElement);
					this.ElMessage(this.$t('elMessages.element_created'));
					this.showDetail = false;
				}
				else
					this.ElMessage.error(this.$t('elMessages.cant_create_element'));
			},

			removeMatrixElement(data)
			{
				this.removeElement(data);
				this.showDetail = false;
				this.ElMessage(this.$t('elMessages.element_removed'));
				this.removeMatrixTableElement(data.selectedElement);
			},

			removePopupMatrixElement(data, result)
			{
				this.showDetail = false;
				this.ElMessage(this.$t('elMessages.element_removed'));
				this.removeMatrixTableElement(data.selectedElement);
			},

			getColumnSettings(colCode)
			{
				for (const column of this.fieldSettings.columnsSettings)
				{
					column.settings = this.$store.getters.getColumnSettings(this.fieldSettings.finalTableCode, colCode, false);
					if(column.key == colCode)
					{
						column.name = (column.name != '')?column.name:column.key;
						return column;
					}
				}
				return false;
			},

			setHeadLine()
			{
				if (typeof this.fieldValue.matrixValue == 'undefined' || !this.fieldValue.matrixValue.length)
					return;

				for (let code in this.fieldValue.matrixValue[0])
				{
					let hedField = this.getColumnSettings(code);
					if(hedField.visibility != "true")
						continue;

					this.headFields.push(hedField);
				}

			}
		},
		mounted()
		{
			if(this.view != 'table')
				this.setHeadLine();

			this.localValue = this.fieldValue;
		}
	};
</script>
<style lang="scss">
	.em-matrix-field__hover-btns
	{
		display: flex;
		align-items: center;
	}
	.detail-field-box .em-matrix
	{
		width: 100vw;
		max-width: 100vw;
		margin-left: -14px;
		overflow: scroll;
	}
	.em-matrix-table__edit-btns
	{
		position: sticky;
		right:0px;
		background:#fff;
		opacity: 0;
	}
	tr:hover .em-matrix-table__edit-btns{opacity: 1; }
	.em-matrix-field__edit, .em-matrix-field__remove
	{
		padding-left: 14px;
		border: none;
		color: #677387;
		cursor: pointer;
		transition: all .125s;
	}
	.em-matrix-field__edit {padding-left: 0px; }
	.em-matrix-field__remove {color: rgba(208, 18, 70, 0.7); }
	.em-matrix-row-add
	{
		display: flex;
		align-items: center;
		padding-left: 13px;
		min-height: 50px;
		button
		{
			background: #fff;
			border: 0px;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 10px;
			border-radius: 3px;
			&:hover
			{
				cursor: pointer;
				background:rgba(103, 115, 135, 0.2);
			}
		}
	}
	.em-matrix-row-add__icon
	{
		line-height: 0;
		font-size: 0;
		margin-right: 6px;
	}
	.em-matrix-row-add__text
	{
		color: #677387;
		font-size: 10px;
	}

	.em-matrix-table
	{
		overflow: scroll;
		width: 100%;
		table
		{
			width:100%;
			font-size: 12px;
			border-collapse: collapse;
			margin:-1px;
			th
			{
				text-transform:capitalize;
				font-weight: normal;
				small{
					display: block;
					color: rgba(103, 115, 135, 0.4);
					margin-top: 3px;
					text-transform: lowercase;
				}
			}
			td,th
			{
				text-align: left;
				border:1px solid #efefef;
				padding:0px 0px 0px 15px;
				min-width: 200px;
				max-width: 400px;
				min-height: 50px;
				white-space: nowrap;
				overflow: visible;
				height: 50px;
				text-overflow:ellipsis;
				position: relative;
				&:last-child{border-right: 0px;}
			}
			th{padding: 13px 15px;}
		}
	}
</style>