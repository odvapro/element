<template>
	<div class="table-vertical-scroll"
		@mousemove="resizeColumn($event, columnDrug.col)"
		@mouseup="endResize($event, columnDrug.col)">
		<div class="table__min-width" :style="{'min-width': getTableMinWidth + 'px'}">
			<div class="table-row no-hover">
				<div class="table-item" v-for="column in table.columns" :style="{ width: column.width + 'px', 'min-width': column.width + 'px' }">
					<div class="table-item-img">
						<img :src="column.em.type_info.iconPath" alt="">
					</div>
					<div class="table-item-name-wrapper">
						<div class="table-item-overide-name">{{column.field}}</div>
						<div class="table-item-real-name">{{column.field}}</div>
					</div>
					<div class="drug-col" @mousedown="reginsterEventResize($event, column)"></div>
				</div>
				<div class="table-item">
					<div class="table__add-column-item">
						<div class="table__add-col-img">
							<svg width="12" height="12">
								<use xlink:href="#plus-white"></use>
							</svg>
						</div>
						<span class="table__add-col-label">Add field</span>
					</div>
				</div>
			</div>
			<div class="table-row" v-for="(row, rowIndex) in tableContent.items">
				<div class="table-overlay-row">
					<button class="table-row-btn">edit</button>
					<button class="table-row-btn">remove</button>
				</div>
				<div
					class="table-item"
					v-for="column in table.columns"
					:style="{width: column.width + 'px', 'min-width': column.width + 'px'}"
				>
					<MainField :fieldValue="row[column.field]"/>
				</div>
				<div class="table-item">
					<div class="table-empty-col"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- <Pagination
		v-if="tableContent.total_pages > 1"
		:maxPage="tableContent.total_pages"
		:current="tableContent.current"
		@change="selectPage"
	/> -->
</template>
<script>
	import EmCheck from '@/components/fields/EmCheckField.vue';
	import MainField from '@/components/fields/MainField.vue';
	import Pagination from '@/components/layouts/Pagination.vue';
	import TagItem from '@/components/forms/TagItem.vue';
	import Popup from '@/mixins/popup.js';
	export default
	{
		props:['table', 'tview'],
		mixins: [Popup],
		components: {MainField, EmCheck, TagItem, Pagination},
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				points:
				{
					isDrug: false,
					posX: this.$cookie.get('drugPosition')
				},
				columnDrug:
				{
					isDrug: false,
					posX: 0,
					start: 0,
					top: 0,
					width: 0,
					col: ''
				},
				openProperties: false
			}
		},
		computed:
		{
			/**
			 * Достать из стора содержимое таблицы
			 */
			tableContent()
			{
				return this.$store.state.tables.tableContent;
			},
			/**
			 * Определить минимальную ширину таблицу
			 */
			getTableMinWidth()
			{
				let sum = 0;

				for (let column in this.table.columns)
					sum += +this.table.columns[column].width;

				return sum + 300;
			},
			/**
			 * Достать название таблицы из урла
			 */
			getUrlTableName()
			{
				this.$route.params.tableName;
			}
		},
		watch:
		{
			/**
			 * Если меняется урл то выполнять
			 */
			'$route.fullPath'()
			{
				this.setDefaultWidth();
				this.getTableContent();
			}
		},
		methods:
		{
			/**
			 * Задать колонкам ширину
			 */
			setDefaultWidth()
			{
				let colObject = {};

				for (var colIndex in this.table.columns)
				{
					colObject = this.table.columns[colIndex];

					if (typeof this.tview.settings.columns == 'undefined')
						this.$set(colObject, 'width', 140);
					else if (typeof this.tview.settings.columns[colIndex] == 'undefined')
						this.$set(colObject, 'width', 140);
					else
						this.$set(colObject, 'width', this.tview.settings.columns[colIndex].width);
				}
			},
			/**
			 * Достать содержимое таблицы
			 */
			async getTableContent()
			{
				var select = [];

				if (typeof this.tview.filter.select != 'undefined')
					select = this.tview.filter.select;

				select.from = this.$route.params.tableCode;
				select.page = this.$route.params.page;
				select.tview = this.$route.params.tview;

				await this.$store.dispatch('select', {select: select});
			},
			/**
			 * Сохранить ширину колонок
			 */
			async saveColumnsWidth()
			{
				let tableColumns = this.table.columns,
					request      = {};

				request['tviewId'] = this.tview.id;
				request['params'] = {};
				request['params']['columns'] = {};

				for (let column in tableColumns)
				{
					request['params']['columns'][column] = {};
					request['params']['columns'][column] = { width: tableColumns[column].width };
				}

				this.$store.dispatch('saveColumnsWith', request);
			},
			/**
			 * Начальные значения для изменения ширины колонки
			 */
			reginsterEventResize(event, col)
			{
				this.columnDrug.start = event.target.getBoundingClientRect().left;
				this.columnDrug.width = col.width;
				this.columnDrug.isDrug = true;
				this.columnDrug.posX = event.pageX;
				this.columnDrug.col = col;
			},
			/**
			 * Убрать событие изменения ширины
			 */
			endResize(event, col)
			{
				if (this.columnDrug.isDrug)
					this.saveColumnsWidth();

				this.columnDrug.isDrug = false;
			},
			/**
			 * Изменять ширину колонки
			 */
			resizeColumn(event, col)
			{
				if (!this.columnDrug.isDrug)
					return false;

				col.width = Math.abs(this.columnDrug.posX - event.pageX - this.columnDrug.width);

				if (col.width < 110)
					col.width = 110;

				if (col.width > 600)
					col.width = 600;
			},
			/**
			 * Инициализация событий для уменьшения/увеливения сайдбара
			 */
			initEventScale()
			{
				var app = document.getElementsByClassName('app-wrapper')[0],
					self = this;

				document.addEventListener('mousedown', function(event)
				{
					if (event.target.classList.value != 'drug')
						return false;

					self.points.isDrug = true;
				}, false);

				document.addEventListener('mousemove', function(event)
				{
					if (!self.points.isDrug)
						return false;

					if (event.pageX < 200 || event.pageX > 480)
						return false;

					self.points.posX = event.pageX;
					app.style.gridTemplateColumns = event.pageX + 'px auto'
				}, false);
				document.addEventListener('mouseup', function(event)
				{
					self.points.isDrug = false;
					self.$cookie.set('drugPosition', self.points.posX, 111);
				}, false);
			},
			/**
			 * Эмит с пагинации. Задает текущую страницу
			 */
			selectPage(page)
			{
				this.$store.dispatch('selectPage', page);
				this.$router.push(`/table/${typeof this.getUrlTableName == 'undefined' ? this.tableInfo.name.real : this.getUrlTableName}/${page}`);
			}
		},
		/**
		 * Хук при загразке страницы
		 */
		mounted()
		{
			this.setDefaultWidth();
			this.getTableContent();
			this.initEventScale();
		}
	}
</script>
<style lang="scss">

	.table-item-img
	{
		width: 14px;
		height: 14px;
		margin-right: 6px;
	}
	.table-overlay-row-option-icon
	{
		width: 19px;
		height: 2px;
		position: relative;
		z-index: 2;
	}
	.table-row-btn
	{
		position: relative;
		z-index: 2;
		color: rgba(103, 115, 135, 0.4);
		font-size: 12px;
		background-color: transparent;
		border: none;
		cursor: pointer;
		padding: 0;
		margin-right: 10px;
		&:hover
		{
			color: rgba(25, 28, 33, 0.7);
			text-decoration: underline;
		}
	}
	.table-item
	{
		display: flex;
		word-break: break-word;
		align-items: center;
		height: 49px;
		padding-right: 10px;
		overflow: hidden;
		padding-left: 9px;
		position: relative;
		border-right: 1px solid rgba(103, 115, 135, 0.1);
		&:last-child
		{
			border-right: none;
		}
	}
	.table-item-overide-name
	{
		line-height: 12px;
		font-size: 12px;
		color: #677387;
		text-transform: capitalize;
	}
	.table-item-real-name
	{
		line-height: 12px;
		font-size: 10px;
		color: rgba(103, 115, 135, 0.4);
		text-transform: lowercase;
	}
	.table-row
	{
		.table-overlay-row
		{
			display: none;
		}
		display: flex;
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		position: relative;
		&:last-child
		{
			border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		}
		&.no-hover:hover .table-overlay-row
		{
			display: none;
		}
		&:hover
		{
			.table-overlay-row
			{
				background-color: rgba(103, 115, 135, 0.1);
				width: 100%;
				padding-left: 10px;
				display: flex;
				align-items: center;
				position: absolute;
				height: 100%;
				top: 0;
				left: 0;
				&:after
				{
					content: '';
					background-color: #f0f1f3;
					width: 139px;
					height: 100%;
					z-index: 1;
					position: absolute;
					left: 0;
				}
			}
		}
		.em-check-label
		{
			margin-right: 8px;
			z-index: 2;
			span
			{
				background-color: #fff;
			}
		}
	}
	.table-item-image-col
	{
		display: flex;
		flex-wrap: wrap;
	}
	.table-item-img-wrapper
	{
		width: 14px;
		height: 14px;
		margin-right: 3px;
		cursor: pointer;
		margin-bottom: 3px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	}
	.table-item-checkbox
	{
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.table-vertical-scroll
	{
		overflow: auto;
	}
	.table__add-column-item
	{
		display: flex;
		align-items: center;
		cursor: pointer;
	}
	.table__add-col-label
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 12px;
	}
	.table__add-col-img
	{
		margin-right: 9px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: contain;
		}
	}
	.drug-col
	{
		width: 4px;
		height: 100%;
		position: absolute;
		top: 0;
		right: -2px;
		cursor: col-resize;
		transition: all 0.3s;
		&:hover
		{
			background-color: #e6e6e6;
		}
	}
</style>