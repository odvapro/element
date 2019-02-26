<template>
	<div class="index__wrapper">
		<div class="index__head">
			<div class="index__head-name" v-if="table">
				<div class="index__icon-wrapper">
					<svg width="14" height="13">
						<use xlink:href="#tableicon"></use>
					</svg>
				</div>
				<div class="index__name-wrapper">
					<div class="index__overide-name">{{table.name}}</div>
					<div class="index__real-name">{{table.code}}</div>
				</div>
			</div>
			<div class="index__head-options">
				<ul class="index__head-options-list">
					<li>Views</li>
					<li @click="togglePropertiesPopup()">
						Properties
						<Properties v-if="isPropertiesPopupShow && propertiesPopupData" :columns="propertiesPopupData" @closePropertiesPopup="togglePropertiesPopup" />
					</li>
					<!-- #TODO popups -->
					<li>Sort</li>
					<li>Filter</li>
					<li class="index__points">
						<svg width="19" height="2">
							<use xlink:href="#points"></use>
						</svg>
					</li>
				</ul>
				<button class="index__head-add-btn">
					<div class="index__head-btn-wrapper">
						<svg width="12" height="12">
							<use xlink:href="#plus"></use>
						</svg>
					</div>
					<span class="index__head-add-btn-name">
						Add Element
					</span>
				</button>
			</div>
		</div>
		<Table :table="table" :tview="activeTview" v-if="table && activeTview" />
	</div>
</template>
<script>
	import Table from '@/components/tviews/Table.vue';
	import Properties from '@/components/popups/Properties.vue';
	import TableWork from '@/mixins/tableWork.js';
	export default
	{
		mixins: [TableWork],
		components: { Table, Properties },
		/**
		 * Глобальные пересенные странциы
		 */
		data()
		{
			return {
				table: false,
				isPropertiesPopupShow: false,
				propertiesPopupData: {}
			}
		},
		computed:
		{
			/**
			 * Достать активный tView
			 */
			activeTview()
			{
				let tviewId = this.$route.params.tview;
				for (let tview of this.table.tviews)
					if(tview.id == tviewId)
						return tview;
			}
		},
		methods:
		{
			/**
			 * Отобразить попап настроек полей
			 */
			togglePropertiesPopup()
			{
				this.isPropertiesPopupShow = !this.isPropertiesPopupShow;
			},
			/**
			 * Определить активную таблицу
			 */
			activeTable()
			{
				for (let table of this.$store.state.tables.tables)
				{
					if(table.code == this.$route.params.tableCode)
					{
						this.table = table;
						this.propertiesPopupData = table.columns;
						break;
					}
				}
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.activeTable();

			for (let table of this.$store.state.tables.tables)
			{
				let tview = this.getDefaultTview(table);
				this.$set(table, 'visible', typeof tview.settings.table == 'undefined' ? false : tview.settings.table.visible === 'true' ? true : false);
			}
		},
		watch:
		{
			/**
			 * Следить за изменением урла
			 */
			'$route.fullPath'()
			{
				this.activeTable();
			}
		}
	}
</script>
<style lang="scss">
	.index__wrapper { padding: 23px 20px 23px 21px; }
	.index__head
	{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		margin-bottom: 16px;
		padding-right: 75px;
	}
	.index__head-name
	{
		display: flex;
		align-items: center;
	}
	.index__icon-wrapper {margin-right: 12px; }
	.index__overide-name
	{
		font-family: $rBold;
		font-size: 20px;
		color: #191C21;
		line-height: 22px;
		text-transform: capitalize;
	}
	.index__real-name
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		text-transform: lowercase;
	}
	.index__head-options
	{
		display: flex;
		align-items: center;
	}
	.index__head-options-list
	{
		padding-right: 6px;
		display: flex;
		align-items: center;
		&__points
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
		li
		{
			color: rgba(25, 28, 33, 0.7);
			font-size: 12px;
			margin-right: 21px;
			cursor: pointer;
			display: flex;
			align-items: center;
			position: relative;
		}
	}
	.index__head-add-btn
	{
		display: flex;
		background-color: rgba(25, 28, 33, 0.7);
		border-radius: 2px;
		padding: 0 11px 0 32px;
		position: relative;
		font-size: 12px;
		color: #fff;
		height: 31px;
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
	.index__head-btn-wrapper
	{
		position: absolute;
		left: 10px;
		width: 12px;
		height: 12px;
		top: calc(50% - 6px);
	}
	.index__head-add-btn-name
	{
		color: #fff;
	}
</style>