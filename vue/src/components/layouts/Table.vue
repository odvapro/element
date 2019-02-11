<template>
	<div class="table-wrapper" @mousemove="resizeColumn($event, column.col)" @mouseup="endResize($event, column.col)">
		<div class="table-head">
			<div class="table-head-name" v-if="tableInfo">
				<div class="table-icon-wrapper">
					<svg width="14" height="13">
						<use xlink:href="#tableicon"></use>
					</svg>
				</div>
				<div class="table-name-wrapper">
					<div class="table-overide-name">{{tableInfo.name.overide}}</div>
					<div class="table-real-name">{{tableInfo.name.real}}</div>
				</div>
			</div>
			<div class="table-head-options">
				<ul class="table-head-options-list">
					<li>Views</li>
					<li @click.stop.prevent="showPopup($event.target, 'Properties', 'center-bottom')">Properties</li>
					<li>Sort</li>
					<li>Filter</li>
					<li class="points">
						<svg width="19" height="2">
							<use xlink:href="#points"></use>
						</svg>
					</li>
				</ul>
				<button class="table-head-add-btn">
					<div class="table-head-btn-wrapper">
						<svg width="12" height="12">
							<use xlink:href="#plus"></use>
						</svg>
					</div>
					<span class="table-head-add-btn-name">
						Add Element
					</span>
				</button>
			</div>
		</div>
		<div class="table-vertical-scroll">
			<div class="table__min-width" :style="{'min-width': getTableMinWidth + 'px'}">
				<div class="table-row no-hover">
					<div class="table-item" v-for="item in tableInfo.tableColumns" :style="{width: item.width + 'px', 'min-width': item.width + 'px'}">
						<div class="table-item-img">
							<img :src="item.em_info.iconPath" alt="">
						</div>
						<div class="table-item-name-wrapper">
							<div class="table-item-overide-name">{{item.field}}</div>
							<div class="table-item-real-name">{{item.field}}</div>
						</div>
						<div class="drug-col" @mousedown="reginsterEventResize($event, item)"></div>
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
				<div class="table-row" v-for="(row, rowIndex) in result.items">
					<div class="table-overlay-row">
						<button class="table-row-btn">edit</button>
						<button class="table-row-btn">remove</button>
					</div>
					<div class="table-item" v-for="item in tableInfo.tableColumns" :style="{width: item.width + 'px', 'min-width': item.width + 'px'}">
						<MainField :fieldValue="row[item.field]"/>
					</div>
					<div class="table-item">
						<div class="table-empty-col"></div>
					</div>
				</div>
			</div>
		</div>
		<Pagination
			v-if="result.total_pages > 1"
			:maxPage="result.total_pages"
			:current="result.current"
			@change="selectPage"
		/>
	</div>
</template>
<script>
	import EmCheck from '@/components/fields/EmCheckField.vue';
	import MainField from '@/components/fields/MainField.vue';
	import Pagination from '@/components/layouts/Pagination.vue';
	import TagItem from '@/components/forms/TagItem.vue';
	import Popup from '@/mixins/popup.js';
	export default
	{
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
					posX: 0
				},
				column:
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
			 * Определить минимальную ширину таблицу
			 */
			getTableMinWidth()
			{
				var width = 0;

				for (var item in this.tableInfo.tableColumns)
				{
					item = this.tableInfo.tableColumns[item];
					width += item.width;
				}

				return width + 300;
			},
			/**
			 * Результат запроса на доставание содержимого таблицы
			 */
			result()
			{
				if(typeof this.$store.state.tables.tableContent.items == 'undefined')
					return {};

				return this.$store.state.tables.tableContent;
			},
			/**
			 * Достать информацию о таблице
			 */
			tableInfo()
			{
				if(!this.$store.state.tables.tableName.real)
					return false;

				return {
					name         : this.$store.state.tables.tableName,
					tableColumns : this.$store.state.tables.tableColumns
				};
			},
			/**
			 * Достать название таблицы из урла
			 */
			getUrlTableName()
			{
				this.$route.params.tableName;
			}
		},
		methods:
		{
			/**
			 * Начальные значения для изменения ширины колонки
			 */
			reginsterEventResize(event, col)
			{
				this.column.start = event.target.getBoundingClientRect().left;
				this.column.width = col.width;
				this.column.isDrug = true;
				this.column.posX = event.pageX;
				this.column.col = col;
			},
			/**
			 * Убрать событие изменения ширины
			 */
			endResize(event, col)
			{
				this.column.isDrug = false;
			},
			/**
			 * Изменять ширину колонки
			 */
			resizeColumn(event, col)
			{
				if (!this.column.isDrug)
					return false;

				col.width = Math.abs(this.column.posX - event.pageX - this.column.width);

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

				if (this.$cookie.get('drugPosition') > 200)
					app.style.gridTemplateColumns = this.$cookie.get('drugPosition') + 'px auto';
				else
					app.style.gridTemplateColumns = '400px auto';

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
			this.initEventScale();
		}
	}
</script>
<style lang="scss">
	.table-head
	{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		margin-bottom: 16px;
	}
	.table-wrapper
	{
		padding: 23px 95px 23px 21px;
	}
	.table-head-name
	{
		display: flex;
		align-items: center;
	}
	.table-real-name
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		text-transform: lowercase;
	}
	.table-overide-name
	{
		font-family: $rBold;
		font-size: 20px;
		color: #191C21;
		line-height: 22px;
		text-transform: capitalize;
	}
	.table-icon-wrapper
	{
		margin-right: 12px;
	}
	.table-head-options
	{
		display: flex;
		align-items: center;
	}
	.table-head-options-list
	{
		padding-right: 6px;
		display: flex;
		align-items: center;
		li
		{
			color: rgba(25, 28, 33, 0.7);
			font-size: 12px;
			margin-right: 21px;
			cursor: pointer;
			display: flex;
			align-items: center;
			&.points
			{
				position: relative;
				padding: 0 7px;
				width: 35px;
				height: 25px;
				img
				{
					width: 100%;
					height: 100%;
					object-fit: contain;
				}
			}
		}
	}
	.table-head-add-btn
	{
		display: flex;
		background-color: rgba(25, 28, 33, 0.7);
		border-radius: 2px;
		padding: 0 11px 0 32px;
		position: relative;
		font-size: 12px;
		color: #fff;
		height: 30px;
		align-items: center;
		color: #fff;
		outline: none;
		border: none;
		cursor: pointer;
		&:active
		{
			border: none;
		}
	}
	.table-head-btn-wrapper
	{
		position: absolute;
		left: 10px;
		width: 12px;
		height: 12px;
		top: calc(50% - 6px);
	}
	.table-head-add-btn-name
	{
		color: #fff;
	}
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