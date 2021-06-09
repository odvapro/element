<template>
	<div @click=openSearch class="search-block">
		<div class="search-block__icon">
			<svg width="12" height="12"><use xlink:href="#search-icon"></use></svg>
		</div>
		<div v-if=opened class="search-block__search">
			<input
				@blur=closeSearch
				ref="searchInput"
				type="text"
				v-model=searchValue
				class="search-block__input"
				:placeholder="$t('search')+'...'"
			>
		</div>
		<div v-else class="search-block__text">{{ searchValue || $t('search') }}</div>
	</div>
</template>

<script>
	export default
	{
		data()
		{
			return {
				searchValue: '',
				opened: false,
				updateTimeout: 0,
			};
		},
		computed:
		{
			table()
			{
				return this.$store.getters.getTable(this.$route.params.tableCode);
			},
			tview()
			{
				let tviewId = this.$route.params.tview;
				for (let tview of this.table.tviews)
					if(tview.id == tviewId)
						return tview;
			},
		},
		watch:
		{
			searchValue(value)
			{
				this.setSearchValue(value);
			},
		},
		methods:
		{
			toggleSearch()
			{
				if (this.opened) {
					this.closeSearch();
				} else {
					this.openSearch();
				}
			},
			openSearch()
			{
				if (this.opened) return;
				this.opened = true;
				setTimeout(() => {
					this.$refs.searchInput.focus();
				}, 100);
			},
			closeSearch()
			{
				if (!this.opened) return;
				this.opened = false;
				this.$refs.searchInput.blur();
			},
			async updateSearchValue(value)
			{
				let requestParams = {select : {}, };
				if (this.tview.filter)
					requestParams.select.where = this.tview.filter;

				if (this.tview.sort)
					requestParams.select.order = this.tview.sort;

				requestParams.select.from   = this.$route.params.tableCode || this.tview.table;
				requestParams.select.page   = this.$route.params.page      || 1;
				requestParams.select.tview  = this.$route.params.tview     || this.tview.id;

				if(this.$route.params.limit)
					requestParams.select.limit = this.$route.params.limit

				requestParams.select.search = value;
				await this.$store.dispatch('search', requestParams);

				this.$store.commit('showLoader',false);

			},
			setSearchValue(value)
			{
				clearTimeout(this.updateTimeout);

				this.updateTimeout = setTimeout(() => {
					this.updateSearchValue(value);
				}, 500);
			},
		},
	};
</script>

<style lang="scss">
	.search-block
	{
		display: flex;
		align-items: center;
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
	.search-block__icon
	{
		font-size: 0;
		line-height: 0;
		width: 10px;
		height: 10px;
		margin-right: 5px;
		svg
		{
			width: 100%;
			height: 100%;
		}
	}
	.search-block__text
	{
		text-overflow: ellipsis;
		overflow: hidden;
		max-width: 70px;
		white-space: nowrap;
	}
	.search-block__search
	{
		overflow: hidden;
	}
	.search-block__input
	{
		border: 0;
		background-color: transparent;
		color: inherit;
		width: 150px;
	}
	@media (max-width: 768px)
	{
		.search-block__input { width: 60px; }
		.search-block__text { max-width: 60px; width: 60px; }
	}
</style>
