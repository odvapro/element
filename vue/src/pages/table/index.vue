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
					<li
						class="index__menu-item"
						:class="{active: popups.isPropertiesPopupShow}"
						@click="openPopup('isPropertiesPopupShow')"
					>
						{{$t('properties')}}
						<Properties
							v-if="popups.isPropertiesPopupShow && propertiesPopupData"
							:columns="propertiesPopupData"
							v-click-outside:isPropertiesPopupShow="closePopup"
						/>
					</li>
					<!-- #TODO popups -->
					<li
						:class="{active: popups.isSortPopupShow}"
						class="index__menu-item"
						@click="openPopup('isSortPopupShow')"
					>
						{{$t('sort')}}
						<SortPopup
							v-if="popups.isSortPopupShow"
							:columns="table.columns"
							:tview="activeTview"
							v-click-outside:isSortPopupShow="closePopup"
						/>
					</li>
					<li
						:class="{active: popups.isFiltersPopupShow}"
						@click="openPopup('isFiltersPopupShow')"
						class="index__menu-item"
					>
						{{$t('filter')}}
						<FiltersPopup
							v-if="popups.isFiltersPopupShow"
							:columns="table.columns"
							:tview="activeTview"
							v-click-outside:isFiltersPopupShow="closePopup"
						/>
					</li>
					<li
						:class="{active: popups.isExportPopupShow}"
						class="index__menu-item index__menu-item-more"
						@click="openPopup('isExportPopupShow')"
					>
						...
						<ExportPopup
							v-if="popups.isExportPopupShow"
							v-click-outside:isExportPopupShow="closePopup"
							:tview="activeTview"
						/>
					</li>
				</ul>
				<button class="index__add-btn el-btn" @click="addElement()">
					<svg width="12" height="12">
						<use xlink:href="#plus"></use>
					</svg>
					{{$t('add_element')}}
				</button>
			</div>
		</div>
		<Table :table="table" :tview="activeTview" v-if="table && activeTview" />
	</div>
</template>
<script>
	import FiltersPopup from '@/components/popups/FiltersPopup.vue';
	import ExportPopup from '@/components/popups/ExportPopup.vue';
	import SortPopup from '@/components/popups/SortPopup.vue';
	import Properties from '@/components/popups/Properties.vue';
	import TableWork from '@/mixins/tableWork.js';
	export default
	{
		mixins: [TableWork],
		components: { Properties, FiltersPopup, ExportPopup, SortPopup },
		/**
		 * Head параметры страницы
		 */
		metaInfo()
		{
			const tableName = (!this.table) ? '' : this.table.name;
			return {
				title: `Table: ${tableName}`
			}
		},
		/**
		 * Глобальные пересенные странциы
		 */
		data()
		{
			return {
				table: false,
				popups:
				{
					isPropertiesPopupShow : false,
					isFiltersPopupShow    : false,
					isSortPopupShow       : false,
					isExportPopupShow     : false,
				},
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
			 * Отобразить/Закрыть попап
			 */
			openPopup(popupName)
			{
				this.popups[popupName] = true;
			},
			/**
			 * Закрыть все попапы
			 */
			closePopup(event,popupName)
			{
				this.popups[popupName] = false;
			},
			/**
			 * Определить активную таблицу
			 */
			activeTable()
			{
				this.table = this.$store.getters.getTable(this.$route.params.tableCode);
				this.propertiesPopupData = this.table.columns;
			},

			addElement()
			{
				this.$router.push(`/table/${this.table.code}/add/`);
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.activeTable();
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
	.index__wrapper { padding: 23px 0px 0px 21px; }
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
		.index__points
		{
			position: relative;
			padding: 0 8px;
			width: 35px;
			height: 25px;
			display: flex;
			align-items: center;
			pointer-events: none;
			img
			{
				width: 100%;
				height: 100%;
				object-fit: contain;
			}
		}
	}
	.index__menu-item
	{
		color: rgba(25, 28, 33, 0.7);
		font-size: 12px;
		margin-right: 5px;
		cursor: pointer;
		position: relative;
		padding: 5px 8px;
		&-more
		{
			font-size: 15px;
			line-height: 1;
			height: 23px;
			padding: 0px 2px;
		}
		&.active, &:hover
		{
			background-color: rgba(103, 115, 135, 0.1);
			border-radius: 2px;
		}
	}
	.index__add-btn svg
	{
		position: relative;
		top:1px;
		margin-right:2px;
	}
</style>