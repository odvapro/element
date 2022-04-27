<template>
	<div class="em-matrix">
		<div class="em-matrix-table" v-if="view=='detail'">
			<table v-if="fieldValue.matrixValue.length">
				<tr>
					<th v-for="fieldCode in tableHead">{{fieldCode}}</th>
					<th></th>
				</tr>
				<tr v-for="(tableRow, rowIndex) in fieldValue.matrixValue">
					<td v-for="columnItem in tableRow">{{columnItem}}</td>
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
			<div class="em-matrix-row-add" @click="popupForCreateMatrixElement()">
				<div class="em-matrix-row-add__icon">
					<svg width="9" height="9">
						<use xlink:href="#plus-gray"></use>
					</svg>
				</div>
				<div class="em-matrix-row-add__text"> New </div>
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
	import detailFunctions from '@/mixins/detailFunctions.js';
	import DetailPopup from '@/components/popups/DetailPopup';
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		components:{DetailPopup},
		mixins: [detailFunctions],
		data()
		{
			return {
				showDetail: false,
				detailTableCode: false,
				detailTableId: false,
				detailName: false,
				currentElement: false,
			};
		},
		computed:
		{
			tableHead()
			{
				if (typeof this.fieldValue.matrixValue == 'undefined' || !this.fieldValue.matrixValue.length)
					return [];

				let tableCols = [];
				for (let code in this.fieldValue.matrixValue[0])
					tableCols.push(code);
				return tableCols;
			},
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
					if (+matrixValue[primaryKeyCode] == +element[primaryKeyCode])
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

				for (let [index, matrixValue] of this.fieldValue.matrixValue.entries())
					if (+matrixValue[primaryKeyCode] == +element[primaryKeyCode])
							return this.fieldValue.matrixValue.splice(index, 1);
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
				this.currentElement[this.fieldSettings.finalTableField] = this.detailElement[this.fieldSettings.localField];
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
		},
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
		cursor: pointer;
		display: flex;
		align-items: center;
		padding-left: 13px;
		min-height: 31px;
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
		max-width: 100%;
		table
		{
			width:calc(100%);
			font-size: 12px;
			border-collapse: collapse;
			margin:-1px;
			th{text-transform:capitalize; }
			td,th
			{
				text-align: left;
				border:1px solid #efefef;
				padding:15px 13px;
				min-width: 50px;
				max-width: 200px;
				white-space: nowrap;
				overflow: hidden;
				text-overflow:ellipsis;
			}
		}
	}
</style>
