<template>
	<div class="index__wrapper">
		<div class="index__head" v-if="table">
			<div class="index__head-name">
				<div class="index__head-burger"><MobileBurger/></div>
				<div @click="openPopup('isEmojiPickerShow')" class="index__icon-wrapper">
					<svg v-if="!tableEmoji" width="14" height="13">
						<use xlink:href="#tableicon"></use>
					</svg>
					<div
						v-else
						class="index__icon-emoji"
					>{{ tableEmoji }}</div>
				</div>
				<EmojiPicker
					:visible.sync=popups.isEmojiPickerShow
					@pick=pickEmoji
				/>
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
						<div class="index__menu-item-title" :class="{ 'index__menu-item-title--modified': propertiesModified }">{{$t('properties')}}</div>
						<Properties
							v-if="popups.isPropertiesPopupShow && propertiesPopupData"
							:columns="propertiesPopupData"
							@updateModified="updateModified"
							@updateColumnsOrder=updateColumnsOrder
							v-click-outside:isPropertiesPopupShow="closePopup"
						/>
					</li>
					<!-- #TODO popups -->
					<li
						:class="{active: popups.isSortPopupShow}"
						class="index__menu-item"
						@click="openPopup('isSortPopupShow')"
					>
						<div class="index__menu-item-title" :class="{ 'index__menu-item-title--modified': sortModified }">{{$t('sort')}}</div>
						<SortPopup
							v-if="popups.isSortPopupShow"
							:columns="table.columns"
							:tview="activeTview"
							@updateModified="updateModified"
							v-click-outside:isSortPopupShow="closePopup"
						/>
					</li>
					<li
						:class="{active: popups.isFiltersPopupShow}"
						@click="openPopup('isFiltersPopupShow')"
						class="index__menu-item"
					>
						<div class="index__menu-item-title" :class="{ 'index__menu-item-title--modified': filterModified }">{{$t('filter')}}</div>
						<FiltersPopup
							v-if="popups.isFiltersPopupShow"
							:columns="table.columns"
							:tview="activeTview"
							@updateModified="updateModified"
							v-click-outside:isFiltersPopupShow="closePopup"
						/>
					</li>
					<li class="index__menu-item">
						<SearchBlock/>
					</li>
					<li
						:class="{active: popups.isMorePopupShow}"
						class="index__menu-more"
					>
						<div
							@click="openPopup('isMorePopupShow')"
							class="index__menu-item-title"
						>...</div>
						<ul
							v-if="popups.isMorePopupShow"
							class="index__menu-more-list"
							v-click-outside:isMorePopupShow="closePopup"
						>
							<li
								:class="{ active: popups.isPropertiesPopupShow, 'index__menu-more-list-item--modified': propertiesModified }"
								class="index__menu-more-list-item index__menu-more-list-item--mobile"
								@click="closePopup(null, 'isMorePopupShow'); openPopup('isPropertiesPopupShow');"
							>{{$t('properties')}}</li>
							<li
								:class="{ active: popups.isSortPopupShow, 'index__menu-more-list-item--modified': sortModified }"
								class="index__menu-more-list-item index__menu-more-list-item--mobile"
								@click="closePopup(null, 'isMorePopupShow'); openPopup('isSortPopupShow');"
							>{{$t('sort')}}</li>
							<li
								:class="{ active: popups.isFiltersPopupShow, 'index__menu-more-list-item--modified': filterModified }"
								class="index__menu-more-list-item index__menu-more-list-item--mobile"
								@click="closePopup(null, 'isMorePopupShow'); openPopup('isFiltersPopupShow');"
							>{{$t('filter')}}</li>
							<li
								class="index__menu-more-list-item"
							><ExportPopup :tview="activeTview" /></li>
						</ul>
					</li>
				</ul>
				<button class="index__add-btn el-btn" @click="addElement()">
					<svg width="12" height="12">
						<use xlink:href="#plus"></use>
					</svg>
					<span class="index__add-btn-text">{{$t('add_element')}}</span>
				</button>
			</div>
		</div>
		<Table
			:table="table"
			:tview="activeTview"
			v-if="table && activeTview"
		/>
	</div>
</template>
<script>
	import FiltersPopup from '@/components/popups/FiltersPopup.vue';
	import ExportPopup from '@/components/popups/ExportPopup.vue';
	import SortPopup from '@/components/popups/SortPopup.vue';
	import Properties from '@/components/popups/Properties.vue';
	import TableWork from '@/mixins/tableWork.js';
	import MobileBurger from '@/components/blocks/MobileBurger.vue';
	import SearchBlock from '@/components/blocks/SearchBlock.vue';
	import EmojiPicker from '@/components/popups/EmojiPicker.vue';

	export default
	{
		mixins: [TableWork],
		components: { Properties, FiltersPopup, ExportPopup, SortPopup, MobileBurger, SearchBlock, EmojiPicker },
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
					isMorePopupShow       : false,
					isEmojiPickerShow     : false,
				},
				propertiesPopupData: {},
				sortModified      : false,
				filterModified    : false,
				propertiesModified: false,
			};
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
			},
			/**
			 * отдает заданную иконку таблицы
			 */
			tableEmoji()
			{
				if (this.table && this.table.tviews[0] && this.table.tviews[0].settings.emoji)
					return this.table.tviews[0].settings.emoji;
				return false;
			},
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.activeTable();
			this.$store.subscribe((mutation, state) => {
				if(mutation.type == 'setTables')
					this.activeTable();
			});
			this.checkModified();
		},
		methods:
		{
			/**
			 * обновляет таблицу по коду
			 */
			async updateColumnsOrder(columns)
			{
				for (let column of columns)
				{
					this.table.columns[column.code].order = column.order;
					this.activeTview.settings.columns[column.code].order = column.order;
				}

				const result = await this.$store.dispatch('saveTableSettings', {
					tviewId: this.activeTview.id,
					params: {
						columns: this.activeTview.settings.columns,
					},
				});
				if (result)
					this.$store.commit('updateTableByCode', { code: this.table.code, value: this.table });

			},
			/**
			 * выбор иконки и обновление таблицы
			 */
			async pickEmoji(item)
			{
				const request = {
					tviewId: this.$route.params.tview,
					params: {
						emoji: item,
					},
				};

				const result = await this.$store.dispatch('saveTableSettings', request);
				if (result)
				{
					const newSettings = {
						...this.table.tviews[0].settings,
						emoji: item,
					};

					this.$store.commit('updateTviewSettings', { settings: newSettings, code: this.table.code });
					this.activeTable();
				}
			},
			/**
			 * проверяет были ли изменены фильтры/свойства/сортировка
			 */
			checkModified()
			{
				if (!this.table) return;

				const tview = this.table.tviews[0];
				this.sortModified = !!tview.sort.length;
				this.filterModified = !!tview.filter.length;

				if (!tview.settings || !tview.settings.columns) return;

				for (let column of Object.values(tview.settings.columns))
					if (column.visible !== 'true') { this.propertiesModified = true; break; }
			},
			/**
			 * обновляет состояние фильтров/свойств/сортировки
			 */
			updateModified(name, state)
			{
				this.$set(this, `${name}Modified`, state);
			},
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
			/**
			 * переход на страницу создания элемента
			 */
			addElement()
			{
				this.$router.push(`/table/${this.table.code}/add/`);
			},
		},
		watch:
		{
			/**
			 * Следить за изменением урла
			 */
			'$route.fullPath'()
			{
				this.activeTable();
			},
		},
	};
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
	.index__icon-wrapper { margin-right: 12px; cursor: pointer; }
	.index__icon-emoji { font-size: 20px; line-height: 26px; }
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
		margin-right: 5px;
		position: relative;
	}
	.index__menu-item-title
	{
		color: rgba(25, 28, 33, 0.7);
		font-size: 12px;
		padding: 5px 8px;
		cursor: pointer;
		border-radius: 2px;
		&.active, &:hover
		{
			background-color: rgba(103, 115, 135, 0.1);
		}
		&--modified
		{
			color: #2F80ED;
			background-color: rgba(#2F80ED, 0.1);
		}
	}
	.index__add-btn svg
	{
		position: relative;
		top:1px;
		margin-right:2px;
	}
	.index__menu-more
	{
		position: relative;
		.index__menu-item-title { padding: 2px 8px 8px; }
	}
	.index__menu-more-list
	{
		position: absolute;
		top: calc(100% + 5px);
		right: 0;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		z-index: 2;
		border-radius: 2px;
		background-color: #fff;
		width:auto;
		min-width: 120px;
		transform: translate(calc(50% - 11px));
	}
	.index__menu-more-list-item
	{
		padding-top: 8px;
		padding-bottom: 8px;
		padding-left: 11px;
		font-size: 10px;
		color: rgba(25, 28, 33, 0.7);
		display: block;
		cursor: pointer;
		&:hover { background-color: rgba(103, 115, 135, 0.1); }
		&--mobile { display: none; }
		&--modified { color: #2F80ED; background-color: rgba(#2F80ED, 0.1); }
	}
	.index__head-burger { margin-right: 20px; }
	@media (max-width: 768px)
	{
		.index__wrapper { min-width: 375px; overflow: scroll; }
		.index__head { padding-right: 14px; align-items: center; }
		.index__overide-name { font-size: 18px; white-space: nowrap; }
		.index__add-btn-text { display: none; }
		.index__menu-item
		{
			.index__menu-item-title { display: none; }
			&.active, &:hover { background-color: transparent; }
		}
		.index__menu-more-list-item--mobile { display: block; }
	}
</style>
